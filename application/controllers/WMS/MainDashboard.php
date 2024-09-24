<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MainDashboard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('WMS/M_MainDashboard', 'M_MainDashboard');
	}

	public function MainDashboardMenu()
	{
		$this->load->model('M_Menu');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',
			Get_Assets_Url() . 'vendors/jquery-ui/jquery-ui.min.css',
			Get_Assets_Url() . 'assets/css/textimage.css'
		);

		$query['js_files'] 	= array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'vendors/jquery-ui/jquery-ui.min.js',

		);

		// Konversi ke format JavaScript Date (YYYY-MM-DD)
		$lastTbgDepo = getLastTbgDepo();
		$query['lastTbgDepo'] = date('Y-m-d', strtotime($lastTbgDepo . ' -1 day'));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/DashboardMenu', $query);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/S_DashboardMenu', $query);
	}

	// public function GetCountByStatusDORealTime()
	// {
	// 	$this->load->model('WMS/M_MainDashboard', 'M_MainDashboard');

	// 	$DepoID = $this->input->post('DepoID');
	// 	$Index = $this->input->post('index');
	// 	$mode = $this->input->post('mode');

	// 	$tgl = explode(" - ", $this->input->post('Tgl_By_Status'));
	// 	$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
	// 	$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

	// 	if ($mode == 0) {
	// 		$tgl1 = date("Y-m-d");
	// 	}

	// 	if ($Index == 1) {
	// 		$data['StatusInProgress'] = $this->M_MainDashboard->GetCountInProgress($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 2) {
	// 		$data['StatusInProgressItemRequest'] = $this->M_MainDashboard->GetCountInProgressItemRequest($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 3) {
	// 		$data['StatusInProgressPickUpItem'] = $this->M_MainDashboard->GetCountInProgressPickUpItem($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 4) {
	// 		$data['StatusPickUpItemConfirmed'] = $this->M_MainDashboard->GetCountPickUpItemConfirmed($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 5) {
	// 		$data['StatusInProgressPackingItem'] = $this->M_MainDashboard->GetCountInProgressPackingItem($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 6) {
	// 		$data['StatusPackingItemConfirmed'] = $this->M_MainDashboard->GetCountPackingItemConfirmed($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 7) {
	// 		$data['StatusInTransitValidation'] = $this->M_MainDashboard->GetCountInTransitValidation($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 8) {
	// 		$data['StatusInTransitValidationCompleted'] = $this->M_MainDashboard->GetCountInTransitValidationCompleted($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 9) {
	// 		$data['StatusInTransit'] = $this->M_MainDashboard->GetCountInTransit($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 10) {
	// 		$data['StatusDelivered'] = $this->M_MainDashboard->GetCountDelivered($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 11) {
	// 		$data['StatusPartiallyDelivered'] = $this->M_MainDashboard->GetCountPartiallyDelivered($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 12) {
	// 		$data['StatusNotDelivered'] = $this->M_MainDashboard->GetCountNotDelivered($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 13) {
	// 		$data['StatusCanceled'] = $this->M_MainDashboard->GetCountCanceled($DepoID, $tgl1, $tgl2, $mode);
	// 	}

	// 	$data['CardDashboard'] = $this->M_MainDashboard->generateCardDashboard($tgl1, $tgl2, $mode);

	// 	echo json_encode($data);
	// }

	// public function GetCountByStatusDORealTime()
	// {
	// 	$this->load->model('WMS/M_MainDashboard', 'M_MainDashboard');

	// 	$DepoID = $this->input->post('DepoID');
	// 	$Index = $this->input->post('index');
	// 	$mode = $this->input->post('mode');

	// 	$tgl = explode(" - ", $this->input->post('Tgl_By_Status'));
	// 	$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
	// 	$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

	// 	if ($mode == 0) {
	// 		$tgl1 = date("Y-m-d");
	// 	}

	// 	if ($Index == 1) {
	// 		$data['StatusInProgress'] = $this->M_MainDashboard->GetCountInProgress($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 2) {
	// 		$data['StatusInProgressItemRequest'] = $this->M_MainDashboard->GetCountInProgressItemRequest($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 3) {
	// 		$data['StatusInProgressPickUpItem'] = $this->M_MainDashboard->GetCountInProgressPickUpItem($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 4) {
	// 		$data['StatusPickUpItemConfirmed'] = $this->M_MainDashboard->GetCountPickUpItemConfirmed($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 5) {
	// 		$data['StatusInProgressPackingItem'] = $this->M_MainDashboard->GetCountInProgressPackingItem($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 6) {
	// 		$data['StatusPackingItemConfirmed'] = $this->M_MainDashboard->GetCountPackingItemConfirmed($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 7) {
	// 		$data['StatusInTransitValidation'] = $this->M_MainDashboard->GetCountInTransitValidation($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 8) {
	// 		$data['StatusInTransitValidationCompleted'] = $this->M_MainDashboard->GetCountInTransitValidationCompleted($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 9) {
	// 		$data['StatusInTransit'] = $this->M_MainDashboard->GetCountInTransit($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 10) {
	// 		$data['StatusDelivered'] = $this->M_MainDashboard->GetCountDelivered($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 11) {
	// 		$data['StatusPartiallyDelivered'] = $this->M_MainDashboard->GetCountPartiallyDelivered($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 12) {
	// 		$data['StatusNotDelivered'] = $this->M_MainDashboard->GetCountNotDelivered($DepoID, $tgl1, $tgl2, $mode);
	// 	} elseif ($Index == 13) {
	// 		$data['StatusCanceled'] = $this->M_MainDashboard->GetCountCanceled($DepoID, $tgl1, $tgl2, $mode);
	// 	}

	// 	$data['CardDashboard'] = $this->M_MainDashboard->generateCardDashboard($tgl1, $tgl2, $mode);

	// 	echo json_encode($data);
	// }

	public function GetCountByStatusDORealTime()
	{
		$this->load->model('WMS/M_MainDashboard', 'M_MainDashboard');

		$DepoID = $this->input->post('DepoID');
		$Tgl_By_Status = $this->input->post('Tgl_By_Status');
		$mode = $this->input->post('mode');

		// $tgl = explode(" - ", $this->input->post('Tgl_By_Status'));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $Tgl_By_Status)));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $Tgl_By_Status)));

		$data['statusDO'] = $this->M_MainDashboard->getStatusDO($DepoID, $tgl1, $tgl2);

		$data['CardDashboard'] = $this->M_MainDashboard->generateCardDashboard($tgl1, $tgl2, $mode);

		echo json_encode($data);
	}

	public function GetListDOByStatus()
	{
		$this->load->model('WMS/M_MainDashboard', 'M_MainDashboard');

		$status = $this->input->post('status');

		$tgl = explode(" - ", $this->input->post('Tgl_By_Status'));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$data = $this->M_MainDashboard->GetListDOByStatus($status, $tgl1, $tgl2);

		echo json_encode($data);
	}

	public function getSKUNotInWMS()
	{
		$this->load->model('WMS/M_MainDashboard', 'M_MainDashboard');
		echo json_encode($this->M_MainDashboard->getSKUNotInWMS());
	}

	public function getDataSKU()
	{
		$id = $this->input->post("id");

		$data = $this->M_MainDashboard->GetKonversiEditDetailById($id);

		echo json_encode($data);
	}
}
