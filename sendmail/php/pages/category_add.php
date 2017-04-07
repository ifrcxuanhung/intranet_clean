<?php
if ($_POST['ok']) {

    $arr = array(
        'name' => 'Name is require',
        'title' => 'Title is require'
    );

    foreach ($arr as $input => $err) {
        if ($_POST[$input] == '')
            $errs[] = $err;
    }

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
            move_uploaded_file($_FILES['logo']['tmp_name'], 'images/' . $_FILES['logo']['name']);
        }
        if ($_FILES['logo2']['name'] != '') {
            //replace all space with -
            $_FILES['logo2']['name'] = str_replace(" ", "-", $_FILES['logo2']['name']);
            $arr['logo2'] = $_FILES['logo2']['name'];
            move_uploaded_file($_FILES['logo2']['tmp_name'], 'images/' . $_FILES['logo2']['name']);
        }
        if ($_FILES['logo3']['name'] != '') {
            //replace all space with -
            $_FILES['logo3']['name'] = str_replace(" ", "-", $_FILES['logo3']['name']);
            $arr['logo3'] = $_FILES['logo3']['name'];
            move_uploaded_file($_FILES['logo3']['tmp_name'], 'images/' . $_FILES['logo3']['name']);
        }
        $newsletter->insert('category', $arr);
        header("location:index.php?p=category_list");
    }
}
?>
<div class="main_content">
<h2><span>Add New Category</span></h2>
<?php
if (is_array($errs)) {
    echo '<ul style="color:red">';
    foreach ($errs as $value)
        echo '<li>' . $value . '</li>';
    echo '</ul>';
}
?>
<form action="" method="post" id="create_form" enctype="multipart/form-data">
    <table cellpadding="5">
        <tr>
            <td>
                Name
            </td>
            <td>
                <input type="text" name="name" class="input_wd" value="<?php echo $_POST['name']; ?>" >
            </td>
        </tr>
        <tr>
            <td>
                Logo 1
            </td>
            <td>

                <input size="100" type="file" name="logo">
            </td>
        </tr>
        <tr>
            <td>
                Logo 2
            </td>
            <td>

                <input size="100" type="file" name="logo2">
            </td>
        </tr>
        <tr>
            <td>
                Logo 3
            </td>
            <td>

                <input size="100" type="file" name="logo3">
            </td>
        </tr>
        <tr>
            <td>
                Title
            </td>
            <td>
                <input type="text" name="title" class="input_wd" value="<?php echo $_POST['title']; ?>">
            </td>
        </tr>
        <tr>
            <td>
                Description
            </td>
            <td>
                <textarea name="description" cols="50" rows="2"><?php echo $_POST['description']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                Email
            </td>
            <td>
                <input type="text" name="email" class="input_wd" value="<?php echo $_POST['email']; ?>">
            </td>
        </tr>
        <tr>
            <td>
                Warnings
            </td>
            <td>
                <textarea name="warnings" cols="50" rows="5"><?php echo $_POST['warnings']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>

            </td>
            <td>
                <input type="submit" name="ok" value="Save"/>
            </td>
        </tr>
    </table>
</form>
</div>