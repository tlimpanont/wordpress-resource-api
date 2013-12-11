<?php
require '../wp-blog-header.php';
require '../wp-load.php';
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();
\Slim\Route::setDefaultConditions(array(
    'id' => '\d+',
    'name' => '\w+',
    'slug' => '\w+'
));
$app = new \Slim\Slim();


$app->get('/posts/?', function () {
	$args = array (
		'post_type'              => 'any',
		'posts_per_page'         => '-1',
		'order'                  => 'ASC',
		'orderby'                => 'title',
	);
	$query = new WP_Query( $args );
	echo json_encode($query->posts, JSON_HEX_QUOT | JSON_HEX_TAG);
});

$app->get('/taxonomies/?', function () {
	parse_str($_SERVER['QUERY_STRING'], $args);
	echo json_encode(get_taxonomies('', 'names'), JSON_HEX_QUOT | JSON_HEX_TAG);
});

$app->get('/taxonomies/name/:taxonomy/?', function ($taxonomy) {
	parse_str($_SERVER['QUERY_STRING'], $args);
	echo json_encode(get_taxonomy($taxonomy), JSON_HEX_QUOT | JSON_HEX_TAG);
});


$app->get('/terms/?', function () {
	parse_str($_SERVER['QUERY_STRING'], $args);
	echo json_encode(get_terms( get_taxonomies("", "names")), JSON_HEX_QUOT | JSON_HEX_TAG);
});

$app->get('/terms/:term_id/:taxonomy/?', function ($term_id, $taxonomy) {
	parse_str($_SERVER['QUERY_STRING'], $args);
	echo json_encode(get_term_by('id', $term_id, $taxonomy), JSON_HEX_QUOT | JSON_HEX_TAG);
});


$app->get('/posts/taxonomy/:taxonomy/term/:term/?', function ($taxonomy, $term) {
	$args = array(
		'post_type' => 'any',
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => array( $term )
			)
		)
	);
	$query = new WP_Query( $args );
	echo json_encode($query->posts, JSON_HEX_QUOT | JSON_HEX_TAG);
});


$app->get('/posts/:post_id/?', function ($post_id) {
	$args = array (
		'p'				 		 => $post_id,
		'post_type'              => 'any',
		'posts_per_page'         => '-1',
		'order'                  => 'ASC',
		'orderby'                => 'title',
	);
	$query = new WP_Query( $args );
	echo json_encode($query->posts, JSON_HEX_QUOT | JSON_HEX_TAG);
});

$app->get('/:post_type/?', function ($post_type) {
	$args = array (
		'post_type'              => $post_type,
		'posts_per_page'         => '-1',
		'order'                  => 'ASC',
		'orderby'                => 'title',
	);
	$query = new WP_Query( $args );
	echo json_encode($query->posts, JSON_HEX_QUOT | JSON_HEX_TAG);
});

$app->get('/:post_type/taxonomy/:taxonomy/term/:term/?', function ($post_type, $taxonomy, $term) {
	$args = array(
		'post_type' => $post_type,
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => array( $term )
			)
		)
	);
	$query = new WP_Query( $args );
	echo json_encode($query->posts, JSON_HEX_QUOT | JSON_HEX_TAG);
});


$app->get('/:post_type/:post_id', function ($post_type, $post_id) {
	$args = array (
		'p'				 		 => $post_id,
		'post_type'              => $post_type,
		'posts_per_page'         => '-1',
		'order'                  => 'ASC',
		'orderby'                => 'title',
	);
	$query = new WP_Query( $args );
	echo json_encode($query->posts, JSON_HEX_QUOT | JSON_HEX_TAG);
});

$app->run();
