<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('setFlash')) {

	function setFlash($type, $message) 
	{
		$ci = &get_instance();
		
		$arrOfFlash = [];
		if ($ci->session->has_userdata('flashMessage')) {
			$arrOfFlash = $ci->session->userdata("flashMessage");
		}
		$arrOfFlash[] = [
			'type' => $type,
			'message' => $message
		];
		$ci->session->set_userdata("flashMessage", $arrOfFlash);
	}
}

if (!function_exists('getAllFlashes')) {

	function getAllFlashes() 
	{
		$ci = &get_instance();
		
		if ($ci->session->has_userdata("flashMessage")) {
			$messages = $ci->session->userdata("flashMessage");
			$ci->session->unset_userdata("flashMessage");
			return $messages;
		}
		return [];
	}
}
