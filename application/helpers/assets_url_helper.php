<?php

function Get_Assets_Url()
{
	$CI = get_instance();
	
	$CI->load->model('M_Vrbl');

	$query = $CI->M_Vrbl->Get_Vrbl_Assets_Url();
	
    return $query[0]['vrbl_ket_patch'];
}

function Get_Assets_Upload_Url()
{
	$CI = get_instance();
	
	$CI->load->model('M_Vrbl');

	$query = $CI->M_Vrbl->Get_Vrbl_Assets_Upload_Url();
	
    return $query[0]['vrbl_ket_patch'];
}