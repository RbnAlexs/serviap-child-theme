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
    THIS IS A TEXT STRING TO OUR ENGLISH VERSION. :)
<?php endif; ?>

</body>
</html>
