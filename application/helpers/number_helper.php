<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('format_idr')) {
	function format_idr($angka)
	{
		$rupiah = "Rp." . number_format($angka, 2, ',', '.');
		return $rupiah;
	}
}

if (!function_exists('formatReplaceArray')) {
	function formatReplaceArray($data)
	{
		$string = "";
		if (!empty($data)) {
			foreach ($data as $key => $value) {
				$string .= $value . ", ";
			}

			return substr($string, 0, -2);
		} else {
			return $string;
		}
	}
}
