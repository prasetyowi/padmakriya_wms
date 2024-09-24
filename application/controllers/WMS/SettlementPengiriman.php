<?php

//require_once APPPATH . 'core/ParentController.php';

class SettlementPengiriman extends CI_Controller
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

		$this->MenuKode = "109009000";
		$this->load->model('M_SKU');
		$this->load->model('M_Principle');
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');
		$this->load->model('WMS/M_DeliveryOrderDraft', 'M_DeliveryOrderDraft');
	}

	public function SettlementPengirimanMenu()
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

		$delivery_order_batch_id = $this->input->get('delivery_order_batch_id');
		$data['delivery_order_batch_id'] = $delivery_order_batch_id;
		$data['Driver'] = $this->M_SettlementPengiriman->Get_Driver();

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


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/SettlementPengiriman/SettlementPengirimanMenu', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SettlementPengiriman/S_SettlementPengirimanMenu', $data);
	}

	public function get_settlement_pengiriman_by_filter()
	{
		// $tgl = explode(" - ", $this->input->post('Tgl_FDJR'));
		$tgl = $this->input->post('Tgl_FDJR');

		$Tgl_FDJR = date('Y-m-d', strtotime(str_replace("/", "-", $tgl)));
		$Tgl_FDJR2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl)));
		$No_FDJR = $this->input->post('No_FDJR');
		$karyawan_id = $this->input->post('karyawan_id');

		$data = $this->M_SettlementPengiriman->get_settlement_pengiriman_by_filter($Tgl_FDJR, $Tgl_FDJR2, $No_FDJR, $karyawan_id);

		echo json_encode($data);
	}

	public function SettlementMenu()
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

		$delivery_order_batch_id = $this->input->get('delivery_order_batch_id');
		$data['delivery_order_batch_id'] = $delivery_order_batch_id;

		// $data['SettlementHeader'] 	= $this->M_SettlementPengiriman->Get_SettlementHeader($delivery_order_batch_id);
		// $data['PenerimaanBarang'] 	= $this->M_SettlementPengiriman->Exec_SettlementPenerimaanBarang($delivery_order_batch_id);
		// $data['PenerimaanTunai'] 	= $this->M_SettlementPengiriman->Exec_SettlementPenerimaanTunai($delivery_order_batch_id);
		// $data['PenerimaanBG'] 		= $this->M_SettlementPengiriman->Exec_SettlementPenerimaanBG($delivery_order_batch_id);
		// $data['KomparasiInvoice']   = $this->M_SettlementPengiriman->Exec_SettlementKomparasiInvoiceTunaivsPenerimaanTunai($delivery_order_batch_id);
		// $data['getPengirimanArea']  = $this->M_SettlementPengiriman->Get_PengirimanArea($delivery_order_batch_id);
		// $data['getToleransi'] = explode('.', $this->db->select("vrbl_value_integer")->from('vrbl')->where("vrbl_param", "TOLERANSI_PAYMENT")->get()->row()->vrbl_value_integer)[0];
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


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/SettlementPengiriman/SettlementForm2', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SettlementPengiriman/S_SettlementForm', $data);
	}

	public function getData()
	{
		$delivery_order_batch_id = $this->input->post('do_batch_id');

		$data['SettlementHeader'] 	= $this->M_SettlementPengiriman->Get_SettlementHeader($delivery_order_batch_id);
		$data['PenerimaanBarang'] 	= $this->M_SettlementPengiriman->Exec_SettlementPenerimaanBarang($delivery_order_batch_id);
		$data['PenerimaanTunai'] 	= $this->M_SettlementPengiriman->Exec_SettlementPenerimaanTunai($delivery_order_batch_id);
		$data['PenerimaanBG'] 		= $this->M_SettlementPengiriman->Exec_SettlementPenerimaanBG($delivery_order_batch_id);
		$data['KomparasiInvoice']   = $this->M_SettlementPengiriman->Exec_SettlementKomparasiInvoiceTunaivsPenerimaanTunai($delivery_order_batch_id);
		$data['getPengirimanArea']  = $this->M_SettlementPengiriman->Get_PengirimanArea($delivery_order_batch_id);
		$data['getToleransi'] = explode('.', $this->db->select("vrbl_value_integer")->from('vrbl')->where("vrbl_param", "TOLERANSI_PAYMENT")->get()->row()->vrbl_value_integer)[0];

		echo json_encode($data);
	}

	public function detailTransaksi()
	{
		$do_batch_id = $this->input->post('do_batch_id');
		$sku_id 	 = $this->input->post('sku_id');
		$data 		 = $this->M_SettlementPengiriman->Exec_ProsesSettlementDetail($do_batch_id, $sku_id);

		echo json_encode($data);
	}

	public function DetailSettlementMenu()
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

		$delivery_order_batch_id = $this->input->get('delivery_order_batch_id');
		$sku_id = $this->input->get('sku_id');

		$data['delivery_order_batch_id'] = $this->input->get('delivery_order_batch_id');
		$data['sku_id'] = $this->input->get('sku_id');
		$data['sku_kode'] = $this->input->get('sku_kode');
		$data['sku_nama'] = $this->input->get('sku_nama');
		$data['sku_kemasan'] = $this->input->get('sku_kemasan');
		$data['sku_satuan'] = $this->input->get('sku_satuan');
		$data['qty_do'] = $this->input->get('qty_do');
		$data['qty_aktual'] = $this->input->get('qty_aktual');
		$data['status'] = $this->input->get('status');

		$data['SettlementDetail'] = $this->M_SettlementPengiriman->Exec_ProsesSettlementDetail($delivery_order_batch_id, $sku_id);

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


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/SettlementPengiriman/SettlementDetail', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SettlementPengiriman/S_SettlementDetail', $data);
	}

	public function DeliveryOrderForm()
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

		$delivery_order_batch_id = $this->input->get('delivery_order_batch_id');
		$data['delivery_order_batch_id'] = $delivery_order_batch_id;

		$data['Lokasi'] = $this->M_DeliveryOrderDraft->GetLokasi();
		$data['Bulan'] = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
		$data['Perusahaan'] = $this->M_SettlementPengiriman->GetPerusahaanByFDJR($delivery_order_batch_id);
		$data['TipePelayanan'] = $this->M_DeliveryOrderDraft->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_SettlementPengiriman->GetTipeDeliveryOrder();
		$data['Area'] = $this->M_DeliveryOrderDraft->GetArea();

		$data['detail'] = $this->M_SettlementPengiriman->GetSKUDODetailBySettlement($delivery_order_batch_id);

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


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/SettlementPengiriman/DeliveryOrderForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);
		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SettlementPengiriman/S_DeliveryOrderForm', $data);
	}

	public function DeliveryOrderReturForm()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');
		$this->load->model('M_ClientPt');
		$this->load->model('M_SKU');
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
			redirect(base_url('MainPage/DepoMenu'));
		}

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['delivery_order_batch_id'] = $this->input->get('delivery_order_batch_id');
		$data['delivery_order_batch_kode'] = $this->M_SettlementPengiriman->GetFDJRKode($this->input->get('delivery_order_batch_id'));

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


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/SettlementPengiriman/DeliveryOrderRetur', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SettlementPengiriman/S_DeliveryOrderRetur', $data);
	}

	public function DeliveryOrderMenu()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$delivery_order_batch_id = $this->input->get('delivery_order_batch_id');
		$data['delivery_order_batch_id'] = $delivery_order_batch_id;
		$data['Perusahaan'] = $this->M_SettlementPengiriman->GetPerusahaanByFDJR($delivery_order_batch_id);
		$data['TipePelayanan'] = $this->M_SettlementPengiriman->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_SettlementPengiriman->GetTipeDeliveryOrder();
		$data['Area'] = $this->M_SettlementPengiriman->GetArea();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/SettlementPengiriman/DOForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SettlementPengiriman/S_DOForm', $data);
	}

	public function ProsesDOReturMenu()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$delivery_order_batch_id = $this->input->get('delivery_order_batch_id');
		$delivery_order_id = $this->input->get('delivery_order_id');
		$delivery_order_kode = $this->M_SettlementPengiriman->get_delivery_order_kode($delivery_order_id);

		$data['Bulan'] = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
		$data['delivery_order_batch_id'] = $delivery_order_batch_id;
		$data['delivery_order_id'] = $delivery_order_id;
		$data['delivery_order_kode'] = $delivery_order_kode;
		$data['Customer'] = $this->M_SettlementPengiriman->GetCustomerByDO($delivery_order_id);
		$data['Perusahaan'] = $this->M_SettlementPengiriman->GetPerusahaanByDO($delivery_order_id);
		$data['TipePelayanan'] = $this->M_SettlementPengiriman->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_SettlementPengiriman->GetTipeDeliveryOrder();
		$data['Area'] = $this->M_SettlementPengiriman->GetArea();
		$data['DODetail'] = $this->M_SettlementPengiriman->get_proses_do_detail_retur($delivery_order_id);
		$data['Lokasi'] = $this->M_SettlementPengiriman->GetLokasi();
		$data['Gudang'] = $this->M_SettlementPengiriman->GetGudang();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/SettlementPengiriman/ProsesDOReturForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SettlementPengiriman/S_ProsesDOReturForm', $data);
	}

	public function ProsesDOReturDetail()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$delivery_order_batch_id = $this->input->get('delivery_order_batch_id');
		$delivery_order_id = $this->input->get('delivery_order_id');
		$delivery_order_kode = $this->M_SettlementPengiriman->get_delivery_order_kode($delivery_order_id);
		$dor_id = $this->M_SettlementPengiriman->get_delivery_order_retur_id($delivery_order_id);

		$data['DOHeader'] = $this->M_SettlementPengiriman->GetDeliveryOrderReturHeaderById($dor_id);
		$data['DODetail'] = $this->M_SettlementPengiriman->GetDeliveryOrderReturDetailById($dor_id);

		$data['Bulan'] = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
		$data['delivery_order_batch_id'] = $delivery_order_batch_id;
		$data['delivery_order_id'] = $delivery_order_id;
		$data['delivery_order_kode'] = $delivery_order_kode;
		$data['Customer'] = $this->M_SettlementPengiriman->GetCustomerByDO($delivery_order_id);
		$data['Perusahaan'] = $this->M_SettlementPengiriman->GetPerusahaanByDO($delivery_order_id);
		$data['TipePelayanan'] = $this->M_SettlementPengiriman->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_SettlementPengiriman->GetTipeDeliveryOrder();
		$data['Area'] = $this->M_SettlementPengiriman->GetArea();
		$data['Lokasi'] = $this->M_SettlementPengiriman->GetLokasi();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/SettlementPengiriman/ProsesDOReturDetail', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SettlementPengiriman/S_ProsesDOReturForm', $data);
	}

	public function GetSettlementMenu()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');

		$data['SettlementHeader'] = $this->M_SettlementPengiriman->Get_SettlementHeader($delivery_order_batch_id);
		$data['PenerimaanBarang'] = $this->M_SettlementPengiriman->Exec_Settlement($delivery_order_batch_id);
		$data['PenerimaanTunai'] = $this->M_SettlementPengiriman->Exec_Settlement($delivery_order_batch_id);

		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetSettlementDetailMenu()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$sku_id = $this->input->post('sku_id');

		$data['SettlementDetail'] = $this->M_SettlementPengiriman->Exec_ProsesSettlementDetail($delivery_order_batch_id, $sku_id);

		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetDetailBKB()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$documentno = $this->input->post('documentno');
		$sku_id = $this->input->post('sku_id');

		$data['BKB'] = $this->M_SettlementPengiriman->Get_DetailBKB($documentno, $sku_id);

		echo json_encode($data);
	}

	public function GetDetailDO()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$documentno = $this->input->post('documentno');
		$sku_id = $this->input->post('sku_id');

		$data['HeaderDO'] = $this->M_SettlementPengiriman->Get_HeaderDO($documentno);
		$data['DetailDO'] = $this->M_SettlementPengiriman->Get_DetailDO($documentno, $sku_id);

		echo json_encode($data);
	}

	public function GetDOById()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$documentno = $this->input->post('documentno');

		$data['HeaderDO'] = $this->M_SettlementPengiriman->GetHeaderDOById($documentno);
		$data['DetailDO'] = $this->M_SettlementPengiriman->GetDetailDOById($documentno);

		echo json_encode($data);
	}

	public function UpdateDOSkuQty()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('delivery_order_id', 'Delivery Order ID', 'required');
		$this->form_validation->set_rules('sku_id', 'SKU ID', 'required');
		$this->form_validation->set_rules('sku_qty', 'SKU QTY', 'required');
		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_id = $this->input->post('delivery_order_id');
			$sku_id = $this->input->post('sku_id');
			$sku_qty = $this->input->post('sku_qty');

			$result = $this->M_SettlementPengiriman->Update_DOSkuQty($delivery_order_id, $sku_id, $sku_qty);

			echo $result;
		}
	}

	public function InsertSettlement()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');
		$this->form_validation->set_rules('statussettlement', 'Status Settlement', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$statussettlement = $this->input->post('statussettlement');

			$cek_settlement = $this->M_SettlementPengiriman->cek_settlement_by_fdjr($delivery_order_batch_id);

			if ($cek_settlement == 0) {
				$this->M_SettlementPengiriman->set_fdjr_status_completed($delivery_order_batch_id);
				$result = $this->M_SettlementPengiriman->Insert_Settlement($delivery_order_batch_id, $statussettlement);
			} else {
				$result = 2;
			}

			echo $result;
		}
	}

	public function GetPerusahaanById()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');

		$id = $this->input->post('id');
		$data = $this->M_SettlementPengiriman->GetPerusahaanById($id);

		echo json_encode($data);
	}

	public function GetSelectedCustomer()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');

		$customer = $this->input->post('customer');
		$perusahaan = $this->input->post('perusahaan');

		$data = $this->M_SettlementPengiriman->GetSelectedCustomer($customer, $perusahaan);

		echo json_encode($data);
	}

	public function GetSelectedPrinciple()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');

		$customer = $this->input->post('customer');
		$perusahaan = $this->input->post('perusahaan');

		$data = $this->M_SettlementPengiriman->GetSelectedPrinciple($customer, $perusahaan);

		echo json_encode($data);
	}

	public function GetSelectedSKU()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');

		$sku_id = $this->input->post('sku_id');

		$data = $this->M_SettlementPengiriman->GetSelectedSKU($sku_id);

		echo json_encode($data);
	}

	public function GetFactoryByTypePelayanan()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');

		$perusahaan = $this->input->post('perusahaan');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$tipe_layanan = $this->input->post('tipe_layanan');
		$nama = $this->input->post('nama');
		$alamat = $this->input->post('alamat');
		$telp = $this->input->post('telp');
		$area = $this->input->post('area');

		$data = $this->M_SettlementPengiriman->GetFactoryByTypePelayanan($perusahaan, $tipe_pembayaran, $tipe_layanan, $nama, $alamat, $telp, $area);

		echo json_encode($data);
	}

	public function GetCustomerByTypePelayanan()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');

		$perusahaan = $this->input->post('perusahaan');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$tipe_layanan = $this->input->post('tipe_layanan');
		$nama = $this->input->post('nama');
		$alamat = $this->input->post('alamat');
		$telp = $this->input->post('telp');
		$area = $this->input->post('area');

		$data = $this->M_SettlementPengiriman->GetCustomerByTypePelayanan($perusahaan, $tipe_pembayaran, $tipe_layanan, $nama, $alamat, $telp, $area);

		echo json_encode($data);
	}

	public function search_filter_chosen_sku()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');

		$client_pt = $this->input->post('client_pt');
		$perusahaan = $this->input->post('perusahaan');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$brand = $this->input->post('brand');
		$principle = $this->input->post('principle');
		$sku_induk = $this->input->post('sku_induk');
		$sku_nama_produk = $this->input->post('sku_nama_produk');
		$sku_kemasan = $this->input->post('sku_kemasan');
		$sku_satuan = $this->input->post('sku_satuan');

		$data = $this->M_SettlementPengiriman->search_filter_chosen_sku($client_pt, $perusahaan, $tipe_pembayaran, $brand, $principle, $sku_induk, $sku_nama_produk, $sku_kemasan, $sku_satuan);

		echo json_encode($data);
	}

	public function search_filter_chosen_sku_by_pabrik()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');

		$client_pt = $this->input->post('client_pt');
		$perusahaan = $this->input->post('perusahaan');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$brand = $this->input->post('brand');
		$principle = $this->input->post('principle');
		$sku_induk = $this->input->post('sku_induk');
		$sku_nama_produk = $this->input->post('sku_nama_produk');
		$sku_kemasan = $this->input->post('sku_kemasan');
		$sku_satuan = $this->input->post('sku_satuan');

		$data = $this->M_SettlementPengiriman->search_filter_chosen_sku_by_pabrik($client_pt, $perusahaan, $tipe_pembayaran, $brand, $principle, $sku_induk, $sku_nama_produk, $sku_kemasan, $sku_satuan);

		echo json_encode($data);
	}

	public function insert_delivery_order()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		// $delivery_order_id = $this->input->post('delivery_order_id');
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$sales_order_id = $this->input->post('sales_order_id');
		// $delivery_order_kode = $this->input->post('delivery_order_kode');
		$delivery_order_yourref = $this->input->post('delivery_order_yourref');
		$client_wms_id = $this->input->post('client_wms_id');
		$delivery_order_tgl_buat_do = $this->input->post('delivery_order_tgl_buat_do');
		$delivery_order_tgl_expired_do = $this->input->post('delivery_order_tgl_expired_do');
		$delivery_order_tgl_surat_jalan = $this->input->post('delivery_order_tgl_surat_jalan');
		$delivery_order_tgl_rencana_kirim = $this->input->post('delivery_order_tgl_rencana_kirim');
		$delivery_order_tgl_aktual_kirim = $this->input->post('delivery_order_tgl_aktual_kirim');
		$delivery_order_keterangan = $this->input->post('delivery_order_keterangan');
		$delivery_order_status = $this->input->post('delivery_order_status');
		$delivery_order_is_prioritas = $this->input->post('delivery_order_is_prioritas');
		$delivery_order_is_need_packing = $this->input->post('delivery_order_is_need_packing');
		$delivery_order_tipe_layanan = $this->input->post('delivery_order_tipe_layanan');
		$delivery_order_tipe_pembayaran = $this->input->post('delivery_order_tipe_pembayaran');
		$delivery_order_sesi_pengiriman = $this->input->post('delivery_order_sesi_pengiriman');
		$delivery_order_request_tgl_kirim = $this->input->post('delivery_order_request_tgl_kirim');
		$delivery_order_request_jam_kirim = $this->input->post('delivery_order_request_jam_kirim');
		$tipe_pengiriman_id = $this->input->post('tipe_pengiriman_id');
		$nama_tipe = $this->input->post('nama_tipe');
		$confirm_rate = $this->input->post('confirm_rate');
		$delivery_order_reff_id = $this->input->post('delivery_order_reff_id');
		$delivery_order_reff_no = $this->input->post('delivery_order_reff_no');
		$delivery_order_total = $this->input->post('delivery_order_total');
		$unit_mandiri_id = $this->input->post('unit_mandiri_id');
		$depo_id = $this->input->post('depo_id');
		$client_pt_id = $this->input->post('client_pt_id');
		$delivery_order_kirim_nama = $this->input->post('delivery_order_kirim_nama');
		$delivery_order_kirim_alamat = $this->input->post('delivery_order_kirim_alamat');
		$delivery_order_kirim_telp = $this->input->post('delivery_order_kirim_telp');
		$delivery_order_kirim_provinsi = $this->input->post('delivery_order_kirim_provinsi');
		$delivery_order_kirim_kota = $this->input->post('delivery_order_kirim_kota');
		$delivery_order_kirim_kecamatan = $this->input->post('delivery_order_kirim_kecamatan');
		$delivery_order_kirim_kelurahan = $this->input->post('delivery_order_kirim_kelurahan');
		$delivery_order_kirim_latitude = $this->input->post('delivery_order_kirim_latitude');
		$delivery_order_kirim_longitude = $this->input->post('delivery_order_kirim_longitude');
		$delivery_order_kirim_kodepos = $this->input->post('delivery_order_kirim_kodepos');
		$delivery_order_kirim_area = $this->input->post('delivery_order_kirim_area');
		$delivery_order_kirim_invoice_pdf = $this->input->post('delivery_order_kirim_invoice_pdf');
		$delivery_order_kirim_invoice_dir = $this->input->post('delivery_order_kirim_invoice_dir');
		$principle_id = $this->input->post('principle_id');
		$delivery_order_ambil_nama = $this->input->post('delivery_order_ambil_nama');
		$delivery_order_ambil_alamat = $this->input->post('delivery_order_ambil_alamat');
		$delivery_order_ambil_telp = $this->input->post('delivery_order_ambil_telp');
		$delivery_order_ambil_provinsi = $this->input->post('delivery_order_ambil_provinsi');
		$delivery_order_ambil_kota = $this->input->post('delivery_order_ambil_kota');
		$delivery_order_ambil_kecamatan = $this->input->post('delivery_order_ambil_kecamatan');
		$delivery_order_ambil_kelurahan = $this->input->post('delivery_order_ambil_kelurahan');
		$delivery_order_ambil_latitude = $this->input->post('delivery_order_ambil_latitude');
		$delivery_order_ambil_longitude = $this->input->post('delivery_order_ambil_longitude');
		$delivery_order_ambil_kodepos = $this->input->post('delivery_order_ambil_kodepos');
		$delivery_order_ambil_area = $this->input->post('delivery_order_ambil_area');
		$delivery_order_update_who = $this->input->post('delivery_order_update_who');
		$delivery_order_update_tgl = $this->input->post('delivery_order_update_tgl');
		$delivery_order_approve_who = $this->input->post('delivery_order_approve_who');
		$delivery_order_approve_tgl = $this->input->post('delivery_order_approve_tgl');
		$delivery_order_reject_who = $this->input->post('delivery_order_reject_who');
		$delivery_order_reject_tgl = $this->input->post('delivery_order_reject_tgl');
		$delivery_order_reject_reason = $this->input->post('delivery_order_reject_reason');
		$delivery_order_no_urut_rute = $this->input->post('delivery_order_no_urut_rute');
		$delivery_order_prioritas_stock = $this->input->post('delivery_order_prioritas_stock');
		$tipe_delivery_order_id = $this->input->post('tipe_delivery_order_id');
		$delivery_order_draft_id = $this->input->post('delivery_order_draft_id');
		$delivery_order_draft_kode = $this->input->post('delivery_order_draft_kode');

		$detail = $this->input->post('detail');

		$dod_id = $this->M_Vrbl->Get_NewID();
		$delivery_order_id = $dod_id[0]['NEW_ID'];

		//generate kode
		$param = "";
		if ($tipe_delivery_order_id == "C5BE83E2-01E8-4E24-B766-26BB4158F2CD") {
			$param =  'KODE_DOR';
			$status_progress_id = "BAF4066F-AAC8-4AF3-A019-DD0777366E50";
			$status_progress_nama = "retur";

			$this->M_SettlementPengiriman->Insert_DeliveryOrderProgressStatus($delivery_order_id, $status_progress_id, $status_progress_nama);
		} else {
			$param =  'KODE_DO';
		}
		$date_now = date('Y-m-d h:i:s');
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_SettlementPengiriman->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$delivery_order_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$this->db->trans_begin();

		//insert ke tr_pemusnahan_stok_draft
		$this->M_SettlementPengiriman->insert_delivery_order($delivery_order_id, $delivery_order_batch_id, $sales_order_id, $delivery_order_kode, $delivery_order_yourref, $client_wms_id, $delivery_order_tgl_buat_do, $delivery_order_tgl_expired_do, $delivery_order_tgl_surat_jalan, $delivery_order_tgl_rencana_kirim, $delivery_order_tgl_aktual_kirim, $delivery_order_keterangan, $delivery_order_status, $delivery_order_is_prioritas, $delivery_order_is_need_packing, $delivery_order_tipe_layanan, $delivery_order_tipe_pembayaran, $delivery_order_sesi_pengiriman, $delivery_order_request_tgl_kirim, $delivery_order_request_jam_kirim, $tipe_pengiriman_id, $nama_tipe, $confirm_rate, $delivery_order_reff_id, $delivery_order_reff_no, $delivery_order_total, $unit_mandiri_id, $depo_id, $client_pt_id, $delivery_order_kirim_nama, $delivery_order_kirim_alamat, $delivery_order_kirim_telp, $delivery_order_kirim_provinsi, $delivery_order_kirim_kota, $delivery_order_kirim_kecamatan, $delivery_order_kirim_kelurahan, $delivery_order_kirim_latitude, $delivery_order_kirim_longitude, $delivery_order_kirim_kodepos, $delivery_order_kirim_area, $delivery_order_kirim_invoice_pdf, $delivery_order_kirim_invoice_dir, $principle_id, $delivery_order_ambil_nama, $delivery_order_ambil_alamat, $delivery_order_ambil_telp, $delivery_order_ambil_provinsi, $delivery_order_ambil_kota, $delivery_order_ambil_kecamatan, $delivery_order_ambil_kelurahan, $delivery_order_ambil_latitude, $delivery_order_ambil_longitude, $delivery_order_ambil_kodepos, $delivery_order_ambil_area, $delivery_order_update_who, $delivery_order_update_tgl, $delivery_order_approve_who, $delivery_order_approve_tgl, $delivery_order_reject_who, $delivery_order_reject_tgl, $delivery_order_reject_reason, $delivery_order_no_urut_rute, $delivery_order_prioritas_stock, $tipe_delivery_order_id, $delivery_order_draft_id, $delivery_order_draft_kode);

		//insert ke tr_pemusnahan_stok_detail_draft
		// echo var_dump($detail);
		foreach ($detail as $key => $value) {
			$delivery_order_detail_id = $this->M_Vrbl->Get_NewID();
			$delivery_order_detail_id = $delivery_order_detail_id[0]['NEW_ID'];
			$this->M_SettlementPengiriman->insert_delivery_order_detail($delivery_order_detail_id, $delivery_order_id, $delivery_order_batch_id, $value);
			// $this->M_SettlementPengiriman->insert_delivery_order_detail2($delivery_order_detail_id, $delivery_order_id, $value);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function get_settlement_status_by_fdjr()
	{
		$this->load->model('WMS/M_SettlementPengiriman', 'M_SettlementPengiriman');

		$fdjr_id = $this->input->get('fdjr_id');

		$data['PenerimaanBarang'] = $this->M_SettlementPengiriman->Exec_SettlementPenerimaanBarang($fdjr_id);
		$data['PenerimaanTunai'] = $this->M_SettlementPengiriman->Exec_SettlementPenerimaanTunai($fdjr_id);

		echo json_encode($data);
	}

	public function CekLastUpdateFDJR()
	{

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$getLastUpdatedDb = $this->db->query("select delivery_order_batch_update_tgl from delivery_order_batch where delivery_order_batch_id ='$delivery_order_batch_id'")->row()->delivery_order_batch_update_tgl;

		$lastUpdated = $this->input->post('lastUpdated') == "null" ? NULL : $this->input->post('lastUpdated');

		$errorNotSameLastUpdated = false;
		if ($lastUpdated !== $getLastUpdatedDb) $errorNotSameLastUpdated = true;

		if ($errorNotSameLastUpdated) {
			echo json_encode(2);
		} else {
			echo json_encode(1);
		}
	}
}
