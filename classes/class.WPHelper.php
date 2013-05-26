<?php
class WPHelper 
{
	public static $default_post_types = array("post", "page", "revision", "attachment", "nav_menu_item");
	public static function get_post_types($type = 'default')
	{
		$data = NULL;
		foreach (get_post_types() as $key => $value) 
		{
			if(in_array($value, self::$default_post_types))
			{
				$data['default'][] = $value;
			}
			else 
			{
				$data['custom'][] = $value;
			}
		}
		return ($type == 'default') ? $data['default'] : $data['custom'];
	}
}