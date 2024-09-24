<?php

date_default_timezone_set('Asia/Jakarta');

class M_PersiapanOpname extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    public function getClientWms()
    {
        $dataclientwms = $this->db->select("client_wms_id, client_wms_nama")
            ->from("client_wms")
            ->where("client_wms_is_aktif", 1)
            ->get()->result();
        return $dataclientwms;
    }
    public function getTipeOpname()
    {
        $dataclientwms = $this->db->select("*")
            ->from("tipe_opname")
            ->where("tipe_opname_is_aktif", 1)
            ->get()->result();
        return $dataclientwms;
    }
    public function getPenanggungJawab()
    {
        return $this->db->query("select * from karyawan ")->result_array();
    }
    public function getTipePenerimaan()
    {
        $dataTipePenerimaan = $this->db->select("penerimaan_tipe_id as id, penerimaan_tipe_nama as nama")
            ->from("penerimaan_tipe")
            ->where("penerimaan_tipe_is_aktif", 1)
            ->where("penerimaan_tipe_jenis", "Pembelian")
            ->get()->result();
        return $dataTipePenerimaan;
    }

    public function get_data_principle_by_client_wms_id($id)
    {
        $query = $this->db->select("b.principle_id as id, b.principle_kode as kode, b.principle_nama as nama")
            ->from("client_wms_principle a")
            ->join("principle b", "a.principle_id = b.principle_id")
            ->where("a.client_wms_id", $id)->get()->result();
        return $query;
    }
    public function getDataDepo()
    {
        $query = $this->db->select("d.depo_id,d.depo_nama")
            ->from("depo d")
            ->where("d.depo_id", $this->session->userdata('depo_id'))->get()->result();
        return $query;
    }
    public function getDetailAreaNamaByRakid($rak_id, $tipe_stock)
    {
        return $this->db->query("select rl.rak_lajur_nama
        from rak as r 
            left join rak_lajur rl on rl.rak_id = r.rak_id
            left join rak_lajur_detail rld on rld.rak_lajur_id = rl.rak_lajur_id 
            left join rak_lajur_detail_pallet rjdp on rl.rak_lajur_id = rjdp.rak_lajur_detail_id
        where r.rak_id = '$rak_id' and rld.rak_lajur_detail_tipe_stock = '$tipe_stock'
        group by rl.rak_lajur_nama
        ")->result_array();
    }

    public function getDepoPrefix($depo_id)
    {
        $listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
        return $listDoBatch->row();
    }
    public function getDataAreaDetail($rak_id, $depo_detail_id, $tipe_stock, $client_id, $principle)
    {
        // $kondisi1 = "";
        // $kondisi2 = "";
        // $kondisi3 = "";

        // if ($arrEditDetail != '' || $arrEditDetail != null) {
        //     $counter = 0;
        //     $union = " UNION ALL ";
        //     $table_sementara = "";
        //     foreach ($arrEditDetail as $key => $value) {
        //         $table_sementara .= "SELECT '" . $value['client_wms_id'] . "' AS client_wms_id, '" . $value['principle_id'] . "' AS principle_id, '" . $value['rak_lajur_detail_id'] . "' AS rak_lajur_detail_id ";

        //         $counter++;

        //         if ($key < count($arrEditDetail) - 1) {
        //             $table_sementara .= $union;
        //         }
        //     }

        //     $kondisi1 = "CASE WHEN bebas.client_wms_id IS NOT NULL THEN '1' ELSE '0' END AS bebas,";

        //     $kondisi2 = "LEFT JOIN (" . $table_sementara . ") bebas
        //     ON bebas.client_wms_id = b.client_wms_id
        //     AND bebas.principle_id = b.principle_id
        //     AND bebas.rak_lajur_detail_id = a.rak_lajur_detail_id";

        //     $kondisi3 = "CASE WHEN bebas.client_wms_id IS NOT NULL THEN '1' ELSE '0' END,";
        // }


        // return $this->db->query("select rl.rak_lajur_nama, rld.rak_lajur_detail_nama,rld.rak_lajur_detail_tipe_stock, count(rjdp.rak_lajur_detail_pallet_id) as jumlah_terisi from 
        // depo_detail d left join rak r on d.depo_id = r.depo_id 
        // left join rak_lajur_detail rld on r.rak_id = rld.rak_id 
        // left join rak_lajur rl on r.rak_id = rl.rak_id
        // left join rak_lajur_detail_pallet rjdp on rld.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
        // where rld.rak_id = '$rak_id'
        // and rld.rak_lajur_detail_tipe_stock = '$tipe_stock'
        // group by rld.rak_lajur_detail_nama, rl.rak_lajur_nama,rld.rak_lajur_detail_tipe_stock 
        // order by rl.rak_lajur_nama")->result_array();
        // return $this->db->query("select rjdp.rak_lajur_detail_pallet_id, rl.rak_lajur_nama, rld.rak_lajur_detail_nama,rld.rak_lajur_detail_tipe_stock, count(rjdp.rak_lajur_detail_pallet_id) as jumlah_pallet 
        // ,
        // CASE
        //     WHEN count(rjdp.rak_lajur_detail_pallet_id) =  0 then 'not filled'
        //     when  count(rjdp.rak_lajur_detail_pallet_id) >  0 then 'filled'
        //     end as status

        // from 
        //         depo_detail d left join rak r on d.depo_id = r.depo_id 
        //         left join rak_lajur_detail rld on r.rak_id = rld.rak_id 
        //         left join rak_lajur rl on r.rak_id = rl.rak_id
        //         left join rak_lajur_detail_pallet rjdp on rld.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
        //         where rld.rak_id = '$rak_id' and rld.rak_lajur_detail_tipe_stock = '$tipe_stock'
        //         group by rld.rak_lajur_detail_nama, rl.rak_lajur_nama,rld.rak_lajur_detail_tipe_stock ,rjdp.rak_lajur_detail_pallet_id
        //         order by rl.rak_lajur_nama")->result_array();
        // return $this->db->query("
        //             select r.rak_id,rl.rak_lajur_id, rjdp.rak_lajur_detail_pallet_id, rl.rak_lajur_nama, rld.rak_lajur_detail_nama,rld.rak_lajur_detail_tipe_stock, count(rjdp.rak_lajur_detail_pallet_id) as jumlah_pallet,
        //     CASE
        //         WHEN count(rjdp.rak_lajur_detail_pallet_id) =  0 then 'not filled'
        //         when  count(rjdp.rak_lajur_detail_pallet_id) >  0 then 'filled'
        //         end as status
        //     from rak as r 
        //         left join rak_lajur rl on rl.rak_id = r.rak_id
        //         left join rak_lajur_detail rld on rld.rak_lajur_id = rl.rak_lajur_id 
        //         left join rak_lajur_detail_pallet rjdp on rld.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
        //     where r.rak_id = '$rak_id' and rld.rak_lajur_detail_tipe_stock = '$tipe_stock'
        //     group by r.rak_id,rl.rak_lajur_nama,rl.rak_lajur_id, rld.rak_lajur_detail_nama,rld.rak_lajur_detail_tipe_stock,
        //         rjdp.rak_lajur_detail_pallet_id
        //     order by rak_lajur_detail_pallet_id desc")->result_array();
        // return $this->db->query("
        // select r.rak_id,rl.rak_lajur_id,kp.client_wms_nama,kp.principle_nama, rjdp.rak_lajur_detail_pallet_id, rl.rak_lajur_nama, rld.rak_lajur_detail_nama,rld.rak_lajur_detail_tipe_stock, count(rjdp.rak_lajur_detail_pallet_id) as jumlah_pallet,
        // CASE
        //     WHEN count(rjdp.rak_lajur_detail_pallet_id) =  0 then 'not filled'
        //     when  count(rjdp.rak_lajur_detail_pallet_id) >  0 then 'filled'
        //     end as status
        // from rak as r 
        //     left join rak_lajur rl on rl.rak_id = r.rak_id
        //     left join rak_lajur_detail rld on rld.rak_lajur_id = rl.rak_lajur_id 
        //     left join rak_lajur_detail_pallet rjdp on rld.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
        //     left join (select p.principle_nama, cw.client_wms_nama, p.principle_id from client_wms cw left join client_wms_principle cp on cw.client_wms_id = cp.client_wms_id
        //                 left join principle p on cp.principle_id = p.principle_id) as kp on kp.principle_id = rld.principle_id
        // where r.rak_id = '$rak_id' and rld.rak_lajur_detail_tipe_stock = '$tipe_stock'
        // group by r.rak_id,rl.rak_lajur_nama,rl.rak_lajur_id, rld.rak_lajur_detail_nama,rld.rak_lajur_detail_tipe_stock,
        //     rjdp.rak_lajur_detail_pallet_id,kp.client_wms_nama,kp.principle_nama
        // order by rak_lajur_detail_pallet_id desc")->result_array();

        $depo_detail_is_flashout = $this->db->select("depo_detail_is_flashout")->from("depo_detail")->where("depo_detail_id", $depo_detail_id)->get()->row()->depo_detail_is_flashout;
        if ($depo_detail_is_flashout == 0 || $depo_detail_is_flashout == null) {
            // $whereClient = $client_id == "" ? "" : "and kp.client_wms_id = '$client_id'";
            $whereClient = $client_id == "" ? "" : "and b.client_wms_id = '$client_id'";
            $wherePrinciple = $principle == "" ? "" : "and b.principle_id = '$principle'";
        } else {
            $whereClient = "";
            $wherePrinciple = "";
        }

        // $query =  "SELECT
        // ISNULL(kp.client_wms_nama, 'All') AS client_wms_nama,
        // ISNULL(kp.principle_nama, 'All') AS principle_nama,
        // ISNULL(kp.principle_kode, 'All') AS area,
        // ISNULL(b.principle_kode, 'not filled') AS principle_kode,
        // b.client_wms_nama AS client,
        // b.principle_id,
        // b.client_wms_id AS clientWMSIDSKU,
        // kp.client_wms_id,
        // a.rak_lajur_detail_id,
        // a.rak_lajur_detail_nama,
        // a.rak_lajur_detail_tipe_stock,
        // a.rak_lajur_detail_kolom,
        // d.rak_lajur_nama,
        // b.jum AS jumlah_pallet,
        // $kondisi1
        // CASE
        //     WHEN COUNT(b.jum) = 0 THEN 'not filled'
        //     WHEN COUNT(b.jum) > 0 THEN 'filled'
        // END AS status
        // -- CASE WHEN bebas.client_wms_id IS NOT NULL THEN '1' ELSE '0' END AS bebas
        // FROM rak_lajur_detail a
        // LEFT JOIN (SELECT
        // prin.principle_kode,
        // prin.principle_id,
        // cw.client_wms_nama,
        // cw.client_wms_id,
        // dp.rak_lajur_detail_id,
        // COUNT(dp.rak_lajur_detail_id) AS jum
        // FROM rak_lajur_detail_pallet dp
        // LEFT JOIN pallet_detail pd
        // ON dp.pallet_id = pd.pallet_id
        // LEFT JOIN sku sku
        // ON pd.sku_id = sku.sku_id
        // LEFT JOIN principle prin
        // ON sku.principle_id = prin.principle_id
        // LEFT JOIN client_wms cw
        // ON sku.client_wms_id = cw.client_wms_id
        // GROUP BY prin.principle_kode,
        //         cw.client_wms_nama,
        //         dp.rak_lajur_detail_id,
        //         prin.principle_id,
        //         cw.client_wms_id) AS b
        // ON a.rak_lajur_detail_id = b.rak_lajur_detail_id
        // INNER JOIN rak c
        // ON a.rak_id = c.rak_id
        // LEFT JOIN (SELECT
        // d.rak_lajur_id,
        // d.rak_lajur_nama
        // FROM rak_lajur d) AS d
        // ON d.rak_lajur_id = a.rak_lajur_id
        // LEFT JOIN (SELECT
        // p.principle_nama,
        // p.principle_kode,
        // cw.client_wms_id,
        // cw.client_wms_nama,
        // p.principle_id
        // FROM client_wms cw
        // LEFT JOIN client_wms_principle cp
        // ON cw.client_wms_id = cp.client_wms_id
        // LEFT JOIN principle p
        // ON cp.principle_id = p.principle_id) AS kp
        // ON kp.principle_id = a.principle_id
        // AND a.client_wms_id = kp.client_wms_id
        // $kondisi2
        // WHERE c.depo_detail_id = '$depo_detail_id' and  a.rak_lajur_detail_tipe_stock='$tipe_stock' 
        // $whereClient $wherePrinciple
        // GROUP BY a.rak_lajur_detail_id,
        //         a.rak_lajur_detail_nama,
        //         a.rak_lajur_detail_tipe_stock,
        //         a.rak_lajur_detail_kolom,
        //         d.rak_lajur_nama,
        //         b.jum,
        //         kp.principle_nama,
        //         kp.client_wms_nama,
        //         kp.principle_id,
        //         kp.principle_kode,
        //         kp.client_wms_id,
        //         b.principle_kode,
        //         b.client_wms_nama,
        //         b.principle_id,
        //         $kondisi3
        //         b.client_wms_id
        //         -- CASE WHEN bebas.client_wms_id IS NOT NULL THEN '1' ELSE '0' END
        // ORDER BY a.rak_lajur_detail_kolom ASC";



        return $this->db->query("
            SELECT
            isnull(kp.client_wms_nama, 'All') as client_wms_nama,
            isnull(kp.principle_nama, 'All') as principle_nama,
            isnull(kp.principle_kode, 'All') as area,
            isnull(b.principle_kode, 'not filled') as principle_kode,
            b.client_wms_nama as client,
            b.principle_id,
            b.client_wms_id as clientWMSIDSKU,
            kp.client_wms_id,
            a.rak_lajur_detail_id,
            a.rak_lajur_detail_nama,
            a.rak_lajur_detail_tipe_stock,
            a.rak_lajur_detail_kolom,
            d.rak_lajur_nama,
            b.jum AS jumlah_pallet,
            CASE
              WHEN COUNT(b.jum) = 0 THEN 'not filled'
              WHEN COUNT(b.jum) > 0 THEN 'filled'
            END AS status
          FROM rak_lajur_detail a
          LEFT JOIN (SELECT
            prin.principle_kode,
            prin.principle_id,
            cw.client_wms_nama,
            cw.client_wms_id,
            dp.rak_lajur_detail_id,
            COUNT(dp.rak_lajur_detail_id) AS jum
          FROM rak_lajur_detail_pallet dp
          inner join pallet_detail pd on dp.pallet_id=pd.pallet_id
          inner JOIN sku sku ON pd.sku_id = sku.sku_id
          inner join principle prin on sku.principle_id=prin.principle_id
          inner join client_wms cw on sku.client_wms_id=cw.client_wms_id
          GROUP BY prin.principle_kode,cw.client_wms_nama,dp.rak_lajur_detail_id, prin.principle_id,  cw.client_wms_id) AS b ON a.rak_lajur_detail_id = b.rak_lajur_detail_id
          INNER JOIN rak c ON a.rak_id = c.rak_id
          left join(select d.rak_lajur_id, d.rak_lajur_nama from rak_lajur d) as d on d.rak_lajur_id = a.rak_lajur_id
          LEFT JOIN (SELECT
            p.principle_nama,
            p.principle_kode,
            cw.client_wms_id,
            cw.client_wms_nama,
            p.principle_id
          FROM client_wms cw
          LEFT JOIN client_wms_principle cp ON cw.client_wms_id = cp.client_wms_id
          LEFT JOIN principle p ON cp.principle_id = p.principle_id) AS kp ON kp.principle_id = a.principle_id
            AND a.client_wms_id = kp.client_wms_id

           WHERE c.depo_detail_id = '$depo_detail_id' and  a.rak_lajur_detail_tipe_stock='$tipe_stock' 
            $whereClient $wherePrinciple

          GROUP BY a.rak_lajur_detail_id,
                   a.rak_lajur_detail_nama,
                   a.rak_lajur_detail_tipe_stock,
                   a.rak_lajur_detail_kolom,
                     d.rak_lajur_nama,
                   b.jum,
                   kp.principle_nama,
                   kp.client_wms_nama,
                   kp.principle_id,
                   kp.principle_kode,
                   kp.client_wms_id,
                   b.principle_kode,
                   b.client_wms_nama,
                   b.principle_id,
                   b.client_wms_id

                   order by a.rak_lajur_detail_kolom ASC")->result_array();

        // return $this->db->query($query)->result_array();
    }
    public function get_width_and_lenght_by_depo($id)
    {
        return $this->db->select("depo_width, depo_length, depo_nama")->from("depo")->where("depo_id", $id)->get()->row();
    }

    public function GetDepoDetailMenu($id)
    {
        return $this->db->select("*")->from("depo_detail")->where("depo_id", $id)->get()->result();
    }
    public function getGudangByDepoId()
    {
        $datagudang = $this->db->select("d.depo_id as depo_id, dd.depo_detail_id as depo_detail_id, dd.depo_detail_nama as depo_detail_nama")
            ->from("depo d")
            ->join("depo_detail dd", "d.depo_id = dd.depo_id", "left")
            ->where("dd.depo_id", $this->session->userdata('depo_id'))
            ->where("dd.depo_detail_is_gudang_penerima", 2)
            ->get()->row();
        return $datagudang;
    }
    public function get_area_rak_gudang($tipe_stock, $client_wms, $principle, $depo)
    {

        $data = $this->db->select('depo_detail_id as id')->from('depo_detail')->where('depo_id', $depo)->get()->result();
        // $depo_detail_id = [];
        // foreach ($data as $key => $value) {
        //     array_push($depo_detail_id, $value->id);
        // }

        $params = array();
        for ($i = 0; $i < count($data); $i++) {
            $params[$i] = "'" . $data[$i]->id . "'";
        }

        $whereClient = $client_wms == "" ? '' : "AND rjd.client_wms_id = '$client_wms'";
        $wherePrinciple = $principle == "" ? '' : "AND rjd.principle_id = '$principle'";

        if ($tipe_stock == "Good Stock") {
            return $this->db->query("
                        SELECT DISTINCT rak.depo_detail_id, rak.rak_id
                        FROM rak
                        LEFT JOIN rak_lajur_detail rjd ON rak.rak_id = rjd.rak_id
                        LEFT JOIN depo_detail dd ON rak.depo_detail_id = dd.depo_detail_id
                        WHERE rak.depo_id = '$depo'
                            AND rak.depo_detail_id IN (" . implode(',', $params) . ")
                            $whereClient
                            $wherePrinciple
                            AND rjd.rak_lajur_detail_tipe_stock = '$tipe_stock'
                            AND isnull(dd.depo_detail_is_flashout, 0) = 0
                        UNION
                        SELECT DISTINCT rak.depo_detail_id, rak.rak_id
                        FROM rak
                        LEFT JOIN rak_lajur_detail rjd ON rak.rak_id = rjd.rak_id
                        LEFT JOIN depo_detail dd ON rak.depo_detail_id = dd.depo_detail_id
                        WHERE rak.depo_id = '$depo'
                            AND rak.depo_detail_id IN (" . implode(',', $params) . ")
                            AND rjd.rak_lajur_detail_tipe_stock = '$tipe_stock'
                            AND isnull(dd.depo_detail_is_flashout, 0) = 1
                    ")->result();
        } else {
            return $this->db->query("
                    SELECT DISTINCT rak.depo_detail_id, rak.rak_id
                    FROM rak
                    LEFT JOIN rak_lajur_detail rjd ON rak.rak_id = rjd.rak_id
                    LEFT JOIN depo_detail dd ON rak.depo_detail_id = dd.depo_detail_id
                    WHERE rak.depo_id = '$depo'
                        AND rak.depo_detail_id IN (" . implode(',', $params) . ")
                        $whereClient
                        $wherePrinciple
                        AND rjd.rak_lajur_detail_tipe_stock = '$tipe_stock'")->result();
            // return $this->db->select("rak.depo_detail_id, rak.rak_id")
            //     ->from("rak")
            //     ->join("rak_lajur_detail rjd", "rak.rak_id = rjd.rak_id", "left")
            //     ->join("depo_detail dd", "rak.depo_detail_id = dd.depo_detail_id", "left")
            //     ->distinct()
            //     ->where("rak.depo_id", $depo)->where_in("rak.depo_detail_id", $depo_detail_id)->where("rjd.client_wms_id", $client_wms)
            //     ->where("rjd.principle_id", $principle)
            //     ->where("rjd.rak_lajur_detail_tipe_stock", $tipe_stock)->get()->result();
        }
    }
    public function getDataPersiapanOpname($perusahaan, $tahun, $bulan, $principle, $tipestock)
    {
        $whereperusahaan = "";
        $whereprinciple = "";
        $wheretipestock = "";
        if ($perusahaan != "") {
            $whereperusahaan = "AND tr.client_wms_id = '$perusahaan' ";
        }
        if ($whereprinciple = "") {
            $whereprinciple = "AND tr.principle_id = '$principle' ";
        }
        if ($wheretipestock = "") {
            $wheretipestock = "AND tr.tipe_stok = '$tipestock' ";
        }
        // return $this->db->query("select tr.tr_opname_plan_id,tr.tr_opname_plan_kode,tr.depo_detail_id, tr.tr_opname_plan_status, tr.tipe_stok, FORMAT(tr.tr_opname_plan_tanggal,'yyyy-MM-dd') as tgl
        //     from tr_opname_plan tr 
        //     where YEAR(tr.tr_opname_plan_tanggal) = '$tahun' and MONTH(tr.tr_opname_plan_tanggal) ='$bulan' $whereperusahaan $whereprinciple $wheretipestock")->result_array();
        // return $this->db->query("select *,FORMAT(tr.tr_opname_plan_tanggal,'yyyy-MM-dd') as tgl from tr_opname_plan_detail trd left join tr_opname_plan tr on tr.tr_opname_plan_id = trd.tr_opname_plan_id
        //     left join rak_lajur_detail_pallet rld on rld.rak_lajur_detail_pallet_id = trd.rak_lajur_detail_pallet_id
        //     left join (select rl.rak_lajur_nama,rlp.rak_lajur_detail_id ,rlp.rak_lajur_detail_nama from rak_lajur rl 
        //     left join rak_lajur_detail rlp on rlp.rak_lajur_id
        //     = rl.rak_lajur_id) as rls on rls.rak_lajur_detail_id = rld.rak_lajur_detail_id
        //     left join (select d.depo_nama, dd.depo_detail_id, dd.depo_detail_nama from depo d left join depo_detail dd on dd.depo_id = d.depo_id
        //                 ) as dd2 on tr.depo_detail_id = dd2.depo_detail_id
        //     right join (select cw.client_wms_id, p.principle_nama, cw.client_wms_nama, p.principle_id from client_wms cw left join client_wms_principle cp on cw.client_wms_id = cp.client_wms_id
        //                         left join principle p on cp.principle_id = p.principle_id) as kp on tr.principle_id = kp.principle_id
        //     where YEAR(tr.tr_opname_plan_tanggal) = '$tahun' and MONTH(tr.tr_opname_plan_tanggal) ='$bulan' $whereperusahaan $whereprinciple $wheretipestock 
        //     order by tr.depo_id")->result_array();
        return $this->db->query("select *,FORMAT(tr.tr_opname_plan_tanggal,'yyyy-MM-dd') as tgl, tp.tipe_opname_nama from tr_opname_plan tr
		left join (select cw.client_wms_id, isnull(p.principle_nama, 'All') as principle_nama,isnull(p.principle_kode, 'All') as principle_kode, isnull(cw.client_wms_nama, 'All') as client_wms_nama, p.principle_id from client_wms cw left join client_wms_principle cp on cw.client_wms_id = cp.client_wms_id
							left join principle p on cp.principle_id = p.principle_id) as kp on tr.client_wms_id = kp.client_wms_id
                            AND tr.principle_id = kp.principle_id
                            left join tipe_opname tp on tr.tipe_opname_id = tp.tipe_opname_id
            where YEAR(tr.tr_opname_plan_tanggal) = '$tahun' and MONTH(tr.tr_opname_plan_tanggal) ='$bulan' and tr.depo_id ='" . $this->session->userdata('depo_id') . "' $whereperusahaan $whereprinciple $wheretipestock 
            order by tgl DESC")->result_array();
    }

    public function getDataTrOpnameById($id)
    {
        // return $this->db->query("select * from tr_opname_plan tr where tr.tr_opname_plan_id = '$id'")->result();

        return $this->db->query("select tr.tr_opname_plan_id,tr.depo_id,tr.depo_detail_id,tr.tr_opname_plan_kode, FORMAT(tr.tr_opname_plan_tanggal,'yyyy-MM-dd')as tgl,tr.client_wms_id,tr.principle_id,tr.karyawan_id_penanggungjawab,tr.tipe_stok,tr.tipe_opname_id,tr.tr_opname_plan_keterangan,tr.tr_opname_plan_status,tr.tr_opname_plan_tgl_create,tr.tr_opname_plan_who_create, tr.tr_opname_plan_tgl_update from tr_opname_plan as tr where tr.tr_opname_plan_id = '$id'")->result();
    }
    public function getDataDetailEditByOpnameID($id)
    {
        return $this->db->query("select trd.rak_lajur_detail_id, trd.principle_id, trd.client_wms_id from tr_opname_plan_detail trd where trd.tr_opname_plan_id = '$id' ")->result();
    }
    public function getDataViewByTrOpnameID($id)
    {
        return $this->db->query("select * from tr_opname_plan_detail trd left join tr_opname_plan tr on tr.tr_opname_plan_id = trd.tr_opname_plan_id
        left join rak_lajur_detail_pallet rld on rld.rak_lajur_detail_id = trd.rak_lajur_detail_id
        left join (select rl.rak_lajur_nama,rlp.rak_lajur_detail_id ,rlp.rak_lajur_detail_nama from rak_lajur rl 
        left join rak_lajur_detail rlp on rlp.rak_lajur_id
        = rl.rak_lajur_id) as rls on rls.rak_lajur_detail_id = trd.rak_lajur_detail_id
        left join (select d.depo_nama, dd.depo_detail_id, dd.depo_detail_nama from depo d left join depo_detail dd on dd.depo_id = d.depo_id) as dd2 on tr.depo_detail_id = dd2.depo_detail_id
        where tr.tr_opname_plan_id = '$id'")->result();
    }

    public function Get_LastUpdateOpname($id)
    {
        return $this->db->query("select tr_opname_plan_tgl_update as tglUpd from tr_opname_plan where tr_opname_plan_id = '$id'")->row_array();
    }

    public function batalkan($id)
    {
        $this->db->set("tr_opname_plan_status", "Canceled");
        $this->db->where("tr_opname_plan_id", $id);
        return $this->db->update("tr_opname_plan");
    }
    public function getTipeStock()
    {
        return $this->db->query("select * from gettipestock() where tipe_stock_is_aktif ='1'")->result();
    }
    public function getIdLajurDetailID($id)
    {
        // var_dump("select rak_lajur_detail_pallet_id from rak_lajur_detail_pallet where rak_lajur_detail_id = '$id'");
        return  $this->db->select("rak_lajur_detail_pallet_id")
            ->from("rak_lajur_detail_pallet")
            ->where_in("rak_lajur_detail_id", $id)->get()->result();
        // return $this->db->query("select rak_lajur_detail_pallet_id from rak_lajur_detail_pallet where rak_lajur_detail_id '$id'")->result_array();
    }
    public function getIdLajurPalletID($id)
    {
        return $this->db->query("select rld.rak_lajur_detail_id from tr_opname_plan tr left join tr_opname_plan_detail trd on tr.tr_opname_plan_id = trd.tr_opname_plan_id
        left join rak_lajur_detail_pallet rld on trd.rak_lajur_detail_pallet_id = rld.rak_lajur_detail_pallet_id
        where trd.tr_opname_plan_id = '$id'")->result();
    }

    public function InsertOpname(
        $newopname_id,
        $depo_id,
        $depo_detail_id,
        $generate_kode,
        $tr_opname_plan_tanggal,
        $client_wms_id,
        $karyawan_id_penanggungjawab,
        $principle_id,
        $tipe_stok,
        $tipe_opname_id,
        $tr_opname_plan_keterangan,
        $tr_opname_plan_status,
        $tr_opname_plan_tgl_create,
        $tr_opname_plan_who_create
    ) {



        $this->db->set("tr_opname_plan_id", $newopname_id);
        $this->db->set("depo_id", $depo_id);
        $this->db->set("depo_detail_id", $depo_detail_id);
        $this->db->set("tr_opname_plan_kode", $generate_kode);
        $this->db->set("tr_opname_plan_tanggal", $tr_opname_plan_tanggal);
        $this->db->set("karyawan_id_penanggungjawab", $karyawan_id_penanggungjawab);
        if ($client_wms_id != "") $this->db->set("client_wms_id", $client_wms_id);
        if ($principle_id != "") $this->db->set("principle_id", $principle_id);
        $this->db->set("tipe_stok", $tipe_stok);
        $this->db->set("tipe_opname_id", $tipe_opname_id);
        $this->db->set("tr_opname_plan_keterangan", $tr_opname_plan_keterangan);
        $this->db->set("tr_opname_plan_status", $tr_opname_plan_status);
        $this->db->set("tr_opname_plan_tgl_create", "GETDATE()", false);
        $this->db->set("tr_opname_plan_who_create", $this->session->userdata('pengguna_username'));

        return $this->db->insert("tr_opname_plan");
    }
    public function InsertDetailOpname($tr_opname_plan_id, $rak_lajur_detail_pallet_id, $principle_id, $client_wms_id)
    {
        if ($principle_id != 'null') {
            $this->db->set("principle_id", $principle_id);
        }

        if ($client_wms_id != 'null') {
            $this->db->set("client_wms_id", $client_wms_id);
        }

        $this->db->set("tr_opname_plan_detail_id", "NEWID()", false);
        $this->db->set("tr_opname_plan_id", $tr_opname_plan_id);
        $this->db->set("rak_lajur_detail_id", $rak_lajur_detail_pallet_id);

        return $this->db->insert("tr_opname_plan_detail");
    }

    public function UpdateOpname(
        $tr_opname_plan_id,
        $depo_id,
        $depo_detail_id,
        $generate_kode,
        $tr_opname_plan_tanggal,
        $client_wms_id,
        $karyawan_id_penanggungjawab,
        $principle_id,
        $tipe_stok,
        $tipe_opname_id,
        $tr_opname_plan_keterangan,
        $tr_opname_plan_status,
        $tr_opname_plan_tgl_create,
        $tr_opname_plan_who_create
    ) {
        $this->db->set("tr_opname_plan_id", $tr_opname_plan_id);
        $this->db->set("depo_id", $depo_id);
        $this->db->set("depo_detail_id", $depo_detail_id);
        // $this->db->set("tr_opname_plan_kode", $generate_kode);
        $this->db->set("tr_opname_plan_tanggal", $tr_opname_plan_tanggal);
        if ($client_wms_id != "") $this->db->set("client_wms_id", $client_wms_id);
        if ($principle_id != "") $this->db->set("principle_id", $principle_id);

        $this->db->set("karyawan_id_penanggungjawab", $karyawan_id_penanggungjawab);
        $this->db->set("tipe_stok", $tipe_stok);
        // $this->db->set("tipe_opname_id", $tipe_opname_id);
        $this->db->set("tr_opname_plan_keterangan", $tr_opname_plan_keterangan);
        $this->db->set("tr_opname_plan_status", $tr_opname_plan_status);
        $this->db->set("tr_opname_plan_who_update", $this->session->userdata("pengguna_username"));
        $this->db->set("tr_opname_plan_tgl_update", "GETDATE()", FALSE);
        // $this->db->set("tr_opname_plan_tgl_create", "GETDATE()", false);
        // $this->db->set("tr_opname_plan_who_create", $this->session->userdata('pengguna_username'));
        $this->db->where('tr_opname_plan_id', $tr_opname_plan_id);
        return $this->db->update("tr_opname_plan");
    }

    function DeleteDetailOpname($tr_opname_plan_id)
    {
        $this->db->where('tr_opname_plan_id', $tr_opname_plan_id);
        return $this->db->delete('tr_opname_plan_detail');
    }

    public function getDataTrOpnameByIdCetak($id)
    {
        return $this->db->query("
        select FORMAT(tr.tr_opname_plan_tgl_start,'dd-MM-yyyy')as tgl_opname,tr.tr_opname_plan_kode as kode_dokumen,
        d.depo_nama,cw.client_wms_nama,p.principle_nama,k.karyawan_nama, tp.tipe_opname_nama, tr.tipe_stok,tr.tr_opname_plan_status,tr.tr_opname_plan_keterangan from tr_opname_plan tr
        left join client_wms cw on cw.client_wms_id = tr.client_wms_id
        left join depo d on d.depo_id = tr.depo_id
        left join  principle p on p.principle_id = tr.principle_id
        left join karyawan k on k.karyawan_id = tr.karyawan_id_penanggungjawab
        left join tipe_opname tp on tp.tipe_opname_id = tr.tipe_opname_id
        where tr_opname_plan_id ='$id'")->row();
    }
    public function getDataDetailEditByOpnameIDCetak($id)
    {
        $query =  $this->db->query("select rl.rak_lajur_detail_nama,p.pallet_kode, sku.sku_kode, sku.sku_nama_produk,sku.sku_kemasan,sku.sku_satuan,pd.sku_stock_expired_date ,pd.sku_stock_qty from tr_opname_plan_detail trd

        left join rak_lajur_detail_pallet rlj on rlj.rak_lajur_detail_id = trd.rak_lajur_detail_id
        left join rak_lajur_detail rl on rl.rak_lajur_detail_id = rlj.rak_lajur_detail_id
        left join pallet_detail pd on pd.pallet_id = rlj.pallet_id
        left join pallet p on p.pallet_id = rlj.pallet_id
        left join sku on sku.sku_id = pd.sku_id 
        where tr_opname_plan_id ='$id'");
        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result();
        }
        return $query;
    }
}
