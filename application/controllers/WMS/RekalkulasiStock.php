<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentController.php';

class RekalkulasiStock extends CI_Controller
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

        $this->MenuKode = "128010000";
        $this->load->model('WMS/M_RekalkulasiStock');
        $this->load->model('M_Menu');
    }

    public function RekalkulasiStockMenu()
    {

        $data = array();

        $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
        if ($data['Menu_Access']['R'] != 1) {
            redirect(base_url('MainPage/Index'));
            exit();
        }

        $data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

        $data['Ses_UserName'] = $this->session->userdata('pengguna_username');

        $data['Title'] = Get_Title_Name();
        $data['Copyright'] = Get_Copyright_Name();

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

        $data['Perusahaan'] = $this->M_RekalkulasiStock->GetPerusahaan();
        $data['Gudang'] = $this->M_RekalkulasiStock->GetDepoDetail();
        $data['Principle'] = $this->M_RekalkulasiStock->GetPrinciple();

        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $this->load->view('layouts/sidebar_header', $data);
        $this->load->view('WMS/RekalkulasiStock/index', $data);
        $this->load->view('layouts/sidebar_footer', $data);

        $this->load->view('master/S_GlobalVariable', $data);
        $this->load->view('WMS/RekalkulasiStock/script', $data);
    }

    public function GetRekalkulasiByFilter()
    {

        $tgl = explode(" - ", $this->input->post('tgl'));

        $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
        $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
        $start = $this->input->post('start');
        $end = $this->input->post('length');
        $draw = $this->input->post('draw');
        $perusahaan = $this->input->post('perusahaan');
        $gudang = $this->input->post('gudang');
        $principle = $this->input->post('principle');
        $sku_kode = $this->input->post('sku_kode');
        $sku_nama_produk = $this->input->post('sku_nama_produk');
        $status = $this->input->post('status');

        $search = "";

        $totalData = $this->M_RekalkulasiStock->GetTotalRekalkulasiByFilter($tgl1, $tgl2, $perusahaan, $gudang, $principle, $sku_kode, $sku_nama_produk, $status);

        $totalFiltered = $totalData;

        // $search = $_POST['search']['value'];
        $data = $this->M_RekalkulasiStock->GetRekalkulasiByFilter($tgl1, $tgl2, $start,  $end, $perusahaan, $gudang, $principle, $sku_kode, $sku_nama_produk, $status);
        $datacount = $this->M_RekalkulasiStock->GetTotalRekalkulasiByFilter($tgl1, $tgl2, $perusahaan, $gudang, $principle, $sku_kode, $sku_nama_produk, $status);

        $totalFiltered = $datacount;

        $data = array(
            "draw" => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($data);
    }

    public function GetProsesRekalkulasiByFilter()
    {

        $tgl = explode(" - ", $this->input->post('tgl'));

        $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
        $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
        $start = $this->input->post('start');
        $end = $this->input->post('length');
        $draw = $this->input->post('draw');
        $perusahaan = $this->input->post('perusahaan');
        $gudang = $this->input->post('gudang');
        $principle = $this->input->post('principle');
        $sku_kode = $this->input->post('sku_kode');
        $sku_nama_produk = $this->input->post('sku_nama_produk');
        $status = $this->input->post('status');
        $search = "";

        $totalData = $this->M_RekalkulasiStock->GetTotalRekalkulasiByFilter($tgl1, $tgl2, $perusahaan, $gudang, $principle, $sku_kode, $sku_nama_produk, $status);

        $totalFiltered = $totalData;

        // $search = $_POST['search']['value'];
        $data = $this->M_RekalkulasiStock->GetProsesRekalkulasiByFilter($tgl1, $tgl2, $start,  $end, $perusahaan, $gudang, $principle, $sku_kode, $sku_nama_produk, $status);
        $datacount = $this->M_RekalkulasiStock->GetTotalRekalkulasiByFilter($tgl1, $tgl2, $perusahaan, $gudang, $principle, $sku_kode, $sku_nama_produk, $status);

        $totalFiltered = $datacount;

        $data = array(
            "draw" => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($data);
    }

    public function SimpanRekalkulasi()
    {

        $tgl = explode(" - ", $this->input->post('tgl'));

        $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
        $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

        $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
        $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
        $start = $this->input->post('start');
        $end = $this->input->post('length');
        $draw = $this->input->post('draw');
        $perusahaan = $this->input->post('perusahaan');
        $gudang = $this->input->post('gudang');
        $principle = $this->input->post('principle');
        $sku_kode = $this->input->post('sku_kode');
        $sku_nama_produk = $this->input->post('sku_nama_produk');
        $status = $this->input->post('status');
        $search = "";

        $totalData = $this->M_RekalkulasiStock->GetTotalRekalkulasiByFilter($tgl1, $tgl2, $perusahaan, $gudang, $principle, $sku_kode, $sku_nama_produk, $status);

        $totalFiltered = $totalData;

        // $search = $_POST['search']['value'];
        $data = $this->M_RekalkulasiStock->SimpanRekalkulasi($tgl1, $tgl2, $start,  $end, $perusahaan, $gudang, $principle, $sku_kode, $sku_nama_produk, $status);
        $datacount = $this->M_RekalkulasiStock->GetTotalRekalkulasiByFilter($tgl1, $tgl2, $perusahaan, $gudang, $principle, $sku_kode, $sku_nama_produk, $status);

        $totalFiltered = $datacount;

        $data = array(
            "draw" => intval($draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($data);

        // $this->db->trans_begin();

        // $this->M_RekalkulasiStock->SimpanRekalkulasi($tgl1, $tgl2);

        // if ($this->db->trans_status() === FALSE) {
        //     $this->db->trans_rollback();
        //     echo json_encode(array("type" => "200"));
        // } else {
        //     $this->db->trans_commit();
        //     echo json_encode(array("type" => "202"));
        // }
    }
}
