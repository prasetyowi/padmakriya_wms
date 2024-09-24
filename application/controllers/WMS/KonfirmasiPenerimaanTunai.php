<?php

//require_once APPPATH . 'core/ParentController.php';

class KonfirmasiPenerimaanTunai extends CI_Controller
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

		$this->MenuKode = "109005000";
		$this->load->model('WMS/M_KonfirmasiPenerimaanTunai', 'M_KonfirmasiPenerimaanTunai');
		$this->load->model('M_Vrbl');
	}

	public function KonfirmasiPenerimaanTunaiMenu()
	{
		$this->load->model('M_Menu');

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
		$data['act'] = "index";

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css'
		);

		$data['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$data['TipePembayaran'] = $this->M_KonfirmasiPenerimaanTunai->Get_tipe_pembayaran();

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/KonfirmasiPenerimaanTunai/KonfirmasiPenerimaanTunai', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/KonfirmasiPenerimaanTunai/S_KonfirmasiPenerimaanTunai', $data);
	}

	public function ClosingPenerimaan()
	{
		$this->load->model('M_Menu');

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

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css'
		);

		$data['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$id = $this->input->get("id");
		$data['id'] = $id;
		$data['act'] = "add";

		$data['TipePembayaran'] = $this->M_KonfirmasiPenerimaanTunai->Get_tipe_pembayaran();
		$data['GetArea'] = $this->M_KonfirmasiPenerimaanTunai->Get_Area($id);
		$data['lastUpdate'] = $this->M_KonfirmasiPenerimaanTunai->Get_LastUpdateDOBatch($id);

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/KonfirmasiPenerimaanTunai/ClosingPenerimaan', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/KonfirmasiPenerimaanTunai/S_KonfirmasiPenerimaanTunai', $data);
	}
	public function ViewClosingPenerimaan()
	{
		$this->load->model('M_Menu');

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

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css'
		);

		$data['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$id = $this->input->get("id");
		$data['id'] = $id;
		$data['act'] = "view";

		$data['TipePembayaran'] = $this->M_KonfirmasiPenerimaanTunai->Get_tipe_pembayaran();
		$data['GetArea'] = $this->M_KonfirmasiPenerimaanTunai->Get_Area($id);
		$data['lastUpdate'] = $this->M_KonfirmasiPenerimaanTunai->Get_LastUpdateDOBatch($id);

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/KonfirmasiPenerimaanTunai/ViewClosingPenerimaan', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/KonfirmasiPenerimaanTunai/S_KonfirmasiPenerimaanTunai', $data);
	}

	public function EditClosingPenerimaan()
	{
		$this->load->model('M_Menu');

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

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['act'] = "edit";

		$data['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css'
		);

		$data['js_files'] = array(
			Get_Assets_Url() . 'assets/js/bootstrap4-toggle.min.js',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.js',
			Get_Assets_Url() . 'assets/js/bootstrap-input-spinner.js',
			Get_Assets_Url() . 'vendors/Chart.js/dist/Chart.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'
			//Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/semantic.min.js'
		);

		$id = $this->input->get("id");
		$data['id'] = $id;

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/KonfirmasiPenerimaanTunai/ClosingPenerimaan', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/KonfirmasiPenerimaanTunai/S_EditKonfirmasiPenerimaanTunai', $data);
	}

	public function GetSuratTugasPengirimanMenu()
	{
		$this->load->model('WMS/M_KonfirmasiPenerimaanTunai', 'M_KonfirmasiPenerimaanTunai');
		$this->load->model('M_function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Menu');

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$data['Driver'] = $this->M_KonfirmasiPenerimaanTunai->Get_Driver();
		$data['StatusFDJR'] = $this->M_KonfirmasiPenerimaanTunai->Get_StatusFDJR();

		echo json_encode($data);
	}

	public function GetSuratTugasPengirimanTable()
	{
		$tgl = explode(" - ", $this->input->post('Tgl_FDJR'));
		$tgl_FDJR1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl_FDJR2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$No_FDJR = $this->input->post('No_FDJR');
		$karyawan_id = $this->input->post('karyawan_id');
		$Status_FDJR = $this->input->post('Status_FDJR');

		$data['SuratTugasPengiriman'] = $this->M_KonfirmasiPenerimaanTunai->Get_SuratTugasPengiriman($tgl_FDJR1, $tgl_FDJR2, $No_FDJR, $karyawan_id, $Status_FDJR);

		echo json_encode($data);
	}

	public function GetDeliveryOrderBatchByID()
	{
		$id = $this->input->post('id');

		$data['DO_Batch'] = $this->M_KonfirmasiPenerimaanTunai->GetDeliveryOrderBatchByID($id);

		echo json_encode($data);
	}

	public function GetListPembayaranByID()
	{
		$id = $this->input->post('id');

		$list_pembayaran = $this->M_KonfirmasiPenerimaanTunai->GetListPembayaranByID($id);
		$List_DO_Tunai = $this->M_KonfirmasiPenerimaanTunai->GetListDOTunaiByID($id);
		$List_DO_Pembayaran = $this->M_KonfirmasiPenerimaanTunai->GetListDOPembayaranByID($id);
		$List_Keterangan = $this->M_KonfirmasiPenerimaanTunai->GetKeterangan($id);

		$response = [
			'list_pembayaran' => $list_pembayaran,
			'List_DO_Tunai' => $List_DO_Tunai,
			'List_DO_Pembayaran' => $List_DO_Pembayaran,
			'List_Keterangan' => $List_Keterangan,
			// 'tipe_pembayaran' => $tipe_pembayaran
		];

		echo json_encode($response);
	}

	public function GetListDODetail()
	{
		$id = $this->input->post('id');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');

		$data = $this->M_KonfirmasiPenerimaanTunai->GetListDODetail($id, $tipe_pembayaran);

		echo json_encode($data);
	}

	public function GetListDOByID()
	{
		$id = $this->input->post('id');

		$data['List_DO'] = $this->M_KonfirmasiPenerimaanTunai->GetListDOByID($id);
		// $data['List_DO_Tunai'] = $this->M_KonfirmasiPenerimaanTunai->GetListDOTunaiByID($id);
		// $data['List_DO_Pembayaran'] = $this->M_KonfirmasiPenerimaanTunai->GetListDOPembayaranByID($id);

		echo json_encode($data);
	}

	public function GetTipeBiaya()
	{
		$data['tipe_biaya'] = $this->M_KonfirmasiPenerimaanTunai->GetTipeBiaya();

		echo json_encode($data);
	}

	// public function saveAllDO()
	// {
	// 	$id_do_batch = $this->input->post('id_do_batch');
	// 	$jumlahTerima = $this->input->post('jumlahTerima');
	// 	$do_pembayaran = $this->input->post('do_pembayaran');
	// 	$lastUpdate = $this->input->post('lastUpdate');
	// 	$delivery_order_batch_nominal_invoice = $this->input->post('delivery_order_batch_nominal_invoice');
	// 	$delivery_order_batch_nominal_selisih = intval($jumlahTerima) - intval($delivery_order_batch_nominal_invoice);
	// 	// $arr_add_biaya = $this->input->post('arr_add_biaya');

	// 	$isNotSameLastUpd = false;
	// 	$lastUpdateBaru = $this->M_KonfirmasiPenerimaanTunai->Get_LastUpdateDOBatch($id_do_batch);
	// 	if ($lastUpdate == $lastUpdateBaru['tglUpd']) $isNotSameLastUpd = true;

	// 	if ($isNotSameLastUpd) {
	// 		$this->db->trans_begin();

	// 		$this->M_KonfirmasiPenerimaanTunai->UpdateDOBatch($id_do_batch, $jumlahTerima, $delivery_order_batch_nominal_invoice, $delivery_order_batch_nominal_selisih);

	// 		$this->M_KonfirmasiPenerimaanTunai->delete_delivery_order_payment($id_do_batch);

	// 		foreach ($do_pembayaran as $key => $value) {
	// 			$delivery_order_payment_id = $this->M_Vrbl->Get_NewID();
	// 			$delivery_order_payment_id = $delivery_order_payment_id[0]['NEW_ID'];

	// 			$delivery_order_batch_id = $id_do_batch;
	// 			$tipe_pembayaran_id = $value['tipe_pembayaran_id'];
	// 			$delivery_order_payment_value = $value['delivery_order_payment_value'];
	// 			$delivery_order_reff_no = $value['delivery_order_reff_no'];
	// 			$delivery_order_payment_tgl_jatuh_tempo = $value['delivery_order_payment_tgl_jatuh_tempo'];

	// 			$this->M_KonfirmasiPenerimaanTunai->Insert_delivery_order_payment($delivery_order_payment_id, $delivery_order_batch_id, $tipe_pembayaran_id, $delivery_order_payment_value, $delivery_order_reff_no, $delivery_order_payment_tgl_jatuh_tempo);

	// 			foreach ($value['detail'] as $key2 => $value2) {
	// 				// if ($tipe_pembayaran_id == $value2['tipe_pembayaran_id'] && $delivery_order_reff_no == $value2['delivery_order_reff_no']) {
	// 				// 	$delivery_order_payment_detail_id = $this->M_Vrbl->Get_NewID();
	// 				// 	$delivery_order_payment_detail_id = $delivery_order_payment_detail_id[0]['NEW_ID'];

	// 				// 	$delivery_order_id = $value2['delivery_order_id'];

	// 				// 	$this->M_KonfirmasiPenerimaanTunai->Insert_delivery_order_payment_detail($delivery_order_payment_detail_id, $delivery_order_payment_id, $delivery_order_id);
	// 				// }

	// 				$delivery_order_payment_detail_id = $this->M_Vrbl->Get_NewID();
	// 				$delivery_order_payment_detail_id = $delivery_order_payment_detail_id[0]['NEW_ID'];

	// 				$delivery_order_id = $value2['delivery_order_id'];

	// 				$this->M_KonfirmasiPenerimaanTunai->Insert_delivery_order_payment_detail($delivery_order_payment_detail_id, $delivery_order_payment_id, $delivery_order_id);
	// 			}
	// 		}

	// 		if ($this->db->trans_status() === FALSE) {
	// 			$this->db->trans_rollback();
	// 			echo json_encode(['type' => 202, 'data' => null]);
	// 		} else {
	// 			$this->db->trans_commit();
	// 			echo json_encode(['type' => 200, 'data' => null]);
	// 		}
	// 	} else {
	// 		echo json_encode(['type' => 203, 'data' => null]);
	// 	}


	// 	// foreach ($arr_do as $key => $value) {
	// 	// 	if ($value["delivery_order_nominal_terima_tunai"] != '') {
	// 	// 		$delivery_order_id = $value["delivery_order_id"];
	// 	// 		$delivery_order_nominal_terima_tunai = $value["delivery_order_nominal_terima_tunai"];

	// 	// 		$this->M_KonfirmasiPenerimaanTunai->UpdateDO($delivery_order_id, $delivery_order_nominal_terima_tunai);
	// 	// 	}
	// 	// }

	// 	// if ($arr_add_biaya != null) {
	// 	// 	foreach ($arr_add_biaya as $key => $value) {
	// 	// 		$this->M_KonfirmasiPenerimaanTunai->deleteDODetail3($value['do_id']);
	// 	// 	}

	// 	// 	foreach ($arr_add_biaya as $key => $value) {
	// 	// 		$delivery_order_detail3_id = $this->M_Vrbl->Get_NewID();
	// 	// 		$delivery_order_detail3_id = $delivery_order_detail3_id[0]['NEW_ID'];
	// 	// 		$delivery_order_id = $value["do_id"];
	// 	// 		$tipe_biaya_id = $value["nama_biaya"];
	// 	// 		$delivery_order_detail3_nilai = $value["nominal_biaya"];
	// 	// 		$delivery_order_detail3_keterangan = $value["keterangan"];

	// 	// 		$this->M_KonfirmasiPenerimaanTunai->insertDODetail3($delivery_order_detail3_id, $delivery_order_id, $tipe_biaya_id, $delivery_order_detail3_nilai, $delivery_order_detail3_keterangan);
	// 	// 	}
	// 	// }
	// }

	public function saveAllDO()
	{
		$id_do_batch = $this->input->post('id_do_batch');
		$jumlahTerima = $this->input->post('jumlahTerima');
		$arr_payment = $this->input->post('arr_payment');
		$arr_keterangan = $this->input->post('arr_keterangan');
		$lastUpdate = $this->input->post('lastUpdate');
		$delivery_order_batch_nominal_invoice = $this->input->post('delivery_order_batch_nominal_invoice');
		$delivery_order_batch_nominal_selisih = intval($jumlahTerima) - intval($delivery_order_batch_nominal_invoice);

		$isNotSameLastUpd = false;
		$lastUpdateBaru = $this->M_KonfirmasiPenerimaanTunai->Get_LastUpdateDOBatch($id_do_batch);
		if ($lastUpdate == $lastUpdateBaru['tglUpd']) $isNotSameLastUpd = true;

		if ($isNotSameLastUpd) {
			$this->db->trans_begin();

			$this->M_KonfirmasiPenerimaanTunai->UpdateDOBatch($id_do_batch, $jumlahTerima, $delivery_order_batch_nominal_invoice, $delivery_order_batch_nominal_selisih);

			$this->M_KonfirmasiPenerimaanTunai->delete_delivery_order_payment($id_do_batch);

			foreach ($arr_payment as $key => $value) {
				$delivery_order_payment_id = $this->M_Vrbl->Get_NewID();
				$delivery_order_payment_id = $delivery_order_payment_id[0]['NEW_ID'];

				$delivery_order_batch_id = $id_do_batch;
				$tipe_pembayaran_id = $value['tipe_pembayaran'];
				$delivery_order_payment_value = $value['nilai_bayar'];
				// $delivery_order_reff_no = $value['delivery_order_reff_no'];
				$delivery_order_reff_no = '';
				$delivery_order_payment_tgl_jatuh_tempo = $value['tgl_jatuh_tempo'];

				$this->M_KonfirmasiPenerimaanTunai->Insert_delivery_order_payment($delivery_order_payment_id, $delivery_order_batch_id, $tipe_pembayaran_id, $delivery_order_payment_value, $delivery_order_reff_no, $delivery_order_payment_tgl_jatuh_tempo);

				// get do id di table delivery order_payment_driver
				$do_id = $this->M_KonfirmasiPenerimaanTunai->getDO($delivery_order_batch_id, $tipe_pembayaran_id);

				// insert table delivery order_payment_detail
				foreach ($do_id as $key => $value2) {
					$this->M_KonfirmasiPenerimaanTunai->Insert_delivery_order_payment_detail($delivery_order_payment_id, $value2['delivery_order_id']);
				}
			}

			// update keterangan do di delivery_order_payment_driver
			foreach ($arr_keterangan as $key => $value) {
				$this->M_KonfirmasiPenerimaanTunai->Update_keterangan_delivery_order_payment_driver($value);
			}

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo json_encode(['type' => 202, 'data' => null]);
			} else {
				$this->db->trans_commit();
				echo json_encode(['type' => 200, 'data' => null]);
			}
		} else {
			echo json_encode(['type' => 203, 'data' => null]);
		}
	}

	public function GetDODetail3()
	{
		$do_id = $this->input->post('do_id');

		$data['GetDODetail3'] = $this->M_KonfirmasiPenerimaanTunai->GetDODetail3($do_id);

		echo json_encode($data);
	}

	public function deleteDODetail3ByID()
	{
		$id = $this->input->post('id');

		$this->M_KonfirmasiPenerimaanTunai->deleteDODetail3($id);

		echo 1;
	}

	public function Get_list_delivery_order()
	{
		$id = $this->input->post('id');
		$do_pembayaran = $this->input->post('do_pembayaran');

		$data = $this->M_KonfirmasiPenerimaanTunai->Get_list_delivery_order($id, $do_pembayaran);

		echo json_encode($data);
	}
}
