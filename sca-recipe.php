<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Plugin Name: Recipe Revolution
 * Easily Create and Share Delicious Recipes with the Newest WordPress Recipe Plugin
 * Version: 1.0.0
 * Requires at least: 4.6
 * Tested up to: 6.1.1
 * Requires PHP: 7.4
 * Stable tag: 1.0.1
 * Author: Scalater Team
 * License: GPLv2 or later
 * Network: false
 * Text Domain: sca-recipe
 * Domain Path: /languages
 */

namespace SCALATER\Recipes;

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . 'bootstrap.php';

use function SCALATER\Framework\init_plugin;

if ( ! init_plugin( __NAMESPACE__, __FILE__ ) ) {
	return;
}






