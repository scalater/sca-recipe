<?php
/**
 * Taxonomies_Type class to add actions and filters related to Taxonomies Types
 *
 * @package SCALATER\Recipes
 * @author Scalater Team
 * @license GPLv2 or later
 */

namespace SCALATER\Recipes;

use SCALATER\Recipes\Traits\Singleton;

defined( 'ABSPATH' ) || exit;

/**
 * Class Taxonomies_Type
 *
 * @package SCALATER\Recipes
 */
class Taxonomies_Type extends Base {
	use Singleton;

	/**
	 * Taxonomy capability
	 *
	 * @var string[]
	 */
	private $cap = [
		'manage_terms' => 'edit_recipes',
		'edit_terms'   => 'edit_recipes',
		'delete_terms' => 'delete_recipes',
		'assign_terms' => 'edit_recipe',
	];

	/**
	 * Adding action hooks
	 */
	protected function init() {
		add_action( 'init', [ $this, 'register_category' ] );
		add_action( 'init', [ $this, 'register_tag' ] );
		add_action( 'init', [ $this, 'register_unit' ] );
		add_action( 'init', [ $this, 'register_cooking' ] );
		add_action( 'init', [ $this, 'register_cuisine' ] );

	}

	/**
	 * Method registers necessary taxonomies types
	 */
	public function register_tag() {
		if ( taxonomy_exists( parent::TAG_TYPE ) ) {
			return;
		}

		$labels = [
			'name'                       => _x( 'Tags', 'taxonomy general name', 'sca-recipes' ),
			'singular_name'              => _x( 'Tag', 'taxonomy singular name', 'sca-recipes' ),
			'search_items'               => __( 'Search Tags', 'sca-recipes' ),
			'popular_items'              => __( 'Popular Tags', 'sca-recipes' ),
			'all_items'                  => __( 'All Tags', 'sca-recipes' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Tag', 'sca-recipes' ),
			'update_item'                => __( 'Update Tag', 'sca-recipes' ),
			'add_new_item'               => __( 'Add New Tag', 'sca-recipes' ),
			'new_item_name'              => __( 'New Tag Name', 'sca-recipes' ),
			'separate_items_with_commas' => __( 'Separate tags with commas', 'sca-recipes' ),
			'add_or_remove_items'        => __( 'Add or remove tags', 'sca-recipes' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags', 'sca-recipes' ),
			'menu_name'                  => __( 'Tags', 'sca-recipes' ),
		];

		register_taxonomy(
			parent::TAG_TYPE,
			parent::POST_TYPE,
			[
				'hierarchical'          => false,
				'labels'                => $labels,
				'show_ui'               => true,
				'show_in_rest'          => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'rewrite'               => [ 'slug' => 'tag' ],
				'capabilities'          => $this->cap,
			]
		);
	}

	/**
	 * Method registers necessary taxonomies types
	 */
	public function register_category() {
		if ( taxonomy_exists( parent::CATEGORY_TYPE ) ) {
			return;
		}

		$labels = [
			'name'              => _x( 'Category', 'taxonomy general name', 'sca-recipes' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name', 'sca-recipes' ),
			'search_items'      => __( 'Search Categories', 'sca-recipes' ),
			'all_items'         => __( 'All Categories', 'sca-recipes' ),
			'parent_item'       => __( 'Parent Category', 'sca-recipes' ),
			'parent_item_colon' => __( 'Parent Subject:', 'sca-recipes' ),
			'edit_item'         => __( 'Edit Category', 'sca-recipes' ),
			'update_item'       => __( 'Update Category', 'sca-recipes' ),
			'add_new_item'      => __( 'Add New Category', 'sca-recipes' ),
			'new_item_name'     => __( 'New Category Name', 'sca-recipes' ),
			'menu_name'         => __( 'Categories', 'sca-recipes' ),
		];

		register_taxonomy(
			parent::CATEGORY_TYPE,
			parent::POST_TYPE,
			[
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => [ 'slug' => 'category' ],
				'capabilities'          => $this->cap,
			]
		);
	}

	/**
	 * Method registers necessary taxonomies types
	 */
	public function register_unit() {
		if ( taxonomy_exists( parent::UNIT_TYPE ) ) {
			return;
		}

		$labels = [
			'name'              => _x( 'Unit', 'taxonomy general name', 'sca-recipes' ),
			'singular_name'     => _x( 'Unit', 'taxonomy singular name', 'sca-recipes' ),
			'search_items'      => __( 'Search Units', 'sca-recipes' ),
			'all_items'         => __( 'All Units', 'sca-recipes' ),
			'parent_item'       => __( 'Parent Unit', 'sca-recipes' ),
			'parent_item_colon' => __( 'Parent Subject:', 'sca-recipes' ),
			'edit_item'         => __( 'Edit Unit', 'sca-recipes' ),
			'update_item'       => __( 'Update Unit', 'sca-recipes' ),
			'add_new_item'      => __( 'Add New Unit', 'sca-recipes' ),
			'new_item_name'     => __( 'New Unit Name', 'sca-recipes' ),
			'menu_name'         => __( 'Units', 'sca-recipes' ),
		];

		register_taxonomy(
			parent::UNIT_TYPE,
			parent::POST_TYPE,
			[
				'hierarchical'      => true,
				'labels'            => $labels,
				'meta_box_cb'       => false,
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => [ 'slug' => 'unit' ],
				'capabilities'          => $this->cap,
			]
		);
	}

	/**
	 * Method registers necessary taxonomies types
	 */
	public function register_cooking() {
		if ( taxonomy_exists( parent::COOKING_TYPE ) ) {
			return;
		}

		$labels = [
			'name'              => _x( 'Cooking', 'taxonomy general name', 'sca-recipes' ),
			'singular_name'     => _x( 'Cooking', 'taxonomy singular name', 'sca-recipes' ),
			'search_items'      => __( 'Search Cooking', 'sca-recipes' ),
			'all_items'         => __( 'All Cooking', 'sca-recipes' ),
			'parent_item'       => __( 'Parent Cooking', 'sca-recipes' ),
			'parent_item_colon' => __( 'Parent Subject:', 'sca-recipes' ),
			'edit_item'         => __( 'Edit Cooking', 'sca-recipes' ),
			'update_item'       => __( 'Update Cooking', 'sca-recipes' ),
			'add_new_item'      => __( 'Add New Cooking', 'sca-recipes' ),
			'new_item_name'     => __( 'New Cooking Name', 'sca-recipes' ),
			'menu_name'         => __( 'Cooking', 'sca-recipes' ),
		];

		register_taxonomy(
			parent::COOKING_TYPE,
			parent::POST_TYPE,
			[
				'hierarchical'      => true,
				'labels'            => $labels,
				'meta_box_cb'       => false,
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => [ 'slug' => 'cooking' ],
				'capabilities'          => $this->cap,
			]
		);
	}

	/**
	 * Method registers necessary taxonomies types
	 */
	public function register_cuisine() {
		if ( taxonomy_exists( parent::CUISINE_TYPE ) ) {
			return;
		}

		$labels = [
			'name'              => _x( 'Cuisine', 'taxonomy general name', 'sca-recipes' ),
			'singular_name'     => _x( 'Cuisine', 'taxonomy singular name', 'sca-recipes' ),
			'search_items'      => __( 'Search Cuisine', 'sca-recipes' ),
			'all_items'         => __( 'All Cuisine', 'sca-recipes' ),
			'parent_item'       => __( 'Parent Cuisine', 'sca-recipes' ),
			'parent_item_colon' => __( 'Parent Subject:', 'sca-recipes' ),
			'edit_item'         => __( 'Edit Cuisine', 'sca-recipes' ),
			'update_item'       => __( 'Update Cuisine', 'sca-recipes' ),
			'add_new_item'      => __( 'Add New Cuisine', 'sca-recipes' ),
			'new_item_name'     => __( 'New Cuisine Name', 'sca-recipes' ),
			'menu_name'         => __( 'Cuisine', 'sca-recipes' ),
		];

		register_taxonomy(
			parent::CUISINE_TYPE,
			parent::POST_TYPE,
			[
				'hierarchical'      => true,
				'labels'            => $labels,
				'meta_box_cb'       => false,
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => [ 'slug' => 'cooking' ],
				'capabilities'          => $this->cap,
			]
		);
	}
}
