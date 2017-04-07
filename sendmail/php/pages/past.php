<?php 

if ($_REQUEST['delete_newsletter_id']) {
    if (_DEMO_MODE) {
        echo "DELETE DISABLED IN DEMO MODE SORRY.... email me if you REALLY want something deleted, or just unsubscribe from an email that gets sent to you :) ";
        exit;
    }
    $newsletter->delete_newsletter($db, $_REQUEST['delete_newsletter_id']);
    ob_end_clean();
    header("Location: index.php?p=past");
    exit;
}

?>


<!-- start main_content -->
<div class="main_content">
    <div class="title_head_ct">
        <h2>Past Newsletters</h2>
    </div>
    <!-- start main content table -->
    <div class="grid_9">
        <div class="widget">
            <div class="widget-title">
                <h4><i class="icon-reorder"></i>List of All Newsletters</h4>
            </div>
            <div class="widget-body">
                <table class="table table-hover datatable" style="clear: both;">
                    <tr>
                        <th>Email Subject</th>
                        <th>Sent From</th>
                        <th>Sent To</th>
                        <th>Opened By</th>
                        <th>Unsubscribed</th>
                        <th>Bounces</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $newsletters = $newsletter->get_newsletters($db);
                    foreach ($newsletters as $n) {
                        $n = $newsletter->get_newsletter($db, $n['newsletter_id']);
                        $sends = $newsletter->get_newsletter_sends($db, $n['newsletter_id']);
                        ?>
                        <tr>
                            <td>
                                <?php echo $n['subject']; ?>
                            </td>
                            <td>
                                &lt;<?php echo $n['from_name']; ?>&gt; <?php echo $n['from_email']; ?> 
                            </td>
                            <td>
                                <?php
                                foreach ($sends as $send) {
                                    $send = $newsletter->get_send($db, $send['send_id']);
                                    ?>

                                    <?php echo count($send['sent_members']); ?> members on <?php echo date("Y-m-d", $send['start_time']); ?> <br>

                                <?php } ?>
                            </td>
                            <td>
                                <?php
                                foreach ($sends as $send) {
                                    $send = $newsletter->get_send($db, $send['send_id']);
                                    ?>

                                    <?php echo count($send['opened_members']); ?> members <br>

                                <?php } ?>
                            </td>
                            <td>
                                <?php
                                foreach ($sends as $send) {
                                    $send = $newsletter->get_send($db, $send['send_id']);
                                    ?>

                                    <?php echo count($send['unsub_members']); ?> members <br>

                                <?php } ?>
                            </td>
                            <td>
                                <?php
                                foreach ($sends as $send) {
                                    $send = $newsletter->get_send($db, $send['send_id']);
                                    ?>

                                    <?php echo count($send['bounce_members']); ?> members <br>

                                <?php } ?>
                            </td>
                            <td>
                                <a class="icon-button edit" href="?p=create&newsletter_id=<?php echo $n['newsletter_id']; ?>">Edit</a> | 
                                <a class="icon-button delete" href="?p=past&delete_newsletter_id=<?php echo $n['newsletter_id']; ?>" onclick="if(confirm('Really delete this newsletter?'))return true;else return false;">Delete</a>
                                <!--a class="cl_fx_hrf" href="?p=open&newsletter_id=<?php echo $n['newsletter_id']; ?>">Stats/Send</a-->
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>