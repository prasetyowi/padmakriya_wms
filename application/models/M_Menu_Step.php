<?php

class M_Menu_Step extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
		
	/* API dari lokasi URL lain */
	public function Get_Menu_Step_By_Menu_Step_Menu_Kode_Parent( $menu_step_menu_kode_header )
	{
		$bahasa = $this->session->userdata('Bahasa');
		
		$query = $this->db->query("	select 
										ms.menu_step_menu_kode_header, ms.menu_step_id, ms.menu_step_menu_kode_parent, ms.menu_step_menu_kode_child,
										ms.menu_step_urut, ms.menu_step_icon, b.bahasa_nama_". $bahasa ." as menu_name, m.menu_kode
									from menu_step_web as ms
										inner join menu_web as m on m.menu_kode = ms.menu_step_menu_kode_child and m.menu_r = 1
										inner join bahasa as b on b.bahasa_kode = 'CAPTION-'+ m.menu_kode
									where menu_step_menu_kode_header = '$menu_step_menu_kode_header'
									order by menu_step_idx");
		
		if( $query->num_rows() == 0 )	{ $query = 0; }	
		else							{ $query = $query->result_array(); }	
		
		return $query;	
	}
	
	public function Get_Menu_Step_Detail_By_Menu_Step_Menu_Kode_Parent( $menu_step_menu_kode_header )
	{
		$query = $this->db->query("	select 
										ms.menu_step_menu_kode_header, ms.menu_step_id, ms.menu_step_menu_kode_parent, ms.menu_step_menu_kode_child,
										msd.menu_step_detail_keterangan_1, msd.menu_step_detail_link_1, 
										msd.menu_step_detail_keterangan_2, msd.menu_step_detail_link_2, 
										msd.menu_step_detail_keterangan_3, msd.menu_step_detail_link_3 
									from menu_step_web as ms
										inner join menu_web as m on m.menu_kode = ms.menu_step_menu_kode_child and m.menu_r = 1
										left join menu_step_detail_web as msd on msd.menu_step_id = ms.menu_step_id
									where menu_step_menu_kode_header = '$menu_step_menu_kode_header'
									order by menu_step_idx");
		
		if( $query->num_rows() == 0 )	{ $query = 0; }	
		else							{ $query = $query->result_array(); }	
		
		return $query;	
	}
	
	public function Get_Menu_Step_Detail_By_Menu_Child( $menu_step_menu_kode_child )
	{
		$bahasa = $this->session->userdata('Bahasa');
		
		$query = $this->db->query("	select 
										ms.menu_step_menu_kode_header, ms.menu_step_id, ms.menu_step_menu_kode_parent, ms.menu_step_menu_kode_child,
										b.bahasa_nama_". $bahasa ." as menu_name, menu_link,
										isnull( msd.menu_step_detail_keterangan_1,'') as menu_step_detail_keterangan_1, 
										isnull( msd.menu_step_detail_link_1,'') as menu_step_detail_link_1, 
										isnull( msd.menu_step_detail_keterangan_2,'') as menu_step_detail_keterangan_2,  
										isnull( msd.menu_step_detail_link_2,'') as menu_step_detail_link_2, 
										isnull( msd.menu_step_detail_keterangan_3,'') as menu_step_detail_keterangan_3,  
										isnull( msd.menu_step_detail_link_3,'') as menu_step_detail_link_3 
									from menu_step_web as ms
										inner join menu_web as m on m.menu_kode = ms.menu_step_menu_kode_child and m.menu_r = 1
										left join menu_step_detail_web as msd on msd.menu_step_id = ms.menu_step_id
										inner join bahasa as b on b.bahasa_kode = 'CAPTION-'+ m.menu_kode
									where menu_step_menu_kode_child = '$menu_step_menu_kode_child'
									order by menu_step_idx");
		
		if( $query->num_rows() == 0 )	{ $query = 0; }	
		else							{ $query = $query->result_array(); }	
		
		return $query;	
	}
	
	public function Insertmenu_to_sort( $sortid, $parent, $child, $name, $level, $menu_step_id, $menu_step_icon )
	{
		$this->db->set('menu_sort_id', $sortid );
		$this->db->set('menu_sort_parent', $parent );
		$this->db->set('menu_sort_child', $child );
		$this->db->set('menu_sort_name', $name );
		$this->db->set('menu_sort_level', $level );
		$this->db->set('menu_step_id', $menu_step_id );
		$this->db->set('menu_step_icon', $menu_step_icon );
		
		$this->db->insert('menu_sort_temp');
		
		$affectedrows = $this->db->affected_rows();
		
		if( $affectedrows == 0 ){	return 0;	}
		else					{	return 1;	}
	}
	
	public function Insertmenu_step_web( 	$menu_step_id, $menu_step_menu_kode_header, $menu_step_menu_kode_parent, $menu_step_menu_kode_child, $menu_step_urut, 
											$menu_step_idx, $menu_step_header_id, $menu_step_icon )
	{
		$this->db->set('menu_step_id', $menu_step_id );
		$this->db->set('menu_step_menu_kode_parent', $menu_step_menu_kode_parent );
		$this->db->set('menu_step_menu_kode_child', $menu_step_menu_kode_child );
		$this->db->set('menu_step_urut', $menu_step_urut );
		$this->db->set('menu_step_idx', $menu_step_idx );
		$this->db->set('menu_step_header_id', $menu_step_header_id );
		$this->db->set('menu_step_menu_kode_header', $menu_step_menu_kode_header );
		$this->db->set('menu_step_icon', $menu_step_icon );
		
		$this->db->insert('menu_step_web');
		
		$affectedrows = $this->db->affected_rows();
		
		if( $affectedrows == 0 ){	return 0;	}
		else					{	return 1;	}
	}
	
	public function Updatemenu_step_web( 	$menu_step_id, $menu_step_menu_kode_header, $menu_step_menu_kode_parent, $menu_step_menu_kode_child, $menu_step_urut, 
											$menu_step_idx, $menu_step_header_id, $menu_step_icon )
	{
		$this->db->set('menu_step_id', $menu_step_id );
		$this->db->set('menu_step_menu_kode_parent', $menu_step_menu_kode_parent );
		$this->db->set('menu_step_urut', $menu_step_urut );
		$this->db->set('menu_step_idx', $menu_step_idx );
		$this->db->set('menu_step_menu_kode_header', $menu_step_menu_kode_header );
		$this->db->set('menu_step_icon', $menu_step_icon );
		
		$this->db->where('menu_step_menu_kode_child', $menu_step_menu_kode_child );
		$this->db->where('menu_step_header_id', $menu_step_header_id );
		
		$this->db->update('menu_step_web');
		
		$affectedrows = $this->db->affected_rows();
		
		if( $affectedrows == 0 ){	return 0;	}
		else					{	return 1;	}
	}
	
	public function Insertmenu_step_detail_web( $menu_step_id, $menu_step_header_id,
												$menu_step_detail_keterangan_1, $menu_step_detail_link_1, 
												$menu_step_detail_keterangan_2, $menu_step_detail_link_2, 
												$menu_step_detail_keterangan_3, $menu_step_detail_link_3 )
	{
		$this->db->set('menu_step_detail_id', "NEWID()", FALSE );
		$this->db->set('menu_step_id', $menu_step_id );
		$this->db->set('menu_step_header_id', $menu_step_header_id );
		$this->db->set('menu_step_detail_keterangan_1', $menu_step_detail_keterangan_1 );
		$this->db->set('menu_step_detail_link_1', $menu_step_detail_link_1 );
		$this->db->set('menu_step_detail_keterangan_2', $menu_step_detail_keterangan_2 );
		$this->db->set('menu_step_detail_link_2', $menu_step_detail_link_2 );
		$this->db->set('menu_step_detail_keterangan_3', $menu_step_detail_keterangan_3 );
		$this->db->set('menu_step_detail_link_3', $menu_step_detail_link_3 );
		
		$this->db->insert('menu_step_detail_web');
		
		$affectedrows = $this->db->affected_rows();
		
		if( $affectedrows == 0 ){	return 0;	}
		else					{	return 1;	}
	}
	
	public function Deletemenu_step_detail_web( $menu_step_header_id )
	{
		$this->db->where('menu_step_header_id', $menu_step_header_id );
		
		$this->db->delete('menu_step_detail_web');
		
		return 1;
	}
	
	public function Exec_Sort_Menu( $sortid )
	{
		$query = $this->db->query("exec MenuSort '$sortid'");
		
		return $query->result_array();
		
	}
	
}