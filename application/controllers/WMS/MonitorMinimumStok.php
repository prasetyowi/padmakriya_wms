<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentController.php';

class MonitorMinimumStok extends CI_Controller
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

		$this->MenuKode = "128001000";
		$this->load->model('WMS/M_PersetujuanPembongkaranBarang', 'M_PersetujuanPembongkaranBarang');
		$this->load->model('M_MonitorMinimumStok');
		$this->load->model('M_ClientWms');
		$this->load->model('M_DepoDetail');
		$this->load->model('M_Principle');
		$this->load->model('M_Vrbl');
		$this->load->model('M_Menu');
	}

	public function MonitorMinimumStokMenu()
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
		$data['Gudang'] = $this->M_DepoDetail->findByDepo_array();
		$data['Principle'] = $this->M_Principle->Get_Principle();
		$data['TipeKonversi'] = $this->M_PersetujuanPembongkaranBarang->GetTipeKonversi();

		$tr_konversi_sku_id = $this->M_Vrbl->Get_NewID();
		$data['tr_konversi_sku_id'] = $tr_konversi_sku_id[0]['NEW_ID'];

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
		$this->load->view('WMS/MonitorMinimumStok/index', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/MonitorMinimumStok/script', $data);
	}

	public function GetMonitorMinimumStok()
	{
		$principle = $this->input->post('principle');
		$depo_detail_id = $this->input->post('depo_detail_id');
		$min_stock = $this->input->post('min_stock');

		$data = $this->M_MonitorMinimumStok->Get_MonitorMinimumStok($principle, $depo_detail_id, $min_stock);

		echo json_encode($data);
	}

	public function get_client_wms_konversi_sku()
	{
		$data = $this->M_MonitorMinimumStok->get_client_wms_konversi_sku();

		echo json_encode($data);
	}

	public function Konversi_sku()
	{
		$error_status = array();
		$arr_list_sku = $this->input->post('arr_list_sku');
		$depo_detail_id = $this->input->post('depo_detail_id');

		if (count($arr_list_sku) > 0) {
			foreach ($arr_list_sku as $value) {

				$getSKULevel = $this->M_MonitorMinimumStok->getSKULevel($value['sku_konversi_group']);

				if (count($getSKULevel) > 0) {
					foreach ($getSKULevel as $val_sku_level) {
						$dataSKUStockBySKUUpperLevel = $this->M_MonitorMinimumStok->GetDataSKUPembongkaran($val_sku_level['sku_id'], $depo_detail_id);

						$getNameSKU = $this->M_MonitorMinimumStok->getNameSKUById($val_sku_level['sku_id']);

						if (count($dataSKUStockBySKUUpperLevel) > 0) {

							$this->db->trans_begin();

							foreach ($dataSKUStockBySKUUpperLevel as $key => $val) {

								$cek_tr_konversi_sku_from_do = $this->M_MonitorMinimumStok->cek_tr_konversi_sku_from_do($val->sku_id, $val->sku_stock_id);

								if (count($cek_tr_konversi_sku_from_do) == 0) {
									$tr_konversi_sku_from_do_id = $this->M_Vrbl->Get_NewID();
									$tr_konversi_sku_from_do_id = $tr_konversi_sku_from_do_id[0]['NEW_ID'];

									$depo_id = $this->session->userdata('depo_id');
									$tr_konversi_sku_from_do_tanggal = date('Y-m-d H:i:s');
									$sku_id = $val->sku_id;
									$sku_qty = $value['sku_stock'];
									$tr_konversi_sku_from_do_status = "";
									$tr_konversi_sku_from_do_tgl_create = date('Y-m-d H:i:s');
									$tr_konversi_sku_from_do_who_create = $this->session->userdata('pengguna_username');
									$tr_konversi_sku_from_do_keterangan = "MINIMUM STOCK";
									$sku_stock_exp_date = $val->sku_stock_expired_date;
									$sku_stock_id = $val->sku_stock_id;

									$this->M_MonitorMinimumStok->Insert_tr_konversi_sku_from_do($tr_konversi_sku_from_do_id, $depo_id, $tr_konversi_sku_from_do_tanggal, $sku_id, $sku_qty, $tr_konversi_sku_from_do_status, $tr_konversi_sku_from_do_tgl_create, $tr_konversi_sku_from_do_who_create, $tr_konversi_sku_from_do_keterangan, $sku_stock_exp_date, $sku_stock_id);
								} else {
									foreach ($cek_tr_konversi_sku_from_do as $key_konversi_sku_from_do => $value_konversi_sku_from_do) {
										$tr_konversi_sku_from_do_id = $value_konversi_sku_from_do['tr_konversi_sku_from_do_id'];
										$depo_id = $value_konversi_sku_from_do['depo_id'];
										$tr_konversi_sku_from_do_tanggal = $value_konversi_sku_from_do['tr_konversi_sku_from_do_tanggal'];
										$sku_id = $value_konversi_sku_from_do['sku_id'];
										$sku_qty = $value_konversi_sku_from_do['sku_qty'] + $value['sku_stock'];
										$tr_konversi_sku_from_do_status = $value_konversi_sku_from_do['tr_konversi_sku_from_do_status'];
										$tr_konversi_sku_from_do_tgl_create = date('Y-m-d H:i:s');
										$tr_konversi_sku_from_do_who_create = $this->session->userdata('pengguna_username');
										$tr_konversi_sku_from_do_keterangan = "MINIMUM STOCK";
										$sku_stock_exp_date = $val->sku_stock_expired_date;
										$sku_stock_id = $val->sku_stock_id;

										$this->M_MonitorMinimumStok->Update_tr_konversi_sku_from_do($tr_konversi_sku_from_do_id, $depo_id, $tr_konversi_sku_from_do_tanggal, $sku_id, $sku_qty, $tr_konversi_sku_from_do_status, $tr_konversi_sku_from_do_tgl_create, $tr_konversi_sku_from_do_who_create, $tr_konversi_sku_from_do_keterangan, $sku_stock_exp_date, $sku_stock_id);
									}
								}
							}

							if ($this->db->trans_status() === FALSE) {
								$this->db->trans_rollback();
								array_push($error_status, ['type' => 500, 'sku' => $getNameSKU]);
							} else {
								$this->db->trans_commit();
								array_push($error_status, ['type' => 200, 'sku' => $getNameSKU]);
							}
						} else {
							array_push($error_status, ['type' => 203, 'sku' => $getNameSKU]);
						}
					}
				}
			}
		} else {
			array_push($error_status, ['type' => 204, 'sku' => ""]);
		}

		echo json_encode($error_status);
	}

	public function KonversiSKUFromDO()
	{
		$detail_sku_konversi = array();
		$data_sku_konversi = array();
		$depo_detail_id = $this->input->post('depo_detail_id');

		$data_tr_konversi_sku_from_do_by_depo = $this->M_MonitorMinimumStok->get_tr_konversi_sku_from_do_by_depo();

		if (count($data_tr_konversi_sku_from_do_by_depo) > 0) {

			foreach ($data_tr_konversi_sku_from_do_by_depo as $key => $value) {

				$query = $this->db->query("Exec proses_konversi_sku_pack_unpack '" . $value['sku_id'] . "', '" . $value['sku_qty'] . "', 'Repack_do'");

				if ($query->num_rows() > 0) {
					foreach ($query->result_array() as $key_query => $value_query) {
						$detail_sku_konversi = array("sku_id" => $value_query['sku_id'], "sku_qty" => $value_query['hasil'], "sku_stock_id" => $value['sku_stock_id'], "sku_stock_exp_date" => $value['sku_stock_exp_date']);
						array_push($data_sku_konversi, $detail_sku_konversi);
					}
				}
			}
			$data_sku_stock_by_konversi_sku = $this->M_MonitorMinimumStok->get_sku_stock_by_konversi_sku($data_sku_konversi);

			$arr_ED = [];

			if (count($data_sku_stock_by_konversi_sku) > 0) {
				foreach ($data_sku_stock_by_konversi_sku as $row) {
					$getED = $this->M_MonitorMinimumStok->getED($row['sku_id'], $row['client_wms_id'], $depo_detail_id);
					if ($getED != 0) {
						// array_push($arr_ED, $getED);
						foreach ($getED as $key => $value) {
							array_push($arr_ED, $value);
							// $arr_ED = array(
							// 	'sku_stock_id' => $value['sku_stock_id'],
							// 	'sku_id' => $value['sku_id'],
							// 	'client_wms_id' => $value['client_wms_id'],
							// 	'depo_id' => $value['depo_id'],
							// 	'sku_stock_batch_no' => $value['sku_stock_batch_no'],
							// 	'depo_detail_id' => $value['depo_detail_id'],
							// 	'sku_stock_expired_date' => $value['sku_stock_expired_date'],
							// );
						}
					}
				}
			}
		} else {
			$data_sku_stock_by_konversi_sku = 0;
			$arr_ED = 0;
		}

		$response = [
			'data' => $data_sku_stock_by_konversi_sku,
			'ed'   => $arr_ED
		];

		echo json_encode($response);
	}

	public function UpdateSKUQtyKonversi()
	{
		$data_tr_konversi_sku_from_do_by_depo = $this->M_MonitorMinimumStok->get_tr_konversi_sku_from_do_by_depo();

		$tr_konversi_sku_id = $this->input->post('tr_konversi_sku_id');

		// echo json_encode($data_tr_konversi_sku_from_do_by_depo);
		// die;

		$this->db->trans_begin();

		foreach ($data_tr_konversi_sku_from_do_by_depo as $key => $value) {

			$query = $this->db->query("Exec proses_konversi_sku_pack_unpack '" . $value['sku_id'] . "', '" . $value['sku_qty'] . "', 'Repack_do'");

			if ($query->num_rows() > 0) {
				foreach ($query->result_array() as $key_query => $value_query) {
					$this->M_MonitorMinimumStok->Update_qty_tr_konversi_sku_detail($tr_konversi_sku_id, $value_query['sku_id'], $value_query['hasil']) . "<br>";
				}
			}
		}

		$this->M_MonitorMinimumStok->delete_tr_konversi_sku_from_do();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}
}
