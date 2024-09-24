<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class CheckInOutShipmentDriver extends ParentController
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
			redirect(base_url('MainPage/Login'));
		endif;

		$this->depo_id = $this->session->userdata('depo_id');
		$this->MenuKode = "138006000";
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_CheckInOutShipmentDriver', 'M_CheckInOutShipmentDriver');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation']);
	}

	public function CheckInOutShipmentDriverMenu()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['kodeInboundSup'] = $this->db->select("security_logbook_id as id, security_logbook_kode as kode")->from("security_logbook")->where('flag', 'Check In Out Shipment Driver')->order_by("security_logbook_kode", "ASC")->get()->result();

		$query['css_files'] = array(
			Get_Assets_Url() . '/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . '/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . '/vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . '/vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . '/vendors/pnotify/dist/pnotify.nonblock.css'
		);

		$query['js_files']     = array(
			Get_Assets_Url() . '/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . '/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . '/vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . '/vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . '/vendors/pnotify/dist/pnotify.nonblock.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/CheckInOutShipmentDriver/Page/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/CheckInOutShipmentDriver/Component/Script/S_CheckInOutShipmentDriver', $data);
	}

	public function add()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		if (!empty($_GET['id'])) {
			$data['dataInboundSupplier'] = $this->M_CheckInOutShipmentDriver->getDataInboundSupplier($_GET['id'], 'form');
			// echo json_encode($data['dataInboundSupplier']);
			// die;
		}
		$data['principle'] = $this->M_CheckInOutShipmentDriver->getDataPrinciple();
		$data['ekspedisi'] = $this->M_CheckInOutShipmentDriver->getDataEkspedisi();
		$data['driver'] = $this->M_CheckInOutShipmentDriver->getDataDriver();
		$data['getNopols'] = $this->M_CheckInOutShipmentDriver->getDataNopols();


		$query['css_files'] = array(
			Get_Assets_Url() . '/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . '/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . '/vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . '/vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . '/vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . '/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css',
		);

		$query['js_files']     = array(
			Get_Assets_Url() . '/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . '/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . '/vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . '/vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . '/vendors/pnotify/dist/pnotify.nonblock.js',

			Get_Assets_Url() . '/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/CheckInOutShipmentDriver/Page/form', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/CheckInOutShipmentDriver/Component/Script/S_CheckInOutShipmentDriver', $data);
	}

	public function filteredData()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_CheckInOutShipmentDriver->filteredData($dataPost));
	}

	public function KonfirmasiBerangkat()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_CheckInOutShipmentDriver->KonfirmasiBerangkat($dataPost));
	}

	public function requestGetDeleveryOrderBatch()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_CheckInOutShipmentDriver->getDataDeliveryOrderBatch($dataPost));
	}

	public function requestGetKaryawanByNopol()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_CheckInOutShipmentDriver->getDataKaryawanByNopol($dataPost));
	}

	public function requestGetNopolByFDJR()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_CheckInOutShipmentDriver->requestGetNopolByFDJR($dataPost));
	}


	public function saveData()
	{
		$kendaraanId = $this->input->post('kendaraanId');
		$karyawanId = $this->input->post('karyawanId');
		$noHandphone = $this->input->post('noHandphone');
		$tglTrukKeberangkatan = $this->input->post('tglTrukKeberangkatan');
		$jamTrukKeberangkatan = $this->input->post('jamTrukKeberangkatan');
		$kmKeberangkatan = $this->input->post('kmKeberangkatan');
		$kmKembali = $this->input->post('kmKembali');
		$keterangan = $this->input->post('keterangan');
		$listDeliveryOrderBatch = explode(',', $this->input->post('listDeliveryOrderBatch'));
		$mode = $this->input->post('mode');

		if ($mode == 'add') {
			if (empty($_FILES['files'])) {
				$this->saveToDb($kendaraanId, $karyawanId, $kmKeberangkatan, $kmKembali, $keterangan, $listDeliveryOrderBatch, $noHandphone, $tglTrukKeberangkatan, $jamTrukKeberangkatan, NULL, $mode);
			} else {
				$this->uploadFileImage($kendaraanId, $karyawanId, $kmKeberangkatan, $kmKembali, $keterangan, $listDeliveryOrderBatch, $noHandphone, $tglTrukKeberangkatan, $jamTrukKeberangkatan, $mode);
			}
		}

		if ($mode == 'edit') {
			if ($this->input->post('files') != 'null') {

				$this->uploadFileImage($kendaraanId, $karyawanId, $kmKeberangkatan, $kmKembali, $keterangan, $listDeliveryOrderBatch, $noHandphone, $tglTrukKeberangkatan, $jamTrukKeberangkatan, $mode);
			} else {
				$this->saveToDb($kendaraanId, $karyawanId, $kmKeberangkatan, $kmKembali, $keterangan, $listDeliveryOrderBatch, $noHandphone, $tglTrukKeberangkatan, $jamTrukKeberangkatan, NULL, $mode);
			}
		}
	}

	private function uploadFileImage($kendaraanId, $karyawanId, $kmKeberangkatan, $kmKembali, $keterangan, $listDeliveryOrderBatch, $noHandphone, $tglTrukKeberangkatan, $jamTrukKeberangkatan, $mode)
	{

		$uploadDirectory = "assets/images/uploads/LogSecurity/";
		$fileExtensionsAllowed = ['jpeg', 'jpg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF']; // Allowed file extensions

		$errors = [];
		$fileNames = [];

		for ($i = 0; $i < count($_FILES['files']['tmp_name']); $i++) {
			$fileName = $_FILES['files']['name'][$i];
			$fileTmpName = $_FILES['files']['tmp_name'][$i];
			$file_type = $_FILES['files']['type'][$i];
			$file_size = $_FILES['files']['size'][$i];
			$files = explode(".", $fileName);
			$name_file = 'ShipmentDriver-' . time() . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789'), 0, 7) . '.' . strtolower(end($files));
			$fileNames[] = $name_file;

			if (!in_array(strtolower(end($files)), $fileExtensionsAllowed)) {
				$errors[] = 2;
			} else {
				$uploadPath = $uploadDirectory . $name_file;
				$didUpload = move_uploaded_file($fileTmpName, $uploadPath);
				if (!$didUpload) {
					$errors[] = 'Data gagal disimpan! Terjadi kesalahan pada server';
				}
			}
		}

		// echo json_encode($fileNames);
		// die;

		if (empty($errors)) {
			$this->saveToDb($kendaraanId, $karyawanId, $kmKeberangkatan, $kmKembali, $keterangan, $listDeliveryOrderBatch, $noHandphone, $tglTrukKeberangkatan, $jamTrukKeberangkatan, json_encode($fileNames), $mode);
		} else {
			foreach ($errors as $key => $value) {
				if ($value == 2) {
					unlink('assets/images/uploads/LogSecurity/' . $fileNames[$key]);
				}
			}
			echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan! File Foto tidak sesuai ketentuan'));
		}
	}

	private function saveToDb($kendaraanId, $karyawanId, $kmKeberangkatan, $kmKembali, $keterangan, $listDeliveryOrderBatch, $noHandphone, $tglTrukKeberangkatan, $jamTrukKeberangkatan, $name_file, $mode)
	{

		$status = ($mode == 'add') ? 'Disimpan!' : 'Diubah';

		$shipmenId = "";

		$this->db->trans_begin();

		if ($mode == 'add') {
			$logSecId = $this->M_Vrbl->Get_NewID();
			$logSecId = $logSecId[0]['NEW_ID'];

			//generate kode
			$date_now = date('Y-m-d h:i:s');
			$param =  'KODE_LOGSEC';
			$vrbl = $this->M_Vrbl->Get_Kode($param);
			$prefix = $vrbl->vrbl_kode;
			// get prefik depo
			$depo_id = $this->session->userdata('depo_id');
			$depoPrefix = $this->M_CheckInOutShipmentDriver->getDepoPrefix($depo_id);
			$unit = $depoPrefix->depo_kode_preffix;
			$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

			$this->M_CheckInOutShipmentDriver->saveData($logSecId, $kendaraanId, $karyawanId, $kmKeberangkatan, $kmKembali, $generate_kode, $keterangan, $listDeliveryOrderBatch, $noHandphone, $tglTrukKeberangkatan, $jamTrukKeberangkatan, $name_file, $mode);

			$shipmenId .= $logSecId;
		}

		if ($mode == 'edit') {
			$this->M_CheckInOutShipmentDriver->saveData(NULL, $kendaraanId, $karyawanId, $kmKeberangkatan, $kmKembali, NULL, $keterangan, $listDeliveryOrderBatch, $noHandphone, $tglTrukKeberangkatan, $jamTrukKeberangkatan, $name_file, $mode);

			$shipmenId .= $this->input->post('shipmentDriverId');
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			if ($mode == 'add') {
				if ($name_file !== NULL) {
					foreach (json_decode($name_file) as $key => $value) {
						unlink('assets/images/uploads/LogSecurity/' . $value);
					}
				}
			}
			echo json_encode([
				'status' => false,
				'message' => "Data gagal " . $status,
				'shipmentId' => $shipmenId
			]);
		} else {
			$this->db->trans_commit();
			echo json_encode([
				'status' => true,
				'message' => "Data berhasil " . $status,
				'shipmentId' => $shipmenId
			]);
		}
	}

	public function deleteData()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_CheckInOutShipmentDriver->deleteData($dataPost));
	}

	public function konfirmasiData()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_CheckInOutShipmentDriver->konfirmasiData($dataPost));
	}

	public function view()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['dataInboundSupplier'] = $this->M_CheckInOutShipmentDriver->getDataInboundSupplier($_GET['id'], 'view');

		$data['principle'] = $this->M_CheckInOutShipmentDriver->getDataPrinciple();
		$data['ekspedisi'] = $this->M_CheckInOutShipmentDriver->getDataEkspedisi();
		$data['driver'] = $this->M_CheckInOutShipmentDriver->getDataDriver();
		$data['getNopols'] = $this->M_CheckInOutShipmentDriver->getDataNopols();

		$query['css_files'] = array(
			base_url('/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css'),
			base_url('/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css'),

			base_url('/vendors/pnotify/dist/pnotify.css'),
			base_url('/vendors/pnotify/dist/pnotify.buttons.css'),
			base_url('/vendors/pnotify/dist/pnotify.nonblock.css'),

			base_url('/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css'),
		);

		$query['js_files']     = array(
			base_url('/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js'),
			base_url('/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js'),

			base_url('/vendors/pnotify/dist/pnotify.js'),
			base_url('/vendors/pnotify/dist/pnotify.buttons.js'),
			base_url('/vendors/pnotify/dist/pnotify.nonblock.js'),

			base_url('/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/CheckInOutShipmentDriver/Page/view', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/CheckInOutShipmentDriver/Component/Script/S_CheckInOutShipmentDriver', $data);
	}
}
