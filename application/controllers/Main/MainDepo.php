<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentLoginController.php';

class MainDepo extends CI_Controller
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

	public function MainDepoMenu()
	{
		$this->load->model('M_Menu');
		$this->load->model('M_Depo');
		$this->load->model('M_Vrbl');
	
		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['sidemenu'] = $this->M_Menu->GetMenu_Sidebar('', $this->session->userdata('pengguna_grup_id'));
	
		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',
			Get_Assets_Url() . 'vendors/jquery-ui/jquery-ui.min.css',
			Get_Assets_Url() . 'assets/css/textimage.css'
		);

		$query['js_files'] 	= array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'vendors/jquery-ui/jquery-ui.min.js'

		);

		$this->session->set_userdata('depo_id', 0);
		$this->session->set_userdata('depo_kode', 0);
		$this->session->set_userdata('depo_nama', 0);
		$this->session->set_userdata('unit_mandiri_id', 0);

		$data = $this->M_Vrbl->Get_Vrbl_By_Param('BACKEND_URL');
		$this->session->set_userdata('backend_url', $data[0]['vrbl_ket_patch']);
		
		$inboxurl = $this->M_Vrbl->Get_Vrbl_By_Param('INBOX_URL');
		$this->session->set_userdata('inbox_url', $data[0]['vrbl_ket_patch']);
		
		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('MainPage/DepoMenu', $query);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('MainPage/S_DepoMenu', $query);
	}

	public function GetDepoMenu()
	{
		$this->load->model('HakAkses/M_Pengguna_Depo', 'M_Pengguna_Depo');

		$pengguna_id = $this->session->userdata('pengguna_id');

		$data = array();
		
		$data['Depo'] = $this->M_Pengguna_Depo->Getpengguna_depo_by_pengguna_id($pengguna_id);

		echo json_encode($data);
	}

	public function VerifyDepo()
	{
		$this->load->library('session');

		if ($this->input->post('hdKey') != 'HOPE')
			redirect(base_url('Main/MainDepo/MainDepoMenu'));

		$this->session->set_userdata('depo_id', 	$this->input->post('depo_id'));
		$this->session->set_userdata('depo_kode', 	$this->input->post('depo_kode'));
		$this->session->set_userdata('depo_nama', 	$this->input->post('depo_nama'));
		$this->session->set_userdata('unit_mandiri_id', 	$this->input->post('unit_mandiri_id'));
		

		$Mode = $this->session->userdata('Mode');

		redirect( base_url( $Mode . '/MainDashboard/MainDashboardMenu'));
	}
}