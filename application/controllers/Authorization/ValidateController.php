<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class ValidateController extends RestController
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
	}
	
	public function ValidatePengguna_post()
	{
		$this->load->model('HakAkses/M_Pengguna','M_Pengguna');
		$this->load->model('HakAkses/M_Pengguna_Grup','M_Pengguna_Grup');
		
		$pengguna_grup_id 	= $this->input->post('pengguna_grup_id');
		$pengguna_id 		= $this->input->post('pengguna_id');
		$karyawan_id 		= $this->input->post('karyawan_id');
		
		$iserror = array();
		
		$res = $this->M_Pengguna->Checkpengguna_exist_by_pengguna_id( $pengguna_id );
		if( $res == 0 )	{	array_push( $iserror, array("error" => 2 ) );	}
		
		$res = $this->M_Pengguna->Checkpengguna_exist_by_karyawan_id( $karyawan_id );
		if( $res == 0 )	{	array_push( $iserror, array("error" => 3 ) );	}
		
		$res = $this->M_Pengguna_Grup->Checkpengguna_grup_exist_by_pengguna_grup_id( $pengguna_grup_id );
		if( $res == 0 )	{	array_push( $iserror, array("error" => 4 ) );	}
		
		echo json_encode( $iserror );
	}

	public function ValidateMenu_post()
	{
		$this->load->model('M_Vrbl');
		$this->load->model('M_Menu');
				
		$menu_link 			= $this->input->post('menu_link');
		$menu_application 	= $this->input->post('menu_application');
		$pengguna_grup_id 	= $this->input->post('pengguna_grup_id');
		$pengguna_username 	= $this->input->post('pengguna_username');
		$pengguna_id 		= $this->input->post('pengguna_id');
		$karyawan_id 		= $this->input->post('karyawan_id');
		$karyawan_nama 		= $this->input->post('karyawan_nama');
		$karyawan_foto 		= $this->input->post('karyawan_foto');

		$data = $this->M_Menu->GetMenu_By_Menu_Link( $menu_link, $menu_application, $pengguna_grup_id );

		if( $data != 0 )
		{
			$dataurl = $this->M_Vrbl->Get_Vrbl_By_Param('BACKEND_URL');
			$inboxurl = $this->M_Vrbl->Get_Vrbl_By_Param('INBOX_URL');
			
			$this->session->set_userdata('inbox_url', $data[0]['vrbl_ket_patch'] );
			$this->session->set_userdata('backend_url', $data[0]['vrbl_ket_patch'] );
			$this->session->set_userdata('pengguna_grup_id', $pengguna_grup_id );
			$this->session->set_userdata('pengguna_id', $pengguna_id );
			$this->session->set_userdata('pengguna_username', $pengguna_username );
			$this->session->set_userdata('karyawan_id', $karyawan_nama );
			$this->session->set_userdata('karyawan_nama', $karyawan_nama );
			$this->session->set_userdata('karyawan_foto', $karyawan_foto );
		}
		
		echo json_encode( $data );
	}
	
	public function Test_post()
	{
		echo 'Success';
	}
}