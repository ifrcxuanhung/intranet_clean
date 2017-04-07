<?php
$page_title = "Dashboard";
?>
<!-- start main_content -->
<div class="main_content">
    <div class="title_head_ct">
        <h2>Dashboard</h2>
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
        <!-- end breadcrumb -->
    </div>
    <!-- start main content table -->
    <div class="grid_9">
        <div class="widget">
            <div class="widget-title">
                <h4><i class="icon-reorder"></i>Recent Newsletter Sends:</h4>
            </div>
            <div class="widget-body">
                <table class="table table-hover datatable" style="clear: both;">
                    <thead>
                        <tr>
                            <th>Send Date</th>
                            <th>Email Subject</th>
                            <th>Sent From</th>
                            <th>Sent To</th>
                            <th>Opened By</th>
                            <th>Unsubscribed</th>
                            <th>Bounces</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $past_sends = $newsletter->get_past_sends($db);
                        $x = 0;
                        foreach ($past_sends as $send) {
                            if ($x++ > 5)
                                break;
                            $n = $newsletter->get_newsletter($db, $send['newsletter_id']);
                            $send = $newsletter->get_send($db, $send['send_id']);
                            ?>
                            <tr>
                                <td>
                                    <?php echo date("Y-m-d H:i:s", $send['start_time']); ?>
                                </td>
                                <td>
                                    <?php echo cut_str($n['subject'], 15); ?>
                                </td>
                                <td>
                                    &lt;<?php echo $n['from_name']; ?>&gt; <?php echo cut_str($n['from_email'], 1000); ?> 
                                </td>
                                <td>
                                    <?php echo count($send['sent_members']); ?> members
                                </td>
                                <td>
                                    <?php echo count($send['opened_members']); ?> members
                                </td>
                                <td>
                                    <?php echo count($send['unsub_members']); ?> members
                                </td>
                                <td>
                                    <?php echo count($send['bounce_members']); ?> members 
                                </td>
                                <td>
                                    <a class="cl_fx_hrf" href="?p=open&newsletter_id=<?php echo $n['newsletter_id']; ?>">Open Newsletter</a> |
                                    <a class="cl_fx_hrf" href="?p=stats&newsletter_id=<?php echo $n['newsletter_id']; ?>&send_id=<?php echo $send['send_id']; ?>">View Stats</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="grid_9">
        <div class="widget">
            <div class="widget-title">
                <h4><i class="icon-reorder"></i>Recent Members:</h4>
            </div>
            <div class="widget-body">
                <table class="table table-hover datatable" style="clear: both;">
                    <thead>
                        <tr>
                            <th>Email Address</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Join Date</th>
                            <th>Groups</th>
                            <th>Sent</th>
                            <th>Opened</th>
                            <th>Bounces</th>
                            <!--th>Campaigns</th-->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $groups = $newsletter->get_groups($db);
                        $members = $newsletter->get_members($db, false, true, 5);
                        while ($member = mysql_fetch_assoc($members)) {
                            $member = $newsletter->get_member($db, $member['member_id']);
                            ?>
                            <tr>
                                <td>
                                    <?php echo cut_str(strtolower(_shl($member['email'], $search['email'])), 1000); ?>
                                </td>
                                <td style="text-transform:capitalize">
                                    <?php echo xmlentities(mb_strtolower(_shl($member['first_name'], $search['name']), 'UTF-8')); ?>
                                </td>
                                <td style="text-transform:uppercase">
                                    <?php echo cut_str(xmlentities(_shl($member['last_name'], $search['name'])), 1000); ?>
                                </td>
                                <td>
                                    <?php echo $member['join_date']; ?>
                                </td>
                                <td>
                                    <?php
                                    $print = '';
                                    foreach ($member['groups'] as $group_id) {
                                        $print .= '<a class="cl_fx_hrf" href="?p=groups&edit_group_id=' . $group_id . '">';
                                        if (isset($search['group_id'][$group_id])) {
                                            $print .= '<span style="background-color:#FFFF66">';
                                            $print .= cut_str($groups[$group_id]['group_name'], 1000 / count($member['groups']));
                                            $print .= '</span>';
                                        } else {
                                            $print .= cut_str($groups[$group_id]['group_name'], 1000 / count($member['groups'])) . "";
                                        }
                                        $print .= '</a>,';
                                    }
                                    echo rtrim($print, ",");
                                    ?>
                                </td>
                                <td>
                                    <?php echo count($member['sent']); ?>
                                </td>
                                <td>
                                    <?php echo count($member['opened']); ?>
                                </td>
                                <td>
                                    <?php echo count($member['bounces']); ?> 
                                </td>

                                <!--td>
                                    <?php
                                    $print = '';
                                    foreach ($member['campaigns'] as $campaign) {
                                        $print .= '<a href="?p=campaign_open&campaign_id=' . $campaign['campaign_id'] . '">';
                                        $print .= cut_str($campaign['campaign_name'], 10) . "";
                                        $print .= '</a>,';
                                    }
                                    echo rtrim($print, ",");
                                    ?>
                                </td-->
                                <td>
                                    <a class="icon-button edit" href="?p=members&edit_member_id=<?php echo $member['member_id']; ?>">Edit</a> | 
                                    <a class="icon-button delete" href="?p=members&delete_member_id=<?php echo $member['member_id']; ?>" onclick="if(confirm('Really delete this member?'))return true;else return false;">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="grid_9">
        <div class="widget">
            <div class="widget-title">
                <h4><i class="icon-reorder"></i>Pending Sends:</h4>
            </div>
            <div class="widget-body">
                <table class="table table-hover datatable" style="clear: both;">
                    <thead>
                        <tr>
                            <th>Newsletter</th>
                            <th>Start Send</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sends = $newsletter->get_pending_sends($db);
                        foreach ($sends as $send) {
                            ?>
                            <tr>
                                <td><?php echo cut_str($send['subject'], 15); ?></td>
                                <td><?php echo $send['start_date']; ?></td>
                                <td><?php echo $send['progress']; ?></td>
                                <td><a class="cl_fx_hrf" href="?p=send&send_id=<?php echo $send['send_id']; ?>">Continue Send</a></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    
    
    <!-- end main content table -->
</div>
<!-- end main_content -->