<?php

namespace SCALATER\Recipes;

defined( 'ABSPATH' ) || exit;

function init_plugin( $namespace, $filename ) {

	if ( ! check_dependencies_met( $namespace ) ) {
		return false;
	}

	add_to_autoload_namespaces( $namespace, dirname( $filename ) );

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

