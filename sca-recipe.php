<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

/**
 * Plugin Name: Recipe Revolution⚡︎
 * Plugin URI: https://scalater.com
 * Description: Easily Create and Share Delicious Recipes with the Newest WordPress Recipe Plugin
 * Version: 1.0.0
 * Requires at least: 4.6
 * Tested up to: 6.1.1
 * Requires PHP: 7.4
 * Stable tag: 1.0.0
 * Author: Scalater Team
 * Author URI: https://scalater.com
 * License: GPLv2 or later
 * Network: false
 * Text Domain: sca-recipe
 * Domain Path: /languages
 *
 * ****************************************************************************
 * WC requires at least: 3.3.0
 * WC tested up to: 6.2.1
 * ****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * ***************************************************************************
 */

namespace SCALATER\Recipes;

defined( 'ABSPATH' ) || exit;

require_once 'bootstrap.php';

const DEPENDENCIES = [ 'woocommerce', 'acf' ];

if ( ! init_plugin( __NAMESPACE__, __FILE__, 'sca-recipe' ) ) {
	return;
}

add_action( 'admin', [ List_Admin::class, 'instance' ] );
add_action( 'init', [ Post_Type::class, 'instance' ] );
add_action( 'init', [ Taxonomies_Type::class, 'instance' ] );





