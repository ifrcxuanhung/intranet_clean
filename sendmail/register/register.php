<?php
session_start();
require_once '../config.php';
require_once '../php/database.php';
require_once '../php/class.newsletter.php';

$newsletter = new newsletter();

if ($_POST) {

    if ($_SESSION['security_code'] != $_POST['security_code']) {
        /* Insert your code for showing an error message here */
        $errs[] = "Le code de sécurité n'est pas valide";
    }

    $arr = array(
        "nom" => "Nom est requis",
        "prenom" => "Prénom est requis",
        "email" => "Email est requis"
    );

    if (is_array($_POST['conference'])) {
        $arr = array(
            "nom" => "Nom est requis",
            "prenom" => "Prénom est requis",
            "email" => "Email est requis"
        );
    } else {
        $arr = array(
            "nom" => "Nom est requis",
            "prenom" => "Prénom est requis",
            "email" => "Email est requis",
            "conference" => "Conference est requis"
        );
    }

    foreach ($arr as $input => $err) {
        if ($_POST[$input] == '')
            $errs[] = $err;
    }

    if ($_POST['email'] != '')
        if (!eregi("^[a-zA-Z0-9.-]*[a-zA-Z0-9]@[a-zA-Z0-9.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]*$", $_POST['email'])) {
            $errs[] = "Email n'est pas valide";
        }

    $category = $_GET['category'];
    $data['info'] = $newsletter->select('category', '*', "name = '$category'");
    $data['info'] = current($data['info']);
    if (!is_array($data['info']) || count($data['info']) == 0) {
        echo 'No data';
        exit();
    }
    if ($data['info']['type'] == 1) {
        if ($_POST['civilite'] == 'Genre')
            $_POST['civilite'] = '';
    }

    /* get group selected for checkbox */
    if (is_array($_POST['conference']) && count($_POST['conference']) > 0) {
        foreach ($_POST['conference'] as $key => $value) {
            $conference .= $key . ",";
        }
        $conference .= "end";
        $conference = str_replace(",end", "", $conference);
    }
    /* end get group selected for checkbox */

    if (!$errs) {
        /* Change check email and group 15-04-2013 */
        $where = "email = '" . $_POST['email'] . "' AND member.member_id = member_group.member_id AND member_group.group_id IN (";
        foreach ($_POST['conference'] as $key => $value) {
            $where .= "'" . $key . "',";
        }
        $where .= "end";
        $where = str_replace(",end", "", $where);
        $where .= ")";
        $member = $newsletter->select('member, member_group', 'email', $where);
        /* end Change check email and group 15-04-2013 */
        if (is_array($member) and count($member) > 0) {
            echo "<script type='text/javascript'>";
            echo "alert('Email a été enregistré')";
            echo "</script>";
        } else {
            $member = $newsletter->select("member", "member_id, email", "email = '" . $_POST['email'] . "'");
            if (is_array($member) and count($member) > 0) {
                $member_id = $member[0]['member_id'];
            } else {
                $arr = array(
                    'civilite' => $_POST['civilite'],
                    'first_name' => $_POST['prenom'],
                    'email' => $_POST['email'],
                    'last_name' => $_POST['nom'],
                    'societe' => $_POST['societe'],
                    'join_date' => date('Y-m-d')
                );
                $member_id = $newsletter->insert('member', $arr);

                $arr = array(
                    'address' => '1',
                    'Code_Postal' => '2',
                    'Ville' => '3',
                    'phone' => '4'
                );

                foreach ($arr as $key => $value) {
                    $a = array(
                        'member_id' => $member_id,
                        'member_field_id' => $value,
                        'value' => $_POST[$key]
                    );
                    $newsletter->insert('member_field_value', $a);
                }
            }

            foreach ($_POST['conference'] as $key => $value) {
                $a = array(
                    'member_id' => $member_id,
                    'group_id' => $key
                );
                $newsletter->insert('member_group', $a);
            }

            /* send mail */
            $data['member'] = $newsletter->get_member('', $member_id);
            $category = $_GET['category'];
            $data['info'] = $newsletter->select('category', '*', "name = '$category'");
            $data['info'] = current($data['info']);
            if (!is_array($data['info']) || count($data['info']) == 0) {
                echo 'No data';
                exit();
            }

            $data['conference'] = $newsletter->select('`group`', '*', "category_name = '" . $data['info']['name'] . "'");

            $content = 'Vous êtes incrit à : <br>';
            if (is_array($data['conference']))
                foreach ($data['conference'] as $key => $value) {
                    foreach ($data['member']['groups'] as $v) {
                        if ($value['group_id'] == $v)
                            $content.= ' - ' . $value['group_name'] . '<br>';
                    }
                }
            if ($data['member']['civilite'] == '') {
                $content .= '<br><label>Nom :</label> ' . $data['member']['first_name'] . '<br>
                <label>Prénom :</label> ' . $data['member']['last_name'] . '<br>
                <label>Email :</label> ' . $data['member']['email'] . '<br>
                <label>Adresse :</label> ' . $data['member']['custom'][1]['value'] . '<br>
                <label>Code postal :</label> ' . $data['member']['custom'][2]['value'] . '<br>
                <label>Ville :</label> ' . $data['member']['custom'][3]['value'] . '<br>
                <label>Téléphone :</label> ' . $data['member']['custom'][4]['value'] . '<br>
                <label>Evénement :</label><br>';
            } else {
                $content .= '<br><label>Civilité :</label> ' . $data['member']['civilite'] . '<br>
		<label>Nom :</label> ' . $data['member']['first_name'] . '<br>
                <label>Prénom :</label> ' . $data['member']['last_name'] . '<br>
                <label>Email :</label> ' . $data['member']['email'] . '<br>
                <label>Société :</label> ' . $data['member']['societe'] . '<br>
		<label>Adresse :</label> ' . $data['member']['custom'][1]['value'] . '<br>
                <label>Code postal :</label> ' . $data['member']['custom'][2]['value'] . '<br>
                <label>Ville :</label> ' . $data['member']['custom'][3]['value'] . '<br>
                <label>Téléphone :</label> ' . $data['member']['custom'][4]['value'] . '<br>
                <label>Evénement :</label><br>';
            }
            if (is_array($data['conference']))
                foreach ($data['conference'] as $key => $value) {
                    foreach ($data['member']['groups'] as $v) {
                        if ($value['group_id'] == $v)
                            $content.= ' - ' . str_replace($data['info']['name'] . ' >', '', $value['group_name']) . '<br>';
                    }
                }
            $newsletter->send_email($data['member']['email'], $data['info']['title'] . ' - ' . $data['info']['description'], $content, _MAIL_SMTP_USER, _MAIL_SMTP_USER, $options = array('bcc' => $data['info']['email']));
            /* end send mail */
            echo '<script>alert("Enregistrement réussi!");window.location.href="register.php?category=' . $_GET['category'] . '";</script>';
        }
    } else {
        $err = "";
        foreach ($errs as $key => $value) {
            if ($value != "") {
                $err .= $value . "\\n\\n";
            }
        }
        $err = str_replace("'", "", $err);
        echo "<script type='text/javascript'>";
        echo "alert('";
        echo $err;
        echo "');";
        $text = 'window.location.href="register.php?category=' . $_GET['category'];
        $text .= $_POST['civilite'] != '' ? '&civilite=' . $_POST['civilite'] : '';
        $text .= $_POST['nom'] != '' ? '&nom=' . $_POST['nom'] : '';
        $text .= $_POST['prenom'] != '' ? '&prenom=' . $_POST['prenom'] : '';
        $text .= $_POST['email'] != '' ? '&email=' . $_POST['email'] : '';
        $text .= $_POST['societe'] != '' ? '&societe=' . $_POST['societe'] : '';
        $text .= $_POST['address'] != '' ? '&address=' . $_POST['address'] : '';
        $text .= $_POST['Code_Postal'] != '' ? '&Code_Postal=' . $_POST['Code_Postal'] : '';
        $text .= $_POST['Ville'] != '' ? '&Ville=' . $_POST['Ville'] : '';
        $text .= $_POST['phone'] != '' ? '&phone=' . $_POST['phone'] : '';
        $text .= $conference != '' ? '&conference=' . $conference : '';
        $text .= '";</script>';
        echo $text;
    }
}
$category = $_GET['category'];
$data['info'] = $newsletter->select('category', '*', "name = '$category'");
$data['info'] = current($data['info']);

if (!is_array($data['info']) || count($data['info']) == 0) {
    echo 'No data';
    exit();
}

$data['conference'] = $newsletter->select('`group`', '*', "category_name = '" . $data[info][name] . "'");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv = "Content-Type" content = "text/html; charset=utf-8" />
        <title><?php echo _l('Newsletter System'); ?>:: Register</title>
        <link rel = "stylesheet" href = "css/style.css" type = "text/css" />
        <link rel = "stylesheet" href = "css/theme-1.css" type = "text/css" /> <!--theme file - can be changed-->
        <!--js files-->

        <script type = "text/javascript" src = "js/jquery.min.js"></script>
        <script type = "text/javascript" src = "js/jquery.form.js"></script>
        <script type="text/javascript" src="js/custom.js"></script>
        <!-- /js files -->
    </head>

    <body>
        <div class="headline-wrapper">
            <div class="headline">
                <div class="headline-inner">

                </div>
            </div>
        </div>
        <!-- /Head Line -->

        <!-- Content Line -->
        <div class="contentline">
            <div class="clear_mr_top"></div>
            <div class="contentline-inner fixw">

                <div class="content mr_top">
                    <div class="two-fifth pull-three last"></div>
                    <div class="removemargin"></div>
                    <!-- SECTION 5 -->

                    <!-- Sponsors -->

                    <div class="sponsors">
                        <?php
                        if ($data['info']['logo'] != '') {
                            ?>
                            <img height="60px" src="../images/<?php echo $data['info']['logo'] ?>" alt="" id="logo" />
                            <?php
                        }
                        if ($data['info']['logo2'] != '') {
                            ?>
                            <img height="60px" src="../images/<?php echo $data['info']['logo2'] ?>" alt="" id="logo2" />
                            <?php
                        }
                        if ($data['info']['logo3'] != '') {
                            ?>
                            <img height="60px" src="../images/<?php echo $data['info']['logo3'] ?>" alt="" id="logo3" />
                            <?php
                        }
                        ?>
                    </div>
                    <!-- /Sponsors -->

                    <!-- /SECTION 5 -->

                    <!-- SECTION 4 -->
                    <h2><?php echo $data['info']['title'] ?> </h2>
                    <div class="contact-form fx_wd_resform">
                        <!-- Contact Form -->
                        <div class="form-wraper fx_wd_resform" id="register-form">
                            <h3><?php echo $data['info']['description'] ?></h3>
                            <form id="register" name="register" class="form"  action="" method="post">
                                <div class="left_resform">
                                    <?php
                                    if ($data['info']['type'] == 1) {
                                        ?>
                                        <div class="field">
                                            <label><font style="color: black; margin-left: 8px;">Genre</font></label>
                                            <select name='civilite' class="fix_wd_select">
                                                <?php
                                                $items = array('Mlle', 'Mme', 'Mr');
                                                foreach ($items as $i) {
                                                    if ($i == $_POST['civilite'] || $i == $_GET['civilite']) {
                                                        $sel = " selected";
                                                    } else {
                                                        $sel = "";
                                                    }
                                                    echo "<option value='" . $i . "'" . $sel . ">" . $i . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="field"><label><font style="color: black; margin-left: 18px;">Nom</font> *</label><input type="text" name="nom" value="<?php echo $_POST['nom'] != '' ? $_POST['nom'] : $_GET['nom']; ?>" class="required fix_wd_txt" /></div>
                                    <div class="field"><label><font style="color: black;">Prénom</font> *</label><input type="text" name="prenom" value="<?php echo $_POST['prenom'] != '' ? $_POST['prenom'] : $_GET['prenom']; ?>" class="required fix_wd_txt" /></div>
                                    <div class="field half-size"><label><font style="color: black; margin-left: 12px;">Email</font> *</label><input type="text" name="email" value="<?php echo $_POST['email'] != '' ? $_POST['email'] : $_GET['email']; ?>" class="required email fix_wd_txt" /></div>
                                    <div style="float:right; padding-right:40px;"><b><font style="color: red;">*</font> Champs obligatoires</b></div>
                                </div>
                                <div class="right_resform">
                                    <?php
                                    if ($data['info']['type'] == 1) {
                                        ?>
                                        <div class="field"><label><font style="color: black; margin-left: 27px;">Société</font></label><input type="text" name="societe"  value="<?php echo $_POST['societe'] != '' ? $_POST['societe'] : $_GET['societe']; ?>" class="fix_wd_txt_right" /></div>
                                        <?php
                                    }
                                    ?>
                                    <div class="field"><label><font style="color: black; margin-left: 23px;">Adresse</font></label><input type="text" name="address"  value="<?php echo $_POST['address'] != '' ? $_POST['address'] : $_GET['address']; ?>" class="required fix_wd_txt_right" /></div>
                                    <div class="field"><label><font style="color: black;">Code Postal</font></label><input type="text" name="Code_Postal" value="<?php echo $_POST['Code_Postal'] != '' ? $_POST['Code_Postal'] : $_GET['Code_Postal']; ?>" class="required fix_wd_txt_right" /></div>
                                    <div class="field"><label><font style="color: black; margin-left: 46px;">Ville</font></label><input type="text" name="Ville" value="<?php echo $_POST['Ville'] != '' ? $_POST['Ville'] : $_GET['Ville']; ?>" class="required fix_wd_txt_right" /></div>
                                    <div class="field half-size"><label><font style="color: black; margin-left: 12px;">Téléphone</font></label><input type="text" name="phone" value="<?php echo $_POST['phone'] != '' ? $_POST['phone'] : $_GET['phone']; ?>" class="required fix_wd_txt_right" /></div>
                                    <div class="field"><img src="captcha/CaptchaSecurityImages.php?width=120&height=30&characters=5" /><label><font style="color: black; margin-left: -36px;">Code de securité</font> *</label><input type="text" style="width: 130px; float: left;" name="security_code" value="<?php echo $_POST['security_code'] ?>" /></div>
                                </div>
                                <div class="bt_res_form">
                                    <label><b>Conference</b> <font style="color: red;">*</font></label>
                                    <?php
                                    if (is_array($data['conference']))
                                        $count = 0;
                                    foreach ($data['conference'] as $key => $value) {
                                        if ($count == 0) {
                                            echo "<div class='row_resform_check'>";
                                        } else {
                                            echo '<div>';
                                        }
                                        echo '<input class="check_fix_res" type="checkbox" value="' . $value['group_id'] . '" name="conference[' . $value['group_id'] . ']" ';
                                        if (isset($_GET['conference'])) {
                                            if (in_array($value['group_id'], explode(",", $_GET['conference']))) {
                                                echo "checked";
                                            }
                                        }
                                        echo ' />' . str_replace($data['info']['name'] . ' >', '', $value['group_name']);
                                        echo '</div>';
                                        $count++;
                                    }
                                    ?>
                                </div>
                                <div class="clear">&nbsp;</div>
                                <div class="bt_resform" align="center">
                                    <input type="submit" name="submit" value="Enregistrer" />
                                </div>
                            </form>
                        </div>
                        <!-- /Contact Form -->
                    </div>

                    <!-- /Contact Form -->
                </div>

                <div class="clear">&nbsp;</div>

                <!-- /SECTION 4 -->
            </div>
        </div>
    </div>

    <!-- /Content Line -->

    <!-- Footer Line -->
    <!--div class="footerline">
        <div class="footerline-inner">
    
        </div>
    </div-->
    <!-- /Footer Line -->

</body>
</html>