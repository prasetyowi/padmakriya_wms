<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

class PenerimaanMutasiAntarUnit extends ParentController
{

    private $MenuKode;

    public function __construct()
    {
        parent::__construct();

        if ($this->session->has_userdata('pengguna_id') == 0) :
            redirect(base_url('MainPage'));
        endif;

        $this->depo_id = $this->session->userdata('depo_id');
        $this->MenuKode = "120001300";
        $this->load->model(['M_Menu', ['WMS/Transaksi/MutasiAntarUnit/M_PenerimaanMutasiAntarUnit', 'M_PenerimaanMutasiAntarUnit'], 'M_Function', 'M_MenuAccess', 'M_Vrbl', 'M_AutoGen']);

        $this->load->helper(array('form', 'url'));
        $this->load->library(['form_validation']);

        date_default_timezone_set('Asia/Jakarta');
    }

    public function PenerimaanMutasiAntarUnitMenu()
    {
        $data = array();

        $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
        if ($data['Menu_Access']['R'] != 1) {
            redirect(base_url('MainPage'));
            exit();
        }

        $data['dokumen'] = $this->M_PenerimaanMutasiAntarUnit->getDokumen();

        $data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));

        // var_dump($data['sidemenu']);
        // die;

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
            Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
            // Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'

        );

        $data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

        $data['Title'] = Get_Title_Name();
        $data['Copyright'] = Get_Copyright_Name();
        $data['Ses_UserName'] = $this->session->userdata('pengguna_username');
        $data['act']    = "index";

        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $this->load->view('layouts/sidebar_header', $data);
        $this->load->view('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/index', $data);
        $this->load->view('layouts/sidebar_footer', $data);

        $this->load->view('master/S_GlobalVariable', $data);
        $this->load->view('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/script', $data);
    }

    public function getDataMutasi()
    {
        $tgl            = explode(" - ", $this->input->post('filter_date'));
        $tgl1           = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
        $tgl2           = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
        $filter_dokumen = $this->input->post('filter_dokumen');
        $data           = $this->M_PenerimaanMutasiAntarUnit->getDataMutasi($tgl1, $tgl2, $filter_dokumen);

        echo json_encode($data);
    }

    public function prosesTerimaMutasi($id)
    {
        $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
        if ($data['Menu_Access']['R'] != 1) {
            redirect(base_url('MainPage'));
            exit();
        }

        $data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));

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
            Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
            Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'

        );

        $data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

        $data['Title'] = Get_Title_Name();
        $data['Copyright'] = Get_Copyright_Name();
        $data['Ses_UserName'] = $this->session->userdata('pengguna_username');

        $data['header'] = $this->M_PenerimaanMutasiAntarUnit->getDataMutasiHeader($id);
        $data['detail'] = $this->M_PenerimaanMutasiAntarUnit->getDataMutasiDetail($id);
        $data['gudang'] = $this->M_PenerimaanMutasiAntarUnit->getGudang();
        $data['act']    = "ProsesTerimaMutasi";

        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $this->load->view('layouts/sidebar_header', $data);
        $this->load->view('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/prosesTerima', $data);
        $this->load->view('layouts/sidebar_footer', $data);

        $this->load->view('master/S_GlobalVariable', $data);
        $this->load->view('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/script', $data);
    }

    public function getKodeAutoComplete()
    {
        $valueParams = $this->input->get('params');
        $type = $this->input->get('type');
        $result = $this->M_PenerimaanMutasiAntarUnit->getKodeAutoComplete($valueParams, $type);
        echo json_encode($result);
    }

    public function check_kode_pallet()
    {
        $kode_pallet    = $this->input->post('kode_pallet');
        $depo_detail_id = $this->input->post('depo_detail_id');

        // get prefik depo
        $depo_id = $this->session->userdata('depo_id');
        $depoPrefix = $this->M_PenerimaanMutasiAntarUnit->getDepoPrefix($depo_id);
        $unit = $depoPrefix->depo_kode_preffix;

        $kode = $unit . "/" . $kode_pallet;

        $pallet = $this->M_PenerimaanMutasiAntarUnit->check_kode_pallet($kode, $depo_detail_id);

        if ($pallet == 0) {
            echo json_encode(array('type' => 201, 'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan", 'pallet_id' => ""));
        } else {
            if ($pallet['pallet_generate_detail2_is_aktif'] == 0) {
                $this->M_PenerimaanMutasiAntarUnit->insertPallet($pallet, $depo_detail_id);
            }

            echo json_encode(array('type' => 200, 'message' => "Kode pallet <strong>" . $kode . "</strong> <span class='text-success'>Valid</span>", 'pallet_id' => $pallet['pallet_generate_detail2_id'], 'kode' => $kode));
        }
    }

    public function insertIntoPalletDetailTemp()
    {
        $pallet_id      = $this->input->post('pallet_id');
        $mutasi_depo_id = $this->input->post('mutasi_depo_id');
        $depo_detail_id = $this->input->post('depo_detail_id');

        $this->db->trans_begin();

        $this->M_PenerimaanMutasiAntarUnit->delPalletDetailTemp($pallet_id);
        $this->M_PenerimaanMutasiAntarUnit->insertIntoPalletDetailTemp($pallet_id, $mutasi_depo_id, $depo_detail_id);

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            echo 0;
        } else {
            $this->db->trans_commit();
            echo 1;
        }
    }

    public function getDataMutasiDetailTemp()
    {
        $pallet_id = $this->input->post('pallet_id');
        $data      = $this->M_PenerimaanMutasiAntarUnit->getDataMutasiDetailTemp($pallet_id);

        echo json_encode($data);
    }

    public function DeletePalletDetailTemp()
    {
        $pallet_detail_id = $this->input->post('pallet_detail_id');
        $result           = $this->M_PenerimaanMutasiAntarUnit->Delete_PalletDetailTemp($pallet_detail_id);

        echo $result;
    }

    public function saveTerimaMutasi()
    {
        $tr_mutasi_depo_id          = $this->input->post('tr_mutasi_depo_id');
        $tr_mutasi_depo_tgl_upd     = $this->input->post('tr_mutasi_depo_tgl_upd');
        $depo_detail_id             = $this->input->post('depo_detail_id');
        $pallet_id                  = $this->input->post('pallet_id');
        $arr_mutasiDetail           = $this->input->post('arr_mutasiDetail');
        $param                      = 'KODE_TMD';
        $vrbl                       = $this->M_Vrbl->Get_Kode($param);
        $prefix                     = $vrbl->vrbl_kode;
        $depo_id                    = $this->session->userdata('depo_id');
        $depoPrefix                 = $this->M_PenerimaanMutasiAntarUnit->getDepoPrefix($depo_id);
        $unit                       = $depoPrefix->depo_kode_preffix;
        $tr_mutasi_depo_terima_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal(date('Y-m-d h:i:s'), $prefix, $unit);
        $tr_mutasi_depo_terima_id   = $this->M_PenerimaanMutasiAntarUnit->Get_NEWID();

        $lastUpdatedChecked = checkLastUpdatedData((object) [
            'table'             => "tr_mutasi_depo",
            'whereField'        => "tr_mutasi_depo_id",
            'whereValue'        => $tr_mutasi_depo_id,
            'fieldDateUpdate'   => "tr_mutasi_depo_tgl_update",
            'fieldWhoUpdate'    => "tr_mutasi_depo_who_update",
            'lastUpdated'       => $tr_mutasi_depo_tgl_upd
        ]);

        if ($lastUpdatedChecked['status'] == 400) {
            echo json_encode(2);
            return false;
        }

        $this->db->trans_begin();

        // INSERT HEADER MUTASI_DEPO_TERIMA
        $this->M_PenerimaanMutasiAntarUnit->insert_tr_mutasi_depo_terima($tr_mutasi_depo_terima_id, $tr_mutasi_depo_terima_kode, $tr_mutasi_depo_id);

        // INSERT / UPDATE SKU_STOCK
        $this->M_PenerimaanMutasiAntarUnit->insert_update_sku_stock($depo_detail_id, $tr_mutasi_depo_id, $pallet_id);

        // INSERT MUTASI_DEPO_TERIMA_DETAIL
        foreach ($arr_mutasiDetail as $data) {
            $this->M_PenerimaanMutasiAntarUnit->insert_tr_mutasi_depo_terima_detail($tr_mutasi_depo_terima_id, $pallet_id, $depo_detail_id, $data['sku_id'], $data['jumlah_sisa'], $data['jumlah_plan'], $data['expired_date']);
        }

        $pallet_detail_id_temp = $this->M_PenerimaanMutasiAntarUnit->get_pallet_detail_temp($tr_mutasi_depo_id, $pallet_id);

        // PALLET_DETAIL_ID ? INSERT : UPDATE
        foreach ($pallet_detail_id_temp as $row) {
            $pallet_detail_id  = $this->M_PenerimaanMutasiAntarUnit->get_pallet_detail($row['pallet_detail_id']);

            if ($pallet_detail_id == 0) {
                $this->M_PenerimaanMutasiAntarUnit->insert_pallet_detail($row);
            } else {
                $this->M_PenerimaanMutasiAntarUnit->update_pallet_detail($row);
            }
        }

        // DELETE PALLET_DETAIL_TEMP
        $this->M_PenerimaanMutasiAntarUnit->delete_pallet_detail_tmp($pallet_id, $tr_mutasi_depo_id);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(0);
        } else {
            // $this->db->trans_rollback();
            $this->db->trans_commit();
            echo json_encode(1);
        }
    }

    public function viewDetailMutasi($id)
    {
        $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
        if ($data['Menu_Access']['R'] != 1) {
            redirect(base_url('MainPage'));
            exit();
        }

        $data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('pengguna_grup_id'));

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
            Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
            Get_Assets_Url() . 'node_modules/html5-qrcode/html5-qrcode.min.js'

        );

        $data['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));

        $data['Title'] = Get_Title_Name();
        $data['Copyright'] = Get_Copyright_Name();
        $data['Ses_UserName'] = $this->session->userdata('pengguna_username');

        $data['header'] = $this->M_PenerimaanMutasiAntarUnit->getDataMutasiHeader($id);
        $data['detail'] = $this->M_PenerimaanMutasiAntarUnit->getDataMutasiDetail($id);
        $data['gudang'] = $this->M_PenerimaanMutasiAntarUnit->getGudang();
        $data['pallet'] = $this->M_PenerimaanMutasiAntarUnit->getPallet($id);
        $data['act']    = "viewDetail";

        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));

        $this->load->view('layouts/sidebar_header', $data);
        $this->load->view('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/viewDetail', $data);
        $this->load->view('layouts/sidebar_footer', $data);

        $this->load->view('master/S_GlobalVariable', $data);
        $this->load->view('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/script', $data);
    }

    public function generateMutasiStock()
    {
        $tr_mutasi_depo_terima_id = $this->input->post('tr_mutasi_depo_terima_id');

        $this->db->trans_begin();

        $getHeaderByID = $this->M_PenerimaanMutasiAntarUnit->getMutasiDepoTerimaByID($tr_mutasi_depo_terima_id);

        foreach ($getHeaderByID as $data) {
            $tr_mutasi_stok_id   = $this->M_PenerimaanMutasiAntarUnit->Get_NEWID();
            $date_now            = date('Y-m-d h:i:s');
            $param               = 'KODE_MTSS';
            $vrbl                = $this->M_Vrbl->Get_Kode($param);
            $prefix              = $vrbl->vrbl_kode;
            // get prefik depo
            $depo_id             = $this->session->userdata('depo_id');
            $depoPrefix          = $this->M_PenerimaanMutasiAntarUnit->getDepoPrefix($depo_id);
            $unit                = $depoPrefix->depo_kode_preffix;
            $tr_mutasi_stok_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

            $this->M_PenerimaanMutasiAntarUnit->insert_tr_mutasi_stok($tr_mutasi_stok_id, $tr_mutasi_stok_kode, $data);
            $this->M_PenerimaanMutasiAntarUnit->insert_tr_mutasi_depo_terima_detail2($tr_mutasi_depo_terima_id, $tr_mutasi_stok_id);

            $getDetailAsal = $this->M_PenerimaanMutasiAntarUnit->getMutasiDepoTerimaDetailAsal($tr_mutasi_depo_terima_id, $data['principle_id']);

            foreach ($getDetailAsal as $dataDetail) {
                $this->M_PenerimaanMutasiAntarUnit->insert_tr_mutasi_stok_detail($tr_mutasi_stok_id, $dataDetail);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(0);
        } else {
            // $this->db->trans_rollback();
            $this->db->trans_commit();
            echo json_encode(1);
        }
    }

    public function showKodeMutasiStock()
    {
        $id   = $this->input->post('id');
        $data = $this->M_PenerimaanMutasiAntarUnit->showKodeMutasiStock($id);

        echo json_encode($data);
    }
}
