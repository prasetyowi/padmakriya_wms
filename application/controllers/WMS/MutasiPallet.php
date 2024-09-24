<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentController.php';

class MutasiPallet extends CI_Controller
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

		$this->MenuKode = "120003000";
	}

	public function MutasiPalletMenu()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiPallet', 'M_MutasiPallet');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

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
		$data['Checker'] = $this->M_MutasiPallet->Get_Checker();
		$data['Gudang'] = $this->M_MutasiPallet->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiPallet->Get_TipeTransaksi();
		$data['Principle'] = $this->M_MutasiPallet->Get_Principle();
		$data['getClientWms'] = $this->M_MutasiPallet->getClientWms();
		$data['Status'] = $this->M_MutasiPallet->Get_Status();
		$data['mutasi_pallet_id'] = $this->M_MutasiPallet->Get_NewID();

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
		$this->load->view('WMS/MutasiPallet/MutasiPallet', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiPallet/S_MutasiPalletMenu', $data);
	}

	public function MutasiPalletForm()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiPallet', 'M_MutasiPallet');

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
		$data['Checker'] = $this->M_MutasiPallet->Get_Checker();
		$data['Gudang'] = $this->M_MutasiPallet->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiPallet->Get_TipeTransaksi();
		$data['Principle'] = $this->M_MutasiPallet->Get_Principle();
		$data['Status'] = $this->M_MutasiPallet->Get_Status();
		$data['MutasiPalletDraft'] = $this->M_MutasiPallet->Get_MutasiDraftPallet();
		$data['mutasi_pallet_id'] = $this->M_MutasiPallet->Get_NewID();
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');
		$data['act'] = "MutasiPalletForm";

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

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/MutasiPallet/MutasiPalletForm', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiPallet/S_MutasiPallet', $data);
	}

	public function MutasiPalletDetail()
	{
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_MutasiPallet', 'M_MutasiPallet');

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
		$data['Checker'] = $this->M_MutasiPallet->Get_Checker();
		$data['Gudang'] = $this->M_MutasiPallet->Get_Gudang();
		$data['TipeTransaksi'] = $this->M_MutasiPallet->Get_TipeTransaksi();
		$data['Principle'] = $this->M_MutasiPallet->Get_Principle();
		$data['Status'] = $this->M_MutasiPallet->Get_Status();
		$data['mutasi_pallet_id'] = $this->input->get('tr_mutasi_pallet_id');
		$data['MutasiPallet'] = $this->M_MutasiPallet->Get_MutasiPalletById($this->input->get('tr_mutasi_pallet_id'));
		$data['MutasiPalletDetail'] = $this->M_MutasiPallet->Get_MutasiPalletDetailById($this->input->get('tr_mutasi_pallet_id'));
		$data['act'] = "MutasiPalletDetail";

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
		);


		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/MutasiPallet/MutasiPalletDetail', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MutasiPallet/S_MutasiPallet', $data);
	}

	public function getDataPrincipleByClientWmsId()
	{
		$this->load->model('WMS/M_MutasiPallet', 'M_MutasiPallet');
		$id = $this->input->post('id');
		$data = $this->M_MutasiPallet->getDataPrincipleByClientWmsId($id);
		echo json_encode($data);
	}

	public function GetPencarianMutasiPalletTable()
	{
		$this->load->model('WMS/M_MutasiPallet', 'M_MutasiPallet');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		// $tanggal = date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('tanggal'))));

		$tgl = explode(" - ", $this->input->post('tanggal'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$id = $this->input->post('id');
		$gudang_asal = $this->input->post('gudang_asal');
		$gudang_tujuan = $this->input->post('gudang_tujuan');
		$tipe = $this->input->post('tipe');
		$client_wms = $this->input->post('client_wms');
		$principle = $this->input->post('principle');
		$checker = $this->input->post('checker');
		$status = $this->input->post('status');

		$data['MutasiPallet'] = $this->M_MutasiPallet->Get_PencarianMutasiPalletTable($tgl1, $tgl2, $id, $gudang_asal, $gudang_tujuan, $tipe, $client_wms, $principle, $checker, $status);

		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetMutasiDraft()
	{
		$this->load->model('WMS/M_MutasiPallet', 'M_MutasiPallet');

		$tr_mutasi_pallet_draft_id = $this->input->post('tr_mutasi_pallet_draft_id');

		$data['MutasiPalletDraft'] = $this->M_MutasiPallet->Get_MutasiPalletDraft($tr_mutasi_pallet_draft_id);
		$data['Pallet'] = $this->M_MutasiPallet->Get_Pallet($tr_mutasi_pallet_draft_id);

		echo json_encode($data);
	}

	public function ReloadPallet()
	{
		$this->load->model('WMS/M_MutasiPallet', 'M_MutasiPallet');

		$tr_mutasi_pallet_draft_id = $this->input->post('tr_mutasi_pallet_draft_id');
		$act = $this->input->post('act');

		if ($act == "update") {
			$data['Pallet'] = $this->M_MutasiPallet->Get_Pallet2($tr_mutasi_pallet_draft_id);
		} else {
			$data['Pallet'] = $this->M_MutasiPallet->Get_Pallet($tr_mutasi_pallet_draft_id);
		}

		echo json_encode($data);
	}

	public function GetPalletDetail()
	{
		$this->load->model('WMS/M_MutasiPallet', 'M_MutasiPallet');

		$pallet_id = $this->input->post('pallet_id');

		$data['PalletDetail'] = $this->M_MutasiPallet->Get_PalletDetail($pallet_id);

		echo json_encode($data);
	}

	public function check_kode_pallet_by_no_mutasi()
	{
		$this->load->model('WMS/M_MutasiPallet', 'M_MutasiPallet');

		$id = $this->input->post('id');
		$kode_pallet = $this->input->post('kode_pallet');

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_MutasiPallet->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		$get_data = $this->M_MutasiPallet->getPrefixPallet($id);

		// $kode = $unit . "/" . $get_data->pallet_jenis_kode . "/" . $kode_pallet;
		// $kode = $unit . "/" . $get_data->pallet_jenis_kode . "/" . $kode_pallet;
		$kode = $unit . "/" . $kode_pallet;

		$result = $this->M_MutasiPallet->check_kode_pallet_by_no_mutasi($id, $kode);
		if ($result == null) {
			echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan", 'kode' => $kode));
		} else {
			//cek jik status null maka update
			if ($result->status == null) {
				//jika cek barcode menggunakan manual input
				if (empty($_FILES['file']['name'])) {
					//jika cek barcode menggunakan scan
					//jika status 0 maka update
					$this->M_MutasiPallet->update_status_tmpdd($result, $file = "");
					echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> <span class='text-success'>Valid</span>", 'kode' => $kode));
				} else {
					$uploadDirectory = "assets/images/uploads/Bukti-Cek-Pallet/";
					$fileExtensionsAllowed = ['jpeg', 'jpg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF']; // Allowed file extensions
					$fileName = $_FILES['file']['name'];
					$fileSize = $_FILES['file']['size'];
					$fileTmpName  = $_FILES['file']['tmp_name'];
					$fileType = $_FILES['file']['type'];
					$file = explode(".", $fileName);
					$fileExtension = strtolower(end($file));
					$name_file = 'bukti-' . time() . '.' . $fileExtension;

					if (!in_array($fileExtension, $fileExtensionsAllowed)) {
						echo json_encode(array('type' => 201, 'message' => "Gagal! File Attactment tidak sesuai ketentuan (JPG & PNG, GIF)", 'kode' => $kode));
					} else {
						$res = $this->M_MutasiPallet->update_status_tmpdd($result, $name_file);
						if ($res) {
							$uploadPath = $uploadDirectory . $name_file;
							compressImage($fileTmpName, $uploadPath, 10);
							echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> <span class='text-success'>Valid</span>", 'kode' => $kode));
						} else {
							echo json_encode(array('type' => 201, 'message' => "Terjadi kesalahan pada server", 'kode' => $kode));
						}
					}
				}
			} else if ($result->status == 0) {
				//jika status 0 maka tampilkan message
				echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> masih belum valid", 'kode' => $kode));
			} else {
				//jika status sudah 1 maka tampilkan message sudah divalidasi
				echo json_encode(array('type' => 202, 'message' => "Kode pallet <strong>" . $kode . "</strong> sudah tervalidasi", 'kode' => $kode));
			}
		}
	}

	public function check_rak_lajur_detail()
	{
		$this->load->model('WMS/M_MutasiPallet', 'M_MutasiPallet');

		$tr_mutasi_pallet_draft_id = $this->input->post('tr_mutasi_pallet_draft_id');
		$tr_mutasi_pallet_detail_draft_id = $this->input->post('tr_mutasi_pallet_detail_draft_id');
		$pallet_id = $this->input->post('pallet_id');
		$gudang_tujuan = $this->input->post('gudang_tujuan');
		$kode = $this->input->post('kode');
		$act = $this->input->post('act');

		$response = $this->M_MutasiPallet->check_rak_lajur_detail($gudang_tujuan, $kode);
		if ($response == null) {
			echo json_encode(array('type' => 201, 'message' => "Rak <strong>" . $kode . "</strong> tidak ditemukan", 'kode' => $kode));
		} else {
			//update rak_lajur_detail_id_tujuan
			if ($act == "update") {
				$this->M_MutasiPallet->updateRakLajurDetailTujuanInTrMutasi2($tr_mutasi_pallet_detail_draft_id, $response->rak_detail_id);
			} else {
				$this->M_MutasiPallet->updateRakLajurDetailTujuanInTrMutasi($tr_mutasi_pallet_detail_draft_id, $response->rak_detail_id);
			}

			echo json_encode(array('type' => 200, 'message' => "Rak <strong>" . $kode . "</strong> ditemukan dan berhasil update", 'kode' => $kode));
		}
	}

	public function InsertMutasiPallet()
	{
		$this->load->model('WMS/M_MutasiPallet', 'M_MutasiPallet');
		$this->load->model('M_Menu');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "U")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('tr_mutasi_pallet_draft_id', 'ID', 'required');
		$this->form_validation->set_rules('tipe_mutasi', 'Tipe Transaksi', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {

			$tipe_mutasi = "Kode Dokumen " . $this->input->post('tipe_mutasi');

			$date_now = date('Y-m-d h:i:s');
			$param =  $this->M_MutasiPallet->Get_ParamTipe($tipe_mutasi);

			$vrbl = $this->M_Vrbl->Get_Kode($param);
			$prefix = $vrbl->vrbl_kode;

			// get prefik depo
			$depo_id = $this->session->userdata('depo_id');
			$depoPrefix = $this->M_MutasiPallet->getDepoPrefix($depo_id);
			$unit = $depoPrefix->depo_kode_preffix;
			$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

			$tr_mutasi_pallet_draft_id = $this->input->post('tr_mutasi_pallet_draft_id');
			$tr_mutasi_pallet_keterangan = $this->input->post('mutasi_keterangan');
			$tr_mutasi_pallet_status = $this->input->post('mutasi_status');
			$lastUpdated = $this->input->post('lastUpdated');
			$tr_mutasi_pallet_id = $this->M_MutasiPallet->Get_NewID();
			$tr_mutasi_pallet_kode = $generate_kode;

			$cek_mutasi_pallet = $this->M_MutasiPallet->Check_MutasiPaletKode($tr_mutasi_pallet_draft_id);
			$cek_rak_tujuan_mutasi_pallet = $this->M_MutasiPallet->Check_RakTujuanMutasiPalet($tr_mutasi_pallet_draft_id);
			$getLastUpdatedDb = $this->db->select("tr_mutasi_pallet_draft_tgl_update")->from("tr_mutasi_pallet_draft")->where("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id)->get()->row()->tr_mutasi_pallet_draft_tgl_update;
			$errorNotSameLastUpdated = false;
			if ($lastUpdated !== $getLastUpdatedDb) $errorNotSameLastUpdated = true;

			if ($cek_mutasi_pallet == 0) {

				if ($cek_rak_tujuan_mutasi_pallet == 0) {

					$this->db->trans_begin();

					$result = $this->M_MutasiPallet->Insert_MutasiPallet($tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_id, $tr_mutasi_pallet_kode, $tr_mutasi_pallet_keterangan, $tr_mutasi_pallet_status);
					$result2 = $this->M_MutasiPallet->Update_MutasiPalletDraft($tr_mutasi_pallet_draft_id);

					$rak_lajur_detail_id = $this->M_MutasiPallet->get_data_rak_lajur_detail($tr_mutasi_pallet_draft_id);
					foreach ($rak_lajur_detail_id as $key => $value) {
						//update rak_lajur_detail_detail di tabel pallet
						$this->M_MutasiPallet->update_data_pallet_by_params2($value);
						$this->M_MutasiPallet->update_data_mutasi_pallet_by_params($tr_mutasi_pallet_draft_id, $value->pallet_id, $value);

						//delete table rak_lajur_detail_pallet by pallet_id
						// $get_data = $this->M_MutasiPallet->check_and_get_data_rak_lajur_detail_pallet($value->pallet_id);
						// if ($get_data != null) {
						// 	$this->M_MutasiPallet->delete_data_to_rak_lajur_detail_pallet($value->pallet_id);
						// }

						//insert pallet id ke table rak_lajur_detail_pallet
						// $this->M_MutasiPallet->insert_data_to_rak_lajur_detail_pallet_2($value);

						//delete table rak_lajur_detail_pallet by pallet_id
						// $get_data_his = $this->M_MutasiPallet->check_data_rak_lajur_detail_pallet_his($value->pallet_id);
						// if ($get_data_his != null) {
						// 	$this->M_MutasiPallet->delete_data_to_rak_lajur_detail_pallet_his($value->pallet_id);
						// }
					}

					if ($errorNotSameLastUpdated) {
						$this->db->trans_rollback();
						echo json_encode(2);
					} else if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						echo json_encode(0);
					} else {
						$this->db->trans_commit();
						echo json_encode(1);
					}
				} else {
					echo "Rak tujuan mutasi pallet masih ada yang kosong";
				}
			} else {
				echo "Mutasi pallet " . $cek_mutasi_pallet . " sudah ada";
			}
		}
	}

	public function UpdateMutasiPallet()
	{
		$this->load->model('WMS/M_MutasiPallet', 'M_MutasiPallet');
		$this->load->model('M_Menu');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "U")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('tr_mutasi_pallet_draft_id', 'ID', 'required');
		$this->form_validation->set_rules('tipe_mutasi', 'Tipe Transaksi', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {

			$tr_mutasi_pallet_draft_id = $this->input->post('tr_mutasi_pallet_draft_id');
			$tr_mutasi_pallet_id = $this->input->post('tr_mutasi_pallet_id');
			$tr_mutasi_pallet_keterangan = $this->input->post('mutasi_keterangan');
			$tr_mutasi_pallet_status = $this->input->post('mutasi_status');
			$tipe_mutasi = $this->input->post('tipe_mutasi');
			$lastUpdated = $this->input->post('lastUpdated');

			$cek_mutasi_pallet = $this->M_MutasiPallet->Check_MutasiPaletKode($tr_mutasi_pallet_draft_id);
			$cek_rak_tujuan_mutasi_pallet = $this->M_MutasiPallet->Check_RakTujuanMutasiPalet($tr_mutasi_pallet_draft_id);
			$getLastUpdatedDb = $this->db->select("tr_mutasi_pallet_tgl_update")->from("tr_mutasi_pallet")->where("tr_mutasi_pallet_id", $tr_mutasi_pallet_id)->get()->row()->tr_mutasi_pallet_tgl_update;

			$errorNotSameLastUpdated = false;
			if ($lastUpdated !== $getLastUpdatedDb) $errorNotSameLastUpdated = true;

			if ($cek_mutasi_pallet == 0) {

				if ($cek_rak_tujuan_mutasi_pallet == 0) {

					$this->db->trans_begin();

					$result = $this->M_MutasiPallet->Update_MutasiPallet($tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_id, $tr_mutasi_pallet_keterangan, $tr_mutasi_pallet_status);
					$result2 = $this->M_MutasiPallet->Update_MutasiPalletDraft($tr_mutasi_pallet_draft_id);

					$rak_lajur_detail_id = $this->M_MutasiPallet->get_data_rak_lajur_detail2($tr_mutasi_pallet_draft_id);
					foreach ($rak_lajur_detail_id as $key => $value) {
						//update rak_lajur_detail_detail di tabel pallet
						$this->M_MutasiPallet->update_data_pallet_by_params2($value);
						$this->M_MutasiPallet->update_data_mutasi_pallet_by_params2($tr_mutasi_pallet_id, $value->pallet_id, $value);

						//delete table rak_lajur_detail_pallet by pallet_id
						// $get_data = $this->M_MutasiPallet->check_and_get_data_rak_lajur_detail_pallet($value->pallet_id);
						// if ($get_data != null) {
						// 	$this->M_MutasiPallet->delete_data_to_rak_lajur_detail_pallet($value->pallet_id);
						// }

						//insert pallet id ke table rak_lajur_detail_pallet
						// $this->M_MutasiPallet->insert_data_to_rak_lajur_detail_pallet_2($value);

						//delete table rak_lajur_detail_pallet by pallet_id
						// $get_data_his = $this->M_MutasiPallet->check_data_rak_lajur_detail_pallet_his($value->pallet_id);
						// if ($get_data_his != null) {
						// 	$this->M_MutasiPallet->delete_data_to_rak_lajur_detail_pallet_his($value->pallet_id);
						// }
					}

					if ($errorNotSameLastUpdated) {
						$this->db->trans_rollback();
						echo json_encode(2);
					} else if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						echo json_encode(0);
					} else {
						$this->db->trans_commit();
						echo json_encode(1);
					}
				} else {
					echo "Rak tujuan mutasi pallet masih ada yang kosong";
				}
			} else {
				echo "Mutasi pallet " . $cek_mutasi_pallet . " sudah ada";
			}
		}
	}

	public function KonfirmasiMutasiPalet()
	{
		$this->load->model('WMS/M_MutasiPallet', 'M_MutasiPallet');
		$this->load->model('M_Menu');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "U")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('tr_mutasi_pallet_draft_id', 'ID', 'required');
		$this->form_validation->set_rules('tr_mutasi_pallet_id', 'ID Mutasi Pallet', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo json_encode(array('type' => 201, 'message' => validation_errors()));
		} else {

			$tr_mutasi_pallet_draft_id = $this->input->post('tr_mutasi_pallet_draft_id');
			$tr_mutasi_pallet_id = $this->input->post('tr_mutasi_pallet_id');
			$lastUpdated = $this->input->post('lastUpdated');
			$tipe_mutasi = $this->input->post('tipe_mutasi');
			$tr_mutasi_pallet_status = "Completed";
			// echo json_encode(2);
			// die;

			$cek_mutasi_pallet = $this->M_MutasiPallet->Check_MutasiPaletKode2($tr_mutasi_pallet_draft_id);
			$getLastUpdatedDb = $this->db->select("tr_mutasi_pallet_tgl_update")->from("tr_mutasi_pallet")->where("tr_mutasi_pallet_id", $tr_mutasi_pallet_id)->get()->row()->tr_mutasi_pallet_tgl_update;

			$errorNotSameLastUpdated = false;
			if ($lastUpdated !== $getLastUpdatedDb) $errorNotSameLastUpdated = true;
			if ($errorNotSameLastUpdated) {
				echo json_encode(array('type' => 2, 'message' => "Sudah Dihanlde User Lain"));
				return false;
			}
			if ($cek_mutasi_pallet != "") {

				$this->db->trans_begin();

				$result = $this->M_MutasiPallet->Konfirmasi_MutasiPalet($tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_status);
				if ($tipe_mutasi != "Mutasi Pallet Antar Rak") {
					$result2 = $this->M_MutasiPallet->Update_sku_stock($tr_mutasi_pallet_draft_id, $tipe_mutasi);
				}

				$result3 = $this->M_MutasiPallet->Insert_rak_lajur_detail_pallet_his($tr_mutasi_pallet_draft_id);

				// if ($result == 1 && $result2 == 1) {
				if ($result == 1) {

					if ($tipe_mutasi == "Mutasi Pallet Antar Gudang") {
						$identity = "MPAG";
						$who = $this->session->userdata('pengguna_username');

						$this->M_MutasiPallet->Exec_proses_posting_stock_card($identity, $tr_mutasi_pallet_id, $who);
					}

					$rak_lajur_detail_id = $this->M_MutasiPallet->get_data_rak_lajur_detail($tr_mutasi_pallet_draft_id);
					foreach ($rak_lajur_detail_id as $key => $value) {
						//update rak_lajur_detail_detail di tabel pallet
						$this->M_MutasiPallet->update_data_pallet_by_params2($value);
						$this->M_MutasiPallet->update_data_mutasi_pallet_by_params2($tr_mutasi_pallet_id, $value->pallet_id, $value);

						$this->M_MutasiPallet->updatePalletIsLock($value->pallet_id);

						//delete table rak_lajur_detail_pallet by pallet_id
						$get_data = $this->M_MutasiPallet->check_and_get_data_rak_lajur_detail_pallet($value->pallet_id);
						if ($get_data != null) {
							$this->M_MutasiPallet->delete_data_to_rak_lajur_detail_pallet($value->pallet_id);
						}

						//insert pallet id ke table rak_lajur_detail_pallet
						$this->M_MutasiPallet->insert_data_to_rak_lajur_detail_pallet_2($value);

						//delete table rak_lajur_detail_pallet by pallet_id
						// $get_data_his = $this->M_MutasiPallet->check_data_rak_lajur_detail_pallet_his($value->pallet_id);
						// if ($get_data_his != null) {
						// 	$this->M_MutasiPallet->delete_data_to_rak_lajur_detail_pallet_his($value->pallet_id);
						// }
					}

					// $getPalletId = $this->db->select("pallet_id")->from("tr_mutasi_pallet_detail")->where("tr_mutasi_pallet_id", $tr_mutasi_pallet_id)->get()->result();
					// foreach ($getPalletId as $key => $value) {
					// 	//update pallet_is_lock jadi 0
					// 	$this->M_MutasiPallet->updatePalletIsLock($value->pallet_id);
					// }

					// 	echo json_encode(array('type' => 200, 'message' => "Mutasi pallet telah dikonfirmasi"));
					// } else if ($result == 0 && $result2 != 1) {
					// 	echo json_encode(array('type' => 201, 'message' => "Mutasi pallet gagal dikonfirmasi"));
					// } else if ($result2 == 2) {
					// 	echo json_encode(array('type' => 201, 'message' => "SKU stock gudang asal tidak ada!"));
					// } else {
					// 	echo json_encode(array('type' => 201, 'message' => "Mutasi pallet gagal dikonfirmasi"));

					if ($this->db->trans_status() == FALSE) {
						$this->db->trans_rollback();

						if ($result == 0 && $result2 != 1) {
							echo json_encode(array('type' => 201, 'message' => "Mutasi pallet gagal dikonfirmasi"));
						} else if ($result2 == 2) {
							echo json_encode(array('type' => 201, 'message' => "SKU stock gudang asal tidak ada!"));
						} else {
							echo json_encode(array('type' => 201, 'message' => "Mutasi pallet gagal dikonfirmasi"));
						}
					} else {
						$this->db->trans_commit();
						echo json_encode(array('type' => 200, 'message' => "Mutasi pallet telah dikonfirmasi"));
					}
				}
			} else {
				echo json_encode(array('type' => 201, 'message' => "Mutasi pallet " . $cek_mutasi_pallet . " tidak ada !"));
			}
		}
	}

	public function getKodeAutoComplete()
	{

		$this->load->model('WMS/M_MutasiPallet', 'M_MutasiPallet');

		$valueParams = $this->input->get('params');
		$type = $this->input->get('type');
		$result = $this->M_MutasiPallet->getKodeAutoComplete($valueParams, $type);
		echo json_encode($result);
	}
}
