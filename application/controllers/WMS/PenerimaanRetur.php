<?php

//require_once APPPATH . 'core/ParentController.php';

class PenerimaanRetur extends CI_Controller
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

		$this->MenuKode = "135014000";
		$this->load->model('M_SKU');
		$this->load->model('M_Principle');
		$this->load->model('WMS/M_PenerimaanRetur', 'M_PenerimaanRetur');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Menu');

		$this->load->library(['form_validation', 'pdfgenerator']);
	}

	public function PenerimaanReturMenu()
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['Driver'] = $this->M_PenerimaanRetur->Get_Driver();
		$data['StatusFDJR'] = $this->M_PenerimaanRetur->Get_StatusFDJR();
		$data['act'] = "PenerimaanReturMenu";

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PenerimaanRetur/PenerimaanRetur', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PenerimaanRetur/S_PenerimaanRetur', $data);
	}

	public function ProsesBTBMenu()
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$delivery_order_batch_id = $this->input->get('delivery_order_batch_id');

		$data['Driver'] = $this->M_PenerimaanRetur->Get_Driver();
		$data['StatusFDJR'] = $this->M_PenerimaanRetur->Get_StatusFDJR();
		$data['FDJRHeader'] = $this->M_PenerimaanRetur->GetHeaderFDJRById($delivery_order_batch_id);
		$data['FDJRDetail'] = $this->M_PenerimaanRetur->GetDetailFDJRById($delivery_order_batch_id);
		$data['FDJRArea'] = $this->M_PenerimaanRetur->GetFDJRAreaById($delivery_order_batch_id);
		$data['SisaBTBFDJR'] = $this->M_PenerimaanRetur->check_sisa_btb_by_fdjr($delivery_order_batch_id);
		$data['act'] = "ProsesBTBMenu";

		foreach ($data['FDJRDetail'] as $row) {
			$arr_cek[] = $row['is_cek'];
		}

		$data['is_cek'] = array_unique($arr_cek);
		// var_dump($data['is_cek']);
		// die;

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PenerimaanRetur/BTBList', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PenerimaanRetur/S_PenerimaanRetur', $data);
	}

	public function filterIsBTB()
	{
		$delivery_order_batch_id = $this->input->post('id');
		$is_btb = $this->input->post('is_btb');
		$FDJRDetail = $this->M_PenerimaanRetur->GetDetailFDJRById2($delivery_order_batch_id, $is_btb);

		echo json_encode($FDJRDetail);
	}

	public function ProsesBTB()
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$delivery_order_batch_id = $this->input->get('delivery_order_batch_id');
		$delivery_order_id = $this->input->get('delivery_order_id');

		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PenerimaanRetur->getDepoPrefix($depo_id)->depo_kode_preffix;

		$data['delivery_order_batch_id'] = $delivery_order_batch_id;
		$data['delivery_order_id'] = $delivery_order_id;
		$data['Principle'] = $this->M_PenerimaanRetur->GetPrincipleByPerusahaan($delivery_order_id);
		$data['Checker'] = $this->M_PenerimaanRetur->GetCheckerByPrinciple();
		$data['Gudang'] = $this->M_PenerimaanRetur->GetGudang();
		$data['DOHeader'] = $this->M_PenerimaanRetur->GetHeaderDeliveryOrderById($delivery_order_id);
		$data['DODetail'] = $this->M_PenerimaanRetur->GetDetailDeliveryOrderById($delivery_order_id);
		$data['PalletGenerator'] = $this->M_PenerimaanRetur->GetPalletGenerator($depoPrefix);
		$data['act'] = "ProsesBTB";

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PenerimaanRetur/BTBForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PenerimaanRetur/S_PenerimaanRetur', $data);
	}

	public function ProsesBTBKiriman($id)
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

			Get_Assets_Url() . 'assets/css/custom/horiInsertPalletDetailTempTerkirimSebagianzontal-scroll.css',
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

		// $delivery_order_batch_id = $this->input->get('delivery_order_batch_id');
		$delivery_order = $this->M_PenerimaanRetur->getDeliveryOrderIDNotDelivered($id);
		$doIds = array_column($delivery_order, 'do_id');
		$data['do_id'] = "'" . implode("', '", $doIds) . "'";
		// $data['do_ids'] = implode(",", $doIds);
		$data['last_upd'] = $delivery_order[0]['delivery_order_batch_update_tgl'];


		$doKodes = array_column($delivery_order, 'do_kode');
		$data['do_kode'] = implode(", ", $doKodes);

		$noSOs = array_column($delivery_order, 'no_so');
		$data['no_so'] = implode(", ", $noSOs);

		$doStatus = array_column($delivery_order, 'do_status');
		// $data['do_status'] = implode(", ", array_unique($doStatus));
		$data['do_status'] = implode(",", $doStatus);

		$doTipeNama = array_column($delivery_order, 'tipe_delivery_order_nama');
		$data['do_tipe_nama'] = implode(", ", $doTipeNama);

		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PenerimaanRetur->getDepoPrefix($depo_id)->depo_kode_preffix;

		$data['delivery_order_batch_id'] = $id;
		$data['act'] = "ProsesBTBKiriman";
		// echo json_encode($data['act']);
		// die;
		// $delivery_order = $delivery_order_id;
		// $data['Principle'] = $this->M_PenerimaanRetur->GetPrincipleByPerusahaan($delivery_order_id);
		$data['Checker'] = $this->M_PenerimaanRetur->GetCheckerByPrinciple();
		$data['Gudang'] = $this->M_PenerimaanRetur->GetGudang();
		$data['DOHeader'] = $this->M_PenerimaanRetur->GetHeaderDeliveryOrderById($delivery_order[0]['do_id']);
		$data['DODetail'] = $this->M_PenerimaanRetur->GetDetailDOBatchById($data['do_id'], $data['act']);

		$data['PalletGenerator'] = $this->M_PenerimaanRetur->GetPalletGenerator($depoPrefix);

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PenerimaanRetur/BTBKiriman', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PenerimaanRetur/S_PenerimaanRetur', $data);
	}

	public function ProsesBTBRetur($id)
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// $delivery_order_batch_id = $this->input->get('delivery_order_batch_id');
		$delivery_order = $this->M_PenerimaanRetur->getDeliveryOrderIDRetur($id);
		$doIds = array_column($delivery_order, 'do_id');
		$data['do_id'] = "'" . implode("', '", $doIds) . "'";
		$data['last_upd'] = $delivery_order[0]['delivery_order_batch_update_tgl'];

		$doKodes = array_column($delivery_order, 'do_kode');
		$data['do_kode'] = implode(", ", $doKodes);

		$noSOs = array_column($delivery_order, 'no_so');
		$data['no_so'] = implode(", ", $noSOs);

		$doStatus = array_column($delivery_order, 'do_status');
		// $data['do_status'] = implode(", ", array_unique($doStatus));
		$data['do_status'] = implode(",", $doStatus);

		$doTipeNama = array_column($delivery_order, 'tipe_delivery_order_nama');
		$data['do_tipe_nama'] = implode(", ", $doTipeNama);

		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PenerimaanRetur->getDepoPrefix($depo_id)->depo_kode_preffix;

		$data['delivery_order_batch_id'] = $id;
		$data['act'] = "ProsesBTBRetur";
		// $delivery_order = $delivery_order_id;
		// $data['Principle'] = $this->M_PenerimaanRetur->GetPrincipleByPerusahaan($delivery_order_id);
		$data['Checker'] = $this->M_PenerimaanRetur->GetCheckerByPrinciple();
		$data['Gudang'] = $this->M_PenerimaanRetur->GetGudangQA();
		$data['DOHeader'] = $this->M_PenerimaanRetur->GetHeaderDeliveryOrderById($delivery_order[0]['do_id']);
		$data['DODetail'] = $this->M_PenerimaanRetur->GetDetailDOBatchById($data['do_id'], $data['act']);

		// echo json_encode($delivery_order);
		// die;
		$data['PalletGenerator'] = $this->M_PenerimaanRetur->GetPalletGenerator($depoPrefix);

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PenerimaanRetur/BTBRetur', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PenerimaanRetur/S_PenerimaanRetur', $data);
	}

	public function BTBDetail()
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$delivery_order_batch_id = $this->input->get('delivery_order_batch_id');
		$delivery_order_id = $this->input->get('delivery_order_id');

		$penerimaan_penjualan_id = $this->M_PenerimaanRetur->Check_PenerimaanPenjualan($delivery_order_batch_id);

		$data['act'] = "BTBDetail";
		$data['delivery_order_batch_id'] = $delivery_order_batch_id;
		$data['delivery_order_id'] = $delivery_order_id;
		$data['Principle'] = $this->M_PenerimaanRetur->GetPrincipleByPenerimaanPenjualan($delivery_order_batch_id);
		$data['Gudang'] = $this->M_PenerimaanRetur->GetGudang();
		$data['DOHeader'] = $this->M_PenerimaanRetur->GetHeaderPenerimaanPenjualanByDOId($delivery_order_id);
		$data['DODetail'] = $this->M_PenerimaanRetur->GetDetailPenerimaanPenjualanByDOId($delivery_order_id);
		// var_dump($data['DODetail']);
		// die;
		$data['Pallet'] = $this->M_PenerimaanRetur->GetPalletPenerimaanPenjualanByDOId($delivery_order_id, $penerimaan_penjualan_id, $data['act']);

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PenerimaanRetur/BTBDetail', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PenerimaanRetur/S_PenerimaanRetur', $data);
	}

	public function ViewBTBKiriman($id)
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$delivery_order = $this->M_PenerimaanRetur->getDeliveryOrderIDNotDelivered($id);
		$doIds = array_column($delivery_order, 'do_id');
		$data['do_id'] = "'" . implode("', '", $doIds) . "'";


		$data['last_upd'] = $delivery_order[0]['delivery_order_batch_update_tgl'];

		$doKodes = array_column($delivery_order, 'do_kode');
		$data['do_kode'] = implode(", ", $doKodes);

		$dob = array_column($delivery_order, 'delivery_order_batch_id');
		$data['dob_id'] = implode(", ", $dob);

		$noSOs = array_column($delivery_order, 'no_so');
		$data['no_so'] = implode(", ", $noSOs);

		$doStatus = array_column($delivery_order, 'do_status');
		// $data['do_status'] = implode(", ", array_unique($doStatus));
		$data['do_status'] = implode(",", $doStatus);

		$doTipeNama = array_column($delivery_order, 'tipe_delivery_order_nama');
		$data['do_tipe_nama'] = implode(", ", $doTipeNama);

		$penerimaan_penjualan_id = $this->M_PenerimaanRetur->Check_PenerimaanPenjualan($id);

		// // $data['delivery_order_batch_id'] = $delivery_order_batch_id;
		// $data['Principle'] = $this->M_PenerimaanRetur->GetPrincipleByPenerimaanPenjualan($id);
		$data['act'] = "ViewBTBKiriman";
		$data['Gudang'] = $this->M_PenerimaanRetur->GetGudang();
		$data['DOHeader'] = $this->M_PenerimaanRetur->GetHeaderPenerimaanPenjualanByDOId($delivery_order[0]['do_id']);
		$data['DODetail'] = $this->M_PenerimaanRetur->GetDetailDOBatchById($data['do_id'], $data['act']);
		$data['Pallet'] = $this->M_PenerimaanRetur->GetPalletPenerimaanPenjualanByDOId($data['do_id'], $penerimaan_penjualan_id, $data['act']);
		// print_r($data['Pallet']);
		// die;

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PenerimaanRetur/BTBDetailKiriman', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PenerimaanRetur/S_PenerimaanRetur', $data);
	}

	public function search_fdjr_by_filter()
	{
		$tgl = explode(" - ", $this->input->post('Tgl_FDJR'));
		$Tgl_FDJR = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$Tgl_FDJR2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$No_FDJR = $this->input->post('No_FDJR');
		$karyawan_id = $this->input->post('karyawan_id');
		$Status_FDJR  = $this->input->post('Status_FDJR');

		$data = $this->M_PenerimaanRetur->search_fdjr_by_filter($Tgl_FDJR, $Tgl_FDJR2, $No_FDJR, $karyawan_id, $Status_FDJR);

		echo json_encode($data);
	}

	public function GetCheckerByPrinciple()
	{
		$perusahaan = $this->input->post('perusahaan');
		$principle = $this->input->post('principle');

		$data = $this->M_PenerimaanRetur->GetCheckerByPrinciple($perusahaan, $principle);

		echo json_encode($data);
	}

	public function get_pallet_by_arr_id()
	{
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		// $pallet_id = $this->input->post('pallet_id');

		$data = $this->M_PenerimaanRetur->get_pallet_by_arr_id($delivery_order_batch_id);

		echo json_encode($data);
	}

	public function InsertPalletTemp()
	{
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$date_now = date('Y-m-d h:i:s');
		$param =  $this->input->post('pallet_jenis_id');

		// $vrbl = "PALLET";
		$vrbl = $this->M_PenerimaanRetur->Get_pallet_jenis();
		$prefix = $vrbl;

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PenerimaanRetur->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$rak_lajur_detail_id = $this->input->post('rak_lajur_detail_id');
		$depo_detail_id = $this->input->post('depo_detail_id');

		$pallet_kode = $generate_kode;
		$pallet_id = $this->M_PenerimaanRetur->Get_NEWID();

		$result = $this->M_PenerimaanRetur->Insert_PalletTemp($delivery_order_batch_id, $pallet_id, $pallet_kode, $rak_lajur_detail_id, $depo_detail_id);

		echo json_encode(array('pallet_id' => $pallet_id));
	}

	public function InsertPalletTemp2()
	{

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$pallet_id = $this->input->post('pallet_id');
		$depo_detail_id = $this->input->post('depo_detail_id');

		$this->M_PenerimaanRetur->Delete_PalletTemp2($pallet_id);
		$result = $this->M_PenerimaanRetur->Insert_PalletTemp2($delivery_order_batch_id, $pallet_id, $depo_detail_id);

		echo json_encode(array('pallet_id' => $pallet_id));
	}

	public function GetPalletDetail()
	{
		$pallet_id = $this->input->post('pallet_id');
		$data = $this->M_PenerimaanRetur->Get_PalletDetail($pallet_id);

		echo json_encode($data);
	}

	public function GetPalletDetail2()
	{
		$penerimaan_penjualan_id = $this->input->post('penerimaan_penjualan_id');
		$delivery_order_id = $this->input->post('delivery_order_id');
		$pallet_id = $this->input->post('pallet_id');

		$data = $this->M_PenerimaanRetur->Get_PalletDetail2($penerimaan_penjualan_id, $delivery_order_id, $pallet_id);

		echo json_encode($data);
	}

	public function GetSKUExpiredDate()
	{
		$sku_id = $this->input->post('sku_id');

		$data = $this->M_PenerimaanRetur->Get_SKU_Expired_Date($sku_id);

		echo json_encode($data);
	}

	public function InsertPalletDetailTempDoRetur()
	{
		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('pallet_id', 'Pallet ID', 'required');
		$this->form_validation->set_rules('delivery_order_id', 'Delivery Order Batch ID', 'required');
		// $this->form_validation->set_rules('client_wms_id', 'Client WMS ID', 'required');
		$this->form_validation->set_rules('depo_detail_id', 'Depo Detail ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_ids = explode(',', $this->input->post('delivery_order_id'));
			$pallet_id = $this->input->post('pallet_id');
			$client_wms_id = $this->input->post('client_wms_id');
			$depo_detail_id = $this->input->post('depo_detail_id');
			$act = $this->input->post('act');
			$this->M_PenerimaanRetur->delPalletDetailTemp();

			$result = $this->M_PenerimaanRetur->Insert_PalletDetailTempDoRetur($delivery_order_ids, $pallet_id, $client_wms_id, $depo_detail_id, $act);

			echo $result;
		}
	}

	public function InsertPalletDetailTempTerkirimSebagian()
	{
		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('pallet_id', 'Pallet ID', 'required');
		$this->form_validation->set_rules('delivery_order_id', 'Delivery Order Batch ID', 'required');
		$this->form_validation->set_rules('client_wms_id', 'Client WMS ID', 'required');
		$this->form_validation->set_rules('depo_detail_id', 'Depo Detail ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_id = $this->input->post('delivery_order_id');
			$pallet_id = $this->input->post('pallet_id');
			$client_wms_id = $this->input->post('client_wms_id');
			$depo_detail_id = $this->input->post('depo_detail_id');
			$act = $this->input->post('act');

			// var_dump("part", $delivery_order_id);
			// die;

			$result = $this->M_PenerimaanRetur->Insert_PalletDetailTempTerkirimSebagian($delivery_order_id, $pallet_id, $client_wms_id, $depo_detail_id, $act);

			echo $result;
		}
	}

	public function InsertPalletDetailTempGagal()
	{
		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('pallet_id', 'Pallet ID', 'required');
		$this->form_validation->set_rules('delivery_order_id', 'Delivery Order Batch ID', 'required');
		// $this->form_validation->set_rules('client_wms_id', 'Client WMS ID', 'required');
		$this->form_validation->set_rules('depo_detail_id', 'Depo Detail ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_batch_id = $this->input->post('do_batch_id');
			$delivery_order_ids = explode(',', $this->input->post('delivery_order_id'));
			$pallet_id = $this->input->post('pallet_id');
			$client_wms_id = $this->input->post('client_wms_id');
			$depo_detail_id = $this->input->post('depo_detail_id');
			$act = $this->input->post('act');
			// var_dump($delivery_order_ids);
			// die;
			$this->M_PenerimaanRetur->delPalletDetailTemp();

			$result = $this->M_PenerimaanRetur->Insert_PalletDetailTempGagal($delivery_order_ids, $pallet_id, $client_wms_id, $depo_detail_id, $act);

			echo $result;
		}
	}

	public function InsertPalletDetailTempRescheduled()
	{
		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('pallet_id', 'Pallet ID', 'required');
		$this->form_validation->set_rules('delivery_order_id', 'Delivery Order Batch ID', 'required');
		// $this->form_validation->set_rules('client_wms_id', 'Client WMS ID', 'required');
		$this->form_validation->set_rules('depo_detail_id', 'Depo Detail ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_id = $this->input->post('delivery_order_id');
			$pallet_id = $this->input->post('pallet_id');
			$client_wms_id = $this->input->post('client_wms_id');
			$depo_detail_id = $this->input->post('depo_detail_id');;

			$result = $this->M_PenerimaanRetur->Insert_PalletDetailTempRescheduled($delivery_order_id, $pallet_id, $client_wms_id, $depo_detail_id);

			echo $result;
		}
	}

	public function DeletePalletTemp()
	{
		$this->load->model('WMS/M_PenerimaanRetur', 'M_PenerimaanRetur');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$pallet_id = $this->input->post('pallet_id');
		$result = $this->M_PenerimaanRetur->Delete_PalletTemp($pallet_id);

		echo $result;
	}

	public function DeletePalletDetailTemp()
	{
		$this->load->model('WMS/M_PenerimaanRetur', 'M_PenerimaanRetur');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$pallet_detail_id = $this->input->post('pallet_detail_id');
		$result = $this->M_PenerimaanRetur->Delete_PalletDetailTemp($pallet_detail_id);

		echo $result;
	}

	public function GetPallet()
	{
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$data['Pallet'] = $this->M_PenerimaanRetur->Get_Pallet($delivery_order_batch_id);
		$data['JenisPallet'] = $this->M_PenerimaanRetur->Get_JenisPallet();

		echo json_encode($data);
	}

	public function check_kode_pallet()
	{
		$kode_pallet = $this->input->post('kode_pallet');
		$depo_detail_id = $this->input->post('depo_detail_id');

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PenerimaanRetur->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		$kode = $unit . "/" . $kode_pallet;

		$pallet_id = $this->M_PenerimaanRetur->check_kode_pallet($kode, $depo_detail_id);
		if ($pallet_id == null) {
			echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan", 'pallet_id' => ""));
		} else {
			echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> <span class='text-success'>Valid</span>", 'pallet_id' => $pallet_id));
		}
	}

	public function UpdateSkuExpDatePalletTemp()
	{
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

			$result = $this->M_PenerimaanRetur->Update_SkuExpDatePalletTemp($pallet_detail_id, $sku_id, $sku_stock_expired_date, $depo_detail_id);
			// $result = $this->M_PenerimaanRetur->Update_SkuExpDatePalletTemp($pallet_detail_id, $sku_stock_id, $sku_stock_expired_date);

			echo $result;
		}
	}

	public function InsertPenerimaanPenjualan()
	{
		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');
		$this->form_validation->set_rules('delivery_order_id', 'Delivery Order ID', 'required');
		$this->form_validation->set_rules('depo_detail_id', '<span name="CAPTION-GUDANGPENERIMA">Gudang Penerima</span>', 'required');
		$this->form_validation->set_rules('karyawan_id', 'Checker', 'required');
		// $this->form_validation->set_rules('penerimaan_tipe_id', '<span name="CAPTION-TIPEPENERIMAAN">Penerimaan Tipe</span>', 'required');
		// $this->form_validation->set_rules('client_wms_id', '<span name="CAPTION-PERUSAHAAN">Perusahaan</span>', 'required');
		// $this->form_validation->set_rules('principle_id', '<span name="CAPTION-PRINCIPLE">Principle</span>', 'required');

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
			$depoPrefix = $this->M_PenerimaanRetur->getDepoPrefix($depo_id);
			$unit = $depoPrefix->depo_kode_preffix;
			$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

			$penerimaan_penjualan_kode = $generate_kode;
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$delivery_order_id = $this->input->post('delivery_order_id');
			$depo_detail_id = $this->input->post('depo_detail_id');
			$karyawan_id = $this->input->post('karyawan_id');
			// $penerimaan_tipe_id = $this->input->post('penerimaan_tipe_id');
			// $penerimaan_tipe = $this->input->post('penerimaan_tipe');
			$penerimaan_penjualan_keterangan = $this->input->post('penerimaan_penjualan_keterangan');
			$client_wms_id = $this->input->post('client_wms_id');
			$principle_id = $this->input->post('principle_id');
			$arr_penerimaan_tipe = $this->input->post('arr_penerimaan_tipe');
			$kondisiBarang = $this->input->post('kondisiBarang');
			$lastUpdated = $this->input->post('lastUpdated');
			$arr_dodetail = $this->input->post('arr_dodetail');

			// $check_btb = $this->M_PenerimaanRetur->Check_PenerimaanPenjualan($penerimaan_penjualan_kode);
			$check_btb = $this->M_PenerimaanRetur->Check_PenerimaanPenjualan($delivery_order_batch_id);
			$check_sku_id = $this->M_PenerimaanRetur->Check_SKUBTB($delivery_order_batch_id);
			$act = $this->input->post('act');

			// var_dump($arr_penerimaan_tipe);
			// die;

			if ($act == 'ProsesBTBKiriman' || $act == 'ProsesBTBRetur') {
				$lastUpdatedChecked = checkLastUpdatedData((object) [
					'table' => "delivery_order_batch",
					'whereField' => "delivery_order_batch_id",
					'whereValue' => $delivery_order_batch_id,
					'fieldDateUpdate' => "delivery_order_batch_update_tgl",
					'fieldWhoUpdate' => "delivery_order_batch_update_who",
					'lastUpdated' => $lastUpdated
				]);
			} else {
				$lastUpdatedChecked = checkLastUpdatedData((object) [
					'table' => "delivery_order",
					'whereField' => "delivery_order_id",
					'whereValue' => $delivery_order_id,
					'fieldDateUpdate' => "delivery_order_update_tgl",
					'fieldWhoUpdate' => "delivery_order_update_who",
					'lastUpdated' => $lastUpdated
				]);
			}

			if ($lastUpdatedChecked['status'] == 400) {
				echo json_encode(2);
				return false;
			}

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

			// var_dump($arr_dodetail);
			// die;
			if ($act == 'ProsesBTBKiriman' || $act == 'ProsesBTBRetur') {
				if ($check_btb == "0") {
					$check_sku_qty_pallet = $this->M_PenerimaanRetur->Check_QtySKUPallet($delivery_order_batch_id);
					if ($check_sku_qty_pallet > 0) {

						$this->db->trans_begin();

						if ($act == 'ProsesBTBKiriman') {
							$penerimaan_tipe_id = '79F2522A-CEA5-4FF8-BC79-C0B28808877D';
							$penerimaan_tipe_nama2 = 'tidak terkirim';
						} else if ($act == 'ProsesBTBRetur') {
							$penerimaan_tipe_id = 'BDCFCBE1-52CF-404F-84B5-A19F0918CA8D';
							$penerimaan_tipe_nama2 = 'retur';
						}

						// INSERT PENERIMAAN PENJUALAN
						$penerimaan_penjualan_id = $this->M_PenerimaanRetur->Get_NEWID();
						$this->M_PenerimaanRetur->Insert_PenerimaanPenjualan($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $delivery_order_batch_id, $depo_detail_id, $karyawan_id, $penerimaan_tipe_id, $penerimaan_tipe_nama2, $penerimaan_penjualan_keterangan, $client_wms_id, $principle_id);

						// UPDATE STOK SKU
						$this->M_PenerimaanRetur->Update_StockSKU($delivery_order_batch_id, $depo_detail_id, $penerimaan_tipe_id, $client_wms_id);

						foreach ($arr_dodetail as $key => $value) {
							//INSERT PENERIMAAN PENJUALAN DETAIL
							$penerimaan_penjualan_detail_id = $this->M_PenerimaanRetur->Get_NEWID();
							$this->M_PenerimaanRetur->Insert_PenerimaanPenjualanDetailBatch($penerimaan_penjualan_detail_id, $penerimaan_penjualan_id, $delivery_order_id, $penerimaan_tipe_id, $value, $client_wms_id, $depo_detail_id);

							$data_sku_stock_by_pallet = $this->M_PenerimaanRetur->get_sku_stock_by_pallet($delivery_order_batch_id, $penerimaan_tipe_id);

							$pallet_fdjr = $this->M_PenerimaanRetur->get_data_pallet_by_fdjr($delivery_order_batch_id);

							foreach ($pallet_fdjr as $key2 => $value2) {
								$cek_pallet_penerimaan = $this->M_PenerimaanRetur->cek_pallet_penerimaan($penerimaan_penjualan_id, $value2['pallet_id']);

								if ($cek_pallet_penerimaan == 0) {
									// INSERT PENERIMAAN PENJUALAN DETAIL 2
									$this->M_PenerimaanRetur->Insert_PenerimaanPenjualanDetail2($penerimaan_penjualan_id, $value2);
								}
							}

							// $status_do = $this->M_PenerimaanRetur->cekStatusDOByDOID($delivery_order_id);

							// foreach ($status_do as $cek) {
							// 	$this->M_PenerimaanRetur->update_stock_delivery_order_detail($delivery_order_id, 0, 0, $value, $act, $cek['delivery_order_status']);
							// }

							// if ($penerimaan_tipe_id == "79F2522A-CEA5-4FF8-BC79-C0B28808877D" || $penerimaan_tipe_id = 'BDCFCBE1-52CF-404F-84B5-A19F0918CA8D') {
							// 	$this->M_PenerimaanRetur->update_stock_delivery_order_detail($delivery_order_id, 0, 0, $value, $act);
							// }

							// UPDATE sku_stock_id => PENERIMAAN PENJUALAN DETAIL
							$this->M_PenerimaanRetur->update_skuStockID_penerimaan_penjualan_detail($depo_detail_id, $penerimaan_penjualan_id);
						}

						//EXEC SKU STOCK CARD
						$this->M_PenerimaanRetur->exec_insert_sku_stock_card($penerimaan_penjualan_id, $this->session->userdata('pengguna_username'));

						$pallet_temp = $this->M_PenerimaanRetur->get_pallet_temp($delivery_order_batch_id);
						$pallet_detail_temp = $this->M_PenerimaanRetur->get_pallet_detail_temp($delivery_order_batch_id, $act);

						foreach ($pallet_temp as $key => $value) {
							$check_pallet = $this->M_PenerimaanRetur->CheckPalletById($value['pallet_id']);
							if ($check_pallet == "0") {
								$this->M_PenerimaanRetur->Insert_Pallet($value);
								$this->M_PenerimaanRetur->Insert_rak_lajur_detail_pallet($value);
								$this->M_PenerimaanRetur->Insert_rak_lajur_detail_pallet_his($depo_detail_id, $value);
							} else {
								$this->M_PenerimaanRetur->Update_Pallet($value);
								$this->M_PenerimaanRetur->Update_rak_lajur_detail_pallet($value);
								$this->M_PenerimaanRetur->Insert_rak_lajur_detail_pallet_his($depo_detail_id, $value);
							}
						}

						foreach ($pallet_detail_temp as $key => $value) {
							// $check_pallet = $this->M_PenerimaanRetur->CheckPalletDetailById($value['pallet_id'], $value['sku_id'], $value['penerimaan_tipe_id'], $value['sku_stock_expired_date']);

							$arr_sku_stock = $this->db->query("SELECT * FROM sku_stock WHERE sku_id = '" . $value['sku_id'] . "' AND sku_stock_expired_date = '" . $value['sku_stock_expired_date'] . "' AND depo_detail_id = '$depo_detail_id' ");

							$check_pallet_detail = $this->M_PenerimaanRetur->CheckPalletDetailById($value['pallet_id'], $value['sku_stock_id']);
							if ($check_pallet_detail == "0") {
								$this->M_PenerimaanRetur->Insert_PalletDetail($value);
							} else {
								$this->M_PenerimaanRetur->Update_PalletDetail($value['pallet_id'], $value['sku_id'], $value['sku_stock_id'], $value);
							}
						}

						$this->M_PenerimaanRetur->Delete_PalletDetail($delivery_order_batch_id);
						$this->M_PenerimaanRetur->Delete_Pallet($delivery_order_batch_id);

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
					$check_sku_qty_pallet = $this->M_PenerimaanRetur->Check_QtySKUPallet($delivery_order_batch_id);
					if ($check_sku_qty_pallet > 0) {

						$this->db->trans_begin();

						if ($act == 'ProsesBTBKiriman') {
							$penerimaan_tipe_id = '79F2522A-CEA5-4FF8-BC79-C0B28808877D';
							$penerimaan_tipe_nama2 = 'tidak terkirim';
						} else if ($act == 'ProsesBTBRetur') {
							$penerimaan_tipe_id = 'BDCFCBE1-52CF-404F-84B5-A19F0918CA8D';
							$penerimaan_tipe_nama2 = 'retur';
						}

						// INSERT PENERIMAAN PENJUALAN
						$penerimaan_penjualan_id = $this->M_PenerimaanRetur->Get_NEWID();
						$this->M_PenerimaanRetur->Insert_PenerimaanPenjualan($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $delivery_order_batch_id, $depo_detail_id, $karyawan_id, $penerimaan_tipe_id, $penerimaan_tipe_nama2, $penerimaan_penjualan_keterangan, $client_wms_id, $principle_id);

						// UPDATE STOK SKU
						$this->M_PenerimaanRetur->Update_StockSKU($delivery_order_batch_id, $depo_detail_id, $penerimaan_tipe_id, $client_wms_id);

						foreach ($arr_dodetail as $key => $value) {
							//INSERT PENERIMAAN PENJUALAN DETAIL
							$penerimaan_penjualan_detail_id = $this->M_PenerimaanRetur->Get_NEWID();
							$this->M_PenerimaanRetur->Insert_PenerimaanPenjualanDetailBatch($penerimaan_penjualan_detail_id, $penerimaan_penjualan_id, $delivery_order_id, $penerimaan_tipe_id, $value, $client_wms_id, $depo_detail_id);

							$data_sku_stock_by_pallet = $this->M_PenerimaanRetur->get_sku_stock_by_pallet($delivery_order_batch_id, $penerimaan_tipe_id);

							$pallet_fdjr = $this->M_PenerimaanRetur->get_data_pallet_by_fdjr($delivery_order_batch_id);

							foreach ($pallet_fdjr as $key2 => $value2) {
								$cek_pallet_penerimaan = $this->M_PenerimaanRetur->cek_pallet_penerimaan($penerimaan_penjualan_id, $value2['pallet_id']);

								if ($cek_pallet_penerimaan == 0) {
									// INSERT PENERIMAAN PENJUALAN DETAIL 2
									$this->M_PenerimaanRetur->Insert_PenerimaanPenjualanDetail2($penerimaan_penjualan_id, $value2);
								}
							}

							// if ($penerimaan_tipe_id == "79F2522A-CEA5-4FF8-BC79-C0B28808877D" || $penerimaan_tipe_id = 'BDCFCBE1-52CF-404F-84B5-A19F0918CA8D') {
							// 	$this->M_PenerimaanRetur->update_stock_delivery_order_detail($delivery_order_id, 0, 0, $value, $act);
							// }

							// UPDATE sku_stock_id => PENERIMAAN PENJUALAN DETAIL
							$this->M_PenerimaanRetur->update_skuStockID_penerimaan_penjualan_detail($depo_detail_id, $penerimaan_penjualan_id);
						}

						// foreach ($arr_penerimaan_tipe as $key => $value) {
						// 	$get_penerimaan_tipe = $this->M_PenerimaanRetur->Get_PenerimaanTipe($delivery_order_batch_id, $value['penerimaan_tipe_id']);
						// 	// echo var_dump($get_penerimaan_tipe);
						// 	// echo "<br>";

						// 	if ($get_penerimaan_tipe != "0") {
						// 		foreach ($get_penerimaan_tipe as $key => $row) {
						// 			$penerimaan_tipe_id = $row['penerimaan_tipe_id'];
						// 			$penerimaan_tipe_nama = $value['tipe'];
						// 			$penerimaan_tipe_nama2 = $row['penerimaan_tipe_nama'];
						// 			// $depo_detail_id = $value['depo_detail_id'];

						// 			// echo $penerimaan_tipe_id . "<br>";

						// 			$penerimaan_penjualan_id = $this->M_PenerimaanRetur->get_penerimaan_penjualan_id($delivery_order_batch_id);
						// 			$penerimaan_penjualan_id = $check_btb;

						// 			$this->M_PenerimaanRetur->Update_StockSKU($delivery_order_batch_id, $depo_detail_id, $penerimaan_tipe_id, $client_wms_id);

						// 			$data_do = $this->M_PenerimaanRetur->get_data_do_by_id($delivery_order_batch_id, $delivery_order_id, $penerimaan_tipe_id, $penerimaan_tipe_nama, $kondisiBarang);
						// 			$data_sku_stock_by_pallet = $this->M_PenerimaanRetur->get_sku_stock_by_pallet($delivery_order_batch_id, $penerimaan_tipe_id);
						// 			$pallet_fdjr = $this->M_PenerimaanRetur->get_data_pallet_by_fdjr($delivery_order_batch_id);

						// 			foreach ($data_do as $key2 => $value2) {
						// 				$penerimaan_penjualan_detail_id = $this->M_PenerimaanRetur->Get_NEWID();
						// 				$this->M_PenerimaanRetur->Insert_PenerimaanPenjualanDetail($penerimaan_penjualan_detail_id, $penerimaan_penjualan_id, $delivery_order_id, $penerimaan_tipe_id, $value2, $client_wms_id, $depo_detail_id);
						// 			}

						// 			foreach ($pallet_fdjr as $key2 => $value2) {
						// 				$cek_pallet_penerimaan = $this->M_PenerimaanRetur->cek_pallet_penerimaan($penerimaan_penjualan_id, $value2['pallet_id']);

						// 				if ($cek_pallet_penerimaan == 0) {
						// 					$this->M_PenerimaanRetur->Insert_PenerimaanPenjualanDetail2($penerimaan_penjualan_id, $value2);
						// 				}
						// 			}

						// 			if ($penerimaan_tipe_id == "79F2522A-CEA5-4FF8-BC79-C0B28808877D") {
						// 				$this->M_PenerimaanRetur->update_stock_delivery_order_detail($delivery_order_id, 0, 0);
						// 			}

						// 			foreach ($data_sku_stock_by_pallet as $key2 => $value2) {
						// 				$sku_stock_card_keterangan = "Bukti Terima Barang dari Outlet";
						// 				$sku_id = $value2['sku_id'];
						// 				$sku_stock_id = $value2['sku_stock_id'];
						// 				$sku_stock_card_jenis = "D";
						// 				$sku_stock_card_qty = $value2['sku_stock_qty'];
						// 				$sku_stock_card_is_posting = 0;

						// 				$this->M_PenerimaanRetur->Insert_sku_stock_card($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $sku_stock_card_keterangan, $sku_stock_card_jenis, $depo_id, $depo_detail_id, $sku_id, $sku_stock_id, $sku_stock_card_qty, $sku_stock_card_is_posting);
						// 			}
						// 		}
						// 	}
						// }

						//EXEC SKU STOCK CARD
						$this->M_PenerimaanRetur->exec_insert_sku_stock_card($penerimaan_penjualan_id, $this->session->userdata('pengguna_username'));

						$pallet_temp = $this->M_PenerimaanRetur->get_pallet_temp($delivery_order_batch_id);
						$pallet_detail_temp = $this->M_PenerimaanRetur->get_pallet_detail_temp($delivery_order_batch_id, $act);

						foreach ($pallet_temp as $key => $value) {
							$check_pallet = $this->M_PenerimaanRetur->CheckPalletById($value['pallet_id']);
							if ($check_pallet == "0") {
								$this->M_PenerimaanRetur->Insert_Pallet($value);
								$this->M_PenerimaanRetur->Insert_rak_lajur_detail_pallet($value);
								$this->M_PenerimaanRetur->Insert_rak_lajur_detail_pallet_his($depo_detail_id, $value);
							} else {
								$this->M_PenerimaanRetur->Update_Pallet($value);
								$this->M_PenerimaanRetur->Update_rak_lajur_detail_pallet($value);
								$this->M_PenerimaanRetur->Insert_rak_lajur_detail_pallet_his($depo_detail_id, $value);
							}
						}

						foreach ($pallet_detail_temp as $key => $value) {
							// $check_pallet = $this->M_PenerimaanRetur->CheckPalletDetailById($value['pallet_id'], $value['sku_id'], $value['penerimaan_tipe_id'], $value['sku_stock_expired_date']);

							$arr_sku_stock = $this->db->query("SELECT * FROM sku_stock WHERE sku_id = '" . $value['sku_id'] . "' AND sku_stock_expired_date = '" . $value['sku_stock_expired_date'] . "' AND depo_detail_id = '$depo_detail_id' ");

							$check_pallet_detail = $this->M_PenerimaanRetur->CheckPalletDetailById($value['pallet_id'], $value['sku_stock_id']);
							if ($check_pallet_detail == "0") {
								$this->M_PenerimaanRetur->Insert_PalletDetail($value);
							} else {
								$this->M_PenerimaanRetur->Update_PalletDetail($value['pallet_id'], $value['sku_id'], $value['sku_stock_id'], $value);
							}
						}

						$this->M_PenerimaanRetur->Delete_PalletDetail($delivery_order_batch_id);
						$this->M_PenerimaanRetur->Delete_Pallet($delivery_order_batch_id);

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

					// echo $check_btb;
				}
			} else {
				if ($check_sku_counter == $jumlah_arr) {
					if ($check_btb == "0") {
						$check_sku_qty_pallet = $this->M_PenerimaanRetur->Check_QtySKUPallet($delivery_order_batch_id);
						if ($check_sku_qty_pallet > 0) {

							$this->db->trans_begin();

							foreach ($arr_penerimaan_tipe as $key => $value) {
								$get_penerimaan_tipe = $this->M_PenerimaanRetur->Get_PenerimaanTipe($delivery_order_batch_id, $value['penerimaan_tipe_id']);
								// echo var_dump($get_penerimaan_tipe);
								// echo "<br>";

								if ($get_penerimaan_tipe != "0") {
									foreach ($get_penerimaan_tipe as $key => $row) {
										$penerimaan_tipe_id = $row['penerimaan_tipe_id'];
										$penerimaan_tipe_nama = $value['tipe'];
										$penerimaan_tipe_nama2 = $row['penerimaan_tipe_nama'];
										// $depo_detail_id = $value['depo_detail_id'];

										// $data_do = $this->M_PenerimaanRetur->get_data_do_by_id($delivery_order_batch_id, $delivery_order_id, $penerimaan_tipe_id, $penerimaan_tipe_nama);

										// echo $penerimaan_tipe_id . "<br>";
										// echo var_dump($data_do);

										$penerimaan_penjualan_id = $this->M_PenerimaanRetur->Get_NEWID();
										// $this->M_PenerimaanRetur->Update_StockSKU($delivery_order_batch_id, $depo_detail_id, $penerimaan_tipe_id, $client_wms_id);

										// $this->M_PenerimaanRetur->Insert_PenerimaanPenjualan($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $delivery_order_batch_id, $depo_detail_id, $karyawan_id, $penerimaan_tipe_id, $penerimaan_tipe_nama2, $penerimaan_penjualan_keterangan, $client_wms_id, $principle_id);

										$data_do = $this->M_PenerimaanRetur->get_data_do_by_id($delivery_order_batch_id, $delivery_order_id, $penerimaan_tipe_id, $penerimaan_tipe_nama, $kondisiBarang, $act);
										// print_r($data_do);
										// die;
										$data_sku_stock_by_pallet = $this->M_PenerimaanRetur->get_sku_stock_by_pallet($delivery_order_batch_id, $penerimaan_tipe_id);

										$pallet_fdjr = $this->M_PenerimaanRetur->get_data_pallet_by_fdjr($delivery_order_batch_id);

										// echo var_dump($pallet_fdjr);

										foreach ($data_do as $key2 => $value2) {
											$penerimaan_penjualan_detail_id = $this->M_PenerimaanRetur->Get_NEWID();
											$this->M_PenerimaanRetur->Insert_PenerimaanPenjualanDetail($penerimaan_penjualan_detail_id, $penerimaan_penjualan_id, $delivery_order_id, $penerimaan_tipe_id, $value2, $client_wms_id, $depo_detail_id);
										}

										foreach ($pallet_fdjr as $key2 => $value2) {
											$cek_pallet_penerimaan = $this->M_PenerimaanRetur->cek_pallet_penerimaan($penerimaan_penjualan_id, $value2['pallet_id']);

											if ($cek_pallet_penerimaan == 0) {
												$this->M_PenerimaanRetur->Insert_PenerimaanPenjualanDetail2($penerimaan_penjualan_id, $value2);
											}
										}

										if ($penerimaan_tipe_id == "79F2522A-CEA5-4FF8-BC79-C0B28808877D") {
											$this->M_PenerimaanRetur->update_stock_delivery_order_detail($delivery_order_id, 0, 0, $value2, $act);
										}

										foreach ($data_sku_stock_by_pallet as $key2 => $value2) {
											$sku_stock_card_keterangan = "Bukti Terima Barang dari Outlet";
											$sku_id = $value2['sku_id'];
											$sku_stock_id = $value2['sku_stock_id'];
											$sku_stock_card_jenis = "D";
											$sku_stock_card_qty = $value2['sku_stock_qty'];
											$sku_stock_card_is_posting = 0;

											$this->M_PenerimaanRetur->Insert_sku_stock_card($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $sku_stock_card_keterangan, $sku_stock_card_jenis, $depo_id, $depo_detail_id, $sku_id, $sku_stock_id, $sku_stock_card_qty, $sku_stock_card_is_posting);
										}
									}
								}
							}

							$pallet_temp = $this->M_PenerimaanRetur->get_pallet_temp($delivery_order_batch_id);
							$pallet_detail_temp = $this->M_PenerimaanRetur->get_pallet_detail_temp($delivery_order_batch_id, $act);

							foreach ($pallet_temp as $key => $value) {
								$check_pallet = $this->M_PenerimaanRetur->CheckPalletById($value['pallet_id']);
								if ($check_pallet == "0") {
									$this->M_PenerimaanRetur->Insert_Pallet($value);
									$this->M_PenerimaanRetur->Insert_rak_lajur_detail_pallet($value);
									$this->M_PenerimaanRetur->Insert_rak_lajur_detail_pallet_his($depo_detail_id, $value);
								} else {
									$this->M_PenerimaanRetur->Update_Pallet($value);
									$this->M_PenerimaanRetur->Update_rak_lajur_detail_pallet($value);
									$this->M_PenerimaanRetur->Insert_rak_lajur_detail_pallet_his($depo_detail_id, $value);
								}
							}

							foreach ($pallet_detail_temp as $key => $value) {
								// $check_pallet = $this->M_PenerimaanRetur->CheckPalletDetailById($value['pallet_id'], $value['sku_id'], $value['penerimaan_tipe_id'], $value['sku_stock_expired_date']);

								$arr_sku_stock = $this->db->query("SELECT * FROM sku_stock WHERE sku_id = '" . $value['sku_id'] . "' AND sku_stock_expired_date = '" . $value['sku_stock_expired_date'] . "' AND depo_detail_id = '$depo_detail_id' ");

								$check_pallet = $this->M_PenerimaanRetur->CheckPalletDetailById($value['pallet_id'], $value['sku_stock_id']);
								if ($check_pallet == "0") {
									$this->M_PenerimaanRetur->Insert_PalletDetail($value);
								} else {
									$this->M_PenerimaanRetur->Update_PalletDetail($value['pallet_id'], $value['sku_id'], $value['sku_stock_id'], $value);
								}
							}

							$this->M_PenerimaanRetur->Delete_PalletDetail($delivery_order_batch_id);
							$this->M_PenerimaanRetur->Delete_Pallet($delivery_order_batch_id);

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

						// echo "y";
					} else {
						$check_sku_qty_pallet = $this->M_PenerimaanRetur->Check_QtySKUPallet($delivery_order_batch_id);
						if ($check_sku_qty_pallet > 0) {

							$this->db->trans_begin();

							foreach ($arr_penerimaan_tipe as $key => $value) {
								$get_penerimaan_tipe = $this->M_PenerimaanRetur->Get_PenerimaanTipe($delivery_order_batch_id, $value['penerimaan_tipe_id']);
								// echo var_dump($get_penerimaan_tipe);
								// echo "<br>";

								if ($get_penerimaan_tipe != "0") {
									foreach ($get_penerimaan_tipe as $key => $row) {
										$penerimaan_tipe_id = $row['penerimaan_tipe_id'];
										$penerimaan_tipe_nama = $value['tipe'];
										$penerimaan_tipe_nama2 = $row['penerimaan_tipe_nama'];
										// $depo_detail_id = $value['depo_detail_id'];

										// echo $penerimaan_tipe_id . "<br>";

										$penerimaan_penjualan_id = $this->M_PenerimaanRetur->get_penerimaan_penjualan_id($delivery_order_batch_id);
										$penerimaan_penjualan_id = $check_btb;

										$this->M_PenerimaanRetur->Update_StockSKU($delivery_order_batch_id, $depo_detail_id, $penerimaan_tipe_id, $client_wms_id);

										$data_do = $this->M_PenerimaanRetur->get_data_do_by_id($delivery_order_batch_id, $delivery_order_id, $penerimaan_tipe_id, $penerimaan_tipe_nama, $kondisiBarang);
										$data_sku_stock_by_pallet = $this->M_PenerimaanRetur->get_sku_stock_by_pallet($delivery_order_batch_id, $penerimaan_tipe_id);
										$pallet_fdjr = $this->M_PenerimaanRetur->get_data_pallet_by_fdjr($delivery_order_batch_id);

										foreach ($data_do as $key2 => $value2) {
											$penerimaan_penjualan_detail_id = $this->M_PenerimaanRetur->Get_NEWID();
											$this->M_PenerimaanRetur->Insert_PenerimaanPenjualanDetail($penerimaan_penjualan_detail_id, $penerimaan_penjualan_id, $delivery_order_id, $penerimaan_tipe_id, $value2, $client_wms_id, $depo_detail_id);
										}

										foreach ($pallet_fdjr as $key2 => $value2) {
											$cek_pallet_penerimaan = $this->M_PenerimaanRetur->cek_pallet_penerimaan($penerimaan_penjualan_id, $value2['pallet_id']);

											if ($cek_pallet_penerimaan == 0) {
												$this->M_PenerimaanRetur->Insert_PenerimaanPenjualanDetail2($penerimaan_penjualan_id, $value2);
											}
										}

										if ($penerimaan_tipe_id == "79F2522A-CEA5-4FF8-BC79-C0B28808877D") {
											$this->M_PenerimaanRetur->update_stock_delivery_order_detail($delivery_order_id, 0, 0);
										}

										foreach ($data_sku_stock_by_pallet as $key2 => $value2) {
											$sku_stock_card_keterangan = "Bukti Terima Barang dari Outlet";
											$sku_id = $value2['sku_id'];
											$sku_stock_id = $value2['sku_stock_id'];
											$sku_stock_card_jenis = "D";
											$sku_stock_card_qty = $value2['sku_stock_qty'];
											$sku_stock_card_is_posting = 0;

											$this->M_PenerimaanRetur->Insert_sku_stock_card($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $sku_stock_card_keterangan, $sku_stock_card_jenis, $depo_id, $depo_detail_id, $sku_id, $sku_stock_id, $sku_stock_card_qty, $sku_stock_card_is_posting);
										}
									}
								}
							}

							$pallet_temp = $this->M_PenerimaanRetur->get_pallet_temp($delivery_order_batch_id);
							$pallet_detail_temp = $this->M_PenerimaanRetur->get_pallet_detail_temp($delivery_order_batch_id);

							foreach ($pallet_temp as $key => $value) {
								$check_pallet = $this->M_PenerimaanRetur->CheckPalletById($value['pallet_id']);
								if ($check_pallet == "0") {
									$this->M_PenerimaanRetur->Insert_Pallet($value);
									$this->M_PenerimaanRetur->Insert_rak_lajur_detail_pallet($value);
									$this->M_PenerimaanRetur->Insert_rak_lajur_detail_pallet_his($depo_detail_id, $value);
								} else {
									$this->M_PenerimaanRetur->Update_Pallet($value);
									$this->M_PenerimaanRetur->Update_rak_lajur_detail_pallet($value);
									$this->M_PenerimaanRetur->Insert_rak_lajur_detail_pallet_his($depo_detail_id, $value);
								}
							}

							foreach ($pallet_detail_temp as $key => $value) {
								// $check_pallet = $this->M_PenerimaanRetur->CheckPalletDetailById($value['pallet_id'], $value['sku_id'], $value['penerimaan_tipe_id'], $value['sku_stock_expired_date']);
								$check_pallet = $this->M_PenerimaanRetur->CheckPalletDetailById($value['pallet_id'], $value['sku_stock_id']);
								if ($check_pallet == "0") {
									$this->M_PenerimaanRetur->Insert_PalletDetail($value);
								} else {
									$this->M_PenerimaanRetur->Update_PalletDetail($value['pallet_id'], $value['sku_id'], $value['sku_stock_id'], $value);
								}
							}

							$this->M_PenerimaanRetur->Delete_PalletDetail($delivery_order_batch_id);
							$this->M_PenerimaanRetur->Delete_Pallet($delivery_order_batch_id);

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

						// echo $check_btb;
					}
				} else {
					echo json_encode(5);
				}
			}
		}
	}

	public function check_sisa_btb_by_do()
	{
		$delivery_order_id = $this->input->post('delivery_order_id');
		// $pallet_id = $this->input->post('pallet_id');

		$data = $this->M_PenerimaanRetur->check_sisa_btb_by_do($delivery_order_id);

		echo json_encode($data);
	}

	public function check_sisa_btb_by_fdjr()
	{
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		// $pallet_id = $this->input->post('pallet_id');

		$data = $this->M_PenerimaanRetur->check_sisa_btb_by_fdjr($delivery_order_batch_id);

		echo json_encode($data);
	}

	public function UpdateClosingPengirimanFDJR()
	{
		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('delivery_order_batch_id', 'Delivery Order Batch ID', 'required');
		$this->form_validation->set_rules('delivery_order_batch_status', 'Delivery Order Batch Status', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
			$delivery_order_batch_status = $this->input->post('delivery_order_batch_status');

			$result = $this->M_PenerimaanRetur->Update_ClosingPengirimanFDJR($delivery_order_batch_id, $delivery_order_batch_status);

			echo $result;
		}
	}

	public function UpdateQtySKUPalletTemp()
	{

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

			$set_qty = $this->M_PenerimaanRetur->Update_QtySKUPalletTemp($pallet_detail_id, 0);

			if ($penerimaan_tipe_nama == "titipan") {
				$result = $this->M_PenerimaanRetur->Update_QtySKUPalletTemp($pallet_detail_id, $sku_stock_qty);
				echo $result;
			} else {

				$check_qty = $this->M_PenerimaanRetur->Check_QtySKUPalletTemp($delivery_order_batch_id, $sku_id, $sku_stock_qty, $penerimaan_tipe_nama);
				if ($check_qty >= 0) {

					$result = $this->M_PenerimaanRetur->Update_QtySKUPalletTemp($pallet_detail_id, $sku_stock_qty);

					echo $result;
				} else {
					echo "2";
				}
			}
		}
	}

	public function GetRakLajurDetail()
	{
		$depo_detail_id = $this->input->post('depo_detail_id');
		$data = $this->M_PenerimaanRetur->GetRakLajurDetail($depo_detail_id);

		echo json_encode($data);
	}

	public function check_rak_lajur_detail()
	{
		$depo_detail_id = $this->input->post('depo_detail_id');
		$pallet_id = $this->input->post('pallet_id');
		$kode = $this->input->post('kode');

		$response = $this->M_PenerimaanRetur->check_rak_lajur_detail($depo_detail_id, $kode);
		if ($response == null) {
			echo json_encode(array('type' => 201, 'message' => "Rak <strong>" . $kode . "</strong> tidak ditemukan", 'kode' => $kode));
		} else {
			//update rak_lajur_detail_id_tujuan
			$this->M_PenerimaanRetur->update_pallet_rak_lajur_detail($pallet_id, $response->rak_detail_id);

			echo json_encode(array('type' => 200, 'message' => "Rak <strong>" . $kode . "</strong> ditemukan dan berhasil update", 'kode' => $kode));
		}
	}

	public function getKodeAutoComplete()
	{
		$valueParams = $this->input->get('params');
		$type = $this->input->get('type');
		$result = $this->M_PenerimaanRetur->getKodeAutoComplete($valueParams, $type);
		echo json_encode($result);
	}

	public function UpdatePalletKodeTempByIdTemp()
	{
		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$pallet_id = $this->input->post('pallet_id');
		$pallet_kode = $this->input->post('pallet_kode');

		$this->db->trans_begin();

		$this->M_PenerimaanRetur->UpdatePalletKodeTemByIdTemp($pallet_id, $pallet_kode);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function print_btb()
	{
		$delivery_order_batch_id = $this->input->get('delivery_order_batch_id');
		$delivery_order_id = $this->input->get('delivery_order_id');
		$nama_toko = $this->input->get('nama_toko');
		$data['DOHeader'] = $this->M_PenerimaanRetur->GetHeaderDeliveryOrderById($delivery_order_id);
		$data['DODetail'] = $this->M_PenerimaanRetur->GetDetailPenerimaanPenjualanByDOId($delivery_order_id);


		$data['nama_toko'] = $nama_toko;
		// //filename dari pdf ketika didownload
		$file_pdf = 'report_distribusi_pengiriman_' . date('d-M-Y H:i:s');
		// //setting paper
		$paper = 'A4';
		// //orientasi paper potrait / landscape
		$orientation = "landscape";
		//load view
		$html = $this->load->view('WMS/PenerimaanRetur/view_cetak_btb', $data, true);
		//generate
		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}
}
