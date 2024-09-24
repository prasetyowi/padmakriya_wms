<script type="text/javascript">
	var IsDetailClcik = 0;
	
	function ShowStep( menu_kode )
	{
		$(".right_col").removeAttr('style');
		$(".right_col").attr('style','display: none;');
		
		$("#menudiagram").removeAttr('style');
		var aplikasi_kode = '<?= $this->session->userdata('Mode'); ?>';
		
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('HakAkses/MenuAndSub/GetMenuChildByMenuKode') ?>",
			data: 
			{
				menu_kode: menu_kode,
				aplikasi_kode: aplikasi_kode
			},
			success: function( response )
			{
				if(response)
				{
					ChMenuChild( response );
				}
			}
		});
	}
	
	function ChMenuChild( JSONMenuApplication )
	{
		var MenuApplication = JSON.parse( JSONMenuApplication );
		
		header_id = ''; 
		
		arrNode = [];
		
		$("#diagramtitle").html( MenuApplication.MenuName[0].menu_name );
		
		var iscreated = 0;
		
		if( MenuApplication.MenuDiagram != 0 )
		{
			iscreated = 1;
			
			aplikasi_kode				= MenuApplication.MenuDiagram[0].aplikasi_kode;
			menu_diagram_id				= MenuApplication.MenuDiagram[0].menu_diagram_id;
			menu_diagram_menu_kode_root	= MenuApplication.MenuDiagram[0].menu_diagram_menu_kode_root;
			menu_diagram_position		= MenuApplication.MenuDiagram[0].menu_diagram_position;
  
			var str = '';
				str += '{ 	"class": "GraphLinksModel", ';
				str += ' 	"linkFromPortIdProperty": "fromPort", ';
				str += '	"linkToPortIdProperty": "toPort", ';
				str += '	"modelData": {"position":"'+ menu_diagram_position +'"}, ';
				
			var strnodedataarray = '';
			if( MenuApplication.MenuNode != 0 )
			{
				for( i=0 ; i<MenuApplication.MenuNode.length ; i++ )
				{
					menu_diagram_id			= MenuApplication.MenuNode[i].menu_diagram_id;
					menu_node_icon			= MenuApplication.MenuNode[i].menu_node_icon;
					menu_node_menu_name		= MenuApplication.MenuNode[i].menu_node_menu_name;
					menu_node_id			= MenuApplication.MenuNode[i].menu_node_id;
					menu_node_location		= MenuApplication.MenuNode[i].menu_node_location;
					menu_node_menu_kode		= MenuApplication.MenuNode[i].menu_node_menu_kode;
					menu_link				= MenuApplication.MenuNode[i].menu_link;
					
					menu_node_size			= MenuApplication.MenuNode[i].menu_node_size;
					
					size = '';
					if( menu_node_size != 'default' && menu_node_size != '' )
					{
						size = ', "size": "'+ menu_node_size +'"';
					}
					menu_node_shape			= MenuApplication.MenuNode[i].menu_node_shape;
					menu_node_custom_id		= MenuApplication.MenuNode[i].menu_node_custom_id;
					
					menu_node_background_color	= MenuApplication.MenuNode[i].menu_node_background_color;
					menu_node_font_color		= MenuApplication.MenuNode[i].menu_node_font_color;
					
					var visible = menu_node_custom_id == '-' ? true : false;
					var stroke = menu_node_custom_id != '-' ? 1 : 0;
					var key = menu_node_menu_kode;
					
					strnodedataarray += '{	"text":"'+ menu_node_menu_name +'", "id": "'+ menu_node_id +'", "key": "'+ key +'", "loc": "'+ menu_node_location +'"'+ size +', "figure": "'+ menu_node_shape +'", "custom_id": "'+ menu_node_custom_id +'"';
					strnodedataarray += '	,"visible": '+ visible +', "strokeWidth": '+ stroke +', "fill": "'+ menu_node_background_color +'", "text_color":"'+ menu_node_font_color +'"';
					
					if( menu_node_icon != '' )
					{
						strnodedataarray += ', "iconname": "'+ menu_node_icon +'", "source": "<?= Get_Assets_Url(); ?>Global/icomoon/PNG/16px/'+ menu_node_icon +'"}';
					}
					else
					{
						strnodedataarray += '}';
					}
					
					if( i < MenuApplication.MenuNode.length - 1 )
					{
						strnodedataarray += ',';
					}
				}
			}
			
			str += '	"nodeDataArray": ['+ strnodedataarray +'],';
			
			arrMenuKet = [];
			
			if( MenuApplication.MenuNodeDetail != 0 )
			{
				for( i=0 ; i<MenuApplication.MenuNodeDetail.length ; i++ )
				{
					menu_node_detail_id				= MenuApplication.MenuNodeDetail[i].menu_node_detail_id;
					menu_node_id					= MenuApplication.MenuNodeDetail[i].menu_node_id;
					menu_node_menu_kode				= MenuApplication.MenuNodeDetail[i].menu_node_menu_kode;
					
					menu_node_detail_keterangan_1	= MenuApplication.MenuNodeDetail[i].menu_node_detail_keterangan_1;
					menu_node_detail_link_1			= MenuApplication.MenuNodeDetail[i].menu_node_detail_link_1;
					menu_node_detail_image_path_1	= MenuApplication.MenuNodeDetail[i].menu_node_detail_image_path_1;
					
					menu_node_detail_keterangan_2	= MenuApplication.MenuNodeDetail[i].menu_node_detail_keterangan_2;
					menu_node_detail_link_2			= MenuApplication.MenuNodeDetail[i].menu_node_detail_link_2;
					menu_node_detail_image_path_2 	= MenuApplication.MenuNodeDetail[i].menu_node_detail_image_path_2;
					
					menu_node_detail_keterangan_3	= MenuApplication.MenuNodeDetail[i].menu_node_detail_keterangan_3;
					menu_node_detail_link_3			= MenuApplication.MenuNodeDetail[i].menu_node_detail_link_3;
					menu_node_detail_image_path_3	= MenuApplication.MenuNodeDetail[i].menu_node_detail_image_path_3;
					
					arrMenuKet.push
					({ 	
						menu_node_detail_id: menu_node_detail_id, menu_node_id: menu_node_id, menu_node_menu_kode: menu_node_menu_kode,
						menu_node_detail_keterangan_1: menu_node_detail_keterangan_1, menu_node_detail_link_1: menu_node_detail_link_1, menu_node_detail_image_path_1: menu_node_detail_image_path_1,
						menu_node_detail_keterangan_2: menu_node_detail_keterangan_2, menu_node_detail_link_2: menu_node_detail_link_2, menu_node_detail_image_path_2: menu_node_detail_image_path_2,
						menu_node_detail_keterangan_3: menu_node_detail_keterangan_3, menu_node_detail_link_3: menu_node_detail_link_3, menu_node_detail_image_path_3: menu_node_detail_image_path_3
					});
				}
			}
			
			var strlinkdataarray = '';

			if( MenuApplication.MenuLink != 0 )
			{
				for( i=0 ; i<MenuApplication.MenuLink.length ; i++ )
				{
					menu_diagram_id				= MenuApplication.MenuLink[i].menu_diagram_id;
					menu_link_id				= MenuApplication.MenuLink[i].menu_link_id;
					menu_link_menu_kode_from	= MenuApplication.MenuLink[i].menu_link_menu_kode_from;
					menu_link_menu_kode_to		= MenuApplication.MenuLink[i].menu_link_menu_kode_to;

					strlinkdataarray += '{ 	"from": "'+ menu_link_menu_kode_from +'", ';
					strlinkdataarray += '	"to": "'+ menu_link_menu_kode_to +'", ';
					strlinkdataarray += '	"points": ';
					
					var strpoints = '';
					for( j=0 ; j<MenuApplication.MenuLinkDetail.length ; j++ )
					{
						d_menu_link_detail_id		= MenuApplication.MenuLinkDetail[j].menu_link_detail_id;
						d_menu_link_detail_point	= parseFloat( MenuApplication.MenuLinkDetail[j].menu_link_detail_point ).toFixed(2);
						
						d_menu_link_detail_urut		= MenuApplication.MenuLinkDetail[j].menu_link_detail_urut;
						d_menu_link_id				= MenuApplication.MenuLinkDetail[j].menu_link_id;
						d_maxlink					= MenuApplication.MenuLinkDetail[j].maxlink;
						
						if( menu_link_id == d_menu_link_id )
						{
							strpoints += d_menu_link_detail_point;
							
							if( d_menu_link_detail_urut != d_maxlink )
							{
								strpoints += ',';
							}
						}
					}
					
					strlinkdataarray += '['+ strpoints +']}';
					
					if( i< MenuApplication.MenuLink.length - 1 )
					{
						strlinkdataarray += ',';
					}
				}
			}
			
				str += '	"linkDataArray": ['+ strlinkdataarray +']}';
			
		}
		
		if( MenuApplication.MenuListMenu != 0 && iscreated == 0 )
		{
			for( i=0 ; i < MenuApplication.MenuListMenu.length ; i++ )
			{
				var menu_id				= MenuApplication.MenuListMenu[i].menu_id;
				var menu_kode			= MenuApplication.MenuListMenu[i].menu_kode;
				var menu_link			= MenuApplication.MenuListMenu[i].menu_link;
				var menu_name			= MenuApplication.MenuListMenu[i].menu_name;
				var menu_class			= MenuApplication.MenuListMenu[i].menu_class;
				var menu_parent			= MenuApplication.MenuListMenu[i].menu_parent;
				var menu_c				= MenuApplication.MenuListMenu[i].menu_c;
				var menu_r				= MenuApplication.MenuListMenu[i].menu_r;
				var menu_u				= MenuApplication.MenuListMenu[i].menu_u;
				var menu_d				= MenuApplication.MenuListMenu[i].menu_d;
				var tipe				= MenuApplication.MenuListMenu[i].tipe;
				var menu_application 	= MenuApplication.MenuListMenu[i].menu_application;
				
				arrNode.push( { text: menu_name, id: menu_kode, key: menu_kode, figure: "Rectangle", "custom_id": "-" } ); 
				
				
			}
			
			myDiagram.model = new go.GraphLinksModel(arrNode);
		}
		else if( iscreated == 1 )
		{
			$("#txaSavedModelView").val( str );
			
			load();
		}
		
		
	}
	function NodeDetail( e, obj)
	{
		var node = obj.part;
		var data = node.data;
		
		if( data.custom_id == '-' )
		{
			var menu_kode = data.key;
			var menu_name = data.text;
			
			$.ajax(
			{
				type: 'POST',    
				url: "<?= base_url('HakAkses/MenuAndSub/GetMenuNodeDetailByMenuKode') ?>",
				data: 
				{
					menu_kode: menu_kode
				},
				success: function( response )
				{
					ChNodeDetail( response, menu_kode, menu_name ); 
				}
			});
		}
	}
	
	function ChNodeDetail( JSONMenuNodeDetail, H_menu_kode, H_menu_name )
	{
		var Menu = JSON.parse( JSONMenuNodeDetail );
		
		if( Menu.MenuNodeDetail != 0 )
		{
			var menu_name						= MenuStepDetail.MenuStepDetailMenu[0].menu_name;
			var x_menu_link 					= MenuStepDetail.MenuStepDetailMenu[0].menu_link;
			var menu_node_detail_keterangan_1	= MenuStepDetail.MenuStepDetailMenu[0].menu_node_detail_keterangan_1;
			var menu_node_detail_keterangan_2	= MenuStepDetail.MenuStepDetailMenu[0].menu_node_detail_keterangan_2;
			var menu_node_detail_keterangan_3	= MenuStepDetail.MenuStepDetailMenu[0].menu_node_detail_keterangan_3; 
			var menu_node_detail_link_1			= MenuStepDetail.MenuStepDetailMenu[0].menu_node_detail_link_1;
			var menu_node_detail_link_2			= MenuStepDetail.MenuStepDetailMenu[0].menu_node_detail_link_2;
			var menu_node_detail_link_3			= MenuStepDetail.MenuStepDetailMenu[0].menu_node_detail_link_3;

			var strdetailket = '';
			
			if( menu_node_detail_keterangan_1 == '' && menu_node_detail_keterangan_2 == '' && menu_node_detail_keterangan_3 == '' &&
				menu_node_detail_link_1 == '' && menu_node_detail_link_2 == '' && menu_node_detail_link_3 == '' )
			{	
				strdetailket = C_TIDAKADADETAILINFOLEBIHLANJUT;	
			}
			else
			{
				if( menu_node_detail_keterangan_1 != '' )
				{
					strdetailket += menu_node_detail_keterangan_1;
				}
				if( menu_node_detail_link_1 != '' )
				{
					strdetailket += ' <a href="'+ menu_node_detail_link_1 + '">'+ C_LIHATINFOLEBIHLANJUT +'</a><br />';
				}
				strdetailket += '<br />';
				if( menu_node_detail_keterangan_2 != '' )
				{
					strdetailket += menu_node_detail_keterangan_2;
				}
				if( menu_node_detail_link_2 != '' )
				{
					strdetailket += ' <a href="'+ menu_node_detail_link_2 + '">'+ C_LIHATINFOLEBIHLANJUT +'</a><br />';
				}
				strdetailket += '<br />';
				if( menu_node_detail_keterangan_3 != '' )
				{
					strdetailket += menu_node_detail_keterangan_3;
				}
				if( menu_node_detail_link_3 != '' )
				{
					strdetailket += '<a href="'+ menu_node_detail_link_3 + '">'+ C_LIHATINFOLEBIHLANJUT +'</a><br />';
				}			
			}
			
			strdetailket = strdetailket.replace('<ThisURL>', '<a href="<?= base_url(); ?>'+ x_menu_link +'">');
			strdetailket = strdetailket.replace('</ThisURL>', '</a>');
			
			var strmodal = 	'';
				strmodal += '<div class="modal fade" id="previewshownodedetail" role="dialog" data-keyboard="false" data-backdrop="static">';
				strmodal +=	'	<div class="modal-dialog modal-xlg">';
				strmodal +=	'		<div class="modal-content">';
				strmodal +=	'			<div class="modal-header bg-primary">';
				strmodal +=	'				<h4 class="modal-title">'+ menu_name +'</h4>';
				strmodal +=	'			</div>';
				strmodal +=	'			<div class="modal-body">';
				strmodal +=	'				<div class="row">';
				strmodal +=	'					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 pre-scrollable">';
				strmodal +=							strdetailket;
				strmodal +=	'					</div>';
				strmodal +=	'				</div>';
				strmodal +=	'			</div>';
				strmodal +=	'			<div class="modal-footer">';
				strmodal +=	'				<button type="button" class="btn btn-info" id="btnfaq" onclick="FAQ(\''+ x_kode +'\')"><label name="CAPTION-BUTUHBANTUAN">'+ C_BUTUHBANTUAN +'</label></button>';
				strmodal +=	'				<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnback"><label name="CAPTION-BACK">'+ C_BACK +'</label></button>';
				strmodal +=	'			</div>';
				strmodal +=	'		</div>';
				strmodal +=	'	</div>';
				strmodal +=	'</div>';
				
				$("#modalnode").html('');
			
				$("#modalnode").append( strmodal );
		}
		else
		{
			var strmodal = 	'';
				strmodal += '<div class="modal fade" id="previewshownodedetail" role="dialog" data-keyboard="false" data-backdrop="static">';
				strmodal +=	'	<div class="modal-dialog modal-xlg">';
				strmodal +=	'		<div class="modal-content">';
				strmodal +=	'			<div class="modal-header bg-primary">';
				strmodal +=	'				<h4 class="modal-title">'+ H_menu_name +'</h4>';
				strmodal +=	'			</div>';
				strmodal +=	'			<div class="modal-body">';
				strmodal +=	'				<div class="row">';
				strmodal +=	'					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 pre-scrollable">';
				strmodal +=							C_TIDAKADADETAILINFOLEBIHLANJUT
				strmodal +=	'					</div>';
				strmodal +=	'				</div>';
				strmodal +=	'			</div>';
				strmodal +=	'			<div class="modal-footer">';
				strmodal +=	'				<button type="button" class="btn btn-info" id="btnfaq" onclick="FAQ(\''+ H_menu_kode +'\')"><label name="CAPTION-BUTUHBANTUAN">'+ C_BUTUHBANTUAN +'</label></button>';
				strmodal +=	'				<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnback"><label name="CAPTION-BACK">'+ C_BACK +'</label></button>';
				strmodal +=	'			</div>';
				strmodal +=	'		</div>';
				strmodal +=	'	</div>';
				strmodal +=	'</div>';
				
				$("#modalnode").html('');
			
				$("#modalnode").append( strmodal );	
		}
		
		$("#previewshownodedetail").modal('show');
	}
	
	function NodeClicked(e, obj) 
	{  
	
		// executed by click and doubleclick handlers
		var node = obj.part;
		
		var key = node.data.key;
		var text = node.data.text;
		var custom_id = node.data.custom_id;
		
		if( custom_id == '-' )
		{
			window.location = '<?= base_url(); ?>HakAkses/MenuAndSub/GoToLink/'+ key;
		}
    }
	
	function FAQ( x_kode )
	{
		window.location = '<?= base_url(); ?>FAQ/'+ x_kode;
	}
	

</script>
