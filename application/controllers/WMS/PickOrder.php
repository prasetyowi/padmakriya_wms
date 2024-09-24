<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentController.php';

class PickOrder extends CI_Controller
{
    private $MenuKode;

    public function __construct()
    {
        parent::__construct();

        if ($this->session->has_userdata('UserID') == 0) :
            redirect(base_url('MainPage/Login'));
        endif;

        $this->MenuKode = "010000003";
        $this->load->model('M_PickList');
        $this->load->model('M_PickOrder');
    }

    public function PickOrderMenu()
    {
        $this->load->model('M_Menu');
        $this->load->model('M_Gudang');

        $data = array();

        $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('UserGroupID'), $this->MenuKode);

        if ($data['Menu_Access']['R'] != 1) {
            redirect(base_url('MainPage/Index'));
            exit();
        }

        $data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('UserGroupID'));
        $data['Ses_UserName'] = $this->session->userdata('pengguna_username');

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

        // Kebutuhan Authority Menu
        $post = $this->input->post();

        if (count($post) > 0) {
            $dataPost = [];
            $dataPost['tglAwal'] = Date('Y-m-d', strtotime($this->input->post('tglAwal')));
            $dataPost['tglAkhir'] = Date('Y-m-d', strtotime($this->input->post('tglAkhir')));
            $dataPost['pickListId'] = $this->input->post('select-picking-list');
            $dataPost['gudangId'] = $this->input->post('select-gudang');
            $dataPost['checkerName'] = $this->input->post('checkerName');
            $data['listPickOrder'] = $this->M_PickOrder->filterPickOrder($dataPost);
        } else {
            $data['listPickOrder'] = $this->M_PickOrder->getAll();
        }
        $data['listGudang'] = $this->M_Gudang->getAllGudang();
        $data['listChecker'] = checkerList();
        $this->session->set_userdata('MenuLink', ltrim(current_url(), base_url()));

        $this->load->view('layouts/header', $data);
        $this->load->view('wms/Pick-Order', $data);
        $this->load->view('layouts/footer', $data);

        $this->load->view('master/S_GlobalVariable', $data);
    }

    public function PickOrderCreate()
    {
        $this->load->model('M_Menu');
        $this->load->model('M_Gudang');

        $data = array();

        $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('UserGroupID'), $this->MenuKode);

        if ($data['Menu_Access']['R'] != 1) {
            redirect(base_url('MainPage/Index'));
            exit();
        }

        $data['listGudang'] = $this->M_Gudang->getAllGudang();
        $data['listChecker'] = checkerList();

        $data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('UserGroupID'));
        $data['Ses_UserName'] = $this->session->userdata('pengguna_username');

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
        );

        // Kebutuhan Authority Menu
        $this->session->set_userdata('MenuLink', ltrim(current_url(), base_url()));

        $this->load->view('layouts/header', $data);
        $this->load->view('wms/Pick-Order-Create', $data);
        $this->load->view('layouts/footer', $data);

        $this->load->view('master/S_GlobalVariable', $data);
    }

    public function loadModalDetail()
    {
        $pickOrderId = $this->input->post('pickOrderId');
        $getDataDetail = $this->M_PickOrder->getDetailByPickOrder($pickOrderId);

        $dataForTable = [];
        $dataForTable['listDetailPickOrder'] = $getDataDetail;

        $this->load->view('wms/modal/modal-detail', $dataForTable);
    }

    public function laodListPickList()
    {
        $this->load->model('M_PickList');

        $post = $this->input->post();

        $gudangId = $post['gudangId'];
        $listPickList = $this->M_PickList->getPickListByGudang($gudangId);

        echo json_encode($listPickList);
    }

    public function getPickListDetail()
    {
        $post = $this->input->post();

        $pickListId = $post['pickListId'];

        $listPickListDetail = $this->M_PickList->getPickListDetail($pickListId);
        $getPickList = $this->M_PickList->getPickListById($pickListId);

        $dataForTable = [];
        $dataForTable['tanggalPickList'] = $getPickList[0]['PickList_Date'];
        $dataForTable['listProduct'] = $listPickListDetail;
        $dataForTable['listGudang'] = $listPickListDetail;

        $this->load->view('wms/table/List-Pick-Order', $dataForTable);
    }

    public function savePickOrder()
    {
        $post = $this->input->post();

        $pickOrder = array_unique($this->input->post('pickOrder'));
        $checker = $this->input->post('checker');
        $assign = $this->input->post('assign');
        foreach ($pickOrder as $key => $data) {
            $dataPickList = array(
                'PickList_ID' => $this->input->post('pickListId'),
                'PickOrder_Kode' => $data,
                'PickOrder_Date' => Date('Y-m-d'),
                'PickOrder_CheckerName' => getCheckerName($checker[$key][$data]),
                'PickOrder_Status' => 'Assigned',
                'PickOrder_IsPrint' => 0,
                'PickOrder_PrintDate' => null,
                'PickOrder_UserPrint' => null,
                'PickOrder_UserCreate' => $this->session->has_userdata('UserID'),
                'PickOrder_DateCreate' => Date('Y-m-d')
            );

            $this->M_PickOrder->savePickOrder($dataPickList);
            $lastId = $this->M_PickOrder->getLastId();

            foreach ($assign as $key2 => $item) {
                if (isset($item[$data])) {
                    $explodeAssign = explode('/', $item[$data]);
                    $assignSku = $explodeAssign[0];
                    $assignExpired = $explodeAssign[1];
                    $assignSatuan = $explodeAssign[2];
                    $assignQty = $explodeAssign[3];
                    $assignQtyActual = $explodeAssign[4];
                    $dataPickListDetail = array(
                        'PickOrderD_ID' => '1CB10201-0303-0202-0101-1B8179C4F' . rand(100, 999),
                        'PickOrder_ID' => $lastId,
                        'Gudang2_ID' => $this->input->post('gudangId'),
                        'SKU_UnitMandiriID' => $assignSku,
                        'PickOrderD_ExpiredDate' => $assignExpired,
                        'PickOrderD_SatuanUnit' => $assignSatuan,
                        'PickOrderD_QtyAssigned' => $assignQty,
                        'PickOrderD_QtyActual' => $assignQtyActual
                    );
                    $this->M_PickOrder->savePickOrderDetail($dataPickListDetail);
                }
            }
        }
        redirect('PickOrder/PickOrderMenu');
    }

    public function updateCetak()
    {
        $dataUpdate = [];
        $dataUpdate['PickOrder_PrintDate'] = Date('Y-m-d');
        $dataUpdate['PickOrder_UserPrint'] = $this->session->has_userdata('UserID');
        $dataUpdate['PickOrder_IsPrint'] = 1;
        $this->db->update('PickOrder', $dataUpdate, array('PickOrder_ID' => $this->input->post('pickOrderId')));

        $debug = $this->db->error();
        echo json_encode($debug);
    }

    public function updateStatus()
    {
        $dataUpdate = [];
        $dataUpdate['PickOrder_Status'] = 'Proses Pengambilan';
        $this->db->update('PickOrder', $dataUpdate, array('PickOrder_ID' => $this->input->post('pickOrderId')));

        $debug = $this->db->error();
        echo json_encode($debug);
    }
}
