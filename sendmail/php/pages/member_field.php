
<!-- start main_content -->
<div class="main_content">
    <div class="title_head_ct">
        <h2>Members Field</h2>
        <!-- start breadcrumb -->
        <?php
        if (isset($_GET["p"]) && $_GET["p"] != "home") {
            echo "<ul class='breadcrumb'>";
            echo "<li><a href='?p=home'><i class='icon-home'></i></a><span class='divider'>&nbsp;</span></li>";
            echo "<li><a href='?p={$_GET["p"]}'>";
            echo ucwords(str_replace("_", " ", $_GET["p"]));
            echo "</a><span class='divider-last'>&nbsp;</span></li>";
            echo "</ul>";
        }
        ?>
        <div class="title_hd">
            <p>Here you can manage all your members field.</p>
        </div>
        <!-- end breadcrumb -->
    </div>

   
        <!-- start main content table -->
        <form action="?p=members&search=true" method="post" id="search_form">
          
            <!--JQRGID-->
            
            <div class="grid_9">
                <div class="widget">
                    <div class="widget-title">
                        <h4><i class="icon-reorder"></i>Member Field</h4>
                        
                    </div>
                <div class="widget-body">
                 <table id="jqGrid"></table>
                 <input type="hidden" name="column" class="column" id="column" attr="">
        		<div id="jqGridPager"></div>
    	       </div>
            </div>
        
        </div>
  

    <script type="text/javascript"> 
    
        $(document).ready(function () {
			//var column = jQuery.parseJSON($("#column").attr('attr'));
            $("#jqGrid").jqGrid({
                url: 'php/ajax_member_field.php',
				editurl: 'php/ajax_member_field_edit.php',
                mtype: "POST",
                datatype: "json",
                page: 1,
                colModel: [
                   {   label : "id",
						//sorttype: 'integer',
						name: 'member_field_id', 
						width: 10,
						key:true,
						hidden:true,
					},
                    {   label : "Field Name",
						//sorttype: 'integer',
						name: 'field_name', 
						width: 100 ,
						editable: true,
					},
					{   label : "Field Type",
						//sorttype: 'integer',
						name: 'field_type', 
						width: 90 ,
						editable: true,
					},
					{   label : "Required",
						//sorttype: 'integer',
						name: 'required', 
						width: 90 ,
						editable: true,
					},
					{   label : "Active",
						//sorttype: 'integer',
						name: 'active', 
						width: 100 ,
						editable: true,
					},
				
					
					
                   
                ],
               
				loadonce: true,
				viewrecords: true,
                //width: 'auto',
                height: 'auto',
				autowidth:true, 
				//shrinkToFit:false,
               	rowNum: 10,
                pager: "#jqGridPager"
            });
			
			// activate the toolbar searching
            $('#jqGrid').jqGrid('filterToolbar');
			$('#jqGrid').navGrid("#jqGridPager", {edit: false, add: false, search:false, del: true, refresh: false, view: false});
			$('#jqGrid').inlineNav('#jqGridPager',
                // the buttons to appear on the toolbar of the grid
                { 
                    edit: true, 
                    add: true, 
                    del: true, 
                    cancel: true,
                    editParams: {
                        keys: true,
                    },
                    addParams: {
                        keys: true
                    }
                });
			
			
        });

    </script>
            
            <!--END JQGRID-->

    </form>
   