<?php
require('../../wp-load.php');
require '../Slim/Slim.php';
require('classes/class.JSONBuilder.php');

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/posts/:name/?', function ($name) {
	
	$posts = get_posts(array("post_type" => $name));
	if(count($posts) <= 0)
	{
		throw new Exception('No posts found!');
	}

	$builder = new JSONBuilder($posts);
	$builder->convert_wp_gallery_shortcodes();
	$builder->build_custom_fields();
	$builder->build_featured_image();
	$builder->build_post_thumbnails();
	$builder->build_post_attachments();   
   	$builder->outputJSON();
});

$app->get('/posts/id/:id/?', function($id) {
	
	preg_match('#\d+#', $id, $digit);
	
	if(count($digit) > 0)
	{
		$post = get_post($id);
		if(count($post) <= 0)
		{
			throw new Exception('No post found!');
		}

		$builder = new JSONBuilder($post);
		$builder->convert_wp_gallery_shortcodes();
		$builder->build_custom_fields();
		$builder->build_featured_image();
		$builder->build_post_thumbnails();
		$builder->build_post_attachments();  
		$builder->outputJSON();
	}
	else
	{
	  	throw new Exception('Param: ' . $id . " must be an integer");
	}
});

$app->run();
