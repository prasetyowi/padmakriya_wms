<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getViewButton')) {

	function getViewButton($controller, $method, $id) 
	{
		$url = site_url('WMS/Distribusi/').$controller."/".$method."/".$id;
		return '<a href="'.$url.'" title="View"><span class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></span> </a>';
	}
}

if (!function_exists('getUpdateButton')) {

	function getUpdateButton($controller, $method, $id) 
	{
		$url = site_url('WMS/Distribusi/').$controller."/".$method."/".$id;
		return '<a href="'.$url.'" title="Edit"><span class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></span> </a>';
	}
}

if (!function_exists('getLookupPickButton')) {

	function getLookupPickButton($buttonClass, $arrOfData = [])
	{
		if (!empty($arrOfData)) {
			$stringData = "";
			foreach($arrOfData as $key => $data) {
				$stringData .= 'data-'.$key.'="'.$data.'" ';
			}
		}
		return '<button data-dismiss="modal" '.$stringData.' type="button" class="btn btn-info '.$buttonClass.'" title="Pilih"><i class="fa fa-arrow-down"></i></button>';
	}
}
