<?php

//require_once APPPATH . 'core/ParentController.php';

class PackList extends CI_Controller
{
    private $MenuKode;

    public function __construct()
    {
        parent::__construct();

        if ($this->session->has_userdata('UserID') == 0) :
            redirect(base_url('MainPage/Login'));
        endif;

        $this->MenuKode = "000010002";
        $this->load->model('M_Packing');
        $this->load->model('M_Menu');
    }

    public function PackListMenu()
    {
        $data = array();

        $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('UserGroupID'), $this->MenuKode);

        $date = date("dmY");
        $product_identification = "P1";
        $product_code = str_pad($product_identification, 3, "-", STR_PAD_LEFT);
        $batch_identification = "B1";
        $batch_code = str_pad($batch_identification, 3, "-", STR_PAD_LEFT);

        $code = $date . $product_code . $batch_code;

        $data['listDO'] = $this->M_Packing->getListDO();
        $data['listPengiriman'] = $this->M_Packing->getListPengiriman();
        $data['listGudang'] = $this->M_Packing->getAllGudang();
        $data['batchCode'] = $code;

        if ($data['Menu_Access']['R'] != 1) {
            redirect(base_url('MainPage/Index'));
            exit();
        }

        $data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('UserGroupID'));
        $data['Ses_UserName'] = $this->session->userdata('pengguna_username');

        // Kebutuhan Authority Menu
        $this->session->set_userdata('MenuLink', ltrim(current_url(), base_url()));

        $this->load->view('layouts/header', $data);
        $this->load->view('wms/PackList/PackingList', $data);
        $this->load->view('layouts/footer', $data);

        $this->load->view('wms/PackList/PackingListScript');
    }

    public function PickingProgressForm()
    {
        $data = array();
        $detailPickList = [];
        $gudangId = $this->input->post('select-gudang');
        $pickListId = $this->input->post('select-pick-list');
        if (strlen($gudangId) == 0) {
            $gudangId = 'AA733980-4641-4737-8A0E-B6492432A662';
        }
        if (strlen($pickListId) > 0) {
            $detailPickList = $this->M_Packing->getPickListDetail($pickListId);
        }
        $data['detailPickList'] = $detailPickList;
        $data['listGudang'] = $this->M_Packing->getAllGudang();
        $data['listPickList'] = $this->M_Packing->getPickListByGudang($gudangId);
        $data['gudangId'] = $gudangId;
        $data['pickListId'] = $pickListId;

        $data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('UserGroupID'));
        $data['Ses_UserName'] = $this->session->userdata('UserName');

        // Kebutuhan Authority Menu
        $this->session->set_userdata('MenuLink', ltrim(current_url(), base_url()));

        $this->load->view('layouts/header', $data);
        $this->load->view('wms/PackList/PickingProgressForm', $data);
        $this->load->view('layouts/footer', $data);

        $this->load->view('wms/PackList/PickingProgressFormScript');
    }

    public function PackingOrder()
    {
        $data = array();
        $batchId = $this->input->post('select-batch');
        $data['listBatch'] = $this->M_Packing->getPickListBatch();
        if (strlen($batchId) > 0) {
            $data['listPickList'] = $this->M_Packing->getPickListByBatch($batchId);
        } else {
            $data['listPickList'] = [];
        }
        $data['batchId'] = $batchId;

        $data['sidemenu'] = $this->M_Menu->GetMenu('', $this->session->userdata('UserGroupID'));
        $data['Ses_UserName'] = $this->session->userdata('pengguna_username');

        // Kebutuhan Authority Menu
        $this->session->set_userdata('MenuLink', ltrim(current_url(), base_url()));

        $this->load->view('layouts/header', $data);
        $this->load->view('wms/PackList/PackingOrder', $data);
        $this->load->view('layouts/footer', $data);

        // $this->load->view('wms/PackList/PickingProgressFormScript');
    }

    public function loadPickListByGudang()
    {
        $gudangId = $this->input->post('gudangId');
        $listPickList = $this->M_Packing->getPickListByGudang($gudangId, 'Assigned');
        echo json_encode($listPickList);
    }

    public function loadListDoBatch()
    {
        $dateStart = $this->input->post('dateStart');
        $dateEnd = $this->input->post('dateEnd');
        $tipePengiriman = $this->input->post('tipePengiriman');
        $listDoBatch = $this->M_Packing->getListDOBatch($dateStart, $dateEnd, $tipePengiriman);
        echo json_encode($listDoBatch);
    }

    public function loadListDo()
    {
        $batchId = $this->input->post('batchId');
        $dateStart = $this->input->post('dateStart');
        $dateEnd = $this->input->post('dateEnd');
        $gudang = $this->input->post('gudang');
        $tipePengiriman = $this->input->post('tipePengiriman');
        $listDo = $this->M_Packing->getListDO($batchId);
        echo json_encode($listDo);
    }

    public function loadListDoDetail()
    {
        $doId = $this->input->post('doId');
        $doDetail = $this->M_Packing->getDoDById($doId);
        echo json_encode($doDetail);
    }

    public function loadListLokasiSKU()
    {
        $detailId = $this->input->post('detailId');
        $doDetail = $this->M_Packing->getLokasiSKU($detailId);
        echo json_encode($doDetail);
    }

    public function updatePickListProgress()
    {
        $this->load->model('M_Packing');

        $pickListD2Id = $this->input->post('pickListD2Id');
        $qtyAssign = $this->input->post('qtyAssign');
        $qtySisa = $this->input->post('qtySisa');
        $doDetail = $this->M_Packing->updateQtyAssign($pickListD2Id, $qtyAssign, $qtySisa);
        echo json_encode($qtyAssign);
    }

    public function createResi()
    {
        $doId = $this->input->post('doId');
        $doDetail = $this->M_Packing->getDoById($doId);
        $doDDetail = $this->M_Packing->getDoDById($doDetail->DO_ID);
        $getLastNumberPickList = $this->M_Packing->getLastNumberPickList();
        $getLastNumberPickListD = $this->M_Packing->getLastNumberPickListD();
        $lastNumber = $getLastNumberPickList->total + 1;
        $lastNumberD = $getLastNumberPickListD->total + 1;
        if ($lastNumber < 10) {
            $lastNumber = '0' . $lastNumber;
        }
        if ($lastNumberD < 10) {
            $lastNumberD = '0' . $lastNumberD;
        }

        //insert PickList

        $dataPickList = [];
        $dataPickList['Gudang_ID'] = $doDDetail[0]['Gudang_ID'];
        $dataPickList['GudangVA_ID'] = $doDDetail[0]['GudangVA_ID'];
        $dataPickList['Channel_ID'] = '';
        $dataPickList['PickList_Kode'] = 'PL/' . Date('Ymd') . '/' . $lastNumber;
        $dataPickList['PickList_Nama'] = 'Pick List ';
        $dataPickList['PickList_Date'] = Date('Y-m-d h:i:s');
        $dataPickList['UnitMandiri_ID'] = $doDetail->UnitMandiri_ID;
        $dataPickList['Status'] = 'Assigned';
        // $dataPickList['TipePengiriman_ID'] = $this->input->post('TipePengiriman_ID');
        $dataPickList['TipePengiriman_ID'] = '8E4E9F79-A279-4F0C-B1F0-3E6BD8673330';
        $dataPickList['Area_ID'] = '';
        $dataPickList['JenisArmada'] = '';
        $dataPickList['Kurir_ID'] = '';
        $dataPickList['KurirD_ID'] = '';
        $dataPickList['NoSuratTugasKurir'] = '';

        $this->M_Packing->insertPickList($dataPickList);


        $lastId = $this->M_Packing->getLastId();

        foreach ($doDDetail as $detail) {
            // insert PickListD
            $dataPickListD = [];
            $dataPickListD['PickList_ID'] = $lastId;
            $dataPickListD['SKU_ID'] = $detail['SKU_ID'];
            $dataPickListD['PickListD_Kode'] = 'PLD/' . Date('Ymd') . '/' . $lastNumberD;
            $dataPickListD['PickListD_QtyTotal'] = $detail['qty'];

            $this->M_Packing->insertPickListD($dataPickListD);


            $lastIdD = $this->M_Packing->getLastIdD();

            // insert PickListD2
            $dataPickListD2 = [];
            $dataPickListD2['PickListD_ID'] = $lastIdD;
            $dataPickListD2['PickList_ID'] = $lastId;
            $dataPickListD2['Gudang2_ID'] = $lastNumberD;
            $dataPickListD2['SKU_UnitMandiriID'] = $detail['SKUUNITMANDIRI_ID'];
            $dataPickListD2['PickListD2_ExpiredDate'] = '';
            $dataPickListD2['PickListD2_SatuanUnit'] = $detail['SKU_Kemasan'];
            $dataPickListD2['PickListD2_QtyPlan'] = $detail['qty'];
            $dataPickListD2['PickListD2_QtyAssigned'] = 0;
            $dataPickListD2['PickListD2_QtySisa'] = $detail['qty'];
            $dataPickListD2['Gudang_ID'] = $detail['Gudang_ID'];
            $dataPickListD2['GudangVA_ID'] = $detail['GudangVA_ID'];

            $this->M_Packing->insertPickListD2($dataPickListD2);
        }


        // insert PickList DO
        $dataPickListDO = [];
        $dataPickListDO['PickingList_ID'] = $lastId;
        $dataPickListDO['DO_ID'] = $doDetail->DO_ID;
        $dataPickListDO['DO_No'] = '';
        $dataPickListDO['NoResi'] = 'RES/PLD' . $lastNumberD . Date('Ymd');
        $dataPickListDO['Prioritas'] = $doDetail->IsPrioritas;
        $dataPickListDO['Checker'] = $this->input->post('checker');

        $this->M_Packing->insertPickListDO($dataPickListDO);

        $doStatus = $this->M_Packing->updateDoStatus($doId);

        $debug = $this->db->error();
        echo json_encode($debug);
    }

    public function getPickListGudang($gudangId)
    {
        $listPickList = $this->M_Packing->getPickListByGudang($gudangId);
        echo json_encode($listPickList);
    }

    public function updateDoStatus($doId)
    {
        $doStatus = $this->M_Packing->updateDoStatus($doId);
        echo json_encode($doStatus);
    }

    public function setdobatch()
    {
        $this->M_Packing->setDataDOBatch();
    }
}
