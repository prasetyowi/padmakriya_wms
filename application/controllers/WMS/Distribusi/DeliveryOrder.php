<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once APPPATH . 'core/ParentController.php';

class DeliveryOrder extends CI_Controller
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
        $this->load->model('M_DeliveryOrder');
    }

    public function DeliveryOrderMenu()
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

        $data["Tgl_Awal"] = date("Y-m-01");
        $data["Tgl_Akhir"] = date("Y-m-t");


        $this->load->view('layouts/header', $data);
        $this->load->view('wms/DeliveryOrder', $data);
        $this->load->view('layouts/footer', $data);

        $this->load->view('master/S_GlobalVariable', $data);
        $this->load->view('wms/S_DeliveryOrder', $data);
    }

    public function GetDeliveryOrder()
    {
        $this->load->model('M_DeliveryOrder');
        $this->load->model('M_Menu');

        $Tgl_Awal = formatYMD($this->input->post('tglawal'));
        $Tgl_Akhir = formatYMD($this->input->post('tglakhir'));
        $txtpaymentype = $this->input->post('txtpaymentype');
        $txtdeliverytype = $this->input->post('txtdeliverytype');
        $txtservicetype = $this->input->post('txtservicetype');
        $numberDo = $this->input->post('numberDo');
        $customerName = $this->input->post('customerName');
        $address = $this->input->post('address');
        $cityName = $this->input->post('cityName');

        $Status = $this->input->post('status');

        $data = array();

        $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('UserGroupID'), $this->MenuKode);
        if ($data['Menu_Access']['R'] != 1) {
            redirect(base_url('MainPage/Index'));
            exit();
        }

        $options = [];
        $options["TglAwal"] = $Tgl_Awal;
        $options["TglAkhir"] = $Tgl_Akhir;
        if (strlen($txtpaymentype) > 0) {
            $options["DO_PaymentType"] = $txtpaymentype;
        }
        if (strlen($txtdeliverytype) > 0) {
            $options["DO_PaymentType"] = $txtdeliverytype;
        }
        if (strlen($txtservicetype) > 0) {
            $options["TipePengirimanId"] = $txtservicetype;
        }
        if (strlen($numberDo) > 0) {
            $options["DO_No"] = $numberDo;
        }
        if (strlen($customerName) > 0) {
            $options["Dest_name"] = $customerName;
        }
        if (strlen($address) > 0) {
            $options["Dest_Addr"] = $address;
        }
        if (strlen($cityName) > 0) {
            $options["Dest_City"] = $cityName;
        }
        $data['DeliveryOrder'] = $this->M_DeliveryOrder->Get_DeliveryOrder($options);

        echo json_encode($data);
    }
    public function save()
    {
        $this->load->model('M_DeliveryOrderDraft');
        $this->load->model('M_DeliveryOrderDetailDraft');
        $this->load->model('M_DeliveryType');
        $this->load->model('M_DeliveryOrderProgress');
        $this->load->model('M_SKU');
        $this->load->model('M_UNITMANDIRI');
        $this->load->model('M_SKU_UNITMANDIRI');
        $this->load->model('M_Menu');

        $TglDO = date("Y-m-d");
        $DO_NO = $this->M_DeliveryOrderDraft->Get_NextNoDo();
        $DO_STATUS = "Draft";
        $UnitMandiri_ID = $this->input->post('UnitMandiri_ID');
        $Prioritas = $this->input->post('Prioritas');
        $Keterangan = $this->input->post('Keterangan');
        $ServiceType = $this->input->post('ServiceType');
        $PaymentType = $this->input->post('PaymentType');
        $Dest_Name = $this->input->post('Dest_Name');
        $Dest_Address = $this->input->post('Dest_Address');
        $Dest_Phone = $this->input->post('Dest_Phone');
        $Dest_Province = $this->input->post('Dest_Province');
        $Dest_City = $this->input->post('Dest_City');
        $Dest_Kecamatan = $this->input->post('Dest_Kecamatan');
        $Dest_Kelurahan = $this->input->post('Dest_Kelurahan');
        $Dest_KodePos = $this->input->post('Dest_KodePos');
        $Dest_Area = $this->input->post('Dest_Area');
        $Pick_Name = $this->input->post('Pick_Name');
        $Pick_Address = $this->input->post('Pick_Address');
        $Pick_Phone = $this->input->post('Pick_Phone');
        $Pick_Province = $this->input->post('Pick_Province');
        $Pick_City = $this->input->post('Pick_City');
        $Pick_Kecamatan = $this->input->post('Pick_Kecamatan');
        $Pick_Kelurahan = $this->input->post('Pick_Kelurahan');
        $Pick_KodePos = $this->input->post('Pick_KodePos');
        $Pick_Area = $this->input->post('Pick_Area');
        $SKU_Items = $this->input->post('SKU_Items');
        $DO_FlagSession = $this->input->post('FlagSession');
        $DeliveryType = $this->input->post('DeliveryType');
        $S_UserName = $this->session->userdata('UserName');
        $errCode = 0;
        $errMessage = "";
        if (strlen($UnitMandiri_ID) == 0) {
            $errCode++;
            $errMessage = "Anda Belum mengisi perusahaan";
        } else {
            $unitMandiriData = $this->M_UNITMANDIRI->Get_UnitMandiri_By_Id2($UnitMandiri_ID);
        }
        if (strlen($Dest_Name) == 0) {
            $errCode++;
            $errMessage = "Anda Belum mengisi nama penerima/lokasi pickup";
        }

        $this->db->trans_start();
        if ($errCode == 0) {
            if ($DO_FlagSession == 1) {
                $DeliveryTypeData = $this->M_DeliveryType->Get_DeliveryType_By_Id($DeliveryType);
            } else {
                $DeliveryTypeData["NamaTipe"] = "";
            }
            $cek = $this->M_DeliveryOrderDraft->Save(
                $TglDO,
                $DO_NO,
                $UnitMandiri_ID,
                $unitMandiriData["UnitMandiri_Code"],
                $Prioritas,
                $Keterangan,
                $ServiceType,
                $PaymentType,
                $Dest_Name,
                $Dest_Address,
                $Dest_Phone,
                $Dest_Province,
                $Dest_City,
                $Dest_Kecamatan,
                $Dest_Kelurahan,
                $Dest_KodePos,
                $Dest_Area,
                $Pick_Name,
                $Pick_Address,
                $Pick_Phone,
                $Pick_Province,
                $Pick_City,
                $Pick_Kecamatan,
                $Pick_Kelurahan,
                $Pick_KodePos,
                $Pick_Area,
                $DO_FlagSession,
                $DeliveryType,
                $DeliveryTypeData["NamaTipe"],
                $DO_STATUS,
                $S_UserName
            );
            $DOD_ID = $this->M_DeliveryOrderDraft->getLastId();
            if (count($SKU_Items) > 0) {
                foreach ($SKU_Items as $SKU) {
                    $qty = $SKU["qty"];
                    $SKU_ID = $SKU["SKU_ID"];
                    $skuData = $this->M_SKU->Get_SKU_By_ID($SKU_ID);
                    $availableQty = $skuData["SKU_Stock_Avail"];
                    if ($availableQty < $qty) {
                        $errCode = 77;
                        $errMessage = "Stock Tersedia Mengalami perubahan silahkan isi kembali data sku";
                        break;
                    }
                    $this->M_SKU_UNITMANDIRI->Update_Stock($SKU_ID, $availableQty - $qty, $S_UserName);

                    $this->M_DeliveryOrderDetailDraft->Save($DOD_ID, $skuData, $qty, $S_UserName);
                }
            }
        }

        if ($errCode == 0) {
            $this->db->trans_commit();
        } else {
            $this->db->trans_rollback();
        }

        echo json_encode(array("errCode" => $errCode, "errMessage" => $errMessage));
    }

    public function RequestDeliveryOrder()
    {
        $this->load->model('M_DeliveryOrderDraft');
        $this->load->model('M_DeliveryOrderDetailDraft');
        $this->load->model('M_DeliveryOrder');
        $this->load->model('M_DeliveryOrderDetail');
        $this->load->model('M_DeliveryOrderProgress');
        $this->load->model('M_DeliveryType');
        $this->load->model('M_ProgressStatus');
        $this->load->model('M_SKU');
        $this->load->model('M_UNITMANDIRI');
        $this->load->model('M_SKU_UNITMANDIRI');
        $this->load->model('M_Menu');
        $errCode = 0;
        $errMessage = "";
        $DO_STATUS = "On Process";
        $DOD_Items = $this->input->post('DOD_Items');
        $S_UserName = $this->session->userdata('UserName');
        $sequenceNo = 1;
        $moduleName = "Delivery Order";
        $this->db->trans_start();
        $ProgressData = $this->M_ProgressStatus->Get_ProgressStatus_By_SequenceNo($sequenceNo, $moduleName);
        if ($ProgressData == 0) {
            $errCode = 1;
            $errMessage = "Progress dengan sequnce 1 tidak ditemukan";
        }
        if (count($DOD_Items) == 0) {
            $errCode = 1;
            $errMessage = "anda belum memilih DOD Item";
        }

        if ($errCode == 0) {
            if (count($DOD_Items) > 0) {
                foreach ($DOD_Items as $DOD) {
                    $prioritas = $DOD["Prioritas"];
                    $DOD_ID = $DOD["DOD_ID"];
                    $this->M_DeliveryOrderDraft->updateStatus($DOD_ID, $DO_STATUS);
                    $DOD_DATA = $this->M_DeliveryOrderDraft->Get_DeliveryOrderDraft_By_ID($DOD_ID);
                    $this->M_DeliveryOrder->save($DOD_DATA, $prioritas, $S_UserName);

                    $DO_ID = $this->M_DeliveryOrder->getLastId();
                    $DOD_Detail_DATA = $this->M_DeliveryOrderDetailDraft->Get_DeliveryOrderDetailDraft_By_DODID($DOD_ID);
                    if (count($DOD_Detail_DATA) > 0) {
                        foreach ($DOD_Detail_DATA as $DOD_Detail) {
                            $this->M_DeliveryOrderDetail->save($DO_ID, $DOD_Detail, $S_UserName);
                        }
                    }
                    $this->M_DeliveryOrderProgress->save($DO_ID, $ProgressData, $S_UserName);
                }
            }
        }

        if ($errCode == 0) {
            $this->db->trans_commit();
        } else {
            $this->db->trans_rollback();
        }

        echo json_encode(array("errCode" => $errCode, "errMessage" => $errMessage));
    }
}
