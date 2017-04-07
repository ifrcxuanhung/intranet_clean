<?php
$data = $newsletter->select('category', '*', 'id = ' . $_GET['id']);
$data = $data[$_GET['id']];

if ($_POST['ok']) {

    $arr = array(
        'name' => 'Name is require',
        'title' => 'Title is require'
    );

    foreach ($arr as $input => $err) {
        if ($_POST[$input] == '')
            $errs[] = $err;
    }
    print_r($_FILES);
    if (!$errs) {
        $arr = array(
            'name' => $_POST['name'],
            'title' => $_POST['title'],
            'email' => $_POST['email'],
            'warnings' => addslashes($_POST['warnings']),
            'description' => addslashes($_POST['description'])
        );

        if ($_FILES['logo']['name'] != '') {
            //replace all space with -
            $_FILES['logo']['name'] = str_replace(" ", "-", $_FILES['logo']['name']);
            $arr['logo'] = $_FILES['logo']['name'];
            unlink('images/' . $data['logo']);
            move_uploaded_file($_FILES['logo']['tmp_name'], 'images/' . $_FILES['logo']['name']);
        }
        if ($_FILES['logo2']['name'] != '') {
            //replace all space with -
            $_FILES['logo2']['name'] = str_replace(" ", "-", $_FILES['logo2']['name']);
            $arr['logo2'] = $_FILES['logo2']['name'];
            unlink('images/' . $data['logo2']);
            move_uploaded_file($_FILES['logo2']['tmp_name'], 'images/' . $_FILES['logo2']['name']);
        }
        if ($_FILES['logo3']['name'] != '') {
            //replace all space with -
            $_FILES['logo3']['name'] = str_replace(" ", "-", $_FILES['logo3']['name']);
            $arr['logo3'] = $_FILES['logo3']['name'];
            unlink('images/' . $data['logo3']);
            move_uploaded_file($_FILES['logo3']['tmp_name'], 'images/' . $_FILES['logo3']['name']);
        }
        $newsletter->update('category', $arr, 'id = ' . $_GET['id']);
        header("location:index.php?p=category_list");
    }
}
if ($_POST['logo']) {
    $arr = array(
        'logo' => ''
    );
    unlink('images/' . $data['logo']);
    $newsletter->update('category', $arr, 'id = ' . $_GET['id']);
    header("location:index.php?p=category_list");
}
if ($_POST['logo2']) {
    $arr = array(
        'logo2' => ''
    );
    unlink('images/' . $data['logo2']);
    $newsletter->update('category', $arr, 'id = ' . $_GET['id']);
    header("location:index.php?p=category_list");
}
if ($_POST['logo3']) {
    $arr = array(
        'logo3' => ''
    );
    unlink('images/' . $data['logo3']);
    $newsletter->update('category', $arr, 'id = ' . $_GET['id']);
    header("location:index.php?p=category_list");
}
?>
<!-- start main_content -->
<div class="main_content">
    <div class="title_head_ct">
        <h2>Newsletter Categories</h2>
        <!-- start breadcrumb -->
        <?php
        if (isset($_GET["p"]) && $_GET["p"] != "home") {
            echo "<ul class='breadcrumb'>";
            echo "<li><a href='?p=home'><i class='icon-home'></i></a><span class='divider'>&nbsp;</span></li>";
            echo "<li><a href='?p=category_list'>";
            echo ucwords(str_replace("_", " ", "category_list"));
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
                <h4><i class="icon-reorder"></i>Edit Category</h4>
            </div>
            <div class="widget-body">
                <?php
                if (is_array($errs)) {
                    echo '<div class="info_com">';
                    echo '<ul style="color: red;">';
                    foreach ($errs as $value) {
                        echo '<li>' . $value . '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }
                ?>
                <form action="" method="post" id="create_form" enctype="multipart/form-data">
                    <table class="table table-hover" style="clear: both;">
                        <tr>
                            <td>
                                Name
                            </td>
                            <td>
                                <input type="text" name="name" style="width: 400px;" value="<?php echo $data['name']; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Logo 1
                                <?php
                                if ($data['logo'] != '') {
                                    echo " | ";
                                    echo "<input type='submit' style='cursor: pointer;' name='logo' value='Clear Image' />";
                                }
                                ?>
                            </td>
                            <td>
                                <img height="50" src="images/<?php echo ($data['logo'] != '') ? $data['logo'] : 'no-image.jpg'; ?>" title="Logo 1" >
                                <br>
                                <input size="65" type="file" name="logo">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Logo 2
                                <?php
                                if ($data['logo2'] != '') {
                                    echo " | ";
                                    echo "<input type='submit' style='cursor: pointer;' name='logo2' value='Clear Image' />";
                                }
                                ?>
                            </td>
                            <td>
                                <img height="50" src="images/<?php echo ($data['logo2'] != '') ? $data['logo2'] : 'no-image.jpg'; ?>" title="Logo 2">
                                <br>
                                <input size="65" type="file" name="logo2">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Logo 3
                                <?php
                                if ($data['logo3'] != '') {
                                    echo " | ";
                                    echo "<input type='submit' style='cursor: pointer;' name='logo3' value='Clear Image' />";
                                }
                                ?>
                            </td>
                            <td>
                                <img height="50" src="images/<?php echo ($data['logo3'] != '') ? $data['logo3'] : 'no-image.jpg'; ?>" title="Logo 3">
                                <br>
                                <input size="65" type="file" name="logo3">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Title
                            </td>
                            <td>
                                <input type="text" name="title" style="width: 400px;" value="<?php echo $data['title'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Description
                            </td>
                            <td>
                                <textarea name="description" cols="50" rows="2"><?php echo $data['description'] ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Email
                            </td>
                            <td>
                                <textarea name="email" cols="50" rows="2"><?php echo $data['email'] ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Warnings
                            </td>
                            <td>
                                <textarea name="warnings" cols="50" rows="5"><?php echo $data['warnings']; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>

                            </td>
                            <td>
                                <input type="submit" style="cursor: pointer;" name="ok" value="Save"/>
                            </td>
                        </tr>
                    </table>
                </form>