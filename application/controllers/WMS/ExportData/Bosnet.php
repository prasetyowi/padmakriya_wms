<?php

// require_once APPPATH . 'core/ParentController.php';

// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once APPPATH . 'core/MenuController.php';
class Bosnet extends MenuController
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
	// private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		// echo "<pre>".print_r($_SESSION, 1)."</pre>";
		// die();

		if ($this->session->has_userdata('pengguna_id') == 0) :
			redirect(base_url('MainPage'));
		endif;

		// $this->MenuKode = "133001100";
		$this->load->model('WMS/ExportData/M_Bosnet', 'M_Bosnet');
		$this->load->model('M_Menu');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_DataTable');
	}

	public function ExportFDJRMenu()
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
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Menu_Name($this->MenuKode);
		$query['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$data['Gudang'] = $this->M_Bosnet->Get_Gudang();
		$data['Pengemudi'] = $this->M_Bosnet->Get_pengemudi();
		$data['ListBulan'] = array("01" => "Jan", "02" => "Feb", "03" => "Mar", "04" => "Apr", "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Aug", "09" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec");

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/ExportData/Bosnet/ExportFDJR/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/ExportData/Bosnet/ExportFDJR/script', $data);
		// $this->load->view('reports/LaporanTransaksi/LaporanInformasiPallet/script', $data);
	}

	public function ExportPenerimaanSuratJalanMenu()
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
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Menu_Name($this->MenuKode);
		$query['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$data['Principle'] = $this->M_Bosnet->Get_Principle();
		$data['ListBulan'] = array("01" => "Jan", "02" => "Feb", "03" => "Mar", "04" => "Apr", "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Aug", "09" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec");

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/ExportData/Bosnet/ExportPenerimaanSuratJalan/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/ExportData/Bosnet/ExportPenerimaanSuratJalan/script', $data);
		// $this->load->view('reports/LaporanTransaksi/LaporanInformasiPallet/script', $data);
	}

	public function ExportDeliveryOrderMenu()
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
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Menu_Name($this->MenuKode);
		$query['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		// $data['Principle'] = $this->M_Bosnet->Get_Principle();
		$data['ListBulan'] = array("01" => "Jan", "02" => "Feb", "03" => "Mar", "04" => "Apr", "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Aug", "09" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec");
		$data['Principle'] = $this->M_Bosnet->Get_PrincipleExistSku();
		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/ExportData/Bosnet/ExportDeliveryOrder/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/ExportData/Bosnet/ExportDeliveryOrder/script', $data);
		// $this->load->view('reports/LaporanTransaksi/LaporanInformasiPallet/script', $data);
	}

	public function ExportBTBMenu()
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
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Menu_Name($this->MenuKode);
		$query['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		// $data['Principle'] = $this->M_Bosnet->Get_Principle();
		$data['ListBulan'] = array("01" => "Jan", "02" => "Feb", "03" => "Mar", "04" => "Apr", "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Aug", "09" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec");
		$data['Principle'] = $this->M_Bosnet->Get_PrincipleExistSku();
		$data['Driver'] = $this->M_Bosnet->Get_driver();

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/ExportData/Bosnet/ExportBTB/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/ExportData/Bosnet/ExportBTB/script', $data);
		// $this->load->view('reports/LaporanTransaksi/LaporanInformasiPallet/script', $data);
	}

	public function Get_bosnet_do_by_filter()
	{
		$tgl = explode(" - ", $this->input->post('tanggal'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$karyawan_eksternal_id = $this->input->post('karyawan_eksternal_id');
		$rit = $this->input->post('rit');
		$gudang = $this->input->post('gudang');

		$data = $this->M_Bosnet->Get_bosnet_do_by_filter($tgl1, $tgl2, $karyawan_eksternal_id, $rit, $gudang);

		echo json_encode($data);
	}

	public function Get_bosnet_do_by_id()
	{
		$tgl = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tanggal'))));

		$karyawan_eksternal_id = $this->input->post('karyawan_eksternal_id');
		$rit = $this->input->post('rit');
		$gudang = $this->input->post('gudang');
		$kendaraan_nopol = $this->input->post('kendaraan_nopol');

		$data = $this->M_Bosnet->Get_bosnet_do_by_id($tgl, $tgl, $karyawan_eksternal_id, $rit, $gudang, $kendaraan_nopol);

		echo json_encode($data);
	}

	public function Get_bosnet_penerimaan_surat_jalan_by_filter()
	{
		$tgl = explode(" - ", $this->input->post('tanggal'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$principle_id = $this->input->post('principle_id');
		$principle_kode = $this->input->post('principle_kode');

		$data = $this->M_Bosnet->Get_bosnet_penerimaan_surat_jalan_by_filter($tgl1, $tgl2, $principle_id, $principle_kode);

		echo json_encode($data);
	}

	public function Get_bosnet_delivery_order_by_filter()
	{
		$tgl = explode(" - ", $this->input->post('tanggal'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$principleArr = $this->input->post('principle') == '' ? array() : $this->input->post('principle');
		$principle =  implode(',', $principleArr);

		// $principle_id = $this->input->post('principle_id');
		// $principle_kode = $this->input->post('principle_kode');

		$data = $this->M_Bosnet->Get_bosnet_delivery_order_by_filter($tgl1, $tgl2, $principle);

		echo json_encode($data);
	}

	public function Get_bosnet_penerimaan_barang_penjualan_by_filter()
	{
		$principleArrVal = array();
		$driverArrVal = array();

		$tgl = explode(" - ", $this->input->post('tanggal'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$principleArr = $this->input->post('principle') == '' ? array() : $this->input->post('principle');
		$driverArr = $this->input->post('driver') == '' ? array() : $this->input->post('driver');

		foreach ($principleArr as $key => $value) {
			$principleArrVal[] = "'" . $value . "'";
		}

		foreach ($driverArr as $key => $value) {
			$driverArrVal[] = "'" . $value . "'";
		}

		$filter_principle =  implode(',', $principleArrVal);
		$filter_driver =  implode(',', $driverArrVal);
		$sistem_eksternal = "BOSNET";

		if ($filter_principle == "") {
			$filter_principle = "";
		} else {
			$filter_principle = "and p.principle_id in (" . $filter_principle . ")";
		}

		if ($filter_driver == "") {
			$filter_driver = "";
		} else {
			$filter_driver = "and fdjr.karyawan_id in (" . $filter_driver . ")";
		}


		$sql = "select 
					so.sales_order_id,
					so.sales_order_kode,
					so.sales_order_no_po,
					do.delivery_order_id,
					do.delivery_order_kode,
					sku.sku_konversi_group,
					sku.sku_nama_produk,
					btbh.depo_id,
					btbh.depo_detail_id,
					isnull(dde.kode_gudang, '') as kode_gudang,
					isnull(dde.tipe_persediaan, '') as tipe_persediaan,
					btbh.penerimaan_tipe_id,
					btbd.kondisi_barang,
					SUM(btbd.sku_jumlah_terima * sku.sku_konversi_faktor) as sku_jumlah_terima
				from penerimaan_penjualan_detail btbd
				left join penerimaan_penjualan btbh 
				on btbh.penerimaan_penjualan_id = btbd.penerimaan_penjualan_id
				left join delivery_order do
				on do.delivery_order_id = btbd.delivery_order_id
				left join delivery_order_batch fdjr
				on fdjr.delivery_order_batch_id = do.delivery_order_batch_id
				left join sales_order so
				on so.sales_order_id = do.sales_order_id
				left join sku
				on sku.sku_id = btbd.sku_id
				left join principle p
				on p.principle_id = sku.principle_id
				left join depo_detail_eksternal dde
				on dde.depo_detail_id = btbh.depo_detail_id
				and dde.sistem_eksternal = '$sistem_eksternal'
				where format(fdjr.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') between '$tgl1' and '$tgl2'
				and btbh.depo_id = '" . $this->session->userdata('depo_id') . "'
				" . $filter_principle . "
				" . $filter_driver . "
				group by so.sales_order_id,
					so.sales_order_kode,
					so.sales_order_no_po,
					do.delivery_order_id,
					do.delivery_order_kode,
					sku.sku_konversi_group,
					sku.sku_nama_produk,
					btbh.depo_id,
					btbh.depo_detail_id,
					btbh.penerimaan_tipe_id,
					isnull(dde.kode_gudang, ''),
					isnull(dde.tipe_persediaan, ''),
					btbd.kondisi_barang";

		$response = $this->M_DataTable->dtTableGetList($sql);

		$output = array(
			"draw" => $response['draw'],
			"recordsTotal" => $response['recordsTotal'],
			"recordsFiltered" => $response['recordsFiltered'],
			"data" => $response['data'],
		);

		echo json_encode($output);
	}

	public function Get_bosnet_penerimaan_surat_jalan_by_id()
	{

		$penerimaan_surat_jalan_id = $this->input->post('penerimaan_surat_jalan_id');
		$principle_id = $this->input->post('principle_id');
		$principle_kode = $this->input->post('principle_kode');
		$tgl = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tgl'))));

		$data = $this->M_Bosnet->Get_bosnet_penerimaan_surat_jalan_by_id($tgl, $principle_id, $principle_kode, $penerimaan_surat_jalan_id);

		echo json_encode($data);
	}

	public function ExportExcelDetailPenerimaanSJ()
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// Ambil data dari POST dan decode dari JSON
		$data = json_decode($this->input->post('data'), true);

		// Menambahkan Header
		$sheet->setCellValue('A1', 'ID Principal');
		$sheet->setCellValue('B1', 'Nama Principal');
		$sheet->setCellValue('C1', 'ID Produk');
		$sheet->setCellValue('D1', 'Nama Produk');
		$sheet->setCellValue('E1', 'Tanggal CO');
		$sheet->setCellValue('F1', 'ID CO');
		$sheet->setCellValue('G1', 'Total');
		$sheet->setCellValue('H1', 'Harga Satuan');
		$sheet->setCellValue('I1', 'Tujuan');

		// Isi body dari data
		$body = $data['body'];
		$rowNumber = 2; // Dimulai dari baris ke-2 setelah header

		foreach ($body as $row) {
			$col = 'A';
			foreach ($row as $cell) {
				$sheet->setCellValue($col . $rowNumber, $cell);
				$col++;
			}
			$rowNumber++;
		}

		// Simpan sebagai file XLS
		$writer = new Xls($spreadsheet);
		$filename = 'Warehouse Management System.xls';

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output'); // Menyimpan output ke browser
		exit();
	}

	public function ExportExcelDetailDeliveryOrder()
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$tgl = explode(" - ", $this->input->post('tanggal'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$data = $this->M_Bosnet->Get_bosnet_delivery_order_by_filter($tgl1, $tgl2);

		// Menambahkan Header
		$sheet->setCellValue('A1', 'BRAND');
		$sheet->setCellValue('B1', 'SO PADMA');
		$sheet->setCellValue('C1', 'NAMA CUST');
		$sheet->setCellValue('D1', 'ALAMAT');
		$sheet->setCellValue('E1', 'DRIVER');
		$sheet->setCellValue('F1', 'KEC');
		$sheet->setCellValue('G1', 'DELIVERY GROUP');
		$sheet->setCellValue('H1', 'SALES');
		$sheet->setCellValue('I1', 'SEGMENT');
		$sheet->setCellValue('J1', 'Sum of SO CTN');
		$sheet->setCellValue('K1', 'KETERANGAN');

		// Isi body dari data
		$numrow = 2;
		foreach ($data as $value) {
			$sheet->setCellValue('A' . $numrow, $value['principle_kode']);
			$sheet->setCellValue('B' . $numrow, $value['sales_order_no_po']);
			$sheet->setCellValue('C' . $numrow, $value['client_pt_nama']);
			$sheet->setCellValue('D' . $numrow, $value['client_pt_alamat']);
			$sheet->setCellValue('E' . $numrow, null);
			$sheet->setCellValue('F' . $numrow, $value['client_pt_kecamatan']);
			$sheet->setCellValue('G' . $numrow, $value['area_kode']);
			$sheet->setCellValue('H' . $numrow, null);
			$sheet->setCellValue('I' . $numrow, null);
			$sheet->setCellValue('J' . $numrow, $value['avg_ctn']);
			$sheet->setCellValue('K' . $numrow, $value['sales_order_keterangan']);

			$numrow++; // Tambah 1 setiap kali looping
		}


		// Simpan sebagai file XLS
		$fileName = 'Delivery Order.xlsx';
		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $fileName . '"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output'); // Menyimpan output ke browser
		exit();
	}

	public function ExportExcelDetailPenerimaanPenjualan()
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$tgl = explode(" - ", $this->input->post('tanggal'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$principleArr = $this->input->post('principle') == '' ? array() : $this->input->post('principle');
		$driverArr = $this->input->post('driver') == '' ? array() : $this->input->post('driver');

		$principle = is_array($principleArr) ? implode(',', $principleArr) : ($principleArr ?? "");
		$driver = is_array($driverArr) ? implode(',', $driverArr) : ($driverArr ?? "");

		// $principleArr = $this->input->post('principle') == '' ? array() : $this->input->post('principle');
		// $principle =  implode(',', $principleArr);

		$data = $this->M_Bosnet->Get_bosnet_penerimaan_barang_penjualan_by_filter($tgl1, $tgl2, $principle, $driver);

		// echo json_encode($principle);
		// die;

		// Menambahkan Header
		$sheet->setCellValue('A1', 'FSO');
		$sheet->setCellValue('B1', 'FDO');
		$sheet->setCellValue('C1', 'PRODUK ID');
		$sheet->setCellValue('D1', 'QTY FSID');
		$sheet->setCellValue('E1', 'GUDANG');
		$sheet->setCellValue('F1', 'TIPE PERSEDIAAN');

		// Isi body dari data
		$numrow = 2;
		foreach ($data as $value) {
			$sheet->setCellValue('A' . $numrow, $value['sales_order_no_po']);
			$sheet->setCellValue('B' . $numrow, $value['delivery_order_kode']);
			$sheet->setCellValue('C' . $numrow, $value['sku_konversi_group']);
			$sheet->setCellValue('D' . $numrow, $value['sku_jumlah_terima']);
			$sheet->setCellValue('E' . $numrow, $value['kode_gudang']);
			$sheet->setCellValue('F' . $numrow, $value['tipe_persediaan']);

			$numrow++; // Tambah 1 setiap kali looping
		}


		// Simpan sebagai file XLS
		$fileName = 'Export BTB WMS.xlsx';
		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $fileName . '"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output'); // Menyimpan output ke browser
		exit();
	}
}
