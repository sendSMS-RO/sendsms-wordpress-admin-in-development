<?php
$title     = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'sendsms-dashboard' );
$gdpr_link = ! empty( $instance['gdpr_link'] ) ? $instance['gdpr_link'] : '';
?>
<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'sendsms-dashboard' ); ?></label>
	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
</p>
<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'gdpr_link' ) ); ?>"><?php esc_attr_e( 'GDPR Link:', 'sendsms-dashboard' ); ?></label>
	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'gdpr_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'gdpr_link' ) ); ?>" type="url" value="<?php echo esc_url( $gdpr_link ); ?>">
</p>
