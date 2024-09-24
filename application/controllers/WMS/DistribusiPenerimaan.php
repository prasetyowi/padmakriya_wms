<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentController.php';

class DistribusiPenerimaan extends CI_Controller
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
		$this->MenuKode = "126004000";
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_DistribusiPenerimaan', 'M_DistribusiPenerimaan');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation']);
	}

	public function DistribusiPenerimaanMenu()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['distriPenerimaan'] = $this->M_DistribusiPenerimaan->get_kode_distribusi_penerimaan();
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

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
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
		$this->load->view('WMS/DistribusiPenerimaan/component/HalamanUtama/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/DistribusiPenerimaan/component/HalamanUtama/S_HalamanUtama', $data);
	}

	public function get_data_penerimaan_by_params()
	{
		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		$kode = $this->input->post('kode');
		$status = $this->input->post('status');
		$result = $this->M_DistribusiPenerimaan->get_data_penerimaan_by_params($tahun, $bulan, $kode, $status);
		echo json_encode($result);
	}

	public function tambah()
	{
		$data = array();
		$data['no_penerimaan'] = $this->M_DistribusiPenerimaan->get_no_penerimaan();

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
		$this->load->view('WMS/DistribusiPenerimaan/DistribusiPenerimaan', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/DistribusiPenerimaan/S_DistribusiPenerimaan', $data);
	}

	public function get_data_dpdt()
	{
		$id = $this->input->post('id');
		$data = $this->M_DistribusiPenerimaan->get_data_dpdt($id);
		$depo = $this->M_DistribusiPenerimaan->get_depo_detail_by_depo($id);
		echo json_encode(array('data' => $data, 'depo' => $depo));
	}

	public function get_data_by_no_penerimaan()
	{
		$id = $this->input->post('id');
		$header = $this->M_DistribusiPenerimaan->get_data_by_no_penerimaan($id);
		$pallet = $this->M_DistribusiPenerimaan->get_data_pallet_by_no_penerimaan($id);
		if (count($pallet) > 0) {
			foreach ($pallet as $value) {
				//cek apakah ada data di distribusi_penerimaan_detail_temp
				$chek_data = $this->M_DistribusiPenerimaan->chek_data_dpdt($value->po_id, $value->pallet_id);
				if ($chek_data->num_rows() == 0) {
					//jika kosong insert ke distribusi_penerimaan_detail_temp
					$this->M_DistribusiPenerimaan->insert_ke_dpdt($value);
				}
			}
		}
		echo json_encode($header);
	}

	public function check_kode_pallet_by_no_penerimaan()
	{
		$po_id = $this->input->post('po_id');
		$kode_pallet = $this->input->post('kode_pallet');

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_DistribusiPenerimaan->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		$get_data = $this->M_DistribusiPenerimaan->getPrefixPallet($po_id);

		$kode = $unit . "/" . $get_data->pallet_jenis_kode . "/" . $kode_pallet;


		$result = $this->M_DistribusiPenerimaan->check_kode_pallet_by_no_penerimaan($po_id, $kode);
		if ($result == null) {
			echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan", 'kode' => $kode));
		} else {
			//cek jika status sudah 1 maka tampilkan message sudah divalidasi
			if ($result->status == 1) {
				echo json_encode(array('type' => 202, 'message' => "Kode pallet <strong>" . $kode . "</strong> sudah tervalidasi", 'kode' => $kode));
			} else {
				//jika cek barcode menggunakan manual input
				if (empty($_FILES['file']['name'])) {
					//jika cek barcode menggunakan scan
					//jika status 0 maka update
					$this->M_DistribusiPenerimaan->update_status_dpdt($result, $file = "");
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
						echo json_encode(array('type' => 201, 'message' => "Gagal! File Attactment tidak sesuai ketentuan (JPG & PNG)", 'kode' => $kode));
					} else {
						$res = $this->M_DistribusiPenerimaan->update_status_dpdt($result, $name_file);
						if ($res) {
							$uploadPath = $uploadDirectory . $name_file;
							compressImage($fileTmpName, $uploadPath, 10);
							echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> <span class='text-success'>Valid</span>", 'kode' => $kode));
						} else {
							echo json_encode(array('type' => 201, 'message' => "Terjadi kesalahan pada server", 'kode' => $kode));
						}
					}
				}
			}
		}
	}

	public function delete_dpdt()
	{
		$id = $this->input->post('id');
		$result = $this->M_DistribusiPenerimaan->delete_dpdt($id);
		if ($result) {
			echo json_encode(array('status' => 'true', 'message' => 'Data berhasil dihapus'));
		} else {
			echo json_encode(array('status' => 'false', 'message' => 'Data gagal dihapus'));
		}
	}

	public function update_gudang_tujuan_by_id()
	{
		$id = $this->input->post('id');
		$gudang_tujuan_id = $this->input->post('gudang_tujuan_id');
		$result = $this->M_DistribusiPenerimaan->update_gudang_tujuan_by_id($id, $gudang_tujuan_id);
		echo json_encode($result);
	}

	public function save_data()
	{
		$tgl = $this->input->post('tgl');
		$principle = $this->input->post('principle');
		$tipe_penerimaan = $this->input->post('tipe_penerimaan');
		$no_penerimaan = $this->input->post('no_penerimaan');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan');
		$gudang_asal = $this->input->post('gudang_asal');
		$checker = $this->input->post('checker');

		$dp_id = $this->M_Vrbl->Get_NewID();
		$dp_id = $dp_id[0]['NEW_ID'];

		//generate kode
		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_DPB';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_DistribusiPenerimaan->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$result = "";
		$result2 = "";

		$this->db->trans_begin();

		$dp_header = $this->M_DistribusiPenerimaan->save_data_to_distribusi_penerimaan($dp_id, $generate_kode, $tgl, $no_penerimaan, $status, $keterangan, $gudang_asal);

		//get data distribusi_penerimaan_detail_temp by no_penerimaan
		$get_data = $this->M_DistribusiPenerimaan->get_data_dpdt_by_no_penerimaan($no_penerimaan);

		if ($dp_header) {
			//insert ke distribusi_penerimaan_detail
			foreach ($get_data as $val) {
				$dp_detail = $this->M_DistribusiPenerimaan->save_data_to_distribusi_penerimaan_detail($dp_id, $val);
				if ($dp_detail) {
					$result = 1;
				} else {
					$result = 0;
				}
			}
		} else {
			$result = 0;
		}

		if ($result == 1) {

			//get data distribusi_penerimaan_detail_temp by no_penerimaan
			$depo_detail_id_tujuan = [];
			foreach ($get_data as $key => $value) {
				if (array_search($value->depo_detail_id_tujuan, $depo_detail_id_tujuan) !== false) {
					unset($get_data[$key]);
				} else {
					$depo_detail_id_tujuan[] = $value->depo_detail_id_tujuan;
				}
			}

			foreach ($depo_detail_id_tujuan as $key => $data) {
				//insert ke tr mutasi pallet draft
				$mp_id = $this->M_Vrbl->Get_NewID();
				$mp_id = $mp_id[0]['NEW_ID'];

				//generate kode
				$date_now_ = date('Y-m-d h:i:s');
				$param_ =  'KODE_DMPAG';
				$vrbl_ = $this->M_Vrbl->Get_Kode($param_);
				$prefix_ = $vrbl_->vrbl_kode;
				// get prefik depo
				$depo_id_ = $this->session->userdata('depo_id');
				$depoPrefix_ = $this->M_DistribusiPenerimaan->getDepoPrefix($depo_id_);
				$unit_ = $depoPrefix_->depo_kode_preffix;
				$generate_kode_ = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now_, $prefix_, $unit_);

				$mp_header = $this->M_DistribusiPenerimaan->save_data_to_mutasi_pallet_draft($mp_id, $dp_id, $generate_kode_, $tgl, $principle, $tipe_penerimaan, $status, $keterangan, $gudang_asal, $data, $checker);
				if ($mp_header) {
					$get_data2 = $this->M_DistribusiPenerimaan->get_data_dpdt_by_no_penerimaan2($no_penerimaan, $data);
					foreach ($get_data2 as $key2 => $val) {
						//insert ke tr mutasi pallet detail draft
						$mp_detail = $this->M_DistribusiPenerimaan->save_data_to_mutasi_pallet_detail_draft($mp_id, $val->pallet_id);
						if ($mp_detail) {
							$result2 = 1;
						} else {
							$result2 = 0;
						}
					}
				} else {
					$result2 = 0;
				}
			}
		} else {
			$result2 = 0;
		}

		if ($result2 == 1) {
			//delete distribusi_penerimaan_detail_temp by no_penerimaan
			$this->M_DistribusiPenerimaan->delete_data_distribusi_penerimaan_detail_temp_by_params($no_penerimaan);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function get_data_detail_pallet()
	{
		$id = $this->input->post('id');
		$result = $this->M_DistribusiPenerimaan->get_data_detail_pallet($id);
		echo json_encode($result);
	}

	public function view($id)
	{
		$data = array();
		$data['id'] = $id;

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
		$this->load->view('WMS/DistribusiPenerimaan/component/view/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		// $this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/DistribusiPenerimaan/component/view/S_view', $data);
	}

	public function get_data_header_konfirmasi_view()
	{
		$id = $this->input->post('id');
		$header = $this->M_DistribusiPenerimaan->get_header_view($id);
		$detail = $this->M_DistribusiPenerimaan->get_detail_view($id);
		echo json_encode(array('header' => $header, 'detail' => $detail));
	}

	public function get_data_detail_pallet_view()
	{
		$id = $this->input->post('id');
		$result = $this->M_DistribusiPenerimaan->get_data_detail_pallet_view($id);
		echo json_encode($result);
	}

	public function getKodeAutoComplete()
	{
		$valueParams = $this->input->get('params');
		$result = $this->M_DistribusiPenerimaan->getKodeAutoComplete($valueParams);
		echo json_encode($result);
	}
}
