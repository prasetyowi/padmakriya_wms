<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class KoreksiStokBarangDraft extends ParentController
{

	private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		// if ($this->session->has_userdata('pengguna_id') == 0) :
		//   redirect(base_url('MainPage'));
		// endif;

		$this->depo_id = $this->session->userdata('depo_id');
		$this->MenuKode = "120008000";
		$this->load->model(['M_Menu', ['WMS/M_KoreksiStokBarangDraft', 'M_KoreksiStokBarangDraft'], 'M_Function', 'M_MenuAccess', 'M_Vrbl', 'M_AutoGen']);

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation']);
	}

	public function KoreksiStokBarangDraftMenu()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url(''));
			exit();
		}

		$data['data_corrections_draft'] = $this->M_KoreksiStokBarangDraft->data_corrections_draft();
		$data['warehouses'] = $this->M_KoreksiStokBarangDraft->get_warehouses();
		$data['principles'] = $this->M_KoreksiStokBarangDraft->get_principles();
		$data['type_transactions'] = $this->M_KoreksiStokBarangDraft->get_type_transactions();

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
		$this->load->view('WMS/KoreksiStokBarangDraft/KoreksiStokBarangDraft', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/KoreksiStokBarangDraft/S_KoreksiStokBarangDraft', $data);
	}

	public function get_data_koreksi_stok_draft_by_filter()
	{
		$filter_no_koreksi_draft = $this->input->post('filter_no_koreksi_draft');
		// $filter_koreksi_draft_tgl_draft = $this->input->post('filter_koreksi_draft_tgl_draft');

		$tgl = explode(" - ", $this->input->post('filter_koreksi_draft_tgl_draft'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$filter_gudang_asal_koreksi_draft = $this->input->post('filter_gudang_asal_koreksi_draft');
		$filter_koreksi_draft_tipe_transaksi = $this->input->post('filter_koreksi_draft_tipe_transaksi');
		$filter_koreksi_draft_principle = $this->input->post('filter_koreksi_draft_principle');
		$filter_koreksi_draft_checker = $this->input->post('filter_koreksi_draft_checker');
		$filter_koreksi_draft_status = $this->input->post('filter_koreksi_draft_status');

		$result = $this->M_KoreksiStokBarangDraft->get_data_koreksi_stok_draft_by_filter($filter_no_koreksi_draft, $tgl1, $tgl2, $filter_gudang_asal_koreksi_draft, $filter_koreksi_draft_tipe_transaksi, $filter_koreksi_draft_principle, $filter_koreksi_draft_checker, $filter_koreksi_draft_status);

		echo json_encode($result);
	}

	public function add()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['warehouses'] = $this->M_KoreksiStokBarangDraft->get_warehouses();
		$data['principles'] = $this->M_KoreksiStokBarangDraft->get_principles();
		$data['type_transactions'] = $this->M_KoreksiStokBarangDraft->get_type_transactions();
		$data['type_document'] = $this->M_KoreksiStokBarangDraft->get_type_document();
		$data['depo'] = $this->M_KoreksiStokBarangDraft->get_DepoNameBySession();

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
		$this->load->view('WMS/KoreksiStokBarangDraft/component/form_tambah_data/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/KoreksiStokBarangDraft/component/form_tambah_data/S_tambah', $data);
	}

	public function get_checker_by_principleId()
	{
		$id = $this->input->post('id');
		$result = $this->M_KoreksiStokBarangDraft->get_checker_by_principleId($id);
		echo json_encode($result);
	}

	public function get_BrandAndSKUInduk()
	{
		$principle_id = $this->input->post('principle_id');
		$brand = $this->M_KoreksiStokBarangDraft->get_BrandByPrincipleId($principle_id);
		$sku_induk = $this->M_KoreksiStokBarangDraft->get_SKUIndukByPrincipleId($principle_id);
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
		$result = $this->M_KoreksiStokBarangDraft->search_filter_chosen_sku($depo, $gudang, $principle, $brand, $sku_induk, $nama_sku, $sku_kode_wms, $sku_kode_pabrik);
		echo json_encode($result);
	}

	public function get_data_sku_by_id()
	{
		$id = $this->input->post('id');
		$result = $this->M_KoreksiStokBarangDraft->get_data_sku_by_id($id);
		echo json_encode($result);
	}

	public function save_data_koreksi_draft()
	{
		$tgl = $this->input->post('tgl');
		$gudang = $this->input->post('gudang');
		$principle = $this->input->post('principle');
		$checker = $this->input->post('checker');
		$tipe = $this->input->post('tipe');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan');
		$detail = $this->input->post('detail');
		$file = $this->input->post('file');
		$tipeDokumen = $this->input->post('tipeDokumen');
		$noReferensiDokumen = $this->input->post('noReferensiDokumen');

		$pengguna_id = $this->session->userdata('pengguna_id');

		$kpd_id = $this->M_Vrbl->Get_NewID();
		$kpd_id = $kpd_id[0]['NEW_ID'];

		//generate kode
		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_DKSB';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_KoreksiStokBarangDraft->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$this->db->trans_begin();

		if ($file != "undefined") {
			$uploadDirectory = "assets/images/uploads/KoreksiStokBarang/";
			$fileExtensionAllowed = ['jpeg', 'jpg', 'png', 'gif', 'JPG', 'JPEG', 'GIF', 'pdf', 'PDF', 'doc', 'DOC', 'docx', 'DOCX', 'xlsx', 'csv', 'xls'];
			$fileName = $_FILES['file']['name'];
			$fileSize = $_FILES['file']['size'];
			$fileTmpName = $_FILES['file']['tmp_name'];
			$file = explode(".", $fileName);
			$fileExtension = strtolower(end($file));
			$name_file = $file[0] . '-' . time() . '.' . $fileExtension;

			if ($fileSize > 1024000) {
				echo json_encode(2);
				return false;
			} else {
				if (!in_array($fileExtension, $fileExtensionAllowed)) {
					echo json_encode(3);
					return false;
				} else {
					//insert ke tr_koreksi_stok_draft
					$uploadPath = $uploadDirectory . $name_file;
					$didUpload = move_uploaded_file($fileTmpName, $uploadPath);
					if (!$didUpload) {
						echo json_encode(4);
						return false;
					} else {
						$this->M_KoreksiStokBarangDraft->insert_to_tr_koreksi_draft($kpd_id, $generate_kode, $tgl, $gudang, $principle, $checker, $tipe, $status, $keterangan, $tipeDokumen, $noReferensiDokumen, $name_file);
					}
				}
			}
		} else {
			//insert ke tr_koreksi_stok_draft
			$this->M_KoreksiStokBarangDraft->insert_to_tr_koreksi_draft($kpd_id, $generate_kode, $tgl, $gudang, $principle, $checker, $tipe, $status, $keterangan, $tipeDokumen, $noReferensiDokumen, null);
		}

		//insert ke tr_koreksi_stok_detail_draft
		$detail = json_decode($detail);
		foreach ($detail as $key => $value) {
			$this->M_KoreksiStokBarangDraft->insert_to_tr_koreksi_detail_draft($kpd_id, $value);
		}

		if ($status == "In Progress Approval") {
			$this->db->query("exec approval_pengajuan '$depo_id', '$pengguna_id', 'APPRV_KOREKSISTOK_01', '$kpd_id', '$generate_kode', 0, 0");
			senderPusher([
				'message' => 'insert pengajuan approval koreksi stok draft'
			]);
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
		$data['warehouses'] = $this->M_KoreksiStokBarangDraft->get_warehouses();
		$data['principles'] = $this->M_KoreksiStokBarangDraft->get_principles();
		$data['type_transactions'] = $this->M_KoreksiStokBarangDraft->get_type_transactions();
		$data['type_document'] = $this->M_KoreksiStokBarangDraft->get_type_document();
		$data['depo'] = $this->M_KoreksiStokBarangDraft->get_DepoNameBySession();

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
		$this->load->view('WMS/KoreksiStokBarangDraft/component/form_edit_data/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/KoreksiStokBarangDraft/component/form_edit_data/S_edit', $data);
	}

	public function getDataKoreksiDraftById()
	{
		$id = $this->input->post('id');
		$header = $this->M_KoreksiStokBarangDraft->getDataKoreksiDraftHeader($id);
		$detail = $this->M_KoreksiStokBarangDraft->getDataKoreksiDraftDetail($id);
		$tipeDokumen = $this->M_KoreksiStokBarangDraft->get_type_document_by_id($header->tipe_dokumen);

		echo json_encode(array('header' => $header, 'detail' => $detail, 'tipeDokumen' => $tipeDokumen));
	}

	public function edit_data_koreksi_draft()
	{
		$id = $this->input->post('id');
		$gudang = $this->input->post('gudang');
		$principle = $this->input->post('principle');
		$checker = $this->input->post('checker');
		$tipe = $this->input->post('tipe');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan');
		$kode = $this->input->post('kode');
		$detail = $this->input->post('detail');
		$lastUpdated = $this->input->post('lastUpdated');
		$file = $this->input->post('file');
		$tipeDokumen = $this->input->post('tipeDokumen');
		$noReferensiDokumen = $this->input->post('noReferensiDokumen');
		// $lastUpdated = '2023-06-09 14:35:20.487';

		$pengguna_id = $this->session->userdata('pengguna_id');
		$depo_id = $this->session->userdata('depo_id');

		$this->db->trans_begin();

		$errorNotSameLastUpdated = false;

		$getLastUpdatedDb = $this->db->select("tr_koreksi_stok_draft_tgl_update")->from("tr_koreksi_stok_draft")->where("tr_koreksi_stok_draft_id", $id)->get()->row()->tr_koreksi_stok_draft_tgl_update;

		if ($lastUpdated !== $getLastUpdatedDb) $errorNotSameLastUpdated = true;

		if ($file != 'undefined') {
			$uploadDirectory = "assets/images/uploads/KoreksiStokBarang/";
			$fileExtensionAllowed = ['jpeg', 'jpg', 'png', 'gif', 'JPG', 'JPEG', 'GIF', 'pdf', 'PDF', 'doc', 'DOC', 'docx', 'DOCX', 'xlsx', 'csv', 'xls'];
			$fileName = $_FILES['file']['name'];
			$fileSize = $_FILES['file']['size'];
			$fileTmpName = $_FILES['file']['tmp_name'];
			$file = explode(".", $fileName);
			$fileExtension = strtolower(end($file));
			$name_file = $file[0] . '-' . time() . '.' . $fileExtension;

			if ($fileSize > 1024000) {
				echo json_encode(3);
				return false;
			} else {
				if (!in_array($fileExtension, $fileExtensionAllowed)) {
					echo json_encode(4);
					return false;
				} else {
					//insert ke tr_koreksi_stok_draft
					$uploadPath = $uploadDirectory . $name_file;
					$didUpload = move_uploaded_file($fileTmpName, $uploadPath);
					if (!$didUpload) {
						echo json_encode(5);
						return false;
					} else {
						$this->M_KoreksiStokBarangDraft->update_to_tr_koreksi_draft($id, $gudang, $principle, $checker, $tipe, $status, $keterangan, $tipeDokumen, $noReferensiDokumen, $name_file);
					}
				}
			}
		} else {
			//update ke tr_koreksi_stok_draft
			$this->M_KoreksiStokBarangDraft->update_to_tr_koreksi_draft($id, $gudang, $principle, $checker, $tipe, $status, $keterangan, $tipeDokumen, $noReferensiDokumen, null);
		}

		//delete data tr_koreksi_stok_detail_draft
		$this->M_KoreksiStokBarangDraft->delete_to_tr_koreksi_detail_draft($id);

		//update / insert ke tr_koreksi_stok_detail_draft
		$detail = json_decode($detail);
		foreach ($detail as $key => $value) {
			$this->M_KoreksiStokBarangDraft->update_to_tr_koreksi_detail_draft($id, $value);
		}

		if ($status == "In Progress Approval") {
			$this->db->query("exec approval_pengajuan '$depo_id', '$pengguna_id', 'APPRV_KOREKSISTOK_01', '$id', '$kode', 0, 0");
			senderPusher([
				'message' => 'insert pengajuan approval koreksi stok draft'
			]);
		}


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else if ($errorNotSameLastUpdated) {
			$this->db->trans_rollback();
			echo json_encode(2);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
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
		$data['warehouses'] = $this->M_KoreksiStokBarangDraft->get_warehouses();
		$data['principles'] = $this->M_KoreksiStokBarangDraft->get_principles();
		$data['type_transactions'] = $this->M_KoreksiStokBarangDraft->get_type_transactions();
		$data['type_document'] = $this->M_KoreksiStokBarangDraft->get_type_document();
		$data['depo'] = $this->M_KoreksiStokBarangDraft->get_DepoNameBySession();

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
		$this->load->view('WMS/KoreksiStokBarangDraft/component/form_view_data/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/KoreksiStokBarangDraft/component/form_view_data/S_view', $data);
	}

	public function autoCompleteReferensiDokumen()
	{
		$reffDokumen = $this->input->post('reffDokumen');
		$tableName = $this->input->post('tableName');
		$tableNameKode = $this->input->post('tableNameKode');
		$tipeDokumen = $this->input->post('tipeDokumen');

		$data = $this->M_KoreksiStokBarangDraft->autoCompleteReferensiDokumen($reffDokumen, $tableName, $tableNameKode, $tipeDokumen);

		echo json_encode($data);
	}
}
