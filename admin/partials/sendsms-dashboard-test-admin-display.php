<?php
// require_once(plugin_dir_path(dirname(dirname(__FILE__))) . 'lib' . DIRECTORY_SEPARATOR . 'csrf.class.php');

// $csrf = new csrf();
?>
<div class="wrap">
    <div class="container mt-4">
        <div class="row">
            <div class="col d-flex justify-content-center">
                <img class="img-fluid" src=<?php echo plugin_dir_url(dirname(__FILE__)) . 'img' . DIRECTORY_SEPARATOR . 'logo-test-area.png'; ?>>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <h2><?=__('Send a test SMS', 'sendsms-dashboard')?></h2>
            </div>
        </div>
        <div class="row mt-5 d-flex justify-content-center">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="input-group mb-4">
                    <span class="input-group-text" id="forPhoneNumber" data-bs-toggle="tooltip" data-placement="top" title="<?=__('We recommend a phone number in E.164 Format but without the + sign', 'sendsms-dashboard')?>"><?=__('Phone number', 'sendsms-dashboard')?></span>
                    <input id="phone_number" type="tel" class="form-control" placeholder="40727363767" aria-label="Phone number" aria-describedby="forPhoneNumber">
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text" id="forFrom" data-bs-toggle="tooltip" data-placement="top" title="<?=__('Your SendSMS Label', 'sendsms-dashboard')?>"><?=__('From', 'sendsms-dashboard')?></span>
                    <input id="label" type="text" class="form-control" value="1898" aria-label="Label" aria-describedby="forLabel">
                </div>
                <div class="row">
                    <div class="col-md-6 d-flex justify-content-center">
                        <div class="mb-4">
                            <input id="gdpr" type="checkbox" value="gdrp" aria-label="Unsubscribe Link" aria-describedby="forGdpr">
                            <span id="forGdpr" data-bs-toggle="tooltip" data-placement="top" title="<?=__('You must specify {gdpr} key message. {gdpr} key will be replaced automaticaly with confirmation unique confirmation link. If {gdpr} key is not specified confirmation link will be placed at the end of message.', 'sendsms-dashboard')?>"><?=__('Unsubscribe link?', 'sendsms-dashboard')?></span>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-center">
                        <div class="mb-4">
                            <input id="short" type="checkbox" value="short" aria-label="Shrink urls?" aria-describedby="forShort">
                            <span id="forShort" data-bs-toggle="tooltip" data-placement="top" title="<?=__('Searches long url and replaces them with coresponding sort url', 'sendsms-dashboard')?>"><?=__('Shrink urls?', 'sendsms-dashboard')?></span>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <span class="input-group-text" id="forMessage" data-bs-toggle="tooltip" data-placement="top" title="<?=__('The message you want to send', 'sendsms-dashboard')?>"><?=__('Message', 'sendsms-dashboard')?></span>
                    <textarea rows="4" id="message" class="form-control sendsms_daschboard_content" aria-label="Message" aria-describedby="forMessage" data-sendsms-counter="counterMessage"></textarea>
                </div>
                <p id="counterMessage" class="text-end fs-6 mt-1"><?=__("The field is empty", 'wc_sendsms')?></p>
                <div class="d-flex justify-content-center mt-5">
                    <button id="button-send-a-test-message" type="button" class="btn btn-primary"><?=__('Send Message', 'sendsms-dashboard')?></button>
                </div>
            </div>
        </div>
    </div>
</div>