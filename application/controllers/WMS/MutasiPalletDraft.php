<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentController.php';

class MutasiPalletDraft extends CI_Controller
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

		$this->MenuKode = "120002000";
	}

	public function MutasiPalletDraftMenu()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

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
		$data['Checker'] = $this->M_MutasiPalletDraft->Get_Checker();
		$data['Gudang'] = $this->M_MutasiPalletDraft->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiPalletDraft->Get_TipeTransaksi();
		$data['Principle'] = $this->M_MutasiPalletDraft->Get_Principle();
		$data['getClientWms'] = $this->M_MutasiPalletDraft->getClientWms();
		$data['Status'] = $this->M_MutasiPalletDraft->Get_Status();
		$data['mutasi_pallet_draft_id'] = $this->M_MutasiPalletDraft->Get_NewID();

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
		$this->load->view('WMS/MutasiPalletDraft/MutasiPalletDraft', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiPalletDraft/S_MutasiPalletDraft', $data);
	}

	public function MutasiPalletDraftForm()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');

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
		$data['Checker'] = $this->M_MutasiPalletDraft->Get_Checker();
		$data['Gudang'] = $this->M_MutasiPalletDraft->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiPalletDraft->Get_TipeTransaksi();
		$data['getClientWms'] = $this->M_MutasiPalletDraft->getClientWms();
		$data['Principle'] = $this->M_MutasiPalletDraft->Get_Principle();
		$data['Status'] = $this->M_MutasiPalletDraft->Get_Status();
		$data['mutasi_pallet_draft_id'] = $this->M_MutasiPalletDraft->Get_NewID();

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
		$this->load->view('WMS/MutasiPalletDraft/MutasiPalletDraftForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiPalletDraft/S_MutasiPalletDraft', $data);
	}

	public function getDataPrincipleByClientWmsId()
	{
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');
		$id = $this->input->post('id');
		$data = $this->M_MutasiPalletDraft->getDataPrincipleByClientWmsId($id);
		echo json_encode($data);
	}

	public function MutasiPalletDraftEdit()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');

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

		$tr_mutasi_pallet_draft_id = $this->input->get('tr_mutasi_pallet_draft_id');

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Checker'] = $this->M_MutasiPalletDraft->Get_Checker();
		$data['Gudang'] = $this->M_MutasiPalletDraft->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiPalletDraft->Get_TipeTransaksi();
		$data['getClientWms'] = $this->M_MutasiPalletDraft->getClientWms();
		$data['Status'] = $this->M_MutasiPalletDraft->Get_Status();
		$data['mutasi_pallet_draft_id'] = $tr_mutasi_pallet_draft_id;
		$data['MutasiPallet'] = $this->M_MutasiPalletDraft->Get_MutasiPalletById($tr_mutasi_pallet_draft_id);
		$data['Principle'] = $this->M_MutasiPalletDraft->getDataPrincipleByClientWmsId($data['MutasiPallet']->client_wms_id);
		$data['MutasiPalletDetail'] = $this->M_MutasiPalletDraft->Get_MutasiPalletDetailById($tr_mutasi_pallet_draft_id);
		$data['tgl_update_before'] = $this->M_MutasiPalletDraft->GetTglUpdate($tr_mutasi_pallet_draft_id);

		$data['GudangTujuan'] = $this->M_MutasiPalletDraft->Get_Gudang($data['MutasiPallet']->depo_detail_id_asal, $data['MutasiPallet']->tipe_mutasi_id);

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
		$this->load->view('WMS/MutasiPalletDraft/MutasiPalletDraftEdit', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiPalletDraft/S_MutasiPalletDraft', $data);
	}

	public function MutasiPalletDraftDetail()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');

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

		$kode = $this->input->get('kode');

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Checker'] = $this->M_MutasiPalletDraft->Get_Checker();
		$data['Gudang'] = $this->M_MutasiPalletDraft->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiPalletDraft->Get_TipeTransaksi();
		$data['Principle'] = $this->M_MutasiPalletDraft->Get_Principle();
		$data['Status'] = $this->M_MutasiPalletDraft->Get_Status();
		// $data['mutasi_pallet_draft_id'] = $tr_mutasi_pallet_draft_id;
		$data['MutasiPallet'] = $this->M_MutasiPalletDraft->Get_MutasiPalletByKode($kode);
		$data['MutasiPalletDetail'] = $this->M_MutasiPalletDraft->Get_MutasiPalletDetailByKode($kode);

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
		$this->load->view('WMS/MutasiPalletDraft/MutasiPalletDraftDetail', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiPalletDraft/S_MutasiPalletDraft', $data);
	}

	public function GetPencarianMutasiPalletDraftTable()
	{
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
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

		$data['MutasiPalletDraft'] = $this->M_MutasiPalletDraft->Get_PencarianMutasiPalletDraftTable($tgl1, $tgl2, $id, $gudang_asal, $gudang_tujuan, $tipe, $client_wms, $principle, $checker, $status);

		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetPallet()
	{
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');

		$depo_detail_id = $this->input->post('gudang_asal');
		$principle = $this->input->post('principle');
		$perusahaan = $this->input->post('perusahaan');

		$data['Gudang'] = $this->M_MutasiPalletDraft->Get_GudangById($depo_detail_id);
		// $data['Principle'] = $this->M_MutasiPalletDraft->Get_PrincipleById($principle);
		// $data['Perusahaan'] = $this->M_MutasiPalletDraft->Get_PerusahaanById($perusahaan);

		$data['Pallet'] = $this->M_MutasiPalletDraft->Get_Pallet($depo_detail_id, $principle, $perusahaan);

		echo json_encode($data);
	}

	public function GetPalletDetail()
	{
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');

		$pallet_id = $this->input->post('pallet_id');

		$data['PalletDetail'] = $this->M_MutasiPalletDraft->Get_PalletDetail($pallet_id);

		echo json_encode($data);
	}

	public function InsertMutasiPalletDraft()
	{
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');
		$this->load->model('M_Menu');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "U")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('tr_mutasi_pallet_draft_id', 'ID', 'required');
		$this->form_validation->set_rules('principle_id', 'Principle', 'required');
		$this->form_validation->set_rules('client_wms_id', 'Perusahaan', 'required');
		$this->form_validation->set_rules('tr_mutasi_pallet_draft_tanggal', 'Tanggal Mutasi', 'required');
		$this->form_validation->set_rules('tr_mutasi_pallet_draft_tipe', 'Tipe Mutasi', 'required');
		$this->form_validation->set_rules('tr_mutasi_pallet_draft_status', 'Status Mutasi', 'required');
		$this->form_validation->set_rules('depo_id_asal', 'Depo Asal', 'required');
		$this->form_validation->set_rules('depo_detail_id_asal', 'Gudang Asal', 'required');
		$this->form_validation->set_rules('depo_id_tujuan', 'Depo Tujuan', 'required');
		$this->form_validation->set_rules('depo_detail_id_tujuan', 'Gudang Tujuan', 'required');
		$this->form_validation->set_rules('tr_mutasi_pallet_draft_tgl_create', 'Tanggal Buat Mutasi', 'required');
		$this->form_validation->set_rules('tr_mutasi_pallet_draft_who_create', 'Pengguna Buat Mutasi', 'required');
		$this->form_validation->set_rules('tr_mutasi_pallet_draft_nama_checker', 'Checker Mutasi', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$tipe_mutasi_nama = $this->M_MutasiPalletDraft->Get_tipe_mutasi_nama($this->input->post('tr_mutasi_pallet_draft_tipe'));

			$tipe_mutasi = "Kode Dokumen Draft " . $tipe_mutasi_nama;

			$date_now = date('Y-m-d h:i:s');
			$param =  $this->M_MutasiPalletDraft->Get_ParamTipe($tipe_mutasi);

			$vrbl = $this->M_Vrbl->Get_Kode($param);
			$prefix = $vrbl->vrbl_kode;

			// get prefik depo
			$depo_id = $this->session->userdata('depo_id');
			$depoPrefix = $this->M_MutasiPalletDraft->getDepoPrefix($depo_id);
			$unit = $depoPrefix->depo_kode_preffix;

			$tr_mutasi_pallet_draft_id = $this->input->post('tr_mutasi_pallet_draft_id');
			$distribusi_penerimaan_id = $this->input->post('distribusi_penerimaan_id');
			$principle_id = $this->input->post('principle_id');
			$client_wms_id = $this->input->post('client_wms_id');
			$tr_mutasi_pallet_draft_kode = "";
			$tr_mutasi_pallet_draft_tanggal = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tr_mutasi_pallet_draft_tanggal'))));
			$tr_mutasi_pallet_draft_tipe = $this->input->post('tr_mutasi_pallet_draft_tipe');
			$tr_mutasi_pallet_draft_keterangan = $this->input->post('tr_mutasi_pallet_draft_keterangan');
			$tr_mutasi_pallet_draft_status = $this->input->post('tr_mutasi_pallet_draft_status');
			$depo_id_asal = $this->input->post('depo_id_asal');
			$depo_detail_id_asal = $this->input->post('depo_detail_id_asal');
			$depo_id_tujuan = $this->input->post('depo_id_tujuan');
			$depo_detail_id_tujuan = $this->input->post('depo_detail_id_tujuan');
			$tr_mutasi_pallet_draft_tgl_create = $this->input->post('tr_mutasi_pallet_draft_tgl_create');
			$tr_mutasi_pallet_draft_who_create = $this->input->post('tr_mutasi_pallet_draft_who_create');
			$tr_mutasi_pallet_draft_nama_checker = $this->input->post('tr_mutasi_pallet_draft_nama_checker');
			$karyawan_id = $this->input->post('karyawan_id');

			$lastDokumenTutupGudang = cekDokumenTutupGudang((object) [
				'tgl_dokumen' => $tr_mutasi_pallet_draft_tanggal
			]);

			if ($lastDokumenTutupGudang['status'] == 500) {
				echo 500;
				return false;
			}

			$tipe_mutasi = $this->M_MutasiPalletDraft->Get_tipe_mutasi($tr_mutasi_pallet_draft_tipe);
			$approvalParam = $this->M_MutasiPalletDraft->Get_ParameterApprovalMutasiPallet($tipe_mutasi);

			if ($tr_mutasi_pallet_draft_status == "In Progress Approval") {
				if ($approvalParam == "0") {
					$this->M_MutasiPalletDraft->Insert_MutasiPalletDraft($tr_mutasi_pallet_draft_id, $distribusi_penerimaan_id, $principle_id, $client_wms_id, $tr_mutasi_pallet_draft_kode, $tr_mutasi_pallet_draft_tanggal, $tr_mutasi_pallet_draft_tipe, $tr_mutasi_pallet_draft_keterangan, "Draft", $depo_id_asal, $depo_detail_id_asal, $depo_id_tujuan, $depo_detail_id_tujuan, $tr_mutasi_pallet_draft_tgl_create, $tr_mutasi_pallet_draft_who_create, $tr_mutasi_pallet_draft_nama_checker);
					$this->M_MutasiPalletDraft->Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_draft_kode, 0, 0);

					$this->setPuhserInt();

					$result = "Approval setting tidak ada";
				} else {
					$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);
					$tr_mutasi_pallet_draft_kode = $generate_kode;

					$this->M_MutasiPalletDraft->Insert_MutasiPalletDraft($tr_mutasi_pallet_draft_id, $distribusi_penerimaan_id, $principle_id, $client_wms_id, $tr_mutasi_pallet_draft_kode, $tr_mutasi_pallet_draft_tanggal, $tr_mutasi_pallet_draft_tipe, $tr_mutasi_pallet_draft_keterangan, $tr_mutasi_pallet_draft_status, $depo_id_asal, $depo_detail_id_asal, $depo_id_tujuan, $depo_detail_id_tujuan, $tr_mutasi_pallet_draft_tgl_create, $tr_mutasi_pallet_draft_who_create, $tr_mutasi_pallet_draft_nama_checker);
					$this->M_MutasiPalletDraft->Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_draft_kode, 0, 0);

					$this->setPuhserInt();

					$result = 1;
				}
			} else {
				$this->M_MutasiPalletDraft->Insert_MutasiPalletDraft($tr_mutasi_pallet_draft_id, $distribusi_penerimaan_id, $principle_id, $client_wms_id, $tr_mutasi_pallet_draft_kode, $tr_mutasi_pallet_draft_tanggal, $tr_mutasi_pallet_draft_tipe, $tr_mutasi_pallet_draft_keterangan, $tr_mutasi_pallet_draft_status, $depo_id_asal, $depo_detail_id_asal, $depo_id_tujuan, $depo_detail_id_tujuan, $tr_mutasi_pallet_draft_tgl_create, $tr_mutasi_pallet_draft_who_create, $tr_mutasi_pallet_draft_nama_checker);
				$result = 1;
			}

			echo $result;
		}
	}

	public function InsertMutasiPalletDetailDraft()
	{
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');
		$this->load->model('M_Menu');

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "U")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('tr_mutasi_pallet_draft_id', 'ID', 'required');
		$this->form_validation->set_rules('pallet_id', 'Pallet', 'required');
		$this->form_validation->set_rules('rak_lajur_detail_id_asal', 'Rak Asal', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$tr_mutasi_pallet_draft_id = $this->input->post('tr_mutasi_pallet_draft_id');
			$pallet_id = $this->input->post('pallet_id');
			$rak_lajur_detail_id_asal = $this->input->post('rak_lajur_detail_id_asal');

			$result = $this->M_MutasiPalletDraft->Insert_MutasiPalletDetailDraft($tr_mutasi_pallet_draft_id, $pallet_id, $rak_lajur_detail_id_asal);
			// $result2 = $this->M_MutasiPalletDraft->insert_pallet_temp($tr_mutasi_pallet_draft_id, $pallet_id);
			// $result3 = $this->M_MutasiPalletDraft->insert_pallet_detail_temp($pallet_id);

			echo $result;
		}
	}

	public function UpdateMutasiPalletDraft()
	{
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');
		$this->load->model('M_Menu');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "U")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('tr_mutasi_pallet_draft_id', 'ID', 'required');
		$this->form_validation->set_rules('principle_id', 'Principle', 'required');
		$this->form_validation->set_rules('client_wms_id', 'Perusahaan', 'required');
		$this->form_validation->set_rules('tr_mutasi_pallet_draft_tanggal', 'Tanggal Mutasi', 'required');
		$this->form_validation->set_rules('tr_mutasi_pallet_draft_tipe', 'Tipe Mutasi', 'required');
		$this->form_validation->set_rules('tr_mutasi_pallet_draft_status', 'Status Mutasi', 'required');
		$this->form_validation->set_rules('depo_id_asal', 'Depo Asal', 'required');
		$this->form_validation->set_rules('depo_detail_id_asal', 'Gudang Asal', 'required');
		$this->form_validation->set_rules('depo_id_tujuan', 'Depo Tujuan', 'required');
		$this->form_validation->set_rules('depo_detail_id_tujuan', 'Gudang Tujuan', 'required');
		$this->form_validation->set_rules('tr_mutasi_pallet_draft_tgl_create', 'Tanggal Buat Mutasi', 'required');
		$this->form_validation->set_rules('tr_mutasi_pallet_draft_who_create', 'Pengguna Buat Mutasi', 'required');
		$this->form_validation->set_rules('tr_mutasi_pallet_draft_nama_checker', 'Checker Mutasi', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$tipe_mutasi_nama = $this->M_MutasiPalletDraft->Get_tipe_mutasi_nama($this->input->post('tr_mutasi_pallet_draft_tipe'));

			$tipe_mutasi = "Kode Dokumen Draft " . $tipe_mutasi_nama;

			$date_now = date('Y-m-d h:i:s');
			$param =  $this->M_MutasiPalletDraft->Get_ParamTipe($tipe_mutasi);

			$vrbl = $this->M_Vrbl->Get_Kode($param);
			$prefix = $vrbl->vrbl_kode;

			// get prefik depo
			$depo_id = $this->session->userdata('depo_id');
			$depoPrefix = $this->M_MutasiPalletDraft->getDepoPrefix($depo_id);
			$unit = $depoPrefix->depo_kode_preffix;

			$is_approvaldana  = 0;
			$total_biaya  = 0;

			$tr_mutasi_pallet_draft_id = $this->input->post('tr_mutasi_pallet_draft_id');
			$tr_mutasi_pallet_draft_kode = "";
			$principle_id = $this->input->post('principle_id');
			$client_wms_id = $this->input->post('client_wms_id');
			$tr_mutasi_pallet_draft_tanggal = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tr_mutasi_pallet_draft_tanggal'))));
			$tr_mutasi_pallet_draft_tipe = $this->input->post('tr_mutasi_pallet_draft_tipe');
			$tr_mutasi_pallet_draft_keterangan = $this->input->post('tr_mutasi_pallet_draft_keterangan');
			$tr_mutasi_pallet_draft_status = $this->input->post('tr_mutasi_pallet_draft_status');
			$depo_id_asal = $this->input->post('depo_id_asal');
			$depo_detail_id_asal = $this->input->post('depo_detail_id_asal');
			$depo_id_tujuan = $this->input->post('depo_id_tujuan');
			$depo_detail_id_tujuan = $this->input->post('depo_detail_id_tujuan');
			$tr_mutasi_pallet_draft_tgl_create = $this->input->post('tr_mutasi_pallet_draft_tgl_create');
			$tr_mutasi_pallet_draft_who_create = $this->input->post('tr_mutasi_pallet_draft_who_create');
			$tr_mutasi_pallet_draft_nama_checker = $this->input->post('tr_mutasi_pallet_draft_nama_checker');
			$karyawan_id = $this->input->post('karyawan_id');

			$lastDokumenTutupGudang = cekDokumenTutupGudang((object) [
				'tgl_dokumen' => $tr_mutasi_pallet_draft_tanggal
			]);

			if ($lastDokumenTutupGudang['status'] == 500) {
				echo 500;
				return false;
			}

			$tipe_mutasi = $this->M_MutasiPalletDraft->Get_tipe_mutasi($tr_mutasi_pallet_draft_tipe);
			$approvalParam = $this->M_MutasiPalletDraft->Get_ParameterApprovalMutasiPallet($tipe_mutasi);

			$tgl_update_before = $this->input->post('tgl_update_before');
			$tglupdateDB = $this->M_MutasiPalletDraft->GetTglUpdate($tr_mutasi_pallet_draft_id);


			if ($tglupdateDB->tgl_update === $tgl_update_before) {

				$result3 = $this->M_MutasiPalletDraft->Delete_pallet_detail_temp($tr_mutasi_pallet_draft_id);
				$result2 = $this->M_MutasiPalletDraft->Delete_pallet_temp($tr_mutasi_pallet_draft_id);
				$result = $this->M_MutasiPalletDraft->Delete_MutasiPalletDetailDraft($tr_mutasi_pallet_draft_id);

				if ($tr_mutasi_pallet_draft_status == "In Progress Approval") {
					if ($approvalParam == "0") {
						$this->M_MutasiPalletDraft->Update_MutasiPalletDraft($tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_draft_kode, $principle_id, $client_wms_id, $tr_mutasi_pallet_draft_tanggal, $tr_mutasi_pallet_draft_tipe, $tr_mutasi_pallet_draft_keterangan, "Draft", $depo_id_asal, $depo_detail_id_asal, $depo_id_tujuan, $depo_detail_id_tujuan, $tr_mutasi_pallet_draft_tgl_create, $tr_mutasi_pallet_draft_who_create, $tr_mutasi_pallet_draft_nama_checker);
						$this->M_MutasiPalletDraft->Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_draft_kode, 0, 0);

						$this->setPuhserInt();

						$result = "Approval setting tidak ada";
					} else {

						$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);
						$tr_mutasi_pallet_draft_kode = $generate_kode;

						$this->M_MutasiPalletDraft->Update_MutasiPalletDraft($tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_draft_kode, $principle_id, $client_wms_id, $tr_mutasi_pallet_draft_tanggal, $tr_mutasi_pallet_draft_tipe, $tr_mutasi_pallet_draft_keterangan, $tr_mutasi_pallet_draft_status, $depo_id_asal, $depo_detail_id_asal, $depo_id_tujuan, $depo_detail_id_tujuan, $tr_mutasi_pallet_draft_tgl_create, $tr_mutasi_pallet_draft_who_create, $tr_mutasi_pallet_draft_nama_checker);
						$this->M_MutasiPalletDraft->Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_draft_kode, 0, 0);


						$this->setPuhserInt();

						$result = 1;
					}
				} else {
					$this->M_MutasiPalletDraft->Update_MutasiPalletDraft($tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_draft_kode, $principle_id, $client_wms_id, $tr_mutasi_pallet_draft_tanggal, $tr_mutasi_pallet_draft_tipe, $tr_mutasi_pallet_draft_keterangan, $tr_mutasi_pallet_draft_status, $depo_id_asal, $depo_detail_id_asal, $depo_id_tujuan, $depo_detail_id_tujuan, $tr_mutasi_pallet_draft_tgl_create, $tr_mutasi_pallet_draft_who_create, $tr_mutasi_pallet_draft_nama_checker);
					$result = 1;
				}
			} else {
				$result = 2;
			}
			echo $result;
		}
	}

	public function setPuhserInt()
	{

		senderPusher([
			'message' => 'insert pengajuan approval mutasi pallet draft'
		]);
	}

	public function UpdateMutasiPalletDetailDraft()
	{
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');
		$this->load->model('M_Menu');

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "U")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('tr_mutasi_pallet_draft_id', 'ID', 'required');
		$this->form_validation->set_rules('pallet_id', 'Pallet', 'required');
		$this->form_validation->set_rules('rak_lajur_detail_id_asal', 'Rak Asal', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$tr_mutasi_pallet_draft_id = $this->input->post('tr_mutasi_pallet_draft_id');
			$pallet_id = $this->input->post('pallet_id');
			$rak_lajur_detail_id_asal = $this->input->post('rak_lajur_detail_id_asal');

			$result = $this->M_MutasiPalletDraft->Update_MutasiPalletDetailDraft($tr_mutasi_pallet_draft_id, $pallet_id, $rak_lajur_detail_id_asal);
			// $result2 = $this->M_MutasiPalletDraft->insert_pallet_temp($tr_mutasi_pallet_draft_id, $pallet_id);
			// $result3 = $this->M_MutasiPalletDraft->insert_pallet_detail_temp($pallet_id);

			echo $result;
		}
	}

	public function DeleteMutasiPalletDetailDraft()
	{
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');
		$this->load->model('M_Menu');

		$tr_mutasi_pallet_draft_id = $this->input->post('tr_mutasi_pallet_draft_id');

		$result3 = $this->M_MutasiPalletDraft->Delete_pallet_detail_temp($tr_mutasi_pallet_draft_id);
		$result2 = $this->M_MutasiPalletDraft->Delete_pallet_temp($tr_mutasi_pallet_draft_id);
		$result = $this->M_MutasiPalletDraft->Delete_MutasiPalletDetailDraft($tr_mutasi_pallet_draft_id);

		echo $result;
	}

	public function GetCheckerPrinciple()
	{
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');

		$principle = $this->input->post('principle');
		$client_wms_id = $this->input->post('client_wms_id');

		header('Content-Type: application/json');

		$result = $this->M_MutasiPalletDraft->Get_CheckerPrinciple($principle, $client_wms_id);

		echo json_encode($result);
	}

	public function GetGudangTujuan()
	{
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');

		$gudangAsal = $this->input->post('gudangAsal');

		header('Content-Type: application/json');

		$result = $this->M_MutasiPalletDraft->Get_Gudang($gudangAsal);

		echo json_encode($result);
	}

	public function GetGudangTujuanByTipe()
	{
		$this->load->model('WMS/M_MutasiPalletDraft', 'M_MutasiPalletDraft');

		$gudang_asal = $this->input->post('gudang_asal');

		header('Content-Type: application/json');

		$result = $this->M_MutasiPalletDraft->Get_GudangTujuanByTipe($gudang_asal);

		echo json_encode($result);
	}
}
