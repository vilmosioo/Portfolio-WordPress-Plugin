'use strict';

var PORTFOLIO_SLIDER = (function (slider, $) {

	slider.init = function(){
		if($.flexslider && PORTFOLIO_SLIDER_DATA){
			$('.' + PORTFOLIO_SLIDER_DATA.sliderClass).each(function(index, el){
				$(el).flexslider(PORTFOLIO_SLIDER_DATA.options);
			});
		}
	};

	return slider;
}(PORTFOLIO_SLIDER || {}, jQuery));

jQuery(document).ready(PORTFOLIO_SLIDER.init);