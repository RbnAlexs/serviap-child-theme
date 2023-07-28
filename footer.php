<?php
/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
	get_template_part( 'template-parts/footer' );
}
?>

<?php wp_footer(); ?>

<script src="//code.tidio.co/gyj5ocbc2unznxoayl8l8vozfkbmx9us.js" async></script>
<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Click to return on the top page" data-toggle="tooltip" data-placement="left">
		<span><i class="fa fa-angle-up"></i></span>
</a>

<?php $my_current_lang = apply_filters( 'wpml_current_language', NULL );
if ($my_current_lang == 'en'): ?>
    <script src="https://js.chilipiper.com/marketing.js" type="text/javascript"></script>
	<script>
	var cpTenantDomain = "serviapgroup";
	var cpRouterName = "inbound-router";
	var cpHubspotFormID = ["68ba3466-4abb-49d4-9700-e63e64f1ce12"];
	var lead = {};
	window.addEventListener("message", function (event) {
	if (event.data.type === "hsFormCallback") {
		if (event.data.eventName === "onFormSubmit") {
			for (var key in event.data.data) {
				if (Array.isArray(event.data.data[key].value)) {event.data.data[key].value = event.data.data[key].value.toString().replaceAll(",",";");}
				lead[event.data.data[key].name] = event.data.data[key].value;
			}
			if(Object.keys(lead).length <= 1){lead = event.data.data;}
		} else if (event.data.eventName === "onFormSubmitted") {
			ChiliPiper.submit(cpTenantDomain, cpRouterName, {map:true,lead:lead});
		}
	}
	});
	</script>
<?php endif; 
if ($my_current_lang == 'es'): ?>
    <script src="https://js.chilipiper.com/marketing.js" type="text/javascript"></script>
	<script>
	var cpTenantDomain = "serviapgroup";
	var cpRouterName = "inbound-router";
	var cpHubspotFormID = ["b684bcb7-2f56-49d1-9054-a6b5077d7672"];
	var lead = {};
	window.addEventListener("message", function (event) {
	if (event.data.type === "hsFormCallback") {
		if (event.data.eventName === "onFormSubmit") {
			for (var key in event.data.data) {
				if (Array.isArray(event.data.data[key].value)) {event.data.data[key].value = event.data.data[key].value.toString().replaceAll(",",";");}
				lead[event.data.data[key].name] = event.data.data[key].value;
			}
			if(Object.keys(lead).length <= 1){lead = event.data.data;}
		} else if (event.data.eventName === "onFormSubmitted") {
			ChiliPiper.submit(cpTenantDomain, cpRouterName, {map:true,lead:lead});
		}
	}
	});
	</script>
<?php endif; ?>

</body>
</html>
