<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentController.php';

class KomparasiStockPalletGlobal extends CI_Controller
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

		$this->load->model('WMS/M_KomparasiStockPalletGlobal', 'M_KomparasiStockPalletGlobal');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('M_ClientWms');
		$this->load->model('M_Depo');
		$this->load->model('M_Principle');
		$this->load->model('M_Karyawan');
		$this->load->model('M_Menu');

		$this->MenuKode = "199100100";
	}

	public function KomparasiStockPalletGlobalMenu()
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
		$data['Gudang'] = $this->M_KomparasiStockPalletGlobal->GetDepoDetail();
		$data['Principle'] = $this->M_KomparasiStockPalletGlobal->GetPrinnciple();

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));


		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/Maintenance/KomparasiStockPalletGlobal/index', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Maintenance/KomparasiStockPalletGlobal/script', $data);
	}

	public function Get_list_komparasi_lokasi_stock_pallet()
	{
		$depo_detail_id = $this->input->post('depo_detail_id');
		$principle_id 	= $this->input->post('principle_id');
		$hasil 			= $this->input->post('hasil');
		$alokasi 		= $this->input->post('alokasi');
		// var_dump($alokasi);
		// die;
		$data 			= $this->M_KomparasiStockPalletGlobal->Get_list_komparasi_lokasi_stock_pallet($depo_detail_id, $principle_id, $hasil, $alokasi);

		echo json_encode($data);
	}

	public function Get_list_komparasi_lokasi_stock_pallet_by_sku_stock_id()
	{
		$sku_stock_id = $this->input->get('sku_stock_id');

		$data['header'] = $this->M_KomparasiStockPalletGlobal->Get_header_komparasi_lokasi_stock_pallet_by_sku_stock_id($sku_stock_id);
		$data['detail'] = $this->M_KomparasiStockPalletGlobal->Get_detail_komparasi_lokasi_stock_pallet_by_sku_stock_id($sku_stock_id);

		echo json_encode($data);
	}

	public function update_pallet_detail()
	{
		$sku_stock_id = $this->input->post('sku_stock_id');
		$detail = $this->input->post('detail');

		$this->db->trans_begin();

		foreach ($detail as $key => $value) {
			$pallet_detail_id = $value['pallet_detail_id'];
			$pallet_id = $value['pallet_id'];
			$sku_stock_qty = $value['sku_stock_qty'];
			$sku_stock_ambil = $value['sku_stock_ambil'];
			$sku_stock_in = $value['sku_stock_in'];
			$sku_stock_out = $value['sku_stock_out'];
			$sku_stock_terima = $value['sku_stock_terima'];

			$this->M_KomparasiStockPalletGlobal->update_pallet_detail($pallet_detail_id, $pallet_id, $sku_stock_id, $sku_stock_qty, $sku_stock_ambil, $sku_stock_in, $sku_stock_out, $sku_stock_terima);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function proses_maintenance_stock_pallet_global()
	{
		$depo_detail_id = $this->input->post('depo_detail_id');
		$principle_id 	= $this->input->post('principle_id');
		$hasil 			= $this->input->post('hasil');
		$alokasi 		= $this->input->post('alokasi');

		$this->db->trans_begin();

		$this->M_KomparasiStockPalletGlobal->proses_maintenance_stock_pallet_global($depo_detail_id, $principle_id, $hasil, $alokasi);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
		// echo $depo;
	}
}