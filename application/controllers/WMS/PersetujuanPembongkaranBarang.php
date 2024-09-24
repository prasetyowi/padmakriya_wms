<?php

class PersetujuanPembongkaranBarang extends CI_Controller
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

		$this->MenuKode = "128002000";
		$this->load->model('WMS/M_PersetujuanPembongkaranBarang', 'M_PersetujuanPembongkaranBarang');
		$this->load->model('M_ClientPt');
		$this->load->model('M_Area');
		$this->load->model('M_StatusProgress');
		$this->load->model('M_SKU');
		$this->load->model('M_Principle');
		$this->load->model('M_TipeDeliveryOrder');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
	}

	public function PersetujuanPembongkaranMenu()
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$data['Perusahaan'] = $this->M_PersetujuanPembongkaranBarang->GetPerusahaan();
		$data['TipeKonversi'] = $this->M_PersetujuanPembongkaranBarang->GetTipeKonversi();
		$data['Principle'] = $this->M_PersetujuanPembongkaranBarang->GetPrincipleAktif();
		$data['status'] = $this->M_PersetujuanPembongkaranBarang->getStatus();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PersetujuanPembongkaranBarang/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PersetujuanPembongkaranBarang/script', $data);
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

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$data['Bulan'] = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
		$data['Perusahaan'] = $this->M_PersetujuanPembongkaranBarang->GetPerusahaan();
		$data['Principle'] = $this->M_PersetujuanPembongkaranBarang->GetPrinciple();
		$data['TipeKonversi'] = $this->M_PersetujuanPembongkaranBarang->GetTipeKonversi();
		$data['Gudang'] = $this->M_PersetujuanPembongkaranBarang->GetGudang();
		$tr_konversi_sku_id = $this->M_Vrbl->Get_NewID();
		$data['tr_konversi_sku_id'] = $tr_konversi_sku_id[0]['NEW_ID'];
		$data['act'] = "add";

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PersetujuanPembongkaranBarang/form', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PersetujuanPembongkaranBarang/s_form', $data);
	}

	public function edit()
	{
		$this->load->model('M_Menu');

		$id = $this->input->get('id');

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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();
		$data['KonversiHeader'] = $this->M_PersetujuanPembongkaranBarang->GetKonversiHeaderById($id);
		$data['KonversiDetail'] = $this->M_PersetujuanPembongkaranBarang->GetKonversiDetailById($id);
		$data['KonversiDetail2'] = $this->M_PersetujuanPembongkaranBarang->GetKonversiDetail2ById($id);
		$data['Perusahaan'] = $this->M_PersetujuanPembongkaranBarang->GetPerusahaan();
		$data['Principle'] = $this->M_PersetujuanPembongkaranBarang->GetPrinciple();
		$data['TipeKonversi'] = $this->M_PersetujuanPembongkaranBarang->GetTipeKonversi();
		$data['Gudang'] = $this->M_PersetujuanPembongkaranBarang->GetGudang();
		$data['act'] = "edit";

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PersetujuanPembongkaranBarang/edit', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PersetujuanPembongkaranBarang/s_form', $data);
	}

	public function detail()
	{
		$this->load->model('M_Menu');

		$id = $this->input->get('id');

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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();
		$data['Bulan'] = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
		$data['KonversiHeader'] = $this->M_PersetujuanPembongkaranBarang->GetKonversiHeaderById($id);
		$data['KonversiDetail'] = $this->M_PersetujuanPembongkaranBarang->GetKonversiDetailById($id);
		$data['KonversiDetail2'] = $this->M_PersetujuanPembongkaranBarang->GetKonversiDetail2ById($id);
		$data['Perusahaan'] = $this->M_PersetujuanPembongkaranBarang->GetPerusahaan();
		$data['Principle'] = $this->M_PersetujuanPembongkaranBarang->GetPrinciple();
		$data['TipeKonversi'] = $this->M_PersetujuanPembongkaranBarang->GetTipeKonversi();
		$data['Gudang'] = $this->M_PersetujuanPembongkaranBarang->GetGudang();
		$data['act'] = "detail";

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PersetujuanPembongkaranBarang/detail', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PersetujuanPembongkaranBarang/script', $data);
	}

	public function GetSelectedSKU()
	{
		$sku_id = $this->input->post('sku_id');
		$sku_stock_expired_date = $this->input->post('sku_stock_expired_date');
		$sku_stock_id = $this->input->post('sku_stock_id');

		$data = $this->M_PersetujuanPembongkaranBarang->GetSelectedSKU($sku_id, $sku_stock_expired_date, $sku_stock_id);

		echo json_encode($data);
	}

	public function GetPrincipleByPerusahaan()
	{
		$perusahaan = $this->input->post('perusahaan');

		$data = $this->M_PersetujuanPembongkaranBarang->GetPrincipleByPerusahaan($perusahaan);

		echo json_encode($data);
	}

	public function search_filter_chosen_sku()
	{
		$perusahaan = $this->input->post('perusahaan');
		$depo_detail_id = $this->input->post('depo_detail_id');
		$principle = $this->input->post('principle');
		$sku_kode = $this->input->post('sku_kode');
		$sku_nama_produk = $this->input->post('sku_nama_produk');
		$sku_satuan = $this->input->post('sku_satuan');
		$tipe_konversi = $this->input->post('tipe_konversi');

		$data = $this->M_PersetujuanPembongkaranBarang->search_filter_chosen_sku($perusahaan, $depo_detail_id, $principle, $sku_kode, $sku_nama_produk, $sku_satuan, $tipe_konversi);

		echo json_encode($data);
	}

	public function insert_tr_konversi_sku_detail2_temp()
	{
		$detail = $this->input->post('detail');

		$this->db->trans_begin();
		//insert ke tr_pemusnahan_stok_detail_draft

		foreach ($detail as $key => $value) {
			$this->M_PersetujuanPembongkaranBarang->delete_tr_konversi_sku_detail2_temp_by_sku_stock_id($value['sku_id'], $value['sku_stock_expired_date']);
		}

		foreach ($detail as $key => $value) {
			$this->M_PersetujuanPembongkaranBarang->insert_tr_konversi_sku_detail2_temp($value);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function insert_tr_konversi_sku()
	{
		// $tr_konversi_sku_id = $this->input->post('tr_konversi_sku_id');
		$client_wms_id = $this->input->post('client_wms_id');
		$depo_detail_id = $this->input->post('depo_detail_id');
		$tipe_konversi_id = $this->input->post('tipe_konversi_id');
		$tr_konversi_sku_kode = $this->input->post('tr_konversi_sku_kode');
		$tr_konversi_sku_tanggal = $this->input->post('tr_konversi_sku_tanggal');
		$tr_konversi_sku_status = $this->input->post('tr_konversi_sku_status');
		$tr_konversi_sku_keterangan = $this->input->post('tr_konversi_sku_keterangan');
		$tr_konversi_sku_tgl_create = $this->input->post('tr_konversi_sku_tgl_create');
		$tr_konversi_sku_who_create = $this->input->post('tr_konversi_sku_who_create');
		$konversi_is_need_approval = $this->input->post('konversi_is_need_approval');
		$karyawan_id = $this->session->userdata('karyawan_id');
		$is_from_do = $this->input->post('is_from_do');

		$detail = $this->input->post('detail');

		$check_konversi_sku = 0;
		$check_qty = 0;

		$approvalParam = "APPRV_UNPACKREPACK_01";
		$depo_id = $this->session->userdata('depo_id');

		// $tr_konversi_sku_id = $this->M_Vrbl->Get_NewID();
		// $tr_konversi_sku_id = $tr_konversi_sku_id[0]['NEW_ID'];


		// foreach ($detail as $key => $value) {
		// 	$cek = $this->M_PersetujuanPembongkaranBarang->CheckKonersiSKU($value['sku_id']);
		// 	if ($cek == 0) {
		// 		$check_konversi_sku++;
		// 	}
		// }

		// foreach ($detail as $key => $value) {
		// 	$cek = $this->M_PersetujuanPembongkaranBarang->CheckQtyKonersiSKU($value['sku_id'], $value['tr_konversi_sku_detail_qty_plan']);
		// 	if ($cek != 0) {
		// 		$check_qty++;
		// 	}
		// }

		// if ($check_konversi_sku == 0) {

		// 	if ($check_qty == 0) {

		// 		$this->db->trans_begin();

		// 		//insert ke tr_pemusnahan_stok_draft
		// 		$this->M_PersetujuanPembongkaranBarang->insert_tr_konversi_sku($tr_konversi_sku_id, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, $tr_konversi_sku_tanggal, $tr_konversi_sku_status, $tr_konversi_sku_tgl_create, $tr_konversi_sku_who_create);

		// 		foreach ($detail as $key => $value) {
		// 			$tr_konversi_sku_detail_id = $this->M_Vrbl->Get_NewID();
		// 			$tr_konversi_sku_detail_id = $tr_konversi_sku_detail_id[0]['NEW_ID'];
		// 			$this->M_PersetujuanPembongkaranBarang->insert_tr_konversi_sku_detail($tr_konversi_sku_detail_id, $tr_konversi_sku_id, $value);

		// 			$detail2_temp = $this->M_PersetujuanPembongkaranBarang->get_tr_konversi_sku_detail2_temp($value['sku_id'], $value['sku_stock_expired_date']);

		// 			foreach ($detail2_temp as $key => $value2) {
		// 				$this->M_PersetujuanPembongkaranBarang->insert_tr_konversi_sku_detail2($tr_konversi_sku_detail_id, $value2);
		// 				$this->M_PersetujuanPembongkaranBarang->check_sku_stock_id($client_wms_id, $depo_detail_id, $value2);
		// 			}
		// 		}

		// 		$this->M_PersetujuanPembongkaranBarang->delete_tr_konversi_sku_detail2_temp();

		// 		if ($konversi_is_need_approval == "1") {
		// 			$this->M_PersetujuanPembongkaranBarang->Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_konversi_sku_id, $tr_konversi_sku_kode, 0, 0);
		// 		}

		// 		if ($this->db->trans_status() === FALSE) {
		// 			$this->db->trans_rollback();
		// 			echo json_encode(0);
		// 		} else {
		// 			$this->db->trans_commit();
		// 			echo json_encode(1);
		// 		}
		// 	} else {
		// 		echo json_encode(2);
		// 	}
		// } else {
		// 	echo json_encode(2);
		// }

		$this->db->trans_begin();

		//insert ke tr_pemusnahan_stok_draft
		// $this->M_PersetujuanPembongkaranBarang->insert_tr_konversi_sku($tr_konversi_sku_id, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, $tr_konversi_sku_tanggal, $tr_konversi_sku_status, $tr_konversi_sku_keterangan, $tr_konversi_sku_tgl_create, $tr_konversi_sku_who_create, $is_from_do);

		$checkPrinciple = "";
		foreach ($detail as $key => $value) {
			if ($value['principle_kode'] != $checkPrinciple) {
				//generate kode
				$date_now = date('Y-m-d h:i:s');
				$param = $tipe_konversi_id;
				$vrbl = $this->M_PersetujuanPembongkaranBarang->Get_Kode($param);
				$prefix = $vrbl->tipe_konversi_kode;
				// get prefik depo
				$depo_id = $this->session->userdata('depo_id');
				$depoPrefix = $this->M_PersetujuanPembongkaranBarang->getDepoPrefix($depo_id);
				$unit = $depoPrefix->depo_kode_preffix;
				$tr_konversi_sku_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);


				$tr_konversi_sku_id = $this->M_Vrbl->Get_NewID();
				$tr_konversi_sku_id = $tr_konversi_sku_id[0]['NEW_ID'];

				$this->M_PersetujuanPembongkaranBarang->insert_tr_konversi_sku($tr_konversi_sku_id, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, $tr_konversi_sku_tanggal, $tr_konversi_sku_status, $tr_konversi_sku_keterangan, $tr_konversi_sku_tgl_create, $tr_konversi_sku_who_create, $is_from_do);
				$checkPrinciple = $value['principle_kode'];

				if ($konversi_is_need_approval == "1") {
					$this->M_PersetujuanPembongkaranBarang->Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_konversi_sku_id, $tr_konversi_sku_kode, 0, 0);
					$this->setPuhserInt();
				}
			}
			// var_dump($tr_konversi_sku_id, $value['principle_kode'], $checkPrinciple);

			$tr_konversi_sku_detail_id = $this->M_Vrbl->Get_NewID();
			$tr_konversi_sku_detail_id = $tr_konversi_sku_detail_id[0]['NEW_ID'];
			$this->M_PersetujuanPembongkaranBarang->insert_tr_konversi_sku_detail($tr_konversi_sku_detail_id, $tr_konversi_sku_id, $value);
		}
		// die;
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function insert_tr_konversi_sku_from_another_menu()
	{
		$tr_konversi_sku_id = $this->input->post('tr_konversi_sku_id');
		$client_wms_id = $this->input->post('client_wms_id');
		$depo_detail_id = $this->input->post('depo_detail_id');
		$tipe_konversi_id = $this->input->post('tipe_konversi_id');
		$tr_konversi_sku_kode = $this->input->post('tr_konversi_sku_kode');
		$tr_konversi_sku_tanggal = $this->input->post('tr_konversi_sku_tanggal');
		$tr_konversi_sku_status = $this->input->post('tr_konversi_sku_status');
		$tr_konversi_sku_keterangan = $this->input->post('tr_konversi_sku_keterangan');
		$tr_konversi_sku_tgl_create = $this->input->post('tr_konversi_sku_tgl_create');
		$tr_konversi_sku_who_create = $this->input->post('tr_konversi_sku_who_create');
		$konversi_is_need_approval = $this->input->post('konversi_is_need_approval');
		$karyawan_id = $this->session->userdata('karyawan_id');
		$is_from_do = $this->input->post('is_from_do');

		$detail = $this->input->post('detail');

		$check_konversi_sku = 0;
		$check_qty = 0;

		$approvalParam = "APPRV_UNPACKREPACK_01";
		$depo_id = $this->session->userdata('depo_id');

		//generate kode
		$date_now = date('Y-m-d h:i:s');
		$param = $tipe_konversi_id;
		$vrbl = $this->M_PersetujuanPembongkaranBarang->Get_Kode($param);
		$prefix = $vrbl->tipe_konversi_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PersetujuanPembongkaranBarang->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$tr_konversi_sku_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$this->db->trans_begin();

		//insert ke tr_pemusnahan_stok_draft
		$this->M_PersetujuanPembongkaranBarang->insert_tr_konversi_sku($tr_konversi_sku_id, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, $tr_konversi_sku_tanggal, $tr_konversi_sku_status, $tr_konversi_sku_keterangan, $tr_konversi_sku_tgl_create, $tr_konversi_sku_who_create, $is_from_do);

		if ($konversi_is_need_approval == "1") {
			$this->M_PersetujuanPembongkaranBarang->Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_konversi_sku_id, $tr_konversi_sku_kode, 0, 0);
			$this->setPuhserInt();
		}

		foreach ($detail as $key => $value) {
			$tr_konversi_sku_detail_id = $this->M_Vrbl->Get_NewID();
			$tr_konversi_sku_detail_id = $tr_konversi_sku_detail_id[0]['NEW_ID'];
			$this->M_PersetujuanPembongkaranBarang->insert_tr_konversi_sku_detail($tr_konversi_sku_detail_id, $tr_konversi_sku_id, $value);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function setPuhserInt()
	{
		senderPusher([
			'message' => 'insert pengajuan approval pembongkaran barang draft'
		]);
	}

	public function update_tr_konversi_sku()
	{
		$tr_konversi_sku_id = $this->input->post('tr_konversi_sku_id');
		$client_wms_id = $this->input->post('client_wms_id');
		$depo_detail_id = $this->input->post('depo_detail_id');
		$tipe_konversi_id = $this->input->post('tipe_konversi_id');
		$tr_konversi_sku_kode = $this->input->post('tr_konversi_sku_kode');
		$tr_konversi_sku_tanggal = $this->input->post('tr_konversi_sku_tanggal');
		$tr_konversi_sku_status = $this->input->post('tr_konversi_sku_status');
		$tr_konversi_sku_keterangan = $this->input->post('tr_konversi_sku_keterangan');
		$tr_konversi_sku_tgl_create = $this->input->post('tr_konversi_sku_tgl_create');
		$tr_konversi_sku_who_create = $this->input->post('tr_konversi_sku_who_create');
		$konversi_is_need_approval = $this->input->post('konversi_is_need_approval');
		$karyawan_id = $this->session->userdata('karyawan_id');
		$tr_konversi_sku_tgl_update = $this->input->post('tr_konversi_sku_tgl_update');

		$detail = $this->input->post('detail');

		$check_konversi_sku = 0;
		$check_qty = 0;

		$approvalParam = "APPRV_UNPACKREPACK_01";
		$depo_id = $this->session->userdata('depo_id');

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "tr_konversi_sku",
			'whereField' => "tr_konversi_sku_id",
			'whereValue' => $tr_konversi_sku_id,
			'fieldDateUpdate' => "tr_konversi_sku_tgl_update",
			'fieldWhoUpdate' => "tr_konversi_sku_who_update",
			'lastUpdated' => $tr_konversi_sku_tgl_update
		]);

		if ($lastUpdatedChecked['status'] == 400) {
			echo json_encode(2);
			return false;
		}

		// foreach ($detail as $key => $value) {
		// 	$cek = $this->M_PersetujuanPembongkaranBarang->CheckKonersiSKU($value['sku_id']);
		// 	if ($cek == 0) {
		// 		$check_konversi_sku++;
		// 	}
		// }

		// foreach ($detail as $key => $value) {
		// 	$cek = $this->M_PersetujuanPembongkaranBarang->CheckQtyKonersiSKU($value['sku_id'], $value['tr_konversi_sku_detail_qty_plan']);
		// 	if ($cek != 0) {
		// 		$check_qty++;
		// 	}
		// }

		// if ($check_konversi_sku == 0) {

		// 	if ($check_qty == 0) {
		// 		$this->db->trans_begin();

		// 		//insert ke tr_pemusnahan_stok_draft
		// 		$this->M_PersetujuanPembongkaranBarang->update_tr_konversi_sku($tr_konversi_sku_id, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, $tr_konversi_sku_tanggal, $tr_konversi_sku_status, $tr_konversi_sku_tgl_create, $tr_konversi_sku_who_create);

		// 		//insert ke tr_pemusnahan_stok_detail_draft

		// 		$this->M_PersetujuanPembongkaranBarang->delete_tr_konversi_sku_detail2($tr_konversi_sku_id);
		// 		$this->M_PersetujuanPembongkaranBarang->delete_tr_konversi_sku_detail($tr_konversi_sku_id);

		// 		foreach ($detail as $key => $value) {
		// 			$tr_konversi_sku_detail_id = $this->M_Vrbl->Get_NewID();
		// 			$tr_konversi_sku_detail_id = $tr_konversi_sku_detail_id[0]['NEW_ID'];
		// 			$this->M_PersetujuanPembongkaranBarang->insert_tr_konversi_sku_detail($tr_konversi_sku_detail_id, $tr_konversi_sku_id, $value);

		// 			$detail2_temp = $this->M_PersetujuanPembongkaranBarang->get_tr_konversi_sku_detail2_temp($value['sku_id'], $value['sku_stock_expired_date']);

		// 			foreach ($detail2_temp as $key => $value2) {
		// 				$this->M_PersetujuanPembongkaranBarang->insert_tr_konversi_sku_detail2($tr_konversi_sku_detail_id, $value2);
		// 			}
		// 		}

		// 		$this->M_PersetujuanPembongkaranBarang->delete_tr_konversi_sku_detail2_temp();

		// 		if ($konversi_is_need_approval == "1") {
		// 			$this->M_PersetujuanPembongkaranBarang->Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_konversi_sku_id, $tr_konversi_sku_kode, 0, 0);
		// 		}

		// 		if ($this->db->trans_status() === FALSE) {
		// 			$this->db->trans_rollback();
		// 			echo json_encode(0);
		// 		} else {
		// 			$this->db->trans_commit();
		// 			echo json_encode(1);
		// 		}
		// 	} else {
		// 		echo json_encode(2);
		// 	}
		// } else {
		// 	echo json_encode(2);
		// }

		$this->db->trans_begin();

		//insert ke tr_pemusnahan_stok_draft
		$this->M_PersetujuanPembongkaranBarang->update_tr_konversi_sku($tr_konversi_sku_id, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, $tr_konversi_sku_tanggal, $tr_konversi_sku_status, $tr_konversi_sku_keterangan, $tr_konversi_sku_tgl_create, $tr_konversi_sku_who_create);

		//insert ke tr_pemusnahan_stok_detail_draft

		$this->M_PersetujuanPembongkaranBarang->delete_tr_konversi_sku_detail2($tr_konversi_sku_id);
		$this->M_PersetujuanPembongkaranBarang->delete_tr_konversi_sku_detail($tr_konversi_sku_id);

		foreach ($detail as $key => $value) {
			$tr_konversi_sku_detail_id = $this->M_Vrbl->Get_NewID();
			$tr_konversi_sku_detail_id = $tr_konversi_sku_detail_id[0]['NEW_ID'];
			$this->M_PersetujuanPembongkaranBarang->insert_tr_konversi_sku_detail($tr_konversi_sku_detail_id, $tr_konversi_sku_id, $value);
		}

		if ($konversi_is_need_approval == "1") {
			$this->M_PersetujuanPembongkaranBarang->Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_konversi_sku_id, $tr_konversi_sku_kode, 0, 0);
			$this->setPuhserInt();
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function GetPersetujuanPembongkaranBarangByFilter()
	{
		$tgl = explode(" - ", $this->input->post('tgl'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$do_no = $this->input->post('do_no');
		$customer = $this->input->post('customer');
		$alamat = $this->input->post('alamat');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$tipe_layanan = $this->input->post('tipe_layanan');
		$status = $this->input->post('status');
		$tipe = $this->input->post('tipe');

		$data = $this->M_PersetujuanPembongkaranBarang->GetPersetujuanPembongkaranBarangByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe);

		echo json_encode($data);
	}

	public function GetPerusahaanById()
	{
		$id = $this->input->post('id');
		$data = $this->M_PersetujuanPembongkaranBarang->GetPerusahaanById($id);

		echo json_encode($data);
	}

	public function proses_konversi_sku_pack_unpack()
	{
		$sku_id = $this->input->post('sku_id');
		$qty_plan = $this->input->post('qty_plan');
		$data = $this->M_PersetujuanPembongkaranBarang->proses_konversi_sku_pack_unpack($sku_id, $qty_plan);

		echo json_encode($data);
	}

	public function GetSKUKonversiBySKU()
	{
		$sku_stock_id = $this->input->post('sku_stock_id');
		$sku_id = $this->input->post('sku_id');
		$sku_stock_expired_date = $this->input->post('sku_stock_expired_date');
		$satuan = $this->input->post('satuan');
		$tipe_konversi = $this->input->post('tipe_konversi');

		$data = $this->M_PersetujuanPembongkaranBarang->GetSKUKonversiBySKU($sku_stock_id, $sku_id, $sku_stock_expired_date, $satuan, $tipe_konversi);

		echo json_encode($data);
	}

	public function GetSKUKonversiBySKU2()
	{
		$tr_konversi_sku_detail_id = $this->input->post('tr_konversi_sku_detail_id');

		$data = $this->M_PersetujuanPembongkaranBarang->GetSKUKonversiBySKU2($tr_konversi_sku_detail_id);

		echo json_encode($data);
	}

	public function GetKonversiSKUByFilter()
	{
		$tgl = explode(" - ", $this->input->post('tgl'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$perusahaan = $this->input->post('perusahaan');
		$status = $this->input->post('status');
		$tipe = $this->input->post('tipe');
		$principle = $this->input->post('principle');

		$data = $this->M_PersetujuanPembongkaranBarang->GetKonversiSKUByFilter($tgl1, $tgl2, $perusahaan, $status, $tipe, $principle);

		echo json_encode($data);
	}

	public function delete_tr_konversi_sku_detail2_temp()
	{
		$this->db->trans_begin();

		$this->M_PersetujuanPembongkaranBarang->delete_tr_konversi_sku_detail2_temp();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}
}
