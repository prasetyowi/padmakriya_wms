<?php

class KelolaDOGagal extends CI_Controller
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

		$this->MenuKode = "109018000";
		$this->load->model('WMS/M_KelolaDOGagal', 'M_KelolaDOGagal');
		$this->load->model('WMS/M_DeliveryOrderBatch', 'M_DeliveryOrderBatch');
		$this->load->model('WMS/M_DeliveryOrderDraft', 'M_DeliveryOrderDraft');
		$this->load->model('M_ClientPt');
		$this->load->model('M_Area');
		$this->load->model('M_StatusProgress');
		$this->load->model('M_SKU');
		$this->load->model('M_Principle');
		$this->load->model('M_TipeDeliveryOrder');
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
	}

	public function KelolaDOGagalMenu()
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
		$data['TipePelayanan'] = $this->M_KelolaDOGagal->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_KelolaDOGagal->GetTipeDeliveryOrder();
		$data['TipeEkspedisi'] = $this->M_KelolaDOGagal->GetTipeEkspedisi();
		$data['Status'] = $this->M_KelolaDOGagal->GetStatusProgress();
		$data['Kendaraan'] = $this->M_KelolaDOGagal->GetKendaraan();
		$data['Driver'] = $this->M_KelolaDOGagal->GetDriver();
		// $data['areas'] = $this->db->select("area_id, area_nama")->from('area')->where("area_is_aktif", 1)->where("area_nama !=", '')->order_by("area_nama", "ASC")->get()->result();
		$data['areas'] = $this->M_KelolaDOGagal->GetArea();;

		$tr_konversi_sku_id = $this->M_Vrbl->Get_NewID();
		$data['tr_konversi_sku_id'] = $tr_konversi_sku_id[0]['NEW_ID'];

		$data['act'] = "index";

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/KelolaDOGagal/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/KelolaDOGagal/script', $data);
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
		$data['Lokasi'] = $this->M_KelolaDOGagal->GetLokasi();
		$data['DOHeader'] = $this->M_KelolaDOGagal->GetKelolaDOGagalHeaderById($id);
		$data['DODetail'] = $this->M_KelolaDOGagal->GetKelolaDOGagalDetailById($id);
		$data['Perusahaan'] = $this->M_KelolaDOGagal->GetPerusahaan();
		$data['TipePelayanan'] = $this->M_KelolaDOGagal->GetTipeLayanan();
		$data['TipeDeliveryOrder'] = $this->M_KelolaDOGagal->GetTipeDeliveryOrder();
		$data['Area'] = $this->M_KelolaDOGagal->GetArea();
		$data['act'] = "detail";

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/KelolaDOGagal/detail', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/KelolaDOGagal/script', $data);
	}

	public function GetKelolaDOGagalById()
	{
		$id = $this->input->post('arr_do');

		$data['DOHeader'] = $this->M_KelolaDOGagal->GetKelolaDOGagalHeaderByListId($id);

		echo json_encode($data);
	}

	public function GetKelolaDOGagalDetailByListId()
	{
		$id = $this->input->post('id');

		// $data['DOHeader'] = $this->M_KelolaDOGagal->GetKelolaDOGagalHeaderByListId($id);
		$data['DODetail'] = $this->M_KelolaDOGagal->GetKelolaDOGagalDetailByListId($id);
		// $data['DODetail2'] = $this->M_KelolaDOGagal->GetKelolaDOGagalDetail2ByListId($id);

		echo json_encode($data);
	}

	public function GetSelectedSKU()
	{
		$sku_id = $this->input->post('sku_id');

		$data = $this->M_KelolaDOGagal->GetSelectedSKU($sku_id);

		echo json_encode($data);
	}

	public function GetFactoryByTypePelayanan()
	{
		$perusahaan = $this->input->post('perusahaan');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$tipe_layanan = $this->input->post('tipe_layanan');
		$nama = $this->input->post('nama');
		$alamat = $this->input->post('alamat');
		$telp = $this->input->post('telp');
		$area = $this->input->post('area');

		$data = $this->M_KelolaDOGagal->GetFactoryByTypePelayanan($perusahaan, $tipe_pembayaran, $tipe_layanan, $nama, $alamat, $telp, $area);

		echo json_encode($data);
	}

	public function GetCustomerByTypePelayanan()
	{
		$perusahaan = $this->input->post('perusahaan');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$tipe_layanan = $this->input->post('tipe_layanan');
		$nama = $this->input->post('nama');
		$alamat = $this->input->post('alamat');
		$telp = $this->input->post('telp');
		$area = $this->input->post('area');

		$data = $this->M_KelolaDOGagal->GetCustomerByTypePelayanan($perusahaan, $tipe_pembayaran, $tipe_layanan, $nama, $alamat, $telp, $area);

		echo json_encode($data);
	}

	public function GetSelectedPrinciple()
	{
		$customer = $this->input->post('customer');
		$perusahaan = $this->input->post('perusahaan');

		$data = $this->M_KelolaDOGagal->GetSelectedPrinciple($customer, $perusahaan);

		echo json_encode($data);
	}

	public function GetSelectedCustomer()
	{
		$customer = $this->input->post('customer');
		$perusahaan = $this->input->post('perusahaan');

		$data = $this->M_KelolaDOGagal->GetSelectedCustomer($customer, $perusahaan);

		echo json_encode($data);
	}

	public function search_filter_chosen_sku()
	{
		$client_pt = $this->input->post('client_pt');
		$perusahaan = $this->input->post('perusahaan');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$brand = $this->input->post('brand');
		$principle = $this->input->post('principle');
		$sku_induk = $this->input->post('sku_induk');
		$sku_nama_produk = $this->input->post('sku_nama_produk');
		$sku_kemasan = $this->input->post('sku_kemasan');
		$sku_satuan = $this->input->post('sku_satuan');

		$data = $this->M_KelolaDOGagal->search_filter_chosen_sku($client_pt, $perusahaan, $tipe_pembayaran, $brand, $principle, $sku_induk, $sku_nama_produk, $sku_kemasan, $sku_satuan);

		echo json_encode($data);
	}

	public function search_filter_chosen_sku_by_pabrik()
	{
		$client_pt = $this->input->post('client_pt');
		$perusahaan = $this->input->post('perusahaan');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$brand = $this->input->post('brand');
		$principle = $this->input->post('principle');
		$sku_induk = $this->input->post('sku_induk');
		$sku_nama_produk = $this->input->post('sku_nama_produk');
		$sku_kemasan = $this->input->post('sku_kemasan');
		$sku_satuan = $this->input->post('sku_satuan');

		$data = $this->M_KelolaDOGagal->search_filter_chosen_sku_by_pabrik($client_pt, $perusahaan, $tipe_pembayaran, $brand, $principle, $sku_induk, $sku_nama_produk, $sku_kemasan, $sku_satuan);

		echo json_encode($data);
	}

	public function insert_delivery_order_draft()
	{
		$delivery_order_batch_id 				= $this->input->post('delivery_order_batch_id');
		$delivery_order_draft_id 				= $this->input->post('delivery_order_draft_id');
		$sales_order_id 						= $this->input->post('sales_order_id');
		$delivery_order_draft_kode 				= $this->input->post('delivery_order_draft_kode');
		$delivery_order_draft_yourref 			= $this->input->post('delivery_order_draft_yourref');
		$client_wms_id 							= $this->input->post('client_wms_id');
		$delivery_order_draft_tgl_buat_do 		= $this->input->post('delivery_order_draft_tgl_buat_do');
		$delivery_order_draft_tgl_expired_do 	= $this->input->post('delivery_order_draft_tgl_expired_do');
		$delivery_order_draft_tgl_surat_jalan 	= $this->input->post('delivery_order_draft_tgl_surat_jalan');
		$delivery_order_draft_tgl_rencana_kirim = $this->input->post('delivery_order_draft_tgl_rencana_kirim');
		$delivery_order_draft_tgl_aktual_kirim 	= $this->input->post('delivery_order_draft_tgl_aktual_kirim');
		$delivery_order_draft_keterangan 		= $this->input->post('delivery_order_draft_keterangan');
		$delivery_order_draft_status 			= $this->input->post('delivery_order_draft_status');
		$delivery_order_draft_is_prioritas 		= $this->input->post('delivery_order_draft_is_prioritas');
		$delivery_order_draft_is_need_packing 	= $this->input->post('delivery_order_draft_is_need_packing');
		$delivery_order_draft_tipe_layanan 		= $this->input->post('delivery_order_draft_tipe_layanan');
		$delivery_order_draft_tipe_pembayaran 	= $this->input->post('delivery_order_draft_tipe_pembayaran');
		$delivery_order_draft_sesi_pengiriman 	= $this->input->post('delivery_order_draft_sesi_pengiriman');
		$delivery_order_draft_request_tgl_kirim = $this->input->post('delivery_order_draft_request_tgl_kirim');
		$delivery_order_draft_request_jam_kirim = $this->input->post('delivery_order_draft_request_jam_kirim');
		$tipe_pengiriman_id 					= $this->input->post('tipe_pengiriman_id');
		$nama_tipe 								= $this->input->post('nama_tipe');
		$confirm_rate 							= $this->input->post('confirm_rate');
		$delivery_order_draft_reff_id 			= $this->input->post('delivery_order_draft_reff_id');
		$delivery_order_draft_reff_no 			= $this->input->post('delivery_order_draft_reff_no');
		$delivery_order_draft_total 			= $this->input->post('delivery_order_draft_total');
		$unit_mandiri_id 						= $this->input->post('unit_mandiri_id');
		$depo_id 								= $this->input->post('depo_id');
		$client_pt_id 							= $this->input->post('client_pt_id');
		$delivery_order_draft_kirim_nama 		= $this->input->post('delivery_order_draft_kirim_nama');
		$delivery_order_draft_kirim_alamat 		= $this->input->post('delivery_order_draft_kirim_alamat');
		$delivery_order_draft_kirim_telp 		= $this->input->post('delivery_order_draft_kirim_telp');
		$delivery_order_draft_kirim_provinsi 	= $this->input->post('delivery_order_draft_kirim_provinsi');
		$delivery_order_draft_kirim_kota 		= $this->input->post('delivery_order_draft_kirim_kota');
		$delivery_order_draft_kirim_kecamatan 	= $this->input->post('delivery_order_draft_kirim_kecamatan');
		$delivery_order_draft_kirim_kelurahan 	= $this->input->post('delivery_order_draft_kirim_kelurahan');
		$delivery_order_draft_kirim_kodepos 	= $this->input->post('delivery_order_draft_kirim_kodepos');
		$delivery_order_draft_kirim_area 		= $this->input->post('delivery_order_draft_kirim_area');
		$delivery_order_draft_kirim_invoice_pdf = $this->input->post('delivery_order_draft_kirim_invoice_pdf');
		$delivery_order_draft_kirim_invoice_dir = $this->input->post('delivery_order_draft_kirim_invoice_dir');
		$pabrik_id 								= $this->input->post('pabrik_id');
		$delivery_order_draft_ambil_nama		= $this->input->post('delivery_order_draft_ambil_nama');
		$delivery_order_draft_ambil_alamat		= $this->input->post('delivery_order_draft_ambil_alamat');
		$delivery_order_draft_ambil_telp		= $this->input->post('delivery_order_draft_ambil_telp');
		$delivery_order_draft_ambil_provinsi	= $this->input->post('delivery_order_draft_ambil_provinsi');
		$delivery_order_draft_ambil_kota		= $this->input->post('delivery_order_draft_ambil_kota');
		$delivery_order_draft_ambil_kecamatan 	= $this->input->post('delivery_order_draft_ambil_kecamatan');
		$delivery_order_draft_ambil_kelurahan 	= $this->input->post('delivery_order_draft_ambil_kelurahan');
		$delivery_order_draft_ambil_kodepos 	= $this->input->post('delivery_order_draft_ambil_kodepos');
		$delivery_order_draft_ambil_area 		= $this->input->post('delivery_order_draft_ambil_area');
		$delivery_order_draft_update_who 		= $this->input->post('delivery_order_draft_update_who');
		$delivery_order_draft_update_tgl 		= $this->input->post('delivery_order_draft_update_tgl');
		$delivery_order_draft_approve_who 		= $this->input->post('delivery_order_draft_approve_who');
		$delivery_order_draft_approve_tgl 		= $this->input->post('delivery_order_draft_approve_tgl');
		$delivery_order_draft_reject_who 		= $this->input->post('delivery_order_draft_reject_who');
		$delivery_order_draft_reject_tgl 		= $this->input->post('delivery_order_draft_reject_tgl');
		$delivery_order_draft_reject_reason 	= $this->input->post('delivery_order_draft_reject_reason');
		$tipe_delivery_order_id 				= $this->input->post('tipe_delivery_order_id');
		$detail 								= $this->input->post('detail');
		$arr_do 								= $this->input->post('arr_do');
		// $detail2 = $this->input->post('detail2');
		$delivery_order_draft_input_pembayaran_tunai = $this->input->post('delivery_order_draft_nominal_tunai');
		$file = $this->input->post('file');

		$area = $this->input->post('area');

		$error = 0;
		$emptySKUInStock = [];
		unset($emptySKUInStock);
		$emptySKUInStock = array();

		$emptyStockKurang = [];
		unset($emptyStockKurang);
		$emptyStockKurang = array();

		$chkSisaQty = [];
		unset($chkSisaQty);
		$chkSisaQty = array();

		$dod_id = $this->M_Vrbl->Get_NewID();
		$dod_id = $dod_id[0]['NEW_ID'];

		//generate kode
		$date_now 		= date('Y-m-d h:i:s');
		$param 			=  'KODE_DOD';
		$vrbl 			= $this->M_Vrbl->Get_Kode($param);
		$prefix 		= $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id 		= $this->session->userdata('depo_id');
		$depoPrefix 	= $this->M_DeliveryOrderDraft->getDepoPrefix($depo_id);
		$unit 			= $depoPrefix->depo_kode_preffix;
		$generate_kode 	= $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$delivery_order_draft_attachment = $this->input->post('delivery_order_draft_attachment');

		$this->db->trans_begin();
		$getTglKirimBySTK = $this->db->query("select dob.delivery_order_batch_tanggal_kirim from delivery_order_batch dob where dob.delivery_order_batch_id ='$delivery_order_batch_id'")->row()->delivery_order_batch_tanggal_kirim;

		if ($delivery_order_draft_status == "not delivered") {
			//insert ke tr_pemusnahan_stok_draft

			$delivery_order_draft_status = "Approved";

			$result = $this->M_DeliveryOrderDraft->insert_delivery_order_draft(
				$dod_id,
				$sales_order_id,
				$generate_kode,
				$delivery_order_draft_yourref,
				$client_wms_id,
				$delivery_order_draft_tgl_buat_do,
				$delivery_order_draft_tgl_expired_do,
				$delivery_order_draft_tgl_surat_jalan,
				$getTglKirimBySTK,
				// $delivery_order_draft_tgl_aktual_kirim,
				$getTglKirimBySTK,
				$delivery_order_draft_keterangan,
				$delivery_order_draft_status,
				$delivery_order_draft_is_prioritas,
				$delivery_order_draft_is_need_packing,
				$delivery_order_draft_tipe_layanan,
				$delivery_order_draft_tipe_pembayaran,
				$delivery_order_draft_sesi_pengiriman,
				null,
				$delivery_order_draft_request_jam_kirim,
				$tipe_pengiriman_id,
				$nama_tipe,
				$confirm_rate,
				$delivery_order_draft_reff_id,
				$delivery_order_draft_reff_no,
				$delivery_order_draft_total,
				$unit_mandiri_id,
				$depo_id,
				$client_pt_id,
				$delivery_order_draft_kirim_nama,
				$delivery_order_draft_kirim_alamat,
				$delivery_order_draft_kirim_telp,
				$delivery_order_draft_kirim_provinsi,
				$delivery_order_draft_kirim_kota,
				$delivery_order_draft_kirim_kecamatan,
				$delivery_order_draft_kirim_kelurahan,
				$delivery_order_draft_kirim_kodepos,
				$delivery_order_draft_kirim_area,
				$delivery_order_draft_kirim_invoice_pdf,
				$delivery_order_draft_kirim_invoice_dir,
				$pabrik_id,
				$delivery_order_draft_ambil_nama,
				$delivery_order_draft_ambil_alamat,
				$delivery_order_draft_ambil_telp,
				$delivery_order_draft_ambil_provinsi,
				$delivery_order_draft_ambil_kota,
				$delivery_order_draft_ambil_kecamatan,
				$delivery_order_draft_ambil_kelurahan,
				$delivery_order_draft_ambil_kodepos,
				$delivery_order_draft_ambil_area,
				$delivery_order_draft_update_who,
				$delivery_order_draft_update_tgl,
				$delivery_order_draft_approve_who,
				$delivery_order_draft_approve_tgl,
				$delivery_order_draft_reject_who,
				$delivery_order_draft_reject_tgl,
				$delivery_order_draft_reject_reason,
				$tipe_delivery_order_id,
				$delivery_order_draft_input_pembayaran_tunai,
				$delivery_order_draft_attachment
			);

			if (!$result) {
				echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan!'));
			} else {
				$data_detail = json_decode($detail);
				//insert ke tr_pemusnahan_stok_detail_draft

				foreach ($data_detail as $key => $value) {
					$dod_id_ = $this->M_Vrbl->Get_NewID();
					$dod_id_ = $dod_id_[0]['NEW_ID'];
					$this->M_DeliveryOrderDraft->insert_delivery_order_detail_draft($dod_id_, $dod_id, $value);

					$qtyDet = $value->sku_qty;

					if ($tipe_delivery_order_id != "C5BE83E2-01E8-4E24-B766-26BB4158F2CD") {
						if ($qtyDet < 0) {
							continue;
							# code...
						}

						if ($value->sku_request_expdate == 0) {

							//filter sku_stock berdasarkan client wms id, sku id
							//ambil qty berdasarkan expired teratas
							$sisaQty = $qtyDet;
							$sku_id_before = "";

							$GetDataSKU = $this->M_KelolaDOGagal->GetDataSKUByReqNull($client_wms_id, $value->sku_id);
							// echo json_encode($GetDataSKU);

							$lastKey = array_search(end($GetDataSKU), $GetDataSKU);

							if (count($GetDataSKU) == 0) {
								$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
								array_push($emptySKUInStock, ['type' => 203, 'data' => $getNameSKU]);
							} else {
								$arr_sum = 0;
								foreach ($GetDataSKU as $key => $val) {
									$sumqtyStock = ($val->sku_stock_awal + $val->sku_stock_masuk) - ($val->sku_stock_saldo_alokasi - $val->sku_stock_keluar);

									$arr_sum += $sumqtyStock;
								}

								if ($qtyDet > $arr_sum) {
									$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
									array_push($emptyStockKurang, ['type' => 204, 'data' => $getNameSKU]);

									// break;
								} else {

									foreach ($GetDataSKU as $key => $val) {

										if (($val->client_wms_id == $client_wms_id) && ($val->sku_id == $value->sku_id)) {
											$qtyStock = ($val->sku_stock_awal + $val->sku_stock_masuk) - ($val->sku_stock_saldo_alokasi - $val->sku_stock_keluar);
											$sku_stock_id = $val->sku_stock_id;

											if ($qtyStock == 0) {
												continue;
											}

											if ($qtyStock >= $sisaQty) {

												$qtyDet2 = $sisaQty;

												$dod2_id = $this->M_Vrbl->Get_NewID();
												$dod2_id = $dod2_id[0]['NEW_ID'];
												$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $val->sku_stock_expired_date, $qtyDet2, $val->sku_konversi_faktor);

												// cek stok_alokasi yg sudah ada
												$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

												//update alokasi stock sku
												$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
												$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

												// $this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);
												$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $qtyDet2, $sku_stock_id);

												$sku_id_before = $val->sku_id;

												# break...
												break;
											} else {

												$sisaQty -= $qtyStock;
												$qtyDet2 = $qtyStock;

												$dod2_id_2 = $this->M_Vrbl->Get_NewID();
												$dod2_id_2 = $dod2_id_2[0]['NEW_ID'];


												$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id_2, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $val->sku_stock_expired_date, $qtyDet2, $val->sku_konversi_faktor);

												// cek stok_alokasi yg sudah ada
												$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

												//update alokasi stock sku
												$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
												$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

												// $this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);
												$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $qtyDet2, $sku_stock_id);

												if ($sisaQty <= 0) {
													break;
												};

												if ($key == $lastKey) {
													if ($sisaQty >= 0) {

														$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);

														array_push($chkSisaQty, ['type' => 201, 'data' => $getNameSKU]);
													}
												}

												$sku_id_before = $val->sku_id;
											}
										} else {
											continue;
										}
									}
								}
							}
						} else if ($value->sku_request_expdate == 1) {
							//filter sku_stock berdasarkan client wms id, sku id
							//ambil qty berdasarkan expired request diatasnya, misal req ed 5 jadi ambil diatasnya 5 
							if ($value->sku_filter_expdate == ">=") {

								$sisaQty = $qtyDet;
								$sku_id_before = "";

								$GetDataSKUBesarDari = $this->M_KelolaDOGagal->GetDataSKUByReqBesarDari($client_wms_id, $value->sku_id, $value->sku_filter_expdatebulan, date("Y-m-d", strtotime(str_replace("/", "-", $delivery_order_draft_tgl_buat_do))));

								$lastKey = array_search(end($GetDataSKUBesarDari), $GetDataSKUBesarDari);

								if (count($GetDataSKUBesarDari) == 0) {

									$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
									array_push($emptySKUInStock, ['type' => 203, 'data' => $getNameSKU]);
								} else {

									$arr_sum = 0;
									foreach ($GetDataSKUBesarDari as $key => $item) {
										$qtySisaStock = ($item->sku_stock_awal + $item->sku_stock_masuk) - ($item->sku_stock_saldo_alokasi - $item->sku_stock_keluar);

										$arr_sum += $qtySisaStock;
									}

									if ($sisaQty > $arr_sum) {
										$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
										array_push($emptyStockKurang, ['type' => 204, 'data' => $getNameSKU]);

										// break;
									} else {
										foreach ($GetDataSKUBesarDari as $key => $item) {
											if (($item->client_wms_id == $client_wms_id) && ($item->sku_id == $value->sku_id)) {
												# code...
												$newIDDet2 = $this->M_Vrbl->Get_NewID();
												$newIDDet1 = $newIDDet2[0]['NEW_ID'];

												// cek stok_alokasi yg sudah ada
												// $dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($item->sku_stock_id);

												$qtySisaStock = ($item->sku_stock_awal + $item->sku_stock_masuk) - ($item->sku_stock_saldo_alokasi - $item->sku_stock_keluar);
												$sku_stock_id = $item->sku_stock_id;

												if ($qtySisaStock == 0) {
													continue;
												}

												if ($qtySisaStock >= $sisaQty) {

													$qtyDet2 = $sisaQty;

													$dod2_id = $this->M_Vrbl->Get_NewID();
													$dod2_id = $dod2_id[0]['NEW_ID'];

													$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $item->sku_stock_expired_date, $qtyDet2, $item->sku_konversi_faktor);

													// cek stok_alokasi yg sudah ada
													$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

													//update alokasi stock sku
													$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
													$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

													$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);

													# break...
													break;
												} else {
													$sisaQty -= $qtySisaStock;
													$qtyDet2 = $qtySisaStock;

													$dod2_id_2 = $this->M_Vrbl->Get_NewID();
													$dod2_id_2 = $dod2_id_2[0]['NEW_ID'];


													$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id_2, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $item->sku_stock_expired_date, $qtyDet2, $item->sku_konversi_faktor);

													// cek stok_alokasi yg sudah ada
													$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

													//update alokasi stock sku
													$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
													$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

													$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);

													if ($sisaQty <= 0) {
														break;
													};

													if ($key == $lastKey) {
														if ($sisaQty >= 0) {

															$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);

															array_push($chkSisaQty, ['type' => 201, 'data' => $getNameSKU]);
														}
													}
												}
											} else {
												continue;
											}
										}
									}
								}
							}
						}
					}
				}

				// foreach ($data_detail as $key => $value) {
				// 	$dod_id_ = $this->M_Vrbl->Get_NewID();
				// 	$dod_id_ = $dod_id_[0]['NEW_ID'];
				// 	$this->M_DeliveryOrderDraft->insert_delivery_order_detail_draft($dod_id_, $dod_id, $value);

				// 	$data_detail2 = $this->M_KelolaDOGagal->GetKelolaDOGagalDetail2ByListId($delivery_order_draft_reff_id, $value->sku_id);

				// 	foreach ($data_detail2 as $key2 => $value2) {
				// 		$dod_id_2 = $this->M_Vrbl->Get_NewID();
				// 		$dod_id_2 = $dod_id_2[0]['NEW_ID'];
				// 		$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod_id_2, $dod_id_, $dod_id, $value2['sku_id'], $value2['sku_stock_id'], $value2['sku_expdate'], $value2['sku_qty']);
				// 	}
				// }
			}

			$no_urut = $this->M_KelolaDOGagal->GetLastNoUrut($delivery_order_batch_id) + 1;
			$list_do = $this->M_DeliveryOrderBatch->GetDODraftById($dod_id, $no_urut);

			foreach ($list_do as $key => $value) {

				$do_id = $this->M_Vrbl->Get_NewID();
				$do_id = $do_id[0]['NEW_ID'];

				//generate kode
				$date_now = date('Y-m-d h:i:s');
				$param =  'KODE_DO';
				$vrbl = $this->M_Vrbl->Get_Kode($param);
				$prefix = $vrbl->vrbl_kode;
				// get prefik depo
				$depo_id = $this->session->userdata('depo_id');
				$depoPrefix = $this->M_DeliveryOrderBatch->getDepoPrefix($depo_id);
				$unit = $depoPrefix->depo_kode_preffix;
				$do_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

				$cek_do = $this->M_KelolaDOGagal->cek_do_by_do_draft($delivery_order_draft_id);

				if ($cek_do == 0) {
					$this->M_DeliveryOrderBatch->insert_delivery_order($delivery_order_batch_id, $do_id, $do_kode, $value, "insert", null);
					$this->M_KelolaDOGagal->UpdateFdjr($delivery_order_batch_id);
					$this->M_KelolaDOGagal->InsertDoAreaIfNotExistByDO($delivery_order_batch_id, $do_id);

					$dodd = $this->M_DeliveryOrderBatch->GetDODraftDetailById($dod_id);

					foreach ($dodd as $key => $value2) {
						$do_detail_id = $this->M_Vrbl->Get_NewID();
						$do_detail_id = $do_detail_id[0]['NEW_ID'];

						// $this->M_DeliveryOrderBatch->insert_delivery_order_detail($do_detail_id, $do_id, $delivery_order_batch_id, $value2);
						$this->M_DeliveryOrderBatch->insert_delivery_order_detail($value2['delivery_order_detail_draft_id'], $do_id, $delivery_order_batch_id, $value2);
					}
				}
			}
		} else if ($delivery_order_draft_status == "partially delivered") {
			$delivery_order_draft_status = "Approved";

			$result = $this->M_DeliveryOrderDraft->insert_delivery_order_draft(
				$dod_id,
				$sales_order_id,
				$generate_kode,
				$delivery_order_draft_yourref,
				$client_wms_id,
				$delivery_order_draft_tgl_buat_do,
				$delivery_order_draft_tgl_expired_do,
				$delivery_order_draft_tgl_surat_jalan,
				$getTglKirimBySTK,
				// $delivery_order_draft_tgl_aktual_kirim,
				$getTglKirimBySTK,
				$delivery_order_draft_keterangan,
				$delivery_order_draft_status,
				$delivery_order_draft_is_prioritas,
				$delivery_order_draft_is_need_packing,
				$delivery_order_draft_tipe_layanan,
				$delivery_order_draft_tipe_pembayaran,
				$delivery_order_draft_sesi_pengiriman,
				null,
				$delivery_order_draft_request_jam_kirim,
				$tipe_pengiriman_id,
				$nama_tipe,
				$confirm_rate,
				$delivery_order_draft_reff_id,
				$delivery_order_draft_reff_no,
				$delivery_order_draft_total,
				$unit_mandiri_id,
				$depo_id,
				$client_pt_id,
				$delivery_order_draft_kirim_nama,
				$delivery_order_draft_kirim_alamat,
				$delivery_order_draft_kirim_telp,
				$delivery_order_draft_kirim_provinsi,
				$delivery_order_draft_kirim_kota,
				$delivery_order_draft_kirim_kecamatan,
				$delivery_order_draft_kirim_kelurahan,
				$delivery_order_draft_kirim_kodepos,
				$delivery_order_draft_kirim_area,
				$delivery_order_draft_kirim_invoice_pdf,
				$delivery_order_draft_kirim_invoice_dir,
				$pabrik_id,
				$delivery_order_draft_ambil_nama,
				$delivery_order_draft_ambil_alamat,
				$delivery_order_draft_ambil_telp,
				$delivery_order_draft_ambil_provinsi,
				$delivery_order_draft_ambil_kota,
				$delivery_order_draft_ambil_kecamatan,
				$delivery_order_draft_ambil_kelurahan,
				$delivery_order_draft_ambil_kodepos,
				$delivery_order_draft_ambil_area,
				$delivery_order_draft_update_who,
				$delivery_order_draft_update_tgl,
				$delivery_order_draft_approve_who,
				$delivery_order_draft_approve_tgl,
				$delivery_order_draft_reject_who,
				$delivery_order_draft_reject_tgl,
				$delivery_order_draft_reject_reason,
				$tipe_delivery_order_id,
				$delivery_order_draft_input_pembayaran_tunai,
				$delivery_order_draft_attachment
			);

			if (!$result) {
				echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan!'));
			} else {
				$data_detail = json_decode($detail);

				//insert ke tr_pemusnahan_stok_detail_draft

				foreach ($data_detail as $key => $value) {
					$dod_id_ = $this->M_Vrbl->Get_NewID();
					$dod_id_ = $dod_id_[0]['NEW_ID'];
					$this->M_DeliveryOrderDraft->insert_delivery_order_detail_draft($dod_id_, $dod_id, $value);

					$qtyDet = $value->sku_qty;

					if ($tipe_delivery_order_id != "C5BE83E2-01E8-4E24-B766-26BB4158F2CD") {
						if ($qtyDet < 0) {
							continue;
							# code...
						}

						if ($value->sku_request_expdate == 0) {

							//filter sku_stock berdasarkan client wms id, sku id
							//ambil qty berdasarkan expired teratas
							$sisaQty = $qtyDet;
							$sku_id_before = "";

							$GetDataSKU = $this->M_KelolaDOGagal->GetDataSKUByStatusFlag($client_wms_id, $value->sku_id);
							// $GetDataSKU = $this->M_KelolaDOGagal->GetDataSKUByReqNull($client_wms_id, $value->sku_id);

							$lastKey = array_search(end($GetDataSKU), $GetDataSKU);

							if (count($GetDataSKU) == 0) {
								$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
								array_push($emptySKUInStock, ['type' => 203, 'data' => $getNameSKU]);
							} else {
								$arr_sum = 0;
								foreach ($GetDataSKU as $key => $val) {
									$sumqtyStock = ($val->sku_stock_awal + $val->sku_stock_masuk) - ($val->sku_stock_saldo_alokasi - $val->sku_stock_keluar);

									$arr_sum += $sumqtyStock;
								}

								if ($qtyDet > $arr_sum) {
									$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
									array_push($emptyStockKurang, ['type' => 204, 'data' => $getNameSKU]);

									// break;
								} else {

									foreach ($GetDataSKU as $key => $val) {

										if (($val->client_wms_id == $client_wms_id) && ($val->sku_id == $value->sku_id)) {

											$qtyStock = ($val->sku_stock_awal + $val->sku_stock_masuk) - ($val->sku_stock_saldo_alokasi - $val->sku_stock_keluar);
											$sku_stock_id = $val->sku_stock_id;

											if ($qtyStock == 0) {
												continue;
											}

											if ($qtyStock >= $sisaQty) {

												$qtyDet2 = $sisaQty;

												$dod2_id = $this->M_Vrbl->Get_NewID();
												$dod2_id = $dod2_id[0]['NEW_ID'];
												$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $val->sku_stock_expired_date, $qtyDet2, $val->sku_konversi_faktor);

												// cek stok_alokasi yg sudah ada
												$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

												//update alokasi stock sku
												$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
												$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

												// $this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);
												$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $qtyDet2, $sku_stock_id);

												$sku_id_before = $val->sku_id;

												# break...
												break;
											} else {

												$sisaQty -= $qtyStock;
												$qtyDet2 = $qtyStock;

												$dod2_id_2 = $this->M_Vrbl->Get_NewID();
												$dod2_id_2 = $dod2_id_2[0]['NEW_ID'];


												$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id_2, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $val->sku_stock_expired_date, $qtyDet2, $val->sku_konversi_faktor);

												// cek stok_alokasi yg sudah ada
												$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

												//update alokasi stock sku
												$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
												$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

												// $this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);
												$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $qtyDet2, $sku_stock_id);

												if ($sisaQty <= 0) {
													break;
												};

												if ($key == $lastKey) {
													if ($sisaQty >= 0) {

														$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);

														array_push($chkSisaQty, ['type' => 201, 'data' => $getNameSKU]);
													}
												}

												$sku_id_before = $val->sku_id;
											}
										} else {
											continue;
										}
									}
								}
							}
						} else if ($value->sku_request_expdate == 1) {
							//filter sku_stock berdasarkan client wms id, sku id
							//ambil qty berdasarkan expired request diatasnya, misal req ed 5 jadi ambil diatasnya 5 
							if ($value->sku_filter_expdate == ">=") {

								$sisaQty = $qtyDet;
								$sku_id_before = "";

								$GetDataSKUBesarDari = $this->M_KelolaDOGagal->GetDataSKUByStatusFlag($client_wms_id, $value->sku_id, $value->sku_filter_expdatebulan, date("Y-m-d", strtotime(str_replace("/", "-", $delivery_order_draft_tgl_buat_do))));

								$lastKey = array_search(end($GetDataSKUBesarDari), $GetDataSKUBesarDari);

								if (count($GetDataSKUBesarDari) == 0) {

									$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
									array_push($emptySKUInStock, ['type' => 203, 'data' => $getNameSKU]);
								} else {

									$arr_sum = 0;
									foreach ($GetDataSKUBesarDari as $key => $item) {
										$qtySisaStock = ($item->sku_stock_awal + $item->sku_stock_masuk) - ($item->sku_stock_saldo_alokasi - $item->sku_stock_keluar);

										$arr_sum += $qtySisaStock;
									}

									if ($sisaQty > $arr_sum) {
										$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
										array_push($emptyStockKurang, ['type' => 204, 'data' => $getNameSKU]);

										// break;
									} else {
										foreach ($GetDataSKUBesarDari as $key => $item) {
											if (($item->client_wms_id == $client_wms_id) && ($item->sku_id == $value->sku_id)) {
												# code...
												$newIDDet2 = $this->M_Vrbl->Get_NewID();
												$newIDDet1 = $newIDDet2[0]['NEW_ID'];

												// cek stok_alokasi yg sudah ada
												// $dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($item->sku_stock_id);

												$qtySisaStock = ($item->sku_stock_awal + $item->sku_stock_masuk) - ($item->sku_stock_saldo_alokasi - $item->sku_stock_keluar);
												$sku_stock_id = $item->sku_stock_id;

												if ($qtySisaStock == 0) {
													continue;
												}

												if ($qtySisaStock >= $sisaQty) {

													$qtyDet2 = $sisaQty;

													$dod2_id = $this->M_Vrbl->Get_NewID();
													$dod2_id = $dod2_id[0]['NEW_ID'];

													$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $item->sku_stock_expired_date, $qtyDet2, $item->sku_konversi_faktor);

													// cek stok_alokasi yg sudah ada
													$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

													//update alokasi stock sku
													$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
													$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

													$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);

													# break...
													break;
												} else {
													$sisaQty -= $qtySisaStock;
													$qtyDet2 = $qtySisaStock;

													$dod2_id_2 = $this->M_Vrbl->Get_NewID();
													$dod2_id_2 = $dod2_id_2[0]['NEW_ID'];


													$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id_2, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $item->sku_stock_expired_date, $qtyDet2, $item->sku_konversi_faktor);

													// cek stok_alokasi yg sudah ada
													$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

													//update alokasi stock sku
													$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
													$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

													$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);

													if ($sisaQty <= 0) {
														break;
													};

													if ($key == $lastKey) {
														if ($sisaQty >= 0) {

															$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);

															array_push($chkSisaQty, ['type' => 201, 'data' => $getNameSKU]);
														}
													}
												}
											} else {
												continue;
											}
										}
									}
								}
							}
						}
					}
				}
			}

			$no_urut = $this->M_KelolaDOGagal->GetLastNoUrut($delivery_order_batch_id) + 1;
			$list_do = $this->M_DeliveryOrderBatch->GetDODraftById($dod_id, $no_urut);

			foreach ($list_do as $key => $value) {

				$do_id = $this->M_Vrbl->Get_NewID();
				$do_id = $do_id[0]['NEW_ID'];

				//generate kode
				$date_now = date('Y-m-d h:i:s');
				$param =  'KODE_DO';
				$vrbl = $this->M_Vrbl->Get_Kode($param);
				$prefix = $vrbl->vrbl_kode;
				// get prefik depo
				$depo_id = $this->session->userdata('depo_id');
				$depoPrefix = $this->M_DeliveryOrderBatch->getDepoPrefix($depo_id);
				$unit = $depoPrefix->depo_kode_preffix;
				$do_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

				$cek_do = $this->M_KelolaDOGagal->cek_do_by_do_draft($delivery_order_draft_id);

				if ($cek_do == 0) {
					$this->M_DeliveryOrderBatch->insert_delivery_order($delivery_order_batch_id, $do_id, $do_kode, $value, "insert", null);
					$this->M_KelolaDOGagal->UpdateFdjr($delivery_order_batch_id);
					$this->M_KelolaDOGagal->InsertDoAreaIfNotExistByDO($delivery_order_batch_id, $do_id);

					$dodd = $this->M_DeliveryOrderBatch->GetDODraftDetailById($dod_id);

					foreach ($dodd as $key => $value2) {
						$do_detail_id = $this->M_Vrbl->Get_NewID();
						$do_detail_id = $do_detail_id[0]['NEW_ID'];

						// $this->M_DeliveryOrderBatch->insert_delivery_order_detail($do_detail_id, $do_id, $delivery_order_batch_id, $value2);
						$this->M_DeliveryOrderBatch->insert_delivery_order_detail($value2['delivery_order_detail_draft_id'], $do_id, $delivery_order_batch_id, $value2);
					}
				}
			}
		} else {
			$no_urut = $this->M_KelolaDOGagal->GetLastNoUrut($delivery_order_batch_id) + 1;
			$list_do = $this->M_DeliveryOrderBatch->GetDODraftById($delivery_order_draft_id, $no_urut);

			foreach ($list_do as $key => $value) {

				$do_id = $this->M_Vrbl->Get_NewID();
				$do_id = $do_id[0]['NEW_ID'];

				//generate kode
				$date_now = date('Y-m-d h:i:s');
				$param =  'KODE_DO';
				$vrbl = $this->M_Vrbl->Get_Kode($param);
				$prefix = $vrbl->vrbl_kode;
				// get prefik depo
				$depo_id = $this->session->userdata('depo_id');
				$depoPrefix = $this->M_DeliveryOrderBatch->getDepoPrefix($depo_id);
				$unit = $depoPrefix->depo_kode_preffix;
				$do_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

				$cek_do = $this->M_KelolaDOGagal->cek_do_by_do_draft($delivery_order_draft_id);

				if ($cek_do == 0) {
					$this->M_DeliveryOrderBatch->insert_delivery_order($delivery_order_batch_id, $do_id, $do_kode, $value, "insert", null);

					$this->M_KelolaDOGagal->InsertDoAreaIfNotExistByDO($delivery_order_batch_id, $do_id);
					$this->M_KelolaDOGagal->UpdateFdjr($delivery_order_batch_id);

					$dodd = $this->M_DeliveryOrderBatch->GetDODraftDetailById($delivery_order_draft_id);

					foreach ($dodd as $key => $value2) {
						$do_detail_id = $this->M_Vrbl->Get_NewID();
						$do_detail_id = $do_detail_id[0]['NEW_ID'];

						// $this->M_DeliveryOrderBatch->insert_delivery_order_detail($do_detail_id, $do_id, $delivery_order_batch_id, $value2);
						$this->M_DeliveryOrderBatch->insert_delivery_order_detail($value2['delivery_order_detail_draft_id'], $do_id, $delivery_order_batch_id, $value2);
					}
				}
			}
		}


		// update fdjr

		$skuEmpty = [];

		$groupSkuEmpty = "";
		foreach ($emptySKUInStock as $key => $data) {

			// $msg = "Qty dari SKU <strong>" . $data['data']->sku_kode . "</strong> tidak ada!";
			// $this->M_DeliveryOrderDraft->Insert_delivery_order_draft_detail_msg($delivery_order_draft_id, $data['data']->sku_kode, $msg);

			$tmpdata = $data;
			unset($tmpdata['type']);
			$skuEmpty[$data['type']]['type'] = $data['type'];
			$skuEmpty[$data['type']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}

		foreach ($skuEmpty as $key => $data) {
			// $tmpdata = $data;
			$groupSkuEmpty = $data;
		}

		$SkuStokKurang = [];

		$groupSkuStokKurang = "";
		foreach ($emptyStockKurang as $key => $data) {

			// $msg = "Qty dari SKU <strong>" . $data['data']->sku_kode . "</strong> kurang";
			// $this->M_DeliveryOrderDraft->Insert_delivery_order_draft_detail_msg($delivery_order_draft_id, $data['data']->sku_kode, $msg);

			$tmpdata = $data;
			unset($tmpdata['type']);
			$SkuStokKurang[$data['type']]['type'] = $data['type'];
			$SkuStokKurang[$data['type']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}

		foreach ($SkuStokKurang as $key => $data) {
			// $tmpdata = $data;
			$groupSkuStokKurang = $data;
		}

		$tmpgroupQtySisa = [];

		$groupQtySisa = "";
		foreach ($chkSisaQty as $key => $data) {

			// $msg = "Qty dari SKU <strong>" . $data['data']->sku_kode . "</strong> tidak cukup, silahkan dicek kembali!";
			// $this->M_DeliveryOrderDraft->Insert_delivery_order_draft_detail_msg($delivery_order_draft_id, $data['data']->sku_kode, $msg);

			$tmpdata = $data;
			unset($tmpdata['type']);
			$tmpgroupQtySisa[$data['type']]['type'] = $data['type'];
			$tmpgroupQtySisa[$data['type']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}

		foreach ($tmpgroupQtySisa as $key => $data) {
			$groupQtySisa = $data;
		}

		// echo $konversi_abaikan_rollback;

		if ($groupSkuEmpty !== "") {
			$this->db->trans_rollback();
			echo json_encode($groupSkuEmpty);
		} else {
			if ($groupSkuStokKurang !== "") {
				$this->db->trans_rollback();
				echo json_encode($groupSkuStokKurang);
			} else {
				if ($groupQtySisa !== "") {
					$this->db->trans_rollback();
					echo json_encode($groupQtySisa);
				} else {
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						echo json_encode(['type' => 202, 'data' => null]);
					} else {
						$this->db->trans_commit();
						echo json_encode(['type' => 200, 'data' => null]);
					}
				}
			}
		}

		// if ($this->db->trans_status() === FALSE) {
		// 	$this->db->trans_rollback();
		// 	echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan!'));
		// } else {
		// 	$this->db->trans_commit();
		// 	echo json_encode(array('status' => true, 'message' => 'Data berhasil disimpan!'));
		// }
	}

	public function insert_delivery_order_draft2()
	{
		$delivery_order_batch_id 				= $this->input->post('delivery_order_batch_id');
		$delivery_order_draft_id 				= $this->input->post('delivery_order_draft_id');
		$sales_order_id 						= $this->input->post('sales_order_id');
		$delivery_order_draft_kode 				= $this->input->post('delivery_order_draft_kode');
		$delivery_order_draft_yourref 			= $this->input->post('delivery_order_draft_yourref');
		$client_wms_id 							= $this->input->post('client_wms_id');
		$delivery_order_draft_tgl_buat_do 		= $this->input->post('delivery_order_draft_tgl_buat_do');
		$delivery_order_draft_tgl_expired_do 	= $this->input->post('delivery_order_draft_tgl_expired_do');
		$delivery_order_draft_tgl_surat_jalan 	= $this->input->post('delivery_order_draft_tgl_surat_jalan');
		$delivery_order_draft_tgl_rencana_kirim = $this->input->post('delivery_order_draft_tgl_rencana_kirim');
		$delivery_order_draft_tgl_aktual_kirim 	= $this->input->post('delivery_order_draft_tgl_aktual_kirim');
		$delivery_order_draft_keterangan 		= $this->input->post('delivery_order_draft_keterangan');
		$delivery_order_draft_status 			= $this->input->post('delivery_order_draft_status');
		$delivery_order_draft_is_prioritas 		= $this->input->post('delivery_order_draft_is_prioritas');
		$delivery_order_draft_is_need_packing 	= $this->input->post('delivery_order_draft_is_need_packing');
		$delivery_order_draft_tipe_layanan 		= $this->input->post('delivery_order_draft_tipe_layanan');
		$delivery_order_draft_tipe_pembayaran 	= $this->input->post('delivery_order_draft_tipe_pembayaran');
		$delivery_order_draft_sesi_pengiriman 	= $this->input->post('delivery_order_draft_sesi_pengiriman');
		$delivery_order_draft_request_tgl_kirim = $this->input->post('delivery_order_draft_request_tgl_kirim');
		$delivery_order_draft_request_jam_kirim = $this->input->post('delivery_order_draft_request_jam_kirim');
		$tipe_pengiriman_id 					= $this->input->post('tipe_pengiriman_id');
		$nama_tipe 								= $this->input->post('nama_tipe');
		$confirm_rate 							= $this->input->post('confirm_rate');
		$delivery_order_draft_reff_id 			= $this->input->post('delivery_order_draft_reff_id');
		$delivery_order_draft_reff_no 			= $this->input->post('delivery_order_draft_reff_no');
		$delivery_order_draft_total 			= $this->input->post('delivery_order_draft_total');
		$unit_mandiri_id 						= $this->input->post('unit_mandiri_id');
		$depo_id 								= $this->input->post('depo_id');
		$client_pt_id 							= $this->input->post('client_pt_id');
		$delivery_order_draft_kirim_nama 		= $this->input->post('delivery_order_draft_kirim_nama');
		$delivery_order_draft_kirim_alamat 		= $this->input->post('delivery_order_draft_kirim_alamat');
		$delivery_order_draft_kirim_telp 		= $this->input->post('delivery_order_draft_kirim_telp');
		$delivery_order_draft_kirim_provinsi 	= $this->input->post('delivery_order_draft_kirim_provinsi');
		$delivery_order_draft_kirim_kota 		= $this->input->post('delivery_order_draft_kirim_kota');
		$delivery_order_draft_kirim_kecamatan 	= $this->input->post('delivery_order_draft_kirim_kecamatan');
		$delivery_order_draft_kirim_kelurahan 	= $this->input->post('delivery_order_draft_kirim_kelurahan');
		$delivery_order_draft_kirim_kodepos 	= $this->input->post('delivery_order_draft_kirim_kodepos');
		$delivery_order_draft_kirim_area 		= $this->input->post('delivery_order_draft_kirim_area');
		$delivery_order_draft_kirim_invoice_pdf = $this->input->post('delivery_order_draft_kirim_invoice_pdf');
		$delivery_order_draft_kirim_invoice_dir = $this->input->post('delivery_order_draft_kirim_invoice_dir');
		$pabrik_id 								= $this->input->post('pabrik_id');
		$delivery_order_draft_ambil_nama		= $this->input->post('delivery_order_draft_ambil_nama');
		$delivery_order_draft_ambil_alamat		= $this->input->post('delivery_order_draft_ambil_alamat');
		$delivery_order_draft_ambil_telp		= $this->input->post('delivery_order_draft_ambil_telp');
		$delivery_order_draft_ambil_provinsi	= $this->input->post('delivery_order_draft_ambil_provinsi');
		$delivery_order_draft_ambil_kota		= $this->input->post('delivery_order_draft_ambil_kota');
		$delivery_order_draft_ambil_kecamatan 	= $this->input->post('delivery_order_draft_ambil_kecamatan');
		$delivery_order_draft_ambil_kelurahan 	= $this->input->post('delivery_order_draft_ambil_kelurahan');
		$delivery_order_draft_ambil_kodepos 	= $this->input->post('delivery_order_draft_ambil_kodepos');
		$delivery_order_draft_ambil_area 		= $this->input->post('delivery_order_draft_ambil_area');
		$delivery_order_draft_update_who 		= $this->input->post('delivery_order_draft_update_who');
		$delivery_order_draft_update_tgl 		= $this->input->post('delivery_order_draft_update_tgl');
		$delivery_order_draft_approve_who 		= $this->input->post('delivery_order_draft_approve_who');
		$delivery_order_draft_approve_tgl 		= $this->input->post('delivery_order_draft_approve_tgl');
		$delivery_order_draft_reject_who 		= $this->input->post('delivery_order_draft_reject_who');
		$delivery_order_draft_reject_tgl 		= $this->input->post('delivery_order_draft_reject_tgl');
		$delivery_order_draft_reject_reason 	= $this->input->post('delivery_order_draft_reject_reason');
		$tipe_delivery_order_id 				= $this->input->post('tipe_delivery_order_id');
		$tipe_delivery_order_nama 				= $this->input->post('tipe_delivery_order_nama');
		$detail 								= $this->input->post('detail');
		$arr_do 								= $this->input->post('arr_do');
		$tgl_kirim_ulang 						= $this->input->post('tgl_kirim_ulang');
		$status 								= $this->input->post('status');

		// $detail2 = $this->input->post('detail2');
		$delivery_order_draft_input_pembayaran_tunai = $this->input->post('delivery_order_draft_nominal_tunai');
		$file = $this->input->post('file');

		$area = $this->input->post('area');

		$error = 0;
		$emptySKUInStock = [];
		unset($emptySKUInStock);
		$emptySKUInStock = array();

		$emptyStockKurang = [];
		unset($emptyStockKurang);
		$emptyStockKurang = array();

		$chkSisaQty = [];
		unset($chkSisaQty);
		$chkSisaQty = array();

		$dod_id = $this->M_Vrbl->Get_NewID();
		$dod_id = $dod_id[0]['NEW_ID'];

		//generate kode
		$date_now 		= date('Y-m-d h:i:s');
		$param 			= $tipe_delivery_order_nama == 'Retur' ? 'KODE_DOR' : 'KODE_DOD';
		$vrbl 			= $this->M_Vrbl->Get_Kode($param);
		$prefix 		= $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id 		= $this->session->userdata('depo_id');
		$depoPrefix 	= $this->M_DeliveryOrderDraft->getDepoPrefix($depo_id);
		$unit 			= $depoPrefix->depo_kode_preffix;
		$generate_kode 	= $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$delivery_order_draft_attachment = $this->input->post('delivery_order_draft_attachment');

		$this->db->trans_begin();
		// $getTglKirimBySTK = $this->db->query("select dob.delivery_order_batch_tanggal_kirim from delivery_order_batch dob where dob.delivery_order_batch_id ='$delivery_order_batch_id'")->row()->delivery_order_batch_tanggal_kirim;

		//update status is_reschedule DeliveryOrder
		$this->M_KelolaDOGagal->updateIsRescheduleDeliveryOrder($status, $delivery_order_draft_id);

		if ($delivery_order_draft_status == "not delivered") {
			//insert ke tr_pemusnahan_stok_draft

			$delivery_order_draft_status = "Approved";

			$result = $this->M_DeliveryOrderDraft->insert_delivery_order_draft(
				$dod_id,
				$sales_order_id,
				$generate_kode,
				$delivery_order_draft_yourref,
				$client_wms_id,
				$delivery_order_draft_tgl_buat_do,
				$delivery_order_draft_tgl_expired_do,
				$delivery_order_draft_tgl_surat_jalan,
				// $getTglKirimBySTK,
				$tgl_kirim_ulang,
				// $delivery_order_draft_tgl_aktual_kirim,
				// $getTglKirimBySTK,
				$tgl_kirim_ulang,
				$delivery_order_draft_keterangan,
				$delivery_order_draft_status,
				$delivery_order_draft_is_prioritas,
				$delivery_order_draft_is_need_packing,
				$delivery_order_draft_tipe_layanan,
				$delivery_order_draft_tipe_pembayaran,
				$delivery_order_draft_sesi_pengiriman,
				null,
				$delivery_order_draft_request_jam_kirim,
				$tipe_pengiriman_id,
				$nama_tipe,
				$confirm_rate,
				$delivery_order_draft_reff_id,
				$delivery_order_draft_reff_no,
				$delivery_order_draft_total,
				$unit_mandiri_id,
				$depo_id,
				$client_pt_id,
				$delivery_order_draft_kirim_nama,
				$delivery_order_draft_kirim_alamat,
				$delivery_order_draft_kirim_telp,
				$delivery_order_draft_kirim_provinsi,
				$delivery_order_draft_kirim_kota,
				$delivery_order_draft_kirim_kecamatan,
				$delivery_order_draft_kirim_kelurahan,
				$delivery_order_draft_kirim_kodepos,
				$delivery_order_draft_kirim_area,
				$delivery_order_draft_kirim_invoice_pdf,
				$delivery_order_draft_kirim_invoice_dir,
				$pabrik_id,
				$delivery_order_draft_ambil_nama,
				$delivery_order_draft_ambil_alamat,
				$delivery_order_draft_ambil_telp,
				$delivery_order_draft_ambil_provinsi,
				$delivery_order_draft_ambil_kota,
				$delivery_order_draft_ambil_kecamatan,
				$delivery_order_draft_ambil_kelurahan,
				$delivery_order_draft_ambil_kodepos,
				$delivery_order_draft_ambil_area,
				$delivery_order_draft_update_who,
				$delivery_order_draft_update_tgl,
				$delivery_order_draft_approve_who,
				$delivery_order_draft_approve_tgl,
				$delivery_order_draft_reject_who,
				$delivery_order_draft_reject_tgl,
				$delivery_order_draft_reject_reason,
				$tipe_delivery_order_nama == 'Retur' ? 'C5BE83E2-01E8-4E24-B766-26BB4158F2CD' : $tipe_delivery_order_id,
				$delivery_order_draft_input_pembayaran_tunai,
				$delivery_order_draft_attachment
			);

			if (!$result) {
				echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan!'));
			} else {
				$data_detail = json_decode($detail);
				//insert ke tr_pemusnahan_stok_detail_draft

				foreach ($data_detail as $key => $value) {
					$dod_id_ = $this->M_Vrbl->Get_NewID();
					$dod_id_ = $dod_id_[0]['NEW_ID'];
					$this->M_DeliveryOrderDraft->insert_delivery_order_detail_draft($dod_id_, $dod_id, $value);

					$qtyDet = $value->sku_qty;

					if ($tipe_delivery_order_id != "C5BE83E2-01E8-4E24-B766-26BB4158F2CD") {
						if ($qtyDet < 0) {
							continue;
							# code...
						}

						if ($value->sku_request_expdate == 0) {

							//filter sku_stock berdasarkan client wms id, sku id
							//ambil qty berdasarkan expired teratas
							$sisaQty = $qtyDet;
							$sku_id_before = "";

							$GetDataSKU = $this->M_KelolaDOGagal->GetDataSKUByReqNull($client_wms_id, $value->sku_id);
							// echo json_encode($GetDataSKU);

							$lastKey = array_search(end($GetDataSKU), $GetDataSKU);

							if (count($GetDataSKU) == 0) {
								$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
								array_push($emptySKUInStock, ['type' => 203, 'data' => $getNameSKU]);
							} else {
								$arr_sum = 0;
								foreach ($GetDataSKU as $key => $val) {
									$sumqtyStock = $val->sku_stock_awal + $val->sku_stock_masuk - $val->sku_stock_saldo_alokasi - $val->sku_stock_keluar;

									$arr_sum += $sumqtyStock;
								}

								if ($qtyDet > $arr_sum) {
									$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
									array_push($emptyStockKurang, ['type' => 204, 'data' => $getNameSKU]);

									// break;
								} else {

									foreach ($GetDataSKU as $key => $val) {

										if (($val->client_wms_id == $client_wms_id) && ($val->sku_id == $value->sku_id)) {
											$qtyStock = $val->sku_stock_awal + $val->sku_stock_masuk - $val->sku_stock_saldo_alokasi - $val->sku_stock_keluar;
											$sku_stock_id = $val->sku_stock_id;

											if ($qtyStock == 0) {
												continue;
											}

											if ($qtyStock >= $sisaQty) {

												$qtyDet2 = $sisaQty;

												$dod2_id = $this->M_Vrbl->Get_NewID();
												$dod2_id = $dod2_id[0]['NEW_ID'];
												$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $val->sku_stock_expired_date, $qtyDet2, $val->sku_konversi_faktor);

												// cek stok_alokasi yg sudah ada
												$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

												//update alokasi stock sku
												$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
												$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

												// $this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);
												$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $qtyDet2, $sku_stock_id);

												$sku_id_before = $val->sku_id;

												# break...
												break;
											} else {

												$sisaQty -= $qtyStock;
												$qtyDet2 = $qtyStock;

												$dod2_id_2 = $this->M_Vrbl->Get_NewID();
												$dod2_id_2 = $dod2_id_2[0]['NEW_ID'];


												$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id_2, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $val->sku_stock_expired_date, $qtyDet2, $val->sku_konversi_faktor);

												// cek stok_alokasi yg sudah ada
												$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

												//update alokasi stock sku
												$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
												$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

												// $this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);
												$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $qtyDet2, $sku_stock_id);

												if ($sisaQty <= 0) {
													break;
												};

												if ($key == $lastKey) {
													if ($sisaQty >= 0) {

														$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);

														array_push($chkSisaQty, ['type' => 201, 'data' => $getNameSKU]);
													}
												}

												$sku_id_before = $val->sku_id;
											}
										} else {
											continue;
										}
									}
								}
							}
						} else if ($value->sku_request_expdate == 1) {
							//filter sku_stock berdasarkan client wms id, sku id
							//ambil qty berdasarkan expired request diatasnya, misal req ed 5 jadi ambil diatasnya 5 
							if ($value->sku_filter_expdate == ">=") {

								$sisaQty = $qtyDet;
								$sku_id_before = "";

								$GetDataSKUBesarDari = $this->M_KelolaDOGagal->GetDataSKUByReqBesarDari($client_wms_id, $value->sku_id, $value->sku_filter_expdatebulan, date("Y-m-d", strtotime(str_replace("/", "-", $delivery_order_draft_tgl_buat_do))));

								$lastKey = array_search(end($GetDataSKUBesarDari), $GetDataSKUBesarDari);

								if (count($GetDataSKUBesarDari) == 0) {

									$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
									array_push($emptySKUInStock, ['type' => 203, 'data' => $getNameSKU]);
								} else {

									$arr_sum = 0;
									foreach ($GetDataSKUBesarDari as $key => $item) {
										$qtySisaStock = $item->sku_stock_awal + $item->sku_stock_masuk - $item->sku_stock_saldo_alokasi - $item->sku_stock_keluar;

										$arr_sum += $qtySisaStock;
									}

									if ($sisaQty > $arr_sum) {
										$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
										array_push($emptyStockKurang, ['type' => 204, 'data' => $getNameSKU]);

										// break;
									} else {
										foreach ($GetDataSKUBesarDari as $key => $item) {
											if (($item->client_wms_id == $client_wms_id) && ($item->sku_id == $value->sku_id)) {
												# code...
												$newIDDet2 = $this->M_Vrbl->Get_NewID();
												$newIDDet1 = $newIDDet2[0]['NEW_ID'];

												// cek stok_alokasi yg sudah ada
												// $dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($item->sku_stock_id);

												$qtySisaStock = $item->sku_stock_awal + $item->sku_stock_masuk - $item->sku_stock_saldo_alokasi - $item->sku_stock_keluar;
												$sku_stock_id = $item->sku_stock_id;

												if ($qtySisaStock == 0) {
													continue;
												}

												if ($qtySisaStock >= $sisaQty) {

													$qtyDet2 = $sisaQty;

													$dod2_id = $this->M_Vrbl->Get_NewID();
													$dod2_id = $dod2_id[0]['NEW_ID'];

													$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $item->sku_stock_expired_date, $qtyDet2, $item->sku_konversi_faktor);

													// cek stok_alokasi yg sudah ada
													$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

													//update alokasi stock sku
													$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
													$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

													$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);

													# break...
													break;
												} else {
													$sisaQty -= $qtySisaStock;
													$qtyDet2 = $qtySisaStock;

													$dod2_id_2 = $this->M_Vrbl->Get_NewID();
													$dod2_id_2 = $dod2_id_2[0]['NEW_ID'];


													$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id_2, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $item->sku_stock_expired_date, $qtyDet2, $item->sku_konversi_faktor);

													// cek stok_alokasi yg sudah ada
													$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

													//update alokasi stock sku
													$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
													$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

													$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);

													if ($sisaQty <= 0) {
														break;
													};

													if ($key == $lastKey) {
														if ($sisaQty >= 0) {

															$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);

															array_push($chkSisaQty, ['type' => 201, 'data' => $getNameSKU]);
														}
													}
												}
											} else {
												continue;
											}
										}
									}
								}
							}
						}
					} else {
						$do_detail2 = $this->M_KelolaDOGagal->getDeliveryOrderDetail2ByDOID($delivery_order_draft_id);

						foreach ($do_detail2 as $value) {
							$newIDDOD2 = $this->M_Vrbl->Get_NewID();
							$newIDDOD2 = $newIDDOD2[0]['NEW_ID'];
							$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($newIDDOD2, $dod_id_, $dod_id, $value->sku_id, $value->sku_stock_id, $value->sku_expdate, $value->sku_qty, $value->sku_qty_composite);
						}
					}
				}

				// foreach ($data_detail as $key => $value) {
				// 	$dod_id_ = $this->M_Vrbl->Get_NewID();
				// 	$dod_id_ = $dod_id_[0]['NEW_ID'];
				// 	$this->M_DeliveryOrderDraft->insert_delivery_order_detail_draft($dod_id_, $dod_id, $value);

				// 	$data_detail2 = $this->M_KelolaDOGagal->GetKelolaDOGagalDetail2ByListId($delivery_order_draft_reff_id, $value->sku_id);

				// 	foreach ($data_detail2 as $key2 => $value2) {
				// 		$dod_id_2 = $this->M_Vrbl->Get_NewID();
				// 		$dod_id_2 = $dod_id_2[0]['NEW_ID'];
				// 		$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod_id_2, $dod_id_, $dod_id, $value2['sku_id'], $value2['sku_stock_id'], $value2['sku_expdate'], $value2['sku_qty']);
				// 	}
				// }
			}

			// $no_urut = $this->M_KelolaDOGagal->GetLastNoUrut($delivery_order_batch_id) + 1;
			// $list_do = $this->M_DeliveryOrderBatch->GetDODraftById($dod_id, $no_urut);

			// foreach ($list_do as $key => $value) {

			// 	$do_id = $this->M_Vrbl->Get_NewID();
			// 	$do_id = $do_id[0]['NEW_ID'];

			// 	//generate kode
			// 	$date_now = date('Y-m-d h:i:s');
			// 	$param =  'KODE_DO';
			// 	$vrbl = $this->M_Vrbl->Get_Kode($param);
			// 	$prefix = $vrbl->vrbl_kode;
			// 	// get prefik depo
			// 	$depo_id = $this->session->userdata('depo_id');
			// 	$depoPrefix = $this->M_DeliveryOrderBatch->getDepoPrefix($depo_id);
			// 	$unit = $depoPrefix->depo_kode_preffix;
			// 	$do_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

			// 	$cek_do = $this->M_KelolaDOGagal->cek_do_by_do_draft($delivery_order_draft_id);

			// 	if ($cek_do == 0) {
			// 		// $this->M_DeliveryOrderBatch->insert_delivery_order($delivery_order_batch_id, $do_id, $do_kode, $value, "insert", null);
			// 		// $this->M_KelolaDOGagal->UpdateFdjr($delivery_order_batch_id);
			// 		// $this->M_KelolaDOGagal->InsertDoAreaIfNotExistByDO($delivery_order_batch_id, $do_id);

			// 		$dodd = $this->M_DeliveryOrderBatch->GetDODraftDetailById($dod_id);

			// 		foreach ($dodd as $key => $value2) {
			// 			$do_detail_id = $this->M_Vrbl->Get_NewID();
			// 			$do_detail_id = $do_detail_id[0]['NEW_ID'];

			// 			// $this->M_DeliveryOrderBatch->insert_delivery_order_detail($do_detail_id, $do_id, $delivery_order_batch_id, $value2);
			// 			// $this->M_DeliveryOrderBatch->insert_delivery_order_detail($value2['delivery_order_detail_draft_id'], $do_id, $delivery_order_batch_id, $value2);
			// 		}
			// 	}
			// }
		} else if ($delivery_order_draft_status == "partially delivered") {
			$delivery_order_draft_status = "Approved";

			$result = $this->M_DeliveryOrderDraft->insert_delivery_order_draft(
				$dod_id,
				$sales_order_id,
				$generate_kode,
				$delivery_order_draft_yourref,
				$client_wms_id,
				$delivery_order_draft_tgl_buat_do,
				$delivery_order_draft_tgl_expired_do,
				$delivery_order_draft_tgl_surat_jalan,
				// $getTglKirimBySTK,
				$tgl_kirim_ulang,
				// $delivery_order_draft_tgl_aktual_kirim,
				// $getTglKirimBySTK,
				$tgl_kirim_ulang,
				$delivery_order_draft_keterangan,
				$delivery_order_draft_status,
				$delivery_order_draft_is_prioritas,
				$delivery_order_draft_is_need_packing,
				$delivery_order_draft_tipe_layanan,
				$delivery_order_draft_tipe_pembayaran,
				$delivery_order_draft_sesi_pengiriman,
				null,
				$delivery_order_draft_request_jam_kirim,
				$tipe_pengiriman_id,
				$nama_tipe,
				$confirm_rate,
				$delivery_order_draft_reff_id,
				$delivery_order_draft_reff_no,
				$delivery_order_draft_total,
				$unit_mandiri_id,
				$depo_id,
				$client_pt_id,
				$delivery_order_draft_kirim_nama,
				$delivery_order_draft_kirim_alamat,
				$delivery_order_draft_kirim_telp,
				$delivery_order_draft_kirim_provinsi,
				$delivery_order_draft_kirim_kota,
				$delivery_order_draft_kirim_kecamatan,
				$delivery_order_draft_kirim_kelurahan,
				$delivery_order_draft_kirim_kodepos,
				$delivery_order_draft_kirim_area,
				$delivery_order_draft_kirim_invoice_pdf,
				$delivery_order_draft_kirim_invoice_dir,
				$pabrik_id,
				$delivery_order_draft_ambil_nama,
				$delivery_order_draft_ambil_alamat,
				$delivery_order_draft_ambil_telp,
				$delivery_order_draft_ambil_provinsi,
				$delivery_order_draft_ambil_kota,
				$delivery_order_draft_ambil_kecamatan,
				$delivery_order_draft_ambil_kelurahan,
				$delivery_order_draft_ambil_kodepos,
				$delivery_order_draft_ambil_area,
				$delivery_order_draft_update_who,
				$delivery_order_draft_update_tgl,
				$delivery_order_draft_approve_who,
				$delivery_order_draft_approve_tgl,
				$delivery_order_draft_reject_who,
				$delivery_order_draft_reject_tgl,
				$delivery_order_draft_reject_reason,
				$tipe_delivery_order_nama == 'Retur' ? 'C5BE83E2-01E8-4E24-B766-26BB4158F2CD' : $tipe_delivery_order_id,
				$delivery_order_draft_input_pembayaran_tunai,
				$delivery_order_draft_attachment
			);

			if (!$result) {
				echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan!'));
			} else {
				$data_detail = json_decode($detail);

				//insert ke tr_pemusnahan_stok_detail_draft

				foreach ($data_detail as $key => $value) {
					$dod_id_ = $this->M_Vrbl->Get_NewID();
					$dod_id_ = $dod_id_[0]['NEW_ID'];
					$this->M_DeliveryOrderDraft->insert_delivery_order_detail_draft($dod_id_, $dod_id, $value);

					$qtyDet = $value->sku_qty;

					if ($tipe_delivery_order_id != "C5BE83E2-01E8-4E24-B766-26BB4158F2CD") {
						if ($qtyDet < 0) {
							continue;
							# code...
						}

						if ($value->sku_request_expdate == 0) {

							//filter sku_stock berdasarkan client wms id, sku id
							//ambil qty berdasarkan expired teratas
							$sisaQty = $qtyDet;
							$sku_id_before = "";

							$GetDataSKU = $this->M_KelolaDOGagal->GetDataSKUByStatusFlag($client_wms_id, $value->sku_id);
							// $GetDataSKU = $this->M_KelolaDOGagal->GetDataSKUByReqNull($client_wms_id, $value->sku_id);

							$lastKey = array_search(end($GetDataSKU), $GetDataSKU);

							if (count($GetDataSKU) == 0) {
								$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
								array_push($emptySKUInStock, ['type' => 203, 'data' => $getNameSKU]);
							} else {
								$arr_sum = 0;
								foreach ($GetDataSKU as $key => $val) {
									$sumqtyStock = $val->sku_stock_awal + $val->sku_stock_masuk - $val->sku_stock_saldo_alokasi - $val->sku_stock_keluar;

									$arr_sum += $sumqtyStock;
								}

								if ($qtyDet > $arr_sum) {
									$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
									array_push($emptyStockKurang, ['type' => 204, 'data' => $getNameSKU]);

									// break;
								} else {

									foreach ($GetDataSKU as $key => $val) {

										if (($val->client_wms_id == $client_wms_id) && ($val->sku_id == $value->sku_id)) {

											$qtyStock = $val->sku_stock_awal + $val->sku_stock_masuk - $val->sku_stock_saldo_alokasi - $val->sku_stock_keluar;
											$sku_stock_id = $val->sku_stock_id;

											if ($qtyStock == 0) {
												continue;
											}

											if ($qtyStock >= $sisaQty) {

												$qtyDet2 = $sisaQty;

												$dod2_id = $this->M_Vrbl->Get_NewID();
												$dod2_id = $dod2_id[0]['NEW_ID'];
												$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $val->sku_stock_expired_date, $qtyDet2, $val->sku_konversi_faktor);

												// cek stok_alokasi yg sudah ada
												$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

												//update alokasi stock sku
												$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
												$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

												// $this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);
												$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $qtyDet2, $sku_stock_id);

												$sku_id_before = $val->sku_id;

												# break...
												break;
											} else {

												$sisaQty -= $qtyStock;
												$qtyDet2 = $qtyStock;

												$dod2_id_2 = $this->M_Vrbl->Get_NewID();
												$dod2_id_2 = $dod2_id_2[0]['NEW_ID'];


												$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id_2, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $val->sku_stock_expired_date, $qtyDet2, $val->sku_konversi_faktor);

												// cek stok_alokasi yg sudah ada
												$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

												//update alokasi stock sku
												$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
												$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

												// $this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);
												$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $qtyDet2, $sku_stock_id);

												if ($sisaQty <= 0) {
													break;
												};

												if ($key == $lastKey) {
													if ($sisaQty >= 0) {

														$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);

														array_push($chkSisaQty, ['type' => 201, 'data' => $getNameSKU]);
													}
												}

												$sku_id_before = $val->sku_id;
											}
										} else {
											continue;
										}
									}
								}
							}
						} else if ($value->sku_request_expdate == 1) {
							//filter sku_stock berdasarkan client wms id, sku id
							//ambil qty berdasarkan expired request diatasnya, misal req ed 5 jadi ambil diatasnya 5 
							if ($value->sku_filter_expdate == ">=") {

								$sisaQty = $qtyDet;
								$sku_id_before = "";

								$GetDataSKUBesarDari = $this->M_KelolaDOGagal->GetDataSKUByStatusFlag($client_wms_id, $value->sku_id, $value->sku_filter_expdatebulan, date("Y-m-d", strtotime(str_replace("/", "-", $delivery_order_draft_tgl_buat_do))));

								$lastKey = array_search(end($GetDataSKUBesarDari), $GetDataSKUBesarDari);

								if (count($GetDataSKUBesarDari) == 0) {

									$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
									array_push($emptySKUInStock, ['type' => 203, 'data' => $getNameSKU]);
								} else {

									$arr_sum = 0;
									foreach ($GetDataSKUBesarDari as $key => $item) {
										$qtySisaStock = $item->sku_stock_awal + $item->sku_stock_masuk - $item->sku_stock_saldo_alokasi - $item->sku_stock_keluar;

										$arr_sum += $qtySisaStock;
									}

									if ($sisaQty > $arr_sum) {
										$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);
										array_push($emptyStockKurang, ['type' => 204, 'data' => $getNameSKU]);

										// break;
									} else {
										foreach ($GetDataSKUBesarDari as $key => $item) {
											if (($item->client_wms_id == $client_wms_id) && ($item->sku_id == $value->sku_id)) {
												# code...
												$newIDDet2 = $this->M_Vrbl->Get_NewID();
												$newIDDet1 = $newIDDet2[0]['NEW_ID'];

												// cek stok_alokasi yg sudah ada
												// $dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($item->sku_stock_id);

												$qtySisaStock = $item->sku_stock_awal + $item->sku_stock_masuk - $item->sku_stock_saldo_alokasi - $item->sku_stock_keluar;
												$sku_stock_id = $item->sku_stock_id;

												if ($qtySisaStock == 0) {
													continue;
												}

												if ($qtySisaStock >= $sisaQty) {

													$qtyDet2 = $sisaQty;

													$dod2_id = $this->M_Vrbl->Get_NewID();
													$dod2_id = $dod2_id[0]['NEW_ID'];

													$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $item->sku_stock_expired_date, $qtyDet2, $item->sku_konversi_faktor);

													// cek stok_alokasi yg sudah ada
													$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

													//update alokasi stock sku
													$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
													$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

													$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);

													# break...
													break;
												} else {
													$sisaQty -= $qtySisaStock;
													$qtyDet2 = $qtySisaStock;

													$dod2_id_2 = $this->M_Vrbl->Get_NewID();
													$dod2_id_2 = $dod2_id_2[0]['NEW_ID'];


													$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id_2, $dod_id_, $dod_id, $value->sku_id, $sku_stock_id, $item->sku_stock_expired_date, $qtyDet2, $item->sku_konversi_faktor);

													// cek stok_alokasi yg sudah ada
													$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

													//update alokasi stock sku
													$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
													$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

													$this->M_KelolaDOGagal->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);

													if ($sisaQty <= 0) {
														break;
													};

													if ($key == $lastKey) {
														if ($sisaQty >= 0) {

															$getNameSKU = $this->M_KelolaDOGagal->getNameSKUById($client_wms_id, $value->sku_id);

															array_push($chkSisaQty, ['type' => 201, 'data' => $getNameSKU]);
														}
													}
												}
											} else {
												continue;
											}
										}
									}
								}
							}
						}
					} else {
						$do_detail2 = $this->M_KelolaDOGagal->getDeliveryOrderDetail2ByDOID($delivery_order_draft_id);

						foreach ($do_detail2 as $value) {
							$newIDDOD2 = $this->M_Vrbl->Get_NewID();
							$newIDDOD2 = $newIDDOD2[0]['NEW_ID'];
							$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($newIDDOD2, $dod_id_, $dod_id, $value->sku_id, $value->sku_stock_id, $value->sku_expdate, $value->sku_qty, $value->sku_qty_composite);
						}
					}
				}
			}

			// $no_urut = $this->M_KelolaDOGagal->GetLastNoUrut($delivery_order_batch_id) + 1;
			// $list_do = $this->M_DeliveryOrderBatch->GetDODraftById($dod_id, $no_urut);

			// foreach ($list_do as $key => $value) {

			// 	$do_id = $this->M_Vrbl->Get_NewID();
			// 	$do_id = $do_id[0]['NEW_ID'];

			// 	//generate kode
			// 	$date_now = date('Y-m-d h:i:s');
			// 	$param =  'KODE_DO';
			// 	$vrbl = $this->M_Vrbl->Get_Kode($param);
			// 	$prefix = $vrbl->vrbl_kode;
			// 	// get prefik depo
			// 	$depo_id = $this->session->userdata('depo_id');
			// 	$depoPrefix = $this->M_DeliveryOrderBatch->getDepoPrefix($depo_id);
			// 	$unit = $depoPrefix->depo_kode_preffix;
			// 	$do_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

			// 	$cek_do = $this->M_KelolaDOGagal->cek_do_by_do_draft($delivery_order_draft_id);

			// 	if ($cek_do == 0) {
			// 		// $this->M_DeliveryOrderBatch->insert_delivery_order($delivery_order_batch_id, $do_id, $do_kode, $value, "insert", null);
			// 		// $this->M_KelolaDOGagal->UpdateFdjr($delivery_order_batch_id);
			// 		// $this->M_KelolaDOGagal->InsertDoAreaIfNotExistByDO($delivery_order_batch_id, $do_id);

			// 		$dodd = $this->M_DeliveryOrderBatch->GetDODraftDetailById($dod_id);

			// 		foreach ($dodd as $key => $value2) {
			// 			$do_detail_id = $this->M_Vrbl->Get_NewID();
			// 			$do_detail_id = $do_detail_id[0]['NEW_ID'];

			// 			// $this->M_DeliveryOrderBatch->insert_delivery_order_detail($do_detail_id, $do_id, $delivery_order_batch_id, $value2);
			// 			// $this->M_DeliveryOrderBatch->insert_delivery_order_detail($value2['delivery_order_detail_draft_id'], $do_id, $delivery_order_batch_id, $value2);
			// 		}
			// 	}
			// }
		} else {
			$no_urut = $this->M_KelolaDOGagal->GetLastNoUrut($delivery_order_batch_id) + 1;
			$list_do = $this->M_DeliveryOrderBatch->GetDODraftById($delivery_order_draft_id, $no_urut);

			foreach ($list_do as $key => $value) {

				$do_id = $this->M_Vrbl->Get_NewID();
				$do_id = $do_id[0]['NEW_ID'];

				//generate kode
				$date_now = date('Y-m-d h:i:s');
				$param =  'KODE_DO';
				$vrbl = $this->M_Vrbl->Get_Kode($param);
				$prefix = $vrbl->vrbl_kode;
				// get prefik depo
				$depo_id = $this->session->userdata('depo_id');
				$depoPrefix = $this->M_DeliveryOrderBatch->getDepoPrefix($depo_id);
				$unit = $depoPrefix->depo_kode_preffix;
				$do_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

				$cek_do = $this->M_KelolaDOGagal->cek_do_by_do_draft($delivery_order_draft_id);

				if ($cek_do == 0) {
					// $this->M_DeliveryOrderBatch->insert_delivery_order($delivery_order_batch_id, $do_id, $do_kode, $value, "insert", null);

					// $this->M_KelolaDOGagal->InsertDoAreaIfNotExistByDO($delivery_order_batch_id, $do_id);
					// $this->M_KelolaDOGagal->UpdateFdjr($delivery_order_batch_id);

					$dodd = $this->M_DeliveryOrderBatch->GetDODraftDetailById($delivery_order_draft_id);

					foreach ($dodd as $key => $value2) {
						$do_detail_id = $this->M_Vrbl->Get_NewID();
						$do_detail_id = $do_detail_id[0]['NEW_ID'];

						// $this->M_DeliveryOrderBatch->insert_delivery_order_detail($do_detail_id, $do_id, $delivery_order_batch_id, $value2);
						// $this->M_DeliveryOrderBatch->insert_delivery_order_detail($value2['delivery_order_detail_draft_id'], $do_id, $delivery_order_batch_id, $value2);
					}
				}
			}
		}


		// update fdjr

		$skuEmpty = [];

		$groupSkuEmpty = "";
		foreach ($emptySKUInStock as $key => $data) {

			// $msg = "Qty dari SKU <strong>" . $data['data']->sku_kode . "</strong> tidak ada!";
			// $this->M_DeliveryOrderDraft->Insert_delivery_order_draft_detail_msg($delivery_order_draft_id, $data['data']->sku_kode, $msg);

			$tmpdata = $data;
			unset($tmpdata['type']);
			$skuEmpty[$data['type']]['type'] = $data['type'];
			$skuEmpty[$data['type']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}

		foreach ($skuEmpty as $key => $data) {
			// $tmpdata = $data;
			$groupSkuEmpty = $data;
		}

		$SkuStokKurang = [];

		$groupSkuStokKurang = "";
		foreach ($emptyStockKurang as $key => $data) {

			// $msg = "Qty dari SKU <strong>" . $data['data']->sku_kode . "</strong> kurang";
			// $this->M_DeliveryOrderDraft->Insert_delivery_order_draft_detail_msg($delivery_order_draft_id, $data['data']->sku_kode, $msg);

			$tmpdata = $data;
			unset($tmpdata['type']);
			$SkuStokKurang[$data['type']]['type'] = $data['type'];
			$SkuStokKurang[$data['type']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}

		foreach ($SkuStokKurang as $key => $data) {
			// $tmpdata = $data;
			$groupSkuStokKurang = $data;
		}

		$tmpgroupQtySisa = [];

		$groupQtySisa = "";
		foreach ($chkSisaQty as $key => $data) {

			// $msg = "Qty dari SKU <strong>" . $data['data']->sku_kode . "</strong> tidak cukup, silahkan dicek kembali!";
			// $this->M_DeliveryOrderDraft->Insert_delivery_order_draft_detail_msg($delivery_order_draft_id, $data['data']->sku_kode, $msg);

			$tmpdata = $data;
			unset($tmpdata['type']);
			$tmpgroupQtySisa[$data['type']]['type'] = $data['type'];
			$tmpgroupQtySisa[$data['type']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}

		foreach ($tmpgroupQtySisa as $key => $data) {
			$groupQtySisa = $data;
		}

		// echo $konversi_abaikan_rollback;
		// $this->db->trans_rollback();
		// return false;

		if ($groupSkuEmpty !== "") {
			echo json_encode($groupSkuEmpty);
		} else {
			if ($groupSkuStokKurang !== "") {
				$this->db->trans_rollback();
				echo json_encode($groupSkuStokKurang);
			} else {
				if ($groupQtySisa !== "") {
					$this->db->trans_rollback();
					echo json_encode($groupQtySisa);
				} else {
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						echo json_encode(['type' => 202, 'data' => null]);
					} else {
						$this->db->trans_commit();
						// $this->db->trans_rollback();
						echo json_encode(['type' => 200, 'data' => null]);
					}
				}
			}
		}

		// if ($this->db->trans_status() === FALSE) {
		// 	$this->db->trans_rollback();
		// 	echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan!'));
		// } else {
		// 	$this->db->trans_commit();
		// 	echo json_encode(array('status' => true, 'message' => 'Data berhasil disimpan!'));
		// }
	}

	public function reject_delivery_order_draft()
	{
		$delivery_order_draft_id = $this->input->post('delivery_order_draft_id');
		$this->db->trans_begin();

		$this->M_KelolaDOGagal->reject_delivery_order_draft($delivery_order_draft_id);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function GetKelolaDOGagalByFilter()
	{

		$tgl = explode(" - ", $this->input->post('tgl'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$do_no = $this->input->post('do_no');
		$customer = $this->input->post('customer');
		$alamat = $this->input->post('alamat');
		$so_eksternal = $this->input->post('so_eksternal');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$tipe_layanan = $this->input->post('tipe_layanan');
		$status = $this->input->post('status');
		$tipe = $this->input->post('tipe');
		$segmen1 = $this->input->post('segmen1');
		$segmen2 = $this->input->post('segmen2');
		$segmen3 = $this->input->post('segmen3');
		$is_reschedule = $this->input->post('is_reschedule');
		$start = $this->input->post('start');
		$end = $this->input->post('length');
		$draw = $this->input->post('draw');
		$search = "";

		$totalData = $this->M_KelolaDOGagal->GetTotalKelolaDOGagalByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $search, $so_eksternal, $is_reschedule);

		$totalFiltered = $totalData;

		if (empty($this->input->post('search')['value'])) {
			$data = $this->M_KelolaDOGagal->GetKelolaDOGagalByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $start, $end, $search, $so_eksternal, $is_reschedule);
		} else {
			$search = $_POST['search']['value'];
			$data = $this->M_KelolaDOGagal->GetKelolaDOGagalByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $start, $end, $search, $so_eksternal, $is_reschedule);

			$datacount = $this->M_KelolaDOGagal->GetTotalKelolaDOGagalByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $search, $so_eksternal, $is_reschedule);

			$totalFiltered = $datacount;
		}

		$data = array(
			"draw" => intval($draw),
			"recordsTotal" => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data" => $data
		);

		echo json_encode($data);

		// echo var_dump($order) . " - " . var_dump($dir);
	}

	public function GetKelolaDOGagalNotRescheduleByFilter()
	{

		$tgl = explode(" - ", $this->input->post('tgl'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$do_no = $this->input->post('do_no');
		$customer = $this->input->post('customer');
		$alamat = $this->input->post('alamat');
		$so_eksternal = $this->input->post('so_eksternal');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$tipe_layanan = $this->input->post('tipe_layanan');
		$status = $this->input->post('status');
		$tipe = $this->input->post('tipe');
		$segmen1 = $this->input->post('segmen1');
		$segmen2 = $this->input->post('segmen2');
		$segmen3 = $this->input->post('segmen3');


		$data = $this->M_KelolaDOGagal->GetKelolaDOGagalNotRescheduleByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $so_eksternal);

		echo json_encode($data);

		// echo var_dump($order) . " - " . var_dump($dir);
	}

	public function GetKelolaDOGagalNotRescheduleBuatDraftByFilter()
	{

		$tgl = explode(" - ", $this->input->post('tgl'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$do_no = $this->input->post('do_no');
		$customer = $this->input->post('customer');
		$alamat = $this->input->post('alamat');
		$so_eksternal = $this->input->post('so_eksternal');
		$tipe_pembayaran = $this->input->post('tipe_pembayaran');
		$tipe_layanan = $this->input->post('tipe_layanan');
		$status = $this->input->post('status');
		$tipe = $this->input->post('tipe');
		$segmen1 = $this->input->post('segmen1');
		$segmen2 = $this->input->post('segmen2');
		$segmen3 = $this->input->post('segmen3');


		$data = $this->M_KelolaDOGagal->GetKelolaDOGagalNotRescheduleBuatDraftByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $so_eksternal);

		echo json_encode($data);

		// echo var_dump($order) . " - " . var_dump($dir);
	}

	public function GetFDJRByFilter()
	{
		$tgl = explode(" - ", $this->input->post('tgl'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$area = $this->input->post('area');

		$data = $this->M_KelolaDOGagal->GetFDJRByFilter($tgl1, $tgl2, $area);
		$fixData = [];
		if ($data != 0) {
			foreach ($data as $key => $value) {
				$area = $this->db->select("area.area_nama")->from("delivery_order_area")
					->join("area", "delivery_order_area.area_id = area.area_id", "left")
					->where("delivery_order_area.delivery_order_batch_id", $value->delivery_order_batch_id)->get()->result();

				$arrArea = [];

				foreach ($area as $key => $val) {
					$arrArea[] = $val->area_nama;
				}

				array_push($fixData, [
					'delivery_order_batch_id' => $value->delivery_order_batch_id,
					'delivery_order_batch_kode' => $value->delivery_order_batch_kode,
					'delivery_order_batch_tipe_layanan_id' => $value->delivery_order_batch_tipe_layanan_id,
					'delivery_order_batch_tipe_layanan_nama' => $value->delivery_order_batch_tipe_layanan_nama,
					'delivery_order_batch_tanggal' => $value->delivery_order_batch_tanggal,
					'delivery_order_batch_tanggal_kirim' => $value->delivery_order_batch_tanggal_kirim,
					'tipe_delivery_order_id' => $value->tipe_delivery_order_id,
					'tipe_delivery_order_alias' => $value->tipe_delivery_order_alias,
					// 'tipe_pengiriman_nama_tipe' => $value->tipe_pengiriman_nama_tipe,
					'delivery_order_batch_status' => $value->delivery_order_batch_status,
					'karyawan_nama' => $value->karyawan_nama,
					'delivery_order_batch_update_tgl' => $value->delivery_order_batch_update_tgl,
					'area' => $arrArea
				]);
			}
		}

		echo json_encode($fixData);
	}

	public function GetPerusahaanById()
	{
		$id = $this->input->post('id');
		$data = $this->M_KelolaDOGagal->GetPerusahaanById($id);

		echo json_encode($data);
	}
	public function GetSegment1()
	{
		$id = $this->input->post('id');
		$data = $this->M_KelolaDOGagal->getSegment();

		echo json_encode($data);
	}
	public function GetSegment2()
	{
		$id = $this->input->post('id');
		$data = $this->M_KelolaDOGagal->getSegment2($id);

		echo json_encode($data);
	}

	public function GetSegment3()
	{
		$id = $this->input->post('id');
		$data = $this->M_KelolaDOGagal->getSegment3($id);

		echo json_encode($data);
	}

	public function CheckDOKirimUlang()
	{
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$delivery_order_id = $this->input->post('delivery_order_id');

		$data = $this->M_KelolaDOGagal->CheckDOKirimUlang($delivery_order_batch_id, $delivery_order_id);

		echo json_encode($data);
	}

	public function getDataSearchEditDO()
	{
		$tgl = explode(" - ", $this->input->post('dateRange'));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$area = $this->input->post('area');
		$status = $this->input->post('status_do');

		$data = $this->M_KelolaDOGagal->getDataSearchEditDO($tgl1, $tgl2, $area, $status);

		echo json_encode($data);
	}


	public function updateDateRencanaKirim()
	{
		$arrData = $this->input->post('arrData');

		$this->db->trans_begin();

		if (count($arrData) > 0) {
			foreach ($arrData as $key => $value) {
				$this->db->update("delivery_order_draft", [
					'delivery_order_draft_tgl_rencana_kirim' => $value['aktualRencanaKirim'] . " 00:00:00.000"
				], ['delivery_order_draft_id' => $value['valueId']]);
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(false);
		} else {
			$this->db->trans_commit();
			echo json_encode(true);
		}
	}

	public function KonversiSKUFromDO()
	{
		$detail_sku_konversi = array();
		$data_sku_konversi = array();
		$data_tr_konversi_sku_from_do_by_depo = $this->M_KelolaDOGagal->get_tr_konversi_sku_from_do_by_depo();

		if (count($data_tr_konversi_sku_from_do_by_depo) > 0) {

			foreach ($data_tr_konversi_sku_from_do_by_depo as $key => $value) {

				$query = $this->db->query("Exec proses_konversi_sku_pack_unpack '" . $value['sku_id'] . "', '" . $value['sku_qty'] . "', 'Repack_do'");

				if ($query->num_rows() > 0) {
					foreach ($query->result_array() as $key_query => $value_query) {
						$detail_sku_konversi = array("sku_id" => $value_query['sku_id'], "sku_qty" => $value_query['hasil']);
						array_push($data_sku_konversi, $detail_sku_konversi);
					}
				}
			}
			$data_sku_stock_by_konversi_sku = $this->M_KelolaDOGagal->get_sku_stock_by_konversi_sku($data_sku_konversi);
		} else {
			$data_sku_stock_by_konversi_sku = 0;
		}

		echo json_encode($data_sku_stock_by_konversi_sku);
	}

	public function get_sku_stock_by_sku()
	{
		$sku_id = $this->input->get('sku_id');
		$data = $this->M_KelolaDOGagal->get_sku_stock_by_sku($sku_id);

		echo json_encode($data);
	}

	public function get_sku_stock_by_gudang()
	{
		$client_wms_id = $this->input->get('client_wms_id');
		$depo_detail_id = $this->input->get('depo_detail_id');
		$sku_id = $this->input->get('sku_id');

		$data = $this->M_KelolaDOGagal->get_sku_stock_by_gudang($client_wms_id, $depo_detail_id, $sku_id);

		echo json_encode($data);
	}

	public function get_client_wms_konversi_sku()
	{
		$data = $this->M_KelolaDOGagal->get_client_wms_konversi_sku();

		echo json_encode($data);
	}

	public function GetDepoDetail()
	{
		$data = $this->M_KelolaDOGagal->GetDepoDetail();

		echo json_encode($data);
	}

	public function get_tr_konversi_sku_from_do_by_depo()
	{
		$tr_konversi_sku_id = $this->M_Vrbl->Get_NewID();
		$data['tr_konversi_sku_id'] = $tr_konversi_sku_id[0]['NEW_ID'];
		$data['konversi'] = $this->M_KelolaDOGagal->get_tr_konversi_sku_from_do_by_depo();

		echo json_encode($data);
	}

	public function UpdateSKUQtyKonversi()
	{
		$data_tr_konversi_sku_from_do_by_depo = $this->M_KelolaDOGagal->get_tr_konversi_sku_from_do_by_depo();

		$tr_konversi_sku_id = $this->input->post('tr_konversi_sku_id');

		$this->db->trans_begin();

		foreach ($data_tr_konversi_sku_from_do_by_depo as $key => $value) {

			$query = $this->db->query("Exec proses_konversi_sku_pack_unpack '" . $value['sku_id'] . "', '" . $value['sku_qty'] . "', 'Repack_do'");

			if ($query->num_rows() > 0) {
				foreach ($query->result_array() as $key_query => $value_query) {
					$this->M_KelolaDOGagal->Update_qty_tr_konversi_sku_detail($tr_konversi_sku_id, $value_query['sku_id'], $value_query['hasil']);
				}
			}
		}

		$this->M_KelolaDOGagal->delete_tr_konversi_sku_from_do();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function insert_delivery_order_draft_detail_msg()
	{
		$delivery_order_draft_id = $this->input->post('delivery_order_draft_id');
		$sku_kode = $this->input->post('sku_kode');
		$msg = $this->input->post('msg');

		$this->db->trans_begin();

		$this->M_KelolaDOGagal->Insert_delivery_order_draft_detail_msg($delivery_order_draft_id, $sku_kode, $msg);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function delete_delivery_order_draft_detail_msg()
	{
		$this->db->trans_begin();

		$this->M_KelolaDOGagal->delete_delivery_order_draft_detail_msg();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function Get_delivery_order_draft_detail_msg()
	{
		$data = $this->M_KelolaDOGagal->Get_delivery_order_draft_detail_msg();
		echo json_encode($data);
	}
	public function BuatBaruDariKelolaDOGagal()
	{
		$area 									= $this->input->post('area');
		$tipe_layanan 							= $this->input->post('tipe_layanan');
		$tipe 									= $this->input->post('tipe');
		$tanggal_kirim 							= $this->input->post('tanggal_kirim');
		$tipe_ekspedisi 						= $this->input->post('tipe_ekspedisi');
		$kendaraan_id 							= $this->input->post('kendaraan_id');
		$karyawan_id 							= $this->input->post('karyawan_id');
		$delivery_order_batch_tipe_layanan_id 	= $this->input->post('delivery_order_batch_tipe_layanan_id');
		$delivery_order_batch_tipe_layanan_no 	= $this->input->post('delivery_order_batch_tipe_layanan_no');
		$delivery_order_batch_tipe_layanan_nama = $this->input->post('delivery_order_batch_tipe_layanan_nama');
		$detail 								= $this->input->post('arr_do');
		$delivery_order_batch_id 				= $this->M_Vrbl->Get_NewID();
		$delivery_order_batch_id 				= $delivery_order_batch_id[0]['NEW_ID'];
		$date_now 								= date('Y-m-d h:i:s');
		$param 									= 'KODE_FDJR';
		$vrbl 									= $this->M_Vrbl->Get_Kode($param);
		$prefix 								= $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id 								= $this->session->userdata('depo_id');
		$depoPrefix 							= $this->M_KelolaDOGagal->getDepoPrefix($depo_id);
		$unit 									= $depoPrefix->depo_kode_preffix;
		$delivery_order_batch_kode 				= $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$this->db->trans_begin();

		$getVolWeigth 							= $this->M_KelolaDOGagal->GetKapasitasKendaraan($kendaraan_id);

		$values 								= "" . implode(", ", $detail) . "";
		$getSumWeightAndVolume 					= $this->db->query("select sum(dod.sku_weight) as sum_weight,sum(dod.sku_volume) as sum_volume 
																	from delivery_order_detail dod 
																	left join delivery_order do on do.delivery_order_id = dod.delivery_order_id 
																	where do.delivery_order_id in ($values)")->row();

		$dob = $this->M_KelolaDOGagal->insert_delivery_order_batch_from_kelola_do_gagal($delivery_order_batch_id, $delivery_order_batch_kode, $tipe_layanan, $tipe, $tanggal_kirim, $tipe_ekspedisi, $delivery_order_batch_tipe_layanan_id, $delivery_order_batch_tipe_layanan_no, $delivery_order_batch_tipe_layanan_nama, $kendaraan_id, $karyawan_id, $getVolWeigth, $getSumWeightAndVolume);

		if ($dob != 0) {
			foreach ($area as $key => $value) {
				$getAreaId = $this->db->query("select area_id from area where area_nama = '$value'")->row();

				$this->db->set("delivery_order_area_id", "NEWID()", FALSE);
				$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
				$this->db->set("area_id", $getAreaId->area_id);

				$this->db->insert("delivery_order_area");
			}
		}
		// if ($errorNotSameLastUpdated) {
		// 	$this->db->trans_rollback();
		// 	echo json_encode(2);
		// } else 
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array('type' => 0, 'message' => "Gagal", 'do_id' => FALSE));
		} else {
			$this->db->trans_commit();
			echo json_encode(array('type' => 1, 'message' => "Berhasil", 'do_id' => $delivery_order_batch_id));
		}
	}
	public function DeleteBuatBaruDariKelolaDOGagal()
	{
		$this->db->trans_begin();
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$this->db->where('delivery_order_batch_id', $delivery_order_batch_id);
		$this->db->delete('delivery_order_batch');
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array('type' => 0, 'message' => "Gagal"));
		} else {
			$this->db->trans_commit();
			echo json_encode(array('type' => 1, 'message' => "Berhasil"));
		}
	}

	public function CekLastUpdateFDJR()
	{
		$delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
		$getLastUpdatedDb = $this->db->query("select delivery_order_batch_update_tgl from delivery_order_batch where delivery_order_batch_id ='$delivery_order_batch_id'")->row()->delivery_order_batch_update_tgl;

		$lastUpdated = $this->input->post('delivery_order_batch_tgl_update') == "null" ? NULL : $this->input->post('delivery_order_batch_tgl_update');
		$errorNotSameLastUpdated = false;
		if ($lastUpdated !== $getLastUpdatedDb) $errorNotSameLastUpdated = true;

		if ($errorNotSameLastUpdated) {
			echo json_encode(2);
		} else {
			echo json_encode(1);
		}
	}

	public function updateIsRescheduleDeliveryOrder()
	{
		$status = $this->input->post('status');
		$arr_do_id = $this->input->post('arr_do_id');

		$queryupdate = $this->M_KelolaDOGagal->updateIsRescheduleDeliveryOrder($status, $arr_do_id);

		$arr = [];
		foreach ($arr_do_id as $key => $value) {
			$cekTipeDO = $this->M_KelolaDOGagal->cekTipeDO($value);

			if ($cekTipeDO->tipe_delivery_order_id != 'C5BE83E2-01E8-4E24-B766-26BB4158F2CD') {
				$arr[] = $value;
			}
		}

		if (COUNT($arr) > 0) {
			$queryupdate = $this->M_KelolaDOGagal->insertMutasiStok($status, $arr);
		}

		echo $queryupdate;
	}

	public function updateIsRescheduleDeliveryOrder2()
	{
		$status = $this->input->post('status');
		$arr_do_id = $this->input->post('arr_do_id');

		$queryupdate = $this->M_KelolaDOGagal->updateIsRescheduleDeliveryOrder($status, $arr_do_id);

		echo $queryupdate;
	}

	public function GenerateDONotReschedule()
	{

		$arr_do_not_reschedule = $this->input->post('arr_do_not_reschedule');
		$arr_do_retur = array();

		$this->db->trans_begin();

		foreach ($arr_do_not_reschedule as $value) {

			if ($value['tipe_delivery_order_id'] == "C5BE83E2-01E8-4E24-B766-26BB4158F2CD") {
				array_push($arr_do_retur, $value['delivery_order_kode']);
			} else {
				$delivery_order_draft_id = $this->M_Vrbl->Get_NewID();
				$delivery_order_draft_id = $delivery_order_draft_id[0]['NEW_ID'];

				//generate kode
				$date_now 		= date('Y-m-d h:i:s');
				$param 			=  'KODE_DOD';
				$vrbl 			= $this->M_Vrbl->Get_Kode($param);
				$prefix 		= $vrbl->vrbl_kode;
				// get prefik depo
				$depo_id 		= $this->session->userdata('depo_id');
				$depoPrefix 	= $this->M_DeliveryOrderDraft->getDepoPrefix($depo_id);
				$unit 			= $depoPrefix->depo_kode_preffix;
				$delivery_order_draft_kode 	= $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

				$this->db->set("is_reschedule", 2);
				$this->db->where("delivery_order_id", $value['delivery_order_id']);
				$this->db->update("delivery_order");

				$sales_order_id = $this->db->query("select sales_order_id from delivery_order where delivery_order_id = '" . $value['delivery_order_id'] . "'");

				if ($sales_order_id->num_rows() == 0) {
					$sales_order_id = "";
				} else {
					$sales_order_id = $sales_order_id->row(0)->sales_order_id;
				}

				$tipe_delivery_order_id = $this->db->query("select tipe_delivery_order_id from delivery_order_draft where CONVERT(NVARCHAR(36),sales_order_id) = '$sales_order_id' and delivery_order_draft_reff_id is null");

				if ($tipe_delivery_order_id->num_rows() == 0) {
					$tipe_delivery_order_id = "";
				} else {
					$tipe_delivery_order_id = $tipe_delivery_order_id->row(0)->tipe_delivery_order_id;
				}

				$this->M_KelolaDOGagal->Generate_do_not_reschedule_header($delivery_order_draft_id, $delivery_order_draft_kode, $value['delivery_order_id'], $value['tanggal_rencana_kirim'], $tipe_delivery_order_id);
				$this->M_KelolaDOGagal->Generate_do_not_reschedule_detail($delivery_order_draft_id, $value['delivery_order_id']);
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array('type' => 0, 'message' => "Gagal", 'data' => ""));
		} else {
			$this->db->trans_commit();
			if (count($arr_do_retur) > 0) {
				echo json_encode(array('type' => 2, 'message' => "Retur", 'data' => $arr_do_retur));
			} else {
				echo json_encode(array('type' => 1, 'message' => "Berhasil", 'data' => ""));
			}
		}

		// echo var_dump($order) . " - " . var_dump($dir);
	}
}
