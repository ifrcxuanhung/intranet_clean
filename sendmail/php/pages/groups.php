<?php
if ($_REQUEST['save'] && $_REQUEST['group_name']) {

    $group_id = $newsletter->save_group($db, $_REQUEST['group_id'], $_REQUEST['group_name'], $_REQUEST['category_id'], $_REQUEST['public'], $_REQUEST['type']);
    if ($group_id) {
        ob_end_clean();
        header("Location: index.php?p=groups");
        exit;
    }
}
if ((int) $_REQUEST['delete']) {

    $newsletter->delete_group($db, (int) $_REQUEST['delete']);
    ob_end_clean();
    header("Location: index.php?p=groups");
    exit;
}

$data['categories'] = $newsletter->select('category');
?>
<!-- start main_content -->
<div class="main_content">
    <div class="title_head_ct">
        <h2>Groups</h2>
        <!-- start breadcrumb -->
        <?php
        if (isset($_GET["p"]) && $_GET["p"] != "home") {
            echo "<ul class='breadcrumb'>";
            echo "<li><a href='?p=home'><i class='icon-home'></i></a><span class='divider'>&nbsp;</span></li>";
            echo "<li><a href='?p={$_GET["p"]}'>";
            echo ucwords(str_replace("_", " ", $_GET["p"]));
            echo "</a><span class='divider-last'>&nbsp;</span></li>";
            echo "</ul>";
        }
        ?>
        <!-- end breadcrumb -->
        <div class="title_hd">
            <p>This allows you to group your subscribers into different categories. 
                When you send out a newsletter, you can pick which group to send to. </p>
        </div>

    </div>
    <!-- start main content table -->
    <div class="grid_9">
        <div class="widget">
            <div class="widget-title">
                <h4><i class="icon-reorder"></i>Edit Group</h4>
            </div>
            <div class="widget-body">
                <form action="?p=groups&save=true" method="post" id="create_form">
                    <?php
                    if ($_REQUEST['edit_group_id']) {
                        $group_id = (int) $_REQUEST['edit_group_id'];
                        $group = $newsletter->get_group($db, $group_id);
                        ?>

                        <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                            <table class="table table-hover datatable" style="clear: both;">
                                <tr>
                                    <td>
                                        Category
                                    </td>
                                    <td>
                                        <select name="category_id" style="width: 412px;">
                                            <option value="">Select Categories</option>
                                            <?php
                                            if (is_array($data['categories']))
                                                foreach ($data['categories'] as $value) {
                                                    echo '<option value="' . $value['name'] . '" ';
                                                    if ($value['name'] == $group['category_name'])
                                                        echo 'selected';
                                                    echo ' >' . $value['name'] . '</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Group Name
                                    </td>
                                    <td>
                                        <input type="text" name="group_name" class="input_wd" value="<?php echo $group['group_name']; ?>"> 
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Public
                                    </td>
                                    <td>
                                        <input type="checkbox" name="public" value="1" <?php echo ($group['public']) ? ' checked' : ''; ?>> Public users can join this group
                                    </td>
                                </tr>
                                <tr>
                                    <td>

                                    </td>
                                    <td>
                                        <input type="submit" style="cursor: pointer;" name="save" value="Save">
                                    </td>
                                </tr>
                            </table>
                        <?php
                    }else {
                        ?>
                        <div class="widget-body">
                            <table class="table table-hover datatable" style="clear: both;">
                                <thead>
                                    <tr>
                                        <th>Group Name</th>
                                        <th>Public</th>
                                        <th>Members</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $groups = $newsletter->get_groups($db);
                                    foreach ($groups as $group) {
                                        $members = $newsletter->get_members($db, $group['group_id']);
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $group['group_name']; ?>
                                            </td>
                                            <td>
                                                <?php echo ($group['public']) ? 'Yes' : 'No'; ?>
                                            </td>
                                            <td>
                                                <?php echo mysql_num_rows($members); ?>
                                            </td>
                                            <td>
                                                <a href="?p=groups&edit_group_id=<?php echo $group['group_id']; ?>" class="icon-button edit">Edit</a> <span>|</span>
                                                <a style="cursor: pointer;" onclick="if(confirm('Delete this group?')){ window.location.href='?p=groups&delete=<?php echo $group['group_id']; ?>'; }" class="icon-button delete">Delete</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
            <div class="widget">
                <div class="widget-title">
                    <h4><i class="icon-reorder"></i>Add New Group</h4>
                </div>
                <div class="widget-body">
                    <table class="table table-hover" style="clear: both;">
                        <input type="hidden" name="group_id" value="new" />
                        <tr>
                            <td>
                                Category
                            </td>
                            <td>
                                <select name="category_id" id="category_id" style="width: 412px;">
                                    <option value="">Select Categories</option>
                                    <?php
                                    if (is_array($data['categories']))
                                        foreach ($data['categories'] as $value)
                                            echo '<option value="' . $value['name'] . '">' . $value['name'] . '</option>';
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Group Name
                            </td>
                            <td>
                                <input type="text" name="group_name" id="group_name" class="input_wd" value="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Public
                            </td>
                            <td>
                                <input type="checkbox" name="public" id="public" value="1"> Public users can join this group
                            </td>
                        </tr>
                        <tr>
                            <td>

                            </td>
                            <td>
                                <input type="submit" style="cursor: pointer;" name="save" value="Add">
                            </td>
                        </tr>
                    </table>
                </div>
            <?php } ?>
            </form>
        </div>
    </div>
</div>
</div>