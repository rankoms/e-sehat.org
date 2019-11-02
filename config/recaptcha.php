<?php

// To use reCAPTCHA, you need to sign up for an API key pair for your site.
// link: http://www.google.com/recaptcha/admin
// $config['recaptcha_site_key'] = '6LeYYGgUAAAAAI_mHZw2NvePSeY_t0zja8g8rMiM';
// $config['recaptcha_secret_key'] = '6LeYYGgUAAAAACYv01yIPBgvM1yt2gHBCTTFvFeL';

global $SConfig;
$config['recaptcha_site_key'] = $SConfig->_site_key;
$config['recaptcha_secret_key'] = $SConfig->_secret_key;

// reCAPTCHA supported 40+ languages listed here:
// https://developers.google.com/recaptcha/docs/language
$config['recaptcha_lang'] = 'in';

/* End of file recaptcha.php */
/* Location: ./application/config/recaptcha.php */
