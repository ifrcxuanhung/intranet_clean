<?php
$settings = $newsletter->get_settings($db);

if ($_REQUEST['save']) {
    if (_DEMO_MODE) {
        ob_end_clean();
        echo "Adjusting settings disabled in demo mode sorry";
        exit;
    }
    $newsletter->save_settings($db, $_REQUEST['settings']);
    ob_end_clean();
    header("Location: index.php?p=settings");
    exit;
}
?>
<!-- start main_content -->
<div class="main_content">
    <div class="title_head_ct">
        <h2>Settings</h2>
        <!-- start breadcrumb -->
        <?php
        if (isset($_GET["p"]) && $_GET["p"] != "home") {
            echo "<ul class='breadcrumb'>";
            echo "<li><a href='?p=home'><i class='icon-home'></i></a><span class='divider'>&nbsp;</span></li>";
            echo "<li><a href='?p=settings'>";
            echo ucwords(str_replace("_", " ", "settings"));
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
                <h4><i class="icon-reorder"></i>All Settings (beta)</h4>
            </div>
            <div class="widget-body">
                <form action="?p=settings&save=true" method="post" id="create_form">
                    <table class="table_ct table-hover" style="clear: both;">
                        <tr>
                            <th width="30%">Setting</th>
                            <th width="30%">Value</th>
                            <td width="40%" rowspan="<?php echo count($settings); ?>" valign="top">
                                <strong>Key:</strong>
                                <ul>
                                    <li><strong>username</strong>: used to login to this system</li>
                                    <li><strong>password</strong>: used to login to this system</li>
                                    <li><strong>default_template</strong>: folder name of the default template to use</li>
                                    <li><strong>from_email</strong>: default from email address when creating newsletters</li>
                                    <li><strong>from_name</strong>: default sender name when creating newsletters</li>
                                    <li><strong>per_page</strong>: how many members to display per page</li>
                                    <li><strong>burst_count</strong>: how many emails to send in bursts (increase if you have a dedicated server)</li>
                                    <li><strong>notify_email</strong>: send an alert here every time someone signs up AND when scheduled emails are sent</li>
                                    <li><strong>subscribe_redirect</strong>: full url to send people after signup (instead of showing default message)</li>
                                    <li><strong>unsubscribe_redirect</strong>: full url to send people after unsubscribe (instead of showing default message)</li>
                                    <li><strong>double_opt_in</strong>: Set to  Yes or No</li>
                                    <li><strong>double_opt_in_subject</strong>: Subject of double-optin email</li>
                                    <li><strong>limit_day</strong>: How many emails you are allowed to send per day</li>
                                    <li><strong>limit_month</strong>: How many emails you are allowed to send per month</li>
                                    <li><strong>limit_hour</strong>: How many emails you are allowed to send per hour</li>
                                    <li><strong>inline_images</strong>: Set this to YES to embed images in emails (makes open rate tracking more inaccurate and slows down sending - but if your newsletter is very graphical this will improve changes of receiver seeing images)</li>
                                    <li><strong>bounce settings:</strong></li>
                                    <li><strong>bounce_email</strong>: where bounce emails will get sent. create a new email account for this if possible.</li>
                                    <li><strong>bounce_username</strong>: pop3 username to access this bounce account</li>
                                    <li><strong>bounce_password</strong>: pop3 password to access this bounce account</li>
                                    <li><strong>bounce_host</strong>: pop3 incoming server address to access this bounce account (eg: mail.yourwebsite.com)</li>
                                </ul>
                            </td>
                        </tr>
                        <?php
                        foreach ($settings as $key => $setting) {
                            ?>
                            <tr>
                                <td>
                                    <input type="text" name="settings[<?php echo $key; ?>][key]" class="input_wd" value="<?php echo $key; ?>">
                                </td>
                                <td>
                                    <input type="text" name="settings[<?php echo $key; ?>][val]" class="input_wd" value="<?php echo $setting; ?>">
                                </td>
                            </tr>
                        <?php } ?>

                        <tr>
                            <td>
                                <input type="text" name="settings[new][key]" class="input_wd" value="">
                            </td>
                            <td>
                                <input type="text" name="settings[new][val]" class="input_wd" value="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input style="cursor: pointer;" type="submit" name="save" value="Save Settings">
                            </td>
                            <td></td>
                        </tr>
                    </table>

                </form>
            </div>
        </div>

        <?php
        $groups = $newsletter->get_groups($db);
        $campaigns = $newsletter->get_campaigns($db);
        $form = $newsletter->get_form($db);
        ?>

        <div class="widget">
            <div class="widget-title">
                <h4><i class="icon-reorder"></i>Embed Subscribe Form</h4>
            </div>
            <div class="widget-body ct_fix">
                <p><a href="http://<?php echo $newsletter->base_href; ?>/ext.php?t=update_form" class="cl_fx_hrf">http://<?php echo $newsletter->base_href; ?>/ext.php?t=update_form</a> Update your details link {LINK_ACCOUNT} template</p>
                <p><a href="http://<?php echo $newsletter->base_href; ?>/ext.php?t=signup_form" class="cl_fx_hrf">http://<?php echo $newsletter->base_href; ?>/ext.php?t=signup_form</a> Signup to newsletter link (eg: to iframe)</p>
                <p>Copy and Paste this HTML code to embed the newsletter subscribe form.</p>
                <table class="table_ct table-hover" style="clear: both;">
                    <tr>
                        <td valign="top">
                            <textarea cols="60" class="input" rows="20"><?php echo htmlspecialchars('<form action="http://' . $newsletter->base_href . '/ext.php?t=signup" method="post">' . $form . '</form>'); ?></textarea>
                        </td>
                        <td valign="top">
                            <em>The default form looks like this, you can change it to suit you:</em>
                            <?php echo $form; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="grid_9">
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Sending CRON Job (beta)</h4>
                </div>
                <div class="widget-body ct_fix">
                    <p>The CRON job will process scheduled newsletter sends and any campaigns that are setup.</p>
                    <p>You can run the cron job manually yourself by <a class="cl_fx_hrf" href="cron.php" target="_blank">clicking here</a> (this may take a while to load - it will show a blank screen when done)</p>
                    <p>For cron setup instructions please <a class="cl_fx_hrf" href="cron.php?t" target="_blank">click here</a>.</p>
                </div>
            </div>
        </div>

        <div class="grid_9">
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Bounce Checking CRON Job (beta)</h4>
                </div>
                <div class="widget-body ct_fix">
                    <p>The CRON job will process bounced emails for statistics.</p>
                    <p>You can run the cron job manually yourself by <a class="cl_fx_hrf" href="cron_bounce.php" target="_blank">clicking here</a> (this may take a while to load - it will show a blank screen when done)</p>
                    <p>For cron setup instructions please <a class="cl_fx_hrf" href="cron_bounce.php?t" target="_blank">click here</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>