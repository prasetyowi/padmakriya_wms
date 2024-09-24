<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KonfirmasiPengirimanCanvas extends CI_Controller
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

		$this->MenuKode = "109101000";
		// $this->MenuKode = "103002000";
		$this->load->model([array('WMS/MonitoringCanvas/M_KonfirmasiPengirimanCanvas', 'M_KonfirmasiPengirimanCanvas'), 'M_Function', 'M_MenuAccess', 'M_Menu']);
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	}

	public function KonfirmasiPengirimanCanvasMenu()
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

		$data['sales'] = $this->M_KonfirmasiPengirimanCanvas->getSales();

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


		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Page/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Component/Script/S_KonfirmasiPengirimanCanvas', $data);
	}

	public function formClosing($fdjrId)
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

		$data['data'] = $this->M_KonfirmasiPengirimanCanvas->getDataFromClosing($fdjrId);
		$data['checkCreateBTB'] = $this->db->select("*")->from("penerimaan_penjualan")->where("delivery_order_batch_id", $fdjrId)->get()->num_rows();

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


		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Page/formClosing', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Component/Script/S_KonfirmasiPengirimanCanvas', $data);
	}

	public function prosesCreateBTB()
	{
		$this->load->model('M_Menu');

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

		// echo json_encode($this->input->get());
		// die;

		$skuKodeGroup = explode(',', $this->input->get('data'));

		$batchId = $this->input->get('batchId');
		$doId = $this->input->get('doId');

		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_KonfirmasiPengirimanCanvas->getDepoPrefix($depo_id)->depo_kode_preffix;

		$data['delivery_order_batch_id'] = $batchId;
		$data['delivery_order_id'] = $doId;
		$data['Principle'] = $this->M_KonfirmasiPengirimanCanvas->GetPrincipleByPerusahaan($doId);
		$data['checkers'] = $this->M_KonfirmasiPengirimanCanvas->GetCheckerByPrinciple();
		$data['gudang'] = $this->M_KonfirmasiPengirimanCanvas->GetGudang();
		$data['header'] = $this->M_KonfirmasiPengirimanCanvas->GetHeaderDeliveryOrderById($doId);
		$data['detail'] = $this->M_KonfirmasiPengirimanCanvas->GetDetailDeliveryOrderById($doId);
		// $data['PalletGenerator'] = $this->M_KonfirmasiPengirimanCanvas->GetPalletGenerator($depoPrefix);
		$data['act'] = "ProsesBTB";

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Page/formBtb', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Component/Script/S_CreateBTB', $data);
	}

	public function formViewBTB($deliveryOrderBatchId, $deliveryOrderId)
	{
		$this->load->model('M_Menu');

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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$penerimaan_penjualan_id = $this->M_KonfirmasiPengirimanCanvas->Check_PenerimaanPenjualan($deliveryOrderBatchId);

		$data['delivery_order_batch_id'] = $deliveryOrderBatchId;
		$data['delivery_order_id'] = $deliveryOrderId;
		$data['Principle'] = $this->M_KonfirmasiPengirimanCanvas->GetPrincipleByPenerimaanPenjualan($deliveryOrderBatchId);
		$data['Gudang'] = $this->M_KonfirmasiPengirimanCanvas->GetGudang();
		$data['DOHeader'] = $this->M_KonfirmasiPengirimanCanvas->GetHeaderPenerimaanPenjualanByDOId($deliveryOrderId);
		$data['DODetail'] = $this->M_KonfirmasiPengirimanCanvas->GetDetailPenerimaanPenjualanByDOId($deliveryOrderId);
		$data['Pallet'] = $this->M_KonfirmasiPengirimanCanvas->GetPalletPenerimaanPenjualanByDOId($deliveryOrderId, $penerimaan_penjualan_id);
		$data['act'] = "BTBDetail";

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Page/detailBtb', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Component/Script/S_CreateBTB', $data);
	}

	public function getKodeAutoComplete()
	{
		$response = $this->db->select("delivery_order_batch_id as id, delivery_order_batch_kode as kode")
			->from("delivery_order_batch")
			->where("depo_id", $this->session->userdata('depo_id'))
			->where("delivery_order_batch_status", 'in transit')
			->like("delivery_order_batch_kode", $this->input->get('params'))->get()->result();

		echo json_encode($response);
	}

	public function getDataByFilter()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_KonfirmasiPengirimanCanvas->getDataByFilter($dataPost));
	}

	public function downloadCanvasSO()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_KonfirmasiPengirimanCanvas->downloadCanvasSO($dataPost));
	}

	public function requestGetDataListDO()
	{
		echo json_encode($this->M_KonfirmasiPengirimanCanvas->requestGetDataListDO($this->input->post('deliveryOrderBatch')));
	}

	public function requestGetSummaryListDO()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_KonfirmasiPengirimanCanvas->requestGetSummaryListDO($dataPost));
	}

	public function requestGetDataDOById()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_KonfirmasiPengirimanCanvas->requestGetDataDOById($dataPost));
	}

	public function saveConfirmCanvas()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));

		$checkProsedure = $this->db->query("exec proses_settlement_canvas '$dataPost->deliveryOrderBacthId'")->result_array();

		$notSameQty = 0;

		if ($checkProsedure) {
			foreach ($checkProsedure as $key => $value) {
				if ($value['qty_canvas'] !== $value['qty_terjual']) $notSameQty++;
			}
		}

		$this->db->trans_begin();

		$result = $this->db->update('delivery_order_batch', [
			'delivery_order_batch_status' => $dataPost->type == 'save' ? 'In Process Closing' : ($notSameQty > 0 ? 'In Process Receiving Outlet' : 'Closing Delivery Confirm'),
			'is_confirm_canvas' => $dataPost->type == 'save' ? 1 : 2
		], ['delivery_order_batch_id' => $dataPost->deliveryOrderBacthId]);

		$getCanvasId = $this->db->select("canvas_id")->from("delivery_order")->where("delivery_order_batch_id", $dataPost->deliveryOrderBacthId)->get()->row()->canvas_id;

		$getDataSKUCanvas = 0;
		if ($dataPost->type !== 'save') {
			$this->db->query("UPDATE canvas SET canvas_status = 'Completed' where canvas_id = '$getCanvasId'");
			$this->db->query("UPDATE dbo.canvas SET canvas_status = 'Completed' where canvas_id = '$getCanvasId'");

			// get Data SKU Canvas
			$getDataSKUCanvas = $this->M_KonfirmasiPengirimanCanvas->getDataSKUCanvas($dataPost->deliveryOrderBacthId);

			if ($getDataSKUCanvas != 0) {
				foreach ($getDataSKUCanvas as $key => $value) {
					$sku_konversi_temp_id = $this->M_Vrbl->Get_NewID();
					$sku_konversi_temp_id = $sku_konversi_temp_id[0]['NEW_ID'];

					// INSERT SKU KONVERSI TEMP
					$this->db->set("sku_konversi_temp_id", $sku_konversi_temp_id);
					$this->db->set("sku_id", $value->sku_id);
					$this->db->set("sku_expired_date", $value->sku_expdate);
					$this->db->set("sku_qty", intval($value->sku_qty));
					$this->db->set("sku_qty_composite", intval($value->sku_qty));
					//$this->db->set("sku_qty_composite", intval($value->sku_qty_composite));
					$this->db->insert("sku_konversi_temp");

					// PROCEDURE proses_konversi_sku
					$hasilExec = $this->db->query("exec proses_konversi_sku '$sku_konversi_temp_id'")->result();

					foreach ($hasilExec as $key => $val) {
						$row = $this->db->query("SELECT sku_konversi_faktor FROM sku WHERE sku_id = '$val->sku_id'")->row();
						// INSERT DELIVERY ORDER DETAIL2 GAGAL
						$this->db->set('delivery_order_detail2_gagal_id', 'NEWID()', FALSE);
						$this->db->set('delivery_order_detail2_id', $value->delivery_order_detail2_id);
						$this->db->set('delivery_order_detail_id', $value->delivery_order_detail_id);
						$this->db->set('delivery_order_id', $value->delivery_order_id);
						$this->db->set('sku_id', $val->sku_id);
						$this->db->set('sku_expdate', $val->sku_expired_date);
						$this->db->set('sku_qty', intval($val->hasil));
						$this->db->set('sku_qty_composite', intval($val->hasil) * intval($row->sku_konversi_faktor));

						$this->db->insert("delivery_order_detail2_gagal");
					}
				}
			}
		}

		if ($getDataSKUCanvas == 0) {
			$result2 = $this->db->update('delivery_order', [
				'delivery_order_status' => 'delivered'
			], ['delivery_order_batch_id' => $dataPost->deliveryOrderBacthId]);
		} else {
			$result2 = $this->db->update('delivery_order', [
				'delivery_order_status' => 'partially delivered'
			], ['delivery_order_batch_id' => $dataPost->deliveryOrderBacthId]);
		}


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();

			echo json_encode([
				'status' => 401,
				'notSameQty' => $notSameQty
			]);
		} else {
			$this->db->trans_commit();

			echo json_encode([
				'status' => 201,
				'notSameQty' => $notSameQty
			]);
		}


		// if ($result) {
		// 	echo json_encode([
		// 		'status' => 201,
		// 		'notSameQty' => $notSameQty
		// 	]);
		// } else {
		// 	echo json_encode([
		// 		'status' => 401,
		// 		'notSameQty' => $notSameQty
		// 	]);
		// }
	}

	public function get_pallet_by_arr_id()
	{
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		// $pallet_id = $this->input->post('pallet_id');

		$data = $this->M_KonfirmasiPengirimanCanvas->get_pallet_by_arr_id($delivery_order_batch_id);

		echo json_encode($data);
	}

	public function InsertPalletTemp()
	{
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$date_now = date('Y-m-d h:i:s');
		$param =  $this->input->post('pallet_jenis_id');

		// $vrbl = "PALLET";
		$vrbl = $this->M_KonfirmasiPengirimanCanvas->Get_pallet_jenis();
		$prefix = $vrbl;

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_KonfirmasiPengirimanCanvas->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$rak_lajur_detail_id = $this->input->post('rak_lajur_detail_id');
		$depo_detail_id = $this->input->post('depo_detail_id');

		$pallet_kode = $generate_kode;
		$pallet_id = $this->M_KonfirmasiPengirimanCanvas->Get_NEWID();

		$result = $this->M_KonfirmasiPengirimanCanvas->Insert_PalletTemp($delivery_order_batch_id, $pallet_id, $pallet_kode, $rak_lajur_detail_id, $depo_detail_id);

		echo json_encode(array('pallet_id' => $pallet_id));
	}

	public function InsertPalletTemp2()
	{

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$pallet_id = $this->input->post('pallet_id');
		$depo_detail_id = $this->input->post('depo_detail_id');

		$this->M_KonfirmasiPengirimanCanvas->Delete_PalletTemp2($pallet_id);
		$result = $this->M_KonfirmasiPengirimanCanvas->Insert_PalletTemp2($delivery_order_batch_id, $pallet_id, $depo_detail_id);

		echo json_encode(array('pallet_id' => $pallet_id));
	}

	public function DeletePalletTemp()
	{
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$pallet_id = $this->input->post('pallet_id');
		$result = $this->M_KonfirmasiPengirimanCanvas->Delete_PalletTemp($pallet_id);

		echo $result;
	}

	public function DeletePalletDetailTemp()
	{
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$pallet_detail_id = $this->input->post('pallet_detail_id');
		$result = $this->M_KonfirmasiPengirimanCanvas->Delete_PalletDetailTemp($pallet_detail_id);

		echo $result;
	}

	public function check_kode_pallet()
	{
		$kode_pallet = $this->input->post('kode_pallet');
		$depo_detail_id = $this->input->post('depo_detail_id');

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_KonfirmasiPengirimanCanvas->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		$kode = $unit . "/" . $kode_pallet;

		$pallet_id = $this->M_KonfirmasiPengirimanCanvas->check_kode_pallet($kode, $depo_detail_id);
		if ($pallet_id == null) {
			echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan", 'pallet_id' => ""));
		} else {
			echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> <span class='text-success'>Valid</span>", 'pallet_id' => $pallet_id));
		}
	}

	public function check_rak_lajur_detail()
	{
		$depo_detail_id = $this->input->post('depo_detail_id');
		$pallet_id = $this->input->post('pallet_id');
		$kode = $this->input->post('kode');

		$response = $this->M_KonfirmasiPengirimanCanvas->check_rak_lajur_detail($depo_detail_id, $kode);
		if ($response == null) {
			echo json_encode(array('type' => 201, 'message' => "Rak <strong>" . $kode . "</strong> tidak ditemukan", 'kode' => $kode));
		} else {
			//update rak_lajur_detail_id_tujuan
			$this->M_KonfirmasiPengirimanCanvas->update_pallet_rak_lajur_detail($pallet_id, $response->rak_detail_id);

			echo json_encode(array('type' => 200, 'message' => "Rak <strong>" . $kode . "</strong> ditemukan dan berhasil update", 'kode' => $kode));
		}
	}

	public function GetCheckerByPrinciple()
	{
		$perusahaan = $this->input->post('perusahaan');
		$principle = $this->input->post('principle');

		$data = $this->M_KonfirmasiPengirimanCanvas->GetCheckerByPrinciple($perusahaan, $principle);

		echo json_encode($data);
	}

	public function GetPallet()
	{
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$data['Pallet'] = $this->M_KonfirmasiPengirimanCanvas->Get_Pallet($delivery_order_batch_id);
		// $data['JenisPallet'] = $this->M_KonfirmasiPengirimanCanvas->Get_JenisPallet();

		echo json_encode($data);
	}

	public function GetPalletDetail()
	{
		$pallet_id = $this->input->post('pallet_id');
		$data = $this->M_KonfirmasiPengirimanCanvas->Get_PalletDetail($pallet_id);

		echo json_encode($data);
	}

	public function GetPalletDetail2()
	{
		$penerimaan_penjualan_id = $this->input->post('penerimaan_penjualan_id');
		$delivery_order_id = $this->input->post('delivery_order_id');
		$pallet_id = $this->input->post('pallet_id');

		$data = $this->M_KonfirmasiPengirimanCanvas->Get_PalletDetail2($penerimaan_penjualan_id, $delivery_order_id, $pallet_id);

		echo json_encode($data);
	}

	public function GetSKUExpiredDate()
	{
		$sku_id = $this->input->post('sku_id');

		$data = $this->M_KonfirmasiPengirimanCanvas->Get_SKU_Expired_Date($sku_id);

		echo json_encode($data);
	}

	public function UpdateSkuExpDatePalletTemp()
	{
		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$this->form_validation->set_rules('pallet_detail_id', 'Pallet Detail ID', 'required');
		// $this->form_validation->set_rules('sku_stock_id', 'SKU Stock ID', 'required');
		$this->form_validation->set_rules('sku_id', 'SKU ID', 'required');
		$this->form_validation->set_rules('sku_stock_expired_date', 'SKU Stock Date', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$pallet_detail_id = $this->input->post('pallet_detail_id');
			// $sku_stock_id = $this->input->post('sku_stock_id');
			$sku_id = $this->input->post('sku_id');
			$depo_detail_id = $this->input->post('depo_detail_id');
			$sku_stock_expired_date = date('Y-m-d', strtotime($this->input->post('sku_stock_expired_date')));

			$result = $this->M_KonfirmasiPengirimanCanvas->Update_SkuExpDatePalletTemp($pallet_detail_id, $sku_id, $sku_stock_expired_date, $depo_detail_id);
			// $result = $this->M_PenerimaanRetur->Update_SkuExpDatePalletTemp($pallet_detail_id, $sku_stock_id, $sku_stock_expired_date);

			echo $result;
		}
	}

	public function UpdateClosingPengirimanFDJR()
	{
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}
		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');
		$this->form_validation->set_rules('delivery_order_batch_status', 'Delivery Order Batch Status', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$delivery_order_batch_status = $this->input->post('delivery_order_batch_status');

			$result = $this->M_KonfirmasiPengirimanCanvas->Update_ClosingPengirimanFDJR($delivery_order_batch_id, $delivery_order_batch_status);

			echo $result;
		}
	}

	public function UpdateQtySKUPalletTemp()
	{

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');
		$this->form_validation->set_rules('pallet_detail_id', 'Pallet Detail ID', 'required');
		$this->form_validation->set_rules('sku_id', 'SKU ID', 'required');
		$this->form_validation->set_rules('sku_stock_qty', 'SKU Stock', 'required');
		$this->form_validation->set_rules('penerimaan_tipe_nama', 'Penerimaan Tipe', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$pallet_detail_id = $this->input->post('pallet_detail_id');
			$sku_id = $this->input->post('sku_id');
			$sku_stock_qty = $this->input->post('sku_stock_qty');
			$penerimaan_tipe_nama = $this->input->post('penerimaan_tipe_nama');

			$set_qty = $this->M_KonfirmasiPengirimanCanvas->Update_QtySKUPalletTemp($pallet_detail_id, 0);

			if ($penerimaan_tipe_nama == "titipan") {
				$result = $this->M_KonfirmasiPengirimanCanvas->Update_QtySKUPalletTemp($pallet_detail_id, $sku_stock_qty);
				echo $result;
			} else {

				$check_qty = $this->M_KonfirmasiPengirimanCanvas->Check_QtySKUPalletTemp($delivery_order_batch_id, $sku_id, $sku_stock_qty, $penerimaan_tipe_nama);
				if ($check_qty >= 0) {

					$result = $this->M_KonfirmasiPengirimanCanvas->Update_QtySKUPalletTemp($pallet_detail_id, $sku_stock_qty);

					echo $result;
				} else {
					echo "2";
				}
			}
		}
	}

	public function GetRakLajurDetail()
	{
		$depo_detail_id = $this->input->post('depo_detail_id');
		$data = $this->M_KonfirmasiPengirimanCanvas->GetRakLajurDetail($depo_detail_id);

		echo json_encode($data);
	}

	public function getKodeAutoComplete2()
	{
		$valueParams = $this->input->get('params');
		$type = $this->input->get('type');
		$result = $this->M_KonfirmasiPengirimanCanvas->getKodeAutoComplete($valueParams, $type);
		echo json_encode($result);
	}

	public function UpdatePalletKodeTempByIdTemp()
	{
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$pallet_id = $this->input->post('pallet_id');
		$pallet_kode = $this->input->post('pallet_kode');

		$this->db->trans_begin();

		$this->M_KonfirmasiPengirimanCanvas->UpdatePalletKodeTemByIdTemp($pallet_id, $pallet_kode);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function getDataSKuByGroup()
	{
		echo json_encode($this->M_KonfirmasiPengirimanCanvas->GetDetailDeliveryOrderById($this->input->post('deliveryOrderId')));
	}

	public function Insertkonfirmasipengirimancanvas()
	{
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');
		$this->form_validation->set_rules('delivery_order_id', 'Delivery Order ID', 'required');
		$this->form_validation->set_rules('depo_detail_id', '<span name="CAPTION-GUDANGPENERIMA">Gudang Penerima</span>', 'required');
		$this->form_validation->set_rules('karyawan_id', 'Checker', 'required');
		// $this->form_validation->set_rules('penerimaan_tipe_id', '<span name="CAPTION-TIPEPENERIMAAN">Penerimaan Tipe</span>', 'required');
		$this->form_validation->set_rules('client_wms_id', '<span name="CAPTION-PERUSAHAAN">Perusahaan</span>', 'required');
		// $this->form_validation->set_rules('principle_id', '<span name="CAPTION-PRINCIPLE">Principle</span>', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$date_now = date('Y-m-d h:i:s');
			$param =  'KODE_BTB_CANVAS';

			$vrbl = $this->M_Vrbl->Get_Kode($param);
			$prefix = $vrbl->vrbl_kode;

			// get prefik depo
			$depo_id = $this->session->userdata('depo_id');
			$depoPrefix = $this->M_KonfirmasiPengirimanCanvas->getDepoPrefix($depo_id);
			$unit = $depoPrefix->depo_kode_preffix;
			$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

			$penerimaan_penjualan_kode = $generate_kode;
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$delivery_order_id = $this->input->post('delivery_order_id');
			$depo_detail_id = $this->input->post('depo_detail_id');
			$karyawan_id = $this->input->post('karyawan_id');
			// $penerimaan_tipe_id = $this->input->post('penerimaan_tipe_id');
			// $penerimaan_tipe = $this->input->post('penerimaan_tipe');
			$penerimaan_penjualan_keterangan = $this->input->post('penerimaan_penjualan_keterangan');
			$client_wms_id = $this->input->post('client_wms_id');
			$principle_id = $this->input->post('principle_id');
			$arrDataSkuBygroup = $this->input->post('arrDataSkuBygroup');
			$dataDetailPallet = $this->input->post('dataDetailPallet');
			$lastUpdated = $this->input->post('lastUpdated');

			$skuStockId = "";

			$this->db->trans_begin();

			// $check_btb = $this->M_KonfirmasiPengirimanCanvas->Check_PenerimaanPenjualan($penerimaan_penjualan_kode);
			$check_btb = $this->M_KonfirmasiPengirimanCanvas->Check_PenerimaanPenjualan($delivery_order_batch_id);

			// $lastUpdatedChecked = checkLastUpdatedData((object) [
			// 	'table' => "delivery_order",
			// 	'whereField' => "delivery_order_id",
			// 	'whereValue' => $delivery_order_id,
			// 	'fieldDateUpdate' => "delivery_order_update_tgl",
			// 	'fieldWhoUpdate' => "delivery_order_update_who",
			// 	'lastUpdated' => $lastUpdated
			// ]);

			// if ($lastUpdatedChecked['status'] == 400) {
			// 	echo json_encode(2);
			// 	return false;
			// }

			foreach ($dataDetailPallet as $key => $value) {
				$pallet_detail_id = $this->M_KonfirmasiPengirimanCanvas->Get_NEWID();
				$this->db->insert('pallet_detail_temp', [
					'pallet_detail_id' => $pallet_detail_id,
					'pallet_id' => $value['pallet_id'],
					'sku_id' => $value['sku_id'],
					'sku_stock_expired_date' => $value['expdate'],
					'sku_stock_qty' => $value['qty'],
					'tanggal_create' => date('Y-m-d H:i:s'),
					'jumlah_terima' => $value['qty_terima'],
					'batch_no' => $value['batch_no']
				]);
			}

			if ($check_btb == "0") {


				$penerimaan_penjualan_id = $this->M_KonfirmasiPengirimanCanvas->Get_NEWID();
				// $this->M_KonfirmasiPengirimanCanvas->Update_StockSKU($delivery_order_batch_id, $depo_detail_id, $client_wms_id);

				$this->M_KonfirmasiPengirimanCanvas->Insert_PenerimaanPenjualan($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $delivery_order_batch_id, $depo_detail_id, $karyawan_id, $penerimaan_penjualan_keterangan, $client_wms_id);

				// $data_do = $this->M_KonfirmasiPengirimanCanvas->get_data_do_by_id($delivery_order_batch_id, $delivery_order_id, $arrDataSkuBygroup);

				$data_sku_stock_by_pallet = $this->M_KonfirmasiPengirimanCanvas->get_sku_stock_by_pallet($delivery_order_batch_id);

				$pallet_fdjr = $this->M_KonfirmasiPengirimanCanvas->get_data_pallet_by_fdjr($delivery_order_batch_id);

				// echo var_dump($pallet_fdjr);

				$getCanvasId = $this->db->select("canvas_id")->from("delivery_order")->where("delivery_order_id", $delivery_order_id)->get()->row()->canvas_id;

				$this->db->update('delivery_order_batch', [
					'delivery_order_batch_status' => 'Closing Delivery Confirm'
				], ['delivery_order_batch_id' => $delivery_order_batch_id]);

				$this->db->query("UPDATE canvas SET canvas_status = 'Completed' where canvas_id = '$getCanvasId'");
				$this->db->query("UPDATE dbo.canvas SET canvas_status = 'Completed' where canvas_id = '$getCanvasId'");

				if ($arrDataSkuBygroup) {
					foreach ($arrDataSkuBygroup as $key2 => $value2) {
						$penerimaan_penjualan_detail_id = $this->M_KonfirmasiPengirimanCanvas->Get_NEWID();

						// if ($getSkuStockId) {
						// 	$skuStockId = $getSkuStockId->sku_stock_id;
						// } else {
						// 	$newSkuStockId = $this->M_KonfirmasiPengirimanCanvas->Get_NEWID();
						// 	$this->db->insert("sku_stock", [
						// 		'sku_stock_id' => $newSkuStockId,
						// 		'unit_mandiri_id' => $this->session->userdata('unit_mandiri_id'),
						// 		'client_wms_id' => $client_wms_id,
						// 		'depo_id' => $this->session->userdata('depo_id'),
						// 		'depo_detail_id' => $depo_detail_id,
						// 		'sku_induk_id' => $value2['sku_induk_id'],
						// 		'sku_id' => $value2['sku_id'],
						// 		'sku_stock_expired_date' => $value2['sku_expdate'],
						// 		'sku_stock_awal' => 0,
						// 		'sku_stock_masuk' => $value2['qty_terima'],
						// 		'sku_stock_alokasi' => 0,
						// 		'sku_stock_akhir' => 0,
						// 		'sku_stock_is_jual' => 1,
						// 		'sku_stock_is_aktif' => 1,
						// 		'sku_stock_is_deleted' => 0,
						// 	]);

						// 	$skuStockId = $newSkuStockId;
						// }

						$this->M_KonfirmasiPengirimanCanvas->Insert_PenerimaanPenjualanDetail($penerimaan_penjualan_detail_id, $penerimaan_penjualan_id, $delivery_order_id, $value2, $skuStockId, $client_wms_id);
					}
				}

				foreach ($pallet_fdjr as $key2 => $value2) {
					$cek_pallet_penerimaan = $this->M_KonfirmasiPengirimanCanvas->cek_pallet_penerimaan($penerimaan_penjualan_id, $value2['pallet_id']);

					if ($cek_pallet_penerimaan == 0) {
						$this->M_KonfirmasiPengirimanCanvas->Insert_PenerimaanPenjualanDetail2($penerimaan_penjualan_id, $value2);
					}
				}

				// if ($penerimaan_tipe_id == "79F2522A-CEA5-4FF8-BC79-C0B28808877D") {
				// 	$this->M_KonfirmasiPengirimanCanvas->update_stock_delivery_order_detail($delivery_order_id, 0, 0);
				// }

				// foreach ($data_sku_stock_by_pallet as $key2 => $value2) {
				// 	$sku_stock_card_keterangan = "Bukti Terima Barang dari Canvas";
				// 	$sku_id = $value2['sku_id'];
				// 	$sku_stock_id = $value2['sku_stock_id'];
				// 	$sku_stock_card_jenis = "D";
				// 	$sku_stock_card_qty = $value2['sku_stock_qty'];
				// 	$sku_stock_card_is_posting = 0;

				// 	$this->M_KonfirmasiPengirimanCanvas->Insert_sku_stock_card($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $sku_stock_card_keterangan, $sku_stock_card_jenis, $depo_id, $depo_detail_id, $sku_id, $sku_stock_id, $sku_stock_card_qty, $sku_stock_card_is_posting);
				// }

				$pallet_temp = $this->M_KonfirmasiPengirimanCanvas->get_pallet_temp($delivery_order_batch_id);
				$pallet_detail_temp = $this->M_KonfirmasiPengirimanCanvas->get_pallet_detail_temp($delivery_order_batch_id);

				foreach ($pallet_temp as $key => $value) {
					$check_pallet = $this->M_KonfirmasiPengirimanCanvas->CheckPalletById($value['pallet_id']);
					if ($check_pallet == "0") {
						$this->M_KonfirmasiPengirimanCanvas->Insert_Pallet($value);
						$this->M_KonfirmasiPengirimanCanvas->Insert_rak_lajur_detail_pallet($value);
						$this->M_KonfirmasiPengirimanCanvas->Insert_rak_lajur_detail_pallet_his($depo_detail_id, $value);
					} else {
						$this->M_KonfirmasiPengirimanCanvas->Update_Pallet($value);
						$this->M_KonfirmasiPengirimanCanvas->Update_rak_lajur_detail_pallet($value);
						$this->M_KonfirmasiPengirimanCanvas->Insert_rak_lajur_detail_pallet_his($depo_detail_id, $value);
					}
				}

				foreach ($pallet_detail_temp as $key => $value) {
					// $check_pallet = $this->M_KonfirmasiPengirimanCanvas->CheckPalletDetailById($value['pallet_id'], $value['sku_id'], $value['penerimaan_tipe_id'], $value['sku_stock_expired_date']);

					// $arr_sku_stock = $this->db->query("SELECT * FROM sku_stock WHERE sku_id = '" . $value['sku_id'] . "' AND sku_stock_expired_date = '" . $value['sku_stock_expired_date'] . "' AND depo_detail_id = '$depo_detail_id' ");

					// $check_pallet = $this->M_KonfirmasiPengirimanCanvas->CheckPalletDetailById($value['pallet_id'], $value['sku_stock_id']);
					// if ($check_pallet == "0") {
					// 	$this->M_KonfirmasiPengirimanCanvas->Insert_PalletDetail($value);
					// } else {
					// 	$this->M_KonfirmasiPengirimanCanvas->Update_PalletDetail($value['pallet_id'], $value['sku_id'], $value['sku_stock_id'], $value);
					// }

					$cek_sku_stock = $this->db->query("exec cek_exist_sku_stock '" . $this->session->userdata('depo_id') . "','$depo_detail_id','" . $value['sku_id'] . "','" . $value['sku_stock_expired_date'] . "'," . $value['sku_stock_qty'] . "");

					$getSkuStockId = $this->db->query("SELECT sku_stock_id 
															FROM sku_stock 
															WHERE depo_detail_id = '$depo_detail_id' AND sku_id = '" . $value['sku_id'] . "'
															AND FORMAT(sku_stock_expired_date,'yyyy-MM-dd') = '" . $value['sku_stock_expired_date'] . "'")->row();

					if ($getSkuStockId) {
						$skuStockId = $getSkuStockId->sku_stock_id;
					}

					$this->M_KonfirmasiPengirimanCanvas->Update_penerimaan_penjualan_sku_stock($penerimaan_penjualan_id, $value['sku_id'], $skuStockId, $value['sku_stock_expired_date']);

					$this->db->query("exec cek_exist_sku_stock_in_pallet '" . $value['pallet_id'] . "','" . $value['sku_id'] . "','$skuStockId','" . $value['sku_stock_expired_date'] . "'," . $value['sku_stock_qty'] . "");

					$sku_stock_card_keterangan = "Bukti Terima Barang dari Canvas";
					$sku_stock_card_jenis = "D";
					$sku_stock_card_is_posting = 0;

					$this->M_KonfirmasiPengirimanCanvas->Insert_sku_stock_card($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $sku_stock_card_keterangan, $sku_stock_card_jenis, $depo_id, $depo_detail_id, $value['sku_id'], $skuStockId, $value['sku_stock_qty'], $sku_stock_card_is_posting);
				}

				$this->M_KonfirmasiPengirimanCanvas->Delete_PalletDetail($delivery_order_batch_id);
				$this->M_KonfirmasiPengirimanCanvas->Delete_Pallet($delivery_order_batch_id);

				// echo "y";
			} else {

				$penerimaan_penjualan_id = $this->M_KonfirmasiPengirimanCanvas->get_penerimaan_penjualan_id($delivery_order_batch_id);
				$penerimaan_penjualan_id = $check_btb;

				// $this->M_KonfirmasiPengirimanCanvas->Update_StockSKU($delivery_order_batch_id, $depo_detail_id, $client_wms_id);

				// $data_do = $this->M_KonfirmasiPengirimanCanvas->get_data_do_by_id($delivery_order_batch_id, $delivery_order_id, $arrDataSkuBygroup);
				$data_sku_stock_by_pallet = $this->M_KonfirmasiPengirimanCanvas->get_sku_stock_by_pallet($delivery_order_batch_id);
				$pallet_fdjr = $this->M_KonfirmasiPengirimanCanvas->get_data_pallet_by_fdjr($delivery_order_batch_id);

				if ($arrDataSkuBygroup) {
					foreach ($arrDataSkuBygroup as $key2 => $value2) {
						$penerimaan_penjualan_detail_id = $this->M_KonfirmasiPengirimanCanvas->Get_NEWID();

						// $getSkuStockId = $this->db->query("SELECT sku_stock_id 
						// 																FROM sku_stock 
						// 																WHERE depo_detail_id = '$depo_detail_id' 
						// 																	AND sku_id = '" . $value2['sku_id'] . "'
						// 																	AND sku_stock_expired_date = '" . $value2['sku_expdate'] . "'")->row();

						// if ($getSkuStockId) {
						// 	$skuStockId = $getSkuStockId->sku_stock_id;
						// } else {
						// 	$newSkuStockId = $this->M_KonfirmasiPengirimanCanvas->Get_NEWID();
						// 	$this->db->insert("sku_stock", [
						// 		'sku_stock_id' => $newSkuStockId,
						// 		'unit_mandiri_id' => $this->session->userdata('unit_mandiri_id'),
						// 		'client_wms_id' => $client_wms_id,
						// 		'depo_id' => $this->session->userdata('depo_id'),
						// 		'depo_detail_id' => $depo_detail_id,
						// 		'sku_induk_id' => $value2['sku_induk_id'],
						// 		'sku_id' => $value2['sku_id'],
						// 		'sku_stock_expired_date' => $value2['sku_expdate'],
						// 		'sku_stock_awal' => 0,
						// 		'sku_stock_masuk' => $value2['qty_terima'],
						// 		'sku_stock_alokasi' => 0,
						// 		'sku_stock_akhir' => 0,
						// 		'sku_stock_is_jual' => 1,
						// 		'sku_stock_is_aktif' => 1,
						// 		'sku_stock_is_deleted' => 0,
						// 	]);

						// 	$skuStockId = $newSkuStockId;
						// }

						$this->M_KonfirmasiPengirimanCanvas->Insert_PenerimaanPenjualanDetail($penerimaan_penjualan_detail_id, $penerimaan_penjualan_id, $delivery_order_id, $value2, $skuStockId, $client_wms_id);
					}
				}

				foreach ($pallet_fdjr as $key2 => $value2) {
					$cek_pallet_penerimaan = $this->M_KonfirmasiPengirimanCanvas->cek_pallet_penerimaan($penerimaan_penjualan_id, $value2['pallet_id']);

					if ($cek_pallet_penerimaan == 0) {
						$this->M_KonfirmasiPengirimanCanvas->Insert_PenerimaanPenjualanDetail2($penerimaan_penjualan_id, $value2);
					}
				}

				// if ($penerimaan_tipe_id == "79F2522A-CEA5-4FF8-BC79-C0B28808877D") {
				// 	$this->M_KonfirmasiPengirimanCanvas->update_stock_delivery_order_detail($delivery_order_id, 0, 0);
				// }

				// foreach ($data_sku_stock_by_pallet as $key2 => $value2) {
				// 	$sku_stock_card_keterangan = "Bukti Terima Barang dari Canvas";
				// 	$sku_id = $value2['sku_id'];
				// 	$sku_stock_id = $value2['sku_stock_id'];
				// 	$sku_stock_card_jenis = "D";
				// 	$sku_stock_card_qty = $value2['sku_stock_qty'];
				// 	$sku_stock_card_is_posting = 0;

				// 	$this->M_KonfirmasiPengirimanCanvas->Insert_sku_stock_card($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $sku_stock_card_keterangan, $sku_stock_card_jenis, $depo_id, $depo_detail_id, $sku_id, $sku_stock_id, $sku_stock_card_qty, $sku_stock_card_is_posting);
				// }

				$pallet_temp = $this->M_KonfirmasiPengirimanCanvas->get_pallet_temp($delivery_order_batch_id);
				$pallet_detail_temp = $this->M_KonfirmasiPengirimanCanvas->get_pallet_detail_temp($delivery_order_batch_id);

				foreach ($pallet_temp as $key => $value) {
					$check_pallet = $this->M_KonfirmasiPengirimanCanvas->CheckPalletById($value['pallet_id']);
					if ($check_pallet == "0") {
						$this->M_KonfirmasiPengirimanCanvas->Insert_Pallet($value);
						$this->M_KonfirmasiPengirimanCanvas->Insert_rak_lajur_detail_pallet($value);
						$this->M_KonfirmasiPengirimanCanvas->Insert_rak_lajur_detail_pallet_his($depo_detail_id, $value);
					} else {
						$this->M_KonfirmasiPengirimanCanvas->Update_Pallet($value);
						$this->M_KonfirmasiPengirimanCanvas->Update_rak_lajur_detail_pallet($value);
						$this->M_KonfirmasiPengirimanCanvas->Insert_rak_lajur_detail_pallet_his($depo_detail_id, $value);
					}
				}

				foreach ($pallet_detail_temp as $key => $value) {
					// $check_pallet = $this->M_KonfirmasiPengirimanCanvas->CheckPalletDetailById($value['pallet_id'], $value['sku_id'], $value['penerimaan_tipe_id'], $value['sku_stock_expired_date']);
					// $check_pallet = $this->M_KonfirmasiPengirimanCanvas->CheckPalletDetailById($value['pallet_id'], $value['sku_stock_id']);
					// if ($check_pallet == "0") {
					// 	$this->M_KonfirmasiPengirimanCanvas->Insert_PalletDetail($value);
					// } else {
					// 	$this->M_KonfirmasiPengirimanCanvas->Update_PalletDetail($value['pallet_id'], $value['sku_id'], $value['sku_stock_id'], $value);
					// }

					$cek_sku_stock = $this->db->query("exec cek_exist_sku_stock '" . $this->session->userdata('depo_id') . "','$depo_detail_id','" . $value['sku_id'] . "','" . $value['sku_stock_expired_date'] . "'," . $value['sku_stock_qty'] . "");

					$getSkuStockId = $this->db->query("SELECT sku_stock_id 
															FROM sku_stock 
															WHERE depo_detail_id = '$depo_detail_id' AND sku_id = '" . $value['sku_id'] . "'
															AND FORMAT(sku_stock_expired_date,'yyyy-MM-dd') = '" . $value['sku_stock_expired_date'] . "'")->row();

					if ($getSkuStockId) {
						$skuStockId = $getSkuStockId->sku_stock_id;
					}

					$this->M_KonfirmasiPengirimanCanvas->Update_penerimaan_penjualan_sku_stock($penerimaan_penjualan_id, $value['sku_id'], $skuStockId, $value['sku_stock_expired_date']);

					$this->db->query("exec cek_exist_sku_stock_in_pallet '" . $value['pallet_id'] . "','" . $value['sku_id'] . "','$skuStockId','" . $value['sku_stock_expired_date'] . "'," . $value['sku_stock_qty'] . "");

					$sku_stock_card_keterangan = "Bukti Terima Barang dari Canvas";
					$sku_stock_card_jenis = "D";
					$sku_stock_card_is_posting = 0;

					$this->M_KonfirmasiPengirimanCanvas->Insert_sku_stock_card($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $sku_stock_card_keterangan, $sku_stock_card_jenis, $depo_id, $depo_detail_id, $value['sku_id'], $skuStockId, $value['sku_stock_qty'], $sku_stock_card_is_posting);
				}

				$this->M_KonfirmasiPengirimanCanvas->Delete_PalletDetail($delivery_order_batch_id);
				$this->M_KonfirmasiPengirimanCanvas->Delete_Pallet($delivery_order_batch_id);
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

	public function search_filter_chosen_sku()
	{
		$filter_sku_id = array();
		$brand = $this->input->post('brand');
		$principle = $this->input->post('principle');
		$sku_nama_produk = $this->input->post('sku_nama_produk');
		$sku_kemasan = $this->input->post('sku_kemasan');
		$sku_satuan = $this->input->post('sku_satuan');
		$list_sku = $this->input->post('list_sku');

		if (isset($list_sku)) {
			if (count($list_sku) > 0) {
				foreach ($list_sku as $value) {
					if ($value['sku_id'] != "" && $value['sku_id'] !== NULL) {
						array_push($filter_sku_id, "'" . $value['sku_id'] . "'");
					}
				}
			}
		}

		$data = $this->M_KonfirmasiPengirimanCanvas->search_filter_chosen_sku($brand, $principle, $sku_nama_produk, $sku_kemasan, $sku_satuan, $filter_sku_id);

		echo json_encode($data);
	}

	public function Get_data_sku_summary_not_have_do()
	{
		$data = array();
		$list_sku_group = array();
		$list_do = $this->input->post('list_do');

		$filter_do = array();

		if (count($list_do) > 0) {
			foreach ($list_do as $value) {
				array_push($filter_do, "'" . $value . "'");
			}
		}

		$list_sku = $this->M_KonfirmasiPengirimanCanvas->Get_delivery_order_detail_by_id($filter_do);

		if (isset($list_sku)) {
			if (count($list_sku) > 0) {
				foreach ($list_sku as $value) {
					if ($value != "" && $value !== NULL) {
						$sku_group = $this->M_KonfirmasiPengirimanCanvas->Exec_proc_sku_konversi_group_canvas($value['sku_konversi_group'], $value['sku_qty_composite']);

						if (count($sku_group) > 0) {
							foreach ($sku_group as $value_group) {
								$list_sku_group = array("sku_konversi_group" => $value_group['sku_konversi_group'], "sku_nama_produk" => $value_group['sku_nama_produk'], "composite_satuan" => $value_group['composite_satuan'], "qty_canvas" => $value_group['qty_canvas'], "qty_terjual" => $value_group['qty_terjual']);
							}
							array_push($data, $list_sku_group);
						}
					}
				}
			}
		}

		echo json_encode($data);
	}

	public function Get_data_sku_by_group()
	{
		$sku_konversi_group = $this->input->post('sku_konversi_group');

		$data = $this->M_KonfirmasiPengirimanCanvas->Get_data_sku_by_group($sku_konversi_group);

		echo json_encode($data);
	}
}
