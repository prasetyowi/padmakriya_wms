<?php

// require_once APPPATH . 'core/ParentController.php';
require_once APPPATH . 'core/MenuController.php';
// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AnalisisJumlahPenerimaan extends MenuController
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
    // private $MenuKode;

    public function __construct()
    {
        parent::__construct();

        // echo "<pre>".print_r($_SESSION, 1)."</pre>";
        // die();

        if ($this->session->has_userdata('pengguna_id') == 0) :
            redirect(base_url('MainPage'));
        endif;

        // $this->MenuKode = "100302510";
        $this->load->model('WMS/OperationalExcellence/PenerimaanBarangSupplier/M_AnalisisJumlahPenerimaan', 'M_AnalisisJumlahPenerimaan');
        $this->load->model('M_Menu');
        $this->load->model('M_Function');
        $this->load->model('M_MenuAccess');
    }

    public function AnalisisJumlahPenerimaanMenu()
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

        $query['Title'] = Get_Title_Menu_Name($this->MenuKode);
        $query['Copyright'] = Get_Copyright_Name();

        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $data['Principle'] = $this->M_AnalisisJumlahPenerimaan->Get_Principle();
        $data['Perusahaan'] = $this->M_AnalisisJumlahPenerimaan->Get_perusahaan();
        $data['ListBulan'] = array("01" => "Jan", "02" => "Feb", "03" => "Mar", "04" => "Apr", "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Aug", "09" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec");

        // Konversi ke format JavaScript Date (YYYY-MM-DD)
        $lastTbgDepo = getLastTbgDepo();
        $data['lastTbgDepo'] = date('Y-m-d', strtotime($lastTbgDepo . ' -1 day'));

        $this->load->view('layouts/sidebar_header', $query);
        $this->load->view('WMS/OperationalExcellence/PenerimaanBarangSupplier/AnalisisJumlahPenerimaan/main', $data);
        $this->load->view('layouts/sidebar_footer', $query);
        $this->load->view('master/S_GlobalVariable', $query);
        $this->load->view('WMS/OperationalExcellence/PenerimaanBarangSupplier/AnalisisJumlahPenerimaan/script', $data);
        // $this->load->view('reports/LaporanTransaksi/LaporanInformasiPallet/script', $data);
    }

    public function GetPrincipleByPerusahaan()
    {
        $perusahaan = $this->input->get('perusahaan');

        $data = $this->M_AnalisisJumlahPenerimaan->GetPrincipleByPerusahaan($perusahaan);

        echo json_encode($data);
    }

    public function Exec_report_analisis_jumlah_penerimaan()
    {
        $tahun = $this->input->post('tahun');
        $tahun2 = $this->input->post('tahun2');
        $bulan = $this->input->post('bulan');
        $bulan2 = $this->input->post('bulan2');
        $client_wms_id = $this->input->post('client_wms_id');
        $principle_id = $this->input->post('principle_id');
        $tipe = $this->input->post('tipe');

        $tglawal = "";
        $tglakhir = "";

        if ($tipe == "tahun") {
            $tglawal = $tahun . "-01-01";
            $tglakhir = $tahun2 . "-12-31";
        } else if ($tipe == "bulan") {
            $tglawal = $tahun . "-" . $bulan . "-01";
            $tglakhir = $tahun2 . "-" . $bulan2 . "-01";
            $tglakhir = date("Y-m-t", strtotime($tglakhir));
        } else if ($tipe == "tanggal") {
            $tgl = explode(" - ", $this->input->post('tanggal'));

            $tglawal = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
            $tglakhir = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
        }

        $data['AnalisisJumlahPenerimaan'] = $this->M_AnalisisJumlahPenerimaan->Exec_report_analisis_jumlah_penerimaan($tglawal, $tglakhir, $client_wms_id, $principle_id, $tipe);
        $data['ChartAnalisisJumlahPenerimaan'] = $this->M_AnalisisJumlahPenerimaan->Get_chart_analisis_jumlah_penerimaan($tglawal, $tglakhir, $client_wms_id, $principle_id, $tipe);
        $data['Kolom'] = $this->M_AnalisisJumlahPenerimaan->Generate_kolom_report_analisis_jumlah_penerimaan($tglawal, $tglakhir, $tipe);

        echo json_encode($data);
    }

    public function Get_detail_analisis_jumlah_penerimaan()
    {
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        $tgl = $this->input->post('tanggal');
        $client_wms_id = $this->input->post('client_wms_id');
        $principle_id = $this->input->post('principle_id');
        $nopol = $this->input->post('nopol');
        $tipe = $this->input->post('tipe');

        $data = $this->M_AnalisisJumlahPenerimaan->Get_detail_analisis_jumlah_penerimaan($tgl, $bulan, $tahun, $client_wms_id, $principle_id, $nopol, $tipe);

        echo json_encode($data);
    }

    public function Get_detail_analisis_jumlah_penerimaan_by_tipe()
    {
        // $filter_tanggal = $this->input->post('filter_tanggal');
        $tglawal = "";
        $tglakhir = "";

        $filter_nopol = $this->input->post('filter_nopol');
        $filter_principle = $this->input->post('filter_principle');
        $filter_perusahaan = $this->input->post('filter_perusahaan');
        $filter_reason = $this->input->post('filter_reason');
        $filter_tipe = $this->input->post('filter_tipe');
        $tahun = $this->input->post('filter_tahun');
        $bulan = $this->input->post('filter_bulan');
        $tanggal = $this->input->post('filter_tanggal');

        if ($filter_tipe == "tahun") {
            $tglawal = $tahun . "-01-01";
            $tglakhir = $tahun . "-12-31";
        } else if ($filter_tipe == "bulan") {
            $tglawal = $tahun . "-" . $bulan . "-01";
            $tglakhir = date("Y-m-t", strtotime($tglawal));
        } else if ($filter_tipe == "tanggal") {
            $tglawal = date('Y-m-d', strtotime($tanggal));
            $tglakhir = date('Y-m-d', strtotime($tanggal));
        }

        $list = $this->M_AnalisisJumlahPenerimaan->get_datatables($tglawal, $tglakhir, $filter_nopol, $filter_principle, $filter_perusahaan, $filter_reason);

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {

            $sku_composite = $this->M_AnalisisJumlahPenerimaan->proses_konversi_sku_composite_simple($row->sku_konversi_group);
            $sku_composite_qty = $this->M_AnalisisJumlahPenerimaan->proses_konversi_sku_qty_composite_simple($row->sku_konversi_group, $row->sku_jumlah_barang_terima_comp, 1);

            $no++;
            $data[] = array(
                $no,
                $row->client_wms_nama,
                $row->penerimaan_surat_jalan_kode,
                $row->penerimaan_surat_jalan_no_sj,
                $row->penerimaan_surat_jalan_tgl,
                $row->penerimaan_surat_jalan_nopol,
                $row->principle_kode,
                $row->sku_konversi_group,
                $row->sku_nama_produk,
                $sku_composite,
                $row->sku_exp_date,
                $sku_composite_qty,
                $row->reason_surat_jalan_ket
            );
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_AnalisisJumlahPenerimaan->count_all($tglawal, $tglakhir, $filter_nopol, $filter_principle, $filter_perusahaan, $filter_reason),
            "recordsFiltered" => $this->M_AnalisisJumlahPenerimaan->count_filtered($tglawal, $tglakhir, $filter_nopol, $filter_principle, $filter_perusahaan, $filter_reason),
            "data" => $data,
        );

        echo json_encode($output);
    }
}
