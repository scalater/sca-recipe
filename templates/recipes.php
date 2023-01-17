<?php

use SCALATER\Recipes\Post_Type;
use SCALATER\Recipes\Base;
use SCALATER\Recipes\Templates;

get_header();
$video_header            = get_field( 'video_header' );
$default_portions        = get_field( 'default_portions' );
$preparation_steps       = get_field( 'preparation_steps' );
$ingredients             = get_field( 'ingredients' );
$last_author__id         = get_post_meta( get_post()->ID, '_edit_last', true );
$time_of_preparation_str = '';
$total_time              = 'P';
$time_of_preparation     = get_field( 'time_of_preparation' );
if ( ! empty( $time_of_preparation['hours'] ) ) {
	$time_of_preparation_str .= sprintf( '%s hour ', ltrim( $time_of_preparation['hours'], '0' ) );
	$total_time              .= sprintf( '%sH', ltrim( $time_of_preparation['hours'], '0' ) );
}
if ( ! empty( $time_of_preparation['minutes'] ) ) {
	$total_time .= sprintf( '%sM', ltrim( $time_of_preparation['minutes'], '0' ) );
	if ( empty( $time_of_preparation['hours'] ) ) {
		$time_of_preparation_str = sprintf( '%s min', ltrim( $time_of_preparation['minutes'], '0' ) );
	} else {
		$time_of_preparation_str .= sprintf( 'and %s min', ltrim( $time_of_preparation['minutes'], '0' ) );
	}
}
$recipe_title      = esc_attr( get_the_title() );
$nutrition         = get_field( 'nutrition' );
$nutrition_details = '';
if ( ! empty( $nutrition ) ) {
	if ( 'paragraph' === $nutrition['type'] && ! empty( $nutrition['nutrition_description'] ) ) {
		$nutrition_details = wp_kses( $nutrition['nutrition_description'], Base::allowed_html() );
	}
}

$tags = get_the_terms( get_post()->ID, Base::TAG_TYPE );
$tags = wp_list_pluck( $tags, 'name' );

$categories = get_the_terms( get_post()->ID, Base::CATEGORY_TYPE );
$categories = wp_list_pluck( $categories, 'name' );

$suitable_for_diet = get_field( 'suitable_for_diet' );
$suitable_for_diet_result = [];
if ( ! empty( $suitable_for_diet ) && is_array( $suitable_for_diet ) ) {
	$field = get_field_object( 'suitable_for_diet' );
	foreach ( $suitable_for_diet as $diet_key => $diet_value ) {
		$suitable_for_diet_result[] = $field['choices'][ $diet_value ];
	}
}

$gallery           = [];
$post_thumbnail_id = get_post_thumbnail_id();
if ( ! empty( $post_thumbnail_id ) ) {
	$gallery[] = $post_thumbnail_id;
}

$gallery_field = get_field( 'gallery' );
if ( ! empty( $gallery_field ) ) {
	$gallery = array_merge( $gallery, $gallery_field );
}

$related_recipes = get_field( 'related_recipes' );

if ( ! empty( $nutrition ) && 'paragraph' !== $nutrition['type'] ) {
	$nutrition_keys = [
		// translators: %s: value of calories
		'calories'                => __( '%s Calories', 'sca-recipes' ),
		// translators: %s: value of Carbohydrate
		'carbohydrate_content'    => __( '%s Carbohydrate Content', 'sca-recipes' ),
		// translators: %s: value of Cholesterol
		'cholesterol_content'     => __( '%s Cholesterol Content', 'sca-recipes' ),
		// translators: %s: value of Fat
		'fat_content'             => __( '%s Fat Content', 'sca-recipes' ),
		// translators: %s: value of Fiber
		'fiber_content'           => __( '%s Fiber Content', 'sca-recipes' ),
		// translators: %s: value of Protein
		'protein_content'         => __( '%s Protein Content', 'sca-recipes' ),
		// translators: %s: value of Saturated Fat
		'saturated_fat_content'   => __( '%s Saturated Fat Content', 'sca-recipes' ),
		// translators: %s: value of Serving Size
		'serving_size'            => __( '%s Serving Size', 'sca-recipes' ),
		// translators: %s: value of Sodium Content
		'sodium_content'          => __( '%s Sodium Content', 'sca-recipes' ),
		// translators: %s: value of Sugar Content
		'sugar_content'           => __( '%s Sugar Content', 'sca-recipes' ),
		// translators: %s: value of Trans Fat
		'trans_fat_content'       => __( '%s Trans Fat Content', 'sca-recipes' ),
		// translators: %s: value of Unsaturated Fat
		'unsaturated_fat_content' => __( '%s Unsaturated Fat Content', 'sca-recipes' ),
	];
	foreach ( $nutrition_keys as $key => $string ) {
		if ( ! empty( $nutrition['details'][ $key ] ) ) {
			$nutrition_details_string[] = sprintf( $string, $nutrition['details'][ $key ] );
		}
	}
	if ( ! empty( $nutrition_details_string ) ) {
		$nutrition_details = sprintf( '<strong>%s </strong>', __( 'Nutrition Details:', 'sca-recipes' ) ) . join( ', ', $nutrition_details_string ) . '.';
	}
}
?>


<div class="content-area">
	<main id="main" class="site-main" role="main">
		<section class="container gutenberg">

			<?php
			while ( have_posts() ) :
				the_post();
				?>

				<div>
					<?php do_action( 'proto_single_post_before' ); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'sca-receipt' ); ?> itemscope itemtype="https://schema.org/Recipe">
						<div class="entry-content" itemprop="mainContentOfPage">

							<section id="header">
								<?php if ( ! empty( $video_header ) ) : ?>
									<iframe class="_br5bo" width="2064" height="450" src="<?php echo esc_url( Templates::convert_youtube_url( $video_header ) ); ?>" title="<?php echo esc_attr( $recipe_title ); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
								<?php endif; ?>
								<h1><?php echo esc_attr( $recipe_title ); ?></h1>
								<?php echo wp_kses( Post_Type::get_recipe_tags( get_post()->ID ), Base::allowed_html() ); ?>

								<div class="_2uu3x">
									<?php if ( ! empty( $time_of_preparation_str ) ) : ?>
										<div class="_rlzh3">
											<svg role="img" viewBox="0 0 24 24">
												<g fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" stroke-width="1">
													<g stroke="currentColor" stroke-width="1.5">
														<g transform="translate(4.000000, 2.000000)">
															<circle cx="8.5" cy="12.46875" r="7.875"></circle>
															<line x1="13.75" x2="15.390625" y1="6.5625" y2="4.921875"></line>
															<line x1="15.0625" x2="15.71875" y1="4.59375" y2="5.25"></line>
															<line x1="8.5" x2="8.5" y1="4.59375" y2="0.65625"></line>
															<line x1="10.46875" x2="6.53125" y1="0.65625" y2="0.65625"></line>
															<line x1="8.5" x2="5.21875" y1="13.125" y2="9.492875"></line>
														</g>
													</g>
												</g>
											</svg>
											<span><?php echo esc_attr( $time_of_preparation_str ); ?></span>
										</div>
									<?php endif; ?>

									<div class="_q86cj">
										<?php if ( ! empty( $last_author__id ) ) : ?>
											<img alt="<?php echo esc_attr( get_the_modified_author() ); ?>" sizes="100px" src="<?php echo esc_url( get_avatar_url( $last_author__id, [ 'size' => '100' ] ) ); ?>">
										<?php endif; ?>
										<span><?php echo esc_attr( get_the_modified_author() ); ?></span>
									</div>
								</div>

								<div class="_2uu3x">
									<?php the_content(); ?>
								</div>

								<div class="_ghesd">
									<?php echo wp_kses( $nutrition_details, Base::allowed_html() ); ?>
								</div>

							</section>

							<section id="recipe">
								<div class="recipe-content">
									<div class="_rm02r">
										<h2><?php esc_attr_e( 'Ingredients', 'sca-recipes' ); ?></h2>

										<div class="_csd8h">
											<button type="button">
												<svg role="img" viewBox="0 0 12 12">
													<path d="M1.313 6.038h9.375" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
												</svg>
											</button>

											<div class="_n7b9q">
												<input data-portion="<?php echo esc_attr( $default_portions ); ?>" inputmode="decimal" type="text" disabled="disabled" autocomplete="off" value="<?php echo esc_attr( $default_portions ); ?>">
												<span><?php esc_attr_e( 'Portions', 'sca-recipes' ); ?></span>
											</div>

											<button type="button">
												<svg role="img" viewBox="0 0 12 12">
													<path d="M6 11.25a.75.75 0 01-.75-.75V6.75H1.5a.75.75 0 010-1.5h3.75V1.5a.75.75 0 011.5 0v3.75h3.75a.75.75 0 010 1.5H6.75v3.75a.75.75 0 01-.75.75z" fill="currentColor"></path>
												</svg>
											</button>
										</div>

										<?php if ( $ingredients ) : ?>
											<?php foreach ( $ingredients as $ingredient ) : ?>
												<?php if ( ! empty( $ingredient['group_name'] ) ) : ?>
													<h3><?php echo esc_attr( $ingredient['group_name'] ); ?></h3>
												<?php endif; ?>
												<?php if ( ! empty( $ingredient['ingredient'] ) ) : ?>
													<ul class="_fn2fq">
														<?php foreach ( $ingredient['ingredient'] as $item ) : ?>
															<?php
															$unit = '';
															if ( ! empty( $item['unit'] ) ) {
																$unit_term = get_term_field( 'name', $item['unit'], BASE::UNIT_TYPE );
																if ( ! is_wp_error( $unit_term ) ) {
																	$unit = $unit_term;
																}
															}
															?>
															<li class="_q8ha5" is-portion-enabled="<?php echo ! empty( $item['amount'] ) && ! empty( $item['unit'] ) ? 1 : 0; ?>" data-type="ingredient" data-amount="<?php echo esc_attr( wc_format_localized_decimal( $item['amount'] ) ); ?>" data-unit="<?php echo esc_attr( $unit ); ?>">
																<strong class="_6qxje"><?php echo sprintf( '<span data-type="amount">%s</span> <span data-type="unit">%s</span>', esc_attr( wc_format_localized_decimal( $item['amount'] ) ), esc_attr( $unit ) ); ?></strong>
																<span class="_5n0m4">
																	<?php
																	if ( ! empty( $item['related_products'] ) ) {
																		$product_with_stock = Post_Type::get_product_with_stock( $item['related_products'] );
																		if ( ! empty( $product_with_stock ) ) {
																			echo sprintf( '<a href="%s">%s</a>', esc_url( $product_with_stock->get_permalink() ), esc_attr( $product_with_stock->get_name() ) );
																		} else {
																			echo esc_attr( $item['name'] );
																		}
																	} else {
																		echo esc_attr( $item['name'] );
																	}
																	?>
																</span>
															</li>
														<?php endforeach; ?>
													</ul>
												<?php endif; ?>
											<?php endforeach; ?>

										<?php endif; ?>

										<button class="_zov6h" style="display: none">
											<svg class="_mz3tf" role="img" viewBox="0 0 24 24">
												<path d="M5.25 11.261a.75.75 0 0 1 0-1.5h5.25a.75.75 0 0 1 0 1.5H5.25zM5.25 15.011a.75.75 0 0 1 0-1.5h3a.75.75 0 0 1 0 1.5h-3zM5.25 18.761a.75.75 0 0 1 0-1.5h3a.75.75 0 0 1 0 1.5h-3z"></path>
												<path d="M2.25 24.011A2.252 2.252 0 0 1 0 21.761V6.011a2.252 2.252 0 0 1 2.25-2.25h3.063C5.675 1.632 7.561.011 9.75.011c2.19 0 4.076 1.621 4.437 3.75h3.063a2.252 2.252 0 0 1 2.25 2.25v2.25a.75.75 0 0 1-1.5 0v-2.25a.75.75 0 0 0-.75-.75H13.5a.75.75 0 0 1-.75-.75c0-1.654-1.346-3-3-3s-3 1.346-3 3a.75.75 0 0 1-.75.75H2.25a.75.75 0 0 0-.75.75v15.75c0 .414.336.75.75.75h7.5a.75.75 0 0 1 0 1.5h-7.5z"></path>
												<circle cx="9.75" cy="4.136" r="1.125"></circle>
												<path d="M17.25 24.011c-3.722 0-6.75-3.028-6.75-6.75s3.028-6.75 6.75-6.75S24 13.539 24 17.261s-3.028 6.75-6.75 6.75zm0-12c-2.895 0-5.25 2.355-5.25 5.25s2.355 5.25 5.25 5.25 5.25-2.355 5.25-5.25-2.355-5.25-5.25-5.25z"></path>
												<path d="M17.25 21.011a.75.75 0 0 1-.75-.75v-2.25h-2.25a.75.75 0 0 1 0-1.5h2.25v-2.25a.75.75 0 0 1 1.5 0v2.25h2.25a.75.75 0 0 1 0 1.5H18v2.25a.75.75 0 0 1-.75.75z"></path>
											</svg>
											<span class="_nehek"><?php esc_attr_e( 'Add to shopping list', 'sca-recipes' ); ?></span>
										</button>
									</div>

									<div class="_ih7i5">
										<h2><?php esc_attr_e( 'This is what you do', 'sca-recipes' ); ?></h2>

										<div class="_5ws47">
											<span class="_d2lkd"><?php esc_attr_e( 'Keep your screen on', 'sca-recipes' ); ?></span>
											<label>
												<input type="checkbox">
												<div class="_w2pi7">
													<span data-state="true"><?php esc_attr_e( 'Yes', 'sca-recipes' ); ?></span>
													<span data-state="false"><?php esc_attr_e( 'No', 'sca-recipes' ); ?></span>
												</div>
											</label>
										</div>
										<?php if ( $preparation_steps ) : ?>
											<?php foreach ( $preparation_steps as $preparation_step ) : ?>
												<?php if ( ! empty( $preparation_step['section_name'] ) ) : ?>
													<h3><?php echo esc_attr( $preparation_step['section_name'] ); ?></h3>
												<?php endif; ?>
												<?php if ( ! empty( $preparation_step['steps'] ) ) : ?>
													<ul class="_b2qgs">
														<?php foreach ( $preparation_step['steps'] as $key => $item ) : ?>
															<li class="_1lpuh">
																<input type="checkbox" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>">
																<label for="<?php echo esc_attr( $key ); ?>"><p class="_h0kmg"><?php echo wp_kses( $item['step_description'], Base::allowed_html() ); ?></p></label>
															</li>
														<?php endforeach; ?>
													</ul>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									</div>
								</div>

								<?php if ( ! empty( $gallery ) ) : ?>
									<section id="gallery">
										<div class="gallery-content">
											<?php foreach ( $gallery as $image ) : ?>
												<?php echo wp_get_attachment_image( $image, 'medium', false, [ 'class' => '_geya4' ] ); ?>
											<?php endforeach; ?>
										</div>
									</section>
								<?php endif; ?>

								<div class="recipe-end">
									<small><?php esc_attr_e( 'Published:', 'sca-recipes' ); ?> <strong><?php the_date(); ?></strong></small>
									<small><?php esc_attr_e( 'Updated:', 'sca-recipes' ); ?> <strong><?php echo esc_attr( the_modified_date( '', '', '', false ) ); ?></strong></small>
								</div>
							</section>

							<?php if ( ! empty( $related_recipes ) ) : ?>
								<section id="recomendations">
								<h2 class="_xc35j"><?php esc_attr_e( 'Recommendations', 'sca-recipes' ); ?></h2>
								<div class="recomendations-content">
									<?php foreach ( $related_recipes as $related ) : ?>
										<div class="_xrhj6">
											<a class="_ve4vy" href="<?php echo esc_url( get_the_permalink( $related ) ); ?>"><?php echo wp_get_attachment_image( get_post_thumbnail_id( $related ), 'medium', false, [ 'class' => '_09wva' ] ); ?></a>
											<a class="_ve4vy" href="<?php echo esc_url( get_the_permalink( $related ) ); ?>"><?php echo esc_attr( get_the_title( $related ) ); ?></a>
											<div class="_h6mxb">
												<?php echo wp_kses( Post_Type::get_recipe_tags( $related ), Base::allowed_html() ); ?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</section>
							<?php endif; ?>
						</div>

					</article><!-- #post-## -->

					<?php do_action( 'proto_single_post_after' ); ?>
				</div>

			<?php endwhile; ?>

		</section>

	</main>
</div>

<?php get_footer(); ?>
