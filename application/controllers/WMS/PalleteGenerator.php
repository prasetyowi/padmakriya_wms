<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class PalleteGenerator extends ParentController
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
		$this->load->model('WMS/M_PalleteGenerator', 'M_PalleteGenerator');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation', 'pdfgenerator']);
	}

	public function PalleteGeneratorMenu()
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

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));
		$data['perusahaan'] = $this->M_PalleteGenerator->getDataPerusahaan();

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PalleteGenerator/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PalleteGenerator/script', $query);
	}

	public function create()
	{
		$this->load->model('M_Menu');


		$data = array();

		$data = array();
		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		if (!$this->session->has_userdata('pengguna_id')) {
			redirect(base_url('MainPage'));
		}

		if (!$this->session->has_userdata('depo_id')) {
			redirect(base_url('Main/MainDepo/DepoMenu'));
		}

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
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
		);

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));
		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$depo_kode_preffix = $this->session->userdata('depo_id');

		$data['perusahaan'] = $this->M_PalleteGenerator->getDataPerusahaan();
		$data['depo'] = $this->M_PalleteGenerator->getDepoKodePreffix($depo_kode_preffix);

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PalleteGenerator/form', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PalleteGenerator/script', $query);
	}

	public function edit($id)
	{
		$this->load->model('M_Menu');


		$data = array();

		$data = array();
		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		if (!$this->session->has_userdata('pengguna_id')) {
			redirect(base_url('MainPage'));
		}

		if (!$this->session->has_userdata('depo_id')) {
			redirect(base_url('Main/MainDepo/DepoMenu'));
		}

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
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
		);

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));
		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$depo_kode_preffix = $this->session->userdata('depo_id');

		$data['byid'] = $this->M_PalleteGenerator->getPalleteGenerateByID($id);
		$data['perusahaan'] = $this->M_PalleteGenerator->getDataPerusahaan();
		$data['depo'] = $this->M_PalleteGenerator->getDepoKodePreffix($depo_kode_preffix);

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PalleteGenerator/edit', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PalleteGenerator/script_edit', $query);
	}

	public function getPalleteGenerateByID()
	{
		$id = $this->input->post("id");
		$data['pg'] = $this->M_PalleteGenerator->getPalleteGenerateByID($id);
		$data['pgd'] = $this->M_PalleteGenerator->getPalleteGenerateDetailByID($id);
		$data['pgd2'] = $this->M_PalleteGenerator->getPalleteGenerateDetail2ByID($id);

		echo json_encode($data);
	}

	public function getDataPrincipleByClientWmsID()
	{
		$principle = $this->input->post('principle');
		$id = $this->input->post('id');
		$output = "";
		if ($id != "") {
			$data = $this->M_PalleteGenerator->getDataPrincipleByClientWmsID($id);
			$output .= '<option value="">Semua</option>';
			foreach ($data as $row) {
				if ($principle) {
					if ($principle == $row->id) {
						$output .= '<option value="' . $row->id . '" selected>' . $row->nama . '</option>';
					} else {
						$output .= '<option value="' . $row->id . '">' . $row->nama . '</option>';
					}
				} else {
					$output .= '<option value="' . $row->id . '">' . $row->nama . '</option>';
				}
			}
		}
		echo json_encode($output);
	}

	public function getDataSuratJalan()
	{
		$perusahaan_sj = $this->input->post("perusahaan_sj");
		$principle_sj = $this->input->post("principle_sj");
		$data["perusahaan"] = $this->M_PalleteGenerator->getPerusahaanID($perusahaan_sj);
		$data["principle"] = $this->M_PalleteGenerator->getPrincipleByClientWmsID($principle_sj);
		$data["suratjalan"] = $this->M_PalleteGenerator->getDataSuratJalan($perusahaan_sj, $principle_sj);

		echo json_encode($data);
	}

	public function getDataSuratJalanPallete()
	{
		$id_sj = $this->input->post("id");

		$data = $this->M_PalleteGenerator->getDataSuratJalanPallete($id_sj);

		echo json_encode($data);
	}

	public function getJumlahGenerate()
	{
		$data = [];
		$jml_gen = $this->input->post("jml_gen");
		$preffix_pallete = $this->input->post("preffix_pallete");

		$pal = $this->M_PalleteGenerator->getPalletJenisKodeByID($preffix_pallete);
		$cek = $pal->pallet_jenis_kode;


		$date_now = date('Y-m-d h:i:s');

		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PalleteGenerator->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		for ($i = 0; $i < $jml_gen; $i++) {
			$data[$i] = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $cek, $unit);
		}

		echo json_encode($data);
	}

	public function savePalleteGenerate()
	{
		// input pallet generate 
		$pg_id = $this->M_Vrbl->Get_NewID();
		$pg_id = $pg_id[0]['NEW_ID'];
		$id_perusahaan = $this->input->post("perusahaan");
		$id_principle = $this->input->post("principle");
		$datetime = date("Y-m-d h:i:s");
		$user = $this->session->userdata('pengguna_username');

		$result = $this->M_PalleteGenerator->savePalleteGenerate($pg_id, $id_perusahaan, $id_principle, $user);

		// input pallet generate detail
		$arr_sj_id = $this->input->post("arr_sj_id");
		if ($arr_sj_id != null) {
			foreach ($arr_sj_id as $val) {
				$pg_detail_id = $this->M_Vrbl->Get_NewID();
				$pg_detail_id = $pg_detail_id[0]['NEW_ID'];
				$sj_id = $val['sj_id'];
				$result2 =	$this->M_PalleteGenerator->savePalleteGenerateDetail($pg_detail_id, $pg_id, $sj_id);
			}
		}

		// input pallet generate detail2
		$arr_pallete_kode = $this->input->post("arr_pallete_kode");
		// $preffix_pallete = $this->input->post("preffix_pallete");
		// $cekPAL = $this->M_PalleteGenerator->getJenisPallet();
		// $cekBIN = $this->M_PalleteGenerator->getJenisKeranjang();

		// if ($preffix_pallete == $cekPAL->pallet_jenis_id) {
		// 	$preffix_pallete = $cekPAL->pallet_jenis_id;
		// } else {
		// 	$preffix_pallete = $cekBIN->pallet_jenis_id;
		// }

		foreach ($arr_pallete_kode as $val) {
			$pg_detail2_id = $this->M_Vrbl->Get_NewID();
			$pg_detail2_id = $pg_detail2_id[0]['NEW_ID'];
			$pg_detail2_kode = $val['pallete_kode'];
			$pg_detail2_pl_id = $val['preffix_pallete'];
			$result3 = $this->M_PalleteGenerator->savePalleteGenerateDetail2($pg_detail2_id, $pg_id, $pg_detail2_kode, $pg_detail2_pl_id);
		}

		echo 1;
	}

	public function saveUpdatePalleteGenerate()
	{

		// input pallet generate 
		$pg_id = $this->input->post("id");
		$lastUpdated = $this->input->post("lastUpdated");
		$user = $this->session->userdata('pengguna_username');
		// $id_perusahaan = $this->input->post("perusahaan");
		// $id_principle = $this->input->post("principle");
		// $result = $this->M_PalleteGenerator->saveUpdatePalleteGenerate($pg_id, $id_perusahaan, $id_principle, $user);

		$this->db->trans_begin();

		$errorNotSameLastUpdated = false;

		$getLastUpdatedDb = $this->db->select("pallet_generate_tgl_update")->from("pallet_generate")->where("pallet_generate_id", $pg_id)->get()->row()->pallet_generate_tgl_update;

		if ($lastUpdated !== $getLastUpdatedDb) $errorNotSameLastUpdated = true;

		$this->db->set("pallet_generate_tgl_update", "GETDATE()", false);
		$this->db->set("pallet_generate_who_update", $user);
		$this->db->where("pallet_generate_id", $pg_id);

		$this->db->update("pallet_generate");

		// input pallet generate detail
		$arr_sj_id = $this->input->post("arr_sj_id");
		$arr_tmp_sj_id = $this->input->post("arr_tmp_sj_id");
		if ($arr_tmp_sj_id != null) {
			foreach ($arr_tmp_sj_id as $id) {
				$this->M_PalleteGenerator->deletePalletGenerateDetail($id);
			}

			foreach ($arr_sj_id as $val) {
				$pg_detail_id = $this->M_Vrbl->Get_NewID();
				$pg_detail_id = $pg_detail_id[0]['NEW_ID'];
				$sj_id = $val['sj_id'];
				$this->M_PalleteGenerator->savePalleteGenerateDetail($pg_detail_id, $pg_id, $sj_id);
			}
		}


		// input pallet generate detail2
		$arr_pallete_kode = $this->input->post("arr_pallete_kode");
		// $cekPAL = $this->M_PalleteGenerator->getJenisPallet();
		// $cekBIN = $this->M_PalleteGenerator->getJenisKeranjang();

		foreach ($arr_pallete_kode as $val) {
			if ($val['pallete_id'] != "") {
				$pallete_id = $val['pallete_id'];
				$pallete_kode = $val['pallete_kode'];
				$last_printed = $val['last_printed'];
				$print_amount = $val['print_amount'];
				$isAktif = $val['isAktif'];
				$pallete_preffix = $val['pallete_preffix'];

				if ($isAktif == "Yes") {
					$isAktif = 1;
				} else {
					$isAktif = 0;
				}

				// if ($pallete_preffix == $cekPAL->pallet_jenis_kode) {
				// 	$pallete_preffix = $cekPAL->pallet_jenis_id;
				// } else {
				// 	$pallete_preffix = $cekBIN->pallet_jenis_id;
				// }

				$this->M_PalleteGenerator->saveUpdatePalleteGenerateDetail2($pallete_id, $pg_id, $pallete_kode, $last_printed, $user, $print_amount, $isAktif, $pallete_preffix);
			} else {
				$pallete_kode = $val['pallete_kode'];
				$last_printed = $val['last_printed'];
				$print_amount = $val['print_amount'];
				$pallete_preffix = $val['pallete_preffix'];

				// if ($pallete_preffix == $cekPAL->pallet_jenis_kode) {
				// 	$pallete_preffix = $cekPAL->pallet_jenis_id;
				// } else {
				// 	$pallete_preffix = $cekBIN->pallet_jenis_id;
				// }

				$pg_detail2_id = $this->M_Vrbl->Get_NewID();
				$pg_detail2_id = $pg_detail2_id[0]['NEW_ID'];
				$this->M_PalleteGenerator->savePalleteGenerateDetail2($pg_detail2_id, $pg_id, $pallete_kode, $pallete_preffix);
			}
		}


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else if ($errorNotSameLastUpdated) {
			$this->db->trans_rollback();
			echo json_encode(2);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function saveUpdatePrintedPalleteGenerate()
	{
		// update pallet generate detail2
		$pg_id = $this->input->post("id");
		$user = $this->session->userdata('pengguna_username');
		$arr_cetak = $this->input->post("arr_cetak");
		$datelastprint = date("Y-m-d H:i:s");

		foreach ($arr_cetak as $val) {
			$printAmount = $this->M_PalleteGenerator->getprintAmountbyID($val);

			$count = $printAmount->pallet_generate_detail2_print_count + 1;

			$result3 = $this->M_PalleteGenerator->saveUpdatePrintedPalleteGenerateDetail2($val, $datelastprint, $count, $user);
		}

		$data = $this->M_PalleteGenerator->getPalleteGenerateDetail2ByID($pg_id);

		echo json_encode($data);
	}

	public function getPalletGenerator()
	{
		$data =  $this->M_PalleteGenerator->getPalleteGenerate();
		echo json_encode($data);
	}

	public function hapusPalletGeneratorDetail2()
	{
		$arr_chk_pg = $this->input->post('arr_chk_pg');

		foreach ($arr_chk_pg as $pg) {
			$ex_1 = explode(".", $pg);
			$action = $this->M_PalleteGenerator->deletePalletGenerateDetail2($ex_1[0]);
		}

		echo 1;
	}

	public function getDataPreffixPallete()
	{
		$data = $this->M_PalleteGenerator->getDataPreffixPallete();
		echo json_encode($data);
	}

	public function getDataFilterPalletGenerator()
	{
		$tgl = explode(" - ", $this->input->post('tgl'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$perusahaan = $this->input->post("perusahaan");
		$principle = $this->input->post("principle");

		$data = $this->M_PalleteGenerator->getDataFilterPalletGenerator($tgl1, $tgl2, $perusahaan, $principle);

		echo json_encode($data);
	}

	public function print()
	{
		$header['header'] = $this->M_PalleteGenerator->get_print_header($this->session->userdata('session_pallet'));

		$file_pdf = 'Cetak Nomer Pallet';

		// setting paper
		$paper = 'A4';

		//orientasi paper potrait / landscape
		$orientation = "portrait";

		$html = $this->load->view('WMS/PalleteGenerator/view_cetak', $header, true);

		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);

		$this->session->unset_userdata('session_pallet');
	}

	public function saveSessionCetak()
	{
		$arr_cetak = $this->input->post('id');

		$data = [
			'session_pallet' => $arr_cetak
		];

		$this->session->set_userdata($data);

		echo 1;
	}
}
