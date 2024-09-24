<?php

class M_Bosnet extends CI_Model
{
    function __construct()
    {
        parent::__construct();

        $this->depo_eksternal = "";

        $query = $this->db->query("SELECT * FROM depo_eksternal WHERE depo_id = '" . $this->session->userdata('depo_id') . "'");


        if ($query->num_rows() == 0) {
            $this->depo_eksternal = "";
        } else {
            $this->depo_eksternal = $query->row(0)->depo_eksternal_kode;
        }
    }

    public function Get_pengemudi()
    {
        $bosnet = $this->load->database('bosnet', TRUE);

        $query = $bosnet->query("SELECT szEmployeeId, szName FROM BOS_PI_Employee WHERE szDivisionId IN ('SALES','DRIVER') AND szWorkplaceId = '$this->depo_eksternal' ORDER BY szName ASC");


        if ($query->num_rows() == 0) {
            $query = array();
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_gudang()
    {
        $bosnet = $this->load->database('bosnet', TRUE);

        $query = $bosnet->query("SELECT szWarehouseId, szName FROM BOS_INV_Warehouse WHERE szWorkplaceId = '$this->depo_eksternal' ORDER BY szWarehouseId ASC");


        if ($query->num_rows() == 0) {
            $query = array();
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_Principle()
    {
        $query = $this->db->query("SELECT
										*
									FROM principle
                                    WHERE ISNULL(principle_is_aktif, 0) = '1'
									ORDER BY principle_kode ASC");


        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_bosnet_do_by_filter($tgl1, $tgl2, $karyawan_eksternal_id, $rit, $gudang)
    {
        set_time_limit(0);

        $bosnet = $this->load->database('bosnet', TRUE);
        $middleware = $this->load->database('middleware', TRUE);

        $arr_so_id = array();
        $arr_so_id_str = "";

        if ($karyawan_eksternal_id == "") {
            $karyawan_eksternal_id = "";
        } else {
            $karyawan_eksternal_id = "AND ISNULL(kse.sales_eksternal_id, '') = '$karyawan_eksternal_id' ";
        }

        $middleware->query("delete from bosnet_do");

        $query_fdrj = $this->db->query("select
                                        fdjr.karyawan_id,
                                        ISNULL(kse.sales_eksternal_id, '') AS sales_eksternal_id,
                                        FORMAT(fdjr.delivery_order_batch_tanggal_kirim,'yyyy-MM-dd') as delivery_order_batch_tanggal_kirim,
                                        ISNULL(so.sales_order_no_po, '') AS sales_order_no_po
                                    from delivery_order_batch fdjr
                                    left join delivery_order do
                                    on do.delivery_order_batch_id = fdjr.delivery_order_batch_id
                                    left join sales_order so
                                    on so.sales_order_id = do.sales_order_id
                                    left join BACKEND.dbo.karyawan_sales_eksternal kse
                                    on kse.karyawan_id = fdjr.karyawan_id
                                    and kse.sistem_eksternal = 'BOSNET'
                                    where FORMAT(fdjr.delivery_order_batch_tanggal_kirim,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
                                    AND fdjr.depo_id = '" . $this->session->userdata('depo_id') . "'
                                    " . $karyawan_eksternal_id . "
                                    AND ISNULL(kse.sales_eksternal_id, '') <> '' 
                                    AND ISNULL(so.sales_order_no_po, '') <> ''
                                    group by fdjr.karyawan_id,
                                        ISNULL(kse.sales_eksternal_id, ''),
                                        FORMAT(fdjr.delivery_order_batch_tanggal_kirim,'yyyy-MM-dd'),
                                        ISNULL(so.sales_order_no_po, '')");

        if ($query_fdrj->num_rows() == 0) {
            $query_fdrj = array();
        } else {
            $query_fdrj = $query_fdrj->result_array();

            foreach ($query_fdrj as $key => $value) {
                array_push($arr_so_id, "'" . $value['sales_order_no_po'] . "'");
            }

            $arr_so_id_str = implode(",", $arr_so_id);

            $query_so_bosnet = $bosnet->query("SELECT * FROM BOS_SD_FDo WHERE szFSoId IN (" . $arr_so_id_str . ")");

            if ($query_so_bosnet->num_rows() == 0) {
                $query_so_bosnet = array();
            } else {
                $query_so_bosnet = $query_so_bosnet->result_array();
            }

            foreach ($query_so_bosnet as $key => $value) {
                $szFDoId = $value['szDoId'] == "" ? null : $value['szDoId'];
                $szFSoId = $value['szFSoId'] == "" ? null : $value['szFSoId'];
                $dtmDelivery = $value['dtmDelivery'] == "" ? null : $value['dtmDelivery'];
                $szOrderTypeId = $value['szOrderTypeId'] == "" ? null : $value['szOrderTypeId'];
                $szVehicleId = $value['szVehicleId'] == "" ? null : $value['szVehicleId'];
                $szDriverId = $value['szDriverId'] == "" ? null : $value['szDriverId'];
                $szSalesId = $value['szSalesId'] == "" ? null : $value['szSalesId'];
                $dtmCreated = $value['dtmCreated'] == "" ? null : $value['dtmCreated'];
                $dtmLastUpdated = $value['dtmLastUpdated'] == "" ? null : $value['dtmLastUpdated'];
                $szWorkplaceId = $value['szWorkplaceId'] == "" ? null : $value['szWorkplaceId'];

                $middleware->set("bosnet_do_id", "NEWID()", FALSE);
                $middleware->set("szFDoId", $szFDoId);
                $middleware->set("szFSoId", $szFSoId);
                $middleware->set("dtmDelivery", $dtmDelivery);
                $middleware->set("szOrderTypeId", $szOrderTypeId);
                $middleware->set("szVehicleId", $szVehicleId);
                $middleware->set("szDriverId", $szDriverId);
                $middleware->set("szSalesId", $szSalesId);
                $middleware->set("dtmCreated", $dtmCreated);
                $middleware->set("dtmLastUpdated", $dtmLastUpdated);
                $middleware->set("szWorkplaceId", $szWorkplaceId);

                $middleware->insert("bosnet_do");
            }
        }

        $query = $this->db->query("select
                                        fdjr.karyawan_id,
                                        ISNULL(kse.sales_eksternal_id, '') AS sales_eksternal_id,
                                        FORMAT(fdjr.delivery_order_batch_tanggal_kirim,'dd-MM-yyyy') as delivery_order_batch_tanggal_kirim,
                                        ISNULL(so.sales_order_no_po, '') AS sales_order_no_po,
                                        ISNULL(bdo.szFDoId, '') as do_id,
                                        ISNULL(bdo.szVehicleId, '') as kendaraan_nopol,
                                        '$rit' as rit,
                                        '$gudang' as gudang
                                    from delivery_order_batch fdjr
                                    left join delivery_order do
                                    on do.delivery_order_batch_id = fdjr.delivery_order_batch_id
                                    left join sales_order so
                                    on so.sales_order_id = do.sales_order_id
                                    LEFT JOIN MIDDLEWARE.dbo.bosnet_do bdo
                                    ON bdo.szFSoId = so.sales_order_no_po
                                    left join BACKEND.dbo.karyawan_sales_eksternal kse
                                    on kse.karyawan_id = fdjr.karyawan_id
                                    and kse.sistem_eksternal = 'BOSNET'
                                    where FORMAT(fdjr.delivery_order_batch_tanggal_kirim,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
                                    AND fdjr.depo_id = '" . $this->session->userdata('depo_id') . "'
                                    " . $karyawan_eksternal_id . "
                                    AND ISNULL(kse.sales_eksternal_id, '') <> '' 
                                    AND ISNULL(so.sales_order_no_po, '') <> ''
                                    group by fdjr.karyawan_id,
                                            ISNULL(kse.sales_eksternal_id, ''),
                                            FORMAT(fdjr.delivery_order_batch_tanggal_kirim,'dd-MM-yyyy'),
                                            ISNULL(so.sales_order_no_po, ''),
                                            ISNULL(bdo.szFDoId, ''),
                                            ISNULL(bdo.szVehicleId, '')");

        if ($query->num_rows() == 0) {
            $query = array();
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_bosnet_penerimaan_surat_jalan_by_filter($tgl1, $tgl2, $principle_id, $principle_kode)
    {
        set_time_limit(0);

        $bosnet = $this->load->database('bosnet', TRUE);
        $middleware = $this->load->database('middleware', TRUE);

        if ($principle_id == "") {
            $principle_id = "";
        } else {
            $principle_id = "AND sku.principle_id = '$principle_id' ";
        }

        // $middleware->query("delete from bosnet_sku_by_perbusahaan where Kode_Depo = '$this->depo_eksternal'");

        $query_so_bosnet = $bosnet->query("SELECT supp.szSuppId AS ID_Principle,
                                                    supp.szName AS Nama_Principle,
                                                    supp.szWorkplaceId AS Kode_Depo,
                                                    supp_prod.szProductId AS ID_Produk,
                                                    supp_prod.szRefProductName AS Nama_Produk,
                                                    CASE 
                                                        WHEN price.szTaxTypeId = 'PPN' THEN 
                                                            ISNULL(price.decBasePurchPrice_S,0) * 1.11
                                                        ELSE 
                                                            price.decBasePurchPrice_S 
                                                        END AS Harga_Satuan,
                                                    price.szTaxTypeId AS Tipe
                                                FROM BOS_AP_Supplier supp
                                                LEFT JOIN BOS_AP_SuppProduct supp_prod ON supp.szSuppId=supp_prod.szSuppId
                                                LEFT JOIN BOS_INV_ProductPurchaseInfo price ON supp_prod.szProductId=price.szProductId
                                                LEFT JOIN BOS_INV_Product prod ON prod.szProductId = supp_prod.szProductId
                                                WHERE supp_prod.szProductId IS NOT NULL
                                                AND supp.szWorkplaceId = '$this->depo_eksternal'
                                                AND prod.szCategory_1 = '$principle_kode'
                                                AND ISNULL(supp.szNPWP,'') <> ''");

        if ($query_so_bosnet->num_rows() == 0) {
            $query_so_bosnet = array();
        } else {
            $query_so_bosnet = $query_so_bosnet->result_array();
        }

        foreach ($query_so_bosnet as $key => $value) {
            $ID_Principle = $value['ID_Principle'] == "" ? null : $value['ID_Principle'];
            $Nama_Principle = $value['Nama_Principle'] == "" ? null : $value['Nama_Principle'];
            $Kode_Depo = $value['Kode_Depo'] == "" ? null : $value['Kode_Depo'];
            $ID_Produk = $value['ID_Produk'] == "" ? null : $value['ID_Produk'];
            $Nama_Produk = $value['Nama_Produk'] == "" ? null : $value['Nama_Produk'];
            $Harga_Satuan = $value['Harga_Satuan'] == "" ? null : $value['Harga_Satuan'];
            $Tipe = $value['Tipe'] == "" ? null : $value['Tipe'];

            $middleware->query("exec proses_bosnet_sku_by_perbusahaan '$ID_Principle','$Nama_Principle','$Kode_Depo','$ID_Produk','$Nama_Produk','$Harga_Satuan','$Tipe'");

            // $middleware->set("bosnet_sku_id", "NEWID()", FALSE);
            // $middleware->set("ID_Principle", $ID_Principle);
            // $middleware->set("Nama_Principle", $Nama_Principle);
            // $middleware->set("Kode_Depo", $Kode_Depo);
            // $middleware->set("ID_Produk", $ID_Produk);
            // $middleware->set("Nama_Produk", $Nama_Produk);
            // $middleware->set("Harga_Satuan", $Harga_Satuan);
            // $middleware->set("Tipe", $Tipe);

            // $middleware->insert("bosnet_sku_by_perbusahaan");
        }

        $query = $this->db->query("select 
                                        hdr.depo_id,
                                        de.depo_eksternal_kode,
                                        sku.principle_id,
                                        bosnet.ID_Principle AS principle_kode,
                                        bosnet.Nama_Principle AS principle_nama,
                                        sku.sku_konversi_group,
                                        sku.sku_nama_produk,
                                        hdr.penerimaan_surat_jalan_id,
                                        hdr.penerimaan_surat_jalan_kode,
										hdr.penerimaan_surat_jalan_no_sj,
                                        FORMAT(hdr.penerimaan_surat_jalan_tgl, 'dd/MM/yyyy') AS penerimaan_surat_jalan_tgl,
                                        SUM(ISNULL(dtl.sku_jumlah_barang, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS sku_jumlah_barang,
                                        ISNULL(bosnet.Harga_Satuan, 0) AS sku_harga
                                    from penerimaan_surat_jalan hdr
                                    left join penerimaan_surat_jalan_detail dtl
                                    on dtl.penerimaan_surat_jalan_id = hdr.penerimaan_surat_jalan_id
                                    left join sku
                                    on sku.sku_id = dtl.sku_id
									left join MIDDLEWARE.dbo.bosnet_sku_by_perbusahaan bosnet
									on bosnet.ID_Produk = sku.sku_konversi_group
                                    left join principle p
                                    on p.principle_id = sku.principle_id
                                    left join depo_eksternal de
                                    on de.depo_id = hdr.depo_id
									where FORMAT(hdr.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd') between '$tgl1' and '$tgl2' 
                                    and hdr.depo_id = '" . $this->session->userdata('depo_id') . "'
                                    and bosnet.ID_Produk IS NOT NULL
                                    " . $principle_id . "
                                    group by hdr.depo_id,
                                        de.depo_eksternal_kode,
                                        sku.principle_id,
                                        bosnet.ID_Principle,
                                        bosnet.Nama_Principle,
                                        sku.sku_konversi_group,
                                        sku.sku_nama_produk,
                                        hdr.penerimaan_surat_jalan_id,
                                        hdr.penerimaan_surat_jalan_kode,
										hdr.penerimaan_surat_jalan_no_sj,
                                        FORMAT(hdr.penerimaan_surat_jalan_tgl, 'dd/MM/yyyy'),
										ISNULL(bosnet.Harga_Satuan, 0)
                                    order by FORMAT(hdr.penerimaan_surat_jalan_tgl, 'dd/MM/yyyy') DESC, sku.sku_konversi_group ASC");

        if ($query->num_rows() == 0) {
            $query = array();
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_perusahaan()
    {
        // $this->db->select("*")
        // 	->from("client_wms")
        // 	->order_by("client_wms_nama");
        // $query = $this->db->get();

        if ($this->session->userdata('client_wms_id') == "") {

            $query = $this->db->query("SELECT b.*
						FROM depo_client_wms a
						LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
						WHERE a.depo_id = '" . $this->session->userdata('depo_id') . "'
						AND b.client_wms_is_aktif = 1
						AND b.client_wms_is_deleted = 0");
        } else {


            $query = $this->db->query("SELECT b.*
						FROM depo_client_wms a
						LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
						WHERE a.client_wms_id = '" . $this->session->userdata('client_wms_id') . "'
						AND a.depo_id = '" . $this->session->userdata('depo_id') . "'
						AND b.client_wms_is_aktif = 1
						AND b.client_wms_is_deleted = 0");
        }

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_laporan_stock_aging($tglawal, $tglakhir, $client_wms_id, $principle_id, $depo_detail_id, $tipe)
    {
        set_time_limit(0);

        $data = array();
        $list_kolom = array();

        $query_laporan =  $this->db->query("exec report_sku_stock_aging_by_group '$tglawal','$tglakhir','$client_wms_id','" . $this->session->userdata('depo_id') . "','$principle_id','$depo_detail_id','$tipe'");

        if ($query_laporan->num_rows() == 0) {
            $query_laporan = array();
        } else {
            $query_laporan = $query_laporan->result_array();

            $query_kolom =  $this->db->query("exec generate_kolom_stock_aging '$tglawal','$tglakhir','$tipe'");

            foreach ($query_laporan as $key => $value) {

                $query_kolom =  $this->db->query("exec generate_kolom_stock_aging '$tglawal','$tglakhir','$tipe'");
                $sku_composite =  $this->db->query("exec proses_konversi_sku_composite_simple '" . $value['sku_konversi_group'] . "'")->row(0)->sku_composite;

                array_push($data, array(
                    'principle_id' => $value['principle_id'],
                    'principle' => $value['principle'],
                    'principle_brand_id' => $value['principle_brand_id'],
                    'brand' => $value['brand'],
                    'sku_nama_produk' => $value['sku_nama_produk'],
                    'sku_konversi_group' => $value['sku_konversi_group'],
                ));

                $data[$key]['sku_satuan'] = $sku_composite;

                foreach ($query_kolom->result_array() as $key2 => $value2) {

                    $sku_composite_qty =  $this->db->query("exec proses_konversi_sku_qty_composite_simple '" . $value['sku_konversi_group'] . "', " . $value[$value2['kolom']] . ", 1")->row(0)->sku_composite_qty;

                    $data[$key][$value2['kolom']] = $sku_composite_qty;
                }
            }
        }

        return $data;
    }

    public function Generate_kolom_stock_aging($tglawal, $tglakhir, $tipe)
    {
        $list_kolom = array();

        $query_kolom =  $this->db->query("exec generate_kolom_stock_aging '$tglawal','$tglakhir','$tipe'");

        if ($query_kolom->num_rows() == 0) {
            $query_kolom = array();
        } else {
            $query_kolom = $query_kolom->result_array();
        }

        return $query_kolom;
    }
}
