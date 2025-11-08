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
		<?php foreach ( $surrounding_images as $index => $item ) : ?>
			<?php
			$image = isset( $item['image'] ) ? $item['image'] : [];
			if ( empty( $image['url'] ) ) {
				continue;
			}

			$style_rules = [];

			$horizontal_offset = isset( $item['horizontal_offset'] ) ? $item['horizontal_offset'] : [];
			$horizontal_size   = isset( $horizontal_offset['size'] ) ? $horizontal_offset['size'] : null;
			$horizontal_unit   = isset( $horizontal_offset['unit'] ) ? $horizontal_offset['unit'] : 'px';

			if ( '' !== $horizontal_size && null !== $horizontal_size ) {
				$style_rules[] = sprintf(
					'%1$s: %2$s%3$s',
					esc_attr( $item['horizontal_anchor'] ),
					$horizontal_size,
					$horizontal_unit
				);
			}

			$vertical_offset = isset( $item['vertical_offset'] ) ? $item['vertical_offset'] : [];
			$vertical_size   = isset( $vertical_offset['size'] ) ? $vertical_offset['size'] : null;
			$vertical_unit   = isset( $vertical_offset['unit'] ) ? $vertical_offset['unit'] : 'px';

			if ( '' !== $vertical_size && null !== $vertical_size ) {
				$style_rules[] = sprintf(
					'%1$s: %2$s%3$s',
					esc_attr( $item['vertical_anchor'] ),
					$vertical_size,
					$vertical_unit
				);
			}

			$image_width = isset( $item['image_width'] ) ? $item['image_width'] : [];
			$width_size  = isset( $image_width['size'] ) ? $image_width['size'] : null;
			$width_unit  = isset( $image_width['unit'] ) ? $image_width['unit'] : 'px';

			if ( '' !== $width_size && null !== $width_size ) {
				$style_rules[] = sprintf(
					'width: %1$s%2$s',
					$width_size,
					$width_unit
				);
			}

			$image_height = isset( $item['image_height'] ) ? $item['image_height'] : [];
			$height_size  = isset( $image_height['size'] ) ? $image_height['size'] : null;
			$height_unit  = isset( $image_height['unit'] ) ? $image_height['unit'] : 'px';

			if ( '' !== $height_size && null !== $height_size ) {
				$style_rules[] = sprintf(
					'height: %1$s%2$s',
					$height_size,
					$height_unit
				);
			}

			$item_classes = [
				'gallery-thumb-inner',
				'gallery-thumb-inner-' . ( $index + 1 ),
			];

			if ( ! empty( $item['desktop_only'] ) ) {
				$item_classes[] = 'gallery-thumb-inner--desktop';
			}
			?>
			<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $item_classes ) ) ); ?>"<?php echo $style_rules ? ' style="' . esc_attr( implode( '; ', $style_rules ) ) . '"' : ''; ?>>
				<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
			</div>
		<?php endforeach; ?>
		<?php unset( $item ); ?>

		<?php if ( ! empty( $main_image['url'] ) ) : ?>
			<div class="gallery-thumb">
				<img src="<?php echo esc_url( $main_image['url'] ); ?>" alt="<?php echo esc_attr( $main_image['alt'] ); ?>">
			</div>
		<?php endif; ?>
	</div>
</div>
