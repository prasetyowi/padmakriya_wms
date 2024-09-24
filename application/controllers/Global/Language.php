<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Language extends CI_Controller {

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
	private $MenuKode;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('Global/M_Bahasa','M_Bahasa');
	}

	public function GetLanguageByFlag()
	{		
		$flag_kode = $this->input->post('flag_kode');
		
		$this->session->set_userdata('Bahasa', $flag_kode );
		
		$data['BahasaList'] = $this->M_Bahasa->Getbahasa( $flag_kode );
		
		echo json_encode( $data );
	}
	
	public function GetLanguage()
	{		
		$data['LanguageMenu'] = $this->M_Bahasa->Getflag();
		
		echo json_encode( $data );
	}
	
	public function GetLanguageByKode()
	{		
		$bahasa_kode 	= $this->input->post('bahasa_kode');
		$flag 			= $this->session->userdata('Bahasa');
		
		$data = $this->M_Bahasa->Getbahasa_by_bahasa_kode( $bahasa_kode, $flag );
		$data = $data[0]['bahasa'];
		
		echo json_encode( $data );
	}
	
	public function SelectLanguage( $bahasa )
	{
		$this->load->model('HakAkses/M_Pengguna','M_Pengguna');
		
		$this->load->library('user_agent');
		
		$this->session->set_userdata('Bahasa', $bahasa );
		
		$pengguna_id = $this->session->userdata('pengguna_id');
		
		$res = $this->M_Bahasa->Updatedefault_language( $bahasa );
		
		$res2 = $this->M_Pengguna->Updatepengguna_pengguna_default_bahasa( $pengguna_id, $bahasa );
		
		$this->session->set_userdata('BahasaList', GetBahasa() );
					
		redirect( $this->agent->referrer() );
		
		//redirect( current_url() );
	}
	
	public function RedirectToSamePage()
	{
		$this->load->library('user_agent');

		redirect( $this->agent->referrer() );
		
		//redirect( current_url() );
	}
}
