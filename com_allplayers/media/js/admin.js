(function($){
	$(function(){
		$("#admin_tabs").tabs({
			beforeLoad:function(event, ui){
				//only show loader if content has not been loaded
				if ($(ui.panel).html().length == 0){
					$('#admin_tabs .loader').show();
				}
			},
			load:function(event, ui){
				$('#admin_tabs .loader').hide();
			}
		});
	});
})(jQuery);
