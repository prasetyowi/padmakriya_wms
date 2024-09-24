<?php

class StatusDO extends CI_Controller
{
	private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		if ($this->session->has_userdata('pengguna_id') == 0) :
			redirect(base_url('MainPage'));
		endif;

		$this->MenuKode = "109016000";
		$this->load->model('WMS/M_StatusDO', 'M_StatusDO');
		$this->load->model('M_Vrbl');
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_DeliveryOrderDraft', 'M_DeliveryOrderDraft');
	}

	public function StatusDOMenu()
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/StatusDO/StatusDO', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/StatusDO/S_StatusDO', $data);
	}

	public function detail()
	{
		$id = $this->input->get('id');

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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();
		$data['Bulan'] = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
		$data['Lokasi'] = $this->M_DeliveryOrderDraft->GetLokasi();
		$data['DOHeader'] = $this->M_StatusDO->GetDeliveryOrderHeaderById($id);
		$data['DODetail'] = $this->M_StatusDO->GetDeliveryOrderDetailById($id);
		$data['Perusahaan'] = $this->M_DeliveryOrderDraft->GetPerusahaan();
		$data['TipePelayanan'] = $this->M_DeliveryOrderDraft->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_DeliveryOrderDraft->GetTipeDeliveryOrder();
		$data['Area'] = $this->M_DeliveryOrderDraft->GetArea();
		$data['act'] = "detail";

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/StatusDO/detail', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/StatusDO/S_StatusDO', $data);
	}

	public function getSalesByDate()
	{
		$tanggal 	= explode(" - ", $this->input->post('date'));
		$tgl 		= date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[0])));
		$tgl2 		= date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[1])));

		$data		= $this->M_StatusDO->getSalesByDate($tgl, $tgl2);

		echo json_encode($data);
	}

	public function getDriverByDate()
	{
		$tanggal 	= explode(" - ", $this->input->post('date'));
		$tgl 		= date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[0])));
		$tgl2 		= date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[1])));

		$data		= $this->M_StatusDO->getDriverByDate($tgl, $tgl2);

		echo json_encode($data);
	}

	public function getNoSOBySales()
	{
		$sales 		= $this->input->post('sales');
		$tanggal 	= explode(" - ", $this->input->post('date'));
		$tgl 		= date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[0])));
		$tgl2 		= date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[1])));
		$data  		= $this->M_StatusDO->getNoSOBySales($sales, $tgl, $tgl2);

		echo json_encode($data);
	}

	public function getNoFDJRBySales()
	{
		$driver 	= $this->input->post('driver');
		$tanggal 	= explode(" - ", $this->input->post('date'));
		$tgl 		= date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[0])));
		$tgl2 		= date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[1])));
		$data   	= $this->M_StatusDO->getNoFDJRByDriver($driver, $tgl, $tgl2);

		echo json_encode($data);
	}

	public function FilterGetDO()
	{
		$noso 				 = $this->input->post('noso');
		$nofdjr 			 = $this->input->post('nofdjr');
		$mode 				 = $this->input->post('mode');
		$sales 				 = $this->input->post('sales');
		$driver 			 = $this->input->post('driver');
		$dateSO 			 = explode(" - ", $this->input->post('dateSO'));
		$tgl1				 = date('Y-m-d', strtotime(str_replace("/", "-", $dateSO[0])));
		$tgl2 				 = date('Y-m-d', strtotime(str_replace("/", "-", $dateSO[1])));
		$dateDO 			 = explode(" - ", $this->input->post('dateDO'));
		$tgl3				 = date('Y-m-d', strtotime(str_replace("/", "-", $dateDO[0])));
		$tgl4 				 = date('Y-m-d', strtotime(str_replace("/", "-", $dateDO[1])));
		$draw                = intval($this->input->post('draw'));
		$start               = intval($this->input->post('start'));
		$length              = intval($this->input->post('length'));
		$search_value        = $this->input->post('search')['value'];
		$order_column        = intval($this->input->post('order')[0]['column']);
		$order_dir           = $this->input->post('order')[0]['dir'];
		$total_data          = $this->M_StatusDO->count_all_data($noso, $nofdjr, $mode, $sales, $driver, $tgl1, $tgl2, $tgl3, $tgl4);
		$total_filtered_data = $this->M_StatusDO->count_filtered_data($search_value, $noso, $nofdjr, $mode, $sales, $driver, $tgl1, $tgl2, $tgl3, $tgl4);
		$data                = $this->M_StatusDO->get_filtered_data($start, $length, $search_value, $order_column, $order_dir, $noso, $nofdjr, $mode, $sales, $driver, $tgl1, $tgl2, $tgl3, $tgl4);

		$response = array(
			'draw'              => $draw,
			'recordsTotal'      => $total_data,
			'recordsFiltered'   => $total_filtered_data,
			'data'              => $data
		);

		echo json_encode($response);
	}

	public function GetDODetail()
	{
		$do_id = $this->input->post('do_id');
		$data  = $this->M_StatusDO->GetDODetail($do_id);

		echo json_encode($data);
	}

	public function cetakStatusDO()
	{
		$data['mode'] 	= $this->input->get('mode');
		$sales 			= $this->input->get('sales');
		$noso		 	= $this->input->get('noso');
		if ($data['mode'] == 0) {
			$data['dateSO'] = explode(" - ", $this->input->get('filter_date_so'));
			$data['tgl']			= date('Y-m-d', strtotime(str_replace("/", "-", $data['dateSO'][0])));
			$data['tgl2'] 			= date('Y-m-d', strtotime(str_replace("/", "-", $data['dateSO'][1])));
		} else {
			$data['dateDO'] = explode(" - ", $this->input->get('filter_date_do'));
			$data['tgl']			= date('Y-m-d', strtotime(str_replace("/", "-", $data['dateDO'][0])));
			$data['tgl2'] 			= date('Y-m-d', strtotime(str_replace("/", "-", $data['dateDO'][1])));
		}
		$driver		    = $this->input->get('driver');
		$nofdjr			= $this->input->get('nofdjr');

		$data['result'] = $this->M_StatusDO->getDetailDO($noso, $nofdjr, $data['mode'], $sales, $driver, $data['tgl'], $data['tgl2']);

		if ($noso != "") {
			$data['noso'] = $this->M_StatusDO->getNoSO($noso);
		} else {
			$data['noso'] = "";
		}
		if ($nofdjr != "") {
			$data['nofdjr']  = $this->M_StatusDO->getNoFDJR($nofdjr);
		} else {
			$data['nofdjr'] = "";
		}
		if ($driver != "") {
			$data['driver'] = $this->M_StatusDO->getDriver($driver);
		} else {
			$data['driver'] = "";
		}
		if ($sales != "") {
			$data['sales']  = $this->M_StatusDO->getSales($sales);
		} else {
			$data['sales'] = "";
		}

		$this->load->view('WMS/StatusDO/cetakStatusDO', $data);
	}
}
