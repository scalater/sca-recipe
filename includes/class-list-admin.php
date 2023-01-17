<?php
/**
 * List_Admin class
 *
 * @package SCALATER\Recipes
 * @author Scalater Team
 * @license GPLv2 or later
 */

namespace SCALATER\Recipes;

defined( 'ABSPATH' ) || exit;

/**
 * Class List_Admin
 *
 * @package SCALATER\Recipes
 */
class List_Admin extends Base {
	use Singleton;

	/**
	 * Adding action hooks
	 */
	protected function init() {
		add_filter( 'manage_' . Base::POST_TYPE . '_posts_columns', [ $this, 'custom_admin_columns' ] );
	}

	/**
	 * Modify the CPT columns
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function custom_admin_columns( $columns ) {
		$columns = [
			'cb' => $columns['cb'],
			'title' => __( 'Title', 'sca-recipe' ),
			'author' => __( 'Author', 'sca-recipe' ),
			'date' => __( 'Date', 'sca-recipe' ),
		];

		return $columns;
	}
}
