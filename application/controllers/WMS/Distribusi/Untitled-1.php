<?php

class DeliveryOrderDraft extends CI_Controller
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

        $this->MenuKode = "135002000";
        $this->load->model('WMS/M_PickList', 'M_PickList');
        $this->load->model('WMS/M_DeliveryOrderDraft', 'M_DeliveryOrderDraft');
        $this->load->model('WMS/M_DeliveryOrder', 'M_DeliveryOrder');
        $this->load->model('WMS/M_DeliveryOrderDetailDraft', 'M_DeliveryOrderDetailDraft');
        $this->load->model('WMS/M_PersetujuanPembongkaranBarang', 'M_PersetujuanPembongkaranBarang');
        $this->load->model('M_ClientPt');
        $this->load->model('M_Area');
        $this->load->model('M_StatusProgress');
        $this->load->model('M_SKU');
        $this->load->model('M_Principle');
        $this->load->model('M_TipeDeliveryOrder');
        $this->load->model('M_AutoGen');
        $this->load->model('M_Vrbl');
    }

    public function index()
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
        $data['TipePelayanan'] = $this->M_DeliveryOrderDraft->GetTipeLayanan();
        $data['TipeDeliveryOrder'] = $this->M_DeliveryOrderDraft->GetTipeDeliveryOrder();
        $data['Status'] = $this->M_DeliveryOrderDraft->GetStatusProgress();
        $data['areas'] = $this->db->select("area_id, area_nama")->from('area')->where("area_is_aktif", 1)->where("area_nama !=", '')->order_by("area_nama", "ASC")->get()->result();
        $data['Perusahaan'] = $this->M_PersetujuanPembongkaranBarang->GetPerusahaan();
        $data['Principle'] = $this->M_PersetujuanPembongkaranBarang->GetPrinciple();
        $data['TipeKonversi'] = $this->M_PersetujuanPembongkaranBarang->GetTipeKonversi();
        $data['Gudang'] = $this->M_PersetujuanPembongkaranBarang->GetGudang();

        $tr_konversi_sku_id = $this->M_Vrbl->Get_NewID();
        $data['tr_konversi_sku_id'] = $tr_konversi_sku_id[0]['NEW_ID'];

        $data['act'] = "index";

        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $this->load->view('layouts/sidebar_header', $query);
        $this->load->view('WMS/DeliveryOrderDraft/index', $data);
        $this->load->view('layouts/sidebar_footer', $query);
        $this->load->view('master/S_GlobalVariable', $query);
        $this->load->view('WMS/DeliveryOrderDraft/script', $data);
    }

    public function create()
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

        $data['Lokasi'] = $this->M_DeliveryOrderDraft->GetLokasi();

        $data['Bulan'] = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
        $data['Perusahaan'] = $this->M_DeliveryOrderDraft->GetPerusahaan();
        $data['TipePelayanan'] = $this->M_DeliveryOrderDraft->GetTipeLayanan();
        $data['TipeDeliveryOrder'] = $this->M_DeliveryOrderDraft->GetTipeDeliveryOrder();
        $data['Area'] = $this->M_DeliveryOrderDraft->GetArea();
        $data['act'] = "add";

        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $this->load->view('layouts/sidebar_header', $query);
        $this->load->view('WMS/DeliveryOrderDraft/form', $data);
        $this->load->view('layouts/sidebar_footer', $query);
        $this->load->view('master/S_GlobalVariable', $query);
        $this->load->view('WMS/DeliveryOrderDraft/script', $data);
    }

    public function edit()
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
        $data['Lokasi'] = $this->M_DeliveryOrderDraft->GetLokasi();
        $data['DOHeader'] = $this->M_DeliveryOrderDraft->GetDeliveryOrderDraftHeaderById($id);
        $data['DODetail'] = $this->M_DeliveryOrderDraft->GetDeliveryOrderDraftDetailById($id);
        $data['Perusahaan'] = $this->M_DeliveryOrderDraft->GetPerusahaan();
        $data['TipePelayanan'] = $this->M_DeliveryOrderDraft->GetTipeLayanan();
        $data['TipeDeliveryOrder'] = $this->M_DeliveryOrderDraft->GetTipeDeliveryOrder();
        $data['Area'] = $this->M_DeliveryOrderDraft->GetArea();
        $data['act'] = "edit";

        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $this->load->view('layouts/sidebar_header', $query);
        $this->load->view('WMS/DeliveryOrderDraft/edit', $data);
        $this->load->view('layouts/sidebar_footer', $query);
        $this->load->view('master/S_GlobalVariable', $query);
        $this->load->view('WMS/DeliveryOrderDraft/script', $data);
    }

    public function DOKirimUlang()
    {
        $this->load->model('M_Menu');

        $id = $this->input->get('delivery_order_id');

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
        $data['Lokasi'] = $this->M_DeliveryOrderDraft->GetLokasi();
        $data['DOHeader'] = $this->M_DeliveryOrder->GetDeliveryOrderHeaderById($id);
        $data['DODetail'] = $this->M_DeliveryOrder->GetDeliveryOrderDetailById($id);
        $data['Perusahaan'] = $this->M_DeliveryOrderDraft->GetPerusahaan();
        $data['TipePelayanan'] = $this->M_DeliveryOrderDraft->GetTipeLayanan();
        $data['TipeDeliveryOrder'] = $this->M_DeliveryOrderDraft->GetTipeDeliveryOrder();
        $data['Area'] = $this->M_DeliveryOrderDraft->GetArea();
        $data['act'] = "kirim ulang";

        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $this->load->view('layouts/sidebar_header', $query);
        $this->load->view('WMS/DeliveryOrderDraft/DOKirimUlang', $data);
        $this->load->view('layouts/sidebar_footer', $query);
        $this->load->view('master/S_GlobalVariable', $query);
        $this->load->view('WMS/DeliveryOrderDraft/script', $data);
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
        $data['Lokasi'] = $this->M_DeliveryOrderDraft->GetLokasi();
        $data['DOHeader'] = $this->M_DeliveryOrderDraft->GetDeliveryOrderDraftHeaderById($id);
        $data['DODetail'] = $this->M_DeliveryOrderDraft->GetDeliveryOrderDraftDetailById($id);
        $data['Perusahaan'] = $this->M_DeliveryOrderDraft->GetPerusahaan();
        $data['TipePelayanan'] = $this->M_DeliveryOrderDraft->GetTipeLayanan();
        $data['TipeDeliveryOrder'] = $this->M_DeliveryOrderDraft->GetTipeDeliveryOrder();
        $data['Area'] = $this->M_DeliveryOrderDraft->GetArea();
        $data['act'] = "detail";

        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $this->load->view('layouts/sidebar_header', $query);
        $this->load->view('WMS/DeliveryOrderDraft/detail', $data);
        $this->load->view('layouts/sidebar_footer', $query);
        $this->load->view('master/S_GlobalVariable', $query);
        $this->load->view('WMS/DeliveryOrderDraft/script', $data);
    }

    public function GetDeliveryOrderDraftById()
    {
        $id = $this->input->post('arr_do_draft');

        $data['DOHeader'] = $this->M_DeliveryOrderDraft->GetDeliveryOrderDraftHeaderByListId($id);
        // $data['DODetail'] = $this->M_DeliveryOrderDraft->GetDeliveryOrderDraftDetailByListId($id);

        echo json_encode($data);
    }

    public function GetDeliveryOrderDraftDetailByListId()
    {
        $id = $this->input->post('id');

        // $data['DOHeader'] = $this->M_DeliveryOrderDraft->GetDeliveryOrderDraftHeaderByListId($id);
        $data['DODetail'] = $this->M_DeliveryOrderDraft->GetDeliveryOrderDraftDetailByListId($id);

        echo json_encode($data);
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
        $nama = $this->input->post('nama');
        $alamat = $this->input->post('alamat');
        $telp = $this->input->post('telp');
        $area = $this->input->post('area');

        $data = $this->M_DeliveryOrderDraft->GetFactoryByTypePelayanan($perusahaan, $tipe_pembayaran, $tipe_layanan, $nama, $alamat, $telp, $area);

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

        $data = $this->M_DeliveryOrderDraft->GetCustomerByTypePelayanan($perusahaan, $tipe_pembayaran, $tipe_layanan, $nama, $alamat, $telp, $area);

        echo json_encode($data);
    }

    public function GetSelectedPrinciple()
    {
        $customer = $this->input->post('customer');
        $perusahaan = $this->input->post('perusahaan');

        $data = $this->M_DeliveryOrderDraft->GetSelectedPrinciple($customer, $perusahaan);

        echo json_encode($data);
    }

    public function GetSelectedCustomer()
    {
        $customer = $this->input->post('customer');
        $perusahaan = $this->input->post('perusahaan');

        $data = $this->M_DeliveryOrderDraft->GetSelectedCustomer($customer, $perusahaan);

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

        $data = $this->M_DeliveryOrderDraft->search_filter_chosen_sku($client_pt, $perusahaan, $tipe_pembayaran, $brand, $principle, $sku_induk, $sku_nama_produk, $sku_kemasan, $sku_satuan);

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

        $data = $this->M_DeliveryOrderDraft->search_filter_chosen_sku_by_pabrik($client_pt, $perusahaan, $tipe_pembayaran, $brand, $principle, $sku_induk, $sku_nama_produk, $sku_kemasan, $sku_satuan);

        echo json_encode($data);
    }

    public function insert_delivery_order_draft()
    {
        $delivery_order_draft_id = $this->input->post('delivery_order_draft_id');
        $sales_order_id = $this->input->post('sales_order_id');
        $delivery_order_draft_kode = $this->input->post('delivery_order_draft_kode');
        $delivery_order_draft_yourref = $this->input->post('delivery_order_draft_yourref');
        $client_wms_id = $this->input->post('client_wms_id');
        $delivery_order_draft_tgl_buat_do = $this->input->post('delivery_order_draft_tgl_buat_do');
        $delivery_order_draft_tgl_expired_do = $this->input->post('delivery_order_draft_tgl_expired_do');
        $delivery_order_draft_tgl_surat_jalan = $this->input->post('delivery_order_draft_tgl_surat_jalan');
        $delivery_order_draft_tgl_rencana_kirim = $this->input->post('delivery_order_draft_tgl_rencana_kirim');
        $delivery_order_draft_tgl_aktual_kirim = $this->input->post('delivery_order_draft_tgl_aktual_kirim');
        $delivery_order_draft_keterangan = $this->input->post('delivery_order_draft_keterangan');
        $delivery_order_draft_status = $this->input->post('delivery_order_draft_status');
        $delivery_order_draft_is_prioritas = $this->input->post('delivery_order_draft_is_prioritas');
        $delivery_order_draft_is_need_packing = $this->input->post('delivery_order_draft_is_need_packing');
        $delivery_order_draft_tipe_layanan = $this->input->post('delivery_order_draft_tipe_layanan');
        $delivery_order_draft_tipe_pembayaran = $this->input->post('delivery_order_draft_tipe_pembayaran');
        $delivery_order_draft_sesi_pengiriman = $this->input->post('delivery_order_draft_sesi_pengiriman');
        $delivery_order_draft_request_tgl_kirim = $this->input->post('delivery_order_draft_request_tgl_kirim');
        $delivery_order_draft_request_jam_kirim = $this->input->post('delivery_order_draft_request_jam_kirim');
        $tipe_pengiriman_id = $this->input->post('tipe_pengiriman_id');
        $nama_tipe = $this->input->post('nama_tipe');
        $confirm_rate = $this->input->post('confirm_rate');
        $delivery_order_draft_reff_id = $this->input->post('delivery_order_draft_reff_id');
        $delivery_order_draft_reff_no = $this->input->post('delivery_order_draft_reff_no');
        $delivery_order_draft_total = $this->input->post('delivery_order_draft_total');
        $unit_mandiri_id = $this->input->post('unit_mandiri_id');
        $depo_id = $this->input->post('depo_id');
        $client_pt_id = $this->input->post('client_pt_id');
        $delivery_order_draft_kirim_nama = $this->input->post('delivery_order_draft_kirim_nama');
        $delivery_order_draft_kirim_alamat = $this->input->post('delivery_order_draft_kirim_alamat');
        $delivery_order_draft_kirim_telp = $this->input->post('delivery_order_draft_kirim_telp');
        $delivery_order_draft_kirim_provinsi = $this->input->post('delivery_order_draft_kirim_provinsi');
        $delivery_order_draft_kirim_kota = $this->input->post('delivery_order_draft_kirim_kota');
        $delivery_order_draft_kirim_kecamatan = $this->input->post('delivery_order_draft_kirim_kecamatan');
        $delivery_order_draft_kirim_kelurahan = $this->input->post('delivery_order_draft_kirim_kelurahan');
        $delivery_order_draft_kirim_kodepos = $this->input->post('delivery_order_draft_kirim_kodepos');
        $delivery_order_draft_kirim_area = $this->input->post('delivery_order_draft_kirim_area');
        $delivery_order_draft_kirim_invoice_pdf = $this->input->post('delivery_order_draft_kirim_invoice_pdf');
        $delivery_order_draft_kirim_invoice_dir = $this->input->post('delivery_order_draft_kirim_invoice_dir');
        $pabrik_id = $this->input->post('pabrik_id');
        $delivery_order_draft_ambil_nama = $this->input->post('delivery_order_draft_ambil_nama');
        $delivery_order_draft_ambil_alamat = $this->input->post('delivery_order_draft_ambil_alamat');
        $delivery_order_draft_ambil_telp = $this->input->post('delivery_order_draft_ambil_telp');
        $delivery_order_draft_ambil_provinsi = $this->input->post('delivery_order_draft_ambil_provinsi');
        $delivery_order_draft_ambil_kota = $this->input->post('delivery_order_draft_ambil_kota');
        $delivery_order_draft_ambil_kecamatan = $this->input->post('delivery_order_draft_ambil_kecamatan');
        $delivery_order_draft_ambil_kelurahan = $this->input->post('delivery_order_draft_ambil_kelurahan');
        $delivery_order_draft_ambil_kodepos = $this->input->post('delivery_order_draft_ambil_kodepos');
        $delivery_order_draft_ambil_area = $this->input->post('delivery_order_draft_ambil_area');
        $delivery_order_draft_update_who = $this->input->post('delivery_order_draft_update_who');
        $delivery_order_draft_update_tgl = $this->input->post('delivery_order_draft_update_tgl');
        $delivery_order_draft_approve_who = $this->input->post('delivery_order_draft_approve_who');
        $delivery_order_draft_approve_tgl = $this->input->post('delivery_order_draft_approve_tgl');
        $delivery_order_draft_reject_who = $this->input->post('delivery_order_draft_reject_who');
        $delivery_order_draft_reject_tgl = $this->input->post('delivery_order_draft_reject_tgl');
        $delivery_order_draft_reject_reason = $this->input->post('delivery_order_draft_reject_reason');
        $tipe_delivery_order_id = $this->input->post('tipe_delivery_order_id');
        $detail = $this->input->post('detail');
        $delivery_order_draft_input_pembayaran_tunai = $this->input->post('delivery_order_draft_input_pembayaran_tunai');
        $file = $this->input->post('file');

        if ($file == "undefined") {
            $dod_id = $this->M_Vrbl->Get_NewID();
            $dod_id = $dod_id[0]['NEW_ID'];

            //generate kode
            $date_now = date('Y-m-d h:i:s');
            $param =  'KODE_DOD';
            $vrbl = $this->M_Vrbl->Get_Kode($param);
            $prefix = $vrbl->vrbl_kode;
            // get prefik depo
            $depo_id = $this->session->userdata('depo_id');
            $depoPrefix = $this->M_DeliveryOrderDraft->getDepoPrefix($depo_id);
            $unit = $depoPrefix->depo_kode_preffix;
            $generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

            // $this->db->trans_begin();

            //insert ke tr_pemusnahan_stok_draft
            $result = $this->M_DeliveryOrderDraft->insert_delivery_order_draft(
                $dod_id,
                $sales_order_id,
                $generate_kode,
                $delivery_order_draft_yourref,
                $client_wms_id,
                $delivery_order_draft_tgl_buat_do,
                $delivery_order_draft_tgl_expired_do,
                $delivery_order_draft_tgl_surat_jalan,
                $delivery_order_draft_tgl_rencana_kirim,
                $delivery_order_draft_tgl_aktual_kirim,
                $delivery_order_draft_keterangan,
                $delivery_order_draft_status,
                $delivery_order_draft_is_prioritas,
                $delivery_order_draft_is_need_packing,
                $delivery_order_draft_tipe_layanan,
                $delivery_order_draft_tipe_pembayaran,
                $delivery_order_draft_sesi_pengiriman,
                $delivery_order_draft_request_tgl_kirim,
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
                null
            );
        } else {
            $uploadDirectory = "assets/images/uploads/Invoice/";
            $fileExtensionAllowed = ['jpeg', 'jpg', 'png', 'gif', 'JPG', 'JPEG', 'GIF', 'pdf', 'PDF', 'doc', 'DOC', 'docx', 'DOCX', 'xlsx', 'csv', 'xls'];
            $fileName = $_FILES['file']['name'];
            $fileSize = $_FILES['file']['size'];
            $fileTmpName = $_FILES['file']['tmp_name'];
            $fileExtension = strtolower(explode(".", $fileName)[1]);
            $name_file = 'invoice-' . time() . '.' . $fileExtension;

            if ($fileSize > 1024000) {
                echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan! Ukuran file maks 1mb'));
            } else {
                if (!in_array($fileExtension, $fileExtensionAllowed)) {
                    echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan! File Attactment tidak sesuai ketentuan'));
                } else {
                    $uploadPath = $uploadDirectory . $name_file;
                    $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
                    if (!$didUpload) {
                        echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan! Terjadi kesalahan pada server'));
                    } else {
                        $dod_id = $this->M_Vrbl->Get_NewID();
                        $dod_id = $dod_id[0]['NEW_ID'];

                        //generate kode
                        $date_now = date('Y-m-d h:i:s');
                        $param =  'KODE_DOD';
                        $vrbl = $this->M_Vrbl->Get_Kode($param);
                        $prefix = $vrbl->vrbl_kode;
                        // get prefik depo
                        $depo_id = $this->session->userdata('depo_id');
                        $depoPrefix = $this->M_DeliveryOrderDraft->getDepoPrefix($depo_id);
                        $unit = $depoPrefix->depo_kode_preffix;
                        $generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

                        // $this->db->trans_begin();

                        //insert ke tr_pemusnahan_stok_draft
                        $result = $this->M_DeliveryOrderDraft->insert_delivery_order_draft(
                            $dod_id,
                            $sales_order_id,
                            $generate_kode,
                            $delivery_order_draft_yourref,
                            $client_wms_id,
                            $delivery_order_draft_tgl_buat_do,
                            $delivery_order_draft_tgl_expired_do,
                            $delivery_order_draft_tgl_surat_jalan,
                            $delivery_order_draft_tgl_rencana_kirim,
                            $delivery_order_draft_tgl_aktual_kirim,
                            $delivery_order_draft_keterangan,
                            $delivery_order_draft_status,
                            $delivery_order_draft_is_prioritas,
                            $delivery_order_draft_is_need_packing,
                            $delivery_order_draft_tipe_layanan,
                            $delivery_order_draft_tipe_pembayaran,
                            $delivery_order_draft_sesi_pengiriman,
                            $delivery_order_draft_request_tgl_kirim,
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
                            $name_file
                        );
                    }
                }
            }
        }

        if (!$result) {
            echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan!'));
        } else {
            $data_detail = json_decode($detail);
            //insert ke tr_pemusnahan_stok_detail_draft
            foreach ($data_detail as $key => $value) {
                $dod_id_ = $this->M_Vrbl->Get_NewID();
                $dod_id_ = $dod_id_[0]['NEW_ID'];
                $this->M_DeliveryOrderDraft->insert_delivery_order_detail_draft($dod_id_, $dod_id, $value);
            }

            echo json_encode(array('status' => true, 'message' => 'Data berhasil disimpan!'));
        }
    }

    public function update_delivery_order_draft()
    {
        $delivery_order_draft_id = $this->input->post('delivery_order_draft_id');
        $sales_order_id = $this->input->post('sales_order_id');
        $delivery_order_draft_kode = $this->input->post('delivery_order_draft_kode');
        $delivery_order_draft_yourref = $this->input->post('delivery_order_draft_yourref');
        $client_wms_id = $this->input->post('client_wms_id');
        $delivery_order_draft_tgl_buat_do = $this->input->post('delivery_order_draft_tgl_buat_do');
        $delivery_order_draft_tgl_expired_do = $this->input->post('delivery_order_draft_tgl_expired_do');
        $delivery_order_draft_tgl_surat_jalan = $this->input->post('delivery_order_draft_tgl_surat_jalan');
        $delivery_order_draft_tgl_rencana_kirim = $this->input->post('delivery_order_draft_tgl_rencana_kirim');
        $delivery_order_draft_tgl_aktual_kirim = $this->input->post('delivery_order_draft_tgl_aktual_kirim');
        $delivery_order_draft_keterangan = $this->input->post('delivery_order_draft_keterangan');
        $delivery_order_draft_status = $this->input->post('delivery_order_draft_status');
        $delivery_order_draft_is_prioritas = $this->input->post('delivery_order_draft_is_prioritas');
        $delivery_order_draft_is_need_packing = $this->input->post('delivery_order_draft_is_need_packing');
        $delivery_order_draft_tipe_layanan = $this->input->post('delivery_order_draft_tipe_layanan');
        $delivery_order_draft_tipe_pembayaran = $this->input->post('delivery_order_draft_tipe_pembayaran');
        $delivery_order_draft_sesi_pengiriman = $this->input->post('delivery_order_draft_sesi_pengiriman');
        $delivery_order_draft_request_tgl_kirim = $this->input->post('delivery_order_draft_request_tgl_kirim');
        $delivery_order_draft_request_jam_kirim = $this->input->post('delivery_order_draft_request_jam_kirim');
        $tipe_pengiriman_id = $this->input->post('tipe_pengiriman_id');
        $nama_tipe = $this->input->post('nama_tipe');
        $confirm_rate = $this->input->post('confirm_rate');
        $delivery_order_draft_reff_id = $this->input->post('delivery_order_draft_reff_id');
        $delivery_order_draft_reff_no = $this->input->post('delivery_order_draft_reff_no');
        $delivery_order_draft_total = $this->input->post('delivery_order_draft_total');
        $unit_mandiri_id = $this->input->post('unit_mandiri_id');
        $depo_id = $this->input->post('depo_id');
        $client_pt_id = $this->input->post('client_pt_id');
        $delivery_order_draft_kirim_nama = $this->input->post('delivery_order_draft_kirim_nama');
        $delivery_order_draft_kirim_alamat = $this->input->post('delivery_order_draft_kirim_alamat');
        $delivery_order_draft_kirim_telp = $this->input->post('delivery_order_draft_kirim_telp');
        $delivery_order_draft_kirim_provinsi = $this->input->post('delivery_order_draft_kirim_provinsi');
        $delivery_order_draft_kirim_kota = $this->input->post('delivery_order_draft_kirim_kota');
        $delivery_order_draft_kirim_kecamatan = $this->input->post('delivery_order_draft_kirim_kecamatan');
        $delivery_order_draft_kirim_kelurahan = $this->input->post('delivery_order_draft_kirim_kelurahan');
        $delivery_order_draft_kirim_kodepos = $this->input->post('delivery_order_draft_kirim_kodepos');
        $delivery_order_draft_kirim_area = $this->input->post('delivery_order_draft_kirim_area');
        $delivery_order_draft_kirim_invoice_pdf = $this->input->post('delivery_order_draft_kirim_invoice_pdf');
        $delivery_order_draft_kirim_invoice_dir = $this->input->post('delivery_order_draft_kirim_invoice_dir');
        $pabrik_id = $this->input->post('pabrik_id');
        $delivery_order_draft_ambil_nama = $this->input->post('delivery_order_draft_ambil_nama');
        $delivery_order_draft_ambil_alamat = $this->input->post('delivery_order_draft_ambil_alamat');
        $delivery_order_draft_ambil_telp = $this->input->post('delivery_order_draft_ambil_telp');
        $delivery_order_draft_ambil_provinsi = $this->input->post('delivery_order_draft_ambil_provinsi');
        $delivery_order_draft_ambil_kota = $this->input->post('delivery_order_draft_ambil_kota');
        $delivery_order_draft_ambil_kecamatan = $this->input->post('delivery_order_draft_ambil_kecamatan');
        $delivery_order_draft_ambil_kelurahan = $this->input->post('delivery_order_draft_ambil_kelurahan');
        $delivery_order_draft_ambil_kodepos = $this->input->post('delivery_order_draft_ambil_kodepos');
        $delivery_order_draft_ambil_area = $this->input->post('delivery_order_draft_ambil_area');
        $delivery_order_draft_update_who = $this->input->post('delivery_order_draft_update_who');
        $delivery_order_draft_update_tgl = $this->input->post('delivery_order_draft_update_tgl');
        $delivery_order_draft_approve_who = $this->input->post('delivery_order_draft_approve_who');
        $delivery_order_draft_approve_tgl = $this->input->post('delivery_order_draft_approve_tgl');
        $delivery_order_draft_reject_who = $this->input->post('delivery_order_draft_reject_who');
        $delivery_order_draft_reject_tgl = $this->input->post('delivery_order_draft_reject_tgl');
        $delivery_order_draft_reject_reason = $this->input->post('delivery_order_draft_reject_reason');
        $tipe_delivery_order_id = $this->input->post('tipe_delivery_order_id');
        $detail = $this->input->post('detail');
        $delivery_order_draft_input_pembayaran_tunai = $this->input->post('delivery_order_draft_input_pembayaran_tunai');
        $file = $this->input->post('file');

        $area = $this->M_DeliveryOrderDraft->Get_area_by_nama($delivery_order_draft_kirim_area);

        if (isset($area->area_id)) {
            $area_id = $area->area_id;
            $this->M_DeliveryOrderDraft->update_area_client_pt($client_pt_id, $area_id);
        }

        if ($file == "undefined") {
            //insert ke tr_pemusnahan_stok_draft
            $result = $this->M_DeliveryOrderDraft->update_delivery_order_draft(
                $delivery_order_draft_id,
                $sales_order_id,
                $delivery_order_draft_kode,
                $delivery_order_draft_yourref,
                $client_wms_id,
                $delivery_order_draft_tgl_buat_do,
                $delivery_order_draft_tgl_expired_do,
                $delivery_order_draft_tgl_surat_jalan,
                $delivery_order_draft_tgl_rencana_kirim,
                $delivery_order_draft_tgl_aktual_kirim,
                $delivery_order_draft_keterangan,
                $delivery_order_draft_status,
                $delivery_order_draft_is_prioritas,
                $delivery_order_draft_is_need_packing,
                $delivery_order_draft_tipe_layanan,
                $delivery_order_draft_tipe_pembayaran,
                $delivery_order_draft_sesi_pengiriman,
                $delivery_order_draft_request_tgl_kirim,
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
                null
            );
        } else {
            $uploadDirectory = "assets/images/uploads/Invoice/";
            $fileExtensionAllowed = ['jpeg', 'jpg', 'png', 'gif', 'JPG', 'JPEG', 'GIF', 'pdf', 'PDF', 'doc', 'DOC', 'docx', 'DOCX', 'xlsx', 'csv', 'xls'];
            $fileName = $_FILES['file']['name'];
            $fileSize = $_FILES['file']['size'];
            $fileTmpName = $_FILES['file']['tmp_name'];
            $fileExtension = strtolower(explode(".", $fileName)[1]);
            $name_file = 'invoice-' . time() . '.' . $fileExtension;

            if ($fileSize > 1024000) {
                echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan! Ukuran file maks 1mb'));
            } else {
                if (!in_array($fileExtension, $fileExtensionAllowed)) {
                    echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan! File Attactment tidak sesuai ketentuan'));
                } else {
                    $uploadPath = $uploadDirectory . $name_file;
                    $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
                    if (!$didUpload) {
                        echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan! Terjadi kesalahan pada server'));
                    } else {
                        //insert ke tr_pemusnahan_stok_draft
                        $result = $this->M_DeliveryOrderDraft->update_delivery_order_draft(
                            $delivery_order_draft_id,
                            $sales_order_id,
                            $delivery_order_draft_kode,
                            $delivery_order_draft_yourref,
                            $client_wms_id,
                            $delivery_order_draft_tgl_buat_do,
                            $delivery_order_draft_tgl_expired_do,
                            $delivery_order_draft_tgl_surat_jalan,
                            $delivery_order_draft_tgl_rencana_kirim,
                            $delivery_order_draft_tgl_aktual_kirim,
                            $delivery_order_draft_keterangan,
                            $delivery_order_draft_status,
                            $delivery_order_draft_is_prioritas,
                            $delivery_order_draft_is_need_packing,
                            $delivery_order_draft_tipe_layanan,
                            $delivery_order_draft_tipe_pembayaran,
                            $delivery_order_draft_sesi_pengiriman,
                            $delivery_order_draft_request_tgl_kirim,
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
                            $name_file
                        );
                    }
                }
            }
        }

        if (!$result) {
            echo json_encode(array('status' => false, 'message' => 'Data gagal disimpan!'));
        } else {
            //insert ke tr_pemusnahan_stok_detail_draft
            $this->M_DeliveryOrderDraft->delete_delivery_order_detail_draft($delivery_order_draft_id);
            $data_detail = json_decode($detail);
            foreach ($data_detail as $key => $value) {
                $dod_id = $this->M_Vrbl->Get_NewID();
                $dod_id = $dod_id[0]['NEW_ID'];
                $this->M_DeliveryOrderDraft->insert_delivery_order_detail_draft($dod_id, $delivery_order_draft_id, $value);
            }

            echo json_encode(array('status' => true, 'message' => 'Data berhasil disimpan!'));
        }
    }

    public function confirm_delivery_order_draft()
    {
        $delivery_order_draft_id = $this->input->post('delivery_order_draft_id');
        $sales_order_id = $this->input->post('sales_order_id');
        $delivery_order_draft_kode = $this->input->post('delivery_order_draft_kode');
        $delivery_order_draft_yourref = $this->input->post('delivery_order_draft_yourref');
        $client_wms_id = $this->input->post('client_wms_id');
        $delivery_order_draft_tgl_buat_do = $this->input->post('delivery_order_draft_tgl_buat_do');
        $delivery_order_draft_tgl_expired_do = $this->input->post('delivery_order_draft_tgl_expired_do');
        $delivery_order_draft_tgl_surat_jalan = $this->input->post('delivery_order_draft_tgl_surat_jalan');
        $delivery_order_draft_tgl_rencana_kirim = $this->input->post('delivery_order_draft_tgl_rencana_kirim');
        $delivery_order_draft_tgl_aktual_kirim = $this->input->post('delivery_order_draft_tgl_aktual_kirim');
        $delivery_order_draft_keterangan = $this->input->post('delivery_order_draft_keterangan');
        $delivery_order_draft_status = $this->input->post('delivery_order_draft_status');
        $delivery_order_draft_is_prioritas = $this->input->post('delivery_order_draft_is_prioritas');
        $delivery_order_draft_is_need_packing = $this->input->post('delivery_order_draft_is_need_packing');
        $delivery_order_draft_tipe_layanan = $this->input->post('delivery_order_draft_tipe_layanan');
        $delivery_order_draft_tipe_pembayaran = $this->input->post('delivery_order_draft_tipe_pembayaran');
        $delivery_order_draft_sesi_pengiriman = $this->input->post('delivery_order_draft_sesi_pengiriman');
        $delivery_order_draft_request_tgl_kirim = $this->input->post('delivery_order_draft_request_tgl_kirim');
        $delivery_order_draft_request_jam_kirim = $this->input->post('delivery_order_draft_request_jam_kirim');
        $tipe_pengiriman_id = $this->input->post('tipe_pengiriman_id');
        $nama_tipe = $this->input->post('nama_tipe');
        $confirm_rate = $this->input->post('confirm_rate');
        $delivery_order_draft_reff_id = $this->input->post('delivery_order_draft_reff_id');
        $delivery_order_draft_reff_no = $this->input->post('delivery_order_draft_reff_no');
        $delivery_order_draft_total = $this->input->post('delivery_order_draft_total');
        $unit_mandiri_id = $this->input->post('unit_mandiri_id');
        $depo_id = $this->input->post('depo_id');
        $client_pt_id = $this->input->post('client_pt_id');
        $delivery_order_draft_kirim_nama = $this->input->post('delivery_order_draft_kirim_nama');
        $delivery_order_draft_kirim_alamat = $this->input->post('delivery_order_draft_kirim_alamat');
        $delivery_order_draft_kirim_telp = $this->input->post('delivery_order_draft_kirim_telp');
        $delivery_order_draft_kirim_provinsi = $this->input->post('delivery_order_draft_kirim_provinsi');
        $delivery_order_draft_kirim_kota = $this->input->post('delivery_order_draft_kirim_kota');
        $delivery_order_draft_kirim_kecamatan = $this->input->post('delivery_order_draft_kirim_kecamatan');
        $delivery_order_draft_kirim_kelurahan = $this->input->post('delivery_order_draft_kirim_kelurahan');
        $delivery_order_draft_kirim_kodepos = $this->input->post('delivery_order_draft_kirim_kodepos');
        $delivery_order_draft_kirim_area = $this->input->post('delivery_order_draft_kirim_area');
        $delivery_order_draft_kirim_invoice_pdf = $this->input->post('delivery_order_draft_kirim_invoice_pdf');
        $delivery_order_draft_kirim_invoice_dir = $this->input->post('delivery_order_draft_kirim_invoice_dir');
        $pabrik_id = $this->input->post('pabrik_id');
        $delivery_order_draft_ambil_nama = $this->input->post('delivery_order_draft_ambil_nama');
        $delivery_order_draft_ambil_alamat = $this->input->post('delivery_order_draft_ambil_alamat');
        $delivery_order_draft_ambil_telp = $this->input->post('delivery_order_draft_ambil_telp');
        $delivery_order_draft_ambil_provinsi = $this->input->post('delivery_order_draft_ambil_provinsi');
        $delivery_order_draft_ambil_kota = $this->input->post('delivery_order_draft_ambil_kota');
        $delivery_order_draft_ambil_kecamatan = $this->input->post('delivery_order_draft_ambil_kecamatan');
        $delivery_order_draft_ambil_kelurahan = $this->input->post('delivery_order_draft_ambil_kelurahan');
        $delivery_order_draft_ambil_kodepos = $this->input->post('delivery_order_draft_ambil_kodepos');
        $delivery_order_draft_ambil_area = $this->input->post('delivery_order_draft_ambil_area');
        $delivery_order_draft_update_who = $this->input->post('delivery_order_draft_update_who');
        $delivery_order_draft_update_tgl = $this->input->post('delivery_order_draft_update_tgl');
        $delivery_order_draft_approve_who = $this->input->post('delivery_order_draft_approve_who');
        $delivery_order_draft_approve_tgl = $this->input->post('delivery_order_draft_approve_tgl');
        $delivery_order_draft_reject_who = $this->input->post('delivery_order_draft_reject_who');
        $delivery_order_draft_reject_tgl = $this->input->post('delivery_order_draft_reject_tgl');
        $delivery_order_draft_reject_reason = $this->input->post('delivery_order_draft_reject_reason');
        $tipe_delivery_order_id = $this->input->post('tipe_delivery_order_id');
        $is_promo = $this->input->post('is_promo');
        $is_canvas = $this->input->post('is_canvas');
        $detail = $this->input->post('detail');
        $delivery_order_draft_input_pembayaran_tunai = $this->input->post('delivery_order_draft_input_pembayaran_tunai');

        $konversi_abaikan_rollback = 0;

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

        $this->db->trans_begin();

        //insert ke tr_pemusnahan_stok_draft
        // $this->M_DeliveryOrderDraft->update_delivery_order_draft(
        // 	$delivery_order_draft_id,
        // 	$sales_order_id,
        // 	$delivery_order_draft_kode,
        // 	$delivery_order_draft_yourref,
        // 	$client_wms_id,
        // 	$delivery_order_draft_tgl_buat_do,
        // 	$delivery_order_draft_tgl_expired_do,
        // 	$delivery_order_draft_tgl_surat_jalan,
        // 	$delivery_order_draft_tgl_rencana_kirim,
        // 	$delivery_order_draft_tgl_aktual_kirim,
        // 	$delivery_order_draft_keterangan,
        // 	$delivery_order_draft_status,
        // 	$delivery_order_draft_is_prioritas,
        // 	$delivery_order_draft_is_need_packing,
        // 	$delivery_order_draft_tipe_layanan,
        // 	$delivery_order_draft_tipe_pembayaran,
        // 	$delivery_order_draft_sesi_pengiriman,
        // 	$delivery_order_draft_request_tgl_kirim,
        // 	$delivery_order_draft_request_jam_kirim,
        // 	$tipe_pengiriman_id,
        // 	$nama_tipe,
        // 	$confirm_rate,
        // 	$delivery_order_draft_reff_id,
        // 	$delivery_order_draft_reff_no,
        // 	$delivery_order_draft_total,
        // 	$unit_mandiri_id,
        // 	$depo_id,
        // 	$client_pt_id,
        // 	$delivery_order_draft_kirim_nama,
        // 	$delivery_order_draft_kirim_alamat,
        // 	$delivery_order_draft_kirim_telp,
        // 	$delivery_order_draft_kirim_provinsi,
        // 	$delivery_order_draft_kirim_kota,
        // 	$delivery_order_draft_kirim_kecamatan,
        // 	$delivery_order_draft_kirim_kelurahan,
        // 	$delivery_order_draft_kirim_kodepos,
        // 	$delivery_order_draft_kirim_area,
        // 	$delivery_order_draft_kirim_invoice_pdf,
        // 	$delivery_order_draft_kirim_invoice_dir,
        // 	$pabrik_id,
        // 	$delivery_order_draft_ambil_nama,
        // 	$delivery_order_draft_ambil_alamat,
        // 	$delivery_order_draft_ambil_telp,
        // 	$delivery_order_draft_ambil_provinsi,
        // 	$delivery_order_draft_ambil_kota,
        // 	$delivery_order_draft_ambil_kecamatan,
        // 	$delivery_order_draft_ambil_kelurahan,
        // 	$delivery_order_draft_ambil_kodepos,
        // 	$delivery_order_draft_ambil_area,
        // 	$delivery_order_draft_update_who,
        // 	$delivery_order_draft_update_tgl,
        // 	$delivery_order_draft_approve_who,
        // 	$delivery_order_draft_approve_tgl,
        // 	$delivery_order_draft_reject_who,
        // 	$delivery_order_draft_reject_tgl,
        // 	$delivery_order_draft_reject_reason,
        // 	$tipe_delivery_order_id,
        // 	$delivery_order_draft_input_pembayaran_tunai,
        // 	null
        // );

        $area = $this->M_DeliveryOrderDraft->Get_area_by_nama($delivery_order_draft_kirim_area);

        if ($is_canvas != "1") {

            if (isset($area->area_id)) {
                $area_id = $area->area_id;
                $this->M_DeliveryOrderDraft->update_area_client_pt($client_pt_id, $area_id);
            }
        }

        //delete ke tr_pemusnahan_stok_detail_draft
        $this->M_DeliveryOrderDraft->delete_delivery_order_detail_draft($delivery_order_draft_id);


        $lastKey = array_search(end($detail), $detail);

        foreach ($detail as $key => $value) {


            $dod_id = $this->M_Vrbl->Get_NewID();
            $dod_id = $dod_id[0]['NEW_ID'];

            $this->M_DeliveryOrderDraft->insert_confirm_delivery_order_detail_draft($dod_id, $delivery_order_draft_id, $value);

            // $sisaQty = $value['sku_qty'];
            $qtyDet = $value['sku_qty'];

            if ($tipe_delivery_order_id != "C5BE83E2-01E8-4E24-B766-26BB4158F2CD") {

                if ($qtyDet < 0) {
                    continue;
                    # code...
                }

                if ($value['sku_request_expdate'] == 0) {

                    //filter sku_stock berdasarkan client wms id, sku id
                    //ambil qty berdasarkan expired teratas

                    $sisaQty = $qtyDet;

                    $GetDataSKU = $this->M_DeliveryOrderDraft->GetDataSKUByReqNull($client_wms_id, $value['sku_id']);

                    if (count($GetDataSKU) == 0) {
                        $getNameSKU = $this->M_DeliveryOrderDraft->getNameSKUById($client_wms_id, $value['sku_id']);
                        // array_push($emptySKUInStock, ['type' => 203, 'data' => $getNameSKU]);

                        $sku_konversi_group = $this->M_DeliveryOrderDraft->getSKUGroup($value['sku_id']);
                        $getSKULevel = $this->M_DeliveryOrderDraft->getSKULevel($sku_konversi_group);

                        if (count($getSKULevel) > 0) {

                            foreach ($getSKULevel as $key => $val_sku_konvers) {
                                $dataSKUStockBySKUUpperLevel = $this->M_DeliveryOrderDraft->GetDataSKUByReqNull($client_wms_id, $val_sku_konvers['sku_id']);

                                if (count($dataSKUStockBySKUUpperLevel) > 0) {
                                    $cek_tr_konversi_sku_from_do = $this->M_DeliveryOrderDraft->cek_tr_konversi_sku_from_do($value['sku_id']);

                                    if (count($cek_tr_konversi_sku_from_do) == 0) {
                                        $tr_konversi_sku_from_do_id = $this->M_Vrbl->Get_NewID();
                                        $tr_konversi_sku_from_do_id = $tr_konversi_sku_from_do_id[0]['NEW_ID'];

                                        $depo_id = $this->session->userdata('depo_id');
                                        $tr_konversi_sku_from_do_tanggal = date('Y-m-d H:i:s');
                                        $sku_id = $value['sku_id'];
                                        $sku_qty = $value['sku_qty'];
                                        $tr_konversi_sku_from_do_status = "";
                                        $tr_konversi_sku_from_do_tgl_create = date('Y-m-d H:i:s');
                                        $tr_konversi_sku_from_do_who_create = $this->session->userdata('pengguna_username');
                                        $tr_konversi_sku_from_do_keterangan = "";

                                        $this->M_DeliveryOrderDraft->Insert_tr_konversi_sku_from_do($tr_konversi_sku_from_do_id, $depo_id, $tr_konversi_sku_from_do_tanggal, $sku_id, $sku_qty, $tr_konversi_sku_from_do_status, $tr_konversi_sku_from_do_tgl_create, $tr_konversi_sku_from_do_who_create, $tr_konversi_sku_from_do_keterangan);
                                    } else {

                                        foreach ($cek_tr_konversi_sku_from_do as $key_konversi_sku_from_do => $value_konversi_sku_from_do) {
                                            $tr_konversi_sku_from_do_id = $value_konversi_sku_from_do['tr_konversi_sku_from_do_id'];
                                            $depo_id = $value_konversi_sku_from_do['depo_id'];
                                            $tr_konversi_sku_from_do_tanggal = $value_konversi_sku_from_do['tr_konversi_sku_from_do_tanggal'];
                                            $sku_id = $value_konversi_sku_from_do['sku_id'];
                                            $sku_qty = $value_konversi_sku_from_do['sku_qty'] + $value['sku_qty'];
                                            $tr_konversi_sku_from_do_status = $value_konversi_sku_from_do['tr_konversi_sku_from_do_status'];
                                            $tr_konversi_sku_from_do_tgl_create = date('Y-m-d H:i:s');
                                            $tr_konversi_sku_from_do_who_create = $this->session->userdata('pengguna_username');
                                            $tr_konversi_sku_from_do_keterangan = "";

                                            $this->M_DeliveryOrderDraft->Update_tr_konversi_sku_from_do($tr_konversi_sku_from_do_id, $depo_id, $tr_konversi_sku_from_do_tanggal, $sku_id, $sku_qty, $tr_konversi_sku_from_do_status, $tr_konversi_sku_from_do_tgl_create, $tr_konversi_sku_from_do_who_create, $tr_konversi_sku_from_do_keterangan);
                                        }
                                    }
                                } else {
                                    array_push($emptySKUInStock, ['type' => 203, 'data' => $getNameSKU]);
                                }

                                $konversi_abaikan_rollback++;
                            }
                        } else {
                            array_push($emptySKUInStock, ['type' => 205, 'data' => $getNameSKU]);
                        }
                    } else {
                        $arr_sum = 0;
                        foreach ($GetDataSKU as $key => $val) {
                            $sumqtyStock = ($val->sku_stock_awal + $val->sku_stock_masuk) - ($val->sku_stock_saldo_alokasi - $val->sku_stock_keluar);

                            $arr_sum += $sumqtyStock;
                        }

                        if ($qtyDet > $arr_sum) {
                            $getNameSKU = $this->M_DeliveryOrderDraft->getNameSKUById($client_wms_id, $value['sku_id']);
                            array_push($emptyStockKurang, ['type' => 204, 'data' => $getNameSKU]);
                            break;
                        } else {
                            foreach ($GetDataSKU as $key => $val) {

                                if (($val->client_wms_id == $client_wms_id) && ($val->sku_id == $value['sku_id'])) {
                                    $qtyStock = ($val->sku_stock_awal + $val->sku_stock_masuk) - ($val->sku_stock_saldo_alokasi - $val->sku_stock_keluar);
                                    $sku_stock_id = $val->sku_stock_id;

                                    if ($qtyStock == 0) {
                                        continue;
                                    }

                                    if ($qtyStock >= $sisaQty) {

                                        $qtyDet2 = $sisaQty;

                                        if ($konversi_abaikan_rollback == 0) {

                                            $dod2_id = $this->M_Vrbl->Get_NewID();
                                            $dod2_id = $dod2_id[0]['NEW_ID'];

                                            $this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id, $dod_id, $delivery_order_draft_id, $value['sku_id'], $sku_stock_id, $val->sku_stock_expired_date, $qtyDet2);

                                            // cek stok_alokasi yg sudah ada
                                            $dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

                                            //update alokasi stock sku
                                            $sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
                                            $sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

                                            $this->M_DeliveryOrderDraft->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);
                                        }


                                        # break...
                                        break;
                                    } else {

                                        $sisaQty -= $qtyStock;
                                        $qtyDet2 = $qtyStock;

                                        if ($konversi_abaikan_rollback == 0) {

                                            $dod2_id_2 = $this->M_Vrbl->Get_NewID();
                                            $dod2_id_2 = $dod2_id_2[0]['NEW_ID'];


                                            $this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id_2, $dod_id, $delivery_order_draft_id, $value['sku_id'], $sku_stock_id, $val->sku_stock_expired_date, $qtyDet2);

                                            // cek stok_alokasi yg sudah ada
                                            $dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

                                            //update alokasi stock sku
                                            $sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
                                            $sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

                                            $this->M_DeliveryOrderDraft->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);

                                            if ($sisaQty <= 0) {
                                                break;
                                            };

                                            if ($key == $lastKey) {
                                                if ($sisaQty >= 0) {
                                                    $getNameSKU = $this->M_DeliveryOrderDraft->getNameSKUById($client_wms_id, $value['sku_id']);
                                                    array_push($chkSisaQty, ['type' => 201, 'data' => $getNameSKU]);
                                                }
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

                if ($value['sku_request_expdate'] == 1) {
                    //filter sku_stock berdasarkan client wms id, sku id
                    //ambil qty berdasarkan expired request diatasnya, misal req ed 5 jadi ambil diatasnya 5 
                    if ($value['sku_filter_expdate'] == ">=") {

                        $sisaQty = $qtyDet;

                        $GetDataSKUBesarDari = $this->M_DeliveryOrderDraft->GetDataSKUByReqBesarDari($client_wms_id, $value['sku_id'], $value['sku_filter_expdatebulan'], date("Y-m-d", strtotime(str_replace("/", "-", $delivery_order_draft_tgl_buat_do))));

                        if (count($GetDataSKUBesarDari) == 0) {

                            $getNameSKU = $this->M_DeliveryOrderDraft->getNameSKUById($client_wms_id, $value['sku_id']);
                            // array_push($emptySKUInStock, ['type' => 203, 'data' => $getNameSKU]);

                            $sku_konversi_group = $this->M_DeliveryOrderDraft->getSKUGroup($value['sku_id']);
                            $getSKULevel = $this->M_DeliveryOrderDraft->getSKULevel($sku_konversi_group);

                            if (count($getSKULevel) > 0) {

                                foreach ($getSKULevel as $key => $val_sku_konvers) {
                                    $dataSKUStockBySKUUpperLevel = $this->M_DeliveryOrderDraft->GetDataSKUByReqBesarDari($client_wms_id, $val_sku_konvers['sku_id'], $value['sku_filter_expdatebulan'], date("Y-m-d", strtotime(str_replace("/", "-", $delivery_order_draft_tgl_buat_do))));

                                    if (count($dataSKUStockBySKUUpperLevel) > 0) {
                                        $cek_tr_konversi_sku_from_do = $this->M_DeliveryOrderDraft->cek_tr_konversi_sku_from_do($value['sku_id']);

                                        if (count($cek_tr_konversi_sku_from_do) == 0) {
                                            $tr_konversi_sku_from_do_id = $this->M_Vrbl->Get_NewID();
                                            $tr_konversi_sku_from_do_id = $tr_konversi_sku_from_do_id[0]['NEW_ID'];

                                            $depo_id = $this->session->userdata('depo_id');
                                            $tr_konversi_sku_from_do_tanggal = date('Y-m-d H:i:s');
                                            $sku_id = $value['sku_id'];
                                            $sku_qty = $value['sku_qty'];
                                            $tr_konversi_sku_from_do_status = "";
                                            $tr_konversi_sku_from_do_tgl_create = date('Y-m-d H:i:s');
                                            $tr_konversi_sku_from_do_who_create = $this->session->userdata('pengguna_username');
                                            $tr_konversi_sku_from_do_keterangan = "";

                                            $this->M_DeliveryOrderDraft->Insert_tr_konversi_sku_from_do($tr_konversi_sku_from_do_id, $depo_id, $tr_konversi_sku_from_do_tanggal, $sku_id, $sku_qty, $tr_konversi_sku_from_do_status, $tr_konversi_sku_from_do_tgl_create, $tr_konversi_sku_from_do_who_create, $tr_konversi_sku_from_do_keterangan);
                                        } else {

                                            foreach ($cek_tr_konversi_sku_from_do as $key_konversi_sku_from_do => $value_konversi_sku_from_do) {
                                                $tr_konversi_sku_from_do_id = $value_konversi_sku_from_do['tr_konversi_sku_from_do_id'];
                                                $depo_id = $value_konversi_sku_from_do['depo_id'];
                                                $tr_konversi_sku_from_do_tanggal = $value_konversi_sku_from_do['tr_konversi_sku_from_do_tanggal'];
                                                $sku_id = $value_konversi_sku_from_do['sku_id'];
                                                $sku_qty = $value_konversi_sku_from_do['sku_qty'] + $value['sku_qty'];
                                                $tr_konversi_sku_from_do_status = $value_konversi_sku_from_do['tr_konversi_sku_from_do_status'];
                                                $tr_konversi_sku_from_do_tgl_create = date('Y-m-d H:i:s');
                                                $tr_konversi_sku_from_do_who_create = $this->session->userdata('pengguna_username');
                                                $tr_konversi_sku_from_do_keterangan = "";

                                                $this->M_DeliveryOrderDraft->Update_tr_konversi_sku_from_do($tr_konversi_sku_from_do_id, $depo_id, $tr_konversi_sku_from_do_tanggal, $sku_id, $sku_qty, $tr_konversi_sku_from_do_status, $tr_konversi_sku_from_do_tgl_create, $tr_konversi_sku_from_do_who_create, $tr_konversi_sku_from_do_keterangan);
                                            }
                                        }
                                    } else {
                                        array_push($emptySKUInStock, ['type' => 203, 'data' => $getNameSKU]);
                                    }

                                    $konversi_abaikan_rollback++;
                                }
                            } else {
                                array_push($emptySKUInStock, ['type' => 205, 'data' => $getNameSKU]);
                            }
                        } else {

                            $arr_sum = 0;
                            foreach ($GetDataSKUBesarDari as $key => $item) {
                                $qtySisaStock = ($item->sku_stock_awal + $item->sku_stock_masuk) - ($item->sku_stock_saldo_alokasi - $item->sku_stock_keluar);

                                $arr_sum += $qtySisaStock;
                            }

                            if ($sisaQty > $arr_sum) {
                                $getNameSKU = $this->M_DeliveryOrderDraft->getNameSKUById($client_wms_id, $value['sku_id']);
                                array_push($emptyStockKurang, ['type' => 204, 'data' => $getNameSKU]);
                                break;
                            } else {
                                foreach ($GetDataSKUBesarDari as $key => $item) {
                                    if (($item->client_wms_id == $client_wms_id) && ($item->sku_id == $value['sku_id'])) {
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

                                            if ($konversi_abaikan_rollback == 0) {

                                                $dod2_id = $this->M_Vrbl->Get_NewID();
                                                $dod2_id = $dod2_id[0]['NEW_ID'];

                                                $this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id, $dod_id, $delivery_order_draft_id, $value['sku_id'], $sku_stock_id, $item->sku_stock_expired_date, $qtyDet2);

                                                // cek stok_alokasi yg sudah ada
                                                $dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

                                                //update alokasi stock sku
                                                $sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
                                                $sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

                                                $this->M_DeliveryOrderDraft->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);
                                            }

                                            # break...
                                            break;
                                        } else {
                                            $sisaQty -= $qtySisaStock;
                                            $qtyDet2 = $qtySisaStock;

                                            if ($konversi_abaikan_rollback == 0) {

                                                $dod2_id_2 = $this->M_Vrbl->Get_NewID();
                                                $dod2_id_2 = $dod2_id_2[0]['NEW_ID'];


                                                $this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id_2, $dod_id, $delivery_order_draft_id, $value['sku_id'], $sku_stock_id, $item->sku_stock_expired_date, $qtyDet2);

                                                // cek stok_alokasi yg sudah ada
                                                $dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

                                                //update alokasi stock sku
                                                $sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
                                                $sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

                                                $this->M_DeliveryOrderDraft->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);

                                                if ($sisaQty <= 0) {
                                                    break;
                                                };

                                                if ($key == $lastKey) {
                                                    if ($sisaQty >= 0) {
                                                        $getNameSKU = $this->M_DeliveryOrderDraft->getNameSKUById($client_wms_id, $value['sku_id']);
                                                        array_push($chkSisaQty, ['type' => 201, 'data' => $getNameSKU]);
                                                    }
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

                    /**kurang dari*/
                    // if ($value['sku_filter_expdate'] == "<=") {
                    // 	$GetDataSKUKurangDari = $this->M_DeliveryOrderDraft->GetDataSKUByReqKurangDari($client_wms_id, $value['sku_id'], $value['sku_filter_expdatebulan'], date("Y-m-d", strtotime(str_replace("/", "-", $delivery_order_draft_tgl_buat_do))));

                    // 	if (count($GetDataSKUKurangDari) == 0) {
                    // 		$error = 1;
                    // 	} else {
                    // 		foreach ($GetDataSKUKurangDari as $key => $item) {
                    // 			if (($item->client_wms_id == $client_wms_id) && ($item->sku_id == $value['sku_id'])) {

                    // 				// cek stok_alokasi yg sudah ada
                    // 				// $dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($item->sku_stock_id);

                    // 				$qtySisaStock = ($item->sku_stock_awal + $item->sku_stock_masuk) - ($item->sku_stock_saldo_alokasi - $item->sku_stock_keluar);
                    // 				$sku_stock_id = $item->sku_stock_id;
                    // 				// $qty = $item->qty;

                    // 				if ($qtySisaStock == 0) {
                    // 					continue;
                    // 				}

                    // 				if ($qtySisaStock >= $sisaQty) {

                    // 					$qtyDet2 = $sisaQty;

                    // 					$dod2_id = $this->M_Vrbl->Get_NewID();
                    // 					$dod2_id = $dod2_id[0]['NEW_ID'];

                    // 					$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id, $dod_id, $delivery_order_draft_id, $value['sku_id'], $sku_stock_id, $item->sku_stock_expired_date, $qtyDet2);

                    // 					// cek stok_alokasi yg sudah ada
                    // 					$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

                    // 					//update alokasi stock sku
                    // 					$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
                    // 					$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

                    // 					$this->M_DeliveryOrderDraft->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);


                    // 					# break...
                    // 					break;
                    // 				} else {
                    // 					$sisaQty -= $qtySisaStock;
                    // 					$qtyDet2 = $qtySisaStock;

                    // 					$dod2_id_2 = $this->M_Vrbl->Get_NewID();
                    // 					$dod2_id_2 = $dod2_id_2[0]['NEW_ID'];


                    // 					$this->M_DeliveryOrderDraft->InsertToDODetail2Draft($dod2_id_2, $dod_id, $delivery_order_draft_id, $value['sku_id'], $sku_stock_id, $item->sku_stock_expired_date, $qtyDet2);

                    // 					// cek stok_alokasi yg sudah ada
                    // 					$dataStockAlokasi =  $this->M_DeliveryOrderDraft->GetDataStockAlokasi($sku_stock_id);

                    // 					//update alokasi stock sku
                    // 					$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
                    // 					$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;

                    // 					$this->M_DeliveryOrderDraft->UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id);

                    // 					if ($sisaQty <= 0) {
                    // 						break;
                    // 					};
                    // 				}
                    // 			} else {
                    // 				continue;
                    // 			}
                    // 		}
                    // 	}
                    // }
                }
            }
        }

        if ($konversi_abaikan_rollback == 0) {
            $this->M_DeliveryOrderDraft->confirm_delivery_order_draft($delivery_order_draft_id);
        }

        $skuEmpty = [];

        $groupSkuEmpty = "";
        foreach ($emptySKUInStock as $key => $data) {
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
            if ($konversi_abaikan_rollback == 0) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
            echo json_encode($groupSkuEmpty);
        } else {
            if ($groupSkuStokKurang !== "") {
                if ($konversi_abaikan_rollback == 0) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }
                echo json_encode($groupSkuStokKurang);
            } else {
                if ($groupQtySisa !== "") {
                    if ($konversi_abaikan_rollback == 0) {
                        $this->db->trans_rollback();
                    } else {
                        $this->db->trans_commit();
                    }
                    echo json_encode($groupQtySisa);
                } else {
                    if ($this->db->trans_status() === FALSE) {
                        if ($konversi_abaikan_rollback == 0) {
                            $this->db->trans_rollback();
                        } else {
                            $this->db->trans_commit();
                        }
                        echo json_encode(['type' => 202, 'data' => null]);
                    } else {
                        $this->db->trans_commit();
                        echo json_encode(['type' => 200, 'data' => null]);
                    }
                }
            }
        }
    }

    public function reject_delivery_order_draft()
    {
        $delivery_order_draft_id = $this->input->post('delivery_order_draft_id');
        $this->db->trans_begin();

        $this->M_DeliveryOrderDraft->reject_delivery_order_draft($delivery_order_draft_id);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(0);
        } else {
            $this->db->trans_commit();
            echo json_encode(1);
        }
    }

    public function GetDeliveryOrderDraftByFilter()
    {

        $tgl = explode(" - ", $this->input->post('tgl'));

        $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
        $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
        $do_no = $this->input->post('do_no');
        $customer = $this->input->post('customer');
        $alamat = $this->input->post('alamat');
        $tipe_pembayaran = $this->input->post('tipe_pembayaran');
        $tipe_layanan = $this->input->post('tipe_layanan');
        $status = $this->input->post('status');
        $tipe = $this->input->post('tipe');
        $segmen1 = $this->input->post('segmen1');
        $segmen2 = $this->input->post('segmen2');
        $segmen3 = $this->input->post('segmen3');
        $start = $this->input->post('start');
        $end = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = "";

        $totalData = $this->M_DeliveryOrderDraft->GetTotalDeliveryOrderDraftByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $search);

        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            $data = $this->M_DeliveryOrderDraft->GetDeliveryOrderDraftByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $start, $end, $search);
        } else {
            $search = $_POST['search']['value'];
            $data = $this->M_DeliveryOrderDraft->GetDeliveryOrderDraftByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $start, $end, $search);

            $datacount = $this->M_DeliveryOrderDraft->GetTotalDeliveryOrderDraftByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $search);

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

    public function GetPerusahaanById()
    {
        $id = $this->input->post('id');
        $data = $this->M_DeliveryOrderDraft->GetPerusahaanById($id);

        echo json_encode($data);
    }
    public function GetSegment1()
    {
        $id = $this->input->post('id');
        $data = $this->M_DeliveryOrderDraft->getSegment();

        echo json_encode($data);
    }
    public function GetSegment2()
    {
        $id = $this->input->post('id');
        $data = $this->M_DeliveryOrderDraft->getSegment2($id);

        echo json_encode($data);
    }

    public function GetSegment3()
    {
        $id = $this->input->post('id');
        $data = $this->M_DeliveryOrderDraft->getSegment3($id);

        echo json_encode($data);
    }

    public function CheckDOKirimUlang()
    {
        $delivery_order_batch_id = $this->input->post('delivery_order_batch_id');
        $delivery_order_id = $this->input->post('delivery_order_id');

        $data = $this->M_DeliveryOrderDraft->CheckDOKirimUlang($delivery_order_batch_id, $delivery_order_id);

        echo json_encode($data);
    }

    public function getDataSearchEditDO()
    {
        $tgl = explode(" - ", $this->input->post('dateRange'));
        $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
        $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
        $area = $this->input->post('area');

        $data = $this->M_DeliveryOrderDraft->getDataSearchEditDO($tgl1, $tgl2, $area);

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
        $data_tr_konversi_sku_from_do_by_depo = $this->M_DeliveryOrderDraft->get_tr_konversi_sku_from_do_by_depo();

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
            $data_sku_stock_by_konversi_sku = $this->M_DeliveryOrderDraft->get_sku_stock_by_konversi_sku($data_sku_konversi);
        } else {
            $data_sku_stock_by_konversi_sku = 0;
        }

        echo json_encode($data_sku_stock_by_konversi_sku);
    }

    public function get_sku_stock_by_sku()
    {
        $sku_id = $this->input->get('sku_id');
        $data = $this->M_DeliveryOrderDraft->get_sku_stock_by_sku($sku_id);

        echo json_encode($data);
    }

    public function GetDepoDetail()
    {
        $data = $this->M_DeliveryOrderDraft->GetDepoDetail();

        echo json_encode($data);
    }

    public function UpdateSKUQtyKonversi()
    {
        $data_tr_konversi_sku_from_do_by_depo = $this->M_DeliveryOrderDraft->get_tr_konversi_sku_from_do_by_depo();

        $tr_konversi_sku_id = $this->input->post('tr_konversi_sku_id');

        $this->db->trans_begin();

        foreach ($data_tr_konversi_sku_from_do_by_depo as $key => $value) {

            $query = $this->db->query("Exec proses_konversi_sku_pack_unpack '" . $value['sku_id'] . "', '" . $value['sku_qty'] . "', 'Repack_do'");

            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $key_query => $value_query) {
                    $this->M_DeliveryOrderDraft->Update_qty_tr_konversi_sku_detail($tr_konversi_sku_id, $value_query['sku_id'], $value_query['hasil']);
                }
            }
        }

        $this->M_DeliveryOrderDraft->delete_tr_konversi_sku_from_do();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(0);
        } else {
            $this->db->trans_commit();
            echo json_encode(1);
        }
    }
}
