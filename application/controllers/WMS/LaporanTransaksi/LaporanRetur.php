<?php

require_once APPPATH . 'core/ParentController.php';

// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanRetur extends ParentController
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

		$this->MenuKode = "101002009";
		$this->load->model('WMS/LaporanTransaksi/M_LaporanRetur', 'M_LaporanRetur');
		$this->load->model('M_Menu');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
	}

	public function LaporanReturMenu()
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
		$data['Gudang'] = $this->M_LaporanRetur->Get_Gudang();
		$data['Principle'] = $this->M_LaporanRetur->Get_Principle();
		$data['ClientWMS'] = $this->M_LaporanRetur->Get_ClientWMS();
		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('reports/LaporanTransaksi/LaporanRetur/main', $data);
		$this->load->view('layouts/sidebar_footer', $query);
		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('reports/LaporanTransaksi/LaporanRetur/script', $data);
		// $this->load->view('reports/LaporanTransaksi/LaporanInformasiPallet/script', $data);
	}

	public function getDetail()
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
		$data = $this->M_LaporanRetur->GetDetail($tgl1, $tgl2, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama);

		foreach ($data as $key => $data) {
			$tmpdata = $data;

			unset($tmpdata['penerimaan_pembelian_id']);
			// unset($tmpdata['karyawan_nama']);
			// unset($tmpdata['penerimaan_pembelian_kode']);
			// unset($tmpdata['sku_nama']);
			$tmpgroup[$data['penerimaan_pembelian_id']]['penerimaan_pembelian_id'] = $data['penerimaan_pembelian_id'];
			$tmpgroup[$data['penerimaan_pembelian_id']]['karyawan_nama'] = $data['karyawan_nama'];
			$tmpgroup[$data['penerimaan_pembelian_id']]['penerimaan_pembelian_kode'] = $data['penerimaan_pembelian_kode'];
			$tmpgroup[$data['penerimaan_pembelian_id']]['sku_nama'] = $data['sku_nama'];

			$tmpgroup[$data['penerimaan_pembelian_id']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}
		// echo json_encode($tmpgroup);
		// $jsonData = json_decode($tmpgroup, true);/
		// foreach ($jsonData as $key => $value) {
		// 	$result[$key] = array(
		// 		'penerimaan_pembelian_id' => $value['penerimaan_pembelian_id'],
		// 		'data' => array()
		// 	);
		// 	$uniqueSku = array();
		// 	foreach ($value['data'] as $item) {
		// 		$uniqueSku[$item['sku_nama']] = true;
		// 	}
		// 	foreach ($uniqueSku as $sku => $_) {
		// 		$result[$key]['data'][] = array('sku_nama' => $sku);
		// 	}
		// }

		$result = [];
		foreach ($tmpgroup as $key => $value) {
			// $result[$key] = $value;
			$result[] = array(
				'penerimaan_pembelian_id' => $value['penerimaan_pembelian_id'],
				'data' => array()
			);
			$uniqueKaryawan = array();
			foreach ($value['data'] as $item) {
				$uniqueKaryawan[$item['karyawan_nama']] = true;
			}
			$index1 = count($result) - 1;
			$result[$index1]['karyawan_nama'] = implode(',', array_keys($uniqueKaryawan));
			// $result[$key]['data'] = array();
			// foreach ($value['data'] as $item) {
			// 	$result[$key]['data'][$item['sku_nama']][] = $item;
			// }
			$uniqueSku = array();
			foreach ($value['data'] as $item) {
				$uniqueSku[$item['sku_nama']] = true;
			}
			// foreach ($uniqueSku as $sku => $_) {
			// 	$result[count]['data'][] = array('sku_nama' => $sku);
			// }
			$index2 = count($result) - 1;
			foreach ($uniqueSku as $sku => $_) {
				$result[$index2]['data'][] = array('sku_nama' => $sku);
			}
		}

		echo json_encode($result);
	}
	public function getPenyakit()
	{
		$total_data = $this->M_LaporanRetur->count_all_data();
		$draw = intval($this->input->post('draw'));
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$search_value = $this->input->post('search')['value'];
		$order_column = intval($this->input->post('order')[0]['column']);
		$order_dir = $this->input->post('order')[0]['dir'];

		$records_filtered = $total_data;
		$data = $this->M_LaporanRetur->get_filtered_data($start, $length, $search_value, $order_column, $order_dir);
		$tmpgroup = [];
		$arr = [];
		// echo json_encode($data);

		foreach ($data as $key => $data) {
			$tmpdata = $data;

			unset($tmpdata['penerimaan_pembelian_id']);
			// unset($tmpdata['karyawan_nama']);
			// unset($tmpdata['penerimaan_pembelian_kode']);
			// unset($tmpdata['sku_nama']);
			$tmpgroup[$data['penerimaan_pembelian_id']]['penerimaan_pembelian_id'] = $data['penerimaan_pembelian_id'];
			$tmpgroup[$data['penerimaan_pembelian_id']]['karyawan_nama'] = $data['karyawan_nama'];
			$tmpgroup[$data['penerimaan_pembelian_id']]['penerimaan_pembelian_kode'] = $data['penerimaan_pembelian_kode'];
			$tmpgroup[$data['penerimaan_pembelian_id']]['sku_nama'] = $data['sku_nama'];

			$tmpgroup[$data['penerimaan_pembelian_id']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}
		// echo json_encode($tmpgroup);
		// $jsonData = json_decode($tmpgroup, true);/

		$result = array();
		foreach ($tmpgroup as $key => $value) {
			$result[$key] = $value;
			$uniqueKaryawan = array();
			$uniqueKaryawan = array();
			foreach ($value['data'] as $item) {
				$uniqueKaryawan[$item['karyawan_nama']] = true;
			}
			$result[$key]['karyawan_nama'] = implode(',', array_keys($uniqueKaryawan));
			$result[$key]['data'] = array();
			foreach ($value['data'] as $item) {
				$result[$key]['data'][$item['sku_nama']][] = $item;
			}
		}

		$output = json_encode($result);
		// echo $output;


		echo json_encode($output);
	}
}
