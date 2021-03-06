<?php
require('class.BuildHelper.php');

class WPPostBuilder extends BuildHelper {
	
/* INVOKE INTERFACE IMPLEMENTATION FUNCTION */
	
	public function convert_wp_gallery_shortcodes()
	{
		$this->implementProtectedFunction("_".__FUNCTION__);
	}

	public function build_custom_fields()
	{
		$this->implementProtectedFunction("_".__FUNCTION__);
	}

	public function build_post_thumbnails() 
	{
		$this->implementProtectedFunction("_".__FUNCTION__);
	}

	public function build_post_attachments() 
	{
		$this->implementProtectedFunction("_".__FUNCTION__);
	}

	public function build_featured_image() 
	{
		$this->implementProtectedFunction("_".__FUNCTION__);
	}

	public function build_taxonomies() 
	{
		$this->implementProtectedFunction("_".__FUNCTION__);
	}


	public function build_terms() 
	{
		$this->implementProtectedFunction("_".__FUNCTION__);
	}

	public function build() {
		$this->convert_wp_gallery_shortcodes();
		$this->build_custom_fields();
		$this->build_featured_image();
		$this->build_post_thumbnails();
		$this->build_post_attachments();
		$this->build_terms();
	}


/* END INVOKE INTERFACE IMPLEMENTATION FUNCTION */


/* THE REAL INTERFACE IMPLEMENTATION */

	protected function _convert_wp_gallery_shortcodes($post) 
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

	protected function _build_custom_fields($post) 
	{
		$post->{"custom_fields"} = get_post_custom($post->ID);
	}

	protected function _build_post_thumbnails($post) 
	{
		$post->{"post_thumbnails"} = array();
		
		foreach (get_intermediate_image_sizes() as $size_string) 
		{
			$post->{"post_thumbnails"}[$size_string] = $this->getImageSrc(get_the_post_thumbnail($post->ID, $size_string));
		}
	}

	protected function _build_post_attachments($post) 
	{
		$post->{'attachments'} = $this->getAttachments($post->ID);	
	}

	protected function _build_featured_image($post) 
	{
		$post->{"featured_image"} = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), $value );
	}

	protected function _build_terms($post) 
	{
		$data = array();
		foreach (get_taxonomies('','names')  as $taxonomy ) 
		{
			$term_array =  wp_get_post_terms($post->ID, $taxonomy);
			if(count($term_array) > 0)
				$data[] = $term_array;
		}

		$post->{"terms"} = $data;
	}

/* END THE REAL INTERFACE IMPLEMENTATION */


}