<?php

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

defined('BASEPATH') or exit('No direct script access allowed');

class Approval extends CI_Controller
{
	private $MenuKode;

	public function __construct()
	{
		parent::__construct();

		if ($this->session->has_userdata('pengguna_id') == 0) :
			redirect(base_url('MainPage/Login'));
		endif;

		$this->load->model('M_Menu');
		$this->load->model('M_Depo');
		$this->load->model('M_Function');
		$this->load->model('M_Vrbl');
		$this->load->model('M_Approval');
	}

	public function ApprovalProses()
	{
		$dataAcc = $this->input->post("dataAcc");
		$dataReject = $this->input->post("dataReject");
		$mode = $this->input->post("mode");

		if ($dataAcc == null && $dataReject == null) {
			$response = array(
				'message' => 'Harap pilih approval terlebih dahulu !!',
				'status' =>  0
			);
			echo json_encode($response);
			return false;
		}
		// var_dump($dataAcc);
		// return false;

		$this->db->trans_begin();

		$data = $this->M_Approval->ApprovalProses($dataAcc, $dataReject);

		if ($mode == 1) {
			foreach ($dataAcc as $key => $value) {
				$this->M_Approval->updateTrKonversiSKU($value['tr_konversi_sku_id'], $value['gudang'], $value['checker']);
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = array(
				'message' => 'Failed create data !!',
				'status' =>  0
			);
			echo json_encode($response);
			return false;
		} else {
			$this->db->trans_commit();
			$response = array(
				'message' => 'success',
				'status' =>  1
			);
			echo json_encode($response);
			return false;
		}

		// if ($data == 1) {
		// 	$response = array(
		// 		'message' => 'success',
		// 		'status' =>  1
		// 	);
		// } else {
		// 	//respone == 0
		// 	$response = array(
		// 		'message' => 'Failed create data !!',
		// 		'status' =>  0
		// 	);
		// }
		// echo json_encode($response);
	}

	public function GetDataApproval()
	{
		$data = $this->M_Approval->Get_DataApproval();
		// var_dump($this->session->userdata('karyawan_level_id'));
		// return false;
		header('Content-Type: application/json');

		$data = json_encode($data);

		echo $data;
	}

	public function getApprovalByApprovalSettingId()
	{
		// $approval_setting_id = $this->input->post("approval_setting_id");
		$approval_setting_id = $this->input->post("approval_setting_id");
		$data = $this->M_Approval->getApprovalByApprovalSettingId($approval_setting_id);
		$data1 = $this->M_Approval->getApprovalSettingByApprovalSettingId($approval_setting_id);

		if ($data1['approval_setting_parameter'] == 'APPRV_UNPACKREPACK_01') {
			$gudang = $this->M_Approval->GetGudang();
			$checker = $this->M_Approval->GetChecker();
		} else {
			$gudang = 400;
			$checker = 400;
		}

		// var_dump($data);
		// return false;
		header('Content-Type: application/json');

		$response = [
			'data' => $data,
			'gudang' => $gudang,
			'checker' => $checker,
		];

		$data = json_encode($response);

		echo $data;
	}
	public function getHistoryApproval()
	{
		$approval_reff_dokumen_kode = $this->input->get("approval_reff_dokumen_kode");
		$data = $this->M_Approval->getHistoryApproval($approval_reff_dokumen_kode);
		// var_dump($data);
		// return false;
		header('Content-Type: application/json');

		$data = json_encode($data);

		echo $data;
	}


	public function getApprovalByDocumentId()
	{
		$dokumen_id = $this->input->post('dokumen_id');
		$data = $this->M_Approval->getApprovalByDocumentId($dokumen_id);
		// var_dump($data);
		// return false;
		header('Content-Type: application/json');

		$data = json_encode($data);

		echo $data;
	}

	public function AnggaranMenu()
	{
		$data = array();

		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);

		$date = date("dmY");

		$data['rangeYear'] = range(date('Y') - 5, date('Y') + 5);

		// $data['listDoBatch'] = $this->M_PengirimanBarang->getListDoBatchPick();
		// $data['listPickOrder'] = $this->M_PengirimanBarang->getListPickOrder();
		// var_dump($data);return false;
		// $data['listPicking'] = $this->M_PengirimanBarang->getDataPickingList();
		// $data['listLayanan'] = $this->M_PengirimanBarang->getListLayanan();
		// $data['listPengiriman'] = $this->M_PengirimanBarang->getListPengiriman();
		// $data['listArea'] = $this->M_PengirimanBarang->getArea();

		// $data['listTipeDO'] = $this->M_PengirimanBarang->getTipeDO();

		if ($data['Menu_Access']['R'] != 1) {
			redirect(base_url('MainPage/Index'));
			exit();
		}



		$data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		// Kebutuhan Authority Menu
		$this->session->set_userdata('MenuLink', ltrim(current_url(), base_url()));

		$data['css_files'] = array(
			Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
			Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
			Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

			Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/components/button.min.css',
			Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/components/card.min.css',
			Get_Assets_Url() . 'assets/css/custom/Semantic-UI-master/dist/components/image.min.css',

			//Get_Assets_Url() . 'assets/css/bootstrap-switch.min.css',
			Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
			Get_Assets_Url() . 'assets/css/custom/scrollbar.css',
			Get_Assets_Url() . 'assets/css/buttondesign.css',

			Get_Assets_Url() . 'assets/css/clock.css'
			//Get_Assets_Url() . 'assets/css/bootstrap-input-spinner.css'
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
		$data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');

		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('master/Barjas/AnggaranMenu', $data);
		$this->load->view('layouts/sidebar_footer', $data);

		$this->load->view('master/Barjas/S_AnggaranMenu', $data);
	}
}
