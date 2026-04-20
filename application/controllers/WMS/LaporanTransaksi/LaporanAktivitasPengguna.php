<?php

// require_once APPPATH . 'core/ParentController.php';
require_once APPPATH . 'core/MenuController.php';
// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanAktivitasPengguna extends MenuController
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

		// $this->MenuKode = "101002001";
		$this->load->model('WMS/LaporanTransaksi/M_LaporanAktivitasPengguna', 'M_LaporanAktivitasPengguna');
		$this->load->model('M_Menu');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
	}

	public function LaporanAktivitasPenggunaMenu()
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

		$data['Gudang'] = $this->M_LaporanAktivitasPengguna->Get_Gudang();
		$data['Principle'] = $this->M_LaporanAktivitasPengguna->Get_Principle();
		$data['ClientWMS'] = $this->M_LaporanAktivitasPengguna->Get_ClientWMS();
		$data['Divisi'] = $this->M_LaporanAktivitasPengguna->Get_karyawan_divisi();

		// Konversi ke format JavaScript Date (YYYY-MM-DD)
		$lastTbgDepo = getLastTbgDepo();
		$lastTbgDepo = $lastTbgDepo == "" ? date('Y-m-d') : $lastTbgDepo;
		$data['lastTbgDepo'] = date('Y-m-d', strtotime($lastTbgDepo . ' -1 day'));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('reports/LaporanTransaksi/LaporanAktivitasPengguna/main', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('reports/LaporanTransaksi/LaporanAktivitasPengguna/script', $data);
		// $this->load->view('reports/LaporanTransaksi/LaporanInformasiPallet/script', $data);
	}

	public function Get_report_aktivitas_karyawan()
	{

		$data = $this->M_LaporanAktivitasPengguna->Get_report_aktivitas_karyawan();

		echo json_encode($data);
	}

	public function Get_report_aktivitas_karyawan_detail()
	{

		$data = $this->M_LaporanAktivitasPengguna->Get_report_aktivitas_karyawan_detail();

		echo json_encode($data);
	}

	public function ExportExcelDetail()
	{
		// 1. Ambil Parameter dari URL (GET)
		$karyawan_id    = $this->input->get('karyawan_id');
		$tipe           = $this->input->get('tipe');
		$filter_tanggal = $this->input->get('filter_tanggal');
		$search         = $this->input->get('search');

		// 2. Ambil Data dari Model (Tanpa Limit/Offset untuk Export)
		$jml_list_data = $this->M_LaporanAktivitasPengguna->get_jml_detail_datatable($karyawan_id, $tipe, $filter_tanggal);

		if ($jml_list_data > 10000) {
			// Kirim header HTTP Error agar AJAX masuk ke fungsi 'error'
			$this->output->set_status_header(500);
			echo json_encode(array(
				"status" => 500,
				"result" => "error",
				"message" => "Kapasitas data melebihi memory limit"
			));
			return;
		}

		$list_data = $this->M_LaporanAktivitasPengguna->get_detail_datatable($karyawan_id, $tipe, $filter_tanggal);

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Detail Aktivitas ' . $tipe);

		// 3. Definisikan Header berdasarkan Tipe
		$headers = ['No']; // Kolom pertama selalu No

		switch ($tipe) {
			case 'FDJR':
				$headers = array_merge($headers, ['No FDJR', 'Tgl Kirim', 'No DO', 'SKU Kode', 'Nama Produk', 'Qty Pcs', 'Qty Ctn', 'Qty Kirim Pcs', 'Qty Kirim Ctn', 'Sisa Pcs', 'Sisa Ctn', 'Persentase']);
				break;
			case 'BKB':
			case 'KOREKSI STOK':
				$headers = array_merge($headers, ['No Dokumen', 'Tanggal', 'SKU Kode', 'Nama Produk', 'Expired Date', 'Qty Plan Pcs', 'Qty Plan Ctn', 'Qty Ambil Pcs', 'Qty Ambil Ctn']);
				break;
			case 'PB':
			case 'BTB RETUR':
				$headers = array_merge($headers, ['No Dokumen', 'Tanggal', 'SKU Kode', 'Nama Produk', 'Expired Date', 'Qty Pcs', 'Qty Ctn', 'Qty Terima Pcs', 'Qty Terima Ctn']);
				break;
			case 'STOK OPNAM':
				$headers = array_merge($headers, ['No Dokumen', 'Tanggal', 'SKU Kode', 'Nama Produk', 'Expired Date', 'Qty Aktual Pcs', 'Qty Aktual Ctn', 'Qty Sistem Pcs', 'Qty Sistem Ctn']);
				break;
			case 'MUTASI STOK':
				$headers = array_merge($headers, ['No Dokumen', 'Tanggal', 'SKU Kode', 'Nama Produk', 'Expired Date', 'Qty Pcs', 'Qty Ctn']);
				break;
			case 'MUTASI PALLET':
				$headers = array_merge($headers, ['No Dokumen', 'Tanggal', 'Pallet Kode', 'SKU Kode', 'Nama Produk', 'Expired Date', 'Qty Pcs', 'Qty Ctn']);
				break;
		}

		// Tulis Header ke Excel & Styling Bold
		$col = 'A';
		foreach ($headers as $h) {
			$sheet->setCellValue($col . '1', $h);
			$sheet->getStyle($col . '1')->getFont()->setBold(true);
			$col++;
		}

		// 4. Isi Data ke Baris
		$rowNum = 2;
		foreach ($list_data as $index => $row) {
			$sheet->setCellValue('A' . $rowNum, $index + 1);

			if ($tipe == 'FDJR') {
				$sisaPcs = ($row->QtyPcs ?? 0) - ($row->QtyKirimPcs ?? 0);
				$sisaCtn = ($row->QtyCtn ?? 0) - ($row->QtyKirimCtn ?? 0);
				$persen  = ($row->QtyPcs > 0) ? ($row->QtyKirimPcs / $row->QtyPcs) : 0;

				$sheet->setCellValue('B' . $rowNum, $row->delivery_order_batch_kode);
				$sheet->setCellValue('C' . $rowNum, $row->delivery_order_batch_tanggal_kirim);
				$sheet->setCellValue('D' . $rowNum, $row->delivery_order_kode);
				$sheet->setCellValue('E' . $rowNum, $row->sku_kode);
				$sheet->setCellValue('F' . $rowNum, $row->sku_nama_produk);
				$sheet->setCellValue('G' . $rowNum, $row->QtyPcs);
				$sheet->setCellValue('H' . $rowNum, $row->QtyCtn);
				$sheet->setCellValue('I' . $rowNum, $row->QtyKirimPcs);
				$sheet->setCellValue('J' . $rowNum, $row->QtyKirimCtn);
				$sheet->setCellValue('K' . $rowNum, $sisaPcs);
				$sheet->setCellValue('L' . $rowNum, $sisaCtn);
				$sheet->setCellValue('M' . $rowNum, $persen);
				$sheet->getStyle('M' . $rowNum)->getNumberFormat()->setFormatCode('0.00%');
			} else if (in_array($tipe, ['BKB', 'KOREKSI STOK'])) {
				$sheet->setCellValue('B' . $rowNum, $row->NoDokumen);
				$sheet->setCellValue('C' . $rowNum, $row->Tanggal);
				$sheet->setCellValue('D' . $rowNum, $row->sku_kode);
				$sheet->setCellValue('E' . $rowNum, $row->sku_nama_produk);
				$sheet->setCellValue('F' . $rowNum, $row->sku_stock_expired_date);
				$sheet->setCellValue('G' . $rowNum, $row->QtyPlanPcs);
				$sheet->setCellValue('H' . $rowNum, $row->QtyPlanCtn);
				$sheet->setCellValue('I' . $rowNum, $tipe == 'BKB' ? $row->QtyAmbilPcs : $row->QtyAktualPcs);
				$sheet->setCellValue('J' . $rowNum, $tipe == 'BKB' ? $row->QtyAmbilCtn : $row->QtyAktualCtn);
			} else if (in_array($tipe, ['PB', 'BTB RETUR'])) {
				$sheet->setCellValue('B' . $rowNum, $row->NoDokumen);
				$sheet->setCellValue('C' . $rowNum, $row->Tanggal);
				$sheet->setCellValue('D' . $rowNum, $row->sku_kode);
				$sheet->setCellValue('E' . $rowNum, $row->sku_nama_produk);
				$sheet->setCellValue('F' . $rowNum, ($tipe == 'PB' ? $row->sku_exp_date : $row->sku_expired_date));
				$sheet->setCellValue('G' . $rowNum, $row->QtyPcs);
				$sheet->setCellValue('H' . $rowNum, $row->QtyCtn);
				$sheet->setCellValue('I' . $rowNum, $row->QtyTerimaPcs);
				$sheet->setCellValue('J' . $rowNum, $row->QtyTerimaCtn);
			} else if ($tipe == 'STOK OPNAM') {
				$sheet->setCellValue('B' . $rowNum, $row->NoDokumen);
				$sheet->setCellValue('C' . $rowNum, $row->Tanggal);
				$sheet->setCellValue('D' . $rowNum, $row->sku_kode);
				$sheet->setCellValue('E' . $rowNum, $row->sku_nama_produk);
				$sheet->setCellValue('F' . $rowNum, $row->sku_expired_date);
				$sheet->setCellValue('G' . $rowNum, $row->QtyAktualPcs);
				$sheet->setCellValue('H' . $rowNum, $row->QtyAktualCtn);
				$sheet->setCellValue('I' . $rowNum, $row->QtySistemPcs);
				$sheet->setCellValue('J' . $rowNum, $row->QtySistemCtn);
			} else if ($tipe == 'MUTASI STOK') {
				$sheet->setCellValue('B' . $rowNum, $row->NoDokumen);
				$sheet->setCellValue('C' . $rowNum, $row->Tanggal);
				$sheet->setCellValue('D' . $rowNum, $row->sku_kode);
				$sheet->setCellValue('E' . $rowNum, $row->sku_nama_produk);
				$sheet->setCellValue('F' . $rowNum, $row->sku_stock_expired_date);
				$sheet->setCellValue('G' . $rowNum, $row->QtyPcs);
				$sheet->setCellValue('H' . $rowNum, $row->QtyCtn);
			} else if ($tipe == 'MUTASI PALLET') {
				$sheet->setCellValue('B' . $rowNum, $row->NoDokumen);
				$sheet->setCellValue('C' . $rowNum, $row->Tanggal);
				$sheet->setCellValue('D' . $rowNum, $row->pallet_kode);
				$sheet->setCellValue('E' . $rowNum, $row->sku_kode);
				$sheet->setCellValue('F' . $rowNum, $row->sku_nama_produk);
				$sheet->setCellValue('G' . $rowNum, $row->sku_stock_expired_date);
				$sheet->setCellValue('H' . $rowNum, $row->QtyPcs);
				$sheet->setCellValue('I' . $rowNum, $row->QtyCtn);
			}

			$rowNum++;
		}

		// Auto size kolom
		foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}

		// 5. Download File
		$filename = "Laporan_Detail_" . str_replace(' ', '_', $tipe) . "_" . date('Ymd_His') . ".xlsx";
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}
}
