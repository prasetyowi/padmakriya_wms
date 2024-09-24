<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class PersiapanMutasiAntarUnit extends ParentController
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
	private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		if ($this->session->has_userdata('pengguna_id') == 0) :
			redirect(base_url('MainPage/Login'));
		endif;

		$this->MenuKode = "120001100";
		// $this->MenuKode = "103002000";
		$this->load->model([array('WMS/Transaksi/MutasiAntarUnit/M_PersiapanMutasiAntarUnit', 'M_PersiapanMutasiAntarUnit'), 'M_Function', 'M_MenuAccess', 'M_Menu']);
		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation', 'pdfgenerator']);
	}

	public function PersiapanMutasiAntarUnitMenu()
	{
		$data = array();

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

		$data['ekspedisis'] = $this->M_PersiapanMutasiAntarUnit->getDataEkspedisis();

		$data['drivers'] = $this->M_PersiapanMutasiAntarUnit->getDataDrivers();

		$data['vehicles'] = $this->M_PersiapanMutasiAntarUnit->getDataVehicles();

		$data['pages'] = 'index';


		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/Page/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/Component/Script/S_PersiapanMutasiAntarUnit', $data);
	}

	public function form()
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

		$data['depos'] = $this->M_PersiapanMutasiAntarUnit->getDataDepos();

		$data['warehouses'] = $this->M_PersiapanMutasiAntarUnit->getDataWarehouses();

		$data['ekspedisis'] = $this->M_PersiapanMutasiAntarUnit->getDataEkspedisis();

		$data['drivers'] = $this->M_PersiapanMutasiAntarUnit->getDataDrivers();

		$data['vehicles'] = $this->M_PersiapanMutasiAntarUnit->getDataVehicles();

		$data['pages'] = 'form';

		$query['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			// Get_Assets_Url() . '/node_modules/html5-qrcode/html5-qrcode.min.js',
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');


		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/Page/form', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/Component/Script/S_PersiapanMutasiAntarUnit', $data);
	}

	public function getKodeAutoComplete()
	{
		$valueParams = $this->input->get('params');
		echo json_encode($this->M_PersiapanMutasiAntarUnit->getKodeAutoComplete($valueParams));
	}

	public function getDataByFilter()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_PersiapanMutasiAntarUnit->getDataByFilter($dataPost));
	}

	public function handlerDeleteDataMutasiDepo()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_PersiapanMutasiAntarUnit->handlerDeleteDataMutasiDepo($dataPost));
	}

	public function getParamsForSkuData()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_PersiapanMutasiAntarUnit->getParamsForSkuData($dataPost));
	}

	public function getDataSKUByParams()
	{
		// header('Content-Type: application/json');
		// $dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_PersiapanMutasiAntarUnit->getDataSKUByParams());
	}

	public function checkScan()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_PersiapanMutasiAntarUnit->checkScan($dataPost));
	}

	public function saveData()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_PersiapanMutasiAntarUnit->saveData($dataPost));
	}

	public function getDataMutasiDepo()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_PersiapanMutasiAntarUnit->getDataMutasiDepo($dataPost));
	}

	public function cetak($trMutasiDepo, $type, $trMutasiDepoDetail)
	{
		$datas = $this->M_PersiapanMutasiAntarUnit->getDataCetak($trMutasiDepo, $type, $trMutasiDepoDetail);

		$this->load->view('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/Page/Print/index', $datas);
	}
}
