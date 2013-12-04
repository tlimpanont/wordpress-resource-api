<?php
require('../../wp-load.php');
require '../Slim/Slim.php';
require('classes/class.WPPostBuilder.php');
require('classes/class.WPPostArgumentsBuilder.php');
require('classes/class.WPTaxonomyBuilder.php');

\Slim\Slim::registerAutoloader();
\Slim\Route::setDefaultConditions(array(
    'id' => '\d+',
    'name' => '\w+'
));
$app = new \Slim\Slim();


$app->get('/posts/:name/?', function ($name) {
	
	$queryArgumentsBuilder = new WPPostArgumentsBuilder($_SERVER['QUERY_STRING']);

	$posts = get_posts($queryArgumentsBuilder->build($name));
	
	if(empty($posts))
		throw new Exception('No posts found!');
	

	$builder = new WPPostBuilder($posts);
	$builder->build();
	$builder->outputJSON();
});

$app->get('/posts/id/:id/?', function($id) {
	
	preg_match('#\d+#', $id, $digit);
	
	if(count($digit) > 0)
	{
		$post = get_post($id);
		if(empty($post))
		{
			throw new Exception('No post found!');
		}

		$builder = new WPPostBuilder($post);
		$builder->build();
		$builder->outputJSON();
	}
	else
	{
	  	throw new Exception('Param: ' . $id . " must be an integer");
	}
});

$app->get('/taxonomies/?' , function() {
  $taxonomies = get_taxonomies("", "all");
  if(empty($taxonomies))
  		throw new Exception('No taxonomies found!');

  $builder = new WPTaxonomyBuilder($taxonomies);
  $builder->override_output(array());
  $builder->build();
  $builder->outputJSON();
});

$app->get('/taxonomies/:name?' , function($name) {
  $taxonomy = get_taxonomy( $name );
  if(empty($taxonomy))
  		throw new Exception('No taxonomy found!');

  $builder = new WPTaxonomyBuilder( $taxonomy );
  $builder->override_output(array());
  $builder->build();
  $builder->outputJSON();
});


$app->run();
