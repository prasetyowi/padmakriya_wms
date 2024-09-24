<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class AdvanceShipmentNotice extends ParentController
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
		$this->MenuKode = "125001000";
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_AdvanceShipmentNotice', 'M_AdvanceShipmentNotice');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation', 'pdfgenerator']);
	}

	public function AdvanceShipmentNoticeMenu()
	{
		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$query['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css'
		);

		$query['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));
		$data['principle'] = $this->M_AdvanceShipmentNotice->GetDataPrinciple();

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/AdvanceShipmentNotice/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/AdvanceShipmentNotice/script', $query);
	}


	public function GetFileFTP()
	{
		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		date_default_timezone_set('Asia/Jakarta');
		$date_now = date("Y-m-d H:i:s");
		$principle_id = $this->input->post('principle_id');

		// upload FTP SUNTORY
		$ftp_server = "ftp.suntorygaruda.id"; // Address of FTP server.
		$ftp_user_name = "padma.live"; // Username
		$ftp_user_pass = 'SuntoryID!'; // Password

		// die("<span style='color:#FF0000'><h2>Couldn't connect to $ftp_server</h2></span>");        // set up basic connection
		// die("<span style='color:#FF0000'><h2>You do not have access to this ftp server!</h2></span>");   // login with username and password, or give invalid user message
		$conn_id = ftp_connect($ftp_server);

		if ($conn_id) {
			$login = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

			if ($login) {
				//dir path file
				$local_file = "assets/images/uploads/HasilGetFileFTP/asnsuntory/asn"; //where you want to throw the file on the webserver (relative to your login dir)
				$local_file_batch = "assets/images/uploads/HasilGetFileFTP/asnsuntory/batch"; //where you want to throw the file on the webserver (relative to your login dir)
				$destination_file = "/process/OUTBOUND/INVOICE";

				// liat isi direktori ftp dan local
				$dir = ftp_nlist($conn_id, "$destination_file");
				$cekFileLocal = scandir($local_file);
				$cekHasilLocal = array_diff($cekFileLocal, array(".", ".."));
				$cekFileLocal_batch = scandir($local_file_batch);
				$cekHasilLocal_batch = array_diff($cekFileLocal_batch, array(".", ".."));

				$arr_file = [];
				$arr_file_batch = [];
				foreach ($dir as $d) {
					$pecah_path = explode("/", $d);
					$pecah_nama = explode("_", $pecah_path[4]);

					if (count($pecah_nama) == 7) {
						array_push($arr_file, $pecah_path[4]);
					} else {
						array_push($arr_file_batch, $pecah_path[4]);
					}
				}

				$cekPerbandinganFile = array_diff($arr_file, $cekHasilLocal);
				$cekPerbandinganFile_batch = array_diff($arr_file_batch, $cekHasilLocal_batch);

				if (count($cekPerbandinganFile) <= 0 && count($cekPerbandinganFile_batch) <= 0) {
					$data['file'] = 'File Tidak Tersedia';
				} else {
					if (count($cekPerbandinganFile) > 0) {
						foreach ($cekPerbandinganFile as $c) {
							$pecah_name_file = explode("_", $c);
							$pecah_ext = explode(".", $pecah_name_file[6]);
							$get_tgl = $pecah_name_file[5];
							$get_jam = $pecah_ext[0];

							ftp_get($conn_id, "$local_file/$c", "$destination_file/$c",  FTP_BINARY);
							$readFile = file("$local_file/$c");

							$no = 0;
							$generate_kode = '';
							foreach ($readFile as $key => $r) {
								if ($key != 0) {
									$arr_r = explode("|", $r);

									$delivery_order_number = $arr_r[0] != "" ? $arr_r[0] : null;
									$delivery_order_line_number = $arr_r[1] != "" ? $arr_r[1] : null;
									$delivery_date = $arr_r[2] != "" ? $arr_r[2] : null;
									$armada_number = $arr_r[3] != "" ? $arr_r[3] : null;
									$expedition_name = $arr_r[4] != "" ? $arr_r[4] : null;
									$po_number = $arr_r[5] != "" ? $arr_r[5] : null;
									$po_line = $arr_r[6] != "" ? $arr_r[6] : null;
									$load_id = $arr_r[7] != "" ? $arr_r[7] : null;
									$sales_order_number = $arr_r[8] != "" ? $arr_r[8] : null;
									$sales_order_line_number = $arr_r[9] != "" ? $arr_r[9] : null;
									$vendor_id = $arr_r[10] != "" ? $arr_r[10] : null;
									$warehouse_id_from = $arr_r[11] != "" ? $arr_r[11] : null;
									$organization_code_from = $arr_r[12] != "" ? $arr_r[12] : null;
									$warehouse_id = $arr_r[13] != "" ? $arr_r[13] : null;
									$customer_name = $arr_r[14] != "" ? $arr_r[14] : null;
									$customer_ship_to = $arr_r[15] != "" ? $arr_r[15] : null;
									$item_id = $arr_r[16] != "" ? $arr_r[16] : null;
									$item_description = $arr_r[17] != "" ? $arr_r[17] : null;
									$transaction_quantity = $arr_r[18] != "" ? $arr_r[18] : null;
									$uom_code = $arr_r[19] != "" ? $arr_r[19] : null;
									$unit_price = $arr_r[20] != "" ? $arr_r[20] : null;
									$net_price = $arr_r[21] != "" ? $arr_r[21] : null;
									$extended_amount = $arr_r[22] != "" ? $arr_r[22] : null;
									$pricing_date = $arr_r[23] != "" ? $arr_r[23] : null;
									$creation_date = $arr_r[24] != "" ? $arr_r[24] : null;
									$creation_time = $arr_r[25] != "" ? $arr_r[25] : null;
									$created_by_name = $arr_r[26] != "" ? $arr_r[26] : null;
									$shipment_number = $arr_r[27] != "" ? trim($arr_r[27]) : null;

									$penerimaan_surat_jalan_temp_id = $this->M_Vrbl->Get_NewID();
									$penerimaan_surat_jalan_temp_id = $penerimaan_surat_jalan_temp_id[0]['NEW_ID'];

									$pt = $this->M_AdvanceShipmentNotice->GetClientPrinciple();
									$penerimaan_tipe_id = $this->M_AdvanceShipmentNotice->GetPenerimaanTipe();
									$depo_id = $this->M_AdvanceShipmentNotice->GetDepo($warehouse_id);

									if ($no == 0) {
										//generate kode
										$date_now = date('Y-m-d h:i:s');
										$param =  'KODE_SJ';
										$vrbl = $this->M_Vrbl->Get_Kode($param);
										$prefix = $vrbl->vrbl_kode;
										// get prefik depo
										$depoPrefix = $this->M_AdvanceShipmentNotice->getDepoPrefix($depo_id['depo_id']);
										$unit = $depoPrefix->depo_kode_preffix;
										$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

										$no++;
									}


									// Cek DO
									$do = $this->M_AdvanceShipmentNotice->CheckDO($delivery_order_number, $shipment_number);

									if ($do != 0) {
										if ($do['tgl_file'] > $get_tgl) {
											continue;
										} else if ($do['tgl_file'] < $get_tgl) {
											// delete by do dan shipment
											$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipment($delivery_order_number, $shipment_number);

											// cek transaction number
											$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

											if ($cek != 0) {
												if ($cek['trans_num'] == 9999) {
													$trans_num = 1;
												} else {
													$trans_num = $cek['trans_num'] + 1;
												}
											} else {
												$trans_num = 1;
											}

											$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
												$penerimaan_surat_jalan_temp_id,
												$depo_id['depo_id'],
												$pt['principle_id'],
												$pt['client_wms_id'],
												$penerimaan_tipe_id['penerimaan_tipe_id'],
												$trans_num,
												$date_now,
												$generate_kode,
												$delivery_order_number,
												$delivery_order_line_number,
												$delivery_date,
												$armada_number,
												$expedition_name,
												$po_number,
												$po_line,
												$load_id,
												$sales_order_number,
												$sales_order_line_number,
												$vendor_id,
												$warehouse_id_from,
												$organization_code_from,
												$warehouse_id,
												$customer_name,
												$customer_ship_to,
												$item_id,
												$item_description,
												$transaction_quantity,
												$uom_code,
												$unit_price,
												$net_price,
												$extended_amount,
												$pricing_date,
												$creation_date,
												$creation_time,
												$created_by_name,
												$shipment_number,
												$get_tgl,
												$get_jam
											);
										} else if ($do['tgl_file'] == $get_tgl) {
											if ($do['jam_file'] > $get_jam) {
												continue;
											} else if ($do['jam_file'] < $get_jam) {
												// delete by do dan shipment
												$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipment($delivery_order_number, $shipment_number);

												// cek transaction number
												$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

												if ($cek != 0) {
													if ($cek['trans_num'] == 9999) {
														$trans_num = 1;
													} else {
														$trans_num = $cek['trans_num'] + 1;
													}
												} else {
													$trans_num = 1;
												}

												$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
													$penerimaan_surat_jalan_temp_id,
													$depo_id['depo_id'],
													$pt['principle_id'],
													$pt['client_wms_id'],
													$penerimaan_tipe_id['penerimaan_tipe_id'],
													$trans_num,
													$date_now,
													$generate_kode,
													$delivery_order_number,
													$delivery_order_line_number,
													$delivery_date,
													$armada_number,
													$expedition_name,
													$po_number,
													$po_line,
													$load_id,
													$sales_order_number,
													$sales_order_line_number,
													$vendor_id,
													$warehouse_id_from,
													$organization_code_from,
													$warehouse_id,
													$customer_name,
													$customer_ship_to,
													$item_id,
													$item_description,
													$transaction_quantity,
													$uom_code,
													$unit_price,
													$net_price,
													$extended_amount,
													$pricing_date,
													$creation_date,
													$creation_time,
													$created_by_name,
													$shipment_number,
													$get_tgl,
													$get_jam
												);
											} else if ($do['jam_file'] == $get_jam) {
												if ($do['tgl_upload'] != $date_now) {

													// delete by do dan shipment
													$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipment($delivery_order_number, $shipment_number);

													// cek transaction number
													$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

													if ($cek != 0) {
														if ($cek['trans_num'] == 9999) {
															$trans_num = 1;
														} else {
															$trans_num = $cek['trans_num'] + 1;
														}
													} else {
														$trans_num = 1;
													}

													$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
														$penerimaan_surat_jalan_temp_id,
														$depo_id['depo_id'],
														$pt['principle_id'],
														$pt['client_wms_id'],
														$penerimaan_tipe_id['penerimaan_tipe_id'],
														$trans_num,
														$date_now,
														$generate_kode,
														$delivery_order_number,
														$delivery_order_line_number,
														$delivery_date,
														$armada_number,
														$expedition_name,
														$po_number,
														$po_line,
														$load_id,
														$sales_order_number,
														$sales_order_line_number,
														$vendor_id,
														$warehouse_id_from,
														$organization_code_from,
														$warehouse_id,
														$customer_name,
														$customer_ship_to,
														$item_id,
														$item_description,
														$transaction_quantity,
														$uom_code,
														$unit_price,
														$net_price,
														$extended_amount,
														$pricing_date,
														$creation_date,
														$creation_time,
														$created_by_name,
														$shipment_number,
														$get_tgl,
														$get_jam
													);
												} else {
													// cek transaction number
													$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

													if ($cek != 0) {
														if ($cek['trans_num'] == 9999) {
															$trans_num = 1;
														} else {
															$trans_num = $cek['trans_num'] + 1;
														}
													} else {
														$trans_num = 1;
													}

													$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
														$penerimaan_surat_jalan_temp_id,
														$depo_id['depo_id'],
														$pt['principle_id'],
														$pt['client_wms_id'],
														$penerimaan_tipe_id['penerimaan_tipe_id'],
														$trans_num,
														$date_now,
														$generate_kode,
														$delivery_order_number,
														$delivery_order_line_number,
														$delivery_date,
														$armada_number,
														$expedition_name,
														$po_number,
														$po_line,
														$load_id,
														$sales_order_number,
														$sales_order_line_number,
														$vendor_id,
														$warehouse_id_from,
														$organization_code_from,
														$warehouse_id,
														$customer_name,
														$customer_ship_to,
														$item_id,
														$item_description,
														$transaction_quantity,
														$uom_code,
														$unit_price,
														$net_price,
														$extended_amount,
														$pricing_date,
														$creation_date,
														$creation_time,
														$created_by_name,
														$shipment_number,
														$get_tgl,
														$get_jam
													);
												}
											}
										}
									} else {
										// cek transaction number
										$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

										if ($cek != 0) {
											if ($cek['trans_num'] == 9999) {
												$trans_num = 1;
											} else {
												$trans_num = $cek['trans_num'] + 1;
											}
										} else {
											$trans_num = 1;
										}

										$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
											$penerimaan_surat_jalan_temp_id,
											$depo_id['depo_id'],
											$pt['principle_id'],
											$pt['client_wms_id'],
											$penerimaan_tipe_id['penerimaan_tipe_id'],
											$trans_num,
											$date_now,
											$generate_kode,
											$delivery_order_number,
											$delivery_order_line_number,
											$delivery_date,
											$armada_number,
											$expedition_name,
											$po_number,
											$po_line,
											$load_id,
											$sales_order_number,
											$sales_order_line_number,
											$vendor_id,
											$warehouse_id_from,
											$organization_code_from,
											$warehouse_id,
											$customer_name,
											$customer_ship_to,
											$item_id,
											$item_description,
											$transaction_quantity,
											$uom_code,
											$unit_price,
											$net_price,
											$extended_amount,
											$pricing_date,
											$creation_date,
											$creation_time,
											$created_by_name,
											$shipment_number,
											$get_tgl,
											$get_jam
										);
									}
								}
							}
						}
					}

					if (count($cekPerbandinganFile_batch) > 0) {
						foreach ($cekPerbandinganFile_batch as $c) {
							$pecah_name_file_batch = explode("_", $c);
							// $pecah_ext_batch = explode(".", $pecah_name_file_batch[6]);

							$get_tgl_batch = $pecah_name_file_batch[5];
							$get_jam_batch = $pecah_name_file_batch[6];

							ftp_get($conn_id, "$local_file_batch/$c", "$destination_file/$c",  FTP_BINARY);
							$readFile = file("$local_file_batch/$c");

							foreach ($readFile as $key => $r) {
								if ($key != 0) {
									$arr_r = explode("|", $r);

									$shipment_number = $arr_r[0] != "" ? trim($arr_r[0]) : null;
									$delivery_number = $arr_r[1] != "" ? $arr_r[1] : null;
									$nomor_armada = $arr_r[2] != "" ? $arr_r[2] : null;
									$nama_expedisi = $arr_r[3] != "" ? $arr_r[3] : null;
									$material = $arr_r[4] != "" ? $arr_r[4] : null;
									$desciption = $arr_r[5] != "" ? $arr_r[5] : null;
									$quantity = $arr_r[6] != "" ? $arr_r[6] : null;
									$uom = $arr_r[7] != "" ? $arr_r[7] : null;
									$batch = $arr_r[8] != "" ? trim($arr_r[8]) : null;

									$penerimaan_surat_jalan_temp2_id = $this->M_Vrbl->Get_NewID();
									$penerimaan_surat_jalan_temp2_id = $penerimaan_surat_jalan_temp2_id[0]['NEW_ID'];

									$sku_exp = substr($batch, 0, 6);
									$sku_exp_date = DateTime::createFromFormat('dmy', $sku_exp);
									$sku_exp_date = $sku_exp_date->format('Y-m-d');

									// Cek DO Batch
									$do_batch = $this->M_AdvanceShipmentNotice->CheckDOBatch($delivery_number, $shipment_number);

									if ($do_batch != 0) {
										if ($do_batch['tgl_file'] > $get_tgl_batch) {
											continue;
										} else if ($do_batch['tgl_file'] < $get_tgl_batch) {
											// delete by do dan shipment batch
											$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipmentBatch($delivery_number, $shipment_number);

											$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
												$penerimaan_surat_jalan_temp2_id,
												$date_now,
												$shipment_number,
												$delivery_number,
												$nomor_armada,
												$nama_expedisi,
												$material,
												$desciption,
												$quantity,
												$uom,
												$batch,
												$get_tgl_batch,
												$get_jam_batch,
												$sku_exp_date
											);
										} else if ($do_batch['tgl_file'] == $get_tgl_batch) {
											if ($do_batch['jam_file'] > $get_jam_batch) {
												continue;
											} else if ($do_batch['jam_file'] < $get_jam_batch) {
												// delete by do dan shipment batch
												$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipmentBatch($delivery_number, $shipment_number);

												$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
													$penerimaan_surat_jalan_temp2_id,
													$date_now,
													$shipment_number,
													$delivery_number,
													$nomor_armada,
													$nama_expedisi,
													$material,
													$desciption,
													$quantity,
													$uom,
													$batch,
													$get_tgl_batch,
													$get_jam_batch,
													$sku_exp_date
												);
											} else if ($do_batch['jam_file'] == $get_jam_batch) {
												if ($do_batch['tgl_upload'] != $date_now) {
													// delete by do dan shipment batch
													$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipmentBatch($delivery_number, $shipment_number);

													$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
														$penerimaan_surat_jalan_temp2_id,
														$date_now,
														$shipment_number,
														$delivery_number,
														$nomor_armada,
														$nama_expedisi,
														$material,
														$desciption,
														$quantity,
														$uom,
														$batch,
														$get_tgl_batch,
														$get_jam_batch,
														$sku_exp_date
													);
												} else {
													$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
														$penerimaan_surat_jalan_temp2_id,
														$date_now,
														$shipment_number,
														$delivery_number,
														$nomor_armada,
														$nama_expedisi,
														$material,
														$desciption,
														$quantity,
														$uom,
														$batch,
														$get_tgl_batch,
														$get_jam_batch,
														$sku_exp_date
													);
												}
											}
										}
									} else {
										$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
											$penerimaan_surat_jalan_temp2_id,
											$date_now,
											$shipment_number,
											$delivery_number,
											$nomor_armada,
											$nama_expedisi,
											$material,
											$desciption,
											$quantity,
											$uom,
											$batch,
											$get_tgl_batch,
											$get_jam_batch,
											$sku_exp_date
										);
									}
								}
							}
						}
					}
				}

				$data['penerimaan'] = $this->M_AdvanceShipmentNotice->GetPenerimaanSuratJalanTemp($principle_id);

				echo json_encode($data);
			} else {
				$response = [
					"type" => 202,
					"message" => "Anda tidak memiliki akses ke server ftp ini!"
				];

				echo json_encode($response);
			}
		} else {
			$response = [
				"type" => 201,
				"message" => "Tidak bisa terhubung $ftp_server"
			];

			echo json_encode($response);
		}
	}

	public function GetFileFTPAuto()
	{
		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		date_default_timezone_set('Asia/Jakarta');
		$date_now = date("Y-m-d H:i:s");
		$principle_id = '7784BF34-C567-4D3D-8560-D8DEBF695C15';

		// upload FTP SUNTORY
		$ftp_server = "ftp.suntorygaruda.id"; // Address of FTP server.
		$ftp_user_name = "padma.live"; // Username
		$ftp_user_pass = 'SuntoryID!'; // Password

		$conn_id = ftp_connect($ftp_server) or die("<span style='color:#FF0000'><h2>Couldn't connect to $ftp_server</h2></span>");        // set up basic connection
		ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<span style='color:#FF0000'><h2>You do not have access to this ftp server!</h2></span>");   // login with username and password, or give invalid user message

		//dir path file
		$local_file = "assets/images/uploads/HasilGetFileFTP/asnsuntory/asn"; //where you want to throw the file on the webserver (relative to your login dir)
		$local_file_batch = "assets/images/uploads/HasilGetFileFTP/asnsuntory/batch"; //where you want to throw the file on the webserver (relative to your login dir)
		$destination_file = "/process/OUTBOUND/INVOICE";

		// liat isi direktori ftp dan local
		$dir = ftp_nlist($conn_id, "$destination_file");
		$cekFileLocal = scandir($local_file);
		$cekHasilLocal = array_diff($cekFileLocal, array(".", ".."));
		$cekFileLocal_batch = scandir($local_file_batch);
		$cekHasilLocal_batch = array_diff($cekFileLocal_batch, array(".", ".."));

		$arr_file = [];
		$arr_file_batch = [];
		foreach ($dir as $d) {
			$pecah_path = explode("/", $d);
			$pecah_nama = explode("_", $pecah_path[4]);

			if (count($pecah_nama) == 7) {
				array_push($arr_file, $pecah_path[4]);
			} else {
				array_push($arr_file_batch, $pecah_path[4]);
			}
		}

		$cekPerbandinganFile = array_diff($arr_file, $cekHasilLocal);
		$cekPerbandinganFile_batch = array_diff($arr_file_batch, $cekHasilLocal_batch);

		if (count($cekPerbandinganFile) <= 0 && count($cekPerbandinganFile_batch) <= 0) {
			$data['file'] = 'File Tidak Tersedia';
		} else {
			if (count($cekPerbandinganFile) > 0) {
				foreach ($cekPerbandinganFile as $c) {
					$pecah_name_file = explode("_", $c);
					$pecah_ext = explode(".", $pecah_name_file[6]);
					$get_tgl = $pecah_name_file[5];
					$get_jam = $pecah_ext[0];

					ftp_get($conn_id, "$local_file/$c", "$destination_file/$c",  FTP_BINARY);
					$readFile = file("$local_file/$c");

					$no = 0;
					$generate_kode = '';
					foreach ($readFile as $key => $r) {
						if ($key != 0) {
							$arr_r = explode("|", $r);

							$delivery_order_number = $arr_r[0] != "" ? $arr_r[0] : null;
							$delivery_order_line_number = $arr_r[1] != "" ? $arr_r[1] : null;
							$delivery_date = $arr_r[2] != "" ? $arr_r[2] : null;
							$armada_number = $arr_r[3] != "" ? $arr_r[3] : null;
							$expedition_name = $arr_r[4] != "" ? $arr_r[4] : null;
							$po_number = $arr_r[5] != "" ? $arr_r[5] : null;
							$po_line = $arr_r[6] != "" ? $arr_r[6] : null;
							$load_id = $arr_r[7] != "" ? $arr_r[7] : null;
							$sales_order_number = $arr_r[8] != "" ? $arr_r[8] : null;
							$sales_order_line_number = $arr_r[9] != "" ? $arr_r[9] : null;
							$vendor_id = $arr_r[10] != "" ? $arr_r[10] : null;
							$warehouse_id_from = $arr_r[11] != "" ? $arr_r[11] : null;
							$organization_code_from = $arr_r[12] != "" ? $arr_r[12] : null;
							$warehouse_id = $arr_r[13] != "" ? $arr_r[13] : null;
							$customer_name = $arr_r[14] != "" ? $arr_r[14] : null;
							$customer_ship_to = $arr_r[15] != "" ? $arr_r[15] : null;
							$item_id = $arr_r[16] != "" ? $arr_r[16] : null;
							$item_description = $arr_r[17] != "" ? $arr_r[17] : null;
							$transaction_quantity = $arr_r[18] != "" ? $arr_r[18] : null;
							$uom_code = $arr_r[19] != "" ? $arr_r[19] : null;
							$unit_price = $arr_r[20] != "" ? $arr_r[20] : null;
							$net_price = $arr_r[21] != "" ? $arr_r[21] : null;
							$extended_amount = $arr_r[22] != "" ? $arr_r[22] : null;
							$pricing_date = $arr_r[23] != "" ? $arr_r[23] : null;
							$creation_date = $arr_r[24] != "" ? $arr_r[24] : null;
							$creation_time = $arr_r[25] != "" ? $arr_r[25] : null;
							$created_by_name = $arr_r[26] != "" ? $arr_r[26] : null;
							$shipment_number = $arr_r[27] != "" ? trim($arr_r[27]) : null;

							$penerimaan_surat_jalan_temp_id = $this->M_Vrbl->Get_NewID();
							$penerimaan_surat_jalan_temp_id = $penerimaan_surat_jalan_temp_id[0]['NEW_ID'];

							$pt = $this->M_AdvanceShipmentNotice->GetClientPrinciple();
							$penerimaan_tipe_id = $this->M_AdvanceShipmentNotice->GetPenerimaanTipe();
							$depo_id = $this->M_AdvanceShipmentNotice->GetDepo($warehouse_id);

							if ($no == 0) {
								//generate kode
								$date_now = date('Y-m-d h:i:s');
								$param =  'KODE_SJ';
								$vrbl = $this->M_Vrbl->Get_Kode($param);
								$prefix = $vrbl->vrbl_kode;
								// get prefik depo
								$depoPrefix = $this->M_AdvanceShipmentNotice->getDepoPrefix($depo_id['depo_id']);
								$unit = $depoPrefix->depo_kode_preffix;
								$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

								$no++;
							}


							// Cek DO
							$do = $this->M_AdvanceShipmentNotice->CheckDO($delivery_order_number, $shipment_number);

							if ($do != 0) {
								if ($do['tgl_file'] > $get_tgl) {
									continue;
								} else if ($do['tgl_file'] < $get_tgl) {
									// delete by do dan shipment
									$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipment($delivery_order_number, $shipment_number);

									// cek transaction number
									$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

									if ($cek != 0) {
										if ($cek['trans_num'] == 9999) {
											$trans_num = 1;
										} else {
											$trans_num = $cek['trans_num'] + 1;
										}
									} else {
										$trans_num = 1;
									}

									$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
										$penerimaan_surat_jalan_temp_id,
										$depo_id['depo_id'],
										$pt['principle_id'],
										$pt['client_wms_id'],
										$penerimaan_tipe_id['penerimaan_tipe_id'],
										$trans_num,
										$date_now,
										$generate_kode,
										$delivery_order_number,
										$delivery_order_line_number,
										$delivery_date,
										$armada_number,
										$expedition_name,
										$po_number,
										$po_line,
										$load_id,
										$sales_order_number,
										$sales_order_line_number,
										$vendor_id,
										$warehouse_id_from,
										$organization_code_from,
										$warehouse_id,
										$customer_name,
										$customer_ship_to,
										$item_id,
										$item_description,
										$transaction_quantity,
										$uom_code,
										$unit_price,
										$net_price,
										$extended_amount,
										$pricing_date,
										$creation_date,
										$creation_time,
										$created_by_name,
										$shipment_number,
										$get_tgl,
										$get_jam
									);
								} else if ($do['tgl_file'] == $get_tgl) {
									if ($do['jam_file'] > $get_jam) {
										continue;
									} else if ($do['jam_file'] < $get_jam) {
										// delete by do dan shipment
										$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipment($delivery_order_number, $shipment_number);

										// cek transaction number
										$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

										if ($cek != 0) {
											if ($cek['trans_num'] == 9999) {
												$trans_num = 1;
											} else {
												$trans_num = $cek['trans_num'] + 1;
											}
										} else {
											$trans_num = 1;
										}

										$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
											$penerimaan_surat_jalan_temp_id,
											$depo_id['depo_id'],
											$pt['principle_id'],
											$pt['client_wms_id'],
											$penerimaan_tipe_id['penerimaan_tipe_id'],
											$trans_num,
											$date_now,
											$generate_kode,
											$delivery_order_number,
											$delivery_order_line_number,
											$delivery_date,
											$armada_number,
											$expedition_name,
											$po_number,
											$po_line,
											$load_id,
											$sales_order_number,
											$sales_order_line_number,
											$vendor_id,
											$warehouse_id_from,
											$organization_code_from,
											$warehouse_id,
											$customer_name,
											$customer_ship_to,
											$item_id,
											$item_description,
											$transaction_quantity,
											$uom_code,
											$unit_price,
											$net_price,
											$extended_amount,
											$pricing_date,
											$creation_date,
											$creation_time,
											$created_by_name,
											$shipment_number,
											$get_tgl,
											$get_jam
										);
									} else if ($do['jam_file'] == $get_jam) {
										if ($do['tgl_upload'] != $date_now) {

											// delete by do dan shipment
											$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipment($delivery_order_number, $shipment_number);

											// cek transaction number
											$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

											if ($cek != 0) {
												if ($cek['trans_num'] == 9999) {
													$trans_num = 1;
												} else {
													$trans_num = $cek['trans_num'] + 1;
												}
											} else {
												$trans_num = 1;
											}

											$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
												$penerimaan_surat_jalan_temp_id,
												$depo_id['depo_id'],
												$pt['principle_id'],
												$pt['client_wms_id'],
												$penerimaan_tipe_id['penerimaan_tipe_id'],
												$trans_num,
												$date_now,
												$generate_kode,
												$delivery_order_number,
												$delivery_order_line_number,
												$delivery_date,
												$armada_number,
												$expedition_name,
												$po_number,
												$po_line,
												$load_id,
												$sales_order_number,
												$sales_order_line_number,
												$vendor_id,
												$warehouse_id_from,
												$organization_code_from,
												$warehouse_id,
												$customer_name,
												$customer_ship_to,
												$item_id,
												$item_description,
												$transaction_quantity,
												$uom_code,
												$unit_price,
												$net_price,
												$extended_amount,
												$pricing_date,
												$creation_date,
												$creation_time,
												$created_by_name,
												$shipment_number,
												$get_tgl,
												$get_jam
											);
										} else {
											// cek transaction number
											$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

											if ($cek != 0) {
												if ($cek['trans_num'] == 9999) {
													$trans_num = 1;
												} else {
													$trans_num = $cek['trans_num'] + 1;
												}
											} else {
												$trans_num = 1;
											}

											$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
												$penerimaan_surat_jalan_temp_id,
												$depo_id['depo_id'],
												$pt['principle_id'],
												$pt['client_wms_id'],
												$penerimaan_tipe_id['penerimaan_tipe_id'],
												$trans_num,
												$date_now,
												$generate_kode,
												$delivery_order_number,
												$delivery_order_line_number,
												$delivery_date,
												$armada_number,
												$expedition_name,
												$po_number,
												$po_line,
												$load_id,
												$sales_order_number,
												$sales_order_line_number,
												$vendor_id,
												$warehouse_id_from,
												$organization_code_from,
												$warehouse_id,
												$customer_name,
												$customer_ship_to,
												$item_id,
												$item_description,
												$transaction_quantity,
												$uom_code,
												$unit_price,
												$net_price,
												$extended_amount,
												$pricing_date,
												$creation_date,
												$creation_time,
												$created_by_name,
												$shipment_number,
												$get_tgl,
												$get_jam
											);
										}
									}
								}
							} else {
								// cek transaction number
								$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

								if ($cek != 0) {
									if ($cek['trans_num'] == 9999) {
										$trans_num = 1;
									} else {
										$trans_num = $cek['trans_num'] + 1;
									}
								} else {
									$trans_num = 1;
								}

								$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
									$penerimaan_surat_jalan_temp_id,
									$depo_id['depo_id'],
									$pt['principle_id'],
									$pt['client_wms_id'],
									$penerimaan_tipe_id['penerimaan_tipe_id'],
									$trans_num,
									$date_now,
									$generate_kode,
									$delivery_order_number,
									$delivery_order_line_number,
									$delivery_date,
									$armada_number,
									$expedition_name,
									$po_number,
									$po_line,
									$load_id,
									$sales_order_number,
									$sales_order_line_number,
									$vendor_id,
									$warehouse_id_from,
									$organization_code_from,
									$warehouse_id,
									$customer_name,
									$customer_ship_to,
									$item_id,
									$item_description,
									$transaction_quantity,
									$uom_code,
									$unit_price,
									$net_price,
									$extended_amount,
									$pricing_date,
									$creation_date,
									$creation_time,
									$created_by_name,
									$shipment_number,
									$get_tgl,
									$get_jam
								);
							}
						}
					}
				}
			}

			if (count($cekPerbandinganFile_batch) > 0) {
				foreach ($cekPerbandinganFile_batch as $c) {
					$pecah_name_file_batch = explode("_", $c);
					// $pecah_ext_batch = explode(".", $pecah_name_file_batch[6]);

					$get_tgl_batch = $pecah_name_file_batch[5];
					$get_jam_batch = $pecah_name_file_batch[6];

					ftp_get($conn_id, "$local_file_batch/$c", "$destination_file/$c",  FTP_BINARY);
					$readFile = file("$local_file_batch/$c");

					foreach ($readFile as $key => $r) {
						if ($key != 0) {
							$arr_r = explode("|", $r);

							$shipment_number = $arr_r[0] != "" ? trim($arr_r[0]) : null;
							$delivery_number = $arr_r[1] != "" ? $arr_r[1] : null;
							$nomor_armada = $arr_r[2] != "" ? $arr_r[2] : null;
							$nama_expedisi = $arr_r[3] != "" ? $arr_r[3] : null;
							$material = $arr_r[4] != "" ? $arr_r[4] : null;
							$desciption = $arr_r[5] != "" ? $arr_r[5] : null;
							$quantity = $arr_r[6] != "" ? $arr_r[6] : null;
							$uom = $arr_r[7] != "" ? $arr_r[7] : null;
							$batch = $arr_r[8] != "" ? trim($arr_r[8]) : null;

							$penerimaan_surat_jalan_temp2_id = $this->M_Vrbl->Get_NewID();
							$penerimaan_surat_jalan_temp2_id = $penerimaan_surat_jalan_temp2_id[0]['NEW_ID'];

							$sku_exp = substr($batch, 0, 6);
							$sku_exp_date = DateTime::createFromFormat('dmy', $sku_exp);
							$sku_exp_date = $sku_exp_date->format('Y-m-d');

							// Cek DO Batch
							$do_batch = $this->M_AdvanceShipmentNotice->CheckDOBatch($delivery_number, $shipment_number);

							if ($do_batch != 0) {
								if ($do_batch['tgl_file'] > $get_tgl_batch) {
									continue;
								} else if ($do_batch['tgl_file'] < $get_tgl_batch) {
									// delete by do dan shipment batch
									$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipmentBatch($delivery_number, $shipment_number);

									$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
										$penerimaan_surat_jalan_temp2_id,
										$date_now,
										$shipment_number,
										$delivery_number,
										$nomor_armada,
										$nama_expedisi,
										$material,
										$desciption,
										$quantity,
										$uom,
										$batch,
										$get_tgl_batch,
										$get_jam_batch,
										$sku_exp_date
									);
								} else if ($do_batch['tgl_file'] == $get_tgl_batch) {
									if ($do_batch['jam_file'] > $get_jam_batch) {
										continue;
									} else if ($do_batch['jam_file'] < $get_jam_batch) {
										// delete by do dan shipment batch
										$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipmentBatch($delivery_number, $shipment_number);

										$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
											$penerimaan_surat_jalan_temp2_id,
											$date_now,
											$shipment_number,
											$delivery_number,
											$nomor_armada,
											$nama_expedisi,
											$material,
											$desciption,
											$quantity,
											$uom,
											$batch,
											$get_tgl_batch,
											$get_jam_batch,
											$sku_exp_date
										);
									} else if ($do_batch['jam_file'] == $get_jam_batch) {
										if ($do_batch['tgl_upload'] != $date_now) {
											// delete by do dan shipment batch
											$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipmentBatch($delivery_number, $shipment_number);

											$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
												$penerimaan_surat_jalan_temp2_id,
												$date_now,
												$shipment_number,
												$delivery_number,
												$nomor_armada,
												$nama_expedisi,
												$material,
												$desciption,
												$quantity,
												$uom,
												$batch,
												$get_tgl_batch,
												$get_jam_batch,
												$sku_exp_date
											);
										} else {
											$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
												$penerimaan_surat_jalan_temp2_id,
												$date_now,
												$shipment_number,
												$delivery_number,
												$nomor_armada,
												$nama_expedisi,
												$material,
												$desciption,
												$quantity,
												$uom,
												$batch,
												$get_tgl_batch,
												$get_jam_batch,
												$sku_exp_date
											);
										}
									}
								}
							} else {
								$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
									$penerimaan_surat_jalan_temp2_id,
									$date_now,
									$shipment_number,
									$delivery_number,
									$nomor_armada,
									$nama_expedisi,
									$material,
									$desciption,
									$quantity,
									$uom,
									$batch,
									$get_tgl_batch,
									$get_jam_batch,
									$sku_exp_date
								);
							}
						}
					}
				}
			}
		}

		echo 1;;
	}

	public function GetDataFilter()
	{
		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$principle_id = $this->input->post('principle');
		// $tgl = explode(" - ", $this->input->post('tgl'));

		// $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		// $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$data = $this->M_AdvanceShipmentNotice->GetDataFilter($principle_id);

		echo json_encode($data);
	}

	// public function getLastUpdateTgl($shipment_number, $principle_id)
	// {
	// 	return $this->db->select("penerimaan_surat_jalan_tgl_update")->from("penerimaan_surat_jalan_temp")->where("shipment_number", $shipment_number)->where("principle_id", $principle_id)->row()->penerimaan_surat_jalan_tgl_update;
	// }

	public function InsertPenerimaanSuratJalan()
	{
		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		date_default_timezone_set('Asia/Jakarta');
		$date_now = date("Y-m-d H:i:s");

		$shipment_number = $this->input->post('shipment_number');
		$principle_id = $this->input->post('principle_id');
		$tglLastUpdate = $this->input->post('tglLastUpdate');

		$psj_temp = $this->M_AdvanceShipmentNotice->GetPSJByTempID($shipment_number, $principle_id);

		$arr_sku_baru = [];
		foreach ($psj_temp as $row) {
			if ($row['penerimaan_surat_jalan_tgl_update'] != $tglLastUpdate) {
				echo 201;
				return false;
			} else if ($row['depo_id'] == '') {
				$this->M_AdvanceShipmentNotice->UpdatePSJTemp($shipment_number, $principle_id, 'depo_id', $date_now,);
				echo 0;
				return false;
			} else if ($row['principle_id'] == '') {
				$this->M_AdvanceShipmentNotice->UpdatePSJTemp($shipment_number, $principle_id, 'principle_id', $date_now);
				echo 0;
				return false;
			} else if ($row['client_wms_id'] == '') {
				$this->M_AdvanceShipmentNotice->UpdatePSJTemp($shipment_number, $principle_id, 'client_wms_id', $date_now);
				echo 0;
				return false;
			} else if ($row['sku_kode'] == '') {
				$this->M_AdvanceShipmentNotice->UpdatePSJTemp($shipment_number, $principle_id, 'sku_kode', $date_now);
				echo 0;
				return false;
			} else {
				$sku_baru = $this->M_AdvanceShipmentNotice->GetSKUIDSuntory($row['principle_id'], $row['sku_kode'], 0);

				if ($sku_baru == 0) {
					array_push($arr_sku_baru, $row['sku_kode']);
				}
			}
		}

		if (count($arr_sku_baru) > 0) {
			$data = implode(', ', $arr_sku_baru);

			$this->M_AdvanceShipmentNotice->UpdatePSJTemp($shipment_number, $principle_id, $data, $date_now);
			echo 0;
			return false;
		} else {
			$this->db->trans_begin();

			$this->M_AdvanceShipmentNotice->UpdatePSJTemp($shipment_number, $principle_id, 'SUKSES', $date_now);

			//get 1 data psj_temp
			$PSJTempRow = $this->M_AdvanceShipmentNotice->GetPSJTempRow($shipment_number, $principle_id);

			// INSERT penerimaan surat jalan
			$penerimaan_surat_jalan_id = $this->M_Vrbl->Get_NewID();
			$penerimaan_surat_jalan_id = $penerimaan_surat_jalan_id[0]['NEW_ID'];

			//generate kode
			$param =  'KODE_SJ';
			$vrbl = $this->M_Vrbl->Get_Kode($param);
			$prefix = $vrbl->vrbl_kode;
			// get prefik depo
			$depoPrefix = $this->M_AdvanceShipmentNotice->getDepoPrefix($PSJTempRow['depo_id']);
			$unit = $depoPrefix->depo_kode_preffix;
			$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

			$insertPenerimaanSuratJalan = $this->M_AdvanceShipmentNotice->insertPenerimaanSuratJalan($PSJTempRow, $penerimaan_surat_jalan_id, $generate_kode, $date_now);
			// end INSERT penerimaan surat jalan

			//Get Data PSJ Temp 2
			$PSJTemp2 = $this->M_AdvanceShipmentNotice->GetPSJTemp2($shipment_number);

			// INSERT penerimaan surat jalan detail temp 2
			foreach ($PSJTemp2 as $row2) {
				$penerimaan_surat_jalan_no_sj = $row2['penerimaan_surat_jalan_no_sj'];
				$sku_kode = $row2['sku_kode'];
				$sku_nama_produk = $row2['sku_nama_produk'];
				$sku_jumlah_barang = $row2['sku_jumlah_barang'];
				$sku_exp_date = $row2['sku_exp_date'];
				$batch = $row2['batch'];

				$insertPSJDetailTemp2 = $this->M_AdvanceShipmentNotice->insertPSJDetailTemp2($penerimaan_surat_jalan_id, $shipment_number, $penerimaan_surat_jalan_no_sj, $sku_kode, $batch, $sku_nama_produk, $sku_jumlah_barang);
			}
			// end INSERT penerimaan surat jalan detail temp 2

			//Get Data PSJ Temp 2 SUM sku_jumlah_barang
			$PSJTemp2SUM = $this->M_AdvanceShipmentNotice->GetPSJTemp2SUM($shipment_number);

			// INSERT 3 tabel
			foreach ($PSJTemp2SUM as $row3) {
				$sku_kode = $row3['sku_kode'];
				$sku_jumlah_barang = $row3['sku_jumlah_barang'];
				$sku_exp_date = $row3['sku_exp_date'];
				$batch = $row3['batch'];

				$sku_id = $this->M_AdvanceShipmentNotice->GetSKUIDSuntory($principle_id, $sku_kode, $sku_jumlah_barang);

				// insert penerimaan surat jalan detail ori
				$insertPSJDetailOri = $this->M_AdvanceShipmentNotice->insertPSJDetailOri($penerimaan_surat_jalan_id, $sku_id['sku_id'], $PSJTempRow['penerimaan_tipe_id'], $sku_jumlah_barang, $sku_exp_date, $batch);
				// end insert penerimaan surat jalan detail ori

				// insert sku konversi temp
				$sku_konversi_temp_id = $this->M_Vrbl->Get_NewID();
				$sku_konversi_temp_id = $sku_konversi_temp_id[0]['NEW_ID'];

				$insertPSJSKUKonversiTemp =  $this->M_AdvanceShipmentNotice->insertPSJSKUKonversiTemp($sku_konversi_temp_id, $sku_id['sku_id'],  intval($sku_id['sku_konversi_faktor']), $sku_exp_date, $sku_jumlah_barang, $batch);
				// end insert sku konversi temp

				// procedure proses_konversi_sku
				$result = $this->M_AdvanceShipmentNotice->ExecProsesKonversiSKU($sku_konversi_temp_id);

				//insert penerimaan surat jalan detail
				foreach ($result as $value) {
					$sku_id = $value['sku_id'];
					$sku_satuan = $value['sku_satuan'];
					$hasil = $value['hasil'];
					$sku_expired_date = $value['sku_expired_date'];
					$batch_no = $value['batch_no'];

					$insertPSJDetail = $this->M_AdvanceShipmentNotice->insertPSJDetail($penerimaan_surat_jalan_id, $sku_id, $hasil, $sku_expired_date, $batch_no);
				}
				// end insert penerimaan surat jalan detail
			}
			// end INSERT 3 tabel

			if ($this->db->trans_status() == FALSE) {
				$this->db->trans_rollback();
				echo 0;
			} else {
				$this->db->trans_commit();
				echo 1;
			}
		}
	}

	public function GetListDOByShipment()
	{
		$shipment_number = $this->input->post('shipment_number');
		$principle_id = $this->input->post('principle_id');

		$data = $this->M_AdvanceShipmentNotice->GetListDOByShipment($shipment_number, $principle_id);

		echo json_encode($data);
	}

	public function ImportASN()
	{
		$files = $_FILES['files'];
		$principle_id = $this->input->post('principle_id');

		$this->db->trans_begin();

		foreach ($files['tmp_name'] as $index => $tmp_name) {

			$fileName = $files['name'][$index];
			$pecah_name_file = explode("_", $fileName);
			$pecah_ext = explode(".", $pecah_name_file[6]);
			$get_tgl = $pecah_name_file[5];
			$get_jam = $pecah_ext[0];

			$fileContent = file($tmp_name);

			$no = 0;
			$generate_kode = '';
			foreach ($fileContent as $key => $value) {
				if ($key != 0) {
					$exp = explode("|", $value);

					$delivery_order_number = $exp[0] != "" ? $exp[0] : null;
					$delivery_order_line_number = $exp[1] != "" ? $exp[1] : null;
					$delivery_date = $exp[2] != "" ? $exp[2] : null;
					$armada_number = $exp[3] != "" ? $exp[3] : null;
					$expedition_name = $exp[4] != "" ? $exp[4] : null;
					$po_number = $exp[5] != "" ? $exp[5] : null;
					$po_line = $exp[6] != "" ? $exp[6] : null;
					$load_id = $exp[7] != "" ? $exp[7] : null;
					$sales_order_number = $exp[8] != "" ? $exp[8] : null;
					$sales_order_line_number = $exp[9] != "" ? $exp[9] : null;
					$vendor_id = $exp[10] != "" ? $exp[10] : null;
					$warehouse_id_from = $exp[11] != "" ? $exp[11] : null;
					$organization_code_from = $exp[12] != "" ? $exp[12] : null;
					$warehouse_id = $exp[13] != "" ? $exp[13] : null;
					$customer_name = $exp[14] != "" ? $exp[14] : null;
					$customer_ship_to = $exp[15] != "" ? $exp[15] : null;
					$item_id = $exp[16] != "" ? $exp[16] : null;
					$item_description = $exp[17] != "" ? $exp[17] : null;
					$transaction_quantity = $exp[18] != "" ? $exp[18] : null;
					$uom_code = $exp[19] != "" ? $exp[19] : null;
					$unit_price = $exp[20] != "" ? $exp[20] : null;
					$net_price = $exp[21] != "" ? $exp[21] : null;
					$extended_amount = $exp[22] != "" ? $exp[22] : null;
					$pricing_date = $exp[23] != "" ? $exp[23] : null;
					$creation_date = $exp[24] != "" ? $exp[24] : null;
					$creation_time = $exp[25] != "" ? $exp[25] : null;
					$created_by_name = $exp[26] != "" ? $exp[26] : null;
					$shipment_number = $exp[27] != "" ? trim($exp[27]) : null;

					$penerimaan_surat_jalan_temp_id = $this->M_Vrbl->Get_NewID();
					$penerimaan_surat_jalan_temp_id = $penerimaan_surat_jalan_temp_id[0]['NEW_ID'];

					$pt = $this->M_AdvanceShipmentNotice->GetClientPrinciple();
					$penerimaan_tipe_id = $this->M_AdvanceShipmentNotice->GetPenerimaanTipe();
					$depo_id = $this->M_AdvanceShipmentNotice->GetDepo($warehouse_id);

					if ($no == 0) {
						//generate kode
						$date_now = date('Y-m-d h:i:s');
						$param =  'KODE_SJ';
						$vrbl = $this->M_Vrbl->Get_Kode($param);
						$prefix = $vrbl->vrbl_kode;
						// get prefik depo
						$depoPrefix = $this->M_AdvanceShipmentNotice->getDepoPrefix($depo_id['depo_id']);
						$unit = $depoPrefix->depo_kode_preffix;
						$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

						$no++;
					}

					// Cek DO
					$do = $this->M_AdvanceShipmentNotice->CheckDO($delivery_order_number, $shipment_number);

					if ($do != 0) {
						if ($do['tgl_file'] > $get_tgl) {
							continue;
						} else if ($do['tgl_file'] < $get_tgl) {
							// delete by do dan shipment
							$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipment($delivery_order_number, $shipment_number);

							// cek transaction number
							$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

							if ($cek != 0) {
								if ($cek['trans_num'] == 9999) {
									$trans_num = 1;
								} else {
									$trans_num = $cek['trans_num'] + 1;
								}
							} else {
								$trans_num = 1;
							}

							$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
								$penerimaan_surat_jalan_temp_id,
								$depo_id['depo_id'],
								$pt['principle_id'],
								$pt['client_wms_id'],
								$penerimaan_tipe_id['penerimaan_tipe_id'],
								$trans_num,
								$date_now,
								$generate_kode,
								$delivery_order_number,
								$delivery_order_line_number,
								$delivery_date,
								$armada_number,
								$expedition_name,
								$po_number,
								$po_line,
								$load_id,
								$sales_order_number,
								$sales_order_line_number,
								$vendor_id,
								$warehouse_id_from,
								$organization_code_from,
								$warehouse_id,
								$customer_name,
								$customer_ship_to,
								$item_id,
								$item_description,
								$transaction_quantity,
								$uom_code,
								$unit_price,
								$net_price,
								$extended_amount,
								$pricing_date,
								$creation_date,
								$creation_time,
								$created_by_name,
								$shipment_number,
								$get_tgl,
								$get_jam
							);
						} else if ($do['tgl_file'] == $get_tgl) {
							if ($do['jam_file'] > $get_jam) {
								continue;
							} else if ($do['jam_file'] < $get_jam) {
								// delete by do dan shipment
								$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipment($delivery_order_number, $shipment_number);

								// cek transaction number
								$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

								if ($cek != 0) {
									if ($cek['trans_num'] == 9999) {
										$trans_num = 1;
									} else {
										$trans_num = $cek['trans_num'] + 1;
									}
								} else {
									$trans_num = 1;
								}

								$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
									$penerimaan_surat_jalan_temp_id,
									$depo_id['depo_id'],
									$pt['principle_id'],
									$pt['client_wms_id'],
									$penerimaan_tipe_id['penerimaan_tipe_id'],
									$trans_num,
									$date_now,
									$generate_kode,
									$delivery_order_number,
									$delivery_order_line_number,
									$delivery_date,
									$armada_number,
									$expedition_name,
									$po_number,
									$po_line,
									$load_id,
									$sales_order_number,
									$sales_order_line_number,
									$vendor_id,
									$warehouse_id_from,
									$organization_code_from,
									$warehouse_id,
									$customer_name,
									$customer_ship_to,
									$item_id,
									$item_description,
									$transaction_quantity,
									$uom_code,
									$unit_price,
									$net_price,
									$extended_amount,
									$pricing_date,
									$creation_date,
									$creation_time,
									$created_by_name,
									$shipment_number,
									$get_tgl,
									$get_jam
								);
							} else if ($do['jam_file'] == $get_jam) {
								if ($do['tgl_upload'] != $date_now) {

									// delete by do dan shipment
									$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipment($delivery_order_number, $shipment_number);

									// cek transaction number
									$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

									if ($cek != 0) {
										if ($cek['trans_num'] == 9999) {
											$trans_num = 1;
										} else {
											$trans_num = $cek['trans_num'] + 1;
										}
									} else {
										$trans_num = 1;
									}

									$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
										$penerimaan_surat_jalan_temp_id,
										$depo_id['depo_id'],
										$pt['principle_id'],
										$pt['client_wms_id'],
										$penerimaan_tipe_id['penerimaan_tipe_id'],
										$trans_num,
										$date_now,
										$generate_kode,
										$delivery_order_number,
										$delivery_order_line_number,
										$delivery_date,
										$armada_number,
										$expedition_name,
										$po_number,
										$po_line,
										$load_id,
										$sales_order_number,
										$sales_order_line_number,
										$vendor_id,
										$warehouse_id_from,
										$organization_code_from,
										$warehouse_id,
										$customer_name,
										$customer_ship_to,
										$item_id,
										$item_description,
										$transaction_quantity,
										$uom_code,
										$unit_price,
										$net_price,
										$extended_amount,
										$pricing_date,
										$creation_date,
										$creation_time,
										$created_by_name,
										$shipment_number,
										$get_tgl,
										$get_jam
									);
								} else {
									// cek transaction number
									$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

									if ($cek != 0) {
										if ($cek['trans_num'] == 9999) {
											$trans_num = 1;
										} else {
											$trans_num = $cek['trans_num'] + 1;
										}
									} else {
										$trans_num = 1;
									}

									$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
										$penerimaan_surat_jalan_temp_id,
										$depo_id['depo_id'],
										$pt['principle_id'],
										$pt['client_wms_id'],
										$penerimaan_tipe_id['penerimaan_tipe_id'],
										$trans_num,
										$date_now,
										$generate_kode,
										$delivery_order_number,
										$delivery_order_line_number,
										$delivery_date,
										$armada_number,
										$expedition_name,
										$po_number,
										$po_line,
										$load_id,
										$sales_order_number,
										$sales_order_line_number,
										$vendor_id,
										$warehouse_id_from,
										$organization_code_from,
										$warehouse_id,
										$customer_name,
										$customer_ship_to,
										$item_id,
										$item_description,
										$transaction_quantity,
										$uom_code,
										$unit_price,
										$net_price,
										$extended_amount,
										$pricing_date,
										$creation_date,
										$creation_time,
										$created_by_name,
										$shipment_number,
										$get_tgl,
										$get_jam
									);
								}
							}
						}
					} else {
						// cek transaction number
						$cek = $this->M_AdvanceShipmentNotice->CheckTransNumber();

						if ($cek != 0) {
							if ($cek['trans_num'] == 9999) {
								$trans_num = 1;
							} else {
								$trans_num = $cek['trans_num'] + 1;
							}
						} else {
							$trans_num = 1;
						}

						$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp(
							$penerimaan_surat_jalan_temp_id,
							$depo_id['depo_id'],
							$pt['principle_id'],
							$pt['client_wms_id'],
							$penerimaan_tipe_id['penerimaan_tipe_id'],
							$trans_num,
							$date_now,
							$generate_kode,
							$delivery_order_number,
							$delivery_order_line_number,
							$delivery_date,
							$armada_number,
							$expedition_name,
							$po_number,
							$po_line,
							$load_id,
							$sales_order_number,
							$sales_order_line_number,
							$vendor_id,
							$warehouse_id_from,
							$organization_code_from,
							$warehouse_id,
							$customer_name,
							$customer_ship_to,
							$item_id,
							$item_description,
							$transaction_quantity,
							$uom_code,
							$unit_price,
							$net_price,
							$extended_amount,
							$pricing_date,
							$creation_date,
							$creation_time,
							$created_by_name,
							$shipment_number,
							$get_tgl,
							$get_jam
						);
					}
				}
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			$data['penerimaan'] = $this->M_AdvanceShipmentNotice->GetPenerimaanSuratJalanTemp($principle_id);
			echo json_encode($data);
		}
	}

	public function ImportBatch()
	{
		date_default_timezone_set('Asia/Jakarta');
		$date_now = date("Y-m-d H:i:s");
		$files = $_FILES['files'];
		$principle_id = $this->input->post('principle_id');

		$this->db->trans_begin();

		foreach ($files['tmp_name'] as $index => $tmp_name) {

			$fileName = $files['name'][$index];
			$pecah_name_file_batch = explode("_", $fileName);
			$get_tgl_batch = $pecah_name_file_batch[5];
			$get_jam_batch = $pecah_name_file_batch[6];

			$fileContent = file($tmp_name);

			foreach ($fileContent as $key => $value) {
				if ($key != 0) {
					$exp = explode("|", $value);

					$shipment_number = $exp[0] != "" ? trim($exp[0]) : null;
					$delivery_number = $exp[1] != "" ? $exp[1] : null;
					$nomor_armada = $exp[2] != "" ? $exp[2] : null;
					$nama_expedisi = $exp[3] != "" ? $exp[3] : null;
					$material = $exp[4] != "" ? $exp[4] : null;
					$desciption = $exp[5] != "" ? $exp[5] : null;
					$quantity = $exp[6] != "" ? $exp[6] : null;
					$uom = $exp[7] != "" ? $exp[7] : null;
					$batch = $exp[8] != "" ? trim($exp[8]) : null;

					$penerimaan_surat_jalan_temp2_id = $this->M_Vrbl->Get_NewID();
					$penerimaan_surat_jalan_temp2_id = $penerimaan_surat_jalan_temp2_id[0]['NEW_ID'];

					$sku_exp = substr($batch, 0, 6);
					$sku_exp_date = DateTime::createFromFormat('dmy', $sku_exp);
					$sku_exp_date = $sku_exp_date->format('Y-m-d');

					// Cek DO Batch
					$do_batch = $this->M_AdvanceShipmentNotice->CheckDOBatch($delivery_number, $shipment_number);

					if ($do_batch != 0) {
						if ($do_batch['tgl_file'] > $get_tgl_batch) {
							continue;
						} else if ($do_batch['tgl_file'] < $get_tgl_batch) {
							// delete by do dan shipment batch
							$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipmentBatch($delivery_number, $shipment_number);

							$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
								$penerimaan_surat_jalan_temp2_id,
								$date_now,
								$shipment_number,
								$delivery_number,
								$nomor_armada,
								$nama_expedisi,
								$material,
								$desciption,
								$quantity,
								$uom,
								$batch,
								$get_tgl_batch,
								$get_jam_batch,
								$sku_exp_date
							);
						} else if ($do_batch['tgl_file'] == $get_tgl_batch) {
							if ($do_batch['jam_file'] > $get_jam_batch) {
								continue;
							} else if ($do_batch['jam_file'] < $get_jam_batch) {
								// delete by do dan shipment batch
								$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipmentBatch($delivery_number, $shipment_number);

								$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
									$penerimaan_surat_jalan_temp2_id,
									$date_now,
									$shipment_number,
									$delivery_number,
									$nomor_armada,
									$nama_expedisi,
									$material,
									$desciption,
									$quantity,
									$uom,
									$batch,
									$get_tgl_batch,
									$get_jam_batch,
									$sku_exp_date
								);
							} else if ($do_batch['jam_file'] == $get_jam_batch) {
								if ($do_batch['tgl_upload'] != $date_now) {
									// delete by do dan shipment batch
									$delete = $this->M_AdvanceShipmentNotice->DeleteDOShipmentBatch($delivery_number, $shipment_number);

									$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
										$penerimaan_surat_jalan_temp2_id,
										$date_now,
										$shipment_number,
										$delivery_number,
										$nomor_armada,
										$nama_expedisi,
										$material,
										$desciption,
										$quantity,
										$uom,
										$batch,
										$get_tgl_batch,
										$get_jam_batch,
										$sku_exp_date
									);
								} else {
									$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
										$penerimaan_surat_jalan_temp2_id,
										$date_now,
										$shipment_number,
										$delivery_number,
										$nomor_armada,
										$nama_expedisi,
										$material,
										$desciption,
										$quantity,
										$uom,
										$batch,
										$get_tgl_batch,
										$get_jam_batch,
										$sku_exp_date
									);
								}
							}
						}
					} else {
						$insert = $this->M_AdvanceShipmentNotice->insert_penerimaan_surat_jalan_temp2(
							$penerimaan_surat_jalan_temp2_id,
							$date_now,
							$shipment_number,
							$delivery_number,
							$nomor_armada,
							$nama_expedisi,
							$material,
							$desciption,
							$quantity,
							$uom,
							$batch,
							$get_tgl_batch,
							$get_jam_batch,
							$sku_exp_date
						);
					}
				}
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			$data['penerimaan'] = $this->M_AdvanceShipmentNotice->GetPenerimaanSuratJalanTemp($principle_id);
			echo json_encode($data);
		}
	}
}
