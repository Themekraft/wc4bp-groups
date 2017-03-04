<div data-taxonomy="" class="woocommerce_attribute wc-metabox closed " rel="0">
	<h3>
		<a href="#" class="remove_row delete">Remove</a>
		<div class="handlediv" title="Click to toggle"></div>
		<strong class="attribute_name">GROUP NAME 1</strong>
	</h3>
	<div class="woocommerce_attribute_data wc-metabox-content">
		<div class="wc4bp_data_inner_content">
			<?php
			woocommerce_wp_select(
				array(
					'id'      => '_membership_level',
					'label'   => _wc4bp_groups( "Membership level:" ),
					'options' => array(
						'1' => _wc4bp_groups( "Moderator" ),
						'2' => _wc4bp_groups( "Admin" ),
						'0' => _wc4bp_groups( "Normal" ),
					)
				)
			);
			
			woocommerce_wp_select(
				array(
					'id'      => '_membership_optional',
					'label'   => _wc4bp_groups( "Is optional:" ),
					'options' => array(
						'1' => _wc4bp_groups( "Yes" ),
						'0' => _wc4bp_groups( "No" ),
					)
				)
			);
			?>
		</div>
	</div>
</div>