<div class="wrap">
    <div class="sendsms-container">
        <img class="sendsms-image-center" src=<?php echo plugin_dir_url(dirname(__FILE__)) . 'img' . DIRECTORY_SEPARATOR . 'logo-test-area.png'; ?>>
        <h1 class="sendsms-text-center"><?= __('Send a test SMS', 'sendsms-dashboard') ?></h1>

        <div class="sendsms-containter-grid-test">
            <div class="sendsms-item-input-1">
                <span id="forPhoneNumber" data-bs-toggle="tooltip" data-placement="top"><?= __('Phone number', 'sendsms-dashboard') ?></span>
                <i class="fas fa-question-circle tooltip" title="<?= __('We recommend a phone number in E.164 Format but without the + sign', 'sendsms-dashboard') ?>"></i>
            </div>
            <input id="phone_number" type="tel" class="sendsms-item-input-1" placeholder="40727363767" aria-label="Phone number" aria-describedby="forPhoneNumber">
            <div class="sendsms-item-input-1">
                <span id="forFrom" data-bs-toggle="tooltip" data-placement="top" title="<?= __('Your SendSMS Label', 'sendsms-dashboard') ?>"><?= __('From', 'sendsms-dashboard') ?></span>
                <i class="fas fa-question-circle"></i>
            </div>
            <input id="label" type="text" class="sendsms-item-input-1" value="1898" aria-label="Label" aria-describedby="forLabel">

            <div class="sendsms-item-input-1">
                <span id="forGdpr" data-bs-toggle="tooltip" data-placement="top" title="<?= __('You must specify {gdpr} key message. {gdpr} key will be replaced automaticaly with confirmation unique confirmation link. If {gdpr} key is not specified confirmation link will be placed at the end of message.', 'sendsms-dashboard') ?>"><?= __('Unsubscribe link?', 'sendsms-dashboard') ?></span>
                <i class="fas fa-question-circle"></i>
            </div>
            <input id="gdpr" class="sendsms-item-input-1" type="checkbox" value="gdrp" aria-label="Unsubscribe Link" aria-describedby="forGdpr">
            <div class="sendsms-item-input-1">
                <span id="forShort" data-bs-toggle="tooltip" data-placement="top" title="<?= __('Searches long url and replaces them with coresponding sort url', 'sendsms-dashboard') ?>"><?= __('Shrink urls?', 'sendsms-dashboard') ?></span>
                <i class="fas fa-question-circle"></i>
            </div>
            <input id="short" class="sendsms-item-input-1" type="checkbox" value="short" aria-label="Shrink urls?" aria-describedby="forShort">

            <div class="sendsms-item-input-1">
                <span id="forMessage" data-bs-toggle="tooltip" data-placement="top" title="<?= __('The message you want to send', 'sendsms-dashboard') ?>"><?= __('Message', 'sendsms-dashboard') ?></span>
                <i class="fas fa-question-circle"></i>
            </div>
            <textarea rows="4" id="message" class="sendsms-item-input-1 sendsms_dashboard_content" aria-label="Message" aria-describedby="forMessage" data-sendsms-counter="counterMessage"></textarea>
            <p id="counterMessage" class="sendsms-item-input-2"><?= __("The field is empty", 'wc_sendsms') ?></p>
            <div class="sendsms-item-input-1-3">
                <button id="button-send-a-test-message" type="button" class="sendsms-button-center button button-primary"><?= __('Send Message', 'sendsms-dashboard') ?></button>
            </div>
        </div>
    </div>
</div>
</div>