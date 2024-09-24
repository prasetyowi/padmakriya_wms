<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PengemasanBarang extends CI_Controller
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
		$this->MenuKode = "135009000";
		$this->load->model('M_Menu');
		$this->load->model('WMS/M_PengemasanBarang', 'M_PengemasanBarang');
		$this->load->model('M_Function');
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Vrbl');
		$this->load->model('M_AutoGen');

		$this->load->helper(array('form', 'url'));
		$this->load->library(['form_validation', 'pdfgenerator']);
	}

	public function PengemasanBarangMenu()
	{

		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage'));
			exit();
		}

		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$data['listNoPickingList'] = $this->M_PengemasanBarang->getListNoPickingList();
		$data['listDoBatch'] = $this->M_PengemasanBarang->getListDoBatch();
		$data['listLayanan'] = $this->M_PengemasanBarang->getListLayanan();
		$data['listPengiriman'] = $this->M_PengemasanBarang->getListPengiriman();
		$data['listArea'] = $this->M_PengemasanBarang->getArea();
		$data['statuses'] = $this->M_PengemasanBarang->getListStatusDoBatch();

		$data['css_files'] = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css'
		);

		$data['js_files']     = array(
			Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
			Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js'

		);

		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('WMS/PengemasanBarang/PengemasanBarang', $data);
		$this->load->view('layouts/sidebar_footer', $data);
		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('WMS/PengemasanBarang/S_PengemasanBarang', $data);
	}

	public function GetPackingMenu()
	{

		if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")) {
			echo 0;
			exit();
		}

		$date_create = $this->input->post("date_create");
		$no_picking_list = $this->input->post("no_picking_list");
		$no_batch_do = $this->input->post("no_batch_do");
		$tipe_layanan = $this->input->post("tipe_layanan");
		$tipe_pengiriman = $this->input->post("tipe_pengiriman");
		$tipe_picking_list = $this->input->post("tipe_picking_list");
		$status = $this->input->post("status");
		$area = $this->input->post("area");
		$data['depo'] = $this->session->userdata('depo_id');
		$data['PackingMenu'] = $this->M_PengemasanBarang->Get_Packing($date_create, $no_picking_list, $no_batch_do, $tipe_layanan, $tipe_pengiriman, $tipe_picking_list, $status, $area);

		// Mendapatkan url yang ngarah ke sini :
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');

		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink($UserGroupID, $MenuLink);

		$data['UserGroupID'] = $this->session->userdata('pengguna_grup_id');
		$data['MenuLink'] = $this->session->userdata('MenuLink');

		echo json_encode($data);
	}

	public function GetDataDetailPickingList()
	{
		$id = $this->input->post('id');
		$data = $this->M_PengemasanBarang->GetDataDetailPickingList($id);
		echo json_encode($data);
	}

	public function GetDataCetakPacking()
	{
		$id = $this->input->post('id');
		$data = $this->M_PengemasanBarang->GetDataCetakPacking($id);
		echo json_encode($data);
	}

	public function CetakLabel()
	{
		$id = $this->input->get('id');
		$temp_arr = [];
		$ex_id = explode(",", $id);
		foreach ($ex_id as $key => $value) {
			$temp_arr[] = $value;
		}
		//query header
		$header = $this->M_PengemasanBarang->CetakLabel($temp_arr);

		//get jml joli
		$jmlKoli = $this->M_PengemasanBarang->getJmlKoli($temp_arr);

		$temp_ph_id = [];
		foreach ($header as $value) {
			$temp_ph_id[] = $value['ph_id'];
		}

		//update table packing h
		$this->M_PengemasanBarang->UpdateCetakLabeToPackingH($temp_ph_id);

		//query detail
		$detail = $this->M_PengemasanBarang->CetakLabelDetail($temp_ph_id);

		// echo json_encode($update);

		$result = [
			'header' => $header,
			'detail' => $detail,
			'jml_koli' => $jmlKoli
		];

		$file_pdf = 'Label resi';

		$paper = array(0, 0, 427, 336);
		// $paper = "A4";

		$orientation = "portrait";
		// $orientation = "landscape";

		// $this->load->view('master/PengemasanBarang/component/view_cetak', $result);
		$html = $this->load->view('WMS/PengemasanBarang/component/view_cetak', $result, true);

		// run dompdf

		$this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
	}

	public function GetDataListkaryawan()
	{
		$data = $this->M_PengemasanBarang->getKaryawan();
		echo json_encode($data);
	}

	public function GetDataDetailPackingDO()
	{
		$id = $this->input->post('id');
		$data = $this->M_PengemasanBarang->GetDataDetailPackingDO($id);
		echo json_encode($data);
	}

	public function UpdateStatus3Table()
	{
		$do_id = $this->input->post('do_id');
		$id_pld = $this->input->post('id_pld');
		$DoBatchId = $this->input->post('DoBatchId');
		$result = $this->M_PengemasanBarang->UpdateStatus3Table($do_id, $id_pld, $DoBatchId);
		echo json_encode($result);
	}

	public function InsertToPacking()
	{
		$data = $this->input->post('data');

		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_PCK';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depo_id = $this->session->userdata('depo_id');
		$depoPrefix = $this->M_PengemasanBarang->getDepoPrefix($depo_id);
		$unit = $depoPrefix->depo_kode_preffix;
		$error = 0;
		foreach ($data as $key => $value) {
			$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);
			$packing_h_id = $this->M_Vrbl->Get_NewID();
			$packing_h_id = $packing_h_id[0]['NEW_ID'];

			$InsertToPacking_H = $this->M_PengemasanBarang->InsertToPackingH($packing_h_id, $value['do_id'], $generate_kode, $value['tanggal_packing'], $value['berat_packing'], $value['volume_packing'], $value['keterangan_packing'], $value['id_packer_packing'], $value['nama_packer_packing']);
			if ($InsertToPacking_H == 1) {
				foreach ($value['data'] as $i => $row) {
					$this->M_PengemasanBarang->InsertToPackingD($packing_h_id, $row);
				}
				$error = 1;
			} else {
				$error = 0;
			}
		}

		echo json_encode($error);
	}

	public function GetDataDetailPackingDOWhenStatusConfirmed()
	{
		$id_pl = $this->input->post('id_pl');
		$id_pld = $this->input->post('id_pld');

		$data = $this->M_PengemasanBarang->GetDataDetailPackingDOWhenStatusConfirmed($id_pl, $id_pld);
		echo json_encode($data);
	}

	public function GetDataDetailPackingStatusConfirmed()
	{
		$do_id = $this->input->post('do_id');
		$arr = $this->M_PengemasanBarang->GetDataDetailIntablePackingStatusConfirmed($do_id);

		$tmpgroup = [];
		$group = [];

		foreach ($arr as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['kode']);
			// unset($tmpdata['tgl_packing']);
			// unset($tmpdata['oleh']);
			$tmpgroup[$data['kode']]['kode'] = $data['kode'];
			// $tmpgroup[$data['kode']]['tgl_packing'] = $data['tgl_packing'];
			// $tmpgroup[$data['kode']]['oleh'] = $data['oleh'];
			$tmpgroup[$data['kode']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}
		foreach ($tmpgroup as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['kode']);
			$group[$data['kode']] = $tmpdata;
		}

		echo json_encode($group);
	}
}
