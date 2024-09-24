<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class PenerimaanBarang extends ParentController
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

		$this->depo_id = $this->session->userdata('depo_id');
		$this->MenuKode = "126006000";
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_PenerimaanBarang', 'M_PenerimaanBarang');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation', 'pdfgenerator']);
	}

	public function PenerimaanBarangMenu()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

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



		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PenerimaanBarang/component/HalamanUtama/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PenerimaanBarang/component/HalamanUtama/S_HalamanUtama', $query);
	}

	public function get_data_perusahaan()
	{
		$data = $this->M_PenerimaanBarang->getClientWms();
		echo json_encode($data);
	}

	public function get_tipe_penerimaan()
	{
		$result = $this->M_PenerimaanBarang->getTipePenerimaan();
		echo json_encode($result);
	}

	public function get_data_principle_by_client_wms_id()
	{
		$id = $this->input->post('id');
		$data = $this->M_PenerimaanBarang->get_data_principle_by_client_wms_id($id);
		echo json_encode($data);
	}

	public function get_data_surat_jalan()
	{
		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		$perusahaan = $this->input->post('perusahaan');
		$principle = $this->input->post('principle');
		// $tipe_penerimaan = $this->input->post('tipe_penerimaan');
		$status = $this->input->post('status');

		$data = $this->M_PenerimaanBarang->get_data_surat_jalan($tahun, $bulan, $perusahaan, $principle, $status);
		echo json_encode($data);
	}

	// public function add($id)
	// {
	//     $data = array();
	//     $data['id'] = $id;
	//     $data['ekspedisis'] = $this->M_PenerimaanBarang->getExpedisi();
	//     $data['gudangs'] = $this->M_PenerimaanBarang->getGudangByDepoId();
	//     $data['checker'] = $this->M_PenerimaanBarang->get_checker_by_depo($id);

	//     // echo json_encode($data['data_detail']);
	//     // die;

	//     $data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
	//     $data['Ses_UserName'] = $this->session->userdata('pengguna_username');

	//     $data['Title'] = Get_Title_Name();
	//     $data['Copyright'] = Get_Copyright_Name();

	//     // $data['tipe_penerimaan'] = $this->M_PenerimaanSuratJalan->getTipePenerimaan();

	//     $query['css_files'] = array(
	//         Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
	//         Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

	//         Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
	//         Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
	//         Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css'
	//     );

	//     $query['js_files']     = array(
	//         Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
	//         Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

	//         Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
	//         Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
	//         Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'

	//     );

	//     $query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

	//     $query['Ses_UserName'] = $this->session->userdata('UserName');

	//     $query['Title'] = Get_Title_Name();
	//     $query['Copyright'] = Get_Copyright_Name();

	//     $this->load->view('layouts/sidebar_header', $query);
	//     $this->load->view('WMS/PenerimaanBarang/PenerimaanBarang', $data);
	//     $this->load->view('layouts/sidebar_footer', $query);

	//     $this->load->view('WMS/PenerimaanBarang/S_PenerimaanBarang', $data);
	// }

	public function add()
	{

		$id = $_GET['id'];
		$suratJalanID = $_GET['surat_jalan_id'];

		$data = array();
		$data['ekspedisis'] = $this->M_PenerimaanBarang->getExpedisi();
		$data['gudangs'] = $this->M_PenerimaanBarang->getGudangByDepoId();
		$data['header'] = $this->M_PenerimaanBarang->getDataheaderPb($id);
		$data['rowData'] = $this->M_PenerimaanBarang->getDataBySuratJalanID($suratJalanID);
		$header = $this->M_PenerimaanBarang->getDataheaderPb($id);
		$rowData = $this->M_PenerimaanBarang->getDataBySuratJalanID($suratJalanID);

		if ($rowData != null) {
			if ($header->e_id == null) {
				$update = $this->M_PenerimaanBarang->updatePenerimaanPembelian($id, $rowData->ekspedisi_id, 'ekspedisi_id');
			}

			if ($header->nopol == null) {
				$update = $this->M_PenerimaanBarang->updatePenerimaanPembelian($id, $rowData->security_logbook_nopol, 'penerimaan_pembelian_nopol');
			}

			if ($header->pengemudi == null) {
				$update = $this->M_PenerimaanBarang->updatePenerimaanPembelian($id, $rowData->security_logbook_nama_driver, 'penerimaan_pembelian_pengemudi');
			}
		}

		//getSuratjalanId
		$arrSjId = [];
		$datasSjId = $this->db->select("penerimaan_surat_jalan_id as id")->from("penerimaan_pembelian_detail3")->where("penerimaan_pembelian_id", $id)->get()->result();
		foreach ($datasSjId as $key => $value) {
			array_push($arrSjId, $value->id);
		}

		//getchecker
		$arrChecker = [];
		$dataChecker = $this->db->select("karyawan_id as id")->from("penerimaan_pembelian_detail4")->where("penerimaan_pembelian_id", $id)->get()->result();
		foreach ($dataChecker as $key => $value) {
			array_push($arrChecker, $value->id);
		}

		$data['dataChecker']  = $arrChecker;

		$data['dataKaryawanLogin'] = $this->db->select("*")->from('karyawan')->where('karyawan_id', $this->session->userdata('karyawan_id'))->get()->row();

		$data['checker'] = $this->M_PenerimaanBarang->get_checker_by_depo($arrSjId);

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		// $data['tipe_penerimaan'] = $this->M_PenerimaanSuratJalan->getTipePenerimaan();

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',
			Get_Assets_Url() . 'node_modules/lightbox2/src/css/lightbox.css'
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js',
			Get_Assets_Url() . 'node_modules/lightbox2/src/js/lightbox.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PenerimaanBarang/PenerimaanBarang', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PenerimaanBarang/S_PenerimaanBarang', $data);
	}

	public function view($id)
	{
		$data = array();
		$data['id'] = $id;

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['header'] = $this->M_PenerimaanBarang->getDataheaderPb($id);
		// $data['checker'] = $this->M_PenerimaanBarang->get_checker_by_depo();

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

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PenerimaanBarang/component/form_view/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		$this->load->view('master/S_GlobalVariable', $query);
		$this->load->view('WMS/PenerimaanBarang/component/form_view/S_view', $data);
	}

	public function getKodePenerimanSuratJalan()
	{
		$data = $this->M_PenerimaanBarang->getKodePenerimanSuratJalan();
		echo json_encode($data);
	}

	public function get_surat_jalan()
	{
		$id = $this->input->post('id');

		$result = $this->M_PenerimaanBarang->get_surat_jalan(explode(',', $id));
		echo json_encode($result);
	}

	public function get_surat_jalan_detail()
	{
		$params = array();
		$params2 = array();

		$penerimaanBarangId = $this->input->post('penerimaanBarangId');
		$pallet_id = $this->input->post('pallet_id');

		//get surat jalan id
		$datas = $this->db->select("penerimaan_surat_jalan_id as id")->from("penerimaan_pembelian_detail3")->where("penerimaan_pembelian_id", $penerimaanBarangId)->get()->result();

		for ($i = 0; $i < count($datas); $i++) {
			$params[$i] = "'" . $datas[$i]->id . "'";
		}

		if ($pallet_id != null) {
			for ($i = 0; $i < count($pallet_id); $i++) {
				$params2[$i] = "'" . $pallet_id[$i] . "'";
			}
		} else {
			array_push($params2, $pallet_id);
		}
		$datadetail = $this->M_PenerimaanBarang->get_surat_jalan_detail($penerimaanBarangId, implode(",", $params), $params2);

		//getSuratjalanId
		$arrSjId = [];
		$datasSjId = $this->db->select("penerimaan_surat_jalan_id as id")->from("penerimaan_pembelian_detail3")->where("penerimaan_pembelian_id", $penerimaanBarangId)->get()->result();
		foreach ($datasSjId as $key => $value) {
			array_push($arrSjId, $value->id);
		}

		echo json_encode([
			'datadetail' => $datadetail,
			'checker' => $this->M_PenerimaanBarang->get_checker_by_depo($arrSjId)
		]);
	}

	public function get_surat_jalan_detail_view()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->get_surat_jalan_detail_view($id);
		echo json_encode($result);
	}


	public function get_data_jenis_palet()
	{
		$result = $this->M_PenerimaanBarang->get_data_jenis_palet();
		echo json_encode($result);
	}

	public function get_data_palet()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->get_data_palet($id);
		$pallet = $this->M_PenerimaanBarang->get_data_jenis_palet();
		$gudang = $this->M_PenerimaanBarang->get_data_gudang_tujuan();
		echo json_encode(['data' => $result, 'pallet' => $pallet, 'gudang' => $gudang]);
	}


	public function update_jenis_pallet_by_id()
	{
		$id = $this->input->post('id');
		$jenis_palet_id = $this->input->post('jenis_palet_id');
		$result = $this->M_PenerimaanBarang->update_jenis_pallet_by_id($id, $jenis_palet_id);
		echo json_encode($result);
	}

	public function update_gudang_tujuan_by_id()
	{
		$id = $this->input->post('id');
		$gudang_tujuan_id = $this->input->post('gudang_tujuan_id');
		$tipe_stock = $this->input->post('tipe_stock');
		$penerimaanBarangId = $this->input->post('penerimaanBarangId');
		$lastUpdated = $this->input->post('lastUpdated');

		$checkedLastUpdated = checkLastUpdatedData((object) [
			'table' => "penerimaan_pembelian",
			'whereField' => "penerimaan_pembelian_id",
			'whereValue' => $penerimaanBarangId,
			'fieldDateUpdate' => "penerimaan_pembelian_tgl_update",
			'fieldWhoUpdate' => "penerimaan_pembelian_who_update",
			'lastUpdated' => $lastUpdated
		]);
		if ($checkedLastUpdated['status'] === 400) {
			echo json_encode([
				'status' => 400,
				'lastUpdatedNew' => null
			]);
			return false;
		}

		$result = $this->M_PenerimaanBarang->update_gudang_tujuan_by_id($id, $gudang_tujuan_id, $tipe_stock);
		echo json_encode([
			'status' => $result ? 200 : 401,
			'lastUpdatedNew' => $result ? $checkedLastUpdated['lastUpdatedNew'] : null
		]);
	}

	public function get_data_rak_by_id()
	{
		$rak_id = $this->input->post('rak_id');
		$tipe_stock = $this->input->post('tipe_stock');
		$result = $this->M_PenerimaanBarang->get_data_rak_by_id($rak_id, $tipe_stock);
		echo json_encode($result);
	}

	public function delete_pallet()
	{
		$id = $this->input->post('id');
		$penerimaanBarangId = $this->input->post('penerimaanBarangId');
		$lastUpdated = $this->input->post('lastUpdated');

		$checkedLastUpdated = checkLastUpdatedData((object) [
			'table' => "penerimaan_pembelian",
			'whereField' => "penerimaan_pembelian_id",
			'whereValue' => $penerimaanBarangId,
			'fieldDateUpdate' => "penerimaan_pembelian_tgl_update",
			'fieldWhoUpdate' => "penerimaan_pembelian_who_update",
			'lastUpdated' => $lastUpdated
		]);
		if ($checkedLastUpdated['status'] === 400) {
			echo json_encode([
				'status' => 400,
				'message' => 'Data gagal dihapus',
				'lastUpdatedNew' => null
			]);
			return false;
		}

		$result = $this->M_PenerimaanBarang->delete_pallet($id);
		echo json_encode([
			'status' => $result ? 200 : 401,
			'message' => $result ? 'Data berhasil dihapus' : 'Data gagal dihapus',
			'lastUpdatedNew' => $result ? $checkedLastUpdated['lastUpdatedNew'] : null
		]);
	}

	public function delete_pallet_detail()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->delete_pallet_detail($id);
		if ($result) {
			echo json_encode(array('status' => 'true', 'message' => 'Data berhasil dihapus'));
		} else {
			echo json_encode(array('status' => 'false', 'message' => 'Data gagal dihapus'));
		}
	}

	public function get_surat_jalan_detail_by_arrId()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->get_surat_jalan_detail_by_arrId($id);
		echo json_encode($result);
	}

	public function get_surat_jalan_detail_by_arrId_view()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->get_surat_jalan_detail_by_arrId_view($id);
		echo json_encode($result);
	}

	public function save_data_to_pallet_detail_temp()
	{
		$data = $this->input->post('data');
		$pallet_id = $this->input->post('pallet_id');
		$sj_id = $this->input->post('sj_id');
		$penerimaanBarangId = $this->input->post('penerimaanBarangId');
		$arr = array();
		// $ed = array();

		foreach ($data as $value) {
			array_push($arr, ['sku_id' => $value['sku_id'], 'ed' => $value['ed'], 'jumlah_barang' => $value['jumlah_barang'], 'batch_no' => $value['batch_no'] == "null" ? null : $value['batch_no']]);
			// array_push($ed, $value['ed']);
		}
		$params = array();
		// $idx = explode(",", $sj_id);
		for ($i = 0; $i < count($sj_id); $i++) {
			$params[$i] = "'" . $sj_id[$i] . "'";
		}
		$result = $this->M_PenerimaanBarang->save_data_to_pallet_detail_temp($arr, implode(',', $params), $pallet_id, $penerimaanBarangId);
		echo json_encode($result);
	}

	public function update_ed_pallet_detail_by_id()
	{
		$id = $this->input->post('id');
		$ed = $this->input->post('ed');

		$result = $this->M_PenerimaanBarang->update_ed_pallet_detail_by_id($id, $ed);
		echo json_encode($result);
	}

	public function update_tipe_pallet_detail_by_id()
	{
		$id = $this->input->post('id');
		$tipe_id = $this->input->post('tipe_id');
		$result = $this->M_PenerimaanBarang->update_tipe_pallet_detail_by_id($id, $tipe_id);
		echo json_encode($result);
	}

	public function update_qty_pallet_detail_by_id()
	{
		$id = $this->input->post('id');
		$qty = $this->input->post('qty');
		$result = $this->M_PenerimaanBarang->update_qty_pallet_detail_by_id($id, $qty);
		echo json_encode($result);
	}

	public function print()
	{
		$tipe = $_GET['tipe'];
		// $checker = $_GET['checker'];
		$id = $_GET['id'];
		if ($tipe == "multiple") {
			// $header = $this->M_PenerimaanBarang->get_print_header($id);
			$result = $this->M_PenerimaanBarang->get_surat_jalan_detail_view($id);
			// $detail = $this->M_PenerimaanBarang->get_print_detail($id);

			$data = [
				'detail' => $result,
				// 'detail' => $detail,
				// 'checker' => $checker
			];

			$file_pdf = 'Bukti Terima Barang';

			// setting paper
			$paper = "A4";

			//orientasi paper potrait / landscape
			$orientation = "landscape";


			$html = $this->load->view('WMS/PenerimaanBarang/component/view_cetak', $data, true);

			$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
		} else {
			$header = $this->M_PenerimaanBarang->get_print_single_header($id);
			// $detail = $this->M_PenerimaanBarang->get_print_single_detail($id);
			$data = [
				'header' => $header,
				// 'detail' => $detail,
				// 'checker' => $checker
			];

			$file_pdf = 'Cetak PO Receipt';

			// setting paper
			// $paper = array(0, 0, 427, 336);
			$paper = array(0, 0, 400, 130);

			//orientasi paper potrait / landscape
			$orientation = "portrait";


			$html = $this->load->view('WMS/PenerimaanBarang/component/view_cetak', $data, true);

			$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);

			// $this->load->view('WMS/PenerimaanBarang/component/view_cetak', $data);
		}
	}

	public function get_data_surat_jalan2()
	{
		$perusahaan_filter_sj = $this->input->post('perusahaan_filter_sj');
		$principle_filter_sj = $this->input->post('principle_filter_sj');
		$data = $this->M_PenerimaanBarang->get_data_surat_jalan2($perusahaan_filter_sj, $principle_filter_sj);

		// $arr = array();

		// foreach ($data as $key => $value) {
		//     if (($value->jml_barang == null) || ($value->jml_barang != $value->jml_terima)) {
		//         array_push($arr, [
		//             'sj_id' => $value->sj_id,
		//             'tgl' => $value->tgl,
		//             'sj_kode' => $value->sj_kode,
		//             'no_sj' => $value->no_sj,
		//             'status' => $value->status,
		//             'pt' => $value->pt,
		//             'p_kode' => $value->p_kode,
		//             'p_nama' => $value->p_nama,
		//             'tipe' => $value->tipe,
		//             'keterangan' => $value->keterangan
		//         ]);
		//     }
		// }

		$result = array_map("unserialize", array_unique(array_map("serialize", $data)));
		echo json_encode($result);
	}

	public function save_to_temp_before()
	{
		$id = $this->input->post('id');

		$pb_id = $this->M_Vrbl->Get_NewID();
		$pb_id = $pb_id[0]['NEW_ID'];

		$this->db->trans_begin();

		foreach ($id as $key => $value) {
			$this->M_PenerimaanBarang->save_to_temp_before($pb_id, $value);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function konfirmasi_data_penerimaan()
	{
		$penerimaanBarangId = $this->input->post('pb_id');
		$lastUpdated = $this->input->post('lastUpdated');

		$this->db->trans_begin();

		$checkedLastUpdated = checkLastUpdatedData((object) [
			'table' => "penerimaan_pembelian",
			'whereField' => "penerimaan_pembelian_id",
			'whereValue' => $penerimaanBarangId,
			'fieldDateUpdate' => "penerimaan_pembelian_tgl_update",
			'fieldWhoUpdate' => "penerimaan_pembelian_who_update",
			'lastUpdated' => $lastUpdated
		]);

		//get sku_id di pallet_detail dan comparekan dengan data yg di penerimaan_pembelian_detail
		$getSkuId = $this->M_PenerimaanBarang->getSkuIdPalletDetail($penerimaanBarangId);

		//get surat jalan id by penerimaan barang id
		$getSjId = $this->M_PenerimaanBarang->getDataPenerimaanById($penerimaanBarangId);


		$arrSkuId = [];
		$arr1 = [];
		$arrSjId = [];
		$arrSkuIdToUseComparePalletDetail = [];
		$dataFinal = [];
		foreach ($getSkuId as $key => $value) {
			array_push($arrSkuId, $value->sku_id);
		}

		foreach ($getSjId as $key => $value) {
			array_push($arrSjId, $value->sj_id);
		}


		$dataPenerimaanbarangDetail = $this->M_PenerimaanBarang->dataPenerimaanbarangDetail($penerimaanBarangId);

		foreach ($dataPenerimaanbarangDetail as $key => $data) {
			if (in_array($data->sku_id, $arrSkuId)) {
				array_push($arr1, $data->sku_id);
			}
		}

		$dataSku = array_unique($arr1);
		$idx = 0;

		foreach ($dataSku as $key => $value) {
			$arrSkuIdToUseComparePalletDetail[$idx++] = "'" . $value . "'";
		}

		//get data and sum pallet detail by penerimaan barang id
		$datas = $this->M_PenerimaanBarang->getDataAndSumPalletDetail(implode(',', $arrSkuIdToUseComparePalletDetail), $penerimaanBarangId);

		foreach ($dataPenerimaanbarangDetail as $key => $data) {
			foreach ($datas as $key => $value) {
				if (($data->sku_id == $value->sku_id) && ($data->sku_exp_date == $value->sku_stock_expired_date_sj)) {
					array_push($dataFinal, [
						'penerimaan_pembelian_detail_id' => $data->penerimaan_pembelian_detail_id,
						'jumlah_terima' => $value->sku_stock_qty + $data->sku_jumlah_terima,
						'sku_id' => $data->sku_id,
						'sku_exp_date' => $value->sku_stock_expired_date_sj
					]);
				}
			}
		}

		$dataFinalUnique = array_map("unserialize", array_unique(array_map("serialize", $dataFinal)));

		foreach ($dataFinalUnique as $key => $value) {
			$this->M_PenerimaanBarang->updateDetailPenerimaanBarangById($penerimaanBarangId, $value);
		}

		//get data pallet_temp
		$get_pallet_temp = $this->M_PenerimaanBarang->get_pallet_temp($arrSjId);

		foreach ($get_pallet_temp as $key => $value) {
			//delete pallet temp
			$this->M_PenerimaanBarang->delete_data_pallet_temp_by_doc_fob($value->pallet_id);

			//delete pallet detail temp
			$this->M_PenerimaanBarang->delete_data_pallet_detail_temp_by_pallet_id($value->pallet_id);
		}


		//update surat jalan menjadi in_progress
		$this->M_PenerimaanBarang->update_surat_jalan_in_progress($arrSjId, "simpan");

		//update flag penerimaan pembelian
		$this->M_PenerimaanBarang->update_flag_pb($penerimaanBarangId);

		$this->db->query("exec proses_update_status_surat_jalan '$penerimaanBarangId'");
		// echo json_encode($d);

		if ($checkedLastUpdated['status'] === 400) {
			$this->db->trans_rollback();
			$response = [
				'status' => 400,
				'message' => 'Data Gagal Dikonfirmasi'
			];
		} else if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'message' => 'Data Gagal Dikonfirmasi',
				'lastUpdatedNew' => null
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'message' => 'Data Berhasil Dikonfirmasi'
			];
		}

		echo json_encode($response);

		// if ($this->db->trans_status() === FALSE) {
		// 	$this->db->trans_rollback();
		// 	echo json_encode(0);
		// } else {
		// 	$this->db->trans_commit();
		// 	echo json_encode(1);
		// }
	}

	public function get_width_and_lenght_by_depo()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->get_width_and_lenght_by_depo($id);
		echo json_encode($result);
	}

	public function GetDepoDetailMenu()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->GetDepoDetailMenu($id);
		echo json_encode($result);
	}

	public function batalkan()
	{
		$id = $this->input->post('id');
		$lastUpdated = $this->input->post('lastUpdated');

		$this->db->trans_begin();

		$checkedLastUpdated = checkLastUpdatedData((object) [
			'table' => "penerimaan_pembelian",
			'whereField' => "penerimaan_pembelian_id",
			'whereValue' => $id,
			'fieldDateUpdate' => "penerimaan_pembelian_tgl_update",
			'fieldWhoUpdate' => "penerimaan_pembelian_who_update",
			'lastUpdated' => $lastUpdated
		]);

		$p_id = $this->M_PenerimaanBarang->get_pallet_real($id);
		foreach ($p_id as $key => $val) {
			//delete pallet
			$this->M_PenerimaanBarang->delete_pallet_real($val->pallet_id);

			//delete pallet detail
			$this->M_PenerimaanBarang->delete_pallet_detail_real($val->pallet_id);
		}

		//get penerimaan_surat_jalan_id
		$sj_id_t = $this->M_PenerimaanBarang->get_sj_id($id);

		$sj_id = [];
		foreach ($sj_id_t as $val) {
			array_push($sj_id, $val->id);
		}

		$get_pallet_temp = $this->M_PenerimaanBarang->get_pallet_temp($sj_id);

		foreach ($get_pallet_temp as $key => $value) {
			//delete pallet temp
			$this->M_PenerimaanBarang->delete_data_pallet_temp_by_doc_fob($value->pallet_id);

			//delete pallet detail temp
			$this->M_PenerimaanBarang->delete_data_pallet_detail_temp_by_pallet_id($value->pallet_id);
		}

		//update surat jalan menjadi open kembali
		$this->M_PenerimaanBarang->update_surat_jalan_in_progress($sj_id, "delete");

		//delete penerimaan pembelian
		$this->M_PenerimaanBarang->delete_penerimaan_pembelian($id);

		//delete penerimaan pembelian detail
		$this->M_PenerimaanBarang->delete_penerimaan_pembelian_detail($id);

		//delete penerimaan pembelian detail2
		$this->M_PenerimaanBarang->delete_penerimaan_pembelian_detail2($id);

		//delete penerimaan pembelian detail3
		$this->M_PenerimaanBarang->delete_penerimaan_pembelian_detail3($id);

		//delete penerimaan pembelian detail4
		$this->M_PenerimaanBarang->delete_penerimaan_pembelian_detail4($id);


		if ($checkedLastUpdated['status'] === 400) {
			$this->db->trans_rollback();
			$response = [
				'status' => 400,
				'message' => 'Data Gagal Dibatalkan',
				'lastUpdatedNew' => null
			];
		} else if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'message' => 'Data Gagal Dibatalkan',
				'lastUpdatedNew' => null
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'message' => 'Data Berhasil Dibatalkan',
				'lastUpdatedNew' => $checkedLastUpdated['lastUpdatedNew']
			];
		}

		echo json_encode($response);

		// if ($this->db->trans_status() === FALSE) {
		// 	$this->db->trans_rollback();
		// 	echo json_encode(0);
		// } else {
		// 	$this->db->trans_commit();
		// 	echo json_encode(1);
		// }
	}

	public function batalkan2()
	{
		$id = $this->input->post('id');

		$this->db->trans_begin();

		$get_pallet_temp = $this->M_PenerimaanBarang->get_pallet_temp($id);

		foreach ($get_pallet_temp as $key => $value) {
			//delete pallet temp
			$this->M_PenerimaanBarang->delete_data_pallet_temp_by_doc_fob($value->pallet_id);

			//delete pallet detail temp
			$this->M_PenerimaanBarang->delete_data_pallet_detail_temp_by_pallet_id($value->pallet_id);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function getDataheaderPb()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->getDataheaderPb($id);
		$ekspedisis = $this->M_PenerimaanBarang->getExpedisi();
		echo json_encode([
			'result' => $result,
			'ekspedisis' => $ekspedisis
		]);
	}

	public function getDataPalletPb()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->getDataPalletPb($id);
		echo json_encode($result);
	}

	public function edit($id)
	{
		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['id'] = $id;

		$data['header'] = $this->M_PenerimaanBarang->getDataheaderPb($id);

		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$query['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',
			Get_Assets_Url() . 'node_modules/lightbox2/src/css/lightbox.css'
		);

		$query['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
			Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js',
			Get_Assets_Url() . 'node_modules/lightbox2/src/js/lightbox.js'

		);

		$query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$query['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$query['Title'] = Get_Title_Name();
		$query['Copyright'] = Get_Copyright_Name();


		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $query);
		$this->load->view('WMS/PenerimaanBarang/component/form_edit/index', $data);
		$this->load->view('layouts/sidebar_footer', $query);

		// $this->load->view('WMS/S_GlobalVariable', $data);
		$this->load->view('WMS/PenerimaanBarang/component/form_edit/S_edit', $data);
	}

	public function get_sj_id()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->get_sj_id($id);
		echo json_encode($result);
	}

	public function edit_data_penerimaan()
	{
		$pb_id = $this->input->post('pb_id');
		$doc_fob = $this->input->post('doc_fob');
		$keterangan = $this->input->post('keterangan');
		$detail_btb = $this->input->post('detail_btb');

		$result = "";
		$arr_pallet_detail_id = array();

		$this->db->trans_begin();

		//update data penerimaan barang
		$this->M_PenerimaanBarang->update_data_penerimaan_pembelian($pb_id, $keterangan);

		//delete penerimaan pembelian detail
		$this->M_PenerimaanBarang->delete_penerimaan_pembelian_detail($pb_id);

		//insert ke penerimaan pembelian detail
		foreach ($detail_btb as $data) {
			$this->M_PenerimaanBarang->save_data_penerimaan_pembelian_detail($pb_id, $data);
		}

		$p_id = $this->M_PenerimaanBarang->get_pallet_real($pb_id);
		foreach ($p_id as $key => $val) {
			//delete pallet
			$this->M_PenerimaanBarang->delete_pallet_real($val->pallet_id);

			//delete pallet detail
			$this->M_PenerimaanBarang->delete_pallet_detail_real($val->pallet_id);
		}

		//delete penerimaan pembelian detail2
		$this->M_PenerimaanBarang->delete_penerimaan_pembelian_detail2($pb_id);

		//get pallet jenis di table pallet temp by fob
		$pallet_temp = $this->M_PenerimaanBarang->get_pallet_temp($doc_fob);
		foreach ($pallet_temp as $key => $value) {
			$pallet_id = $this->M_Vrbl->Get_NewID();
			$pallet_id = $pallet_id[0]['NEW_ID'];

			$date_now_ = date('Y-m-d h:i:s');
			$param_ =  $value->pallet_jenis_id;
			$vrbl_ = $this->M_PenerimaanBarang->Get_KodePallet($param_);
			$prefix_ = $vrbl_;
			// get prefik depo
			$depo_id_ = $this->session->userdata('depo_id');
			$depoPrefix_ = $this->M_PenerimaanBarang->getDepoPrefix($depo_id_);
			$unit_ = $depoPrefix_->depo_kode_preffix;
			$generate_kode_pallet = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now_, $prefix_, $unit_);

			//insert ke pallet dari pallet temp
			$this->M_PenerimaanBarang->save_data_pallet_from_temp($pallet_id, $generate_kode_pallet, $value);

			//insert ke penerimaan pembelian detail2
			$pbd_2_id = $this->M_Vrbl->Get_NewID();
			$pbd_2_id = $pbd_2_id[0]['NEW_ID'];
			$this->M_PenerimaanBarang->save_data_penerimaan_pembelian_detail2($pb_id, $pallet_id, $value->depo_detail_id, $pbd_2_id);

			//get data pallet detail temp by pallet_id dari temp
			$pallet_detail_temp = $this->M_PenerimaanBarang->get_pallet_detail_temp($value->pallet_id);
			foreach ($pallet_detail_temp as $val) {
				$pallet_detail_id = $this->M_Vrbl->Get_NewID();
				$pallet_detail_id = $pallet_detail_id[0]['NEW_ID'];
				//insert ke pallet detail dari pallet detail temp
				$insertPalletDetail = $this->M_PenerimaanBarang->save_data_pallet_detail_from_temp($pallet_detail_id, $pallet_id, $val, $pbd_2_id, $pb_id);
				if ($insertPalletDetail) {
					array_push($arr_pallet_detail_id, array('pallet_detail_id' => $pallet_detail_id, 'sku_id' => $val->sku_id));

					$result = 1;
					// echo json_encode(array('status' => true, 'message' => 'Data berhasil disimpan!'));
				} else {
					$result = 0;
					// echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan!'));
				}
			}
		}

		if ($result == 1) {

			//delete penerimaan pembelian detail3
			$this->M_PenerimaanBarang->delete_penerimaan_pembelian_detail3($pb_id);

			//insert ke penerimaan pembelian detail3
			foreach ($doc_fob as $key => $val) {
				$this->M_PenerimaanBarang->save_data_penerimaan_pembelian_detail3($pb_id, $val);
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function get_perusahaan()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->get_perusahaan($id);
		echo json_encode($result);
	}

	public function get_principle()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->get_principle($id);
		echo json_encode($result);
	}

	public function get_area_rak_gudang()
	{
		$tipe_stock = $this->input->post('tipe_stock');
		$client_wms = $this->input->post('client_wms');
		$principle = $this->input->post('principle');
		$depo = $this->input->post('depo');

		$result = $this->M_PenerimaanBarang->get_area_rak_gudang($tipe_stock, $client_wms, $principle, $depo);
		echo json_encode($result);
	}

	public function check_data_ed_in_pallet_detail_temp()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->check_data_ed_in_pallet_detail_temp($id);
		echo json_encode($result);
	}

	public function get_sku_in_pallet_temp()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->get_sku_in_pallet_temp($id);
		echo json_encode($result);
	}

	public function check_data_when_status_close_show_alert()
	{
		$id = $this->input->post('id');
		$result = $this->M_PenerimaanBarang->check_data_when_status_close_show_alert($id);
		echo json_encode($result);
	}

	public function checkKodePalletAndInsertToTemp()
	{
		$surat_jalan_id = explode(',', $this->input->post('surat_jalan_id'));
		$client_id = $this->input->post('client_id');
		$principle_id = $this->input->post('principle_id');
		$kode = $this->input->post('kode');
		$type = $this->input->post('type');
		$mode = $this->input->post('mode');
		$aktif = $this->input->post('aktif');

		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PenerimaanBarang->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;

		// $kode = $unit . "/" . $kode_pallet;
		if ($type == "pallet") {
			$kodePallet = preg_replace('/\s+/', '', $unit  . "/" . $kode);

			$result = $this->M_PenerimaanBarang->checkKodePallet($surat_jalan_id, $client_id,  $principle_id,  $kodePallet);
			if ($result == null) {
				echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kodePallet . "</strong> tidak ditemukan", 'kode' => $kodePallet));
			} else {

				//check jika kode pallet sudah digunakan atau belum di
				$existKodePallet = $this->M_PenerimaanBarang->existKodePallet($kodePallet);

				$existKodePalletTemp = $this->M_PenerimaanBarang->existKodePalletTemp($kodePallet);

				//check aktif atau tidak
				if ($result->pallet_generate_detail2_is_aktif == 1) {
					echo json_encode(array('type' => 202, 'message' => "Kode pallet <strong>" . $kodePallet . "</strong> sudah terpakai", 'kode' => $kodePallet));
				} else {
					//jika sudah digunakan maka tampilkan message sudah terpakai
					if ($existKodePallet != null) {
						echo json_encode(array('type' => 202, 'message' => "Kode pallet <strong>" . $kodePallet . "</strong> sudah terpakai", 'kode' => $kodePallet));
					} else {
						//check jika kode pallet sudah digunakan atau belum di pallet temp
						//jika sudah digunakan maka tampilkan message pallet sedang digunakan di penerimaan barang lain
						if ($existKodePalletTemp != null) {
							echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kodePallet . "</strong> sedang dihandle oleh karyawan " . $existKodePalletTemp->karyawan_nama, 'kode' => $kodePallet));
						} else {
							//insert ke table pallet temp
							$this->M_PenerimaanBarang->save_data_pallet($surat_jalan_id, $result);
							// if ($aktif == 0) {
							// 	$this->M_PenerimaanBarang->save_data_pallet_is_aktif($surat_jalan_id, $result);
							// }
							echo json_encode(array('type' => 200, 'message' => "success scan pallet", 'kode' => $kodePallet));
						}
					}
				}
			}
		}

		if ($type == "sku") {
			$kodeSKU = preg_replace('/\s+/', '', $kode);
			$result = $this->M_PenerimaanBarang->checkKodeSKU($surat_jalan_id, $kodeSKU, $mode);
			if (empty($result)) {
				echo json_encode([
					'type' => 201,
					'message' => "Kode <strong>" . $kodeSKU . "</strong> tidak ditemukan",
					'kode' => $kodeSKU,
					'data' => []
				]);
			} else {
				echo json_encode([
					'type' => 200,
					'message' => "succes scan kode sku",
					'kode' => $kodeSKU,
					'data' => $result
				]);
			}
		}
	}

	public function checkMinimunExpiredDate()
	{
		$ed_request = $this->input->post('ed_request');
		$sku_id = $this->input->post('sku_id');

		$getMinimumED = $this->db->select("sku_minimum_expired_date")->from("sku")->where('sku_id', $sku_id)->get()->row();

		$tgl = date('Y-m-d', strtotime('+' . $getMinimumED->sku_minimum_expired_date . ' month'));

		$date1 = date('Y-m', strtotime($ed_request));
		$date2 = date('Y-m', strtotime($tgl));

		if ($date1 < $date2) {
			echo json_encode([
				'type' => 201,
				'message' => 'Expired Date tidak boleh dibawah minimum Expired date ' . $date2
			]);
		} else {
			echo json_encode([
				'type' => 200,
				'message' => 'successfully'
			]);
		}
	}

	public function generateNewIdPenerimaan()
	{
		$PenerimaanBarangId = $this->M_Vrbl->Get_NewID();
		$PenerimaanBarangId = $PenerimaanBarangId[0]['NEW_ID'];

		echo json_encode($PenerimaanBarangId);
	}

	public function saveDataPenerimaanbarang()
	{
		$penerimaanId = $this->input->post('penerimaanId');
		$sjId = $this->input->post('sjId');
		$client_id = $this->input->post('client_id');
		$principle_id = $this->input->post('principle_id');
		//generate kode
		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_BTB_BELI';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PenerimaanBarang->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$konversiTempId = $this->M_Vrbl->Get_NewID();
		$konversiTempId = $konversiTempId[0]['NEW_ID'];

		$this->db->trans_begin();

		$this->M_PenerimaanBarang->save_data_penerimaan_pembelian($penerimaanId, $generate_kode, $client_id, $principle_id);

		//get data penerimaan surat jalan detail by surat jalan id
		$dataSuratjalanDetail = $this->M_PenerimaanBarang->getDataSuratJalanDetail($sjId);

		//insert ke konversi temp
		foreach ($dataSuratjalanDetail as $key => $value) {
			$this->M_PenerimaanBarang->saveDataToKonversiTemp($konversiTempId, $value);
		}

		//procedure temp table konversi
		$datas = $this->db->query("Exec proses_konversi_sku '$konversiTempId'");

		foreach ($datas->result_array() as $key => $item) {
			$this->M_PenerimaanBarang->save_data_penerimaan_pembelian_detail($penerimaanId, $item);
		}

		foreach ($sjId as $key => $val) {
			$this->M_PenerimaanBarang->save_data_penerimaan_pembelian_detail3($penerimaanId, $val);
		}

		//update surat jalan menjadi in_progress
		$this->M_PenerimaanBarang->update_surat_jalan_in_progress($sjId, "simpan");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(0);
		} else {
			$this->db->trans_commit();
			echo json_encode(1);
		}
	}

	public function konfirmasiDataPerPallet()
	{
		$penerimaanBarangId = $this->input->post('penerimaanBarangId');
		$client_id = $this->input->post('client_id');
		$tgl = $this->input->post('tgl');
		$gudang_asal = $this->input->post('gudang_penerima');
		// $checker = $this->input->post('checker');
		$keterangan = $this->input->post('keterangan');
		$pallet_id = $this->input->post('pallet_id');
		$principle_id = $this->input->post('principle_id');
		$karyawan_id = $this->input->post('karyawan_id');
		$who_create = $this->session->userdata("pengguna_username");
		$lastUpdated = $this->input->post('lastUpdated');

		$dp_id = $this->M_Vrbl->Get_NewID();
		$dp_id = $dp_id[0]['NEW_ID'];


		//generate kode
		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_DPB';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PenerimaanBarang->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		$result = "";
		// $result2 = "";
		$result2 = 1;

		$this->db->trans_begin();

		$checkedLastUpdated = checkLastUpdatedData((object) [
			'table' => "penerimaan_pembelian",
			'whereField' => "penerimaan_pembelian_id",
			'whereValue' => $penerimaanBarangId,
			'fieldDateUpdate' => "penerimaan_pembelian_tgl_update",
			'fieldWhoUpdate' => "penerimaan_pembelian_who_update",
			'lastUpdated' => $lastUpdated
		]);

		//get penerimaan_surat_jalan_id
		$sj_id_t = $this->M_PenerimaanBarang->get_sj_id($penerimaanBarangId);

		$sj_id = [];
		foreach ($sj_id_t as $val) {
			array_push($sj_id, $val->id);
		}

		$this->M_PenerimaanBarang->save_data_to_distribusi_penerimaan($dp_id, $generate_kode, $tgl, $penerimaanBarangId, "Open", $keterangan, $gudang_asal);


		//get pallet jenis di table pallet temp by fob
		$pallet_temp = $this->M_PenerimaanBarang->getPalletTempByPalletId($pallet_id);

		$getPalletGenerate = $this->db->select("pallet_generate_detail2_id as pallet_id")->from("pallet_generate_detail2")->where("pallet_generate_detail2_kode", $pallet_temp->pallet_kode)->get()->row();

		$insertPallet = $this->M_PenerimaanBarang->save_data_pallet_from_temp($getPalletGenerate->pallet_id, $pallet_temp);
		if ($insertPallet) {
			//insert ke penerimaan pembelian detail2
			$pbd_2_id = $this->M_Vrbl->Get_NewID();
			$pbd_2_id = $pbd_2_id[0]['NEW_ID'];
			$this->M_PenerimaanBarang->save_data_penerimaan_pembelian_detail2($penerimaanBarangId, $getPalletGenerate->pallet_id, $pallet_temp->depo_detail_id, $pbd_2_id);

			//update status aktif di pallet generate detail 2
			$this->db->set("pallet_generate_detail2_is_aktif", 1);
			$this->db->where("pallet_generate_detail2_id", $getPalletGenerate->pallet_id);
			$this->db->update("pallet_generate_detail2");

			//get data pallet detail temp by pallet_id dari temp
			$pallet_detail_temp = $this->M_PenerimaanBarang->get_pallet_detail_temp($pallet_id);
			foreach ($pallet_detail_temp as $val) {
				$pallet_detail_id = $this->M_Vrbl->Get_NewID();
				$pallet_detail_id = $pallet_detail_id[0]['NEW_ID'];
				//insert ke pallet detail dari pallet detail temp
				$this->M_PenerimaanBarang->save_data_pallet_detail_from_temp($pallet_detail_id, $getPalletGenerate->pallet_id, $val, $pbd_2_id, $penerimaanBarangId);

				// //delete pallet temp
				// $this->M_PenerimaanBarang->delete_data_pallet_temp_by_doc_fob($pallet_id);

				// //delete pallet detail temp
				// $this->M_PenerimaanBarang->delete_data_pallet_detail_temp_by_pallet_id($pallet_id);
			}
		}


		//get data distribusi_penerimaan_detail2 by pb id
		$get_data = $this->M_PenerimaanBarang->get_data_dpdt_by_no_penerimaan($penerimaanBarangId, $pallet_id);

		//insert ke distribusi_penerimaan_detail
		foreach ($get_data as $val) {
			$dp_detail = $this->M_PenerimaanBarang->save_data_to_distribusi_penerimaan_detail($dp_id, $val);
			if ($dp_detail) {
				$result = 1;
			} else {
				$result = 0;
			}
		}

		if ($result == 1) {
			$depo_detail_id_tujuan = [];
			foreach ($get_data as $key => $value) {
				if (array_search($value->depo_detail_id, $depo_detail_id_tujuan) !== false) {
					unset($get_data[$key]);
				} else {
					$depo_detail_id_tujuan[] = $value->depo_detail_id;
				}
			}


			foreach ($depo_detail_id_tujuan as $key => $data) {
				//insert ke tr mutasi pallet draft
				$mp_id = $this->M_Vrbl->Get_NewID();
				$mp_id = $mp_id[0]['NEW_ID'];

				//generate kode
				$date_now_ = date('Y-m-d h:i:s');
				$param_ =  'KODE_DMPAG';
				$vrbl_ = $this->M_Vrbl->Get_Kode($param_);
				$prefix_ = $vrbl_->vrbl_kode;
				// get prefik depo
				$depo_id_ = $this->session->userdata('depo_id');
				$depoPrefix_ = $this->M_PenerimaanBarang->getDepoPrefix($depo_id_);
				$unit_ = $depoPrefix_->depo_kode_preffix;
				$generate_kode_ = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now_, $prefix_, $unit_);

				$this->M_PenerimaanBarang->save_data_to_mutasi_pallet_draft($mp_id, $dp_id, $generate_kode_, $tgl, $principle_id, "Open", $keterangan, $gudang_asal, $data, $karyawan_id, $client_id);

				$get_data2 = $this->M_PenerimaanBarang->get_data_dpdt_by_no_penerimaan2($penerimaanBarangId, $data, $pallet_id);
				foreach ($get_data2 as $key2 => $val) {
					//insert ke tr mutasi pallet detail draft
					$mp_detail = $this->M_PenerimaanBarang->save_data_to_mutasi_pallet_detail_draft($mp_id, $val->pallet_id);
					if ($mp_detail) {
						$result2 = 1;
					} else {
						$result2 = 0;
					}
				}
			}
		}

		if ($result2 == 1) {

			$get_pallet_detail_id = $this->M_PenerimaanBarang->get_pallet_detail_id($penerimaanBarangId, $pallet_id);
			//get data penerimaan_pembelian dan check jika di sku_stock ada sku_id yg sama dan ed yg sama, makan update, jika kosong insert
			$params = [];
			for ($i = 0; $i < count($sj_id); $i++) {
				$params[$i] = "'" . $sj_id[$i] . "'";
			}

			$data_ = $this->M_PenerimaanBarang->check_data_in_sku_stock($params, $penerimaanBarangId, $pallet_id);

			foreach ($data_ as $key => $value) {
				$sku_stock_id = $this->M_Vrbl->Get_NewID();
				$sku_stock_id = $sku_stock_id[0]['NEW_ID'];

				$check_data_sku_stock_by_params = $this->M_PenerimaanBarang->check_data_sku_stock_by_params($value);
				if ($check_data_sku_stock_by_params == null) {
					//insert ke table sku_stock
					$res = $this->M_PenerimaanBarang->insert_data_to_sku_stock($sku_stock_id, $value, $gudang_asal);
					if ($res) {
						//update sku_stok_id di table pallet_detail
						foreach ($get_pallet_detail_id as $val) {
							if ($value->pallet_detail_id == $val->pallet_detail_id) {
								$this->M_PenerimaanBarang->update_sku_stock_id_in_pallet_detail($val->pallet_detail_id, $sku_stock_id);
							}
						}
					}
				} else {
					//update ke table sku_stock
					// $stock_masuk = $check_data_sku_stock_by_params->sku_stock_masuk + $value->qty;
					$this->db->query("exec insertupdate_sku_stock 'masuk', '$check_data_sku_stock_by_params->sku_stock_id', '$value->client_wms_id', '$value->qty'");
					// $this->db->query("UPDATE sku_stock SET client_wms_id = '$data->client_wms_id' WHERE sku_stock_id = '$check_data_sku_stock_by_params->sku_stock_id'");
					// $res2 = $this->M_PenerimaanBarang->update_data_to_sku_stock($value, $check_data_sku_stock_by_params);
					//update sku_stok_id di table pallet_detail
					foreach ($get_pallet_detail_id as $val) {
						if ($value->pallet_detail_id == $val->pallet_detail_id) {
							$this->M_PenerimaanBarang->update_sku_stock_id_in_pallet_detail($val->pallet_detail_id, $check_data_sku_stock_by_params->sku_stock_id);
						}
					}
					// if ($res2) {

					// }
				}
			}

			$this->db->query("exec proses_posting_stock_card 'BTBSUPPLIER2', '$getPalletGenerate->pallet_id', '$who_create'");
		}

		if ($checkedLastUpdated['status'] === 400) {
			$this->db->trans_rollback();
			$response = [
				'status' => 400,
				'message' => 'Data Gagal Dibatalkan',
				'lastUpdatedNew' => null
			];
		} else if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'message' => $this->db->error()['message'],
				'lastUpdatedNew' => null
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'message' => 'Data Berhasil Dikonfirmasi',
				'lastUpdatedNew' => $checkedLastUpdated['lastUpdatedNew']
			];
		}

		echo json_encode($response);
	}

	public function checkPalletIsreadyInPbd2()
	{
		$penerimaanBarangId = $this->input->post('penerimaanbarangId');
		$pallet = $this->M_PenerimaanBarang->checkPalletIsreadyInPbd2($penerimaanBarangId);

		echo json_encode($pallet);
	}

	public function getDataPenerimaanById()
	{
		$penerimaanBarangId = $this->input->post('penerimaanBarangId');
		$result = $this->M_PenerimaanBarang->getDataPenerimaanById($penerimaanBarangId);

		echo json_encode($result);
	}

	public function checkLastUpdatedData($penerimaanBarangId, $lastUpdated)
	{

		$this->db->trans_begin();


		$errorNotSameLastUpdated = false;


		$getLastUpdatedDb = $this->db->select("penerimaan_pembelian_tgl_update")->from("penerimaan_pembelian")->where("penerimaan_pembelian_id", $penerimaanBarangId)->get()->row()->penerimaan_pembelian_tgl_update;

		if ($lastUpdated !== $getLastUpdatedDb) $errorNotSameLastUpdated = true;

		return $lastUpdated;
		die;

		$this->db->set("penerimaan_pembelian_tgl_update", date('Y-m-d H:i:s'));
		$this->db->set("penerimaan_pembelian_who_update", $this->session->userdata('pengguna_username'));
		$this->db->where("penerimaan_pembelian_id", $penerimaanBarangId);

		$this->db->update("penerimaan_pembelian");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'lastUpdatedNew' => null
			];
		} else if ($errorNotSameLastUpdated) {
			$this->db->trans_rollback();
			$response = [
				'status' => 400,
				'lastUpdatedNew' => null
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'lastUpdatedNew' => date('Y-m-d H:i:s')
			];
		}

		return $response;
	}

	public function requestUpdateHeaderService()
	{
		$penerimaanBarangId = $this->input->post('penerimaanBarangId');
		$gudang_penerima = $this->input->post('gudangPenerima');
		$expedisi = $this->input->post('jasaPengangkut');
		$lastUpdated = $this->input->post('lastUpdated');

		// $checkedLastUpdated = $this->checkLastUpdatedData($penerimaanBarangId, $lastUpdated);
		$checkedLastUpdated = checkLastUpdatedData((object) [
			'table' => "penerimaan_pembelian",
			'whereField' => "penerimaan_pembelian_id",
			'whereValue' => $penerimaanBarangId,
			'fieldDateUpdate' => "penerimaan_pembelian_tgl_update",
			'fieldWhoUpdate' => "penerimaan_pembelian_who_update",
			'lastUpdated' => $lastUpdated
		]);

		if ($checkedLastUpdated['status'] === 400) {
			echo json_encode([
				'status' => 400,
				'lastUpdatedNew' => null
			]);
			return false;
		}

		$result = $this->M_PenerimaanBarang->requestUpdateHeaderService($penerimaanBarangId, $gudang_penerima, $expedisi);
		echo json_encode([
			'status' => $result ? 200 : 401,
			'lastUpdatedNew' => $result ? $checkedLastUpdated['lastUpdatedNew'] : null
		]);
	}

	public function requestUpdateHeaderVehicle()
	{
		$penerimaanBarangId = $this->input->post('penerimaanBarangId');
		$gudang_penerima = $this->input->post('gudangPenerima');
		$no_kendaraan  = $this->input->post('kendaraan');
		$lastUpdated = $this->input->post('lastUpdated');

		$checkedLastUpdated = checkLastUpdatedData((object) [
			'table' => "penerimaan_pembelian",
			'whereField' => "penerimaan_pembelian_id",
			'whereValue' => $penerimaanBarangId,
			'fieldDateUpdate' => "penerimaan_pembelian_tgl_update",
			'fieldWhoUpdate' => "penerimaan_pembelian_who_update",
			'lastUpdated' => $lastUpdated
		]);

		if ($checkedLastUpdated['status'] === 400) {
			echo json_encode([
				'status' => 400,
				'lastUpdatedNew' => null
			]);
			return false;
		}

		$result = $this->M_PenerimaanBarang->requestUpdateHeaderVehicle($penerimaanBarangId, $gudang_penerima, $no_kendaraan);
		echo json_encode([
			'status' => $result ? 200 : 401,
			'lastUpdatedNew' => $result ? $checkedLastUpdated['lastUpdatedNew'] : null
		]);
	}

	public function requestUpdateHeaderDriver()
	{
		$penerimaanBarangId = $this->input->post('penerimaanBarangId');
		$gudang_penerima = $this->input->post('gudangPenerima');
		$nama_pengemudi = $this->input->post('namaPengemudi');
		$lastUpdated = $this->input->post('lastUpdated');

		$checkedLastUpdated = checkLastUpdatedData((object) [
			'table' => "penerimaan_pembelian",
			'whereField' => "penerimaan_pembelian_id",
			'whereValue' => $penerimaanBarangId,
			'fieldDateUpdate' => "penerimaan_pembelian_tgl_update",
			'fieldWhoUpdate' => "penerimaan_pembelian_who_update",
			'lastUpdated' => $lastUpdated
		]);
		if ($checkedLastUpdated['status'] === 400) {
			echo json_encode([
				'status' => 400,
				'lastUpdatedNew' => null
			]);
			return false;
		}

		$result = $this->M_PenerimaanBarang->requestUpdateHeaderDriver($penerimaanBarangId, $gudang_penerima, $nama_pengemudi);
		echo json_encode([
			'status' => $result ? 200 : 401,
			'lastUpdatedNew' => $result ? $checkedLastUpdated['lastUpdatedNew'] : null
		]);
	}

	public function requestUpdateHeaderChecker()
	{
		$penerimaanBarangId = $this->input->post('penerimaanBarangId');
		$gudang_penerima = $this->input->post('gudangPenerima');
		$checker = $this->input->post('checker');
		$lastUpdated = $this->input->post('lastUpdated');

		$this->db->trans_begin();

		$checkedLastUpdated = checkLastUpdatedData((object) [
			'table' => "penerimaan_pembelian",
			'whereField' => "penerimaan_pembelian_id",
			'whereValue' => $penerimaanBarangId,
			'fieldDateUpdate' => "penerimaan_pembelian_tgl_update",
			'fieldWhoUpdate' => "penerimaan_pembelian_who_update",
			'lastUpdated' => $lastUpdated
		]);

		$this->M_PenerimaanBarang->requestUpdateHeaderChecker($penerimaanBarangId, $gudang_penerima, $checker);

		if ($checkedLastUpdated['status'] === 400) {
			$this->db->trans_rollback();
			$response = [
				'status' => 400,
				'lastUpdatedNew' => null
			];
		} else if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'lastUpdatedNew' => null
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'lastUpdatedNew' => $checkedLastUpdated['lastUpdatedNew']
			];
		}

		echo json_encode($response);
	}

	public function requestUpdateHeaderketerangan()
	{
		$penerimaanBarangId = $this->input->post('penerimaanBarangId');
		$gudang_penerima = $this->input->post('gudangPenerima');
		$keterangan = $this->input->post('keterangan');
		$lastUpdated = $this->input->post('lastUpdated');

		$checkedLastUpdated = checkLastUpdatedData((object) [
			'table' => "penerimaan_pembelian",
			'whereField' => "penerimaan_pembelian_id",
			'whereValue' => $penerimaanBarangId,
			'fieldDateUpdate' => "penerimaan_pembelian_tgl_update",
			'fieldWhoUpdate' => "penerimaan_pembelian_who_update",
			'lastUpdated' => $lastUpdated
		]);
		if ($checkedLastUpdated['status'] === 400) {
			echo json_encode([
				'status' => 400,
				'lastUpdatedNew' => null
			]);
			return false;
		}

		$result = $this->M_PenerimaanBarang->requestUpdateHeaderketerangan($penerimaanBarangId, $gudang_penerima, $keterangan);
		echo json_encode([
			'status' => $result ? 200 : 401,
			'lastUpdatedNew' => $result ? $checkedLastUpdated['lastUpdatedNew'] : null
		]);
	}

	public function checkPalletConfirm()
	{
		$id = $this->input->post('id');

		$result = $this->M_PenerimaanBarang->checkPalletConfirm($id);
		echo json_encode($result);
	}

	public function updateCheckerBySku()
	{
		$this->db->set("karyawan_id", $this->input->post('value'));
		$this->db->where("penerimaan_pembelian_detail_id", $this->input->post('id'));
		$this->db->update("penerimaan_pembelian_detail");
	}

	public function getKodeAutoCompleteOld()
	{
		$valueParams = $this->input->get('params');
		$typeScan = $this->input->get('type');
		$result = $this->M_PenerimaanBarang->getKodeAutoComplete($valueParams, $typeScan);
		echo json_encode($result);
	}
	public function getKodeAutoComplete()
	{
		$valueParams = $this->input->get('params');
		$typeScan = $this->input->get('type');
		$prosesId = $this->input->get('prosesId');
		$principleId = $this->input->get('principleId');
		$result = $this->M_PenerimaanBarang->getKodeAutoComplete($valueParams, $typeScan, $prosesId, $principleId);
		echo json_encode($result);
	}

	public function print_bukti_penerimaan()
	{
		$id = $_GET['id'];
		$p_kode = $_GET['p_kode'];
		$p_nama = $_GET['p_nama'];
		$pt = $_GET['pt'];
		$header = $this->M_PenerimaanBarang->getDataheaderPb($id);
		$result = $this->M_PenerimaanBarang->getDataDetailCetakBuktiPenerimaan($id);
		$checker = $this->M_PenerimaanBarang->getDataDetailCheckerCetakBuktiPenerimaan($id);

		$data = [
			'atas' => $header,
			'detail' => $result,
			// 'detail' => $detail,
			'checker' => $checker,
			'p_kode' => $p_kode,
			'p_nama' => $p_nama,
			'pt' => $pt
		];


		// echo json_encode($data);
		// die;

		$file_pdf = 'Bukti Terima Barang';

		// setting paper
		$paper = "A4";

		//orientasi paper potrait / landscape
		$orientation = "landscape";


		$html = $this->load->view('WMS/PenerimaanBarang/component/view_cetak_bukti_penerimaan', $data, true);

		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}
}
