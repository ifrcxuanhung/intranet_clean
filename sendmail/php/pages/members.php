<?php


$groups = $newsletter->get_groups($db);


if (isset($_REQUEST['csvsample'])) {
    ob_end_clean();
    header("Content-type: text/csv");
    header('Content-Disposition: attachment; filename="sample_import.txt"');
    echo "Email" . chr(9) . "First Name" . chr(9) . "Last Name\n";
    echo "demo@demo.com" . chr(9) . "Bob" . chr(9) . "Smith\n";
    echo "demo@example.com" . chr(9) . "Jane" . chr(9) . "Doe\n";
    exit;
}

if (isset($_REQUEST['import'])) {
    ob_end_clean();
    if (_DEMO_MODE) {
        echo "CSV import not allowed in demo mode sorry";
        exit;
    }
    $filename = $_FILES['upload']['tmp_name'];
    if (is_uploaded_file($filename)) {
        $fd = fopen($filename, "r");
        $csv_data = array();
        while ($row = fgetcsv($fd)) {
            $csv_data [] = $row;
        }

        // remove first row, it should be the headers
        $csv_headers = array_shift($csv_data);
        // pull out the defaults, first name, last name, emails
        $positions = array(
            "first_name" => 1,
            "last_name" => 2,
            "email" => 0,
        );
        $group_key = false;
        $custom_fields = array();
        foreach ($csv_headers as $key => $val) {
            if (preg_match('/email/i', $val)) {
                $positions['email'] = $key;
            } else if (preg_match('/first/i', $val)) {
                $positions['first_name'] = $key;
            } else if (preg_match('/last/i', $val)) {
                $positions['last_name'] = $key;
            } else if (!$group_key && preg_match('/Subscribed/i', $val)) {
                $group_key = $key;
            } else {
                // make it a custom field.
                $custom_fields[$key] = $val;
            }
        }
        // process the others.. no error checking
        // their fault if they do it wrong :D
        // i warned them.

        $group_ids = (isset($_REQUEST['group_id']) && is_array($_REQUEST['group_id'])) ? $_REQUEST['group_id'] : array();
        // do the processing:
        $import_count = 0;
        
        set_time_limit(0);
        ini_set('memory_limit', -1);
        foreach ($csv_data as $data) {
            if (!trim($data[0]))
                continue;
            $custom = array();
            foreach ($custom_fields as $key => $val) {
                $custom[$val] = trim($data[$key]);
            }
            $this_group_ids = $group_ids;
            if ($group_key) {
                // this import has a field which lists which groups they are a part of
                $this_group_ids = array();
                foreach ($groups as $group) {
                    if (preg_match('#' . preg_quote($group['group_name'], '#') . '#i', $data[$group_key])) {
                        $this_group_ids[] = $group['group_id'];
                    }
                }
            }
            //$data = explode(chr(9), $data[0]);
            $fields = array(
                "email" => trim($data[0]),
                "civilite" => trim($data[1]),
                "first_name" => trim($data[2]),
                "last_name" => trim($data[3]),
                "societe" => trim($data[4]),
                "group_id" => $this_group_ids,
                "custom" => $custom,
            );
            
            $member_id = $newsletter->save_member($db, "new", $fields);
            /*
            if ($member_id) {
                /* get list 
                $list_field = $newsletter->select('member_field', 'member_field_id');
                if (count($list_field) > 0) {
                    foreach($list_field as $key => $value) {
                        //$newsletter->insert('member_field_value', array('member_id' => $member_id, 'member_field_id' => $value['member_field_id']));
                    }
                }
                $import_count++;
            }
            */
            $import_count++;
        }
        echo "Successfully imported $import_count members. Lets hope it worked! <a href='?p=members'>click here</a> to find out!";
        exit;
    }
}


if ($_REQUEST['save']) {
    /* && $_REQUEST['member_id'] && $_REQUEST['mem_email'] */
    $fields = array(
        "civilite" => htmlspecialchars($_REQUEST['civilite']),
        "first_name" => htmlspecialchars($_REQUEST['mem_first_name']),
        "last_name" => htmlspecialchars($_REQUEST['mem_last_name']),
        "email" => htmlspecialchars($_REQUEST['mem_email']),
        "societe" => htmlspecialchars($_REQUEST['societe']),
        "group_id" => $_REQUEST['group_id'],
        "campaign_id" => $_REQUEST['campaign_id'],
        "custom" => $_REQUEST['mem_custom_val'],
    );
    $member_id = $newsletter->save_member($db, $_REQUEST['member_id'], $fields);
    if ($member_id) {
        /* save custom fields */
        if ($_REQUEST['mem_custom_new_val'] && $_REQUEST['mem_custom_new_key']) {
            $newsletter->save_member_custom($db, $member_id, $_REQUEST['mem_custom_new_key'], $_REQUEST['mem_custom_new_val'], true);
        }
        ob_end_clean();
        header("Location: index.php?p=members");//&edit_member_id=$member_id
        exit;
    }
}

if ($_REQUEST['delete_member_id']) {
    if (_DEMO_MODE) {
        echo "DELETE DISABLED IN DEMO MODE SORRY.... email me if you REALLY want something deleted, or just unsubscribe from an email that gets sent to you :) ";
        exit;
    }
    $newsletter->delete_member($db, $_REQUEST['delete_member_id']);
    ob_end_clean();
    header("Location: index.php?p=members");
    exit;
}
/* $all_members = $newsletter->get_members_all($db);
 while ($member = mysql_fetch_assoc($all_members)) {
	 echo "<pre>";print_r($member);
 }exit;*/
if ($_REQUEST['export']) {
    ob_end_clean();
    header("Content-Type: application/html; charset=UTF-8");
    header("Content-type: application/octet-stream");
    header('Content-Disposition: attachment; filename="newsletter-members.xls"');
    header("Pragma: no-cache");
    header("Expires: 0");
    $all_members = $newsletter->get_members_all($db);
    $csvs = "Email" . chr(9) . htmlspecialchars("Civilité") . chr(9) . "First Name" . chr(9) . "Last Name" . chr(9) . htmlspecialchars("Company") . chr(9) . htmlspecialchars("Address") . chr(9) . htmlspecialchars("District") . chr(9) . htmlspecialchars("Phone") . chr(9) . htmlspecialchars("Group") . chr(9) ."\n";
    while ($member = mysql_fetch_assoc($all_members)) {
        //$member = $newsletter->get_member($db, $member['member_id']);
        $csvs.= htmlspecialchars($member['email']) . chr(9) . htmlspecialchars($member['civilite']) . chr(9);
        $csvs.= htmlspecialchars($member['first_name']) . chr(9) . htmlspecialchars($member['last_name']) . chr(9) . htmlspecialchars($member['societe']) . chr(9). htmlspecialchars($member['address']). chr(9). htmlspecialchars($member['Zip code']) . chr(9). htmlspecialchars($member['Phone']) . chr(9). htmlspecialchars($member['group_name']) . chr(9);
        $csvs.= "\n";
    }
    echo $csv = chr(255) . chr(254) . mb_convert_encoding($csvs, "UTF-16LE", "UTF-8");
    exit;
}

?>

<!-- start main_content -->
<div class="main_content">
    <div class="title_head_ct">
        <h2>Members/Subscribers</h2>
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
            <p>Here you can manage all your members/subscribers.</p>
        </div>
        <!-- end breadcrumb -->
    </div>

    <?php
    if ($_REQUEST['edit_member_id']) {
        $member_id = (int) $_REQUEST['edit_member_id'];
        $member_data = $newsletter->get_member($db, $member_id);
        ?>

        <!-- start main content table -->
        <form action="" method="post" id="create_form">
            <input type="hidden" name="member_id" value="<?php echo $member_id; ?>">
            <input type="hidden" name="save" value="true">
            <div class="grid_9">
                <div class="widget">
                    <div class="widget-title">
                        <h4><i class="icon-reorder"></i>Edit Member</h4>
                    </div>
                    <div class="widget-body">
                        <?php include("members_form.php"); ?>
                    </div>
                </div>
            </div>
        </form>

        <div class="grid_9">

            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Recent Member Activity</h4>
                </div>
                <div class="widget-body">
                    <table class="table_ct table-hover datatable" style="clear: both;">
                        <thead>
                            <tr>
                                <th>Send Date</th>
                                <th>Email Subject</th>
                                <th>Sent From</th>
                                <th>Opened</th>
                                <th>Bounced</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($member_data['sent'] as $send) {
                                $n = $newsletter->get_newsletter($db, $send['newsletter_id']);
                                ?>
                                <tr>
                                    <td>
                                        <?php echo date("Y-m-d H:i:s", $send['start_time']); ?>
                                    </td>
                                    <td>
                                        <?php echo cut_str($n['subject'], 15); ?>
                                    </td>
                                    <td>
                                        &lt;<?php echo cut_str($n['from_name'], 10); ?>&gt; <?php echo cut_str($n['from_email'], 15); ?>
                                    </td>
                                    <td>
                                        <?php echo ($send['open_time'] > 0) ? 'YES: ' . date('Y-m-d H:i:s', $send['open_time']) : 'NO'; ?>
                                    </td>
                                    <td>
                                        <?php echo ($send['bounce_time'] > 0) ? 'YES: ' . date('Y-m-d H:i:s', $send['bounce_time']) : 'NO'; ?>
                                    </td>
                                    <td>
                                        <a class="cl_fx_hrf" href="?p=open&newsletter_id=<?php echo $n['newsletter_id']; ?>">Open Newsletter</a> |
                                        <a class="cl_fx_hrf" href="?p=stats&newsletter_id=<?php echo $n['newsletter_id']; ?>&send_id=<?php echo $send['send_id']; ?>">View Full Stats</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    } else {
        $groups = $newsletter->get_groups($db);

        /* pull out data ready for processing */
        $per_page_limit = (isset($newsletter->settings['per_page']) && (int) $newsletter->settings['per_page']) ? (int) $newsletter->settings['per_page'] : 30;
        $search = array();

        /* if(isset($_REQUEST['searchsession'])){
          $search = $_SESSION['_mem_search'];
          } */
        /* so we can add other fields later easier. */
        if (isset($_REQUEST['search_fields']) && is_array($_REQUEST['search_fields'])) {
            $search = array_merge($search, $_REQUEST['search_fields']);
        }

        if (isset($_REQUEST['current-start-letter']) && $_REQUEST['current-start-letter'] == $search['start-letter']) {
            /* clicked it again twice. */
            unset($search['start-letter']);
        }

        /* if(!$search){
          $_SESSION['_mem_search'] = array();
          } */
        if (isset($_POST['sort'])) {
            $sort = $_POST['sort'];
            if ($_POST['change'] == 'true') {
                if ($_SESSION['type'] == 'asc') {
                    $_SESSION['type'] = $type = 'desc';
                } else {
                    $type = $_SESSION['type'] = 'asc';
                }
            } else {
                $type = $_SESSION['type'];
            }
        } else {
            $sort = "";
        }
        $pagable_members = $newsletter->get_members($db, false, false, false, $search, $sort, $type);
        /* used to work out how many pages */
        $page_count = ceil(mysql_num_rows($pagable_members) / $per_page_limit);
        unset($all_search['start-letter']);
        $all_members = $newsletter->get_members($db, false, false, false, $all_search, $sort, $type);
        /* used for buttons highlight and total mem count */
        $member_fields = $newsletter->get_member_fields($db);
        ?>
        <!-- start main content table -->
        <form action="?p=members&search=true" method="post" id="search_form">
            <!--div class="grid_9">

                <div class="widget">
                    <div class="widget-title">
                        <h4><i class="icon-reorder"></i>Search Members</h4>
                        <span class="tools">
                            <a href="javascript:;" class="icon-chevron-down"></a>
                            <a href="javascript:;" class="icon-remove"></a>
                        </span>
                    </div>
                    <div class="widget-body">
                        <input type="hidden" name="ps" value="0" id="ps" />
                        <input type="hidden" class="formsortdata" name="sort" id="sort" value="<?php echo isset($_POST['sort']) ? $_POST['sort'] : ''; ?>" />
                        <table class="table_ct table-hover" style="clear: both;">
                            <tbody>
                                <tr>
                                    <td valign="top">
                                        Email:
                                    </td>
                                    <td valign="top" width="25%">
                                        <input type="text" name="search_fields[email]" id="email" value="<?php echo htmlspecialchars($search['email']); ?>" style="width:300px">
                                    </td>
                                    <td valign="top" width="5%">
                                        Name:
                                    </td>
                                    <td valign="top" width="25%">
                                        <input type="text" name="search_fields[name]" id="namesearch" value="<?php echo htmlspecialchars($search['name']); ?>" style="width:300px">
                                    </td>
                                    <td valign="top">
                                        <input style="cursor: pointer;" type="submit" name="search" value="Search!">
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" width="6%">
                                        Groups:
                                    </td>
                                    <td valign="top">
                                        <?php foreach ($groups as $group) { ?>
                                            <input type="checkbox" name="search_fields[group_id][<?php echo $group['group_id']; ?>]" <?php echo (isset($search['group_id'][$group['group_id']])) ? 'checked' : ''; ?> value="true"> <?php echo cut_str($group['group_name'], 30); ?>
                                            <?php echo '<b>(' . mysql_num_rows($newsletter->get_members($db, $group['group_id'])) . ' members)</b>' ?>
                                            <br>
                                        <?php } ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div-->
            <!--JQRGID-->
            
            <div class="grid_9">
                <div class="widget">
                    <div class="widget-title">
                        <h4><i class="icon-reorder"></i>Member List <span>(<?php echo mysql_num_rows($all_members); ?>)</span></h4>
                        <a href="?p=members_add" style="padding:5px; background: #89c4f4; text-decoration: none; border:1px solid #cecece; color: #FFF; float: right"><span class="icon-user"></span> <?php echo _l('Add Members'); ?></a>   
                    </div>
                <div class="widget-body">
                 <table id="jqGrid"></table>
                 <input type="hidden" name="column" class="column" id="column" attr="">
        		<div id="jqGridPager"></div>
    	       </div>
            </div>
        
        </div>
        <?php 
		$jq_member_field = $newsletter->getMemberField($db);?>

    <script type="text/javascript"> 
    
        $(document).ready(function () {
			//var column = jQuery.parseJSON($("#column").attr('attr'));
            $("#jqGrid").jqGrid({
                url: 'php/ajax.php',
                mtype: "POST",
                datatype: "json",
                page: 1,
                colModel: [
                   {   label : "Member_id",
						//sorttype: 'integer',
						name: 'member_id', 
						width: 10,
						key:true,
						hidden:true,
					},
                    {   label : "Email",
						//sorttype: 'integer',
						name: 'email', 
						width: 280 
					},
					{   label : "Civilité",
						//sorttype: 'integer',
						name: 'civilite', 
						width: 90 
					},
					{   label : "First Name",
						//sorttype: 'integer',
						name: 'first_name', 
						width: 90 
					},
					{   label : "Last Name",
						//sorttype: 'integer',
						name: 'last_name', 
						width: 90 
					},
					{   label : "Company",
						//sorttype: 'integer',
						name: 'societe', 
						width: 120 
					},
					<?php echo $jq_member_field;?>
					
					{   label : "Groups",
						//sorttype: 'integer',
						name: 'group_name', 
						width: 90,
						cellattr: function(rowId, cellValue, rawObject, cm, rdata) {
							return 'style="white-space: normal;"'
						}
					},
					{
						label: "Actions",
                        name: "actions",
                        width: 120,
						align:'center',
                        formatter: returnMyLink,      
                    },
					
					
                   
                ],
               
				loadonce: true,
				viewrecords: true,
                //width: 'auto',
                height: 'auto',
				autowidth:true, 
				//shrinkToFit:false,
               // rowNum: 25,
                pager: "#jqGridPager"
            });
			
			// activate the toolbar searching
            $('#jqGrid').jqGrid('filterToolbar');
			$('#jqGrid').jqGrid('navGrid',"#jqGridPager", {                
                search: false, // show search button on the toolbar
                add: false,
                edit: false,
                del: false,
                refresh: true
            });
			
			function returnMyLink(cellValue, options, rowdata, action) 
			{
				//console.log(options);
				return "<a class='icon-button edit' href='?p=members&edit_member_id=" + options.rowId + "' >Edit</a> | <a class='icon-button delete' style='color:#FF0000;' href='?p=members&delete_member_id=" + options.rowId + "'>Delete</a>";
			}
			
			
        });

    </script>
            
            <!--END JQGRID-->
            
            
            

            <div class="grid_9">
                <div class="widget">
                    <div class="widget-title">
                        <h4><i class="icon-reorder"></i>Other Action</h4>
                    </div>
                    <div class="widget-body">
                        <table class="table table-hover" style="clear: both;">
                           
                            <tr>

                            <tr>
                                <td>
                                    <input style="cursor: pointer;" type="button" onclick="window.location.href='?p=members&export=true'" name="Export_all_members_to_CSV" value="Export all members to XLS" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
          
    		
            <!-- end main content table -->
            <br/>

            <?php
        }
        ?>
    </form>
    
      <form action="?p=members_add&import=true" method="post" id="create_form" enctype="multipart/form-data">
       			 <div class="grid_9">

            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Import Members from CSV (beta)</h4>
                </div>
                <div class="widget-body ct_fix">
                    <p> <a href="?p=members_add&csvsample" class="cl_fx_hrf">Click here</a> to download a sample CSV file, use this format when importing your own members below. </p>
                    <p class="lst"> <strong>BETA:</strong> Please <em>MAKE SURE</em> your file is in the same format as provided above.<br>
                        There is no error checking in this beta import. </p>
                    <table class="table_ct table-hover">
                        <tr>
                            <td width="15%">Choose Your CSV File</td>
                            <td width="65%"><input type="file" name="upload" size="70" /></td>
                        </tr>
                        <tr>
                            <td>Import members into these groups</td>
                            <td>
                                <?php foreach ($groups as $group) { ?>
                                    <input type="checkbox" name="group_id[]" value="<?php echo $group['group_id']; ?>"> <?php echo $group['group_name']; ?> <br>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input style="cursor: pointer;" type="submit" name="upload_file" value="Upload CSV File"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- end main content table -->
    		</form>
    <div id="page_number" style="display: none;"><?php echo isset($_POST['ps']) ? $_POST['ps'] : '0'; ?></div>
    <div id="letter" style="display: none;"><?php echo ($_POST['current-start-letter'] != '') ? $_POST['current-start-letter'] : ''; ?></div>   
    
    
    
    <!--{   label : "Address",
						//sorttype: 'integer',
						name: 'address', 
						width: 200,
						cellattr: function(rowId, cellValue, rawObject, cm, rdata) {
							return 'style="white-space: normal;"'
						}
					},
					{   label : "District",
						//sorttype: 'integer',
						name: 'Zip code', 
						width: 70 
					},
					
					{   label : "Phone",
						//sorttype: 'integer',
						name: 'Phone', 
						width: 120 
					},
					
					{   label : "Test",
						//sorttype: 'integer',
						name: 'Test', 
						width: 120 
					},-->