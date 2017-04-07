<?php

require_once '../config.php';
require_once '../php/database.php';
require_once '../php/class.newsletter.php';

$newsletter = new newsletter();

if($_POST['nom']){

    $arr = array(
        'nom' => 'Nom is require',
        'prenom' => 'Prenom is require',
        'email' => 'Email is require'
    );

    foreach($arr as $input => $err){
        if($_POST[$input] == '')
            $errs[] = $err;
    }

    if(!$errs){
        $arr = array(
            'first_name' => $_POST['nom'],
            'last_name' => $_POST['prenom'],
            'email' => $_POST['email'],
            'join_date' => date('Y-m-d')            
        );
        $member_id = $newsletter->insert('member',$arr);

        $arr = array(            
            'address' => '1',
            'Code_Postal' => '2',
            'Ville' => '3',
            'phone' => '4'
        );

        foreach($arr as $key => $value){
            $a = array(
                'member_id' => $member_id,
                'member_field_id' => $value,
                'value' => $_POST[$key]
            );
            $newsletter->insert('member_field_value',$a);
        }

        foreach($_POST['conference'] as $key => $value){
            $a = array(
                'member_id' => $member_id,
                'group_id' => $key
            );
            $newsletter->insert('member_group',$a);
        }
        
    }
}


$category = $_GET['category'];
$data['info'] = $newsletter->select('categories','*',"name = '$category'");
$data['info'] = current($data['info']);

if(!is_array($data['info']) || count($data['info']) == 0){    
    echo 'No data';
    exit();
}

$data['conference'] = $newsletter->select('`group`','*',"category_name = '".$data[info][name]."'");

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
            <img src="../images/<?php echo $data['info']['logo']?>" id="logo" style="max-height: 80px; max-width: 150px" />
            <h1><?php echo $data['info']['title']?></h1>
            <h2><?php echo $data['info']['description']?></h2>
            <form id="register" name="register" action="" method="post">
                <div id="left">
                    <label>Nom *</label> <input type="text" name="nom"  />
                    <label>Prénom *</label> <input type="text" name="prenom"  />
                    <label>Email *</label> <input type="text" name="email"  />                    
                    <label>Conference</label>
                    <div>
                        <?php
                        
                        if(is_array($data['conference']))
                        foreach ($data['conference'] as $key => $value) {
                            echo '<input style="margin-left:10px" type="checkbox" value="'.$value['group_id'].'" name="conference['.$value['group_id'].']">
                                <span style="color:#999999; ">'.str_replace($data[info][name].' >', '', $value['group_name']).'</span><br>';
                        }
                        ?>
                    </div>
                </div>
                <div id="right">
                    <label>Adresse </label> <input type="text" name="address"  />
                    <label>Code Postal </label> <input type="text" name="Code_Postal"  />
                    <label>Ville </label> <input type="text" name="Ville"  />
                    <label>Téléphone </label> <input type="text" name="phone"  />
                </div>
                <div style="clear:both; float:right; margin-right:20px; margin-top:10px; width: 80px;">
                    <input type="image" src="images/bt_submit.png" name="submit" />
                </div>
            </form>
        </div><!-- end #container-->
    </body>
</html>
