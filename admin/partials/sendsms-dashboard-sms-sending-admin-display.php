<div class="wrap">
	<div class="sendsms-container">
		<img class="sendsms-image-center" src=<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'img' . DIRECTORY_SEPARATOR . 'logo-test-area.png'; ?>>
		<h1 class="sendsms-text-center"><?php echo __( 'Send mass SMS', 'sendsms-dashboard' ); ?></h1>
		<?php wp_nonce_field( 'sendsms-security-nonce' ); ?>
		<div class="sendsms-container-grid-mass">
			<div class="sendsms-item-input-1">
				<span><?php echo __( 'Send to', 'sendsms-dashboard' ); ?></span>
				<div class="tooltip">
					<i class="fas fa-question-circle"></i>
					<span class="tooltiptext">
						<?php echo __( 'Chose the category you want to send to', 'sendsms-dashboard' ); ?>
					</span>
				</div>
			</div>
			<select id="receiver_type" class="sendsms-item-input-1">
				<option value="subscribers">Subscribers</option>
				<option value="users">Site users</option>
			</select>
			<div id="role_text"class="sendsms-item-input-1 sendsms-hidden">
				<span><?php echo __( 'Roles', 'sendsms-dashboard' ); ?></span>
				<div class="tooltip">
					<i class="fas fa-question-circle"></i>
					<span class="tooltiptext">
						<?php echo __( 'Chose the specific role you want to send the message to', 'sendsms-dashboard' ); ?>
					</span>
				</div>
			</div>
			<select id="role_selector" class="sendsms-item-input-1 sendsms-hidden">
				<option value="all">All</option>
				<?php foreach ( get_editable_roles() as $key => $value ) { ?>
					<option value="<?php echo esc_attr( $value['name'] ); ?>"><?php echo esc_html( $value['name'] ); ?></option>
				<?php } ?>
			</select>

			<div class="sendsms-item-input-1">
				<span id="forMessage"><?php echo __( 'Message', 'sendsms-dashboard' ); ?></span>
			</div>
			<textarea rows="4" id="message" class="sendsms-item-input-1 sendsms_dashboard_content" aria-label="Message" aria-describedby="forMessage" data-sendsms-counter="counterMessage"></textarea>
			<p id="counterMessage" class="sendsms-item-input-2"><?php echo __( 'The field is empty', 'wc_sendsms' ); ?></p>
			<div class="sendsms-item-input-1-3">
				<button id="button-send-mass-message" type="button" class="sendsms-button-center button button-primary"><?php echo __( 'Send Message', 'sendsms-dashboard' ); ?></button>
			</div>
		</div>
	</div>
</div>
</div>
