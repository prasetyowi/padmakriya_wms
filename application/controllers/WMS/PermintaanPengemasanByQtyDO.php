<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class PermintaanPengemasanByQtyDO extends ParentController
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

		$this->depo_id = $this->session->userdata('depo_id');
		$this->MenuKode = "128001300";
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_PermintaanPengemasanByQtyDO', 'M_PermintaanPengemasanByQtyDO');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_Depo');
		$this->load->model('M_AutoGen');
		$this->load->model('M_ClientWms');
		$this->load->model('M_DepoDetail');
		$this->load->model('M_Principle');
	}
	public function PermintaanPengemasanByQtyDOMenu()
	{
		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage/Index'));
			exit();
		}

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['Perusahaan'] = $this->M_ClientWms->findAll_array();
		$data['TipeDeliveryOrder'] = $this->M_PermintaanPengemasanByQtyDO->GetTipeDeliveryOrder();
		$data['Gudang'] = $this->M_DepoDetail->findByDepo_array();
		$data['Principle'] = $this->M_Principle->Get_Principle();

		$tr_konversi_sku_id = $this->M_Vrbl->Get_NewID();
		$data['tr_konversi_sku_id'] = $tr_konversi_sku_id[0]['NEW_ID'];

		$tr_konversi_sku_temp_id = $this->M_Vrbl->Get_NewID();
		$tr_konversi_sku_temp_id = $tr_konversi_sku_temp_id[0]['NEW_ID'];

		$data['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css'
		);

		$data['js_files'] 	= array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
		);

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PermintaanPengemasanByQtyDO/index', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PermintaanPengemasanByQtyDO/script', $data);
	}

	public function getDataSearch()
	{
		$tipe_do 	= $this->input->post('filter_tipedo');
		$tgl 		= $this->input->post('filter_tanggal');

		$data['result'] = $this->M_PermintaanPengemasanByQtyDO->getDataSearch($tgl, $tipe_do);
		$data['pengemasan'] = $this->M_PermintaanPengemasanByQtyDO->GetListPengemasan($tgl, $tipe_do, "", "");

		echo json_encode($data);
	}

	public function showDetailDOBySKU()
	{
		$sku_id 	= $this->input->post('sku_id');
		$tipe_do 	= $this->input->post('filter_tipedo');
		$tgl 		= $this->input->post('filter_tanggal');
		$data 		= $this->M_PermintaanPengemasanByQtyDO->showDetailDOBySKU($sku_id, $tgl, $tipe_do);

		echo json_encode($data);
	}

	public function GetListHitungKonversiPengemasan()
	{
		$filter_sku_id = array();
		$tipe_do = $this->input->post('filter_tipedo');
		$tgl = $this->input->post('filter_tanggal');
		$list_sku_id = $this->input->post('list_sku_id');

		if (count($list_sku_id) > 0) {
			foreach ($list_sku_id as $key => $value) {
				array_push($filter_sku_id, "'" . $value . "'");
			}
		}

		$data = $this->M_PermintaanPengemasanByQtyDO->GetListHitungKonversiPengemasan($tgl, $tipe_do, $filter_sku_id);

		echo json_encode($data);
	}

	public function HitungQtyByKemasan()
	{
		$arr_detail = $this->input->post("arr_detail");
		$arrKonversi = [];
		$resultKonversi = '';
		foreach ($arr_detail as $key => $value) {
			$id_temp = $this->M_Vrbl->Get_NewID();
			$id_temp = $id_temp[0]['NEW_ID'];
			// $sku_expired_date = $value['sku_expdate'];
			$sku_id = $value['sku_id'];
			$sku_konversi_group = $value['sku_konversi_group'];
			$sku_konversi_level = $value['sku_konversi_level'];
			$depo_detail_id = $value['depo_detail_id'];
			$sku_qty = (int)$value['sku_qty_draft'] - (int)$value['sku_stock'];
			$sku_qty_composite = ((int)$value['sku_qty_draft'] - (int)$value['sku_stock']) * (int)$value['sku_konversi_faktor'];
			$cekKonversiLevel = $this->M_PermintaanPengemasanByQtyDO->cekKonversiLevel($sku_konversi_group, $depo_detail_id, $sku_konversi_level);
			// echo json_encode($cekKonversiLevel);
			// die;
			if (count($cekKonversiLevel) > 0) {
				// $this->M_PermintaanPengemasanByQtyDO->insertSKUKonversiTemp($id_temp, $sku_id, $sku_expired_date, $sku_qty, $sku_qty_composite);
				$resultKonversi = $this->M_PermintaanPengemasanByQtyDO->HitungQtyByKemasan($sku_id, (int)$value['sku_qty_draft']);
				// echo var_dump($resultKonversi);
				// die;

				foreach ($cekKonversiLevel as $key => $val) {
					if (count($resultKonversi) > 0) {
						foreach ($resultKonversi as $key => $item) {
							if ($val['stok'] <= $item['hasil']) {
								array_push($arrKonversi, [
									'index' => (int)$value['idx'],
									'hasil' => $item['hasil'],
									'idx' => 0,
									'sku_id' =>  $item['sku_id'],
									'sku_id_draft' => $value['sku_id'],
									'sku_konversi_level' => $val['sku_konversi_level'],
									'sku_satuan' => $value['sku_satuan'],
									'qty' => (int)$value['sku_qty_draft'],
									'sku_satuan2' => $val['sku_satuan'],
									'sku_expired_date' => $val['sku_stock_expired_date'],
									'qty2' => $val['stok'],
									'sku_stock_id' => $val['sku_stock_id'],
									'keterangan' => 'Tidak Cukup Untuk Dibongkar',
									'depo_detail_id' => $val['depo_detail_id'],
									'depo_detail_nama' => $val['depo_detail_nama']
								]);
							} else if ($val['stok'] >= $item['hasil']) {
								array_push($arrKonversi, [
									'index' => (int)$value['idx'],
									'hasil' => $item['hasil'],
									'idx' => $item['idx'],
									'sku_id' => $item['sku_id'],
									'sku_id_draft' => $value['sku_id'],
									'sku_konversi_level' => $item['sku_konversi_level'],
									'sku_satuan' => $value['sku_satuan'],
									'qty' => (int)$value['sku_qty_draft'],
									'sku_satuan2' => $val['sku_satuan'],
									'sku_expired_date' => $val['sku_stock_expired_date'],
									'qty2' => $val['stok'],
									'sku_stock_id' => $val['sku_stock_id'],
									'keterangan' => 'Cukup Untuk Dibongkar',
									'depo_detail_id' => $val['depo_detail_id'],
									'depo_detail_nama' => $val['depo_detail_nama']
								]);
							} else {
								array_push($arrKonversi, [
									'index' => (int)$value['idx'],
									'hasil' => 0,
									'idx' => $item['idx'],
									'sku_id' => $item['sku_id'],
									'sku_id_draft' => $value['sku_id'],
									'sku_konversi_level' => $item['sku_konversi_level'],
									'sku_satuan' => $value['sku_satuan'],
									'qty' => (int)$value['sku_qty_draft'],
									'sku_satuan2' => $val['sku_satuan'],
									'sku_expired_date' => $val['sku_stock_expired_date'],
									'qty2' => $val['stok'],
									'sku_stock_id' => $val['sku_stock_id'],
									'keterangan' => 'ERROR, data yang dihitung tidak memadai',
									'depo_detail_id' => $val['depo_detail_id'],
									'depo_detail_nama' => $val['depo_detail_nama']
								]);
							}
						}
					}
				}
			} else if (count($cekKonversiLevel) == 0) {
				array_push($arrKonversi, [
					'index' => (int)$value['idx'],
					'hasil' => 0,
					'idx' => 0,
					'sku_id' => $sku_id,
					'sku_id_draft' =>  $sku_id,
					'sku_konversi_level' => '',
					'sku_satuan' => '',
					'qty' =>  (int)$value['sku_qty_draft'],
					'sku_expired_date' => 0,
					'qty2' => 0,
					'sku_satuan2' => '',
					'sku_stock_id' => NULL,
					'keterangan' => 'Tidak Dapat Dibongkar',
					'depo_detail_id' => '',
					'depo_detail_nama' => ''
				]);
			}
		}

		$arrayHasil = [];

		if (count($arrKonversi) != 0) {
			// foreach ($arrKonversi as $innerArray) {
			// 	foreach ($innerArray as $item) {
			// 		$flattenedArray[] = $item;
			// 		$flattenedArray[]['qty'] = $item[''];
			// 	}
			// }
			foreach ($arr_detail as $key => $value1) {
				foreach ($arrKonversi as $key2 => $value2) {
					if ($value1['sku_id'] == $value2['sku_id_draft'] && (int)$value1['sku_qty_draft'] == (int)$value2['qty']) {
						array_push($arrayHasil, [
							'index' => (int)$value2['index'],
							'sku_id' => $value1['sku_id'],
							'sku_nama_produk' => $value1['sku_nama_produk'],
							'client_wms_id' => $value1['client_wms_id'],
							'depo_detail_id' => $value2['depo_detail_id'],
							'depo_detail_nama' => $value2['depo_detail_nama'],
							'principle' => $value1['principle'],
							'sku_kode' => $value1['sku_kode'],
							'brand' => $value1['brand'],
							'sku_kemasan' => $value1['sku_kemasan'],
							'sku_satuan' => $value1['sku_satuan'],
							'sku_qty_draft' => $value1['sku_qty_draft'],
							'hasil' => $value2['hasil'],
							'sku_satuan2' => $value2['sku_satuan2'],
							'sku_stock_id' => $value2['sku_stock_id'],
							'qty2' => $value2['qty2'],
							'keterangan' => $value2['keterangan'],
							'sku_expired_date' => $value2['sku_expired_date'],
						]);
					}
				}
			}

			$mergedData = [];

			// Merge data for the same client wms id
			foreach ($arrayHasil as $key => $item) {
				$principle = $item['principle'];
				$client_wms_id = $item['client_wms_id'];
				$index = $item['index'];
				if (!isset($mergedData[$index])) {
					$mergedData[$index] = [
						"client_wms_id" => $item['client_wms_id'],
						"principle" => $item['principle'],
						"depo_detail_id" => $item['depo_detail_id'],
						"data" => []
					];
				}
				$mergedData[$index]['data'][] = [
					'index' => $item['index'],
					"sku_id" => $item['sku_id'],
					"sku_kode" => $item['sku_kode'],
					"brand" => $item['brand'],
					"sku_kemasan" => $item['sku_kemasan'],
					'sku_nama_produk' => $item['sku_nama_produk'],
					'client_wms_id' => $item['client_wms_id'],
					'depo_detail_id' => $item['depo_detail_id'],
					'depo_detail_nama' => $item['depo_detail_nama'],
					"sku_satuan" => $item['sku_satuan'],
					"sku_satuan2" => $item['sku_satuan2'],
					"sku_expdate" => '',
					"sku_stock_id" => $item['sku_stock_id'],
					'keterangan' => $item['keterangan'],
					'stok' => $item['qty2'],
					"principle" => $item['principle'],
					'sku_qty_draft' => $item['sku_qty_draft'],
					'sku_expired_date' => $item['sku_expired_date'],
					"hasil" => $item['hasil']
				];
			}

			// Convert merged data associative array to indexed array
			$finalMergedData = array_values($mergedData);
			// $this->requestPembongkaran($finalMergedData);

			echo json_encode($finalMergedData);
		} else {
			echo json_encode(0);
		}
	}

	public function RequestPengemasan()
	{
		$arr_persiapan_pengemasan = $this->input->post("arr_persiapan_pengemasan");

		$group_persiapan_pengemasan = $this->M_PermintaanPengemasanByQtyDO->GetTableSementaraGroupByPrincipleDepoDetail($arr_persiapan_pengemasan);

		$this->db->trans_begin();

		foreach ($group_persiapan_pengemasan as $key => $value) {

			$id_temp = $this->M_Vrbl->Get_NewID();
			$id_temp = $id_temp[0]['NEW_ID'];
			$date_now = date('Y-m-d h:i:s');
			$prefix = 'RPK';
			// get prefik depo
			$depo_id = $this->session->userdata('depo_id');
			$depoPrefix = $this->M_PermintaanPengemasanByQtyDO->getDepoPrefix($depo_id);
			$unit = $depoPrefix->depo_kode_preffix;
			$tr_konversi_sku_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);
			$client_wms_id = $value['client_wms_id'];
			$depo_detail_id = $value['depo_detail_id'];
			$principle_id = $value['principle_id'];
			$tipe_konversi_id = "D5CF6747-6C95-40E1-A64B-6A9057DD320B";

			$data_detail = $this->M_PermintaanPengemasanByQtyDO->Get_tr_konversi_sku_detail_temp($client_wms_id, $principle_id, $depo_detail_id, $arr_persiapan_pengemasan);

			$this->M_PermintaanPengemasanByQtyDO->insert_tr_konversi_sku($id_temp, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, NULL, NULL, NULL, NULL, NULL, 1);

			foreach ($data_detail as $key2 => $value2) {
				$tr_konversi_sku_detail_id = $this->M_Vrbl->Get_NewID();
				$tr_konversi_sku_detail_id = $tr_konversi_sku_detail_id[0]['NEW_ID'];

				$this->M_PermintaanPengemasanByQtyDO->insert_tr_konversi_sku_detail($tr_konversi_sku_detail_id, $id_temp, $value2);
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function Insert_tr_konversi_sku_temp()
	{

		$tr_konversi_sku_temp_id = $this->input->post('tr_konversi_sku_temp_id');
		$client_wms_id = $this->input->post('client_wms_id');
		$depo_detail_id = $this->input->post('depo_detail_id');
		$sku_id = $this->input->post('sku_id');
		$sku_stock_id = "";
		$sku_stock_expired_date = "";
		$tr_konversi_sku_detail_qty_plan = $this->input->post('sku_qty_draft');

		$this->db->trans_begin();

		$this->M_PermintaanPengemasanByQtyDO->Insert_tr_konversi_sku_temp($tr_konversi_sku_temp_id, $client_wms_id, $sku_id, $depo_detail_id, $sku_stock_id, $sku_stock_expired_date, $tr_konversi_sku_detail_qty_plan);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(["status" => 0, "tr_konversi_sku_temp_id" => ""]);
		} else {
			$this->db->trans_commit();
			echo json_encode(["status" => 1, "tr_konversi_sku_temp_id" => $tr_konversi_sku_temp_id]);
		}
	}

	public function Get_tr_konversi_sku_temp_by_id()
	{
		$tr_konversi_sku_temp_id = $this->input->get('tr_konversi_sku_temp_id');

		$data = $this->M_PermintaanPengemasanByQtyDO->Get_tr_konversi_sku_temp_by_id($tr_konversi_sku_temp_id);
		echo json_encode($data);
	}

	public function GetListPengemasan()
	{
		$tipe_do = $this->input->post('filter_tipedo');
		$tgl = $this->input->post('filter_tanggal');
		$principle = $this->input->post('principle');
		$flag = $this->input->post('flag');

		$data = $this->M_PermintaanPengemasanByQtyDO->GetListPengemasan($tgl, $tipe_do, $principle, $flag);
		echo json_encode($data);
	}

	public function Delete_tr_konversi_sku_temp_by_id()
	{
		$tr_konversi_sku_temp_id = $this->input->post('tr_konversi_sku_temp_id');

		$this->db->trans_begin();

		$this->M_PermintaanPengemasanByQtyDO->Delete_tr_konversi_sku_temp_by_id($tr_konversi_sku_temp_id);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}
}
