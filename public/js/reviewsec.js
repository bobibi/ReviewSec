$('document').ready(function(){
	function displayError(msg) {
		$("#query-form").after('<section id="error"><p>'+msg+'</p></section>');
	}
	function clearError() {
		$("#error").html('');
	}
	$('#query-form > form').submit(function(event){
		// TODO: extract product id and site name from url
			// http://www.amazon.com/gp/product/B0075IRTVI/
		clearError();
		var productLink = $('#query-form > form input[name="ProductLink"]').val().trim();
		if(productLink=="") {
			event.preventDefault();
			return false;
		}
			
		var linkReg = /(\.|^)([a-z A-Z 0-9]+)(.com\/.+\/)([A-Z 0-9]{10})([^a-zA-Z0-9])/;
		var urlSplit = linkReg.exec(productLink);
			
		if(urlSplit) { // input is a valid link
			$('#query-form > form input[name="Site"]').val(urlSplit[2]);
			$('#query-form > form input[name="ProductID"]').val(urlSplit[4]);
			return true;
		} else { // input is an ASIN
			var asinReg = new RegExp("^[A-Z 0-9 a-z]{10}$");
			urlSplit = asinReg.exec(productLink);
			if(urlSplit) {
				$('#query-form > form input[name="Site"]').val('amazon');
				$('#query-form > form input[name="ProductID"]').val(urlSplit[0]);				
				return true;
			}
			displayError("invalid product link");
			event.preventDefault();
			return false;
		}
	});
	
	var focus = true;
	$('#query-form > form input[type="text"]').on("click", function(){
		if(focus) $(this).select();
		focus = false;
	});
	
	$('#query-form > form input[type="text"]').on("focusout", function(){
		focus = true;
	});
	
	$('nav a').on("click", function(){		
		$('nav li').removeClass('selected');
		$(this).parents("li").addClass('selected');		
	});
	
	$("#static-content").on("load", function(){
		$(this).height($(this).contents().find("html").height());
		window.location.href = window.location.href.replace(/#.*$/, "") + '#content';
	});
	
	$("#how-to").on("click", function(e){
		e.preventDefault();
		$("#how-to-detail").toggle();
		return false;
	});
});

