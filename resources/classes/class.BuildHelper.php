<?php
class BuildHelper {
	
	protected $resources = NULL;
	protected $resource = NULL;
	protected $output = NULL;

	public function __construct($data)
	{
		(is_array($data)) ? $this->resources = $data : $this->resource = $data;
	}

	public function outputJSON() 
	{
		// if we dont intent to change the output value manually
		if($this->output == null)
		{
			$output =  ($this->resource == NULL) ?  $this->resources : $this->resource;
		}
		else
		{
			$output = $this->output;
		}
		
		echo json_encode($output,  JSON_HEX_QUOT | JSON_HEX_TAG);
	}

	public function override_output($init_data) {
		$this->output = $init_data;
	}

	protected function forEachResources($function_name) 
	{
		foreach ($this->resources as $resource) 
		{
			$this->{$function_name}($resource);
		}
	}

	protected function implementProtectedFunction($function_name)
	{
		($this->resource == NULL) ? $this->forEachResources($function_name) : 
			$this->{$function_name}($this->resource);
	}

/* UTITLTY HELPERS */
	protected function noWPGalleryShortCodes($content) 
	{
		return preg_replace('#\[\w+\s+\w+=\"\w+,?\w+\"]#','',$content);
	}
	protected function getWPGalleryShortCodes($content) 
	{
		preg_match_all('#\[\w+\s+\w+=\"\w+,?\w+\"]#',$content,$matches);
		return $matches[0];
	}
	protected function getImageSrc($img_element) {
		preg_match( '/src="([^"]*)"/i',  $img_element , $matches );
		return $matches[1];
	}

	protected function getAttachments($post_id) {
		return get_posts(array("post_type" => "attachment", "post_parent" => $post_id));
	}
/* END UTITLTY HELPERS */
}