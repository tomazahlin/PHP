$(window).load(function() {
	// executes when complete page is fully loaded, including all frames, objects and images
	
	var p = window.location.href;
	p = p.split("#"); // remove anchor links at the end
	p = p[0];
	
	// reconstruct anchor links to point to the same page, not to the base href defined in head
	$("a.anchor").each(function(){
		var l = $(this).attr('href');
		if (l.substr(0,1) == "#") {
			$(this).attr('href', p + l);
		}
	});
	
	// equalize height of home-pages divs to the maximum
	var max_height = 0;
	$("div.home_page_item").each(function(){
		if ($(this).height() > max_height) {
			max_height = $(this).height();
		}
	});
	$("div.home_page_item").each(function(){
		$(this).css("height", max_height)
	});
	
	// find images in the content and add lightbox plugin to them
	$("div.content-lightbox img.lightbox").each(function(){
		var src = $(this).attr('src');
		if(typeof title === 'undefined' || title === false) {
			title = '';
		}
		$(this).wrap('<a href="' + src + '" title="' + title + '" rel="lightbox[content]" class="smooth" />');
	});
	
	// replace whitespaces in menu subitems with non-breaking whitespace, so the items dont collapse
	$('#menu ul > li > ul > li > a ').each(function() {
    	$(this).html($(this).html().replace(/\s/g, '&nbsp;'));
    });
	
	// remove empty span elements from widgets
	$("div.widget span.label").each(function(){
		if($(this).html() == "&nbsp;") {
			$(this).remove();	
		}
	});
});

$(document).ready(function(){  // Product content tags
  
	$('div.product-content-tabs ul.tabs li').click(function(){  
		var tab_id = $(this).attr('data-tab');  
		  
		$('ul.tabs li').removeClass('current');  
		$('.tab-content').removeClass('current');  
		  
		$(this).addClass('current');  
		$("#"+tab_id).addClass('current');  
	})  
	
	$('select#product-attachments > option').live("click", function() { 
		var url = $(this).attr('href');
		if(url.length > 0) {
			$('a#download-link').attr('href', url);
			$('a#download-link').fadeIn(250);
		}
		else {
			$('a#download-link').attr('href', '').hide();
		}
	}) 
	
	$("#carousel").PikaChoose({carousel:true});
	
	$('#Price').trigger('focusout');
	
	if($("li#product-tab-3-gallery")){ // If product has images
		$('img#product-primary-picture-thumbnail').click(function(){
			$('img#product-primary-picture-gallery').trigger('click');
			$('li#product-tab-3-gallery').trigger('click');
		})
	}
})