<?php

namespace SCALATER\Recipes;

defined( 'ABSPATH' ) || exit;

function autoload( $class ) {
	global $sca_autoload_namespaces;

	if ( strpos( $class, 'SCALATER\\' ) !== 0 || empty( $sca_autoload_namespaces ) ) {
		return;
	}

	$load_path = null;
	$autoload  = false;

	$pieces    = explode( '\\', $class );
	$classname = array_pop( $pieces );
	$namespace = implode( '\\', $pieces );

	foreach ( $sca_autoload_namespaces as $key => $load_path ) {
		if ( $namespace === $key || ( strpos( $namespace, $key . '\\' ) === 0 ) ) {
			$autoload = true;
			break;
		}
	}

	if ( ! $autoload || ! $load_path ) {
		return;
	}

	$path = $load_path . '/includes' . strtolower( str_replace( [ '\\', '_' ], [ '/', '-' ], substr( $namespace, strlen( $key ) ) ) ) . '/';
	$slug = strtolower( str_replace( '_', '-', $classname ) ) . '.php';

	$prefixes = [ 'class', 'trait', 'abstract' ];

	foreach ( $prefixes as $prefix ) {
		$filename = $path . $prefix . '-' . $slug;

		if ( file_exists( $filename ) ) {
			require_once $filename;
			return;
		}
	}
}

spl_autoload_register( __NAMESPACE__ . '\autoload' );

function add_to_autoload_namespaces( $namespace, $load_path ) {
	global $sca_autoload_namespaces;
	$sca_autoload_namespaces[ $namespace ] = $load_path;

	uksort(
		$sca_autoload_namespaces,
		function ( $a, $b ) {
			// make sure deeper namespaces are resolved first
			return strlen( $b ) - strlen( $a );
		}
	);
}

function init_plugin( $namespace, $filename, $slug ) {

	if ( ! check_dependencies_met( $namespace ) ) {
		return false;
	}

	add_to_autoload_namespaces( $namespace, dirname( $filename ) );

	define( $namespace . '\URL', plugins_url( '/', __DIR__ . DIRECTORY_SEPARATOR . $slug . '.php' ) );
	define( $namespace . '\HANDLE', $slug );

	add_action( 'plugins_loaded', function () use ( $slug ) {
		load_plugin_textdomain( $slug, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		do_action( 'scalater/plugin_loaded' );
	}, 999 );

	return true;
}

function check_dependencies_met( $namespace ) {
	$dependencies = constant( $namespace . '\DEPENDENCIES' );

	if ( is_array( $dependencies ) ) {
		$dependencies = array_filter( $dependencies );
	}

	if ( empty( $dependencies ) ) {
		return true;
	}

	$missing = [];
	$hash    = [
		'acf' => 'advanced-custom-fields/acf.php',
		'woocommerce' => 'woocommerce/woocommerce.php',
	];

	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	foreach ( $dependencies as $dependency ) {
		if ( isset( $hash[ $dependency ] ) ) {
			$dependency = $hash[ $dependency ];
		}

		if ( 'sca-' === substr( $dependency, 0, 4 ) ) {
			$dependency = "$dependency/$dependency.php";
		}

		if (
			! \is_plugin_active( $dependency ) &&
			! \is_plugin_active_for_network( $dependency )
		) {
			$missing[] = $dependency;
		}
	}

	if ( ! empty( $missing ) ) {
		add_admin_notice( $namespace, $missing );
		return false;
	}

	return true;
}

function add_admin_notice( $namespace, $missing ) {
	$name = constant( $namespace . '\NAME' );
	add_action(
		'admin_notices',
		function() use ( $name, $missing ) {
			?>
			<div class="notice notice-error" >
				<p>Please enable the following dependencies before using <?php echo esc_html( $name ); ?></p>
				<ul>
					<?php foreach ( $missing as $plugin ) : ?>
						<li><?php echo esc_html( $plugin ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php
		}
	);
}

