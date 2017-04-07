<?php
$newsletter_id = (int) $_REQUEST['newsletter_id'];
if (!$newsletter_id) {
    // basic error checking.
    echo 'Please go back and pick a newsletter';
}

if (isset($_REQUEST['delete'])) {
    if (_DEMO_MODE) {
        echo "Sorry, cant delete newsletters in demo mode... ";
        exit;
    }
    $newsletter->delete_newsletter($db, $newsletter_id);
    ob_end_clean();
    header("Location: index.php?p=past");
    exit;
}

$errors = array();
if (isset($_REQUEST['save']) && $_REQUEST['save']) {

    // save the newsletter
    // check required fields.

    $fields = array(
        //"template" => $_REQUEST['template'],
        "subject" => $_REQUEST['subject'],
        "from_name" => $_REQUEST['from_name'],
        //"content" => $_REQUEST['newsletter_content'],
        "from_email" => $_REQUEST['from_email'],
        "bounce_email" => $_REQUEST['bounce_email'],
    );
	
    // basic error checking, nothing fancy
    foreach ($fields as $key => $val) {
        if (!trim($val)) {
            $errors [] = 'Required field missing: ' . ucwords(str_replace('_', ' ', $key));
        }
    }

    if (!$errors) {

        $newsletter_id = $newsletter->save($db, $newsletter_id, $fields);
        if ($newsletter_id) {
            if ($_REQUEST['send']) {
                // user wants to send this newsletter!! create a send a start away..

                if (isset($_REQUEST['dont_send_duplicate']) && $_REQUEST['dont_send_duplicate']) {
                    $dont_sent_duplicates = true;
                } else {
                    $dont_sent_duplicates = false;
                }
                if (is_array($_REQUEST['group_id'])) {
                    $send_groups = $_REQUEST['group_id'];
                } else {
                    $errors [] = "Please select a group to send to";
                }

                if (!$errors) {
                    $send_id = $newsletter->create_send($db, $newsletter_id, $send_groups, $dont_sent_duplicates, $_REQUEST['send_later']);

                    if (!$send_id) {
                        $errors[] = "No members found to send to";
                    } else {
                        ob_end_clean();
                        header("Location: index.php?p=send&send_id=$send_id");
                        exit;
                    }
                }
            } else {
                ob_end_clean();
                header("Location: index.php?p=open&newsletter_id=$newsletter_id");
                exit;
            }
        } else {
            $errors [] = 'Failed to create newsletter in database';
        }
    }


    foreach ($errors as $error) {
        echo '<div style="font-weight:bold; color:#FF0000; font-size:20px;">' . $error . '</div>';
    }
}


$newsletter_data = $newsletter->get_newsletter($db, $newsletter_id);

$sends = $newsletter->get_newsletter_sends($db, $newsletter_id);
?>
<!-- start main_content -->
<div class="main_content">
    <div class="title_head_ct">
        <h2>Newsletter</h2>
    </div>
    <!-- start main content table -->
    <form action="?p=open&save=true" method="post" id="create_form">
        <div class="grid_9">
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Subject: <?php echo $newsletter_data['subject']; ?> | <a class="cl_fx_hrf" href="#" onclick="$('#other_settings').slideToggle(); return false;">Show settings / edit newsletter again</a></h4>
                </div>
                <div class="widget-body">
                    <input type="hidden" name="newsletter_id" value="<?php echo $newsletter_id; ?>">
                    <div id="other_settings" style="display:none;">
                        <table class="table table-hover datatable" style="clear: both;">
                            <tr>
                                <td>
                                    Email Subject:
                                </td>
                                <td>
                                    <input type="text" class="input_wd" name="subject" value="<?php echo $newsletter_data['subject']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    From Name:
                                </td>
                                <td>
                                    <input type="text" class="input_wd" name="from_name" value="<?php echo $newsletter_data['from_name']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    From Email:
                                </td>
                                <td>
                                    <input type="text" class="input_wd" name="from_email" value="<?php echo $newsletter_data['from_email']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Bounce Email:
                                </td>
                                <td>
                                    <input type="text" class="input_wd" name="bounce_email" value="<?php echo $newsletter_data['bounce_email']; ?>"> (bounced newsletters get sent to this address)
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Advanced Editing:
                                </td>
                                <td>
                                    Your HTML newsletter is saved here: <a class="cl_fx_hrf" href="<?php echo _NEWSLETTERS_DIR; ?>newsletter-<?php echo $newsletter_data['newsletter_id']; ?>.html" target="_blank"><?php echo _NEWSLETTERS_DIR; ?>newsletter-<?php echo $newsletter_data['newsletter_id']; ?>.html</a> and here <a class="cl_fx_hrf" href="<?php echo _NEWSLETTERS_DIR; ?>newsletter-<?php echo $newsletter_data['newsletter_id']; ?>-full.html" target="_blank"><?php echo _NEWSLETTERS_DIR; ?>newsletter-<?php echo $newsletter_data['newsletter_id']; ?>-full.html</a> <br>
                                    You can download these files with FTP, make your advanced changes, and then re-upload it before clicking send below. <br>
                                    You can also go <a class="cl_fx_hrf" href="?p=create&newsletter_id=<?php echo $newsletter_data['newsletter_id']; ?>">back</a> to the edit screen to change this newsletter.
                                </td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                                <td>
                                    <input style="cursor: pointer;" type="submit" name="save" value="Save">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid_9">
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Preview (optional)</h4>
                </div>
                <div class="widget-body">
                    <table class="table table-hover" style="clear: both;">
                        <tr>
                            <td>
                                Preview in Browser
                            </td>
                            <td>
                                <input style="cursor: pointer;" type="submit" name="preview1" value="Open Preview" onclick="this.form.action='preview.php'; popupwin=window.open('about:blank','popupwin','width=700,height=800,scrollbars=1,resizeable=1'); if(!popupwin){alert('Please disable popup blocker'); return false;} this.form.target='popupwin';">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Preview in Email
                            </td>
                            <td>
                                <input type="text" name="preview_email" id="preview_email" class="input_wd" value="<?php echo $newsletter_data['from_email']; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input style="cursor: pointer;" type="submit" name="preview2" value="Send Preview" onclick="this.form.action='preview.php?email=true'; popupwin=window.open('about:blank','popupwin','width=500,height=400,scrollbars=1,resizeable=1'); if(!popupwin){alert('Please disable popup blocker'); return false;} this.form.target='popupwin';">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid_9">
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Send <?php echo (count($sends)) ? ' newsletter out again' : ''; ?></h4>
                </div>
                <div class="widget-body">
                    <table class="table table-hover" style="clear: both;">
                        <tr>
                            <td>
                                Tick which groups you would like to send to:
                            </td>
                            <td>
                                <input type="checkbox" name="group_id[]" value="ALL"> <b>All Members</b><br>
                                <?php
                                $groups = $newsletter->get_groups($db);
								
                                foreach ($groups as $group) {
                                    ?>
                                    <input type="checkbox" name="group_id[]" value="<?php echo $group['group_id']; ?>"> <?php echo $group['group_name']; ?> <br>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Don't send to people who have already received this newsletter:
                            </td>
                            <td>
                                <input type="checkbox" name="dont_send_duplicate" value="true" checked>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Schedule send for a later date:
                            </td>
                            <td>
                                <input type="text" name="send_later" value="" size="10"> (date format: YYYY-MM-DD)
                            </td>
                        </tr>
                        <tr>
                            <td>

                            </td>
                            <td>
                                <input style="cursor: pointer;" type="submit" name="send" value="Send<?php echo (count($sends)) ? ' again' : ''; ?>!">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <?php
        /* see if pending sends exist: */
        $pending = $newsletter->get_pending_sends($db, $newsletter_id);
        if ($pending) {
            ?>
            <div class="grid_9">
                <div class="widget">
                    <div class="widget-title">
                        <h4><i class="icon-reorder"></i>Pending Sends for this newsletter:</h4>
                    </div>
                    <div class="widget-body">
                        <table class="table table-hover" style="clear: both;">
                            <tr>
                                <td>Newsletter</td>
                                <td>Start Send</td>
                                <td>Progress</td>
                                <td>Action</td>
                            </tr>
                            <?php
                            foreach ($pending as $send) {
                                ?>
                                <tr>
                                    <td><?php echo $send['subject']; ?></td>
                                    <td><?php echo $send['start_date']; ?></td>
                                    <td><?php echo $send['progress']; ?></td>
                                    <td><a class="cl_fx_hrf" href="?p=send&send_id=<?php echo $send['send_id']; ?>">Continue Sending</a></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <?
        }

        /* see if previous sends exist */
        if ($sends) {
            ?>
            <div class="grid_9">
                <div class="widget">
                    <div class="widget-title">
                        <h4><i class="icon-reorder"></i>Previous Sends of this Newsletter</h4>
                    </div>
                    <div class="widget-body">
                        <table class="table table-hover" style="clear: both;">
                            <tr>
                                <td>Sent Date</td>
                                <td>Sent To</td>
                                <td>Opened By</td>
                                <td>Unsubscribed</td>
                                <td>Bounces</td>
                                <td>Link Clicks</td>
                                <td></td>
                            </tr>
                            <?php
                            foreach ($sends as $send) {
                                $send = $newsletter->get_send($db, $send['send_id']);
                                ?>
                                <tr>
                                    <td>
                                        <?php echo date("Y-m-d", $send['start_time']); ?>
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
                                        <a href="?p=stats&newsletter_id=<?php echo $newsletter_id; ?>&send_id=<?php echo $send['send_id']; ?>">View Stats</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>

        <div class="grid_9">
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Other actions</h4>
                </div>
                <div class="widget-body">
                    <table class="table table-hover" style="clear: both;">
                        <th>
                            <a class="cl_fx_hrf" href="#" onclick="if(confirm('Really delete this newsletter and all newsletter history? Cannot undo!')){ window.location.href='?p=open&newsletter_id=<?php echo $newsletter_id; ?>&delete=true'; } return false;">Delete Newsletter</a>
                        </th>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>