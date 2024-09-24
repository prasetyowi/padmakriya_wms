<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class DeliveryOrderBatch extends ParentController
{
	private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		// echo "<pre>".print_r($_SESSION, 1)."</pre>";
		// die();

		if ($this->session->has_userdata('pengguna_id') == 0) :
			redirect(base_url('MainPage'));
		endif;

		$this->MenuKode = "135003000";

		$this->load->model("WMS/M_DeliveryOrderBatch", 'M_DeliveryOrderBatch');
		$this->load->model("WMS/M_DeliveryOrder", 'M_DeliveryOrder');
		$this->load->model("WMS/M_DeliveryOrderDetail", 'M_DeliveryOrderDetail');
		$this->load->model("WMS/M_DeliveryOrderProgress", 'M_DeliveryOrderProgress');
		$this->load->model("M_TipePengiriman");
		$this->load->model("M_Area");
		$this->load->model("M_SKU");
		$this->load->model("M_TipeEkspedisi");
		$this->load->model("M_TipeLayanan");
		$this->load->model("M_StatusProgress");
		$this->load->model("M_ClientPt");
		$this->load->model('M_DepoDetail');
		$this->load->model('M_Kendaraan');
		$this->load->model("M_Karyawan");
		$this->load->model("M_StatusProgress");
		$this->load->model("M_TipeDeliveryOrder");
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('M_DataTable');

		$this->load->library(['form_validation', 'pdfgenerator']);
	}

	public function index()
	{
		$this->load->model('M_Menu');


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
			Get_Assets_Url() . 'assets/js/dataTables.rowsGroup.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();
		$data['Area'] = $this->M_DeliveryOrderBatch->GetArea();
		$data['TipePelayanan'] = $this->M_DeliveryOrderBatch->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_DeliveryOrderBatch->GetTipeDeliveryOrder();
		$data['TipePengiriman'] = $this->M_DeliveryOrderBatch->GetTipePengiriman();
		$data['Status'] = $this->M_DeliveryOrderBatch->GetStatusProgress();
		$data['Kendaraan'] = $this->M_DeliveryOrderBatch->GetKendaraan();
		$data['Driver'] = $this->M_DeliveryOrderBatch->GetDriver();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/DeliveryOrderBatch/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/DeliveryOrderBatch/script', $data);
	}

	public function create()
	{
		$this->load->model('M_Menu');

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
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css',
			Get_Assets_Url() . 'assets/css/theme.blue.css'
		);

		$query['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'assets/js/jquery.tablesorter.js',
			Get_Assets_Url() . 'assets/js/jquery.tablesorter.widgets.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			"https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$data['TipePelayanan'] = $this->M_DeliveryOrderBatch->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_DeliveryOrderBatch->GetTipeDeliveryOrder();
		$data['Area'] = $this->M_DeliveryOrderBatch->GetArea();
		$data['TipeEkspedisi'] = $this->M_DeliveryOrderBatch->GetTipeEkspedisi();
		$data['Kendaraan'] = $this->M_DeliveryOrderBatch->GetKendaraanByID($this->input->get('kendaraan'));
		$data['Driver'] = $this->M_DeliveryOrderBatch->GetDriverByID($this->input->get('driver'));
		$data['Gudang'] = $this->M_DeliveryOrderBatch->GetGudang();
		$delivery_order_temp_id = $this->M_Vrbl->Get_NewID();
		$data['do_temp_id'] = $delivery_order_temp_id[0]['NEW_ID'];
		$delivery_order_batch_id = $this->M_Vrbl->Get_NewID();
		$data['do_batch_id'] = $delivery_order_batch_id[0]['NEW_ID'];
		$data['vrbl'] = $this->M_DeliveryOrderBatch->GetVrbl();
		$data['vrblLocation'] = $this->M_DeliveryOrderBatch->GetVrblLocation();

		$data['UseMap'] = $this->M_Vrbl->Get_Vrbl_By_Param('USE_MAP');

		$data['Segmentasi1'] = $this->M_DeliveryOrderBatch->getSegmentasi(null);

		$tglKirim = date('d-m-Y', strtotime($this->input->get('tgl_kirim')));
		$kendaraan = $this->input->get('kendaraan');
		$driver = $this->input->get('driver');
		$ritasi = $this->input->get('ritasi');
		$mode = $this->input->get('mode');

		$data['tgl_kirim'] = $tglKirim;
		$data['ritasi'] = $ritasi;
		$data['mode'] = $mode;

		if ($mode == 'last') {
			$data['ritasiByID'] = $this->M_DeliveryOrderBatch->getRitasi($tglKirim, $kendaraan, $driver, $ritasi);
		}

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/DeliveryOrderBatch/form', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/DeliveryOrderBatch/script', $data);
	}

	public function edit()
	{
		$this->load->model('M_Menu');

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
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css',
			Get_Assets_Url() . 'assets/css/theme.blue.css'
		);

		$query['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'assets/js/jquery.tablesorter.js',
			Get_Assets_Url() . 'assets/js/jquery.tablesorter.widgets.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			"https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$id = $this->input->get('id');
		$data['id'] = $this->input->get('id');
		$data['FDJR'] = $this->M_DeliveryOrderBatch->GetHeaderFDJRById($id);
		$data['FdjrArea'] = $this->M_DeliveryOrderBatch->GetHeaderFdjrAreaById($id);
		$data['rowFDJR'] = $this->M_DeliveryOrderBatch->GetRowHeaderFDJRById($id);
		$data['DO_SKU'] = $this->M_DeliveryOrderBatch->GetSKUSummaryFDJRById($id);
		// $data['DO'] = $this->M_DeliveryOrderBatch->GetDetailFDJRById($id);
		$data['TipePelayanan'] = $this->M_DeliveryOrderBatch->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_DeliveryOrderBatch->GetTipeDeliveryOrder();
		$data['Area'] = $this->M_DeliveryOrderBatch->GetArea();
		$data['TipeEkspedisi'] = $this->M_DeliveryOrderBatch->GetTipeEkspedisi();
		$data['Kendaraan'] = $this->M_DeliveryOrderBatch->GetKendaraan();
		$data['Driver'] = $this->M_DeliveryOrderBatch->GetDriver();
		$data['Gudang'] = $this->M_DeliveryOrderBatch->GetGudang();
		$data['vrbl'] = $this->M_DeliveryOrderBatch->GetVrbl();
		$data['vrblLocation'] = $this->M_DeliveryOrderBatch->GetVrblLocation();

		$data['Segmentasi1'] = $this->M_DeliveryOrderBatch->getSegmentasi(null);

		// echo json_encode($data['Area']);
		// die;

		$do = $this->M_DeliveryOrderBatch->GetDetailFDJRById($id);
		$delivery_order_temp_id = $this->M_Vrbl->Get_NewID();
		$delivery_order_temp_id = $delivery_order_temp_id[0]['NEW_ID'];
		foreach ($do as $value) {
			$this->M_DeliveryOrderBatch->InsertDOTemp($delivery_order_temp_id, $value['delivery_order_batch_id'], $value['delivery_order_draft_id']);

			$arr[] = "'" . $value['delivery_order_draft_id'] . "'";
		}


		$DO_SKU = $this->M_DeliveryOrderBatch->GetSKUSummaryFDJRById($id);

		$arr = [];
		foreach ($DO_SKU as $value) {
			if (!isset($arr[$value['group_carton']])) {
				$arr[$value['group_carton']] = ['group' => $value['group_carton'], 'total_pcs' => floatval($value['total_konversi_pcs']), 'sku_konversi_faktor_carton' => floatval($value['sku_konversi_faktor_carton'])];
			} else {
				$arr[$value['group_carton']]['total_pcs'] +=  floatval($value['total_konversi_pcs']);
			}
		}

		$hasil = 0;
		foreach ($arr as $value) {
			$nilai = floatval($value['total_pcs']) / floatval($value['sku_konversi_faktor_carton'] <= 0 ? 1 : $value['sku_konversi_faktor_carton']);
			$hasil = $hasil + $nilai;
		}

		$data['totalComposite'] = "Total " . ceil($hasil) . " Krt";

		// $data['totalComposite'] = $this->M_DeliveryOrderBatch->GetSelectedDODraftSKU2($arr);

		// $dataDO = $this->M_DeliveryOrderBatch->GetDOTempByBatchNoUrut("'" . $delivery_order_temp_id . "'", null, "'" . $id . "'");

		// $fixData = [];
		// if ($data != 0) {
		// 	foreach ($dataDO as $key => $value) {
		// 		$area = $this->db->select("delivery_order_detail_draft.sku_qty, sku.sku_satuan")->from("delivery_order_detail_draft")
		// 			->join("sku", "delivery_order_detail_draft.sku_id = sku.sku_id", "left")
		// 			->where("delivery_order_detail_draft.delivery_order_draft_id", $value['delivery_order_draft_id'])->get()->result();

		// 		$arrArea = [];

		// 		foreach ($area as $key => $val) {
		// 			$arrArea[] = $val->sku_qty . " " . preg_replace('/\d+/u', '', $val->sku_satuan);
		// 		}

		// 		array_push($fixData, [
		// 			'delivery_order_no_urut_rute' => $value['delivery_order_no_urut_rute'],
		// 			'delivery_order_batch_id' => $value['delivery_order_batch_id'],
		// 			'delivery_order_temp_id' => $value['delivery_order_temp_id'],
		// 			'client_pt_id' => $value['client_pt_id'],
		// 			'delivery_order_draft_kirim_nama' => $value['delivery_order_draft_kirim_nama'],
		// 			'delivery_order_draft_kirim_telp' => $value['delivery_order_draft_kirim_telp'],
		// 			'delivery_order_draft_kirim_alamat' => $value['delivery_order_draft_kirim_alamat'],
		// 			'client_pt_latitude' => $value['client_pt_latitude'],
		// 			'client_pt_longitude' => $value['client_pt_longitude'],
		// 			'sku_weight' => $value['sku_weight'],
		// 			'sku_volume' => $value['sku_volume'],
		// 			'product' => $arrArea
		// 		]);
		// 	}
		// }
		$data['DO'] = $this->M_DeliveryOrderBatch->GetDOTempByBatchNoUrut("'" . $delivery_order_temp_id . "'", null, "'" . $id . "'");

		$data['temp_id'] = $delivery_order_temp_id;

		$data['UseMap'] = $this->M_Vrbl->Get_Vrbl_By_Param('USE_MAP');

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/DeliveryOrderBatch/edit', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/DeliveryOrderBatch/script', $data);
	}

	public function detail()
	{
		$this->load->model('M_Menu');

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
			Get_Assets_Url() . 'assets/js/jquery.tablesorter.js',
			Get_Assets_Url() . 'assets/js/jquery.tablesorter.widgets.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			"https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$id = $this->input->get('id');
		$data['FDJR'] = $this->M_DeliveryOrderBatch->GetHeaderFDJRById($id);
		$data['FdjrArea'] = $this->M_DeliveryOrderBatch->GetHeaderFdjrAreaById($id);
		$data['rowFDJR'] = $this->M_DeliveryOrderBatch->GetRowHeaderFDJRById($id);

		$data['DO_SKU'] = $this->M_DeliveryOrderBatch->GetSKUSummaryFDJRById($id);
		$data['TipePelayanan'] = $this->M_DeliveryOrderBatch->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_DeliveryOrderBatch->GetTipeDeliveryOrder();
		$data['Area'] = $this->M_DeliveryOrderBatch->GetArea();
		$data['TipeEkspedisi'] = $this->M_DeliveryOrderBatch->GetTipeEkspedisi();
		$data['Kendaraan'] = $this->M_DeliveryOrderBatch->GetKendaraan();
		$data['Driver'] = $this->M_DeliveryOrderBatch->GetDriver();
		$data['Gudang'] = $this->M_DeliveryOrderBatch->GetGudang();

		$data['UseMap'] = $this->M_Vrbl->Get_Vrbl_By_Param('USE_MAP');

		// $dataDO = $this->M_DeliveryOrderBatch->GetDetailFDJRByAlamat($id);

		// $fixData = [];
		// if ($dataDO != 0) {
		// 	foreach ($dataDO as $key => $value) {
		// 		$area = $this->db->select("delivery_order_detail_draft.sku_qty, sku.sku_satuan")
		// 			->from("delivery_order")
		// 			->join("delivery_order_detail_draft", "delivery_order.delivery_order_draft_id = delivery_order_detail_draft.delivery_order_draft_id", "left")
		// 			->join("sku", "delivery_order_detail_draft.sku_id = sku.sku_id", "left")
		// 			->where("delivery_order.delivery_order_batch_id", $value['delivery_order_batch_id'])->get()->result();

		// 		$arrArea = [];

		// 		foreach ($area as $key => $val) {
		// 			$arrArea[] = $val->sku_qty . " " . preg_replace('/\d+/u', '', $val->sku_satuan);
		// 		}

		// 		array_push($fixData, [
		// 			'delivery_order_batch_id' => $value['delivery_order_batch_id'],
		// 			'delivery_order_no_urut_rute' => $value['delivery_order_no_urut_rute'],
		// 			'delivery_order_kirim_nama' => $value['delivery_order_kirim_nama'],
		// 			'delivery_order_kirim_alamat' => $value['delivery_order_kirim_alamat'],
		// 			'delivery_order_kirim_telp' => $value['delivery_order_kirim_telp'],
		// 			'delivery_order_kirim_latitude' => $value['delivery_order_kirim_latitude'],
		// 			'delivery_order_kirim_longitude' => $value['delivery_order_kirim_longitude'],
		// 			'sku_weight' => $value['sku_weight'],
		// 			'sku_volume' => $value['sku_volume'],
		// 			'product' => $arrArea
		// 		]);
		// 	}
		// }
		$data['DO'] = $this->M_DeliveryOrderBatch->GetDetailFDJRByAlamat($id);
		$doID = $this->M_DeliveryOrderBatch->GetDOIDByDOBatch($id);

		foreach ($doID as $value) {
			$arr[] = "'" . $value['delivery_order_id'] . "'";
		}

		$DO_SKU = $this->M_DeliveryOrderBatch->GetSKUSummaryFDJRById($id);

		$arr = [];
		foreach ($DO_SKU as $value) {
			if (!isset($arr[$value['group_carton']])) {
				$arr[$value['group_carton']] = ['group' => $value['group_carton'], 'total_pcs' => floatval($value['total_konversi_pcs']), 'sku_konversi_faktor_carton' => floatval($value['sku_konversi_faktor_carton'])];
			} else {
				$arr[$value['group_carton']]['total_pcs'] += floatval($value['total_konversi_pcs']);
			}
		}

		$hasil = 0;
		foreach ($arr as $value) {
			$nilai = floatval($value['total_pcs']) / floatval($value['sku_konversi_faktor_carton'] <= 0 ? 1 : $value['sku_konversi_faktor_carton']);
			$hasil = $hasil + $nilai;
		}

		$data['totalComposite'] = "Total " . ceil($hasil) . " Krt";

		// $data['totalComposite'] = $this->M_DeliveryOrderBatch->GetTotalComposite($arr);

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/DeliveryOrderBatch/detail', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/DeliveryOrderBatch/script', $data);
	}

	//Page for re - Assigment driver
	public function reAssigmentDriver()
	{
		$this->load->model('M_Menu');

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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			"https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$id = $this->input->get('id');
		$data['delivery_order_batch_id'] = $this->input->get('id');
		$data['FDJR'] = $this->M_DeliveryOrderBatch->GetHeaderFDJRById($id);
		$data['DO'] = $this->M_DeliveryOrderBatch->GetDetailFDJRById($id);
		$data['DO_SKU'] = $this->M_DeliveryOrderBatch->GetSKUSummaryFDJRById($id);
		$data['TipePelayanan'] = $this->M_DeliveryOrderBatch->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_DeliveryOrderBatch->GetTipeDeliveryOrder();
		$data['Area'] = $this->M_DeliveryOrderBatch->GetArea();
		$data['TipeEkspedisi'] = $this->M_DeliveryOrderBatch->GetTipeEkspedisi();
		$data['Kendaraan'] = $this->M_DeliveryOrderBatch->GetKendaraan();
		$data['Driver'] = $this->M_DeliveryOrderBatch->GetDriver();
		$data['Gudang'] = $this->M_DeliveryOrderBatch->GetGudang();

		// var_dump($aaa["kendaraan_id"]);

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/DeliveryOrderBatch/reAssigmentDriver', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/DeliveryOrderBatch/script', $data);
	}

	public function GetDeliveryOrderBatchByFilter()
	{
		$tgl = explode(" - ", $this->input->post('do_batch_date_filter'));
		$tglkirim = explode(" - ", $this->input->post('do_pengiriman_date_filter'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$tgl3 = date('Y-m-d', strtotime(str_replace("/", "-", $tglkirim[0])));
		$tgl4 = date('Y-m-d', strtotime(str_replace("/", "-", $tglkirim[1])));

		$do_batch_number = $this->input->post('do_batch_number_filter');
		$tipe_pengiriman = $this->input->post('tipe_pengiriman_filter');
		$do_pengiriman_date = $this->input->post('do_pengiriman_date_filter');
		$tipe_pelayanan = $this->input->post('tipe_pelayanan_filter');
		$tipe_delivery_order = $this->input->post('tipe_delivery_order_filter');
		$pengemasan = $this->input->post('pengemasan_filter');
		$area = $this->input->post('area_filter');
		$status = $this->input->post('status_filter');

		if ($do_batch_number == "") {
			$do_batch_number = "";
		} else {
			$do_batch_number = "AND do.delivery_order_batch_kode LIKE '%" . $do_batch_number . "%' ";
		}

		// if ($tipe_pengiriman == "") {
		// 	$tipe_pengiriman = "";
		// } else {
		// 	$tipe_pengiriman = "AND do.tipe_pengiriman = '$tipe_pengiriman' ";
		// }
		if ($tipe_delivery_order == "") {
			$tipe_delivery_order = "";
		} else {
			$tipe_delivery_order = "AND do.tipe_delivery_order_id = '$tipe_delivery_order' ";
		}

		if ($tipe_pelayanan == "") {
			$tipe_pelayanan = "";
		} else {
			$tipe_pelayanan = "AND do.delivery_order_batch_tipe_layanan_id = '$tipe_pelayanan' ";
		}

		if ($area == "") {
			$area = "";
		} else {
			$area = "AND delivery_order_area.area_id = '$area' ";
		}

		if ($status == "") {
			$status = "";
		} else {
			$status = "AND do.delivery_order_batch_status = '$status' ";
		}

		$sql = "SELECT DISTINCT ROW_NUMBER () OVER (ORDER BY format(do.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') DESC, kendaraan.kendaraan_nopol, karyawan.karyawan_nama, delivery_order_batch_h_ritasi ASC) AS idx,
		do.delivery_order_batch_id
		,do.delivery_order_batch_kode
		,do.delivery_order_batch_tipe_layanan_id
		,do.delivery_order_batch_tipe_layanan_nama
		,ISNULL(dobh.kendaraan_berat_gr_max, 0) AS berat_max
		,ISNULL(dobh.kendaraan_volume_cm3_max, 0) AS volume_max
		,ISNULL(do.kendaraan_berat_gr_terpakai, 0) AS berat_terpakai
		,ISNULL(do.kendaraan_volume_cm3_terpakai, 0) AS volume_terpakai
		,COUNT(DISTINCT (do2.delivery_order_id)) AS total_outlet
		,FORMAT(do.delivery_order_batch_tanggal, 'dd-MM-yyyy') AS delivery_order_batch_tanggal
		,FORMAT(do.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') AS delivery_order_batch_tanggal_kirim
		,do.tipe_delivery_order_id
		,do.delivery_order_batch_status
		,tipe_delivery_order.tipe_delivery_order_alias
		,karyawan.karyawan_nama
		,dobh.delivery_order_batch_h_ritasi
		,kendaraan.kendaraan_nopol
		,do.delivery_order_batch_update_tgl
		FROM delivery_order_batch do
		LEFT JOIN delivery_order_batch_h dobh
			ON do.delivery_order_batch_h_id = dobh.delivery_order_batch_h_id
		LEFT JOIN delivery_order do2
			ON do.delivery_order_batch_id = do2.delivery_order_batch_id
		LEFT JOIN delivery_order_area
			ON do.delivery_order_batch_id = delivery_order_area.delivery_order_batch_id
		--LEFT JOIN tipe_pengiriman ON tipe_pengiriman.tipe_pengiriman_id = do.tipe_pengiriman_id
		LEFT JOIN tipe_delivery_order
			ON tipe_delivery_order.tipe_delivery_order_id = do.tipe_delivery_order_id
		LEFT JOIN karyawan
			ON dobh.karyawan_id = karyawan.karyawan_id
		LEFT JOIN kendaraan
			ON dobh.kendaraan_id = kendaraan.kendaraan_id
		WHERE format(do.delivery_order_batch_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
		AND format(do.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') BETWEEN '$tgl3' AND '$tgl4'
		AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
		" . $do_batch_number . "
		" . $tipe_pelayanan . "
		" . $area . "
		" . $status . "
		" . $tipe_delivery_order . "
		GROUP BY do.delivery_order_batch_id,
		do.delivery_order_batch_kode,
		do.delivery_order_batch_tipe_layanan_id,
		do.delivery_order_batch_tipe_layanan_nama,
		ISNULL(dobh.kendaraan_berat_gr_max, 0),
		ISNULL(dobh.kendaraan_volume_cm3_max, 0),
		ISNULL(do.kendaraan_berat_gr_terpakai, 0),
		ISNULL(do.kendaraan_volume_cm3_terpakai, 0),
		format(do.delivery_order_batch_tanggal, 'dd-MM-yyyy'),
		format(do.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy'),
		do.tipe_delivery_order_id,
		do.delivery_order_batch_status,
		tipe_delivery_order.tipe_delivery_order_alias,
		karyawan.karyawan_nama,
		kendaraan.kendaraan_nopol,
		dobh.delivery_order_batch_h_ritasi,
		do.delivery_order_batch_update_tgl";

		$response = $this->M_DataTable->dtTableGetList($sql);

		// $data = $this->M_DeliveryOrderBatch->GetDeliveryOrderBatchByFilter($tgl1, $tgl2, $do_batch_number, $tgl3, $tgl4, $tipe_pelayanan, $pengemasan, $area, $status, $tipe_delivery_order);
		$fixData = [];
		if (COUNT($response['data']) != 0) {
			foreach ($response['data'] as $key => $value) {
				$area = $this->db->select("area.area_nama")->from("delivery_order_area")
					->join("area", "delivery_order_area.area_id = area.area_id", "left")
					->where("delivery_order_area.delivery_order_batch_id", $value['delivery_order_batch_id'])->get()->result();

				$arrArea = [];

				foreach ($area as $key => $val) {
					$arrArea[] = $val->area_nama;
				}

				array_push($fixData, [
					'delivery_order_batch_id' => $value['delivery_order_batch_id'],
					'delivery_order_batch_kode' => $value['delivery_order_batch_kode'],
					'delivery_order_batch_tipe_layanan_id' => $value['delivery_order_batch_tipe_layanan_id'],
					'delivery_order_batch_tipe_layanan_nama' => $value['delivery_order_batch_tipe_layanan_nama'],
					'delivery_order_batch_tanggal' => $value['delivery_order_batch_tanggal'],
					'delivery_order_batch_tanggal_kirim' => $value['delivery_order_batch_tanggal_kirim'],
					'delivery_order_batch_update_tgl' => $value['delivery_order_batch_update_tgl'],
					'tipe_delivery_order_id' => $value['tipe_delivery_order_id'],
					'tipe_delivery_order_alias' => $value['tipe_delivery_order_alias'],
					// 'tipe_pengiriman_nama_tipe' => $value['tipe_pengiriman_nama_tipe'],
					'delivery_order_batch_status' => $value['delivery_order_batch_status'],
					'karyawan_nama' => $value['karyawan_nama'],
					'kendaraan_nopol' => $value['kendaraan_nopol'],
					'area' => $arrArea,
					'berat_max' => $value['berat_max'],
					'volume_max' => $value['volume_max'],
					'berat_terpakai' => $value['berat_terpakai'],
					'volume_terpakai' => $value['volume_terpakai'],
					'total_outlet' => $value['total_outlet'],
					'delivery_order_batch_h_ritasi' => $value['delivery_order_batch_h_ritasi']
				]);
			}
		}

		$output = array(
			"draw" => $response['draw'],
			"recordsTotal" => $response['recordsTotal'],
			"recordsFiltered" => $response['recordsFiltered'],
			"data" => $fixData,
		);

		echo json_encode($output);
	}

	public function GetDODraftByFilter()
	{
		$tgl = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tgl'))));
		$area = $this->input->post('area');
		$tipe = $this->input->post('tipe');
		$tipe_layanan = $this->input->post('tipe_layanan');
		$kelas_jalan = $this->input->post('kelas_jalan');
		$kendaraan = $this->input->post('kendaraan');

		$data = $this->M_DeliveryOrderBatch->GetDODraftByFilter($tgl, $area, $tipe, $tipe_layanan, $kelas_jalan, $kendaraan);

		$sourceData = $data;
		$result = [];

		if ($sourceData != 0) {
			foreach ($sourceData as $item) {
				$key = $item['delivery_order_draft_kirim_nama'] . $item['delivery_order_draft_kirim_alamat'] . $item['delivery_order_draft_kirim_kecamatan'] . $item['delivery_order_draft_kirim_area'];

				if (!isset($result[$key])) {
					$result[$key] = $item;
				} else {
					$result[$key]['sales_order_no_po'] .= ", " . $item['sales_order_no_po'];
					$result[$key]['principle_kode'] .= ", " . $item['principle_kode'];
					$result[$key]['sku_weight'] += $item['sku_weight'];
					$result[$key]['sku_volume'] += $item['sku_volume'];
				}
			}

			// Ubah array asosiatif hasil menjadi array numerik
			$result = array_values($result);
		} else {
			$result = 0;
		}

		echo json_encode($result);
	}

	public function GetDODraftByAlamat()
	{
		$tgl = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tgl'))));
		$area = $this->input->post('area');
		$tipe = $this->input->post('tipe');
		$tipe_layanan = $this->input->post('tipe_layanan');
		$alamat = $this->input->post('alamat');
		$nama = $this->input->post('nama');
		$do_temp_id = $this->input->post('do_temp_id');
		$do_batch_id = $this->input->post('do_batch_id');

		$data = $this->M_DeliveryOrderBatch->GetDODraftByAlamat($tgl, $area, $tipe, $tipe_layanan, $alamat, $nama);

		// INSERT TABLE DELIVERY_ORDER_TEMP
		foreach ($data as $value) {

			$data = $this->M_DeliveryOrderBatch->InsertDOTemp($do_temp_id, $do_batch_id, $value['delivery_order_draft_id']);
		}

		$data2 = $this->M_DeliveryOrderBatch->GetDOTempByBatch("'" . $do_temp_id . "'", $alamat, $nama);

		// $fixData = [];
		// if ($data != 0) {
		// 	foreach ($data as $key => $value) {
		// 		$area = $this->db->select("delivery_order_detail_draft.sku_qty, sku.sku_satuan")->from("delivery_order_detail_draft")
		// 			->join("sku", "delivery_order_detail_draft.sku_id = sku.sku_id", "left")
		// 			->where("delivery_order_detail_draft.delivery_order_draft_id", $value['delivery_order_draft_id'])->get()->result();

		// 		$arrArea = [];

		// 		foreach ($area as $key => $val) {
		// 			$arrArea[] = $val->sku_qty . " " . preg_replace('/\d+/u', '', $val->sku_satuan);
		// 		}

		// 		array_push($fixData, [
		// 			'delivery_order_batch_id' => $value['delivery_order_batch_id'],
		// 			'delivery_order_temp_id' => $value['delivery_order_temp_id'],
		// 			'client_pt_id' => $value['client_pt_id'],
		// 			'delivery_order_draft_kirim_nama' => $value['delivery_order_draft_kirim_nama'],
		// 			'delivery_order_draft_kirim_telp' => $value['delivery_order_draft_kirim_telp'],
		// 			'delivery_order_draft_kirim_alamat' => $value['delivery_order_draft_kirim_alamat'],
		// 			'client_pt_latitude' => $value['client_pt_latitude'],
		// 			'client_pt_longitude' => $value['client_pt_longitude'],
		// 			'sku_weight' => $value['sku_weight'],
		// 			'sku_volume' => $value['sku_volume'],
		// 			'product' => $arrArea
		// 		]);
		// 	}
		// }

		echo json_encode($data2);
	}

	public function GetDODraftByAlamat2()
	{
		$tgl = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tgl'))));
		$area = $this->input->post('area');
		$tipe = $this->input->post('tipe');
		$tipe_layanan = $this->input->post('tipe_layanan');
		$alamat = $this->input->post('alamat');
		$nama = $this->input->post('nama');
		$do_temp_id = $this->input->post('do_temp_id');
		$do_batch_id = $this->input->post('do_batch_id');

		$data = $this->M_DeliveryOrderBatch->GetDODraftByAlamat($tgl, $area, $tipe, $tipe_layanan, $alamat, $nama);

		$arr = [];
		foreach ($data as $value) {
			$arr[] = "'" . $value['delivery_order_draft_id'] . "'";
		}

		// $data2 = $this->M_DeliveryOrderBatch->GetSelectedDODraftSKU2($arr);
		// $str = rtrim($data2['composite'], "; ");

		$data2 = $this->M_DeliveryOrderBatch->GetSelectedDODraftSKU($arr);

		if ($data2 != 0) {
			$arrComposite = [];
			foreach ($data2 as $value) {
				if (!isset($arrComposite[$value['group_carton']])) {
					$arrComposite[$value['group_carton']] = ['group' => $value['group_carton'], 'total_pcs' => floatval($value['total_konversi_pcs']), 'sku_konversi_faktor_carton' => floatval($value['sku_konversi_faktor_carton'])];
				} else {
					$arrComposite[$value['group_carton']]['total_pcs'] += floatval($value['total_konversi_pcs']);
				}
			}

			$hasil = 0;
			foreach ($arrComposite as $value) {
				$nilai = floatval($value['total_pcs']) / floatval($value['sku_konversi_faktor_carton'] <= 0 ? 1 : $value['sku_konversi_faktor_carton']);
				$hasil = $hasil + $nilai;
			}
		} else {
			$hasil = 0;
		}

		// $sku_konversi_temp_id = $this->M_Vrbl->Get_NewID();
		// $sku_konversi_temp_id = $sku_konversi_temp_id[0]['NEW_ID'];

		// //insert sku_konversi_temp
		// foreach ($data2 as $val) {
		// 	$insert = $this->M_DeliveryOrderBatch->insertSKUKonversiTemp($sku_konversi_temp_id, $val['sku_id'], $val['sku_qty'], $val['sku_qty'] * round($val['sku_konversi_faktor']));
		// }

		// //exec procedure proses_konversi_composite
		// $hasilExec = $this->M_DeliveryOrderBatch->execProsesKonversiComposite($sku_konversi_temp_id);

		// $karton = 0;
		// $pack = 0;
		// $pcs = 0;
		// foreach ($hasilExec as $value2) {
		// 	$explode = explode("/", $value2['Ratio']);

		// 	if (COUNT($explode) == 3) {
		// 		$karton = $karton + (int)$explode[0];
		// 		$pack = $pack + (int)$explode[1];
		// 		$pcs = $pcs + (int)$explode[2];
		// 	} else {
		// 		$karton = $karton + (int)$explode[0];
		// 		$pcs = $pcs + (int)$explode[1];
		// 	}
		// }

		// $response = [
		// 	'karton' => $karton,
		// 	'pack' => $pack,
		// 	'pcs' => $pcs
		// ];

		echo json_encode("Total " . ceil($hasil) . " Krt");
	}

	public function GetAreaSimulasi()
	{
		$tgl_kirim = $this->input->post('tgl_kirim');
		$tipe_layanan = $this->input->post('tipe_layanan');
		$tipe = $this->input->post('tipe');
		$segmentasi1 = $this->input->post('segmentasi1');
		$segmentasi2 = $this->input->post('segmentasi2');
		$segmentasi3 = $this->input->post('segmentasi3');
		$mode = $this->input->post('mode');
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');

		// $areaWilayah = $this->M_DeliveryOrderBatch->GetAreaWilayahSimulasi($tgl_kirim, $tipe_layanan, $tipe);
		$area = $this->M_DeliveryOrderBatch->GetAreaSimulasi($tgl_kirim, $tipe_layanan, $tipe, $segmentasi1, $segmentasi2, $segmentasi3, $mode, $delivery_order_batch_id);

		$arr = [];
		$jumlahOutlet = 0;
		$jumlahDO = 0;
		$berat = 0;
		$volume = 0;
		if ($area != 0) {
			foreach ($area as $val) {
				if (!isset($arr[$val['area_wilayah']])) {
					$arr[$val['area_wilayah']] = ['area_wilayah' => $val['area_wilayah'], 'jml_do' => $val['jml_do'], 'jml_outlet' => $val['jml_outlet'], 'berat' => $val['sku_weight'], 'volume' => $val['sku_volume']];
				} else {
					$arr[$val['area_wilayah']]['jml_do'] += $val['jml_do'];
					$arr[$val['area_wilayah']]['jml_outlet'] += $val['jml_outlet'];
					$arr[$val['area_wilayah']]['berat'] += $val['sku_weight'];
					$arr[$val['area_wilayah']]['volume'] += $val['sku_volume'];
				}

				$jumlahOutlet += $val['jml_outlet'];
				$jumlahDO += $val['jml_do'];
				$berat += $val['sku_weight'];
				$volume += $val['sku_volume'];
			}

			$areaWilayah = array_values($arr);
		} else {
			$areaWilayah = 0;
		}

		$response = [
			'area_wilayah' => $areaWilayah,
			'area' => $area,
			'jumlahOutlet' => $jumlahOutlet,
			'jumlahDO' => $jumlahDO,
			'berat' => $berat,
			'volume' => $volume,
		];

		echo json_encode($response);
	}

	public function GetDOTempByIDTempWithNoUrut()
	{
		$temp_do = $this->input->post('temp_do');
		$alamat_do = $this->input->post('alamat_do');
		$nama_do = $this->input->post('nama_do');
		$alamat_by_nourut = $this->input->post('no_urut');

		$case = '';
		foreach ($alamat_by_nourut as $value) {
			$alamat = $value['alamat_do'];
			$nama = $value['nama_do'];
			$nourut = $value['no_urut'];
			$case .= "WHEN do.delivery_order_draft_kirim_alamat = " . $alamat . " AND do.delivery_order_draft_kirim_nama = " . $nama . " THEN " . $nourut . "";
		}

		$data = $this->M_DeliveryOrderBatch->GetDOTempByIDTempWithNoUrut($temp_do, $alamat_do, $case, $nama_do);

		// $fixData = [];
		// if ($data != 0) {
		// 	foreach ($data as $key => $value) {
		// 		$area = $this->db->select("delivery_order_detail_draft.sku_qty, sku.sku_satuan")->from("delivery_order_detail_draft")
		// 			->join("sku", "delivery_order_detail_draft.sku_id = sku.sku_id", "left")
		// 			->where("delivery_order_detail_draft.delivery_order_draft_id", $value['delivery_order_draft_id'])->get()->result();

		// 		$arrArea = [];

		// 		foreach ($area as $key => $val) {
		// 			$arrArea[] = $val->sku_qty . " " . preg_replace('/\d+/u', '', $val->sku_satuan);
		// 		}

		// 		array_push($fixData, [
		// 			'delivery_order_batch_id' => $value['delivery_order_batch_id'],
		// 			'delivery_order_temp_id' => $value['delivery_order_temp_id'],
		// 			'client_pt_id' => $value['client_pt_id'],
		// 			'delivery_order_draft_kirim_nama' => $value['delivery_order_draft_kirim_nama'],
		// 			'delivery_order_draft_kirim_telp' => $value['delivery_order_draft_kirim_telp'],
		// 			'delivery_order_draft_kirim_alamat' => $value['delivery_order_draft_kirim_alamat'],
		// 			'no_urut' => $value['no_urut'],
		// 			'client_pt_latitude' => $value['client_pt_latitude'],
		// 			'client_pt_longitude' => $value['client_pt_longitude'],
		// 			'sku_weight' => $value['sku_weight'],
		// 			'sku_volume' => $value['sku_volume'],
		// 			'product' => $arrArea
		// 		]);
		// 	}
		// }

		echo json_encode($data);
	}

	public function GetDOTempByIDTemp()
	{
		$temp_do = $this->input->post('temp_do');
		$alamat_do = $this->input->post('alamat_do');
		$nama_do = $this->input->post('nama_do');

		$data = $this->M_DeliveryOrderBatch->GetDOTempByBatch($temp_do, $alamat_do, $nama_do);

		// $fixData = [];
		// if ($data != 0) {
		// 	foreach ($data as $key => $value) {
		// 		$area = $this->db->select("delivery_order_detail_draft.sku_qty, sku.sku_satuan")->from("delivery_order_detail_draft")
		// 			->join("sku", "delivery_order_detail_draft.sku_id = sku.sku_id", "left")
		// 			->where("delivery_order_detail_draft.delivery_order_draft_id", $value['delivery_order_draft_id'])->get()->result();

		// 		$arrArea = [];

		// 		foreach ($area as $key => $val) {
		// 			$arrArea[] = $val->sku_qty . " " . preg_replace('/\d+/u', '', $val->sku_satuan);
		// 		}

		// 		array_push($fixData, [
		// 			'delivery_order_batch_id' => $value['delivery_order_batch_id'],
		// 			'delivery_order_temp_id' => $value['delivery_order_temp_id'],
		// 			'client_pt_id' => $value['client_pt_id'],
		// 			'delivery_order_draft_kirim_nama' => $value['delivery_order_draft_kirim_nama'],
		// 			'delivery_order_draft_kirim_telp' => $value['delivery_order_draft_kirim_telp'],
		// 			'delivery_order_draft_kirim_alamat' => $value['delivery_order_draft_kirim_alamat'],
		// 			'client_pt_latitude' => $value['client_pt_latitude'],
		// 			'client_pt_longitude' => $value['client_pt_longitude'],
		// 			'sku_weight' => $value['sku_weight'],
		// 			'sku_volume' => $value['sku_volume'],
		// 			'product' => $arrArea
		// 		]);
		// 	}
		// }

		echo json_encode($data);
	}

	public function GetDOTempByAlamat()
	{
		$temp_do = $this->input->post('temp_do');
		$alamat_do = $this->input->post('alamat_do');

		$data = $this->M_DeliveryOrderBatch->GetDOTempByBatch($temp_do, $alamat_do);

		echo json_encode($data);
	}

	public function GetAreaByTgl()
	{
		$tgl_kirim = $this->input->post('tgl_kirim');
		$do_batch_id = $this->input->post('do_batch_id');
		$mode = $this->input->post('mode');

		$data = $this->M_DeliveryOrderBatch->GetAreaByTgl($tgl_kirim, $do_batch_id, $mode);

		echo json_encode($data);
	}


	public function GetSelectedDODraft()
	{
		$do_id = $this->input->post('do_id');

		$data = $this->M_DeliveryOrderBatch->GetSelectedDODraft($do_id);

		echo json_encode($data);
	}

	public function GetIDDOByBatch()
	{
		$temp_do = $this->input->post('temp_do');
		$alamat_do = $this->input->post('alamat_do');
		$nama_do = $this->input->post('nama_do');

		$data = $this->M_DeliveryOrderBatch->GetIDDOByBatch($temp_do, $alamat_do, $nama_do);

		echo json_encode($data);
	}

	public function GetDetailIDDOByBatch()
	{
		$batch_do = $this->input->post('batch_do');
		$alamat_do = $this->input->post('alamat_do');

		$data = $this->M_DeliveryOrderBatch->GetDetailIDDOByBatch($batch_do, $alamat_do);

		echo json_encode($data);
	}

	public function GetIDDOByBatchByNoUrut()
	{
		$temp_do = $this->input->post('temp_do');
		$alamat_do = $this->input->post('alamat_do');
		$nama_do = $this->input->post('nama_do');
		$alamat_by_nourut = $this->input->post('alamat_by_nourut');

		$case = '';
		foreach ($alamat_by_nourut as $value) {
			$alamat = $value['alamat_do'];
			$nama = $value['nama_do'];
			$nourut = $value['no_urut'];
			$case .= "WHEN delivery_order_draft_kirim_alamat = " . $alamat . " AND delivery_order_draft_kirim_nama = " . $nama . "  THEN " . $nourut . "";
		}

		$data = $this->M_DeliveryOrderBatch->GetIDDOByBatchByNoUrut($temp_do, $alamat_do, $case, $nama_do);

		echo json_encode($data);
	}

	public function GetSelectedDODraftSKU()
	{
		$do_id = $this->input->post('do_id');

		$data = $this->M_DeliveryOrderBatch->GetSelectedDODraftSKU($do_id);

		if ($data != 0) {
			$arr = [];
			foreach ($data as $value) {
				if (!isset($arr[$value['group_carton']])) {
					$arr[$value['group_carton']] = ['group' => $value['group_carton'], 'total_pcs' => floatval($value['total_konversi_pcs']), 'sku_konversi_faktor_carton' => floatval($value['sku_konversi_faktor_carton'])];
				} else {
					$arr[$value['group_carton']]['total_pcs'] += floatval($value['total_konversi_pcs']);
				}
			}

			$hasil = 0;
			foreach ($arr as $value) {
				$nilai = floatval($value['total_pcs']) / floatval($value['sku_konversi_faktor_carton'] <= 0 ? 1 : $value['sku_konversi_faktor_carton']);
				$hasil = $hasil + $nilai;
			}
		} else {
			$hasil = 0;
		}

		// $data2 = $this->M_DeliveryOrderBatch->GetSelectedDODraftSKU2($do_id);
		// $str = rtrim($data2['composite'], "; ");

		$response = [
			'data' => $data,
			'composite' => "Total " . ceil($hasil) . " Krt"
		];

		echo json_encode($response);
	}

	public function GetSelectedDODraftSKUDetail()
	{
		$sku_id = $this->input->post('sku_id');
		$dod_id = $this->input->post('dod_id');

		$data = $this->M_DeliveryOrderBatch->GetSelectedDODraftSKUDetail($dod_id, $sku_id);

		echo json_encode($data);
	}

	public function GetSelectedDOSKUDetail()
	{
		$sku_id = $this->input->post('sku_id');
		$fdjr_id = $this->input->post('fdjr_id');

		$data = $this->M_DeliveryOrderBatch->GetSelectedDOSKUDetail($fdjr_id, $sku_id);

		echo json_encode($data);
	}

	public function GetKapasitasTerpakai()
	{
		$do_id = $this->input->post('do_id');
		$kendaraan_id = $this->input->post('kendaraan_id');

		$data = $this->M_DeliveryOrderBatch->GetKapasitasTerpakai($do_id);

		// if ($kendaraan_id != null) {
		// 	$byDriver = $this->M_DeliveryOrderBatch->GetKapasitasTerpakaiByDriver($kendaraan_id);

		// 	if ($byDriver != 0) {
		// 		$data['sku_weight'] += $byDriver['beratTerpakai'];
		// 		$data['sku_volume'] += $byDriver['volumeTerpakai'];
		// 	}
		// }

		echo json_encode($data);
	}

	public function GetKapasitasKendaraan()
	{
		$kendaraan = $this->input->post('kendaraan');

		if ($kendaraan != '') {
			$data = $this->M_DeliveryOrderBatch->GetKapasitasKendaraan($kendaraan);
		} else {
			$data = 0;
		}

		echo json_encode($data);
	}

	//Function for update and insert into other db 
	public function UpreAssigmentDriver()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('kendaraan_id_pengganti', 'Kendaraan', 'required');
		$this->form_validation->set_rules('karyawan_id_pengganti', 'Driver', 'required');
		$this->form_validation->set_rules('reason', 'Reason', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());
			echo json_encode(validation_errors());
		} else {

			$this->db->trans_begin();

			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$depo_id = $this->input->post('depo_id');
			$delivery_order_batch_create_who = $this->input->post('delivery_order_batch_create_who');
			$kendaraan_id_awal = $this->input->post('kendaraan_id_awal');
			$karyawan_id_awal = $this->input->post('karyawan_id_awal');
			$kendaraan_id_pengganti = $this->input->post('kendaraan_id_pengganti');
			$karyawan_id_pengganti = $this->input->post('karyawan_id_pengganti');
			$reason = $this->input->post('reason');
			$tglLastUpdate = $this->input->post('tglLastUpdate');
			$delivery_order_batch_log_id = $this->M_Vrbl->Get_NewID();
			$delivery_order_batch_log_id = $delivery_order_batch_log_id[0]['NEW_ID'];

			$lastUpdatedChecked = checkLastUpdatedData((object) [
				'table' => "delivery_order_batch",
				'whereField' => "delivery_order_batch_id",
				'whereValue' => $delivery_order_batch_id,
				'fieldDateUpdate' => "delivery_order_batch_update_tgl",
				'fieldWhoUpdate' => "delivery_order_batch_update_who",
				'lastUpdated' => $tglLastUpdate
			]);

			//insert command
			$result = $this->M_DeliveryOrderBatch->insertNewData($delivery_order_batch_log_id, $delivery_order_batch_id, $depo_id, $delivery_order_batch_create_who, $kendaraan_id_awal, $karyawan_id_awal, $kendaraan_id_pengganti, $karyawan_id_pengganti, $reason);

			// var_dump($result);
			// die;

			$kendaraan_id_pengganti = $this->input->post('kendaraan_id_pengganti');
			$karyawan_id_pengganti = $this->input->post('karyawan_id_pengganti');
			$kendaraan_volume_cm3_max = $this->input->post('kendaraan_volume_cm3_max');
			$kendaraan_berat_gr_max = $this->input->post('kendaraan_berat_gr_max');
			//update command
			$result2 = $this->M_DeliveryOrderBatch->updateNewData($kendaraan_id_pengganti, $karyawan_id_pengganti, $kendaraan_volume_cm3_max, $kendaraan_berat_gr_max);

			echo json_encode(responseJson((object)[
				'lastUpdatedChecked' => $lastUpdatedChecked,
				'status' => 'Disimpan'
			]));
		}
	}

	public function insert_delivery_order_batch()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('tipe_delivery_order_id', 'Tipe', 'required');
		$this->form_validation->set_rules('delivery_order_batch_tipe_layanan_id', 'Tipe Layanan', 'required');
		$this->form_validation->set_rules('tipe_ekspedisi_id', 'Tipe Ekspedisi', 'required');
		$this->form_validation->set_rules('kendaraan_id', 'Kendaraan', 'required');
		$this->form_validation->set_rules('karyawan_id', 'Driver', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo json_encode(validation_errors());
		} else {
			// $delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			// $delivery_order_batch_kode = $this->input->post('delivery_order_batch_kode');
			$unit_mandiri_id = $this->input->post('unit_mandiri_id');
			$client_wms_id = $this->input->post('client_wms_id');
			$depo_id = $this->input->post('depo_id');
			$depo_detail_id = $this->input->post('depo_detail_id');
			$delivery_order_batch_ritasi = $this->input->post('delivery_order_batch_ritasi');
			$delivery_order_batch_tanggal = $this->input->post('delivery_order_batch_tanggal');
			$delivery_order_batch_tanggal_kirim = $this->input->post('delivery_order_batch_tanggal_kirim');
			$tipe_pengiriman_id = $this->input->post('tipe_pengiriman_id');
			$delivery_order_batch_tipe_layanan_id = $this->input->post('delivery_order_batch_tipe_layanan_id');
			$delivery_order_batch_tipe_layanan_no = $this->input->post('delivery_order_batch_tipe_layanan_no');
			$delivery_order_batch_tipe_layanan_nama = $this->input->post('delivery_order_batch_tipe_layanan_nama');
			$tipe_delivery_order_id = $this->input->post('tipe_delivery_order_id');
			$delivery_order_batch_is_need_packing = $this->input->post('delivery_order_batch_is_need_packing');
			$delivery_order_batch_create_who = $this->input->post('delivery_order_batch_create_who');
			$delivery_order_batch_create_tgl = $this->input->post('delivery_order_batch_create_tgl');
			$area_id = $this->input->post('area_id');
			$delivery_order_batch_status = $this->input->post('delivery_order_batch_status');
			$kendaraan_id = $this->input->post('kendaraan_id');
			$karyawan_id = $this->input->post('karyawan_id');
			$tipe_ekspedisi_id = $this->input->post('tipe_ekspedisi_id');
			$kendaraan_volume_cm3_max = $this->input->post('kendaraan_volume_cm3_max');
			$kendaraan_berat_gr_max = $this->input->post('kendaraan_berat_gr_max');
			$kendaraan_volume_cm3_terpakai = $this->input->post('kendaraan_volume_cm3_terpakai');
			$kendaraan_berat_gr_terpakai = $this->input->post('kendaraan_berat_gr_terpakai');
			$kendaraan_volume_cm3_sisa = $this->input->post('kendaraan_volume_cm3_sisa');
			$kendaraan_berat_gr_sisa = $this->input->post('kendaraan_berat_gr_sisa');
			$kendaraan_km_awal = $this->input->post('kendaraan_km_awal');
			$kendaraan_km_akhir = $this->input->post('kendaraan_km_akhir');
			$kendaraan_km_terpakai = $this->input->post('kendaraan_km_terpakai');
			$picking_list_id = $this->input->post('picking_list_id');
			$picking_order_id = $this->input->post('picking_order_id');
			$serah_terima_kirim_id = $this->input->post('serah_terima_kirim_id');
			$list_do = $this->input->post('list_do');
			$delivery_order_batch_id = $this->input->post('batch');
			$ritasi = $this->input->post('ritasi');
			$beratBatch = $this->input->post('beratBatch');
			$volumeBatch = $this->input->post('volumeBatch');

			// $delivery_order_batch_id = $this->M_Vrbl->Get_NewID();
			// $delivery_order_batch_id = $delivery_order_batch_id[0]['NEW_ID'];

			// generate kode
			$date_now = date('Y-m-d h:i:s');
			$param =  'KODE_FDJR';
			$vrbl = $this->M_Vrbl->Get_Kode($param);
			$prefix = $vrbl->vrbl_kode;
			// get prefik depo
			$depo_id = $this->session->userdata('depo_id');
			$depoPrefix = $this->M_DeliveryOrderBatch->getDepoPrefix($depo_id);
			$unit = $depoPrefix->depo_kode_preffix;
			$delivery_order_batch_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

			// echo var_dump($list_do);

			$tmpData = '';
			foreach ($list_do as $key => $value) {
				$dod = $this->M_DeliveryOrderBatch->GetDODraftById($value['delivery_order_draft_id'], $value['no_urut']);

				foreach ($dod as $key => $value2) {
					$do_draft_id = $value2['delivery_order_draft_id'];
					$checkData = $this->db->query("SELECT delivery_order_id FROM delivery_order WHERE delivery_order_draft_id = '$do_draft_id'")->row_array();

					if (!empty($checkData)) {
						$tmpData = $checkData;
					}
				}
			}

			if (!empty($tmpData)) {
				echo json_encode(400);
			} else {
				$this->db->trans_begin();

				//cek ritasi
				$checkRitasi = $this->M_DeliveryOrderBatch->checkRitasi($delivery_order_batch_tanggal_kirim, $karyawan_id, $kendaraan_id, $ritasi, $kendaraan_volume_cm3_max, $kendaraan_berat_gr_max, $kendaraan_volume_cm3_terpakai, $kendaraan_berat_gr_terpakai, $kendaraan_volume_cm3_sisa, $kendaraan_berat_gr_sisa);

				if ($checkRitasi != 0) {
					$this->M_DeliveryOrderBatch->updateRitasi($checkRitasi['delivery_order_batch_h_id'], intval($kendaraan_volume_cm3_terpakai), intval($kendaraan_berat_gr_terpakai), $kendaraan_volume_cm3_sisa, $kendaraan_berat_gr_sisa);
					$dobhID = $checkRitasi['delivery_order_batch_h_id'];
				} else {
					$dobhID = $this->M_Vrbl->Get_NewID();
					$dobhID = $dobhID[0]['NEW_ID'];

					$this->M_DeliveryOrderBatch->insertRitasi($dobhID, $delivery_order_batch_tanggal_kirim, $karyawan_id, $kendaraan_id, $ritasi, $kendaraan_volume_cm3_max, $kendaraan_berat_gr_max, $kendaraan_volume_cm3_terpakai, $kendaraan_berat_gr_terpakai, $kendaraan_volume_cm3_sisa, $kendaraan_berat_gr_sisa);
				}

				//insert ke idelivery_order_batch
				$this->M_DeliveryOrderBatch->insert_delivery_order_batch($delivery_order_batch_id, $delivery_order_batch_kode, $unit_mandiri_id, $client_wms_id, $depo_id, $depo_detail_id, $delivery_order_batch_ritasi, $delivery_order_batch_tanggal, $delivery_order_batch_tanggal_kirim, $tipe_pengiriman_id, $delivery_order_batch_tipe_layanan_id, $delivery_order_batch_tipe_layanan_no, $delivery_order_batch_tipe_layanan_nama, $tipe_delivery_order_id, $delivery_order_batch_is_need_packing, $delivery_order_batch_create_who, $delivery_order_batch_create_tgl, $delivery_order_batch_status, $kendaraan_id, $karyawan_id, $tipe_ekspedisi_id, $kendaraan_volume_cm3_max, $kendaraan_berat_gr_max, $volumeBatch, $beratBatch, $kendaraan_volume_cm3_sisa, $kendaraan_berat_gr_sisa, $kendaraan_km_awal, $kendaraan_km_akhir, $kendaraan_km_terpakai, $picking_list_id, $picking_order_id, $serah_terima_kirim_id, $dobhID);

				foreach ($area_id as $key => $value) {
					$this->db->set("delivery_order_area_id", "NEWID()", FALSE);
					$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
					$this->db->set("area_id", $value);

					$this->db->insert("delivery_order_area");
				}

				foreach ($list_do as $key => $value) {

					$dod = $this->M_DeliveryOrderBatch->GetDODraftById($value['delivery_order_draft_id'], $value['no_urut']);
					//insert ke delivery_order
					//insert ke delivery_order_detail
					foreach ($dod as $key => $value2) {
						$do_id = $this->M_Vrbl->Get_NewID();
						$do_id = $do_id[0]['NEW_ID'];

						//generate kode
						$date_now = date('Y-m-d h:i:s');
						$param =  'KODE_DO';
						$vrbl = $this->M_Vrbl->Get_Kode($param);
						$prefix = $vrbl->vrbl_kode;
						// get prefik depo
						$depo_id = $this->session->userdata('depo_id');
						$depoPrefix = $this->M_DeliveryOrderBatch->getDepoPrefix($depo_id);
						$unit = $depoPrefix->depo_kode_preffix;
						$do_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

						$this->M_DeliveryOrderBatch->insert_delivery_order($delivery_order_batch_id, $do_id, $do_kode, $value2, "insert", $delivery_order_batch_tanggal_kirim);

						$dodd = $this->M_DeliveryOrderBatch->GetDODraftDetailById($value['delivery_order_draft_id']);

						foreach ($dodd as $key => $value3) {
							$dod_id = $this->M_Vrbl->Get_NewID();
							$dod_id = $dod_id[0]['NEW_ID'];

							$this->M_DeliveryOrderBatch->insert_delivery_order_detail($value3['delivery_order_detail_draft_id'], $do_id, $delivery_order_batch_id, $value3);
						}
					}
				}

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					echo json_encode(0);
				} else {
					$this->db->trans_commit();
					echo json_encode(1);
				}
			}
		}
	}

	public function update_delivery_order_batch()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		// $this->form_validation->set_rules('area_id', 'Area', 'required');
		$this->form_validation->set_rules('tipe_delivery_order_id', 'Tipe', 'required');
		$this->form_validation->set_rules('delivery_order_batch_tipe_layanan_id', 'Tipe Layanan', 'required');
		$this->form_validation->set_rules('tipe_ekspedisi_id', 'Tipe Ekspedisi', 'required');
		$this->form_validation->set_rules('kendaraan_id', 'Kendaraan', 'required');
		$this->form_validation->set_rules('karyawan_id', 'Driver', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo json_encode(validation_errors());
		} else {
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$delivery_order_batch_kode = $this->input->post('delivery_order_batch_kode');
			$unit_mandiri_id = $this->input->post('unit_mandiri_id');
			$client_wms_id = $this->input->post('client_wms_id');
			$depo_id = $this->input->post('depo_id');
			$depo_detail_id = $this->input->post('depo_detail_id');
			$delivery_order_batch_ritasi = $this->input->post('delivery_order_batch_ritasi');
			$delivery_order_batch_tanggal = $this->input->post('delivery_order_batch_tanggal');
			$delivery_order_batch_tanggal_kirim = $this->input->post('delivery_order_batch_tanggal_kirim');
			$tipe_pengiriman_id = $this->input->post('tipe_pengiriman_id');
			$delivery_order_batch_tipe_layanan_id = $this->input->post('delivery_order_batch_tipe_layanan_id');
			$delivery_order_batch_tipe_layanan_no = $this->input->post('delivery_order_batch_tipe_layanan_no');
			$delivery_order_batch_tipe_layanan_nama = $this->input->post('delivery_order_batch_tipe_layanan_nama');
			$tipe_delivery_order_id = $this->input->post('tipe_delivery_order_id');
			$delivery_order_batch_is_need_packing = $this->input->post('delivery_order_batch_is_need_packing');
			$delivery_order_batch_create_who = $this->input->post('delivery_order_batch_create_who');
			$delivery_order_batch_create_tgl = $this->input->post('delivery_order_batch_create_tgl');
			$area = $this->input->post('area');
			$delivery_order_batch_status = $this->input->post('delivery_order_batch_status');
			$kendaraan_id = $this->input->post('kendaraan_id');
			$karyawan_id = $this->input->post('karyawan_id');
			$tipe_ekspedisi_id = $this->input->post('tipe_ekspedisi_id');
			$kendaraan_volume_cm3_max = $this->input->post('kendaraan_volume_cm3_max');
			$kendaraan_berat_gr_max = $this->input->post('kendaraan_berat_gr_max');
			$kendaraan_volume_cm3_terpakai = $this->input->post('kendaraan_volume_cm3_terpakai');
			$kendaraan_berat_gr_terpakai = $this->input->post('kendaraan_berat_gr_terpakai');
			$kendaraan_volume_cm3_sisa = $this->input->post('kendaraan_volume_cm3_sisa');
			$kendaraan_berat_gr_sisa = $this->input->post('kendaraan_berat_gr_sisa');
			$kendaraan_km_awal = $this->input->post('kendaraan_km_awal');
			$kendaraan_km_akhir = $this->input->post('kendaraan_km_akhir');
			$kendaraan_km_terpakai = $this->input->post('kendaraan_km_terpakai');
			$picking_list_id = $this->input->post('picking_list_id');
			$picking_order_id = $this->input->post('picking_order_id');
			$serah_terima_kirim_id = $this->input->post('serah_terima_kirim_id');
			$list_do = $this->input->post('list_do');
			$temp_do = $this->input->post('temp_do');
			$tglLastUpdate = $this->input->post('tglLastUpdate');
			$ritasiID = $this->input->post('ritasiID');
			$beratBatch = $this->input->post('beratBatch');
			$volumeBatch = $this->input->post('volumeBatch');

			$this->db->trans_begin();

			$lastUpdatedChecked = checkLastUpdatedData((object) [
				'table' => "delivery_order_batch",
				'whereField' => "delivery_order_batch_id",
				'whereValue' => $delivery_order_batch_id,
				'fieldDateUpdate' => "delivery_order_batch_update_tgl",
				'fieldWhoUpdate' => "delivery_order_batch_update_who",
				'lastUpdated' => $tglLastUpdate
			]);

			//update table ritasi
			$this->M_DeliveryOrderBatch->updateRitasi($ritasiID, intval($kendaraan_volume_cm3_terpakai), intval($kendaraan_berat_gr_terpakai), $kendaraan_volume_cm3_sisa, $kendaraan_berat_gr_sisa);

			//insert ke idelivery_order_batch
			$this->M_DeliveryOrderBatch->update_delivery_order_batch($delivery_order_batch_id, $delivery_order_batch_kode, $unit_mandiri_id, $client_wms_id, $depo_id, $depo_detail_id, $delivery_order_batch_ritasi, $delivery_order_batch_tanggal, $delivery_order_batch_tanggal_kirim, $tipe_pengiriman_id, $delivery_order_batch_tipe_layanan_id, $delivery_order_batch_tipe_layanan_no, $delivery_order_batch_tipe_layanan_nama, $tipe_delivery_order_id, $delivery_order_batch_is_need_packing, $delivery_order_batch_create_who, $delivery_order_batch_create_tgl, $delivery_order_batch_status, $kendaraan_id, $karyawan_id, $tipe_ekspedisi_id, $kendaraan_volume_cm3_max, $kendaraan_berat_gr_max, $volumeBatch, $beratBatch, $kendaraan_volume_cm3_sisa, $kendaraan_berat_gr_sisa, $kendaraan_km_awal, $kendaraan_km_akhir, $kendaraan_km_terpakai, $picking_list_id, $picking_order_id, $serah_terima_kirim_id);

			$this->db->delete('delivery_order_area', ['delivery_order_batch_id' => $delivery_order_batch_id]);

			foreach ($area as $key => $value) {
				$this->db->set("delivery_order_area_id", "NEWID()", FALSE);
				$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
				$this->db->set("area_id", $value);

				$this->db->insert("delivery_order_area");
			}

			foreach ($list_do as $key => $value) {

				$dod = $this->M_DeliveryOrderBatch->GetDODraftById($value['delivery_order_draft_id'], $value['no_urut']);
				$cek_do = $this->M_DeliveryOrderBatch->Check_DO($value['delivery_order_draft_id'], $delivery_order_batch_id);

				if ($cek_do == 0) {

					//insert ke delivery_order
					//insert ke delivery_order_detail
					foreach ($dod as $key => $value2) {
						$do_id = $this->M_Vrbl->Get_NewID();
						$do_id = $do_id[0]['NEW_ID'];

						//generate kode
						$date_now = date('Y-m-d h:i:s');
						$param =  'KODE_DO';
						$vrbl = $this->M_Vrbl->Get_Kode($param);
						$prefix = $vrbl->vrbl_kode;
						// get prefik depo
						$depo_id = $this->session->userdata('depo_id');
						$depoPrefix = $this->M_DeliveryOrderBatch->getDepoPrefix($depo_id);
						$unit = $depoPrefix->depo_kode_preffix;
						$do_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

						$this->M_DeliveryOrderBatch->insert_delivery_order($delivery_order_batch_id, $do_id, $do_kode, $value2, "insert", $delivery_order_batch_tanggal_kirim);

						$dodd = $this->M_DeliveryOrderBatch->GetDODraftDetailById($value['delivery_order_draft_id']);

						foreach ($dodd as $key => $value3) {
							$dod_id = $this->M_Vrbl->Get_NewID();
							$dod_id = $dod_id[0]['NEW_ID'];

							$this->M_DeliveryOrderBatch->insert_delivery_order_detail($value3['delivery_order_detail_draft_id'], $do_id, $delivery_order_batch_id, $value3);
						}
					}
				} else {
					$this->M_DeliveryOrderBatch->update_delivery_order($delivery_order_batch_id, $value['delivery_order_draft_id'], $value['no_urut'], "insert", null, $delivery_order_batch_tanggal_kirim);
				}
			}

			//delete do (cek table do not exist table do temp)
			$cekDO = $this->M_DeliveryOrderBatch->GetDONotExists($temp_do, $delivery_order_batch_id);
			if ($cekDO != 0) {
				foreach ($cekDO as $key => $value) {
					$query = $this->M_DeliveryOrderBatch->DeleteDONotExists($value['delivery_order_id']);
				}
			}

			echo json_encode(responseJson((object)[
				'lastUpdatedChecked' => $lastUpdatedChecked,
				'status' => 'Disimpan'
			]));

			// if ($this->db->trans_status() === FALSE) {
			// 	$this->db->trans_rollback();
			// 	echo json_encode(0);
			// } else {
			// 	$this->db->trans_commit();
			// 	echo json_encode(1);
			// }
		}
	}

	public function confirm_delivery_order_batch()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		// $this->form_validation->set_rules('area_id', 'Area', 'required');
		$this->form_validation->set_rules('tipe_delivery_order_id', 'Tipe', 'required');
		$this->form_validation->set_rules('delivery_order_batch_tipe_layanan_id', 'Tipe Layanan', 'required');
		$this->form_validation->set_rules('tipe_ekspedisi_id', 'Tipe Ekspedisi', 'required');
		$this->form_validation->set_rules('kendaraan_id', 'Kendaraan', 'required');
		$this->form_validation->set_rules('karyawan_id', 'Driver', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo json_encode(validation_errors());
		} else {
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$delivery_order_batch_kode = $this->input->post('delivery_order_batch_kode');
			$unit_mandiri_id = $this->input->post('unit_mandiri_id');
			$client_wms_id = $this->input->post('client_wms_id');
			$depo_id = $this->input->post('depo_id');
			$depo_detail_id = $this->input->post('depo_detail_id');
			$delivery_order_batch_ritasi = $this->input->post('delivery_order_batch_ritasi');
			$delivery_order_batch_tanggal = $this->input->post('delivery_order_batch_tanggal');
			$delivery_order_batch_tanggal_kirim = $this->input->post('delivery_order_batch_tanggal_kirim');
			$tipe_pengiriman_id = $this->input->post('tipe_pengiriman_id');
			$delivery_order_batch_tipe_layanan_id = $this->input->post('delivery_order_batch_tipe_layanan_id');
			$delivery_order_batch_tipe_layanan_no = $this->input->post('delivery_order_batch_tipe_layanan_no');
			$delivery_order_batch_tipe_layanan_nama = $this->input->post('delivery_order_batch_tipe_layanan_nama');
			$tipe_delivery_order_id = $this->input->post('tipe_delivery_order_id');
			$delivery_order_batch_is_need_packing = $this->input->post('delivery_order_batch_is_need_packing');
			$delivery_order_batch_create_who = $this->input->post('delivery_order_batch_create_who');
			$delivery_order_batch_create_tgl = $this->input->post('delivery_order_batch_create_tgl');
			$area = $this->input->post('area');
			$delivery_order_batch_status = $this->input->post('delivery_order_batch_status');
			$kendaraan_id = $this->input->post('kendaraan_id');
			$karyawan_id = $this->input->post('karyawan_id');
			$tipe_ekspedisi_id = $this->input->post('tipe_ekspedisi_id');
			$kendaraan_volume_cm3_max = $this->input->post('kendaraan_volume_cm3_max');
			$kendaraan_berat_gr_max = $this->input->post('kendaraan_berat_gr_max');
			$kendaraan_volume_cm3_terpakai = $this->input->post('kendaraan_volume_cm3_terpakai');
			$kendaraan_berat_gr_terpakai = $this->input->post('kendaraan_berat_gr_terpakai');
			$kendaraan_volume_cm3_sisa = $this->input->post('kendaraan_volume_cm3_sisa');
			$kendaraan_berat_gr_sisa = $this->input->post('kendaraan_berat_gr_sisa');
			$kendaraan_km_awal = $this->input->post('kendaraan_km_awal');
			$kendaraan_km_akhir = $this->input->post('kendaraan_km_akhir');
			$kendaraan_km_terpakai = $this->input->post('kendaraan_km_terpakai');
			$picking_list_id = $this->input->post('picking_list_id');
			$picking_order_id = $this->input->post('picking_order_id');
			$serah_terima_kirim_id = $this->input->post('serah_terima_kirim_id');
			$list_do = $this->input->post('list_do');
			$tglLastUpdate = $this->input->post('tglLastUpdate');
			$beratBatch = $this->input->post('beratBatch');
			$volumeBatch = $this->input->post('volumeBatch');
			$ritasiID = $this->input->post('ritasiID');

			$this->db->trans_begin();

			$lastUpdatedChecked = checkLastUpdatedData((object) [
				'table' => "delivery_order_batch",
				'whereField' => "delivery_order_batch_id",
				'whereValue' => $delivery_order_batch_id,
				'fieldDateUpdate' => "delivery_order_batch_update_tgl",
				'fieldWhoUpdate' => "delivery_order_batch_update_who",
				'lastUpdated' => $tglLastUpdate
			]);

			//update table ritasi
			$this->M_DeliveryOrderBatch->updateRitasi($ritasiID, intval($kendaraan_volume_cm3_terpakai), intval($kendaraan_berat_gr_terpakai), $kendaraan_volume_cm3_sisa, $kendaraan_berat_gr_sisa);

			//insert ke idelivery_order_batch
			$this->M_DeliveryOrderBatch->confirm_delivery_order_batch($delivery_order_batch_id, $delivery_order_batch_kode, $unit_mandiri_id, $client_wms_id, $depo_id, $depo_detail_id, $delivery_order_batch_ritasi, $delivery_order_batch_tanggal, $delivery_order_batch_tanggal_kirim, $tipe_pengiriman_id, $delivery_order_batch_tipe_layanan_id, $delivery_order_batch_tipe_layanan_no, $delivery_order_batch_tipe_layanan_nama, $tipe_delivery_order_id, $delivery_order_batch_is_need_packing, $delivery_order_batch_create_who, $delivery_order_batch_create_tgl, $delivery_order_batch_status, $kendaraan_id, $karyawan_id, $tipe_ekspedisi_id, $kendaraan_volume_cm3_max, $kendaraan_berat_gr_max, $volumeBatch, $beratBatch, $kendaraan_volume_cm3_sisa, $kendaraan_berat_gr_sisa, $kendaraan_km_awal, $kendaraan_km_akhir, $kendaraan_km_terpakai, $picking_list_id, $picking_order_id, $serah_terima_kirim_id);

			$this->db->delete('delivery_order_area', ['delivery_order_batch_id' => $delivery_order_batch_id]);

			foreach ($area as $key => $value) {
				$this->db->set("delivery_order_area_id", "NEWID()", FALSE);
				$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
				$this->db->set("area_id", $value);

				$this->db->insert("delivery_order_area");
			}


			$temp = [];

			foreach ($list_do as $key => $value) {
				//update status do draft menajdi in progress item request
				// $this->M_DeliveryOrderBatch->UpdateStatusDODraftToProgress($value['delivery_order_draft_id']);

				$dod = $this->M_DeliveryOrderBatch->GetDODraftById($value['delivery_order_draft_id'], $value['no_urut']);
				$cek_do = $this->M_DeliveryOrderBatch->Check_DO($value['delivery_order_draft_id'], $delivery_order_batch_id);

				// $cek_doDraft2 = $this->M_DeliveryOrderBatch->Check_DODraft2($value['delivery_order_draft_id']);
				// echo json_encode($cek_do);

				if ($cek_do == 0) {

					//insert ke delivery_order
					//insert ke delivery_order_detail
					foreach ($dod as $key => $value2) {
						$do_id = $this->M_Vrbl->Get_NewID();
						$do_id = $do_id[0]['NEW_ID'];

						//generate kode
						$date_now = date('Y-m-d h:i:s');
						$param =  'KODE_DO';
						$vrbl = $this->M_Vrbl->Get_Kode($param);
						$prefix = $vrbl->vrbl_kode;
						// get prefik depo
						$depo_id = $this->session->userdata('depo_id');
						$depoPrefix = $this->M_DeliveryOrderBatch->getDepoPrefix($depo_id);
						$unit = $depoPrefix->depo_kode_preffix;
						$do_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

						$this->M_DeliveryOrderBatch->insert_delivery_order($delivery_order_batch_id, $do_id, $do_kode, $value2, "laksanakan", $delivery_order_batch_tanggal_kirim);
						$this->M_DeliveryOrderBatch->insert_delivery_order_progress($do_id);

						$dodd = $this->M_DeliveryOrderBatch->GetDODraftDetailById($value['delivery_order_draft_id']);

						foreach ($dodd as $key => $value3) {

							$dod_id = $this->M_Vrbl->Get_NewID();
							$dod_id = $dod_id[0]['NEW_ID'];

							$this->M_DeliveryOrderBatch->insert_delivery_order_detail($value3['delivery_order_detail_draft_id'], $do_id, $delivery_order_batch_id, $value3);

							$cek_doDraft2 = $this->M_DeliveryOrderBatch->Check_DODraft2($value['delivery_order_draft_id'], $value3['delivery_order_detail_draft_id']);

							//insert ke delivery_order_detail2
							foreach ($cek_doDraft2 as $key => $item) {
								$this->M_DeliveryOrderBatch->insert_delivery_order_detail2($do_id, $value3['delivery_order_detail_draft_id'], $item);
							}
						}

						// insert ke delivery_order_payment_driver
						$this->M_DeliveryOrderBatch->insert_delivery_order_payment_driver($do_id, $value['delivery_order_draft_tipe_pembayaran'], $value['delivery_order_draft_nominal_tunai']);
					}
				} else {
					//generate kode
					$date_now = date('Y-m-d h:i:s');
					$param =  'KODE_DO';
					$vrbl = $this->M_Vrbl->Get_Kode($param);
					$prefix = $vrbl->vrbl_kode;
					// get prefik depo
					$depo_id = $this->session->userdata('depo_id');
					$depoPrefix = $this->M_DeliveryOrderBatch->getDepoPrefix($depo_id);
					$unit = $depoPrefix->depo_kode_preffix;
					$do_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

					//get do_id by 
					$getDoId = $this->db->select("delivery_order_id")->from("delivery_order")->where("delivery_order_draft_id", $value['delivery_order_draft_id'])->get()->row();

					$this->M_DeliveryOrderBatch->update_delivery_order($delivery_order_batch_id, $value['delivery_order_draft_id'], $value['no_urut'], "laksanakan", $do_kode, $delivery_order_batch_tanggal_kirim);
					$this->M_DeliveryOrderBatch->insert_delivery_order_progress($getDoId->delivery_order_id);

					foreach ($cek_do as $val) {
						$Get_DOD_ID = $this->db->select("delivery_order_detail_id")
							->from("delivery_order_detail")
							->where('delivery_order_id', $val->delivery_order_id)->get()->result();
						//insert ke delivery_order_detail2
						foreach ($Get_DOD_ID as $key => $valDO) {

							$cek_doDraft2 = $this->M_DeliveryOrderBatch->Check_DODraft2($value['delivery_order_draft_id'], $valDO->delivery_order_detail_id);

							foreach ($cek_doDraft2 as $key => $item) {
								if ($valDO->delivery_order_detail_id == $item->delivery_order_detail_draft_id) {
									$this->M_DeliveryOrderBatch->insert_delivery_order_detail2($val->delivery_order_id, $valDO->delivery_order_detail_id, $item);
									// array_push($temp, [
									// 	'dod_id' => $valDO->delivery_order_detail_id,
									// 	"sku_id" => $item->sku_id,
									// 	"sku_stock_id" => $item->sku_stock_id,
									// 	"sku_expdate" => $item->sku_expdate,
									// 	"sku_qty" => $item->sku_qty,
									// ]);
								}
							}
						}
					}

					// delete dan insert ke delivery_order_payment_driver
					$this->M_DeliveryOrderBatch->delete_delivery_order_payment_driver($getDoId->delivery_order_id);
					$this->M_DeliveryOrderBatch->insert_delivery_order_payment_driver($getDoId->delivery_order_id, $value['delivery_order_draft_tipe_pembayaran'], $value['delivery_order_draft_nominal_tunai']);
				}
			}

			$this->db->query("exec proses_create_picking_list '$delivery_order_batch_id', '" . $this->session->userdata('pengguna_username') . "'");

			echo json_encode(responseJson((object)[
				'lastUpdatedChecked' => $lastUpdatedChecked,
				'status' => 'Dilaksanakan'
			]));

			// if ($this->db->trans_status() === FALSE) {
			// 	$this->db->trans_rollback();
			// 	echo json_encode(0);
			// } else {
			// 	$this->db->trans_commit();
			// 	echo json_encode(1);
			// }
		}
	}

	public function batalkan_do_batch()
	{
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$tglLastUpdate = $this->input->post('tglLastUpdate');

		$this->db->trans_begin();

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "delivery_order_batch",
			'whereField' => "delivery_order_batch_id",
			'whereValue' => $delivery_order_batch_id,
			'fieldDateUpdate' => "delivery_order_batch_update_tgl",
			'fieldWhoUpdate' => "delivery_order_batch_update_who",
			'lastUpdated' => $tglLastUpdate
		]);

		//cek ritasi
		$IDRitasi = $this->M_DeliveryOrderBatch->getRitasiByDOBatch($delivery_order_batch_id);
		$jumlahRitasi = $this->M_DeliveryOrderBatch->getCountDOBatchByRitasi($IDRitasi['delivery_order_batch_h_id']);

		$this->db->delete('delivery_order_batch', ['delivery_order_batch_id' => $delivery_order_batch_id]);

		if ($jumlahRitasi['jml'] > 1) {
			$volumeTerpakai = intval($IDRitasi['volumeTerpakaiRitasi']) - intval($IDRitasi['volumeTerpakaiBatch']);
			$beratTerpakai = intval($IDRitasi['beratTerpakaiRitasi']) - intval($IDRitasi['BeratTerpakaiBatch']);
			$volumeSisa = intval($IDRitasi['volumeSisaRitasi']) + intval($IDRitasi['volumeTerpakaiBatch']);
			$beratSisa = intval($IDRitasi['beratSisaRitasi']) + intval($IDRitasi['BeratTerpakaiBatch']);

			$update = $this->M_DeliveryOrderBatch->updateRitasi($IDRitasi['delivery_order_batch_h_id'], $volumeTerpakai, $beratTerpakai, $volumeSisa, $beratSisa);
		} else {
			$this->db->delete('delivery_order_batch_h', ['delivery_order_batch_h_id' => $IDRitasi['delivery_order_batch_h_id']]);
		}

		$this->db->delete('delivery_order', ['delivery_order_batch_id' => $delivery_order_batch_id]);
		$this->db->delete('delivery_order_detail', ['delivery_order_batch_id' => $delivery_order_batch_id]);
		$this->db->delete('delivery_order_area', ['delivery_order_batch_id' => $delivery_order_batch_id]);
		$this->db->delete('delivery_order_temp', ['delivery_order_batch_id' => $delivery_order_batch_id]);

		echo json_encode(responseJson((object)[
			'lastUpdatedChecked' => $lastUpdatedChecked,
			'status' => 'Dibatalkan'
		]));
	}

	public function checkLastUpdate()
	{
		$do_batch_id = $this->input->post('do_batch_id');
		$do_batch_update_tgl = $this->input->post('do_batch_update_tgl');

		$lastUpdate = $this->db->select('delivery_order_batch_update_tgl as tglUpdate')->from('delivery_order_batch')->where('delivery_order_batch_id', $do_batch_id)->get()->row()->tglUpdate;

		if ($do_batch_update_tgl == $lastUpdate) {
			echo json_encode(1);
		} else {
			echo json_encode(0);
		}
	}

	public function DeleteDO()
	{
		// $fdjr_id = $this->input->post('fdjr_id');
		// $do_id = $this->input->post('do_id');
		$arr_del_do = $this->input->post('arr_del_do');
		$tglLastUpdate = $this->input->post('tglLastUpdate');
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');

		$this->db->trans_begin();

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "delivery_order_batch",
			'whereField' => "delivery_order_batch_id",
			'whereValue' => $delivery_order_batch_id,
			'fieldDateUpdate' => "delivery_order_batch_update_tgl",
			'fieldWhoUpdate' => "delivery_order_batch_update_who",
			'lastUpdated' => $tglLastUpdate
		]);

		foreach ($arr_del_do as $key => $value) {
			$this->M_DeliveryOrderBatch->DeleteDO($value['fdjr_id'], $value['do_id']);
		}

		echo json_encode(responseJson((object)[
			'lastUpdatedChecked' => $lastUpdatedChecked,
			'status' => 'Diupdate'
		]));
	}

	public function DeleteDOTempByAlamat()
	{
		$do_id = $this->input->post('do_id');
		$temp_do = $this->input->post('temp_do');
		$batch_id = $this->input->post('batch_id');

		$data = $this->M_DeliveryOrderBatch->DeleteDOTempByAlamat($do_id, $temp_do);
		// $data = $this->M_DeliveryOrderBatch->DeleteDOByAlamat($do_id, $batch_id);

		echo 1;
	}

	public function DeleteDOByAlamat()
	{
		$do_id = $this->input->post('do_id');
		$temp_do = $this->input->post('temp_do');
		$batch_id = $this->input->post('batch_id');

		$data = $this->M_DeliveryOrderBatch->DeleteDOTempByAlamat($do_id, $temp_do);
		$data = $this->M_DeliveryOrderBatch->DeleteDOByAlamat($do_id, $batch_id);

		echo 1;
	}

	public function DeleteDOTemp()
	{
		$temp_do = $this->input->post('temp_do');

		$data = $this->M_DeliveryOrderBatch->DeleteDOTemp($temp_do);

		echo 1;
	}

	public function UpdateLatLongDO()
	{
		$client_pt_id = $this->input->post("client_pt_id");
		$alamat_do = $this->input->post("alamat_do");
		$latlong_text = $this->input->post("latlong_text");
		$mode = $this->input->post("mode");
		$mode_do = $this->input->post("mode_do");

		$data = $this->M_DeliveryOrderBatch->UpdateLatLongDODraft($client_pt_id, $latlong_text, $mode);

		if ($alamat_do != '' && $mode_do != '') {
			$data = $this->M_DeliveryOrderBatch->UpdateLatLongDO($alamat_do, $latlong_text, $mode_do);
		}

		echo 1;
	}

	public function OptimasiRute()
	{
		$do_temp_id = $this->input->post('do_temp_id');
		$depo_id = $this->input->post('depo_id');

		$data = $this->M_DeliveryOrderBatch->OptimasiRute($do_temp_id, $depo_id);

		// $fixData = [];
		// if ($data != 0) {
		// 	foreach ($data as $key => $value) {
		// 		$area = $this->db->select("delivery_order_detail_draft.sku_qty, sku.sku_satuan")->from("delivery_order_detail_draft")
		// 			->join("sku", "delivery_order_detail_draft.sku_id = sku.sku_id", "left")
		// 			->where("delivery_order_detail_draft.delivery_order_draft_id", $value['delivery_order_draft_id'])->get()->result();

		// 		$arrArea = [];

		// 		foreach ($area as $key => $val) {
		// 			$arrArea[] = $val->sku_qty . " " . preg_replace('/\d+/u', '', $val->sku_satuan);
		// 		}

		// 		array_push($fixData, [
		// 			'delivery_order_batch_id' => $value['delivery_order_batch_id'],
		// 			'delivery_order_no_urut_rute' => $value['delivery_order_no_urut_rute'],
		// 			'delivery_order_kirim_nama' => $value['delivery_order_kirim_nama'],
		// 			'delivery_order_kirim_alamat' => $value['delivery_order_kirim_alamat'],
		// 			'delivery_order_kirim_telp' => $value['delivery_order_kirim_telp'],
		// 			'delivery_order_kirim_latitude' => $value['delivery_order_kirim_latitude'],
		// 			'delivery_order_kirim_longitude' => $value['delivery_order_kirim_longitude'],
		// 			'sku_weight' => $value['sku_weight'],
		// 			'sku_volume' => $value['sku_volume'],
		// 			'product' => $arrArea
		// 		]);
		// 	}
		// }

		echo json_encode($data);
	}


	public function lihat_map()
	{
		$this->load->model('M_Menu');

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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			"https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		$data['UseMap'] = $this->M_Vrbl->Get_Vrbl_By_Param('USE_MAP');

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/DeliveryOrderBatch/lihat_map', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/DeliveryOrderBatch/script_lihat_map', $data);
	}

	public function print()
	{
		$id = $this->input->get('id');
		$data['FDJR'] = $this->M_DeliveryOrderBatch->GetCetakHeaderFDJRById($id);
		$data['FDJRDetail'] = $this->M_DeliveryOrderBatch->GetCetakHeaderFDJRDetailById($id);
		$data['FDJRTotal'] = $this->M_DeliveryOrderBatch->GetCetakHeaderFDJRTotalById($id);
		// $data['AssigmentDriver'] = $this->M_DeliveryOrderBatch->GetAssigmentDriver($id);
		// $data['kendaraan_nopol'] = $this->M_DeliveryOrderBatch->GetKendaraanById($data['FDJR'][0]['kendaraan_id']);
		// $data['pengemudi'] = $this->M_DeliveryOrderBatch->GetPengemudiById($data['FDJR'][0]['karyawan_id']);
		// $data['DO'] = $this->M_DeliveryOrderBatch->GetDetailFDJRByAlamatPrint($id);
		// $data['rowFDJR'] = $this->M_DeliveryOrderBatch->GetRowHeaderFDJRById($id);
		// echo var_dump($data['DO']);

		// echo json_encode($data['AssigmentDriver']);
		// die;

		//title dari pdf
		$this->data['title_pdf'] = 'Laporan Validasi Pengeluaran Barang';

		//filename dari pdf ketika didownload
		$file_pdf = 'report_validasi_pengeluaran_barang_' . date('d-M-Y H:i:s');
		// //setting paper
		$paper = 'A4';
		// //orientasi paper potrait / landscape
		$orientation = "landscape";

		$html = $this->load->view('WMS/DeliveryOrderBatch/view_cetak_do_batch', $data, true);

		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}

	public function GetDeliveryOrderByFDJRDraft()
	{
		$tgl = explode(" - ", $this->input->post('delivery_order_batch_tanggal_kirim'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$data['FDJR'] = $this->M_DeliveryOrderBatch->GetDeliveryOrderBatchDraft($tgl1, $tgl2);
		$data['DO'] = $this->M_DeliveryOrderBatch->GetDeliveryOrderByFDJRDraft($tgl1, $tgl2);
		$data['Area'] = $this->M_DeliveryOrderBatch->GetArea();

		echo json_encode($data);
	}

	public function GetAreaByFDJRID()
	{
		$id = $this->input->post('id');
		$data = $this->M_DeliveryOrderBatch->GetAreaByFDJRID($id);

		echo json_encode($data);
	}

	public function move_delivery_order()
	{
		// $fdjr_id = $this->input->post('fdjr_id');
		// $do_id = $this->input->post('do_id');

		$delivery_order_batch_id = "";
		$delivery_order_batch_tanggal_kirim = "";
		$arr_fdjr_draft = $this->input->post('arr_fdjr_draft');
		$arr_do_draft = $this->input->post('arr_do_draft');

		$this->db->trans_begin();

		foreach ($arr_fdjr_draft as $key => $val) {
			$delivery_order_batch_id = $val['delivery_order_batch_id'];
			$delivery_order_batch_tanggal_kirim = $val['delivery_order_batch_tanggal_kirim'];

			$lastUpdatedChecked = checkLastUpdatedData((object) [
				'table' => "delivery_order_batch",
				'whereField' => "delivery_order_batch_id",
				'whereValue' => $delivery_order_batch_id,
				'fieldDateUpdate' => "delivery_order_batch_update_tgl",
				'fieldWhoUpdate' => "delivery_order_batch_update_who",
				'lastUpdated' => $val['delivery_order_batch_update_tgl']
			]);

			// echo var_dump($lastUpdatedChecked);
			if ($lastUpdatedChecked['status'] != 200) {
				echo json_encode($lastUpdatedChecked['status']);
				die;
			}
		}

		// foreach ($arr_do_draft as $key => $val) {
		// 	$lastUpdatedChecked = checkLastUpdatedData((object) [
		// 		'table' => "delivery_order",
		// 		'whereField' => "delivery_order_id",
		// 		'whereValue' => $val['delivery_order_id'],
		// 		'fieldDateUpdate' => "delivery_order_update_tgl",
		// 		'fieldWhoUpdate' => "delivery_order_update_who",
		// 		'lastUpdated' => $val['delivery_order_update_tgl']
		// 	]);

		// 	// echo var_dump($lastUpdatedChecked);
		// 	if ($lastUpdatedChecked['status'] != 200) {
		// 		echo json_encode($lastUpdatedChecked['status']);
		// 		die;
		// 	}
		// }

		foreach ($arr_do_draft as $key => $val) {
			$this->M_DeliveryOrderBatch->move_delivery_order($delivery_order_batch_id, $delivery_order_batch_tanggal_kirim, $val['delivery_order_id']);
		}

		// $this->db->trans_rollback();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(500);
		} else {
			$this->db->trans_commit();
			echo json_encode(200);
		}

		// foreach ($arr_del_do as $key => $value) {
		// 	$this->M_DeliveryOrderBatch->DeleteDO($value['fdjr_id'], $value['do_id']);
		// }

		// echo json_encode(responseJson((object)[
		// 	'lastUpdatedChecked' => $lastUpdatedChecked,
		// 	'status' => 'Diupdate'
		// ]));
	}

	public function print_by_do()
	{
		$id = $this->input->get('id');
		$data = $this->M_DeliveryOrderBatch->getDetailBatchPerDO($id);
		$hasil_akhir = [];
		$new_data = null;
		$sumprc = 0;
		$sumjumlah = 0;
		foreach ($data as $item) {
			$delivery_order_kode = $item['delivery_order_kode'];

			// Jika $new_data belum diinisialisasi atau "delivery_order_kode" berbeda
			if ($new_data === null || $new_data['delivery_order_kode'] !== $delivery_order_kode) {
				// Jika $new_data sudah ada dan memiliki "data," tambahkan ke $hasil_akhir
				if ($new_data !== null && isset($new_data['data'])) {
					$hasil_akhir[] = $new_data;
				}

				$sumprc += (int)$item['sku_disc_percent'];
				$sumjumlah += (int)$item['jumlah'];
				// Inisialisasi $new_data dengan data baru
				$new_data = [
					'delivery_order_kode' => $delivery_order_kode,
					'client_pt_nama' => $item['client_pt_nama'],
					'client_pt_alamat' => $item['client_pt_alamat'],
					'delivery_order_kirim_nama' => $item['delivery_order_kirim_nama'],
					'delivery_order_kirim_alamat' => $item['delivery_order_kirim_alamat'],
					'delivery_order_tgl_buat_do' => $item['delivery_order_tgl_buat_do'],
					'client_pt_telepon' => $item['client_pt_telepon'],
					'sum_sku_disc_percent' => $sumprc,
					'sum_jumlah' => $sumjumlah,
					'data' => [],
				];
			}

			// Tambahkan data SKU ke dalam sub-array "data"
			$new_data['data'][] = [
				'delivery_order_kode' => $delivery_order_kode,
				'sku_nama_produk' => $item['sku_nama_produk'],
				'sku_kode' => $item['sku_kode'],
				'sku_satuan' => $item['sku_satuan'],
				'sku_harga_satuan' => $item['sku_harga_satuan'],
				'sku_qty' => $item['sku_qty'],
				'sku_disc_percent' => $item['sku_disc_percent'],
				'jumlah' => $item['jumlah'],
				'sku_harga_nett' => $item['sku_harga_nett'],
			];

			// Jika "data" memiliki dua SKU, tambahkan ke $hasil_akhir dan reset "data"
			if (count($new_data['data']) == 10) {
				$hasil_akhir[] = $new_data;
				$new_data = null;
			}
		}

		// Jika $new_data masih ada, tambahkan ke $hasil_akhir
		if ($new_data !== null && isset($new_data['data'])) {
			$hasil_akhir[] = $new_data;
		}
		// echo json_encode($hasil_akhir);
		// print_r($hasil_akhir);
		// die;

		$data['data_do'] = $hasil_akhir;
		$this->load->view('WMS/DeliveryOrderBatch/view_cetak_by_do', $data);
	}

	public function getSegmentasiByID()
	{
		$id = $this->input->post('idSeg');

		$data = $this->M_DeliveryOrderBatch->getSegmentasi($id);

		echo json_encode($data);
	}

	public function getRitasi()
	{
		$tgl_kirim = date('d-m-Y', strtotime($this->input->post('tgl_kirim')));
		$kendaraan = $this->input->post('kendaraan');
		$driver = $this->input->post('driver');
		$ritasi = $this->input->post('ritasi');

		$data = $this->M_DeliveryOrderBatch->getRitasi($tgl_kirim, $kendaraan, $driver, $ritasi);

		echo json_encode($data);
	}
}
