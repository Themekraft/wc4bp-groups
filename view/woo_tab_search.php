<p class="form-field">
    <span class="expand-close">
        <a href="#" class="expand_all"><?php _e_wc4bp_groups( 'Expand' ); ?></a> / <a href="#" class="close_all"><?php _e_wc4bp_groups( 'Close' ); ?></a>
    </span>
    <label for="wc4bp-group-ids"><?php _e_wc4bp_groups( 'Select groups to add' ); ?></label>
    <input type="hidden" class="wc4bp-group-search" style="width: 50%;" id="wc4bp-group-ids" name="wc4bp-group-ids"
           data-placeholder="<?php esc_attr_e_wc4bp_groups( 'Search for a group' ); ?>" data-action="wc4bp_group_search"
           data-multiple="true" data-exclude="<?php echo intval( $post->ID ); ?>"
           data-selected="<?php
	       $group_ids = array_filter( array_map( 'absint', (array) get_post_meta( $post->ID, '_wc4bp-group-ids', true ) ) );
	       $json_ids  = array();
	
	       foreach ( $group_ids as $group_id ) {
		       $product = wc_get_product( $group_id );
		       if ( is_object( $product ) ) {
			       $json_ids[ $group_id ] = wp_kses_post( html_entity_decode( $product->get_formatted_name(), ENT_QUOTES, get_bloginfo( 'charset' ) ) );
		       }
	       }
	
	       echo esc_attr( json_encode( $json_ids ) );
	       ?>" value="<?php echo implode( ',', array_keys( $json_ids ) ); ?>"/>
    <button type="button" class="button wc4bp add_groups"><?php _e_wc4bp_groups( 'Add Group' ); ?></button>
    <span class="wc4bp-group-loading"></span>
</p>