<?php defined('BASEPATH') OR exit('No direct script access allowed');

$env = ENVIRONMENT;

if ($env === 'development') {
    $config['recaptcha_site_key']   = '6Ld39twrAAAAAPQLYKunVpacvJkrpSR3re7BKgqn'; 
    $config['recaptcha_secret_key'] = '6Ld39twrAAAAAFhs9D-9AOjAiNmkwXssFh3wO_eU'; 
} else {
    $config['recaptcha_site_key']   = '6Ld39twrAAAAAPQLYKunVpacvJkrpSR3re7BKgqn';
    $config['recaptcha_secret_key'] = '6Ld39twrAAAAAFhs9D-9AOjAiNmkwXssFh3wO_eU';
}
