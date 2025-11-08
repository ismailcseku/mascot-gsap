<?php
/**
 * View template for the GSAP Image Gallery widget.
 *
 * @var array $classes
 * @var array $animation_data
 * @var array $main_image
 * @var array $surrounding_images
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$wrapper_classes = implode( ' ', array_map( 'sanitize_html_class', array_filter( $classes ) ) );

$data_attributes = '';
foreach ( $animation_data as $data_key => $data_value ) {
	$data_attributes .= sprintf(
		' data-%1$s="%2$s"',
		esc_attr( $data_key ),
		esc_attr( $data_value )
	);
}
?>

<div class="gallery-area text-center <?php echo esc_attr( $wrapper_classes ); ?>"<?php echo $data_attributes; ?>>
	<div class="gallery-thumb-wrap">
		<?php foreach ( $surrounding_images as $index => $image ) : ?>
			<?php if ( empty( $image['url'] ) ) : ?>
				<?php continue; ?>
			<?php endif; ?>
			<div class="gallery-thumb-inner gallery-thumb-inner-<?php echo esc_attr( $index ); ?>">
				<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
			</div>
		<?php endforeach; ?>

		<?php if ( ! empty( $main_image['url'] ) ) : ?>
			<div class="gallery-thumb">
				<img src="<?php echo esc_url( $main_image['url'] ); ?>" alt="<?php echo esc_attr( $main_image['alt'] ); ?>">
			</div>
		<?php endif; ?>
	</div>
</div>

