<!-- start main_content -->
<div class="main_content">
    <div class="title_head_ct">
        <h2>Newsletter Campaigns</h2>
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
                <h4><i class="icon-reorder"></i>List of All Campaigns | <a href="?p=campaign_open&campaign_id=new" class="cl_fx_hrf">Create new Campaign</a></h4>
            </div>
            <div class="widget-body">
                <table class="table table-hover datatable" style="clear: both;">
                    <thead>
                        <tr>
                            <th>Campaign Name</th>
                            <th>Number of Members</th>
                            <th>Number of Newsletters</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $campaigns = $newsletter->get_campaigns($db);
                        foreach ($campaigns as $n) {
                            $n = $newsletter->get_campaign($db, $n['campaign_id']);
                            ?>
                            <tr>
                                <td>
                                    <?php echo $n['campaign_name']; ?>
                                </td>
                                <td>
                                    <?php echo mysql_num_rows($n['members_rs']); ?>
                                </td>
                                <td>
                                    <?php echo mysql_num_rows($n['newsletter_rs']); ?>
                                </td>
                                <td>
                                    <a class="cl_fx_hrf" href="?p=campaign_open&campaign_id=<?php echo $n['campaign_id']; ?>">Open</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>