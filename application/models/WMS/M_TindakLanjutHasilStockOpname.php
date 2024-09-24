<?php
date_default_timezone_set('Asia/Jakarta');

class M_TindakLanjutHasilStockOpname extends CI_Model
{

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  public function getPerusahaan()
  {
    $query = $this->db->query("SELECT
    client_wms_id,
    client_wms_nama
  FROM client_wms WHERE client_wms_is_aktif = 1 AND client_wms_is_deleted = 0");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result_array();
    }

    return $query;
  }

  public function getPrincipleByID($id)
  {
    $query = $this->db->query("SELECT
    b.principle_id,
    b.principle_nama
  FROM client_wms_principle a
  LEFT JOIN principle b
    ON a.principle_id = b.principle_id
  WHERE client_wms_id = '$id'");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result_array();
    }

    return $query;
  }

  public function getDataDokumenOpname()
  {
    $query = $this->db->query("SELECT
    tr_opname_plan_id AS id,
    tr_opname_plan_kode AS kode,
    tr_opname_plan_status AS status
  FROM tr_opname_plan
  WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
  AND tr_opname_plan_status = 'Completed'
  AND tipe_opname_id = 'CAC2CFC6-4371-4021-A8FE-AEF91F7C970F'
  AND NOT EXISTS (SELECT
    tr_opname_plan_id
  FROM tr_opname_result
  WHERE tr_opname_plan_id = tr_opname_plan.tr_opname_plan_id)
  ORDER BY tr_opname_plan_kode ASC");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result_array();
    }

    return $query;
  }

  public function getDataOpnameByKode($id)
  {
    $query = $this->db->query("SELECT
        topp.tr_opname_plan_id AS id,
        topp.tr_opname_plan_kode AS kode,
        topp.tr_opname_plan_keterangan AS keterangan,
        k.karyawan_nama AS penanggung_jawab,
        FORMAT(topp.tr_opname_plan_tgl_create, 'yyyy-MM-dd') AS tanggal,
        cw.client_wms_nama AS perusahaan,
        p.principle_nama AS principle,
        topp.tipe_stok AS jenis_stock,
        tp.tipe_opname_nama AS tipe_stock_opname,
        depo.depo_nama AS unit_cabang,
        dd.depo_detail_nama AS area_opname,
        topp.tr_opname_plan_status AS status
      FROM tr_opname_plan topp
      LEFT JOIN client_wms cw
        ON topp.client_wms_id = cw.client_wms_id
      LEFT JOIN principle p
        ON topp.principle_id = p.principle_id
      LEFT JOIN tipe_opname tp
        ON topp.tipe_opname_id = tp.tipe_opname_id
      LEFT JOIN karyawan k
        ON topp.karyawan_id_penanggungjawab = k.karyawan_id
      LEFT JOIN depo
        ON topp.depo_id = depo.depo_id
      LEFT JOIN depo_detail dd
        ON topp.depo_detail_id = dd.depo_detail_id
      WHERE topp.tr_opname_plan_id = '$id'");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->row_array();
    }

    return $query;
  }

  public function getDataOpnameSelisihByID($id)
  {
    $query = $this->db->query("SELECT
    a.sku_stock_id,
    a.sku_id,
    b.sku_kode,
    b.sku_nama_produk,
    b.sku_satuan,
    SUM(a.sku_actual_qty_opname) AS sku_actual,
    SUM(a.sku_qty_sistem) AS sku_qty_sistem,
    (SUM(a.sku_actual_qty_opname) - SUM(a.sku_qty_sistem)) AS selisih
  FROM tr_opname_plan_detail3 a
  LEFT JOIN sku b
    ON a.sku_id = b.sku_id
  WHERE (a.sku_actual_qty_opname - a.sku_qty_sistem) <> 0 AND a.tr_opname_plan_id = '$id'
  GROUP BY a.sku_stock_id,
           a.sku_id,
           b.sku_kode,
           b.sku_nama_produk,
           b.sku_satuan
  ORDER BY b.sku_kode ASC");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result_array();
    }

    return $query;
  }

  public function getTrOpnamePlanByID($trOpnameID)
  {
    $query = $this->db->query("SELECT
    depo_id,
    depo_detail_id,
    tr_opname_plan_kode,
    tr_opname_plan_tanggal,
    tr_opname_plan_id,
    tr_opname_plan_keterangan,
    tr_opname_plan_status,
    tr_opname_plan_tgl_approve,
    tr_opname_plan_who_approve
  FROM tr_opname_plan WHERE tr_opname_plan_id = '$trOpnameID'");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->row_array();
    }

    return $query;
  }

  public function searchFilter($tgl1, $tgl2, $perusahaan, $principle)
  {
    if ($perusahaan == "") {
      $perusahaan = "";
    } else {
      $perusahaan = "AND b.client_wms_id = '$perusahaan'";
    }

    if ($principle == "") {
      $principle = "";
    } else {
      $principle = "AND b.principle_id = '$principle'";
    }

    $query = $this->db->query("SELECT
    a.tr_opname_result_id, 
    a.tr_opname_result_kode, 
    format(a.tr_opname_result_tgl_create, 'yyyy-MM-dd') as tgl, 
    ISNULL(c.client_wms_nama, '') as client_wms_nama, 
    ISNULL(d.principle_kode, '') as principle_kode,
    a.tr_opname_result_status as status,
    a.tr_opname_result_tgl_update as tglUpdate
  FROM tr_opname_result a
  LEFT JOIN tr_opname_plan b
    ON a.tr_opname_plan_id = b.tr_opname_plan_id
    LEFT JOIN client_wms c on b.client_wms_id = c.client_wms_id
    LEFT JOIN principle d on b.principle_id = d.principle_id
  WHERE FORMAT(tr_opname_result_tgl_create, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2' $perusahaan $principle");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result_array();
    }

    return $query;
  }

  public function getDataOpnameResultByID($id)
  {
    $query = $this->db->query("SELECT a.tr_opname_result_id AS id, 
              a.tr_opname_result_kode AS kode, 
              b.tr_opname_plan_keterangan as keterangan, 
              f.karyawan_nama AS penanggung_jawab,
              FORMAT(b.tr_opname_plan_tgl_create, 'dd-MM-yyyy') AS tanggal,
              a.tr_opname_result_tgl_update AS tglUpdate,
              c.client_wms_nama AS perusahaan,
              d.principle_kode AS principle,
              b.tipe_stok AS jenis_stock,
              e.tipe_opname_nama AS tipe_stock_opname,
              g.depo_nama AS unit_cabang,
              h.depo_detail_nama AS area_opname,
              b.tr_opname_plan_status AS status
              FROM tr_opname_result a
              LEFT JOIN tr_opname_plan b ON a.tr_opname_plan_id = b.tr_opname_plan_id
              LEFT JOIN client_wms c ON b.client_wms_id = c.client_wms_id
              LEFT JOIN principle d ON b.principle_id = d.principle_id
              LEFT JOIN tipe_opname e ON b.tipe_opname_id = e.tipe_opname_id
              LEFT JOIN karyawan f ON b.karyawan_id_penanggungjawab = f.karyawan_id
              LEFT JOIN depo g ON b.depo_id = g.depo_id
              LEFT JOIN depo_detail h ON h.depo_detail_id = b.depo_detail_id
              WHERE a.tr_opname_result_id = '$id'
              ");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->row_array();
    }

    return $query;
  }

  public function getDataOpnameResultDetailByID($id)
  {
    $query = $this->db->query("SELECT a.tr_opname_result_detail_id AS id, 
    b.sku_kode, 
    b.sku_nama_produk, 
    b.sku_satuan, 
    a.sku_stock_id,
    a.sku_id,
    a.sku_actual_qty_opname,
    a.sku_qty_sistem,
    a.sku_qty_selisih,
    a.qty_invoice, 
    a.qty_pemutihan, 
    a.qty_opname_ulang
    FROM tr_opname_result_detail a
    LEFT JOIN sku b ON a.sku_id = b.sku_id
    WHERE a.tr_opname_result_id = '$id'
    ORDER BY b.sku_kode ASC");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result_array();
    }

    return $query;
  }

  public function insertTrOpnameResult($data, $tr_opname_result_id)
  {
    $this->db->set('tr_opname_result_id', $tr_opname_result_id);
    $this->db->set('depo_id', $data['depo_id']);
    $this->db->set('depo_detail_id', $data['depo_detail_id']);
    $this->db->set('tr_opname_result_kode', $data['tr_opname_plan_kode']);
    $this->db->set('tr_opname_result_tanggal', $data['tr_opname_plan_tanggal']);
    $this->db->set('tr_opname_plan_id', $data['tr_opname_plan_id']);
    $this->db->set('tr_opname_result_keterangan', $data['tr_opname_plan_keterangan']);
    $this->db->set('tr_opname_result_status', 'In Progress');
    $this->db->set('tr_opname_result_tgl_create', 'GETDATE()', FALSE);
    $this->db->set('tr_opname_result_who_create', $this->session->userdata('pengguna_username'));
    $this->db->set('tr_opname_result_tgl_update', "GETDATE()", FALSE);
    $this->db->set('tr_opname_result_who_update', $this->session->userdata('pengguna_username'));
    // $this->db->set('tr_opname_result_tgl_approve', $data['tr_opname_plan_tgl_approve']);
    // $this->db->set('tr_opname_result_who_approve', $data['tr_opname_plan_who_approve']);

    $this->db->insert("tr_opname_result");

    $affectedrows = $this->db->affected_rows();

    if ($affectedrows > 0) {
      $queryinsert = 1;
    } else {
      $queryinsert = 0;
    }

    return $queryinsert;
  }

  public function insertTrOpnameResultDetail($tr_opname_result_id, $value)
  {
    $this->db->set('tr_opname_result_detail_id', "NEWID()", FALSE);
    $this->db->set('tr_opname_result_id', $tr_opname_result_id);
    $this->db->set('sku_stock_id', $value['sku_stock_id']);
    $this->db->set('sku_id', $value['sku_id']);
    // $this->db->set('is_invoice', $value['statusInvoice']);
    // $this->db->set('is_pemutihan', $value['statusPemutihan']);
    // $this->db->set('is_opname_ulang', $value['statusOpnameUlang']);
    $this->db->set('sku_actual_qty_opname', $value['qtyActual']);
    $this->db->set('sku_qty_sistem', $value['qtySistem']);
    $this->db->set('sku_qty_selisih', $value['selisihAwal']);
    $this->db->set('qty_invoice', $value['qtyInvoice']);
    $this->db->set('qty_pemutihan', $value['qtyPemutihan']);
    $this->db->set('qty_opname_ulang', $value['qtyOpnameUlang']);

    // if ($status == 'invoice') {
    //   $this->db->set('is_invoice', 1);
    //   $this->db->set('is_pemutihan', 0);
    //   $this->db->set('is_opname_ulang', 0);
    // } else if ($status == 'pemutihan') {
    //   $this->db->set('is_pemutihan', 1);
    //   $this->db->set('is_invoice', 0);
    //   $this->db->set('is_opname_ulang', 0);
    // } else {
    //   $this->db->set('is_opname_ulang', 1);
    //   $this->db->set('is_invoice', 0);
    //   $this->db->set('is_pemutihan', 0);
    // }

    $this->db->insert("tr_opname_result_detail");

    $affectedrows = $this->db->affected_rows();

    if ($affectedrows > 0) {
      $queryinsert = 1;
    } else {
      $queryinsert = 0;
    }

    return $queryinsert;
  }

  public function updateTrOpnameResult($id, $mode)
  {
    if ($mode == 'update') {
      $this->db->set("tr_opname_result_status", 'In Progress');
      $this->db->set('tr_opname_result_tgl_approve', "GETDATE()", FALSE);
      $this->db->set('tr_opname_result_who_approve', $this->session->userdata('pengguna_username'));
      $this->db->set('tr_opname_result_tgl_update', "GETDATE()", FALSE);
      $this->db->set('tr_opname_result_who_update', $this->session->userdata('pengguna_username'));
    } else {
      $this->db->set("tr_opname_result_status", 'Completed');
      $this->db->set('tr_opname_result_tgl_update', "GETDATE()", FALSE);
      $this->db->set('tr_opname_result_who_update', $this->session->userdata('pengguna_username'));
    }
    $this->db->where("tr_opname_result_id", $id);
    $this->db->update("tr_opname_result");
  }

  public function deleteTrOpnameResultDetail($id)
  {
    $this->db->where("tr_opname_result_id", $id);
    $this->db->delete("tr_opname_result_detail");

    $affectedrows = $this->db->affected_rows();

    if ($affectedrows > 0) {
      $querydelete = 1;
    } else {
      $querydelete = 0;
    }

    return $querydelete;
  }
}
