<?php

class M_Menu_Node extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
		
	/* API dari lokasi URL lain */
	
	public function Getmenu_by_menu_parent( $menu_parent )
	{
		$query = $this->db->query("	select menu_id, menu_kode, menu_link, bahasa_nama_". $this->session->userdata('Bahasa') ." as menu_name, 
										menu_class, menu_parent, menu_order,	
										menu_c, menu_r, menu_u, menu_d, tipe, menu_application, menu_is_detail
									from menu_web as m
										inner join bahasa as b on b.bahasa_kode = 'CAPTION-'+ m.menu_kode
									where menu_parent = '$menu_parent' order by menu_kode");
									
		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return $query->result_array();	}
	}
	
	public function Getmenu_not_in_menu_diagram_by_menu_kode( $menu_kode, $arrMenuKode )
	{
		$query = $this->db->query("	select bahasa_nama_". $this->session->userdata('Bahasa') ." as menu_node_menu_name, menu_kode  
									from menu_web as m
										inner join bahasa as b on b.bahasa_kode = 'CAPTION-'+ menu_kode
									where menu_parent = '$menu_kode' and 
										menu_kode not in (	select mn.menu_node_menu_kode 
															from menu_diagram as md
																inner join menu_node as mn on mn.menu_diagram_id = md.menu_diagram_id 	
															where md.menu_diagram_menu_kode_root = '$menu_kode' ) and
										menu_kode not in ( ". $arrMenuKode . " )
									order by menu_kode
									");
									
		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return $query->result_array();	}
	}
	
	public function Getmenu_by_menu_kode( $menu_kode )
	{
		$query = $this->db->query("	select b.bahasa_nama_". $this->session->userdata('Bahasa') ." as menu_name									from menu_web as m
										inner join bahasa as b on b.bahasa_kode = 'CAPTION-'+ m.menu_kode
									where menu_kode = '$menu_kode'");
									
		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return $query->result_array();	}
	}
	
	public function Getmenu_diagram_by_aplikasi_kode_menu_kode( $aplikasi_kode, $menu_kode )
	{
		$query = $this->db->query("	select menu_diagram_id, menu_diagram_menu_kode_root, menu_diagram_position, aplikasi_kode 
									from menu_diagram
									where aplikasi_kode = '$aplikasi_kode' and menu_diagram_menu_kode_root = '$menu_kode'");
									
		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return $query->result_array();	}
	}
	
	public function Getmenu_node_by_menu_diagram_id( $menu_diagram_id )	
	{
		$query = $this->db->query("	select menu_node_id, menu_diagram_id, 
										case when menu_node_custom_id = '-' then bahasa_nama_". $this->session->userdata('Bahasa') ." else menu_node_menu_name end as menu_node_menu_name, 
										menu_node_menu_kode, menu_node_location, isnull( menu_node_icon, '' ) as menu_node_icon, menu_node_shape, isnull(menu_node_size,'default') as menu_node_size, menu_node_custom_id,
										menu_node_background_color, menu_node_font_color
									from menu_node as mn	
										left join menu_web as m on m.menu_kode = mn.menu_node_menu_kode
										left join bahasa as b on b.bahasa_kode = 'CAPTION-'+ m.menu_kode
									where menu_diagram_id = '$menu_diagram_id' order by menu_node_menu_kode");
									
		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return $query->result_array();	}
	}
	
	public function Getmenu_node_detail_by_menu_diagram_id( $menu_diagram_id )
	{
		$query = $this->db->query("	select 	md.menu_node_detail_id, md.menu_node_id, m.menu_node_menu_kode, mw.menu_name,
											isnull( md.menu_node_detail_keterangan_1, '') as menu_node_detail_keterangan_1, isnull( md.menu_node_detail_link_1, '') as menu_node_detail_link_1, isnull( md.menu_node_detail_image_path_1, '') as menu_node_detail_image_path_1, 
											isnull( md.menu_node_detail_keterangan_2, '') as menu_node_detail_keterangan_2, isnull( md.menu_node_detail_link_2, '') as menu_node_detail_link_2, isnull( md.menu_node_detail_image_path_2, '') as menu_node_detail_image_path_2, 
											isnull( md.menu_node_detail_keterangan_3, '') as menu_node_detail_keterangan_3, isnull( md.menu_node_detail_link_3, '') as menu_node_detail_link_3, isnull( md.menu_node_detail_image_path_3, '') as menu_node_detail_image_path_3
									from menu_node as m
										inner join menu_node_detail as md on md.menu_node_id = m.menu_node_id
										inner join menu_web as mw on mw.menu_kode = m.menu_node_menu_kode
									where menu_diagram_id = '$menu_diagram_id' order by menu_node_menu_kode");
									
		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return $query->result_array();	}
	}
	
	public function Getmenu_node_detail_by_menu_kode( $menu_kode )
	{
		$query = $this->db->query("	select 	md.menu_node_detail_id, md.menu_node_id, m.menu_node_menu_kode, mw.menu_name, mw.menu_link,
											isnull( md.menu_node_detail_keterangan_1, '') as menu_node_detail_keterangan_1, isnull( md.menu_node_detail_link_1, '') as menu_node_detail_link_1, isnull( md.menu_node_detail_image_path_1, '') as menu_node_detail_image_path_1, 
											isnull( md.menu_node_detail_keterangan_2, '') as menu_node_detail_keterangan_2, isnull( md.menu_node_detail_link_2, '') as menu_node_detail_link_2, isnull( md.menu_node_detail_image_path_2, '') as menu_node_detail_image_path_2, 
											isnull( md.menu_node_detail_keterangan_3, '') as menu_node_detail_keterangan_3, isnull( md.menu_node_detail_link_3, '') as menu_node_detail_link_3, isnull( md.menu_node_detail_image_path_3, '') as menu_node_detail_image_path_3
									from menu_node as m
										inner join menu_node_detail as md on md.menu_node_id = m.menu_node_id
										inner join menu_web as mw on mw.menu_kode = m.menu_node_menu_kode
									where menu_node_menu_kode = '$menu_kode' order by menu_node_menu_kode");
									
		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return $query->result_array();	}
	}
	
	public function Getmenu_node_detail_menu_link_by_menu_kode( $menu_kode )
	{
		$query = $this->db->query("	select menu_link
									from menu_web
									where menu_kode = '$menu_kode'");
									
		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return $query->result_array();	}
	}
	
	public function Getmenu_link_by_menu_diagram_id( $menu_diagram_id )	
	{
		$query = $this->db->query("	select menu_link_id, menu_diagram_id, menu_link_menu_kode_from, menu_link_menu_kode_to
									from menu_link
									where menu_diagram_id = '$menu_diagram_id' order by menu_link_id");
									
		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return $query->result_array();	}
	}
	
	public function Getmenu_link_detail_by_menu_diagram_id( $menu_diagram_id )
	{
		$query = $this->db->query("	select ml.menu_diagram_id, mld.menu_link_detail_id, mld.menu_link_id, mld.menu_link_detail_point, mld.menu_link_detail_urut, maxlink
									from menu_link as ml 
										inner join menu_link_detail as mld on mld.menu_link_id = ml.menu_link_id
										inner join (select max(menu_link_detail_urut) as maxlink, ml.menu_link_id 
													from menu_link as ml 
														inner join menu_link_detail as mld on mld.menu_link_id = ml.menu_link_id 
													where ml.menu_diagram_id = '$menu_diagram_id' 
													group by ml.menu_link_id ) as link on link.menu_link_id = ml.menu_link_id
									where ml.menu_diagram_id = '$menu_diagram_id' 
									order by mld.menu_link_id, mld.menu_link_detail_urut");
									
		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return $query->result_array();	}
	}
		
	public function Insertmenu_diagram( $menu_diagram_id, $aplikasi_kode, $menu_diagram_menu_kode_root, $menu_diagram_position )
	{
		$this->db->set('menu_diagram_id', $menu_diagram_id );
		$this->db->set('aplikasi_kode', $aplikasi_kode );
		$this->db->set('menu_diagram_menu_kode_root', $menu_diagram_menu_kode_root );
		$this->db->set('menu_diagram_position', $menu_diagram_position );
		
		$this->db->insert('menu_diagram');
		
		$affectedrows = $this->db->affected_rows();
		
		if( $affectedrows == 0 ){	return 0;	}
		else					{	return 1;	}
	}
	
	public function Insertmenu_node(	$menu_node_id, $menu_diagram_id, $menu_node_menu_kode, $menu_node_menu_name, 
										$menu_node_location, $menu_node_icon, $menu_node_size, $menu_node_shape, $menu_node_custom_id,
										$menu_node_background_color, $menu_node_font_color )
	{
		$this->db->set('menu_node_id', $menu_node_id );
		$this->db->set('menu_diagram_id', $menu_diagram_id );
		$this->db->set('menu_node_menu_kode', $menu_node_menu_kode );
		$this->db->set('menu_node_menu_name', $menu_node_menu_name );
		$this->db->set('menu_node_location', $menu_node_location );
		$this->db->set('menu_node_icon', $menu_node_icon );
		$this->db->set('menu_node_size', $menu_node_size );
		$this->db->set('menu_node_shape', $menu_node_shape );
		$this->db->set('menu_node_custom_id', $menu_node_custom_id );
		$this->db->set('menu_node_background_color', $menu_node_background_color );
		$this->db->set('menu_node_font_color', $menu_node_font_color );
		
		$this->db->insert('menu_node');
		
		$affectedrows = $this->db->affected_rows();
		
		if( $affectedrows == 0 ){	return 0;	}
		else					{	return 1;	}
	}
	
	public function Insertmenu_node_detail( 	$menu_node_detail_id, $menu_node_id, 
												$menu_node_detail_keterangan_1, $menu_node_detail_link_1,
												$menu_node_detail_keterangan_2, $menu_node_detail_link_2,
												$menu_node_detail_keterangan_3, $menu_node_detail_link_3 )
	{
		$this->db->set('menu_node_detail_id', "NEWID()", FALSE );
		$this->db->set('menu_node_id', $menu_node_id );
		$this->db->set('menu_node_detail_keterangan_1', $menu_node_detail_keterangan_1 );
		$this->db->set('menu_node_detail_link_1', $menu_node_detail_link_1 );
		$this->db->set('menu_node_detail_keterangan_2', $menu_node_detail_keterangan_2 );
		$this->db->set('menu_node_detail_link_2', $menu_node_detail_link_2 );
		$this->db->set('menu_node_detail_keterangan_3', $menu_node_detail_keterangan_3 );
		$this->db->set('menu_node_detail_link_3', $menu_node_detail_link_3 );
		
		$this->db->insert('menu_node_detail');
		
		$affectedrows = $this->db->affected_rows();
		
		if( $affectedrows == 0 ){	return 0;	}
		else					{	return 1;	}
	}
	
	public function Insertmenu_link( $menu_link_id, $menu_diagram_id, $menu_link_menu_kode_from, $menu_link_menu_kode_to )
	{
		$this->db->set('menu_link_id', $menu_link_id );
		$this->db->set('menu_diagram_id', $menu_diagram_id );
		$this->db->set('menu_link_menu_kode_from', $menu_link_menu_kode_from );
		$this->db->set('menu_link_menu_kode_to', $menu_link_menu_kode_to );
		
		$this->db->insert('menu_link');
		
		$affectedrows = $this->db->affected_rows();
		
		if( $affectedrows == 0 ){	return 0;	}
		else					{	return 1;	}
	}
	
	public function Insertmenu_link_detail( $menu_link_detail_id, $menu_link_id, $menu_link_detail_point, $menu_link_detail_urut )
	{
		$this->db->set('menu_link_detail_id', $menu_link_detail_id );
		$this->db->set('menu_link_id', $menu_link_id );
		$this->db->set('menu_link_detail_point', $menu_link_detail_point );
		$this->db->set('menu_link_detail_urut', $menu_link_detail_urut );
		
		$this->db->insert('menu_link_detail');
		
		$affectedrows = $this->db->affected_rows();
		
		if( $affectedrows == 0 ){	return 0;	}
		else					{	return 1;	}
	}
	
	public function Deletemenu_link_detail_by_menu_diagram_id( $menu_diagram_id )
	{
		$query = $this->db->query("	delete mld 
									from menu_link as ml 
										inner join menu_link_detail as mld on mld.menu_link_id = ml.menu_link_id 
									where ml.menu_diagram_id = '$menu_diagram_id'");
		return 1;
	}
	
	public function Deletemenu_link_by_menu_diagram_id( $menu_diagram_id )
	{
		$query = $this->db->query("	delete from menu_link where menu_diagram_id = '$menu_diagram_id'");
		
		return 1;
	}
	
	public function Deletemenu_node_detail_by_menu_diagram_id( $menu_diagram_id )
	{
		$query = $this->db->query("	delete mnd 
									from menu_node as mn 
										inner join menu_node_detail as mnd on mnd.menu_node_id = mn.menu_node_id 
									where mn.menu_diagram_id = '$menu_diagram_id'");
		return 1;
	}
	
	public function Deletemenu_node_by_menu_diagram_id( $menu_diagram_id )
	{
		$query = $this->db->query("	delete from menu_node where menu_diagram_id = '$menu_diagram_id'");
		
		return 1;
	}
	
	public function Deletemenu_diagram_by_menu_diagram_id( $menu_diagram_id )
	{
		$query = $this->db->query("	delete from menu_diagram where menu_diagram_id = '$menu_diagram_id'");
		
		return 1;
	}
	
}