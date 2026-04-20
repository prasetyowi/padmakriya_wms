<?php

// require_once APPPATH . 'core/ParentController.php';
require_once APPPATH . 'core/MenuController.php';
// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class KomparasiStokFisikDanSistem extends MenuController
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

        // $this->MenuKode = "100303010";
        $this->load->model('WMS/OperationalExcellence/AnalisisHasilStockOpname/M_KomparasiStokFisikDanSistem', 'M_KomparasiStokFisikDanSistem');
        $this->load->model('M_Menu');
        $this->load->model('M_Function');
        $this->load->model('M_MenuAccess');
    }

    public function KomparasiStokFisikDanSistemMenu()
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

        $data['Principle'] = $this->M_KomparasiStokFisikDanSistem->Get_Principle();
        $data['Perusahaan'] = $this->M_KomparasiStokFisikDanSistem->Get_perusahaan();
        $data['ListBulan'] = array("01" => "Jan", "02" => "Feb", "03" => "Mar", "04" => "Apr", "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Aug", "09" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec");

        // Konversi ke format JavaScript Date (YYYY-MM-DD)
        $lastTbgDepo = getLastTbgDepo();
        $data['lastTbgDepo'] = date('Y-m-d', strtotime($lastTbgDepo . ' -1 day'));

        $this->load->view('layouts/sidebar_header', $query);
        $this->load->view('WMS/OperationalExcellence/AnalisisHasilStockOpname/KomparasiStokFisikDanSistem/main', $data);
        $this->load->view('layouts/sidebar_footer', $query);
        $this->load->view('master/S_GlobalVariable', $query);
        $this->load->view('WMS/OperationalExcellence/AnalisisHasilStockOpname/KomparasiStokFisikDanSistem/script', $data);
        // $this->load->view('reports/LaporanTransaksi/LaporanInformasiPallet/script', $data);
    }

    public function GetPrincipleByPerusahaan()
    {
        $perusahaan = $this->input->get('perusahaan');

        $data = $this->M_KomparasiStokFisikDanSistem->GetPrincipleByPerusahaan($perusahaan);

        echo json_encode($data);
    }

    public function Get_komparasi_stok_fisik_dan_sistem()
    {
        $tgl = explode(" - ", $this->input->post('tanggal'));

        $tglawal = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
        $tglakhir = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

        $client_wms_id = $this->input->post('client_wms_id');
        $principle_id = $this->input->post('principle_id');

        $data = $this->M_KomparasiStokFisikDanSistem->Get_komparasi_stok_fisik_dan_sistem($tglawal, $tglakhir, $client_wms_id, $principle_id);

        echo json_encode($data);
    }

    public function Get_detail_komparasi_stok_fisik_dan_sistem()
    {
        $tglawal = $this->input->post('tanggal');
        $tglakhir = $this->input->post('tanggal');

        $client_wms_id = $this->input->post('client_wms_id');
        $principle_id = $this->input->post('principle_id');

        $list = $this->M_KomparasiStokFisikDanSistem->Get_detail_komparasi_stok_fisik_dan_sistem($tglawal, $tglakhir, $client_wms_id, $principle_id);

        $data = array();
        foreach ($list as $row) {
            $sku_composite = $this->M_KomparasiStokFisikDanSistem->proses_konversi_sku_composite_simple($row->sku_konversi_group);
            $sku_actual_qty_opname_comp = $this->M_KomparasiStokFisikDanSistem->proses_konversi_sku_qty_composite_simple($row->sku_konversi_group, $row->sku_actual_qty_opname_comp, 1);
            $sku_qty_sistem_comp = $this->M_KomparasiStokFisikDanSistem->proses_konversi_sku_qty_composite_simple($row->sku_konversi_group, $row->sku_qty_sistem_comp, 1);

            $data[] = array(
                'tr_opname_plan_kode' => $row->tr_opname_plan_kode,
                'client_wms_nama' => $row->client_wms_nama,
                'tr_opname_plan_tanggal' => $row->tr_opname_plan_tanggal,
                'karyawan_nama' => $row->karyawan_nama,
                'tipe_stok' => $row->tipe_stok,
                'tipe_opname_nama' => $row->tipe_opname_nama,
                'tr_opname_plan_status' => $row->tr_opname_plan_status,
                'tr_opname_plan_tgl_start' => $row->tr_opname_plan_tgl_start,
                'tr_opname_plan_tgl_end' => $row->tr_opname_plan_tgl_end,
                'principle_kode' => $row->principle_kode,
                'sku_konversi_group' => $row->sku_konversi_group,
                'sku_nama_produk' => $row->sku_nama_produk,
                'sku_expired_date' => $row->sku_expired_date,
                'is_selisih' => $row->is_selisih,
                'sku_composite' => $sku_composite,
                'sku_actual_qty_opname_comp' => $sku_actual_qty_opname_comp,
                'sku_qty_sistem_comp' => $sku_qty_sistem_comp
            );
        }

        echo json_encode($data);
    }

    public function Get_detail_komparasi_stok_fisik_dan_sistem_by_id_datatable()
    {

        $penerimaan_pembelian_id = $this->input->post('penerimaan_pembelian_id');

        $list = $this->M_KomparasiStokFisikDanSistem->get_datatables($penerimaan_pembelian_id);

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {

            $sku_composite = $this->M_KomparasiStokFisikDanSistem->proses_konversi_sku_composite_simple($row->sku_konversi_group);
            $sku_composite_qty = $this->M_KomparasiStokFisikDanSistem->proses_konversi_sku_qty_composite_simple($row->sku_konversi_group, $row->sku_jumlah_terima_comp, 1);

            $no++;
            $data[] = array(
                $no,
                $row->client_wms_nama,
                $row->penerimaan_pembelian_kode,
                $row->penerimaan_pembelian_tgl,
                $row->principle_kode,
                $row->sku_konversi_group,
                $row->sku_nama_produk,
                $sku_composite,
                $row->sku_exp_date,
                $sku_composite_qty
            );
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_KomparasiStokFisikDanSistem->count_all($penerimaan_pembelian_id),
            "recordsFiltered" => $this->M_KomparasiStokFisikDanSistem->count_filtered($penerimaan_pembelian_id),
            "data" => $data,
        );

        echo json_encode($output);
    }
}
