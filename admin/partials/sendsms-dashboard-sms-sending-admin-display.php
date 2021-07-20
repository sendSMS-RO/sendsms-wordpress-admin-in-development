<div class="wrap">
    <div class="sendsms-container">
        <img class="sendsms-image-center" src=<?php echo plugin_dir_url(dirname(__FILE__)) . 'img' . DIRECTORY_SEPARATOR . 'logo-test-area.png'; ?>>
        <h1 class="sendsms-text-center"><?php echo __('Send a test SMS', 'sendsms-dashboard') ?></h1>
        <?php wp_nonce_field('sendsms-security-nonce'); ?>
        <div class="sendsms-container-grid-test">
            <div class="sendsms-item-input-1">
                <span id="forPhoneNumber"><?php echo __('Phone number', 'sendsms-dashboard') ?></span>
                <div class="tooltip">
                    <i class="fas fa-question-circle"></i>
                    <span class="tooltiptext">
                        <?php echo __('We recommend a phone number in E.164 Format but without the + sign', 'sendsms-dashboard') ?>
                    </span>
                </div>
            </div>
            <input id="phone_number" type="tel" class="sendsms-item-input-1" placeholder="40727363767" aria-label="Phone number" aria-describedby="forPhoneNumber">
            <div class="sendsms-item-input-1">
                <span id="forGdpr"><?php echo __('Add unsubscribe link?', 'sendsms-dashboard') ?></span>
                <div class="tooltip">
                    <i class="fas fa-question-circle"></i>
                    <span class="tooltiptext">
                        <?php echo __('You must specify {gdpr} key message. {gdpr} key will be replaced automatically with confirmation unique confirmation link. If {gdpr} key is not specified confirmation link will be placed at the end of message.', 'sendsms-dashboard') ?>
                    </span>
                </div>
            </div>
            <input id="gdpr" class="sendsms-item-input-1" type="checkbox" value="gdpr" aria-label="Unsubscribe Link" aria-describedby="forGdpr">
            <div class="sendsms-item-input-1">
                <span id="forShort"><?php echo __('Shrink urls?', 'sendsms-dashboard') ?></span>
                <div class="tooltip">
                    <i class="fas fa-question-circle"></i>
                    <span class="tooltiptext">
                        <?php echo __('Searches long url and replaces them with corresponding short url. Please use only urls that start with https:// or http://', 'sendsms-dashboard') ?>
                    </span>
                </div>
            </div>
            <input id="short" class="sendsms-item-input-1" type="checkbox" value="short" aria-label="Shrink urls?" aria-describedby="forShort">

            <div class="sendsms-item-input-1">
                <span id="forMessage"><?php echo __('Message', 'sendsms-dashboard') ?></span>
            </div>
            <textarea rows="4" id="message" class="sendsms-item-input-1 sendsms_dashboard_content" aria-label="Message" aria-describedby="forMessage" data-sendsms-counter="counterMessage"></textarea>
            <p id="counterMessage" class="sendsms-item-input-2"><?php echo __("The field is empty", 'wc_sendsms') ?></p>
            <div class="sendsms-item-input-1-3">
                <button id="button-send-a-test-message" type="button" class="sendsms-button-center button button-primary"><?php echo __('Send Message', 'sendsms-dashboard') ?></button>
            </div>
        </div>
    </div>
</div>
</div>