<?php
/*
Plugin Name: CHI.AR for products
Plugin URI: https://chi.digital/
Description: Plugin that provides AR models using CHI.Model service on products page created by WooCommerce.
Author: Mykhailo Tkach
Version: 1.0
Author URI: https://facebook.com/mnwko
License: GPL3

CHI.AR is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

CHI.AR is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with CHI.AR. If not, see https://www.gnu.org/licenses/gpl.txt.
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define('CHI_AR_VERSION','3.2.0');
define('CHI_AR_URL',plugin_dir_url(__FILE__));
define('CHI_AR_DIR',plugin_dir_path(__FILE__));

function chiar_activate(){
    if ( !class_exists( 'WooCommerce' ) ) {
        die('You should activate WooCommerce firstly');
    };
}
register_activation_hook(__FILE__,'chiar_activate');

include_once(CHI_AR_DIR.'/Chiar.php');
add_action( 'init', array( 'Chiar', 'getInstance' ) );


include(CHI_AR_DIR.'/includes/product-meta.php');
include(CHI_AR_DIR.'/includes/variation-meta.php');
include(CHI_AR_DIR.'/includes/main-menu-page.php');
include(CHI_AR_DIR.'/includes/models-menu-page.php');
include(CHI_AR_DIR.'/includes/helpers.php');
include(CHI_AR_DIR.'/includes/ajax.php');
