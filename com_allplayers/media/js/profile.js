(function($){
	$(function(){

		SqueezeBox.assign($$('a.edit-profile'), {
			//parse: 'rel',
			handler: 'iframe',
			size:{
				x: '1000',
				y: '700' //TODO: Make match window height
			}
		});
	});

})(jQuery);