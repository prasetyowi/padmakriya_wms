<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentController.php';

class KoreksiKartuStok extends CI_Controller
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

		$this->load->model('WMS/M_KoreksiKartuStok', 'M_KoreksiKartuStok');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('M_Menu');
		$this->load->model('M_DataTable');

		$this->MenuKode = "199100200";
	}

	public function KoreksiKartuStokMenu()
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'

		);


		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Perusahaan'] = $this->M_KoreksiKartuStok->GetPerusahaan();
		$data['Gudang'] = $this->M_KoreksiKartuStok->GetDepoDetail();
		$data['Principle'] = $this->M_KoreksiKartuStok->GetPrinnciple();

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));
		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/Maintenance/KoreksiKartuStok/index', $data);
		$this->load->view('layouts/sidebar_footer', $data);
		$this->load->view('master/S_GlobalVariable', $data);

		$this->load->view('WMS/Maintenance/KoreksiKartuStok/script', $data);
	}

	public function getData()
	{

		$draw = 50;
		$data_laporan_stock = $this->M_KoreksiKartuStok->getData();

		$data = array(
			"draw" => intval($draw),
			// "iTotalRecords" => 100000000,
			// "iTotalDisplayRecords" => 100000000,
			"aaData" => $data_laporan_stock
		);
		echo json_encode($data);
	}

	public function deleteDetailPallet()
	{
		// $data = $this->input->post('detail');
		$depo = $this->session->userdata('depo_id');

		$data = $this->db->query("SELECT  pr.principle_nama, sku.sku_nama_produk, p.pallet_kode, pd.*, (ISNULL(pd.sku_stock_qty, 0) - ISNULL(pd.sku_stock_ambil, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_out, 0) + ISNULL(pd.sku_stock_terima, 0)) AS sum_total
		FROM pallet_detail pd
			INNER JOIN pallet p
				ON p.pallet_id = pd.pallet_id
			inner join sku on sku.sku_id = pd.sku_id
			inner join principle pr on pr.principle_id = sku.principle_id
		WHERE p.depo_id= '$depo' AND p.pallet_is_lock = 0 AND (ISNULL(pd.sku_stock_qty, 0) - ISNULL(pd.sku_stock_ambil, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_out, 0) + ISNULL(pd.sku_stock_terima, 0)) = 0")->result_array();

		foreach ($data as $key => $value) {
			$this->M_KoreksiKartuStok->deletePalletDetailIsZero($value);
		}
		echo json_encode(1);
	}

	public function proses()
	{
		// echo json_encode(1);
		// die;
		$data = $this->M_KoreksiKartuStok->proses();
		echo json_encode(1);
	}
}
