<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class PersetujuanHasilStockOpname extends ParentController
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
		$this->MenuKode = "123007000";
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_PersetujuanHasilStockOpname', 'M_PersetujuanHasilStockOpname');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation', 'pdfgenerator']);
	}

	public function PersetujuanHasilStockOpname()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		// $data['kodeDokumen'] = $this->M_PersetujuanHasilStockOpname->getKodeDokumenOpname();
		$data['depoDetail'] = $this->M_PersetujuanHasilStockOpname->getDepoDetail();

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
		$this->load->view('WMS/PersetujuanHasilStockOpname/Page/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PersetujuanHasilStockOpname/Component/Script/S_PersetujuanHasilStockOpname', $data);
	}

	public function getDataDokumenOpnameKode()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_PersetujuanHasilStockOpname->getDataDokumenOpnameKode($dataPost));
	}

	public function getDataOpnameByKode()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_PersetujuanHasilStockOpname->getDataOpnameByKode($dataPost));
	}

	public function saveDataApprovalOpname()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_PersetujuanHasilStockOpname->saveDataApprovalOpname($dataPost));
	}

	public function saveDataApprovalOpnameByCompare()
	{
		$opnameId = $this->input->post('opnameId');
		$mappingData = $this->input->post('mappingData');
		echo json_encode($this->M_PersetujuanHasilStockOpname->saveDataApprovalOpnameByCompare($opnameId, $mappingData));
	}

	public function getDataDetailOpnameBySKU()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_PersetujuanHasilStockOpname->getDataDetailOpnameBySKU($dataPost));
	}

	public function konfirmasiDataApprovalOpname()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		$dataOpnamePallet = $this->M_PersetujuanHasilStockOpname->getDataOpnamePallet($dataPost->opnameId);
		$palletId = [];
		$palletDetailId = [];

		foreach ($dataOpnamePallet as $key => $value) {
			$palletId[] = $value->pallet_id;
		}

		$getPalletDetail = $this->db->select("pallet_detail_id")->from('pallet_detail')->where_in('pallet_id', $palletId)->get()->result();
		if (!empty($getPalletDetail)) {
			foreach ($getPalletDetail as $key => $value) {
				array_push($palletDetailId, $value->pallet_detail_id);
				# code...
			}
		}
		// echo json_encode($palletDetailId);

		$paramsDetail = array();
		// $idx = explode(",", $sj_id);
		if (!empty($palletDetailId)) {
			for ($i = 0; $i < count($palletDetailId); $i++) {
				$paramsDetail[$i] = "'" . $palletDetailId[$i] . "'";
			}
		}

		$isNotSameLastUpd = false;
		$lastUpdateBaru = $this->M_PersetujuanHasilStockOpname->Get_LastUpdateOpname($dataPost->opnameId);

		if ($dataPost->last_update == $lastUpdateBaru['tglUpd']) $isNotSameLastUpd = true;

		if ($isNotSameLastUpd) {
			echo json_encode($this->M_PersetujuanHasilStockOpname->konfirmasiDataApprovalOpname($dataPost, implode(',', $paramsDetail)));
		} else {
			echo json_encode(203);
		}
	}

	public function cetakDataApproveOpname()
	{

		$dataPost = (object) [
			'value' => $this->input->get('id'),
			'type' => null
		];

		$response = $this->M_PersetujuanHasilStockOpname->getDataOpnameByKode($dataPost);

		$this->load->view('WMS/PersetujuanHasilStockOpname/Component/Cetak/index', $response);

		// $file_pdf = 'Hasil Stok Opname';

		// // setting paper
		// $paper = "A4";

		// //orientasi paper potrait / landscape
		// $orientation = "landscape";

		// $html = $this->load->view('WMS/PersetujuanHasilStockOpname/Component/Cetak/index', $response, true);

		// $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}

	public function cetakDataOpnameBySKU()
	{
		$dataPost = (object) [
			'opnameId' => $this->input->get('id'),
			'type' => $this->input->get('type')
		];

		$response = $this->M_PersetujuanHasilStockOpname->GetDataDetailOpnameBySKU($dataPost);

		$this->load->view('WMS/PersetujuanHasilStockOpname/Component/Cetak/cetakDataOpnameListSKU', $response);
	}

	public function exportDataOpnameBySKU()
	{

		$dataPost = (object) [
			'opnameId' => $this->input->get('id'),
			'type' => $this->input->get('type')
		];

		$response = $this->M_PersetujuanHasilStockOpname->GetDataDetailOpnameBySKU($dataPost);

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$numrow = 1;

		$sheet->setCellValue('A' . $numrow, 'TANGGAL');
		$sheet->setCellValue('B' . $numrow, 'NO DOKUMEN');
		$sheet->setCellValue('C' . $numrow, 'PT');
		$sheet->setCellValue('D' . $numrow, 'PRINCIPLE');
		$sheet->setCellValue('E' . $numrow, 'JENIS STOK');
		$sheet->setCellValue('F' . $numrow, 'TIPE STOK');
		$sheet->setCellValue('G' . $numrow, 'ID SKU');
		$sheet->setCellValue('H' . $numrow, 'SKU');
		$sheet->setCellValue('I' . $numrow, 'ED');
		$sheet->setCellValue('J' . $numrow, 'KODE LOKASI');
		$sheet->setCellValue('K' . $numrow, 'AREA');
		$sheet->setCellValue('L' . $numrow, 'KODE PALLET');
		$sheet->setCellValue('M' . $numrow, 'QTY SISTEM');
		$sheet->setCellValue('N' . $numrow, 'QTY AKTUAL');
		$sheet->setCellValue('O' . $numrow, 'DEVIASI');

		$numrow = ++$numrow;

		foreach ($response['detail'] as $key => $value) {
			foreach ($value['data'] as $keys => $v) {
				// thead table
				$sheet->setCellValue('A' . $numrow, $response['header']->tanggal);
				$sheet->setCellValue('B' . $numrow, $response['header']->kode);
				$sheet->setCellValue('C' . $numrow, $response['header']->perusahaan);
				$sheet->setCellValue('D' . $numrow, $response['header']->principle);
				$sheet->setCellValue('E' . $numrow, $response['header']->jenis_stock);
				$sheet->setCellValue('F' . $numrow, $response['header']->tipe_stock_opname);
				$sheet->setCellValue('G' . $numrow, $key);
				$sheet->setCellValue('H' . $numrow, $value['sku_nama_produk']);
				$sheet->setCellValue('I' . $numrow, $value['sku_expired_date']);
				$sheet->setCellValue('J' . $numrow, $value['rak_lajur_detail_nama']);
				$sheet->setCellValue('K' . $numrow, $value['area']);
				$sheet->setCellValue('L' . $numrow, $v['pallet_kode']);
				$sheet->setCellValue('M' . $numrow, $v['sku_qty_sistem']);
				$sheet->setCellValue('N' . $numrow, $v['sku_actual_qty_opname']);
				$sheet->setCellValue('O' . $numrow, $v['deviasi']);

				++$numrow;
			}
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Export Data Opname By SKU.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	/** For Compare */

	public function getKodeDokumenOpnameCompare()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_PersetujuanHasilStockOpname->getKodeDokumenOpnameCompare($dataPost));
	}

	public function getCompareOpname()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_PersetujuanHasilStockOpname->getCompareOpname($dataPost));
	}

	/** End For Compare */
}
