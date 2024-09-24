<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class DeliveryOrderDraft extends ParentController
{

  private $MenuKode;

  public function __construct()
  {
    parent::__construct();

    if ($this->session->has_userdata('pengguna_id') == 0) :
      redirect(base_url('MainPage/Login'));
    endif;

    $this->depo_id = $this->session->userdata('depo_id');

    $this->MenuKode = "130005002";
    $this->load->model('WMS/M_PickList', 'M_PickList');
    $this->load->model('WMS/M_DeliveryOrderDraft', 'M_DeliveryOrderDraft');
    $this->load->model('WMS/M_DeliveryOrderDetailDraft', 'M_DeliveryOrderDetailDraft');
    $this->load->model('M_ClientPt');
    $this->load->model('M_Area');
    $this->load->model('M_StatusProgress');
    $this->load->model('M_SKU');
    $this->load->model('M_Principle');
    $this->load->model('M_TipeDeliveryOrder');

    $this->load->helper(array('form', 'url'));
    $this->load->library(['form_validation']);
  }

  public function index()
  {

    $data = array();

    $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
    if ($data['Menu_Access']['R'] != 1) {
      redirect(base_url('MainPage'));
      exit();
    }

    $data['Checker'] = $this->M_PemusnahanDraft->Get_Checker();
    $data['Gudang'] = $this->M_PemusnahanDraft->Get_Gudang();
    $data['TipeTransaksi'] = $this->M_PemusnahanDraft->Get_TipeTransaksi();
    $data['Principle'] = $this->M_PemusnahanDraft->Get_Principle();

    $data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
    $data['Ses_UserName'] = $this->session->userdata('pengguna_username');

    $data['Title'] = Get_Title_Name();
    $data['Copyright'] = Get_Copyright_Name();

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
    $this->load->view('WMS/DeliveryOrderDraft/index', $data);
    $this->load->view('layouts/sidebar_footer', $query);

    // $this->load->view('master/S_GlobalVariable', $data);
    $this->load->view('WMS/DeliveryOrderDraft/script', $data);
  }

  public function create()
  {

    $this->load->model('M_Menu');
    $this->load->model('M_ClientWms');

    $data = array();

    $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
    if ($data['Menu_Access']['R'] != 1) {
      redirect(base_url('MainPage'));
      exit();
    }

    $data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));
    $data['Ses_UserName'] = $this->session->userdata('pengguna_username');

    $data['Title'] = Get_Title_Name();
    $data['Copyright'] = Get_Copyright_Name();

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

    $data['clientwms'] = $this->M_ClientWms->findAll();
    $data['statuses'] = $this->M_StatusProgress->findAllByCriteria(['status_progress_is_aktif' => '1', 'status_progress_modul' => $this->M_StatusProgress::MODUL_DO_DRAFT]);
    $data['types'] = $this->M_TipeDeliveryOrder->findManyByCriteria([['colName' => 'tipe_delivery_order_id', 'colValue' => $this->M_TipeDeliveryOrder->getExceptionId(), 'type' => 'notin']]);
    $data['areas'] = $this->M_Area->findManyByCriteria([['colName' => 'area_is_aktif', 'colValue' => '1', 'type' => 'specific',]]);

    $query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

    $query['Ses_UserName'] = $this->session->userdata('pengguna_username');

    $query['Title'] = Get_Title_Name();
    $query['Copyright'] = Get_Copyright_Name();


    // Kebutuhan Authority Menu 
    $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

    $this->load->view('layouts/sidebar_header', $query);
    $this->load->view('WMS/DeliveryOrderDraft/form', $data);
    $this->load->view('layouts/sidebar_footer', $query);

    // $this->load->view('master/S_GlobalVariable', $data);
    $this->load->view('WMS/DeliveryOrderDraft/script', $data);
  }

  public function GetSelectedSKU()
  {
    $sku_id = $this->input->post('sku_id');

    $data = $this->M_DeliveryOrderDraft->GetSelectedSKU($sku_id);

    echo json_encode($data);
  }

  public function GetFactoryByTypePelayanan()
  {
    $perusahaan = $this->input->post('perusahaan');
    $tipe_pembayaran = $this->input->post('tipe_pembayaran');
    $tipe_layanan = $this->input->post('tipe_layanan');

    $data = $this->M_DeliveryOrderDraft->GetFactoryByTypePelayanan($perusahaan, $tipe_pembayaran, $tipe_layanan);

    echo json_encode($data);
  }

  public function GetCustomerByTypePelayanan()
  {
    $perusahaan = $this->input->post('perusahaan');
    $tipe_pembayaran = $this->input->post('tipe_pembayaran');
    $tipe_layanan = $this->input->post('tipe_layanan');

    $data = $this->M_DeliveryOrderDraft->GetCustomerByTypePelayanan($perusahaan, $tipe_pembayaran, $tipe_layanan);

    echo json_encode($data);
  }

  public function search_filter_chosen_sku()
  {
    $client_pt = $this->input->post('client_pt');
    $tipe_pembayaran = $this->input->post('tipe_pembayaran');
    $brand = $this->input->post('brand');
    $principle = $this->input->post('principle');
    $sku_induk = $this->input->post('sku_induk');
    $sku_nama_produk = $this->input->post('sku_nama_produk');
    $sku_kemasan = $this->input->post('sku_kemasan');
    $sku_satuan = $this->input->post('sku_satuan');

    $data = $this->M_DeliveryOrderDraft->search_filter_chosen_sku($client_pt, $tipe_pembayaran, $brand, $principle, $sku_induk, $sku_nama_produk, $sku_kemasan, $sku_satuan);

    echo json_encode($data);
  }
}
