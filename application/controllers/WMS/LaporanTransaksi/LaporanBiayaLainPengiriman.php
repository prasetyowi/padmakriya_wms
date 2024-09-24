<?php

require_once APPPATH . 'core/ParentController.php';

class LaporanBiayaLainPengiriman extends ParentController
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

		$this->MenuKode = "101002015";
		$this->load->model('WMS/LaporanTransaksi/M_LaporanBiayaLainPengiriman', 'M_LaporanBiayaLainPengiriman');
		$this->load->model('M_Menu');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_DataTable');
	}

	public function LaporanBiayaLainPengirimanMenu()
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

		$data['Driver'] = $this->M_LaporanBiayaLainPengiriman->Get_Driver();

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('reports/LaporanTransaksi/LaporanBiayaLainPengiriman/main', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('reports/LaporanTransaksi/LaporanBiayaLainPengiriman/script', $data);
		// $this->load->view('reports/LaporanTransaksi/LaporanInformasiPallet/script', $data);
	}

	public function getData()
	{
		$depo = $this->session->userdata('depo_id');
		$tgl = explode(" - ", $this->input->post('filter_tanggal_kirim'));
		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$filter_driver = $this->input->post('filter_driver');

		if ($filter_driver != '') {
			$kondisiDriver = "AND a.karyawan_id = '$filter_driver'";
		} else {
			$kondisiDriver = "";
		}

		$sql = "SELECT ROW_NUMBER ( ) OVER ( ORDER BY delivery_order_batch_kode ASC ) AS idx, delivery_order_batch_kode, FORMAT(delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') AS delivery_order_batch_tanggal_kirim, b.karyawan_id, b.karyawan_nama, c.delivery_order_id, c.delivery_order_kirim_nama, c.delivery_order_kirim_alamat, count(d.delivery_order_detail3_id) AS total_biaya,
		SUM(d.delivery_order_detail3_nilai) AS total_nominal_tunai 
		FROM delivery_order_batch a 
		LEFT JOIN karyawan b ON a.karyawan_id = b.karyawan_id
		LEFT JOIN delivery_order c ON a.delivery_order_batch_id = c.delivery_order_batch_id
		LEFT JOIN delivery_order_detail3 d ON c.delivery_order_id = d.delivery_order_id
		WHERE FORMAT(delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') >= '$tgl1' AND FORMAT(delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') <= '$tgl2' 
		$kondisiDriver
		GROUP BY delivery_order_batch_kode, 
		delivery_order_batch_tanggal_kirim, 
		b.karyawan_nama, 
		b.karyawan_id,
		c.delivery_order_id,
		c.delivery_order_kirim_nama, 
		c.delivery_order_kirim_alamat
		HAVING 
			count(d.delivery_order_detail3_id) > 0";
		$response = $this->M_DataTable->dtTableGetList($sql);

		$output = array(
			"draw" => $response['draw'],
			"recordsTotal" => $response['recordsTotal'],
			"recordsFiltered" => $response['recordsFiltered'],
			"data" => $response['data'],
		);

		echo json_encode($output);
	}

	public function getDetailBiaya()
	{
		$do_batch_kode = $this->input->post('do_batch_kode');
		$do_id = $this->input->post('do_id');
		$tgl_kirim = $this->input->post('tgl_kirim');

		$data = $this->M_LaporanBiayaLainPengiriman->getDetailBiaya($do_batch_kode, $do_id, $tgl_kirim);

		echo json_encode($data);
	}
}
