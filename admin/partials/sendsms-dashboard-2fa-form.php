<?php

/**
 * This function generates the html responsible for the 2fa login form
 * 
 * @since 1.0.0
 */
function sendsms_dashboard_printHTML2fa($user, $nonce, $redirect, $class, $error = '', $regenCode = true)
{
    $interim_login = isset($_REQUEST['interim-login']);
    $rememberme = false;
    if (!empty($_REQUEST['rememberme'])) {
        $rememberme = true;
    }
    $rememberme = intval($rememberme);

    if (!function_exists('login_header')) {
        // We really should migrate login_header() out of `wp-login.php` so it can be called from an includes file.
        include_once TWO_FACTOR_DIR . 'includes/function.login-header.php';
    }

    login_header();

    if (!empty($error)) {
        echo '<div id="login_error"><strong>' . esc_html($error) . '</strong><br /></div>';
    }
    ?>

    <form name="sendsms_validate_form" id="loginform" action="<?php echo esc_url(sendsms_dashboard_login_url(array('action' => 'sendsms_validate'), 'login_post')); ?>" method="post" autocomplete="off">
        <input type="hidden" name="wp-auth-id" id="wp-auth-id" value="<?php echo esc_attr($user->ID); ?>" />
        <input type="hidden" name="wp-auth-nonce" id="wp-auth-nonce" value="<?php echo esc_attr($nonce); ?>" />
        <?php if ($interim_login) { ?>
            <input type="hidden" name="interim-login" value="1" />
        <?php } else { ?>
            <input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect); ?>" />
        <?php } ?>
        <input type="hidden" name="rememberme" id="rememberme" value="<?php echo esc_attr($rememberme); ?>" />
        <?php
        include_once ABSPATH . '/wp-admin/includes/template.php';
        ?>
        <p><?php esc_html_e('Enter the code received via SMS.', 'sendsms-dashboard'); ?></p><br />
        <p>
            <label for="authcode"><?php esc_html_e('Verification Code:', 'sendsms-dashboard'); ?></label>
            <input type="tel" name="code" id="authcode" class="input" value="" />
        </p>
        <?php
        submit_button(__('Submit', 'sendsms-dashboard'));
        ?>
    </form>
    <?php
    if ($regenCode) {
        $class->generate_auth_code($user);
    }
    do_action('login_footer');
}

function sendsms_dashboard_login_url($params = array(), $scheme = 'login')
{
    if (!is_array($params)) {
        $params = array();
    }

    $params = urlencode_deep($params);

    return add_query_arg($params, site_url('wp-login.php', $scheme));
}
