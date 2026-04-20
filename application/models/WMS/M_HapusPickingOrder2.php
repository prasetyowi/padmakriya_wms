<?php

class M_HapusPickingOrder2 extends CI_Model
{
    public function getKodeAutoComplete($value)
    {
        return $this->db->select("picking_order_kode as kode")
            ->from("picking_order")
            ->where("depo_id", $this->session->userdata('depo_id'))
            ->like("picking_order_kode", $value)->get()->result();
    }

    public function Get_DetailPengeluaranBarangManual($picking_order_kode)
    {
        $query = $this->db->query("SELECT
										picking_order.picking_order_id,
										picking_order_plan.picking_order_plan_id,
										picking_order.depo_id,
										picking_order.depo_detail_id,
										depo_detail.depo_detail_nama,
										ISNULL(rak.rak_nama, '') AS rak_nama,
										depo.depo_nama,
										picking_order.picking_order_kode,
										picking_order_plan.picking_order_plan_nourut,
										FORMAT(picking_order.picking_order_tanggal, 'yyyy-MM-dd') AS picking_order_tanggal,
										picking_order_plan.delivery_order_id,
										delivery_order.delivery_order_kode,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order_batch.delivery_order_batch_tanggal_kirim,
										picking_list.picking_list_id,
										picking_list.picking_list_kode,
										picking_list.picking_list_tgl_kirim,
										picking_order_plan.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias AS picking_order_plan_tipe,
										tipe_delivery_order.tipe_delivery_order_nama,
										picking_order.picking_order_keterangan,
										picking_order.picking_order_status,
										picking_order_plan.picking_order_plan_status,
										picking_order.picking_order_tgl_update,
										principal.principle_kode AS principal,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.sku_satuan,
										sku.sku_kemasan,
										picking_order_plan.sku_id,
										picking_order_plan.sku_stock_id,
										FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
										picking_order_plan.sku_stock_qty_ambil AS sku_stock_qty_ambil,
										SUM(picking_order_aktual.sku_stock_qty_ambil) AS sku_stock_qty_ambil_aktual,
										CASE
										WHEN picking_order_aktual.karyawan_id IS NOT NULL THEN picking_order_aktual.karyawan_id
										ELSE picking_order_plan.karyawan_id
										END AS karyawan_id,
										CASE
										WHEN picking_order_aktual.karyawan_nama IS NOT NULL THEN picking_order_aktual.karyawan_nama
										ELSE picking_order_plan.karyawan_nama
										END AS karyawan_nama,
										karyawan.karyawan_nama as driver
									FROM picking_order
									LEFT JOIN picking_order_plan
										ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN (SELECT
										picking_order_aktual_h.picking_order_id,
										picking_order_aktual_h.karyawan_id,
										picking_order_aktual_h.karyawan_nama,
										picking_order_aktual_d.picking_order_plan_id,
										picking_order_aktual_d.delivery_order_id,
										picking_order_aktual_d.sku_id,
										picking_order_aktual_d.sku_stock_id,
										picking_order_aktual_d.sku_stock_expired_date,
										SUM(picking_order_aktual_d.sku_stock_qty_ambil) AS sku_stock_qty_ambil
									FROM picking_order_aktual_h
									LEFT JOIN picking_order_aktual_d
										ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
									GROUP BY picking_order_aktual_h.picking_order_id,
											picking_order_aktual_h.karyawan_id,
											picking_order_aktual_h.karyawan_nama,
											picking_order_aktual_d.picking_order_plan_id,
											picking_order_aktual_d.delivery_order_id,
											picking_order_aktual_d.sku_id,
											picking_order_aktual_d.sku_stock_id,
											picking_order_aktual_d.sku_stock_expired_date) picking_order_aktual
										ON picking_order.picking_order_id = picking_order_aktual.picking_order_id
										AND picking_order_aktual.picking_order_plan_id = picking_order_plan.picking_order_plan_id
									LEFT JOIN picking_list
										ON picking_list.picking_list_id = picking_order.picking_list_id
									LEFT JOIN delivery_order
										ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN delivery_order_batch
										ON picking_list.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN karyawan 
										ON delivery_order_batch.karyawan_id = karyawan.karyawan_id
									LEFT JOIN sku_stock
										ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN rak_sku
										ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
									LEFT JOIN rak
										ON rak.rak_id = rak_sku.rak_id
									LEFT JOIN SKU
										ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN sku_induk
										ON SKU.sku_induk_id = sku_induk.sku_induk_id
									LEFT JOIN principle principal
										ON sku.principle_id = principal.principle_id
									LEFT JOIN depo_detail
										ON depo_detail.depo_detail_id = picking_order.depo_detail_id
									LEFT JOIN depo
										ON depo.depo_id = picking_order.depo_id
									LEFT JOIN tipe_delivery_order
										ON tipe_delivery_order.tipe_delivery_order_id = picking_order_plan.tipe_delivery_order_id
									WHERE picking_order.picking_order_kode = '$picking_order_kode'
									GROUP BY picking_order.picking_order_id,
											picking_order_plan.picking_order_plan_id,
											picking_order.depo_id,
											picking_order.depo_detail_id,
											depo_detail.depo_detail_nama,
											ISNULL(rak.rak_nama, ''),
											depo.depo_nama,
											picking_order.picking_order_kode,
											picking_order_plan.picking_order_plan_nourut,
											FORMAT(picking_order.picking_order_tanggal, 'yyyy-MM-dd'),
											picking_order_plan.delivery_order_id,
											delivery_order.delivery_order_kode,
											delivery_order_batch.delivery_order_batch_kode,
											delivery_order_batch.delivery_order_batch_tanggal_kirim,
											picking_list.picking_list_id,
											picking_list.picking_list_kode,
											picking_list.picking_list_tgl_kirim,
											picking_order_plan.tipe_delivery_order_id,
											tipe_delivery_order.tipe_delivery_order_alias,
											tipe_delivery_order.tipe_delivery_order_nama,
											picking_order.picking_order_keterangan,
											picking_order.picking_order_status,
											picking_order_plan.picking_order_plan_status,
											picking_order.picking_order_tgl_update,
											principal.principle_kode,
											sku.sku_kode,
											sku.sku_nama_produk,
											sku.sku_satuan,
											sku.sku_kemasan,
											picking_order_plan.sku_id,
											picking_order_plan.sku_stock_id,
											FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
											picking_order_plan.sku_stock_qty_ambil,
											CASE
												WHEN picking_order_aktual.karyawan_id IS NOT NULL THEN picking_order_aktual.karyawan_id
												ELSE picking_order_plan.karyawan_id
											END,
											CASE
												WHEN picking_order_aktual.karyawan_nama IS NOT NULL THEN picking_order_aktual.karyawan_nama
												ELSE picking_order_plan.karyawan_nama
											END,
											karyawan.karyawan_nama
									ORDER BY principal.principle_kode, sku.sku_kode, FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') ASC");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function Get_DetailPengeluaranBarangManualMix($picking_order_kode)
    {

        $pickingListId = $this->db->select("picking_list_id")->from("picking_order")->where("picking_order_kode", $picking_order_kode)->get()->row()->picking_list_id;

        $query = $this->db->query("SELECT
																	picking_order.picking_order_id,
																	picking_order_plan.picking_order_plan_id,
																	picking_order.depo_id,
																	picking_order.depo_detail_id,
																	depo_detail.depo_detail_nama,
																	ISNULL(rak.rak_nama, '') AS rak_nama,
																	depo.depo_nama,
																	picking_order.picking_order_kode,
																	picking_order_plan.picking_order_plan_nourut,
																	FORMAT(picking_order.picking_order_tanggal, 'yyyy-MM-dd') AS picking_order_tanggal,
																	--picking_order_plan.delivery_order_id,
																	--delivery_order.delivery_order_kode,
																	delivery_order_batch.delivery_order_batch_kode,
																	delivery_order_batch.delivery_order_batch_tanggal_kirim,
																	picking_list.picking_list_id,
																	picking_list.picking_list_kode,
																	picking_list.picking_list_tgl_kirim,
																	delivery_order.tipe_delivery_order_id,
																	CASE 
																		WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_nama
																		WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk')
																		ELSE NULL
																	END as tipe_delivery_order_nama,
																	CASE 
																		WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_alias
																		WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU')
																		ELSE NULL
																	END as picking_order_plan_tipe,
																	--ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') AS tipe_delivery_order_nama,
																	--ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU') AS picking_order_plan_tipe,
																	picking_order.picking_order_keterangan,
																	picking_order.picking_order_status,
																	picking_order_plan.picking_order_plan_status,
																	principal.principle_kode AS principal,
																	sku.sku_kode,
																	sku.sku_nama_produk,
																	sku.sku_satuan,
																	sku.sku_kemasan,
																	picking_order_plan.sku_id,
																	picking_order_plan.sku_stock_id,
																	FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
																	picking_order_plan.sku_stock_qty_ambil AS sku_stock_qty_ambil,
																	picking_order_aktual.sku_stock_qty_ambil AS sku_stock_qty_ambil_aktual,
																	CASE
																		WHEN picking_order_aktual.karyawan_id IS NOT NULL THEN picking_order_aktual.karyawan_id
																		ELSE picking_order_plan.karyawan_id
																	END AS karyawan_id,
																	CASE
																		WHEN picking_order_aktual.karyawan_nama IS NOT NULL THEN picking_order_aktual.karyawan_nama
																		ELSE picking_order_plan.karyawan_nama
																	END AS karyawan_nama
																FROM picking_order
																LEFT JOIN picking_order_plan
																	ON picking_order.picking_order_id = picking_order_plan.picking_order_id
																LEFT JOIN (SELECT
																	picking_order_aktual_h.picking_order_id,
																	picking_order_aktual_h.karyawan_id,
																	picking_order_aktual_h.karyawan_nama,
																	picking_order_aktual_d.picking_order_plan_id,
																	picking_order_aktual_d.delivery_order_id,
																	picking_order_aktual_d.sku_id,
																	picking_order_aktual_d.sku_stock_id,
																	picking_order_aktual_d.sku_stock_expired_date,
																	SUM(picking_order_aktual_d.sku_stock_qty_ambil) AS sku_stock_qty_ambil
																FROM picking_order_aktual_h
																LEFT JOIN picking_order_aktual_d
																	ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
																GROUP BY picking_order_aktual_h.picking_order_id,
																				picking_order_aktual_h.karyawan_id,
																				picking_order_aktual_h.karyawan_nama,
																				picking_order_aktual_d.picking_order_plan_id,
																				picking_order_aktual_d.delivery_order_id,
																				picking_order_aktual_d.sku_id,
																				picking_order_aktual_d.sku_stock_id,
																				picking_order_aktual_d.sku_stock_expired_date) picking_order_aktual
																	ON picking_order.picking_order_id = picking_order_aktual.picking_order_id
																	AND picking_order_aktual.picking_order_plan_id = picking_order_plan.picking_order_plan_id
																LEFT JOIN picking_list
																	ON picking_list.picking_list_id = picking_order.picking_list_id
																LEFT JOIN (select
																							do.delivery_order_id,
																							do.delivery_order_kode,
																							do.delivery_order_reff_id,
																							do.tipe_delivery_order_id,
																							dod2.sku_id,
																							dod2.sku_stock_id
																						from delivery_order do
																						left join delivery_order_detail2 dod2
																							on dod2.delivery_order_id = do.delivery_order_id
																						where do.delivery_order_id in (select delivery_order_id from picking_list_detail where picking_list_id = '$pickingListId')) delivery_order
																	ON delivery_order.sku_stock_id = picking_order_plan.sku_stock_id
																LEFT JOIN delivery_order_batch
																	ON picking_list.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
																LEFT JOIN sku_stock
																	ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
																LEFT JOIN rak_sku
																	ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
																LEFT JOIN rak
																	ON rak.rak_id = rak_sku.rak_id
																LEFT JOIN SKU
																	ON SKU.sku_id = picking_order_plan.sku_id
																LEFT JOIN sku_induk
																	ON SKU.sku_induk_id = sku_induk.sku_induk_id
																LEFT JOIN principle principal
																	ON sku.principle_id = principal.principle_id
																LEFT JOIN depo_detail
																	ON depo_detail.depo_detail_id = picking_order.depo_detail_id
																LEFT JOIN depo
																	ON depo.depo_id = picking_order.depo_id
																LEFT JOIN tipe_delivery_order
																	ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
																WHERE picking_order.picking_order_kode = '$picking_order_kode'
																GROUP BY picking_order.picking_order_id,
																				picking_order_plan.picking_order_plan_id,
																				picking_order.depo_id,
																				picking_order.depo_detail_id,
																				depo_detail.depo_detail_nama,
																				ISNULL(rak.rak_nama, ''),
																				depo.depo_nama,
																				picking_order.picking_order_kode,
																				picking_order_plan.picking_order_plan_nourut,
																				FORMAT(picking_order.picking_order_tanggal, 'yyyy-MM-dd'),
																				--picking_order_plan.delivery_order_id,
																				--delivery_order.delivery_order_kode,
																				delivery_order_batch.delivery_order_batch_kode,
																				delivery_order_batch.delivery_order_batch_tanggal_kirim,
																				picking_list.picking_list_id,
																				picking_list.picking_list_kode,
																				picking_list.picking_list_tgl_kirim,
																				delivery_order.tipe_delivery_order_id,
																				picking_order_aktual.sku_stock_qty_ambil,
																				CASE 
																					WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_nama
																					WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk')
																					ELSE NULL
																				END,
																				CASE 
																					WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_alias
																					WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU')
																					ELSE NULL
																				END,
																				--ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk'),
																				--ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU'),
																				picking_order.picking_order_keterangan,
																				picking_order.picking_order_status,
																				picking_order_plan.picking_order_plan_status,
																				principal.principle_kode,
																				sku.sku_kode,
																				sku.sku_nama_produk,
																				sku.sku_satuan,
																				sku.sku_kemasan,
																				picking_order_plan.sku_id,
																				picking_order_plan.sku_stock_id,
																				FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
																				picking_order_plan.sku_stock_qty_ambil,
																				CASE
																					WHEN picking_order_aktual.karyawan_id IS NOT NULL THEN picking_order_aktual.karyawan_id
																					ELSE picking_order_plan.karyawan_id
																				END,
																				CASE
																					WHEN picking_order_aktual.karyawan_nama IS NOT NULL THEN picking_order_aktual.karyawan_nama
																					ELSE picking_order_plan.karyawan_nama
																				END
																ORDER BY principal.principle_kode, sku.sku_kode, FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') ASC");



        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        // return $this->dbwms->last_query();
        return $query;
    }

    public function getDepoPreffix()
    {
        return $this->db->query("SELECT depo_kode_preffix FROM depo where depo_id = '" . $this->session->userdata('depo_id') . "'")->row_array();
    }

    public function deletePickingOrder($picking_order_kode)
    {
        return $this->db->query("exec proses_maintenance_dokumen_bkb2  '" . $picking_order_kode . "'")->row_array();
    }
}
