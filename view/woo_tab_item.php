<div id="wc4bp_item_<?php echo esc_attr( $group->group_id ); ?>" data-taxonomy="" class="woocommerce_attribute wc-metabox closed wc4bp-group-item" rel="0" group_name="<?php echo esc_attr( $group->group_name ); ?>" group_id="<?php echo esc_attr( $group->group_id ); ?>">
    <h3>
        <a group_id="<?php echo esc_attr( $group->group_id ); ?>" href="#" class="remove_row delete wc4bp-group-group-remove"><?php wc4bp_groups_manager::esc_attr_e_wc4bp_groups('Remove') ?></a>
        <div class="handlediv" title="<?php wc4bp_groups_manager::esc_attr_e_wc4bp_groups('Click to toggle') ?>"></div>
        <strong class="attribute_name"><?php echo esc_html( $group->group_name ); ?></strong>
    </h3>
    <div class="woocommerce_attribute_data wc-metabox-content">
        <div class="wc4bp_data_inner_content">
			<?php
			$membership_level = array(
				'id'      => '_membership_level',
				'label'   => wc4bp_groups_manager::_wc4bp_groups( "Membership level:" ),
				'options' => array(
					'1' => wc4bp_groups_manager::_wc4bp_groups( "Moderator" ),
					'2' => wc4bp_groups_manager::_wc4bp_groups( "Admin" ),
					'0' => wc4bp_groups_manager::_wc4bp_groups( "Normal" ),
				)
			);
			
			if ( isset( $group->is_optional ) ) {
				$membership_level['value'] = $group->member_type;
			}
			
			woocommerce_wp_select( $membership_level );
			
			$is_optional = array(
				'id'      => '_membership_optional',
				'label'   => wc4bp_groups_manager::_wc4bp_groups( "Is optional:" ),
				'options' => array(
					'1' => wc4bp_groups_manager::_wc4bp_groups( "Yes" ),
					'0' => wc4bp_groups_manager::_wc4bp_groups( "No" ),
				)
			);
			if ( isset( $group->is_optional ) ) {
				$is_optional['value'] = $group->is_optional;
			}
			
			woocommerce_wp_select( $is_optional );
			?>
        </div>
    </div>
</div>