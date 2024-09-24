<?php

class MonitoringDeliveryOrder extends CI_Controller
{
    private $MenuKode;

    public function __construct()
    {
        parent::__construct();

        if ($this->session->has_userdata('pengguna_id') == 0) :
            redirect(base_url('MainPage'));
        endif;

        $this->MenuKode = "109016100";
        $this->load->model('WMS/M_MonitoringDeliveryOrder', 'M_MonitoringDeliveryOrder');
        $this->load->model('M_Vrbl');
        $this->load->model('M_Menu');
        $this->load->model('M_DataTable');
        $this->load->model('WMS/M_DeliveryOrder', 'M_DeliveryOrder');
    }

    public function MonitoringDeliveryOrderMenu()
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

        $data['Title'] = Get_Title_Name();
        $data['Copyright'] = Get_Copyright_Name();

        $data['css_files'] = array(
            Get_Assets_Url() . 'assets/css/custom/bootstrap4-toggle.min.css',
            Get_Assets_Url() . 'assets/js/bootstrap-touchspin-master/dist/jquery.bootstrap-touchspin.css',

            Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
            Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
            Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',

            Get_Assets_Url() . 'assets/css/custom/horizontal-scroll.css',
            Get_Assets_Url() . 'assets/css/custom/scrollbar.css'
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

        $data['perusahaan'] = $this->M_MonitoringDeliveryOrder->getPerusahaan();
        $data['principle'] = $this->M_MonitoringDeliveryOrder->getPrinciple();

        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $this->load->view('layouts/sidebar_header', $data);
        $this->load->view('WMS/MonitoringDeliveryOrder/index', $data);
        $this->load->view('layouts/sidebar_footer', $data);

        $this->load->view('master/S_GlobalVariable', $data);
        $this->load->view('WMS/MonitoringDeliveryOrder/script', $data);
    }

    public function getDataFilter()
    {
        $perusahaan = $this->input->post('perusahaan');
        $principle = $this->input->post('principle');
        $tgl_kirim = $this->input->post('tgl_kirim');
        $status = $this->input->post('status');

        $tgl = explode(" - ", $tgl_kirim);
        $tgl1 = date('Y-m-d', strtotime(str_replace('/', '-', $tgl[0])));
        $tgl2 = date('Y-m-d', strtotime(str_replace('/', '-', $tgl[1])));

        $data = $this->M_MonitoringDeliveryOrder->getDataFilter($perusahaan, $principle, $tgl1, $tgl2, $status);

        echo json_encode($data);
    }

    public function getDetailDO()
    {
        $perusahaan = $this->input->post('perusahaan');
        $principle = $this->input->post('principle');
        $tgl_kirim = $this->input->post('tgl_kirim');
        $status = $this->input->post('status');
        $depo_id = $this->session->userdata('depo_id');

        $tgl = explode(" - ", $tgl_kirim);
        $tgl1 = date('Y-m-d', strtotime(str_replace('/', '-', $tgl[0])));
        $tgl2 = date('Y-m-d', strtotime(str_replace('/', '-', $tgl[1])));

        if ($perusahaan == '') {
            $perusahaan2 = '';
        } else {
            $perusahaan2 = "AND a.client_wms_id = '$perusahaan'";
            $perusahaandod = "AND dod.client_wms_id = '$perusahaan'";
        }

        if ($principle == 'null') {
            $principle2 = "AND b.principle_id is null";
            $principledod = "AND so.principle_id is null";
        } else {
            $principle2 = "AND b.principle_id = '$principle'";
            $principledod = "AND so.principle_id = '$principle'";
        }

        if ($status == 'do draft') {
            $sql = "SELECT ROW_NUMBER ( ) OVER ( ORDER BY Subquery.delivery_order_kode ASC ) AS idx, Subquery.sales_order_no_po,  Subquery.tipe_delivery_order_nama, Subquery.delivery_order_tgl_aktual_kirim, '' AS delivery_order_batch_kode, '' AS karyawan_nama, Subquery.delivery_order_kode, Subquery.principle_kode, Subquery.delivery_order_kirim_nama, Subquery.delivery_order_kirim_alamat
            FROM (SELECT DISTINCT
            p.principle_kode,  tdo.tipe_delivery_order_nama, so.sales_order_no_po, FORMAT(dod.delivery_order_draft_tgl_aktual_kirim, 'yyyy-MM-dd') AS delivery_order_tgl_aktual_kirim, dod.delivery_order_draft_kode as delivery_order_kode, CASE
              WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_nama
              ELSE dod.delivery_order_draft_kirim_nama
            END AS delivery_order_kirim_nama, CASE
              WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
              ELSE dod.delivery_order_draft_kirim_alamat
            END AS delivery_order_kirim_alamat
           FROM (
                    SELECT delivery_order_draft_id, tipe_delivery_order_id, delivery_order_draft_kode, delivery_order_draft_tipe_layanan, delivery_order_draft_ambil_nama, delivery_order_draft_kirim_nama, delivery_order_draft_ambil_alamat, delivery_order_draft_kirim_alamat, delivery_order_draft_status, depo_id, delivery_order_draft_tgl_aktual_kirim, CASE WHEN sales_order_id IS NULL THEN canvas_id ELSE sales_order_id END AS reffid, client_wms_id from delivery_order_draft
                ) dod
                LEFT JOIN ( SELECT sales_order_id AS id, sales_order_no_po, principle_id, 'SO' AS flag FROM sales_order WHERE tipe_sales_order_id NOT IN('7EB8BB9F-7162-4155-8084-FE2EE45AE32D')
                            UNION
                            SELECT cvs.canvas_id AS id, ISNULL(so.sales_order_no_po, '') AS sales_order_no_po,  cvs.principle_id, 'CANVAS' AS flag FROM canvas cvs LEFT JOIN sales_order so ON cvs.canvas_reff_kode = so.sales_order_kode
                ) so ON dod.reffid = so.id
          LEFT JOIN principle p
            ON p.principle_id = so.principle_id
            LEFT JOIN tipe_delivery_order tdo
			ON tdo.tipe_delivery_order_id = dod.tipe_delivery_order_id
          WHERE FORMAT(dod.delivery_order_draft_tgl_aktual_kirim, 'yyyy-MM-dd') >= '$tgl1'
          AND FORMAT(dod.delivery_order_draft_tgl_aktual_kirim, 'yyyy-MM-dd') <= '$tgl2'
          AND dod.depo_id = '$depo_id'
          AND dod.delivery_order_draft_status = 'Approved'
          AND NOT EXISTS (SELECT
              delivery_order_draft_id
            FROM delivery_order o
            WHERE o.delivery_order_draft_id = dod.delivery_order_draft_id
            AND o.delivery_order_draft_id IS NOT NULL)
            $perusahaandod
            $principledod ) AS Subquery";
        } else {
            if ($status == 'exception') {
                $status2 = "AND a.delivery_order_status NOT IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered', 'approved')";
            } else {
                $status2 = "AND a.delivery_order_status = '$status'";
            }

            $sql = "SELECT ROW_NUMBER ( ) OVER ( ORDER BY Subquery.delivery_order_kode ASC ) AS idx, Subquery.sales_order_no_po, Subquery.tipe_delivery_order_nama, Subquery.delivery_order_tgl_aktual_kirim, Subquery.delivery_order_kode, Subquery.principle_kode, Subquery.delivery_order_batch_kode, Subquery.karyawan_nama, Subquery.delivery_order_kirim_nama, Subquery.delivery_order_kirim_alamat
        FROM (
            SELECT DISTINCT tdo.tipe_delivery_order_nama, a.delivery_order_kode, FORMAT(a.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') AS delivery_order_tgl_aktual_kirim, b.sales_order_no_po, e.delivery_order_batch_kode, f.karyawan_nama, d.principle_kode, CASE 
				WHEN a.delivery_order_tipe_layanan = 'Pickup Only' THEN delivery_order_ambil_nama
				ELSE a.delivery_order_kirim_nama
			END AS delivery_order_kirim_nama, CASE 
				WHEN a.delivery_order_tipe_layanan = 'Pickup Only' THEN delivery_order_ambil_alamat
				ELSE a.delivery_order_kirim_alamat
			END AS delivery_order_kirim_alamat
                FROM (
                        SELECT delivery_order_kode, tipe_delivery_order_id, delivery_order_tipe_layanan, delivery_order_ambil_nama, delivery_order_kirim_nama, delivery_order_ambil_alamat, delivery_order_kirim_alamat, delivery_order_status, delivery_order_batch_id, depo_id, delivery_order_tgl_aktual_kirim, CASE WHEN sales_order_id IS NULL THEN canvas_id ELSE sales_order_id END AS reffid, client_wms_id from delivery_order
                ) a
                LEFT JOIN ( SELECT sales_order_id AS id, sales_order_no_po, principle_id, 'SO' AS flag FROM sales_order WHERE tipe_sales_order_id NOT IN('7EB8BB9F-7162-4155-8084-FE2EE45AE32D')
                            UNION
                            SELECT cvs.canvas_id AS id, ISNULL(so.sales_order_no_po, '') AS sales_order_no_po,  cvs.principle_id, 'CANVAS' AS flag FROM canvas cvs LEFT JOIN sales_order so ON cvs.canvas_reff_kode = so.sales_order_kode
                ) b ON a.reffid = b.id
                LEFT JOIN client_wms c on a.client_wms_id = c.client_wms_id 
                LEFT JOIN principle d on d.principle_id = b.principle_id
				left join delivery_order_batch e on e.delivery_order_batch_id = a.delivery_order_batch_id
				left join karyawan f on f.karyawan_id = e.karyawan_id
                LEFT JOIN tipe_delivery_order tdo
			    ON tdo.tipe_delivery_order_id = a.tipe_delivery_order_id
                WHERE FORMAT(a.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') >= '$tgl1' AND FORMAT(a.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') <= '$tgl2'   
                AND a.depo_id = '$depo_id'
                $perusahaan2
                $principle2
                $status2
            ) AS Subquery";
        }

        $response = $this->M_DataTable->dtTableGetList($sql);

        $output = array(
            "draw" => $response['draw'],
            "recordsTotal" => $response['recordsTotal'],
            "recordsFiltered" => $response['recordsFiltered'],
            "data" => $response['data'],
        );
        echo json_encode($output);
    }
}
