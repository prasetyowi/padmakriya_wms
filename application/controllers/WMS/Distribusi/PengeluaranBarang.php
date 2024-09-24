<?php

class PengeluaranBarang extends CI_Controller
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

		$this->MenuKode = "135007000";
	}

	public function PengeluaranBarangMenu()
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
		$this->load->view('WMS/PengeluaranBarang/PengeluaranBarang', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PengeluaranBarang/S_PengeluaranBarang', $data);
	}

	public function FormPengeluaranBarangMenu()
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
		$this->load->view('WMS/PengeluaranBarang/PengeluaranBarangForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PengeluaranBarang/S_PengeluaranBarangForm', $data);
	}

	public function DetailPengeluaranBarangMenu()
	{
		$this->load->model('M_Menu');

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
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
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css',
			Get_Assets_Url() . '/node_modules/lightbox2/src/css/lightbox.css'
		);

		$data['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . '/node_modules/html5-qrcode/html5-qrcode.min.js',
			Get_Assets_Url() . '/node_modules/lightbox2/src/js/lightbox.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$picking_order_kode = $this->input->get('picking_order_kode');

		// $data['picking_order_kode'] = str_replace("-","/",$picking_order_kode);
		$data['picking_order_kode'] = $picking_order_kode;

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PengeluaranBarang/PengeluaranBarangDetail', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PengeluaranBarang/S_PengeluaranBarangDetail', $data);
	}

	public function GetPengeluaranBarangMenu()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		// $data['PengeluaranBarangMenu'] = $this->M_PengeluaranBarang->Get_PengeluaranBarang( $Tgl_PPB, $No_PPB, $No_FDJR, $No_PB, $Tipe_PB );
		$data['TipePB'] = $this->M_PengeluaranBarang->Get_TipePB();

		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetPengeluaranBarangTable()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		// $Tgl_PPB = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('Tgl_PPB'))));

		$tgl = explode(" - ", $this->input->post('Tgl_PPB'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$No_PPB = $this->input->post('No_PPB');
		$No_FDJR = $this->input->post('No_FDJR');
		$No_PB = $this->input->post('No_PB');
		$Tipe_PB  = $this->input->post('Tipe_PB');
		$Status_PB  = $this->input->post('Status_PB');

		$dataBKB = $this->M_PengeluaranBarang->Get_PengeluaranBarang($tgl1, $tgl2, $No_PPB, $No_FDJR, $No_PB, $Tipe_PB, $Status_PB);

		$fixData = [];
		if ($dataBKB != 0) {
			foreach ($dataBKB as $key => $value) {
				$area = $this->db->select("area.area_nama")->from("delivery_order_area")
					->join("area", "delivery_order_area.area_id = area.area_id", "left")
					->where("delivery_order_area.delivery_order_batch_id", $value['delivery_order_batch_id'])->get()->result();

				$arrArea = [];

				foreach ($area as $key => $val) {
					$arrArea[] = $val->area_nama;
				}

				array_push($fixData, [
					'picking_order_id' => $value['picking_order_id'],
					'depo_id' => $value['depo_id'],
					'depo_nama' => $value['depo_nama'],
					'picking_order_kode' => $value['picking_order_kode'],
					'picking_order_tanggal' => $value['picking_order_tanggal'],
					'delivery_order_batch_kode' => $value['delivery_order_batch_kode'],
					'delivery_order_batch_tanggal_kirim' => $value['delivery_order_batch_tanggal_kirim'],
					'picking_list_kode' => $value['picking_list_kode'],
					'picking_list_tgl_kirim' => $value['picking_list_tgl_kirim'],
					'tipe_delivery_order_id' => $value['tipe_delivery_order_id'],
					'picking_list_tipe' => $value['picking_list_tipe'],
					'picking_order_keterangan' => $value['picking_order_keterangan'],
					'picking_order_status' => $value['picking_order_status'],
					'tipe_delivery_order_id' => $value['tipe_delivery_order_id'],
					'picking_order_plan_tipe' => $value['picking_order_plan_tipe'],
					'karyawan_nama' => $value['karyawan_nama'],
					'kendaraan_nopol' => $value['kendaraan_nopol'],
					'area' => $arrArea
				]);
			}
		}

		$data['PengeluaranBarang'] = $fixData;

		echo json_encode($data);
	}

	public function GetFormPengeluaranBarangMenu()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$data['TipeDO'] = $this->M_PengeluaranBarang->Get_TipeDO();
		$data['NoPB'] = $this->M_PengeluaranBarang->Get_NoPB();

		// Mendapatkan url yang ngarah ke sini :
		// $MenuLink = $this->session->userdata('MenuLink');
		$MenuLink = "WMS/Distribusi/PengeluaranBarang/PengeluaranBarangMenu";
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetFormPengeluaranBarang()
	{
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_PPB';

		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PengeluaranBarang->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);
		// $generate_kode = $this->M_PengeluaranBarang->Get_NewNoPPB( $prefix, $unit );

		$picking_list_id = $this->input->post('picking_list_id');

		$data['PengeluaranBarang'] = $this->M_PengeluaranBarang->Get_NewPengeluaranBarang($picking_list_id);
		// $data['Checker'] = $this->M_PengeluaranBarang->Get_Checker_BKB( $picking_order_id );
		// $data['NoPPB'] = $generate_kode;
		$data['NoPPB'] = "";

		// Mendapatkan url yang ngarah ke sini :
		// $MenuLink = $this->session->userdata('MenuLink');
		$MenuLink = "WMS/Distribusi/PengeluaranBarang/PengeluaranBarangMenu";
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetFormPengeluaranBarangBulk()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_list_id = $this->input->post('picking_list_id');

		$data['PengeluaranBarangBulk'] = $this->M_PengeluaranBarang->Get_NewPengeluaranBarangBulk($picking_list_id);
		// $data['Checker'] = $this->M_PengeluaranBarang->Get_Checker_Principal($this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id)[0]['depo_id'], $this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id)[0]['principal']);

		// Mendapatkan url yang ngarah ke sini :
		// $MenuLink = $this->session->userdata('MenuLink');
		$MenuLink = "WMS/Distribusi/PengeluaranBarang/PengeluaranBarangMenu";
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetFormPengeluaranBarangStandar()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_list_id = $this->input->post('picking_list_id');

		$data['PengeluaranBarangStandar'] = $this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id);
		$data['Checker'] = $this->M_PengeluaranBarang->Get_Checker_BKB_Principal($this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id)[0]['principal'], $this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id)[0]['depo_id']);

		// Mendapatkan url yang ngarah ke sini :
		// $MenuLink = $this->session->userdata('MenuLink');
		$MenuLink = "WMS/Distribusi/PengeluaranBarang/PengeluaranBarangMenu";
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetFormPengeluaranBarangKirimUlang()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_list_id = $this->input->post('picking_list_id');

		$data['PengeluaranBarangKirimUlang'] = $this->M_PengeluaranBarang->Get_NewPengeluaranBarangKirimUlang($picking_list_id);
		// $data['Checker'] = $this->M_PengeluaranBarang->Get_Checker_Principal($this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id)[0]['depo_id'], $this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id)[0]['principal']);

		// Mendapatkan url yang ngarah ke sini :
		// $MenuLink = $this->session->userdata('MenuLink');
		$MenuLink = "WMS/Distribusi/PengeluaranBarang/PengeluaranBarangMenu";
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetFormPengeluaranBarangCanvas()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_list_id = $this->input->post('picking_list_id');

		$data['PengeluaranBarangCanvas'] = $this->M_PengeluaranBarang->Get_NewPengeluaranBarangCanvas($picking_list_id);
		// $data['Checker'] = $this->M_PengeluaranBarang->Get_Checker_Principal($this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id)[0]['depo_id'], $this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id)[0]['principal']);

		// Mendapatkan url yang ngarah ke sini :
		// $MenuLink = $this->session->userdata('MenuLink');
		$MenuLink = "WMS/Distribusi/PengeluaranBarang/PengeluaranBarangMenu";
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetFormPengeluaranBarangMix()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_list_id = $this->input->post('picking_list_id');

		$data['PengeluaranBarangMix'] = $this->M_PengeluaranBarang->Get_NewPengeluaranBarangMix($picking_list_id);

		// Mendapatkan url yang ngarah ke sini :
		// $MenuLink = $this->session->userdata('MenuLink');
		$MenuLink = "WMS/Distribusi/PengeluaranBarang/PengeluaranBarangMenu";
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetFormPengeluaranBarangMixBulk()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_list_id = $this->input->post('picking_list_id');

		$data['PengeluaranBarangBulk'] = $this->M_PengeluaranBarang->Get_NewPengeluaranBarangMixBulk($picking_list_id);
		// $data['Checker'] = $this->M_PengeluaranBarang->Get_Checker_Principal($this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id)[0]['depo_id'], $this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id)[0]['principal']);

		// Mendapatkan url yang ngarah ke sini :
		// $MenuLink = $this->session->userdata('MenuLink');
		$MenuLink = "Distribusi/PengeluaranBarang/PengeluaranBarangMenu";
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetFormPengeluaranBarangMixStandar()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_list_id = $this->input->post('picking_list_id');

		$data['PengeluaranBarangStandar'] = $this->M_PengeluaranBarang->Get_NewPengeluaranBarangMixStandar($picking_list_id);
		$data['Checker'] = $this->M_PengeluaranBarang->Get_Checker_BKB_Principal($this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id)[0]['principal'], $this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id)[0]['depo_id']);

		// Mendapatkan url yang ngarah ke sini :
		// $MenuLink = $this->session->userdata('MenuLink');
		$MenuLink = "Distribusi/PengeluaranBarang/PengeluaranBarangMenu";
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetFormPengeluaranBarangMixKirimUlang()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_list_id = $this->input->post('picking_list_id');

		$data['PengeluaranBarangKirimUlang'] = $this->M_PengeluaranBarang->Get_NewPengeluaranBarangMixKirimUlang($picking_list_id);
		// $data['Checker'] = $this->M_PengeluaranBarang->Get_Checker_Principal($this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id)[0]['depo_id'], $this->M_PengeluaranBarang->Get_NewPengeluaranBarangStandar($picking_list_id)[0]['principal']);

		// Mendapatkan url yang ngarah ke sini :
		// $MenuLink = $this->session->userdata('MenuLink');
		$MenuLink = "Distribusi/PengeluaranBarang/PengeluaranBarangMenu";
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetDetailPengeluaranBarangMenu()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_order_kode = $this->input->post('picking_order_kode');

		$data['Checker'] = $this->M_PengeluaranBarang->Get_Checker_BKB_By_Kode($picking_order_kode);
		$data['PengeluaranBarang'] = $this->M_PengeluaranBarang->Get_DetailPengeluaranBarang($picking_order_kode);
		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetDetailPengeluaranBarangMixMenu()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_order_kode = $this->input->post('picking_order_kode');

		$data['Checker'] = $this->M_PengeluaranBarang->Get_Checker_BKB_By_Kode($picking_order_kode);
		$data['PengeluaranBarang'] = $this->M_PengeluaranBarang->Get_DetailPengeluaranBarangMix($picking_order_kode);
		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetDetailBKBAktualPlan()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$picking_order_id = $this->input->post('picking_order_id');
		$picking_order_plan_id = $this->input->post('picking_order_plan_id');

		$data['PengeluaranBarang'] = $this->M_PengeluaranBarang->Get_Detail_BKB_Aktual_Plan($picking_order_id, $picking_order_plan_id);

		echo json_encode($data);
	}

	public function GetDaftarBKB()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_order_id = $this->input->post('picking_order_id');
		$tipe_bkb = $this->input->post('tipe_bkb');

		$data['PengeluaranBarang'] = $this->M_PengeluaranBarang->Get_DaftarBKB($picking_order_id, $tipe_bkb);
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
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_order_aktual_h_id = $this->input->post('picking_order_aktual_h_id');

		$data['PengeluaranBarang'] = $this->M_PengeluaranBarang->Get_DetailBKB($picking_order_aktual_h_id);
		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}


	public function GetPickingBKB()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_BKB';

		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PengeluaranBarang->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);
		// $generate_kode = $this->M_PengeluaranBarang->Get_NewNoBKB( $prefix, $unit );

		$picking_order_id = $this->input->post('picking_order_id');

		// $data['DOKode'] = $this->M_PengeluaranBarang->Get_Delivery_Order_Picking_Order($picking_order_id);
		$data['Checker'] = $this->M_PengeluaranBarang->Get_Checker_BKB($picking_order_id);
		$data['PengeluaranBarang'] = $this->M_PengeluaranBarang->Get_PickingBKB($picking_order_id);
		// $data['NoBKB'] = $this->M_PengeluaranBarang->Get_NewNoBKB();
		$data['NoBKB'] = $generate_kode;
		$data['IdBKB'] = $this->M_Vrbl->Get_NewID();
		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetDOKode()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$picking_order_id = $this->input->post('picking_order_id');
		$karyawan = explode(" || ", $this->input->post('karyawan_id'));
		$karyawan_id = $karyawan[0];
		$karyawan_nama = $karyawan[1];

		$data['DOKode'] = $this->M_PengeluaranBarang->Get_Delivery_Order_Picking_Order($picking_order_id, $karyawan_id);

		echo json_encode($data);
	}

	public function GetPickingBKBBulk()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_order_id = $this->input->post('picking_order_id');
		$karyawan = explode(" || ", $this->input->post('karyawan_id'));
		$principle = $this->input->post('principle');
		$karyawan_id = $karyawan[0];
		$karyawan_nama = $karyawan[1];

		$data = $this->M_PengeluaranBarang->Get_PickingBKBBulk($picking_order_id, $karyawan_id, $principle);

		echo json_encode($data);
	}

	public function GetPickingBKBFlushOut()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_order_id = $this->input->post('picking_order_id');
		$karyawan = explode(" || ", $this->input->post('karyawan_id'));
		$principle = $this->input->post('principle');
		$karyawan_id = $karyawan[0];
		$karyawan_nama = $karyawan[1];

		$data = $this->M_PengeluaranBarang->Get_PickingBKBFlushOut($picking_order_id, $karyawan_id, $principle);

		echo json_encode($data);
	}

	public function GetPickingBKBStandar()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_order_id = $this->input->post('picking_order_id');
		$delivery_order_kode = $this->input->post('delivery_order_kode');

		$principle = $this->input->post('principle');

		$data['PengeluaranBarang'] = $this->M_PengeluaranBarang->Get_PickingBKBStandar($picking_order_id, $delivery_order_kode, $principle);

		echo json_encode($data);
	}

	public function GetPickingBKBKirimUlang()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_order_id = $this->input->post('picking_order_id');
		$karyawan = explode(" || ", $this->input->post('karyawan_id'));

		$principle = $this->input->post('principle');
		$karyawan_id = $karyawan[0];
		$karyawan_nama = $karyawan[1];

		$data = $this->M_PengeluaranBarang->Get_PickingBKBKirimUlang($picking_order_id, $karyawan_id, $principle);

		echo json_encode($data);
	}

	public function GetPickingBKBCanvas()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$picking_order_id = $this->input->post('picking_order_id');
		$karyawan = explode(" || ", $this->input->post('karyawan_id'));

		$principle = $this->input->post('principle');
		$karyawan_id = $karyawan[0];
		$karyawan_nama = $karyawan[1];

		$data = $this->M_PengeluaranBarang->Get_PickingBKBCanvas($picking_order_id, $karyawan_id, $principle);

		echo json_encode($data);
	}

	public function GetSKUExpiredDate()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$sku_id = $this->input->post('sku_id');

		$data['ExpiredDate'] = $this->M_PengeluaranBarang->Get_SKU_Expired_Date($sku_id);

		echo json_encode($data);
	}

	public function SaveAddNewPickingOrder()
	{
		$this->load->model('M_AutoGen');
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Vrbl');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "C")) {
			echo 0;
			exit();
		}

		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_PPB';

		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PengeluaranBarang->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);
		// $generate_kode = $this->M_PengeluaranBarang->Get_NewNoPPB( $prefix, $unit );

		$picking_order_tanggal = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('picking_order_tanggal'))));
		// $picking_order_kode = $this->input->post('picking_order_kode');
		$picking_order_keterangan = $this->input->post('picking_order_keterangan');
		$picking_order_type = $this->input->post('picking_order_type');
		$picking_order_status = $this->input->post('picking_order_status');
		$tipe_do = $this->input->post('tipe_do');
		$picking_list_id = $this->input->post('picking_list_id');
		// $lastUpdated = $this->input->post('lastUpdated');

		$tipeDoNoAlias = $this->db->get_where('tipe_delivery_order', ['tipe_delivery_order_alias' => $tipe_do])->row();

		$no_pb = $this->input->post('no_pb');
		if ($this->input->post('karyawan') != "" || $this->input->post('karyawan') != null) {
			$karyawan = explode(" || ", $this->input->post('karyawan'));
			$S_UserID = $karyawan[0];
			$S_pengguna_nama = $karyawan[1];
		} else {
			$S_UserID = "";
			$S_pengguna_nama = "";
		}

		$bulk = $this->input->post('bulk');
		$standar = $this->input->post('standar');
		$kirimUlang = $this->input->post('kirimUlang');
		$canvas = $this->input->post('canvas');

		// echo json_encode([
		// 	'bulk' => $bulk,
		// 	'standar' => $standar,
		// 	'kirimUlang' => $kirimUlang,
		// ]);

		// die;

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('picking_list_id', 'Picking List ID', 'required');
		$this->form_validation->set_rules('depo_id', 'Depo ID', 'required');
		// $this->form_validation->set_rules('depo_detail_id', 'Depo Detail ID', 'required');
		$this->form_validation->set_rules('picking_order_tanggal', 'Picking Order Tanggal', 'required');
		// $this->form_validation->set_rules('picking_order_kode', 'Picking Order Kode', 'required');
		// $this->form_validation->set_rules('picking_order_keterangan', 'Keterangan', 'required');
		$this->form_validation->set_rules('picking_order_type', 'Picking Order Type', 'required');

		if ($tipeDoNoAlias->tipe_delivery_order_nama == "Standar") {
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'required');
		}

		$this->form_validation->set_rules('no_pb', 'No PB', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$Tipe = 'MASTER Pengeluaran Barang';
			$Inisial = 'MPB';

			$this->db->trans_begin();

			// $lastUpdatedChecked = checkLastUpdatedData((object) [
			// 	'table' => "picking_list",
			// 	'whereField' => "picking_list_id",
			// 	'whereValue' => $picking_list_id,
			// 	'fieldDateUpdate' => "picking_list_tgl_update",
			// 	'fieldWhoUpdate' => "picking_list_who_update",
			// 	'lastUpdated' => $lastUpdated,
			// ]);

			$newid = $this->M_Vrbl->Get_NewID();

			$picking_order_id = $newid[0]['NEW_ID'];
			// $depo_id = $this->input->post('depo_id');
			$depo_id = $this->session->userdata('depo_id');
			$picking_order_kode = $generate_kode;
			// $depo_detail_id = $this->input->post('depo_detail_id');


			$duplicate = $this->M_PengeluaranBarang->Check_PengeluaranBarang_Duplicate($picking_order_kode); // pengecekan nama, optional bisa diremark bila tidak dibutuhkan
			$duplicate_pb = $this->M_PengeluaranBarang->Check_No_PB_Duplicate($no_pb);

			$error1 = 0;
			$error2 = 0;

			if ($duplicate == 0) {
				if ($duplicate_pb == 0) {
					if ($tipeDoNoAlias->tipe_delivery_order_nama == "Bulk") {
						// $S_UserID = $this->session->userdata('pengguna_id');
						// $S_pengguna_nama = $this->session->userdata('pengguna_username');

						$this->M_PengeluaranBarang->Insert_PickingOrder($picking_order_id, $picking_list_id, $depo_id, $picking_order_tanggal, $picking_order_kode, $picking_order_keterangan, $picking_order_status);
						$this->M_PengeluaranBarang->Insert_PickingOrderPlanBulk($picking_order_id, $picking_list_id, $picking_order_type, $picking_order_status);


						$this->M_PengeluaranBarang->Update_DilaksanakanPickingOrder($picking_order_id, $picking_list_id, $picking_order_status, $picking_order_keterangan);

						$getPickingOrderPlanId = $this->M_PengeluaranBarang->getPickingOrderPlanId($picking_order_id);

						$this->M_PengeluaranBarang->Update_PickingOrderPlan($picking_order_id, $picking_order_status, $S_UserID, $S_pengguna_nama, $standar, $getPickingOrderPlanId);
						// echo $result;
						// echo $generate_kode;
					} else if ($tipeDoNoAlias->tipe_delivery_order_nama == "Flush Out") {
						// $S_UserID = $this->session->userdata('pengguna_id');
						// $S_pengguna_nama = $this->session->userdata('pengguna_username');

						$this->M_PengeluaranBarang->Insert_PickingOrder($picking_order_id, $picking_list_id, $depo_id, $picking_order_tanggal, $picking_order_kode, $picking_order_keterangan, $picking_order_status);
						$this->M_PengeluaranBarang->Insert_PickingOrderPlanFlushOut($picking_order_id, $picking_list_id, $picking_order_type, $picking_order_status);


						$this->M_PengeluaranBarang->Update_DilaksanakanPickingOrder($picking_order_id, $picking_list_id, $picking_order_status, $picking_order_keterangan);

						$getPickingOrderPlanId = $this->M_PengeluaranBarang->getPickingOrderPlanId($picking_order_id);

						$this->M_PengeluaranBarang->Update_PickingOrderPlan($picking_order_id, $picking_order_status, $S_UserID, $S_pengguna_nama, $standar, $getPickingOrderPlanId);
						// echo $result;
						// echo $generate_kode;
					} else if ($tipeDoNoAlias->tipe_delivery_order_nama == "Standar") {

						// $S_UserID = $this->session->userdata('pengguna_id');
						// $S_pengguna_nama = $this->session->userdata('pengguna_username');

						$this->M_PengeluaranBarang->Insert_PickingOrder($picking_order_id, $picking_list_id, $depo_id, $picking_order_tanggal, $picking_order_kode, $picking_order_keterangan, $picking_order_status);
						$this->M_PengeluaranBarang->Insert_PickingOrderPlanStandar($picking_order_id, $picking_list_id, $picking_order_type, $S_UserID, $S_pengguna_nama, $picking_order_status);

						$this->M_PengeluaranBarang->Update_DilaksanakanPickingOrder($picking_order_id, $picking_list_id, $picking_order_status, $picking_order_keterangan);

						$getPickingOrderPlanId = $this->M_PengeluaranBarang->getPickingOrderPlanId($picking_order_id);

						$this->M_PengeluaranBarang->Update_PickingOrderPlan($picking_order_id, $picking_order_status, $S_UserID, $S_pengguna_nama, $standar, $getPickingOrderPlanId);

						// echo $result;
						// echo $generate_kode;
					} else if ($tipeDoNoAlias->tipe_delivery_order_nama == "Reschedule") {

						// $S_UserID = $this->session->userdata('pengguna_id');
						// $S_pengguna_nama = $this->session->userdata('pengguna_username');

						$this->M_PengeluaranBarang->Insert_PickingOrder($picking_order_id, $picking_list_id, $depo_id, $picking_order_tanggal, $picking_order_kode, $picking_order_keterangan, $picking_order_status);
						$this->M_PengeluaranBarang->Insert_PickingOrderPlanKirimUlang($picking_order_id, $picking_list_id, $picking_order_type, $picking_order_status);


						$this->M_PengeluaranBarang->Update_DilaksanakanPickingOrder($picking_order_id, $picking_list_id, $picking_order_status, $picking_order_keterangan);

						$getPickingOrderPlanId = $this->M_PengeluaranBarang->getPickingOrderPlanId($picking_order_id);

						$this->M_PengeluaranBarang->Update_PickingOrderPlan($picking_order_id, $picking_order_status, $S_UserID, $S_pengguna_nama, $standar, $getPickingOrderPlanId);
						// echo $result;
						// echo $generate_kode;
					} else if ($tipeDoNoAlias->tipe_delivery_order_nama == "Canvas") {

						// $S_UserID = $this->session->userdata('pengguna_id');
						// $S_pengguna_nama = $this->session->userdata('pengguna_username');

						$this->M_PengeluaranBarang->Insert_PickingOrder($picking_order_id, $picking_list_id, $depo_id, $picking_order_tanggal, $picking_order_kode, $picking_order_keterangan, $picking_order_status);
						$this->M_PengeluaranBarang->Insert_PickingOrderPlanCanvas($picking_order_id, $picking_list_id, $picking_order_type, $picking_order_status);


						$this->M_PengeluaranBarang->Update_DilaksanakanPickingOrder($picking_order_id, $picking_list_id, $picking_order_status, $picking_order_keterangan);

						$getPickingOrderPlanId = $this->M_PengeluaranBarang->getPickingOrderPlanId($picking_order_id);

						$this->M_PengeluaranBarang->Update_PickingOrderPlan($picking_order_id, $picking_order_status, $S_UserID, $S_pengguna_nama, $standar, $getPickingOrderPlanId);
						// echo $result;
						// echo $generate_kode;
					} else if ($tipeDoNoAlias->tipe_delivery_order_nama == "Mix") {


						// $S_UserID = $this->session->userdata('pengguna_id');
						// $S_pengguna_nama = $this->session->userdata('pengguna_username');

						$this->M_PengeluaranBarang->Insert_PickingOrder($picking_order_id, $picking_list_id, $depo_id, $picking_order_tanggal, $picking_order_kode, $picking_order_keterangan, $picking_order_status);

						$this->M_PengeluaranBarang->Insert_PickingOrderPlanMix($picking_order_id, $picking_list_id, $picking_order_type, $S_UserID, $S_pengguna_nama, $picking_order_status, $bulk, $standar, $kirimUlang);

						$this->M_PengeluaranBarang->Update_DilaksanakanPickingOrder($picking_order_id, $picking_list_id, $picking_order_status, $picking_order_keterangan);

						$getPickingOrderPlanId = $this->M_PengeluaranBarang->getPickingOrderPlanId($picking_order_id);

						$this->M_PengeluaranBarang->Update_PickingOrderPlan($picking_order_id, $picking_order_status, $S_UserID, $S_pengguna_nama, $standar, $getPickingOrderPlanId);

						// echo $result;
						// echo $generate_kode;
					}
				} else {
					$error1++;
				}
			} else {
				$error2++; // 3 = duplicate name
			}

			// if ($lastUpdatedChecked['status'] === 400) {
			// 	$response = [
			// 		'status' => 400,
			// 		'message' => 'Simpan BKB gagal'
			// 	];
			// 	$this->db->trans_rollback();
			// } else 
			if ($this->db->trans_status() === FALSE) {
				$response = [
					'status' => 401,
					'message' => $this->db->_error_message(),
				];
				$this->db->trans_rollback();
			} else if ($error1 > 0) {
				$response = [
					'status' => 402,
					'message' => 'PB sudah ada PPB'
				];
				$this->db->trans_rollback();
			} else if ($error2 > 0) {
				$response = [
					'status' => 402,
					'message' => 'PPB sudah ada'
				];
				$this->db->trans_rollback();
			} else {
				$response = [
					'status' => 200,
					'message' => 'Data berhasil ditambah',
					'generateKode' => $generate_kode
				];
				$this->db->trans_commit();
			}

			echo json_encode($response);

			// if ($this->db->trans_status() === FALSE) {
			// 	$this->db->trans_rollback();
			// 	echo $this->db->_error_message();
			// } else if ($error1 > 0) {
			// 	$this->db->trans_rollback();
			// 	echo 4;
			// } else if ($error2 > 0) {
			// 	$this->db->trans_rollback();
			// 	echo 3;
			// } else {
			// 	$this->db->trans_commit();
			// 	echo $generate_kode;
			// }
		}

		//progress pas simpan BKB msih error
	}

	public function UpdatePickingOrder()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Vrbl');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "C")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('picking_order_id', 'Picking Order ID', 'required');
		$this->form_validation->set_rules('picking_order_status', 'Picking Order Status', 'required');
		// $this->form_validation->set_rules('picking_order_keterangan', 'Keterangan', 'required');

		$picking_order_id = $this->input->post('picking_order_id');
		$picking_order_status = $this->input->post('picking_order_status');
		// $karyawan_id = $this->session->userdata('pengguna_id');
		// $karyawan_nama = $this->session->userdata('pengguna_username');
		$picking_order_keterangan = $this->input->post('picking_order_keterangan');
		$picking_order_plan_id = $this->input->post('picking_order_plan_id');

		$tipe_do = $this->input->post('tipe_do');

		if ($this->input->post('karyawan') != "" || $this->input->post('karyawan') != null) {
			$karyawan = explode(" || ", $this->input->post('karyawan'));
			$karyawan_id = $karyawan[0];
			$karyawan_nama = $karyawan[1];
		} else {
			$karyawan_id = "";
			$karyawan_nama = "";
		}

		$standar = $this->input->post('standar');
		// $lastUpdated = $this->input->post('lastUpdated');

		if ($tipe_do == "Standar") {
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'required');
		}

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$Tipe = 'MASTER Pengeluaran Barang';
			$Inisial = 'MPB';

			$this->db->trans_begin();

			// $lastUpdatedChecked = checkLastUpdatedData((object) [
			// 	'table' => "picking_order",
			// 	'whereField' => "picking_order_id",
			// 	'whereValue' => $picking_order_id,
			// 	'fieldDateUpdate' => "picking_order_tgl_update",
			// 	'fieldWhoUpdate' => "picking_order_who_update",
			// 	'lastUpdated' => $lastUpdated,
			// ]);

			$S_UserID = $this->session->userdata('pengguna_id');
			$S_pengguna_nama = $this->session->userdata('pengguna_username');

			$result = $this->M_PengeluaranBarang->Update_PickingOrder($picking_order_id, $picking_order_status, $picking_order_keterangan);
			$result2 = $this->M_PengeluaranBarang->Update_PickingOrderPlan($picking_order_id, $picking_order_status, $karyawan_id, $karyawan_nama, $standar, $picking_order_plan_id);

			// if ($lastUpdatedChecked['status'] === 400) {
			// 	$response = [
			// 		'status' => 400,
			// 		'message' => 'BKB Gagal Dibatalkan',
			// 	];
			// 	$this->db->trans_rollback();
			// } else 

			if ($this->db->trans_status() === FALSE) {
				$response = [
					'status' => 401,
					'message' => $this->db->error()['message'],
				];
				$this->db->trans_rollback();
			} else {
				$response = [
					'status' => 200,
					'message' => 'BKB Berhasil Dibatalkan',
					// 'lastUpdatedNew' => $lastUpdatedChecked['lastUpdatedNew']
				];
				$this->db->trans_commit();
			}

			echo json_encode($response);
		}
	}

	public function UpdateDilaksanakanPickingOrder()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Vrbl');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "C")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('picking_order_id', 'Picking Order ID', 'required');
		$this->form_validation->set_rules('picking_order_status', 'Picking Order Status', 'required');
		// $this->form_validation->set_rules('picking_order_keterangan', 'Keterangan', 'required');

		$picking_order_id = $this->input->post('picking_order_id');
		$picking_list_id = $this->input->post('picking_list_id');
		$delivery_order_batch_kode = $this->input->post('delivery_order_batch_kode');
		$picking_order_status = $this->input->post('picking_order_status');
		// $karyawan_id = $this->session->userdata('pengguna_id');
		// $karyawan_nama = $this->session->userdata('pengguna_username');
		$picking_order_keterangan = $this->input->post('picking_order_keterangan');
		$tipe_do = $this->input->post('tipe_do');
		$picking_order_plan_id = $this->input->post('picking_order_plan_id');

		if ($this->input->post('karyawan') != "" || $this->input->post('karyawan') != null) {
			$karyawan = explode(" || ", $this->input->post('karyawan'));
			$karyawan_id = $karyawan[0];
			$karyawan_nama = $karyawan[1];
		} else {
			$karyawan_id = "";
			$karyawan_nama = "";
		}


		$standar = $this->input->post('standar');

		if ($tipe_do == "Standar") {
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'required');
		}


		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$Tipe = 'MASTER Pengeluaran Barang';
			$Inisial = 'MPB';

			$S_UserID = $this->session->userdata('pengguna_id');
			$S_pengguna_nama = $this->session->userdata('pengguna_username');

			$result = $this->M_PengeluaranBarang->Update_DilaksanakanPickingOrder($picking_order_id, $picking_list_id, $picking_order_status, $picking_order_keterangan);
			$result2 = $this->M_PengeluaranBarang->Update_PickingOrderPlan($picking_order_id, $picking_order_status, $karyawan_id, $karyawan_nama, $standar, $picking_order_plan_id);

			echo json_encode($result);
		}
	}

	public function UpdateSelesaiPickingOrder()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Vrbl');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "C")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('picking_order_id', 'Picking Order ID', 'required');
		$this->form_validation->set_rules('picking_order_status', 'Picking Order Status', 'required');
		// $this->form_validation->set_rules('picking_order_keterangan', 'Keterangan', 'required');

		$picking_order_id = $this->input->post('picking_order_id');
		$picking_list_id = $this->input->post('picking_list_id');
		// $delivery_order_batch_kode = $this->input->post('delivery_order_batch_kode');
		$picking_order_status = $this->input->post('picking_order_status');
		// $karyawan_id = $this->session->userdata('pengguna_id');
		// $karyawan_nama = $this->session->userdata('pengguna_username');
		$picking_order_keterangan = $this->input->post('picking_order_keterangan');
		$picking_order_plan_id = $this->input->post('picking_order_plan_id');

		$tipe_do = $this->input->post('tipe_do');
		// $lastUpdated = $this->input->post('lastUpdated');

		if ($this->input->post('karyawan') != "" || $this->input->post('karyawan') != null) {
			$karyawan = explode(" || ", $this->input->post('karyawan'));
			$karyawan_id = $karyawan[0];
			$karyawan_nama = $karyawan[1];
		} else {
			$karyawan_id = "";
			$karyawan_nama = "";
		}

		$standar = $this->input->post('standar');

		if ($tipe_do == "Standar") {
			$this->form_validation->set_rules('karyawan', 'Karyawan', 'required');
		}

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$Tipe = 'MASTER Pengeluaran Barang';
			$Inisial = 'MPB';

			$S_UserID = $this->session->userdata('pengguna_id');
			$S_pengguna_nama = $this->session->userdata('pengguna_username');

			$this->db->trans_begin();

			// $lastUpdatedChecked = checkLastUpdatedData((object) [
			// 	'table' => "picking_order",
			// 	'whereField' => "picking_order_id",
			// 	'whereValue' => $picking_order_id,
			// 	'fieldDateUpdate' => "picking_order_tgl_update",
			// 	'fieldWhoUpdate' => "picking_order_who_update",
			// 	'lastUpdated' => $lastUpdated,
			// ]);

			$this->M_PengeluaranBarang->Update_SelesaiPickingOrder($picking_order_id, $picking_list_id, $picking_order_status, $picking_order_keterangan);
			$this->M_PengeluaranBarang->Update_PickingOrderPlan($picking_order_id, $picking_order_status, $karyawan_id, $karyawan_nama, $standar, $picking_order_plan_id);

			//get data picking order plan d
			// $datas = $this->M_PengeluaranBarang->GetDataPickingOrderPlanDByPoId($picking_order_id);
			// foreach ($datas as $key => $value) {
			// 	if ($value->pallet_id != null) {
			// 		//delete data di rak lajur detail pallet yg pallet id nya tidak sama dngan null
			// 		$this->M_PengeluaranBarang->DeleteRakLajurDetailPalletByPalletId($value->pallet_id);
			// 	}
			// }

			// if ($lastUpdatedChecked['status'] === 400) {
			// 	$response = [
			// 		'status' => 400,
			// 		'message' => 'BKB Gagal Dikonfirmasi',
			// 	];
			// 	$this->db->trans_rollback();
			// } else 

			if ($this->db->trans_status() === FALSE) {
				$response = [
					'status' => 401,
					'message' => $this->db->error()['message'],
				];
				$this->db->trans_rollback();
			} else {
				$response = [
					'status' => 200,
					'message' => 'BKB Berhasil Dikonfirmasi',
					// 'lastUpdatedNew' => $lastUpdatedChecked['lastUpdatedNew']
				];
				$this->db->trans_commit();
			}

			echo json_encode($response);

			// echo json_encode($datas);
		}
	}

	public function SaveAddNewPickingOrderAktual_H()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Vrbl');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "C")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$picking_order_aktual_h_id = $this->input->post('picking_order_aktual_h_id');
		$picking_order_aktual_kode = $this->input->post('picking_order_aktual_kode');
		$picking_order_id = $this->input->post('picking_order_id');
		$picking_order_kode = $this->input->post('picking_order_kode');
		// $lastUpdated = $this->input->post('lastUpdated');
		$karyawan = explode(" || ", $this->input->post('karyawan_id'));

		$this->form_validation->set_rules('picking_order_aktual_h_id', 'ID BKB', 'required');
		$this->form_validation->set_rules('picking_order_aktual_kode', 'No BKB', 'required');
		$this->form_validation->set_rules('picking_order_id', 'PPB ID', 'required');
		$this->form_validation->set_rules('picking_order_kode', 'No PPB', 'required');
		$this->form_validation->set_rules('karyawan_id', 'Karyawan', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$Tipe = 'MASTER Pengeluaran Barang';
			$Inisial = 'MPB';

			$this->db->trans_begin();

			// $lastUpdatedChecked = checkLastUpdatedData((object) [
			// 	'table' => "picking_order",
			// 	'whereField' => "picking_order_id",
			// 	'whereValue' => $picking_order_id,
			// 	'fieldDateUpdate' => "picking_order_tgl_update",
			// 	'fieldWhoUpdate' => "picking_order_who_update",
			// 	'lastUpdated' => $lastUpdated,
			// ]);

			$karyawan_id = $karyawan[0];
			$karyawan_nama = $karyawan[1];
			$karyawan_id = $this->session->userdata('pengguna_id');
			$karyawan_nama = $this->session->userdata('pengguna_username');

			$duplicate = $this->M_PengeluaranBarang->Check_BKB_Duplicate($picking_order_aktual_kode); // pengecekan nama, optional bisa diremark bila tidak dibutuhkan

			$result = $this->M_PengeluaranBarang->Insert_PickingOrderAktual_H($picking_order_aktual_h_id, $picking_order_id, $picking_order_aktual_kode, $karyawan_id, $karyawan_nama);

			// if ($lastUpdatedChecked['status'] === 400) {
			// 	$response = [
			// 		'status' => 400,
			// 		'message' => 'Data Gagal Disimpan',
			// 	];
			// 	$this->db->trans_rollback();
			// } else 

			if ($this->db->trans_status() === FALSE) {
				$response = [
					'status' => 401,
					'message' => $this->db->error()['message'],
				];
				$this->db->trans_rollback();
			} else if ($duplicate > 0) {
				$response = [
					'status' => 401,
					'message' => 'Duplicate Name',
				];
				$this->db->trans_rollback();
			} else {
				$response = [
					'status' => 200,
					'message' => 'Data Berhasil Disimpan',
					// 'lastUpdatedNew' => $lastUpdatedChecked['lastUpdatedNew']
				];
				$this->db->trans_commit();
			}

			echo json_encode($response);
		}
	}

	public function CheckPickingOrderAktual_D()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('picking_order_aktual_h_id', 'BKB ID', 'required');
		$this->form_validation->set_rules('picking_order_plan_id', 'PB ID', 'required');
		$this->form_validation->set_rules('delivery_order_id', 'DO ID', 'required');
		$this->form_validation->set_rules('sku_id', 'SKU ID', 'required');
		$this->form_validation->set_rules('sku_stock_id', 'SKU Stock ID', 'required');
		$this->form_validation->set_rules('sku_stock_expired_date', 'SKU Expired Date', 'required');
		$this->form_validation->set_rules('sku_stock_qty_ambil', 'Aktual Qty Ambil', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			echo 1;
		}
	}

	public function SaveAddNewPickingOrderAktual_D()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Vrbl');
		$this->load->helper(array('form', 'url'));

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "C")) {
			echo 0;
			exit();
		}

		$data = $this->input->post('data');
		$picking_order_aktual_h_id = $this->input->post('picking_order_aktual_h_id');
		$qtyAktualTempArr = $this->input->post('qtyAktualTempArr');
		$arrScanPilihDetail = $this->input->post('arrScanPilihDetail');
		$arrScanPilihDetail2 = $this->input->post('arrScanPilihDetail2');

		$this->db->trans_begin();

		$Tipe = 'MASTER Pengeluaran Barang';
		$Inisial = 'MPB';

		$arrTemp = [];
		$data1 = [];
		$data2 = [];
		$result = [];

		$arrTemp2 = [];
		$data3 = [];
		$data4 = [];
		$result2 = [];

		$getCountNumberBKB = $this->M_PengeluaranBarang->Get_CountNumberBKB($data[0]['picking_order_id']);

		foreach ($data as $key => $value) {

			$picking_order_aktual_d_id = $this->M_Vrbl->Get_NewID();
			$picking_order_aktual_d_id = $picking_order_aktual_d_id[0]['NEW_ID'];

			if (!empty($qtyAktualTempArr)) {
				foreach ($qtyAktualTempArr as $key => $val) {
					if ($value['picking_order_plan_id'] == $val['picking_order_plan_id']) {
						array_push($arrTemp, ['picking_order_plan_id' => $val['picking_order_plan_id'], 'sku_stock_qty_ambil' => $val['value'], 'checked' => $val['checked']]);

						if ($val['checked'] == 'true') {
							$this->db->update('pallet', ['rak_lajur_detail_id' => NULL], ['pallet_id' => $val['pallet_id']]);

							$dataRak = $this->db->select("c.depo_id, c.depo_detail_id, b.rak_lajur_detail_id, a.pallet_id")
								->from("rak_lajur_detail_pallet a")
								->join('rak_lajur_detail b', 'a.rak_lajur_detail_id = b.rak_lajur_detail_id', 'left')
								->join('rak c', 'b.rak_id = c.rak_id', 'left')
								->where('a.pallet_id', $val['pallet_id'])->get()->row();
							$this->db->insert('rak_lajur_detail_pallet_his', [
								'rak_lajur_detail_pallet_id' => $this->M_Vrbl->Get_NewID()[0]['NEW_ID'],
								'depo_id' => $dataRak->depo_id,
								'depo_detail_id' => $dataRak->depo_detail_id,
								'rak_lajur_detail_id' => $dataRak->rak_lajur_detail_id,
								'pallet_id' => $dataRak->pallet_id,
								'tanggal_create' => date('Y-m-d H:i:s'),
								'who_create' => $this->session->userdata('pengguna_username'),
							]);

							$this->db->delete('rak_lajur_detail_pallet', ['pallet_id' => $val['pallet_id']]);
						}

						//update qty pallet detail
						$this->M_PengeluaranBarang->UpdateQtypaletDetail($val['pallet_detail_id'], $val['value']);

						//insert pallet ke aktual d 2
						$this->M_PengeluaranBarang->insertToAktualD2($picking_order_aktual_d_id, $val['pallet_id']);
					}
				}

				if (array_search($value['picking_order_plan_id'], array_column($qtyAktualTempArr, 'picking_order_plan_id')) !== false) {
					array_push($data2, [
						'picking_order_aktual_d_id' => $picking_order_aktual_d_id,
						'delivery_order_id' => $value['delivery_order_id'],
						'sku_id' => $value['sku_id'],
						'sku_stock_id' => $value['sku_stock_id'],
						'sku_stock_expired_date' => $value['sku_stock_expired_date'],
						'picking_order_id' => $value['picking_order_id'],
						'tipe_do' => $value['tipe_do'],
						'depo_detail_id' => $value['depo_detail_id'],
						'maxqty' => $value['maxqty']
					]);
				}
			}

			if (!empty($arrScanPilihDetail2)) {
				foreach ($arrScanPilihDetail2 as $key => $val) {
					if ($value['picking_order_plan_id'] == $val['picking_order_plan_id']) {

						array_push($arrTemp2, ['picking_order_plan_id' => $val['picking_order_plan_id'], 'sku_stock_qty_ambil' => $val['qtyAktual']]);

						//update qty pallet detail
						$this->M_PengeluaranBarang->UpdateQtypaletDetail($val['pallet_detail_id'], $val['qtyAktual']);

						//insert pallet ke aktual d 2
						$this->M_PengeluaranBarang->insertToAktualD2($picking_order_aktual_d_id, $val['pallet_id']);
					}
				}

				if (array_search($value['picking_order_plan_id'], array_column($arrScanPilihDetail2, 'picking_order_plan_id')) !== false) {
					array_push($data4, [
						'picking_order_aktual_d_id' => $picking_order_aktual_d_id,
						'delivery_order_id' => $value['delivery_order_id'],
						'sku_id' => $value['sku_id'],
						'sku_stock_id' => $value['sku_stock_id'],
						'sku_stock_expired_date' => $value['sku_stock_expired_date'],
						'picking_order_id' => $value['picking_order_id'],
						'tipe_do' => $value['tipe_do'],
						'depo_detail_id' => $value['depo_detail_id'],
						'maxqty' => $value['maxqty']
					]);
				}
			}
		}

		if (!empty($qtyAktualTempArr)) {
			$sum = array_reduce($arrTemp, function ($a, $b) {
				isset($a[$b['picking_order_plan_id']]) ? $a[$b['picking_order_plan_id']]['sku_stock_qty_ambil'] += $b['sku_stock_qty_ambil'] : $a[$b['picking_order_plan_id']] = $b;
				return $a;
			});


			foreach ($sum as $key => $valueSum) {
				array_push($data1, $valueSum);
			}


			foreach ($data2 as $key => $val) {
				// echo json_encode($key);
				array_push($result, [
					'picking_order_aktual_d_id' => $val['picking_order_aktual_d_id'],
					'delivery_order_id' => $val['delivery_order_id'],
					'sku_id' => $val['sku_id'],
					'sku_stock_id' => $val['sku_stock_id'],
					'sku_stock_expired_date' => $val['sku_stock_expired_date'],
					'picking_order_id' => $val['picking_order_id'],
					'tipe_do' => $val['tipe_do'],
					'depo_detail_id' => $val['depo_detail_id'],
					'maxqty' => $val['maxqty'],
					'no_urut' => $getCountNumberBKB + $key + 1,
					'picking_order_plan_id' => $data1[$key]['picking_order_plan_id'],
					'sku_stock_qty_ambil' => $data1[$key]['sku_stock_qty_ambil'],
					'checked' => $data1[$key]['checked']
				]);
			}



			foreach ($result as $key => $val) {

				//insert ke picking order aktual D
				$this->M_PengeluaranBarang->Insert_PickingOrderAktual_D($val, $picking_order_aktual_h_id);

				//get sum qty ambil di tabel picking order aktual d
				$getData = $this->db->select("ISNULL(SUM(sku_stock_qty_ambil), 0) as qty_ambil")->from("picking_order_aktual_d")->where("picking_order_plan_id", $val['picking_order_plan_id'])->get()->row();

				if ($getData->qty_ambil == $val['maxqty']) {
					//update is_take di picking_order plan
					$this->M_PengeluaranBarang->updateDataIsTakeInPickingOrderPlan($val['picking_order_plan_id']);
				}
			}
		}

		if (!empty($arrScanPilihDetail2)) {
			$sum = array_reduce($arrTemp2, function ($a, $b) {
				isset($a[$b['picking_order_plan_id']]) ? $a[$b['picking_order_plan_id']]['sku_stock_qty_ambil'] += $b['sku_stock_qty_ambil'] : $a[$b['picking_order_plan_id']] = $b;
				return $a;
			});

			foreach ($sum as $key => $val) {
				array_push($data3, $val);
			}

			foreach ($data4 as $key => $val) {
				// echo json_encode($key);
				array_push($result2, [
					'picking_order_aktual_d_id' => $val['picking_order_aktual_d_id'],
					'delivery_order_id' => $val['delivery_order_id'],
					'sku_id' => $val['sku_id'],
					'sku_stock_id' => $val['sku_stock_id'],
					'sku_stock_expired_date' => $val['sku_stock_expired_date'],
					'picking_order_id' => $val['picking_order_id'],
					'tipe_do' => $val['tipe_do'],
					'depo_detail_id' => $val['depo_detail_id'],
					'maxqty' => $val['maxqty'],
					'no_urut' => $getCountNumberBKB + $key + 1,
					'picking_order_plan_id' => $data3[$key]['picking_order_plan_id'],
					'sku_stock_qty_ambil' => $data3[$key]['sku_stock_qty_ambil'],
					'checked' => 'false'
				]);
			}

			foreach ($result2 as $key => $val) {

				//insert ke picking order aktual D
				$this->M_PengeluaranBarang->Insert_PickingOrderAktual_D($val, $picking_order_aktual_h_id);

				//get sum qty ambil di tabel picking order aktual d
				$getData = $this->db->select("ISNULL(SUM(sku_stock_qty_ambil), 0) as qty_ambil")->from("picking_order_aktual_d")->where("picking_order_plan_id", $val['picking_order_plan_id'])->get()->row();

				if ($getData->qty_ambil == $val['maxqty']) {
					//update is_take di picking_order plan
					$this->M_PengeluaranBarang->updateDataIsTakeInPickingOrderPlan($val['picking_order_plan_id']);
				}
			}
		}


		//prosedure sku_stock_card
		$this->db->query("exec proses_posting_stock_card 'BKB', '$picking_order_aktual_h_id', '" . $this->session->userdata('pengguna_username') . "'");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->db->delete('picking_order_aktual_h', ['picking_order_aktual_h_id' => $picking_order_aktual_h_id]);
			$response = [
				'status' => 400,
				'message' => $this->db->error()['message'],
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'message' => 'Data berhasil disimpan',
			];
		}

		echo json_encode($response);
	}

	public function UpdateQtypaletDetail()
	{

		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$qtyAktualTempArr = $this->input->post('qtyAktualTempArr');

		$res = "";

		foreach ($qtyAktualTempArr as $key => $value) {
			//update qty pallet detail
			$chk = $this->M_PengeluaranBarang->UpdateQtypaletDetail($value['pallet_detail_id'], $value['value']);
			$res = $chk ? true : false;
		}

		echo json_encode($res);
	}

	public function SaveUpdatePengeluaranBarang()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Menu');

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "U")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('PengeluaranBarang_ID', 'ID', 'required');
		$this->form_validation->set_rules('PengeluaranBarang_Nama', 'Nama PengeluaranBarang', 'required');
		$this->form_validation->set_rules('PengeluaranBarang_Jenis', 'Jenis PengeluaranBarang', 'required');
		$this->form_validation->set_rules('PengeluaranBarang_Group', 'Grup PengeluaranBarang', 'required');
		$this->form_validation->set_rules('IsSPPBR', 'Status SPPBR', 'required');
		$this->form_validation->set_rules('Flag_EComm', 'Jenis E-Commerce', 'required');
		$this->form_validation->set_rules('PengeluaranBarang_IsActive', 'Status PengeluaranBarang', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$PengeluaranBarang_ID = $this->input->post('PengeluaranBarang_ID');
			$PengeluaranBarang_Nama = $this->input->post('PengeluaranBarang_Nama');
			$PengeluaranBarang_Jenis = $this->input->post('PengeluaranBarang_Jenis');
			$PengeluaranBarang_Group = $this->input->post('PengeluaranBarang_Group');
			$IsSPPBR = $this->input->post('IsSPPBR');
			$Flag_EComm = $this->input->post('Flag_EComm');
			$PengeluaranBarang_IsActive = $this->input->post('PengeluaranBarang_IsActive');

			$S_UserID = $this->session->userdata('pengguna_id');
			$S_pengguna_nama = $this->session->userdata('pengguna_username');

			$result = $this->M_PengeluaranBarang->Update_PengeluaranBarang($PengeluaranBarang_ID, $PengeluaranBarang_Nama, $PengeluaranBarang_Jenis, $PengeluaranBarang_Group, $IsSPPBR, $Flag_EComm, $PengeluaranBarang_IsActive, $S_pengguna_nama, $S_UserID);

			echo $result;
		}
	}

	public function DeletePengeluaranBarangMenu()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Menu');

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "D")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('PengeluaranBarangID', 'ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$PengeluaranBarangID = $this->input->post('PengeluaranBarangID');

			$result = $this->M_PengeluaranBarang->Delete_PengeluaranBarang($PengeluaranBarangID);

			echo $result;
		}
	}

	public function CheckQtyAmbil()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$sku_stock_id = $this->input->post('sku_stock_id');

		$CheckQty = $this->M_PengeluaranBarang->Check_Qty_Ambil($sku_stock_id);

		echo json_encode($CheckQty);
	}

	public function CheckQtyPlan()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$picking_order_plan_id = $this->input->post('picking_order_plan_id');

		$CheckQty = $this->M_PengeluaranBarang->Check_Qty_Plan($picking_order_plan_id);

		echo json_encode($CheckQty);
	}

	public function CheckIsTakePickingOrderPlan()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$picking_order_plan_id = $this->input->post('picking_order_plan_id');

		$ChkIsTake = $this->M_PengeluaranBarang->CheckIsTakePickingOrderPlan($picking_order_plan_id);

		echo json_encode($ChkIsTake);
	}

	public function GetCountNumberBKB()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$picking_order_id = $this->input->post('picking_order_id');

		$no = $this->M_PengeluaranBarang->Get_CountNumberBKB($picking_order_id);

		echo $no;
	}

	public function CetakBKBBulk()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Menu');

		$picking_order_id = $this->input->get('picking_order_id');
		$driverppb = $this->input->get('driver');
		$tipe = $this->input->get('tipe');
		$data['tipe'] = $this->input->get('tipe');
		$cetak = $this->input->get('cetak');
		$UpdWho = $this->session->userdata('pengguna_username');

		$this->load->helper(array('form', 'url'));

		// $result = $this->M_WMSMini->Update_CetakManifestDelieryOrder( $DO_ID, $UpdWho );

		// echo $result;

		// $data['bkbbulk'] = $this->M_PengeluaranBarang->Get_BKBBulkById($picking_order_id);
		// $data['bkbbulk'] = $this->M_PengeluaranBarang->Get_BKBBulkById($picking_order_id);
		$data['driverppb'] = $driverppb;
		$data['bkbbulkheader'] = $this->M_PengeluaranBarang->Get_BKBBulkByIdHeader($picking_order_id);

		if ($tipe == "plan") {

			if ($cetak == 'allprinciple') {
				$data['bkbbulk'] = $this->M_PengeluaranBarang->Get_BKBBulkById($picking_order_id, $cetak);
			} else {
				$hasil2 = $this->M_PengeluaranBarang->Get_BKBBulkById($picking_order_id, $cetak);

				$arr = [];
				foreach ($hasil2 as  $value) {
					$key = $value->principal;

					if (!isset($arr[$key])) {
						$arr[$key][] = $value;
					} else {
						$arr[$key][] = $value;
					}
				}

				$data['bkbbulk'] = array_values($arr);
			}
		} else if ($tipe == "aktual") {

			if ($cetak == 'allprinciple') {
				$data['bkbbulk'] = $this->M_PengeluaranBarang->Get_BKBBulkAktualById($picking_order_id, $cetak);
			} else {
				$hasil2 = $this->M_PengeluaranBarang->Get_BKBBulkAktualById($picking_order_id, $cetak);

				$arr = [];
				foreach ($hasil2 as  $value) {
					$key = $value->principal;

					if (!isset($arr[$key])) {
						$arr[$key][] = $value;
					} else {
						$arr[$key][] = $value;
					}
				}

				$data['bkbbulk'] = array_values($arr);
			}
		}

		$data['mode'] = 'satuan';

		$data['cetak'] = $cetak;

		$this->load->view('WMS/PengeluaranBarang/report/CetakBKBBulk', $data);
	}

	public function CetakBKBBulkComposite()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Menu');
		$this->load->model('M_Vrbl');

		$picking_order_id = $this->input->get('picking_order_id');
		$driverppb = $this->input->get('driver');
		$tipe = $this->input->get('tipe');
		$cetak = $this->input->get('cetak');
		$UpdWho = $this->session->userdata('pengguna_username');

		$this->load->helper(array('form', 'url'));

		$data['driverppb'] = $driverppb;
		$data['bkbbulkheader'] = $this->M_PengeluaranBarang->Get_BKBBulkByIdHeader($picking_order_id);

		if ($tipe == "plan") {

			$composite = $this->M_PengeluaranBarang->Get_BKBBulkByIdComposite($picking_order_id, null, $cetak);

			// insert sku_konversi_temp
			$sku_konversi_temp = $this->M_Vrbl->Get_NewID();
			$sku_konversi_temp = $sku_konversi_temp[0]['NEW_ID'];
			foreach ($composite as $key => $value) {
				$insert = $this->M_PengeluaranBarang->insertSKUKonversiTemp($sku_konversi_temp, $value->sku_id, $value->sku_stock_expired_date, $value->sku_stock_qty_ambil, $value->sku_qty_composite, $value->total_ambil, $value->total_ambil_composite, $value->batch_no, $tipe);
			}

			$hasilExec = $this->M_PengeluaranBarang->execProsesKonversiComposite($sku_konversi_temp);

			if ($cetak == 'allprinciple') {
				$data['bkbbulk'] = $this->M_PengeluaranBarang->Get_BKBBulkByIdComposite($picking_order_id, $hasilExec, $cetak);
			} else {
				$hasil2 = $this->M_PengeluaranBarang->Get_BKBBulkByIdComposite($picking_order_id, $hasilExec, $cetak);

				$arr = [];
				foreach ($hasil2 as  $value) {
					$key = $value->principal;

					if (!isset($arr[$key])) {
						$arr[$key][] = $value;
					} else {
						$arr[$key][] = $value;
					}
				}

				$data['bkbbulk'] = array_values($arr);
			}
		} else if ($tipe == "aktual") {


			$composite = $this->M_PengeluaranBarang->Get_BKBBulkAktualByIdComposite($picking_order_id, null, $cetak);

			// insert sku_konversi_temp
			$sku_konversi_temp = $this->M_Vrbl->Get_NewID();
			$sku_konversi_temp = $sku_konversi_temp[0]['NEW_ID'];
			foreach ($composite as $key => $value) {
				$insert = $this->M_PengeluaranBarang->insertSKUKonversiTemp($sku_konversi_temp, $value->sku_id, $value->sku_stock_expired_date, $value->sku_stock_qty_ambil, $value->sku_qty_composite, $value->total_ambil, $value->total_ambil_composite, $value->batch_no, $tipe);
			}

			$hasilExec = $this->M_PengeluaranBarang->execProsesKonversiComposite($sku_konversi_temp);

			if ($cetak == 'allprinciple') {
				$data['bkbbulk'] = $this->M_PengeluaranBarang->Get_BKBBulkAktualByIdComposite($picking_order_id, $hasilExec, $cetak);
			} else {
				$hasil2 = $this->M_PengeluaranBarang->Get_BKBBulkAktualByIdComposite($picking_order_id, $hasilExec, $cetak);

				$arr = [];
				foreach ($hasil2 as  $value) {
					$key = $value->principal;

					if (!isset($arr[$key])) {
						$arr[$key][] = $value;
					} else {
						$arr[$key][] = $value;
					}
				}

				$data['bkbbulk'] = array_values($arr);
			}
		}

		$data['tipe'] = $this->input->get('tipe');
		$data['mode'] = 'composite';
		$data['cetak'] = $cetak;

		$this->load->view('WMS/PengeluaranBarang/report/CetakBKBBulk', $data);
	}

	public function CetakBKBKirimUlang()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Menu');

		$picking_order_id = $this->input->get('picking_order_id');
		$driverppb = $this->input->get('driver');
		$tipe = $this->input->get('tipe');
		$data['tipe'] = $this->input->get('tipe');
		$cetak = $this->input->get('cetak');
		$UpdWho = $this->session->userdata('pengguna_username');

		$this->load->helper(array('form', 'url'));


		// $result = $this->M_WMSMini->Update_CetakManifestDelieryOrder( $DO_ID, $UpdWho );

		// echo $result;

		// $data['bkbbulk'] = $this->M_PengeluaranBarang->Get_BKBBulkById($picking_order_id);
		// $data['bkbbulk'] = $this->M_PengeluaranBarang->Get_BKBBulkById($picking_order_id);
		$data['driverppb'] = $driverppb;
		$data['bkbkirimulangheader'] = $this->M_PengeluaranBarang->Get_BKBBulkByIdHeader($picking_order_id);

		if ($tipe == "plan") {
			if ($cetak == 'allprinciple') {
				$data['bkbkirimulang'] = $this->M_PengeluaranBarang->Get_BKBKirimUlangById($picking_order_id, $cetak);
			} else {
				$hasil2 = $this->M_PengeluaranBarang->Get_BKBKirimUlangById($picking_order_id, $cetak);

				$arr = [];
				foreach ($hasil2 as  $value) {
					$key = $value->principal;

					if (!isset($arr[$key])) {
						$arr[$key][] = $value;
					} else {
						$arr[$key][] = $value;
					}
				}

				$data['bkbkirimulang'] = array_values($arr);
			}
		} else if ($tipe == "aktual") {

			if ($cetak == 'allprinciple') {
				$data['bkbkirimulang'] = $this->M_PengeluaranBarang->Get_BKBKirimUlangAktualById($picking_order_id, $cetak);
			} else {
				$hasil2 = $this->M_PengeluaranBarang->Get_BKBKirimUlangAktualById($picking_order_id, $cetak);

				$arr = [];
				foreach ($hasil2 as  $value) {
					$key = $value->principal;

					if (!isset($arr[$key])) {
						$arr[$key][] = $value;
					} else {
						$arr[$key][] = $value;
					}
				}

				$data['bkbkirimulang'] = array_values($arr);
			}
		}

		$data['mode'] = 'satuan';

		$data['cetak'] = $cetak;

		$this->load->view('WMS/PengeluaranBarang/report/CetakBKBKirimUlang', $data);
	}

	public function CetakBKBKirimUlangComposite()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Menu');
		$this->load->model('M_Vrbl');

		$picking_order_id = $this->input->get('picking_order_id');
		$driverppb = $this->input->get('driver');
		$tipe = $this->input->get('tipe');
		$cetak = $this->input->get('cetak');
		$UpdWho = $this->session->userdata('pengguna_username');

		$this->load->helper(array('form', 'url'));

		$data['driverppb'] = $driverppb;
		$data['bkbkirimulangheader'] = $this->M_PengeluaranBarang->Get_BKBBulkByIdHeader($picking_order_id);

		if ($tipe == "plan") {


			$composite = $this->M_PengeluaranBarang->Get_BKBKirimUlangByIdComposite($picking_order_id, null, $cetak);

			// insert sku_konversi_temp
			$sku_konversi_temp = $this->M_Vrbl->Get_NewID();
			$sku_konversi_temp = $sku_konversi_temp[0]['NEW_ID'];
			foreach ($composite as $key => $value) {
				$insert = $this->M_PengeluaranBarang->insertSKUKonversiTemp($sku_konversi_temp, $value->sku_id, $value->sku_stock_expired_date, $value->sku_stock_qty_ambil, $value->sku_qty_composite, $value->total_ambil, $value->total_ambil_composite, $value->batch_no, $tipe);
			}

			$hasilExec = $this->M_PengeluaranBarang->execProsesKonversiComposite($sku_konversi_temp);

			if ($cetak == 'allprinciple') {
				$data['bkbkirimulang'] = $this->M_PengeluaranBarang->Get_BKBKirimUlangByIdComposite($picking_order_id, $hasilExec, $cetak);
			} else {
				$hasil2 = $this->M_PengeluaranBarang->Get_BKBKirimUlangByIdComposite($picking_order_id, $hasilExec, $cetak);

				$arr = [];
				foreach ($hasil2 as  $value) {
					$key = $value->principal;

					if (!isset($arr[$key])) {
						$arr[$key][] = $value;
					} else {
						$arr[$key][] = $value;
					}
				}

				$data['bkbkirimulang'] = array_values($arr);
			}
		} else if ($tipe == "aktual") {

			$composite = $this->M_PengeluaranBarang->Get_BKBKirimUlangAktualByIdComposite($picking_order_id, null, $cetak);

			// insert sku_konversi_temp
			$sku_konversi_temp = $this->M_Vrbl->Get_NewID();
			$sku_konversi_temp = $sku_konversi_temp[0]['NEW_ID'];
			foreach ($composite as $key => $value) {
				$insert = $this->M_PengeluaranBarang->insertSKUKonversiTemp($sku_konversi_temp, $value->sku_id, $value->sku_stock_expired_date, $value->sku_stock_qty_ambil, $value->sku_qty_composite, $value->total_ambil, $value->total_ambil_composite, $value->batch_no, $tipe);
			}

			$hasilExec = $this->M_PengeluaranBarang->execProsesKonversiComposite($sku_konversi_temp);

			if ($cetak == 'allprinciple') {
				$data['bkbkirimulang'] = $this->M_PengeluaranBarang->Get_BKBKirimUlangAktualByIdComposite($picking_order_id, $hasilExec, $cetak);
			} else {
				$hasil2 = $this->M_PengeluaranBarang->Get_BKBKirimUlangAktualByIdComposite($picking_order_id, $hasilExec, $cetak);

				$arr = [];
				foreach ($hasil2 as  $value) {
					$key = $value->principal;

					if (!isset($arr[$key])) {
						$arr[$key][] = $value;
					} else {
						$arr[$key][] = $value;
					}
				}

				$data['bkbkirimulang'] = array_values($arr);
			}
		}

		$data['tipe'] = $this->input->get('tipe');
		$data['mode'] = 'composite';

		$data['cetak'] = $cetak;

		$this->load->view('WMS/PengeluaranBarang/report/CetakBKBKirimUlang', $data);
	}

	// public function CetakBKBCanvas()
	// {
	// 	$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
	// 	$this->load->model('M_Menu');

	// 	$picking_order_id = $this->input->get('picking_order_id');
	// 	$driverppb = $this->input->get('driver');
	// 	$UpdWho = $this->session->userdata('pengguna_username');

	// 	$this->load->helper(array('form', 'url'));


	// 	// $result = $this->M_WMSMini->Update_CetakManifestDelieryOrder( $DO_ID, $UpdWho );

	// 	// echo $result;

	// 	// $data['bkbbulk'] = $this->M_PengeluaranBarang->Get_BKBBulkById($picking_order_id);
	// 	$data['bkbcanvas'] = $this->M_PengeluaranBarang->Get_BKBBulkById($picking_order_id);
	// 	$data['driverppb'] = $driverppb;

	// 	$this->load->view('WMS/PengeluaranBarang/report/CetakBKBCanvas', $data);
	// }

	// public function getCetakBKB()
	// {

	// 	$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

	// 	$dataBKB = $this->M_PengeluaranBarang->Get_BKBBulkById($this->input->post('pickingOrderId'));

	// 	$tmpgroup = [];
	// 	$group = [];

	// 	$group2 = [];

	// 	foreach ($dataBKB as $key => $data) {
	// 		$tmpdata = $data;
	// 		unset($tmpdata['principal']);
	// 		unset($tmpdata['sku_kode']);
	// 		unset($tmpdata['sku_nama_produk']);
	// 		unset($tmpdata['sku_stock_qty_ambil']);
	// 		unset($tmpdata['depo_detail_nama']);
	// 		$tmpgroup[$data['principal']]['principal'] = $data['principal'];
	// 		$tmpgroup[$data['principal']]['sku_kode'] = $data['sku_kode'];
	// 		$tmpgroup[$data['principal']]['sku_nama_produk'] = $data['sku_nama_produk'];
	// 		$tmpgroup[$data['principal']]['sku_stock_qty_ambil'] = $data['sku_stock_qty_ambil'];
	// 		$tmpgroup[$data['principal']]['depo_detail_nama'] = $data['depo_detail_nama'];
	// 		$tmpgroup[$data['principal']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
	// 	}

	// 	foreach ($tmpgroup as $key => $data) {
	// 		$tmpdata = $data;
	// 		// unset($tmpdata['principal']);
	// 		$knl = [];

	// 		foreach ($tmpdata['data'] as $key => $value) {
	// 			$tmpdataaa = $value;
	// 			// unset($tmpdataaa['ambil2']);
	// 			// unset($tmpdataaa['rak_lajur_detail_nama']);
	// 			$knl[$value['ambil2']]['ambil2'] = $value['ambil2'];
	// 			// $knl[$value['ambil2']]['rak_lajur_detail_nama'] = $value['rak_lajur_detail_nama'];
	// 			$knl[$value['ambil2']]['data'][] = $tmpdataaa;
	// 		}

	// 		foreach ($knl as $key => $value) {
	// 			$tmpAkhir = $value;
	// 			// echo json_encode($tmpAkhir);
	// 			// die;

	// 			// unset($tmpAkhir['ambil2']);
	// 			$knla = [];

	// 			foreach ($tmpAkhir['data'] as $key => $val) {
	// 				$tmpdataaaff = $val;

	// 				unset($tmpdataaaff['rak_lajur_detail_nama']);
	// 				// unset($tmpdataaaff['ambil2']);
	// 				$knla[$val['rak_lajur_detail_nama']]['rak_lajur_detail_nama'] = $val['rak_lajur_detail_nama'];
	// 				$knla[$val['rak_lajur_detail_nama']]['ambil2'] = $tmpAkhir['ambil2'];
	// 				$knla[$val['rak_lajur_detail_nama']]['data'][] = $tmpdataaaff;
	// 			}
	// 			$group3 = [];

	// 			foreach ($knla as $key => $v) {
	// 				$tmpAkhirrr = $v;
	// 				// unset($tmpAkhir['ambil2']);
	// 				$group3[] = $v;
	// 			}

	// 			unset($tmpAkhir['data']);
	// 			$tmpAkhir['data'] = $group3;
	// 			$group2[] = $tmpAkhir;
	// 		}

	// 		unset($tmpdata['data']);
	// 		$tmpdata['data'] = $group2;
	// 		$group[$data['principal']] = $tmpdata;
	// 	}
	// 	// echo json_encode($group2);
	// 	echo json_encode($dataBKB);
	// }

	public function CetakBKBStandar()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Menu');

		$picking_order_id = $this->input->get('picking_order_id');
		$UpdWho = $this->session->userdata('pengguna_username');

		$this->load->helper(array('form', 'url'));


		// $result = $this->M_WMSMini->Update_CetakManifestDelieryOrder( $DO_ID, $UpdWho );

		// echo $result;

		$data['bkbstandar'] = $this->M_PengeluaranBarang->Get_BKBStandarById($picking_order_id);
		// $data['title'] = 'label resi';
		// var_dump($data);
		$this->load->view('WMS/PengeluaranBarang/report/CetakBKBStandar', $data);
	}

	public function CetakLabel()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('pdfgenerator');

		$picking_order_id = $this->input->get('picking_order_id');

		// $data['bkbstandar'] = $this->M_PengeluaranBarang->Get_CetakLabel($picking_order_id);

		$arr = $this->M_PengeluaranBarang->Get_CetakLabel($picking_order_id);
		$tmpgroup = [];
		$group = [];

		foreach ($arr as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['picking_order_aktual_h_id']);
			unset($tmpdata['picking_order_aktual_kode']);
			unset($tmpdata['picking_order_aktual_tgl']);
			unset($tmpdata['delivery_order_kode']);
			unset($tmpdata['picking_list_kode']);
			unset($tmpdata['driver_nama']);
			unset($tmpdata['karyawan_nama']);
			$tmpgroup[$data['picking_order_aktual_h_id']]['picking_order_aktual_h_id'] = $data['picking_order_aktual_h_id'];
			$tmpgroup[$data['picking_order_aktual_h_id']]['picking_order_aktual_kode'] = $data['picking_order_aktual_kode'];
			$tmpgroup[$data['picking_order_aktual_h_id']]['picking_order_aktual_tgl'] = $data['picking_order_aktual_tgl'];
			$tmpgroup[$data['picking_order_aktual_h_id']]['delivery_order_kode'] = $data['delivery_order_kode'];
			$tmpgroup[$data['picking_order_aktual_h_id']]['picking_list_kode'] = $data['picking_list_kode'];
			$tmpgroup[$data['picking_order_aktual_h_id']]['driver_nama'] = $data['driver_nama'];
			$tmpgroup[$data['picking_order_aktual_h_id']]['karyawan_nama'] = $data['karyawan_nama'];
			// $tmpgroup[$data['picking_order_aktual_kode']]['instruksi'] = $data['instruksi'];
			$tmpgroup[$data['picking_order_aktual_h_id']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}

		foreach ($tmpgroup as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['picking_order_aktual_h_id']);
			$group[$data['picking_order_aktual_h_id']] = $tmpdata;
		}

		$data = [
			'title' => 'label Keranjang',
			'label' => $group
		];

		// echo json_encode($group);
		// die;

		// filename dari pdf ketika didownload

		$file_pdf = 'Label keranjang';

		// setting paper

		// $paper = array(0, 0, 290, 491);
		// // $paper = array(0, 0, 377, 566);

		// //orientasi paper potrait / landscape

		// $orientation = "landscape";
		$paper = array(0, 0, 427, 336);

		$orientation = "landscape";

		// $this->load->view('master/PengeluaranBarang/report/CetakLabelKeranjang', $data);

		$html = $this->load->view('WMS/PengeluaranBarang/report/CetakLabelKeranjang', $data, true);

		// return $html;

		// run dompdf

		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}

	public function CetakResi()
	{
		$this->load->model('M_WMSMini');
		$this->load->model('M_Menu');

		if (!$this->M_Menu->CheckMenu($this->session->userdata('UserGroupID'), $this->MenuKode, "D")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->load->library('pdfgenerator');

		$DO_ID = $this->input->get('DO_ID');
		$UpdWho = $this->session->userdata('pengguna_username');

		$result = $this->M_WMSMini->Update_CetakResiDelieryOrder($DO_ID, $UpdWho);
		$arr = $this->M_WMSMini->Get_CetakResi($DO_ID);


		$tmpgroup = [];
		$group = [];

		foreach ($arr as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['reff_id']);
			unset($tmpdata['no_resi']);
			unset($tmpdata['weight']);
			unset($tmpdata['tipe_pengiriman']);
			unset($tmpdata['nama_penerima']);
			unset($tmpdata['alamat_penerima']);
			unset($tmpdata['no_penerima']);
			unset($tmpdata['instruksi']);
			$tmpgroup[$data['reff_id']]['no_resi'] = $data['no_resi'];
			$tmpgroup[$data['reff_id']]['weight'] = $data['weight'];
			$tmpgroup[$data['reff_id']]['reff_id'] = $data['reff_id'];
			$tmpgroup[$data['reff_id']]['tipe_pengiriman'] = $data['tipe_pengiriman'];
			$tmpgroup[$data['reff_id']]['nama_penerima'] = $data['nama_penerima'];
			$tmpgroup[$data['reff_id']]['alamat_penerima'] = $data['alamat_penerima'];
			$tmpgroup[$data['reff_id']]['no_penerima'] = $data['no_penerima'];
			$tmpgroup[$data['reff_id']]['instruksi'] = $data['instruksi'];
			$tmpgroup[$data['reff_id']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}
		foreach ($tmpgroup as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['reff_id']);
			$group[$data['reff_id']] = $tmpdata;
		}

		$data = [
			'title' => 'label resi',
			'label' => $group
		];


		// filename dari pdf ketika didownload

		$file_pdf = 'Label resi';

		// setting paper

		// $paper = 'A6';
		// $paper = array(0, 0, 290, 491);

		// //orientasi paper potrait / landscape

		// $orientation = "landscape";

		$paper = array(0, 0, 427, 336);

		$orientation = "portrait";



		$html = $this->load->view('WMS/WMSMini/report/CetakLabelResi', $data, true);


		// run dompdf

		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}

	public function GetCheckerBKBPrinciple()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$principal = $this->input->post('principal');

		$data['Checker'] = $this->M_PengeluaranBarang->Get_Checker_BKB_Principal($principal);

		echo json_encode($data);
	}

	public function GetLocationRak()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$depo_detail_id = $this->input->post('depo_detail_id');
		$sku_id = $this->input->post('sku_id');
		$expired_date = $this->input->post('expired_date');
		$result = $this->M_PengeluaranBarang->GetLocationRak($depo_detail_id, $sku_id, $expired_date);

		$tmpgroup = [];
		$group = [];

		foreach ($result as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['nama']);

			$tmpgroup[$data['nama']]['nama'] = $data['nama'];

			$tmpgroup[$data['nama']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}
		foreach ($tmpgroup as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['nama']);
			$group[$data['nama']] = $tmpdata;
		}
		echo json_encode($group);
	}


	public function GetDetailRakPalletById()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$sku_kode = $this->input->post('skuKode');
		$expiredDate = $this->input->post('expiredDate');
		$rak_lajur_detail_id = $this->input->post('rak_lajur_detail_id');
		$sameSKU = $this->input->post('sameSKU');
		$result = $this->M_PengeluaranBarang->GetDetailRakPalletById($sku_kode, $expiredDate, $rak_lajur_detail_id, $sameSKU);

		$tmpgroup = [];
		$group = [];

		foreach ($result as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['pallet_id']);
			unset($tmpdata['pallet_jenis_nama']);
			unset($tmpdata['pallet_kode']);

			$tmpgroup[$data['pallet_id']]['pallet_id'] = $data['pallet_id'];
			$tmpgroup[$data['pallet_id']]['pallet_jenis_nama'] = $data['pallet_jenis_nama'];
			$tmpgroup[$data['pallet_id']]['pallet_kode'] = $data['pallet_kode'];

			$tmpgroup[$data['pallet_id']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}
		foreach ($tmpgroup as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['pallet_id']);
			$group[$data['pallet_id']] = $tmpdata;
		}
		echo json_encode($group);
	}

	public function CheckScanPalletInRak()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');

		$kode_pallet = $this->input->post('kode');
		$typeInput = $this->input->post('typeInput');
		$skuKode = $this->input->post('skuKode');
		$palletId = $this->input->post('palletId');

		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PengeluaranBarang->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		// if ($typeInput == "scan-sku") {
		// 	$kode = preg_replace('/\s+/', '', $kode_pallet);
		// } else {
		// }
		$kode = preg_replace('/\s+/', '', $unit  . "/" . $kode_pallet);

		if ($typeInput == "non-pilih") {
			$depo_detail_id = $this->input->post('depo_detail_id');
			$nama_rak = $this->input->post('nama_rak');

			$result = $this->M_PengeluaranBarang->CheckScanPalletInRak($depo_detail_id, $nama_rak, $kode, $typeInput, $palletId, null);

			if ($result == null) {
				echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan", 'kode' => $kode));
			} else {
				echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> valid", 'kode' => $kode, 'data' => $result));
			}
		}

		// if ($typeInput == "pilih") {
		// 	$result = $this->M_PengeluaranBarang->CheckScanPalletInRak(null, null, $kode, $typeInput, $palletId);

		// 	if ($result == null) {
		// 		echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan", 'kode' => $kode));
		// 	} else {
		// 		echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> valid", 'kode' => $kode, 'data' => $result));
		// 	}
		// }

		if ($typeInput == "scan-sku") {
			$result = $this->M_PengeluaranBarang->CheckScanPalletInRak(null, null, $kode, $typeInput, $palletId, $skuKode);

			if (empty($result)) {
				echo json_encode(array('type' => 201, 'message' => "Pallet <strong>" . $kode . "</strong> tidak ditemukan", 'kode' => $kode));
			} else {
				echo json_encode(array('type' => 200, 'message' => "Pallet <strong>" . $kode . "</strong> valid", 'kode' => $kode, 'data' => $result));
			}
		}

		// get prefik depo


		// $result = $this->M_PengeluaranBarang->CheckScanPalletInRak($depo_detail_id, $pallet_id, $nama_rak, $kode);
		// echo json_encode($result);
	}

	public function getKodeAutoComplete()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$valueParams = $this->input->get('params');
		$type = $this->input->get('type');
		$result = $this->M_PengeluaranBarang->getKodeAutoComplete($valueParams, $type);
		echo json_encode($result);
	}

	public function getPalletBySkuId()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$result = $this->M_PengeluaranBarang->getPalletBySkuId($this->input->post('sku_id'));
		echo json_encode($result);
	}

	public function CetakBKBBulkAktual()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Menu');

		$picking_order_aktual_h_id = $this->input->get('picking_order_aktual_h_id');
		$tipe = $this->input->get('tipe');
		$cetak = $this->input->get('cetak');
		$UpdWho = $this->session->userdata('pengguna_username');

		$this->load->helper(array('form', 'url'));

		$data['header'] = $this->M_PengeluaranBarang->Get_HeaderBKBAktual($picking_order_aktual_h_id);
		if ($cetak == 'allprinciple') {
			$data['detail'] = $this->M_PengeluaranBarang->Get_DetailBKBAktual($picking_order_aktual_h_id);
		} else {
			$hasil2 = $this->M_PengeluaranBarang->Get_DetailBKBAktual($picking_order_aktual_h_id);

			$arr = [];
			foreach ($hasil2 as  $value) {
				$key = $value->principal;

				if (!isset($arr[$key])) {
					$arr[$key][] = $value;
				} else {
					$arr[$key][] = $value;
				}
			}

			$data['detail'] = array_values($arr);
		}

		$data['cetak'] = $cetak;

		$this->load->view('WMS/PengeluaranBarang/report/CetakBKBBulkAktual', $data);
	}
	public function CetakBKBCanvas()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Menu');

		$picking_order_id = $this->input->get('picking_order_id');
		$driverppb = $this->input->get('driver');
		$tipe = $this->input->get('tipe');
		$data['tipe'] = $this->input->get('tipe');
		$cetak = $this->input->get('cetak');
		$UpdWho = $this->session->userdata('pengguna_username');

		$this->load->helper(array('form', 'url'));

		// $result = $this->M_WMSMini->Update_CetakManifestDelieryOrder( $DO_ID, $UpdWho );

		// echo $result;

		// $data['bkbbulk'] = $this->M_PengeluaranBarang->Get_BKBBulkById($picking_order_id);
		// $data['bkbbulk'] = $this->M_PengeluaranBarang->Get_BKBBulkById($picking_order_id);
		$data['driverppb'] = $driverppb;
		$data['bkbcanvasheader'] = $this->M_PengeluaranBarang->Get_BKBBulkByIdHeader($picking_order_id);

		if ($tipe == "plan") {

			if ($cetak == 'allprinciple') {
				$data['bkbcanvas'] = $this->M_PengeluaranBarang->Get_BKBCanvasById($picking_order_id, $cetak);
			} else {
				$hasil2 = $this->M_PengeluaranBarang->Get_BKBCanvasById($picking_order_id, $cetak);

				$arr = [];
				if (count($hasil2) != 0) {
					foreach ($hasil2 as  $value) {
						$key = $value->principal;

						if (!isset($arr[$key])) {
							$arr[$key][] = $value;
						} else {
							$arr[$key][] = $value;
						}
					}
				}

				$data['bkbcanvas'] = array_values($arr);
			}
		} else if ($tipe == "aktual") {

			if ($cetak == 'allprinciple') {
				$data['bkbcanvas'] = $this->M_PengeluaranBarang->Get_BKBCanvasAktualById($picking_order_id, $cetak);
			} else {
				$hasil2 = $this->M_PengeluaranBarang->Get_BKBCanvasAktualById($picking_order_id, $cetak);
				$arr = [];
				if (count($hasil2) != 0) {
					foreach ($hasil2 as  $value) {
						$key = $value->principal;

						if (!isset($arr[$key])) {
							$arr[$key][] = $value;
						} else {
							$arr[$key][] = $value;
						}
					}
				}

				$data['bkbcanvas'] = array_values($arr);
			}
		}

		$data['mode'] = 'satuan';

		$data['cetak'] = $cetak;

		$this->load->view('WMS/PengeluaranBarang/report/CetakBKBCanvas', $data);
	}

	public function CetakBKBCanvasComposite()
	{
		$this->load->model('WMS/M_PengeluaranBarang', 'M_PengeluaranBarang');
		$this->load->model('M_Menu');
		$this->load->model('M_Vrbl');

		$picking_order_id = $this->input->get('picking_order_id');
		$driverppb = $this->input->get('driver');
		$tipe = $this->input->get('tipe');
		$cetak = $this->input->get('cetak');
		$UpdWho = $this->session->userdata('pengguna_username');

		$this->load->helper(array('form', 'url'));

		$data['driverppb'] = $driverppb;
		$data['bkbcanvasheader'] = $this->M_PengeluaranBarang->Get_BKBBulkByIdHeader($picking_order_id);

		if ($tipe == "plan") {

			$composite = $this->M_PengeluaranBarang->Get_BKBCanvasByIdComposite($picking_order_id, null, $cetak);

			// insert sku_konversi_temp
			$sku_konversi_temp = $this->M_Vrbl->Get_NewID();
			$sku_konversi_temp = $sku_konversi_temp[0]['NEW_ID'];
			foreach ($composite as $key => $value) {
				$insert = $this->M_PengeluaranBarang->insertSKUKonversiTemp($sku_konversi_temp, $value->sku_id, $value->sku_stock_expired_date, $value->sku_stock_qty_ambil, $value->sku_qty_composite, $value->total_ambil, $value->total_ambil_composite, $value->batch_no, $tipe);
			}

			$hasilExec = $this->M_PengeluaranBarang->execProsesKonversiComposite($sku_konversi_temp);

			if ($cetak == 'allprinciple') {
				$data['bkbcanvas'] = $this->M_PengeluaranBarang->Get_BKBCanvasByIdComposite($picking_order_id, $hasilExec, $cetak);
			} else {
				$hasil2 = $this->M_PengeluaranBarang->Get_BKBCanvasByIdComposite($picking_order_id, $hasilExec, $cetak);

				$arr = [];
				if (count($hasil2) != 0) {
					foreach ($hasil2 as  $value) {
						$key = $value->principal;

						if (!isset($arr[$key])) {
							$arr[$key][] = $value;
						} else {
							$arr[$key][] = $value;
						}
					}
				}

				$data['bkbcanvas'] = array_values($arr);
			}
		} else if ($tipe == "aktual") {


			$composite = $this->M_PengeluaranBarang->Get_BKBCanvasAktualByIdComposite($picking_order_id, null, $cetak);

			// insert sku_konversi_temp
			$sku_konversi_temp = $this->M_Vrbl->Get_NewID();
			$sku_konversi_temp = $sku_konversi_temp[0]['NEW_ID'];
			foreach ($composite as $key => $value) {
				$insert = $this->M_PengeluaranBarang->insertSKUKonversiTemp($sku_konversi_temp, $value->sku_id, $value->sku_stock_expired_date, $value->sku_stock_qty_ambil, $value->sku_qty_composite, $value->total_ambil, $value->total_ambil_composite, $value->batch_no, $tipe);
			}

			$hasilExec = $this->M_PengeluaranBarang->execProsesKonversiComposite($sku_konversi_temp);

			if ($cetak == 'allprinciple') {
				$data['bkbcanvas'] = $this->M_PengeluaranBarang->Get_BKBCanvasAktualByIdComposite($picking_order_id, $hasilExec, $cetak);
			} else {
				$hasil2 = $this->M_PengeluaranBarang->Get_BKBCanvasAktualByIdComposite($picking_order_id, $hasilExec, $cetak);

				$arr = [];
				if (count($hasil2) != 0) {
					foreach ($hasil2 as  $value) {
						$key = $value->principal;

						if (!isset($arr[$key])) {
							$arr[$key][] = $value;
						} else {
							$arr[$key][] = $value;
						}
					}
				}

				$data['bkbcanvas'] = array_values($arr);
			}
		}

		$data['tipe'] = $this->input->get('tipe');
		$data['mode'] = 'composite';
		$data['cetak'] = $cetak;

		$this->load->view('WMS/PengeluaranBarang/report/CetakBKBCanvas', $data);
	}
}
