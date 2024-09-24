<?php

require_once APPPATH . 'core/ParentController.php';

// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;

class LaporanPenjualan extends ParentController
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

		// echo "<pre>".print_r($_SESSION, 1)."</pre>";
		// die();

		if ($this->session->has_userdata('pengguna_id') == 0) :
			redirect(base_url('MainPage'));
		endif;

		$this->MenuKode = "101002002";
		$this->load->model('WMS/LaporanTransaksi/M_LaporanPenjualan', 'M_LaporanPenjualan');
		$this->load->model('M_Menu');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
	}

	public function LaporanPenjualanMenu()
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

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$data['Gudang'] = $this->M_LaporanPenjualan->Get_Gudang();
		$data['Principle'] = $this->M_LaporanPenjualan->Get_Principle();
		$data['ClientWMS'] = $this->M_LaporanPenjualan->Get_ClientWMS();
		$data['Driver'] = $this->M_LaporanPenjualan->Get_Driver();

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('reports/LaporanTransaksi/LaporanPenjualan/main', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('reports/LaporanTransaksi/LaporanPenjualan/script', $data);
		// $this->load->view('reports/LaporanTransaksi/LaporanInformasiPallet/script', $data);
	}

	public function getDetail()
	{
		$tgl = explode(" - ", $this->input->post('filter_stock_tanggal'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$tgl_1 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl_2 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[1])));
		$client_wms = $this->input->post('client_wms');
		$depo_id = $this->session->userdata('depo_id');
		$depo_detail_id = $this->input->post('depo_detail_id');
		$principle_id = $this->input->post('principle');
		$sku_kode = $this->input->post('sku_kode');
		$sku_nama = $this->input->post('sku_nama');
		$driver = $this->input->post('driver');

		$total_data = $this->M_LaporanPenjualan->count_all_data($tgl1, $tgl2, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $driver);
		$draw = intval($this->input->post('draw'));
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$search_value = $this->input->post('search')['value'];
		$order_column = intval($this->input->post('order')[0]['column']);
		$order_dir = $this->input->post('order')[0]['dir'];

		$records_filtered = $total_data;
		// $data = $this->M_LaporanPenjualan->GetDetailNew($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama);
		$data = $this->M_LaporanPenjualan->GetDetailNewProcedure($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $tgl_1, $tgl_2, $driver);

		$response = array(
			'draw' => $draw,
			'recordsTotal' => $total_data,
			'recordsFiltered' => $records_filtered,
			'data' => $data
		);
		echo json_encode($response);
	}

	public function exportExcel()
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$tgl = explode(" - ", $this->input->get('filter_stock_tanggal'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$tgl_1 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl_2 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[1])));
		$client_wms = $this->input->get('client_wms');
		$depo_id = $this->session->userdata('depo_id');
		$principle_id = $this->input->get('principle');
		$sku_kode = $this->input->get('sku_kode');
		$sku_nama = $this->input->get('sku_nama');
		$driver = $this->input->get('driver');

		$data = $this->M_LaporanPenjualan->GetDetailNewProcedure2($tgl1, $tgl2, $client_wms, $depo_id, $principle_id, $sku_kode, $sku_nama, $tgl_1, $tgl_2, $driver);

		$sheet->setCellValue('A1', 'Depo');
		$sheet->setCellValue('B1', 'Tanggal DO');
		$sheet->setCellValue('C1', 'Tanggal Terkirim DO');
		$sheet->setCellValue('D1', 'No. DO');
		$sheet->setCellValue('E1', 'No SO Eksternal');
		$sheet->setCellValue('F1', 'Nama Outlet');
		$sheet->setCellValue('G1', 'Kode SKU');
		$sheet->setCellValue('H1', 'Nama SKU');
		$sheet->setCellValue('I1', 'QTY Order');
		$sheet->setCellValue('J1', 'Qty Terkirim');
		$sheet->setCellValue('K1', 'Total Harga');
		$sheet->setCellValue('L1', 'Penyelesaian Pengiriman');
		$sheet->setCellValue('M1', 'Pengemudi');
		$sheet->setCellValue('N1', 'Nopol');
		$sheet->setCellValue('O1', 'Tgl. FDJR');
		$sheet->setCellValue('P1', 'Status DO');
		$sheet->setCellValue('Q1', 'Keterangan');

		// Mendefinisikan style bold untuk header
		$boldStyle = [
			'font' => [
				'bold' => true,
			],
		];

		// Mengatur header pada sel spreadsheet dengan teks tebal
		$sheet->getStyle('A1:Q1')->applyFromArray($boldStyle);

		$numrow = 2;
		foreach ($data as $value) {
			$sheet->setCellValue('A' . $numrow, $value['depo_nama']);
			$sheet->setCellValue('B' . $numrow, $value['tgl_do']);
			$sheet->setCellValue('C' . $numrow, $value['tgl_terkirim']);
			$sheet->setCellValue('D' . $numrow, $value['delivery_order_kode']);
			$sheet->setCellValue('E' . $numrow, $value['sales_order_no']);
			$sheet->setCellValue('F' . $numrow, $value['nama_outlet']);
			$sheet->setCellValue('G' . $numrow, $value['sku_kode']);
			$sheet->setCellValue('H' . $numrow, $value['sku_nama_produk']);
			$sheet->setCellValue('I' . $numrow, $value['sku_qty']);
			$sheet->setCellValue('J' . $numrow, $value['sku_qty_kirim']);
			$sheet->setCellValue('K' . $numrow, $value['harga']);
			$sheet->setCellValue('L' . $numrow, $value['delivery_order_batch_kode']);
			$sheet->setCellValue('M' . $numrow, $value['karyawan_nama']);
			$sheet->setCellValue('N' . $numrow, $value['kendaraan_nopol']);
			$sheet->setCellValue('O' . $numrow, $value['delivery_order_batch_tanggal_kirim']);
			$sheet->setCellValue('P' . $numrow, $value['delivery_order_status']);
			$sheet->setCellValue('Q' . $numrow, $value['reason']);

			$numrow++; // Tambah 1 setiap kali looping
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Laporan Transaksi Penjualan.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}
}
