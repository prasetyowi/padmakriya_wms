<?php

class M_SKU extends CI_Model
{
	private $table_name = 'sku';

	private $primary_key = 'sku_id';

	private $cols = [
        [
            'name' => 'sku_induk_nama',
            'filterType' => 'similar',
        ],
        [
            'name' => 'sku_nama_produk',
            'filterType' => 'similar',
        ],
        [
            'name' => 'sku_kemasan',
            'filterType' => 'similar',
        ],
        [
            'name' => 'sku_satuan',
            'filterType' => 'similar',
        ],
        [
            'name' => 'principle_nama',
            'filterType' => 'similar',
        ],
		[
            'name' => 'principle_brand_nama',
            'filterType' => 'similar',
        ],
    ];

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function Getsatuan_unit_barang_by_nama_satuan( $nama_satuan )
	{
		$query = $this->db->query("select * from dbo.Satuan_unitBarang() where nama_satuan = '$nama_satuan'");
		
		if( $query->num_rows() == 0){ return 0;	}
		else						{ return 1;	}
	}
	
	public function Getsku_konversi_all_except_self( $sku_id )
	{
		$this->db	->select("sku_id, sku_kode, sku_nama_produk, k.kemasan_id, k.kemasan_kode, k.kemasan_nama, k.flag_satuan_terkecil")
					->from("sku as s")
					->join("kemasan as k","k.kemasan_id = s.kemasan_id","inner")
					->where("sku_id <>", $sku_id)
					->where("flag_satuan_terkecil", 1)
					->order_by("sku_nama_produk");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
		
	public function Getsku_konversi_all_by_sku_induk_id( $sku_induk_id )
	{
		$this->db	->select("	sku_id, sku_kode, sku_nama_produk, null as kemasan_id, sku_kemasan as kemasan_kode, 
								sku_kemasan as kemasan_nama, 0 as sku_flag_satuan_terkecil", FALSE )
					->from("sku as s")
					//->join("kemasan as k","k.kemasan_id = s.kemasan_id","inner")
					->where("sku_induk_id", $sku_induk_id)
					->order_by("sku_nama_produk");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
	
	
	public function Getsku_non_hadiah_search( $q )
	{
		// si = sku_induk, k1 = Principle, k2 = Kategori, k = kemasan
		$this->db	->select("	TOP 20 sku_id, sku_kode, sku_nama_produk, sku_satuan,
								si.sku_induk_id, si.sku_induk_kode, si.sku_induk_nama", FALSE) 
					->from("sku as s")
					->join("sku_induk as si","s.sku_induk_id = si.sku_induk_id", "inner")
					->where("isnull( sku_is_hadiah, 0) =", 0, FALSE )
					->like("sku_nama_produk", $q )
					->order_by("sku_kode");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
		
	public function Getsku_non_hadiah()
	{
		// si = sku_induk, k1 = Principle, k2 = Kategori, k = kemasan
		$this->db	->select("	TOP 20 sku_id, sku_kode, sku_nama_produk, sku_satuan,
								si.sku_induk_id, si.sku_induk_kode, si.sku_induk_nama", FALSE) 
					->from("sku as s")
					->join("sku_induk as si","s.sku_induk_id = si.sku_induk_id", "inner")
					->where("isnull( sku_is_hadiah, 0) =", 0, FALSE )
					->order_by("sku_kode");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
		
	public function Getsku_specific()
	{
		// si = sku_induk, k1 = Principle, k2 = Kategori, k = kemasan
		$this->db	->select("	sku_id, sku_kode, sku_nama_produk, sku_kemasan, sku_satuan, sku_harga_jual,
								si.sku_induk_id, si.sku_induk_kode, si.sku_induk_nama,
								k1.kategori_id as kategori1_id, k1.kategori_kode, k1.kategori_nama, 
								k2.kategori_id as kategori2_id, k2.kategori_kode as kategori2_kode, k2.kategori_nama as kategori2_nama, 
								k3.kategori_id as kategori3_id, k3.kategori_kode as kategori3_kode, k3.kategori_nama as kategori3_nama, 
								k4.kategori_id as kategori4_id, k4.kategori_kode as kategori4_kode, k4.kategori_nama as kategori4_nama, 
								
								p.principle_id, p.principle_kode, p.principle_nama, 
								pb.principle_brand_id, pb.principle_kode, pb.principle_brand_nama
								") 
					->from("sku as s")
					->join("sku_induk as si","s.sku_induk_id = si.sku_induk_id", "inner")
					->join("kategori as k1","k1.kategori_id = s.kategori1_id and k1.kategori_level = 0", "left")
					->join("kategori as k2","k2.kategori_id = s.kategori2_id and k2.kategori_level = 1", "left")
					->join("kategori as k3","k3.kategori_id = s.kategori3_id and k3.kategori_level = 2", "left")
					->join("kategori as k4","k4.kategori_id = s.kategori4_id and k4.kategori_level = 3", "left")
					
					->join("principle as p","p.principle_id = s.principle_id", "left")
					->join("principle_brand as pb","pb.principle_brand_id = s.principle_brand_id", "left")
					->order_by("sku_kode");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
		
	public function Getsku_specific_non_hadiah()
	{
		// si = sku_induk, k1 = Principle, k2 = Kategori, k = kemasan
		$this->db	->select("	sku_id, sku_kode, sku_nama_produk, sku_kemasan, sku_satuan, sku_harga_jual,
								si.sku_induk_id, si.sku_induk_kode, si.sku_induk_nama,
								k1.kategori1_id, k1.kategori_kode, k1.kategori_nama, 
								k2.kategori1_id as kategori2_id, k2.kategori_kode as kategori2_kode, k2.kategori_nama as kategori2_nama, 
								k3.kategori1_id as kategori3_id, k3.kategori_kode as kategori3_kode, k3.kategori_nama as kategori3_nama, 
								k4.kategori1_id as kategori4_id, k4.kategori_kode as kategori4_kode, k4.kategori_nama as kategori4_nama, 
								
								p.principle_id, p.principle_kode, p.principle_nama, 
								pb.principle_brand_id, pb.principle_kode, pb.principle_brand_nama
								") 
					->from("sku as s")
					->join("sku_induk as si","s.sku_induk_id = si.sku_induk_id", "inner")
					->join("kategori as k1","k1.kategori1_id = s.kategori1_id and k1.kategori_level = 0", "left")
					->join("kategori as k2","k2.kategori1_id = s.kategori2_id and k2.kategori_level = 1", "left")
					->join("kategori as k3","k3.kategori1_id = s.kategori3_id and k3.kategori_level = 2", "left")
					->join("kategori as k4","k4.kategori1_id = s.kategori4_id and k4.kategori_level = 3", "left")
					
					->join("principle as p","p.principle_id = s.principle_id", "left")
					->join("principle_brand as pb","pb.principle_brand_id = s.principle_brand_id", "left")
					->where("isnull( sku_is_hadiah, 0) =", 0, FALSE)
					->order_by("sku_kode");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
		
	public function Getsku()
	{
		// si = sku_induk, k1 = Principle, k2 = Kategori, k = kemasan
		$this->db	->select("	sku_id, sku_kode, sku_varian, sku_weight, sku_weight_unit, sku_length, sku_length_unit, sku_width,
								sku_width_unit, sku_height, sku_height_unit, sku_volume, sku_volume_unit, sku_harga_jual, 
								sku_nama_produk, sku_deskripsi, sku_origin, sku_kondisi, sku_sales_min_qty, sku_ppnbm_persen, sku_ppn_persen, sku_pph,
								sku_is_aktif, sku_is_jual, sku_kemasan, sku_satuan,
								si.sku_induk_id, si.sku_induk_kode, si.sku_induk_nama,
								k1.kategori1_id, k1.kategori_kode, k1.kategori_nama, 
								k2.kategori1_id as kategori2_id, k2.kategori_kode as kategori2_kode, k2.kategori_nama as kategori2_nama, 
								k3.kategori1_id as kategori3_id, k3.kategori_kode as kategori3_kode, k3.kategori_nama as kategori3_nama, 
								k4.kategori1_id as kategori4_id, k4.kategori_kode as kategori4_kode, k4.kategori_nama as kategori4_nama, 
								
								p.principle_id, p.principle_kode, p.principle_nama, 
								pb.principle_brand_id, pb.principle_kode, pb.principle_brand_nama
								") 
					->from("sku as s")
					->join("sku_induk as si","s.sku_induk_id = si.sku_induk_id", "inner")
					->join("kategori as k1","k1.kategori1_id = s.kategori1_id and k1.kategori_level = 0", "left")
					->join("kategori as k2","k2.kategori1_id = s.kategori2_id and k2.kategori_level = 1", "left")
					->join("kategori as k3","k3.kategori1_id = s.kategori3_id and k3.kategori_level = 2", "left")
					->join("kategori as k4","k4.kategori1_id = s.kategori4_id and k4.kategori_level = 3", "left")
					
					->join("principle as p","p.principle_id = s.principle_id", "left")
					->join("principle_brand as pb","pb.principle_brand_id = s.principle_brand_id", "left")
					->order_by("sku_kode");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
				
	public function Getsku_by_sku_id( $sku_id )
	{
		$this->db	->select("	sku_id, sku_kode, sku_varian, sku_weight, sku_weight_unit, sku_length, sku_length_unit, sku_width,
								sku_width_unit, sku_height, sku_height_unit, sku_volume, sku_volume_unit, sku_harga_jual, 
								sku_nama_produk, sku_deskripsi, sku_origin, sku_kondisi, sku_sales_min_qty, sku_ppnbm_persen, sku_ppn_persen, sku_pph,
								sku_is_aktif, sku_is_jual, sku_satuan,
								si.sku_induk_id, si.sku_induk_kode, si.sku_induk_nama,
								k1.kategori1_id, k1.kategori_kode, k1.kategori_nama, 
								k2.kategori1_id as kategori2_id, k2.kategori_kode as kategori2_kode, k2.kategori_nama as kategori2_nama, 
								k3.kategori1_id as kategori3_id, k3.kategori_kode as kategori3_kode, k3.kategori_nama as kategori3_nama, 
								k4.kategori1_id as kategori4_id, k4.kategori_kode as kategori4_kode, k4.kategori_nama as kategori4_nama, 
								
								p.principle_id, p.principle_kode, p.principle_nama, 
								pb.principle_brand_id, pb.principle_kode, pb.principle_brand_nama", FALSE) 
					->from("sku as s")
					->join("sku_induk as si","s.sku_induk_id = si.sku_induk_id", "inner")
					->join("kategori as k1","k1.kategori1_id = s.kategori1_id and k1.kategori_level = 0", "left")
					->join("kategori as k2","k2.kategori1_id = s.kategori2_id and k2.kategori_level = 1", "left")
					->join("kategori as k3","k3.kategori1_id = s.kategori3_id and k3.kategori_level = 2", "left")
					->join("kategori as k4","k4.kategori1_id = s.kategori4_id and k4.kategori_level = 4", "left")
					
					->join("principle as p","p.principle_id = s.principle_id", "left")
					->join("principle_brand as pb","pb.principle_brand_id = s.principle_brand_id", "left")
					->where("sku_id", $sku_id);
		$query = $this->db->get();	
		
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = $query->result_array(); }
		
		return $query;
	}
	
	public function Checkinsert_sku_duplicate_others_kode( $sku_kode )
	{
		$this->db	->select("sku_id")
					->from("sku")
					->where("sku_kode", $sku_kode);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Checkinsert_sku_diplicate_others_sku_nama_produk( $sku_nama_produk )
	{
		$this->db	->select("sku_id")
					->from("sku")
					->where("sku_nama_produk", $sku_nama_produk);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Checkupdate_sku_Duplicate_Others_kode( $sku_id, $sku_kode )
	{
		$this->db	->select("sku_id")
					->from("sku")
					->where("sku_kode", $sku_kode)
					->where("sku_id <>", $sku_id);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Checkupdate_sku_Duplicate_Others_namaProduk( $sku_id, $sku_nama_produk )
	{
		$this->db	->select("sku_id")
					->from("sku")
					->where("sku_nama_produk", $sku_nama_produk)
					->where("sku_id <>", $sku_id);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Getsku_by_sku_induk_id( $sku_induk_id ) 
	{	
		$this->db	->select("	sku_id, sku_kode, sku_nama_produk, sku_deskripsi, null as kemasan_id, sku_kemasan as kemasan_nama,
								sku_kondisi, sku_origin, sku_harga_jual, sku_sales_min_qty, sku_satuan,
								sku_pph, sku_ppnbm_persen, sku_ppn_persen, sku_is_aktif, s.sku_is_jual, 
								sku_weight_netto, sku_weight_netto_unit, sku_weight_product, sku_weight_product_unit,
								sku_weight_packaging, sku_weight_packaging_unit, sku_weight_gift, sku_weight_gift_unit,
								sku_length, sku_length_unit, sku_width, sku_width_unit, 
								sku_height, sku_height_unit, sku_volume, sku_volume_unit, sku_bosnet_id", FALSE)
					->from("sku as s")
					->where("sku_induk_id", $sku_induk_id)
					->order_by("sku_kode","ASC");
					
		$query = $this->db->get();
		
		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return $query->result_array();	}
	}
	
	public function Getsku_By_sku_induk_id_sku_is_hadiah( $sku_induk_id ) 
	{	
		$this->db	->select("	sku_id, sku_kode, sku_nama_produk, sku_deskripsi, null as kemasan_id, sku_kemasan as kemasan_nama,
								sku_kondisi, sku_origin, sku_harga_jual, sku_sales_min_qty, sku_satuan,
								sku_pph, sku_ppnbm_persen, sku_ppn_persen, sku_is_aktif, s.sku_is_jual, 
								sku_weight_netto, sku_weight_netto_unit, sku_weight_product, sku_weight_product_unit,
								sku_weight_packaging, sku_weight_packaging_unit, sku_weight_gift, sku_weight_gift_unit,
								sku_length, sku_length_unit, sku_width, sku_width_unit, 
								sku_height, sku_height_unit, sku_volume, sku_volume_unit, skubosnet_id", FALSE)
					->from("sku as s")
					//->join("kemasan as k","k.kemasan_id = s.kemasan_id","inner")
					->where("sku_induk_id", $sku_induk_id)
					->where("isnull( sku_is_hadiah, 0 ) =", 1, FALSE)
					->order_by("sku_kode","ASC");
					
		$query = $this->db->get();
		
		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return $query->result_array();	}
	}
	
	public function Getsku_bosnet_by_sku_induk_id( $sku_induk_id ) 
	{	
		$this->db	->select("sku_bosnet_id, sku_id, sku_induk_id, bosnet_id")
					->from("sku_bosnet")
					->where("sku_induk_id", $sku_induk_id)
					->order_by("bosnet_id","ASC");
					
		$query = $this->db->get();
		
		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return $query->result_array();	}
	}
	/*
	public function Insertsku( 	$sku_kode, $sku_varian, $sku_induk_id, $sku_weight, $sku_weight_unit, $sku_length, $sku_length_unit, $sku_width,
								$sku_width_unit, $sku_height, $sku_height_unit, $sku_volume, $sku_volume_unit, $kemasan_id, $sku_harga_jual, $kategori1_id,
								$kategori2_id, $kategori3_id, $kategori4_id, $principle_id, $principle_brand_id,
								$sku_nama_produk, $sku_deskripsi, $sku_origin, $sku_kondisi, $sku_sales_min_qty, $sku_ppnbm_persen, $sku_ppn_persen, $sku_pph,
								$sku_is_aktif, $sku_is_jual )
	{	
		if( $kategori1_id == '' ){ $kategori1_id = null; }
		if( $kategori2_id == '' ){ $kategori2_id = null; }
		if( $kategori3_id == '' ){ $kategori3_id = null; }
		if( $kategori4_id == '' ){ $kategori4_id = null; }
		
		$this->db->set("sku_id", "NEWID()", FALSE);
		$this->db->set("sku_kode", $sku_kode);
		$this->db->set("sku_varian", $sku_varian);	
		$this->db->set("sku_harga_jual", $sku_harga_jual);
		$this->db->set("sku_origin", $sku_origin);
		$this->db->set("sku_nama_produk", $sku_nama_produk);		
		$this->db->set("sku_deskripsi", $sku_deskripsi);		
		$this->db->set("sku_kondisi", $sku_kondisi);
		$this->db->set("sku_sales_min_qty", $sku_sales_min_qty);		
		$this->db->set("sku_ppnbm_persen", $sku_ppnbm_persen);		
		$this->db->set("sku_ppn_persen", $sku_ppn_persen);
		$this->db->set("sku_ppn", $sku_ppn);	
		
		$this->db->set("sku_induk_id", $sku_induk_id);	
		$this->db->set("kemasan_id", $kemasan_id);	
		
		$this->db->set("sku_weight", $sku_weight);		
		$this->db->set("sku_weight_unit", $sku_weight_unit);
		$this->db->set("sku_length", $sku_length);		
		$this->db->set("sku_length_unit", $sku_length_unit);		
		$this->db->set("sku_width", $sku_width);
		$this->db->set("sku_width_unit", $sku_width_unit);
		$this->db->set("sku_height", $sku_height);
		$this->db->set("sku_height_unit", $sku_height_unit);
		$this->db->set("sku_volume", $sku_volume);		
		$this->db->set("sku_volume_unit", $sku_volume_unit);	
		
		$this->db->set("sku_is_aktif", $sku_is_aktif);	
		$this->db->set("sku_is_jual", $sku_is_jual);	
		
		$this->db->set("kategori1_id", $kategori1_id);
		$this->db->set("kategori2_id", $kategori2_id);		
		$this->db->set("kategori3_id", $kategori3_id);		
		$this->db->set("kategori4_id", $kategori4_id);		
		
		$this->db->set("principle_id", $principle_id);		
		$this->db->set("principle_brand_id", $principle_brand_id);	
		
		$this->db->insert("sku");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryinsert = 1;	}
		else					{	$queryinsert = 0;	}
	
		return $queryinsert;
	}
	*/
	public function Insertsku_temp(	$batu_id, $sku_id, $sku_kode, $sku_varian, $sku_nama_produk, $sku_deskripsi, $sku_kemasan, $sku_satuan,
									$sku_kondisi, $sku_origin, $sku_harga_jual, $sku_sales_min_qty,
									$sku_pph, $sku_ppnbm_persen, $sku_ppn_persen, $sku_is_aktif, $sku_is_jual, 
									$sku_weight_netto, $sku_weight_netto_unit, $sku_weight_product,	$sku_weight_product_unit,
									$sku_weight_packaging, $sku_weight_packaging_unit,	$sku_weight_gift, $sku_weight_gift_unit,
									$sku_length, $sku_length_unit, $sku_width, $sku_width_unit, 
									$sku_height, $sku_height_unit, $sku_volume, $sku_volume_unit,
									$kategori1_id, $kategori2_id, $kategori3_id, $kategori4_id, $principle_id, $principle_brand_id )
	{
		$this->db->set("batu_id", $batu_id );
		$this->db->set("sku_id", $sku_id ); 
		$this->db->set("sku_kode", $sku_kode ); 
		$this->db->set("sku_varian", $sku_varian ); 
		$this->db->set("sku_nama_produk", $sku_nama_produk );
		$this->db->set("sku_deskripsi", $sku_deskripsi );
		$this->db->set("sku_kemasan", $sku_kemasan );
		$this->db->set("sku_satuan", $sku_satuan );
		$this->db->set("sku_kondisi", $sku_kondisi );
		$this->db->set("sku_origin", $sku_origin );
		$this->db->set("sku_harga_jual", $sku_harga_jual );
		$this->db->set("sku_sales_min_qty", $sku_sales_min_qty );
		$this->db->set("sku_pph", $sku_pph );
		$this->db->set("sku_ppnbm_persen", $sku_ppnbm_persen );
		$this->db->set("sku_ppn_persen", $sku_ppn_persen );
		$this->db->set("sku_is_aktif", $sku_is_aktif );
		$this->db->set("sku_is_jual", $sku_is_jual );
		$this->db->set("sku_weight_netto", $sku_weight_netto );
		$this->db->set("sku_weight_netto_unit", $sku_weight_netto_unit );
		$this->db->set("sku_weight_product", $sku_weight_product );
		$this->db->set("sku_weight_product_unit", $sku_weight_product_unit );
		$this->db->set("sku_weight_packaging", $sku_weight_packaging );
		$this->db->set("sku_weight_packaging_unit", $sku_weight_packaging_unit );
		$this->db->set("sku_weight_gift", $sku_weight_gift );
		$this->db->set("sku_weight_gift_unit", $sku_weight_gift_unit );
		$this->db->set("sku_length", $sku_length );
		$this->db->set("sku_length_unit", $sku_length_unit );
		$this->db->set("sku_width", $sku_width );
		$this->db->set("sku_width_unit", $sku_width_unit );
		$this->db->set("sku_height", $sku_height );
		$this->db->set("sku_height_unit", $sku_height_unit );
		$this->db->set("sku_volume", $sku_volume );
		$this->db->set("sku_volume_unit", $sku_volume_unit );
		$this->db->set("kategori1_id", $kategori1_id );
		$this->db->set("kategori2_id", $kategori2_id );
		$this->db->set("kategori3_id", $kategori3_id );
		$this->db->set("kategori4_id", $kategori4_id );
		$this->db->set("principle_id", $principle_id );
		$this->db->set("principle_brand_id", $principle_brand_id );
		
		$this->db->insert("sku_temp");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	return 1;	}
		else					{	return 0;	}
	}	
	
	public function Insertsku_bosnet( $sku_id, $sku_induk_id, $bosnet_id )
	{
		$this->db->set("skubosnet_id", "NEWID()", FALSE);
		$this->db->set("sku_id", $sku_id );
		$this->db->set("sku_induk_id", $sku_induk_id );
		$this->db->set("bosnet_id", $bosnet_id );
		
		$this->db->insert("sku_bosnet");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	return 1;	}
		else					{	return 0;	}
	}
	
	public function Insertsku_bosnet_temp( $batu_id, $sku_id, $sku_induk_id, $bosnet_id )
	{
		$this->db->set("batu_id", $batu_id );
		$this->db->set("sku_bosnet_id", "NEWID()", FALSE);
		$this->db->set("sku_id", $sku_id );
		$this->db->set("sku_induk_id", $sku_induk_id );
		$this->db->set("bosnet_id", $bosnet_id );
		
		$this->db->insert("sku_bosnet_temp");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	return 1;	}
		else					{	return 0;	}
	}
	
	public function Updatesku_non_active_by_sku_induk_id( $sku_induk_id )
	{
		$this->db->set("sku_is_aktif", 1 );
		$this->db->set("sku_is_jual", 0 );
		
		$this->db->where("sku_induk_id", $sku_induk_id );
		
		$this->db->update("sku");
		
		$affectedrows = $this->db->affected_rows();
		
		if($affectedrows > 0)	{	return 1;	}
		else					{	return 0;	}
	}
	
	public function Updatesku(	$sku_id, $sku_kode, $sku_nama_produk, $sku_deskripsi, 
								$sku_kondisi, $sku_origin, $sku_harga_jual, $sku_sales_min_qty,
								$sku_pph, $sku_ppnbm_persen, $sku_ppn_persen, $sku_is_aktif, $sku_is_jual, 
								$sku_weight_netto, $sku_weight_netto_unit, $sku_weight_product,	$sku_weight_product_unit,
								$sku_weight_packaging, $sku_weight_packaging_unit,	$sku_weight_gift, $sku_weight_gift_unit,
								$sku_length, $sku_length_unit, $sku_width, $sku_width_unit, 
								$sku_height, $sku_height_unit, $sku_volume, $sku_volume_unit,
								$kategori1_id, $kategori2_id, $kategori3_id, $kategori4_id, $principle_id, $principle_brand_id )
	{
		$this->db->set("sku_kode", $sku_kode ); 
		$this->db->set("sku_nama_produk", $sku_nama_produk );
		$this->db->set("sku_deskripsi", $sku_deskripsi );
		$this->db->set("sku_kondisi", $sku_kondisi );
		$this->db->set("sku_origin", $sku_origin );
		$this->db->set("sku_harga_jual", $sku_harga_jual );
		$this->db->set("sku_sales_min_qty", $sku_sales_min_qty );
		$this->db->set("sku_ppn", $sku_ppn );
		$this->db->set("sku_ppnbm_persen", $sku_ppnbm_persen );
		$this->db->set("sku_ppn_persen", $sku_ppn_persen );
		$this->db->set("sku_is_aktif", $sku_is_aktif );
		$this->db->set("sku_is_jual", $sku_is_jual );
		$this->db->set("sku_weight_netto", $sku_weight_netto );
		$this->db->set("sku_weight_netto_unit", $sku_weight_netto_unit );
		$this->db->set("sku_weight_product", $sku_weight_product );
		$this->db->set("sku_weight_product_unit", $sku_weight_product_unit );
		$this->db->set("sku_weight_packaging", $sku_weight_packaging );
		$this->db->set("sku_weight_packaging_unit", $sku_weight_packaging_unit );
		$this->db->set("sku_weight_gift", $sku_weight_gift );
		$this->db->set("sku_weight_gift_unit", $sku_weight_gift_unit );
		$this->db->set("sku_length", $sku_length );
		$this->db->set("sku_length_unit", $sku_length_unit );
		$this->db->set("sku_width", $sku_width );
		$this->db->set("sku_width_unit", $sku_width_unit );
		$this->db->set("sku_height", $sku_height );
		$this->db->set("sku_height_unit", $sku_height_unit );
		$this->db->set("sku_volume", $sku_volume );
		$this->db->set("sku_volume_unit", $sku_volume_unit );
		$this->db->set("kategori1_id", $kategori1_id );
		$this->db->set("kategori2_id", $kategori2_id );
		$this->db->set("kategori3_id", $kategori3_id );
		$this->db->set("kategori4_id", $kategori4_id );
		$this->db->set("principle_id", $principle_id );
		$this->db->set("principle_brand_id", $principle_brand_id );
		
		$this->db->where("sku_id", $sku_id ); 
		
		$this->db->update("sku");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	return 1;	}
		else					{	return 0;	}
	}	
	
	public function Updatesku_with_bosnet(	$sku_id, $sku_kode, $sku_nama_produk, $sku_deskripsi, 
											$sku_kondisi, $sku_origin, $sku_harga_jual, $sku_sales_min_qty,
											$sku_is_aktif, $sku_is_jual, 
											$sku_weight_netto, $sku_weight_netto_unit, $sku_weight_product,	$sku_weight_product_unit,
											$sku_weight_packaging, $sku_weight_packaging_unit,	$sku_weight_gift, $sku_weight_gift_unit,
											$sku_length, $sku_length_unit, $sku_width, $sku_width_unit, 
											$sku_height, $sku_height_unit, $sku_volume, $sku_volume_unit,
											$kategori1_id, $kategori2_id, $kategori3_id, $kategori4_id, $principle_id, $principle_brand_id )
	{
		$this->db->set("sku_kode", $sku_kode ); 
		$this->db->set("sku_nama_produk", $sku_nama_produk );
		$this->db->set("sku_deskripsi", $sku_deskripsi );
		$this->db->set("sku_kondisi", $sku_kondisi );
		$this->db->set("sku_origin", $sku_origin );
		$this->db->set("sku_harga_jual", $sku_harga_jual );
		$this->db->set("sku_sales_min_qty", $sku_sales_min_qty );
		$this->db->set("sku_is_aktif", $sku_is_aktif );
		$this->db->set("sku_is_jual", $sku_is_jual );
		$this->db->set("sku_weight_netto", $sku_weight_netto );
		$this->db->set("sku_weight_netto_unit", $sku_weight_netto_unit );
		$this->db->set("sku_weight_product", $sku_weight_product );
		$this->db->set("sku_weight_product_unit", $sku_weight_product_unit );
		$this->db->set("sku_weight_packaging", $sku_weight_packaging );
		$this->db->set("sku_weight_packaging_unit", $sku_weight_packaging_unit );
		$this->db->set("sku_weight_gift", $sku_weight_gift );
		$this->db->set("sku_weight_gift_unit", $sku_weight_gift_unit );
		$this->db->set("sku_length", $sku_length );
		$this->db->set("sku_length_unit", $sku_length_unit );
		$this->db->set("sku_width", $sku_width );
		$this->db->set("sku_width_unit", $sku_width_unit );
		$this->db->set("sku_height", $sku_height );
		$this->db->set("sku_height_unit", $sku_height_unit );
		$this->db->set("sku_volume", $sku_volume );
		$this->db->set("sku_volume_unit", $sku_volume_unit );
		$this->db->set("kategori1_id", $kategori1_id );
		$this->db->set("kategori2_id", $kategori2_id );
		$this->db->set("kategori3_id", $kategori3_id );
		$this->db->set("kategori4_id", $kategori4_id );
		$this->db->set("principle_id", $principle_id );
		$this->db->set("principle_brand_id", $principle_brand_id );
		
		$this->db->where("sku_id", $sku_id ); 
		
		$this->db->update("sku");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	return 1;	}
		else					{	return 0;	}
	}	
	
	public function Updatesku_with_bosnet_hadiah(	$sku_id, $sku_kode, $sku_nama_produk, $sku_deskripsi, $sku_kemasan, $sku_satuan,
													$sku_kondisi, $sku_origin, $sku_harga_jual, $sku_sales_min_qty,
													//$sku_pph, $sku_ppnbm_persen, $sku_ppn_persen, 
													$sku_is_aktif, $sku_is_jual, 
													$sku_weight_netto, $sku_weight_netto_unit, $sku_weight_product,	$sku_weight_product_unit,
													$sku_weight_packaging, $sku_weight_packaging_unit,	$sku_weight_gift, $sku_weight_gift_unit,
													$sku_length, $sku_length_unit, $sku_width, $sku_width_unit, 
													$sku_height, $sku_height_unit, $sku_volume, $sku_volume_unit,
													$kategori1_id, $kategori2_id, $kategori3_id, $kategori4_id, $principle_id, $principle_brand_id )
	{
		$this->db->set("sku_kode", $sku_kode ); 
		$this->db->set("sku_nama_produk", $sku_nama_produk );
		$this->db->set("sku_deskripsi", $sku_deskripsi );
		$this->db->set("sku_kemasan", $sku_kemasan );
		$this->db->set("sku_satuan", $sku_satuan );
		$this->db->set("sku_kondisi", $sku_kondisi );
		$this->db->set("sku_origin", $sku_origin );
		$this->db->set("sku_harga_jual", $sku_harga_jual );
		$this->db->set("sku_sales_min_qty", $sku_sales_min_qty );
		//$this->db->set("sku_ppn", $sku_ppn );
		//$this->db->set("sku_ppnbm_persen", $sku_ppnbm_persen );
		//$this->db->set("sku_ppn_persen", $sku_ppn_persen );
		$this->db->set("sku_is_aktif", $sku_is_aktif );
		$this->db->set("sku_is_jual", $sku_is_jual );
		$this->db->set("sku_weight_netto", $sku_weight_netto );
		$this->db->set("sku_weight_netto_unit", $sku_weight_netto_unit );
		$this->db->set("sku_weight_product", $sku_weight_product );
		$this->db->set("sku_weight_product_unit", $sku_weight_product_unit );
		$this->db->set("sku_weight_packaging", $sku_weight_packaging );
		$this->db->set("sku_weight_packaging_unit", $sku_weight_packaging_unit );
		$this->db->set("sku_weight_gift", $sku_weight_gift );
		$this->db->set("sku_weight_gift_unit", $sku_weight_gift_unit );
		$this->db->set("sku_length", $sku_length );
		$this->db->set("sku_length_unit", $sku_length_unit );
		$this->db->set("sku_width", $sku_width );
		$this->db->set("sku_width_unit", $sku_width_unit );
		$this->db->set("sku_height", $sku_height );
		$this->db->set("sku_height_unit", $sku_height_unit );
		$this->db->set("sku_volume", $sku_volume );
		$this->db->set("sku_volume_unit", $sku_volume_unit );
		$this->db->set("kategori1_id", $kategori1_id );
		$this->db->set("kategori2_id", $kategori2_id );
		$this->db->set("kategori3_id", $kategori3_id );
		$this->db->set("kategori4_id", $kategori4_id );
		$this->db->set("principle_id", $principle_id );
		$this->db->set("principle_brand_id", $principle_brand_id );
		
		$this->db->where("sku_id", $sku_id ); 
		
		$this->db->update("sku");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	return 1;	}
		else					{	return 0;	}
	}	
	
	public function Deletesku($sku_id)
	{
		$this->db->where("sku_id", $sku_id);
		
		$this->db->delete("sku");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$querydelete = 1;	}
		else					{	$querydelete = 0;	}
	
		return $querydelete;
	}
	
	public function Deletesku_bosnet_by_sku_induk_id( $sku_induk_id )
	{
		$this->db->where("sku_induk_id", $sku_induk_id);
		
		$this->db->delete("sku_bosnet");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$querydelete = 1;	}
		else					{	$querydelete = 0;	}
	
		return $querydelete;
	}

	public function countAll()
    {
		$this->db->where('sku_is_deleted', '0');
		$this->db->where('sku_is_aktif', '1');

        return $this->db->count_all_results($this->table_name);
    }

	public function findOneByPK($pkValue)
	{
		$this->db->where($this->primary_key, $pkValue);

		$objects = $this->db->get($this->table_name);
		$results = $objects->result();
		return $results[0];
	}

	public function getAttributeLabels($label)
	{
		$labels = [
			'sku_induk_id' => 'SKU Induk',
			'sku_nama_produk' => 'SKU',
			'sku_kemasan' => 'Kemasan',
			'sku_satuan' => 'Satuan',
			'principle_id' => 'Principle',
			'principle_brand_id' => 'Brand',
			'sku_kode' => 'Kode SKU'
		];

		return isset($labels[$label]) ? $labels[$label] : '';
	}

	/*
	 * Mendapatkan Principle ID berdasarkan Karyawan ID
	 */
	private function getPrinciplesByEmployeeId()
	{
		$this->load->model("HakAkses/M_Pengguna","M_Pengguna");
		$pengguna = $this->M_Pengguna->findOneByPK($this->session->userdata("pengguna_id"));
		
		$this->load->model("M_KaryawanPrinciple");
		$karyawanPrinciples = $this->M_KaryawanPrinciple->findManyByCriteria(['karyawan_id' => $pengguna->karyawan_id]);

		$arrOfPrinciples = [];
		foreach($karyawanPrinciples as $karyawanPrinciple) {
			$arrOfPrinciples[] = $karyawanPrinciple->principle_id;
		}

		return $arrOfPrinciples;
	}

	/*
	 * Fungsi ini untuk mendapatkan principle setelah fungsi getPrinciplesByEmployeeId dijalankan
	 * Params:
	 * $id - ID dari client_wms_id
	 * $principles - array principle yang didapat dari fungsi getPrinciplesByEmployeeId
	 * 
	 * return array of principle IDs
	 */
	private function getPrinciplesByClientWmsId($id, $principles)
	{
		$arrOfPrinciples = [];

		$this->load->model("M_ClientWmsPrinciple");
		$criteria[] = [
			'colName' => 'client_wms_id',
			'colValue' => $id,
			'type' => 'specific'
		];
		if (!empty($principles)) {
			$criteria[] = [
				'colName' => 'principle_id',
				'colValue' => $principles,
				'type' => 'in'
			];
		}
		$wmsPrinciples = $this->M_ClientWmsPrinciple->findManyByCriteria($criteria);

		foreach($wmsPrinciples as $wmsPrinciple) {
			$arrOfPrinciples[] = $wmsPrinciple->principle_id;
		}

		return $arrOfPrinciples;
	}

	public function getPrinciplesByClientPtPaymentType($clientPtId, $paymentType, $principles)
	{
		$arrOfPrinciples = [];

		$this->load->model("M_ClientPtPrinciple");

		$criteria[] = [
				'colName' => 'client_pt_id',
				'colValue' => $clientPtId,
				'type' => 'specific'
		];
		if (!empty($principles)) {
			$criteria[] = [
				'colName' => 'principle_id',
				'colValue' => $principles,
				'type' => 'in'
			];
		}

		if ($paymentType == $this->M_DeliveryOrderDraft::PAYMENT_TYPE_NON_COD) {
			$criteria[] = [
				'colName' => 'client_pt_principle_is_kredit',
				'colValue' => '1',
				'type' => 'specific'
			];
		}

		$ptPrinciples = $this->M_ClientPtPrinciple->findManyByCriteria($criteria);

		foreach($ptPrinciples as $ptPrinciple) {
			$arrOfPrinciples[] = $ptPrinciple->principle_id;
		}

		return $arrOfPrinciples;
	}

	/*
	 * search data to be used spesifically for datatable
	 * 
	 * Params:
	 * $criteria - array of criteria for filtering. It's key is the column name.
	 * $limit - int
	 * $offset - int
	 * $orders - array of orders, column key is the column name and dir key is the direction asc or desc
	 * $asLookup - false is for list page while true is for lookup modal
	 * 
	 * return array of result for datatable
	 */
    public function search($criteria = array(), $limit = '', $offset = '', $orders = array(), $asLookup = false)
	{
		$arrOfPrinciples = $this->getPrinciplesByEmployeeId();

		if (isset($_POST['client_wms_id']) && !empty($_POST['client_wms_id'])) {
			$arrOfPrinciples = $this->getPrinciplesByClientWmsId($_POST['client_wms_id'], $arrOfPrinciples);
		}

		if (isset($_POST['client_pt_id']) && isset($_POST['tipe_pembayaran']) && !empty($_POST['client_pt_id']) && !empty($_POST['tipe_pembayaran'])) {
			$arrOfPrinciples = $this->getPrinciplesByClientPtPaymentType($_POST['client_pt_id'], $_POST['tipe_pembayaran'], $arrOfPrinciples);
		}

		if (empty($arrOfPrinciples)) {
			return [];
		}

		$records = array();

		$this->db->join('sku_induk', 'sku_induk.sku_induk_id = '.$this->table_name.'.sku_induk_id', 'left');
		$this->db->join('principle', 'principle.principle_id = '.$this->table_name.'.principle_id', 'left');
		$this->db->join('principle_brand', 'principle_brand.principle_brand_id = '.$this->table_name.'.principle_brand_id', 'left');

        //limit and offset
		if ($limit != "" && $limit != "-1") {
			$this->db->limit($limit);
			if ($offset != "") {
				$this->db->limit($limit, $offset);
			}
		}

        //filters
        foreach($this->cols as $col) {
            if (isset($criteria[$col['name']])) {
                if ($col['filterType'] == 'similar' && $criteria[$col['name']] != "") {
                    $this->db->like($col['name'], $criteria[$col['name']]);
                } else if ($col['filterType'] == 'specific' && $criteria[$col['name']] != "") {
                    $this->db->where($col['name'], $criteria[$col['name']]);
                } else if ($col['filterType'] == 'range' && $criteria[$col['name']][$col['rangeField'][0]] != "" && $criteria[$col['name']][$col['rangeField'][1]] != "") {
                    $this->db->where($col['name'].' BETWEEN \''.$criteria[$col['name']][$col['rangeField'][0]].'\' AND \''.$criteria[$col['name']][$col['rangeField'][1]].'\'');
                }
            }
        }

		$this->db->where_in('sku.principle_id', $arrOfPrinciples);

        //sorting
        if (!empty($orders)) {
            foreach($orders as $order) {
                $this->db->order_by($this->cols[$order['column']]['name'], $order['dir']);
            }
        }

        $objects = $this->db->get($this->table_name);

        $records = [];
        if ($objects != false) {
			foreach($objects->result() as $i => $object) {
				$row = [];
				if ($asLookup) {
					$row[] = $object->sku_id;
				}
				$row[] = !empty($object->sku_induk_nama) ? $object->sku_induk_nama : '';
				$row[] = !empty($object->sku_nama_produk) ? $object->sku_nama_produk : '';
				$row[] = !empty($object->sku_kemasan) ? $object->sku_kemasan : '';
				$row[] = !empty($object->sku_satuan) ? $object->sku_satuan : '';
				$row[] = !empty($object->principle_kode) ? $object->principle_kode : '';
				$row[] = !empty($object->principle_brand_nama) ? $object->principle_brand_nama : '';
				if (!$asLookup) {
					$row[] = '<div style="white-space: nowrap">'
						.getViewButton('DeliveryOrderDraft', 'view', $object->sku_id)
						.getUpdateButton('DeliveryOrderDraft', 'update', $object->sku_id)
						.'</div>';
				} else {
					$row[] = getLookupPickButton('btn-choose-sku', ['id' => $object->sku_id]);
				}

				$records[] = $row;
			}
		}

		return $records;
	}
}
