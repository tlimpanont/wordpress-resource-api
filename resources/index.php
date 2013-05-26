<?php
require('../../wp-load.php');
require '../Slim/Slim.php';
require('classes/class.JSONBuilder.php');

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/posts/:name/?', function ($name) {
	
	$builder = new JSONBuilder(get_posts(array("post_type" => $name)));
	$builder->convert_wp_gallery_shortcodes();
	$builder->build_custom_fields();
	$builder->build_featured_image();
	$builder->build_post_thumbnails();
	$builder->build_post_attachments();   
   	$builder->outputJSON();
});

$app->get('/posts/id/:id/?', function($id) {
	
	$builder = new JSONBuilder(get_post($id));
	$builder->convert_wp_gallery_shortcodes();
	$builder->build_custom_fields();
	$builder->build_featured_image();
	$builder->build_post_thumbnails();
	$builder->build_post_attachments();  
	$builder->outputJSON();
});

$app->run();
