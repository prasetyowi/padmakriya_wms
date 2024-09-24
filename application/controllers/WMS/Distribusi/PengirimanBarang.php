<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PengirimanBarang extends CI_Controller
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
		$this->MenuKode = "135011000";
		$this->load->model('M_Picking');
		$this->load->model('WMS/M_PengirimanBarang', 'M_PengirimanBarang');
		$this->load->model('M_Menu');
		$this->load->model('M_Depo');
		$this->load->model('M_Function');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->library(['form_validation', 'pdfgenerator']);
	}

	public function PengirimanBarangMenu()
	{
		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);

		$date = date("dmY");

		$data['listDoBatch'] = $this->M_PengirimanBarang->getListDoBatchPick();
		$data['listPickOrder'] = $this->M_PengirimanBarang->getListPickOrder();
		// $data['listPicking'] = $this->M_PengirimanBarang->getDataPickingList();
		// $data['listLayanan'] = $this->M_PengirimanBarang->getListLayanan();
		// $data['listPengiriman'] = $this->M_PengirimanBarang->getListPengiriman();
		// $data['listArea'] = $this->M_PengirimanBarang->getArea();

		// $data['listTipeDO'] = $this->M_PengirimanBarang->getTipeDO();

		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
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
		$this->load->view('WMS/PengirimanBarang/PengirimanBarang', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PengirimanBarang/S_PengirimanBarang', $data);
	}

	public function PengirimanBarangForm()
	{
		$data = array();
		$detailPickList = [];

		$data['listDoBatch'] = $this->M_PengirimanBarang->getListDoBatchPick();
		$data['listPickOrder'] = $this->M_PengirimanBarang->getListPickOrder();
		$data['listDriver'] = $this->M_PengirimanBarang->getListDriver();
		// var_dump($data['gudang']);return false;
		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');
		$data['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css',
			Get_Assets_Url() . '/node_modules/lightbox2/src/css/lightbox.css'
		);

		$data['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . '/node_modules/html5-qrcode/html5-qrcode.min.js',
			Get_Assets_Url() . '/node_modules/lightbox2/src/js/lightbox.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu
		$this->session->set_userdata('MenuLink', ltrim(current_url(), base_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PengirimanBarang/PengirimanBarangForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);


		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PengirimanBarang/S_PengirimanBarangForm');
	}

	public function PengirimanBarangDetail()
	{
		$data = array();
		$pengiriman_barang_id = $this->uri->segment('5');
		$data['stp'] = $this->M_PengirimanBarang->getDataPengirimanDetail($pengiriman_barang_id);
		$data['stp_d1'] = $this->M_PengirimanBarang->getDataPengirimanDetailD1($pengiriman_barang_id);
		$data['stp_d2'] = $this->M_PengirimanBarang->getDataPengirimanDetailD2($pengiriman_barang_id);
		$data['stp_d3'] = $this->M_PengirimanBarang->getDataPengirimanDetailD3($pengiriman_barang_id);
		$data['stp_d4'] = $this->M_PengirimanBarang->getDataPengirimanDetailD4($pengiriman_barang_id);
		// var_dump($data['stp_d2']);
		// return false;

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
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

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PengirimanBarang/PengirimanBarangDetail', $data);
		$this->load->view('layouts/sidebar_footer', $data);


		// $this->load->view('master/PengirimanBarang/S_PengirimanBarangDetail');

	}

	public function PengirimanBarangEdit()
	{
		$data = array();
		$pengiriman_barang_id = $this->uri->segment('5');
		$data['stp'] = $this->M_PengirimanBarang->getDataPengirimanDetail($pengiriman_barang_id);
		$data['stp_d1'] = $this->M_PengirimanBarang->getDataPengirimanDetailD1($pengiriman_barang_id);
		$data['stp_d2'] = $this->M_PengirimanBarang->getDataPengirimanDetailD2($pengiriman_barang_id);
		$data['stp_d3'] = $this->M_PengirimanBarang->getDataPengirimanDetailD3($pengiriman_barang_id);
		$data['stp_d4'] = $this->M_PengirimanBarang->getDataPengirimanDetailD4($pengiriman_barang_id);
		// var_dump($data['stp_d2']);
		// return false;

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');
		$this->session->set_userdata('MenuLink', ltrim(current_url(), base_url()));
		$data['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css',
			Get_Assets_Url() . '/node_modules/lightbox2/src/css/lightbox.css'
		);

		$data['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . '/node_modules/html5-qrcode/html5-qrcode.min.js',
			Get_Assets_Url() . '/node_modules/lightbox2/src/js/lightbox.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PengirimanBarang/PengirimanBarangEdit', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PengirimanBarang/S_PengirimanBarangEdit');
	}

	public function getDataDetailStp1()
	{
		$pengiriman_barang_id = $this->input->post('pengirimanBarangId');
		echo json_encode($this->M_PengirimanBarang->getDataPengirimanDetailD1($pengiriman_barang_id));
	}

	public function getDataDetailStp2()
	{
		$pengiriman_barang_id = $this->input->post('pengirimanBarangId');
		echo json_encode($this->M_PengirimanBarang->getDataPengirimanDetailD2($pengiriman_barang_id));
	}

	public function getDataDetailStp3()
	{
		$pengiriman_barang_id = $this->input->post('pengirimanBarangId');
		echo json_encode($this->M_PengirimanBarang->getDataPengirimanDetailD3($pengiriman_barang_id));
	}

	public function getDataDetailStp4()
	{
		$pengiriman_barang_id = $this->input->post('pengirimanBarangId');
		echo json_encode($this->M_PengirimanBarang->getDataPengirimanDetailD4($pengiriman_barang_id));
	}

	public function SavePengirimanBarang()
	{
		$picking_order_id = $this->input->post('ppb_id');
		$delivery_order_batch_id = $this->input->post('no_batch_do');
		$keterangan = $this->input->post('keterangan');
		$last_update = $this->input->post('last_update');

		$newid1 = $this->M_Function->Get_NewID();
		$newid = $newid1[0]['kode'];

		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_STP';

		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_Picking->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$dataSTP = array(
			'serah_terima_kirim_id' => $newid,
			'delivery_order_batch_id' => $delivery_order_batch_id,
			'picking_order_id' => $picking_order_id,
			'serah_terima_kirim_kode' => $generate_kode,
			'serah_terima_kirim_tgl' => date('Y-m-d h:i:s'),
			'serah_terima_kirim_keterangan' => $keterangan,
			'serah_terima_kirim_who' => $this->session->userdata('pengguna_username'),
		);

		// tipe bulk -> tbl [serah_terima_kirim_d1]
		$dataBulk = $this->input->post('dataBulk');
		$dataFlushOut = $this->input->post('dataFlushOut');
		// $jumlah_ambil_plan = $this->input->post('qty_ambil_plan');
		// $jumlah_ambil_aktual = $this->input->post('qty_ambil_aktual');
		// $jumlah_serah_terima = $this->input->post('qty_serah_terima');
		// $jumlah_serah_terima_rusak = $this->input->post('qty_serah_terima_rusak');

		// tipe standar -> tbl [serah_terima_kirim_d2]
		$dataStandar = $this->input->post('dataStandar');
		// $jumlah_paket = $this->input->post('qty_packed');
		// $jumlah_serah_terima_paket = $this->input->post('qty_packed_terima');

		// tipe reschedule -> tbl [serah_terima_kirim_d3]
		$dataReschedule = $this->input->post('dataReschedule');

		// tipe canvas -> tbl [serah_terima_kirim_d4]
		$dataCanvas = $this->input->post('dataCanvas');

		$data = $this->M_PengirimanBarang->SavePengirimanBarang($dataSTP, $dataBulk, $dataFlushOut, $dataStandar, $dataReschedule, $dataCanvas, $picking_order_id, $last_update, $newid);

		echo json_encode($data);
	}

	public function UpdatePengirimanBarang()
	{
		$serah_terima_kirim_id 			= $this->input->post('serah_terima_kirim_id');
		$last_update 					= $this->input->post('last_update');


		$serah_terima_kirim_d1_id 		= $this->input->post('serah_terima_kirim_d1_id');
		$jumlah_serah_terima_1 			= $this->input->post('jumlah_serah_terima_1');
		$jumlah_serah_terima_rusak_1 	= $this->input->post('jumlah_serah_terima_rusak_1');

		$serah_terima_kirim_d2_id 		= $this->input->post('serah_terima_kirim_d2_id');
		$jumlah_serah_terima_2 			= $this->input->post('jumlah_serah_terima_2');

		$serah_terima_kirim_d3_id 		= $this->input->post('serah_terima_kirim_d3_id');
		$jumlah_serah_terima_3 			= $this->input->post('jumlah_serah_terima_3');
		$jumlah_serah_terima_rusak_3 	= $this->input->post('jumlah_serah_terima_rusak_3');

		$serah_terima_kirim_d4_id 		= $this->input->post('serah_terima_kirim_d4_id');
		$jumlah_serah_terima_4 			= $this->input->post('jumlah_serah_terima_4');
		$jumlah_serah_terima_rusak_4 	= $this->input->post('jumlah_serah_terima_rusak_4');

		$data 							= $this->M_PengirimanBarang->UpdatePengirimanBarang($serah_terima_kirim_id, $last_update, $serah_terima_kirim_d1_id, $jumlah_serah_terima_1, $jumlah_serah_terima_rusak_1, $serah_terima_kirim_d2_id, $jumlah_serah_terima_2, $serah_terima_kirim_d3_id, $jumlah_serah_terima_3, $jumlah_serah_terima_rusak_3, $serah_terima_kirim_d4_id, $jumlah_serah_terima_4, $jumlah_serah_terima_rusak_4);

		echo json_encode($data);
	}

	public function ConfirmPengirimanBarang()
	{
		$serah_terima_kirim_id 		= $this->input->post('serah_terima_kirim_id');
		$delivery_order_batch_id 	= $this->input->post('delivery_order_batch_id');
		$delivery_order_id 			= $this->input->post('delivery_order_id');
		$last_update 				= $this->input->post('last_update');
		$data 						= $this->M_PengirimanBarang->ConfirmPengirimanBarang($serah_terima_kirim_id, $delivery_order_batch_id, $delivery_order_id, $last_update);

		echo json_encode($data);
	}

	public function getDataPengirimanSearch()
	{
		$ppb_id = $this->input->get('picking_order_id');
		$date   = explode(" - ", $this->input->get('tgl'));
		$tgl   	= date('Y-m-d', strtotime(str_replace("/", "-", $date[0])));
		$tgl2   = date('Y-m-d', strtotime(str_replace("/", "-", $date[1])));

		$data = $this->M_PengirimanBarang->getDataPengirimanSearch($ppb_id, $tgl, $tgl2);
		header('Content-Type: application/json');

		$data = json_encode($data);

		echo $data;
	}

	public function GetPpbByDriver()
	{
		$karyawanId = $this->input->get('karyawanId');
		$data = $this->M_PengirimanBarang->GetPpbByDriver($karyawanId);
		// var_dump($data);return false;
		header('Content-Type: application/json');

		$data = json_encode($data);

		echo $data;
	}

	public function GetDoBatchByPickOrderId()
	{
		$ppb_id = $this->input->get('ppb_id');
		$data = $this->M_PengirimanBarang->GetDoBatchByPickOrderId($ppb_id);
		// var_dump($data);return false;
		header('Content-Type: application/json');

		$data = json_encode($data);

		echo $data;
	}
	public function GetDataDetailBulk()
	{
		$ppb_id = $this->input->get('ppb_id');

		$data = $this->M_PengirimanBarang->GetDataDetailBulk($ppb_id);
		// var_dump($data);return false;
		header('Content-Type: application/json');

		$data = json_encode($data);

		echo $data;
	}
	public function GetDataDetailReschedule()
	{
		$ppb_id = $this->input->get('ppb_id');

		$data = $this->M_PengirimanBarang->GetDataDetailReschedule($ppb_id);
		// var_dump($data);return false;
		header('Content-Type: application/json');

		$data = json_encode($data);

		echo $data;
	}
	public function GetDataDetailStandar()
	{
		$ppb_id = $this->input->get('ppb_id');

		$data = $this->M_PengirimanBarang->GetDataDetailStandar($ppb_id);
		// var_dump($data);return false;
		header('Content-Type: application/json');

		$data = json_encode($data);

		echo $data;
	}

	public function GetDataDetail()
	{
		$ppb_id = $this->input->get('ppb_id');
		$tipe = $this->input->get('tipe');

		$data = $this->M_PengirimanBarang->GetDataDetail($ppb_id, $tipe);
		// var_dump($data);return false;
		header('Content-Type: application/json');

		$data = json_encode($data);

		echo $data;
	}

	public function print()
	{
		$id = $this->input->get('id');
		$data['row'] = $this->M_PengirimanBarang->getDataReport($id);

		// $this->load->view('WMS/PengirimanBarang/view_cetak', $data);

		//title dari pdf
		$this->data['title_pdf'] = 'Laporan Distribusi Pengiriman Barang';

		// //filename dari pdf ketika didownload
		$file_pdf = 'report_distribusi_pengiriman_' . date('d-M-Y H:i:s');
		// //setting paper
		$paper = 'A4';
		// //orientasi paper potrait / landscape
		$orientation = "landscape";

		$html = $this->load->view('WMS/PengirimanBarang/view_cetak', $data, true);

		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}
	public function print_serah_terima()
	{
		$id = $this->input->get('id');
		$data['stp'] = $this->M_PengirimanBarang->getDataPengirimanDetail($id);

		$data['stp_d1'] = $this->M_PengirimanBarang->getDataPengirimanDetailD1($id);
		$data['stp_d2'] = $this->M_PengirimanBarang->getDataPengirimanDetailD2($id);
		$data['stp_d3'] = $this->M_PengirimanBarang->getDataPengirimanDetailD3($id);
		$data['stp_d4'] = $this->M_PengirimanBarang->getDataPengirimanDetailD4($id);
		// $data['row'] = $this->M_PengirimanBarang->getDataReport($id);
		// echo json_encode($data['stp_d1']);
		// die;
		// $this->load->view('WMS/PengirimanBarang/view_cetak', $data);

		//title dari pdf
		$this->data['title_pdf'] = 'Laporan Serah Terima Barang';

		// //filename dari pdf ketika didownload
		$file_pdf = 'report_distribusi_pengiriman_' . date('d-M-Y H:i:s');
		// //setting paper
		$paper = 'A4';
		// //orientasi paper potrait / landscape
		$orientation = "landscape";

		$html = $this->load->view('WMS/PengirimanBarang/view_cetak_serah_terima', $data, true);

		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}

	public function CheckScanKodeSKU()
	{
		$dataPost = $this->input->post();

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');

		$result = "";

		if ($dataPost['mode'] == 'insert') {
			if (in_array($dataPost['type'], ['bulk', 'reschedule', 'canvas'])) {
				$result .= $this->db->query("SELECT sku.sku_id
																	FROM picking_order pk
																	LEFT JOIN picking_order_plan pld ON pk.picking_order_id = pld.picking_order_id
																	LEFT JOIN sku ON sku.sku_id = pld.sku_id
																	WHERE pk.picking_order_id = '" . $dataPost['pengirimanBarangId'] . "'
																		AND pld.delivery_order_id IS NULL
																		AND sku.sku_kode = '" . $dataPost['kode'] . "'
																					")->row();
			}

			if ($dataPost['type'] == 'standar') {
				$result .= $this->db->select("sku.sku_id")
					->from('picking_order po')
					->join('picking_list pk', 'pk.picking_list_id = po.picking_list_id', 'left')
					->join('picking_list_detail pkd', 'pkd.picking_list_id = pk.picking_list_id', 'left')
					->join('delivery_order do', 'pkd.delivery_order_id = do.delivery_order_id', 'left')
					->join('delivery_order_detail dod', 'do.delivery_order_id = dod.delivery_order_id', 'left')
					->join('sku', 'dod.sku_id = sku.sku_id', 'left')
					->where('po.picking_order_id', $dataPost['pengirimanBarangId'])
					->where('do.tipe_delivery_order_id', '0E626A53-82FC-4EA6-A4A2-1265279D6E1C')
					->where('sku.sku_kode', $dataPost['kode'])
					->get()->row();
			}
		}

		if ($dataPost['mode'] == 'update') {
			if (in_array($dataPost['type'], ['bulk', 'reschedule', 'canvas'])) {
				$result .= $this->db->query("SELECT sku.sku_id
																		FROM serah_terima_kirim_d1 std1
																		LEFT JOIN sku ON sku.sku_id = std1.sku_id
																		LEFT JOIN principle ON principle.principle_id = sku.principle_id
																		WHERE std1.serah_terima_kirim_id = '" . $dataPost['pengirimanBarangId'] . "' 
																			AND sku.sku_kode = '" . $dataPost['kode'] . "'")->row();
			}

			if ($dataPost['type'] == 'standar') {
				$result .= $this->db->query("SELECT sku.sku_id
																		FROM serah_terima_kirim_d2 std2
																		LEFT JOIN delivery_order do ON std2.delivery_order_id = do.delivery_order_id
																		LEFT JOIN delivery_order_detail dod ON do.delivery_order_id = dod.delivery_order_id
																		LEFT JOIN sku ON dod.sku_id = sku.sku_id
																		LEFT JOIN principle ON principle.principle_id = sku.principle_id
																		WHERE std2.serah_terima_kirim_id = '" . $dataPost['pengirimanBarangId'] . "'
																			AND sku.sku_kode = '" . $dataPost['kode'] . "'")->row();
			}
		}

		if ($result == null) {
			echo json_encode([
				'type' => 201,
				'message' => "Kode Sku <strong>" . $dataPost['kode'] . "</strong> tidak ditemukan",
				'kode' => $dataPost['kode']
			]);
		} else {
			echo json_encode([
				'type' => 200,
				'message' => "Kode Sku <strong>" . $dataPost['kode'] . "</strong> valid",
				'kode' => $dataPost['kode'], 'data' => $result
			]);
		}
	}

	public function getKodeAutoComplete()
	{
		$valueParams = $this->input->get('params');
		$result = $this->M_PengirimanBarang->getKodeAutoComplete($valueParams);
		echo json_encode($result);
	}
}