<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentController.php';

class MutasiStokDraft extends CI_Controller
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
			redirect(base_url('MainPage'));
		endif;

		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiStokDraft', 'M_MutasiStokDraft');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->MenuKode = "120004000";
	}

	public function MutasiStokDraftMenu()
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
			redirect(base_url('MainPage/DepoMenu'));
		}

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Checker'] = $this->M_MutasiStokDraft->Get_Checker();
		$data['Gudang'] = $this->M_MutasiStokDraft->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiStokDraft->Get_TipeTransaksi();
		$data['Principle'] = $this->M_MutasiStokDraft->Get_Principle();
		$data['getClientWms'] = $this->M_MutasiStokDraft->getClientWms();
		$data['Status'] = $this->M_MutasiStokDraft->Get_Status();
		$data['mutasi_pallet_draft_id'] = $this->M_MutasiStokDraft->Get_NewID();
		$data['act'] = "index";

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css'
		);

		$data['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/MutasiStokDraft/MutasiStokDraft', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiStokDraft/S_MutasiStokDraft', $data);
	}

	public function MutasiStokDraftForm()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiStokDraft', 'M_MutasiStokDraft');

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
			redirect(base_url('Main/MainDepo/MainDepoMenu'));
		}

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Checker'] = $this->M_MutasiStokDraft->Get_Checker();
		$data['Gudang'] = $this->M_MutasiStokDraft->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiStokDraft->Get_TipeTransaksi();
		$data['getClientWms'] = $this->M_MutasiStokDraft->getClientWms();
		$data['Principle'] = $this->M_MutasiStokDraft->Get_Principle();
		$data['Status'] = $this->M_MutasiStokDraft->Get_Status();
		$data['mutasi_pallet_draft_id'] = $this->M_MutasiStokDraft->Get_NewID();
		$data['act'] = "tambah";

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css'
		);

		$data['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/MutasiStokDraft/MutasiStokDraftForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiStokDraft/S_MutasiStokDraft', $data);
	}

	public function MutasiStokDraftBySKUBonusForm()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiStokDraft', 'M_MutasiStokDraft');

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
			redirect(base_url('Main/MainDepo/MainDepoMenu'));
		}

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Checker'] = $this->M_MutasiStokDraft->Get_Checker();
		$data['Gudang'] = $this->M_MutasiStokDraft->Get_Gudang_is_jual();
		$data['TipeTransaksi'] = $this->M_MutasiStokDraft->Get_TipeTransaksi();
		$data['getClientWms'] = $this->M_MutasiStokDraft->getClientWms();
		$data['Principle'] = $this->M_MutasiStokDraft->Get_Principle();
		$data['Status'] = $this->M_MutasiStokDraft->Get_Status();
		$data['TipeDeliveryOrder'] = $this->M_MutasiStokDraft->GetTipeDeliveryOrder();
		$data['mutasi_pallet_draft_id'] = $this->M_MutasiStokDraft->Get_NewID();
		$data['act'] = "tambah";

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css'
		);

		$data['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/MutasiStokDraft/MutasiStokDraftBySKUBonusForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiStokDraft/S_MutasiStokDraftBySKUBonus', $data);
	}

	public function getDataPrincipleByClientWmsId()
	{
		$this->load->model('WMS/M_MutasiStokDraft', 'M_MutasiStokDraft');
		$id = $this->input->post('id');
		$data = $this->M_MutasiStokDraft->getDataPrincipleByClientWmsId($id);
		echo json_encode($data);
	}

	public function MutasiStokDraftEdit()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiStokDraft', 'M_MutasiStokDraft');

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
			redirect(base_url('Main/MainDepo/MainDepoMenu'));
		}

		$tr_mutasi_stok_id = $this->input->get('tr_mutasi_stok_id');

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Checker'] = $this->M_MutasiStokDraft->Get_Checker();
		$data['Gudang'] = $this->M_MutasiStokDraft->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiStokDraft->Get_TipeTransaksi();
		$data['getClientWms'] = $this->M_MutasiStokDraft->getClientWms();
		$data['Status'] = $this->M_MutasiStokDraft->Get_Status();
		$data['tr_mutasi_stok_id'] = $tr_mutasi_stok_id;
		$data['Header'] = $this->M_MutasiStokDraft->Get_tr_mutasi_stok_header($tr_mutasi_stok_id);
		$data['Principle'] = $this->M_MutasiStokDraft->getDataPrincipleByClientWmsId($data['Header']->client_wms_id);
		$data['Detail'] = $this->M_MutasiStokDraft->Get_tr_mutasi_stok_detail($tr_mutasi_stok_id);
		$data['act'] = "edit";

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css'
		);

		$data['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/MutasiStokDraft/MutasiStokDraftEdit', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiStokDraft/S_MutasiStokDraft', $data);
	}

	public function MutasiStokDraftDetail()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiStokDraft', 'M_MutasiStokDraft');

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
			redirect(base_url('Main/MainDepo/MainDepoMenu'));
		}

		$tr_mutasi_stok_id = $this->input->get('tr_mutasi_stok_id');

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Checker'] = $this->M_MutasiStokDraft->Get_Checker();
		$data['Gudang'] = $this->M_MutasiStokDraft->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiStokDraft->Get_TipeTransaksi();
		$data['getClientWms'] = $this->M_MutasiStokDraft->getClientWms();
		$data['Status'] = $this->M_MutasiStokDraft->Get_Status();
		$data['tr_mutasi_stok_id'] = $tr_mutasi_stok_id;
		$data['Header'] = $this->M_MutasiStokDraft->Get_tr_mutasi_stok_header($tr_mutasi_stok_id);
		$data['Principle'] = $this->M_MutasiStokDraft->getDataPrincipleByClientWmsId($data['Header']->client_wms_id);
		$data['Detail'] = $this->M_MutasiStokDraft->Get_tr_mutasi_stok_detail($tr_mutasi_stok_id);
		$data['act'] = "detail";

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css'
		);

		$data['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/MutasiStokDraft/MutasiStokDraftDetail', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiStokDraft/S_MutasiStokDraft', $data);
	}

	public function GetPencarianMutasiStokDraftTable()
	{
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}
		// $tanggal = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tanggal'))));

		$tgl = explode(" - ", $this->input->post('tanggal'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$id = $this->input->post('id');
		$gudang_asal = $this->input->post('gudang_asal');
		$gudang_tujuan = $this->input->post('gudang_tujuan');
		$tipe = $this->input->post('tipe');
		$client_wms = $this->input->post('client_wms');
		$principle = $this->input->post('principle');
		$checker = $this->input->post('checker');
		$status = $this->input->post('status');

		$data['MutasiStokDraft'] = $this->M_MutasiStokDraft->Get_PencarianMutasiStokDraftTable($tgl1, $tgl2, $id, $gudang_asal, $gudang_tujuan, $tipe, $client_wms, $principle, $checker, $status);

		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetSKU()
	{

		$filter_sku_stock_id = array();
		$depo_detail_id = $this->input->post('gudang_asal');
		$principle = $this->input->post('principle');
		$perusahaan = $this->input->post('perusahaan');
		$arr_list_multi_sku = $this->input->post('arr_list_multi_sku');

		if (isset($arr_list_multi_sku)) {
			if (count($arr_list_multi_sku) > 0) {
				foreach ($arr_list_multi_sku as $value) {
					if ($value['sku_stock_id'] != "" && $value['sku_stock_id'] != null) {
						array_push($filter_sku_stock_id, "'" . $value['sku_stock_id'] . "'");
					}
				}
			}
		}

		$data['Gudang'] = $this->M_MutasiStokDraft->Get_GudangById($depo_detail_id);
		$data['Principle'] = $this->M_MutasiStokDraft->Get_PrincipleById($principle);
		$data['Perusahaan'] = $this->M_MutasiStokDraft->Get_PerusahaanById($perusahaan);

		$data['SKU'] = $this->M_MutasiStokDraft->Get_SKU($depo_detail_id, $principle, $perusahaan, $filter_sku_stock_id);

		echo json_encode($data);
	}

	public function GetSKUByDOBonus()
	{

		$filter_sku_stock_id = array();
		$tgl = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tgl'))));;
		$tipe_do = $this->input->post('tipe_do');
		$depo_detail_id = $this->input->post('gudang_asal');
		$principle = $this->input->post('principle');
		$perusahaan = $this->input->post('perusahaan');
		$arr_list_multi_sku = $this->input->post('arr_list_multi_sku');

		if (isset($arr_list_multi_sku)) {
			if (count($arr_list_multi_sku) > 0) {
				foreach ($arr_list_multi_sku as $value) {
					if ($value['sku_stock_id'] != "" && $value['sku_stock_id'] != null) {
						array_push($filter_sku_stock_id, "'" . $value['sku_stock_id'] . "'");
					}
				}
			}
		}

		$data['Gudang'] = $this->M_MutasiStokDraft->Get_GudangById($depo_detail_id);
		$data['Principle'] = $this->M_MutasiStokDraft->Get_PrincipleById($principle);
		$data['Perusahaan'] = $this->M_MutasiStokDraft->Get_PerusahaanById($perusahaan);

		$data['SKU'] = $this->M_MutasiStokDraft->Get_SKUByDOBonus($tgl, $tipe_do, $depo_detail_id, $principle, $perusahaan, $filter_sku_stock_id);

		echo json_encode($data);
	}

	public function Get_list_mutasi_stock_detail()
	{

		$filter_sku_stock_id = array();
		$tr_mutasi_stok_id = $this->input->post('tr_mutasi_stok_id');
		$arr_list_multi_sku = $this->input->post('arr_list_multi_sku');

		if (isset($arr_list_multi_sku)) {
			if (count($arr_list_multi_sku) > 0) {
				foreach ($arr_list_multi_sku as $value) {
					if ($value['sku_stock_id'] != "" && $value['sku_stock_id'] != null) {
						array_push($filter_sku_stock_id, "'" . $value['sku_stock_id'] . "'");
					}
				}

				$data = $this->M_MutasiStokDraft->Get_list_mutasi_stock_detail($tr_mutasi_stok_id, $filter_sku_stock_id, $arr_list_multi_sku);
			} else {
				$data = array();
			}
		} else {
			$data = array();
		}

		echo json_encode($data);
	}

	public function Get_pallet_by_sku_stock_id()
	{

		$sku_stock_id = $this->input->post('sku_stock_id');

		$data = $this->M_MutasiStokDraft->Get_pallet_by_sku_stock_id($sku_stock_id);

		echo json_encode($data);
	}

	public function GetPalletDetail()
	{
		$this->load->model('WMS/M_MutasiStokDraft', 'M_MutasiStokDraft');

		$pallet_id = $this->input->post('pallet_id');

		$data['PalletDetail'] = $this->M_MutasiStokDraft->Get_PalletDetail($pallet_id);

		echo json_encode($data);
	}

	public function insert_tr_mutasi_stok()
	{
		$tr_mutasi_stok_id = $this->M_Vrbl->Get_NewID();
		$tr_mutasi_stok_id = $tr_mutasi_stok_id[0]['NEW_ID'];

		// generate kode
		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_MTSS';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_MutasiStokDraft->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$tr_mutasi_stok_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$checker = explode(" || ", $this->input->post('tr_mutasi_stok_nama_checker'));

		$approvalParam = "APPRV_MUTASISTOCK_01";

		$client_wms_id = $this->input->post('client_wms_id');
		$principle_id = $this->input->post('principle_id');
		$tr_mutasi_stok_tanggal = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tr_mutasi_stok_tanggal'))));
		$tr_mutasi_stok_keterangan = $this->input->post('tr_mutasi_stok_keterangan');
		$tr_mutasi_stok_status = $this->input->post('tr_mutasi_stok_status');
		$depo_id_asal = $depo_id;
		$depo_detail_id_asal = $this->input->post('depo_detail_id_asal');
		$tr_mutasi_stok_tgl_create = $this->input->post('tr_mutasi_stok_tgl_create');
		$tr_mutasi_stok_who_create = $this->input->post('tr_mutasi_stok_who_create');
		$karyawan_id = $checker[0];
		$tr_mutasi_stok_nama_checker = $checker[1];
		$tr_mutasi_stok_tgl_update = $this->input->post('tr_mutasi_stok_tgl_update');
		$tr_mutasi_stokt_who_update = $this->input->post('tr_mutasi_stokt_who_update');
		$detail = $this->input->post('detail');

		$lastDokumenTutupGudang = cekDokumenTutupGudang((object) [
			'tgl_dokumen' => $tr_mutasi_stok_tanggal
		]);

		if ($lastDokumenTutupGudang['status'] == 500) {
			echo json_encode(500);
			return false;
		}

		$this->db->trans_begin();

		$this->M_MutasiStokDraft->insert_tr_mutasi_stok($tr_mutasi_stok_id, $client_wms_id, $principle_id, $tr_mutasi_stok_kode, $tr_mutasi_stok_tanggal, $tr_mutasi_stok_keterangan, $tr_mutasi_stok_status, $depo_id_asal, $tr_mutasi_stok_tgl_create, $tr_mutasi_stok_who_create, $tr_mutasi_stok_nama_checker, $tr_mutasi_stok_tgl_update, $tr_mutasi_stokt_who_update);

		foreach ($detail as $key => $value) {
			$tr_mutasi_stok_detail_id = $this->M_Vrbl->Get_NewID();
			$tr_mutasi_stok_detail_id = $tr_mutasi_stok_detail_id[0]['NEW_ID'];

			$pallet_id_asal = $value['pallet_id_asal'];
			$sku_stock_id = $value['sku_stock_id'];
			$sku_id = $value['sku_id'];
			$sku_stock_expired_date = date('Y-m-d', strtotime(str_replace("/", "-", $value['sku_stock_expired_date'])));
			$sku_stock_qty = $value['sku_stock_qty'];

			$this->M_MutasiStokDraft->insert_tr_mutasi_stok_detail($tr_mutasi_stok_detail_id, $tr_mutasi_stok_id, $depo_detail_id_asal, $pallet_id_asal, $sku_stock_id, $sku_id, $sku_stock_expired_date, $sku_stock_qty);
		}

		if ($tr_mutasi_stok_status == "In Progress Approval") {
			$this->M_MutasiStokDraft->Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_mutasi_stok_id, $tr_mutasi_stok_kode, 0, 0);
		}

		// $this->db->trans_rollback();
		// echo json_encode(0);
		// die;

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function update_tr_mutasi_stok()
	{
		$checker = explode(" || ", $this->input->post('tr_mutasi_stok_nama_checker'));
		$depo_id = $this->session->userdata('depo_id');

		$approvalParam = "APPRV_MUTASISTOCK_01";

		$tr_mutasi_stok_id = $this->input->post('tr_mutasi_stok_id');
		$tr_mutasi_stok_kode = $this->input->post('tr_mutasi_stok_kode');
		$client_wms_id = $this->input->post('client_wms_id');
		$principle_id = $this->input->post('principle_id');
		$tr_mutasi_stok_tanggal = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tr_mutasi_stok_tanggal'))));
		$tr_mutasi_stok_keterangan = $this->input->post('tr_mutasi_stok_keterangan');
		$tr_mutasi_stok_status = $this->input->post('tr_mutasi_stok_status');
		$depo_id_asal = $depo_id;
		$depo_detail_id_asal = $this->input->post('depo_detail_id_asal');
		$tr_mutasi_stok_tgl_create = $this->input->post('tr_mutasi_stok_tgl_create');
		$tr_mutasi_stok_who_create = $this->input->post('tr_mutasi_stok_who_create');
		$karyawan_id = $checker[0];
		$tr_mutasi_stok_nama_checker = $checker[1];
		$tr_mutasi_stok_tgl_update = $this->input->post('tr_mutasi_stok_tgl_update');
		$tr_mutasi_stokt_who_update = $this->input->post('tr_mutasi_stokt_who_update');
		$detail = $this->input->post('detail');

		$lastDokumenTutupGudang = cekDokumenTutupGudang((object) [
			'tgl_dokumen' => $tr_mutasi_stok_tanggal
		]);

		if ($lastDokumenTutupGudang['status'] == 500) {
			echo json_encode(500);
			return false;
		}

		$this->db->trans_begin();

		$this->M_MutasiStokDraft->update_tr_mutasi_stok($tr_mutasi_stok_id, $client_wms_id, $principle_id, $tr_mutasi_stok_kode, $tr_mutasi_stok_tanggal, $tr_mutasi_stok_keterangan, $tr_mutasi_stok_status, $depo_id_asal, $tr_mutasi_stok_tgl_create, $tr_mutasi_stok_who_create, $tr_mutasi_stok_nama_checker, $tr_mutasi_stok_tgl_update, $tr_mutasi_stokt_who_update);

		$this->M_MutasiStokDraft->delete_tr_mutasi_stok_detail($tr_mutasi_stok_id);

		foreach ($detail as $key => $value) {
			$tr_mutasi_stok_detail_id = $this->M_Vrbl->Get_NewID();
			$tr_mutasi_stok_detail_id = $tr_mutasi_stok_detail_id[0]['NEW_ID'];

			$pallet_id_asal = $value['pallet_id_asal'];
			$sku_stock_id = $value['sku_stock_id'];
			$sku_id = $value['sku_id'];
			$sku_stock_expired_date = date('Y-m-d', strtotime(str_replace("/", "-", $value['sku_stock_expired_date'])));
			$sku_stock_qty = $value['sku_stock_qty'];

			$this->M_MutasiStokDraft->insert_tr_mutasi_stok_detail($tr_mutasi_stok_detail_id, $tr_mutasi_stok_id, $depo_detail_id_asal, $pallet_id_asal, $sku_stock_id, $sku_id, $sku_stock_expired_date, $sku_stock_qty);
		}

		if ($tr_mutasi_stok_status == "In Progress Approval") {
			$this->M_MutasiStokDraft->Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_mutasi_stok_id, $tr_mutasi_stok_kode, 0, 0);
		}

		// $this->db->trans_rollback();
		// echo json_encode(0);
		// die;

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function setPuhserInt()
	{

		senderPusher([
			'message' => 'insert pengajuan approval mutasi pallet draft'
		]);
	}

	public function GetCheckerPrinciple()
	{
		$this->load->model('WMS/M_MutasiStokDraft', 'M_MutasiStokDraft');

		$principle = $this->input->post('principle');
		$client_wms_id = $this->input->post('client_wms_id');

		header('Content-Type: application/json');

		$result = $this->M_MutasiStokDraft->Get_CheckerPrinciple($principle, $client_wms_id);

		echo json_encode($result);
	}

	public function Get_list_sku_bonus_tidak_ada_di_gudang_bonus()
	{
		$tgl = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tgl'))));;
		$tipe_do = $this->input->post('tipe_do');
		$principle = $this->input->post('principle');
		$perusahaan = $this->input->post('perusahaan');

		$data = $this->M_MutasiStokDraft->Get_list_sku_bonus_tidak_ada_di_gudang_bonus($tgl, $tipe_do, $principle, $perusahaan);

		echo json_encode($data);
	}

	public function Get_detail_sku_bonus_tidak_ada_di_gudang_bonus()
	{
		$tgl = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tgl'))));;
		$tipe_do = $this->input->post('tipe_do');
		$principle = $this->input->post('principle');
		$perusahaan = $this->input->post('perusahaan');
		$sku_id = $this->input->post('sku_id');

		$data = $this->M_MutasiStokDraft->Get_detail_sku_bonus_tidak_ada_di_gudang_bonus($tgl, $tipe_do, $principle, $perusahaan, $sku_id);

		echo json_encode($data);
	}

	public function Get_detail_gudang_asal_by_sku()
	{
		$tgl = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tgl'))));;
		$tipe_do = $this->input->post('tipe_do');
		$principle = $this->input->post('principle');
		$perusahaan = $this->input->post('perusahaan');
		$sku_id = $this->input->post('sku_id');

		$data = $this->M_MutasiStokDraft->Get_detail_gudang_asal_by_sku($tgl, $tipe_do, $principle, $perusahaan, $sku_id);

		echo json_encode($data);
	}
}
