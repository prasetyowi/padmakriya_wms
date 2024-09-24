<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class InboundSupplier extends ParentController
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
		$this->MenuKode = "138003000";
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_InboundSupplier', 'M_InboundSupplier');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation']);
	}

	public function InboundSupplierMenu()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['kodeInboundSup'] = $this->db->select("security_logbook_id as id, security_logbook_kode as kode")->from("security_logbook")->where('flag', 'Inbound Supplier')->order_by("security_logbook_kode", "ASC")->get()->result();

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
		$this->load->view('WMS/InboundSupplier/Page/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/InboundSupplier/Component/Script/S_InboundSupplier', $data);
	}

	public function filteredData()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_InboundSupplier->filteredData($dataPost));
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
			$data['dataInboundSupplier'] = $this->M_InboundSupplier->getDataInboundSupplier($_GET['id']);
			$data['suratJalan'] = $this->M_InboundSupplier->getDataSuratJalan($_GET['id']);
			// echo json_encode($data['dataInboundSupplier']);
			// die;
		} else {
			$data['suratJalan'] = $this->M_InboundSupplier->getDataSuratJalan(null);
		}

		$data['principle'] = $this->M_InboundSupplier->getDataPrinciple();
		$data['ekspedisi'] = $this->M_InboundSupplier->getDataEkspedisi();

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
		$this->load->view('WMS/InboundSupplier/Page/form', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/InboundSupplier/Component/Script/S_InboundSupplier', $data);
	}

	public function deleteImageById()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		$this->db->where('security_logbook_detail_attachment', $dataPost->dataImage);
		$this->db->delete('security_logbook_detail');
		unlink('assets/images/uploads/LogSecurity/' . $dataPost->dataImage);
	}

	public function saveData()
	{
		$noSuratJalan = $this->input->post('noSuratJalan');
		$principle = $this->input->post('principle');
		$ekspedisi = $this->input->post('ekspedisi');
		$nopol = $this->input->post('nopol');
		$namaDriver = $this->input->post('namaDriver');
		$noHandphone = $this->input->post('noHandphone');
		$tglTrukMasuk = $this->input->post('tglTrukMasuk');
		$totalCatatanSJ = $this->input->post('totalCatatanSJ');
		$jamTrukMasuk = $this->input->post('jamTrukMasuk');
		$mode = $this->input->post('mode');

		if ($mode == 'add') {
			if (empty($_FILES['files'])) {
				$this->saveToDb($noSuratJalan, $principle, $ekspedisi, $nopol, $namaDriver, $noHandphone, $tglTrukMasuk, $totalCatatanSJ, $jamTrukMasuk, NULL, $mode);
			} else {
				$this->uploadFileImage($noSuratJalan, $principle, $ekspedisi, $nopol, $namaDriver, $noHandphone, $tglTrukMasuk, $totalCatatanSJ, $jamTrukMasuk, $mode);
			}
		}

		if ($mode == 'edit') {
			if ($this->input->post('files') != 'null') {
				//get name file in db
				// $nameFile = $this->db->select('security_logbook_detail_attachment as file')->from('security_logbook_detail')->where('security_logbook_id', $this->input->post('inboundSupplierId'))->get()->result();
				// if (!empty($nameFile)) {
				//   foreach ($nameFile as $key => $value) {
				//     unlink('assets/images/uploads/LogSecurity/' . $value);
				//   }
				// }

				$this->uploadFileImage($noSuratJalan, $principle, $ekspedisi, $nopol, $namaDriver, $noHandphone, $tglTrukMasuk, $totalCatatanSJ, $jamTrukMasuk, $mode);
			} else {
				$this->saveToDb($noSuratJalan, $principle, $ekspedisi, $nopol, $namaDriver, $noHandphone, $tglTrukMasuk, $totalCatatanSJ, $jamTrukMasuk, NULL, $mode);
			}
		}
	}

	private function uploadFileImage($noSuratJalan, $principle, $ekspedisi, $nopol, $namaDriver, $noHandphone, $tglTrukMasuk, $totalCatatanSJ, $jamTrukMasuk, $mode)
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
			$name_file = 'InboundSupplier-' . time() . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789'), 0, 7) . '.' . strtolower(end($files));
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
			$this->saveToDb($noSuratJalan, $principle, $ekspedisi, $nopol, $namaDriver, $noHandphone, $tglTrukMasuk, $totalCatatanSJ, $jamTrukMasuk, json_encode($fileNames), $mode);
		} else {
			foreach ($errors as $key => $value) {
				if ($value == 2) {
					unlink('assets/images/uploads/LogSecurity/' . $fileNames[$key]);
				}
			}
			echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan! File Foto tidak sesuai ketentuan'));
		}
	}

	private function saveToDb($noSuratJalan, $principle, $ekspedisi, $nopol, $namaDriver, $noHandphone, $tglTrukMasuk, $totalCatatanSJ, $jamTrukMasuk, $name_file, $mode)
	{

		$status = ($mode == 'add') ? 'Disimpan!' : 'Diubah';
		$suratJalanKode = $this->M_InboundSupplier->getSuratJalanKodeByID($noSuratJalan);

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
			$depoPrefix = $this->M_InboundSupplier->getDepoPrefix($depo_id);
			$unit = $depoPrefix->depo_kode_preffix;
			$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

			$this->M_InboundSupplier->saveData($logSecId, $noSuratJalan, $principle, $ekspedisi, $generate_kode, $nopol, $namaDriver, $noHandphone, $tglTrukMasuk, $totalCatatanSJ, $jamTrukMasuk, $name_file, $mode, $suratJalanKode);
		}

		if ($mode == 'edit') {
			$this->M_InboundSupplier->saveData(NULL, $noSuratJalan, $principle, $ekspedisi, NULL, $nopol, $namaDriver, $noHandphone, $tglTrukMasuk, $totalCatatanSJ, $jamTrukMasuk, $name_file, $mode, $suratJalanKode);
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
				'message' => "Data gagal " . $status
			]);
		} else {
			$this->db->trans_commit();
			echo json_encode([
				'status' => true,
				'message' => "Data berhasil " . $status
			]);
		}
	}

	public function konfirmasiData()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_InboundSupplier->konfirmasiData($dataPost));
	}

	public function deleteData()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_InboundSupplier->deleteData($dataPost));
	}

	public function view()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['dataInboundSupplier'] = $this->M_InboundSupplier->getDataInboundSupplier($_GET['id']);

		$data['principle'] = $this->M_InboundSupplier->getDataPrinciple();
		$data['ekspedisi'] = $this->M_InboundSupplier->getDataEkspedisi();

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
		$this->load->view('WMS/InboundSupplier/Page/view', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/InboundSupplier/Component/Script/S_InboundSupplier', $data);
	}
}
