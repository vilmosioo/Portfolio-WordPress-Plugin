<?php
/*
* Custom Post
* 
* Creates a custom post for a WordPress theme. You can overwrite any default arguments that you wish.
*
* Example usage: VI_Portfolio_Custom_Post::create(array('name' => 'Portfolio'))
* 
* Required: VI_Portfolio_Utils.php
*/

require_once 'VI_Portfolio_Utils.php';

class VI_Portfolio_Custom_Post{
	
	static public function create($args = array('name' => 'Portfolio')){
		return new VI_Portfolio_Custom_Post($args);
	}

	protected $name, $supports, $slug;

	protected function __construct($args) {
		$args['labels'] = array_merge(
			array(
				'name' => $args['name'],
				'singular_name' => $args['name'],
				'add_new' => 'Add New',
				'add_new_item' => 'Add New '. $args['name'],
				'edit_item' => 'Edit '. $args['name'],
				'new_item' => 'New '. $args['name'],
				'all_items' => 'All '. VI_Portfolio_Utils::pluralize($args['name']),
				'view_item' => 'View '. $args['name'],
				'search_items' => 'Search '. $args['name'],
				'not_found' =>  'No '. VI_Portfolio_Utils::pluralize($args['name']).' found',
				'not_found_in_trash' => 'No '. VI_Portfolio_Utils::pluralize($args['name']).' found in Trash', 
				'parent_item_colon' => '',
				'menu_name' => VI_Portfolio_Utils::pluralize($args['name'])
			),
			isset($args['labels']) ? $args['labels'] : array()
		);
		$args['supports'] = array_merge(
			array('title', 'editor', 'author', 'thumbnail', 'custom-fields'), 
			isset($args['supports']) ? $args['supports'] : array()
		);

		$args = array_merge( array(
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'rewrite' => array( 'slug' => VI_Portfolio_Utils::generate_slug($args['name']) ),
			'capability_type' => 'post',
			'has_archive' => VI_Portfolio_Utils::generate_slug($args['name']), 
			'hierarchical' => false,
			'menu_position' => null
		), $args ); 

		$this->name = $args['name'];
		$this->supports = $args['supports'];
		$this->slug = VI_Portfolio_Utils::generate_slug($args['name']);
		
		register_post_type( $this->slug, $args );
		
		add_action( 'right_now_content_table_end' , array(&$this, 'add_to_dashboard') );
		
		// WP 3.8 hack
		add_filter('dashboard_glance_items', array(&$this, 'add_to_dashboard_hack'));
	}

	public function add_to_dashboard_hack($elements) {
		$post_type = get_post_type_object($this->slug);
		$num_posts = wp_count_posts( $post_type->name );
		$num = number_format_i18n( $num_posts->publish );
		if(intval($num) == 1){
			$text = _n( $post_type->labels->singular_name, $post_type->labels->name , intval( $num_posts->publish ) );
		} else {
			$text = _n( VI_Portfolio_Utils::pluralize($post_type->labels->singular_name), VI_Portfolio_Utils::pluralize($post_type->labels->name) , intval( $num_posts->publish ) );
		}

		if ( current_user_can( 'edit_posts' ) ) {
			$text = "<a href='edit.php?post_type=$post_type->name'>$num $text</a>";
			echo '<li class="post-count">' . $text . '</li>';
		}

	  return $elements;
	}

	public function add_to_dashboard(){
		$post_type = get_post_type_object($this->slug);
		$num_posts = wp_count_posts( $post_type->name );
		$num = number_format_i18n( $num_posts->publish );
		$text = _n( VI_Portfolio_Utils::pluralize($post_type->labels->singular_name), VI_Portfolio_Utils::pluralize($post_type->labels->name) , intval( $num_posts->publish ) );
		if ( current_user_can( 'edit_posts' ) ) {
			$num = "<a href='edit.php?post_type=$post_type->name'>$num</a>";
			$text = "<a href='edit.php?post_type=$post_type->name'>$text</a>";
		}
		echo '<tr><td class="first b b-' . $post_type->name . '">' . $num . '</td>';
		echo '<td class="t ' . $post_type->name . '">' . $text . '</td></tr>';
	}
}
?>
