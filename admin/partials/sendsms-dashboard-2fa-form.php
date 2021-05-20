<?php

/**
 * This function generates the html responsable for the 2fa login form
 * 
 * @since 1.0.0
 */
function printHTML2fa($user, $nonce, $regirect)
{
    $interim_login = isset($_REQUEST['interim-login']);
    $rememberme = false;
    if (!empty($_REQUEST['rememberme'])) {
        $rememberme = true;
    }
?>
<?php
}
