<?php 
require("../wp-load.php");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.min.css">
	<title>Wordpress Post RESTful Api</title>
</head>
<body ng-app="WPApi" ng-controller="MainController">
	 <div class="container">
    	<div class="row">
    		<div class="span8">
    			<h1>REST API v1.0 Resources </h1>
				<div ng-view></div>
    		</div>
    		<div class="span4">
				<h3>Resources</h3>
				<div class="well">
					<ul class="nav nav-list">
						 <li class="nav-header">Posts</li>
					    <?php foreach (get_post_types() as $key => $value): ?>
				     			<li><a href="#/posts/<?=$value;?>"><?= $value;?> </a></li>
				     	<?php endforeach ?>
					</ul>

				</div>
    	</div>
  	</div>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/jquery-1.8.3.min.js"><\/script>')</script>
  	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>
	<script type="text/javascript" src="js/WPApi.js"></script>
</body>
</html>