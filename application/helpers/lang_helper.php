<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function GetBahasa()
{ 
	$CI = get_instance();
	
	$bahasa = $CI->session->userdata('Bahasa');

	$CI->load->model('Global/M_Bahasa','M_Bahasa');

	$bahasa = $CI->M_Bahasa->Getbahasa( $bahasa );

	return $bahasa;
}

