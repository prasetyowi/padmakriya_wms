<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KomparasiLokasiStockPalletGlobal2 extends CI_Controller
{
	private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		if ($this->session->has_userdata('pengguna_id') == 0) :
			redirect(base_url('MainPage'));
		endif;

		$this->load->model('WMS/M_KomparasiLokasiStockPalletGlobal2', 'M_KomparasiLokasiStockPalletGlobal2');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('M_ClientWms');
		$this->load->model('M_Depo');
		$this->load->model('M_Principle');
		$this->load->model('M_Karyawan');
		$this->load->model('M_Menu');

		$this->MenuKode = "199100200";
	}

	public function KomparasiLokasiStockPalletGlobal2Menu()
	{
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

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

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

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Perusahaan'] = $this->M_ClientWms->findAll_array();
		$data['Depo'] = $this->M_Depo->Getdepo_active();
		$data['Gudang'] = $this->M_KomparasiLokasiStockPalletGlobal2->GetDepoDetail();
		$data['Principle'] = $this->M_KomparasiLokasiStockPalletGlobal2->GetPrinnciple();

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));


		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/Maintenance/KomparasiLokasiStockPalletGlobal2/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/Maintenance/KomparasiLokasiStockPalletGlobal2/script', $data);
	}

	public function Get_list_komparasi_lokasi_stock_pallet()
	{
		$data = $this->M_KomparasiLokasiStockPalletGlobal2->Get_list_komparasi_lokasi_stock_pallet();

		echo json_encode($data);
	}

	public function Get_komparasi_lokasi_stock_pallet_by_id()
	{
		$depo_detail_id = $this->input->get('depo_detail_id');

		$data = $this->M_KomparasiLokasiStockPalletGlobal2->Get_komparasi_lokasi_stock_pallet_by_id($depo_detail_id);

		echo json_encode($data);
	}


	public function Get_rak_lajur_detail_by_depo_detail_id()
	{
		$depo_detail_id = $this->input->get('depo_detail_id');

		$data = $this->M_KomparasiLokasiStockPalletGlobal2->Get_rak_lajur_detail_by_depo_detail_id($depo_detail_id);

		echo json_encode($data);
	}

	public function update_pallet()
	{

		$pallet_id = $this->input->post('pallet_id');
		$pallet_detail_id = $this->input->post('pallet_detail_id');
		$rak_lajur_detail_id = $this->input->post('rak_lajur_detail_id');

		$this->db->trans_begin();
		$this->db->query("update pallet_detail set pallet_id = '$pallet_id' where pallet_detail_id = '$pallet_detail_id'");

		// $this->M_KomparasiLokasiStockPalletGlobal2->update_pallet($pallet_id, $rak_lajur_detail_id);
		// $this->M_KomparasiLokasiStockPalletGlobal2->update_rak_lajur_detail_pallet($pallet_id, $rak_lajur_detail_id);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}
}
