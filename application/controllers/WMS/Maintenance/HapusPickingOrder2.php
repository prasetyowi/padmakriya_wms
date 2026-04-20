<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require_once APPPATH . 'core/ParentController.php';
require_once APPPATH . 'core/MenuController.php';
class HapusPickingOrder2 extends MenuController
{
	// private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		if ($this->session->has_userdata('pengguna_id') == 0) :
			redirect(base_url('MainPage'));
		endif;

		$this->load->model('WMS/M_HapusPickingOrder2', 'M_HapusPickingOrder2');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('M_ClientWms');
		$this->load->model('M_Depo');
		$this->load->model('M_Principle');
		$this->load->model('M_Karyawan');
		$this->load->model('M_Menu');

		// $this->MenuKode = "199100307";
	}

	public function HapusPickingOrder2Menu()
	{
		$data = array();
		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		if (!$this->session->has_userdata('pengguna_id')) {
			redirect(base_url('MainPage'));
		}

		if (!$this->session->has_userdata('depo_id')) {
			redirect(base_url('Main/MainDepo/DepoMenu'));
		}

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css'
		);

		$query['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Menu_Name($this->MenuKode);
		$query['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));


		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/Maintenance/HapusPickingOrder2/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/Maintenance/HapusPickingOrder2/script', $data);
	}

	public function getKodeAutoComplete()
	{
		$valueParams = $this->input->get('params');
		$result = $this->M_HapusPickingOrder2->getKodeAutoComplete($valueParams);
		echo json_encode($result);
	}

	public function GetDetailPengeluaranBarangManualMenu()
	{
		$picking_order_kode = $this->input->post('picking_order_kode');

		$data['PengeluaranBarangManual'] = $this->M_HapusPickingOrder2->Get_DetailPengeluaranBarangManual($picking_order_kode);

		echo json_encode($data);
	}

	public function GetDetailPengeluaranBarangManualMixMenu()
	{
		$picking_order_kode = $this->input->post('picking_order_kode');

		$data['PengeluaranBarangManual'] = $this->M_HapusPickingOrder2->Get_DetailPengeluaranBarangManualMix($picking_order_kode);

		echo json_encode($data);
	}

	public function deletePickingOrder()
	{
		$picking_order_kode = $this->input->post('picking_order_kode');

		$data = $this->M_HapusPickingOrder2->deletePickingOrder($picking_order_kode);

		echo $data;
	}
}
