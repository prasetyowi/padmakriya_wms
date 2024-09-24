<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';


// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ProsesOpname extends ParentController
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
		$this->MenuKode = "123004000";
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_ProsesOpname', 'M_ProsesOpname');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation']);
	}

	public function ProsesOpnameMenu()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['userLogin'] = $this->M_ProsesOpname->getDataUserLogin();
		$data['dataOpname'] = $this->M_ProsesOpname->getDataOpnameByStatus();

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
		$this->load->view('WMS/ProsesOpname/Page/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/ProsesOpname/Component/Script/S_ProsesOpname', $data);
	}

	/** --------------------------------------- Untuk Proses Opname By Surat Kerja -------------------------------- */

	public function ProsesDataOpname()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}


		$arr = $this->M_ProsesOpname->getDataDetailOpnameBySuratKerjaDetail($_GET['prosesId']);
		// $tmpgroup = [];
		// $group = [];

		// foreach ($arr as $key => $data) {
		//   $tmpdata = $data;
		//   unset($tmpdata['rak_lajur_nama']);
		//   $tmpgroup[$data['rak_lajur_nama']]['rak_lajur_nama'] = $data['rak_lajur_nama'];
		//   // $tmpgroup[$data['picking_order_aktual_kode']]['instruksi'] = $data['instruksi'];
		//   $tmpgroup[$data['rak_lajur_nama']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		// }

		// foreach ($tmpgroup as $key => $data) {
		//   $tmpdata = $data;
		//   unset($tmpdata['rak_lajur_nama']);
		//   $group[$data['rak_lajur_nama']] = $tmpdata;
		// }

		$data['header'] = $this->M_ProsesOpname->getDataDetailOpnameBySuratKerjaHeader($_GET['prosesId']);

		$data['detail'] = $arr;
		// $data['principle'] = $this->db->select("principle_id, principle_nama")->from("principle")->get()->result();

		// echo json_encode($arr);
		// die;

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
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js',

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		// $this->load->view('WMS/ProsesOpname/Page/proses', $data);
		$this->load->view('WMS/ProsesOpname/Page/prosesByRak', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/ProsesOpname/Component/Script/S_ProsesOpname', $data);
	}

	public function konfirmasiDataProsesOpname()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ProsesOpname->konfirmasiDataProsesOpname($dataPost));
	}

	/** --------------------------------------- End Untuk Proses Opname By Surat Kerja -------------------------------- */

	/** Proses Baru */

	/** --------------------------------------- Untuk Proses Opname By Rak -------------------------------- */

	public function getPrinciple()
	{
		$tr_opname_plan_id = $this->input->get("tr_opname_plan_id");

		$principle_id = $this->db->select("principle_id")->from("tr_opname_plan")->where("tr_opname_plan_id", $tr_opname_plan_id)->get()->row()->principle_id;

		$data = $this->db->select("principle_id, principle_nama")->from("principle")->get()->result();

		$response = [
			'data' => $data,
			'principle_id' => $principle_id
		];

		echo json_encode($response);
	}

	public function ProsesDataOpnameByRak()
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . '/node_modules/html5-qrcode/html5-qrcode.min.js',

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/ProsesOpname/Page/prosesByRak', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/ProsesOpname/Component/Script/S_ProsesOpname', $data);
	}

	public function checkKodeScan()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		//check status header opname
		$status = $this->db->select("tr_opname_plan_status as status")->from('tr_opname_plan')->where('tr_opname_plan_id', $dataPost->opnameId)->get()->row()->status;

		if ($dataPost->typeScan === "lokasi") {
			$kodeLokasi = preg_replace('/\s+/', '', $dataPost->kode);

			//jika sttatus header opname In Progress Revision, maka ambil data dari table asli
			$this->checkLokasi($dataPost, $kodeLokasi, $status);
			// if ($status == "In Progress Revision") {
			//   $this->checkLokasi($dataPost, $kodeLokasi, $status);
			// } else {
			//   $this->checkLokasi($dataPost, $kodeLokasi, $status);
			// }
		}

		if ($dataPost->typeScan === "pallet" || $dataPost->typeScan === "newPallet") {
			// get prefik depo
			$depo_id = $this->session->userdata('depo_id');
			$depoPrefix = $this->M_ProsesOpname->getDepoPrefix($depo_id);
			$unit = $depoPrefix->depo_kode_preffix;

			$kodePallet = preg_replace('/\s+/', '', $unit  . "/" . $dataPost->kode);

			if ($status == "In Progress Revision") {
				$result = $this->M_ProsesOpname->checkKodePallet($dataPost, $kodePallet, 'revisi', $status);
			} else {
				$result = $this->M_ProsesOpname->checkKodePallet($dataPost, $kodePallet, 'non revisi', $status);
			}
			echo json_encode($result);
		}

		if ($dataPost->typeScan === "barcode") {
			$barcode = preg_replace('/\s+/', '', $dataPost->kode);
			$updateSku = $this->db->update('sku', [
				'sku_kode_sku_principle' => $barcode
			], ['sku_id' => $dataPost->skuId]);

			if ($updateSku) {
				$response =  [
					'type' => 200,
					'message' => "Berhasil scan barcode",
					'kode' => $barcode
				];
			} else {
				$response =  [
					'type' => 201,
					'message' => "Gagal scan barcode",
					'kode' => $barcode
				];
			}

			echo json_encode($response);
		}

		if ($dataPost->typeScan === "addScanSku") {
			$skuID = preg_replace('/\s+/', '', $dataPost->kode);

			$result = $this->M_ProsesOpname->getDataSkuByScan($skuID);

			echo json_encode($result);
		}
	}

	// public function updatePalletProsesOpnameByRak()
	// {
	// 	header('Content-Type: application/json');

	// 	$dataPost = json_decode(file_get_contents("php://input"));
	// 	echo json_encode($this->M_ProsesOpname->updatePalletProsesOpnameByRak($dataPost));
	// }

	private function checkLokasi($dataPost, $kodeLokasi, $status)
	{
		$result = $this->M_ProsesOpname->checkKodeLokasi($dataPost, $kodeLokasi);
		if (empty($result)) {
			$insert = $this->M_ProsesOpname->insertOpnamePlanDetail($dataPost, $kodeLokasi, $status);

			$result = $this->M_ProsesOpname->checkKodeLokasi($dataPost, $kodeLokasi);

			echo json_encode($this->M_ProsesOpname->checkDataPalletIntemp($dataPost, $result, $kodeLokasi, $status));

			// echo json_encode([
			// 	'type' => 201,
			// 	'message' => "Lokasi <strong>" . $kodeLokasi . "</strong> tidak ditemukan",
			// 	'kode' => $kodeLokasi,
			// 	'statusOpname' => $status,
			// 	'data' => []
			// ]);
		} else {
			echo json_encode($this->M_ProsesOpname->checkDataPalletIntemp($dataPost, $result, $kodeLokasi, $status));
		}
	}

	public function deleteOpnameDetail2Temp()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ProsesOpname->deleteOpnameDetail2Temp($dataPost));
	}

	public function deleteOpnameDetail3Temp()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ProsesOpname->deleteOpnameDetail3Temp($dataPost));
	}

	public function deleteOpnameDetailRow()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ProsesOpname->deleteOpnameDetailRow($dataPost));;
	}

	public function getDataPalletDetailById()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ProsesOpname->getDataPalletDetailById($dataPost));
	}

	public function getDataSku()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ProsesOpname->getDataSku($dataPost));
	}

	public function getDataSkuById()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ProsesOpname->getDataSkuById($dataPost));
	}

	public function checkMinimunExpiredDate()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ProsesOpname->checkMinimunExpiredDate($dataPost));
	}

	public function saveDataProsesOpnameByRak()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_ProsesOpname->saveDataProsesOpnameByRak($dataPost));
	}

	public function checkStatusHeaderOpname()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->db->select("tr_opname_plan_status as status")->from('tr_opname_plan')->where('tr_opname_plan_id', $dataPost->opnameId)->get()->row()->status);
	}

	public function konfirmasiDataProsesOpnameByRak()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ProsesOpname->konfirmasiDataProsesOpnameByRak($dataPost));
	}

	/** --------------------------------------- End Untuk Proses Opname By Rak -------------------------------- */


	/** Proses Baru */

	/** --------------------------------------- Untuk detail list data Card paling bawah -------------------------------- */

	public function DetailData()
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


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/ProsesOpname/Page/detail', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/ProsesOpname/Component/Script/S_ProsesOpname', $data);
	}

	public function getDaftarSuratKerjaDetail()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_ProsesOpname->getDaftarSuratKerjaDetail($dataPost->typeOpname));
	}

	public function updateLaksanakanOpname()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_ProsesOpname->updateLaksanakanOpname($dataPost->id));
	}

	public function getKodeAutoComplete()
	{
		$valueParams = $this->input->get('params');
		$typeScan = $this->input->get('type');
		$prosesId = $this->input->get('prosesId');
		$principleId = $this->input->get('principleId');
		$result = $this->M_ProsesOpname->getKodeAutoComplete($valueParams, $typeScan, $prosesId, $principleId);
		echo json_encode($result);
	}

	/** --------------------------------------- End Untuk detail list data Card paling bawah -------------------------------- */
	// Tambah baru cetak
	public function CetakData()
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$tr_opname_plan_id = $this->input->get('id');

		$kodeopname = $this->M_ProsesOpname->GetKodeOpname($tr_opname_plan_id);
		$detail_opname = $this->M_ProsesOpname->GetDetailOpnameCetak($tr_opname_plan_id);

		$sheet->setCellValue('A1', 'NO');
		$sheet->setCellValue('B1', 'Nama Depo');
		$sheet->setCellValue('C1', 'Area');
		$sheet->setCellValue('D1', 'Detail Rak');
		$sheet->setCellValue('E1', 'Nomer Pallet');
		$sheet->setCellValue('F1', 'Kode Barang');
		$sheet->setCellValue('G1', 'Nama Barang');
		$sheet->setCellValue('H1', 'ED');
		$sheet->setCellValue('I1', 'Batch Number');
		$sheet->setCellValue('J1', 'Qty');

		$fileName = '';
		$numrow = 2;
		foreach ($detail_opname as $key => $value) {
			$fileName = "" . $kodeopname->tr_opname_plan_kode . ".xlsx";


			$sheet->setCellValue('A' . $numrow, $key + 1);
			$sheet->setCellValue('B' . $numrow, $value['depo_nama']);
			$sheet->setCellValue('C' . $numrow, $value['tipe_stok']);
			$sheet->setCellValue('D' . $numrow, $value['nama_detail_rak']);
			$sheet->setCellValue('E' . $numrow, $value['pallet_kode']);
			$sheet->setCellValue('F' . $numrow, $value['sku_kode']);
			$sheet->setCellValue('G' . $numrow, $value['sku_nama_produk']);
			$sheet->setCellValue('H' . $numrow, $value['sku_expired_date']);
			$sheet->setCellValue('I' . $numrow, $value['sku_batch_no']);
			$sheet->setCellValue('J' . $numrow, $value['sku_actual_qty_opname']);

			$numrow++; // Tambah 1 setiap kali looping
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename=' . $fileName . ''); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}
}
