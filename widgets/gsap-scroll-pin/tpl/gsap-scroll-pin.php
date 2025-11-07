<div class="mascot-gsap-scroll-pin <?php if( !empty($classes) ) echo esc_attr(implode(' ', $classes)); ?>">
	<div class="scroll-pin-wrapper" data-gsap-scroll-pin='<?php echo esc_attr( json_encode( $animation_data ) ); ?>'>
		<?php
		$title_tag = isset($title_tag) ? $title_tag : 'h4';
		$title_text = isset($title_text) ? $title_text : '';

		printf(
			'<%1$s class="scroll-pin-title">',
			esc_attr($title_tag)
		);

		if ( !empty($title_text) ) {
			printf(
				'%1$s',
				esc_html($title_text)
			);
		}
		?>


		<?php
		foreach (  $title_list as $item ) {
			$title_part_classes = array();
			$title_part_classes[] = 'elementor-repeater-item-'.$item['_id'];
			?>
			<span class="<?php echo esc_attr(implode(' ', $title_part_classes)); ?>"><?php echo esc_html( $item['title_other_text'] );?></span>
		<?php } ?>
		<?php
			printf(
				'</%1$s>',
				esc_attr($title_tag)
			);
		?>
	</div>
</div>

