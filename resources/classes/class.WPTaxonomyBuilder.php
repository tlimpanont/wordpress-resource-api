<?php 

class WPTaxonomyBuilder extends BuildHelper {
	
	var $taxonomies = array();

	public function __construct($taxonomies) {
		parent::__construct($taxonomies);
	}

	public function build_taxonomies() 
	{
		$this->implementProtectedFunction("_".__FUNCTION__);
	}

	public function build() {
		$this->build_taxonomies();
	}

	protected function _build_taxonomies($taxonomy) 
	{   
		   foreach (get_post_types() as $post_type) 
		   {
		   		$post_counter = 0;
		   		$post = array();
		   		
		   		
		   		foreach (get_terms($taxonomy->name, 'orderby=count&hide_empty=0') as $term) 
		   		{
		   			$args=array(
						'post_type'=> $post_type,
						'orderby'=>'count',
						'posts_per_page'=>-1,
						'tax_query' => array(
							array("taxonomy" => $taxonomy->name, "field" => "slug",  "terms" => $term->slug)
						)
					);
					$post_counter += count(get_posts($args));
					$post = array_merge(get_posts($args), $post);
		   		}
		   }

		   	// serrach for terms
			array_push($this->output, array('count_posts' =>  $post_counter, 'taxonomy_name' => $taxonomy->name, "related_posts" => $post));
	}
}