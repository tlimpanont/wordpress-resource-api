<?php
/* 	arguments can be set in the URL as Query String. See: http://codex.wordpress.org/Function_Reference/get_posts 
	example: ?posts_per_page=-1&order=ASC&orderby=title
*/
class WPPostArgumentsBuilder {
	var $query_string = "";
	var $allowed_query_params = array(
		'posts_per_page',
		'offset',
		'category',
		'orderby',
		'order',
		'include',
		'exclude',
		'meta_key',
		'meta_value',
		// 'post_type', post type is already defined in the resource api url
		'post_mime_type',
		'post_parent',
		'post_status',
		'suppress_filters',
		'term',
		'taxonomy'
	);

	public function __construct($query_string) 
	{
		$this->query_string = $query_string;
		parse_str($this->query_string, $params);
		$this->parsed_query_string = $params;

		if(!$this->checkAllowedQueryParams())
			throw new Exception("One or more query string param(s) is not permitted!");
	}

	private function checkAllowedQueryParams() 
	{
		foreach ($this->parsed_query_string as $key => $value) 
		{
			if(!in_array($key, $this->allowed_query_params))
			{
				return false;
			}
		}

		return true;
	}

	public function build($post_name) 
	{
		if($this->parsed_query_string['taxonomy'] && $this->parsed_query_string['term'])
		{
			return array_merge($this->parsed_query_string, 
				array(
					"post_type" => $post_name,
					'tax_query' => array(
				        array(
				            'taxonomy' => $this->parsed_query_string['taxonomy'] ,
				            'field' => 'slug',
				            'terms' => $this->parsed_query_string['term']
				      )
				)
			));
		}
		else
		{
			return array_merge($this->parsed_query_string, 
				array(
					"post_type" => $post_name
			));
		}
	}
}