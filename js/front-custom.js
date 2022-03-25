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

});

