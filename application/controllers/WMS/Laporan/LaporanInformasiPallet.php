<?php

class LaporanInformasiPallet extends CI_Controller
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

		// echo "<pre>".print_r($_SESSION, 1)."</pre>";
		// die();

		if ($this->session->has_userdata('pengguna_id') == 0) :
			redirect(base_url('MainPage'));
		endif;

		$this->MenuKode = "101001005";
		$this->load->model('WMS/M_LaporanInformasiPallet', 'M_LaporanInformasiPallet');
		$this->load->model('M_ClientPt');
		$this->load->model('M_Area');
		$this->load->model('M_StatusProgress');
		$this->load->model('M_SKU');
		$this->load->model('M_Principle');
		$this->load->model('M_TipeDeliveryOrder');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
	}

	public function LaporanInformasiPalletMenu()
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('reports/LaporanInformasiPallet/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('reports/LaporanInformasiPallet/script', $data);
	}

	public function get_pallet_by_kode()
	{
		$kode_pallet = $this->input->post('pallet_kode');

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_LaporanInformasiPallet->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		$kode = $unit . "/" . $kode_pallet;

		$data['header'] = $this->M_LaporanInformasiPallet->get_pallet_header_by_kode($kode);
		$data['detail'] = $this->M_LaporanInformasiPallet->get_pallet_detail_by_kode($kode);

		echo json_encode($data);
		// var_dump($data);
	}

	public function deletePalletDetail()
	{
		$pallet_kode = $this->input->post('pallet_kode');
		$selectedSKU = $this->input->post('pallet_detail_id');

		$depo_id 	 = $this->session->userdata('depo_id');
		$depoPrefix  = $this->M_LaporanInformasiPallet->getDepoPrefix($depo_id);
		$unit 		 = $depoPrefix->depo_kode_preffix;
		$kode 		 = $unit . "/" . $pallet_kode;

		$result 	 = $this->M_LaporanInformasiPallet->deleteSelectedSKU($kode, $selectedSKU);

		$response = array(
			'status' => $result['status'],
			'message' => $result['message']
		);

		echo json_encode($response);
	}

	public function get_history_pallet_by_id()
	{
		$id = $this->input->post('pallet_id');

		$data = $this->M_LaporanInformasiPallet->get_history_pallet_by_id($id);

		echo json_encode($data);
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function getKodeAutoComplete()
	{
		$valueParams = $this->input->get('params');
		$result = $this->M_LaporanInformasiPallet->getKodeAutoComplete($valueParams);
		echo json_encode($result);
	}

	public function releasePallet()
	{
		$pallet_id = $this->input->post('pallet_id');

		//get data table pallet
		$dataPallet = $this->M_LaporanInformasiPallet->getPalletByID($pallet_id);

		$this->db->trans_begin();

		//get kode pallet dan update table pallet_generate_detail2
		$update = $this->M_LaporanInformasiPallet->updatePalletGenerateDetail2($dataPallet);

		//insert ke tabel pallet_hist
		$insert = $this->M_LaporanInformasiPallet->insertPalletHis($dataPallet);

		// ambil data tabel pallet_detail dan insert ke tabel pallet_detail_his
		$dataPalletDetail = $this->M_LaporanInformasiPallet->getPalletDetailByID($pallet_id);
		foreach ($dataPalletDetail as  $value) {
			$insert = $this->M_LaporanInformasiPallet->insertPalletDetailHis($value);
		}

		//delete table pallet, pallet detail, rak_lajur_detail_pallet by pallet_id
		$delete = $this->M_LaporanInformasiPallet->deletePalletByID($pallet_id);
		$delete = $this->M_LaporanInformasiPallet->deletePalletDetailByID($pallet_id);
		$delete = $this->M_LaporanInformasiPallet->deleteRakLajurDetailPalletByID($pallet_id);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}
}
