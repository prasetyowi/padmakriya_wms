<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class AssignmentPengembalianBarang extends ParentController
{

	private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		// if ($this->session->has_userdata('pengguna_id') == 0) :
		//   redirect(base_url('MainPage'));
		// endif;

		$this->depo_id = $this->session->userdata('depo_id');
		$this->MenuKode = "120008000";
		$this->load->model(['M_Menu', ['WMS/M_AssignmentPengembalianBarang', 'M_AssignmentPengembalianBarang'], 'M_Function', 'M_MenuAccess', 'M_Vrbl', 'M_AutoGen']);

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation']);
	}

	public function AssignmentPengembalianBarangMenu()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url(''));
			exit();
		}


		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['dataKoreksiPallet'] = $this->M_AssignmentPengembalianBarang->getDataKoreksiPallet();

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
		$this->load->view('WMS/AssignmentPengembalianBarang/AssignmentPengembalianBarang', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/AssignmentPengembalianBarang/S_AssignmentPengembalianBarang', $data);
	}


	public function getDataKoreksiPalletByFilter()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_AssignmentPengembalianBarang->getDataKoreksiPalletByFilter($dataPost));
	}


	public function add()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['depo'] = $this->M_AssignmentPengembalianBarang->get_DepoNameBySession();
		$data['typeTransactions'] = $this->M_AssignmentPengembalianBarang->getTypeTransactions();
		$data['principles'] = $this->M_AssignmentPengembalianBarang->getPrinciples();

		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));

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
		$this->load->view('WMS/AssignmentPengembalianBarang/Page/Tambah/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/AssignmentPengembalianBarang/Page/Tambah/S_tambah', $data);
	}

	public function checkKodePallet()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_AssignmentPengembalianBarang->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		// $kode = $unit . "/" . $kode_pallet;
		$kode = preg_replace('/\s+/', '', $unit  . "/" . $dataPost->kode_pallet);

		$result = $this->M_AssignmentPengembalianBarang->checkKodePallet($kode);
		if (empty($result)) {
			echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan", 'kode' => $kode, 'data' => []));
		} else {
			echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> ditemukan", 'kode' => $kode, 'data' => $result));
		}
	}

	public function getAllSKU()
	{
		// $skuIdInTable =  $this->input->post('skuIdInTable');
		// $principle =  $this->input->post('principle');
		// $principleBrand =  $this->input->post('principleBrand');
		echo json_encode($this->M_AssignmentPengembalianBarang->getAllSKU());
	}

	public function getAllSKUById()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		$result = $this->M_AssignmentPengembalianBarang->getAllSKUById($dataPost->pickingValidationDetail4Id);
		echo json_encode($result);
	}

	public function requestGetPrincipleBrand()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		$result = $this->M_AssignmentPengembalianBarang->requestGetPrincipleBrand($dataPost->principleId);
		echo json_encode($result);
	}

	public function checkMinimunExpiredDate()
	{
		$result = [];
		$id = $this->input->post('id');

		$getMinimumED = $this->db->select("sku_id, sku_minimum_expired_date")->from("sku")->where_in('sku_id', $id)->get()->result();

		foreach ($getMinimumED as $key => $value) {
			$tgl = date('Y-m', strtotime('+' . $value->sku_minimum_expired_date . ' month'));
			array_push($result, ['sku_id' => $value->sku_id, 'date' => $tgl . '-01']);
		}

		echo json_encode($result);
	}

	public function saveData()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));

		$this->db->trans_begin();

		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_KSP';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_AssignmentPengembalianBarang->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		//insert to tr koreksi stok pallet

		$tr_pallet_id = $this->M_Vrbl->Get_NewID();
		$tr_pallet_id = $tr_pallet_id[0]['NEW_ID'];
		$date = date('Y-m-d H:i:s');



		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "pallet",
			'whereField' => "pallet_id",
			'whereValue' => $dataPost->pallet_id,
			'fieldDateUpdate' => "pallet_tgl_update",
			'fieldWhoUpdate' => "pallet_who_update",
			'lastUpdated' => $dataPost->lastupdate,
		]);


		$dataHeader = [
			'tr_koreksi_stok_pallet_id' => $tr_pallet_id,
			'principle_id' => $dataPost->principle_id,
			'tr_koreksi_stok_pallet_kode' => $generate_kode,
			'tr_koreksi_stok_pallet_tanggal' => $date,
			'tipe_mutasi_id' => $dataPost->typeTransaction,
			'tr_koreksi_stok_pallet_keterangan' => $dataPost->keterangan == "" ? NULL : $dataPost->keterangan,
			'tr_koreksi_stok_pallet_status' => "Open",
			'depo_id_asal' => $this->session->userdata('depo_id'),
			'depo_detail_id_asal' => $dataPost->depo_detail_id,
			'depo_id_tujuan' => $this->session->userdata('depo_id'),
			'depo_detail_id_tujuan' => $dataPost->depo_detail_id,
			'tr_koreksi_stok_pallet_tgl_create' => $date,
			'tr_koreksi_stok_pallet_who_create' => $this->session->userdata('pengguna_username'),
			'client_wms_id' => $dataPost->client_wms_id,
			'pallet_id' => $dataPost->pallet_id,
			'is_assigment_pengeluaran_barang' => 1
		];
		$this->db->insert('tr_koreksi_stok_pallet', $dataHeader);


		foreach ($dataPost->finalDetailData as $key => $data) {

			$skuId = $data->skuId !== "null" ? $data->skuId : NULL;
			$skuStockId = $data->skuStockId !== "null" ? $data->skuStockId : NULL;

			$tr_pallet_detail_id = $this->M_Vrbl->Get_NewID();
			$tr_pallet_detail_id = $tr_pallet_detail_id[0]['NEW_ID'];

			$qtyReduced =  $data->qtyPlan - $data->qtyAvailable;

			$this->M_AssignmentPengembalianBarang->updateQtySisaInPickingValidationDetail4($data->pickingValidationDetail4Id, $qtyReduced);

			$chkDataInPalletDetail = $this->M_AssignmentPengembalianBarang->chkDataInPalletDetail($data, $dataPost->depo_detail_id);

			if ($chkDataInPalletDetail != null) {
				$this->M_AssignmentPengembalianBarang->updatePalletAndSkuStock($chkDataInPalletDetail, $data->qtyPlan);
				$this->M_AssignmentPengembalianBarang->insertToTrKoreksiPalletDetail($tr_pallet_detail_id, $tr_pallet_id, $data, $chkDataInPalletDetail->sku_id, $chkDataInPalletDetail->sku_stock_id);
			} else {
				//item pallet tambah dari sku

				$sku_stock_id = $this->M_Vrbl->Get_NewID();
				$sku_stock_id = $sku_stock_id[0]['NEW_ID'];

				$pallet_detail_id = $this->M_Vrbl->Get_NewID();
				$pallet_detail_id = $pallet_detail_id[0]['NEW_ID'];

				//insert ke pallet detail dan isi qtynya ke kolom stok in
				$this->M_AssignmentPengembalianBarang->insertToPalletDetail($pallet_detail_id, $dataPost->pallet_id, $data, $data->qtyPlan);

				$this->M_AssignmentPengembalianBarang->insertToTrKoreksiPalletDetail($tr_pallet_detail_id, $tr_pallet_id, $data, $skuId, $skuStockId);

				//insert ke sku stock dan check brdasarkan sku_id, depo_id, depo_detail_id dan expired_datenya
				$checkDataInSkuStockByparams = $this->M_AssignmentPengembalianBarang->checkDataInSkuStockByparams($data, $dataPost->depo_detail_id);

				//jika kosong maka insert ke sku_stock dan update kolom sku_stock_id di table pallet_detail
				if ($checkDataInSkuStockByparams == null) {
					$res = $this->M_AssignmentPengembalianBarang->insert_data_to_sku_stock($sku_stock_id, $data, $dataPost->depo_detail_id, $dataPost->client_wms_id, $data->qtyPlan);
					if ($res) {
						//update sku_stok_id di table pallet_detail
						$this->M_AssignmentPengembalianBarang->update_sku_stock_id_in_pallet_detail($pallet_detail_id, $sku_stock_id, $tr_pallet_detail_id);
					}
				} else {
					//jika ada maka update ke sku_stok dan update kolom sku_stock_id di table pallet_detail
					$res2 = $this->M_AssignmentPengembalianBarang->update_data_to_sku_stock($checkDataInSkuStockByparams, $dataPost->client_wms_id, $data->qtyPlan);
					if ($res2) {
						//update sku_stok_id di table pallet_detail
						$this->M_AssignmentPengembalianBarang->update_sku_stock_id_in_pallet_detail($pallet_detail_id, $checkDataInSkuStockByparams->sku_stock_id, $tr_pallet_detail_id);
					}
				}
			}
		}

		$this->db->query("exec proses_posting_stock_card 'KSP', '$tr_pallet_id', '" . $this->session->userdata('pengguna_username') . "'");


		// if ($this->db->trans_status() === false) {
		// 	$this->db->trans_rollback();
		// 	echo json_encode(false);
		// } else {
		// 	$this->db->trans_commit();
		// 	echo json_encode(true);
		// }
		if ($lastUpdatedChecked['status'] === 400) {
			$this->db->trans_rollback();
			$response = [
				'status' => 400,
				'message' => 'Data Gagal Disimpan',
				'lastUpdatedNew' => null
			];
		} else if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'message' => 'Data Gagal Disimpan',
				'lastUpdatedNew' => null
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'message' => 'Data Berhasil Disimpan',
				'lastUpdatedNew' => $lastUpdatedChecked['lastUpdatedNew']
			];
		}
		echo json_encode($response);
	}

	public function view($id)
	{
		$data = array();
		$data['id'] = $id;

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['header'] = $this->M_AssignmentPengembalianBarang->getDataHeaderKoreksiPallet($id);
		$data['detail'] = $this->M_AssignmentPengembalianBarang->getDataDetailKoreksiPallet($id);

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

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/AssignmentPengembalianBarang/Page/View/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/AssignmentPengembalianBarang/Page/View/script', $data);
	}

	public function getKodeAutoComplete()
	{
		$valueParams = $this->input->get('params');
		$result = $this->M_AssignmentPengembalianBarang->getKodeAutoComplete($valueParams);
		echo json_encode($result);
	}
}
