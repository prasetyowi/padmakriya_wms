<?php

class ClosingGudang extends CI_Controller
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

        $this->MenuKode = "109012000";
        $this->load->model('WMS/M_PickList', 'M_PickList');
        $this->load->model('WMS/M_ClosingGudang', 'M_ClosingGudang');
        $this->load->model('WMS/M_DeliveryOrderDetailDraft', 'M_DeliveryOrderDetailDraft');
        $this->load->model('M_ClientPt');
        $this->load->model('M_Area');
        $this->load->model('M_StatusProgress');
        $this->load->model('M_SKU');
        $this->load->model('M_Principle');
        $this->load->model('M_TipeDeliveryOrder');
        $this->load->model('M_AutoGen');
        $this->load->model('M_Vrbl');
        $this->load->model('M_Menu');
    }

    public function ClosingGudangMenu()
    {
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

        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $this->load->view('layouts/sidebar_header', $query);
        $this->load->view('WMS/ClosingGudang/index', $data);
        $this->load->view('layouts/sidebar_footer', $query);
        $this->load->view('master/S_GlobalVariable', $query);
        $this->load->view('WMS/ClosingGudang/script', $data);
    }

    public function GetClosingGudangDokumen()
    {
        $tgl = $this->input->post('tgl');

        $data['dokumen'] = $this->M_ClosingGudang->GetClosingGudangDokumen($tgl);
        $data['status_tbg'] = $this->M_ClosingGudang->GetStatusClosingGudang($tgl);

        echo json_encode($data);
    }

    public function GetDetailClosingGudangDokumen()
    {
        $tgl = $this->input->post('tgl');
        $kode = $this->input->post('kode');
        $status_closing = $this->input->post('status_closing');

        $data = $this->M_ClosingGudang->GetDetailClosingGudangDokumen($tgl, $kode, $status_closing);

        echo json_encode($data);
    }

    public function Insert_tr_tutup_gudang()
    {
        if (!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "U")) {
            echo 0;
            exit();
        }

        $date_now = date('Y-m-d h:i:s');
        $param =  'KODE_TBG';
        $vrbl = $this->M_Vrbl->Get_Kode($param);
        $prefix = $vrbl->vrbl_kode;

        // get prefik depo
        $depo_id = $this->session->userdata('depo_id');
        $depoPrefix = $this->M_ClosingGudang->getDepoPrefix($depo_id);
        $unit = $depoPrefix->depo_kode_preffix;
        $tr_tutup_gudang_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

        $tr_tutup_gudang_tgl_tbg = date('Y-m-d', strtotime($this->input->post('tgl')));
        $tr_tutup_gudang_tgl_next_tbg = date('Y-m-d', strtotime($this->input->post('tgl')));
        $tr_tutup_gudang_status = "Closed";

        $check_is_tr_tutup_gudang = $this->M_ClosingGudang->check_tr_tutup_gudang($tr_tutup_gudang_tgl_tbg);

        if ($check_is_tr_tutup_gudang == 0) {

            if ($date_now >= $tr_tutup_gudang_tgl_tbg) {
                $this->db->trans_begin();

                $this->M_ClosingGudang->Insert_tr_tutup_gudang($tr_tutup_gudang_kode, $tr_tutup_gudang_tgl_tbg, $tr_tutup_gudang_tgl_next_tbg, $tr_tutup_gudang_status);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    echo json_encode(0);
                } else {
                    $this->db->trans_commit();
                    echo json_encode(1);
                }
            } else {
                echo json_encode(2);
            }
        } else {
            echo json_encode(3);
        }
    }

    public function getDetailTutupGudang()
    {
        $data = $this->M_ClosingGudang->getDetailTutupGudang();

        echo json_encode($data);
    }
}
