<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class ValidasiPengeluaranBarang extends ParentController
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
			redirect(base_url('MainPage/Login'));
		endif;

		$this->depo_id = $this->session->userdata('depo_id');
		$this->MenuKode = "135012000";
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_ValidasiPengeluaranBarang', 'M_ValidasiPengeluaranBarang');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation']);
		$this->load->library(['form_validation', 'pdfgenerator']);
	}

	public function ValidasiPengeluaranBarangMenu()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['dataValidation'] = $this->db->select("picking_validation.picking_validation_id as id, picking_validation.picking_validation_kode as kode, karyawan.karyawan_nama as driver")
			->from("picking_validation")
			->join("delivery_order_batch", "picking_validation.picking_order_id = delivery_order_batch.picking_order_id", "left")
			->join("karyawan", "delivery_order_batch.karyawan_id = karyawan.karyawan_id", "left")
			->where('picking_validation.depo_id', $this->session->userdata('depo_id'))
			->order_by('picking_validation.picking_validation_kode', 'ASC')->get()->result();


		$data['rangeYear'] = range(date('Y') - 10, date('Y') + 10);
		$data['rangeMonth'] = array(
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember',
		);

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
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/ValidasiPengeluaranBarang/Page/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/ValidasiPengeluaranBarang/Component/Script/S_ValidasiPengeluaranBarang', $data);
	}

	public function getFlterDataPickingValidation()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_ValidasiPengeluaranBarang->getFlterDataPickingValidation($dataPost));
	}

	public function deleteDataPickingValidation()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_ValidasiPengeluaranBarang->deleteDataPickingValidation($dataPost));
	}

	public function form()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['dataFDJR'] = $this->db->select("delivery_order_batch_id as id, delivery_order_batch_kode as kode")
			->from("delivery_order_batch")
			->where('depo_id', $this->session->userdata('depo_id'))
			->where_in('delivery_order_batch_status', ['in transit validation completed', 'completed', 'in transit validation'])
			->order_by('delivery_order_batch_kode', 'ASC')->get()->result();

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/ValidasiPengeluaranBarang/Page/form', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/ValidasiPengeluaranBarang/Component/Script/S_ValidasiPengeluaranBarang', $data);
	}

	public function form_by_picking_order()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['dataFDJR'] = $this->db->select("delivery_order_batch_id as id, delivery_order_batch_kode as kode")
			->from("delivery_order_batch")
			->where('depo_id', $this->session->userdata('depo_id'))
			->where('delivery_order_batch_status', 'in transit validation')
			->order_by('delivery_order_batch_kode', 'ASC')->get()->result();

		$picking_order_id = $this->input->get('picking_order_id');
		$delivery_order_batch_id = $this->input->get('delivery_order_batch_id');

		$data['picking_order_id'] = $picking_order_id;
		$data['picking_order'] = $this->M_ValidasiPengeluaranBarang->getPickingOrderKode($picking_order_id);
		$data['delivery_order_batch_id'] = $delivery_order_batch_id;

		$data['act'] = "form_by_picking_order";

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/ValidasiPengeluaranBarang/Page/form_by_picking_order', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/ValidasiPengeluaranBarang/Component/Script/S_ValidasiPengeluaranBarang', $data);
	}

	public function CetakValidasiPengeluaranBarang()
	{
		$fdjr = $this->input->get('fdjr');
		$ppb = $this->input->get('ppb');
		$do_id = $this->input->get('do_id');
		$data['row'] = $this->M_ValidasiPengeluaranBarang->getDataReport($fdjr, $ppb, $do_id);
		$data['FDJR'] = $this->M_ValidasiPengeluaranBarang->GetHeaderFDJRById($fdjr);
		$data['AssigmentDriver'] = $this->M_ValidasiPengeluaranBarang->GetAssigmentDriver($fdjr);
		$data['kendaraan_nopol'] = $this->M_ValidasiPengeluaranBarang->GetKendaraanById($data['FDJR'][0]['kendaraan_id']);
		$data['pengemudi'] = $this->M_ValidasiPengeluaranBarang->GetPengemudiById($data['FDJR'][0]['karyawan_id']);

		// echo json_encode($data['AssigmentDriver']);
		// die;

		// echo var_dump($data);

		// $this->load->view('WMS/ValidasiPengeluaranBarang/Page/print', $data);

		//title dari pdf
		$this->data['title_pdf'] = 'Laporan Validasi Pengeluaran Barang';

		//filename dari pdf ketika didownload
		$file_pdf = 'report_validasi_pengeluaran_barang_' . date('d-M-Y H:i:s');
		// //setting paper
		$paper = array(0, 0, 684, 792);
		// //orientasi paper potrait / landscape
		$orientation = "potrait";

		$html = $this->load->view('WMS/ValidasiPengeluaranBarang/Page/print', $data, true);

		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}

	public function getDataFdjrById()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));
		// $dataPost = $this->input->post();
		echo json_encode($this->M_ValidasiPengeluaranBarang->getDataFdjrById($dataPost));
	}

	public function handlerGetDataProsesValidasi()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));
		// $dataPost = $this->input->post();
		echo json_encode($this->M_ValidasiPengeluaranBarang->handlerGetDataProsesValidasi($dataPost));
	}

	public function getDetailBarangDetailBySkuId()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ValidasiPengeluaranBarang->getDetailBarangDetailBySkuId($dataPost));
	}

	public function getDetailDropKoreksiDObyId()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ValidasiPengeluaranBarang->getDetailDropKoreksiDObyId($dataPost));
	}

	public function saveDataProsesValidasi()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ValidasiPengeluaranBarang->saveDataProsesValidasi($dataPost));
	}

	public function konfirmasiDataProsesValidasi()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ValidasiPengeluaranBarang->konfirmasiDataProsesValidasi($dataPost));
	}

	public function konfirmasiSelesaiDataProsesValidasi()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ValidasiPengeluaranBarang->konfirmasiSelesaiDataProsesValidasi($dataPost));
	}

	public function konfirmasiSelesaiDepan()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ValidasiPengeluaranBarang->konfirmasiSelesaiDepan($dataPost));
	}

	public function generatePickingList()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ValidasiPengeluaranBarang->generatePickingList($dataPost));
	}

	public function generateKoreksiBKB()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ValidasiPengeluaranBarang->generateKoreksiBKB($dataPost));
	}

	public function generateKembaliLokasi()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		echo json_encode($this->M_ValidasiPengeluaranBarang->generateKembaliLokasi($dataPost));
	}

	public function getDataPickingValidationEdit()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_ValidasiPengeluaranBarang->getDataPickingValidationEdit($dataPost));
	}

	public function getAkumulasiDoByProsedure()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_ValidasiPengeluaranBarang->getAkumulasiDoByProsedure($dataPost));
	}

	public function getDataSKUBySkuId()
	{
		header('Content-Type: application/json');
		$dataPost = json_decode(file_get_contents("php://input"));
		echo json_encode($this->M_ValidasiPengeluaranBarang->getDataSKUBySkuId($dataPost));
	}
}
