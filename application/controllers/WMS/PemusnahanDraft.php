<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class PemusnahanDraft extends ParentController
{

	private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		if ($this->session->has_userdata('pengguna_id') == 0) :
			redirect(base_url('MainPage/Login'));
		endif;

		$this->depo_id = $this->session->userdata('depo_id');
		$this->MenuKode = "120014000";
		// $this->load->model(['M_Menu', 'M_PemusnahanDraft', 'M_Function', 'M_MenuAccess', 'M_Vrbl', 'M_AutoGen']);

		$this->load->model('M_Menu');
		$this->load->model('WMS/M_PemusnahanDraft', 'M_PemusnahanDraft');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation']);
	}

	public function PemusnahanDraftMenu()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['Checker'] = $this->M_PemusnahanDraft->Get_Checker();
		$data['Gudang'] = $this->M_PemusnahanDraft->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_PemusnahanDraft->Get_TipeTransaksi();
		$data['Principle'] = $this->M_PemusnahanDraft->Get_Principle();

		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css'
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PemusnahanDraft/PemusnahanDraft', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PemusnahanDraft/S_PemusnahanDraft', $data);
	}

	public function get_data_pemusnahan_draft_by_filter()
	{
		$filter_no_pemusnahan_draft = $this->input->post('filter_no_pemusnahan_draft');
		$filter_pemusnahan_draft_tgl_draft = $this->input->post('filter_pemusnahan_draft_tgl_draft');
		$filter_gudang_asal_pemusnahan_draft = $this->input->post('filter_gudang_asal_pemusnahan_draft');
		$filter_pemusnahan_draft_tipe_transaksi = $this->input->post('filter_pemusnahan_draft_tipe_transaksi');
		$filter_pemusnahan_draft_principle = $this->input->post('filter_pemusnahan_draft_principle');
		$filter_pemusnahan_draft_checker = $this->input->post('filter_pemusnahan_draft_checker');
		$filter_pemusnahan_draft_status = $this->input->post('filter_pemusnahan_draft_status');

		$result = $this->M_PemusnahanDraft->get_data_pemusnahan_draft_by_filter($filter_no_pemusnahan_draft, $filter_pemusnahan_draft_tgl_draft, $filter_gudang_asal_pemusnahan_draft, $filter_pemusnahan_draft_tipe_transaksi, $filter_pemusnahan_draft_principle, $filter_pemusnahan_draft_checker, $filter_pemusnahan_draft_status);

		echo json_encode($result);
		// echo $filter_pemusnahan_draft_tgl_draft;
	}

	public function add()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['Checker'] = $this->M_PemusnahanDraft->Get_Checker();
		$data['Ekspedisi'] = $this->M_PemusnahanDraft->get_ekspedisi();
		$data['Driver'] = $this->M_PemusnahanDraft->get_driver();
		$data['Kendaraan'] = $this->M_PemusnahanDraft->get_kendaraan();
		$data['Gudang'] = $this->M_PemusnahanDraft->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_PemusnahanDraft->Get_TipeTransaksi();
		$data['Principle'] = $this->M_PemusnahanDraft->Get_Principle();

		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css'
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PemusnahanDraft/component/form_tambah_data/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PemusnahanDraft/component/form_tambah_data/S_tambah', $data);
	}

	public function get_checker_by_principleId()
	{
		$id = $this->input->post('id');
		$result = $this->M_PemusnahanDraft->get_checker_by_principleId($id);
		echo json_encode($result);
	}

	public function get_BrandAndSKUInduk()
	{
		$principle_id = $this->input->post('principle_id');
		$brand = $this->M_PemusnahanDraft->get_BrandByPrincipleId($principle_id);
		$sku_induk = $this->M_PemusnahanDraft->get_SKUIndukByPrincipleId($principle_id);
		echo json_encode(array('brand' => $brand, 'sku_induk' => $sku_induk));
	}

	public function search_filter_chosen_sku()
	{
		$depo = $this->input->post('depo');
		$gudang = $this->input->post('gudang');
		$principle = $this->input->post('principle');
		$brand = $this->input->post('brand');
		$sku_induk = $this->input->post('sku_induk');
		$nama_sku = $this->input->post('nama_sku');
		$sku_kode_wms = $this->input->post('sku_kode_wms');
		$sku_kode_pabrik = $this->input->post('sku_kode_pabrik');
		$result = $this->M_PemusnahanDraft->search_filter_chosen_sku($depo, $gudang, $principle, $brand, $sku_induk, $nama_sku, $sku_kode_wms, $sku_kode_pabrik);
		echo json_encode($result);
	}

	public function get_data_sku_by_id()
	{
		$id = $this->input->post('id');
		$result = $this->M_PemusnahanDraft->get_data_sku_by_id($id);
		echo json_encode($result);
	}

	public function save_data_pemusnahan_draft()
	{
		$tgl = $this->input->post('tgl');
		$gudang = $this->input->post('gudang');
		$principle = $this->input->post('principle');
		$checker = $this->input->post('checker');
		$tipe = $this->input->post('tipe');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan');
		$detail = $this->input->post('detail');
		$ekspedisi = $this->input->post('ekspedisi');
		$driver = $this->input->post('driver');
		$kendaraan = $this->input->post('kendaraan');
		$nopol = $this->input->post('nopol');

		$kpd_id = $this->M_Vrbl->Get_NewID();
		$kpd_id = $kpd_id[0]['NEW_ID'];

		//generate kode
		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_DKSB';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PemusnahanDraft->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$this->db->trans_begin();

		//insert ke tr_pemusnahan_stok_draft
		$this->M_PemusnahanDraft->insert_to_tr_koreksi_draft($kpd_id, $generate_kode, $tgl, $gudang, $principle, $checker, $tipe, $status, $keterangan, $ekspedisi, $driver, $kendaraan, $nopol);

		//insert ke tr_pemusnahan_stok_detail_draft
		foreach ($detail as $key => $value) {
			$this->M_PemusnahanDraft->insert_to_tr_koreksi_detail_draft($kpd_id, $value);
		}

		if ($status == "In Progress Approval") {
			$this->db->query("exec approval_pengajuan '" . $this->session->userdata('depo_id') . "', '" . $this->session->userdata('pengguna_id') . "', 'APPRV_PEMUSNAHAN_01', '$kpd_id', '$generate_kode', 0, 0");
			$this->setPuhserInt();
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function edit($id)
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['id'] = $id;
		$data['Checker'] = $this->M_PemusnahanDraft->Get_Checker();
		$data['Ekspedisi'] = $this->M_PemusnahanDraft->get_ekspedisi();
		$data['Driver'] = $this->M_PemusnahanDraft->get_driver();
		$data['Kendaraan'] = $this->M_PemusnahanDraft->get_kendaraan();
		$data['Gudang'] = $this->M_PemusnahanDraft->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_PemusnahanDraft->Get_TipeTransaksi();
		$data['Principle'] = $this->M_PemusnahanDraft->Get_Principle();
		$data['depo'] = $this->M_PemusnahanDraft->get_DepoNameBySession();

		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css'
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PemusnahanDraft/component/form_edit_data/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PemusnahanDraft/component/form_edit_data/S_edit', $data);
	}

	public function getDataPemusnahanDraftById()
	{
		$id = $this->input->post('id');
		$header = $this->M_PemusnahanDraft->getDataPemusnahanDraftHeader($id);
		$detail = $this->M_PemusnahanDraft->getDataPemusnahanDraftDetail($id);

		echo json_encode(array('header' => $header, 'detail' => $detail));
	}

	public function edit_data_pemusnahan_draft()
	{
		$id = $this->input->post('id');

		$kode = $this->input->post('kode');
		$gudang = $this->input->post('gudang');
		$principle = $this->input->post('principle');
		$checker = $this->input->post('checker');
		$tipe = $this->input->post('tipe');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan');
		$detail = $this->input->post('detail');
		$ekspedisi = $this->input->post('ekspedisi');
		$driver = $this->input->post('driver');
		$kendaraan = $this->input->post('kendaraan');
		$nopol = $this->input->post('nopol');
		$lastUpdated = $this->input->post('lastUpdate');
		$getLastUpdatedDb = $this->db->select("tr_koreksi_stok_draft_tgl_update")->from("tr_koreksi_stok_draft")->where("tr_koreksi_stok_draft_id", $id)->get()->row()->tr_koreksi_stok_draft_tgl_update;

		$errorNotSameLastUpdated = false;
		if ($lastUpdated !== $getLastUpdatedDb) $errorNotSameLastUpdated = true;
		if ($errorNotSameLastUpdated) {
			$this->db->trans_rollback();
			// echo json_encode(array('type' => 2, 'message' => "Sudah Dihanlde User Lain"));
			echo json_encode(2);
			return false;
		}

		$this->db->trans_begin();

		//update ke tr_pemusnahan_stok_draft
		$this->M_PemusnahanDraft->update_to_tr_koreksi_draft($id, $gudang, $principle, $checker, $tipe, $status, $keterangan, $ekspedisi, $driver, $kendaraan, $nopol);

		//delete data tr_pemusnahan_stok_detail_draft
		$this->M_PemusnahanDraft->delete_to_tr_koreksi_detail_draft($id);

		//update / insert ke tr_pemusnahan_stok_detail_draft
		foreach ($detail as $key => $value) {
			$this->M_PemusnahanDraft->update_to_tr_koreksi_detail_draft($id, $value);
		}

		if ($status == "In Progress Approval") {
			$this->db->query("exec approval_pengajuan '" . $this->session->userdata('depo_id') . "', '" . $this->session->userdata('pengguna_id') . "', 'APPRV_PEMUSNAHAN_01', '$id', '$kode', 0, 0");
			$this->setPuhserInt();
		}

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
			'message' => 'insert pengajuan approval pemusnahan draft'
		]);
	}

	public function view($id)
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['id'] = $id;
		$data['Checker'] = $this->M_PemusnahanDraft->Get_Checker();
		$data['Ekspedisi'] = $this->M_PemusnahanDraft->get_ekspedisi();
		$data['Driver'] = $this->M_PemusnahanDraft->get_driver();
		$data['Kendaraan'] = $this->M_PemusnahanDraft->get_kendaraan();
		$data['Gudang'] = $this->M_PemusnahanDraft->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_PemusnahanDraft->Get_TipeTransaksi();
		$data['Principle'] = $this->M_PemusnahanDraft->Get_Principle();
		$data['depo'] = $this->M_PemusnahanDraft->get_DepoNameBySession();

		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css'
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PemusnahanDraft/component/form_view_data/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PemusnahanDraft/component/form_view_data/S_view', $data);
	}

	public function get_nopol_by_kendaraan_model()
	{
		$kendaraan_model = $this->input->post('kendaraan_model');
		$result = $this->M_PemusnahanDraft->get_nopol($kendaraan_model);
		echo json_encode($result);
	}
}
