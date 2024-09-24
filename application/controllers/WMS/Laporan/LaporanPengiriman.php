<?php

require_once APPPATH . 'core/ParentController.php';

// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanPengiriman extends ParentController
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
			redirect(base_url('MainPage'));
		endif;

		$this->MenuKode = "101001000";

		$this->load->model('M_Menu');
		$this->load->model('WMS/M_LaporanPengiriman', 'M_LaporanPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
	}

    public function LaporanPengirimanMenu()
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
			redirect(base_url('MainPage/DepoMenu'));
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

			Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/components/button.min.css',
			Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/components/card.min.css',
			Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/components/image.min.css',

			//Get_Assets_Url() . 'assets/css/bootstrap-switch.min.css',
			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css',
			Get_Assets_Url() . 'assets/css/buttondesign.css',

			Get_Assets_Url() . 'assets/css/clock.css'
			//Get_Assets_Url() . 'assets/css/bootstrap-input-spinner.css'
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

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('reports/LaporanPengiriman/index', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('reports/LaporanPengiriman/script', $data);
    }

    public function GetDataFilter()
    {
        $tgl = $this->input->post('tgl');

		$data = $this->M_LaporanPengiriman->GetDataDOByFilter($tgl);

        echo json_encode($data);
    }

    public function DownloadExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

		$tgl = $this->input->get('tgl');
		$status = $this->input->get('status');
		
		if ($status != 'partially delivered') {
			$data = $this->M_LaporanPengiriman->GetDataDOByStatusTgl($tgl, $status);

			$sheet->setCellValue('A1', 'TGL DELIVERY');
			$sheet->setCellValue('B1', 'PENGEMUDI');
			$sheet->setCellValue('C1', 'NOMOR SO FAS');
			$sheet->setCellValue('D1', 'NOMOR SO');
			$sheet->setCellValue('E1', 'NOMOR DO');
			$sheet->setCellValue('F1', 'STATUS (ASC)');
			$sheet->setCellValue('G1', 'PEMBAYARAN');
			$sheet->setCellValue('H1', 'NOMINAL');
	
			$fileName = 'Laporan Pengiriman.xlsx';
			$numrow = 2;

			foreach ($data as $value) {
				$sheet->setCellValue('A' . $numrow, $value['tgl']);
				$sheet->setCellValue('B' . $numrow, $value['pengemudi']);
				$sheet->setCellValue('C' . $numrow, $value['so_fas']);
				$sheet->setCellValue('D' . $numrow, $value['so']);
				$sheet->setCellValue('E' . $numrow, $value['do']);
				$sheet->setCellValue('F' . $numrow, $value['status_do']);
				$sheet->setCellValue('G' . $numrow, $value['pembayaran']);
				$sheet->setCellValue('H' . $numrow, round($value['jumlah_bayar']));

				$numrow++;
			}
	
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename=' . $fileName . ''); // Set nama file excel nya
			header('Cache-Control: max-age=0');
			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
		} else {
			$data2 = $this->M_LaporanPengiriman->GetDataDOByStatusTgl2($tgl, $status);

			$sheet->setCellValue('A1', 'TGL DELIVERY');
			$sheet->setCellValue('B1', 'PENGEMUDI');
			$sheet->setCellValue('C1', 'NOMOR SO FAS');
			$sheet->setCellValue('D1', 'NOMOR SO');
			$sheet->setCellValue('E1', 'NOMOR DO (BILA ADA)');
			$sheet->setCellValue('F1', 'SKU');
			$sheet->setCellValue('G1', 'QTY AWAL');
			$sheet->setCellValue('H1', 'QTY TERKIRIM');
			$sheet->setCellValue('I1', 'SELISIH');
	
			$fileName = 'Laporan Pengiriman.xlsx';
			$numrow = 2;

			foreach ($data2 as $value) {
				$sheet->setCellValue('A' . $numrow, $value['tgl']);
				$sheet->setCellValue('B' . $numrow, $value['pengemudi']);
				$sheet->setCellValue('C' . $numrow, $value['so_fas']);
				$sheet->setCellValue('D' . $numrow, $value['so']);
				$sheet->setCellValue('E' . $numrow, $value['do']);
				$sheet->setCellValue('F' . $numrow, $value['sku']);
				$sheet->setCellValue('G' . $numrow, $value['sku_qty']);
				$sheet->setCellValue('H' . $numrow, $value['sku_qty_kirim']);
				$sheet->setCellValue('I' . $numrow, $value['selisih']);

				$numrow++;
			}
	
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename=' . $fileName . ''); // Set nama file excel nya
			header('Cache-Control: max-age=0');
			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
		}

       
    }
}
