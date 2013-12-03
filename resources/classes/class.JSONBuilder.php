<?php
interface IPostBuilder
{
    public function convert_wp_gallery_shortcodes();
    public function build_custom_fields();
    public function build_post_thumbnails();
    public function build_post_attachments();
    public function build_featured_image();
    public function outputJSON();
}
class BuildHelper {
	public function __construct() {

	}
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
}
class JSONBuilder extends BuildHelper implements IPostBuilder  {
	
	var $posts = NULL;
	var $post = NULL;
	var $output = NULL;

	public function __construct($data)
	{
		(is_array($data)) ? $this->posts = $data : $this->post = $data;
	}

	private function forEachPosts($function_name) 
	{
		foreach ($this->posts as $post) 
		{
			$this->{$function_name}($post);
		}
	}
	private function implementPrivateFunction($function_name)
	{
		($this->post == NULL) ? $this->forEachPosts($function_name) : 
			$this->{$function_name}($this->post);
	}

/* INVOKE INTERFACE IMPLEMENTATION FUNCTION */
	
	public function outputJSON() 
	{
		$output =  ($this->post == NULL) ?  $this->posts : $this->post;
		echo json_encode($output,  JSON_HEX_QUOT | JSON_HEX_TAG);
	}

	public function convert_wp_gallery_shortcodes()
	{
		$this->implementPrivateFunction("_".__FUNCTION__);
	}

	public function build_custom_fields()
	{
		$this->implementPrivateFunction("_".__FUNCTION__);
	}

	public function build_post_thumbnails() 
	{
		$this->implementPrivateFunction("_".__FUNCTION__);
	}

	public function build_post_attachments() 
	{
		$this->implementPrivateFunction("_".__FUNCTION__);
	}

	public function build_featured_image() 
	{
		$this->implementPrivateFunction("_".__FUNCTION__);
	}

/* END INVOKE INTERFACE IMPLEMENTATION FUNCTION */


/* THE REAL INTERFACE IMPLEMENTATION */

	private function _convert_wp_gallery_shortcodes($post) 
	{
		$shortcodes = $this->getWPGalleryShortCodes($post->post_content);
		$post_content = $this->noWPGalleryShortCodes($post->post_content);
		if (count($shortcodes) > 0) 
		{
			foreach ($shortcodes as $shortcode) {
				$post_content .= do_shortcode($shortcode);
			}
			$post->post_content = $post_content;
		}
	}

	private function _build_custom_fields($post) 
	{
		$post->{"custom_fields"} = get_post_custom($post->ID);
	}

	private function _build_post_thumbnails($post) 
	{
		$post->{"post_thumbnails"} = array();
		
		foreach (get_intermediate_image_sizes() as $size_string) 
		{
			$post->{"post_thumbnails"}[$size_string] = $this->getImageSrc(get_the_post_thumbnail($post->ID, $size_string));
		}
	}

	private function _build_post_attachments($post) 
	{
		$post->{'attachments'} = $this->getAttachments($post->ID);	
	}

	private function _build_featured_image($post) 
	{
		$post->{"featured_image"} = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), $value );
	}

/* END THE REAL INTERFACE IMPLEMENTATION */


}