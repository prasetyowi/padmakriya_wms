<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentController.php';

class HapusPalletDetail extends CI_Controller
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

		$this->load->model('WMS/M_HapusPalletDetail', 'M_HapusPalletDetail');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
		$this->load->model('M_Menu');
		$this->load->model('M_DataTable');

		$this->MenuKode = "199100200";
	}

	public function HapusPalletDetailMenu()
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

		$data['Perusahaan'] = $this->M_HapusPalletDetail->GetPerusahaan();
		$data['Gudang'] = $this->M_HapusPalletDetail->GetDepoDetail();
		$data['Principle'] = $this->M_HapusPalletDetail->GetPrinnciple();

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));
		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/Maintenance/HapusPalletDetail/index', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/Maintenance/HapusPalletDetail/script', $data);
	}

	public function getData()
	{
		$depo = $this->session->userdata('depo_id');
		$sql = "SELECT ROW_NUMBER ( ) OVER ( ORDER BY p.pallet_kode ASC ) AS idx, pr.principle_nama,sku.sku_nama_produk, p.pallet_kode,
		pd.*,
		(ISNULL(pd.sku_stock_qty, 0) - ISNULL(pd.sku_stock_ambil, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_out, 0) + ISNULL(pd.sku_stock_terima, 0)) AS sum_total
		FROM pallet_detail pd
			INNER JOIN pallet p
				ON p.pallet_id = pd.pallet_id
			inner join sku on sku.sku_id = pd.sku_id
			inner join principle pr on pr.principle_id = sku.principle_id
		WHERE p.depo_id= '$depo' AND p.pallet_is_lock =0 AND (ISNULL(pd.sku_stock_qty, 0) - ISNULL(pd.sku_stock_ambil, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_out, 0) + ISNULL(pd.sku_stock_terima, 0)) <= 0";
		$response = $this->M_DataTable->dtTableGetList($sql);

		$output = array(
			"draw" => $response['draw'],
			"recordsTotal" => $response['recordsTotal'],
			"recordsFiltered" => $response['recordsFiltered'],
			"data" => $response['data'],
		);
		echo json_encode($output);
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
		WHERE p.depo_id= '$depo' AND p.pallet_is_lock = 0 AND (ISNULL(pd.sku_stock_qty, 0) - ISNULL(pd.sku_stock_ambil, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_out, 0) + ISNULL(pd.sku_stock_terima, 0)) <= 0")->result_array();

		foreach ($data as $key => $value) {
			$this->M_HapusPalletDetail->deletePalletDetailIsZero($value);
		}
		echo json_encode(1);
	}
}
