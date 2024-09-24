<?php

require_once APPPATH . 'core/ParentController.php';

// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanAgingStock extends ParentController
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

        $this->MenuKode = "101002017";
        $this->load->model('WMS/LaporanTransaksi/M_LaporanAgingStock', 'M_LaporanAgingStock');
        $this->load->model('M_Menu');
        $this->load->model('M_Function');
        $this->load->model('M_MenuAccess');
    }

    public function LaporanAgingStockMenu()
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

        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $data['Gudang'] = $this->M_LaporanAgingStock->Get_Gudang();
        $data['Principle'] = $this->M_LaporanAgingStock->Get_Principle();
        $data['Perusahaan'] = $this->M_LaporanAgingStock->Get_perusahaan();
        $data['ListBulan'] = array("01" => "Jan", "02" => "Feb", "03" => "Mar", "04" => "Apr", "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Aug", "09" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec");

        // Konversi ke format JavaScript Date (YYYY-MM-DD)
        $lastTbgDepo = getLastTbgDepo();
        $data['lastTbgDepo'] = date('Y-m-d', strtotime($lastTbgDepo . ' -1 day'));

        $this->load->view('layouts/sidebar_header', $query);
        $this->load->view('reports/LaporanTransaksi/LaporanAgingStock/main', $data);
        $this->load->view('layouts/sidebar_footer', $query);
        $this->load->view('master/S_GlobalVariable', $query);
        $this->load->view('reports/LaporanTransaksi/LaporanAgingStock/script', $data);
        // $this->load->view('reports/LaporanTransaksi/LaporanInformasiPallet/script', $data);
    }

    public function GetPrincipleByPerusahaan()
    {
        $perusahaan = $this->input->get('perusahaan');

        $data = $this->M_LaporanAgingStock->GetPrincipleByPerusahaan($perusahaan);

        echo json_encode($data);
    }

    public function Get_laporan_stock_aging()
    {
        $tahun = $this->input->post('tahun');
        $tahun2 = $this->input->post('tahun2');
        $bulan = $this->input->post('bulan');
        $bulan2 = $this->input->post('bulan2');
        $client_wms_id = $this->input->post('client_wms_id');
        $principle_id = $this->input->post('principle_id');
        $depo_detail_id = $this->input->post('depo_detail_id');
        $tipe = $this->input->post('tipe');

        $tglawal = "";
        $tglakhir = "";

        if ($tipe == "tahun") {
            $tglawal = $tahun . "-01-01";
            $tglakhir = $tahun2 . "-01-01";
        } else if ($tipe == "bulan") {
            $tglawal = $tahun . "-" . $bulan . "-01";
            $tglakhir = $tahun2 . "-" . $bulan2 . "-01";
        }

        $data['LaporanAgingStock'] = $this->M_LaporanAgingStock->Get_laporan_stock_aging($tglawal, $tglakhir, $client_wms_id, $principle_id, $depo_detail_id, $tipe);
        $data['Kolom'] = $this->M_LaporanAgingStock->Generate_kolom_stock_aging($tglawal, $tglakhir, $tipe);

        echo json_encode($data);
    }
}
