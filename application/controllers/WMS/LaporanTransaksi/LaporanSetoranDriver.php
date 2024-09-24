<?php

require_once APPPATH . 'core/ParentController.php';

// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanSetoranDriver extends ParentController
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

		$this->MenuKode = "101002013";
		$this->load->model('WMS/LaporanTransaksi/M_LaporanSetoranDriver', 'M_LaporanSetoranDriver');
		$this->load->model('M_Menu');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
	}

	public function LaporanSetoranDriverMenu()
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

		$data['Gudang'] = $this->M_LaporanSetoranDriver->Get_Gudang();
		$data['Principle'] = $this->M_LaporanSetoranDriver->Get_Principle();
		$data['ClientWMS'] = $this->M_LaporanSetoranDriver->Get_ClientWMS();
		$data['Driver'] = $this->M_LaporanSetoranDriver->Get_Driver();

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('reports/LaporanTransaksi/LaporanSetoranDriver/main', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('reports/LaporanTransaksi/LaporanSetoranDriver/script', $data);
		// $this->load->view('reports/LaporanTransaksi/LaporanInformasiPallet/script', $data);
	}

	public function GetDetail()
	{
		$tgl = explode(" - ", $this->input->post('tanggal_kirim'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$client_wms = $this->input->post('client_wms');
		$depo_id = $this->session->userdata('depo_id');
		$principle_id = $this->input->post('principle');
		$driver = $this->input->post('driver');

		$data = $this->M_LaporanSetoranDriver->GetDetailLaporanSetoranDriver($tgl1, $tgl2, $client_wms, $depo_id, $principle_id, $driver);

		echo json_encode($data);
	}

	// public function getDetail()
	// {
	// 	$tgl = explode(" - ", $this->input->post('filter_stock_tanggal'));

	// 	$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
	// 	$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

	// 	$tgl_1 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[0])));
	// 	$tgl_2 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[1])));
	// 	$client_wms = $this->input->post('client_wms');
	// 	$depo_id = $this->session->userdata('depo_id');
	// 	$depo_detail_id = $this->input->post('depo_detail_id');
	// 	$principle_id = $this->input->post('principle');
	// 	$sku_kode = $this->input->post('sku_kode');
	// 	$sku_nama = $this->input->post('sku_nama');
	// 	$driver = $this->input->post('driver');

	// 	$total_data = $this->M_LaporanSetoranDriver->count_all_data($tgl1, $tgl2, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $driver);
	// 	$draw = intval($this->input->post('draw'));
	// 	$start = intval($this->input->post('start'));
	// 	$length = intval($this->input->post('length'));
	// 	$search_value = $this->input->post('search')['value'];
	// 	$order_column = intval($this->input->post('order')[0]['column']);
	// 	$order_dir = $this->input->post('order')[0]['dir'];

	// 	$records_filtered = $total_data;
	// 	// $data = $this->M_LaporanSetoranDriver->GetDetailNew($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama);
	// 	$data = $this->M_LaporanSetoranDriver->GetDetailNewProcedure($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $tgl_1, $tgl_2, $driver);

	// 	$response = array(
	// 		'draw' => $draw,
	// 		'recordsTotal' => $total_data,
	// 		'recordsFiltered' => $records_filtered,
	// 		'data' => $data
	// 	);
	// 	echo json_encode($response);
	// }
}
