<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class KonfirmasiDistribusiPenerimaan extends ParentController
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
		$this->MenuKode = "126010000";
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_KonfirmasiDistribusiPenerimaan', 'M_KonfirmasiDistribusiPenerimaan');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation']);

		$this->load->library(['form_validation', 'pdfgenerator']);
	}

	public function KonfirmasiDistribusiPenerimaanMenu()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$query['draft_mutasi'] = $this->M_KonfirmasiDistribusiPenerimaan->get_no_draft_mutasi();
		$query['gudangs'] = $this->M_KonfirmasiDistribusiPenerimaan->get_gudangs();

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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/KonfirmasiDistribusiPenerimaan/component/HalamanUtama/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/KonfirmasiDistribusiPenerimaan/component/HalamanUtama/S_HalamanUtama', $data);
	}

	public function get_data_mutasi_pallet_draft_by_kode()
	{
		$no_mutasi = $this->input->post('no_mutasi');
		// $tgl = $this->input->post('tgl');
		$gudang_tujuan = $this->input->post('gudang_tujuan');
		$status = $this->input->post('status');
		$result = $this->M_KonfirmasiDistribusiPenerimaan->get_data_mutasi_pallet_draft_by_kode($no_mutasi, $gudang_tujuan, $status);
		echo json_encode($result);
	}

	public function konfirmasi($id)
	{
		$data = array();
		$data['id'] = $id;

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// //get data pallet by tr_mutasi_pallet_id
		// $get_data = $this->M_KonfirmasiDistribusiPenerimaan->get_data_pallet_by_id_mutasi($id);

		// //insert ke pallet_temp
		// foreach ($get_data as $key => $value) {
		//     //cek jika ada pallet_id di maka jgn insert, klo kosong insert
		//     $existing = $this->M_KonfirmasiDistribusiPenerimaan->existing_data_in_pallet_temp($value->pallet_id);
		//     if ($existing == null) {
		//         $this->M_KonfirmasiDistribusiPenerimaan->insert_to_pallet_temp($value);
		//     }
		// }

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',
			Get_Assets_Url() . 'node_modules/lightbox2/src/css/lightbox.css'
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js',
			Get_Assets_Url() . 'node_modules/lightbox2/src/js/lightbox.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/KonfirmasiDistribusiPenerimaan/KonfirmasiDistribusiPenerimaan', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/KonfirmasiDistribusiPenerimaan/S_KonfirmasiDistribusiPenerimaan', $data);
	}

	public function get_data_header_konfirmasi()
	{
		$id = $this->input->post('id');
		$header = $this->M_KonfirmasiDistribusiPenerimaan->get_data_header_konfirmasi($id);
		$detail = $this->M_KonfirmasiDistribusiPenerimaan->get_data_detail_konfirmasi($id);
		echo json_encode(array('header' => $header, 'detail' => $detail));
	}

	public function get_data_detail_pallet()
	{
		$id = $this->input->post('id');
		$result = $this->M_KonfirmasiDistribusiPenerimaan->get_data_detail_pallet($id);
		echo json_encode($result);
	}

	public function check_kode_pallet_by_no_mutasi()
	{
		$id = $this->input->post('id');
		$kode_pallet = $this->input->post('kode_pallet');

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_KonfirmasiDistribusiPenerimaan->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		// $get_data = $this->M_KonfirmasiDistribusiPenerimaan->getPrefixPallet($id);

		// $kode = $unit . "/" . $kode_pallet;
		$kode = preg_replace('/\s+/', '', $unit  . "/" . $kode_pallet);

		$result = $this->M_KonfirmasiDistribusiPenerimaan->check_kode_pallet_by_no_mutasi($id, $kode);
		if ($result == null) {
			echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan", 'kode' => $kode));
		} else {
			//cek jik status null maka update
			if ($result->status == null) {
				// //jika cek barcode menggunakan manual input
				// if (empty($_FILES['file']['name'])) {
				// 	//jika cek barcode menggunakan scan
				// 	//jika status 0 maka update
				// 	$this->M_KonfirmasiDistribusiPenerimaan->update_status_tmpdd($result, $file = "");
				// 	echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> <span class='text-success'>Valid</span>", 'kode' => $kode));
				// } else {
				// 	$uploadDirectory = "assets/images/uploads/Bukti-Cek-Pallet/";
				// 	$fileExtensionsAllowed = ['jpeg', 'jpg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF']; // Allowed file extensions
				// 	$fileName = $_FILES['file']['name'];
				// 	$fileSize = $_FILES['file']['size'];
				// 	$fileTmpName  = $_FILES['file']['tmp_name'];
				// 	$fileType = $_FILES['file']['type'];
				// 	$file = explode(".", $fileName);
				// 	$fileExtension = strtolower(end($file));
				// 	$name_file = 'bukti-' . time() . '.' . $fileExtension;

				// 	if (!in_array($fileExtension, $fileExtensionsAllowed)) {
				// 		echo json_encode(array('type' => 201, 'message' => "Gagal! File Attactment tidak sesuai ketentuan (JPG & PNG, GIF)", 'kode' => $kode));
				// 	} else {
				// 		$res = $this->M_KonfirmasiDistribusiPenerimaan->update_status_tmpdd($result, $name_file);
				// 		if ($res) {
				// 			$uploadPath = $uploadDirectory . $name_file;
				// 			compressImage($fileTmpName, $uploadPath, 10);
				// 			echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> <span class='text-success'>Valid</span>", 'kode' => $kode));
				// 		} else {
				// 			echo json_encode(array('type' => 201, 'message' => "Terjadi kesalahan pada server", 'kode' => $kode));
				// 		}
				// 	}
				// }

				$this->M_KonfirmasiDistribusiPenerimaan->update_status_tmpdd($result, $file = "");
				echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> <span class='text-success'>Valid</span>", 'kode' => $kode));
			} else if ($result->status == 0) {
				//jika status 0 maka tampilkan message
				echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> masih belum valid", 'kode' => $kode));
			} else {
				//jika status sudah 1 maka tampilkan message sudah divalidasi
				echo json_encode(array('type' => 202, 'message' => "Kode pallet <strong>" . $kode . "</strong> sudah tervalidasi", 'kode' => $kode));
			}
		}
	}
	public function check_kode_pallet_tujuan_by_no_mutasi()
	{
		$id = $this->input->post('id');
		$kode_pallet = $this->input->post('kode_pallet');
		$rak_lajur = $this->input->post('rak_lajur');
		$tr_mutasi_pallet_detail_draft_id = $this->input->post('tr_mutasi_pallet_detail_draft_id');

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_KonfirmasiDistribusiPenerimaan->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		// $get_data = $this->M_KonfirmasiDistribusiPenerimaan->getPrefixPallet($id);

		// $kode = $unit . "/" . $kode_pallet;
		$kode = preg_replace('/\s+/', '', $unit  . "/" . $kode_pallet);

		$result = $this->M_KonfirmasiDistribusiPenerimaan->check_kode_pallet_tujuan_by_no_mutasi($id, $kode, $rak_lajur);
		if ($result == null) {
			echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan", 'kode' => $kode));
		} else {
			//cek jik status null maka update
			// if ($result->status == null) {

			$this->M_KonfirmasiDistribusiPenerimaan->update_data_pallet_tujuan_by_params($result, $id, $tr_mutasi_pallet_detail_draft_id);

			echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> <span class='text-success'>Valid</span>", 'kode' => $kode));
			// } else if ($result->status == 0) {
			// 	//jika status 0 maka tampilkan message
			// 	echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> masih belum valid", 'kode' => $kode));
			// } else {
			// 	//jika status sudah 1 maka tampilkan message sudah divalidasi
			// 	echo json_encode(array('type' => 202, 'message' => "Kode pallet <strong>" . $kode . "</strong> sudah tervalidasi", 'kode' => $kode));
			// }
		}
	}

	public function check_rak_lajur_detail()
	{
		$id = $this->input->post('id');
		$pallet_id = $this->input->post('pallet_id');
		$gudang_tujuan = $this->input->post('gudang_tujuan');
		$kode = $this->input->post('kode');

		$response = $this->M_KonfirmasiDistribusiPenerimaan->check_rak_lajur_detail($gudang_tujuan, $kode);
		if ($response == null) {
			echo json_encode(array('type' => 201, 'message' => "Rak <strong>" . $kode . "</strong> tidak ditemukan", 'kode' => $kode));
		} else {

			//get data tr_mutasi_pallet_draft_id
			$data = $this->M_KonfirmasiDistribusiPenerimaan->get_data_tr_mutasi_pallet_draft_id($id, $pallet_id);

			//update rak_detail_id di tr_mutasi_pallet_detail_draft
			$this->M_KonfirmasiDistribusiPenerimaan->update_data_pallet_by_params($response, $data);

			echo json_encode(array('type' => 200, 'message' => "Rak <strong>" . $kode . "</strong> ditemukan dan berhasil update", 'kode' => $kode));
		}
	}

	public function check_exist_in_tr_mutasi_pallet()
	{
		$id = $this->input->post('id');
		$result = $this->M_KonfirmasiDistribusiPenerimaan->check_exist_in_tr_mutasi_pallet($id);
		echo json_encode($result);
	}

	public function save_data()
	{
		// die;
		$mutasi_draft_id = $this->input->post('mutasi_draft_id');
		$principle = $this->input->post('principle');
		$client_wms = $this->input->post('client_wms');
		$tgl = $this->input->post('tgl');
		$tipe_transaksi = $this->input->post('tipe_transaksi');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan');
		$gudang_detail_asal = $this->input->post('gudang_detail_asal');
		$gudang_detail_tujuan = $this->input->post('gudang_detail_tujuan');
		$checker = $this->input->post('checker');
		$lastUpdated = $this->input->post('lastUpdated');

		$mp_id = $this->M_Vrbl->Get_NewID();
		$mp_id = $mp_id[0]['NEW_ID'];

		//generate kode
		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_MPAG';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_KonfirmasiDistribusiPenerimaan->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$result = "";

		$this->db->trans_begin();

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "tr_mutasi_pallet_draft",
			'whereField' => "tr_mutasi_pallet_draft_id",
			'whereValue' => $mutasi_draft_id,
			'fieldDateUpdate' => "tr_mutasi_pallet_draft_tgl_update",
			'fieldWhoUpdate' => "tr_mutasi_pallet_draft_who_update",
			'lastUpdated' => $lastUpdated
		]);

		//check exist or not in table tr_mutasi_pallet by mutasi draft id
		$exist = $this->M_KonfirmasiDistribusiPenerimaan->check_exist_in_tr_mutasi_pallet($mutasi_draft_id);
		if ($exist == null) {
			$this->save_to_db($mp_id, $generate_kode, $tgl, $mutasi_draft_id, $principle, $client_wms, $tipe_transaksi, $status, $keterangan, $gudang_detail_asal, $gudang_detail_tujuan, $checker);
		} else {
			//delete data tr_mutasi_pallet dan detail
			$get_data_tr_mutasi = $this->M_KonfirmasiDistribusiPenerimaan->get_data_tr_mutasi($mutasi_draft_id);

			$this->M_KonfirmasiDistribusiPenerimaan->delete_data_tr_mutasi_dan_detail($mutasi_draft_id, $get_data_tr_mutasi->tr_mutasi_pallet_id);

			$this->save_to_db($mp_id, $generate_kode, $tgl, $mutasi_draft_id, $principle, $client_wms, $tipe_transaksi, $status, $keterangan, $gudang_detail_asal, $gudang_detail_tujuan, $checker);
		}

		//get data rak_lajur_detail di tabel pallet_temp
		$rak_lajur_detail_id = $this->M_KonfirmasiDistribusiPenerimaan->get_data_rak_lajur_detail($mutasi_draft_id);
		foreach ($rak_lajur_detail_id as $key => $value) {
			if ($value->rak_lajur_detail_id_tujuan != '' || $value->rak_lajur_detail_id_tujuan != null) {
				//update rak_lajur_detail_detail di tabel pallet
				$this->M_KonfirmasiDistribusiPenerimaan->update_data_pallet_by_params2($value);
				//delete table rak_lajur_detail_pallet by pallet_id
				$get_data = $this->M_KonfirmasiDistribusiPenerimaan->check_and_get_data_rak_lajur_detail_pallet($value->pallet_id);
				if ($get_data != null) {
					$this->M_KonfirmasiDistribusiPenerimaan->delete_data_to_rak_lajur_detail_pallet($value->pallet_id);
				}

				//insert pallet id ke table rak_lajur_detail_pallet
				$this->M_KonfirmasiDistribusiPenerimaan->insert_data_to_rak_lajur_detail_pallet($value);
			}
			// if ($value->pallet_id_tujuan != '') {
			// 	$this->M_KonfirmasiDistribusiPenerimaan->eksekusi_pallet($mutasi_draft_id, $value, $gudang_detail_asal, $gudang_detail_tujuan);
			// }
		}

		echo json_encode(responseJson((object)[
			'lastUpdatedChecked' => $lastUpdatedChecked,
			'status' => 'Disimpan'
		]));

		// if ($lastUpdatedChecked['status'] === 400) {
		// 	$this->db->trans_rollback();
		// 	$response = [
		// 		'status' => 400,
		// 		'message' => 'Data Gagal Disimpan',
		// 		'lastUpdatedNew' => null
		// 	];
		// } else if ($this->db->trans_status() === FALSE) {
		// 	$this->db->trans_rollback();
		// 	$response = [
		// 		'status' => 401,
		// 		'message' => 'Data Gagal Disimpan',
		// 		'lastUpdatedNew' => null
		// 	];
		// } else {
		// 	$this->db->trans_commit();
		// 	$response = [
		// 		'status' => 200,
		// 		'message' => 'Data Berhasil Disimpan',
		// 		'lastUpdatedNew' => $lastUpdatedChecked['lastUpdatedNew']
		// 	];
		// }

		// echo json_encode($response);

		// if ($this->db->trans_status() === FALSE) {
		// 	$this->db->trans_rollback();
		// 	echo json_encode(0);
		// } else {
		// 	$this->db->trans_commit();
		// 	echo json_encode(1);
		// }
	}

	private function save_to_db($mp_id, $generate_kode, $tgl, $mutasi_draft_id, $principle, $client_wms, $tipe_transaksi, $status, $keterangan, $gudang_detail_asal, $gudang_detail_tujuan, $checker)
	{
		$this->M_KonfirmasiDistribusiPenerimaan->save_data_to_tr_mutasi_pallet($mp_id, $generate_kode, $tgl, $mutasi_draft_id, $principle, $client_wms, $tipe_transaksi, $status, $keterangan, $gudang_detail_asal, $gudang_detail_tujuan, $checker);

		//get data tr_mutasi_pallet_detail_draft by mutasi_draft_id
		$get_data = $this->M_KonfirmasiDistribusiPenerimaan->get_data_tmpd_by_mutasi_draft_id($mutasi_draft_id);

		//insert ke tr_mutasi_pallet_detail
		foreach ($get_data as $val) {
			$this->M_KonfirmasiDistribusiPenerimaan->save_data_to_tr_mutasi_pallet_detail($mp_id, $val);
		}
	}

	public function confirm_data()
	{
		$mutasi_draft_id = $this->input->post('mutasi_draft_id');
		$distribusi_id = $this->input->post('distribusi_id');
		$tipe_transaksi = $this->input->post('tipe_transaksi');
		$gudang_detail_asal = $this->input->post('gudang_detail_asal');
		$gudang_detail_tujuan = $this->input->post('gudang_detail_tujuan');
		$who_create = $this->session->userdata('pengguna_username');
		$lastUpdated = $this->input->post('lastUpdated');

		// $this->db->trans_begin();

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "tr_mutasi_pallet_draft",
			'whereField' => "tr_mutasi_pallet_draft_id",
			'whereValue' => $mutasi_draft_id,
			'fieldDateUpdate' => "tr_mutasi_pallet_draft_tgl_update",
			'fieldWhoUpdate' => "tr_mutasi_pallet_draft_who_update",
			'lastUpdated' => $lastUpdated
		]);

		//update status tr_mutasi_pallet dan tr_mutasi_pallet_draft
		$this->M_KonfirmasiDistribusiPenerimaan->update_data_tr_mutasi($mutasi_draft_id);

		//get status table tr_mutasi_pallet_draft by distribusi_id
		$get_status_tmpd = $this->M_KonfirmasiDistribusiPenerimaan->get_status_tmpd($distribusi_id);
		$temp = [];
		foreach ($get_status_tmpd as $key => $value) {
			if ($value->status == "Completed") {
				array_push($temp, $value);
			}
		}

		if (count($get_status_tmpd) == count($temp)) {
			//update status distribusi_penerimaan dngan syarat table di tr_mutasi_pallet_draft yg punya id distribusi completed smua
			$this->M_KonfirmasiDistribusiPenerimaan->update_status_table_distribusi($distribusi_id);
		}

		//get_data_pallet_temp
		$get_data = $this->M_KonfirmasiDistribusiPenerimaan->get_data_rak_lajur_detail($mutasi_draft_id);
		foreach ($get_data as $key => $data) {
			if ($data->rak_lajur_detail_id_tujuan != '' || $data->rak_lajur_detail_id_tujuan != null) {
				//insert pallet id ke table rak_lajur_detail_pallet_his
				$this->M_KonfirmasiDistribusiPenerimaan->insert_data_to_rak_lajur_detail_pallet_his($tipe_transaksi, $gudang_detail_asal, $gudang_detail_tujuan, $data);
			}
		}

		$data_ = $this->M_KonfirmasiDistribusiPenerimaan->check_data_in_sku_stock($mutasi_draft_id);
		foreach ($data_ as $key => $value) {
			$check_data_sku_stock_asal = $this->M_KonfirmasiDistribusiPenerimaan->check_data_sku_stock_asal($value);
			$check_data_sku_stock_tujuan = $this->M_KonfirmasiDistribusiPenerimaan->check_data_sku_stock_tujuan($value);
			// echo json_encode([
			//     'data' => $value,
			//     'stock_asal' => $check_data_sku_stock_asal->row(0),
			//     'stock_tujuan' => $check_data_sku_stock_tujuan->row(0)
			// ]);
			if ($check_data_sku_stock_tujuan->num_rows() == 0) {
				//insert ke table sku_stock
				$this->M_KonfirmasiDistribusiPenerimaan->insert_data_to_sku_stock($value, $check_data_sku_stock_asal->row(0));
			} else {
				//update ke table sku_stock
				$this->M_KonfirmasiDistribusiPenerimaan->update_data_to_sku_stock($value, $check_data_sku_stock_asal->row(0), $check_data_sku_stock_tujuan->row(0));
			}
		}
		//delete data di table pallet_temp by mutasi_id
		$this->M_KonfirmasiDistribusiPenerimaan->delete_data_in_pallet_temp($mutasi_draft_id);

		//get data id tr_mutasi_pallet
		$datas = $this->M_KonfirmasiDistribusiPenerimaan->get_data_tr_mutasi_id($mutasi_draft_id);
		//prosedure sku_stock_card
		// if ($datas->rak_lajur_detail_id_tujuan != '' || $datas->rak_lajur_detail_id_tujuan != null) {
		$this->db->query("exec proses_posting_stock_card 'MPAG', '$datas->id', '$who_create'");
		// }
		$data2_ = $this->M_KonfirmasiDistribusiPenerimaan->check_data_in_sku_stock($mutasi_draft_id);
		foreach ($data2_ as $key => $value) {
			if ($value->pallet_id_tujuan != null || $value->pallet_id_tujuan != '') {
				$this->db->query("delete pallet_detail where pallet_detail_id ='$value->pallet_detail_id'");
			}
		}
		echo json_encode(responseJson((object)[
			'lastUpdatedChecked' => $lastUpdatedChecked,
			'status' => 'Dikonfirmasi'
		]));

		// if ($this->db->trans_status() === FALSE) {
		// 	$this->db->trans_rollback();
		// 	echo json_encode(0);
		// } else {
		// 	$this->db->trans_commit();
		// 	echo json_encode(1);
		// }
	}

	public function view($id)
	{
		$data = array();
		$data['id'] = $id;

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',
			Get_Assets_Url() . 'node_modules/lightbox2/src/css/lightbox.css'
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js',
			Get_Assets_Url() . 'node_modules/lightbox2/src/js/lightbox.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/KonfirmasiDistribusiPenerimaan/component/view/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/KonfirmasiDistribusiPenerimaan/component/view/S_view', $data);
	}

	public function get_data_header_konfirmasi_view()
	{
		$id = $this->input->post('id');
		$header = $this->M_KonfirmasiDistribusiPenerimaan->get_data_header_konfirmasi_view($id);
		$detail = $this->M_KonfirmasiDistribusiPenerimaan->get_data_detail_konfirmasi_view($id);
		echo json_encode(array('header' => $header, 'detail' => $detail));
	}

	public function get_data_detail_pallet_view()
	{
		$id = $this->input->post('id');
		$result = $this->M_KonfirmasiDistribusiPenerimaan->get_data_detail_pallet_view($id);
		echo json_encode($result);
	}

	public function checkKodePallet()
	{
		$kode_pallet = $this->input->post('kode_pallet');

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_KonfirmasiDistribusiPenerimaan->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		// $kode = $unit . "/" . $kode_pallet;
		$kode = preg_replace('/\s+/', '', $unit  . "/" . $kode_pallet);

		$result = $this->M_KonfirmasiDistribusiPenerimaan->checkKodePallet($kode);
		if (empty($result)) {
			echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan", 'kode' => $kode, 'data' => []));
		} else {
			echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> ditemukan", 'kode' => $kode, 'data' => $result));
		}
	}

	public function getKodeAutoComplete()
	{
		$valueParams = $this->input->get('params');
		$type = $this->input->get('type');
		$result = $this->M_KonfirmasiDistribusiPenerimaan->getKodeAutoComplete($valueParams, $type);
		echo json_encode($result);
	}

	public function setprint()
	{
		$id = $this->input->post('id');
		$this->session->set_userdata('sess_id_temp', $id);

		echo 1;
	}
	public function print_cetak()
	{
		$id = $this->session->userdata('sess_id_temp');
		$arr = array();

		foreach ($id as $key => $value) {
			array_push($arr, $this->M_KonfirmasiDistribusiPenerimaan->get_data_detail_konfirmasi_view_cetak($value));
		}

		$data['s_detail'] = $arr;

		//title dari pdf
		$this->data['title_pdf'] = 'Laporan Serah Terima Barang';

		// //filename dari pdf ketika didownload
		$file_pdf = 'report_distribusi_pengiriman_' . date('d-M-Y H:i:s');
		// //setting paper
		$paper = 'A4';
		// //orientasi paper potrait / landscape
		$orientation = "landscape";

		$html = $this->load->view('WMS/KonfirmasiDistribusiPenerimaan/component/view_cetak', $data, true);

		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
		// $this->session->unset_userdata('sess_id_temp');
	}
}
