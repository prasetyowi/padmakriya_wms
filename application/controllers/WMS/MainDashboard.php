<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require_once APPPATH . 'core/ParentController.php';
require_once APPPATH . 'core/MenuController.php';
class MainDashboard extends MenuController
{
	// private $MenuKode;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('WMS/M_MainDashboard', 'M_MainDashboard');
		// $this->MenuKode = "100000000";
	}


	public function MainDashboardMenu()
	{
		$this->load->model('M_Menu');

		$query['Title'] = Get_Title_Menu_Name($this->MenuKode);
		$query['Copyright'] = Get_Copyright_Name();

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));


		$query['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css',
			Get_Assets_Url() . 'assets/css/theme.blue.css'
		);

		$query['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'assets/js/jquery.tablesorter.js',
			Get_Assets_Url() . 'assets/js/jquery.tablesorter.widgets.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			"https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$last_tbg = "";

		$last_tbg_query = $this->db->query("select FORMAT(DATEADD(DAY, 1, ISNULL(depo_last_tbg, DATEADD(DAY, -1, GETDATE()))), 'dd/MM/yyyy') as next_tbg from depo where depo_id = '" . $this->session->userdata('depo_id') . "'");

		if ($last_tbg_query->num_rows() == 0) {
			$last_tbg = "";
		} else {
			$last_tbg = $last_tbg_query->row(0)->next_tbg;
		}

		$query['lastTbg'] = $last_tbg;

		// Konversi ke format JavaScript Date (YYYY-MM-DD)
		$lastTbgDepo = getLastTbgDepo();
		$query['lastTbgDepo'] = date('Y-m-d', strtotime($lastTbgDepo . ' -1 day'));
		$query['perusahaan'] = $this->M_MainDashboard->getPerusahan();

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/DashboardMenu', $query);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/S_DashboardMenu', $query);
	}

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

		$tgl = $this->input->post('Tgl_By_Status');
		// $tgl = explode(" - ", $this->input->post('Tgl_By_Status'));
		// $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		// $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));


		$data = $this->M_MainDashboard->GetListDOByStatus($status, $tgl, $tgl);

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

	public function getReportDashboardDOI()
	{
		$client_wms_id = $this->input->post("client_wms");
		$principle_id = $this->input->post("principle");
		$doi_internal = $this->input->post("doi_internal");
		$filter_month = $this->input->post("filter_month");

		$data = $this->M_MainDashboard->getReportDashboardDOI($client_wms_id, $principle_id, $doi_internal, $filter_month);

		echo json_encode($data);
	}

	public function getDashboardSJSupplier()
	{
		$client_wms_id = $this->input->post("client_wms");
		$principle_id = $this->input->post("principle");
		$mode = $this->input->post("mode");
		$tanggal = explode(' - ', $this->input->post("tanggal"));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[1])));

		$data = $this->M_MainDashboard->getDashboardSJSupplier($client_wms_id, $principle_id, $mode, $tgl1, $tgl2);

		echo json_encode($data);
	}

	public function getDashboardTruckSupplier()
	{
		$client_wms_id = $this->input->post("client_wms");
		$principle_id = $this->input->post("principle");
		$mode = $this->input->post("mode");
		$tanggal = explode(' - ', $this->input->post("tanggal"));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[1])));

		$data = $this->M_MainDashboard->getDashboardTruckSupplier($client_wms_id, $principle_id, $mode, $tgl1, $tgl2);

		echo json_encode($data);
	}

	public function getDashboardStock()
	{
		$client_wms_id = $this->input->post("client_wms");
		$principle_id = $this->input->post("principle");
		$depo_detail = $this->input->post("depo_detail");

		$data = $this->M_MainDashboard->getDashboardStock($client_wms_id, $principle_id, $depo_detail);

		echo json_encode($data);
	}

	public function getDashboardServiceLevel()
	{
		$client_wms_id = $this->input->post("client_wms");
		$principle_id = $this->input->post("principle");
		$tipe = $this->input->post("tipe");
		$mode = $this->input->post("mode");
		$tanggal = explode(' - ', $this->input->post("tanggal"));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[1])));

		$data = $this->M_MainDashboard->getDashboardServiceLevel($client_wms_id, $principle_id, $tipe, $mode, $tgl1, $tgl2);

		echo json_encode($data);
	}

	public function getDashboardStatusDO()
	{
		$client_wms_id = $this->input->post("client_wms");
		$principle_id = $this->input->post("principle");
		$status = $this->input->post("status");
		$mode = $this->input->post("mode");
		$tanggal = explode(' - ', $this->input->post("tanggal"));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[1])));

		$data = $this->M_MainDashboard->getDashboardStatusDO($client_wms_id, $principle_id, $status, $mode, $tgl1, $tgl2);

		echo json_encode($data);
	}

	public function getKeterangan()
	{
		$mode = $this->input->post("mode");

		$data = $this->M_MainDashboard->getKeterangan($mode);

		echo json_encode($data);
	}

	public function getDashboardFleetProductivity()
	{
		$mode = $this->input->post("mode");
		$flag = $this->input->post("flag");
		$status_assign = $this->input->post("status_assign");
		$value = $this->input->post("value");
		$area = $this->input->post("area");
		$tanggal = explode(' - ', $this->input->post("tanggal"));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tanggal[1])));
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$search = $this->input->post("search[value]");
		$draw = $this->input->post("draw");

		$data = $this->M_MainDashboard->getDashboardFleetProductivity($mode, $flag, $status_assign, $value, $tgl1, $tgl2, $start, $length, $search, $draw, $area);

		echo json_encode($data);
	}

	public function listDataByCard()
	{
		$this->load->model('WMS/M_MainDashboard', 'M_MainDashboard');

		$modul = $this->input->post('modul');
		$Tgl_By_Status = $this->input->post('Tgl_By_Status');

		$data = $this->M_MainDashboard->getListDataByCard($modul, $Tgl_By_Status);

		echo json_encode($data);
	}
}
