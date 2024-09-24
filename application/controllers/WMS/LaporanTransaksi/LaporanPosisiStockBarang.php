<?php

require_once APPPATH . 'core/ParentController.php';

class LaporanPosisiStockBarang extends ParentController
{
	private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		if ($this->session->has_userdata('pengguna_id') == 0) :
			redirect(base_url('MainPage'));
		endif;

		$this->MenuKode = "101002012"; //"101001001";

		$this->load->model('M_Menu');
		$this->load->model('WMS/M_LaporanPosisiStockBarang');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
	}

	public function LaporanPosisiStockBarangMenu()
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
		);

		$data['Principle'] = $this->M_LaporanPosisiStockBarang->Get_Principle();
		$data['Gudang'] = $this->M_LaporanPosisiStockBarang->Get_Gudang();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('reports/LaporanPosisiStockBarang/main', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('reports/LaporanPosisiStockBarang/script_js', $data);
	}

	public function GetLaporanPosisiStockBarang()
	{
		$depo_id            = $this->session->userdata('depo_id');
		$principle_id       = $this->input->post('principle_id');
		$depo_detail_id       = $this->input->post('depo_detail_id');

		$draw               = $this->input->post('draw');
		$start              = $this->input->post('start');
		$length             = 10; //$this->input->post('length');


		$search_value       = ''; //$this->input->post('search')['value'];
		$order_column       = 0; //$this->input->post('order')[0]['column'];
		$order_dir          = 'asc'; //$this->input->post('order')[0]['dir'];
		$total_data         = $this->M_LaporanPosisiStockBarang->count_all_data(null, null, null, null, null, $depo_id, $principle_id, $depo_detail_id);

		$records_filtered   = $total_data;
		$data_laporan_stock = $this->M_LaporanPosisiStockBarang->exec_report_posisi_stock_barang($start, $length, $search_value, $order_column, "'" . $order_dir . "'", $depo_id, $principle_id, $depo_detail_id);

		$response = array(
			'draw' => $draw,
			'recordsTotal' => $total_data,
			'recordsFiltered' => $records_filtered,
			'data' => $data_laporan_stock
		);
		// $response = array(
		// 	'draw' => $draw,
		// 	'recordsTotal' => count($data_laporan_stock),
		// 	'recordsFiltered' => count($data_laporan_stock),
		// 	'data' => $data_laporan_stock
		// );

		echo json_encode($response);
	}
}
