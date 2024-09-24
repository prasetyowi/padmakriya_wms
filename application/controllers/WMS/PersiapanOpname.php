<?php

use Mpdf\Tag\Th;

defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class PersiapanOpname extends ParentController
{
	// test baru
	// test baru2

	private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		// if ($this->session->has_userdata('pengguna_id') == 0) :
		//   redirect(base_url('MainPage'));
		// endif;

		$this->depo_id = $this->session->userdata('depo_id');
		$this->MenuKode = "123002000";
		$this->load->model(['M_Menu', ['WMS/M_KoreksiStokBarangDraft', 'M_KoreksiStokBarangDraft'], 'M_Function', 'M_MenuAccess', 'M_Vrbl', 'M_AutoGen']);

		$this->load->model('WMS/M_PersiapanOpname', 'M_PersiapanOpname');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation', 'pdfgenerator']);
	}

	public function PersiapanOpnameMenu()
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
		$data['rangeYear'] = range(date('Y') - 10, date('Y') + 10);
		$data['rangeMonth'] = array(
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember',
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PersiapanOpname/PersiapanOpname', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PersiapanOpname/S_PersiapanOpname', $data);
	}
	public function get_data_penanggung_jawab()
	{
		$data = $this->M_PersiapanOpname->getPenanggungJawab();
		echo json_encode($data);
	}
	public function eek()
	{
		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_KSP';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_KoreksiPallet->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);
	}

	public function get_tipe_opname()
	{
		$data = $this->M_PersiapanOpname->getTipeOpname();
		echo json_encode($data);
	}
	public function get_data_perusahaan()
	{
		$data = $this->M_PersiapanOpname->getClientWms();
		echo json_encode($data);
	}
	public function getDataDepo()
	{
		$data = $this->M_PersiapanOpname->getDataDepo();
		echo json_encode($data);
	}
	public function getDataDetailArea()
	{
		$rak_id = $this->input->post('rak_id');
		$depo_detail_id = $this->input->post('depo_detail_id');
		$tipe_stock = $this->input->post('tipe_stock');
		$client_id = $this->input->post('client_id');
		$principle = $this->input->post('principle');
		$data = $this->M_PersiapanOpname->getDataAreaDetail($rak_id, $depo_detail_id, $tipe_stock, $client_id, $principle);
		echo json_encode($data);
	}
	public function getNamaUtamaRak()
	{
		$rak_id = $this->input->post('rak_id');
		$tipe_stock = $this->input->post('tipe_stock');
		$data = $this->M_PersiapanOpname->getDetailAreaNamaByRakid($rak_id, $tipe_stock);
		echo json_encode($data);
	}
	public function get_data_rak_by_id()
	{
		$rak_id = $this->input->post('rak_id');
		$tipe_stock = $this->input->post('tipe_stock');
		$result = $this->M_PersiapanOpname->get_data_rak_by_id($rak_id, $tipe_stock);
		echo json_encode($result);
	}
	public function get_data_principle_by_client_wms_id()
	{
		$id = $this->input->post('id');
		$data = $this->M_PersiapanOpname->get_data_principle_by_client_wms_id($id);
		echo json_encode($data);
	}
	public function get_width_and_lenght_by_depo()
	{
		$id = $this->input->post('id');
		$result = $this->M_PersiapanOpname->get_width_and_lenght_by_depo($id);
		echo json_encode($result);
	}
	public function GetDepoDetailMenu()
	{
		$id = $this->input->post('id');
		$result = $this->M_PersiapanOpname->GetDepoDetailMenu($id);
		echo json_encode($result);
	}

	public function get_area_rak_gudang()
	{
		$tipe_stock = $this->input->post('tipe_stock');
		$client_wms = $this->input->post('client_wms');
		$principle = $this->input->post('principle');
		$depo = $this->input->post('depo');

		$result = $this->M_PersiapanOpname->get_area_rak_gudang($tipe_stock, $client_wms, $principle, $depo);
		echo json_encode($result);
	}

	public function GetCodeGen()
	{
		$date_now = date('Y-m-d h:i:s');
		$name_tipe_opname = $this->input->post('name_tipe_opname');
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PersiapanOpname->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $name_tipe_opname, $unit);
		echo ($generate_kode);
	}
	public function getTipeStock()
	{
		$data = $this->M_PersiapanOpname->getTipeStock();
		echo json_encode($data);
	}

	public function GetDataViewByTrOpnameID()
	{
		$id = $this->input->post("id");
		$data = $this->M_PersiapanOpname->getDataViewByTrOpnameID($id);
		echo json_encode($data);
	}
	public function GetLajurDetailId()
	{
		$arraytemp = array();
		$id = $this->input->post("id");
		foreach ($id as $key => $value) {
			array_push($arraytemp, $value['rak_lajur_detail_id']);
		}

		$data = $this->M_PersiapanOpname->getIdLajurDetailID($arraytemp);
		echo json_encode($data);
	}
	public function GetLajurDetailPaletId()
	{
		$id = $this->input->post("id");
		// foreach ($id as $item) {
		$data = $this->M_PersiapanOpname->getIdLajurPalletID($id);
		// }
		echo json_encode($data);
	}
	public function InsertOpname()
	{
		$tr_opname_plan_id = $this->input->post("tr_opname_plan_id");
		$depo_id = $this->input->post("depo_id");
		$depo_detail_id = $this->input->post("depo_detail_id");
		$tr_opname_plan_kode = $this->input->post("tr_opname_plan_kode");
		$tr_opname_plan_tanggal = $this->input->post("tr_opname_plan_tanggal");
		$client_wms_id = $this->input->post("client_wms_id");
		$karyawan_id_penanggungjawab = $this->input->post("karyawan_id_penanggungjawab");
		$principle_id = $this->input->post("principle_id");
		$tipe_stok = $this->input->post("tipe_stok");
		$tipe_opname_id = $this->input->post("tipe_opname_id");
		$name_tipe_opname = $this->input->post("name_tipe_opname");
		$tr_opname_plan_keterangan = $this->input->post("tr_opname_plan_keterangan");
		$tr_opname_plan_status = $this->input->post("tr_opname_plan_status");
		$tr_opname_plan_tgl_create = $this->input->post("tr_opname_plan_tgl_create");
		$tr_opname_plan_who_create = $this->input->post("tr_opname_plan_who_create");
		$arr_chk_detail = $this->input->post('arr_chk');

		$pengguna_id = $this->session->userdata('karyawan_id');
		$newopname_id = $this->M_Vrbl->Get_NewID();
		$newopname_id = $newopname_id[0]['NEW_ID'];

		$date_now = date('Y-m-d h:i:s');

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PersiapanOpname->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $name_tipe_opname, $unit);

		$this->db->trans_begin();

		if ($tr_opname_plan_status == "In Progress Approval") {
			$this->db->query("exec approval_pengajuan '$depo_id', '$pengguna_id', 'APPRV_PREOPNAME_01', '$newopname_id', '$generate_kode', 0, 0");
			$this->setPuhserInt();
		}

		$data = $this->M_PersiapanOpname->InsertOpname(
			$newopname_id,
			$depo_id,
			$depo_detail_id,
			$generate_kode,
			$tr_opname_plan_tanggal,
			$client_wms_id,
			$karyawan_id_penanggungjawab,
			$principle_id,
			$tipe_stok,
			$tipe_opname_id,
			$tr_opname_plan_keterangan,
			$tr_opname_plan_status,
			$tr_opname_plan_tgl_create,
			$tr_opname_plan_who_create
		);
		// if ($data == true) {
		// 	foreach ($arr_chk_detail as $key => $value) {
		// 		// var_dump($value);
		// 		$data = $this->M_PersiapanOpname->InsertDetailOpname($newopname_id, $value['rak_lajur_detail_id'], $value['principle_id'], $value['client_wms_id']);
		// 	}
		// }

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo false;
		} else {
			$this->db->trans_commit();
			echo true;
		}

		// echo json_encode($data);
	}



	public function UpdateOpname()
	{
		$tr_opname_plan_id = $this->input->post("tr_opname_plan_id");
		$depo_id = $this->input->post("depo_id");
		$depo_detail_id = $this->input->post("depo_detail_id");
		$tr_opname_plan_kode = $this->input->post("tr_opname_plan_kode");
		$tr_opname_plan_tanggal = $this->input->post("tr_opname_plan_tanggal");
		$client_wms_id = $this->input->post("client_wms_id");
		$karyawan_id_penanggungjawab = $this->input->post("karyawan_id_penanggungjawab");
		$principle_id = $this->input->post("principle_id");
		$tipe_stok = $this->input->post("tipe_stok");
		$tipe_opname_id = $this->input->post("tipe_opname_id");
		$name_tipe_opname = $this->input->post("name_tipe_opname");
		$tr_opname_plan_keterangan = $this->input->post("tr_opname_plan_keterangan");
		$tr_opname_plan_status = $this->input->post("tr_opname_plan_status");
		$tr_opname_plan_tgl_create = $this->input->post("tr_opname_plan_tgl_create");
		$tr_opname_plan_who_create = $this->input->post("tr_opname_plan_who_create");
		$arr_chk_detail = $this->input->post('arr_edit');
		$last_update_tgl = $this->input->post('last_update_tgl');

		$pengguna_id = $this->session->userdata('karyawan_id');
		// $newopname_id = $this->M_Vrbl->Get_NewID();
		// $newopname_id = $newopname_id[0]['NEW_ID'];

		$date_now = date('Y-m-d h:i:s');

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PersiapanOpname->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $name_tipe_opname, $unit);

		$isNotSameLastUpd = false;
		$lastUpdateBaru = $this->M_PersiapanOpname->Get_LastUpdateOpname($tr_opname_plan_id);
		if ($last_update_tgl == $lastUpdateBaru['tglUpd']) $isNotSameLastUpd = true;

		if ($isNotSameLastUpd) {
			$this->db->trans_begin();

			if ($tr_opname_plan_status == "In Progress Approval") {
				$this->db->query("exec approval_pengajuan '$depo_id', '$pengguna_id', 'APPRV_PREOPNAME_01', '$tr_opname_plan_id', '$generate_kode', 0, 0");
				$this->setPuhserInt();
			}

			$data = $this->M_PersiapanOpname->UpdateOpname(
				$tr_opname_plan_id,
				$depo_id,
				$depo_detail_id,
				$generate_kode,
				$tr_opname_plan_tanggal,
				$client_wms_id,
				$karyawan_id_penanggungjawab,
				$principle_id,
				$tipe_stok,
				$tipe_opname_id,
				$tr_opname_plan_keterangan,
				$tr_opname_plan_status,
				$tr_opname_plan_tgl_create,
				$tr_opname_plan_who_create
			);
			// if ($data == true) {
			// 	$data = $this->M_PersiapanOpname->DeleteDetailOpname($tr_opname_plan_id);
			// 	foreach ($arr_chk_detail as $key => $value) {
			// 		// var_dump($value);

			// 		$data = $this->M_PersiapanOpname->InsertDetailOpname($tr_opname_plan_id, $value['rak_lajur_detail_id'], $value['principle_id'], $value['client_wms_id']);
			// 	}
			// }

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo false;
			} else {
				$this->db->trans_commit();
				echo true;
			}
		} else {
			echo json_encode(203);
		}

		// echo json_encode($data);
	}

	public function setPuhserInt()
	{
		senderPusher([
			'message' => 'insert pengajuan approval persiapan opname draft'
		]);
	}


	public function get_DataPersiapanOpname()
	{
		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		$perusahaan = $this->input->post('perusahaan');
		$principle = $this->input->post('principle');
		// $tipe_penerimaan = $this->input->post('tipe_penerimaan');
		$tipestock = $this->input->post('status');

		$data = $this->M_PersiapanOpname->getDataPersiapanOpname($perusahaan, $tahun, $bulan, $principle, $tipestock);
		echo json_encode($data);
	}

	public function getEditDataById()
	{
		$id = $this->input->post("id");
		$data = $this->M_PersiapanOpname->getDataTrOpnameById($id);
		echo json_encode($data);
	}
	public function getDataDetailByOpnameID()
	{
		$id = $this->input->post("id");
		$data = $this->M_PersiapanOpname->getDataDetailEditByOpnameID($id);
		echo json_encode($data);
	}
	// public function cekDataTemporary()
	// {
	// 	$data = $this->input->post("data");
	// 	$arrEditDetail = $this->input->post("arrEditDetail");
	// 	$data = $this->M_PersiapanOpname->getDataDetailEditByOpnameID($id);
	// 	echo json_encode($data);
	// }

	public function add()
	{

		$id = $_GET['id'];

		$data = array();
		$data['ekspedisis'] = $this->M_PenerimaanBarang->getExpedisi();
		$data['gudangs'] = $this->M_PenerimaanBarang->getGudangByDepoId();
		$data['header'] = $this->M_PenerimaanBarang->getDataheaderPb($id);

		//getSuratjalanId
		$arrSjId = [];
		$datasSjId = $this->db->select("penerimaan_surat_jalan_id as id")->from("penerimaan_pembelian_detail3")->where("penerimaan_pembelian_id", $id)->get()->result();
		foreach ($datasSjId as $key => $value) {
			array_push($arrSjId, $value->id);
		}

		$data['checker'] = $this->M_PenerimaanBarang->get_checker_by_depo($arrSjId);

		// echo json_encode($this->session->userdata('depo_id'));
		// die;

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// $data['tipe_penerimaan'] = $this->M_PenerimaanSuratJalan->getTipePenerimaan();

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',
			Get_Assets_Url() . '/node_modules/lightbox2/src/css/lightbox.css'
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js',
			Get_Assets_Url() . '/node_modules/lightbox2/src/js/lightbox.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PersiapanOpname/PenerimaanBarang', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PenerimaanBarang/S_PenerimaanBarang', $data);
	}

	public function batalkan()
	{
		$id = $this->input->post('id');
		$data = $this->M_PersiapanOpname->batalkan($id);
		echo json_encode($data);
	}

	public function print_serah_terima()
	{

		$id = $this->input->get('id');
		$data['atas'] = $this->M_PersiapanOpname->getDataTrOpnameByIdCetak($id);
		// $data['detail'] = $this->M_PersiapanOpname->getDataDetailEditByOpnameID($id);
		$data['detail'] = $this->M_PersiapanOpname->getDataDetailEditByOpnameIDCetak($id);

		//title dari pdf
		$this->data['title_pdf'] = 'Laporan Serah Terima Barang';

		// //filename dari pdf ketika didownload
		$file_pdf = 'report_distribusipengiriman' . date('d-M-Y H:i:s');
		// //setting paper
		$paper = 'A4';
		// //orientasi paper potrait / landscape
		$orientation = "landscape";

		$html = $this->load->view('WMS/PersiapanOpname/view_cetak_persiapan_opname', $data, true);
		$tes = "";
		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}
}
