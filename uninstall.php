<?php
/**
 * Uninstall Actions
 *
 * @package cecf
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}
/**
 * Require Configuration
 */
require_once 'ce-plugin-config.php';
$option_name = CE_Plugin_Config::get_options_var_name();

delete_option( $option_name );
