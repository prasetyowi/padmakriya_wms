<?php

class PembongkaranBarang extends CI_Controller
{
	//a

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

		$this->MenuKode = "128006000";
		$this->load->model('WMS/M_PembongkaranBarang', 'M_PembongkaranBarang');
		$this->load->model('M_ClientPt');
		$this->load->model('M_Area');
		$this->load->model('M_StatusProgress');
		$this->load->model('M_SKU');
		$this->load->model('M_Principle');
		$this->load->model('M_TipeDeliveryOrder');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
	}

	public function PembongkaranMenu()
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

		$data['Perusahaan'] = $this->M_PembongkaranBarang->GetPerusahaan();
		$data['TipeKonversi'] = $this->M_PembongkaranBarang->GetTipeKonversi();
		$data['status'] = $this->M_PembongkaranBarang->getStatus();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PembongkaranBarang/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PembongkaranBarang/script', $data);
	}

	public function getKodeAutoComplete()
	{
		$valueParams = $this->input->get('params');
		$depo_detail_id_tujuan = $this->input->get('depo_detail_id_tujuan');
		$result = $this->M_PembongkaranBarang->getKodeAutoComplete($valueParams, $depo_detail_id_tujuan);

		echo json_encode($result);
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$data['Bulan'] = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
		$data['KonversiSKU'] = $this->M_PembongkaranBarang->GetKonversiSKU();
		$data['act'] = "add";

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PembongkaranBarang/form', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PembongkaranBarang/s_form', $data);
	}

	public function edit()
	{
		$tes = $this->db->query("SELECT * FROM tr_konversi_sku_detail2_temp")->result_array();
		// var_dump(count($tes));
		// die;
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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();
		$data['KonversiHeader'] = $this->M_PembongkaranBarang->GetKonversiEditHeaderById($id);
		$data['KonversiDetail'] = $this->M_PembongkaranBarang->GetKonversiEditDetailById($id);
		$data['KonversiDetail2'] = $this->M_PembongkaranBarang->GetKonversiEditDetail2ById($id);

		$arr_detail1 = [];
		foreach ($data['KonversiDetail'] as $value) {
			$sumDetail = $this->M_PembongkaranBarang->sumDetailById($value['tr_konversi_sku_detail_id']);
			// $arr_detail1[] = $sumDetail;

			foreach ($sumDetail as $key => $value) {
				$arr_detail1[] = array('total1' => $value['total1'], 'tr_konversi_sku_detail_id' => $value['tr_konversi_sku_detail_id']);
			}
		}

		$arr_detail2 = [];
		foreach ($data['KonversiDetail2'] as $value) {
			if ($value['tr_konversi_sku_detail_id'] != null) {
				$sumDetail2 = $this->M_PembongkaranBarang->sumDetail2ById($value['tr_konversi_sku_detail_id']);

				foreach ($sumDetail2 as $key => $value) {
					$arr_detail2[] = array('total2' => $value['total2'], 'tr_konversi_sku_detail_id' => $value['tr_konversi_sku_detail_id']);
				}
			} else {
				$arr_detail2 = [];
			}
		}
		// var_dump($arr_detail2);
		// die;
		$data['act'] = "edit";
		$data['arr_detail1'] = $arr_detail1;
		$data['arr_detail2'] = $arr_detail2;

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PembongkaranBarang/edit', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PembongkaranBarang/s_form', $data);
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
		$data['KonversiHeader'] = $this->M_PembongkaranBarang->GetKonversiEditHeaderById($id);
		$data['KonversiDetail'] = $this->M_PembongkaranBarang->GetKonversiEditDetailById($id);
		$data['KonversiDetail2'] = $this->M_PembongkaranBarang->GetKonversiEditDetail2ById($id);
		$data['act'] = "detail";

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PembongkaranBarang/detail', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PembongkaranBarang/script', $data);
	}

	public function cetak()
	{
		$id = $this->input->get('id');

		$response = $this->M_PembongkaranBarang->getDataKonversiByID($id);

		$this->load->view('WMS/PembongkaranBarang/cetak', $response);

		// $file_pdf = 'Pembongkaran barang';

		// // setting paper
		// $paper = "A4";

		// //orientasi paper potrait / landscape
		// $orientation = "landscape";

		// $html = $this->load->view('WMS/PembongkaranBarang/cetak', $response, true);

		// $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}

	public function GetKonversiById()
	{
		$id = $this->input->post('tr_konversi_sku_id');

		$data['KonversiHeader'] = $this->M_PembongkaranBarang->GetKonversiHeaderById($id);
		$data['KonversiDetail'] = $this->M_PembongkaranBarang->GetKonversiDetailById($id);

		echo json_encode($data);
	}

	public function GetKonversiDetailPallet()
	{
		$tr_konversi_sku_detail_id = $this->input->post('tr_konversi_sku_detail_id');
		$sku_id = $this->input->post('sku_id');
		$sku_stock_expired_date = $this->input->post('sku_stock_expired_date');
		$sku_stock_id = $this->input->post('sku_stock_id');

		$data = $this->M_PembongkaranBarang->GetKonversiDetailPallet($tr_konversi_sku_detail_id, $sku_id, $sku_stock_expired_date, $sku_stock_id);

		echo json_encode($data);
	}

	public function GetKonversiDetailPallet2()
	{
		$tr_konversi_sku_detail_id = $this->input->post('tr_konversi_sku_detail_id');

		$data = $this->M_PembongkaranBarang->GetKonversiDetailPallet2($tr_konversi_sku_detail_id);

		echo json_encode($data);
	}

	public function GetPrincipleByPerusahaan()
	{
		$perusahaan = $this->input->post('perusahaan');

		$data = $this->M_PembongkaranBarang->GetPrincipleByPerusahaan($perusahaan);

		echo json_encode($data);
	}

	public function search_filter_chosen_sku()
	{
		$perusahaan = $this->input->post('perusahaan');
		$principle = $this->input->post('principle');
		$sku_kode = $this->input->post('sku_kode');
		$sku_nama_produk = $this->input->post('sku_nama_produk');
		$sku_satuan = $this->input->post('sku_satuan');

		$data = $this->M_PembongkaranBarang->search_filter_chosen_sku($perusahaan, $principle, $sku_kode, $sku_nama_produk, $sku_satuan);

		echo json_encode($data);
	}

	public function insert_tr_konversi_sku_detail2_temp()
	{
		$detail = $this->input->post('detail');

		$this->db->trans_begin();
		//insert ke tr_pemusnahan_stok_detail_draft

		foreach ($detail as $key => $value) {
			$this->M_PembongkaranBarang->delete_tr_konversi_sku_detail2_temp_by_sku_stock_id($value['sku_id'], $value['sku_stock_expired_date'], $value['pallet_id_asal']);
			$this->M_PembongkaranBarang->insert_tr_konversi_sku_detail2_temp($value);
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
		$tr_konversi_sku_tgl_update = $this->input->post('tr_konversi_sku_tgl_update');
		$karyawan_id = $this->session->userdata('karyawan_id');
		$detail = $this->input->post('detail');

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "tr_konversi_sku",
			'whereField' => "tr_konversi_sku_id",
			'whereValue' => $tr_konversi_sku_id,
			'fieldDateUpdate' => "tr_konversi_sku_tgl_update",
			'fieldWhoUpdate' => "tr_konversi_sku_who_update",
			'lastUpdated' => $tr_konversi_sku_tgl_update
		]);

		if ($lastUpdatedChecked['status'] == 400) {
			echo json_encode(3);
			return false;
		}

		$check_konversi_sku = 0;
		$check_qty = 0;

		$approvalParam = "APPRV_UNPACKREPACK_01";
		$depo_id = $this->session->userdata('depo_id');

		// $tr_konversi_sku_id = $this->M_Vrbl->Get_NewID();
		// $tr_konversi_sku_id = $tr_konversi_sku_id[0]['NEW_ID'];

		//generate kode
		$date_now = date('Y-m-d h:i:s');
		$param = $tipe_konversi_id;
		$vrbl = $this->M_PembongkaranBarang->Get_Kode($param);
		$prefix = $vrbl->tipe_konversi_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PembongkaranBarang->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$tr_konversi_sku_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		foreach ($detail as $key => $value) {
			$cek = $this->M_PembongkaranBarang->CheckKonversiSKU($value['tr_konversi_sku_detail_id']);
			if ($cek == 0) {
				$check_konversi_sku++;
			}
		}

		foreach ($detail as $key => $value) {
			$cek = $this->M_PembongkaranBarang->CheckQtyKonersiSKU($value['tr_konversi_sku_detail_id']);
			if ($cek != 0) {
				$check_qty++;
			}
		}

		if ($check_konversi_sku == 0) {
			if ($check_qty == 0) {
				$this->db->trans_begin();

				$this->M_PembongkaranBarang->update_tr_konversi_sku($tr_konversi_sku_id, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, $tr_konversi_sku_tanggal, $tr_konversi_sku_status, $tr_konversi_sku_keterangan, $tr_konversi_sku_tgl_create, $tr_konversi_sku_who_create);

				foreach ($detail as $key => $value) {

					$detail2_temp = $this->M_PembongkaranBarang->get_tr_konversi_sku_detail2_temp($value['tr_konversi_sku_detail_id'], $value['pallet_id_asal']);

					foreach ($detail2_temp as $key => $value2) {
						// $this->M_PembongkaranBarang->check_sku_stock_id($client_wms_id, $depo_detail_id, $value2);
						// $sku_stock_id_tujuan = $this->M_PembongkaranBarang->get_sku_stock_id_tujuan($client_wms_id, $depo_detail_id, $value2['sku_id'], $value2['sku_stock_expired_date'])->sku_stock_id;
						$sku_stock_id_tujuan = "";

						// $this->M_PembongkaranBarang->update_pallet_detail($sku_stock_id_tujuan, $value2);
						$this->M_PembongkaranBarang->insert_tr_konversi_sku_detail2($sku_stock_id_tujuan, $value2);
						// $this->M_PembongkaranBarang->insert_tr_konversi_sku_detail3($sku_stock_id_tujuan, $value2);
					}
				}

				$this->M_PembongkaranBarang->delete_tr_konversi_sku_detail2_temp();

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					echo json_encode(0);
				} else {
					$this->db->trans_commit();
					echo json_encode(1);
				}
			} else {
				echo json_encode(2);
			}
		} else {
			echo json_encode(2);
		}
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
		$tr_konversi_sku_who_create = $this->input->post('tr_konversi_sku_who_create');
		$tr_konversi_sku_tgl_update = $this->input->post('tr_konversi_sku_tgl_update');
		$karyawan_id = $this->session->userdata('karyawan_id');

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
			echo json_encode(3);
			return false;
		}

		foreach ($detail as $key => $value) {
			$cek = $this->M_PembongkaranBarang->CheckKonversiSKU($value['tr_konversi_sku_detail_id']);
			if ($cek == 0) {
				$check_konversi_sku++;
			}
		}

		foreach ($detail as $key => $value) {
			$cek = $this->M_PembongkaranBarang->CheckQtyKonersiSKU($value['tr_konversi_sku_detail_id']);
			if ($cek != 0) {
				$check_qty++;
			}
		}

		if ($check_konversi_sku == 0) {

			if ($check_qty == 0) {

				$this->db->trans_begin();

				$this->M_PembongkaranBarang->update_tr_konversi_sku($tr_konversi_sku_id, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, $tr_konversi_sku_tanggal, $tr_konversi_sku_status, $tr_konversi_sku_keterangan, $tr_konversi_sku_tgl_create, $tr_konversi_sku_who_create);

				foreach ($detail as $key => $value) {

					$detail2_temp = $this->M_PembongkaranBarang->get_tr_konversi_sku_detail2_temp($value['tr_konversi_sku_detail_id'], $value['pallet_id_asal']);

					$this->M_PembongkaranBarang->delete_tr_konversi_sku_detail2($value['tr_konversi_sku_detail_id'], $value['pallet_id_asal']);
					// $this->M_PembongkaranBarang->delete_tr_konversi_sku_detail3($value['tr_konversi_sku_detail_id'], $value['pallet_id_asal']);

					foreach ($detail2_temp as $key => $value2) {
						// $this->M_PembongkaranBarang->check_sku_stock_id($client_wms_id, $depo_detail_id, $value2);
						// $sku_stock_id_tujuan = $this->M_PembongkaranBarang->get_sku_stock_id_tujuan($client_wms_id, $depo_detail_id, $value2['sku_id'], $value2['sku_stock_expired_date'])->sku_stock_id;
						$sku_stock_id_tujuan = "";

						// $this->M_PembongkaranBarang->update_pallet_detail($sku_stock_id_tujuan, $value2);
						$this->M_PembongkaranBarang->insert_tr_konversi_sku_detail2($sku_stock_id_tujuan, $value2);
						// $this->M_PembongkaranBarang->insert_tr_konversi_sku_detail3($sku_stock_id_tujuan, $value2);
					}
				}

				$this->M_PembongkaranBarang->delete_tr_konversi_sku_detail2_temp();

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					echo json_encode(0);
				} else {
					$this->db->trans_commit();
					echo json_encode(1);
				}
			} else {
				echo json_encode(2);
			}
		} else {
			echo json_encode(2);
		}
	}

	public function konfirmasi_tr_konversi_sku()
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
		$tr_konversi_sku_tgl_update = $this->input->post('tr_konversi_sku_tgl_update');
		$karyawan_id = $this->session->userdata('karyawan_id');

		$detail = $this->input->post('detail');

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "tr_konversi_sku",
			'whereField' => "tr_konversi_sku_id",
			'whereValue' => $tr_konversi_sku_id,
			'fieldDateUpdate' => "tr_konversi_sku_tgl_update",
			'fieldWhoUpdate' => "tr_konversi_sku_who_update",
			'lastUpdated' => $tr_konversi_sku_tgl_update
		]);

		if ($lastUpdatedChecked['status'] == 400) {
			echo json_encode(3);
			return false;
		}

		$check_konversi_sku = 0;
		$check_qty = 0;

		$identity = "PEMBONGKARAN";
		$approvalParam = "APPRV_UNPACKREPACK_01";
		$depo_id = $this->session->userdata('depo_id');

		foreach ($detail as $key => $value) {
			$cek = $this->M_PembongkaranBarang->CheckKonversiSKU($value['tr_konversi_sku_detail_id']);
			if ($cek == 0) {
				$check_konversi_sku++;
			}
		}

		foreach ($detail as $key => $value) {
			$cek = $this->M_PembongkaranBarang->CheckQtyKonersiSKU($value['tr_konversi_sku_detail_id']);
			if ($cek != 0) {
				$check_qty++;
			}
		}

		if ($check_konversi_sku == 0) {
			if ($check_qty == 0) {
				$this->db->trans_begin();
				$this->M_PembongkaranBarang->update_tr_konversi_sku($tr_konversi_sku_id, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, $tr_konversi_sku_tanggal, $tr_konversi_sku_status, $tr_konversi_sku_keterangan, $tr_konversi_sku_tgl_create, $tr_konversi_sku_who_create);
				$this->M_PembongkaranBarang->check_sku_stock_id($client_wms_id, $depo_detail_id, $tr_konversi_sku_id);

				foreach ($detail as $key => $value) {
					$detail2_temp = $this->M_PembongkaranBarang->get_tr_konversi_sku_detail2_temp($value['tr_konversi_sku_detail_id'], $value['pallet_id_asal']);
					// echo var_dump($detail2_temp);
					$this->M_PembongkaranBarang->delete_tr_konversi_sku_detail2($value['tr_konversi_sku_detail_id'], $value['pallet_id_asal']);
					// $this->M_PembongkaranBarang->delete_tr_konversi_sku_detail3($value['tr_konversi_sku_detail_id'], $value['pallet_id_asal']);
					foreach ($detail2_temp as $key => $value2) {
						$sku_stock_id_tujuan = $this->M_PembongkaranBarang->get_sku_stock_id_tujuan($client_wms_id, $depo_detail_id, $value2['sku_id'], $value2['sku_stock_expired_date'])->sku_stock_id;
						$this->M_PembongkaranBarang->update_pallet_detail($sku_stock_id_tujuan, $value2);
						$this->M_PembongkaranBarang->insert_tr_konversi_sku_detail2($sku_stock_id_tujuan, $value2);
						// $this->M_PembongkaranBarang->insert_tr_konversi_sku_detail3($sku_stock_id_tujuan, $value2);
					}
				}

				$this->M_PembongkaranBarang->delete_tr_konversi_sku_detail2_temp();
				$this->M_PembongkaranBarang->Exec_proses_posting_sku_stock_card($identity, $tr_konversi_sku_id, $tr_konversi_sku_who_create);

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					echo json_encode(0);
				} else {
					$this->db->trans_commit();
					echo json_encode(1);
				}
			} else {
				echo json_encode(2);
			}
		} else {
			echo json_encode(2);
		}
	}

	public function GetPembongkaranBarangByFilter()
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

		$data = $this->M_PembongkaranBarang->GetPembongkaranBarangByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe);

		echo json_encode($data);
	}

	public function GetPerusahaanById()
	{
		$id = $this->input->post('id');
		$data = $this->M_PembongkaranBarang->GetPerusahaanById($id);

		echo json_encode($data);
	}

	public function proses_konversi_sku_pack_unpack()
	{
		$sku_id = $this->input->post('sku_id');
		$qty_plan = $this->input->post('qty_plan');
		$data = $this->M_PembongkaranBarang->proses_konversi_sku_pack_unpack($sku_id, $qty_plan);

		echo json_encode($data);
	}

	public function GetSKUKonversiBySKU()
	{
		$sku_id = $this->input->post('sku_id');
		$sku_stock_expired_date = $this->input->post('sku_stock_expired_date');
		$satuan = $this->input->post('satuan');
		$tipe_konversi = $this->input->post('tipe_konversi');

		$data = $this->M_PembongkaranBarang->GetSKUKonversiBySKU($sku_id, $sku_stock_expired_date, $satuan, $tipe_konversi);

		echo json_encode($data);
	}

	public function GetSKUKonversiBySKU2()
	{
		$tr_konversi_sku_detail_id = $this->input->post('tr_konversi_sku_detail_id');

		$data = $this->M_PembongkaranBarang->GetSKUKonversiBySKU2($tr_konversi_sku_detail_id);

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

		$data = $this->M_PembongkaranBarang->GetKonversiSKUByFilter($tgl1, $tgl2, $perusahaan, $status, $tipe);

		echo json_encode($data);
	}

	public function delete_tr_konversi_sku_detail2_temp()
	{
		$this->db->trans_begin();

		$this->M_PembongkaranBarang->delete_tr_konversi_sku_detail2_temp();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function delete_tr_konversi_sku_detail2_temp_by_pallet()
	{
		$this->db->trans_begin();

		$tr_konversi_sku_detail_id = $this->input->post('tr_konversi_sku_detail_id');
		$pallet_id = $this->input->post('pallet_id');

		$this->M_PembongkaranBarang->delete_tr_konversi_sku_detail2_temp_by_pallet($tr_konversi_sku_detail_id, $pallet_id);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function update_tr_konversi_sku_detail2_temp_by_pallet()
	{
		$this->db->trans_begin();

		$tr_konversi_sku_detail_id = $this->input->post('tr_konversi_sku_detail_id');
		$pallet_id = $this->input->post('pallet_id');
		$tr_konversi_sku_detail2_qty = $this->input->post('tr_konversi_sku_detail2_qty');
		$tr_konversi_sku_detail2_qty_result = $this->input->post('tr_konversi_sku_detail2_qty_result');

		$this->M_PembongkaranBarang->update_tr_konversi_sku_detail2_temp_by_pallet($tr_konversi_sku_detail_id, $pallet_id, $tr_konversi_sku_detail2_qty, $tr_konversi_sku_detail2_qty_result);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function check_kode_pallet()
	{
		$kode_pallet = $this->input->post('kode_pallet');
		$sku_stock_id = $this->input->post('sku_stock_id');

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PembongkaranBarang->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		$kode = $unit . "/" . $kode_pallet;

		$pallet_id = $this->M_PembongkaranBarang->check_kode_pallet_and_qty($kode, $sku_stock_id);
		if ($pallet_id == null) {
			echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan", 'pallet_id' => "", 'kode' => ""));
		} else {
			if ($pallet_id->sku_stock_qty > 0) {

				echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> <span class='text-success'>Valid</span>", 'pallet_id' => $pallet_id->pallet_id, 'kode' => $kode));
			} else {
				echo json_encode(array('type' => 202, 'message' => "Kode pallet <strong>" . $kode . "</strong> <span class='text-danger'>Stock Qty Kosong</span>", 'pallet_id' => $pallet_id->pallet_id, 'kode' => $kode));
			}
		}
	}

	public function Getkonversiskudetail2()
	{
		$id = $this->input->post('tr_konversi_sku_detail_id');
		$pallet_id = $this->input->post('pallet_id');
		$satuan = $this->input->post('satuan');
		$tipe_konversi = $this->input->post('tipe_konversi');

		$data['header'] = $this->M_PembongkaranBarang->Getkonversiskuheader2($id);
		$data['detail'] = $this->M_PembongkaranBarang->Getkonversiskudetail2($id, $pallet_id, $satuan, $tipe_konversi);

		echo json_encode($data);
	}

	public function GetViewkonversiskudetail2()
	{
		$id = $this->input->post('tr_konversi_sku_detail2_id');

		$data['header'] = $this->M_PembongkaranBarang->GetViewkonversiskuheader2($id);
		$data['detail'] = $this->M_PembongkaranBarang->GetViewkonversiskudetail2($id);

		echo json_encode($data);
	}

	public function check_konversi_sku_detail2()
	{
		$check_konversi_sku = 0;
		$check_qty = 0;

		$detail = $this->input->post('detail');

		foreach ($detail as $key => $value) {
			$cek = $this->M_PembongkaranBarang->CheckKonersiSKU($value['sku_id']);
			if ($cek == 0) {
				$check_konversi_sku++;
			}
		}

		foreach ($detail as $key => $value) {
			$cek = $this->M_PembongkaranBarang->CheckQtyKonersiSKU($value['sku_id'], $value['tr_konversi_sku_detail_qty_plan']);
			if ($cek != 0) {
				$check_qty++;
			}
		}

		if ($check_konversi_sku == 0) {
			if ($check_qty == 0) {
				echo json_encode(1);
			} else {
				echo json_encode(2);
			}
		} else {
			echo json_encode(2);
		}
	}
}
