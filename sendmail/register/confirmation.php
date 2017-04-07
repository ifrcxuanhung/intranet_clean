<?php
require_once '../config.php';
require_once '../php/database.php';
require_once '../php/class.newsletter.php';

$newsletter = new newsletter();
$data['member'] = $newsletter->get_member('', $_GET['member_id']);
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
        <title>Confirmation</title>
        <link href="css/style.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript">
			<!--//

			function freeSubmit(){
			if(document.forms[0][1].disabled==true) {
			  document.forms[0][1].disabled=false;
			}
			else {
			  document.forms[0][1].disabled=true;
			 }
			}

			//-->
		</script>
    </head>

    <body>
        <div id="cotainter">
            <img src="../images/<?php echo $data['info']['logo'] ?>" id="logo" style="max-height: 80px; max-width: 150px" />
            <h1><?php echo $data['info']['title'] ?></h1>
            <h2><?php echo $data['info']['description'] ?></h2>


            <form id="register" name="register" action="" method="post">
                <?php 			
				if ($data['info']['type'] == 1)
				{
                ?>
				<label>Civilité :</label> <div id="text"><?php echo $data['member']['civilite']?></div>
				<?php
				}
				?>
				<label>Nom :</label> <div id="text"><?php echo $data['member']['first_name']?></div>
                <label>Prénom :</label> <div id="text"><?php echo $data['member']['last_name']?></div>
                <label>Email :</label> <div id="text"><?php echo $data['member']['email']?></div>
                 <?php 			
				if ($data['info']['type'] == 1)
				{
                ?>
				<label>Société :</label> <div id="text"><?php echo $data['member']['societe']?></div>
				<?php
				}
				?>
				<label>Adresse :</label> <div id="text"><?php echo $data['member']['custom'][1]['value']?></div>
                <label>Code postal :</label> <div id="text"><?php echo $data['member']['custom'][2]['value']?></div>
                <label>Ville :</label> <div id="text"><?php echo $data['member']['custom'][3]['value']?></div>
                <label>Téléphone :</label> <div id="text"><?php echo $data['member']['custom'][4]['value']?></div>
                <label>Conférence :</label>
                <div id="text">
                    <?php
                    if (is_array($data['conference']))
                        foreach ($data['conference'] as $key => $value) {
                            foreach($data['member']['groups'] as $v){
                                if($value['group_id'] == $v)
                                echo '- '. str_replace($data[info][name] . ' >', '', $value['group_name']) . '<br>';
                            }
                        }
                    ?>
                </div>
                
                <div style="float: left; width: 100%; margin-top: 10px" align="center">Nous confirmons votre INSCRIPTION</div>
                
            </form>
        </div><!-- end #container-->
    </body>
</html>