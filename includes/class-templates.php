<?php
/**
 * Template class to override the custom post type template hierarchy
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
class Templates extends Base {
	use Singleton;

	/**
	 * Adding action hooks
	 */
	protected function init() {
		add_filter( 'single_template', [ $this, 'template_override' ] );
	}

	/**
	 * Convert YouTube short and watch url to embed
	 *
	 * @param $url
	 *
	 * @return string
	 */
	public static function convert_youtube_url( $url ): string {
		$short_url_regex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
		$long_url_regex  = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

		$youtube_id = '';
		if ( preg_match( $long_url_regex, $url, $matches ) ) {
			$youtube_id = $matches[ count( $matches ) - 1 ];
		}

		if ( preg_match( $short_url_regex, $url, $matches ) ) {
			$youtube_id = $matches[ count( $matches ) - 1 ];
		}

		return sprintf( 'https://www.youtube.com/embed/%s', $youtube_id );
	}

	/**
	 * Override the default template for the CPT Recipe
	 *
	 * @param $template
	 *
	 * @return mixed
	 */
	public function template_override( $template ) {
		if ( parent::POST_TYPE === get_post_type() ) {
			return locate_template( 'recipes.php');
		}

		return $template;
	}

}
