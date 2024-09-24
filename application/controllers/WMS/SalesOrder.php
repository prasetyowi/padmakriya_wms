<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentController.php';

class SalesOrder extends CI_Controller
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

		$this->MenuKode = "132001000";
		$this->load->model('WMS/M_DeliveryOrderDraft', 'M_DeliveryOrderDraft');
		$this->load->model('M_SalesOrder');
	}

	public function DownloadSOFASMenu()
	{
		$this->load->model('M_Menu');
		$this->load->model('M_SalesOrder');


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

		$data['Perusahaan'] = $this->M_SalesOrder->GetPerusahaan();

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
		$this->load->view('WMS/SalesOrder/sales_order/SalesOrder', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SalesOrder/sales_order/S_SalesOrder', $data);
	}

	public function DownloadCanvasFASMenu()
	{
		$this->load->model('M_Menu');
		$this->load->model('M_SalesOrder');

		$this->MenuKode = "132002000";


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

		$data['Perusahaan'] = $this->M_SalesOrder->GetPerusahaan();

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
		$this->load->view('WMS/SalesOrder/canvas/canvas', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SalesOrder/canvas/s_canvas', $data);
	}

	public function DownloadStockMovementBosnetMenu()
	{
		$this->load->model('M_Menu');
		$this->load->model('M_SalesOrder');

		$this->MenuKode = "132003000";


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

		$data['Perusahaan'] = $this->M_SalesOrder->GetPerusahaan();
		$data['TipeTransaksi'] = $this->M_SalesOrder->Get_tipe_transaksi_bosnet();

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
		$this->load->view('WMS/SalesOrder/stock_movement/stock_movement', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/SalesOrder/stock_movement/s_stock_movement', $data);
	}

	public function GetSalesOrderMenu()
	{
		$this->load->model('M_SalesOrder');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$pengguna_grup_id = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($pengguna_grup_id, $MenuLink);

		$data['pengguna_grup_id'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetSalesOrderFAS()
	{
		$this->load->model('M_SalesOrder');

		$tgl = date('Y-m-d', strtotime($this->input->post('tgl')));
		$status = $this->input->post('status');
		$principle = $this->input->post('principle');
		$perusahaan_eksternal = $this->input->post('perusahaan_eksternal');

		// $tgl = explode(" - ", $this->input->post('tgl'));

		// $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		// $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		// $depo = "'999'";

		$data['SalesOrderInDO'] = $this->M_SalesOrder->Get_SalesOrderInDO($tgl, $perusahaan_eksternal, $principle);
		$data['SalesOrderNotInDO'] = $this->M_SalesOrder->Get_SalesOrderNotInDO($tgl, $status, $perusahaan_eksternal, $principle);

		echo json_encode($data);
	}

	public function GetCanvasFAS()
	{
		$this->load->model('M_SalesOrder');

		$tgl = date('Y-m-d', strtotime($this->input->post('tgl')));
		$status = $this->input->post('status');
		$perusahaan_eksternal = $this->input->post('perusahaan_eksternal');

		// $tgl = explode(" - ", $this->input->post('tgl'));

		// $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		// $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		// $depo = "'999'";

		$data['CanvasInDO'] = $this->M_SalesOrder->Get_CanvasInDO($tgl, $perusahaan_eksternal);
		$data['CanvasNotInDO'] = $this->M_SalesOrder->Get_CanvasNotInDO($tgl, $perusahaan_eksternal);

		echo json_encode($data);
	}

	public function SaveSalesOrderFAS()
	{
		ini_set('max_execution_time', '0');

		$this->load->model('M_SalesOrder');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "C")) {
			echo 0;
			exit();
		}

		$valdiasi = array();

		$tgl = date('Y-m-d', strtotime($this->input->post('tgl')));
		$perusahaan = $this->input->post('perusahaan');
		$principle = $this->input->post('principle');
		$switched = $this->input->post('switched');

		if ($switched == "0") {
			// $get_sales_order = $this->M_SalesOrder->Get_sales_order($tgl);

			$this->db->trans_begin();

			$so_internal = $this->M_SalesOrder->insert_sales_order_internal($tgl, $perusahaan, $principle);
			$this->M_SalesOrder->insert_sales_order_detail_internal($tgl, $perusahaan, $principle);
			$this->M_SalesOrder->insert_sales_order_detail2_internal($tgl, $perusahaan, $principle);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				array_push($valdiasi, array("kode" => "0", "msg" => "CAPTION-ALERT-DOWNLOADSOEKSTERNALFAILED"));
			} else {
				$this->db->trans_commit();
				array_push($valdiasi, array("kode" => "1", "msg" => $so_internal['affectedrows'] . " SO FAS berhasil diunduh!"));
			}

			// if ($get_sales_order != "0") {
			// 	foreach ($get_sales_order as $key => $value) {

			// 		// if ($value['client_wms_id'] != "") {

			// 		$cek_do = $this->M_SalesOrder->check_so_duplicate($value['sales_order_kode']);

			// 		if ($cek_do == 0) {
			// 			$dod_id = $this->M_Vrbl->Get_NewID();
			// 			$dod_id = $dod_id[0]['NEW_ID'];

			// 			//generate kode
			// 			$date_now = date('Y-m-d h:i:s');
			// 			$param =  'KODE_DOD';
			// 			$vrbl = $this->M_Vrbl->Get_Kode($param);
			// 			$prefix = $vrbl->vrbl_kode;
			// 			// get prefik depo
			// 			$depo_id = $this->session->userdata('depo_id');
			// 			$depoPrefix = $this->M_SalesOrder->getDepoPrefix($depo_id);
			// 			$unit = $depoPrefix->depo_kode_preffix;
			// 			$do_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

			// 			$this->db->trans_begin();

			// 			$this->M_SalesOrder->Insert_sales_order($value);

			// 			if ($this->db->trans_status() === FALSE) {
			// 				$this->db->trans_rollback();
			// 				array_push($valdiasi, array("kode" => "0", "msg" => "CAPTION-ALERT-GENERATEDSOEKSTERNALFAILED"));
			// 			} else {
			// 				$this->db->trans_commit();
			// 				array_push($valdiasi, array("kode" => "1", "msg" => "CAPTION-ALERT-GENERATEDSOFASSUCCESS"));
			// 			}
			// 		} else {
			// 			array_push($valdiasi, array("kode" => "2", "msg" => "CAPTION-ALERT-SOSUDAHADA", "so" => $value['sales_order_kode']));
			// 		}

			// 		$arr_list_so = $this->M_SalesOrder->Get_sales_order_wms($tgl);

			// 		// echo var_dump($arr_list_so) . "<br>";

			// 		if ($arr_list_so != "0") {

			// 			foreach ($arr_list_so as $key => $value) {

			// 				$get_sales_order_detail = $this->M_SalesOrder->Get_sales_order_detail($value['sales_order_id'], $perusahaan);

			// 				// echo var_dump($get_sales_order_detail) . "<br>";

			// 				if ($get_sales_order_detail != "0") {

			// 					foreach ($get_sales_order_detail as $key => $value2) {
			// 						if (isset($value2['sales_order_id'])) {
			// 							$this->M_SalesOrder->Insert_sales_order_detail($value2);
			// 							// $this->M_SalesOrder->Insert_sales_order_detail2($dodd_id, $dod_id, $value['sales_order_id']);
			// 						}
			// 					}
			// 				}
			// 			}
			// 		}
			// 		// } else {
			// 		// 	array_push($valdiasi, array("kode" => "3", "msg" => "CAPTION-ALERT-ADASOYGTIDAKMEMILIKIPERUSAHAAN", "so" => $value['sales_order_kode']));
			// 		// }
			// 	}
			// }

			// $valdiasi_uniq = array_unique($valdiasi);
			// rsort($valdiasi_uniq);
			// echo json_encode($valdiasi_uniq);
			echo json_encode($valdiasi);
		} else {
			$url = Get_FAS_URL() . "API/SistemEksternal/SalesOrderHeader?tgl1=" . $tgl . "&tgl2=" . $tgl;

			$header = array('Content-Type: application/json');

			//构建curl请求
			$c = curl_init();
			curl_setopt($c, CURLOPT_URL, $url);
			curl_setopt($c, CURLOPT_HTTPHEADER, $header);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_BUFFERSIZE, 10240);

			//获取到数据 json格式
			$datajson = curl_exec($c);

			$get_sales_order = json_decode($datajson, true);

			// echo var_dump($get_sales_order) . "<br>";

			// echo count($get_sales_order);

			// echo $get_sales_order['message'];

			if (!isset($get_sales_order['message'])) {
				foreach ($get_sales_order as $key => $value) {

					// if ($value['client_wms_id'] != "") {

					$cek_do = $this->M_SalesOrder->check_so_duplicate($value['sales_order_kode']);

					if ($cek_do == 0) {
						$dod_id = $this->M_Vrbl->Get_NewID();
						$dod_id = $dod_id[0]['NEW_ID'];

						//generate kode
						$date_now = date('Y-m-d h:i:s');
						$param =  'KODE_DOD';
						$vrbl = $this->M_Vrbl->Get_Kode($param);
						$prefix = $vrbl->vrbl_kode;
						// get prefik depo
						$depo_id = $this->session->userdata('depo_id');
						$depoPrefix = $this->M_SalesOrder->getDepoPrefix($depo_id);
						$unit = $depoPrefix->depo_kode_preffix;
						$do_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

						$this->db->trans_begin();

						$this->M_SalesOrder->Insert_sales_order($value);

						if ($this->db->trans_status() === FALSE) {
							$this->db->trans_rollback();
							array_push($valdiasi, array("kode" => "0", "msg" => "CAPTION-ALERT-DOWNLOADSOEKSTERNALFAILED"));
						} else {
							$this->db->trans_commit();
							array_push($valdiasi, array("kode" => "1", "msg" => "CAPTION-ALERT-DOWNLOADSOFASSUCCESS"));
						}
					} else {
						array_push($valdiasi, array("kode" => "2", "msg" => "CAPTION-ALERT-SOSUDAHADA", "so" => $value['sales_order_kode']));
					}
					// } else {
					// 	array_push($valdiasi, array("kode" => "3", "msg" => "CAPTION-ALERT-ADASOYGTIDAKMEMILIKIPERUSAHAAN", "so" => $value['sales_order_kode']));
					// }
				}

				$arr_list_so = $this->M_SalesOrder->Get_sales_order($tgl);

				// echo var_dump($arr_list_so) . "<br>";

				if ($arr_list_so != "0") {

					foreach ($arr_list_so as $key => $value) {
						$url2 = Get_FAS_URL() . "API/SistemEksternal/SalesOrderDetail?sales_order_id=" . $value['sales_order_id'];

						$header2 = array('Content-Type: application/json');

						//构建curl请求
						$c2 = curl_init();
						curl_setopt($c2, CURLOPT_URL, $url2);
						curl_setopt($c2, CURLOPT_HTTPHEADER, $header2);
						curl_setopt($c2, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($c2, CURLOPT_BUFFERSIZE, 10240);

						//获取到数据 json格式
						$datajson2 = curl_exec($c2);

						$get_sales_order_detail = json_decode($datajson2, true);

						// echo var_dump($get_sales_order_detail) . "<br>";

						// if ($get_sales_order_detail !== NULL) {

						foreach ($get_sales_order_detail as $key2 => $value2) {
							if (isset($value2['sales_order_id'])) {
								// echo $value2['sales_order_id'];
								$this->M_SalesOrder->Insert_sales_order_detail($value2);
								// $this->M_SalesOrder->Insert_sales_order_detail2($dodd_id, $dod_id, $value['sales_order_id']);
							}
						}
						// }
					}
				}
			} else {
				array_push($valdiasi, array("kode" => "0", "msg" => "CAPTION-ALERT-DOWNLOADSOEKSTERNALFAILED"));
			}

			// $valdiasi_uniq = array_unique($valdiasi);
			// rsort($valdiasi_uniq);
			// echo json_encode($valdiasi_uniq);
			echo json_encode($valdiasi);
		}
	}

	public function SaveDeliveryOrder()
	{
		ini_set('max_execution_time', '0');

		$this->load->model('M_SalesOrder');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "C")) {
			echo 0;
			exit();
		}

		$valdiasi = array();

		$tgl = date('Y-m-d', strtotime($this->input->post('tgl')));
		$perusahaan_eksternal = $this->input->post('perusahaan_eksternal');
		$principle = $this->input->post('principle');

		// $tgl = explode(" - ", $this->input->post('tgl'));

		// $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		// $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$get_sales_order = $this->M_SalesOrder->Get_sales_order_wms($tgl, $perusahaan_eksternal, $principle);
		// echo count($get_sales_order);

		if (count($get_sales_order) > 0) {

			foreach ($get_sales_order as $key => $value) {

				$cek_client_pt = $this->M_SalesOrder->Cek_client_pt($value['client_pt_id']);

				// echo var_dump($cek_client_pt);

				if ($cek_client_pt == "0") {
					$client_pt_id = $this->M_Vrbl->Get_NewID();
					$client_pt_id = $client_pt_id[0]['NEW_ID'];

					$this->M_SalesOrder->Insert_client_pt($client_pt_id, $value['client_pt_id']);

					$get_client_pt_eksternal = $this->M_SalesOrder->Get_client_pt_by_eksternal($value['client_pt_id']);

					if (isset($get_client_pt_eksternal->client_pt_nama)) {
						$client_pt_nama = $get_client_pt_eksternal->client_pt_nama;
						$client_pt_alamat = $get_client_pt_eksternal->client_pt_alamat;
						$client_pt_propinsi = $get_client_pt_eksternal->client_pt_propinsi;
						$client_pt_kota = $get_client_pt_eksternal->client_pt_kota;
						$client_pt_kecamatan = $get_client_pt_eksternal->client_pt_kecamatan;
						$client_pt_kelurahan = $get_client_pt_eksternal->client_pt_kelurahan;
						$client_pt_kodepos = $get_client_pt_eksternal->client_pt_kodepos;

						$this->M_SalesOrder->Update_client_pt_eksternal($client_pt_id, $client_pt_nama, $client_pt_alamat, $client_pt_kelurahan, $client_pt_kecamatan, $client_pt_kota, $client_pt_propinsi, $client_pt_kodepos);
					}
				}

				$get_client_pt = $this->M_SalesOrder->Get_client_pt($value['client_pt_id']);
				$arr_client_wms = $this->M_SalesOrder->Get_client_wms_so($value['sales_order_id']);

				// echo var_dump($get_client_pt);

				if (isset($get_client_pt->client_pt_id)) {

					if (count($arr_client_wms) > 0) {
						$client_pt_id = $get_client_pt->client_pt_id;
						$client_pt_nama = $get_client_pt->client_pt_nama;
						$client_pt_alamat = $get_client_pt->client_pt_alamat;
						$client_pt_telepon = $get_client_pt->client_pt_telepon;
						$client_pt_propinsi = $get_client_pt->client_pt_propinsi;
						$client_pt_kota = $get_client_pt->client_pt_kota;
						$client_pt_kecamatan = $get_client_pt->client_pt_kecamatan;
						$client_pt_kelurahan = $get_client_pt->client_pt_kelurahan;
						$client_pt_kodepos = $get_client_pt->client_pt_kodepos;
						$area_nama = $get_client_pt->area_nama;

						// echo $area_nama . "<br>";

						foreach ($arr_client_wms as $key2 => $value2) {

							if ($value2['client_wms_id'] != "") {

								$cek_do = $this->M_SalesOrder->Check_DO_By_SO($value2['sales_order_id'], $value2['client_wms_id']);

								if ($cek_do == 0) {
									$dod_id = $this->M_Vrbl->Get_NewID();
									$dod_id = $dod_id[0]['NEW_ID'];

									//generate kode
									$date_now = date('Y-m-d h:i:s');
									$param =  $value['tipe_sales_order_id'] == 'AD89E05B-46A6-453B-8F19-886514234A21' ? 'KODE_DOR' : 'KODE_DOD';
									// $param =  'KODE_DOD';
									$vrbl = $this->M_Vrbl->Get_Kode($param);
									$prefix = $vrbl->vrbl_kode;
									// get prefik depo
									$depo_id = $this->session->userdata('depo_id');
									$depoPrefix = $this->M_SalesOrder->getDepoPrefix($depo_id);
									$unit = $depoPrefix->depo_kode_preffix;
									$do_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

									$this->db->trans_begin();

									$this->M_SalesOrder->Insert_delivery_order($dod_id, $do_kode, $value2['client_wms_id'], $value2['sku_harga_nett'], $client_pt_id, $client_pt_nama, $client_pt_telepon, $client_pt_alamat, $client_pt_kelurahan, $client_pt_kecamatan, $client_pt_kota, $client_pt_propinsi, $client_pt_kodepos, $area_nama, $value, 'sales');

									$get_sales_order_detail = $this->M_SalesOrder->Get_sales_order_detail_wms($value2['sales_order_id'], $value2['client_wms_id']);

									foreach ($get_sales_order_detail as $key3 => $value3) {
										$dodd_id = $this->M_Vrbl->Get_NewID();
										$dodd_id = $dodd_id[0]['NEW_ID'];

										$this->M_SalesOrder->Insert_delivery_order_detail($dodd_id, $dod_id, $value3);

										if ($value['tipe_sales_order_id'] == "AD89E05B-46A6-453B-8F19-886514234A21") {

											$get_sales_order_detail2 = $this->M_SalesOrder->Get_sales_order_detail2_wms($value3['sales_order_detail_id']);

											foreach ($get_sales_order_detail2 as $key4 => $value4) {
												$dodd2_id = $this->M_Vrbl->Get_NewID();
												$dodd2_id = $dodd2_id[0]['NEW_ID'];
												$this->M_SalesOrder->Insert_delivery_order_detail2($dodd2_id, $dodd_id, $dod_id, $value4);
											}
										}
									}

									if ($this->db->trans_status() === FALSE) {
										$this->db->trans_rollback();
										array_push($valdiasi, array("kode" => "0", "msg" => "CAPTION-ALERT-GENERATEDDOGAGAL"));
									} else {
										$this->db->trans_commit();
										array_push($valdiasi, array("kode" => "1", "msg" => "CAPTION-ALERT-GENERATEDDOSUCCESS"));
									}
								} else {
									array_push($valdiasi, array("kode" => "2", "msg" => "CAPTION-ALERT-SOSUDAHADADO", "so" => $value['sales_order_kode']));
								}
							} else {
								array_push($valdiasi, array("kode" => "3", "msg" => "CAPTION-ALERT-ADASOYGTIDAKMEMILIKIPERUSAHAAN", "so" => $value['sales_order_kode']));
							}
						}
					}
				}
			}
		} else {
			array_push($valdiasi, array("kode" => "4", "msg" => "CAPTION-ALERT-SOTIDAKDITEMUKAN"));
		}

		// $valdiasi_uniq = array_unique($valdiasi);
		// rsort($valdiasi_uniq);
		// echo json_encode($valdiasi_uniq);
		echo json_encode($valdiasi);
	}

	public function SaveCanvasDeliveryOrder()
	{
		ini_set('max_execution_time', '0');

		$this->load->model('M_SalesOrder');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "C")) {
			echo 0;
			exit();
		}

		$valdiasi = array();

		$tgl = date('Y-m-d', strtotime($this->input->post('tgl')));
		$perusahaan_eksternal = $this->input->post('perusahaan_eksternal');

		$sku_stock_qty_temp = 0;
		$arr_do_detail2_temp = array();
		$data_do_detail2_temp = array();

		// $tgl = explode(" - ", $this->input->post('tgl'));

		// $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		// $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$get_canvas_order = $this->M_SalesOrder->Get_canvas_wms($tgl, $perusahaan_eksternal);
		// echo count($get_sales_order);

		if (count($get_canvas_order) > 0) {

			foreach ($get_canvas_order as $key => $value) {

				$client_pt_id = null;
				$client_pt_nama = null;
				$client_pt_alamat = null;
				$client_pt_telepon = null;
				$client_pt_propinsi = null;
				$client_pt_kota = null;
				$client_pt_kecamatan = null;
				$client_pt_kelurahan = null;
				$client_pt_kodepos = null;
				$area_nama = null;

				$sku_harga_nett = $value['sku_harga_nett'];

				if ($value['client_wms_id'] != "") {

					$cek_do = $this->M_SalesOrder->Check_DO_By_SO($value['sales_order_id'], $value['client_wms_id']);

					if ($cek_do == 0) {
						$dod_id = $this->M_Vrbl->Get_NewID();
						$dod_id = $dod_id[0]['NEW_ID'];

						//generate kode
						$date_now = date('Y-m-d h:i:s');
						$param =  'KODE_DOD';
						$vrbl = $this->M_Vrbl->Get_Kode($param);
						$prefix = $vrbl->vrbl_kode;
						// get prefik depo
						$depo_id = $this->session->userdata('depo_id');
						$depoPrefix = $this->M_SalesOrder->getDepoPrefix($depo_id);
						$unit = $depoPrefix->depo_kode_preffix;
						$do_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

						$this->db->trans_begin();

						$this->M_SalesOrder->Insert_delivery_order($dod_id, $do_kode, $value['client_wms_id'], $sku_harga_nett, $value['client_pt_id'], $value['client_pt_nama'], $value['client_pt_telepon'], $value['client_pt_alamat'], $value['client_pt_kelurahan'], $value['client_pt_kecamatan'], $value['client_pt_kota'], $value['client_pt_propinsi'], $value['client_pt_kodepos'], $value['area_kode'], $value, 'canvas');

						$get_canvas_detail = $this->M_SalesOrder->Get_canvas_detail_wms($value['sales_order_id'], $value['client_wms_id']);

						foreach ($get_canvas_detail as $key => $value2) {
							$dodd_id = $this->M_Vrbl->Get_NewID();
							$dodd_id = $dodd_id[0]['NEW_ID'];

							// $data_sku_stock_canvas = $this->M_SalesOrder->cek_sku_stock_canvas($value2['sku_id']);

							// foreach ($data_sku_stock_canvas as $key_sku_stock => $value_sku_stock) {
							// 	$sku_stock_qty_temp = $value2['sku_qty'];

							// 	if ($sku_stock_qty_temp >= $value2['sku_qty']) {
							// 		$data_do_detail2_temp = array("sku_id" => $value2['sku_id'], "sku_stock_id" => $value_sku_stock['sku_stock_id'], "sku_expdate" => $value_sku_stock['sku_stock_expired_date'], "sku_qty" => $value2['sku_qty']);

							// 		array_push($arr_do_detail2_temp, $data_do_detail2_temp);
							// 	} else {

							// 		$sku_stock_qty_temp = $sku_stock_qty_temp - $value_sku_stock['stock_akhir'];
							// 		$data_do_detail2_temp = array("sku_id" => $value2['sku_id'], "sku_stock_id" => $value_sku_stock['sku_stock_id'], "sku_expdate" => $value_sku_stock['sku_stock_expired_date'], "sku_qty" => $value_sku_stock['stock_akhir']);

							// 		array_push($arr_do_detail2_temp, $data_do_detail2_temp);
							// 	}
							// }

							$this->M_SalesOrder->Insert_delivery_order_detail($dodd_id, $dod_id, $value2);
							// $this->M_SalesOrder->Insert_sales_order_detail2($dodd_id, $dod_id, $value['sales_order_id']);

							// $get_canvas_detail_2 = $this->M_SalesOrder->Get_canvas_detail2_wms($value['sales_order_id']);

							// if ($get_canvas_detail_2) {
							// 	foreach ($get_canvas_detail_2 as $key => $value3) {
							// 		if ($value2['sales_order_detail_id'] === $value3->canvas_detail_id) {
							// 			$dodd2_id = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];
							// 			$this->db->insert('delivery_order_detail2_draft', [
							// 				'delivery_order_detail2_draft_id' => $dodd2_id,
							// 				'delivery_order_detail_draft_id' => $dodd_id,
							// 				'delivery_order_draft_id' => $dod_id,
							// 				'sku_id' => $value3->sku_id,
							// 				'sku_stock_id' => $value3->sku_stock_id,
							// 				'sku_expdate' => $value3->sku_expdate,
							// 				'sku_qty' => $value3->sku_qty,
							// 				'sku_qty_composite' => $value3->sku_composite,
							// 			]);

							// 			$this->db->query("exec insertupdate_sku_stock 'saldoalokasi_tambah', '$value3->sku_stock_id', NULL, '$value3->sku_qty'");
							// 		}
							// 	}
							// }
						}

						if ($this->db->trans_status() === FALSE) {
							$this->db->trans_rollback();
							array_push($valdiasi, array("kode" => "0", "msg" => "CAPTION-ALERT-GENERATEDDOGAGAL"));
						} else {
							$this->db->trans_commit();
							array_push($valdiasi, array("kode" => "1", "msg" => "CAPTION-ALERT-GENERATEDDOSUCCESS"));
						}
					} else {
						array_push($valdiasi, array("kode" => "2", "msg" => "CAPTION-ALERT-SOSUDAHADADO", "so" => $value['sales_order_kode']));
					}
				} else {
					array_push($valdiasi, array("kode" => "3", "msg" => "CAPTION-ALERT-ADASOYGTIDAKMEMILIKIPERUSAHAAN", "so" => $value['sales_order_kode']));
				}
			}
		} else {
			array_push($valdiasi, array("kode" => "4", "msg" => "CAPTION-ALERT-SOTIDAKDITEMUKAN"));
		}

		// $valdiasi_uniq = array_unique($valdiasi);
		// rsort($valdiasi_uniq);
		// echo json_encode($valdiasi_uniq);
		echo json_encode($valdiasi);
		// echo json_encode($arr_do_detail2_temp);
	}

	public function Sync_customer()
	{
		ini_set('max_execution_time', 0);

		$this->load->model('M_SalesOrder');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "C")) {
			echo 0;
			exit();
		}

		$customer_id = $this->input->get('customer_id');
		$perusahaan_eksternal = $this->input->get('perusahaan_eksternal');
		$switched = $this->input->get('switched');

		$cek_customer_eksternal = $this->M_SalesOrder->cek_customer_eksternal($customer_id, $perusahaan_eksternal);

		// echo $customer_id;

		$valdiasi = array();

		if ($cek_customer_eksternal == 0) {

			if ($switched == "0") {
				$this->db->trans_begin();

				$this->M_SalesOrder->Insert_client_pt_eksternal_internal($customer_id, $perusahaan_eksternal);

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					array_push($valdiasi, array("kode" => "0", "msg" => "CAPTION-ALERT-SYNCCUSTOMERGAGAL"));
				} else {
					$this->db->trans_commit();
					array_push($valdiasi, array("kode" => "1", "msg" => "CAPTION-ALERT-SYNCCUSTOMERBERHASIL"));
				}
			} else {

				$url = Get_FAS_URL() . "API/SistemEksternal/Customer?customer_id=" . $customer_id;

				$header = array('Content-Type: application/json');

				//构建curl请求
				$c = curl_init();
				curl_setopt($c, CURLOPT_URL, $url);
				curl_setopt($c, CURLOPT_HTTPHEADER, $header);
				curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($c, CURLOPT_BUFFERSIZE, 10240);

				//获取到数据 json格式
				$datajson = curl_exec($c);

				$get_customer = json_decode($datajson, true);

				// echo var_dump($get_customer);

				if ($get_customer !== NULL) {
					foreach ($get_customer as $key => $value) {
						if (isset($value['client_pt_id'])) {

							$this->db->trans_begin();

							$this->M_SalesOrder->Insert_client_pt_eksternal($value, $perusahaan_eksternal);

							if ($this->db->trans_status() === FALSE) {
								$this->db->trans_rollback();
								array_push($valdiasi, array("kode" => "0", "msg" => "CAPTION-ALERT-SYNCCUSTOMERGAGAL"));
							} else {
								$this->db->trans_commit();
								array_push($valdiasi, array("kode" => "1", "msg" => "CAPTION-ALERT-SYNCCUSTOMERBERHASIL"));
							}
						} else {
							array_push($valdiasi, array("kode" => "3", "msg" => "CAPTION-ALERT-SYNCCUSTOMERGAGAL"));
						}
					}
				}
			}
		} else {
			$msg = array("kode" => "2", "msg" => "CAPTION-ALERT-SUDAHSYNCCUSTOMER");
			array_push($valdiasi, $msg);
		}

		$result = array_map("unserialize", array_unique(array_map("serialize", $valdiasi)));
		echo json_encode($result);

		// $valdiasi_uniq = array_unique($valdiasi);
		// rsort($valdiasi_uniq);
		// echo json_encode($valdiasi_uniq);
		// echo json_encode($valdiasi);
	}

	public function SaveCanvasFAS()
	{
		ini_set('max_execution_time', '0');

		$this->load->model('M_SalesOrder');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "C")) {
			echo 0;
			exit();
		}

		$valdiasi = array();

		$tgl = date('Y-m-d', strtotime($this->input->post('tgl')));
		$perusahaan = $this->input->post('perusahaan');
		$switched = $this->input->post('switched');

		$this->db->trans_begin();

		$this->M_SalesOrder->insert_canvas_internal($tgl, $perusahaan);
		$this->M_SalesOrder->insert_canvas_detail_internal($tgl, $perusahaan);
		$this->M_SalesOrder->insert_canvas_detail2_internal($tgl, $perusahaan);
		$this->M_SalesOrder->insert_canvas_detail3_internal($tgl, $perusahaan);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			array_push($valdiasi, array("kode" => "0", "msg" => "CAPTION-ALERT-GENERATEDSOEKSTERNALFAILED"));
		} else {
			$this->db->trans_commit();
			array_push($valdiasi, array("kode" => "1", "msg" => "CAPTION-ALERT-GENERATEDSOFASSUCCESS"));
		}

		// $valdiasi_uniq = array_unique($valdiasi);
		// rsort($valdiasi_uniq);
		// echo json_encode($valdiasi_uniq);
		echo json_encode($valdiasi);
	}

	public function GetDeliveryOrderDraftCanvasByFilter()
	{
		$tgl = date('Y-m-d', strtotime($this->input->post('tgl')));
		$perusahaan_eksternal = $this->input->post('perusahaan_eksternal');

		$data['DOHeader'] = $this->M_SalesOrder->GetDeliveryOrderDraftCanvasByFilter($tgl, $perusahaan_eksternal);
		// $data['DODetail'] = $this->M_DeliveryOrderDraft->GetDeliveryOrderDraftDetailByListId($id);

		echo json_encode($data);
	}

	function GetPrincipleByPerusahaan()
	{

		$perusahaan = $this->input->get('perusahaan');

		$data = $this->M_SalesOrder->GetPrincipleByPerusahaan($perusahaan);

		echo json_encode($data);
	}

	function Get_stock_movement_bosnet_wms()
	{
		$filter_principle = array();

		$tgl = date('Y-m-d', strtotime($this->input->post('tgl')));
		$perusahaan = $this->input->post('perusahaan');
		$tipe_transaksi = $this->input->post('tipe_transaksi');
		$principle = $this->M_SalesOrder->GetPrincipleByPerusahaan($perusahaan);
		$depo_id = $this->session->userdata('depo_id');
		$sistem_eksternal = "BOSNET";
		$depo_eksternal = $this->M_SalesOrder->Get_depo_eksternal($sistem_eksternal, $depo_id);

		if (count($principle) > 0) {
			foreach ($principle as $value) {
				array_push($filter_principle, "'" . $value['principle_kode'] . "'");
			}

			$data = $this->M_SalesOrder->Get_stock_movement_bosnet_wms($tgl, $filter_principle, $tipe_transaksi, $depo_eksternal);
		} else {
			$data = array();
		}

		echo json_encode($data);
	}

	public function SaveStockMovementBosnet()
	{
		ini_set('max_execution_time', '0');

		$this->load->model('M_SalesOrder');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "C")) {
			echo 0;
			exit();
		}

		$valdiasi = array();
		$filter_principle = array();

		$tgl = date('Y-m-d', strtotime($this->input->post('tgl')));
		$perusahaan = $this->input->post('perusahaan');
		$tipe_transaksi = $this->input->post('tipe_transaksi');
		$principle = $this->M_SalesOrder->GetPrincipleByPerusahaan($perusahaan);
		$depo_id = $this->session->userdata('depo_id');
		$sistem_eksternal = "BOSNET";
		$depo_eksternal = $this->M_SalesOrder->Get_depo_eksternal($sistem_eksternal, $depo_id);

		if (count($principle) > 0) {

			foreach ($principle as $value) {
				array_push($filter_principle, "'" . $value['principle_kode'] . "'");
			}

			// $tgl = explode(" - ", $this->input->post('tgl'));

			// $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
			// $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

			if ($depo_eksternal != "") {

				$list_stock_movement = $this->M_SalesOrder->Get_stock_movement_bosnet($tgl, $filter_principle, $tipe_transaksi, $depo_eksternal);
				// echo count($get_sales_order);

				if (count($list_stock_movement) > 0) {

					$this->db->trans_begin();

					foreach ($list_stock_movement as $key => $value) {

						$this->M_SalesOrder->Delete_stock_movement_bosnet($value['szProductId'], $value['dtmTransaction'], $value['szTrnId']);

						$this->M_SalesOrder->Insert_stock_movement_bosnet($value['szProductId'], $value['szPrincipleId'], $value['szLocationType'], $value['szLocationId'], $value['szStockTypeId'], $value['dtmTransaction'], $value['gdHistoryId'], $value['shOrder'], $value['decQty'], $value['decDocQty'], $value['szDocUomId'], $value['szTrnId'], $value['szDocId'], $value['szStockTransferReason'], $value['szOrderTypeId'], $value['szOrderItemTypeId'], $value['szUrl'], $value['szReportedAsType'], $value['szReportedAsId'], $value['szPartyType'], $value['szPartyId'], $value['szEmployeeId'], $value['szRefDocId'], $value['szPartyLocType'], $value['szPartyLocId'], $value['szFakturPajakId'], $value['decCOGS'], $value['dtmLastUpdated'], $value['bFreeze'], $value['szDistProductId'], $value['decDistQty'], $value['szOwnerId'], $value['szProductName']);
					}

					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						array_push($valdiasi, array("kode" => "0", "msg" => "CAPTION-ALERT-GENERATEDSTOCKMOVEMENTGAGAL"));
					} else {
						$this->db->trans_commit();
						array_push($valdiasi, array("kode" => "1", "msg" => "CAPTION-ALERT-GENERATEDSTOCKMOVEMENTSUCCESS"));
					}
				} else {
					array_push($valdiasi, array("kode" => "2", "msg" => "CAPTION-ALERT-STOCKMOVEMENTTIDAKDITEMUKAN"));
				}
			} else {
				array_push($valdiasi, array("kode" => "3", "msg" => "CAPTION-ALERT-DEPOEKSTERNALTIDAKADA"));
			}
		} else {
			array_push($valdiasi, array("kode" => "4", "msg" => "CAPTION-ALERT-PERUSAHAANTIDAKMEMILIKIPRINCIPLE"));
		}

		// $valdiasi_uniq = array_unique($valdiasi);
		// rsort($valdiasi_uniq);
		// echo json_encode($valdiasi_uniq);
		echo json_encode($valdiasi);
		// echo json_encode($arr_do_detail2_temp);
	}

	public function Generate_retur_supplier()
	{
		ini_set('max_execution_time', '0');

		$this->load->model('M_SalesOrder');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->model('M_Menu');
		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "C")) {
			echo 0;
			exit();
		}

		$valdiasi = array();
		$filter_principle = array();

		$tgl = date('Y-m-d', strtotime($this->input->post('tgl')));
		$perusahaan = $this->input->post('perusahaan');
		$tipe_transaksi = $this->input->post('tipe_transaksi');
		$principle = $this->M_SalesOrder->GetPrincipleByPerusahaan($perusahaan);
		$depo_id = $this->session->userdata('depo_id');
		$sistem_eksternal = "BOSNET";
		$depo_eksternal = $this->M_SalesOrder->Get_depo_eksternal($sistem_eksternal, $depo_id);

		if (count($principle) > 0) {

			foreach ($principle as $value) {
				array_push($filter_principle, "'" . $value['principle_kode'] . "'");
			}

			// $tgl = explode(" - ", $this->input->post('tgl'));

			// $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
			// $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

			if ($depo_eksternal != "") {

				$list_principle = $this->M_SalesOrder->Get_stock_movement_bosnet_wms_group_by_principle($tgl, $filter_principle, $tipe_transaksi, $depo_eksternal);
				// echo count($get_sales_order);

				if (count($list_principle) > 0) {

					$this->db->trans_begin();

					foreach ($list_principle as $key => $value) {

						$kpd_id = $this->M_Vrbl->Get_NewID();
						$kpd_id = $kpd_id[0]['NEW_ID'];

						$date_now = date('Y-m-d h:i:s');
						$param =  'KODE_DKSB';
						$vrbl = $this->M_Vrbl->Get_Kode($param);
						$prefix = $vrbl->vrbl_kode;
						// get prefik depo
						$depo_id = $this->session->userdata('depo_id');
						$depoPrefix = $this->M_SalesOrder->getDepoPrefix($depo_id);
						$unit = $depoPrefix->depo_kode_preffix;
						$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

						// $tgl = date('Y-m-d');
						$gudang = "";
						$principle = $value['principle_id'];
						$principle_kode = $value['principle_kode'];
						$checker = "";
						$tipe = "86A1887B-28F2-4F81-86C1-84650B2F2FEC";
						$status = "Draft";
						$keterangan = "";
						$ekspedisi = "";
						$driver = "";
						$kendaraan = "";
						$nopol = "";
						$no_referensi_dokumen = $value['szDocId'];
						$tipe_stock = $value['szStockTypeId'];

						$cek_tr_koreksi_draft = $this->M_SalesOrder->cek_tr_koreksi_draft($no_referensi_dokumen, $tipe_stock);

						if ($cek_tr_koreksi_draft == 0) {

							$this->M_SalesOrder->insert_to_tr_koreksi_draft($kpd_id, $generate_kode, $tgl, $gudang, $principle, $checker, $tipe, $status, $keterangan, $ekspedisi, $driver, $kendaraan, $nopol, $no_referensi_dokumen);

							$list_detail = $this->M_SalesOrder->Get_stock_movement_bosnet_wms_by_principle($tgl, $principle_kode, $tipe_transaksi, $depo_eksternal, $tipe_stock);

							foreach ($list_detail as $key2 => $value2) {
								$list_detail_konversi = $this->M_SalesOrder->Exec_proses_sku_konversi_group($value2['szProductId'], $value2['decQty']);

								foreach ($list_detail_konversi as $key3 => $value3) {
									$kpdd_id = $this->M_Vrbl->Get_NewID();
									$kpdd_id = $kpdd_id[0]['NEW_ID'];

									$this->M_SalesOrder->insert_to_tr_koreksi_detail_draft($kpdd_id, $kpd_id, $value3['sku_id'], NULL, $value3['qty']);
								}
							}
						}
					}

					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						array_push($valdiasi, array("kode" => "0", "msg" => "CAPTION-ALERT-GENERATEDRETURSUPPLIERGAGAL"));
					} else {
						$this->db->trans_commit();
						array_push($valdiasi, array("kode" => "1", "msg" => "CAPTION-ALERT-GENERATEDRETURSUPPLIERSUCCESS"));
					}
				} else {
					array_push($valdiasi, array("kode" => "2", "msg" => "CAPTION-ALERT-RETURSUPPLIERTIDAKDITEMUKAN"));
				}
			} else {
				array_push($valdiasi, array("kode" => "3", "msg" => "CAPTION-ALERT-DEPOEKSTERNALTIDAKADA"));
			}
		} else {
			array_push($valdiasi, array("kode" => "4", "msg" => "CAPTION-ALERT-PERUSAHAANTIDAKMEMILIKIPRINCIPLE"));
		}

		// $valdiasi_uniq = array_unique($valdiasi);
		// rsort($valdiasi_uniq);
		// echo json_encode($valdiasi_uniq);
		echo json_encode($valdiasi);
		// echo json_encode($arr_do_detail2_temp);
	}
}
