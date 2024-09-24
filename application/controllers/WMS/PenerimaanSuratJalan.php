<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class PenerimaanSuratJalan extends ParentController
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
		$this->MenuKode = "126002000";
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_PenerimaanSuratJalan', 'M_PenerimaanSuratJalan');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Depo');

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation']);
	}

	public function PenerimaanSuratJalanMenu()
	{
		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		// $data['tipe_penerimaan'] = $this->M_PenerimaanSuratJalan->getTipePenerimaan();
		$data['rangeYear'] = range(date('Y') - 10, date('Y') + 10);
		$data['rangeMonth'] = array(
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember',
		);

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
			Get_Assets_Url() . 'assets/js/dataTables.rowsGroup.js',

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
		$this->load->view('WMS/PenerimaanSuratJalan/PenerimaanSuratJalan', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PenerimaanSuratJalan/S_PenerimaanSuratJalan', $data);
	}

	public function get_data_perusahaan()
	{
		$data = $this->M_PenerimaanSuratJalan->getClientWms();
		echo json_encode($data);
	}

	public function getSecurityLogbook()
	{
		$data = $this->M_PenerimaanSuratJalan->getSecurityLogbook();
		echo json_encode($data);
	}

	public function generateSuratJalan()
	{
		$sl_id = $this->input->post('chk_sj');
		$data = $this->M_PenerimaanSuratJalan->generateSuratJalan($sl_id);

		echo json_encode($data);
	}

	public function get_data_surat_jalan()
	{
		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		$perusahaan = $this->input->post('perusahaan');
		$principle = $this->input->post('principle');
		$tipe_penerimaan = $this->input->post('tipe_penerimaan');
		$status = $this->input->post('status');

		$data = $this->M_PenerimaanSuratJalan->get_data_surat_jalan($tahun, $bulan, $perusahaan, $principle, $tipe_penerimaan, $status);
		echo json_encode($data);
	}

	public function get_data_detail_surat_jalan()
	{
		$id = $this->input->post('id');
		$data = $this->M_PenerimaanSuratJalan->get_data_detail_surat_jalan($id);
		echo json_encode($data);
	}

	public function get_data_principle_by_client_wms_id()
	{
		$id = $this->input->post('id');
		$data = $this->M_PenerimaanSuratJalan->get_data_principle_by_client_wms_id($id);
		echo json_encode($data);
	}

	public function get_data_principle_by_principle_id()
	{
		$id = $this->input->post('id');
		$data = $this->M_PenerimaanSuratJalan->get_data_principle_by_principle_id($id);
		echo json_encode($data);
	}

	public function get_data_sku_by_principle_id()
	{
		$client_wms_id = $this->input->post('client_wms_id');
		$id = $this->input->post('id');
		$data = $this->M_PenerimaanSuratJalan->get_data_sku_by_principle_id($client_wms_id, $id);
		echo json_encode($data);
	}

	public function get_sku_by_id()
	{
		$dataPost = $this->input->post('data');

		$arrSkuID = [];
		$result = [];
		foreach ($dataPost as $key => $value) {
			array_push($arrSkuID, $value['id']);
		}
		$data = $this->M_PenerimaanSuratJalan->get_sku_by_id($arrSkuID);
		foreach ($data as $key => $value) {
			foreach ($dataPost as $key => $val) {
				if ($value['sku_id'] == $val['id']) {
					array_push($result, [
						'sku_id' => $value['sku_id'],
						'sku_kode' => $value['sku_kode'],
						'sku_nama_produk' => $value['sku_nama_produk'],
						'sku_kemasan' => $value['sku_kemasan'],
						'sku_satuan' => $value['sku_satuan'],
						'sku_harga_jual' => $value['sku_harga_jual'],
						'qty' => $val['qty'],
					]);
				}
			}
		}
		echo json_encode($result);
	}

	public function get_tipe_penerimaan()
	{
		$result = $this->M_PenerimaanSuratJalan->getTipePenerimaan();
		echo json_encode($result);
	}

	public function get_tipe_penerimaan2()
	{
		$result = $this->M_PenerimaanSuratJalan->getTipePenerimaan2();
		echo json_encode($result);
	}

	public function generate()
	{
		$msg = [];

		$tgl 				= $this->input->post('tgl');
		$perusahaan 		= $this->input->post('perusahaan');
		$principle 			= $this->input->post('principle');
		$tipe_penerimaan 	= $this->input->post('tipe_penerimaan');
		$status 			= $this->input->post('status');
		$no_surat_jalan 	= $this->input->post('no_surat_jalan');
		$no_kendaraan 	= $this->input->post('no_kendaraan');

		$this->db->trans_begin();

		for ($i = 0; $i < count($no_surat_jalan); $i++) {
			$uploadDirectory 		= "assets/images/uploads/Surat-Jalan/";
			$fileExtensionsAllowed 	= ['jpeg', 'jpg', 'png', 'gif', 'JPG', 'JPEG', 'GIF', 'pdf', 'PDF', 'doc', 'DOC', 'docx', 'DOCX', 'xlsx', 'csv', 'xls', 'PNG']; // Allowed file extensions
			$fileName 				= $_FILES['file']['name'][$i];
			$fileSize 				= $_FILES['file']['size'][$i];
			$fileTmpName  			= $_FILES['file']['tmp_name'][$i];
			$fileType 				= $_FILES['file']['type'][$i];
			$file 					= explode(".", $fileName);
			$fileExtension 			= strtolower(end($file));
			$name_file 				= 'surat-jalan-' . time() . '.' . $fileExtension;
			$sj_id 					= $this->M_Vrbl->Get_NewID();
			$sj_id 					= $sj_id[0]['NEW_ID'];

			if (empty($fileName)) {
				$this->saveToDb2($sj_id, $principle[$i], $perusahaan[$i], $tipe_penerimaan[$i], $no_surat_jalan[$i], $tgl[$i], $status[$i], null, $no_kendaraan[$i]);
			} else {
				if ($fileSize > 1024000) {
					$msg = array('status' => false, 'message' => 'Data gagal disimpan! Ukuran file maks 1mb');
				} else {
					if (!in_array($fileExtension, $fileExtensionsAllowed)) {
						$msg = array('status' => false, 'message' => 'Data gagal disimpan! File Attactment tidak sesuai ketentuan');
					} else {
						$uploadPath = $uploadDirectory . $name_file;
						$didUpload = move_uploaded_file($fileTmpName, $uploadPath);
						if (!$didUpload) {
							$msg = array('status' => false, 'message' => 'Data gagal disimpan! Terjadi kesalahan pada server');
						} else {
							$this->saveToDb2($sj_id, $principle[$i], $perusahaan[$i], $tipe_penerimaan[$i], $no_surat_jalan[$i], $tgl[$i], $status[$i], $name_file, $no_kendaraan[$i]);
						}
					}
				}
			}
		}
		if (!empty($msg)) {
			echo json_encode($msg);
			die;
		}

		if ($this->db->trans_status() == FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan!'));
		} else {
			$this->db->trans_commit();
			echo json_encode(array('status' => true, 'message' => 'Data berhasil disimpan!'));
		}
	}

	private function saveToDb2($sj_id, $principle, $perusahaan, $tipe_penerimaan, $no_surat_jalan, $tgl, $status, $name_file, $no_kendaraan)
	{
		//generate kode
		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_SJ';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PenerimaanSuratJalan->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$result = $this->M_PenerimaanSuratJalan->generate($sj_id, $principle, $perusahaan, $tipe_penerimaan, $generate_kode, $no_surat_jalan, $tgl, $status, $name_file, $no_kendaraan);
		if (!$result) {
			return array('status' => false, 'message' => 'Data gagal disimpan!');
		} else {
			return array('status' => true, 'message' => 'Data berhasil disimpan!');
		}
	}

	public function save()
	{
		$tgl = $this->input->post('tgl');
		$perusahaan = $this->input->post('perusahaan');
		$principle = $this->input->post('principle');
		$tipe_penerimaan = $this->input->post('tipe_penerimaan');
		$status = $this->input->post('status');
		$no_surat_jalan = $this->input->post('no_surat_jalan');
		$no_surat_jalan_counter = $this->input->post('no_surat_jalan_counter');
		$no_kendaraan = $this->input->post('no_kendaraan');
		$keterangan = $this->input->post('keterangan');

		$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'trim|required', [
			'required' => 'Perusahaan tidak boleh kosong!'
		]);

		$this->form_validation->set_rules('principle', 'Principle', 'trim|required', [
			'required' => 'Principle tidak boleh kosong!'
		]);

		$this->form_validation->set_rules('tipe_penerimaan', 'Tipe Penerimaan', 'trim|required', [
			'required' => 'Tipe Penerimaan tidak boleh kosong!'
		]);

		$this->form_validation->set_rules('status', 'Status', 'trim|required', [
			'required' => 'Status tidak boleh kosong!'
		]);

		$this->form_validation->set_rules('no_surat_jalan', 'No Surat Jalan', 'trim|required', [
			'required' => 'No Surat Jalan tidak boleh kosong!'
		]);

		$this->form_validation->set_rules('no_kendaraan', 'No Kendaraan', 'trim|required', [
			'required' => 'No Kendaraan tidak boleh kosong!'
		]);

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo json_encode(array('status' => false, 'message' => validation_errors()));
		} else {
			$sj_id = $this->M_Vrbl->Get_NewID();
			$sj_id = $sj_id[0]['NEW_ID'];

			$uploadDirectory = "assets/images/uploads/Surat-Jalan/";
			$fileExtensionsAllowed = ['jpeg', 'jpg', 'png', 'gif', 'JPG', 'JPEG', 'GIF', 'pdf', 'PDF', 'doc', 'DOC', 'docx', 'DOCX', 'xlsx', 'csv', 'xls', 'PNG']; // Allowed file extensions
			$fileName = $_FILES['file']['name'];
			$fileSize = $_FILES['file']['size'];
			$fileTmpName  = $_FILES['file']['tmp_name'];
			$fileType = $_FILES['file']['type'];
			$file = explode(".", $fileName);
			$fileExtension = strtolower(end($file));
			$name_file = 'surat-jalan-' . time() . '.' . $fileExtension;

			if (empty($fileName)) {
				echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan! File Attactment tidak boleh kosong'));
			} else {
				if ($fileSize > 1024000) {
					echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan! Ukuran file maks 1mb'));
				} else {
					if (!in_array($fileExtension, $fileExtensionsAllowed)) {
						echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan! File Attactment tidak sesuai ketentuan'));
					} else {
						$uploadPath = $uploadDirectory . $name_file;
						$didUpload = move_uploaded_file($fileTmpName, $uploadPath);
						if (!$didUpload) {
							echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan! Terjadi kesalahan pada server'));
						} else {
							if ($no_surat_jalan_counter == "") {
								$check_duplicate_no_sj = $this->M_PenerimaanSuratJalan->check_duplicate_no_sj($perusahaan, $principle, $no_surat_jalan);
								if ($check_duplicate_no_sj > 0) {
									echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan! No. Surat Jalan telah digunakan'));
								} else {
									$this->saveToDb($sj_id, $principle, $perusahaan, $tipe_penerimaan, $no_surat_jalan, $tgl, $status, $keterangan, $name_file, $no_surat_jalan_counter, $no_kendaraan);
								}
							} else {
								$this->saveToDb($sj_id, $principle, $perusahaan, $tipe_penerimaan, $no_surat_jalan, $tgl, $status, $keterangan, $name_file, $no_surat_jalan_counter, $no_kendaraan);
							}
						}
					}
				}
			}
		}
	}

	private function saveToDb($sj_id, $principle, $perusahaan, $tipe_penerimaan, $no_surat_jalan, $tgl, $status, $keterangan, $name_file, $no_surat_jalan_counter, $no_kendaraan)
	{
		//generate kode
		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_SJ';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PenerimaanSuratJalan->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$result = $this->M_PenerimaanSuratJalan->save($sj_id, $principle, $perusahaan, $tipe_penerimaan, $generate_kode, $no_surat_jalan, $tgl, $status, $keterangan, $name_file, $no_surat_jalan_counter, $no_kendaraan);
		if (!$result) {
			echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan!'));
		} else {
			$data_detail = json_decode($this->input->post('data_detail'));
			foreach ($data_detail as $key => $value) {
				// $diskon = explode(".", $value->diskon);
				// $value_diskon = explode(".", $value->value_diskon);
				// $netto = explode(".", $value->netto);
				// $bruto = explode(".", $value->bruto);
				$this->M_PenerimaanSuratJalan->save_detail($sj_id, $value);
			}

			//procedure temp table konversi
			$datas = $this->db->query("Exec proses_konversi_sku '$sj_id'");

			foreach ($datas->result_array() as $key => $item) {
				$this->M_PenerimaanSuratJalan->save_detail_sj($sj_id, $item);
			}

			echo json_encode(array('status' => true, 'message' => 'Data berhasil disimpan!'));
		}
	}

	public function check_duplicate_no_sj_by_id()
	{
		$id = $this->input->post('id');
		$perusahaan = $this->input->post('perusahaan');
		$principle = $this->input->post('principle');
		$no_sj = $this->input->post('no_sj');

		$check_duplicate_no_sj = $this->M_PenerimaanSuratJalan->check_duplicate_no_sj_by_id($id, $perusahaan, $principle, $no_sj);
		if ($check_duplicate_no_sj > 0) {
			echo json_encode(array('status' => false, 'message' => 'No. Surat Jalan telah digunakan'));
		}
	}

	public function edit($id)
	{
		$data = array();
		$data['id'] = $id;
		$data['data'] = $this->M_PenerimaanSuratJalan->get_data_edit_surat_jalan_header($id);
		$data['data_detail'] = [];
		$data_detail =  $this->M_PenerimaanSuratJalan->get_data_edit_surat_jalan_detail($id);
		foreach ($data_detail as $key => $value) {
			$data['data_detail'][] = $value['tipe_id'];
		}

		// echo json_encode($data['data_detail']);
		// die;

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// $data['tipe_penerimaan'] = $this->M_PenerimaanSuratJalan->getTipePenerimaan();

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
		$this->load->view('WMS/PenerimaanSuratJalan/component/form_edit_surat_jalan/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PenerimaanSuratJalan/component/form_edit_surat_jalan/S_edit', $data);
	}

	public function view($id)
	{
		$data = array();
		$data['id'] = $id;
		$data['data'] = $this->M_PenerimaanSuratJalan->get_data_edit_surat_jalan_header($id);
		$data['data_detail'] = [];
		$data_detail =  $this->M_PenerimaanSuratJalan->get_data_edit_surat_jalan_detail($id);
		foreach ($data_detail as $key => $value) {
			$data['data_detail'][] = $value['tipe_id'];
		}

		// echo json_encode($data['data_detail']);
		// die;

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// $data['tipe_penerimaan'] = $this->M_PenerimaanSuratJalan->getTipePenerimaan();

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
		$this->load->view('WMS/PenerimaanSuratJalan/component/form_view/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PenerimaanSuratJalan/component/form_view/S_view', $data);
	}

	public function get_data_edit_surat_jalan_header()
	{
		$id = $this->input->post('id');
		$data = $this->M_PenerimaanSuratJalan->get_data_edit_surat_jalan_header($id);
		echo json_encode($data);
	}

	public function get_data_edit_surat_jalan_detail()
	{
		$id = $this->input->post('id');
		$data = $this->M_PenerimaanSuratJalan->get_data_edit_surat_jalan_detail($id);
		echo json_encode($data);
	}

	public function get_data_principle_by_client_wms_id_edit()
	{
		$id = $this->input->post('id');
		$principle_id = $this->input->post('principle_id');
		$data = $this->M_PenerimaanSuratJalan->get_data_principle_by_client_wms_id($id);
		$output = '<option value="">--Pilih Principle</option>';
		foreach ($data as $row) {
			if ($principle_id) {
				if ($principle_id == $row->id) {
					$output .= '<option value="' . $row->id . '" selected>' . $row->nama . '</option>';
				} else {
					$output .= '<option value="' . $row->id . '">' . $row->nama . '</option>';
				}
			} else {
				$output .= '<option value="' . $row->id . '">' . $row->nama . '</option>';
			}
			// $output .= '<option value="' . $row->id . '">' . $row->nama . '</option>';
		}
		// $this->output->set_content_type('application/json')->set_output(json_encode($output));
		echo json_encode($output);
	}

	public function delete_file_db()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanSuratJalan->delete_file_db($id);
		echo json_encode($result);
	}

	public function get_file_db_by_id()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanSuratJalan->get_file_db_by_id($id);
		echo json_encode($result);
	}

	public function update()
	{
		$id = $this->input->post('id');
		$tgl = $this->input->post('tgl');
		$perusahaan = $this->input->post('perusahaan');
		$principle = $this->input->post('principle');
		$tipe_penerimaan = $this->input->post('tipe_penerimaan');
		$status = $this->input->post('status');
		$no_surat_jalan = $this->input->post('no_surat_jalan');
		$no_surat_jalan_counter = $this->input->post('no_surat_jalan_counter');
		$no_kendaraan = $this->input->post('no_kendaraan');
		$keterangan = $this->input->post('keterangan');
		$lastUpdated = $this->input->post('lastUpdated');

		$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'trim|required', [
			'required' => 'Perusahaan tidak boleh kosong!'
		]);

		$this->form_validation->set_rules('principle', 'Principle', 'trim|required', [
			'required' => 'Principle tidak boleh kosong!'
		]);

		$this->form_validation->set_rules('tipe_penerimaan', 'Tipe Penerimaan', 'trim|required', [
			'required' => 'Tipe Penerimaan tidak boleh kosong!'
		]);

		$this->form_validation->set_rules('status', 'Status', 'trim|required', [
			'required' => 'Status tidak boleh kosong!'
		]);

		$this->form_validation->set_rules('no_surat_jalan', 'No Surat Jalan', 'trim|required', [
			'required' => 'No Surat Jalan tidak boleh kosong!'
		]);

		$this->form_validation->set_rules('no_kendaraan', 'No Kendaraan', 'trim|required', [
			'required' => 'No Surat Jalan tidak boleh kosong!'
		]);

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo json_encode(array('status' => false, 'message' => validation_errors()));
		} else {
			$file_db = $this->input->post('file_db');

			$errorNotSameLastUpdated = false;

			$getLastUpdatedDb = $this->db->select("penerimaan_surat_jalan_tgl_update")->from("penerimaan_surat_jalan")->where("penerimaan_surat_jalan_id", $id)->get()->row()->penerimaan_surat_jalan_tgl_update;

			if ($lastUpdated !== $getLastUpdatedDb) $errorNotSameLastUpdated = true;

			if ($errorNotSameLastUpdated) {
				echo json_encode(array('status' => 'not same updated'));
				return false;
			}

			if (!isset($file_db)) {
				$uploadDirectory = "assets/images/uploads/Surat-Jalan/";
				$fileExtensionsAllowed = ['jpeg', 'jpg', 'png', 'gif', 'JPG', 'JPEG', 'GIF', 'pdf', 'PDF', 'doc', 'DOC', 'docx', 'DOCX', 'xlsx', 'csv', 'xls', 'PNG']; // Allowed file extensions
				$fileName = $_FILES['file']['name'];
				$fileSize = $_FILES['file']['size'];
				$fileTmpName  = $_FILES['file']['tmp_name'];
				$fileType = $_FILES['file']['type'];
				$file = explode(".", $fileName);
				$fileExtension = strtolower(end($file));
				$name_file = 'surat-jalan-' . time() . '.' . $fileExtension;

				if (empty($fileName)) {
					echo json_encode(array('status' => false, 'message' => 'Data gagal diedit! File Attactment tidak boleh kosong'));
				} else {
					if ($fileSize > 1024000) {
						echo json_encode(array('status' => false, 'message' => 'Data gagal diedit! Ukuran file maks 1mb'));
					} else {
						if (!in_array($fileExtension, $fileExtensionsAllowed)) {
							echo json_encode(array('status' => false, 'message' => 'Data gagal diedit! File Attactment tidak sesuai ketentuan'));
						} else {
							$uploadPath = $uploadDirectory . $name_file;
							//delete file di folder dan db
							$delete_file = $this->M_PenerimaanSuratJalan->delete_file_db($id);
							if ($delete_file == 1) {
								$didUpload = move_uploaded_file($fileTmpName, $uploadPath);
								if (!$didUpload) {
									echo json_encode(array('status' => false, 'message' => 'Data gagal diedit! Terjadi kesalahan pada server'));
								} else {

									if ($no_surat_jalan_counter == "") {
										$check_duplicate_no_sj = $this->M_PenerimaanSuratJalan->check_duplicate_no_sj_by_id($id, $perusahaan, $principle, $no_surat_jalan);
										if ($check_duplicate_no_sj > 0) {
											echo json_encode(array('status' => false, 'message' => 'Data gagal diedit! No. Surat Jalan telah digunakan'));
										} else {
											$this->editToDb($id, $principle, $perusahaan, $tipe_penerimaan, $no_surat_jalan, $tgl, $status, $keterangan, $name_file, $no_surat_jalan_counter, $no_kendaraan);
										}
									} else {
										$this->editToDb($id, $principle, $perusahaan, $tipe_penerimaan, $no_surat_jalan, $tgl, $status, $keterangan, $name_file, $no_surat_jalan_counter, $no_kendaraan);
									}
								}
							} else {
								echo json_encode(array('status' => false, 'message' => 'Data gagal diedit! Terjadi kesalahan pada server'));
							}
						}
					}
				}
			} else {

				if ($no_surat_jalan_counter == "") {
					$check_duplicate_no_sj = $this->M_PenerimaanSuratJalan->check_duplicate_no_sj_by_id($id, $perusahaan, $principle, $no_surat_jalan);
					if ($check_duplicate_no_sj > 0) {
						echo json_encode(array('status' => false, 'message' => 'Data gagal diedit! No. Surat Jalan telah digunakan'));
					} else {
						$this->editToDb($id, $principle, $perusahaan, $tipe_penerimaan, $no_surat_jalan, $tgl, $status, $keterangan, null, $no_surat_jalan_counter, $no_kendaraan);
					}
				} else {
					$this->editToDb($id, $principle, $perusahaan, $tipe_penerimaan, $no_surat_jalan, $tgl, $status, $keterangan, null, $no_surat_jalan_counter, $no_kendaraan);
				}
			}
		}
	}

	private function editToDb($id, $principle, $perusahaan, $tipe_penerimaan, $no_surat_jalan, $tgl, $status, $keterangan, $name_file, $no_surat_jalan_counter, $no_kendaraan)
	{
		if ($name_file != null) {
			$result = $this->M_PenerimaanSuratJalan->update($id, $principle, $perusahaan, $tipe_penerimaan, $no_surat_jalan, $tgl, $status, $keterangan, $name_file, $no_surat_jalan_counter, $no_kendaraan);
		} else {
			$result = $this->M_PenerimaanSuratJalan->update2($id, $principle, $perusahaan, $tipe_penerimaan, $no_surat_jalan, $tgl, $status, $keterangan, $no_surat_jalan_counter, $no_kendaraan);
		}

		if (!$result) {
			echo json_encode(array('status' => false, 'message' => 'Data gagal diedit!'));
		} else {
			$data_detail = json_decode($this->input->post('data_detail'));


			//delete detail 2 ori
			$this->db->where("penerimaan_surat_jalan_id", $id);
			$delete2 = $this->db->delete("penerimaan_surat_jalan_detail_ori");
			if ($delete2) {
				foreach ($data_detail as $key => $value) {
					$this->M_PenerimaanSuratJalan->update_detail($id, $value);
				}
			}

			$this->db->where("penerimaan_surat_jalan_id", $id);
			$this->db->delete("penerimaan_surat_jalan_detail");

			//procedure temp table konversi
			$datas = $this->db->query("Exec proses_konversi_sku '$id'");

			foreach ($datas->result_array() as $key => $item) {
				$this->M_PenerimaanSuratJalan->save_detail_sj($id, $item);
			}

			echo json_encode(array('status' => true, 'message' => 'Data berhasil diedit!'));
		}
	}

	public function cancel_surat_jalan()
	{
		$id = $this->input->post('id');
		$reason = $this->input->post('reason');
		$result = $this->M_PenerimaanSuratJalan->cancel_surat_jalan($id, $reason);
		echo json_encode($result);
	}

	public function checkMinimunExpiredDate()
	{
		$result = [];
		$arrSkuId = [];
		$dataPost = $this->input->post('data');

		foreach ($dataPost as $key => $value) {
			array_push($arrSkuId, $value['id']);
		}

		$getMinimumED = $this->db->select("sku_id, sku_minimum_expired_date")->from("sku")->where_in('sku_id', $arrSkuId)->get()->result();

		foreach ($getMinimumED as $key => $value) {
			$tgl = date('Y-m', strtotime('+' . $value->sku_minimum_expired_date . ' month'));
			array_push($result, ['sku_id' => $value->sku_id, 'date' => $tgl . '-01']);
		}

		echo json_encode($result);
	}

	public function edit_reason($id)
	{
		$data = array();
		$data['id'] = $id;
		$data['data'] = $this->M_PenerimaanSuratJalan->get_data_edit_surat_jalan_header($id);

		// echo json_encode($data_detail);
		// die;

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// $data['tipe_penerimaan'] = $this->M_PenerimaanSuratJalan->getTipePenerimaan();

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
		$this->load->view('WMS/PenerimaanSuratJalan/component/form_edit_reason/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PenerimaanSuratJalan/component/form_edit_reason/S_view', $data);
	}

	public function getDataDetailSuratjalanKonversi()
	{
		$id = $this->input->post('id');
		$data = $this->M_PenerimaanSuratJalan->getDataDetailSuratjalanKonversi($id);
		echo json_encode($data);
	}

	public function getDatareason()
	{
		$data = $this->M_PenerimaanSuratJalan->getDatareason();
		echo json_encode($data);
	}
	public function getDataReasonDetail()
	{
		$id = $this->input->post('id');
		$reasonDetailId = $this->input->post('global_reason_detail_id');
		$data = $this->M_PenerimaanSuratJalan->getDataReasonDetail($id);
		$output = '<option value="">--Pilih Principle</option>';
		foreach ($data as $row) {
			if ($reasonDetailId) {
				if ($reasonDetailId == $row->reason_surat_jalan_detail_id) {
					$output .= '<option value="' . $row->reason_surat_jalan_detail_id . '" selected>' . $row->keterangan . '</option>';
				} else {
					$output .= '<option value="' . $row->reason_surat_jalan_detail_id . '">' . $row->keterangan . '</option>';
				}
			} else {
				$output .= '<option value="' . $row->reason_surat_jalan_detail_id . '">' . $row->keterangan . '</option>';
			}
		}
		echo json_encode($output);
	}

	public function updateReason()
	{
		$global_id = $this->input->post('global_id');
		$reason = $this->input->post('reason');
		$reasonDetail = $this->input->post('reasonDetail');
		$arrDetailSjd = $this->input->post('arrDetailSjd');

		$result = $this->M_PenerimaanSuratJalan->updateReason($global_id, $reason, $reasonDetail, $arrDetailSjd);
		echo json_encode($result);
	}

	public function getNoSuratJalanEksternal()
	{
		$input = $this->input->get('params');
		$result = $this->M_PenerimaanSuratJalan->getNoSuratJalanEksternal($input);
		echo json_encode($result);
	}

	public function getLastNoCounterSuratJalan()
	{
		$input = $this->input->get('params');
		$data = $this->M_PenerimaanSuratJalan->getLastNoCounterSuratJalan($input);
		$result = explode('-', $data->kode);


		if (count($result) == 1) {
			echo json_encode(['numberCounter' => $result[0] . "-" . "001"]);
		} else {
			$kode = (int)substr($result[1], -1, 3) + 1;
			$firsthalf = $result[0] . "-" . str_repeat('0', 3 - strlen((string)$kode)) . $kode;
			echo json_encode([
				'client_wms_id' => $data->client_wms_id,
				'principle_id' => $data->principle_id,
				'numberCounter' => $firsthalf
			]);
		}
	}
}
