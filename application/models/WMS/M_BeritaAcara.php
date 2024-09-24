<?php

class M_BeritaAcara extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }

  public function GetDataPrinciple()
  {
    $query = $this->db->select("principle_id, principle_kode")
      ->from("principle")
      ->order_by("principle_kode", "ASC")
      ->get();

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result();
    }

    return $query;
  }

  public function GetDataFilter($tgl1, $tgl2, $principle_id)
  {
    $query = $this->db->query("SELECT
        a.penerimaan_surat_jalan_id,
        format(a.penerimaan_surat_jalan_tgl_create, 'yyyy-MM-dd HH:mm:ss') AS tgl,
        a.penerimaan_surat_jalan_kode,
        a.penerimaan_surat_jalan_no_sj,
        a.penerimaan_surat_jalan_status,
        b.principle_use_asn,
        a.is_status,
        AVG(c.is_download) as is_download,
        a.penerimaan_surat_jalan_tgl_update
      FROM penerimaan_surat_jalan a
      LEFT JOIN principle b
        ON b.principle_id = a.principle_id
      LEFT JOIN penerimaan_surat_jalan_detail_temp2 c
		    ON c.penerimaan_surat_jalan_id = a.penerimaan_surat_jalan_id
      WHERE a.principle_id = '$principle_id' 
      AND a.depo_id = '" . $this->session->userdata('depo_id') . "'
      AND format(a.penerimaan_surat_jalan_tgl_create, 'yyyy-MM-dd') >= '$tgl1'
      AND format(a.penerimaan_surat_jalan_tgl_create, 'yyyy-MM-dd') <= '$tgl2'
      GROUP BY a.penerimaan_surat_jalan_id, a.penerimaan_surat_jalan_tgl_create, a.penerimaan_surat_jalan_kode,
      a.penerimaan_surat_jalan_no_sj,
      a.penerimaan_surat_jalan_status,
      b.principle_use_asn,
      a.is_status,
      a.penerimaan_surat_jalan_tgl_update
      ORDER BY
      CASE 
	      WHEN penerimaan_surat_jalan_status = 'Close' then 0
      else 1 end ASC,
      penerimaan_surat_jalan_tgl_create ASC");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result();
    }

    return $query;
  }

  public function GetDataSelisihSKUDetailOri($penerimaan_surat_jalan_id)
  {
    $query = $this->db->query("SELECT
      b.sku_konversi_group,
      b.sku_nama_produk,
      SUM(ISNULL(a.sku_jumlah_barang, 0)) AS sku_jumlah_barang,
      SUM(ISNULL(a.sku_jumlah_barang_terima, 0)) AS sku_jumlah_barang_terima
    FROM penerimaan_surat_jalan_detail_ori a
    LEFT JOIN sku b
      ON b.sku_id = a.sku_id
    WHERE penerimaan_surat_jalan_id = '$penerimaan_surat_jalan_id'
    GROUP BY b.sku_konversi_group,
             b.sku_nama_produk
    ORDER BY b.sku_konversi_group");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result_array();
    }

    return $query;
  }

  public function GetDataSelisihSKUDetailTemp2($data, $penerimaan_surat_jalan_id)
  {
    $implode = implode(', ', $data);

    $query = $this->db->query("SELECT
      penerimaan_surat_jalan_id,
      sku_kode,
      sku_nama_produk,
      SUM(ISNULL(sku_jumlah_barang, 0)) AS sku_jumlah_barang,
      SUM(ISNULL(terima, 0)) AS terima,
      SUM(ISNULL(rusak, 0)) AS rusak,
      SUM(ISNULL(barang_tertinggal, 0)) AS barang_tertinggal,
      SUM(ISNULL(kardus_rusak, 0)) AS kardus_rusak,
      SUM(ISNULL(total, 0)) AS total,
      SUM(ISNULL(sumz, 0)) AS sumz
    FROM penerimaan_surat_jalan_detail_temp2
    WHERE sku_kode IN ($implode)
    AND penerimaan_surat_jalan_id = '$penerimaan_surat_jalan_id'
    GROUP BY penerimaan_surat_jalan_id,
            sku_kode, sku_nama_produk
    ORDER BY sku_kode");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result_array();
    }

    return $query;
  }

  public function GetPSJDetailTemp2BySKUPSJ($penerimaan_surat_jalan_id, $sku_kode)
  {
    $query = $this->db->query("SELECT
    penerimaan_surat_jalan_detail_temp2_id,
    sku_kode,
    sku_jumlah_barang,
    terima,
    rusak,
    barang_tertinggal,
    kardus_rusak,
    total,
    sumz
  FROM penerimaan_surat_jalan_detail_temp2
  WHERE penerimaan_surat_jalan_id = '$penerimaan_surat_jalan_id'
  AND sku_kode = '$sku_kode'
  ORDER BY sku_jumlah_barang ASC");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result_array();
    }

    return $query;
  }

  public function GetListDOByShipment($shipment_number, $principle_id)
  {
    $query = $this->db->query("SELECT 
		penerimaan_surat_jalan_no_sj,
		shipment_number,
		sku_kode,
		sku_jumlah_barang
		FROM penerimaan_surat_jalan_temp
		WHERE principle_id = '$principle_id' 
		AND shipment_number = '$shipment_number'");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result_array();
    }

    return $query;
  }

  public function GetHeaderPDF($id)
  {
    $query = $this->db->query("SELECT TOP 1
        CONCAT('WH/', e.depo_eksternal_kode, '-', c.penerimaan_surat_jalan_no_sj, '/', FORMAT(a.penerimaan_pembelian_tgl, 'dd/yyyy')) AS no_ba,
        FORMAT(a.penerimaan_pembelian_tgl, 'dddd dd MMMM yyyy', 'id') AS tgl1,
        FORMAT(a.penerimaan_pembelian_tgl, 'dd MMMM yyyy', 'id') AS tgl2,
        FORMAT(a.penerimaan_pembelian_tgl, 'dd/MM/yyyy') AS tgl3,
        f.depo_nama,
        c.penerimaan_surat_jalan_no_sj,
        g.ekspedisi_nama,
        h.principle_kode,
        h.principle_nama,
        a.penerimaan_pembelian_nopol,
        a.penerimaan_pembelian_pengemudi,
        i.client_wms_nama,
        e.depo_eksternal_kode
      FROM penerimaan_pembelian a
      LEFT JOIN penerimaan_pembelian_detail3 b
        ON a.penerimaan_pembelian_id = b.penerimaan_pembelian_id
      LEFT JOIN penerimaan_surat_jalan c
        ON b.penerimaan_surat_jalan_id = c.penerimaan_surat_jalan_id
      LEFT JOIN depo_eksternal e
        ON a.depo_id = e.depo_id
      LEFT JOIN depo f
        ON a.depo_id = f.depo_id
      LEFT JOIN ekspedisi g
        ON a.ekspedisi_id = g.ekspedisi_id
      LEFT JOIN principle h
        ON a.principle_id = h.principle_id
      LEFT JOIN client_wms i
		    ON a.client_wms_id = i.client_wms_id
      WHERE c.penerimaan_surat_jalan_id = '" . $id . "'");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->row_array();
    }

    return $query;
  }

  // public function GetContensPDF($id)
  // {
  //   //get sku_id di pallet_detail dan comparekan dengan data yg di penerimaan_pembelian_detail
  //   $getSkuId = $this->getSkuIdPalletDetail($id);

  //   $arr1 = [];
  //   $arrSkuId = [];
  //   $arrSkuIdToUseComparePalletDetail = [];
  //   foreach ($getSkuId as $key => $value) {
  //     array_push($arrSkuId, $value->sku_id);
  //   };

  //   $dataPenerimaanbarangDetail =  $this->dataPenerimaanbarangDetail($id);

  //   foreach ($dataPenerimaanbarangDetail as $key => $data) {
  //     if (in_array($data->sku_id, $arrSkuId)) {
  //       array_push($arr1, $data->sku_id);
  //     };
  //   };

  //   $dataSku = array_unique($arr1);
  //   $idx = 0;

  //   foreach ($dataSku as $key => $value) {
  //     $arrSkuIdToUseComparePalletDetail[$idx++] = "'" . $value . "'";
  //   };

  //   $query = $this->db->query("SELECT
  //       pbd.penerimaan_pembelian_detail_id AS id,
  //       pbd.sku_jumlah_barang AS jml_barang,
  //       pbd2.qty AS jml_terima,
  //       pbd.sku_exp_date,
  //       pbd.batch_no,
  //       sku.sku_kode AS sku_kode,
  //       sku.sku_nama_produk AS sku_nama,
  //       sku.sku_kemasan AS sku_kemasan,
  //       sku.sku_satuan AS sku_satuan
  //     FROM penerimaan_pembelian_detail pbd
  //     LEFT JOIN (SELECT
  //       pbd2.penerimaan_pembelian_id,
  //       sku_id,
  //       SUM(pd.sku_stock_qty) AS qty
  //     FROM penerimaan_pembelian_detail2 pbd2
  //     LEFT JOIN pallet_detail pd
  //       ON pbd2.pallet_id = pd.pallet_id
  //     WHERE pbd2.penerimaan_pembelian_id = '$id'
  //     AND pd.sku_id IN (" . implode(',', $arrSkuIdToUseComparePalletDetail) . ")
  //     GROUP BY pbd2.penerimaan_pembelian_id,
  //              sku_id) AS pbd2
  //       ON pbd.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id
  //       AND pbd.sku_id = pbd2.sku_id
  //     LEFT JOIN sku
  //       ON pbd.sku_id = sku.sku_id
  //     WHERE pbd.penerimaan_pembelian_id = '$id'");

  //   if ($query->num_rows() == 0) {
  //     $query = 0;
  //   } else {
  //     $query = $query->result();
  //   };

  //   return $query;
  // }

  public function GetContensPDF($id)
  {
    $query = $this->db->query("SELECT
    penerimaan_surat_jalan_no_sj,
    sku_kode,
    sku_nama_produk,
    SUM(ISNULL(sku_jumlah_barang, 0)) AS sku_jumlah_barang,
    SUM(ISNULL(terima, 0)) AS terima,
    SUM(ISNULL(rusak, 0)) AS rusak,
    SUM(ISNULL(barang_tertinggal, 0)) AS barang_tertinggal,
    SUM(ISNULL(kardus_rusak, 0)) AS kardus_rusak
  FROM penerimaan_surat_jalan_detail_temp2
  WHERE penerimaan_surat_jalan_id = '$id'
  GROUP BY penerimaan_surat_jalan_no_sj,
           sku_kode,
           sku_nama_produk
  ORDER BY penerimaan_surat_jalan_no_sj ASC");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result_array();
    }

    return $query;
  }

  public function getSkuIdPalletDetail($id)
  {
    $query = $this->db->query("SELECT 
        sku_id 
        FROM pallet_detail 
        WHERE pallet_id in (SELECT pallet_id 
        FROM penerimaan_pembelian_detail2 
        WHERE penerimaan_pembelian_id = '$id')");

    if ($query->num_rows() == 0) {
      $query = [];
    } else {
      $query = $query->result();
    }

    return $query;
  }

  public function dataPenerimaanbarangDetail($penerimaanBarangId)
  {
    $query = $this->db->query("SELECT penerimaan_pembelian_id, 
        penerimaan_pembelian_detail_id, 
        sku_id, 
        sku_exp_date, 
        isnull(sku_jumlah_terima, 0) as sku_jumlah_terima
        FROM penerimaan_pembelian_detail
        WHERE penerimaan_pembelian_id = '$penerimaanBarangId'");

    if ($query->num_rows() == 0) {
      $query = [];
    } else {
      $query = $query->result();
    }

    return $query;
  }

  public function GetDOPSJDetailTemp2($penerimaan_surat_jalan_id)
  {
    $query = $this->db->query("SELECT DISTINCT
    penerimaan_surat_jalan_id,
    shipment_number,
    penerimaan_surat_jalan_no_sj,
    is_download
    FROM penerimaan_surat_jalan_detail_temp2
    WHERE penerimaan_surat_jalan_id = '$penerimaan_surat_jalan_id'
    ORDER BY penerimaan_surat_jalan_no_sj ASC");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result_array();
    }

    return $query;
  }

  public function GetSKUPSJDetailTemp2BYDO($penerimaan_surat_jalan_id, $penerimaan_surat_jalan_no_sj)
  {
    $query = $this->db->query("SELECT
    sku_kode,
    sku_nama_produk,
    penerimaan_surat_jalan_no_sj,
    SUM(ISNULL(terima, 0)) AS terima
  FROM penerimaan_surat_jalan_detail_temp2
  WHERE penerimaan_surat_jalan_id = '$penerimaan_surat_jalan_id'
  AND penerimaan_surat_jalan_no_sj = '$penerimaan_surat_jalan_no_sj'
  GROUP BY sku_kode,
           sku_nama_produk,
           penerimaan_surat_jalan_no_sj
  ORDER BY penerimaan_surat_jalan_no_sj ASC");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result_array();
    }

    return $query;
  }

  public function GetHargaSatuanBosnet($sku_kode)
  {
    $db2 = $this->load->database('bosnet', TRUE);
    $query = $db2->query("SELECT a.[szProductId] ,(a.[decPurchPrice] * 1.11) as harga_satuan FROM [padma_live_replika].[dbo].[BOS_INV_ProductPurchaseInfo] a, [padma_live_replika].[dbo].[BOS_INV_Product] b where a.[szProductId]=b.[szProductId] and b.[szCategory_1]='SUNTORY' and a.[szProductId] = '$sku_kode'");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->row_array();
    }

    return $query;
  }

  public function GetIsDownloadPSJDetailTemp2($penerimaan_surat_jalan_id, $penerimaan_surat_jalan_no_sj)
  {
    $query = $this->db->query("SELECT TOP 1
    is_download
  FROM penerimaan_surat_jalan_detail_temp2
  WHERE penerimaan_surat_jalan_id = '$penerimaan_surat_jalan_id'
  AND penerimaan_surat_jalan_no_sj = '$penerimaan_surat_jalan_no_sj'");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->row_array();
    }

    return $query;
  }

  public function GetDataPOD($penerimaan_surat_jalan_id, $penerimaan_surat_jalan_no_sj)
  {
    $query = $this->db->query("SELECT
    NEWID() AS id,
    c.transaction_number,
    d.depo_eksternal_kode,
    b.penerimaan_surat_jalan_no_sj,
    c.delivery_order_line_number,
    b.sku_kode,
    SUM(ISNULL(b.terima, 0)) AS terima,
    c.sku_satuan,
    SUM(ISNULL(b.rusak, 0)) AS rusak,
    SUM(ISNULL(b.barang_tertinggal, 0)) AS barang_tertinggal,
    SUM(ISNULL(b.kardus_rusak, 0)) AS kardus_rusak,
    SUM(ISNULL(b.sumz, 0)) AS sumz,
    format(c.penerimaan_surat_jalan_tgl, 'dd/MM/yyyy') as penerimaan_surat_jalan_tgl
  FROM penerimaan_surat_jalan a
  LEFT JOIN penerimaan_surat_jalan_detail_temp2 b
    ON a.penerimaan_surat_jalan_id = b.penerimaan_surat_jalan_id
  LEFT JOIN penerimaan_surat_jalan_temp c
    ON b.penerimaan_surat_jalan_no_sj = c.penerimaan_surat_jalan_no_sj
    AND b.sku_kode = c.sku_kode
    AND b.shipment_number = c.shipment_number
    AND a.principle_id = c.principle_id
  LEFT JOIN depo_eksternal d
    ON a.depo_id = d.depo_id
  WHERE a.penerimaan_surat_jalan_id = '$penerimaan_surat_jalan_id'
  AND b.penerimaan_surat_jalan_no_sj = '$penerimaan_surat_jalan_no_sj'
  GROUP BY c.transaction_number,
           d.depo_eksternal_kode,
           b.penerimaan_surat_jalan_no_sj,
           c.delivery_order_line_number,
           b.sku_kode,
           c.sku_satuan,
           c.penerimaan_surat_jalan_tgl
  ORDER BY c.penerimaan_surat_jalan_tgl");

    if ($query->num_rows() == 0) {
      $query = 0;
    } else {
      $query = $query->result_array();
    }

    return $query;
  }

  public function UpdatePSJDetailTemp2($penerimaan_surat_jalan_detail_temp2_id, $terima, $rusak, $barang_tertinggal, $kardus_rusak, $total, $sumz)
  {
    $this->db->set('terima', $terima);
    $this->db->set('rusak', $rusak);
    $this->db->set('barang_tertinggal', $barang_tertinggal);
    $this->db->set('kardus_rusak', $kardus_rusak);
    $this->db->set('total', $total);
    $this->db->set('sumz', $sumz);

    $this->db->where('penerimaan_surat_jalan_detail_temp2_id', $penerimaan_surat_jalan_detail_temp2_id);

    $queryupdate = $this->db->update('penerimaan_surat_jalan_detail_temp2');

    $affectedrows = $this->db->affected_rows();

    if ($affectedrows > 0) {
      $queryupdate = 1;
    } else {
      $queryupdate = 0;
    }

    return $queryupdate;
  }

  public function updatePSJDetailTemp2SKU($arr_temp, $temp_penerimaan_surat_jalan_id)
  {
    $this->db->set('terima', 'sku_jumlah_barang', false);
    $this->db->where('penerimaan_surat_jalan_id', $temp_penerimaan_surat_jalan_id);
    $this->db->where_not_in('sku_kode', $arr_temp);

    $queryupdate = $this->db->update('penerimaan_surat_jalan_detail_temp2');

    $affectedrows = $this->db->affected_rows();

    if ($affectedrows > 0) {
      $queryupdate = 1;
    } else {
      $queryupdate = 0;
    }

    return $queryupdate;
  }

  public function UpdateIsStatusPSJ($penerimaan_surat_jalan_id, $mode)
  {
    $this->db->set('is_status', $mode);

    $this->db->where('penerimaan_surat_jalan_id', $penerimaan_surat_jalan_id);

    $queryupdate = $this->db->update('penerimaan_surat_jalan');

    $affectedrows = $this->db->affected_rows();

    if ($affectedrows > 0) {
      $queryupdate = 1;
    } else {
      $queryupdate = 0;
    }

    return $queryupdate;
  }

  public function UpdateIsDownloadPSJDetailTemp2($penerimaan_surat_jalan_id, $penerimaan_surat_jalan_no_sj, $nilai)
  {
    $this->db->set('is_download', $nilai);
    $this->db->where('penerimaan_surat_jalan_id', $penerimaan_surat_jalan_id);
    $this->db->where('penerimaan_surat_jalan_no_sj', $penerimaan_surat_jalan_no_sj);

    $queryupdate = $this->db->update('penerimaan_surat_jalan_detail_temp2');

    $affectedrows = $this->db->affected_rows();

    if ($affectedrows > 0) {
      $queryupdate = 1;
    } else {
      $queryupdate = 0;
    }

    return $queryupdate;
  }
}
