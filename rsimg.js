(function ($) {
 
	$.fn.rsimage = function (options) {
		// This is the easiest way to have default options.
		var self;
		var settings = $.extend({
			// These are the defaults.
			sWIDTH : screen.width,
			sHEIGHT : screen.height,
			quality : 100,
			crop_type : 'crop',
			siteURL : '',
			
		}, options);
		
		 get_width = function(){
			if(self.attr('width') !== undefined){ return self.attr('width'); }
			else {return settings.sWIDTH;	}
		 };
		
		 get_height = function(){
			 console.log(self.attr('height'));
			if(self.attr('height') !== undefined){ return self.attr('height'); }
			else {return settings.sHEIGHT;	}
		 };
		
		 get_crop_type = function(){
		    if(self.attr('data-croptype')){ return self.attr('data-croptype'); }
			else {return settings.crop_type;	}
		 }
		 
		 get_quality = function(){
		    if(self.attr('data-quality')){ return self.attr('data-quality'); }
			else {return settings.quality;	}
		 }
		 this.each(function(){
			  self = $(this); 
			  var w = get_width();
			  var h = get_height();
			  var c = get_crop_type();
			  var q = get_quality();
			  var s = $(this).attr('data-src');
			 $(this).attr('src', settings.siteURL + '?w='+ w + '&h=' + h + '&c='+ c + '&q=' +q+  '&img=' +s );
		 }); 
	};
}(jQuery));
 
