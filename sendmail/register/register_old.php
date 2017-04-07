<?php
session_start();
require_once '../config.php';
require_once '../php/database.php';
require_once '../php/class.newsletter.php';


$newsletter = new newsletter();

if ($_POST) {
    if ($_SESSION['security_code'] != $_POST['security_code']) {
        // Insert your code for showing an error message here
        $errs[] = "Security code is not valid";
    }
    $arr = array(
        'nom' => 'Nom is require',
        'prenom' => 'Prenom is require',
        'email' => 'Email is require'
    );

    foreach ($arr as $input => $err) {
        if ($_POST[$input] == '')
            $errs[] = $err;
    }

    if ($_POST['email'] != '')
        if (!eregi("^[a-zA-Z0-9.-]*[a-zA-Z0-9]@[a-zA-Z0-9.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]*$", $_POST['email'])) {

            $errs[] = "Email is not valid";
        }

    if (!$errs) {
        $member = $newsletter->select('member', 'email', "email = '" . $_POST['email'] . "'");
        if (is_array($member) and count($member) > 0) {
            $errs[] = "Email was registed";
        } else {
            $arr = array(
                'first_name' => $_POST['prenom'],
                'last_name' => $_POST['nom'],
                'email' => $_POST['email'],
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

            foreach ($_POST['conference'] as $key => $value) {
                $a = array(
                    'member_id' => $member_id,
                    'group_id' => $key
                );
                $newsletter->insert('member_group', $a);
            }

            // send mail
            $data['member'] = $newsletter->get_member('', $member_id);
            $category = $_GET['category'];
            $data['info'] = $newsletter->select('category', '*', "name = '$category'");
            $data['info'] = current($data['info']);
            if (!is_array($data['info']) || count($data['info']) == 0) {
                echo 'No data';
                exit();
            }
            if ($data['info']['type'] == 1) {
                if ($_POST['societe'] == '')
                    $errs[] = "Société est requise";
            }
            $data['conference'] = $newsletter->select('`group`', '*', "category_name = '" . $data[info][name] . "'");

            $content = '
	    Vous êtes incrit à : <br>';
            if (is_array($data['conference']))
                foreach ($data['conference'] as $key => $value) {
                    foreach ($data['member']['groups'] as $v) {
                        if ($value['group_id'] == $v)
                            $content.= ' - ' . $value['group_name'] . '<br>';
                    }
                }
            $content .= '<br><label>Nom :</label> ' . $data['member']['first_name'] . '<br>
	    <label>Prénom :</label> ' . $data['member']['last_name'] . '<br>
	    <label>Email :</label> ' . $data['member']['email'] . '<br>
	    <label>Adresse :</label> ' . $data['member']['custom'][1]['value'] . '<br>
	    <label>Code postal :</label> ' . $data['member']['custom'][2]['value'] . '<br>
	    <label>Ville :</label> ' . $data['member']['custom'][3]['value'] . '<br>
	    <label>Téléphone :</label> ' . $data['member']['custom'][4]['value'] . '<br>
	    <label>Conférence :</label><br>';
            if (is_array($data['conference']))
                foreach ($data['conference'] as $key => $value) {
                    foreach ($data['member']['groups'] as $v) {
                        if ($value['group_id'] == $v)
                            $content.= ' - ' . str_replace($data[info][name] . ' >', '', $value['group_name']) . '<br>';
                    }
                }

            $newsletter->send_email($data['member']['email'], $data['info']['title'] . ' - ' . $data['info']['description'], $content, _MAIL_SMTP_USER, _MAIL_SMTP_USER, $options = array());
            $newsletter->send_email($data['info']['email'], $data['info']['title'] . ' - ' . $data['info']['description'], $content, _MAIL_SMTP_USER, _MAIL_SMTP_USER, $options = array());



            // end send mail


            echo '<script>window.location.href="confirmation.php?category=' . $_GET['category'] . '&member_id=' . $member_id . '";</script>';
        }
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
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Register</title>
        <link href="css/style.css" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <div id="cotainter">
            <img src="../images/<?php echo $data['info']['logo'] ?>" id="logo" style="max-height: 80px; max-width: 150px" />
            <h1><?php echo $data['info']['title'] ?></h1>
            <h2><?php echo $data['info']['description'] ?></h2>


            <form id="register" name="register" action="" method="post">
                <?php
                if (is_array($errs)) {
                    echo '<ul class="err">';
                    foreach ($errs as $v)
                        echo '<li>' . $v . '</li>';
                    echo '</ul>';
                }
                ?>
                <div id="left">
                    <label>Nom *</label> <input type="text" name="nom" value="<?php echo $_POST['nom'] ?>"  />
                    <label>Prénom *</label> <input type="text" name="prenom" value="<?php echo $_POST['prenom'] ?>"   />
                    <label>Email *</label> <input type="text" name="email" value="<?php echo $_POST['email'] ?>"   />
                    <label></label><div style="float: right; margin-top:13px; margin-right:18px">* Champs obligatoires</div>
                </div>
                <div id="right">
                    <label>Adresse </label> <input type="text" name="address"  value="<?php echo $_POST['address'] ?>"  />
                    <label>Code postal </label> <input type="text" name="Code_Postal"  value="<?php echo $_POST['Code_Postal'] ?>"  />
                    <label>Ville </label> <input type="text" name="Ville" value="<?php echo $_POST['Ville'] ?>"   />
                    <label>Téléphone </label> <input type="text" name="phone" value="<?php echo $_POST['phone'] ?>" />
                </div>
                <div id="center"  class="clear">
                    <label>Conférence</label>
                    <div id="checkbox">
                        <?php
                        if (is_array($data['conference']))
                            foreach ($data['conference'] as $key => $value) {
                                echo '<input type="checkbox" value="' . $value['group_id'] . '" name="conference[' . $value['group_id'] . ']" ';
                                if ($_POST['conference'][$value['group_id']] == $value['group_id'])
                                    echo 'checked';
                                echo ' >' . str_replace($data[info][name] . ' >', '', $value['group_name']) . '<br>';
                            }
                        ?>
                    </div>
                    <div id="checkbox">
                        <label>Security code </label>
                        <img style="float: left; margin-top: 5px" src="captcha/CaptchaSecurityImages.php?width=120&height=30&characters=5" />
                        <input name="security_code" type="text" style="margin-left:20px" />
                    </div>
                </div>
                <div align="center" class="clear"  >
                    <br />
                    <input type="submit" name="submit" value="  Enregister  " />

                </div>

                <div id="warning">
                    <?php
                    if ($data['info']['warnings'] != '') {
                        echo str_replace(chr(10), '<br>', $data['info']['warnings']);
                    }
                    ?>
                </div>

            </form>
        </div><!-- end #container-->
    </body>
</html>