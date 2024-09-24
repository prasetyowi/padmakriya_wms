<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MainSession extends CI_Controller
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

	public function MainSessionMenu()
	{
		$this->session->sess_destroy();
		
		$karyawan_id 		= $this->input->post('karyawan_id');
		$karyawan_nama 		= $this->input->post('karyawan_nama');
		$karyawan_foto 		= $this->input->post('karyawan_foto');
		$pengguna_id 		= $this->input->post('pengguna_id');
		$pengguna_grup_id 	= $this->input->post('pengguna_grup_id');
		$pengguna_username 	= $this->input->post('pengguna_username');
		$aplikasi_url	 	= $this->input->post('Mode');
		$Bahasa	 	= $this->input->post('Bahasa');
		
		$this->session->set_userdata('karyawan_id', $karyawan_id ); 
		$this->session->set_userdata('karyawan_nama', $karyawan_nama ); 
		$this->session->set_userdata('karyawan_foto', $karyawan_foto ); 
		$this->session->set_userdata('pengguna_id', $pengguna_id ); 
		$this->session->set_userdata('pengguna_grup_id', $pengguna_grup_id ); 
		$this->session->set_userdata('pengguna_username', $pengguna_username ); 
		$this->session->set_userdata('Bahasa', $Bahasa ); 
		
		echo 1;
	}
	
	public function DestroySessionMenu()
	{
		$this->session->sess_destroy();
		
		echo 1;
	}
}