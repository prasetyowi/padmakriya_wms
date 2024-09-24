<?php

require_once APPPATH . 'core/ParentController.php';

// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanLeadTimeDO extends ParentController
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

		$this->MenuKode = "101002014";
		$this->load->model('WMS/LaporanTransaksi/M_LaporanLeadTimeDO', 'M_LaporanLeadTimeDO');
		$this->load->model('M_Menu');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
	}

	public function LaporanLeadTimeDOMenu()
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

		// $data['Gudang'] = $this->M_LaporanLeadTimeDO->Get_Gudang();
		$data['Principle'] = $this->M_LaporanLeadTimeDO->Get_Principle();
		// $data['ClientWMS'] = $this->M_LaporanLeadTimeDO->Get_ClientWMS();
		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('reports/LaporanTransaksi/LaporanLeadTimeDO/main', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('reports/LaporanTransaksi/LaporanLeadTimeDO/script', $data);
		// $this->load->view('reports/LaporanTransaksi/LaporanInformasiPallet/script', $data);
	}

	public function Get_laporan_lead_time_do()
	{
		$tgl = explode(" - ", $this->input->post('filter_stock_tanggal'));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$depo_id = $this->session->userdata('depo_id');
		$principle_id = $this->input->post('principle_id');

		$data = $this->M_LaporanLeadTimeDO->Get_laporan_lead_time_do($tgl1, $tgl2, $depo_id, $principle_id);

		echo json_encode($data);
	}

	public function Get_laporan_lead_time_do_detail_so_approved()
	{
		$tgl = explode(" - ", $this->input->post('filter_stock_tanggal'));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$depo_id = $this->session->userdata('depo_id');
		$principle_id = $this->input->post('principle_id');
		$tipe = $this->input->post('tipe');
		$range = $this->input->post('range');

		$draw = intval($this->input->post('draw'));
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$search_value = $this->input->post('search')['value'];
		$order_column = intval($this->input->post('order')[0]['column']);
		$order_dir = $this->input->post('order')[0]['dir'];

		$total_data = $this->M_LaporanLeadTimeDO->Get_total_laporan_lead_time_do_detail_so_approved($tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range);
		$data = $this->M_LaporanLeadTimeDO->Get_laporan_lead_time_do_detail_so_approved($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range);

		$records_filtered = $total_data;

		$response = array(
			'draw' => $draw,
			'recordsTotal' => $total_data,
			'recordsFiltered' => $records_filtered,
			'data' => $data
		);
		echo json_encode($response);
	}

	public function Get_laporan_lead_time_do_detail_so_approved_belum_punya_do_draft()
	{
		$tgl = explode(" - ", $this->input->post('filter_stock_tanggal'));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$depo_id = $this->session->userdata('depo_id');
		$principle_id = $this->input->post('principle_id');
		$tipe = $this->input->post('tipe');
		$range = $this->input->post('range');

		$draw = intval($this->input->post('draw'));
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$search_value = $this->input->post('search')['value'];
		$order_column = intval($this->input->post('order')[0]['column']);
		$order_dir = $this->input->post('order')[0]['dir'];

		$total_data = $this->M_LaporanLeadTimeDO->Get_total_laporan_lead_time_do_detail_so_approved_belum_punya_do_draft($tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range);
		$data = $this->M_LaporanLeadTimeDO->Get_laporan_lead_time_do_detail_so_approved_belum_punya_do_draft($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range);

		$records_filtered = $total_data;

		$response = array(
			'draw' => $draw,
			'recordsTotal' => $total_data,
			'recordsFiltered' => $records_filtered,
			'data' => $data
		);
		echo json_encode($response);
	}

	public function Get_laporan_lead_time_do_detail_so_approved_jadi_do_draft()
	{
		$tgl = explode(" - ", $this->input->post('filter_stock_tanggal'));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$depo_id = $this->session->userdata('depo_id');
		$principle_id = $this->input->post('principle_id');
		$tipe = $this->input->post('tipe');
		$range = $this->input->post('range');

		$draw = intval($this->input->post('draw'));
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$search_value = $this->input->post('search')['value'];
		$order_column = intval($this->input->post('order')[0]['column']);
		$order_dir = $this->input->post('order')[0]['dir'];

		$total_data = $this->M_LaporanLeadTimeDO->Get_total_laporan_lead_time_do_detail_so_approved_jadi_do_draft($tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range);
		$data = $this->M_LaporanLeadTimeDO->Get_laporan_lead_time_do_detail_so_approved_jadi_do_draft($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range);

		$records_filtered = $total_data;

		$response = array(
			'draw' => $draw,
			'recordsTotal' => $total_data,
			'recordsFiltered' => $records_filtered,
			'data' => $data
		);
		echo json_encode($response);
	}

	public function Get_laporan_lead_time_do_detail_do_draft_approved()
	{
		$tgl = explode(" - ", $this->input->post('filter_stock_tanggal'));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$depo_id = $this->session->userdata('depo_id');
		$principle_id = $this->input->post('principle_id');
		$tipe = $this->input->post('tipe');
		$range = $this->input->post('range');

		$draw = intval($this->input->post('draw'));
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$search_value = $this->input->post('search')['value'];
		$order_column = intval($this->input->post('order')[0]['column']);
		$order_dir = $this->input->post('order')[0]['dir'];

		$total_data = $this->M_LaporanLeadTimeDO->Get_total_laporan_lead_time_do_detail_do_draft_approved($tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range);
		$data = $this->M_LaporanLeadTimeDO->Get_laporan_lead_time_do_detail_do_draft_approved($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range);

		$records_filtered = $total_data;

		$response = array(
			'draw' => $draw,
			'recordsTotal' => $total_data,
			'recordsFiltered' => $records_filtered,
			'data' => $data
		);
		echo json_encode($response);
	}

	public function Get_laporan_lead_time_do_detail_do_dilaksanakan()
	{
		$tgl = explode(" - ", $this->input->post('filter_stock_tanggal'));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$depo_id = $this->session->userdata('depo_id');
		$principle_id = $this->input->post('principle_id');
		$tipe = $this->input->post('tipe');
		$range = $this->input->post('range');

		$draw = intval($this->input->post('draw'));
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$search_value = $this->input->post('search')['value'];
		$order_column = intval($this->input->post('order')[0]['column']);
		$order_dir = $this->input->post('order')[0]['dir'];

		$total_data = $this->M_LaporanLeadTimeDO->Get_total_laporan_lead_time_do_detail_do_dilaksanakan($tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range);
		$data = $this->M_LaporanLeadTimeDO->Get_laporan_lead_time_do_detail_do_dilaksanakan($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range);

		$records_filtered = $total_data;

		$response = array(
			'draw' => $draw,
			'recordsTotal' => $total_data,
			'recordsFiltered' => $records_filtered,
			'data' => $data
		);
		echo json_encode($response);
	}

	public function getDetail()
	{
		$tgl = explode(" - ", $this->input->post('filter_stock_tanggal'));

		// $tgl1 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[0])));
		// $tgl2 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[1])));

		// $tgl_1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		// $tgl_2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$tgl_1 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl_2 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[1])));
		$client_wms = str_replace("null", "", $this->input->post('client_wms'));
		$depo_id = $this->session->userdata('depo_id');
		$depo_detail_id = str_replace("null", "", $this->input->post('depo_detail_id'));
		$principle_id = str_replace("null", "", $this->input->post('principle_id'));
		$sku_kode = $this->input->post('sku_kode');
		$sku_nama = $this->input->post('sku_nama');

		$draw = intval($this->input->post('draw'));
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$search_value = $this->input->post('search')['value'];
		$order_column = intval($this->input->post('order')[0]['column']);
		$order_dir = $this->input->post('order')[0]['dir'];


		$total_data = $this->M_LaporanLeadTimeDO->count_all_data($tgl1, $tgl2, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $tgl_1, $tgl_2);

		$records_filtered = $total_data;
		// $data = $this->M_LaporanLeadTimeDO->GetDetail($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $tgl_1, $tgl_2);
		$data = $this->M_LaporanLeadTimeDO->GetDetailNewProcedure($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $tgl_1, $tgl_2);
		$response = array(
			'draw' => $draw,
			'recordsTotal' => $total_data,
			'recordsFiltered' => $records_filtered,
			'data' => $data
		);
		echo json_encode($response);
	}

	public function GetDetail2()
	{

		$tgl = explode(" - ", $this->input->post('filter_stock_tanggal'));

		$tgl1 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[1])));


		$client_wms = $this->input->post('client_wms');
		$depo_id = $this->session->userdata('depo_id');
		$depo_detail_id = $this->input->post('depo_detail_id');
		$principle_id = $this->input->post('principle_id');
		$sku_kode = $this->input->post('sku_kode');
		$sku_nama = $this->input->post('sku_nama');

		$draw = $this->input->post('draw');
		$offset = $this->input->post('start');
		$num_rows = $this->input->post('length');
		$order_index = $_POST['order'][0]['column'];
		$order_by = $_POST['columns'][$order_index]['data'];
		$order_direction = $_POST['order'][0]['dir'];
		$keyword = $_POST['search']['value'];

		$base_sql = "
		FROM sku_stock_card ssc
		LEFT JOIN depo ON depo.depo_id = ssc.depo_id
		LEFT JOIN depo_detail dd ON dd.depo_detail_id = ssc.depo_detail_id
		LEFT JOIN sku ON sku.sku_id = ssc.sku_id
		LEFT JOIN principle ON principle.principle_id = sku.principle_id
		LEFT JOIN sku_stock ss ON ss.sku_stock_id = ssc.sku_stock_id AND ss.sku_id = ssc.sku_id
		LEFT JOIN (
			SELECT
				sku_id,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Stock Opname' THEN sku_stock_card_qty ELSE 0 END) AS opname,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Permintaan Pengeluaran Barang' THEN sku_stock_card_qty ELSE 0 END) AS penjualan,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Bukti Terima Barang dari Outlet' and sku_stock_card_jenis ='K' THEN sku_stock_card_qty ELSE 0 END) AS penerimaan_retur_outlet,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Mutasi Pallet Antar Gudang' and sku_stock_card_jenis ='K' THEN sku_stock_card_qty ELSE 0 END) AS mutasi_in_antar_gudang,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Mutasi Pallet Antar Gudang' and sku_stock_card_jenis ='D' THEN sku_stock_card_qty ELSE 0 END) AS mutasi_out_antar_gudang,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Koreksi Barang Masuk' and sku_stock_card_keterangan ='Koreksi Stok Pallet' and sku_stock_card_jenis ='D' THEN sku_stock_card_qty ELSE 0 END) AS koreksi_adjustmen_in,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Koreksi Barang Keluar' and sku_stock_card_keterangan ='Koreksi Stok Pallet' and sku_stock_card_jenis ='K'  THEN sku_stock_card_qty ELSE 0 END) AS koreksi_adjustmen_out,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Konfirmasi Penerimaan Barang Supplier' THEN sku_stock_card_qty ELSE 0 END) AS penerimaan_supplier,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Konversi in' and sku_stock_card_jenis ='D' THEN sku_stock_card_qty ELSE 0 END) AS pembongkaran_D,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Konversi out' and sku_stock_card_jenis ='K' THEN sku_stock_card_qty ELSE 0 END) AS pembongkaran_K
			FROM sku_stock_card
			GROUP BY sku_id
		) AS skuu ON skuu.sku_id = ssc.sku_id
		LEFT JOIN (
			SELECT
				sku_stock_card_id,
				SUM(sku_stock_card_qty) AS counta2
			FROM sku_stock_card
			GROUP BY sku_stock_card_id
		) AS skuu2 ON skuu2.sku_stock_card_id = ssc.sku_stock_card_id
		WHERE FORMAT(ssc.sku_stock_card_tanggal, 'MM/dd/yyyy') BETWEEN '$tgl1' AND '$tgl2' and
				ssc.depo_id = '" . $this->session->userdata('depo_id') . "'
				and (
					depo.depo_nama like '%{$keyword}%'
					or  dd.depo_detail_nama like '%{$keyword}%'
					or  principle.principle_nama like '%{$keyword}%'
					or sku.sku_nama_produk like '%{$keyword}%'
					or sku.sku_kemasan like '%{$keyword}%'
					or sku_stock_expired_date like '%{$keyword}%'
					or sku_stock_card_tanggal like '%{$keyword}%'
					
					or skuu.opname like '%{$keyword}%'
					or skuu.penerimaan_supplier like '%{$keyword}%'
					or skuu.penerimaan_retur_outlet like '%{$keyword}%'
					or skuu.penjualan like '%{$keyword}%'
					or skuu.koreksi_adjustmen_in like '%{$keyword}%'
					or skuu.koreksi_adjustmen_out like '%{$keyword}%'
					or skuu.mutasi_in_antar_gudang like '%{$keyword}%'
					or skuu.mutasi_out_antar_gudang like '%{$keyword}%'
					or skuu.mutasi_out_antar_gudang like '%{$keyword}%'
				)
		";

		$data_sql = "SELECT
					ssc.sku_id,
					ssc.sku_stock_card_id,
					depo.depo_nama,
					dd.depo_detail_nama,
					principle.principle_nama,
					sku.sku_nama_produk,
					sku.sku_kemasan,
					--  ssc.sku_stock_card_qty,
					FORMAT(ss.sku_stock_expired_date,'dd-MM-yyyy') as sku_stock_expired_date,
					FORMAT(ssc.sku_stock_card_tanggal,'dd-MM-yyyy') as sku_stock_card_tanggal,
					CASE 
						WHEN ISNULL(ssc.sku_stock_card_tanggal, '$tgl1') < '$tgl1' then sum(ssc.sku_stock_card_qty)
					end as stock_awal,
					CASE 
						WHEN ISNULL(ssc.sku_stock_card_tanggal, '$tgl2') < '$tgl2' then sum(ssc.sku_stock_card_qty)
					end as stock_akhir,
					COALESCE(skuu.opname, 0) AS sum_opname,
					COALESCE(skuu.penerimaan_supplier, 0) AS penerimaan_supplier,
					COALESCE(skuu.penerimaan_retur_outlet, 0) AS penerimaan_retur_outlet,
					COALESCE(skuu.penjualan, 0) AS penjualan,
					COALESCE(skuu.koreksi_adjustmen_in, 0) AS koreksi_adjustmen_in,
					COALESCE(skuu.koreksi_adjustmen_out, 0) AS koreksi_adjustmen_out,
					COALESCE(skuu.mutasi_in_antar_gudang, 0) AS mutasi_in_antar_gudang,
					COALESCE(skuu.mutasi_out_antar_gudang, 0) AS mutasi_out_antar_gudang,
					FORMAT(ssc.sku_stock_card_tanggal, 'dd-MM-yyyy'),
					SUM(skuu2.counta2) AS sum_counta2
				, row_number() over (
					order by {$order_by} {$order_direction}
				  ) as nomor
			{$base_sql}
			GROUP BY
				ssc.sku_id,
				ssc.sku_stock_card_id,
				depo.depo_nama,
				dd.depo_detail_nama,
				principle.principle_nama,
				sku.sku_nama_produk,
				sku.sku_kemasan,
				ss.sku_stock_expired_date,
				ssc.sku_stock_card_tanggal,
				skuu.opname,
				skuu.penerimaan_supplier,
			skuu.penerimaan_retur_outlet, 
			skuu.penjualan, 
				skuu.koreksi_adjustmen_in, 
				skuu.koreksi_adjustmen_out,
				skuu.mutasi_in_antar_gudang,
			skuu.mutasi_out_antar_gudang,
				FORMAT(ssc.sku_stock_card_tanggal, 'dd-MM-yyyy')
			order by
				{$order_by} {$order_direction}
			OFFSET {$offset} ROWS
			FETCH FIRST {$num_rows} ROWS ONLY
		";

		$src = $this->db->query($data_sql);

		$count_sql = "
			select count(*) AS total
			{$base_sql}
		";
		$total_records = $this->db->query($count_sql)->row()->total;

		$response = array(
			'draw' => intval($draw),
			'iTotalRecords' => $src->num_rows(),
			'iTotalDisplayRecords' => $total_records,
			'aaData' => $src->result(),
		);


		echo json_encode($response);
	}

	public function GetLaporanLeadTimeDODetailData()
	{
		$draw = 50;

		$tgl = explode(" - ", $this->input->post('filter_stock_tanggal'));

		$tgl1 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('m/d/Y', strtotime(str_replace("/", "-", $tgl[1])));
		// $tipe_report = "detail";
		$tipe_report = $this->input->post('tipe_report');
		$client_wms = str_replace("null", "", $this->input->post('client_wms'));
		$depo_id = $this->session->userdata('depo_id');
		$depo_detail_id = str_replace("null", "", $this->input->post('depo_detail_id'));
		$principle_id = str_replace("null", "", $this->input->post('principle_id'));
		$sku_id = $this->input->post('sku_id');
		$sku_kode = $this->input->post('sku_kode');
		$sku_nama = $this->input->post('sku_nama');
		$tipe_transaksi = $this->input->post('tipe_transaksi');
		$sku_stock_expired_date = $this->input->post('sku_stock_expired_date');
		$stockAwal = $this->input->post('stockAwal');

		// $data_laporan_stock = $this->M_LaporanStock->exec_report_stock_movement($tipe_report, $tgl1, $tgl2, $tipe_transaksi, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama);

		$newDataStockAwal = [
			"sku_stock_card_dokumen_id" => '',
			"sku_stock_card_dokumen_no" => '',
			"sku_stock_card_tanggal" => '',
			"depo_id" => $depo_id,
			"depo_nama" => '',
			"depo_detail_id" => $depo_detail_id,
			"depo_detail_nama" => '',
			"principle_id" => $principle_id,
			"principle_kode" => '',
			"tipe_mutasi_id" => '',
			"tipe_mutasi_nama" => '',
			"sku_id" => $sku_id,
			"sku_kode" => $sku_kode,
			"sku_nama_produk" => $sku_nama,
			"sku_stock_card_keterangan" => 'Stock Awal',
			"ekspedisi_nama" => '',
			"driver_nama" => '',
			"stock_in" => $stockAwal,
			"stock_out" => '',
			"stock_total" => $stockAwal,
		];

		$data_laporan_stock = $this->M_LaporanLeadTimeDO->Get_laporan_transaksi_stock_movement_detail($tipe_report, $tgl1, $tgl2, $tipe_transaksi, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_id, $sku_kode, $sku_nama, $sku_stock_expired_date, $newDataStockAwal);


		// $newDataStockAkhir = [
		// 	"sku_stock_card_dokumen_id" => '',
		// 	"sku_stock_card_dokumen_no" => '',
		// 	"sku_stock_card_tanggal" => '',
		// 	"depo_id" => '',
		// 	"depo_nama" => '',
		// 	"depo_detail_id" => '',
		// 	"depo_detail_nama" => '',
		// 	"principle_id" => '',
		// 	"principle_kode" => '',
		// 	"tipe_mutasi_id" => '',
		// 	"tipe_mutasi_nama" => '',
		// 	"sku_id" => '',
		// 	"sku_kode" => '',
		// 	"sku_nama_produk" => '',
		// 	"sku_stock_card_keterangan" => 'Stock Akhir',
		// 	"ekspedisi_nama" => '',
		// 	"driver_nama" => '',
		// 	"stock_in" => '',
		// 	"stock_out" => '',
		// 	"stock_total" => '',
		// ];

		// array_unshift($data_laporan_stock, $newDataStockAwal);

		// array_push($data_laporan_stock, $newDataStockAkhir);

		$data = array(
			"draw" => intval($draw),
			// "iTotalRecords" => 100,
			// "iTotalDisplayRecords" => 10,
			"aaData" => $data_laporan_stock
		);

		echo json_encode($data);
	}
}
