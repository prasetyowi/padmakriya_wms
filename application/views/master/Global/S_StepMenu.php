<link href="<?php echo base_url(); ?>vendors/hortree/src/jquery.hortree.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>vendors/hortree/src/jquery.hortree.js"></script>
<script src="<?php echo base_url(); ?>vendors/hortree/src/jquery.line.js"></script>

<script type="text/javascript">
	var arrMenuTree = [];
	var arrMenuTreeDetail = [];
	
	function ShowStep( menu_kode )
	{
		var menu_name = GetLanguageByKode( 'CAPTION-'+ menu_kode );
		$(".right_col div:eq(0)").removeAttr('id');
		$(".right_col div:eq(0)").attr('id','menusteplist');
		
		var strmenudefault = 	'<div class="page-title">';
			strmenudefault += 	'	<div class="title_left">';
			strmenudefault +=	'		<h3>'+ menu_name +'</h3>';
			strmenudefault += 	'	</div>';
			strmenudefault += 	'</div>';
			strmenudefault += 	'<div class="clearfix"></div>';
			strmenudefault += 	'<div id="modalstep"></div>';
			strmenudefault += 	'<div id="menustep" style="overflow-x: scroll;"></div>';
			
		$("#menusteplist").html( strmenudefault );
		
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('HakAkses/MenuAndSub/GetMenuChildByMenuKode') ?>",
			data: 
			{
				menu_parent: menu_kode
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
	
	function ChMenuChild( JSONMenuTree )
	{
		var MenuTree = JSON.parse( JSONMenuTree );
		
		if( MenuTree.MenuStepMenu != 0 )
		{
			var obj = '';
			var P_menu_step_id = '';
			var P_menu_step_level = 0;
			
			var strheader = '';
			var strjson = '';
			var strpenutup = '';
			
			var c = 0;
			var tandakoma = 0;
			var menu_children = '';
			
			arrMenuTree = [];
			arrMenuTreeDetail = [];
			
			for( i=0 ; i < MenuTree.MenuStepMenu.length ; i++ )
			{
				var menu_step_id				= MenuTree.MenuStepMenu[i].menu_step_id;
				var menu_step_header_id			= MenuTree.MenuStepMenu[i].menu_step_header_id;
				var menu_step_menu_kode_header	= MenuTree.MenuStepMenu[i].menu_step_menu_kode_header;
				var menu_step_menu_kode_parent	= MenuTree.MenuStepMenu[i].menu_step_menu_kode_parent;
				var menu_step_menu_kode_child	= MenuTree.MenuStepMenu[i].menu_step_menu_kode_child;
				var menu_step_icon				= MenuTree.MenuStepMenu[i].menu_step_icon;
				var menu_name					= MenuTree.MenuStepMenu[i].menu_name;
				var menu_step_urut				= MenuTree.MenuStepMenu[i].menu_step_urut;
				var menu_step_id				= MenuTree.MenuStepMenu[i].menu_step_id;
			
				header_id = menu_step_header_id; 
			
				arrMenuTree.push( { icon: menu_step_icon, header_id: menu_step_header_id, parent: menu_step_menu_kode_parent, child: menu_step_menu_kode_child, 
								name: menu_name, level : menu_step_urut, menu_step_id : menu_step_id } );
				
				//var urut = menu_step_urut +'. ';
				var urut = '';
				
				if( menu_step_urut == 0 )
				{	
					strheader += 	'{';
					strheader += 	'	"description" : "'+ menu_name +'",';
					strheader += 	'	"children": [';
				}
				else 				
				{	
					if( P_menu_step_level < menu_step_urut ) // level nya seperti sebelumnya
					{
						strjson += 	'{';
						strjson += 	'	"description" : "'+ urut + menu_name +'",';
						strjson += 	'	"children": [';
						
						c++;
					}
					else if( P_menu_step_level > menu_step_urut )
					{
						var diff = P_menu_step_level - menu_step_urut;
						
						for( j=0 ; j<=diff ; j++ )
						{
							strjson += ']}';
						}
						
						strjson += 	',{';
						strjson += 	'	"description" : "'+ urut + menu_name +'",';
						strjson += 	'	"children": [';
						
						for( j=0 ; j<diff ; j++ )
						{
							c--;
						}
					}
					else if( P_menu_step_level == menu_step_urut )
					{
						strjson += 	']},';
						strjson += 	'{';
						strjson += 	'	"description" : "'+ urut + menu_name +'",';
						strjson += 	'	"children": [';
						
					}
				}
				
				if( menu_step_urut == 0 )
				{
					strpenutup = ']}';
				}
				
				P_menu_step_level = menu_step_urut;
				
				
			}
			
			for( i=0 ; i<c ; i++ )
			{
				strjson = strjson + ']}';
			}
			
			obj = '['+ strheader + strjson + strpenutup +']'; 

			obj = JSON.parse( obj );

			$('#menustep').hortree(
			{	
				lineStrokeWidth: 2,
				lineZindex: 8,
				lineColor:'#4b86b7',
				data: obj
			});
			
			$(".hortree-label").css('word-break','break-all');
			
			if( MenuTree.MenuStepDetailMenu != 0 )
			{
				for( i=0 ; i < MenuTree.MenuStepMenu.length ; i++ )
				{
					var menu_step_id					= MenuTree.MenuStepDetailMenu[i].menu_step_id;
					var menu_step_menu_kode_header		= MenuTree.MenuStepDetailMenu[i].menu_step_menu_kode_header;
					var menu_step_menu_kode_parent		= MenuTree.MenuStepDetailMenu[i].menu_step_menu_kode_parent;
					var menu_step_menu_kode_child		= MenuTree.MenuStepDetailMenu[i].menu_step_menu_kode_child;
					var menu_step_detail_keterangan_1	= MenuTree.MenuStepDetailMenu[i].menu_step_detail_keterangan_1;
					var menu_step_detail_link_1			= MenuTree.MenuStepDetailMenu[i].menu_step_detail_link_1;
					var menu_step_detail_keterangan_2	= MenuTree.MenuStepDetailMenu[i].menu_step_detail_keterangan_2;
					var menu_step_detail_link_2			= MenuTree.MenuStepDetailMenu[i].menu_step_detail_link_2;
					var menu_step_detail_keterangan_3	= MenuTree.MenuStepDetailMenu[i].menu_step_detail_keterangan_3;
					var menu_step_detail_link_3			= MenuTree.MenuStepDetailMenu[i].menu_step_detail_link_3;
				
					arrMenuTreeDetail.push( { 	parent: menu_step_menu_kode_parent, child: menu_step_menu_kode_child, menu_step_id: menu_step_id, 
												menu_step_detail_keterangan_1: menu_step_detail_keterangan_1, menu_step_detail_link_1: menu_step_detail_link_1,
												menu_step_detail_keterangan_2: menu_step_detail_keterangan_2, menu_step_detail_link_2: menu_step_detail_link_2,
												menu_step_detail_keterangan_3: menu_step_detail_keterangan_3, menu_step_detail_link_3: menu_step_detail_link_3
										} );
			
				}		
			}
		}
		
		var lview = $("#menustep .hortree-label").length;
		
		for( i=0 ; i<lview ; i++ )
		{
			
			var menu_name = $("#menustep .hortree-label").eq(i).html();
			
			if( i == 0 )
			{
				var idx = arrMenuTree.findIndex( val => val.name == menu_name );
				
				$("#menustep .hortree-label").eq(i).attr('data-menu_kode', menu_kode );
				$("#menustep .hortree-label").eq(i).attr('data-menu_step_id', menu_step_id );
			}
			else if( i != 0 )
			{
				//menu_name = menu_name.split('. ');
				
				var idx = arrMenuTree.findIndex( val => val.name == menu_name );
				
				$("#menustep .hortree-label").eq(i).attr('data-menu_kode', menu_kode );
				$("#menustep .hortree-label").eq(i).attr('data-menu_step_id', menu_step_id );
			}
			
			var menu_kode 		= arrMenuTree[idx].child;
			var menu_step_id 	= arrMenuTree[idx].menu_step_id;
			var icon		 	= arrMenuTree[idx].icon;
			
			$("#menustep .hortree-label").eq(i).addClass('selectedview');
			$("#menustep .hortree-label").eq(i).attr('id','selectedview_'+ menu_kode );
			$("#menustep .hortree-label").eq(i).attr('data-menu_step_id', menu_step_id );
			$("#menustep .hortree-label").eq(i).attr('data-menu_kode', menu_kode );
			
			
			if( icon != 'null' && icon != null && icon != '')
			{
				var isi = $("#selectedview_"+ menu_kode ).html();
					isi = '<label id="lbicon_'+ menu_kode +'"><i class="fas '+ icon +' fa-4x"></i></label> <div id="divicon_'+ menu_kode +'"></div>' + isi;
					
				strinfo = '<i style="color: #4b86b7; position: absolute; top: 0; right: calc(100% - 120px); z-index: 1;" class="fa fa-info-circle fa-2x" data-menu_kode="'+ menu_kode +'" id="ipreviewmenustepdetail_'+ menu_kode +'"></i>';
					
				
				$("#selectedview_"+ menu_kode ).html(isi);
				$("#selectedview_"+ menu_kode ).append( strinfo );
				
				$("#selectedview_"+ menu_kode ).attr('data-menu_kode', menu_kode );
				//var strinfo = 
				//$( strinfo ).insertAfter( $("#selectedview_"+ menu_kode) );
			}
			
			$("#ipreviewmenustepdetail_"+ menu_kode ).click
			(
				function(e)
				{
					$(".side-menu li").removeClass('active');
					$(".child_menu").css('display','none');
					var x_kode = $(this).attr('data-menu_kode');
				
					PreviewMenuStepDetail( x_kode );
						
					e.stopPropagation();
				}
			);

			function PreviewMenuStepDetail( x_kode )
			{
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('HakAkses/MenuAndSub/GetMenuStepDetail') ?>",
					data: 
					{
						menu_child: x_kode
					},
					success: function( response )
					{
						if(response)
						{
							ChPreviewMenuStepDetail( response, x_kode );
						}
					}
				});
			}
			
			function ChPreviewMenuStepDetail( JSONMenuStepDetail, x_kode )
			{
				var MenuStepDetail = JSON.parse( JSONMenuStepDetail );
				
				if( MenuStepDetail.MenuStepDetailMenu != 0 )
				{
					var menu_name						= MenuStepDetail.MenuStepDetailMenu[0].menu_name;
					var x_menu_link 					= MenuStepDetail.MenuStepDetailMenu[0].menu_link;
					var menu_step_detail_keterangan_1	= MenuStepDetail.MenuStepDetailMenu[0].menu_step_detail_keterangan_1;
					var menu_step_detail_keterangan_2	= MenuStepDetail.MenuStepDetailMenu[0].menu_step_detail_keterangan_2;
					var menu_step_detail_keterangan_3	= MenuStepDetail.MenuStepDetailMenu[0].menu_step_detail_keterangan_3; 
					var menu_step_detail_link_1			= MenuStepDetail.MenuStepDetailMenu[0].menu_step_detail_link_1;
					var menu_step_detail_link_2			= MenuStepDetail.MenuStepDetailMenu[0].menu_step_detail_link_2;
					var menu_step_detail_link_3			= MenuStepDetail.MenuStepDetailMenu[0].menu_step_detail_link_3;

					var strdetailket = '';
					
					if( menu_step_detail_keterangan_1 == '' && menu_step_detail_keterangan_2 == '' && menu_step_detail_keterangan_3 == '' &&
						menu_step_detail_link_1 == '' && menu_step_detail_link_2 == '' && menu_step_detail_link_3 == '' )
					{	
						strdetailket = C_TIDAKADADETAILINFOLEBIHLANJUT;	
					}
					else
					{
						if( menu_step_detail_keterangan_1 != '' )
						{
							strdetailket += menu_step_detail_keterangan_1;
						}
						if( menu_step_detail_link_1 != '' )
						{
							strdetailket += ' <a href="'+ menu_step_detail_link_1 + '">'+ C_LIHATINFOLEBIHLANJUT +'</a><br />';
						}
						strdetailket += '<br />';
						if( menu_step_detail_keterangan_2 != '' )
						{
							strdetailket += menu_step_detail_keterangan_2;
						}
						if( menu_step_detail_link_2 != '' )
						{
							strdetailket += ' <a href="'+ menu_step_detail_link_2 + '">'+ C_LIHATINFOLEBIHLANJUT +'</a><br />';
						}
						strdetailket += '<br />';
						if( menu_step_detail_keterangan_3 != '' )
						{
							strdetailket += menu_step_detail_keterangan_3;
						}
						if( menu_step_detail_link_3 != '' )
						{
							strdetailket += '<a href="'+ menu_step_detail_link_3 + '">'+ C_LIHATINFOLEBIHLANJUT +'</a><br />';
						}			
					}
					
					strdetailket = strdetailket.replace('<ThisURL>', '<a href="<?= base_url(); ?>'+ x_menu_link +'">');
					strdetailket = strdetailket.replace('</ThisURL>', '</a>');
					
					var strmodal = 	'';
						strmodal += '<div class="modal fade" id="previewshowstepdetail" role="dialog" data-keyboard="false" data-backdrop="static">';
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
						
						$("#modalstep").html('');
					
						$("#modalstep").append( strmodal );
						
				}

				$("#previewshowstepdetail").modal('show');
				
				
			}
			
			$("#selectedview_"+ menu_kode ).click	
			(
				function(e)
				{
					var menu_kode = $(this).attr('data-menu_kode');
				
					$.ajax({
						type: 'POST',
						url: "<?= base_url('HakAkses/MenuAndSub/GetMenuLinkByMenuKode') ?>",
						data: 
						{
							menu_kode: menu_kode
						},
						success: function(response) 
						{
							var link = JSON.parse( response );
							
							if( link.MenuLink[0].menu_link != '#sidebar-menu' )
							{
								window.location = '<?= base_url(); ?>'+ link.MenuLink[0].menu_link;
							}
						}
					});
				}
			);

		}
		
		$(".hortree-label").css('border', 'none');
		$(".hortree-label").css('background', 'none');
		$(".hortree-label").css('cursor', 'pointer');
		
	}
	
	function FAQ( x_kode )
	{
		window.location = '<?= base_url(); ?>FAQ/'+ x_kode;
	}
	

</script>
