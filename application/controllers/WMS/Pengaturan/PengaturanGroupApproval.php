<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PengaturanGroupApproval extends CI_Controller
{

	private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		if ($this->session->has_userdata('pengguna_id') == 0) :
			redirect(base_url('MainPage'));
		endif;

		$this->load->model('WMS/M_GroupApproval', 'M_GroupApproval');
		$this->load->model('M_Vrbl');

		$this->MenuKode = "199006000";
	}

	public function PengaturanGroupApprovalMenu()
	{
		$this->load->model('M_Menu');

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

		$data['datas'] = $this->M_GroupApproval->getApprovalGrup();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/Pengaturan/PengaturanGrupApproval/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Pengaturan/PengaturanGrupApproval/script', $data);
	}

	public function saveGroup()
	{
		$grup_id 	= $this->M_Vrbl->Get_NewID();
		$grup_id 	= $grup_id[0]['NEW_ID'];
		$depo_id	= $this->session->userdata('depo_id');
		$nama 		= $this->input->post('nama');
		$keterangan = $this->input->post('keterangan');
		$is_aktif	= 1;
		$tgl_create	= date('Y-m-d H:i:s');
		$who_create = $this->session->userdata('pengguna_id');

		$this->M_GroupApproval->saveGroup($grup_id, $depo_id, $nama, $keterangan, $is_aktif, $tgl_create, $who_create);

		echo 1;
	}

	// public function GetLevelMenu()
	// {
	// 	$this->load->model('M_GroupApproval');
	// 	$this->load->model('M_MenuAccess');

	// 	$this->load->model('M_Menu');
	// 	if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
	// 		echo 0;
	// 		exit();
	// 	}

	// 	$data['LevelMenu'] = $this->M_GroupApproval->Getkaryawan_level();

	// 	// Mendapatkan url yang ngarah ke sini :
	// 	$MenuLink = $this->session->userdata('MenuLink');
	// 	$pengguna_grup_id = $this->session->userdata('pengguna_grup_id');

	// 	$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($pengguna_grup_id, $MenuLink);

	// 	$data['pengguna_grup_id'] = $this->session->userdata('pengguna_grup_id');
	// 	$data['MenuLink'] = $this->session->userdata('MenuLink');

	// 	echo json_encode($data);
	// }

	public function getKaryawan()
	{
		// $client_wms_id = $this->input->post('client_wms_id');
		$data = $this->M_GroupApproval->getKaryawan();

		echo json_encode($data);
	}

	public function getDivisiAndLevel()
	{
		$karyawan_id = $this->input->post('karyawan_id');
		$data = $this->M_GroupApproval->getDivisiAndLevel($karyawan_id);

		echo json_encode($data);
	}

	public function getDetailByGrupID()
	{
		$arr_cek = [];
		$approval_group_id = $this->input->post('approval_group_id');
		$approval_detail 	 = $this->M_GroupApproval->getDetailByGrupID($approval_group_id);

		foreach ($approval_detail as $row) {
			$query = $this->M_GroupApproval->isRelation($row['approval_group_detail_id']);

			if (count($query) > 0) {
				$arr_cek[$query['approval_group_detail_id']] = $query['approval_group_detail_id'];
			}
		}

		$cek = $arr_cek;
		$response = [
			'detail' => $approval_detail,
			'cek' => $cek
		];

		echo json_encode($response);
	}

	public function saveDetail()
	{
		$grup_id 	= $this->input->post('grup_id');
		$arr_detail = $this->input->post('arr_detail');

		$this->db->trans_begin();

		$this->M_GroupApproval->deleteDetail($grup_id);
		foreach ($arr_detail as $data) {
			$detail_id 	= $this->M_Vrbl->Get_NewID();
			$detail_id 	= $detail_id[0]['NEW_ID'];
			$karyawan_id = $data['karyawan_id'];
			$urutan 	= $data['urutan'];

			$this->M_GroupApproval->saveDetail($detail_id, $grup_id, $karyawan_id, $urutan);
		}

		if ($this->db->trans_status() == false) {
			$this->db->trans_rollback();
			echo 0;
		} else {
			$this->db->trans_commit();
			echo 1;
		}
	}

	public function SaveUpdateLevel()
	{
		$this->load->model('M_GroupApproval');
		$this->load->model('M_Menu');

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "U")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('Level_id', 'ID', 'required');
		$this->form_validation->set_rules('Level_kode', 'Kode Level', 'required');
		$this->form_validation->set_rules('Level_nama', 'Nama Level', 'required');
		$this->form_validation->set_rules('Level_jenis', 'Tipe', 'required');
		$this->form_validation->set_rules('Level_warna', 'Warna', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$Level_id 		= $this->input->post('Level_id');
			$Level_kode 	= $this->input->post('Level_kode');
			$Level_nama 	= $this->input->post('Level_nama');
			$Level_jenis 	= $this->input->post('Level_jenis');
			$Level_warna 	= $this->input->post('Level_warna');

			$duplicateLevel_kode = $this->M_GroupApproval->CheckLevel_duplicate_by_Level_kode_others($Level_kode, $Level_id); // pengecekan nama, optional bisa diremark bila tidak dibutuhkan

			if ($duplicateLevel_kode == 0) {
				$duplicateLevel_nama = $this->M_GroupApproval->CheckLevel_duplicate_by_Level_nama_others($Level_nama, $Level_id); // pengecekan nama, optional bisa diremark bila tidak dibutuhkan

				if ($duplicateLevel_nama == 0) {
					$duplicateLevel_warna = $this->M_GroupApproval->CheckLevel_duplicate_by_Level_warna_others($Level_warna, $Level_id); // pengecekan nama, optional bisa diremark bila tidak dibutuhkan

					if ($duplicateLevel_warna == 0) {
						$result = $this->M_GroupApproval->UpdateLevel($Level_id, $Level_kode, $Level_nama, $Level_jenis, $Level_warna);

						echo $result;
					} else {
						echo 4;
					}
				} else {
					echo 3;
				}
			} else {
				echo 2;
			}
		}
	}

	public function DeleteLevelMenu()
	{
		$this->load->model('M_GroupApproval');
		$this->load->model('M_Menu');

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "D")) {
			echo 0;
			exit();
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('Level_id', 'ID', 'required');

		if ($this->form_validation->run() == FALSE) {
			log_message('debug', validation_errors());

			echo validation_errors();
		} else {
			$Level_id = $this->input->post('Level_id');

			$candelete = $this->M_GroupApproval->CheckLevel_is_used($Level_id); // pengecekan nama, optional bisa diremark bila tidak dibutuhkan

			if ($candelete == 1) {
				$result = $this->M_GroupApproval->DeleteLevel($Level_id);

				echo $result;
			} else {
				echo 2;
			}
		}
	}
}
