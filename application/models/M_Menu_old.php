<?php

class M_Menu extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
		
	/* API dari lokasi URL lain */
	public function Getmenu_and_sub()
	{
		$query = $this->db->query("	select menu_id, menu_kode, menu_link, menu_name, menu_class, menu_parent, menu_c, menu_r, menu_u, menu_d, tipe, menu_application
									from menu_web as m
									order by menu_kode");
		
		if( $query->num_rows() == 0 )	{ $query = 0; }	
		else							{ $query = $query->result_array(); }	
		
		return $query;	
	}
		
	public function GetMenu_Menu_Kode_By_Menu_Link( $menu_link, $menu_application )
	{
		$query = $this->db->query("	select m.menu_kode
									from menu_web as m
									where menu_link = '$menu_link' and menu_application = '$menu_application'");
		
		if( $query->num_rows() == 0 )	{ $query = 0; }	
		else							{ $query = $query->result_array(); }	
		
		return $query;	
	}
	
	public function GetMenu_Menu_Kode_By_Menu_Link_Only( $menu_link )
	{
		$query = $this->db->query("	select m.menu_kode
									from menu_web as m
									where menu_link = '$menu_link'");
		
		if( $query->num_rows() == 0 )	{ $query = 0; }	
		else							{ $query = $query->result_array(); }	
		
		return $query;	
	}
	
	public function GetMenu_By_Menu_Link( $menu_link, $menu_application, $pengguna_grup_id )
	{
		$query = $this->db->query("	select menu_id, m.menu_kode, menu_link, menu_name, menu_class, menu_parent, menu_order, 
										menu_c,	menu_r, menu_u, menu_d, tipe, menu_application, 
										status_c, status_r, status_u, status_d
									from menu_web as m
										inner join menu_access_web as ma on ma.menu_kode = m.menu_kode
									where menu_link = '$menu_link' and menu_application = '$menu_application'
										and ma.pengguna_grup_id = '$pengguna_grup_id'
									
									");
		
		if( $query->num_rows() == 0 )	{ $query = 0; }	
		else							{ $query = $query->result_array(); }	
		
		return $query;	
	}
	
	
	/* END OF API dari lokasi URL lain */
	
	public function Get_Menu_Status_By_menu_parent_TOP_1( $pengguna_grup_id )
	{
		$query = $this->db->query("	select TOP 1 m.menu_kode as Kd, ma.status_r, m.menu_link, m.menu_kode, m.menu_parent, m.menu_order, menu_application
									from menu_web as m
										inner join menu_access_web as ma on ma.menu_kode = m.menu_kode
									where ma.pengguna_grup_id = '$pengguna_grup_id'
										and isnull(m.menu_parent,'') = ''
										and ma.status_r = 1
									order by menu_kode " );
		
		if( $query->num_rows() == 0 )	{ $query = 0; }	
		else							{ $query = $query->result_array(); }	
		
		return $query;	
	}
	
	public function Get_Menu_Utility()
	{
		$query = $this->db->query("	select menu_kode, menu_c, menu_r, menu_u, menu_d, menu_name, menu_link, menu_parent, menu_order, menu_application
									from menu_web
									where menu_parent in ('030000000','030003000') and menu_kode not in ('030003000')
									order by menu_kode" );
		
		if( $query->num_rows() == 0 )	{ $query = 0; }	
		else							{ $query = $query->result_array(); }	
		
		return $query;	
	}
	
	public function Get_Menu_Status_By_menu_parent( $pengguna_grup_id, $menu_parent )
	{
		$this->db	->select("ma.status_r, m.menu_link, m.menu_kode, m.menu_parent, m.menu_order, menu_application")
					->from("menu_web as m")
					->join("menu_access_web as ma","ma.menu_kode = m.menu_kode","inner")
					->where("ma.pengguna_grup_id", $pengguna_grup_id) 
					->where("isnull(m.menu_parent,'')", $menu_parent)
					->where("ma.status_r", 1)
					->order_by("menu_kode", "ASC");
		$query = $this->db->get();
		
		if( $query->num_rows() == 0 )	{ $query = 0; }	
		else							{ $query = $query->result_array(); }	
		
		return $query;	
	}
	
	public function Get_Menu_Status_By_menu_kode( $pengguna_grup_id, $menu_kode )
	{
		$this->db	->select("ma.status_r, m.menu_link, m.menu_kode, m.menu_parent, m.menu_order, menu_application")
					->from("menu_web as m")
					->join("menu_access_web as ma","ma.menu_kode = m.menu_kode","inner")
					->where("ma.pengguna_grup_id", $pengguna_grup_id) 
					->where("m.menu_kode", $menu_kode)
					->where("ma.status_r", 1)
					->order_by("menu_kode", "ASC");
		$query = $this->db->get();
		
		if( $query->num_rows() == 0 )	{ $query = 0; }	
		else							{ $query = $query->result_array(); }	
		
		return $query;	
	}
	
	public function CheckMenu($pengguna_grup_id, $menu_kode, $MenuAccess){
		
		$query = $this->db->query("	select status_r, status_c, status_u, status_d
									from menu_access_web
									where pengguna_grup_id = '$pengguna_grup_id'
										and menu_kode = '$menu_kode'" );
								
		$resultMenu = $query->row_array();
		if($resultMenu){
			
			if($MenuAccess == ""){
				return true;
			}
			else{
				if(isset($resultMenu["status_".strtolower($MenuAccess)])){
					if($resultMenu["status_".strtolower($MenuAccess)] == 1){
						return true;
					}
					else{
						return false;
					}
				}
				else{
					return false;
				}
			}
		}
		else{
			return false;
		}
	}
	
	// punya ko henry
	public function Getmenu_access_web($pengguna_grup_id, $menu_kode){
		$Mode = $this->session->userdata('Mode');
		
		$query = $this->db->query("	select status_r as R, status_c as C, status_u as U, status_d as D
									from menu_access_web
									where pengguna_grup_id = '$pengguna_grup_id' 
										and menu_kode = '$menu_kode'");
								
		return $query->row_array();	
	}
	
	public function GetMenu($currentpage,$pengguna_grup_id,$menuparent="",$awalan=""){
		$menu = '';
		$query = $this->db->query("	select a.* 
									from menu_web a 
										inner join menu_access_web b on a.menu_kode = b.menu_kode and b.pengguna_grup_id = '$pengguna_grup_id' 
												and a.menu_link <> '' and b.status_r='1' and tipe = 0
									where isnull(a.menu_parent,'') = '$menuparent' 
									order by a.menu_kode");
								
		$resultMenu = $query->result_array();
		$n = $query->num_rows();
		if($n > 0)
		{
			if($menuparent == "")
			{
				
				$menu .= "<ul class=\"nav side-menu\">";
			}
			else
			{
				$menu .= "<ul class=\"nav child_menu\" ".(substr($currentpage,0,strlen($awalan)) == $awalan ?' style="display:block;" ':'').">";
			}
		}


		foreach($resultMenu as $rowMenu)
		{
			$menuname = strtolower(str_replace(" ","",$rowMenu["menu_name"]));
			$menuid = $rowMenu["menu_kode"];
			$childexist = false;
			
			$query = $this->db->query("select a.* from menu_web a 
									inner join menu_access_web b on a.menu_kode=b.menu_kode 
									and b.pengguna_grup_id = '$pengguna_grup_id' 
									and b.status_r='1' 
									and a.menu_link <> '' 
									where isnull(a.menu_parent,'') = '$menuid' and tipe = 0
									order by a.menu_kode");
			$resultMenuChild = $query->result_array();
			$nchild = $query->num_rows();
			

			if($menuparent == "")
			{
				
				if($nchild == 0)
				{
					if($rowMenu["menu_link"] != "#sidebar-menu"){
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".base_url($rowMenu["menu_link"])."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
					else{
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".$rowMenu["menu_link"]."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
					
				}
				else
				{
					$menu .= "<li ".(substr($currentpage,0,strlen($awalan.$menuname)) == $awalan.$menuname ?' class="active" ':'')."><a><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." <span class=\"fa fa-chevron-down\"></span></a>";
					
				}
		
			}
			else
			{
				if($nchild == 0)
				{					
					if($rowMenu["menu_link"] != "#sidebar-menu"){
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".base_url($rowMenu["menu_link"])."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
					else{						
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".$rowMenu["menu_link"]."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
				}
				else
				{
					$menu .= "<li ".(substr($currentpage,0,strlen($awalan.$menuname)) == $awalan.$menuname?' class="active" ':'')."><a><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." <span class=\"fa fa-chevron-down\"></span></a>";
				
				}
			}
			$menu .= $this->GetMenu($currentpage,$pengguna_grup_id,$menuid,$awalan.$menuname."_");
			$menu .= "</li>";
		}
		if($n > 0)
		{
			if($menuparent == ""){
				
				$menu .= '<li><a href="'.base_url().'"><i class="fa fa-arrow-left"></i> Main Menu </a>';

				$menu .= '<li><a href="'.base_url('MainPage/Logout').'"><i class="fa fa-sign-out"></i> Logout</a></li>';
			}
			$menu .= "</ul>";
		}		
		return $menu;
	}

	public function GetMenu_Sidebar($currentpage,$pengguna_grup_id,$menuparent="",$awalan=""){
		$menu = '';
		$query = $this->db->query("	select a.* 
									from menu_web a 
										inner join menu_access_web b on a.menu_kode = b.menu_kode and b.pengguna_grup_id = '$pengguna_grup_id' 
												and a.menu_link <> '' and b.status_r='1' 
									where isnull(a.menu_parent,'') = '$menuparent' and tipe = 0
										and menu_application = '". $this->session->userdata('Mode') ."'
									order by a.menu_kode");
								
		$resultMenu = $query->result_array();
		$n = $query->num_rows();
		if($n > 0)
		{
			if($menuparent == "")
			{
				
				$menu .= "<ul class=\"nav side-menu\">";
			}
			else
			{
				$menu .= "<ul class=\"nav child_menu\" ".(substr($currentpage,0,strlen($awalan)) == $awalan ?' style="display:block;" ':'').">";
			}
		}


		foreach($resultMenu as $rowMenu)
		{
			$menuname = strtolower(str_replace(" ","",$rowMenu["menu_name"]));
			$menuid = $rowMenu["menu_kode"];
			$childexist = false;
			
			$query = $this->db->query("select a.* from menu_web a 
									inner join menu_access_web b on a.menu_kode = b.menu_kode 
									and b.pengguna_grup_id = '$pengguna_grup_id' 
									and b.status_r = '1' 
									and a.menu_link <> '' 
									where isnull(a.menu_parent,'') = '$menuid'
										and menu_application = '". $this->session->userdata('Mode') ."'
									order by a.menu_kode");
			$resultMenuChild = $query->result_array();
			$nchild = $query->num_rows();
			

			if($menuparent == "")
			{
				
				if($nchild == 0)
				{
					if($rowMenu["menu_link"] != "#sidebar-menu"){
						$menu .= "<li ".( $currentpage == $awalan.$menuname?' class="current-page" ':'' )."><a href=\"".base_url($rowMenu["menu_link"])."\"><i class=\"".$rowMenu["menu_class"]."\"></i><label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> </a>";
					}
					else{
						$menu .= "<li ".( $currentpage == $awalan.$menuname?' class="current-page" ':'' )."><a href=\"".$rowMenu["menu_link"]."\"><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> </a>";
					}
					
				}
				else
				{
					$menu .= "<li ".(substr($currentpage,0,strlen($awalan.$menuname)) == $awalan.$menuname ?' class="active" ':'')."><a><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> <span class=\"fa fa-chevron-down\"></span></a>";
					
				}
		
			}
			else
			{
				if($nchild == 0)
				{					
					if($rowMenu["menu_link"] != "#sidebar-menu"){
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".base_url($rowMenu["menu_link"])."\"><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> </a>";
					}
					else{						
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".$rowMenu["menu_link"]."\"><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> </a>";
					}
				}
				else
				{
					$menu .= "<li ".(substr($currentpage,0,strlen($awalan.$menuname)) == $awalan.$menuname?' class="active" ':'')."><a)'><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> <span class=\"fa fa-chevron-down\"></span></a>";
				
				}
			}
			$menu .= $this->GetMenu_Sidebar($currentpage,$pengguna_grup_id,$menuid,$awalan.$menuname."_");
			$menu .= "</li>";
		}
		if($n > 0)
		{
			if($menuparent == ""){
				
				//$menu .= '<li><a href="'.base_url().'"><i class="fa fa-arrow-left"></i> Main Menu </a>';

				$menu .= '<li><a href="'.base_url('MainPage/Logout').'"><i class="fa fa-sign-out"></i> Logout</a></li>';
			}
			$menu .= "</ul>";
		}		
		return $menu;
	}
	
	public function GetMenu_Sidebar_GlobalSetup($currentpage,$pengguna_grup_id,$menuparent="",$awalan=""){
		$menu = '';
		$query = $this->db->query("	select a.* 
									from menu_web a 
										inner join menu_access_web b on a.menu_kode = b.menu_kode and b.pengguna_grup_id = '$pengguna_grup_id' 
												and a.menu_link <> '' and b.status_r='1' 
									where isnull(a.menu_parent,'') = '$menuparent' and tipe = 0
										and menu_application = 'MASTER'
									order by a.menu_kode");
								
		$resultMenu = $query->result_array();
		$n = $query->num_rows();
		if($n > 0)
		{
			if($menuparent == "")
			{
				
				$menu .= "<ul class=\"nav side-menu\">";
			}
			else
			{
				$menu .= "<ul class=\"nav child_menu\" ".(substr($currentpage,0,strlen($awalan)) == $awalan ?' style="display:block;" ':'').">";
			}
		}


		foreach($resultMenu as $rowMenu)
		{
			$menuname = strtolower(str_replace(" ","",$rowMenu["menu_name"]));
			$menuid = $rowMenu["menu_kode"];
			$childexist = false;
			
			$query = $this->db->query("select a.* from menu_web a 
									inner join menu_access_web b on a.menu_kode = b.menu_kode 
									and b.pengguna_grup_id = '$pengguna_grup_id' 
									and b.status_r = '1' 
									and a.menu_link <> '' 
									where isnull(a.menu_parent,'') = '$menuid'
										and menu_application = 'MASTER'
									order by a.menu_kode");
			$resultMenuChild = $query->result_array();
			$nchild = $query->num_rows();
			

			if($menuparent == "")
			{
				
				if($nchild == 0)
				{
					if($rowMenu["menu_link"] != "#sidebar-menu"){
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".base_url($rowMenu["menu_link"])."\"><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> </a>";
					}
					else{
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".$rowMenu["menu_link"]."\"><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> </a>";
					}
					
				}
				else
				{
					$menu .= "<li ".(substr($currentpage,0,strlen($awalan.$menuname)) == $awalan.$menuname ?' class="active" ':'')."><a><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> <span class=\"fa fa-chevron-down\"></span></a>";
					
				}
		
			}
			else
			{
				if($nchild == 0)
				{					
					if($rowMenu["menu_link"] != "#sidebar-menu"){
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".base_url($rowMenu["menu_link"])."\"><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> </a>";
					}
					else{						
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".$rowMenu["menu_link"]."\"><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> </a>";
					}
				}
				else
				{
					$menu .= "<li ".(substr($currentpage,0,strlen($awalan.$menuname)) == $awalan.$menuname?' class="active" ':'')."><a><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> <span class=\"fa fa-chevron-down\"></span></a>";
				
				}
			}
			$menu .= $this->GetMenu_Sidebar_GlobalSetup($currentpage,$pengguna_grup_id,$menuid,$awalan.$menuname."_");
			$menu .= "</li>";
		}
		if($n > 0)
		{
			if($menuparent == ""){
				
				//$menu .= '<li><a href="'.base_url().'"><i class="fa fa-arrow-left"></i> Main Menu </a>';

				$menu .= '<li><a href="'.base_url('MainPage/Logout').'"><i class="fa fa-sign-out"></i> Logout</a></li>';
			}
			$menu .= "</ul>";
		}		
		return $menu;
	}
	
	public function GetMenu_Depo($currentpage,$pengguna_grup_id,$menuparent="",$awalan=""){
		$menu = '';
		$query = $this->db->query("	select a.* 
									from menu_web a 
										inner join menu_access_web b on a.menu_kode = b.menu_kode and b.pengguna_grup_id = '$pengguna_grup_id' 
												and a.menu_link <> '' and b.status_r='1' 
									where isnull(a.menu_parent,'') = '$menuparent' and tipe = 1
										and menu_application = '". $this->session->userdata('Mode') ."'
									order by a.menu_kode");
								
		$resultMenu = $query->result_array();
		$n = $query->num_rows();
		if($n > 0)
		{
			if($menuparent == "")
			{
				
				$menu .= "<ul class=\"nav side-menu\">";
			}
			else
			{
				$menu .= "<ul class=\"nav child_menu\" ".(substr($currentpage,0,strlen($awalan)) == $awalan ?' style="display:block;" ':'').">";
			}
		}


		foreach($resultMenu as $rowMenu)
		{
			$menuname = strtolower(str_replace(" ","",$rowMenu["menu_name"]));
			$menuid = $rowMenu["menu_kode"];
			$childexist = false;
			
			$query = $this->db->query("select a.* from menu_web a 
									inner join menu_access_web b on a.menu_kode = b.menu_kode 
									and b.pengguna_grup_id = '$pengguna_grup_id' 
									and b.status_r = '1' 
									and a.menu_link <> '' 
									where isnull(a.menu_parent,'') = '$menuid'
										and menu_application = '". $this->session->userdata('Mode') ."'
									order by a.menu_kode");
			$resultMenuChild = $query->result_array();
			$nchild = $query->num_rows();
			

			if($menuparent == "")
			{
				
				if($nchild == 0)
				{
					if($rowMenu["menu_link"] != "#sidebar-menu"){
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".base_url($rowMenu["menu_link"])."\"><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> </a>";
					}
					else{
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".$rowMenu["menu_link"]."\"><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> </a>";
					}
					
				}
				else
				{
					$menu .= "<li ".(substr($currentpage,0,strlen($awalan.$menuname)) == $awalan.$menuname ?' class="active" ':'')."><a><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> <span class=\"fa fa-chevron-down\"></span></a>";
					
				}
		
			}
			else
			{
				if($nchild == 0)
				{					
					if($rowMenu["menu_link"] != "#sidebar-menu"){
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".base_url($rowMenu["menu_link"])."\"><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> </a>";
					}
					else{						
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".$rowMenu["menu_link"]."\"><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> </a>";
					}
				}
				else
				{
					$menu .= "<li ".(substr($currentpage,0,strlen($awalan.$menuname)) == $awalan.$menuname?' class="active" ':'')."><a><i class=\"".$rowMenu["menu_class"]."\"></i> <label name='CAPTION-". $rowMenu["menu_kode"] ."'>".$rowMenu["menu_name"]."</label> <span class=\"fa fa-chevron-down\"></span></a>";
				
				}
			}
			$menu .= $this->GetMenu_Depo($currentpage,$pengguna_grup_id,$menuid,$awalan.$menuname."_");
			$menu .= "</li>";
		}
		if($n > 0)
		{
			if($menuparent == ""){
				
				//$menu .= '<li><a href="'.base_url().'"><i class="fa fa-arrow-left"></i> Main Menu </a>';

				$menu .= '<li><a href="'.base_url('MainPage/Logout').'"><i class="fa fa-sign-out"></i> Logout</a></li>';
			}
			$menu .= "</ul>";
		}		
		return $menu;
	}
	
	public function GetMenu_Sidebar_Administrator($currentpage,$menuparent="",$awalan=""){
		$menu = '';
		$query = $this->db->query("	select a.* 
									from menu_web a 
									where isnull(a.menu_parent,'') = '$menuparent' and tipe = 0 and menu_link <> ''
										and menu_application = '". $this->session->userdata('Mode') ."'
									order by a.menu_kode");
								
		$resultMenu = $query->result_array();
		$n = $query->num_rows();
		if($n > 0)
		{
			if($menuparent == "")
			{
				
				$menu .= "<ul class=\"nav side-menu\">";
			}
			else
			{
				$menu .= "<ul class=\"nav child_menu\" ".(substr($currentpage,0,strlen($awalan)) == $awalan ?' style="display:block;" ':'').">";
			}
		}


		foreach($resultMenu as $rowMenu)
		{
			$menuname = strtolower(str_replace(" ","",$rowMenu["menu_name"]));
			$menuid = $rowMenu["menu_kode"];
			$childexist = false;
			
			$query = $this->db->query("	select a.* from menu_web a 
										where isnull(a.menu_parent,'') = '$menuid' and a.menu_link <> '' 
											and menu_application = '". $this->session->userdata('Mode') ."'
										order by a.menu_kode");
			$resultMenuChild = $query->result_array();
			$nchild = $query->num_rows();
			

			if($menuparent == "")
			{
				
				if($nchild == 0)
				{
					if($rowMenu["menu_link"] != "#sidebar-menu"){
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".base_url($rowMenu["menu_link"])."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
					else{
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".$rowMenu["menu_link"]."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
					
				}
				else
				{
					$menu .= "<li ".(substr($currentpage,0,strlen($awalan.$menuname)) == $awalan.$menuname ?' class="active" ':'')."><a><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." <span class=\"fa fa-chevron-down\"></span></a>";
					
				}
		
			}
			else
			{
				if($nchild == 0)
				{					
					if($rowMenu["menu_link"] != "#sidebar-menu"){
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".base_url($rowMenu["menu_link"])."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
					else{						
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".$rowMenu["menu_link"]."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
				}
				else
				{
					$menu .= "<li ".(substr($currentpage,0,strlen($awalan.$menuname)) == $awalan.$menuname?' class="active" ':'')."><a><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." <span class=\"fa fa-chevron-down\"></span></a>";
				
				}
			}
			$menu .= $this->GetMenu_Sidebar_Administrator($currentpage,$menuid,$awalan.$menuname."_");
			$menu .= "</li>";
		}
		if($n > 0)
		{
			if($menuparent == ""){
				
				//$menu .= '<li><a href="'.base_url().'"><i class="fa fa-arrow-left"></i> Main Menu </a>';

				$menu .= '<li><a href="'.base_url('MainPage/Logout').'"><i class="fa fa-sign-out"></i> Logout</a></li>';
			}
			$menu .= "</ul>";
		}		
		return $menu;
	}
	
	public function GetMenu_Depo_Administrator($currentpage,$menuparent="",$awalan=""){
		$menu = '';
		$query = $this->db->query("	select a.* 
									from menu_web a 
									where isnull(a.menu_parent,'') = '$menuparent' and tipe = 1 and menu_link <> ''
										and menu_application = '". $this->session->userdata('Mode') ."'
									order by a.menu_kode");
								
		$resultMenu = $query->result_array();
		$n = $query->num_rows();
		if($n > 0)
		{
			if($menuparent == "")
			{
				
				$menu .= "<ul class=\"nav side-menu\">";
			}
			else
			{
				$menu .= "<ul class=\"nav child_menu\" ".(substr($currentpage,0,strlen($awalan)) == $awalan ?' style="display:block;" ':'').">";
			}
		}


		foreach($resultMenu as $rowMenu)
		{
			$menuname = strtolower(str_replace(" ","",$rowMenu["menu_name"]));
			$menuid = $rowMenu["menu_kode"];
			$childexist = false;
			
			$query = $this->db->query("	select a.* from menu_web a 
										where isnull(a.menu_parent,'') = '$menuid' and a.menu_link <> '' 
											and menu_application = '". $this->session->userdata('Mode') ."'
										order by a.menu_kode");
			$resultMenuChild = $query->result_array();
			$nchild = $query->num_rows();
			

			if($menuparent == "")
			{
				
				if($nchild == 0)
				{
					if($rowMenu["menu_link"] != "#sidebar-menu"){
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".base_url($rowMenu["menu_link"])."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
					else{
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".$rowMenu["menu_link"]."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
					
				}
				else
				{
					$menu .= "<li ".(substr($currentpage,0,strlen($awalan.$menuname)) == $awalan.$menuname ?' class="active" ':'')."><a><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." <span class=\"fa fa-chevron-down\"></span></a>";
					
				}
		
			}
			else
			{
				if($nchild == 0)
				{					
					if($rowMenu["menu_link"] != "#sidebar-menu"){
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".base_url($rowMenu["menu_link"])."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
					else{						
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".$rowMenu["menu_link"]."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
				}
				else
				{
					$menu .= "<li ".(substr($currentpage,0,strlen($awalan.$menuname)) == $awalan.$menuname?' class="active" ':'')."><a><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." <span class=\"fa fa-chevron-down\"></span></a>";
				
				}
			}
			$menu .= $this->GetMenu_Depo_Administrator($currentpage,$menuid,$awalan.$menuname."_");
			$menu .= "</li>";
		}
		if($n > 0)
		{
			if($menuparent == ""){
				
				//$menu .= '<li><a href="'.base_url().'"><i class="fa fa-arrow-left"></i> Main Menu </a>';

				$menu .= '<li><a href="'.base_url('MainPage/Logout').'"><i class="fa fa-sign-out"></i> Logout</a></li>';
			}
			$menu .= "</ul>";
		}		
		return $menu;
	}
	
	public function GetMenu_Master($currentpage,$pengguna_grup_id,$menuparent="",$awalan=""){
		$menu = '';
		$query = $this->db->query("	select a.* 
									from menu_web a 
										inner join menu_access_web b on a.menu_kode = b.menu_kode and b.pengguna_grup_id = '$pengguna_grup_id' 
												and a.menu_link <> '' and b.status_r='1' 
									where isnull(a.menu_parent,'') = '$menuparent' and tipe = 2
										and menu_application = '". $this->session->userdata('Mode') ."'
									order by a.menu_kode");
								
		$resultMenu = $query->result_array();
		$n = $query->num_rows();
		if($n > 0)
		{
			if($menuparent == "")
			{
				
				$menu .= "<ul class=\"nav side-menu\">";
			}
			else
			{
				$menu .= "<ul class=\"nav child_menu\" ".(substr($currentpage,0,strlen($awalan)) == $awalan ?' style="display:block;" ':'').">";
			}
		}


		foreach($resultMenu as $rowMenu)
		{
			$menuname = strtolower(str_replace(" ","",$rowMenu["menu_name"]));
			$menuid = $rowMenu["menu_kode"];
			$childexist = false;
			
			$query = $this->db->query("select a.* from menu_web a 
									inner join menu_access_web b on a.menu_kode = b.menu_kode 
									and b.pengguna_grup_id = '$pengguna_grup_id' 
									and b.status_r = '1' 
									and a.menu_link <> '' 
									where isnull(a.menu_parent,'') = '$menuid'
										and menu_application = '". $this->session->userdata('Mode') ."'
									order by a.menu_kode");
			$resultMenuChild = $query->result_array();
			$nchild = $query->num_rows();
			

			if($menuparent == "")
			{
				
				if($nchild == 0)
				{
					if($rowMenu["menu_link"] != "#sidebar-menu"){
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".base_url($rowMenu["menu_link"])."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
					else{
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".$rowMenu["menu_link"]."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
					
				}
				else
				{
					$menu .= "<li ".(substr($currentpage,0,strlen($awalan.$menuname)) == $awalan.$menuname ?' class="active" ':'')."><a><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." <span class=\"fa fa-chevron-down\"></span></a>";
					
				}
		
			}
			else
			{
				if($nchild == 0)
				{					
					if($rowMenu["menu_link"] != "#sidebar-menu"){
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".base_url($rowMenu["menu_link"])."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
					else{						
						$menu .= "<li ".($currentpage == $awalan.$menuname?' class="current-page" ':'')."><a href=\"".$rowMenu["menu_link"]."\"><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." </a>";
					}
				}
				else
				{
					$menu .= "<li ".(substr($currentpage,0,strlen($awalan.$menuname)) == $awalan.$menuname?' class="active" ':'')."><a><i class=\"".$rowMenu["menu_class"]."\"></i> ".$rowMenu["menu_name"]." <span class=\"fa fa-chevron-down\"></span></a>";
				
				}
			}
			$menu .= $this->GetMenu_Master($currentpage,$pengguna_grup_id,$menuid,$awalan.$menuname."_");
			$menu .= "</li>";
		}
		if($n > 0)
		{
			if($menuparent == ""){
				
				//$menu .= '<li><a href="'.base_url().'"><i class="fa fa-arrow-left"></i> Main Menu </a>';

				$menu .= '<li><a href="'.base_url('MainPage/Logout').'"><i class="fa fa-sign-out"></i> Logout</a></li>';
			}
			$menu .= "</ul>";
		}		
		return $menu;
	}
	
	public function Check_If_Use_Master( $pengguna_grup_id )
	{
		$query = $this->db->query( "select menu_id
									from menu_web as m
										inner join menu_access_web as ma on ma.menu_kode = m.menu_kode
									where Tipe = 2 and ma.pengguna_grup_id = '$pengguna_grup_id'" );

		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return 1;	}
	}
	
	public function Check_If_Use_Master_Administrator()
	{
		$query = $this->db->query( "select menu_id
									from menu_web as m
										inner join menu_access_web as ma on ma.menu_kode = m.menu_kode
									where Tipe = 2" );

		if( $query->num_rows() == 0 )	{	return 0;	}
		else							{	return 1;	}
	}
	
	public function Exec_Proc_GetMenuByMenuKode( $menu_kode )
	{
		$query = $this->db->query("	exec GetMenuByMenuKode '$menu_kode' ");
		
		if( $query->num_rows() == 0 )	{ $query = 0; }	
		else							{ $query = $query->result_array(); }	
		
		return $query;	
	}
		
}