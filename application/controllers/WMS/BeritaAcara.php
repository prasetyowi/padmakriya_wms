<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/ParentController.php';

// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BeritaAcara extends ParentController
{
    private $MenuKode;

    public function __construct()
    {
        parent::__construct();

        if ($this->session->has_userdata('pengguna_id') == 0) :
            redirect(base_url('MainPage'));
        endif;

        $this->depo_id = $this->session->userdata('depo_id');
        $this->MenuKode = "125003000";
        $this->load->model('M_Menu');
        $this->load->model('WMS/M_BeritaAcara', 'M_BeritaAcara');
        $this->load->model('M_Function');
        $this->load->model('M_MenuAccess');
        $this->load->model('M_Vrbl');
        $this->load->model('M_AutoGen');

        $this->load->helper(array('form', 'url'));
        $this->load->library(['form_validation', 'pdfgenerator']);
    }

    public function BeritaAcaraMenu()
    {
        $data = array();

        $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
        if ($data['Menu_Access']['R'] != 1) {
            redirect(base_url('MainPage'));
            exit();
        }

        $query['sidemenu'] = $this->M_Menu->GetMenu_Depo('', $this->session->userdata('pengguna_grup_id'));
        $query['Ses_UserName'] = $this->session->userdata('pengguna_username');

        $query['Title'] = Get_Title_Name();
        $query['Copyright'] = Get_Copyright_Name();

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

        // Kebutuhan Authority Menu 
        $this->session->set_userdata('MenuLink', str_replace(base_url(), '', current_url()));
        $data['principle'] = $this->M_BeritaAcara->GetDataPrinciple();

        $this->load->view('layouts/sidebar_header', $query);
        $this->load->view('WMS/BeritaAcara/index', $data);
        $this->load->view('layouts/sidebar_footer', $query);

        $this->load->view('master/S_GlobalVariable', $query);
        $this->load->view('WMS/BeritaAcara/script', $query);
    }

    public function GetDataFilter()
    {
        $data = array();

        $data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);
        if ($data['Menu_Access']['R'] != 1) {
            redirect(base_url('MainPage'));
            exit();
        }

        $principle_id = $this->input->post('principle');
        $tgl = explode(" - ", $this->input->post('tgl'));

        $tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
        $tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

        $data = $this->M_BeritaAcara->GetDataFilter($tgl1, $tgl2, $principle_id);

        echo json_encode($data);
    }

    public function GetListDOByShipment()
    {
        $shipment_number = $this->input->post('shipment_number');
        $principle_id = $this->input->post('principle_id');

        $data = $this->M_BeritaAcara->GetListDOByShipment($shipment_number, $principle_id);

        echo json_encode($data);
    }

    public function GetDataSelisihSKUDetailOri()
    {
        $penerimaan_surat_jalan_id = $this->input->post('penerimaan_surat_jalan_id');

        $data = $this->M_BeritaAcara->GetDataSelisihSKUDetailOri($penerimaan_surat_jalan_id);

        $arr = [];
        // mencari data yang selisih disimpan di array
        foreach ($data as $value) {
            if ($value['sku_jumlah_barang'] - $value['sku_jumlah_barang_terima'] != 0) {
                array_push($arr, ['sku_konversi_group' => $value['sku_konversi_group'], 'sku_nama_produk' => $value['sku_nama_produk'], 'sku_jumlah_barang' => $value['sku_jumlah_barang'], 'sku_jumlah_barang_terima' => $value['sku_jumlah_barang_terima']]);
            }
        }

        if (count($arr) == 0) {
            // update is_status penerimaan_surat_jalan
            $update = $this->M_BeritaAcara->UpdateIsStatusPSJ($penerimaan_surat_jalan_id, 2);
        }

        echo json_encode($arr);
    }

    public function GetDataSelisihSKUDetailTemp2()
    {
        $penerimaan_surat_jalan_id = $this->input->post('penerimaan_surat_jalan_id');

        $data = $this->M_BeritaAcara->GetDataSelisihSKUDetailOri($penerimaan_surat_jalan_id);

        $arr = [];
        // mencari data yang selisih disimpan di array
        foreach ($data as $value) {
            if ($value['sku_jumlah_barang'] - $value['sku_jumlah_barang_terima'] != 0) {
                array_push($arr, "'" . $value['sku_konversi_group'] . "'");
            }
        }

        // mencari data sku by data selisih dalam array
        $data2 = $this->M_BeritaAcara->GetDataSelisihSKUDetailTemp2($arr, $penerimaan_surat_jalan_id);

        if ($data2 == 0) {
            $this->M_BeritaAcara->UpdateIsStatusPSJ($penerimaan_surat_jalan_id, 1);
        }

        echo json_encode($data2);
    }

    public function UpdatePSJDetailTemp2()
    {
        $data = $this->input->post('sku');
        $penerimaan_surat_jalan_id = $this->input->post('penerimaan_surat_jalan_id');
        $tglUpdate = $this->input->post('tglUpdate');

        $lastUpdatedChecked = checkLastUpdatedData((object) [
            'table' => "penerimaan_surat_jalan",
            'whereField' => "penerimaan_surat_jalan_id",
            'whereValue' => $penerimaan_surat_jalan_id,
            'fieldDateUpdate' => "penerimaan_surat_jalan_tgl_update",
            'fieldWhoUpdate' => "penerimaan_surat_jalan_who_update",
            'lastUpdated' => $tglUpdate
        ]);

        if ($lastUpdatedChecked['status'] != 200) {
            echo json_encode(400);
        } else {
            $arr_sku = [];
            $arr_temp = [];
            $temp_penerimaan_surat_jalan_id = '';
            foreach ($data as $value) {
                $penerimaan_surat_jalan_id = $value['penerimaan_surat_jalan_id'];
                $sku = $value['sku_kode'];
                $terima = $value['terima'];
                $rusak = $value['rusak'];
                $barang_tertinggal = $value['barang_tertinggal'];
                $kardus_rusak = $value['kardus_rusak'];
                $total = $value['total'];
                $sumz = $value['sumz'];

                array_push($arr_temp, $sku);

                $temp_penerimaan_surat_jalan_id = $penerimaan_surat_jalan_id;

                $sisaQty = 0;
                $sisaQtyTerima = 0;
                $sisaRusak = 0;
                $sisaBarangTertinggal = 0;
                $query = $this->M_BeritaAcara->GetPSJDetailTemp2BySKUPSJ($penerimaan_surat_jalan_id, $sku);
                foreach ($query as $row) {
                    if ($sisaQtyTerima > 0) {
                        if ($sisaQtyTerima > $row['sku_jumlah_barang']) {
                            // CLEAR
                            $sisaQtyTerima = $sisaQtyTerima - $row['sku_jumlah_barang'];
                            array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $row['sku_jumlah_barang'], 'rusak' => 0, 'barang_tertinggal' => 0, 'kardus_rusak' => 0, 'total' => $row['sku_jumlah_barang'], 'sumz' => 0]);
                        } elseif ($sisaQtyTerima < $row['sku_jumlah_barang']) {
                            $sisaQty = $row['sku_jumlah_barang'] - $sisaQtyTerima;

                            if ($rusak > 0) {
                                // CLEAR
                                if ($rusak > $sisaQty) {
                                    // CLEAR
                                    $sisaRusak = $rusak - $sisaQty;
                                    array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $sisaQtyTerima, 'rusak' => $sisaQty, 'barang_tertinggal' => 0, 'kardus_rusak' => 0, 'total' => $sisaQtyTerima + $sisaQty, 'sumz' => $sisaQty]);
                                    // terima tanpa sisa
                                    $sisaQtyTerima = 0;
                                } elseif ($rusak < $sisaQty) {
                                    $sisaQty = $sisaQty - $rusak;

                                    if ($barang_tertinggal > $sisaQty) {
                                        // CLEAR
                                        $sisaBarangTertinggal = $barang_tertinggal - $sisaQty;
                                        array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $sisaQtyTerima, 'rusak' => $rusak, 'barang_tertinggal' => $sisaQty, 'kardus_rusak' => 0, 'total' => $sisaQtyTerima + $rusak + $sisaQty, 'sumz' => $rusak + $sisaQty]);
                                        $sisaQtyTerima = 0;
                                        $sisaRusak = 0;
                                    } elseif ($barang_tertinggal == $sisaQty) {
                                        // CLEAR
                                        array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $sisaQtyTerima, 'rusak' => $rusak, 'barang_tertinggal' => $sisaQty, 'kardus_rusak' => $kardus_rusak, 'total' => $sisaQtyTerima + $rusak + $sisaQty, 'sumz' => $rusak + $sisaQty + $kardus_rusak]);
                                        $sisaQtyTerima = 0;
                                        $sisaRusak = 0;
                                    }
                                } elseif ($rusak == $sisaQty) {
                                    // CLEAR
                                    if ($barang_tertinggal > 0) {
                                        // CLEAR
                                        $sisaBarangTertinggal = $barang_tertinggal;
                                        array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $sisaQtyTerima, 'rusak' => $rusak, 'barang_tertinggal' => 0, 'kardus_rusak' => 0, 'total' => $sisaQtyTerima + $rusak, 'sumz' => $rusak]);
                                    } else {
                                        // CLEAR
                                        array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $sisaQtyTerima, 'rusak' => $rusak, 'barang_tertinggal' => 0, 'kardus_rusak' => $kardus_rusak, 'total' => $sisaQtyTerima + $rusak, 'sumz' => $rusak + $kardus_rusak]);
                                    }
                                }
                            } elseif ($barang_tertinggal > 0) {
                                // CLEAR
                                if ($barang_tertinggal > $sisaQty) {
                                    // CLEAR
                                    $sisaBarangTertinggal = $barang_tertinggal - $sisaQty;
                                    array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $sisaQtyTerima, 'rusak' => 0, 'barang_tertinggal' => $sisaQty, 'kardus_rusak' => 0, 'total' => $sisaQtyTerima + $sisaQty, 'sumz' => $sisaQty]);
                                    // terima tanpa sisa
                                    $sisaQtyTerima = 0;
                                } elseif ($barang_tertinggal == $sisaQty) {
                                    // CLEAR
                                    array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $sisaQtyTerima, 'rusak' => 0, 'barang_tertinggal' => $sisaQty, 'kardus_rusak' => $kardus_rusak, 'total' => $sisaQtyTerima + $sisaQty, 'sumz' => $sisaQty + $kardus_rusak]);
                                    // terima tanpa sisa
                                    $sisaQtyTerima = 0;
                                }
                            }
                        } elseif ($sisaQtyTerima == $row['sku_jumlah_barang']) {
                            // CLEAR
                            if ($rusak > 0) {
                                // CLEAR
                                $sisaRusak = $rusak;
                                array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $row['sku_jumlah_barang'], 'rusak' => 0, 'barang_tertinggal' => 0, 'kardus_rusak' => $kardus_rusak, 'total' => $row['sku_jumlah_barang'], 'sumz' => $kardus_rusak]);
                                $sisaQtyTerima = 0;
                            } elseif ($barang_tertinggal > 0) {
                                // CLEAR
                                $sisaBarangTertinggal = $barang_tertinggal;
                                array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $row['sku_jumlah_barang'], 'rusak' => 0, 'barang_tertinggal' => 0, 'kardus_rusak' => $kardus_rusak, 'total' => $row['sku_jumlah_barang'], 'sumz' => $kardus_rusak]);
                                $sisaQtyTerima = 0;
                            } else {
                                // CLEAR
                                array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $row['sku_jumlah_barang'], 'rusak' => 0, 'barang_tertinggal' => 0, 'kardus_rusak' => $kardus_rusak, 'total' => $row['sku_jumlah_barang'], 'sumz' => $kardus_rusak]);
                                $sisaQtyTerima = 0;
                            }
                        }
                    } elseif ($sisaRusak > 0) {
                        // CLEAR
                        if ($sisaRusak > $row['sku_jumlah_barang']) {
                            // CLEAR
                            $sisaRusak = $sisaRusak - $row['sku_jumlah_barang'];
                            array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => 0, 'rusak' => $row['sku_jumlah_barang'], 'barang_tertinggal' => 0, 'kardus_rusak' => 0, 'total' => $row['sku_jumlah_barang'], 'sumz' => $row['sku_jumlah_barang']]);
                        } elseif ($sisaRusak < $row['sku_jumlah_barang']) {
                            // CLEAR
                            $sisaQty = $row['sku_jumlah_barang'] - $sisaRusak;

                            if ($barang_tertinggal == $sisaQty) {
                                // CLEAR
                                array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => 0, 'rusak' => $sisaRusak, 'barang_tertinggal' => $sisaQty, 'kardus_rusak' => $kardus_rusak, 'total' => $sisaRusak + $sisaQty, 'sumz' => $sisaRusak + $sisaQty + $kardus_rusak]);
                                $sisaRusak = 0;
                            } elseif ($barang_tertinggal > $sisaQty) {
                                // CLEAR
                                $sisaBarangTertinggal = $barang_tertinggal - $sisaQty;
                                array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => 0, 'rusak' => $sisaRusak, 'barang_tertinggal' => $sisaQty, 'kardus_rusak' => 0, 'total' => $sisaRusak + $sisaQty, 'sumz' => $sisaRusak + $sisaQty]);
                                $sisaRusak = 0;
                            }
                        } elseif ($sisaRusak == $row['sku_jumlah_barang']) {
                            // CLEAR
                            if ($barang_tertinggal > 0) {
                                $sisaBarangTertinggal = $barang_tertinggal;
                                array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => 0, 'rusak' => $row['sku_jumlah_barang'], 'barang_tertinggal' => 0, 'kardus_rusak' => 0, 'total' => $row['sku_jumlah_barang'], 'sumz' => $row['sku_jumlah_barang']]);
                            } else {
                                array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => 0, 'rusak' => $row['sku_jumlah_barang'], 'barang_tertinggal' => 0, 'kardus_rusak' => $kardus_rusak, 'total' => $row['sku_jumlah_barang'], 'sumz' => $row['sku_jumlah_barang'] + $kardus_rusak]);
                            }
                        }
                    } elseif ($sisaBarangTertinggal > 0) {
                        // CLEAR
                        if ($sisaBarangTertinggal == $row['sku_jumlah_barang']) {
                            array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => 0, 'rusak' => 0, 'barang_tertinggal' => $row['sku_jumlah_barang'], 'kardus_rusak' => $kardus_rusak, 'total' => $row['sku_jumlah_barang'], 'sumz' => $row['sku_jumlah_barang'] + $kardus_rusak]);
                            // barang tertinggal tanpa sisa
                            $sisaBarangTertinggal = 0;
                        } elseif ($sisaBarangTertinggal > $row['sku_jumlah_barang']) {
                            $sisaBarangTertinggal = $sisaBarangTertinggal - $row['sku_jumlah_barang'];
                            array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => 0, 'rusak' => 0, 'barang_tertinggal' => $row['sku_jumlah_barang'], 'kardus_rusak' => 0, 'total' => $row['sku_jumlah_barang'], 'sumz' => $row['sku_jumlah_barang']]);
                        }
                    } else {
                        // CEK CEK GAISSS (KELAR)
                        if ($terima > $row['sku_jumlah_barang']) {
                            // CLEAR
                            $sisaQtyTerima = $terima - $row['sku_jumlah_barang'];
                            array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $row['sku_jumlah_barang'], 'rusak' => 0, 'barang_tertinggal' => 0, 'kardus_rusak' => 0, 'total' => $row['sku_jumlah_barang'], 'sumz' => 0]);
                        } elseif ($terima < $row['sku_jumlah_barang']) {
                            // CLEAR
                            $sisaQty = $row['sku_jumlah_barang'] - $terima;

                            if ($rusak > 0) {
                                if ($rusak > $sisaQty) {
                                    // CLEAR
                                    $sisaRusak = $rusak - $sisaQty;
                                    array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => $sisaQty, 'barang_tertinggal' => 0, 'kardus_rusak' => 0, 'total' => $terima + $sisaQty, 'sumz' => $sisaQty]);
                                } elseif ($rusak < $sisaQty) {
                                    // CLEAR
                                    $sisaQty = $sisaQty - $rusak;
                                    if ($barang_tertinggal > $sisaQty) {
                                        $sisaBarangTertinggal = $barang_tertinggal - $sisaQty;
                                        array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => $rusak, 'barang_tertinggal' => $sisaQty, 'kardus_rusak' => 0, 'total' => $terima + $rusak + $sisaQty, 'sumz' => $rusak + $sisaQty]);
                                    } elseif ($barang_tertinggal == $sisaQty) {
                                        array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => $rusak, 'barang_tertinggal' => $sisaQty, 'kardus_rusak' => $kardus_rusak, 'total' => $terima + $rusak + $sisaQty, 'sumz' => $rusak + $sisaQty + $kardus_rusak]);
                                    }
                                } elseif ($rusak == $sisaQty) {
                                    // CLEAR
                                    if ($barang_tertinggal > 0) {
                                        $sisaBarangTertinggal = $barang_tertinggal;
                                        array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => $rusak, 'barang_tertinggal' => 0, 'kardus_rusak' => 0, 'total' => $terima + $rusak, 'sumz' => $rusak]);
                                    } else {
                                        array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => $rusak, 'barang_tertinggal' => 0, 'kardus_rusak' => $kardus_rusak, 'total' => $terima + $rusak, 'sumz' => $rusak + $kardus_rusak]);
                                    }
                                }
                            } elseif ($barang_tertinggal > 0) {
                                // CLEAR
                                if ($barang_tertinggal > $sisaQty) {
                                    $sisaBarangTertinggal = $barang_tertinggal - $sisaQty;
                                    array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => $rusak, 'barang_tertinggal' => $sisaQty, 'kardus_rusak' => 0, 'total' => $terima + $rusak + $sisaQty, 'sumz' => $rusak + $sisaQty]);
                                } elseif ($barang_tertinggal == $sisaQty) {
                                    array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => $rusak, 'barang_tertinggal' => $sisaQty, 'kardus_rusak' => $kardus_rusak, 'total' => $terima + $rusak + $sisaQty, 'sumz' => $rusak + $sisaQty + $kardus_rusak]);
                                }
                            }
                        } elseif ($terima == $row['sku_jumlah_barang']) {
                            if ($rusak > 0) {
                                // CLEAR
                                if ($rusak > $sisaQty) {
                                    // CLEAR
                                    $sisaRusak = $rusak - $sisaQty;
                                    array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => $sisaQty, 'barang_tertinggal' => 0, 'kardus_rusak' => 0, 'total' => $terima + $sisaQty, 'sumz' => $sisaQty]);
                                } elseif ($rusak < $sisaQty) {
                                    // CLEAR
                                    $sisaQty = $sisaQty - $rusak;
                                    if ($barang_tertinggal > $sisaQty) {
                                        $sisaBarangTertinggal = $barang_tertinggal - $sisaQty;
                                        array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => $rusak, 'barang_tertinggal' => $sisaQty, 'kardus_rusak' => 0, 'total' => $terima + $rusak + $sisaQty, 'sumz' => $rusak + $sisaQty]);
                                    } elseif ($barang_tertinggal == $sisaQty) {
                                        array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => $rusak, 'barang_tertinggal' => $sisaQty, 'kardus_rusak' => $kardus_rusak, 'total' => $terima + $rusak + $sisaQty, 'sumz' => $rusak + $sisaQty + $kardus_rusak]);
                                    }
                                } elseif ($rusak == $sisaQty) {
                                    // CLEAR
                                    if ($barang_tertinggal > 0) {
                                        $sisaBarangTertinggal = $barang_tertinggal;
                                        array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => $rusak, 'barang_tertinggal' => 0, 'kardus_rusak' => 0, 'total' => $terima + $rusak, 'sumz' => $rusak]);
                                    } else {
                                        array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => $rusak, 'barang_tertinggal' => 0, 'kardus_rusak' => $kardus_rusak, 'total' => $terima + $rusak, 'sumz' => $rusak + $kardus_rusak]);
                                    }
                                }
                            } elseif ($barang_tertinggal > 0) {
                                // CLEAR
                                if ($barang_tertinggal > $sisaQty) {
                                    $sisaBarangTertinggal = $barang_tertinggal - $sisaQty;
                                    array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => $rusak, 'barang_tertinggal' => $sisaQty, 'kardus_rusak' => 0, 'total' => $terima + $rusak + $sisaQty, 'sumz' => $rusak + $sisaQty]);
                                } elseif ($barang_tertinggal == $sisaQty) {
                                    array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => $rusak, 'barang_tertinggal' => $sisaQty, 'kardus_rusak' => $kardus_rusak, 'total' => $terima + $rusak + $sisaQty, 'sumz' => $rusak + $sisaQty + $kardus_rusak]);
                                }
                            } else {
                                // CLEAR
                                array_push($arr_sku, ['penerimaan_surat_jalan_detail_temp2_id' => $row['penerimaan_surat_jalan_detail_temp2_id'], 'sku_kode' => $row['sku_kode'], 'sku_jumlah_barang' => $row['sku_jumlah_barang'], 'terima' => $terima, 'rusak' => 0, 'barang_tertinggal' => 0, 'kardus_rusak' => $kardus_rusak, 'total' => $terima, 'sumz' => $kardus_rusak]);
                            }
                        }
                    }
                }
            }

            $this->db->trans_begin();

            $updatePSJ = $this->M_BeritaAcara->UpdateIsStatusPSJ($temp_penerimaan_surat_jalan_id, 1);

            foreach ($arr_sku as $value) {
                $penerimaan_surat_jalan_detail_temp2_id = $value['penerimaan_surat_jalan_detail_temp2_id'];
                $sku_kode = $value['sku_kode'];
                $sku_jumlah_barang = $value['sku_jumlah_barang'];
                $terima = $value['terima'];
                $rusak = $value['rusak'];
                $barang_tertinggal = $value['barang_tertinggal'];
                $kardus_rusak = $value['kardus_rusak'];
                $total = $value['total'];
                $sumz = $value['sumz'];

                $query = $this->M_BeritaAcara->UpdatePSJDetailTemp2($penerimaan_surat_jalan_detail_temp2_id, $terima, $rusak, $barang_tertinggal, $kardus_rusak, $total, $sumz);
            }

            $updatePSJDetailTemp2SKU = $this->M_BeritaAcara->updatePSJDetailTemp2SKU($arr_temp, $temp_penerimaan_surat_jalan_id);

            echo json_encode(responseJson((object)[
                'lastUpdatedChecked' => $lastUpdatedChecked,
                'status' => 'Diupdate'
            ]));
        }
    }

    public function GetDOPSJDetailTemp2()
    {
        $penerimaan_surat_jalan_id = $this->input->post('penerimaan_surat_jalan_id');

        $data = $this->M_BeritaAcara->GetDOPSJDetailTemp2($penerimaan_surat_jalan_id);

        echo json_encode($data);
    }

    public function ExportPDF($id)
    {
        $data['header'] = $this->M_BeritaAcara->GetHeaderPDF($id);
        $data['contens'] = $this->M_BeritaAcara->GetContensPDF($id);

        $file_pdf = "Berita Acara";

        //setting paper
        $paper = 'A4';

        //orientasi paper potrait / landscape
        $orientation = "portrait";

        $html = $this->load->view('WMS/BeritaAcara/view_export_pdf', $data, true);

        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }

    public function ExportBosnet()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $penerimaan_surat_jalan_id = $this->input->get('id');
        $penerimaan_surat_jalan_no_sj = $this->input->get('do');

        $pricipal = $this->M_BeritaAcara->GetHeaderPDF($penerimaan_surat_jalan_id);
        $sku_by_do = $this->M_BeritaAcara->GetSKUPSJDetailTemp2BYDO($penerimaan_surat_jalan_id, $penerimaan_surat_jalan_no_sj);

        $sheet->setCellValue('A1', 'ID Pricipal');
        $sheet->setCellValue('B1', 'Nama Pricipal');
        $sheet->setCellValue('C1', 'ID Produk');
        $sheet->setCellValue('D1', 'Nama Produk');
        $sheet->setCellValue('E1', 'Tanggal CO');
        $sheet->setCellValue('F1', 'ID CO');
        $sheet->setCellValue('G1', 'Total (Dalam Satuan Besar)');
        $sheet->setCellValue('H1', 'Harga Satuan');
        $sheet->setCellValue('I1', 'Tujuan(Depo)');

        $fileName = '';
        $numrow = 2;
        foreach ($sku_by_do as $value) {
            $fileName = "" . $value['penerimaan_surat_jalan_no_sj'] . ".xlsx";
            $data = $this->M_BeritaAcara->GetHargaSatuanBosnet($value['sku_kode']);

            $sheet->setCellValue('A' . $numrow, $pricipal['depo_eksternal_kode'] . '-UMI-SGB');
            $sheet->setCellValue('B' . $numrow, $pricipal['client_wms_nama']);
            $sheet->setCellValue('C' . $numrow, $value['sku_kode']);
            $sheet->setCellValue('D' . $numrow, $value['sku_nama_produk']);
            $sheet->setCellValue('E' . $numrow, $pricipal['tgl3']);
            $sheet->setCellValue('F' . $numrow, $value['penerimaan_surat_jalan_no_sj']);
            $sheet->setCellValue('G' . $numrow, $value['terima']);
            $sheet->setCellValue('H' . $numrow, $data['harga_satuan']);
            $sheet->setCellValue('I' . $numrow, $pricipal['depo_eksternal_kode']);

            $numrow++; // Tambah 1 setiap kali looping
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $fileName . ''); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        $slc = $this->M_BeritaAcara->GetIsDownloadPSJDetailTemp2($penerimaan_surat_jalan_id, $penerimaan_surat_jalan_no_sj);

        if ($slc['is_download'] != 0) {
            if ($slc['is_download'] == 1) {
                $update = $this->M_BeritaAcara->UpdateIsDownloadPSJDetailTemp2($penerimaan_surat_jalan_id, $penerimaan_surat_jalan_no_sj, 1);
            } else if ($slc['is_download'] == 2) {
                $update = $this->M_BeritaAcara->UpdateIsDownloadPSJDetailTemp2($penerimaan_surat_jalan_id, $penerimaan_surat_jalan_no_sj, 3);
            }
        } else {
            $update = $this->M_BeritaAcara->UpdateIsDownloadPSJDetailTemp2($penerimaan_surat_jalan_id, $penerimaan_surat_jalan_no_sj, 1);
        }
    }

    public function ExportPOD()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tgl = date("Ymd_His");

        $penerimaan_surat_jalan_id = $this->input->get('id');
        $penerimaan_surat_jalan_no_sj = $this->input->get('do');

        // nama file hasil export
        $fileName = "POD_" . $penerimaan_surat_jalan_no_sj . "_" . $tgl . ".txt";

        // karakter separator
        $separator = "|";
        $isiText = '';

        // header file text (EXPORT FILE TXT LOCAL)
        header("Content-type: text/plain");
        header('Content-Disposition: attachment; filename=' . $fileName);

        echo "TRANS_NUMBER" . $separator . "TRANSACTION_ID" . $separator . "TRANSACTION_LINE" . $separator . "RECEIPT_DATE" . $separator . "ZDE_BOSWERKS" . $separator . "SGB_DELIVERY_NUMBER" . $separator . "DO_LINE_NUMBER" . $separator . "FB_PO_NUMBER" . $separator . "FB_PO_LINE_NUMBER" . $separator . "SGB_ITEM_CODE" . $separator . "PRIMARY_QUANTITY" . $separator . "UOM_CODE" . $separator . "TRANSACTION_ID_REF" . $separator . "TRANSACTION_LINE_REF" . $separator . "Z001" . $separator . "Z002" . $separator . "Z003" . $separator . "Z004" . $separator . "Z005" . $separator . "Z006" . $separator . "Z007" . $separator . "Z008" . $separator . "Z009" . $separator . "Z010" . $separator . "SUM_Z" . $separator . "CHECK_IN_DATE" . $separator . "CHECK_IN_TIME" . "\r\n";

        // getdata 
        $tgl_terima = $this->M_BeritaAcara->GetHeaderPDF($penerimaan_surat_jalan_id);
        $query = $this->M_BeritaAcara->GetDataPOD($penerimaan_surat_jalan_id, $penerimaan_surat_jalan_no_sj);

        foreach ($query as $value) {
            $id = $value['id'];
            $transaction_number = $value['transaction_number'];
            $depo_eksternal_kode = $value['depo_eksternal_kode'];
            $penerimaan_surat_jalan_no_sj = $value['penerimaan_surat_jalan_no_sj'];
            $delivery_order_line_number = $value['delivery_order_line_number'];
            $sku_kode = $value['sku_kode'];
            $terima = $value['terima'];
            $sku_satuan = $value['sku_satuan'];
            $rusak = $value['rusak'];
            $barang_tertinggal = $value['barang_tertinggal'];
            $kardus_rusak = $value['kardus_rusak'];
            $sumz = $value['sumz'];
            $penerimaan_surat_jalan_tgl = $value['penerimaan_surat_jalan_tgl'];

            echo $id . $separator . $id . $separator . $transaction_number . $separator . $tgl_terima['tgl3'] . $separator . $depo_eksternal_kode . $separator . $penerimaan_surat_jalan_no_sj . $separator . $delivery_order_line_number . $separator . $id . $separator . $transaction_number . $separator . $sku_kode . $separator . $terima . $separator . $sku_satuan . $separator . $id . $separator . $transaction_number . $separator . $rusak . $separator . "0" . $separator . $barang_tertinggal . $separator . "0" . $separator . "0" . $separator . "0" . $separator . "0" . $separator . "0" . $separator . "0" . $separator . $kardus_rusak . $separator . $sumz . $separator . $penerimaan_surat_jalan_tgl . $separator . $penerimaan_surat_jalan_tgl . "\r\n";
            $isiText .= $id . $separator . $id . $separator . $transaction_number . $separator . $tgl_terima['tgl3'] . $separator . $depo_eksternal_kode . $separator . $penerimaan_surat_jalan_no_sj . $separator . $delivery_order_line_number . $separator . $id . $separator . $transaction_number . $separator . $sku_kode . $separator . $terima . $separator . $sku_satuan . $separator . $id . $separator . $transaction_number . $separator . $rusak . $separator . "0" . $separator . $barang_tertinggal . $separator . "0" . $separator . "0" . $separator . "0" . $separator . "0" . $separator . "0" . $separator . "0" . $separator . $kardus_rusak . $separator . $sumz . $separator . $penerimaan_surat_jalan_tgl . $separator . $penerimaan_surat_jalan_tgl . "\r\n";
        }

        $slc = $this->M_BeritaAcara->GetIsDownloadPSJDetailTemp2($penerimaan_surat_jalan_id, $penerimaan_surat_jalan_no_sj);

        if ($slc['is_download'] != 0) {
            if ($slc['is_download'] == 1) {
                $update = $this->M_BeritaAcara->UpdateIsDownloadPSJDetailTemp2($penerimaan_surat_jalan_id, $penerimaan_surat_jalan_no_sj, 3);
            } else if ($slc['is_download'] == 2) {
                $update = $this->M_BeritaAcara->UpdateIsDownloadPSJDetailTemp2($penerimaan_surat_jalan_id, $penerimaan_surat_jalan_no_sj, 2);
            }
        } else {
            $update = $this->M_BeritaAcara->UpdateIsDownloadPSJDetailTemp2($penerimaan_surat_jalan_id, $penerimaan_surat_jalan_no_sj, 2);
        }

        // EXPORT FILE TXT KE SERVER
        $filePathName = "assets/images/uploads/Temp_POD/POD_" . $penerimaan_surat_jalan_no_sj . "_" . $tgl . ".txt";
        $file = fopen("$filePathName", "w");
        fwrite($file, "TRANS_NUMBER" . $separator . "TRANSACTION_ID" . $separator . "TRANSACTION_LINE" . $separator . "RECEIPT_DATE" . $separator . "ZDE_BOSWERKS" . $separator . "SGB_DELIVERY_NUMBER" . $separator . "DO_LINE_NUMBER" . $separator . "FB_PO_NUMBER" . $separator . "FB_PO_LINE_NUMBER" . $separator . "SGB_ITEM_CODE" . $separator . "PRIMARY_QUANTITY" . $separator . "UOM_CODE" . $separator . "TRANSACTION_ID_REF" . $separator . "TRANSACTION_LINE_REF" . $separator . "Z001" . $separator . "Z002" . $separator . "Z003" . $separator . "Z004" . $separator . "Z005" . $separator . "Z006" . $separator . "Z007" . $separator . "Z008" . $separator . "Z009" . $separator . "Z010" . $separator . "SUM_Z" . $separator . "CHECK_IN_DATE" . $separator . "CHECK_IN_TIME" . "\r\n");
        fwrite($file, $isiText);

        // upload FTP SUNTORY
        $ftp_server = "ftp.suntorygaruda.id"; // Address of FTP server.
        $ftp_user_name = "padma.live"; // Username
        $ftp_user_pass = 'SuntoryID!'; // Password

        $conn_id = ftp_connect($ftp_server) or die("<span style='color:#FF0000'><h2>Couldn't connect to $ftp_server</h2></span>");        // set up basic connection
        ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<span style='color:#FF0000'><h2>You do not have access to this ftp server!</h2></span>");   // login with username and password, or give invalid user message

        ftp_set_option($conn_id, FTP_USEPASVADDRESS, false);

        ftp_pasv($conn_id, true);

        //File POD
        $local_file = "assets/images/uploads/Temp_POD"; //where you want to throw the file on the webserver (relative to your login dir)
        $destination_file = "/process/OUTBOUND/INVOICE";
        $filesFix = "POD_" . $penerimaan_surat_jalan_no_sj . "_" . $tgl . ".txt"; //where you want to throw the file on the webserver (relative to your login dir)

        if (ftp_put($conn_id, "$destination_file/$filesFix", "$local_file/$filesFix", FTP_BINARY)) {
            // hapus file
            unlink("$local_file/$filesFix");
        } else {
        }
        ftp_close($conn_id);
    }
}
