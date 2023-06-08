// Botón Back to Top 
jQuery(document).ready(function($){
	$(window).scroll(function () {
		   if ($(this).scrollTop() > 550) {
			   $('#back-to-top').fadeIn();
		   } else {
			   $('#back-to-top').fadeOut();
		   }
	   });
	   // scroll body to 0px on click
	   $('#back-to-top').click(function () {
		   //$('#back-to-top').tooltip('hide');
		   $('body,html').stop().animate({
			   scrollTop: 0
		   }, 1100);
		   return false;
	   });
	   //$('#back-to-top').tooltip('show');

	jQuery(window).scroll(function(){
        var scrollTop = jQuery(document).scrollTop();
        var anchors = jQuery('body').find('.ez-toc-section');

        offset_1 = 150;
        offest_2 = 1;

        for (var i = 0; i < anchors.length; i++){

            if (scrollTop > jQuery(anchors[i]).offset().top - offset_1 && scrollTop < jQuery(anchors[i]).offset().top + jQuery(anchors[i]).height() - offest_2) {
                jQuery('nav ul li a').removeClass('active');
                jQuery('nav ul li a[href="#' + jQuery(anchors[i]).attr('id') + '"]').addClass('active');
            }

        }
    });

	var subindices = document.getElementsByClassName("ez-toc-heading-level-3");
	console.log(subindices.length);
	for (var i = 0; i < subindices.length; i++) {
	var enlace = subindices[i].getElementsByTagName("a")[0];
	var contenido = enlace.innerHTML;
	contenido = contenido.replace(/^\d+\)\s*/, "");
	enlace.innerHTML = contenido;
}

});

