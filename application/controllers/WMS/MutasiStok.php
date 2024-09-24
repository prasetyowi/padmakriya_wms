<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentController.php';

class MutasiStok extends CI_Controller
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
		$this->load->model('WMS/M_MutasiStok', 'M_MutasiStok');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->MenuKode = "120005000";
	}

	public function MutasiStokMenu()
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
		$data['Checker'] = $this->M_MutasiStok->Get_Checker();
		$data['Gudang'] = $this->M_MutasiStok->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiStok->Get_TipeTransaksi();
		$data['Principle'] = $this->M_MutasiStok->Get_Principle();
		$data['getClientWms'] = $this->M_MutasiStok->getClientWms();
		$data['Status'] = $this->M_MutasiStok->Get_Status();
		$data['mutasi_pallet_draft_id'] = $this->M_MutasiStok->Get_NewID();

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
		$this->load->view('WMS/MutasiStok/MutasiStok', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiStok/S_MutasiStok', $data);
	}

	public function MutasiStokForm()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiStok', 'M_MutasiStok');

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
		$data['Checker'] = $this->M_MutasiStok->Get_Checker();
		$data['Gudang'] = $this->M_MutasiStok->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiStok->Get_TipeTransaksi();
		$data['getClientWms'] = $this->M_MutasiStok->getClientWms();
		$data['Principle'] = $this->M_MutasiStok->Get_Principle();
		$data['Status'] = $this->M_MutasiStok->Get_Status();
		$data['mutasi_pallet_draft_id'] = $this->M_MutasiStok->Get_NewID();

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
		$this->load->view('WMS/MutasiStok/MutasiStokForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiStok/S_MutasiStok', $data);
	}

	public function getDataPrincipleByClientWmsId()
	{
		$this->load->model('WMS/M_MutasiStok', 'M_MutasiStok');
		$id = $this->input->post('id');
		$data = $this->M_MutasiStok->getDataPrincipleByClientWmsId($id);
		echo json_encode($data);
	}

	public function MutasiStokEdit()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiStok', 'M_MutasiStok');

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
		$data['Checker'] = $this->M_MutasiStok->Get_Checker();
		$data['Gudang'] = $this->M_MutasiStok->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiStok->Get_TipeTransaksi();
		$data['getClientWms'] = $this->M_MutasiStok->getClientWms();
		$data['Status'] = $this->M_MutasiStok->Get_Status();
		$data['tr_mutasi_stok_id'] = $tr_mutasi_stok_id;
		$data['Header'] = $this->M_MutasiStok->Get_tr_mutasi_stok_header($tr_mutasi_stok_id);
		$data['Principle'] = $this->M_MutasiStok->getDataPrincipleByClientWmsId($data['Header']->client_wms_id);
		$data['Detail'] = $this->M_MutasiStok->Get_tr_mutasi_stok_detail($tr_mutasi_stok_id);
		$data['Detail2'] = $this->M_MutasiStok->Get_tr_mutasi_stok_detail2($tr_mutasi_stok_id);
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
		$this->load->view('WMS/MutasiStok/MutasiStokEdit', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiStok/S_MutasiStok', $data);
	}

	public function MutasiStokDetail()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiStok', 'M_MutasiStok');

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
		$data['Checker'] = $this->M_MutasiStok->Get_Checker();
		$data['Gudang'] = $this->M_MutasiStok->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiStok->Get_TipeTransaksi();
		$data['getClientWms'] = $this->M_MutasiStok->getClientWms();
		$data['Status'] = $this->M_MutasiStok->Get_Status();
		$data['tr_mutasi_stok_id'] = $tr_mutasi_stok_id;
		$data['Header'] = $this->M_MutasiStok->Get_tr_mutasi_stok_header($tr_mutasi_stok_id);
		$data['Principle'] = $this->M_MutasiStok->getDataPrincipleByClientWmsId($data['Header']->client_wms_id);
		$data['Detail'] = $this->M_MutasiStok->Get_tr_mutasi_stok_detail($tr_mutasi_stok_id);
		$data['Detail2'] = $this->M_MutasiStok->Get_tr_mutasi_stok_detail2($tr_mutasi_stok_id);
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
		$this->load->view('WMS/MutasiStok/MutasiStokDetail', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiStok/S_MutasiStok', $data);
	}

	public function GetPencarianMutasiStokTable()
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

		$data['MutasiStok'] = $this->M_MutasiStok->Get_PencarianMutasiStokTable($tgl1, $tgl2, $id, $gudang_asal, $gudang_tujuan, $tipe, $client_wms, $principle, $checker, $status);

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

		$data['Gudang'] = $this->M_MutasiStok->Get_GudangById($depo_detail_id);
		$data['Principle'] = $this->M_MutasiStok->Get_PrincipleById($principle);
		$data['Perusahaan'] = $this->M_MutasiStok->Get_PerusahaanById($perusahaan);

		$data['SKU'] = $this->M_MutasiStok->Get_SKU($depo_detail_id, $principle, $perusahaan, $filter_sku_stock_id);

		echo json_encode($data);
	}

	public function Get_list_mutasi_stock_detail()
	{

		$filter_sku_stock_id = array();
		$push_arr_tr_mutasi_stok_detail2 = array();
		$tr_mutasi_stok_id = $this->input->post('tr_mutasi_stok_id');
		$arr_list_multi_sku = $this->input->post('arr_list_multi_sku');
		$arr_tr_mutasi_stok_detail2 = $this->input->post('arr_tr_mutasi_stok_detail2');

		if (isset($arr_list_multi_sku)) {
			if (count($arr_list_multi_sku) > 0) {
				foreach ($arr_list_multi_sku as $value) {
					if ($value['sku_stock_id'] != "" && $value['sku_stock_id'] != null) {
						array_push($filter_sku_stock_id, "'" . $value['sku_stock_id'] . "'");
					}
				}
			}
		}

		if (isset($arr_tr_mutasi_stok_detail2)) {
			if (count($arr_tr_mutasi_stok_detail2) > 0) {
				$push_arr_tr_mutasi_stok_detail2 = $arr_tr_mutasi_stok_detail2;
			}
		}

		$data = $this->M_MutasiStok->Get_list_mutasi_stock_detail($tr_mutasi_stok_id, $filter_sku_stock_id, $arr_list_multi_sku, $push_arr_tr_mutasi_stok_detail2);

		echo json_encode($data);
	}

	public function Get_list_mutasi_stock_detail2()
	{
		$push_arr_tr_mutasi_stok_detail2 = array();
		$tr_mutasi_stok_detail_id = $this->input->post('tr_mutasi_stok_detail_id');
		$arr_tr_mutasi_stok_detail2 = $this->input->post('arr_tr_mutasi_stok_detail2');

		if (isset($arr_tr_mutasi_stok_detail2)) {
			if (count($arr_tr_mutasi_stok_detail2) > 0) {
				$push_arr_tr_mutasi_stok_detail2 = $arr_tr_mutasi_stok_detail2;
			}
		}

		$data = $this->M_MutasiStok->Get_list_mutasi_stock_detail2($tr_mutasi_stok_detail_id, $push_arr_tr_mutasi_stok_detail2);

		echo json_encode($data);
	}

	public function Get_total_qty_mutasi_stock_detail2()
	{
		$push_arr_tr_mutasi_stok_detail2 = array();
		$tr_mutasi_stok_detail_id = $this->input->post('tr_mutasi_stok_detail_id');
		$arr_tr_mutasi_stok_detail2 = $this->input->post('arr_tr_mutasi_stok_detail2');

		if (isset($arr_tr_mutasi_stok_detail2)) {
			if (count($arr_tr_mutasi_stok_detail2) > 0) {
				$push_arr_tr_mutasi_stok_detail2 = $arr_tr_mutasi_stok_detail2;
			}
		}

		$data['total'] = $this->M_MutasiStok->Get_total_qty_mutasi_stock_detail2($tr_mutasi_stok_detail_id, $push_arr_tr_mutasi_stok_detail2);

		echo json_encode($data);
	}

	public function Get_pallet_by_sku_stock_id()
	{

		$sku_stock_id = $this->input->post('sku_stock_id');

		$data = $this->M_MutasiStok->Get_pallet_by_sku_stock_id($sku_stock_id);

		echo json_encode($data);
	}

	public function GetPalletDetail()
	{
		$this->load->model('WMS/M_MutasiStok', 'M_MutasiStok');

		$pallet_id = $this->input->post('pallet_id');

		$data['PalletDetail'] = $this->M_MutasiStok->Get_PalletDetail($pallet_id);

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
		$depoPrefix = $this->M_MutasiStok->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$tr_mutasi_stok_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$checker = explode(" || ", $this->input->post('tr_mutasi_stok_nama_checker'));

		$client_wms_id = $this->input->post('client_wms_id');
		$principle_id = $this->input->post('principle_id');
		$tr_mutasi_stok_tanggal = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tr_mutasi_stok_tanggal'))));
		$tr_mutasi_stok_keterangan = $this->input->post('tr_mutasi_stok_keterangan');
		$tr_mutasi_stok_status = $this->input->post('tr_mutasi_stok_status');
		$depo_id_asal = $depo_id;
		$depo_detail_id_asal = $this->input->post('depo_detail_id_asal');
		$tr_mutasi_stok_tgl_create = $this->input->post('tr_mutasi_stok_tgl_create');
		$tr_mutasi_stok_who_create = $this->input->post('tr_mutasi_stok_who_create');
		$tr_mutasi_stok_nama_checker = $checker[1];
		$tr_mutasi_stok_tgl_update = $this->input->post('tr_mutasi_stok_tgl_update');
		$tr_mutasi_stokt_who_update = $this->input->post('tr_mutasi_stokt_who_update');
		$detail = $this->input->post('detail');

		$this->db->trans_begin();

		$this->M_MutasiStok->insert_tr_mutasi_stok($tr_mutasi_stok_id, $client_wms_id, $principle_id, $tr_mutasi_stok_kode, $tr_mutasi_stok_tanggal, $tr_mutasi_stok_keterangan, $tr_mutasi_stok_status, $depo_id_asal, $tr_mutasi_stok_tgl_create, $tr_mutasi_stok_who_create, $tr_mutasi_stok_nama_checker, $tr_mutasi_stok_tgl_update, $tr_mutasi_stokt_who_update);

		foreach ($detail as $key => $value) {
			$tr_mutasi_stok_detail_id = $this->M_Vrbl->Get_NewID();
			$tr_mutasi_stok_detail_id = $tr_mutasi_stok_detail_id[0]['NEW_ID'];

			$pallet_id_asal = $value['pallet_id_asal'];
			$sku_stock_id = $value['sku_stock_id'];
			$sku_id = $value['sku_id'];
			$sku_stock_expired_date = date('Y-m-d', strtotime(str_replace("/", "-", $value['sku_stock_expired_date'])));
			$sku_stock_qty = $value['sku_stock_qty'];

			$this->M_MutasiStok->insert_tr_mutasi_stok_detail($tr_mutasi_stok_detail_id, $tr_mutasi_stok_id, $depo_detail_id_asal, $pallet_id_asal, $sku_stock_id, $sku_id, $sku_stock_expired_date, $sku_stock_qty);
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

		$tr_mutasi_stok_id = $this->input->post('tr_mutasi_stok_id');
		$tr_mutasi_stok_kode = $this->input->post('tr_mutasi_stok_kode');
		$client_wms_id = $this->input->post('client_wms_id');
		$principle_id = $this->input->post('principle_id');
		$tr_mutasi_stok_tanggal = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tr_mutasi_stok_tanggal'))));
		$tr_mutasi_stok_keterangan = $this->input->post('tr_mutasi_stok_keterangan');
		$tr_mutasi_stok_status = "In Progress";
		// $tr_mutasi_stok_status = $this->input->post('tr_mutasi_stok_status');
		$depo_id_asal = $depo_id;
		$depo_detail_id_asal = $this->input->post('depo_detail_id_asal');
		$tr_mutasi_stok_tgl_create = $this->input->post('tr_mutasi_stok_tgl_create');
		$tr_mutasi_stok_who_create = $this->input->post('tr_mutasi_stok_who_create');
		$tr_mutasi_stok_nama_checker = $checker[1];
		$tr_mutasi_stok_tgl_update = $this->input->post('tr_mutasi_stok_tgl_update');
		$tr_mutasi_stokt_who_update = $this->input->post('tr_mutasi_stokt_who_update');
		$detail = $this->input->post('detail');
		$detail2 = $this->input->post('detail2');

		$cek_detail2 = $this->M_MutasiStok->cek_mutasi_stok_detail2($tr_mutasi_stok_id, $detail2);

		if (count($cek_detail2) > 0) {
			echo json_encode(array("status" => 401, "data" => $cek_detail2));
			return false;
		}

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "tr_mutasi_stok",
			'whereField' => "tr_mutasi_stok_id",
			'whereValue' => $tr_mutasi_stok_id,
			'fieldDateUpdate' => "tr_mutasi_stok_tgl_update",
			'fieldWhoUpdate' => "tr_mutasi_stokt_who_update",
			'lastUpdated' => $tr_mutasi_stok_tgl_update
		]);

		if ($lastUpdatedChecked['status'] == 400) {
			echo json_encode(array("status" => 400, "data" => ""));
			return false;
		}

		$this->db->trans_begin();

		$this->M_MutasiStok->update_last_update_tr_mutasi_stok($tr_mutasi_stok_id, $tr_mutasi_stok_status, $tr_mutasi_stok_tgl_update, $tr_mutasi_stokt_who_update);

		$this->M_MutasiStok->delete_tr_mutasi_stok_detail2($tr_mutasi_stok_id);

		foreach ($detail2 as $key => $value) {
			$tr_mutasi_stok_detail2_id = $this->M_Vrbl->Get_NewID();
			$tr_mutasi_stok_detail2_id = $tr_mutasi_stok_detail2_id[0]['NEW_ID'];

			$tr_mutasi_stok_detail_id = $value['tr_mutasi_stok_detail_id'];
			// $tr_mutasi_stok_id = $value['tr_mutasi_stok_id'];
			$depo_detail_id_tujuan = $value['depo_detail_id_tujuan'];
			$pallet_id_tujuan = $value['pallet_id_tujuan'];
			$sku_stock_id_tujuan = $value['sku_stock_id_tujuan'];
			$sku_id = $value['sku_id'];
			$sku_stock_expired_date = date('Y-m-d', strtotime(str_replace("/", "-", $value['sku_stock_expired_date'])));
			$sku_stock_qty = $value['sku_stock_qty'];


			$this->M_MutasiStok->insert_tr_mutasi_stok_detail2($tr_mutasi_stok_detail2_id, $tr_mutasi_stok_detail_id, $tr_mutasi_stok_id, $depo_detail_id_tujuan, $pallet_id_tujuan, $sku_stock_id_tujuan, $sku_id, $sku_stock_expired_date, $sku_stock_qty);
		}

		// $this->db->trans_rollback();
		// die;

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array("status" => 500, "data" => ""));
		} else {
			$this->db->trans_commit();
			echo json_encode(array("status" => 200, "data" => ""));
		}
	}

	public function konfirmasi_tr_mutasi_stok()
	{
		$checker = explode(" || ", $this->input->post('tr_mutasi_stok_nama_checker'));
		$depo_id = $this->session->userdata('depo_id');

		$tr_mutasi_stok_id = $this->input->post('tr_mutasi_stok_id');
		$tr_mutasi_stok_kode = $this->input->post('tr_mutasi_stok_kode');
		$client_wms_id = $this->input->post('client_wms_id');
		$principle_id = $this->input->post('principle_id');
		$tr_mutasi_stok_tanggal = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tr_mutasi_stok_tanggal'))));
		$tr_mutasi_stok_keterangan = $this->input->post('tr_mutasi_stok_keterangan');
		$tr_mutasi_stok_status = "Completed";
		// $tr_mutasi_stok_status = $this->input->post('tr_mutasi_stok_status');
		$depo_id_asal = $depo_id;
		$depo_detail_id_asal = $this->input->post('depo_detail_id_asal');
		$tr_mutasi_stok_tgl_create = $this->input->post('tr_mutasi_stok_tgl_create');
		$tr_mutasi_stok_who_create = $this->input->post('tr_mutasi_stok_who_create');
		$tr_mutasi_stok_nama_checker = $checker[1];
		$tr_mutasi_stok_tgl_update = $this->input->post('tr_mutasi_stok_tgl_update');
		$tr_mutasi_stokt_who_update = $this->input->post('tr_mutasi_stokt_who_update');
		$detail = $this->input->post('detail');
		$detail2 = $this->input->post('detail2');

		$identity = "MUTASISTOCK";
		$who = $this->session->userdata('pengguna_username');

		$cek_detail2 = $this->M_MutasiStok->cek_mutasi_stok_detail2($tr_mutasi_stok_id, $detail2);

		if (count($cek_detail2) > 0) {
			echo json_encode(array("status" => 401, "data" => $cek_detail2));
			return false;
		}

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "tr_mutasi_stok",
			'whereField' => "tr_mutasi_stok_id",
			'whereValue' => $tr_mutasi_stok_id,
			'fieldDateUpdate' => "tr_mutasi_stok_tgl_update",
			'fieldWhoUpdate' => "tr_mutasi_stokt_who_update",
			'lastUpdated' => $tr_mutasi_stok_tgl_update
		]);

		if ($lastUpdatedChecked['status'] == 400) {
			echo json_encode(array("status" => 400, "data" => ""));
			return false;
		}

		$this->db->trans_begin();

		$this->M_MutasiStok->update_last_update_tr_mutasi_stok($tr_mutasi_stok_id, $tr_mutasi_stok_status, $tr_mutasi_stok_tgl_update, $tr_mutasi_stokt_who_update);

		$this->M_MutasiStok->delete_tr_mutasi_stok_detail2($tr_mutasi_stok_id);

		foreach ($detail as $key => $value) {
			$tr_mutasi_stok_detail_id = $this->M_Vrbl->Get_NewID();
			$tr_mutasi_stok_detail_id = $tr_mutasi_stok_detail_id[0]['NEW_ID'];

			$pallet_id_asal = $value['pallet_id_asal'];
			$sku_stock_id = $value['sku_stock_id'];
			$sku_id = $value['sku_id'];
			$sku_stock_expired_date = date('Y-m-d', strtotime(str_replace("/", "-", $value['sku_stock_expired_date'])));
			$sku_stock_qty = $value['sku_stock_qty'];
			$tipe_sku_stock = "keluar";
			$tipe_pallet = "sku_stock_out";

			$this->M_MutasiStok->Exec_insertupdate_sku_stock($tipe_sku_stock, $sku_stock_id, $client_wms_id, $sku_stock_qty);

			$this->M_MutasiStok->Exec_insertupdate_sku_stock_pallet($tipe_pallet, $pallet_id_asal, $sku_stock_id, $sku_stock_qty);
		}

		foreach ($detail2 as $key => $value) {
			$tr_mutasi_stok_detail2_id = $this->M_Vrbl->Get_NewID();
			$tr_mutasi_stok_detail2_id = $tr_mutasi_stok_detail2_id[0]['NEW_ID'];

			$tr_mutasi_stok_detail_id = $value['tr_mutasi_stok_detail_id'];
			// $tr_mutasi_stok_id = $value['tr_mutasi_stok_id'];
			$depo_detail_id_tujuan = $value['depo_detail_id_tujuan'];
			$pallet_id_tujuan = $value['pallet_id_tujuan'];
			$sku_stock_id_tujuan = $value['sku_stock_id_tujuan'];
			$sku_id = $value['sku_id'];
			$sku_stock_expired_date = date('Y-m-d', strtotime(str_replace("/", "-", $value['sku_stock_expired_date'])));
			$sku_stock_qty = $value['sku_stock_qty'];


			$this->M_MutasiStok->insert_tr_mutasi_stok_detail2($tr_mutasi_stok_detail2_id, $tr_mutasi_stok_detail_id, $tr_mutasi_stok_id, $depo_detail_id_tujuan, $pallet_id_tujuan, $sku_stock_id_tujuan, $sku_id, $sku_stock_expired_date, $sku_stock_qty);

			$this->M_MutasiStok->Exec_cek_exist_sku_stock($depo_detail_id_tujuan, $sku_id, $sku_stock_expired_date, $sku_stock_qty);

			$sku_stock_id = $this->M_MutasiStok->Get_sku_stock_id($depo_detail_id_tujuan, $sku_id, $sku_stock_expired_date);

			$this->M_MutasiStok->Exec_cek_exist_sku_stock_in_pallet($pallet_id_tujuan, $sku_id, $sku_stock_id, $sku_stock_expired_date, $sku_stock_qty);

			$this->M_MutasiStok->update_tr_mutasi_stok_detail2_sku_stock_tujuan($tr_mutasi_stok_detail2_id, $sku_stock_id);
		}

		$this->M_MutasiStok->Exec_proses_posting_stock_card($identity, $tr_mutasi_stok_id, $who);

		// $this->db->trans_rollback();
		// die;

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array("status" => 500, "data" => ""));
		} else {
			$this->db->trans_commit();
			echo json_encode(array("status" => 200, "data" => ""));
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
		$this->load->model('WMS/M_MutasiStok', 'M_MutasiStok');

		$principle = $this->input->post('principle');
		$client_wms_id = $this->input->post('client_wms_id');

		header('Content-Type: application/json');

		$result = $this->M_MutasiStok->Get_CheckerPrinciple($principle, $client_wms_id);

		echo json_encode($result);
	}

	public function Get_list_gudang_tujuan()
	{
		$term = $this->input->get('q');
		$depo_detail_id_tujuan = $this->input->get('depo_detail_id_tujuan');

		$data = $this->M_MutasiStok->Get_list_gudang_tujuan($term, $depo_detail_id_tujuan);

		$results = array();
		foreach ($data as $item) {
			$results[] = array(
				'id' => $item->id,
				'text' => $item->text // Assuming 'name' is the field you want to display
			);
		}

		echo json_encode($results);
	}

	public function Get_list_pallet_tujuan()
	{
		$term = $this->input->get('q');
		$depo_detail_id_tujuan = $this->input->get('depo_detail_id_tujuan');
		$pallet_id_tujuan = $this->input->get('pallet_id_tujuan');

		$data = $this->M_MutasiStok->Get_list_pallet_tujuan($term, $depo_detail_id_tujuan, $pallet_id_tujuan);

		$results = array();
		foreach ($data as $item) {
			$results[] = array(
				'id' => $item->id,
				'text' => $item->text // Assuming 'name' is the field you want to display
			);
		}

		echo json_encode($results);
	}
}
