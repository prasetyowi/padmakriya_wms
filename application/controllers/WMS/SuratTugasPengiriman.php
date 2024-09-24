<?php

//require_once APPPATH . 'core/ParentController.php';

class SuratTugasPengiriman extends CI_Controller
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

		$this->MenuKode = "109001000";
		$this->load->model('M_SKU');
		$this->load->model('M_Principle');
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('WMS/M_DeliveryOrderDraft', 'M_DeliveryOrderDraft');

		$this->load->library(['form_validation', 'pdfgenerator']);
	}

	public function SuratTugasPengirimanMenu()
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
		$this->load->view('WMS/SuratTugasPengiriman/SuratTugasPengiriman', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SuratTugasPengiriman/S_SuratTugasPengiriman', $data);
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
		$this->load->view('WMS/SuratTugasPengiriman/SettlementForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SuratTugasPengiriman/S_SettlementForm', $data);
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

		$data['delivery_order_batch_id'] = $this->input->get('delivery_order_batch_id');
		$data['sku_id'] = $this->input->get('sku_id');
		$data['sku_kode'] = $this->input->get('sku_kode');
		$data['sku_nama'] = $this->input->get('sku_nama');
		$data['sku_kemasan'] = $this->input->get('sku_kemasan');
		$data['sku_satuan'] = $this->input->get('sku_satuan');
		$data['qty_do'] = $this->input->get('qty_do');
		$data['qty_aktual'] = $this->input->get('qty_aktual');
		$data['status'] = $this->input->get('status');

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
		$this->load->view('WMS/SuratTugasPengiriman/SettlementDetail', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SuratTugasPengiriman/S_SettlementDetail', $data);
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
		$data['Perusahaan'] = $this->M_SuratTugasPengiriman->GetPerusahaanByFDJR($delivery_order_batch_id);
		$data['TipePelayanan'] = $this->M_DeliveryOrderDraft->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_SuratTugasPengiriman->GetTipeDeliveryOrder();
		$data['Area'] = $this->M_DeliveryOrderDraft->GetArea();

		$data['detail'] = $this->M_SuratTugasPengiriman->GetSKUDODetailBySettlement($delivery_order_batch_id);

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
		$this->load->view('WMS/SuratTugasPengiriman/DeliveryOrderForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);
		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SuratTugasPengiriman/S_DeliveryOrderForm', $data);
	}

	public function DeliveryOrderReturForm()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
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
		$data['delivery_order_batch_kode'] = $this->M_SuratTugasPengiriman->GetFDJRKode($this->input->get('delivery_order_batch_id'));

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
		$this->load->view('WMS/SuratTugasPengiriman/DeliveryOrderRetur', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SuratTugasPengiriman/S_DeliveryOrderRetur', $data);
	}

	public function ClosingPengirimanMenu()
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
		$this->load->view('WMS/SuratTugasPengiriman/ClosingPengirimanForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SuratTugasPengiriman/S_ClosingPengirimanForm', $data);
	}

	public function BTBFormMenu()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
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

		$data['delivery_order_batch_id'] = $this->input->get('delivery_order_batch_id');
		$data['Perusahaan'] = $this->M_SuratTugasPengiriman->GetPerusahaan();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/SuratTugasPengiriman/BTBForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SuratTugasPengiriman/S_BTBForm', $data);
	}

	public function DeliveryOrderMenu()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
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
		$data['Perusahaan'] = $this->M_SuratTugasPengiriman->GetPerusahaanByFDJR($delivery_order_batch_id);
		$data['TipePelayanan'] = $this->M_SuratTugasPengiriman->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_SuratTugasPengiriman->GetTipeDeliveryOrder();
		$data['Area'] = $this->M_SuratTugasPengiriman->GetArea();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/SuratTugasPengiriman/DOForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SuratTugasPengiriman/S_DOForm', $data);
	}

	public function ProsesDOReturMenu()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
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
		$delivery_order_kode = $this->M_SuratTugasPengiriman->get_delivery_order_kode($delivery_order_id);

		$data['Bulan'] = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
		$data['delivery_order_batch_id'] = $delivery_order_batch_id;
		$data['delivery_order_id'] = $delivery_order_id;
		$data['delivery_order_kode'] = $delivery_order_kode;
		$data['Customer'] = $this->M_SuratTugasPengiriman->GetCustomerByDO($delivery_order_id);
		$data['Perusahaan'] = $this->M_SuratTugasPengiriman->GetPerusahaanByDO($delivery_order_id);
		$data['TipePelayanan'] = $this->M_SuratTugasPengiriman->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_SuratTugasPengiriman->GetTipeDeliveryOrder();
		$data['Area'] = $this->M_SuratTugasPengiriman->GetArea();
		$data['DODetail'] = $this->M_SuratTugasPengiriman->get_proses_do_detail_retur($delivery_order_id);
		$data['Lokasi'] = $this->M_SuratTugasPengiriman->GetLokasi();
		$data['Gudang'] = $this->M_SuratTugasPengiriman->GetGudang();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/SuratTugasPengiriman/ProsesDOReturForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SuratTugasPengiriman/S_ProsesDOReturForm', $data);
	}

	public function ProsesDOReturDetail()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
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
		$delivery_order_kode = $this->M_SuratTugasPengiriman->get_delivery_order_kode($delivery_order_id);
		$dor_id = $this->M_SuratTugasPengiriman->get_delivery_order_retur_id($delivery_order_id);

		$data['DOHeader'] = $this->M_SuratTugasPengiriman->GetDeliveryOrderReturHeaderById($dor_id);
		$data['DODetail'] = $this->M_SuratTugasPengiriman->GetDeliveryOrderReturDetailById($dor_id);

		$data['Bulan'] = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
		$data['delivery_order_batch_id'] = $delivery_order_batch_id;
		$data['delivery_order_id'] = $delivery_order_id;
		$data['delivery_order_kode'] = $delivery_order_kode;
		$data['Customer'] = $this->M_SuratTugasPengiriman->GetCustomerByDO($delivery_order_id);
		$data['Perusahaan'] = $this->M_SuratTugasPengiriman->GetPerusahaanByDO($delivery_order_id);
		$data['TipePelayanan'] = $this->M_SuratTugasPengiriman->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_SuratTugasPengiriman->GetTipeDeliveryOrder();
		$data['Area'] = $this->M_SuratTugasPengiriman->GetArea();
		$data['Lokasi'] = $this->M_SuratTugasPengiriman->GetLokasi();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/SuratTugasPengiriman/ProsesDOReturDetail', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SuratTugasPengiriman/S_ProsesDOReturForm', $data);
	}

	public function BTBDetail()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
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

		$data['delivery_order_batch_id'] = $this->input->get('delivery_order_batch_id');
		$data['Perusahaan'] = $this->M_SuratTugasPengiriman->GetPerusahaan();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/SuratTugasPengiriman/BTBDetail', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SuratTugasPengiriman/S_BTBDetail', $data);
	}

	public function print()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Menu');

		$delivery_order_batch_id = $this->input->get('delivery_order_batch_id');
		$data['delivery_order_batch_id'] = $delivery_order_batch_id;

		$data['ClosingPengirimanHeader'] = $this->M_SuratTugasPengiriman->Get_ClosingPengirimanHeader($delivery_order_batch_id);
		$data['ClosingPengirimanDetail'] = $this->M_SuratTugasPengiriman->Get_ClosingPengirimanDetail($delivery_order_batch_id);
		$data['ClosingPengirimanDODetail'] = $this->M_SuratTugasPengiriman->GetClosingPengirimanDODetail($delivery_order_batch_id);

		//title dari pdf
		$this->data['title_pdf'] = 'Report Confirm Delivery';

		// //filename dari pdf ketika didownload
		$file_pdf = 'report_confirm_delivery_' . date('dMY');
		// //setting paper
		$paper = 'A4';
		// //orientasi paper potrait / landscape
		$orientation = "landscape";

		$html = $this->load->view('WMS/SuratTugasPengiriman/view_cetak_konfirmasi_pengiriman', $data, true);

		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}

	public function GetSuratTugasPengirimanMenu()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$data['Driver'] = $this->M_SuratTugasPengiriman->Get_Driver();
		$data['StatusFDJR'] = $this->M_SuratTugasPengiriman->Get_StatusFDJR();

		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetSettlementMenu()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');

		$data['SettlementHeader'] = $this->M_SuratTugasPengiriman->Get_SettlementHeader($delivery_order_batch_id);
		$data['Settlement'] = $this->M_SuratTugasPengiriman->Exec_Settlement($delivery_order_batch_id);

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
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$sku_id = $this->input->post('sku_id');

		$data['Settlement'] = $this->M_SuratTugasPengiriman->Exec_ProsesSettlementDetail($delivery_order_batch_id, $sku_id);

		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetClosingPengirimanMenu()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');

		$data['ClosingPengirimanHeader'] = $this->M_SuratTugasPengiriman->Get_ClosingPengirimanHeader($delivery_order_batch_id);
		$data['ClosingPengiriman'] = $this->M_SuratTugasPengiriman->Get_ClosingPengirimanDetail($delivery_order_batch_id);
		$data['ClosingPengirimanArea'] = $this->M_SuratTugasPengiriman->Get_ClosingPengirimanArea($delivery_order_batch_id);
		$data['Reason'] = $this->M_SuratTugasPengiriman->Get_Reason();

		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetSuratTugasPengirimanTable()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$tgl = explode(" - ", $this->input->post('Tgl_FDJR'));

		$Tgl_FDJR = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$Tgl_FDJR2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$No_FDJR = $this->input->post('No_FDJR');
		$karyawan_id = $this->input->post('karyawan_id');
		$Status_FDJR  = $this->input->post('Status_FDJR');

		$data['SuratTugasPengiriman'] = $this->M_SuratTugasPengiriman->Get_SuratTugasPengiriman($Tgl_FDJR, $Tgl_FDJR2, $No_FDJR, $karyawan_id, $Status_FDJR);

		echo json_encode($data);
	}

	public function GetClosingPengirimanByDO()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$delivery_order_id = $this->input->post('delivery_order_id');

		$data['DeliveryOrder'] = $this->M_SuratTugasPengiriman->Get_ClosingPengirimanByDO($delivery_order_id);
		$data['Reason'] = $this->M_SuratTugasPengiriman->Get_Reason();

		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function UpdateClosingPengirimanByDO()
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
		$this->form_validation->set_rules('delivery_order_detail_id', 'Delivery Detail Order ID', 'required');
		$this->form_validation->set_rules('sku_qty_kirim', 'SKU Qty Kirim', 'required');
		// $this->form_validation->set_rules('reason_id', 'Reason ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_id = $this->input->post('delivery_order_id');
			$delivery_order_detail_id = $this->input->post('delivery_order_detail_id');
			$delivery_order_detail2_id = $this->input->post('delivery_order_detail2_id');
			$sku_qty_kirim = $this->input->post('sku_qty_kirim');
			$reason_id = $this->input->post('reason_id');

			$result = $this->M_SuratTugasPengiriman->Delete_DeliveryOrderDetailTemp($delivery_order_id, $delivery_order_detail_id, $delivery_order_detail2_id);
			$result = $this->M_SuratTugasPengiriman->Update_ClosingPengirimanByDO($delivery_order_id, $delivery_order_detail_id, $sku_qty_kirim, $reason_id, $delivery_order_detail2_id);

			echo $result;
		}
	}

	public function DeleteClosingPengirimanByDO()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$result = $this->M_SuratTugasPengiriman->Delete_ClosingPengirimanByDO();

		echo $result;
	}

	public function UpdateClosingPengirimanDO()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');
		$this->form_validation->set_rules('delivery_order_batch_status', 'Delivery Order Batch Status', 'required');
		// $this->form_validation->set_rules('kendaraan_km_terpakai', 'kendaraan km terpakai', 'required');
		// $this->form_validation->set_rules('kendaraan_km_akhir', 'kendaraan km akhir', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$delivery_order_batch_status = $this->input->post('delivery_order_batch_status');
			$kendaraan_km_akhir = $this->input->post('kendaraan_km_akhir');
			$delivery_order_batch_update_tgl = $this->input->post('delivery_order_batch_update_tgl');
			$kendaraan_km_terpakai = $this->input->post('kendaraan_km_terpakai');

			$arr_do_list = $this->input->post('arr_do_list');

			$arr_add_biaya = $this->input->post('arr_add_biaya');

			$lastUpdatedChecked = checkLastUpdatedData((object) [
				'table' => "delivery_order_batch",
				'whereField' => "delivery_order_batch_id",
				'whereValue' => $delivery_order_batch_id,
				'fieldDateUpdate' => "delivery_order_batch_update_tgl",
				'fieldWhoUpdate' => "delivery_order_batch_update_who",
				'lastUpdated' => $delivery_order_batch_update_tgl
			]);

			if ($lastUpdatedChecked['status'] == 400) {
				echo json_encode(2);
				return false;
			}

			$this->db->trans_begin();

			$this->M_SuratTugasPengiriman->Update_ClosingPengirimanFDJR($delivery_order_batch_id, $delivery_order_batch_status, $kendaraan_km_akhir, $kendaraan_km_terpakai);

			if ($arr_do_list != null) {
				foreach ($arr_do_list as $key => $value) {
					$delivery_order_id = $value['delivery_order_id'];
					$delivery_order_batch_id = $value['delivery_order_batch_id'];
					$status_progress_id = $value['status_progress_id'];
					$status_progress_nama = $value['status_progress_nama'];
					$delivery_order_batch_status = $value['delivery_order_batch_status'];
					$reason_id = $value['reason_id'];
					// $kendaraan_km_akhir = $value['kendaraan_km_akhir'];
					$kendaraan_km_terpakai = $value['kendaraan_km_terpakai'];
					$yourref = $value['yourref'];
					$delivery_order_jumlah_bayar = str_replace(',', '', $value['nominal_tunai']);
					$delivery_order_is_paid = $value['is_paid'];
					$delivery_order_tipe_pembayaran = $value['delivery_order_tipe_pembayaran'];
					$tipe_delivery_order_nama = $value['tipe_delivery_order_nama'];


					$this->M_SuratTugasPengiriman->Update_ClosingPengirimanDO($delivery_order_id, $status_progress_nama, $reason_id, $yourref, $delivery_order_jumlah_bayar, $delivery_order_is_paid, $delivery_order_tipe_pembayaran, $tipe_delivery_order_nama);
					// $this->M_SuratTugasPengiriman->Update_ClosingPengirimanFDJR($delivery_order_batch_id, $delivery_order_batch_status, $kendaraan_km_akhir, $kendaraan_km_terpakai);
					// $this->M_SuratTugasPengiriman->Insert_DeliveryOrderProgressStatus($delivery_order_id, $status_progress_id, $status_progress_nama);
					// $result4 = $this->M_SuratTugasPengiriman->Delete_ClosingPengirimanByDO();
				}
			}

			if ($arr_add_biaya != null) {
				foreach ($arr_add_biaya as $key => $value) {
					$this->M_SuratTugasPengiriman->deleteDODetail3($value['do_id']);
				}

				foreach ($arr_add_biaya as $key => $value) {
					$delivery_order_detail3_id = $this->M_Vrbl->Get_NewID();
					$delivery_order_detail3_id = $delivery_order_detail3_id[0]['NEW_ID'];
					$delivery_order_id = $value["do_id"];
					$tipe_biaya_id = $value["nama_biaya"];
					$delivery_order_detail3_nilai = $value["nominal_biaya"];
					$delivery_order_detail3_keterangan = $value["keterangan"];

					$delivery_order_detail3_nilai = str_replace(",", "", $delivery_order_detail3_nilai);

					$this->M_SuratTugasPengiriman->insertDODetail3($delivery_order_detail3_id, $delivery_order_id, $tipe_biaya_id, $delivery_order_detail3_nilai, $delivery_order_detail3_keterangan);
				}
			}


			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo json_encode(0);
			} else {
				$this->db->trans_commit();
				echo json_encode(1);
			}
		}
	}

	public function GetDetailBKB()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$documentno = $this->input->post('documentno');
		$sku_id = $this->input->post('sku_id');

		$data['BKB'] = $this->M_SuratTugasPengiriman->Get_DetailBKB($documentno, $sku_id);

		echo json_encode($data);
	}

	public function GetDetailDO()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$documentno = $this->input->post('documentno');
		$sku_id = $this->input->post('sku_id');

		$data['HeaderDO'] = $this->M_SuratTugasPengiriman->Get_HeaderDO($documentno);
		$data['DetailDO'] = $this->M_SuratTugasPengiriman->Get_DetailDO($documentno, $sku_id);

		echo json_encode($data);
	}

	public function InsertSettlement()
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

		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');
		$this->form_validation->set_rules('statussettlement', 'Status Settlement', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$statussettlement = $this->input->post('statussettlement');

			$result = $this->M_SuratTugasPengiriman->Insert_Settlement($delivery_order_batch_id, $statussettlement);

			echo $result;
		}
	}

	public function GetReason()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$data['Reason'] = $this->M_SuratTugasPengiriman->Get_Reason();

		echo json_encode($data);
	}

	public function GetTipePembayaran()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$data = $this->M_SuratTugasPengiriman->Get_tipe_pembayaran();

		echo json_encode($data);
	}

	public function GetKmAwalFDJR()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');

		$km_awal = $this->M_SuratTugasPengiriman->Get_KmAwalFDJR($delivery_order_batch_id);

		echo $km_awal;
	}

	public function GetBTBFormMenu()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');

		$data['BTBHeader'] = $this->M_SuratTugasPengiriman->Get_BTBHeader($delivery_order_batch_id);
		$data['GudangPenerima'] = $this->M_SuratTugasPengiriman->Get_GudangPenerima();

		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetBTBDetailByPrinciple()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$perusahaan = $this->input->post('perusahaan');
		$principle = $this->input->post('principle');

		$data['BTBDoRetur'] = $this->M_SuratTugasPengiriman->Get_BTBDoRetur($delivery_order_batch_id, $perusahaan, $principle);
		$data['BTBTerkirimSebagian'] = $this->M_SuratTugasPengiriman->Get_BTBTerkirimSebagian($delivery_order_batch_id, $perusahaan, $principle);
		$data['BTBGagal'] = $this->M_SuratTugasPengiriman->Get_BTBGagal($delivery_order_batch_id, $perusahaan, $principle);

		echo json_encode($data);
	}

	public function GetSKU()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$sku_induk = $this->input->post('sku_induk');
		$sku = $this->input->post('sku');
		$principle = $this->input->post('principle');
		$brand = $this->input->post('brand');

		$data['sku'] = $this->M_SuratTugasPengiriman->Get_SKU($sku_induk, $sku, $principle, $brand);

		echo json_encode($data);
	}

	public function GetSKUDelivery()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');

		$data['sku'] = $this->M_SuratTugasPengiriman->Get_SKUDelivery($delivery_order_batch_id);

		echo json_encode($data);
	}

	public function GetSKUExpiredDate()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$sku_id = $this->input->post('sku_id');

		$data['ExpiredDate'] = $this->M_SuratTugasPengiriman->Get_SKU_Expired_Date($sku_id);

		echo json_encode($data);
	}

	public function GetJenisPallet()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$data['JenisPallet'] = $this->M_SuratTugasPengiriman->Get_JenisPallet();

		echo json_encode($data);
	}

	public function InsertPalletTemp()
	{
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$date_now = date('Y-m-d h:i:s');
		$param =  $this->input->post('pallet_jenis_id');

		$vrbl = "PALLET";
		$prefix = $vrbl;

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_SuratTugasPengiriman->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$pallet_kode = $generate_kode;
		$pallet_id = $this->M_SuratTugasPengiriman->Get_NEWID();

		$result = $this->M_SuratTugasPengiriman->Insert_PalletTemp($delivery_order_batch_id, $pallet_id, $pallet_kode);

		echo json_encode(array('pallet_id' => $pallet_id));
	}

	public function InsertPalletTemp2()
	{
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$pallet_id = $this->input->post('pallet_id');

		$this->M_SuratTugasPengiriman->Delete_PalletTemp2($pallet_id);
		$result = $this->M_SuratTugasPengiriman->Insert_PalletTemp2($delivery_order_batch_id, $pallet_id);

		echo json_encode(array('pallet_id' => $pallet_id));
	}

	public function UpdatePalletTemp()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_AutoGen');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$date_now = date('Y-m-d h:i:s');
		$param =  $this->input->post('pallet_jenis_id');

		$vrbl = $this->M_SuratTugasPengiriman->Get_KodePallet($param);
		$prefix = $vrbl;

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_SuratTugasPengiriman->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('pallet_id', 'Pallet ID', 'required');
		$this->form_validation->set_rules('pallet_jenis_id', 'Pallet Jenis ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$pallet_id = $this->input->post('pallet_id');
			$pallet_jenis_id = $this->input->post('pallet_jenis_id');
			$pallet_kode = $generate_kode;

			$result = $this->M_SuratTugasPengiriman->Update_PalletTemp($pallet_id, $pallet_jenis_id);

			echo $result;
		}
	}

	public function DeletePalletTemp()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$pallet_id = $this->input->post('pallet_id');
		$result = $this->M_SuratTugasPengiriman->Delete_PalletTemp($pallet_id);

		echo $result;
	}

	public function DeletePalletDetailTemp()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$pallet_detail_id = $this->input->post('pallet_detail_id');
		$result = $this->M_SuratTugasPengiriman->Delete_PalletDetailTemp($pallet_detail_id);

		echo $result;
	}

	public function InsertPalletDetailTemp()
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

		$this->form_validation->set_rules('pallet_id', 'Pallet ID', 'required');
		$this->form_validation->set_rules('sku_id', 'SKU ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$pallet_id = $this->input->post('pallet_id');
			$sku_id = $this->input->post('sku_id');
			$penerimaan_tipe_id = $this->input->post('penerimaan_tipe_id');
			$sku_stock_qty = 0;

			// $check_sku_id = $this->M_SuratTugasPengiriman->Check_SKUPallet($pallet_id, $sku_id);

			// if ($check_sku_id == 0) {

			// 	$result = $this->M_SuratTugasPengiriman->Insert_PalletDetailTemp($pallet_id, $sku_id, $sku_stock_qty, $penerimaan_tipe_id);

			// 	echo $result;
			// } else {
			// 	echo "2";
			// }

			$result = $this->M_SuratTugasPengiriman->Insert_PalletDetailTemp($pallet_id, $sku_id, $sku_stock_qty, $penerimaan_tipe_id);

			echo $result;
		}
	}

	public function UpdatePalletDetailTemp()
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

		$this->form_validation->set_rules('pallet_id', 'Pallet ID', 'required');
		$this->form_validation->set_rules('sku_id', 'SKU ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$pallet_id = $this->input->post('pallet_id');
			$pallet_detail_id = $this->input->post('pallet_detail_id');
			$sku_stock_id = $this->input->post('sku_stock_id');
			$sku_stock_expired_date = $this->input->post('sku_stock_expired_date');
			$sku_stock_qty = $this->input->post('sku_stock_qty');

			$result = $this->M_SuratTugasPengiriman->Update_PalletDetailTemp($pallet_id, $pallet_detail_id, $sku_stock_id, $sku_stock_expired_date, $sku_stock_qty);

			echo $result;
		}
	}

	public function DeletePalletPalletTemp()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$pallet_id = $this->input->post('pallet_id');
		$result = $this->M_SuratTugasPengiriman->Delete_PalletPalletTemp($pallet_id);

		echo $result;
	}

	public function GetPallet()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$data['Pallet'] = $this->M_SuratTugasPengiriman->Get_Pallet($delivery_order_batch_id);
		$data['JenisPallet'] = $this->M_SuratTugasPengiriman->Get_JenisPallet();

		echo json_encode($data);
	}

	public function GetPalletDetail()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$pallet_id = $this->input->post('pallet_id');
		$data['PalletDetail'] = $this->M_SuratTugasPengiriman->Get_PalletDetail($pallet_id);

		echo json_encode($data);
	}

	public function GetPalletDetailTable()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$pallet_id = $this->input->post('pallet_id');
		$data['PalletDetail'] = $this->M_SuratTugasPengiriman->Get_PalletDetailTable($pallet_id);

		echo json_encode($data);
	}

	public function UpdateQtySKUPalletTemp()
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

		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');
		$this->form_validation->set_rules('pallet_detail_id', 'Pallet Detail ID', 'required');
		$this->form_validation->set_rules('sku_id', 'SKU ID', 'required');
		$this->form_validation->set_rules('sku_stock_qty', 'SKU Stock', 'required');
		$this->form_validation->set_rules('penerimaan_tipe_nama', 'Penerimaan Tipe', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$pallet_detail_id = $this->input->post('pallet_detail_id');
			$sku_id = $this->input->post('sku_id');
			$sku_stock_qty = $this->input->post('sku_stock_qty');
			$penerimaan_tipe_nama = $this->input->post('penerimaan_tipe_nama');

			$set_qty = $this->M_SuratTugasPengiriman->Update_QtySKUPalletTemp($pallet_detail_id, 0);

			if ($penerimaan_tipe_nama == "titipan") {

				$result = $this->M_SuratTugasPengiriman->Update_QtySKUPalletTemp($pallet_detail_id, $sku_stock_qty);
				echo $result;
			} else {

				$check_qty = $this->M_SuratTugasPengiriman->Check_QtySKUPalletTemp($delivery_order_batch_id, $sku_id, $sku_stock_qty, $penerimaan_tipe_nama);
				if ($check_qty >= 0) {

					$result = $this->M_SuratTugasPengiriman->Update_QtySKUPalletTemp($pallet_detail_id, $sku_stock_qty);

					echo $result;
				} else {
					echo "2";
				}
			}
		}
	}

	public function UpdateSkuExpDatePalletTemp()
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

		$this->form_validation->set_rules('pallet_detail_id', 'Pallet Detail ID', 'required');
		// $this->form_validation->set_rules('sku_stock_id', 'SKU Stock ID', 'required');
		$this->form_validation->set_rules('sku_id', 'SKU ID', 'required');
		$this->form_validation->set_rules('sku_stock_expired_date', 'SKU Stock Date', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$pallet_detail_id = $this->input->post('pallet_detail_id');
			// $sku_stock_id = $this->input->post('sku_stock_id');
			$sku_id = $this->input->post('sku_id');
			$depo_detail_id = $this->input->post('depo_detail_id');
			$sku_stock_expired_date = date('Y-m-d', strtotime($this->input->post('sku_stock_expired_date')));

			$result = $this->M_SuratTugasPengiriman->Update_SkuExpDatePalletTemp($pallet_detail_id, $sku_id, $sku_stock_expired_date, $depo_detail_id);
			// $result = $this->M_SuratTugasPengiriman->Update_SkuExpDatePalletTemp($pallet_detail_id, $sku_stock_id, $sku_stock_expired_date);

			echo $result;
		}
	}

	public function InsertPalletDetailTempDoRetur()
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

		$this->form_validation->set_rules('pallet_id', 'Pallet ID', 'required');
		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$pallet_id = $this->input->post('pallet_id');

			$result = $this->M_SuratTugasPengiriman->Insert_PalletDetailTempDoRetur($delivery_order_batch_id, $pallet_id);

			echo $result;
		}
	}

	public function InsertPalletDetailTempTerkirimSebagian()
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

		$this->form_validation->set_rules('pallet_id', 'Pallet ID', 'required');
		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$pallet_id = $this->input->post('pallet_id');

			$result = $this->M_SuratTugasPengiriman->Insert_PalletDetailTempTerkirimSebagian($delivery_order_batch_id, $pallet_id);

			echo $result;
		}
	}

	public function InsertPalletDetailTempGagal()
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

		$this->form_validation->set_rules('pallet_id', 'Pallet ID', 'required');
		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$pallet_id = $this->input->post('pallet_id');

			$result = $this->M_SuratTugasPengiriman->Insert_PalletDetailTempGagal($delivery_order_batch_id, $pallet_id);

			echo $result;
		}
	}

	public function InsertPenerimaanPenjualan()
	{
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
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

		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');
		// $this->form_validation->set_rules('depo_detail_id', '<span name="CAPTION-GUDANGPENERIMA">Gudang Penerima</span>', 'required');
		$this->form_validation->set_rules('karyawan_id', 'Checker', 'required');
		// $this->form_validation->set_rules('penerimaan_tipe_id', '<span name="CAPTION-TIPEPENERIMAAN">Penerimaan Tipe</span>', 'required');
		$this->form_validation->set_rules('client_wms_id', '<span name="CAPTION-PERUSAHAAN">Perusahaan</span>', 'required');
		$this->form_validation->set_rules('principle_id', '<span name="CAPTION-PRINCIPLE">Principle</span>', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$date_now = date('Y-m-d h:i:s');
			$param =  'KODE_BTB_JUAL';

			$vrbl = $this->M_Vrbl->Get_Kode($param);
			$prefix = $vrbl->vrbl_kode;

			// get prefik depo
			$depo_id = $this->session->userdata('depo_id');
			$depoPrefix = $this->M_SuratTugasPengiriman->getDepoPrefix($depo_id);
			$unit = $depoPrefix->depo_kode_preffix;
			$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

			$penerimaan_penjualan_kode = $generate_kode;
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			// $depo_detail_id = $this->input->post('depo_detail_id');
			$karyawan_id = $this->input->post('karyawan_id');
			// $penerimaan_tipe_id = $this->input->post('penerimaan_tipe_id');
			// $penerimaan_tipe = $this->input->post('penerimaan_tipe');
			$penerimaan_penjualan_keterangan = $this->input->post('penerimaan_penjualan_keterangan');
			$client_wms_id = $this->input->post('client_wms_id');
			$principle_id = $this->input->post('principle_id');
			$arr_penerimaan_tipe = $this->input->post('arr_penerimaan_tipe');
			$kondisiBarang = $this->input->post('kondisiBarang');

			$check_btb = $this->M_SuratTugasPengiriman->Check_PenerimaanPenjualan($penerimaan_penjualan_kode);
			$check_sku_id = $this->M_SuratTugasPengiriman->Check_SKUBTB($delivery_order_batch_id);
			$pallet_temp = $this->M_SuratTugasPengiriman->get_pallet_temp($delivery_order_batch_id);
			$pallet_detail_temp = $this->M_SuratTugasPengiriman->get_pallet_detail_temp($delivery_order_batch_id);

			$check_sku_counter = 0;
			$jumlah_arr = 0;

			if ($check_sku_id == 0) {
				$jumlah_arr = 0;
			} else {
				$jumlah_arr = count($check_sku_id);
				foreach ($check_sku_id as $row) {
					if ($row['check_sku'] == 1) {
						$check_sku_counter += 1;
					}
				}
			}

			if ($check_sku_counter == $jumlah_arr) {
				if ($check_btb == 0) {
					$check_sku_qty_pallet = $this->M_SuratTugasPengiriman->Check_QtySKUPallet($delivery_order_batch_id);
					if ($check_sku_qty_pallet > 0) {

						$this->db->trans_begin();

						foreach ($arr_penerimaan_tipe as $key => $value) {
							$get_penerimaan_tipe = $this->M_SuratTugasPengiriman->Get_PenerimaanTipe($delivery_order_batch_id, $value['penerimaan_tipe_id']);
							// echo var_dump($get_penerimaan_tipe);
							// echo "<br>";

							if ($get_penerimaan_tipe != 0) {
								foreach ($get_penerimaan_tipe as $key => $row) {
									$penerimaan_tipe_id = $row['penerimaan_tipe_id'];
									$penerimaan_tipe_nama = $row['penerimaan_tipe_nama'];
									$depo_detail_id = $value['depo_detail_id'];

									// echo $penerimaan_tipe_id . "<br>";

									$penerimaan_penjualan_id = $this->M_SuratTugasPengiriman->Get_NEWID();
									$this->M_SuratTugasPengiriman->Insert_PenerimaanPenjualan($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $delivery_order_batch_id, $depo_detail_id, $karyawan_id, $penerimaan_tipe_id, $penerimaan_tipe_nama, $penerimaan_penjualan_keterangan, $client_wms_id, $principle_id);
									$this->M_SuratTugasPengiriman->Update_StockSKU($delivery_order_batch_id, $depo_detail_id, $penerimaan_tipe_id);
								}
							}
						}

						foreach ($pallet_temp as $key => $value) {
							$check_pallet = $this->M_SuratTugasPengiriman->CheckPalletById($value['pallet_id']);
							if ($check_pallet == 0) {
								$this->M_SuratTugasPengiriman->Insert_Pallet($value);
							}
						}

						foreach ($pallet_detail_temp as $key => $value) {
							$check_pallet = $this->M_SuratTugasPengiriman->CheckPalletDetailById($value['pallet_id'], $value['sku_id'], $value['sku_stock_id']);
							if ($check_pallet == "0") {
								$this->M_SuratTugasPengiriman->Insert_PalletDetail($value);
							} else {
								$this->M_SuratTugasPengiriman->Update_PalletDetail($value['pallet_id'], $value['sku_id'], $value['sku_stock_id'], $value);
							}
						}

						$this->M_SuratTugasPengiriman->Delete_PalletDetail($delivery_order_batch_id);
						$this->M_SuratTugasPengiriman->Delete_Pallet($delivery_order_batch_id);

						if ($this->db->trans_status() === FALSE) {
							$this->db->trans_rollback();
							echo json_encode(0);
						} else {
							$this->db->trans_commit();
							echo json_encode(1);
						}
					} else {
						echo json_encode(3);
					}
				} else {
					echo json_encode(2);
				}
			} else {
				echo json_encode(5);
			}
		}
	}

	public function InsertPallet()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$pallet_temp = $this->M_SuratTugasPengiriman->get_pallet_temp($delivery_order_batch_id);
		$pallet_detail_temp = $this->M_SuratTugasPengiriman->get_pallet_detail_temp($delivery_order_batch_id);

		// echo var_dump($pallet_temp);

		$this->db->trans_begin();

		foreach ($pallet_temp as $key => $value) {
			$check_pallet = $this->M_SuratTugasPengiriman->CheckPalletById($value['pallet_id']);
			if ($check_pallet == 0) {
				$this->M_SuratTugasPengiriman->Insert_Pallet($value);
			}
		}

		foreach ($pallet_detail_temp as $key => $value) {
			$check_pallet = $this->M_SuratTugasPengiriman->CheckPalletDetailById($value['pallet_id'], $value['sku_id'], $value['sku_stock_id']);
			if ($check_pallet == "0") {
				$this->M_SuratTugasPengiriman->Insert_PalletDetail($value);
			} else {
				$this->M_SuratTugasPengiriman->Update_PalletDetail($value['pallet_id'], $value['sku_id'], $value['sku_stock_id'], $value);
			}
		}

		$this->M_SuratTugasPengiriman->Delete_PalletDetail($delivery_order_batch_id);

		$this->M_SuratTugasPengiriman->Delete_Pallet($delivery_order_batch_id);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function CheckPenerimaanPenjualanByDo()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$delivery_order_id = $this->input->post('delivery_order_id');

		$check_btb = $this->M_SuratTugasPengiriman->Check_PenerimaanPenjualanByDo($delivery_order_batch_id, $delivery_order_id);

		echo $check_btb;
		// echo json_encode($check_btb);
	}

	public function UpdateClosingPengirimanFDJR()
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

		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');
		$this->form_validation->set_rules('delivery_order_batch_status', 'Delivery Order Batch Status', 'required');
		// $this->form_validation->set_rules('kendaraan_km_terpakai', 'kendaraan km terpakai', 'required');
		// $this->form_validation->set_rules('kendaraan_km_akhir', 'kendaraan km akhir', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$delivery_order_batch_status = $this->input->post('delivery_order_batch_status');
			$kendaraan_km_akhir = $this->input->post('kendaraan_km_akhir');
			$kendaraan_km_terpakai = $this->input->post('kendaraan_km_terpakai');
			$delivery_order_batch_update_tgl = $this->input->post('delivery_order_batch_update_tgl');
			$arr_do_list = $this->input->post('arr_do_list');

			$lastUpdatedChecked = checkLastUpdatedData((object) [
				'table' => "delivery_order_batch",
				'whereField' => "delivery_order_batch_id",
				'whereValue' => $delivery_order_batch_id,
				'fieldDateUpdate' => "delivery_order_batch_update_tgl",
				'fieldWhoUpdate' => "delivery_order_batch_update_who",
				'lastUpdated' => $delivery_order_batch_update_tgl
			]);

			if ($lastUpdatedChecked['status'] == 400) {
				echo json_encode(2);
				return false;
			}

			$this->db->trans_begin();

			$result = $this->M_SuratTugasPengiriman->Update_ClosingPengirimanFDJR($delivery_order_batch_id, $delivery_order_batch_status, $kendaraan_km_akhir, $kendaraan_km_terpakai);

			foreach ($arr_do_list as $key => $value) {
				$delivery_order_id = $value['delivery_order_id'];
				$delivery_order_batch_id = $value['delivery_order_batch_id'];
				$status_progress_id = $value['status_progress_id'];
				$status_progress_nama = $value['status_progress_nama'];
				$delivery_order_batch_status = $value['delivery_order_batch_status'];
				$reason_id = $value['reason_id'];
				// $kendaraan_km_akhir = $value['kendaraan_km_akhir'];
				$kendaraan_km_terpakai = $value['kendaraan_km_terpakai'];
				$yourref = $value['yourref'];
				$delivery_order_jumlah_bayar = $value['nominal_tunai'];
				$delivery_order_is_paid = $value['is_paid'];
				$delivery_order_tipe_pembayaran = $value['delivery_order_tipe_pembayaran'];

				$this->M_SuratTugasPengiriman->Insert_DeliveryOrderProgressStatus($delivery_order_id, $status_progress_id, $status_progress_nama);
			}

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo json_encode(0);
			} else {
				$this->db->trans_commit();
				echo json_encode(1);
			}

			// echo $result;
		}
	}

	public function CheckStatusPenerimaanPenjualan()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');

		$check_btb = $this->M_SuratTugasPengiriman->Check_StatusPenerimaanPenjualan($delivery_order_batch_id);

		echo $check_btb;
		// echo json_encode($check_btb);
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

			$result = $this->M_SuratTugasPengiriman->Update_DOSkuQty($delivery_order_id, $sku_id, $sku_qty);

			echo $result;
		}
	}

	public function GetPerusahaan()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$data['Perusahaan'] = $this->M_SuratTugasPengiriman->Get_Perusahaan();

		echo json_encode($data);
	}

	public function GetCustomer()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$client_wms_id = $this->input->post('client_wms_id');
		$client_pt_nama = $this->input->post('client_pt_nama');
		$client_pt_alamat = $this->input->post('client_pt_alamat');
		$client_pt_telepon = $this->input->post('client_pt_telepon');
		$area_id = $this->input->post('area_id');

		$data['Customer'] = $this->M_SuratTugasPengiriman->Get_Customer($client_wms_id, $client_pt_nama, $client_pt_alamat, $client_pt_telepon, $area_id);

		echo json_encode($data);
	}

	public function GetDeliveryOrderReturMenu()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$data['Perusahaan'] = $this->M_SuratTugasPengiriman->Get_Perusahaan();

		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = "SuratTugasPengiriman/SuratTugasPengirimanMenu";
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetDataPerusahaan()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$client_wms_id = $this->input->post('client_wms_id');

		$data['Perusahaan'] = $this->M_SuratTugasPengiriman->Get_PerusahaanById($client_wms_id);

		echo json_encode($data);
	}

	public function InsertDeliveryOrderDetailTemp()
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

		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');
		$this->form_validation->set_rules('sku_id', 'SKU ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$sku_id = $this->input->post('sku_id');
			$depo_id = $this->session->userdata('depo_id');
			$depo_detail_id = $this->input->post('depo_detail_id');
			$sku_kode = $this->input->post('sku_kode');
			$sku_nama_produk = $this->input->post('sku_nama_produk');
			$sku_harga_satuan = $this->input->post('sku_harga_satuan');
			$sku_disc_percent = $this->input->post('sku_disc_percent');
			$sku_disc_rp = $this->input->post('sku_disc_rp');
			$sku_harga_nett = $this->input->post('sku_harga_nett');
			$sku_request_expdate = $this->input->post('sku_request_expdate');
			$sku_filter_expdate = $this->input->post('sku_filter_expdate');
			$sku_filter_expdatebulan = $this->input->post('sku_filter_expdatebulan');
			$sku_filter_expdatetahun = $this->input->post('sku_filter_expdatetahun');
			$sku_weight = $this->input->post('sku_weight');
			$sku_weight_unit = $this->input->post('sku_weight_unit');
			$sku_length = $this->input->post('sku_length');
			$sku_length_unit = $this->input->post('sku_length_unit');
			$sku_width = $this->input->post('sku_width');
			$sku_width_unit = $this->input->post('sku_width_unit');
			$sku_height = $this->input->post('sku_height');
			$sku_height_unit = $this->input->post('sku_height_unit');
			$sku_volume = $this->input->post('sku_volume');
			$sku_volume_unit = $this->input->post('sku_volume_unit');
			$sku_qty = $this->input->post('sku_qty');
			$sku_keterangan = $this->input->post('sku_keterangan');
			$sku_qty_kirim = $this->input->post('sku_qty_kirim');
			$reason_id = $this->input->post('reason_id');

			$check_sku_id = $this->M_SuratTugasPengiriman->Check_SKUDelivery($delivery_order_batch_id, $sku_id);

			if ($check_sku_id == 0) {
				$result = $this->M_SuratTugasPengiriman->Insert_DeliveryOrderDetailTemp($delivery_order_batch_id, $sku_id, $depo_id, $depo_detail_id, $sku_kode, $sku_nama_produk, $sku_harga_satuan, $sku_disc_percent, $sku_disc_rp, $sku_harga_nett, $sku_request_expdate, $sku_filter_expdate, $sku_filter_expdatebulan, $sku_filter_expdatetahun, $sku_weight, $sku_weight_unit, $sku_length, $sku_length_unit, $sku_width, $sku_width_unit, $sku_height, $sku_height_unit, $sku_volume, $sku_volume_unit, $sku_qty, $sku_keterangan, $sku_qty_kirim, $reason_id);
				echo $result;
			} else {
				echo "2";
			}

			// $result = $this->M_SuratTugasPengiriman->Insert_PalletDetailTemp($pallet_id, $sku_id, $sku_stock_qty, $penerimaan_tipe_id);

			// echo $result;
		}
	}

	public function UpdateReqExpDate()
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

		$this->form_validation->set_rules('delivery_order_detail_id', 'Delivery Order Detail ID', 'required');
		$this->form_validation->set_rules('sku_id', 'SKU ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_detail_id = $this->input->post('delivery_order_detail_id');
			$sku_id = $this->input->post('sku_id');
			$req_exp_date = $this->input->post('req_exp_date');

			$result = $this->M_SuratTugasPengiriman->Update_ReqExpDate($delivery_order_detail_id, $sku_id, $req_exp_date);

			echo $result;
		}
	}

	public function UpdateSKUKeterangan()
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

		$this->form_validation->set_rules('delivery_order_detail_id', 'Delivery Order Detail ID', 'required');
		$this->form_validation->set_rules('sku_id', 'SKU ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_detail_id = $this->input->post('delivery_order_detail_id');
			$sku_id = $this->input->post('sku_id');
			$sku_keterangan = $this->input->post('sku_keterangan');

			$result = $this->M_SuratTugasPengiriman->Update_SKUKeterangan($delivery_order_detail_id, $sku_id, $sku_keterangan);

			echo $result;
		}
	}

	public function UpdateSKUQty()
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

		$this->form_validation->set_rules('delivery_order_detail_id', 'Delivery Order Detail ID', 'required');
		$this->form_validation->set_rules('sku_id', 'SKU ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_detail_id = $this->input->post('delivery_order_detail_id');
			$sku_id = $this->input->post('sku_id');
			$sku_qty = $this->input->post('sku_qty');

			$result = $this->M_SuratTugasPengiriman->Update_SKUQty($delivery_order_detail_id, $sku_id, $sku_qty);

			echo $result;
		}
	}

	public function DeleteSKUDelivery()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$delivery_order_detail_id = $this->input->post('delivery_order_detail_id');

		$result = $this->M_SuratTugasPengiriman->Delete_SKUDelivery($delivery_order_detail_id);

		echo $result;
	}

	public function InsertDeliveryOrder()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$do_id = $this->M_Vrbl->Get_NewID();
		$do_id = $do_id[0]['NEW_ID'];

		//generate kode
		$date_now = date('Y-m-d h:i:s');
		$param = 'KODE_DO';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_SuratTugasPengiriman->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$sales_order_id = null;
		$delivery_order_kode = $generate_kode;
		$delivery_order_yourref = null;
		$client_wms_id = $this->input->post('client_wms_id');
		$delivery_order_tgl_buat_do = date('Y-m-d', strtotime($this->input->post('delivery_order_tgl_buat_do')));
		$delivery_order_tgl_expired_do = date('Y-m-d', strtotime($this->input->post('delivery_order_tgl_expired_do')));
		$delivery_order_tgl_surat_jalan = date('Y-m-d', strtotime($this->input->post('delivery_order_tgl_surat_jalan')));
		$delivery_order_tgl_rencana_kirim = date('Y-m-d', strtotime($this->input->post('delivery_order_tgl_rencana_kirim')));
		$delivery_order_tgl_aktual_kirim = date('Y-m-d', strtotime($this->input->post('delivery_order_tgl_aktual_kirim')));
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

		$this->db->trans_begin();

		//insert ke tr_pemusnahan_stok_draft
		$this->M_SuratTugasPengiriman->Insert_DeliveryOrder($do_id, $delivery_order_batch_id, $sales_order_id, $delivery_order_kode, $delivery_order_yourref, $client_wms_id, $delivery_order_tgl_buat_do, $delivery_order_tgl_expired_do, $delivery_order_tgl_surat_jalan, $delivery_order_tgl_rencana_kirim, $delivery_order_tgl_aktual_kirim, $delivery_order_keterangan, $delivery_order_status, $delivery_order_is_prioritas, $delivery_order_is_need_packing, $delivery_order_tipe_layanan, $delivery_order_tipe_pembayaran, $delivery_order_sesi_pengiriman, $delivery_order_request_tgl_kirim, $delivery_order_request_jam_kirim, $tipe_pengiriman_id, $nama_tipe, $confirm_rate, $delivery_order_reff_id, $delivery_order_reff_no, $delivery_order_total, $unit_mandiri_id, $depo_id, $client_pt_id, $delivery_order_kirim_nama, $delivery_order_kirim_alamat, $delivery_order_kirim_telp, $delivery_order_kirim_provinsi, $delivery_order_kirim_kota, $delivery_order_kirim_kecamatan, $delivery_order_kirim_kelurahan, $delivery_order_kirim_latitude, $delivery_order_kirim_longitude, $delivery_order_kirim_kodepos, $delivery_order_kirim_area, $delivery_order_kirim_invoice_pdf, $delivery_order_kirim_invoice_dir, $principle_id, $delivery_order_ambil_nama, $delivery_order_ambil_alamat, $delivery_order_ambil_telp, $delivery_order_ambil_provinsi, $delivery_order_ambil_kota, $delivery_order_ambil_kecamatan, $delivery_order_ambil_kelurahan, $delivery_order_ambil_latitude, $delivery_order_ambil_longitude, $delivery_order_ambil_kodepos, $delivery_order_ambil_area, $delivery_order_update_who, $delivery_order_update_tgl, $delivery_order_approve_who, $delivery_order_approve_tgl, $delivery_order_reject_who, $delivery_order_reject_tgl, $delivery_order_reject_reason, $delivery_order_no_urut_rute, $delivery_order_prioritas_stock, $tipe_delivery_order_id, $delivery_order_draft_id, $delivery_order_draft_kode);

		//insert ke tr_pemusnahan_stok_detail_draft
		foreach ($detail as $key => $value) {
			$dod_id = $this->M_Vrbl->Get_NewID();
			$dod_id = $dod_id[0]['NEW_ID'];
			$this->M_SuratTugasPengiriman->Insert_DeliveryOrderDetail($dod_id, $do_id, $value);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function InsertDeliveryOrderRetur()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');
		$this->form_validation->set_rules('client_wms_id', 'Perusahaan', 'required');
		$this->form_validation->set_rules('client_pt_id', 'Customer', 'required');
		$this->form_validation->set_rules('delivery_order_tipe_pembayaran', 'Tipe Pembayaran', 'required');
		$this->form_validation->set_rules('delivery_order_tipe_layanan', 'Tipe Layanan', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {

			$date_now = date('Y-m-d h:i:s');
			$param =  'KODE_DOR';

			$vrbl = $this->M_Vrbl->Get_Kode($param);
			$prefix = $vrbl->vrbl_kode;

			// get prefik depo
			$depo_id = $this->session->userdata('depo_id');
			$depoPrefix = $this->M_SuratTugasPengiriman->getDepoPrefix($depo_id);
			$unit = $depoPrefix->depo_kode_preffix;
			$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

			$delivery_order_id = $this->M_SuratTugasPengiriman->Get_NEWID();
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$sales_order_id = null;
			$delivery_order_kode = $generate_kode;
			$delivery_order_yourref = null;
			$client_wms_id = $this->input->post('client_wms_id');
			$delivery_order_tgl_buat_do = date('Y-m-d', strtotime($this->input->post('delivery_order_tgl_buat_do')));
			$delivery_order_tgl_expired_do = date('Y-m-d', strtotime($this->input->post('delivery_order_tgl_expired_do')));
			$delivery_order_tgl_surat_jalan = date('Y-m-d', strtotime($this->input->post('delivery_order_tgl_surat_jalan')));
			$delivery_order_tgl_rencana_kirim = date('Y-m-d', strtotime($this->input->post('delivery_order_tgl_rencana_kirim')));
			$delivery_order_tgl_aktual_kirim = date('Y-m-d', strtotime($this->input->post('delivery_order_tgl_aktual_kirim')));
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

			// $check_do_retur = $this->M_SuratTugasPengiriman->Check_DeliveryOrderRetur($delivery_order_kode);
			$check_do_retur = 0;

			if ($check_do_retur == 0) {
				$result = $this->M_SuratTugasPengiriman->Insert_DeliveryOrderRetur($delivery_order_id, $delivery_order_batch_id, $sales_order_id, $delivery_order_kode, $delivery_order_yourref, $client_wms_id, $delivery_order_tgl_buat_do, $delivery_order_tgl_expired_do, $delivery_order_tgl_surat_jalan, $delivery_order_tgl_rencana_kirim, $delivery_order_tgl_aktual_kirim, $delivery_order_keterangan, $delivery_order_status, $delivery_order_is_prioritas, $delivery_order_is_need_packing, $delivery_order_tipe_layanan, $delivery_order_tipe_pembayaran, $delivery_order_sesi_pengiriman, $delivery_order_request_tgl_kirim, $delivery_order_request_jam_kirim, $tipe_pengiriman_id, $nama_tipe, $confirm_rate, $delivery_order_reff_id, $delivery_order_reff_no, $delivery_order_total, $unit_mandiri_id, $depo_id, $client_pt_id, $delivery_order_kirim_nama, $delivery_order_kirim_alamat, $delivery_order_kirim_telp, $delivery_order_kirim_provinsi, $delivery_order_kirim_kota, $delivery_order_kirim_kecamatan, $delivery_order_kirim_kelurahan, $delivery_order_kirim_latitude, $delivery_order_kirim_longitude, $delivery_order_kirim_kodepos, $delivery_order_kirim_area, $delivery_order_kirim_invoice_pdf, $delivery_order_kirim_invoice_dir, $principle_id, $delivery_order_ambil_nama, $delivery_order_ambil_alamat, $delivery_order_ambil_telp, $delivery_order_ambil_provinsi, $delivery_order_ambil_kota, $delivery_order_ambil_kecamatan, $delivery_order_ambil_kelurahan, $delivery_order_ambil_latitude, $delivery_order_ambil_longitude, $delivery_order_ambil_kodepos, $delivery_order_ambil_area, $delivery_order_update_who, $delivery_order_update_tgl, $delivery_order_approve_who, $delivery_order_approve_tgl, $delivery_order_reject_who, $delivery_order_reject_tgl, $delivery_order_reject_reason, $delivery_order_no_urut_rute, $delivery_order_prioritas_stock, $tipe_delivery_order_id, $delivery_order_draft_id, $delivery_order_draft_kode);
				$result2 = $this->M_SuratTugasPengiriman->Insert_DeliveryOrderDetailRetur($delivery_order_id, $delivery_order_batch_id);
				$result3 = $this->M_SuratTugasPengiriman->Delete_DeliveryOrderReturDetailTemp($delivery_order_batch_id);
				echo $result;
			} else {
				echo "2";
			}
		}
	}

	public function DeleteDeliveryOrderReturDetailTemp()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');

		$result = $this->M_SuratTugasPengiriman->Delete_DeliveryOrderReturDetailTemp($delivery_order_batch_id);

		echo $result;
	}

	public function check_kode_pallet()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$kode_pallet = $this->input->post('kode_pallet');

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_SuratTugasPengiriman->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		$kode = $unit . "/" . $kode_pallet;

		$pallet_id = $this->M_SuratTugasPengiriman->check_kode_pallet($kode);
		if ($pallet_id == null) {
			echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan", 'pallet_id' => ""));
		} else {
			echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> <span class='text-success'>Valid</span>", 'pallet_id' => $pallet_id));
		}
	}

	public function GetPrincipleByPerusahaan()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$perusahaan = $this->input->post('perusahaan');

		$data = $this->M_SuratTugasPengiriman->GetPrincipleByPerusahaan($perusahaan);

		echo json_encode($data);
	}

	public function GetCheckerByPrinciple()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$perusahaan = $this->input->post('perusahaan');
		$principle = $this->input->post('principle');

		$data = $this->M_SuratTugasPengiriman->GetCheckerByPrinciple($perusahaan, $principle);

		echo json_encode($data);
	}

	public function get_pallet_by_arr_id()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		// $pallet_id = $this->input->post('pallet_id');

		$data = $this->M_SuratTugasPengiriman->get_pallet_by_arr_id($delivery_order_batch_id);

		echo json_encode($data);
	}

	public function check_sisa_btb_by_fdjr()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		// $pallet_id = $this->input->post('pallet_id');

		$data = $this->M_SuratTugasPengiriman->check_sisa_btb_by_fdjr($delivery_order_batch_id);

		echo json_encode($data);
	}

	public function GetPerusahaanById()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$id = $this->input->post('id');
		$data = $this->M_SuratTugasPengiriman->GetPerusahaanById($id);

		echo json_encode($data);
	}

	public function GetSelectedCustomer()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$customer = $this->input->post('customer');
		$perusahaan = $this->input->post('perusahaan');

		$data = $this->M_SuratTugasPengiriman->GetSelectedCustomer($customer, $perusahaan);

		echo json_encode($data);
	}

	public function GetSelectedPrinciple()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$customer = $this->input->post('customer');
		$perusahaan = $this->input->post('perusahaan');

		$data = $this->M_SuratTugasPengiriman->GetSelectedPrinciple($customer, $perusahaan);

		echo json_encode($data);
	}

	public function GetSelectedSKU()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$sku_id = $this->input->post('sku_id');

		$data = $this->M_SuratTugasPengiriman->GetSelectedSKU($sku_id);

		echo json_encode($data);
	}

	public function GetFactoryByTypePelayanan()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$perusahaan = $this->input->post('perusahaan');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$tipe_layanan = $this->input->post('tipe_layanan');
		$nama = $this->input->post('nama');
		$alamat = $this->input->post('alamat');
		$telp = $this->input->post('telp');
		$area = $this->input->post('area');

		$data = $this->M_SuratTugasPengiriman->GetFactoryByTypePelayanan($perusahaan, $tipe_pembayaran, $tipe_layanan, $nama, $alamat, $telp, $area);

		echo json_encode($data);
	}

	public function GetCustomerByTypePelayanan()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$perusahaan = $this->input->post('perusahaan');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$tipe_layanan = $this->input->post('tipe_layanan');
		$nama = $this->input->post('nama');
		$alamat = $this->input->post('alamat');
		$telp = $this->input->post('telp');
		$area = $this->input->post('area');

		$data = $this->M_SuratTugasPengiriman->GetCustomerByTypePelayanan($perusahaan, $tipe_pembayaran, $tipe_layanan, $nama, $alamat, $telp, $area);

		echo json_encode($data);
	}

	public function search_filter_chosen_sku()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$client_pt = $this->input->post('client_pt');
		$perusahaan = $this->input->post('perusahaan');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$brand = $this->input->post('brand');
		$principle = $this->input->post('principle');
		$sku_induk = $this->input->post('sku_induk');
		$sku_nama_produk = $this->input->post('sku_nama_produk');
		$sku_kemasan = $this->input->post('sku_kemasan');
		$sku_satuan = $this->input->post('sku_satuan');

		$data = $this->M_SuratTugasPengiriman->search_filter_chosen_sku($client_pt, $perusahaan, $tipe_pembayaran, $brand, $principle, $sku_induk, $sku_nama_produk, $sku_kemasan, $sku_satuan);

		echo json_encode($data);
	}

	public function search_filter_chosen_sku_by_pabrik()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');

		$client_pt = $this->input->post('client_pt');
		$perusahaan = $this->input->post('perusahaan');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$brand = $this->input->post('brand');
		$principle = $this->input->post('principle');
		$sku_induk = $this->input->post('sku_induk');
		$sku_nama_produk = $this->input->post('sku_nama_produk');
		$sku_kemasan = $this->input->post('sku_kemasan');
		$sku_satuan = $this->input->post('sku_satuan');

		$data = $this->M_SuratTugasPengiriman->search_filter_chosen_sku_by_pabrik($client_pt, $perusahaan, $tipe_pembayaran, $brand, $principle, $sku_induk, $sku_nama_produk, $sku_kemasan, $sku_satuan);

		echo json_encode($data);
	}

	public function insert_delivery_order()
	{
		$this->load->model('WMS/M_SuratTugasPengiriman', 'M_SuratTugasPengiriman');
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

		if ($tipe_delivery_order_id == "C5BE83E2-01E8-4E24-B766-26BB4158F2CD") {
			$detail2 = $this->input->post('detail2');

			foreach ($detail as $key => $value) {

				$getNameSKU = $this->M_DeliveryOrderDraft->getNameSKUById($client_wms_id, $value['sku_id']);
				// echo var_dump($detail2);

				if ($detail2 !== NULL) {
					$cek_total_sku_qty_detail2 = $this->M_DeliveryOrderDraft->cek_total_sku_qty_detail2($value['sku_id'], $detail2);

					foreach ($cek_total_sku_qty_detail2 as $key_cek_qty => $value_cek_qty) {

						if ($value['sku_qty'] != $value_cek_qty['sku_qty']) {
							array_push($cekQtyDoDetailDetail2, ['type' => '210', 'sku_kode' => $value_cek_qty['sku_kode'], 'sku_nama_produk' => $value_cek_qty['sku_nama_produk']]);
							$this->db->trans_rollback();
							echo json_encode($cekQtyDoDetailDetail2);
							die;
						}
					}
				} else {
					array_push($cekQtyDoDetailDetail2, ['type' => '210', 'sku_kode' => "", 'sku_nama_produk' => $getNameSKU->sku]);
					$this->db->trans_rollback();

					echo json_encode($cekQtyDoDetailDetail2);
					die;
				}
			}
		}

		$dod_id = $this->M_Vrbl->Get_NewID();
		$delivery_order_id = $dod_id[0]['NEW_ID'];

		//generate kode
		$param = "";
		if ($tipe_delivery_order_id == "C5BE83E2-01E8-4E24-B766-26BB4158F2CD") {
			$param =  'KODE_DOR';
			$status_progress_id = "BAF4066F-AAC8-4AF3-A019-DD0777366E50";
			$status_progress_nama = "retur";

			$this->M_SuratTugasPengiriman->Insert_DeliveryOrderProgressStatus($delivery_order_id, $status_progress_id, $status_progress_nama);
		} else {
			$param =  'KODE_DO';
		}
		$date_now = date('Y-m-d h:i:s');
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_SuratTugasPengiriman->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$delivery_order_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$this->db->trans_begin();

		//insert ke tr_pemusnahan_stok_draft
		$this->M_SuratTugasPengiriman->insert_delivery_order($delivery_order_id, $delivery_order_batch_id, $sales_order_id, $delivery_order_kode, $delivery_order_yourref, $client_wms_id, $delivery_order_tgl_buat_do, $delivery_order_tgl_expired_do, $delivery_order_tgl_surat_jalan, $delivery_order_tgl_rencana_kirim, $delivery_order_tgl_aktual_kirim, $delivery_order_keterangan, $delivery_order_status, $delivery_order_is_prioritas, $delivery_order_is_need_packing, $delivery_order_tipe_layanan, $delivery_order_tipe_pembayaran, $delivery_order_sesi_pengiriman, $delivery_order_request_tgl_kirim, $delivery_order_request_jam_kirim, $tipe_pengiriman_id, $nama_tipe, $confirm_rate, $delivery_order_reff_id, $delivery_order_reff_no, $delivery_order_total, $unit_mandiri_id, $depo_id, $client_pt_id, $delivery_order_kirim_nama, $delivery_order_kirim_alamat, $delivery_order_kirim_telp, $delivery_order_kirim_provinsi, $delivery_order_kirim_kota, $delivery_order_kirim_kecamatan, $delivery_order_kirim_kelurahan, $delivery_order_kirim_latitude, $delivery_order_kirim_longitude, $delivery_order_kirim_kodepos, $delivery_order_kirim_area, $delivery_order_kirim_invoice_pdf, $delivery_order_kirim_invoice_dir, $principle_id, $delivery_order_ambil_nama, $delivery_order_ambil_alamat, $delivery_order_ambil_telp, $delivery_order_ambil_provinsi, $delivery_order_ambil_kota, $delivery_order_ambil_kecamatan, $delivery_order_ambil_kelurahan, $delivery_order_ambil_latitude, $delivery_order_ambil_longitude, $delivery_order_ambil_kodepos, $delivery_order_ambil_area, $delivery_order_update_who, $delivery_order_update_tgl, $delivery_order_approve_who, $delivery_order_approve_tgl, $delivery_order_reject_who, $delivery_order_reject_tgl, $delivery_order_reject_reason, $delivery_order_no_urut_rute, $delivery_order_prioritas_stock, $tipe_delivery_order_id, $delivery_order_draft_id, $delivery_order_draft_kode);

		//insert ke tr_pemusnahan_stok_detail_draft
		// echo var_dump($detail);
		foreach ($detail as $key => $value) {
			$delivery_order_detail_id = $this->M_Vrbl->Get_NewID();
			$delivery_order_detail_id = $delivery_order_detail_id[0]['NEW_ID'];
			$this->M_SuratTugasPengiriman->insert_delivery_order_detail($delivery_order_detail_id, $delivery_order_id, $delivery_order_batch_id, $value);
			// $this->M_SuratTugasPengiriman->insert_delivery_order_detail2($delivery_order_detail_id, $delivery_order_id, $value);

			$do_detail2 = $this->M_DeliveryOrderDraft->get_do_detail2_sementara($value['sku_id'], $detail2);

			foreach ($do_detail2 as $key2 => $value2) {
				$dod2_id = $this->M_Vrbl->Get_NewID();
				$dod2_id = $dod2_id[0]['NEW_ID'];
				$this->M_SuratTugasPengiriman->insert_delivery_order_detail2($dod2_id, $delivery_order_detail_id, $delivery_order_id, $value['sku_id'], $value2['sku_stock_id'], $value2['sku_stock_expired_date'], $value2['sku_qty'], $value2['sku_konversi_faktor']);
			}
		}

		// $this->db->trans_rollback();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function GetDODetail3()
	{
		$do_id = $this->input->post('do_id');

		$data['GetDODetail3'] = $this->M_SuratTugasPengiriman->GetDODetail3($do_id);

		echo json_encode($data);
	}

	public function GetTipeBiaya()
	{
		$data['tipe_biaya'] = $this->M_SuratTugasPengiriman->GetTipeBiaya();

		echo json_encode($data);
	}

	public function deleteDODetail3ByID()
	{
		$id = $this->input->post('id');

		$this->M_SuratTugasPengiriman->deleteDODetail3($id);

		echo 1;
	}

	public function getKodeAutoComplete()
	{
		$valueParams = $this->input->get('params');
		$result = $this->M_SuratTugasPengiriman->getKodeAutoComplete($valueParams);
		echo json_encode($result);
	}

	public function CekDOKirimUlang()
	{
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');

		$data = $this->M_SuratTugasPengiriman->CekDOKirimUlang($delivery_order_batch_id);

		echo json_encode($data);
	}
}
