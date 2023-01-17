<?php
/**
 * Post class to add actions and filters related to Post types
 *
 * @package SCALATER\Recipes
 * @author Scalater Team
 * @license GPLv2 or later
 */

namespace SCALATER\Recipes;

defined( 'ABSPATH' ) || exit;

/**
 * Class Post
 *
 * @package SCALATER\Recipes
 */
class Post_Type extends Base {
	use Singleton;

	/**
	 * Adding action hooks
	 */
	protected function init() {
		add_action( 'init', [ $this, 'register' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'add_scripts' ] );
	}

	/**
	 * Use this action to add CSS + JavaScript
	 */
	public function add_scripts() {
		if ( parent::POST_TYPE === get_post_type() ) {
			wp_enqueue_style( 'sca-recipes', $this->get_asset_url( 'sca-recipes', 'css' ), [], $this->get_version() );
			wp_enqueue_script( 'sca-recipes', $this->get_asset_url( 'sca-recipes' ), [ 'jquery' ], $this->get_version(), true );
		}
	}

	/**
	 * Method registers necessary post type
	 */
	public function register() {
		if ( post_type_exists( parent::POST_TYPE ) ) {
			return;
		}

		register_post_type(
			parent::POST_TYPE,
			[
				'labels'             => [
					'name'               => __( 'Recipes', 'sca-recipes' ),
					'singular_name'      => __( 'Recipe', 'sca-recipes' ),
					'all_items'          => __( 'All Recipes', 'sca-recipes' ),
					'menu_name'          => _x( 'Recipes', 'Admin menu name', 'sca-recipes' ),
					'add_new'            => __( 'Add New', 'sca-recipes' ),
					'add_new_item'       => __( 'Add new recipe', 'sca-recipes' ),
					'edit_item'          => __( 'Edit recipe', 'sca-recipes' ),
					'new_item'           => __( 'New recipe', 'sca-recipes' ),
					'view_item'          => __( 'View recipe', 'sca-recipes' ),
					'search_items'       => __( 'Find recipe', 'sca-recipes' ),
					'not_found'          => __( 'Recipes have not been found', 'sca-recipes' ),
					'not_found_in_trash' => __( 'No recipes in the trash', 'sca-recipes' ),
					'parent_item_colon'  => '',
				],
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_nav_menus'  => false,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => [ 'slug' => 'recipe' ],
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_icon'          => 'dashicons-carrot',
				'show_in_rest'       => false,
				'supports'           => [ 'title', 'editor', 'author', 'thumbnail' ],
				'taxonomies'         => [],
				'capability_type'    => 'recipe',
				'map_meta_cap'       => true,
				'capabilities'       => [
					'delete_posts'           => 'delete_recipes',
					'delete_post'            => 'delete_recipe',
					'delete_published_posts' => 'delete_published_recipes',
					'delete_private_posts'   => 'delete_private_recipes',
					'delete_others_posts'    => 'delete_others_recipes',
					'edit_post'              => 'edit_recipe',
					'edit_posts'             => 'edit_recipes',
					'edit_others_posts'      => 'edit_others_recipes',
					'edit_published_posts'   => 'edit_published_recipes',
					'read_post'              => 'read_recipe',
					'read_private_posts'     => 'read_private_recipes',
					'publish_posts'          => 'publish_recipes',
				],
			]
		);
	}

	/**
	 * Get the first product with stock
	 *
	 * @param $products
	 *
	 * @return false|\WC_Product
	 */
	public static function get_product_with_stock( $products ) {
		//todo add a validation to check if the wc_get_product function exists
		foreach ( $products as $item ) {
			$product = wc_get_product( $item );
			if ( ! empty( $product ) ) {
				if ( ! empty( $product->get_stock_quantity() ) ) {
					return $product;
				}
			}
		}

		return false;
	}

	//todo this function need to be rebuild because it's bases on acf
	public static function get_recipe_tags( $recipe_id ) {
		$tags = get_the_terms( $recipe_id, Base::TAG_TYPE );
		$tags = wp_list_pluck( $tags, 'name' );

		$categories = get_the_terms( $recipe_id, Base::CATEGORY_TYPE );
		$categories = wp_list_pluck( $categories, 'name' );

		$suitable_for_diet        = get_field( 'suitable_for_diet', $recipe_id );
		$suitable_for_diet_result = [];
		if ( ! empty( $suitable_for_diet ) && is_array( $suitable_for_diet ) ) {
			$field = get_field_object( 'suitable_for_diet', $recipe_id );
			foreach ( $suitable_for_diet as $diet_value ) {
				$suitable_for_diet_result[] = $field['choices'][ $diet_value ];
			}
		}

		if ( ! empty( $tags ) || ! empty( $categories ) || ! empty( $suitable_for_diet_result ) ) {

			$html = '<div>';
			if ( ! empty( $tags ) ) {
				$html .= join( ', ', array_map( 'esc_attr', $tags ) );
			}
			if ( ! empty( $tags ) && ! empty( $categories ) ) {
				$html .= esc_attr( ' | ' );
			}
			if ( ! empty( $categories ) ) {
				$html .= join( ', ', array_map( 'esc_attr', $categories ) );
			}
			if ( ! empty( $suitable_for_diet_result ) && ! empty( $categories ) ) {
				$html .= esc_attr( ' | ' );
			}
			if ( ! empty( $suitable_for_diet_result ) ) {
				$html .= join( ', ', array_map( 'esc_attr', $suitable_for_diet_result ) );
			}

			return $html . '</div>';
		}

		return '';
	}
}
