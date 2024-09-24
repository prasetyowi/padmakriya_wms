<script>
	function ShowTreeMenu( menu_kode )
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('Global/MenuTree/GetMenuByMenuKode') ?>",
			data: 
			{
				menu_kode : menu_kode
			},
			success: function( response )
			{
				if(response)
				{
					ChShowTreeMenu( response );
				}
			}
		});
	}
	
	function ChShowTreeMenu( JSONMenuTree )
	{
		var strdef 	= '';
		
		strdef += 	'<div class="x_title">';
		strdef += 	'	<div class="clearfix"></div>';
		strdef +=	'</div>';
		
		$(".menutree").html( strdef );
		
		var MenuTree = JSON.parse( JSONMenuTree );
		
		var str = '';
		
		//str += '<div class="tf-tree" style="font-size: 10pt;">';
		//str += '<ul>';
		
		$(".menutree").removeClass('form_wizard');
		$(".menutree").removeClass('wizard_verticle');
		
		$(".menutree").addClass('form_wizard');
		$(".menutree").addClass('wizard_verticle');
		
		$(".menutree").append('<ul class="list-unstyled wizard_steps">');
		
		if( MenuTree.MenuTreeMenu != 0 )
		{
			var MenuParent = '';
			
			var numli = 0;
			var isawal = 0;
			
			var str = '';
			
			for( i=0 ; i<MenuTree.MenuTreeMenu.length ; i++ )
			{
				var menu_kode_parent 	= MenuTree.MenuTreeMenu[i].menu_kode_parent;
				var menu_kode_child 	= MenuTree.MenuTreeMenu[i].menu_kode_child;
				var menu_nama 			= MenuTree.MenuTreeMenu[i].menu_nama;
				
				var menu_class 			= MenuTree.MenuTreeMenu[i].menu_class;
				var menu_link 			= MenuTree.MenuTreeMenu[i].menu_link;
				var menu_application	= MenuTree.MenuTreeMenu[i].menu_application;
				var menu_order			= MenuTree.MenuTreeMenu[i].menu_order;
				
				str += '<li><a href="#step-'+ menu_kode +'"><span class="step_no">'+ menu_nama +'</span></a></li>';
				
				/*
				if( menu_application == 'MASTER' )
				{
					if( menu_link == '#sidebar-menu' )
					{
						str += '	<li><span class="tf-nc">'+ menu_nama +'</span>';
						str += '	<ul>';
						
						numli = parseInt(numli) + 1;
					}
					else if( menu_link != '#sidebar-menu' )
					{
						str += '	<li><span class="tf-nc">'+ menu_nama +'</span></li>';
					}
				}
				else if( menu_application == 'FAS' || menu_application == 'WMS' )
				{
					if( isawal == 0 )
					{
						str += '<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">';
					}
					
					if( menu_link != '#sidebar-menu' )
					{	
						str += '	<button type="button" class="btn btn-primary" style="width: 100px; height: 100px; white-space: normal;">'+ menu_nama +'</button>';
					}
					
					if( isawal == 0 )
					{
						str += '</div>';
					}
					
				}	*/
			}
			
			str += '</ul>';
		}
		
		$(".menutree").append(str);
		
		var strpenutup = '';
		strpenutup += '	</div>';
		
		$(".menutree").append( strpenutup );
			
	}
</script>