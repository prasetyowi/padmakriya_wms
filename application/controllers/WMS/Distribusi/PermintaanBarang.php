<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PermintaanBarang extends CI_Controller
{
	private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		if ($this->session->has_userdata('pengguna_id') == 0) :
			redirect(base_url('MainPage'));
		endif;

		$this->depo_id = $this->session->userdata('depo_id');
		$this->unit_mandiri_id = $this->session->userdata('unit_mandiri_id');
		$this->MenuKode = "135006000";
		$this->load->model('M_Picking');
		$this->load->model('M_Menu');
		$this->load->model('M_Depo');
		$this->load->model('M_Function');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');
	}

	public function PermintaanBarangMenu()
	{
		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);

		$date = date("dmY");
		$product_identification = "P1";
		$product_code = str_pad($product_identification, 3, "-", STR_PAD_LEFT);
		$batch_identification = "B1";
		$batch_code = str_pad($batch_identification, 3, "-", STR_PAD_LEFT);

		$code = $date . $product_code . $batch_code;


		$data['listDoBatch'] = $this->M_Picking->getListDoBatch();
		// $data['listPicking'] = $this->M_Picking->getDataPickingList();
		$data['listLayanan'] = $this->M_Picking->getListLayanan();
		$data['listPengiriman'] = $this->M_Picking->getListPengiriman();
		$data['listArea'] = $this->M_Picking->getArea();
		$data['driver'] = $this->M_Picking->getDriver();
		$data['listTipeDO'] = $this->M_Picking->getTipeDO();
		// var_dump($data['listTipeDO']);
		// return false;
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage/Index'));
			exit();
		}

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		// Kebutuhan Authority Menu
		$this->session->set_userdata('MenuLink', ltrim(current_url(), base_url()));

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

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PickingList/PickingList', $data);
		$this->load->view('layouts/sidebar_footer', $data);
		$this->load->view('WMS/PickingList/S_PickingList', $data);
	}

	public function getFDJRByDriver()
	{
		$driver = $this->input->post('driver');
		$data = $this->M_Picking->getFDJRByDriver($driver);

		echo json_encode($data);
	}

	public function PickingProgressForm()
	{
		$data = array();
		$detailPickList = [];
		$gudangId = $this->input->post('select-gudang');
		$pickListId = $this->input->post('select-pick-list');
		if (strlen($gudangId) == 0) {
			$gudangId = 'AA733980-4641-4737-8A0E-B6492432A662';
		}
		if (strlen($pickListId) > 0) {
			$detailPickList = $this->M_Picking->getPickListDetail($pickListId);
		}
		$data['detailPickList'] = $detailPickList;
		$data['listGudang'] = $this->M_Picking->getAllGudang();
		$data['listPickList'] = $this->M_Picking->getPickListByGudang($gudangId);
		$data['gudangId'] = $gudangId;
		$data['pickListId'] = $pickListId;





		$data['depo'] = $this->M_Picking->Get_Depo_By_Depo_ID($this->depo_id);
		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		// Kebutuhan Authority Menu
		$this->session->set_userdata('MenuLink', ltrim(current_url(), base_url()));

		$this->load->view('layouts/header', $data);
		$this->load->view('WMS/PickList/PickingProgressForm', $data);
		$this->load->view('layouts/footer', $data);

		$this->load->view('WMS/PickList/PickingProgressFormScript');
	}
	public function PickingDetail()
	{
		$data = array();
		$picking_list_id = $this->uri->segment('5');
		$data['picking_list_id'] = $this->uri->segment('5');
		$data['picking'] = $this->M_Picking->getDataPickingListDetail($picking_list_id);

		$data['area'] = $this->M_Picking->Get_PickingArea($data['picking']->delivery_order_batch_id);

		$data['depo'] = $this->M_Picking->Get_Depo_By_Depo_ID($this->depo_id);

		$depo_detail_id = $data['picking']->depo_detail_id;


		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');
		$this->session->set_userdata('MenuLink', ltrim(current_url(), base_url()));
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
		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PickingList/PickingDetail', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('WMS/PickingList/S_PickingDetail');
	}
	public function PickingForm()
	{
		$data = array();
		$detailPickList = [];

		$data['listPengiriman'] = $this->M_Picking->getListPengiriman();
		$data['listDoBatch'] = $this->M_Picking->getListDoBatch();
		$data['listArea'] = $this->M_Picking->getArea();

		$data['depo'] = $this->M_Picking->Get_Depo_By_Depo_ID($this->depo_id);
		$data['gudang'] = $this->M_Picking->Get_DepoD_By_Depo_ID($this->depo_id);
		// var_dump($data['gudang']);return false;
		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');
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
		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();
		// Kebutuhan Authority Menu
		$this->session->set_userdata('MenuLink', ltrim(current_url(), base_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PickingList/PickingForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);


		$this->load->view('WMS/PickingList/S_PickingForm');
	}

	public function PickingPrint()
	{
		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);

		$date = date("dmY");

		$data['listDoBatch'] = $this->M_Picking->getListDoBatch();
		$data['listPicking'] = $this->M_Picking->getDataPickingList();
		$data['listLayanan'] = $this->M_Picking->getListLayanan();
		$data['listPengiriman'] = $this->M_Picking->getListPengiriman();
		$data['listArea'] = $this->M_Picking->getArea();

		$data['listTipeDO'] = $this->M_Picking->getTipeDO();

		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage/Index'));
			exit();
		}

		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		// Kebutuhan Authority Menu
		$this->session->set_userdata('MenuLink', ltrim(current_url(), base_url()));

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
		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PickingList/PickingPrint', $data);
		$this->load->view('layouts/sidebar_footer', $data);
		$this->load->view('WMS/PickingList/S_PickingList', $data);
		$this->load->view('master/S_GlobalVariable', $data);
	}

	public function SavePickingList()
	{
		// try {

		$this->load->library('form_validation');

		$this->form_validation->set_rules('delivery_order_batch_id', 'DO Batch', 'trim|required', [
			'required' => 'DO Batch tidak boleh kosong!'
		]);

		$this->form_validation->set_rules('depo_detail_id', 'depo_detail_id', 'trim|required', [
			'required' => 'Gudang tidak boleh kosong!'
		]);

		// eror handling validation
		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());
			$response = array(
				'message' => validation_errors(),
				'status' =>  0
			);
			echo json_encode($response);
			return false;
		}

		$picking_list_tgl_kirim = $this->input->post('picking_list_tgl_kirim');

		$tglKirim = date_create($picking_list_tgl_kirim);

		$newid1 = $this->M_Function->Get_NewID();
		$newid = $newid1[0]['kode'];

		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_PB';

		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_Picking->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		// {unit}/{prefix}/tgl.bln.thun.counter
		// generate kode
		// $generate_kode = $this->M_Picking->generateKode($date_now,$prefix,$unit);
		// var_dump($generate_kode); return false;   
		// $kode = $generate_kode->kode;

		// $kode = 'SBY/DO/2504202201';
		// echo $kode;
		// return false;

		// get last kode
		// $date_now = date('d');
		// $month_now = date('m');
		// $year_now = date('Y');
		// $kode = $this->M_Picking->getLastKode($prefix,$date_now,$month_now,$year_now);
		// var_dump($kode);    
		// return false;

		$dataPickList = array(
			'picking_list_id' => $newid,
			'picking_list_kode' => $generate_kode,
			'area_id' => $this->input->post('area_id'),
			'tipe_delivery_order_id' => $this->input->post('tipe_delivery_order_id'),
			'delivery_order_batch_id' => $this->input->post('delivery_order_batch_id'),
			'picking_list_tgl_kirim' => date_format($tglKirim, "Y-m-d H:i:s"),
			'picking_list_keterangan' => $this->input->post('picking_list_keterangan'),
			'picking_list_status' => $this->input->post('picking_list_status'),
			'depo_detail_id' => $this->input->post('depo_detail_id'),
			'depo_id' => $this->input->post('depo_id'),
			'unit_mandiri_id' => $this->session->userdata('unit_mandiri_id'),
			'picking_list_create_tgl' => date('Y-m-d h:i:s'),
			'picking_list_create_who' => $this->session->userdata('pengguna_username'),
		);

		$do_id = $this->input->post('do_id');
		$prioritas_stock = $this->input->post('prioritas_stock');
		$dataDoD = $this->input->post('dataDoD'); //data detail do
		$dataSkuED = $this->input->post('dataSkuED'); // data sku stock per ED

		if ($dataDoD == null) {
			$response = array(
				'message' => 'Mohon Cek detail ED terlebih dahulu !',
				'status' =>  0
			);
		}

		$data = $this->M_Picking->SavePickingList($dataPickList, $do_id, $prioritas_stock, $dataDoD, $dataSkuED);
		// $data = 1;
		// var_dump($data);
		if ($data == 1) {
			$response = array(
				'message' => 'success',
				'status' =>  1
			);
		} else {
			$response = array(
				'message' => 'Failed create data !!',
				'status' =>  0
			);
		}
		echo json_encode($response);

		// } catch (\Throwable $th) {
		//     $response = array(
		//         'message' => $th,
		//         'status' =>  0
		//     );

		//     echo json_encode($response);   
		//     //throw $th;
		// }

		// var_dump($data);                                                                                                                                                                                             
	}

	public function GetDoBatchByArea()
	{
		$area_id = $this->input->get('area_id');
		$tipe = $this->input->get('tipe');
		// echo $kode;
		$DoBatch = $this->M_Picking->GetDoBatchByArea($area_id, $tipe);
		// var_dump($DoBatch);return false;
		header('Content-Type: application/json');

		$data = json_encode($DoBatch);

		echo $data;
	}
	public function getPickingDetailSKU()
	{
		$picking_list_id = $this->input->get('picking_list_id');
		$do_id = $this->input->get('do_id');

		$DoBatch = $this->M_Picking->getPickingDetailSKU($picking_list_id, $do_id);
		// var_dump($DoBatch);return false;
		header('Content-Type: application/json');

		$data = json_encode($DoBatch);

		echo $data;
	}

	function getDataPickingList()
	{
		$data = $this->M_Picking->getDataPickingList();
		$fixData = [];
		if (!empty($data)) {
			foreach ($data as $key => $value) {
				$area = $this->db->select("area.area_nama")->from("delivery_order_area")
					->join("area", "delivery_order_area.area_id = area.area_id", "left")
					->where("delivery_order_area.delivery_order_batch_id", $value->delivery_order_batch_id)->get()->result();

				$arrArea = [];

				foreach ($area as $key => $val) {
					$arrArea[] = $val->area_nama;
				}

				array_push($fixData, [
					'picking_list_id' => $value->picking_list_id,
					'unit_mandiri_id' => $value->unit_mandiri_id,
					'depo_id' => $value->depo_id,
					'depo_detail_id' => $value->depo_detail_id,
					'area_id' => $value->area_id,
					'delivery_order_batch_id' => $value->delivery_order_batch_id,
					'tipe_delivery_order_id' => $value->tipe_delivery_order_id,
					'picking_list_kode' => $value->picking_list_kode,
					'picking_list_tgl_kirim' => $value->picking_list_tgl_kirim,
					'picking_list_create_who' => $value->picking_list_create_who,
					'picking_list_create_tgl' => $value->picking_list_create_tgl,
					'picking_list_keterangan' => $value->picking_list_keterangan,
					'picking_list_status' => $value->picking_list_status,
					'picking_list_is_from_validation' => $value->picking_list_is_from_validation,
					'tipe_delivery_order_alias' => $value->tipe_delivery_order_alias,
					'tgl_picking' => $value->tgl_picking,
					'do_batch_kode' => $value->do_batch_kode,
					'tipe_pengiriman' => $value->tipe_pengiriman,
					'nama_layanan' => $value->nama_layanan,
					'ispacking' => $value->ispacking,
					'karyawan_nama' => $value->karyawan_nama,
					'area' => $arrArea
				]);
			}
		}
		//output dalam format JSON
		echo json_encode($fixData);
	}

	function getDataPickingListSearch()
	{
		// $area_id = $this->input->get('area');
		// $pick_id = $this->input->get('pick_id');
		// $tipe_layanan = $this->input->get('tipe_layanan');
		// $tipe_pengiriman = $this->input->get('tipe_pengiriman');
		// $status = $this->input->get('status');

		$tanggal 	= explode(" - ", $this->input->get('tgl'));
		$tgl 		= date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[0])));
		$tgl2 		= date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[1])));
		$driver		= $this->input->post('driver');
		$fdjr		= $this->input->post('fdjr');

		$tgl_picking_list = $this->input->get('tgl_picking_list');
		// $tgl = date_create($tgl_picking_list);
		// $tgl = date_format($tgl, "Y-m-d");
		$tgl_picking_list2 = $this->input->get('tgl_picking_list2');
		// $tgl2 = date_create($tgl_picking_list2);
		// $tgl2 = date_format($tgl2, "Y-m-d");
		$tipe_delivery_order_id = $this->input->get('tipe_delivery_order_id');
		// $data = $this->M_Picking->getDataPickingListSearch($area_id, $pick_id, $status, $tgl, $tgl2, $tipe_delivery_order_id, $tipe_layanan, $tipe_pengiriman);
		$data = $this->M_Picking->getDataPickingListSearch($tgl, $tgl2, $driver, $fdjr);

		$fixData = [];
		if (!empty($data)) {
			foreach ($data as $key => $value) {
				$area = $this->db->select("area.area_nama")->from("delivery_order_area")
					->join("area", "delivery_order_area.area_id = area.area_id", "left")
					->where("delivery_order_area.delivery_order_batch_id", $value->delivery_order_batch_id)->get()->result();

				$arrArea = [];

				foreach ($area as $key => $val) {
					$arrArea[] = $val->area_nama;
				}

				array_push($fixData, [
					'picking_list_id' => $value->picking_list_id,
					'unit_mandiri_id' => $value->unit_mandiri_id,
					'depo_id' => $value->depo_id,
					'depo_detail_id' => $value->depo_detail_id,
					'area_id' => $value->area_id,
					'delivery_order_batch_id' => $value->delivery_order_batch_id,
					'tipe_delivery_order_id' => $value->tipe_delivery_order_id,
					'picking_list_kode' => $value->picking_list_kode,
					'picking_list_tgl_kirim' => $value->picking_list_tgl_kirim,
					'picking_list_create_who' => $value->picking_list_create_who,
					'picking_list_create_tgl' => $value->picking_list_create_tgl,
					'picking_list_keterangan' => $value->picking_list_keterangan,
					'picking_list_status' => $value->picking_list_status,
					'picking_list_is_from_validation' => $value->picking_list_is_from_validation,
					'tipe_delivery_order_alias' => $value->tipe_delivery_order_alias,
					'tgl_picking' => $value->tgl_picking,
					'do_batch_kode' => $value->do_batch_kode,
					'tipe_pengiriman' => $value->tipe_pengiriman,
					'nama_layanan' => $value->nama_layanan,
					'ispacking' => $value->ispacking,
					'karyawan_nama' => $value->karyawan_nama,
					'area' => $arrArea
				]);
			}
		}
		//output dalam format JSON
		echo json_encode($fixData);
	}

	public function GetDoBatchById()
	{
		$doBatchId = $this->input->get('DoBatchId');

		// echo $kode;
		$DoBatch = $this->M_Picking->GetDoBatchById($doBatchId);

		header('Content-Type: application/json');

		$data = json_encode($DoBatch);

		echo $data;
	}
	public function GetPickingById()
	{
		$PicklistId = $this->input->get('PicklistId');

		// echo $kode;
		$Picklist = $this->M_Picking->GetPickingById($PicklistId);

		header('Content-Type: application/json');

		$data = json_encode($Picklist);

		echo $data;
	}

	public function getDoByBatchId()
	{
		try {
			$doBatchId = $this->input->get('DoBatchId');
			$PickingListID = $this->input->get('PickingListID');

			// echo $kode;
			$Do = $this->M_Picking->getDoByBatchId($doBatchId, $PickingListID);

			header('Content-Type: application/json');

			$data = json_encode($Do);

			echo $data;
		} catch (\Throwable $th) {

			// echo array();
		}
	}

	public function getDoDById()
	{
		$DoID = $this->input->get('DoID');

		// echo $DoID;
		$DoD = $this->M_Picking->getDoDById($DoID);

		header('Content-Type: application/json');

		$data = json_encode($DoD);

		echo $data;
	}
	public function getSKUStockBySKUID()
	{
		$skuID = $this->input->get('sku_id');
		$depoDID = $this->input->get('depo_detail_id');
		$depoID = $this->input->get('depo_id');

		// echo $kode;
		$Sku = $this->M_Picking->getSKUStockBySKUID($skuID, $depoDID, $depoID);

		header('Content-Type: application/json');

		$data = json_encode($Sku);

		echo $data;
	}

	public function test()
	{
		$data = array();
		$picking_list_id = $this->uri->segment('5');
		$data['picking'] = $this->M_Picking->getDataPickingListDetail($picking_list_id);
		$data['picking_list_id'] = $this->uri->segment('5');
		// var_dump($data['picking']);
		// return false;

		$data['depo'] = $this->M_Picking->Get_Depo_By_Depo_ID($this->depo_id);
		$depo_detail_id = $data['picking']->depo_detail_id;


		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');
		$this->session->set_userdata('MenuLink', ltrim(current_url(), base_url()));
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
		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PickingList/PickingDetail', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('WMS/PickingList/S_PickingDetail');
	}
}
