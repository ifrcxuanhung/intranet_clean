<?php
$page_title = "Create Newsletter";

$input_method = 'manual';

$newsletter_id = (int) $_REQUEST['newsletter_id'];
if (!$newsletter_id) {
    $newsletter_id = 'new';
}
$newsletter_content_id = ($newsletter_id == 'new') ? 'new' : false;
if (isset($_REQUEST['newsletter_content_id'])) {
    $newsletter_content_id = (int) $_REQUEST['newsletter_content_id'];
    if (!$newsletter_content_id) {
        $newsletter_content_id = 'new';
    }
}
/*if ($_REQUEST['hung']) {
		$newsletter->send_email('ifrc.xuanhung@gmail.com', "[PREVIEW] ", 'grwegre', 'ifrc.xuanhung@gmail.com', 'hung');
}*/
$errors = array();
if ($_REQUEST['save']) {

    // save the newsletter 
    // check required fields.
    $fields = array(
        "template" => $_REQUEST['template'],
        "subject" => $_REQUEST['subject'],
        "from_name" => $_REQUEST['from_name'],
        //"content" => $_REQUEST['newsletter_content'], // not required any more
        "from_email" => $_REQUEST['from_email'],
        "bounce_email" => $_REQUEST['bounce_email'],
    );

    // basic error checking, nothing fancy
    foreach ($fields as $key => $val) {
        if (!trim($val)) {
            $errors [] = 'Required field missing: ' . ucwords(str_replace('_', ' ', $key));
        }
    }

    if (isset($_REQUEST['newsletter_content'])) {
        // old static html way:
        $fields['content'] = $_REQUEST['newsletter_content'];
    }
    if (!$errors) {
        $newsletter_id = $newsletter->save($db, $newsletter_id, $fields);
    }

    if ($newsletter_content_id) {
        if (!$errors) {
            //$newsletter_id = $newsletter->save($db,$newsletter_id,$fields);
            $newsletter_content_id = $newsletter->save_content($db, $newsletter_id, $newsletter_content_id);
            //echo "Save $newsletter_id  '$newsletter_content_id'";exit;
            if ($newsletter_id && $newsletter_content_id) {
                // save newsletter content thumb and main image.
                if (is_uploaded_file($_FILES['image_thumb']['tmp_name'])) {
                    if (!_DEMO_MODE) {
                        $folder = _IMAGES_DIR . 'newsletter-' . $newsletter_id . '/';
                        if (!is_dir($folder)) {
                            mkdir($folder);
                        }
                        if (is_dir($folder)) {
                            move_uploaded_file($_FILES['image_thumb']['tmp_name'], $folder . $newsletter_content_id . '-thumb.jpg');
                            foreach (glob($folder . '_thumb/' . $newsletter_content_id . '-thumb.jpg*') as $thumb) {
                                unlink($thumb);
                            }
                        }
                    } else {
                        $errors[] = "Image uploads disabled in demo mode sorry.";
                    }
                }
                if (is_uploaded_file($_FILES['image_main']['tmp_name'])) {
                    if (!_DEMO_MODE) {
                        $folder = _IMAGES_DIR . 'newsletter-' . $newsletter_id . '/';
                        if (!is_dir($folder)) {
                            mkdir($folder);
                        }
                        if (is_dir($folder)) {
                            move_uploaded_file($_FILES['image_main']['tmp_name'], $folder . $newsletter_content_id . '.jpg');
                            foreach (glob($folder . '_thumb/' . $newsletter_content_id . '.jpg*') as $thumb) {
                                unlink($thumb);
                            }
                        }
                    } else {
                        $errors[] = "Image uploads disabled in demo mode sorry.";
                    }
                }
            } else if (!$newsletter_id) {
                $errors [] = 'Failed to create newsletter in database';
            }
        }
    }

    if (is_uploaded_file($_FILES['image']['tmp_name'])) {
        if (!_DEMO_MODE) {
            move_uploaded_file($_FILES['image']['tmp_name'], _IMAGES_DIR . basename($_FILES['image']['name']));
        } else {
            $errors[] = "Image uploads disabled in demo mode sorry.";
        }
    }
    if (is_uploaded_file($_FILES['attachment']['tmp_name'])) {
        if (!_DEMO_MODE) {
            move_uploaded_file($_FILES['attachment']['tmp_name'], _IMAGES_DIR . basename($_FILES['attachment']['name']));
        } else {
            $errors[] = "Attachment uploads disabled in demo mode sorry.";
        }
    }
    if ($_REQUEST['next']) {
        ob_end_clean();
        header("Location: index.php?p=open&newsletter_id=$newsletter_id");
        exit;
    }
    foreach ($errors as $error) {
        echo '<div style="font-weight:bold; color:#FF0000; font-size:20px;">' . $error . '</div>';
    }

    /* if(!$errors){
      if(isset($_REQUEST['next_newsletter_content_id']) && $_REQUEST['next_newsletter_content_id']){
      ob_end_clean();
      header("Location: index.php?p=create&newsletter_id=$newsletter_id&newsletter_content_id=".$_REQUEST['next_newsletter_content_id']);
      exit;
      }
      } */
}

if (isset($_REQUEST['next_action_key'])) {
    switch ($_REQUEST['next_action_key']) {
        case 'delete_content':
            if (!$errors) {
                $newsletter->delete_content($db, $newsletter_id, (int) $_REQUEST['next_action_val']);
                ob_end_clean();
                header("Location: index.php?p=create&newsletter_id=$newsletter_id");
                exit;
            }
            break;
        case 'swap_content':
            if (!$errors) {
                ob_end_clean();
                header("Location: index.php?p=create&newsletter_id=$newsletter_id&newsletter_content_id=" . $_REQUEST['next_action_val']);
                exit;
            }
            break;
        case 'preview':
            if (!$errors) {
                ob_end_clean();
                header("Location: index.php?p=preview&newsletter_id=$newsletter_id&hide_menu=true");
                exit;
            }
            break;
        case 'preview_email':
            if (!$errors) {
                ob_end_clean();
                header("Location: index.php?p=preview&newsletter_id=$newsletter_id&hide_menu=true&email=" . urlencode($_REQUEST['next_action_val']));
                exit;
            }
            break;
    }
}

$templates = $newsletter->get_templates();
$default_template = $newsletter->settings['default_template'];


if ($newsletter_id != 'new') {
    $newsletter_data = $newsletter->get_newsletter($db, $newsletter_id);
    $current_template = $newsletter_data['template'];
} else {
    $current_template = $default_template;
    /* ob_start();
      if(is_file(_TEMPLATE_DIR.$default_template."/inside.html")){
      include(_TEMPLATE_DIR.$default_template."/inside.html");
      }
      $inside_content = ob_get_clean(); */
    /* find a new name for this newsletter. */
    $newsletter_name = date('F') . ' 2013';
    if (_DEMO_MODE) {
        $all_newsletters = $newsletter->get_newsletters($db);
        $x = 1;
        while (true) {
            $this_name = $newsletter_name . " $x";
            $has = false;
            foreach ($all_newsletters as $n) {
                if ($n['subject'] == $this_name) {
                    $has = true;
                    break;
                }
            }
            $x++;
            if (!$has) {
                $newsletter_name = $this_name;
                break;
            }
        }
    }
    $newsletter_data = arraY(
        "template" => $default_template,
        "subject" => $newsletter_name,
        "from_name" => $newsletter->settings['from_name'],
        "from_email" => $newsletter->settings['from_email'],
        "bounce_email" => $newsletter->settings['bounce_email'],
            /* "content"=>htmlspecialchars($inside_content), */
    );
}


if ($newsletter_id == 'new' || $_REQUEST['template_reload']) {
    ob_start();
    if (is_file(_TEMPLATE_DIR . $current_template . "/inside.php")) {
        include(_TEMPLATE_DIR . $current_template . "/inside.php");
    }
    $inside_content = ob_get_clean();
	
    $newsletter_data['content'] = $inside_content;
	
}
?>

<script src="layout/js/tinymce/tinymce.min.js"></script>
<script>
	tinymce.init({
		selector:'textarea.newsletter_content',
	});
</script>

<!-- start main_content -->
<div class="main_content">
    <div class="title_head_ct">
        <h2>Create Newsletter</h2>
        <div class="title_hd">
            <p>Here you can manage all your members/subscribers.</p>
        </div>
    </div>
    <!-- start main content table -->
    <form action="?p=create&save=true#editor" method="post" id="create_form" enctype="multipart/form-data">
        <input type="hidden" name="newsletter_id" value="<?php echo $newsletter_id; ?>">
        <input type="hidden" name="template" id="template" value="<?php echo $newsletter_data['template']; ?>">
        <input type="hidden" name="template_reload" id="template_reload" value="">
        <input type="hidden" name="newsletter_content_id" id="newsletter_content_id" value="<?php echo $newsletter_content_id; ?>">
        <input type="hidden" name="next_action_key" id="next_action_key" value="">
        <input type="hidden" name="next_action_val" id="next_action_val" value="">
        <div class="grid_9">
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Step 1: Template</h4>
                </div>
                <div class="widget-body ct_fix">
                    <table class="table_ct table-hover" style="clear: both;">
                        <div class="template">
                            <?php
                            foreach ($templates as $template) {
                                ?>
                                <th style="cursor: pointer;">
                                <div class="template<?php echo ($newsletter_data['template'] == $template['name']) ? ' selected' : ''; ?>" rel="<?php echo $template['name']; ?>">
                                    <img src="<?php echo $template['dir']; ?>/preview.jpg" border="0" /><br />
                                    <strong><?php echo $template['name']; ?></strong>
                                </div>
                                </th>
                            <?php } ?>
                        </div>
                    </table>
                </div>
                <script language="javascript">
                    $('.template').click(function(){
                        $('.templates .selected').removeClass('selected');
                        $(this).addClass('selected');
                        $('#template').val($(this).attr('rel'));
                        /* prompt to re-load with content available */
<?php if ($input_method != 'wizard') { ?>
            var reload_content = confirm('Would you like to use this template inner content (this will replace any existing content below with template defaults)');
            if(reload_content){
                $('#template_reload').val('1');
            }
<?php } ?>
        $('#create_form')[0].action='?p=create&save=true#editor'; 
        $('#create_form')[0].target='_self';
        $('#create_form')[0].submit();
        return false;
    });
                </script>
            </div>
        </div>

        <div class="grid_9">
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Step 2 : Settings</h4>
                </div>
                <div class="widget-body ct_fix">
                    <table class="table_ct table-hover" style="clear: both;">
                        <tr>
                            <td>
                                Email Subject: <span class="required">*</span>
                            </td>
                            <td>
                                <input type="text" class="input_wd" name="subject" value="<?php echo $newsletter_data['subject']; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                From Name: <span class="required">*</span>
                            </td>
                            <td>
                                <input type="text" class="input_wd" name="from_name" value="<?php echo $newsletter_data['from_name']; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                From Email: <span class="required">*</span>
                            </td>
                            <td>
                                <input type="text" class="input_wd" name="from_email" value="<?php echo $newsletter_data['from_email']; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Bounce Email: <span class="required">*</span>
                            </td>
                            <td>
                                <input type="text" class="input_wd" name="bounce_email" value="<?php echo $newsletter_data['bounce_email']; ?>"> (Bounced newsletters get sent to this address)
                            </td>
                        </tr>
                        <tr>
                            <td>

                            </td>
                            <td>
                                <input type="submit" name="save_settings" style="cursor: pointer;" value="Save Settings">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid_9">
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Step 3 : Content</h4>
                </div>
                <div class="widget-body ct_fix">

                    <script language="javascript" type="text/javascript">
                        var image_width=0;
                        var image_height=0;
                    </script>

<!--<input type="radio" name="input_method" value="wizard" <?php if (!$input_method || $input_method == 'wizard') echo ' checked'; ?>> Wizard <input type="radio" name="input_method" value="manual"<?php if ($input_method == 'manual') echo ' checked'; ?>> Manual HTML -->


                    <div class="box">
                            <!--<p>Here you can copy and paste from Word, or simply type out your newsletter. The above template will be applied to this content.<br>
                            Note: if you copy and paste from Word, please use the 'Paste from Word' button. </p>-->
                        <a name="editor"></a>
                        <?php 
                        if ($input_method == 'wizard') {
                            /* include the wizzard file from the template */
                            $newsletter_contents = $newsletter->get_newsletter_contents($db, $newsletter_id);
							
                            $group_titles = array();
                            if ($newsletter_contents) {
                                foreach ($newsletter_contents as $key => $val) {
                                    $group_titles[$val['group_title']] = true;
                                }
                            }
                            include(_TEMPLATE_DIR . $current_template . '/wizard_ui.php');
                        } else {
							
                            ?>
							
                            <table class="table_ct table-hover" style="clear: both;">
                                <tr>
                                    <td valign="top">
                                        <table class="table table-hover" style="float: left; clear: both;">
                                            <tr>
                                                <td valign="top">
                                                    <textarea rows="15" cols="70" class="newsletter_content" name="newsletter_content" id="newsletter_content"><?php //echo htmlspecialchars($newsletter_data['content']);
													echo  str_replace("sendmail.upmd.fr","upmd.fr",
mb_convert_encoding($newsletter_data['content'], "UTF-8")); ?></textarea>
                                                </td>
                                                <td valign="top">
                                                    <div id="image_insert">
                                                        <table class="table table-hover" style="float: left; clear: both;">
                                                            <tr>
                                                                <td><strong>Insert image into article:</strong></td>
                                                                <td>
                                                                    <?php if ($input_method == 'wizard') { ?>
                                                                        Your "main image" above will display at the top of your article.<br>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <select name="image_url" id="image_url" style="width: 312px;">
                                                                        <option value=""> #1: select an image </option>
                                                                        <?php
                                                                        foreach ($newsletter->get_images($db, $newsletter_id) as $attachment) {
                                                                            ?>
                                                                            <option value="<?php echo $attachment['link']; ?>"><?php echo $attachment['name']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <select name="image_size" id="image_size" style="width: 312px;" onchange="$('#image_alt')[0].focus().select();">
                                                                        <option value=""> #2: select size </option>
                                                                        <option value="replace">Replace Existing</option>
                                                                        <option value="100x100">Thumbnail #1 - 100x100</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <input type="text" name="image_alt" id="image_alt" class="input_wd" value="Image Description" onfocus="if(this.value=='Image Description')this.value='';">
                                                                </td>
                                                            </tr>
                                                            <tr>

                                                                <td>
                                                                    <input type="button" name="image_insert" style="cursor: pointer;" onclick="insert_image();" value="Insert Image">
                                                                </td>
                                                                <td>
                                                                    <a class="cl_fx_hrf" href="#" onclick="$('#image_insert').hide(); $('#image_upload').show(); return false;">Upload New Image</a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div id="image_upload" style="display: none;">
                                                        <table class="table table-hover" style="float: left; clear: both;">
                                                            <tr>
                                                                <td colspan="2"><strong>Upload new Image:</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <input type="file" name="image" value="" size="40">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input type="submit" name="attach" style="cursor: pointer;" value="Upload" onclick="this.form.action='?p=create&save=true#editor'; this.form.target='_self';" />
                                                                </td>
                                                                <td>
                                                                    <a class="cl_fx_hrf" href="#" onclick="$('#image_upload').hide(); $('#image_insert').show(); return false;">Insert Existing Image</a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <script language="javascript">
                                                        function insert_image(){
                                                            var src = $('#image_url').val();
                                                            $('#image_url').val('');
                                                            var size = $('#image_size').val();
                                                            $('#image_size').val('');
                                                            var alt = $('#image_alt').val();
                                                            if(alt=='Image Description')alt='';
                                                            $('#image_alt').val('Image Description');
                                                            /* validation: */
                                                            if(!src || src == '')return;
                                                            /* is the user currently clicking on an image: */
                                                                                                                                                                                                                                                        								
                                                            var imghtml = '<img src="' + src + '" alt="'+alt+'"';
                                                            if(size == 'replace'){
                                                            }else if(size != ''){
                                                                /* split and use size. */
                                                                var foo = size.split('x');
                                                                image_width = foo[0];
                                                                image_height= foo[1];
                                                            }else{
                                                                image_width = image_height = 0;
                                                            }
                                                            if(image_width) imghtml += ' width="'+image_width+'"';
                                                            if(image_height) imghtml += ' height="'+image_height+'"';
                                                            imghtml += ' />';
                                                            tinyMCE.execCommand('mceInsertRawHTML',false, imghtml);
                                                        }
                                                    </script>
                                                    <!--<h3>Attachments (beta):</h3>
                                                    <?php
													//echo "<pre>";print_r(str_replace("sendmail.upmd.fr","upmd.fr",$newsletter->get_attachments($db, $newsletter_id)));exit;
                                                    foreach ($newsletter->get_attachments($db, $newsletter_id) as $attachment) {
                                                        ?>
                                                                                                                                                                                                                <a href="<?php echo $attachment['link']; ?>" target="_blank"><?php echo $attachment['name']; ?></a> <input type="checkbox" name="del_attachment_id[]" value="<?php echo $attachment['name']; ?>">delete <br>
                                                    <?php } ?>
                                                    Upload: <input type="file" name="attachment" value="" size="6">
                                                    <hr>
                                                    <input type="submit" name="attach" value="Save">-->
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid_9">
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Step 4 : Preview</h4>
                </div>
                <div class="widget-body ct_fix">
                    <table class="table table-hover" style="clear: both;">
                        <tr>
                            <td>
                                Preview in Browser
                            </td>
                            <td>
                                <input type="submit" name="preview1" style="cursor: pointer;" value="Open Preview" onclick="$('#next_action_key').val('preview');">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Preview in Email
                            </td>
                            <td>
                                <input type="text" class="input_wd" name="preview_email" id="preview_email" value="<?php echo $newsletter->email; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" name="preview2" style="cursor: pointer;" value="Send Preview" onclick="$('#next_action_key').val('preview_email');$('#next_action_val').val($('#preview_email').val());"> 
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid_9">
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Step 5 : Save</h4>
                </div>
                <div class="widget-body ct_fix">
                    <table class="table table-hover" style="clear: both;">
                        <tr>
                            <td>
                                Once you are happy with your preview, click this button to go to the next step.
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" name="save_cont" style="cursor: pointer;" value="Save Newsletter and Continue to next step..." onclick="this.form.action='?p=create&save=true&next=true'; this.form.target='_self';" />
                                
                                 <!--<input type="submit" name="hung" style="cursor: pointer;" value="hung" />-->
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>