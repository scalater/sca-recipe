<?php

namespace SCALATER\Recipes;

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
