<?php

require_once APPPATH . 'core/ParentController.php';

class LaporanStock extends ParentController
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

		$this->MenuKode = "101001001";

		$this->load->model('M_Menu');
		$this->load->model('WMS/M_LaporanStock');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
	}

	public function LaporanStockMenu()
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

		$data['Gudang'] = $this->M_LaporanStock->Get_Gudang();
		$data['Principle'] = $this->M_LaporanStock->Get_Principle();
		$data['ClientWMS'] = $this->M_LaporanStock->Get_ClientWMS();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('reports/LaporanStock/main', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('reports/LaporanStock/script_js', $data);
	}

	public function GetLaporanStockRekapData()
	{
		$draw = 50;

		$tgl = explode(" - ", $this->input->post('filter_stock_tanggal'));

		$tgl1 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[1])));

		$tipe_report = "rekap";
		$client_wms = $this->input->post('client_wms');
		$depo_id = $this->session->userdata('depo_id');
		$depo_detail_id = $this->input->post('depo_detail_id');
		$principle_id = $this->input->post('principle_id');
		$sku_kode = $this->input->post('sku_kode');
		$sku_nama = $this->input->post('sku_nama');

		// $tipe_report = "rekap";
		// $tgl1 = date('m/d/Y', strtotime("2022-07-21"));
		// $tgl2 = date('m/d/Y', strtotime("2022-07-22"));
		// $client_wms = "";
		// $depo_id = $this->session->userdata('depo_id');
		// $depo_detail_id = "";
		// $principle_id = "";
		// $sku_kode = "";
		// $sku_nama = "";

		$data_laporan_stock = $this->M_LaporanStock->exec_report_stock($tipe_report, $tgl1, $tgl2, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama);

		$data = array(
			"draw" => intval($draw),
			// "iTotalRecords" => 100000000,
			// "iTotalDisplayRecords" => 100000000,
			"aaData" => $data_laporan_stock
		);

		// echo var_dump($data['LaporanStock']);

		// echo var_dump($data['LaporanStock']['aaData'][0]['depo_id']);

		echo json_encode($data);
	}

	public function GetLaporanStockDetailData()
	{
		$draw = 50;

		$tgl = explode(" - ", $this->input->post('filter_stock_tanggal'));

		$tgl1 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[1])));
		$tipe_report = "detail";
		$client_wms = $this->input->post('client_wms');
		$depo_id = $this->session->userdata('depo_id');
		$depo_detail_id = $this->input->post('depo_detail_id');
		$principle_id = $this->input->post('principle_id');
		$sku_kode = $this->input->post('sku_kode');
		$sku_nama = $this->input->post('sku_nama');

		$data_laporan_stock = $this->M_LaporanStock->exec_report_stock($tipe_report, $tgl1, $tgl2, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama);

		$data = array(
			"draw" => intval($draw),
			// "iTotalRecords" => 100,
			// "iTotalDisplayRecords" => 10,
			"aaData" => $data_laporan_stock
		);

		echo json_encode($data);
	}
}
