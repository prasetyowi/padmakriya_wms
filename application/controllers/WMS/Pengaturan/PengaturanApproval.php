<?php

defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class PengaturanApproval extends ParentController

{
	private $MenuKode;
	private $url = 'WMS/Pengaturan/PengaturanApproval';

	public function __construct(String $url = '')
	{
		parent::__construct();

		$this->load->model('M_Menu');
		$this->load->model('M_Depo');
		$this->load->model('M_Function');
		$this->load->model('M_Vrbl');
		$this->load->model('WMS/M_PengaturanApproval', 'M_PengaturanApproval');
		$this->load->library('menusession');
		// $this->load->library('loadview');
		// $this->load->model('WMS/M_Approval', 'M_Approval');
		$this->MenuKode = "199005000";
		$this->$url = $url;

		$this->menusession->checkMenuAccess($this->MenuKode);
	}

	public function PengaturanApprovalMenu()
	{
		// Header('Content-Type: application/json');
		// echo json_encode($this->session->userdata('area')['area_id']);
		// exit;
		$data = array();
		// $data['area_id'] = $this->M_Depo->Getarea_by_depo_id($this->session->userdata('depo_id'));
		// $this->session->set_userdata('area', $data);

		// $this->menusession->checkMenuAccess($this->MenuKode);
		//load data menu and css file yg digunakan di header n sidebar

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css'
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		// $dataMenu = $this->menusession->getDataMenu();

		$data['depo'] = $this->M_Depo->Getdepo_by_depo_id($this->session->userdata('depo_id'));
		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/Pengaturan/PengaturanApproval/PengaturanApprovalMenu', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Pengaturan/PengaturanApproval/s_PengaturanApprovalMenu', $data);
	}
	public function PengaturanApprovalForm()
	{
		$data = array();

		// $this->menusession->checkMenuAccess($this->MenuKode);
		//load data menu and css file yg digunakan di header n sidebar
		// $dataMenu = $this->menusession->getDataMenu();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css'
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$data['menu_wms'] = $this->M_PengaturanApproval->getListMenuWMS();
		$data['jenis_approval'] = $this->M_PengaturanApproval->getListJenisApproval();
		// var_dump($data);
		// exit;
		$data['karyawan_divisi'] = $this->M_PengaturanApproval->getListKaryawanDivisi();
		$data['karyawan_level'] = $this->M_PengaturanApproval->getListKaryawanLevel();
		// Header('Content-Type: application/json');
		// echo json_encode($data['pelanggan']);
		// exit;

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/Pengaturan/PengaturanApproval/PengaturanApprovalForm', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Pengaturan/PengaturanApproval/S_PengaturanApprovalForm', $data);
	}

	public function PengaturanApprovalEdit()
	{

		$data = array();

		//load data menu and css file yg digunakan di header n sidebar
		// $dataMenu = $this->menusession->getDataMenu();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css'
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$id = $this->input->get("id");

		$data["pengaturan_approval"] = $this->M_PengaturanApproval->getPengaturanApprovalById($id);
		$data["pengaturan_approval_detail"] = $this->M_PengaturanApproval->getPengaturanApprovalDetailByID($id);
		$data['menu_wms'] = $this->M_PengaturanApproval->getListMenuWMS();
		$data['jenis_approval'] = $this->M_PengaturanApproval->getListJenisApproval();
		$data['karyawan_divisi'] = $this->M_PengaturanApproval->getListKaryawanDivisi();
		$data['karyawan_level'] = $this->M_PengaturanApproval->getListKaryawanLevel();
		// var_dump($data['pengaturan_approval']);
		// exit;
		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/Pengaturan/PengaturanApproval/PengaturanApprovalEdit', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Pengaturan/PengaturanApproval/S_PengaturanApprovalEdit', $data);
	}

	//untuk mendapatkan detail table dari approval
	public function getApprovalByID()
	{
		$id = $this->input->get("id");
		// $data = $this->M_PengaturanApproval->getPengaturanApprovalById($id);
		$data = $this->M_PengaturanApproval->getPengaturanApprovalDetailByID($id);
		echo json_encode($data);
	}

	public function PengaturanApprovalView()
	{

		$data = array();


		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css'
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$id = $this->input->get("id");

		$data["pengaturan_approval"] = $this->M_PengaturanApproval->getPengaturanApprovalById($id);
		$data["pengaturan_approval_detail"] = $this->M_PengaturanApproval->getPengaturanApprovalDetailByID($id);
		// var_dump($data);
		// exit();
		//load data menu and css file yg digunakan di header n sidebar
		// $dataMenu = $this->menusession->getDataMenu();
		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/Pengaturan/PengaturanApproval/PengaturanApprovalView', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Pengaturan/PengaturanApproval/S_PengaturanApprovalView', $data);
	}

	public function SavePengaturanApproval()
	{
		try {
			$date_now = date('Y-m-d h:i:s');

			$depo_id = $this->session->userdata('depo_id');
			$is_paralel = $this->input->post("is_paralel");
			$parameter = $this->input->post("parameter");
			$menu_id = $this->input->post("menu_id");
			$menu_kode = $this->input->post("menu_kode");
			$keterangan = $this->input->post("keterangan");
			$jenis = $this->input->post("jenis");
			$reff_url = $this->input->post("reff_url");
			$status = $this->input->post("status");
			$dataDetail = $this->input->post("dataDetail");
			$data = $this->M_PengaturanApproval->SavePengaturanApproval(
				$is_paralel,
				$parameter,
				$menu_id,
				$menu_kode,
				$jenis,
				$keterangan,
				$reff_url,
				$status,
				$dataDetail
			);
			// Header('Content-Type: application/json');
			// //output dalam format JSON 
			// echo json_encode($data);
			if ($data == 1) {
				$response = array(
					'message' => 'success',
					'status' =>  1
				);
			} else {
				$response = array(
					'message' => 'Failed create data !!',
					'status' =>  0
				);
			}
			echo json_encode($response);
		} catch (\Throwable $th) {
			//throw $th;
		}
	}

	public function UpdatePengaturanApproval()
	{
		try {
			$is_paralel = $this->input->post("is_paralel");
			$parameter = $this->input->post("parameter");
			$approval_setting_id = $this->input->post("id");
			$menu_id = $this->input->post("menu_id");
			$menu_kode = $this->input->post("menu_kode");
			$keterangan = $this->input->post("keterangan");
			$jenis = $this->input->post("jenis");
			$reff_url = $this->input->post("reff_url");
			$status = $this->input->post("status");
			$dataDetail = $this->input->post("dataDetail");
			// echo 11;
			// exit;
			$data = $this->M_PengaturanApproval->UpdatePengaturanApproval(
				$approval_setting_id,
				$is_paralel,
				$parameter,
				$menu_id,
				$menu_kode,
				$jenis,
				$keterangan,
				$reff_url,
				$status,
				$dataDetail
			);
			if ($data == 1) {
				$response = array(
					'message' => 'success',
					'status' =>  1
				);
			} else {
				$response = array(
					'message' => 'Failed create data !!',
					'status' =>  0
				);
			}
			echo json_encode($response);
		} catch (\Throwable $th) {
			//throw $th;
		}
	}

	public function getDataSearch()
	{

		$data = $this->M_PengaturanApproval->getDataSearch();

		Header('Content-Type: application/json');
		//output dalam format JSON
		echo json_encode($data);
	}
	public function CheckParameterTersimpan()
	{
		$parameter = $this->input->get("parameter");
		$data = $this->M_PengaturanApproval->CheckParameterTersimpan($parameter);
		Header('Content-Type: application/json');
		//output dalam format JSON 
		echo json_encode($data);
	}

	public function getListKaryawanDivisi()
	{
		$data = $this->M_PengaturanApproval->getListKaryawanDivisi();
		Header('Content-Type: application/json');
		//output dalam format JSON 
		echo json_encode($data);
	}
	public function getListKaryawanLevel()
	{
		$data = $this->M_PengaturanApproval->getListKaryawanlevel();
		Header('Content-Type: application/json');
		//output dalam format JSON 
		echo json_encode($data);
	}
	public function getListKaryawanLevelByDivisi()
	{
		$divisi_id = $this->input->get("divisi_id");
		$data = $this->M_PengaturanApproval->getListKaryawanLevelByDivisi($divisi_id);
		Header('Content-Type: application/json');
		//output dalam format JSON 
		echo json_encode($data);
	}

	public function getPengaturanApprovalDetailByID()
	{

		$kredit_limit_id = $this->input->get("kredit_limit_id");
		$data = $this->M_PengaturanApproval->getPengaturanApprovalDetailByID($kredit_limit_id);
		Header('Content-Type: application/json');
		//output dalam format JSON 
		echo json_encode($data);
	}
	public function getPelangganPrincipleById()
	{
		$pelanggan_id = $this->input->get("pelanggan_id");
		$data = $this->M_PengaturanApproval->getPelangganPrincipleById($pelanggan_id);
		Header('Content-Type: application/json');
		//output dalam format JSON 
		echo json_encode($data);
	}

	public function getPelangganByCorporateId()
	{
		$client_pt_corporate_id = $this->input->get("client_pt_corporate_id");
		$data = $this->M_PengaturanApproval->getPelangganByCorporateId($client_pt_corporate_id);
		Header('Content-Type: application/json');
		//output dalam format JSON 
		echo json_encode($data);
	}
	public function getDataPrinciple()
	{
		$data = $this->M_PengaturanApproval->getDataPrinciple();
		Header('Content-Type: application/json');
		//output dalam format JSON 
		echo json_encode($data);
	}
	public function getDataClientSegmen1()
	{
		$data = $this->M_PengaturanApproval->getDataClientSegmen1();
		Header('Content-Type: application/json');
		//output dalam format JSON 
		echo json_encode($data);
	}
	public function getDataClientSegmen2()
	{
		$reff_id = $this->input->get("reff_id");
		$data = $this->M_PengaturanApproval->getDataClientSegmen2($reff_id);
		Header('Content-Type: application/json');
		//output dalam format JSON 
		echo json_encode($data);
	}
	public function getDataClientSegmen3()
	{
		$reff_id = $this->input->get("reff_id");
		$data = $this->M_PengaturanApproval->getDataClientSegmen2($reff_id);
		Header('Content-Type: application/json');
		//output dalam format JSON 
		echo json_encode($data);
	}

	public function checkData()
	{
		$arrayParam = array();
		$arrayData = array();
		$getDataParam = $this->M_PengaturanApproval->checkData();
		if (count($getDataParam) != 0) {
			foreach ($getDataParam as $key => $value) {
				array_push($arrayParam, $value->param);
			}
			// 
			$data = $this->M_PengaturanApproval->getData($arrayParam);


			if ($data != 0) {
				foreach ($data as $key => $value) {
					array_push($arrayData, $value->approval_setting_parameter);
				}
			}
			$cek = array_diff($arrayParam, $arrayData);
			$cek = array_unique($cek);
			// var_dump($cek);

			if (count($cek) != 0) {
				// $cek = str_replace('"', "'", $cek);
				// $getDataValue = $this->M_PengaturanApproval->getDataAll($cek);

				// if (count($getDataValue) > 0) {
				// foreach ($getDataValue as $key => $value) {
				foreach ($cek as $key => $value) {
					// var_dump($value);
					$this->M_PengaturanApproval->insertPengaturanApproval($value);
				}
				// die;
				echo json_encode(1);
				// } else {
				// 	echo json_encode(2);

			} else {
				echo json_encode(2);
			}
		} else {
			echo json_encode(4);
		}

		// echo json_encode($cekdata)	;
	}

	public function getListNamaGroupApproval()
	{
		$data = $this->M_PengaturanApproval->getListNamaGroupApproval();
		echo json_encode($data);
	}

	public function getDataByApprovalGroupId()
	{
		$id = $this->input->post('id');

		$data = $this->M_PengaturanApproval->getDataByApprovalGroupId($id);
		echo json_encode($data);
	}
}
