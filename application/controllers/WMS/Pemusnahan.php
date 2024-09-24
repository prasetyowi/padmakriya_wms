<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';
class Pemusnahan extends ParentController
{

	private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		if ($this->session->has_userdata('pengguna_id') == 0) :
			redirect(base_url('MainPage'));
		endif;

		$this->depo_id = $this->session->userdata('depo_id');
		$this->MenuKode = "120009000";
		$this->load->model(['M_Menu', ['WMS/M_Pemusnahan', 'M_Pemusnahan'], 'M_Function', 'M_MenuAccess', 'M_Vrbl', 'M_AutoGen']);

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation']);
	}

	public function PemusnahanMenu()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['stock_corrections'] = $this->M_Pemusnahan->get_stock_corrections();
		$data['warehouses'] = $this->M_Pemusnahan->get_warehouses();
		$data['principles'] = $this->M_Pemusnahan->get_principles();
		$data['type_transactions'] = $this->M_Pemusnahan->get_type_transactions();

		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

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


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/Pemusnahan/Pemusnahan', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Pemusnahan/S_Pemusnahan', $data);
	}

	public function get_checker_by_principleId()
	{
		$id = $this->input->post('id');
		$result = $this->M_Pemusnahan->get_checker_by_principleId($id);
		echo json_encode($result);
	}

	public function get_data_koreksi_stok_by_filter()
	{
		$filter_no_koreksi = $this->input->post('filter_no_koreksi');
		$filter_koreksi_tgl = $this->input->post('filter_koreksi_tgl');
		$filter_gudang_asal_koreksi = $this->input->post('filter_gudang_asal_koreksi');
		$filter_koreksi_tipe_transaksi = $this->input->post('filter_koreksi_tipe_transaksi');
		$filter_koreksi_principle = $this->input->post('filter_koreksi_principle');
		$filter_koreksi_checker = $this->input->post('filter_koreksi_checker');
		$filter_koreksi_status = $this->input->post('filter_koreksi_status');

		$result = $this->M_Pemusnahan->get_data_koreksi_stok_by_filter($filter_no_koreksi, $filter_koreksi_tgl, $filter_gudang_asal_koreksi, $filter_koreksi_tipe_transaksi, $filter_koreksi_principle, $filter_koreksi_checker, $filter_koreksi_status);

		echo json_encode($result);
	}

	public function add()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['no_koreksi'] = $this->M_Pemusnahan->getNoKoreksiDraft();

		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
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


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/Pemusnahan/component/form_tambah_data/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Pemusnahan/component/form_tambah_data/S_tambah', $data);
	}

	public function getDataKoreksiDraftById()
	{
		$id = $this->input->post('id');
		$header = $this->M_Pemusnahan->getDataKoreksiDraftHeader($id);

		//get data detail draft kemudian insert ke temp
		$get_data = $this->M_Pemusnahan->getDataKoreksiDraftDetail($id);

		//get and check exist data in temp
		$exist = $this->M_Pemusnahan->check_exist_data_in_detail_temp($id);
		if ($exist == null) {
			//insert ke table temp
			foreach ($get_data as $key => $value) {
				$this->M_Pemusnahan->insertDataToTableDetailTemp($value);
			}
		}

		//get data in table temp
		$detail = $this->M_Pemusnahan->getDataKoreksiDraftDetailTemp($id);

		echo json_encode(array('header' => $header, 'detail' => $detail));
	}

	public function getDataPalletBySkuStockId()
	{
		$koreksi_draft_id = $this->input->post('koreksi_draft_id');
		$id = $this->input->post('id');
		$gudang_id = $this->input->post('gudang_id');
		$ed = $this->input->post('ed');

		//get data detail draft kemudian insert ke temp
		$get_data = $this->M_Pemusnahan->getDataPalletBySkuStockId($id, $gudang_id, $ed);

		//get and check exist data in temp
		foreach ($get_data as $key => $value) {
			$exist = $this->M_Pemusnahan->check_exist_data_in_detail2_temp($value);
			if ($exist == null) {
				//insert ke table temp
				$data = $this->M_Pemusnahan->insertDataToTableDetail2Temp($value);
			}
		}
		$result = $this->M_Pemusnahan->getDataPalletBySkuStockIdTemp($koreksi_draft_id, $id, $gudang_id, $ed);
		echo json_encode($result);
	}

	public function getDataPemusnahanPallet()
	{
		$koreksi_id = $this->input->post('koreksi_id');
		$id = $this->input->post('id');
		$gudang_id = $this->input->post('gudang_id');
		$ed = $this->input->post('ed');

		$result = $this->M_Pemusnahan->getDataPemusnahanPallet($koreksi_id, $id, $gudang_id, $ed);
		echo json_encode($result);
	}

	public function UpdateQtyAmbilInDetail2Temp()
	{
		$id = $this->input->post('id');
		$pallet_id = $this->input->post('pallet_id');
		$qty = $this->input->post('qty');
		$result = $this->M_Pemusnahan->UpdateQtyAmbilInDetail2Temp($id, $pallet_id, $qty);
		echo json_encode($result);
	}

	public function check_kode_pallet_by_no_pallet()
	{
		$id = $this->input->post('id');
		$kpdd_id = $this->input->post('kpdd_id');
		$kode_pallet = $this->input->post('kode_pallet');

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_Pemusnahan->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		$kode = $unit . "/" . $kode_pallet;

		$result = $this->M_Pemusnahan->check_kode_pallet_by_no_pallet($kpdd_id, $id, $kode);
		if ($result == null) {
			echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan", 'kode' => $kode));
		} else {
			//cek jik status null maka update
			if ($result->status == null) {
				//jika cek barcode menggunakan manual input
				if (empty($_FILES['file']['name'])) {
					//jika cek barcode menggunakan scan
					//jika status 0 maka update
					$this->M_Pemusnahan->update_status_tmpdd($result, $file = "");
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
						$res = $this->M_Pemusnahan->update_status_tmpdd($result, $name_file);
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

	public function check_exist_in_tr_koreksi_draft()
	{
		$id = $this->input->post('id');
		$result = $this->M_Pemusnahan->check_exist_in_tr_koreksi_draft($id);
		echo json_encode($result);
	}

	public function save_data()
	{
		$koreksi_draft_id = $this->input->post('koreksi_draft_id');
		$principle_id = $this->input->post('principle_id');
		$tgl = $this->input->post('tgl');
		$tipe_id = $this->input->post('tipe_id');
		$keterangan = $this->input->post('keterangan');
		$gudang_id = $this->input->post('gudang_id');
		$checker = $this->input->post('checker');

		$kp_id = $this->M_Vrbl->Get_NewID();
		$kp_id = $kp_id[0]['NEW_ID'];

		//generate kode
		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_KSB';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_Pemusnahan->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$result = "";

		$lastUpdated = $this->input->post('lastUpdated');
		$getLastUpdatedDb = $this->db->select("tr_koreksi_stok_draft_tgl_update")->from("tr_koreksi_stok_draft")->where("tr_koreksi_stok_draft_id", $koreksi_draft_id)->get()->row()->tr_koreksi_stok_draft_tgl_update;

		$errorNotSameLastUpdated = false;
		if ($lastUpdated !== $getLastUpdatedDb) $errorNotSameLastUpdated = true;
		if ($errorNotSameLastUpdated) {
			// $this->db->trans_rollback();
			// echo json_encode(array('type' => 2, 'message' => "Sudah Dihanlde User Lain"));
			echo json_encode(2);
			return false;
		}

		$this->db->trans_begin();

		//check exist or not in table tr_koreksi_stok by mutasi draft id
		$exist = $this->M_Pemusnahan->check_exist_in_tr_koreksi_draft($koreksi_draft_id);

		if ($exist == null) {
			$this->save_to_db($kp_id, $generate_kode, $tgl, $koreksi_draft_id, $principle_id, $tipe_id, $keterangan, $gudang_id, $checker);
		} else {
			//delete data tr_koreksi_stok dan detail
			$get_data_tr_koreksi_stok = $this->M_Pemusnahan->get_data_tr_koreksi_stok($koreksi_draft_id);

			$this->M_Pemusnahan->delete_data_tr_koreksi_dan_detail($koreksi_draft_id, $get_data_tr_koreksi_stok->tr_koreksi_stok_id);

			$this->save_to_db($kp_id, $generate_kode, $tgl, $koreksi_draft_id, $principle_id, $tipe_id, $keterangan, $gudang_id, $checker);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function update_data()
	{
		$koreksi_id = $this->input->post('koreksi_id');
		$koreksi_draft_id = $this->input->post('koreksi_draft_id');
		$keterangan = $this->input->post('keterangan');
		$result = "";
		$lastUpdated = $this->input->post('lastUpdated');

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "tr_koreksi_stok",
			'whereField' => "tr_koreksi_stok_id",
			'whereValue' => $koreksi_id,
			'fieldDateUpdate' => "tr_koreksi_stok_pallet_tgl_update",
			'fieldWhoUpdate' => "tr_koreksi_stok_pallet_who_update",
			'lastUpdated' => $lastUpdated
		]);

		if ($lastUpdatedChecked['status'] == 400) {
			echo json_encode(2);
			return false;
		}

		$this->db->trans_begin();

		//delete data tr_koreksi_stok dan detail
		$get_data_tr_koreksi_stok = $this->M_Pemusnahan->get_data_tr_koreksi_stok($koreksi_draft_id);

		$this->M_Pemusnahan->delete_data_tr_koreksi_detail($get_data_tr_koreksi_stok->tr_koreksi_stok_id);
		$this->M_Pemusnahan->delete_data_tr_koreksi_detail2($get_data_tr_koreksi_stok->tr_koreksi_stok_id);

		$this->update_to_db($koreksi_draft_id, $koreksi_id, $keterangan);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	private function save_to_db($kp_id, $generate_kode, $tgl, $koreksi_draft_id, $principle_id, $tipe_id, $keterangan, $gudang_id, $checker)
	{
		$this->M_Pemusnahan->save_data_to_tr_koreksi_stok($kp_id, $generate_kode, $tgl, $koreksi_draft_id, $principle_id, $tipe_id, $keterangan, $gudang_id, $checker);

		//get data tr_koreksi_stok_detail_draft by koreksi_draft_id
		$get_data = $this->M_Pemusnahan->get_data_tmpd_by_koreksi_draft_id($koreksi_draft_id);

		//insert ke tr_koreksi_stok_detail
		foreach ($get_data as $val) {
			$kpd_id = $this->M_Vrbl->Get_NewID();
			$kpd_id = $kpd_id[0]['NEW_ID'];

			$get_detail_data = $this->M_Pemusnahan->get_data_detail2_tmpd($val->tr_koreksi_stok_detail_draft_id, $koreksi_draft_id);
			foreach ($get_detail_data as $key => $value) {
				//insert ke tr_koreksi_detail2
				$this->M_Pemusnahan->save_data_to_tr_koreksi_stok_detail2($kpd_id, $kp_id, $value);
			}

			$this->M_Pemusnahan->save_data_to_tr_koreksi_stok_detail($kpd_id, $kp_id, $val);
		}
	}

	private function update_to_db($koreksi_draft_id, $koreksi_id, $keterangan)
	{
		$this->M_Pemusnahan->update_data_to_tr_koreksi_stok($koreksi_draft_id, $koreksi_id, $keterangan);

		//get data tr_koreksi_stok_detail_draft by koreksi_draft_id
		$get_data = $this->M_Pemusnahan->get_data_tmpd_by_koreksi_draft_id($koreksi_draft_id);

		//insert ke tr_koreksi_stok_detail
		foreach ($get_data as $val) {
			$kpd_id = $this->M_Vrbl->Get_NewID();
			$kpd_id = $kpd_id[0]['NEW_ID'];

			$get_detail_data = $this->M_Pemusnahan->get_data_detail2_tmpd($val->tr_koreksi_stok_detail_draft_id, $koreksi_draft_id);
			foreach ($get_detail_data as $key => $value) {
				//insert ke tr_koreksi_detail2
				$this->M_Pemusnahan->save_data_to_tr_koreksi_stok_detail2($kpd_id, $koreksi_id, $value);
			}

			$this->M_Pemusnahan->save_data_to_tr_koreksi_stok_detail($kpd_id, $koreksi_id, $val);
		}
	}

	public function confirm_data()
	{
		$koreksi_draft_id 	= $this->input->post('koreksi_draft_id');
		$koreksi_id 		= $this->input->post('koreksi_id');
		$keterangan 		= $this->input->post('keterangan');
		$tipe_id 			= $this->input->post('tipe_id');
		$tipe 				= "";
		$data_detail 		= $this->input->post('data_detail');
		$who_create 		= $this->session->userdata('pengguna_username');
		$lastUpdated 		= $this->input->post('lastUpdated');

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "tr_koreksi_stok",
			'whereField' => "tr_koreksi_stok_id",
			'whereValue' => $koreksi_id,
			'fieldDateUpdate' => "tr_koreksi_stok_pallet_tgl_update",
			'fieldWhoUpdate' => "tr_koreksi_stok_pallet_who_update",
			'lastUpdated' => $lastUpdated
		]);

		if ($lastUpdatedChecked['status'] == 400) {
			echo json_encode(2);
			return false;
		}

		$this->db->trans_begin();

		//update status tr_koreksi_stok dan tr_koreksi_stok_draft
		$get_data_tr_koreksi_stok = $this->M_Pemusnahan->get_data_tr_koreksi_stok($koreksi_draft_id);
		$koreksi_id = $get_data_tr_koreksi_stok->tr_koreksi_stok_id;

		$this->M_Pemusnahan->delete_data_tr_koreksi_detail($get_data_tr_koreksi_stok->tr_koreksi_stok_id);
		$this->M_Pemusnahan->delete_data_tr_koreksi_detail2($get_data_tr_koreksi_stok->tr_koreksi_stok_id);

		$this->update_to_db($koreksi_draft_id, $koreksi_id, $keterangan);

		$this->M_Pemusnahan->update_data_tr_koreksi($koreksi_draft_id);

		$get_tipe_name = $this->db->select("tipe_mutasi_nama")->from("tipe_mutasi")->where("tipe_mutasi_id", $tipe_id)->get()->row();

		//update di sku_stock berdasarkan tipe keluar atau masuk
		$this->M_Pemusnahan->update_sku_stock_by_tipe_id($get_tipe_name, $data_detail);

		//delete detail temp dan detail2 temp
		$this->M_Pemusnahan->delete_data_in_detail_temp($koreksi_draft_id);

		if ($get_tipe_name->tipe_mutasi_nama == "Koreksi Masuk") {
			$tipe = "KOREKSIIN";
		}

		if ($get_tipe_name->tipe_mutasi_nama == "Koreksi Keluar") {
			$tipe = "KOREKSIOUT";
		}

		//get data id tr_koreksi_id
		$datas = $this->M_Pemusnahan->get_data_tr_koreksi_stok_id($koreksi_draft_id);

		//prosedure sku_stock_card
		$this->db->query("exec proses_posting_stock_card '$tipe', '$datas->id', '$who_create'");


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function cancel_pallet()
	{
		$id = $this->input->post('id');
		$pallet_id = $this->input->post('pallet_id');
		$result = $this->M_Pemusnahan->cancel_pallet($id, $pallet_id);
		echo json_encode($result);
	}

	public function edit($id)
	{
		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['id'] = $id;
		// $data['warehouses'] = $this->M_PemusnahanDraft->get_warehouses();
		// $data['principles'] = $this->M_PemusnahanDraft->get_principles();
		// $data['type_transactions'] = $this->M_PemusnahanDraft->get_type_transactions();
		// $data['depo'] = $this->M_PemusnahanDraft->get_DepoNameBySession();

		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
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


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/Pemusnahan/component/form_edit_data/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Pemusnahan/component/form_edit_data/S_edit', $data);
	}

	public function view($id)
	{
		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['id'] = $id;
		// $data['warehouses'] = $this->M_PemusnahanDraft->get_warehouses();
		// $data['principles'] = $this->M_PemusnahanDraft->get_principles();
		// $data['type_transactions'] = $this->M_PemusnahanDraft->get_type_transactions();
		// $data['depo'] = $this->M_PemusnahanDraft->get_DepoNameBySession();

		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
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


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/Pemusnahan/component/form_view_data/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Pemusnahan/component/form_view_data/S_view', $data);
	}

	public function getDataKoreksiEditById()
	{
		$id = $this->input->post('id');
		$header = $this->M_Pemusnahan->getDataKoreksiHeaderEdit($id);

		$detail = $this->M_Pemusnahan->getDataKoreksiDetailEdit($id);

		echo json_encode(array('header' => $header, 'detail' => $detail));
	}

	public function check_exist_in_tr_koreksi_edit()
	{
		$id = $this->input->post('id');
		$result = $this->M_Pemusnahan->check_exist_in_tr_koreksi_edit($id);
		echo json_encode($result);
	}

	public function getKodeAutoComplete()
	{
		$valueParams = $this->input->get('params');
		$result = $this->M_Pemusnahan->getKodeAutoComplete($valueParams);
		echo json_encode($result);
	}
}
