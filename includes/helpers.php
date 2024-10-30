<?php
defined('CHI_AR_VERSION') or die;
if (!function_exists('chiar_getCurrentPage')) {
    function chiar_getCurrentPage()
    {
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='') ? 'https://' : 'http://';
        $page_url = $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        return explode('?',$page_url)[0];
    }
}

if (!function_exists('chiar_getRequestArgs')) {
    function chiar_getRequestArgs($token)
    {
        global $wp_version;
        $args = array(
            'timeout' => 5,
            'redirection' => 5,
            'httpversion' => '1.0',
            'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url(),
            'blocking' => true,
            'headers' => array(
                'accept-encoding' => 'gzip, deflate',
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $token,
            ),
            'cookies' => array(),
            'body' => null,
            'compress' => false,
            'decompress' => true,
            'sslverify' => true,
            'stream' => false,
            'filename' => null
        );
        return $args;
    }
}

function chiar_in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && chiar_in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}