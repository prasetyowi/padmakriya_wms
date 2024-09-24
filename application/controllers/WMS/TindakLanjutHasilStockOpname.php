<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class TindakLanjutHasilStockOpname extends ParentController
{
    private $MenuKode;

    public function __construct()
    {
        parent::__construct();

        if ($this->session->has_userdata('pengguna_id') == 0) :
            redirect(base_url('MainPage'));
        endif;

        $this->depo_id = $this->session->userdata('depo_id');
        $this->MenuKode = "123009000";
        $this->load->model('M_Menu');

        $this->load->model('WMS/M_TindakLanjutHasilStockOpname', 'M_TindakLanjutHasilStockOpname');
        $this->load->model('M_Function');
        $this->load->model('M_MenuAccess');
        $this->load->model('M_Vrbl');
        $this->load->model('M_AutoGen');

        $this->load->helper(array('form', 'url'));
        $this->load->library(['form_validation', 'pdfgenerator']);
    }

    public function TindakLanjutHasilStockOpnameMenu()
    {

        $data = array();

        $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
        if ($data['Menu_Access']['R'] != 1) {
            redirect(base_url('MainPage'));
            exit();
        }

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

        $data['perusahaan'] = $this->M_TindakLanjutHasilStockOpname->getPerusahaan();


        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $this->load->view('layouts/sidebar_header', $query);
        $this->load->view('WMS/TindakLanjutHasilStockOpname/index', $data);
        $this->load->view('layouts/sidebar_footer', $query);

        $this->load->view('master/S_GlobalVariable', $data);
        $this->load->view('WMS/TindakLanjutHasilStockOpname/S_TindakLanjutHasilStockOpname', $data);
    }

    public function create()
    {

        $data = array();

        $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
        if ($data['Menu_Access']['R'] != 1) {
            redirect(base_url('MainPage'));
            exit();
        }

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
        $this->load->view('WMS/TindakLanjutHasilStockOpname/create', $data);
        $this->load->view('layouts/sidebar_footer', $query);

        $this->load->view('master/S_GlobalVariable', $data);
        $this->load->view('WMS/TindakLanjutHasilStockOpname/S_TindakLanjutHasilStockOpname', $data);
    }

    public function edit($id)
    {

        $data = array();

        $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
        if ($data['Menu_Access']['R'] != 1) {
            redirect(base_url('MainPage'));
            exit();
        }

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

        $data['header'] = $this->M_TindakLanjutHasilStockOpname->getDataOpnameResultByID($id);
        $data['detail'] = $this->M_TindakLanjutHasilStockOpname->getDataOpnameResultDetailByID($id);


        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $this->load->view('layouts/sidebar_header', $query);
        $this->load->view('WMS/TindakLanjutHasilStockOpname/edit', $data);
        $this->load->view('layouts/sidebar_footer', $query);

        $this->load->view('master/S_GlobalVariable', $data);
        $this->load->view('WMS/TindakLanjutHasilStockOpname/S_TindakLanjutHasilStockOpname', $data);
    }

    public function detail($id)
    {

        $data = array();

        $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
        if ($data['Menu_Access']['R'] != 1) {
            redirect(base_url('MainPage'));
            exit();
        }

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

        $data['header'] = $this->M_TindakLanjutHasilStockOpname->getDataOpnameResultByID($id);
        $data['detail'] = $this->M_TindakLanjutHasilStockOpname->getDataOpnameResultDetailByID($id);


        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $this->load->view('layouts/sidebar_header', $query);
        $this->load->view('WMS/TindakLanjutHasilStockOpname/detail', $data);
        $this->load->view('layouts/sidebar_footer', $query);

        $this->load->view('master/S_GlobalVariable', $data);
        $this->load->view('WMS/TindakLanjutHasilStockOpname/S_TindakLanjutHasilStockOpname', $data);
    }

    public function getDataDokumenOpname()
    {
        $data = $this->M_TindakLanjutHasilStockOpname->getDataDokumenOpname();

        echo json_encode($data);
    }

    public function getDataOpnameByKode()
    {
        $id = $this->input->post("value");

        $header = $this->M_TindakLanjutHasilStockOpname->getDataOpnameByKode($id);
        $detail = $this->M_TindakLanjutHasilStockOpname->getDataOpnameSelisihByID($id);

        $response = [
            'header' => $header,
            'detail' => $detail
        ];

        echo json_encode($response);
    }

    public function getPrincipleByID()
    {
        $id = $this->input->post("value");

        $data = $this->M_TindakLanjutHasilStockOpname->getPrincipleByID($id);

        echo json_encode($data);
    }

    public function searchFilter()
    {
        $tgl = $this->input->post("tgl");
        $perusahaan = $this->input->post("perusahaan");
        $principle = $this->input->post("principle");

        $tgl = explode(" - ", $tgl);
        $tgl1 = date("Y-m-d", strtotime(str_replace("/", "-", $tgl[0])));
        $tgl2 = date("Y-m-d", strtotime(str_replace("/", "-", $tgl[1])));

        $data = $this->M_TindakLanjutHasilStockOpname->searchFilter($tgl1, $tgl2, $perusahaan, $principle);

        echo json_encode($data);
    }

    public function saveDataOpname()
    {
        $mode = $this->input->post('mode');
        $id = $this->input->post('id');
        $arr_chk = $this->input->post('arr_chk');
        $tglUpdate = $this->input->post('tglUpdate');

        $this->db->trans_begin();

        if ($mode == 'insert') {
            $data = $this->M_TindakLanjutHasilStockOpname->getTrOpnamePlanByID($id);

            $tr_opname_result_id = $this->M_Vrbl->Get_NewID();
            $tr_opname_result_id = $tr_opname_result_id[0]['NEW_ID'];

            $insert = $this->M_TindakLanjutHasilStockOpname->insertTrOpnameResult($data, $tr_opname_result_id);
        } else {
            $lastUpdatedChecked = checkLastUpdatedData((object) [
                'table' => "tr_opname_result",
                'whereField' => "tr_opname_result_id",
                'whereValue' => $id,
                'fieldDateUpdate' => "tr_opname_result_tgl_update",
                'fieldWhoUpdate' => "tr_opname_result_who_update",
                'lastUpdated' => $tglUpdate
            ]);

            $update =  $this->M_TindakLanjutHasilStockOpname->updateTrOpnameResult($id, $mode);
            $delete = $this->M_TindakLanjutHasilStockOpname->deleteTrOpnameResultDetail($id);
        }

        foreach ($arr_chk as $key => $value) {
            if ($mode == 'insert') {
                $insertDetail = $this->M_TindakLanjutHasilStockOpname->insertTrOpnameResultDetail($tr_opname_result_id, $value);
            } else {
                $insertDetail = $this->M_TindakLanjutHasilStockOpname->insertTrOpnameResultDetail($id, $value);
            }
        }

        if ($mode == 'insert') {
            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
                echo json_encode($response = ['status' => 401]);
            } else {
                $this->db->trans_commit();
                echo json_encode($response = ['status' => 200]);
            }
        } else {
            echo json_encode(responseJson((object)[
                'lastUpdatedChecked' => $lastUpdatedChecked,
                'status' => 'Disimpan'
            ]));
        }
    }

    public function checkLastUpdate()
    {
        $tglUpdate = $this->input->post("tglUpdate");
        $status = $this->input->post("status");
        $tr_opname_result_id = $this->input->post("tr_opname_result_id");

        $tglUpdateDB = $this->db->select("tr_opname_result_tgl_update")->from("tr_opname_result")->where("tr_opname_result_id", $tr_opname_result_id)->get()->row()->tr_opname_result_tgl_update;

        if ($tglUpdate == $tglUpdateDB) {
            echo json_encode(200);
        } else {
            echo json_encode(400);
        }
    }
}
