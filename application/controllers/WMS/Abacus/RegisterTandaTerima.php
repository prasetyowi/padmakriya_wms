<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentController.php';

class RegisterTandaTerima extends CI_Controller
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

		$this->load->model('M_Menu');
		$this->load->model('WMS/Abacus/M_RegisterTandaTerima', 'M_RegisterTandaTerima');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->MenuKode = "136000100";
	}

	public function RegisterTandaTerimaMenu()
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
			redirect(base_url('Main/MainDepo/MainDepoMenu'));
		}

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Perusahaan'] = $this->M_RegisterTandaTerima->GetPerusahaan();
		$data['act'] = "index";

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
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/Abacus/RegisterTandaTerima/index', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Abacus/RegisterTandaTerima/script', $data);
	}

	public function create()
	{

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

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['Perusahaan'] = $this->M_RegisterTandaTerima->GetPerusahaan();

		$data['act'] = "add";

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/Abacus/RegisterTandaTerima/form', $data);
		$this->load->view('layouts/sidebar_footer', $data);
		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Abacus/RegisterTandaTerima/script', $data);
	}

	public function edit()
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
			redirect(base_url('Main/MainDepo/MainDepoMenu'));
		}

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Perusahaan'] = $this->M_RegisterTandaTerima->GetPerusahaan();

		$data['act'] = "edit";

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

		$id = $this->input->get('id');

		$data['Header'] = $this->M_RegisterTandaTerima->Get_tr_abacus_header_by_id($id);
		$data['Detail'] = $this->M_RegisterTandaTerima->Get_tr_abacus_detail_by_id($id);

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/Abacus/RegisterTandaTerima/edit', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Abacus/RegisterTandaTerima/script', $data);
	}


	public function detail()
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
			redirect(base_url('Main/MainDepo/MainDepoMenu'));
		}

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Perusahaan'] = $this->M_RegisterTandaTerima->GetPerusahaan();

		$data['act'] = "detail";

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

		$id = $this->input->get('id');

		$data['Header'] = $this->M_RegisterTandaTerima->Get_tr_abacus_header_by_id($id);
		$data['Detail'] = $this->M_RegisterTandaTerima->Get_tr_abacus_detail_by_id($id);

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/Abacus/RegisterTandaTerima/detail', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Abacus/RegisterTandaTerima/script', $data);
	}


	public function GetJumlahBayarDO()
	{
		$tanggal = $this->input->post('tanggal');
		$perusahaan = $this->input->post('perusahaan');

		$data = $this->M_RegisterTandaTerima->GetJumlahBayarDO($tanggal, $perusahaan);
		echo json_encode($data);
	}

	public function Get_tr_abacus_by_filter()
	{
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$tgl = explode(" - ", $this->input->get('tanggal'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$perusahaan = $this->input->get('perusahaan');
		$status = $this->input->get('status');

		$data = $this->M_RegisterTandaTerima->Get_tr_abacus_by_filter($tgl1, $tgl2, $perusahaan, $status);

		echo json_encode($data);
	}

	public function insert_tr_abacus()
	{
		$tr_abacus_id = $this->M_Vrbl->Get_NewID();
		$tr_abacus_id = $tr_abacus_id[0]['NEW_ID'];

		//generate kode
		$date_now = date('Y-m-d h:i:s');
		$param =  "KODE_ABACUS";
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_RegisterTandaTerima->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$tr_abacus_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		// $tr_abacus_id = $this->input->post('tr_abacus_id');
		$client_wms_id = $this->input->post('client_wms_id');
		$depo_id = $this->input->post('depo_id');
		// $tr_abacus_kode = $this->input->post('tr_abacus_kode');
		$tr_abacus_reff_kode = $this->input->post('tr_abacus_reff_kode');
		$tr_abacus_grand_total = $this->input->post('tr_abacus_grand_total');
		$tr_abacus_tanggal = $this->input->post('tr_abacus_tanggal');
		$tr_abacus_status = $this->input->post('tr_abacus_status');
		$tr_abacus_tgl_create = $this->input->post('tr_abacus_tgl_create');
		$tr_abacus_who_create = $this->input->post('tr_abacus_who_create');
		$tr_abacus_keterangan = $this->input->post('tr_abacus_keterangan');
		$tr_abacus_tgl_update = $this->input->post('tr_abacus_tgl_update');
		$tr_abacus_who_update = $this->input->post('tr_abacus_who_update');
		$detail = $this->input->post('detail');

		$this->db->trans_begin();

		$this->M_RegisterTandaTerima->insert_tr_abacus($tr_abacus_id, $client_wms_id, $depo_id, $tr_abacus_kode, $tr_abacus_reff_kode, $tr_abacus_grand_total, $tr_abacus_tanggal, $tr_abacus_status, $tr_abacus_tgl_create, $tr_abacus_who_create, $tr_abacus_keterangan, $tr_abacus_tgl_update, $tr_abacus_who_update);

		foreach ($detail as $value) {
			$tr_abacus_detail_id = $this->M_Vrbl->Get_NewID();
			$tr_abacus_detail_id = $tr_abacus_detail_id[0]['NEW_ID'];

			$principle_id = $value['principle_id'];
			$no_rekening = $value['no_rekening'];
			$tr_abacus_detail_total = $value['tr_abacus_detail_total'];

			$this->M_RegisterTandaTerima->insert_tr_abacus_detail($tr_abacus_detail_id, $tr_abacus_id, $principle_id, $no_rekening, $tr_abacus_detail_total);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function update_tr_abacus()
	{

		$tr_abacus_id = $this->input->post('tr_abacus_id');
		$client_wms_id = $this->input->post('client_wms_id');
		$depo_id = $this->input->post('depo_id');
		$tr_abacus_kode = $this->input->post('tr_abacus_kode');
		$tr_abacus_reff_kode = $this->input->post('tr_abacus_reff_kode');
		$tr_abacus_grand_total = $this->input->post('tr_abacus_grand_total');
		$tr_abacus_tanggal = $this->input->post('tr_abacus_tanggal');
		$tr_abacus_status = $this->input->post('tr_abacus_status');
		$tr_abacus_tgl_create = $this->input->post('tr_abacus_tgl_create');
		$tr_abacus_who_create = $this->input->post('tr_abacus_who_create');
		$tr_abacus_keterangan = $this->input->post('tr_abacus_keterangan');
		$tr_abacus_tgl_update = $this->input->post('tr_abacus_tgl_update');
		$tr_abacus_who_update = $this->input->post('tr_abacus_who_update');
		$detail = $this->input->post('detail');

		$this->db->trans_begin();

		$this->M_RegisterTandaTerima->update_tr_abacus($tr_abacus_id, $client_wms_id, $depo_id, $tr_abacus_kode, $tr_abacus_reff_kode, $tr_abacus_grand_total, $tr_abacus_tanggal, $tr_abacus_status, $tr_abacus_tgl_create, $tr_abacus_who_create, $tr_abacus_keterangan, $tr_abacus_tgl_update, $tr_abacus_who_update);

		$this->M_RegisterTandaTerima->delete_tr_abacus($tr_abacus_id);

		foreach ($detail as $value) {
			$tr_abacus_detail_id = $this->M_Vrbl->Get_NewID();
			$tr_abacus_detail_id = $tr_abacus_detail_id[0]['NEW_ID'];

			$principle_id = $value['principle_id'];
			$no_rekening = $value['no_rekening'];
			$tr_abacus_detail_total = $value['tr_abacus_detail_total'];

			$this->M_RegisterTandaTerima->insert_tr_abacus_detail($tr_abacus_detail_id, $tr_abacus_id, $principle_id, $no_rekening, $tr_abacus_detail_total);
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
