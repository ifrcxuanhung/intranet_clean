<?php
$data = $newsletter->select('category');
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
            echo "<li><a href='?p={$_GET["p"]}'>";
            echo ucwords(str_replace("_", " ", $_GET["p"]));
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
                <h4><i class="icon-reorder"></i>List Categories | <a class="cl_fx_hrf" href="index.php?p=category_add">Add New</a></h4>
            </div>
            <table class="table table-striped table-bordered datatable" style="clear: both;">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Logo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data as $key => $value) {
                        ?>
                        <tr>
                            <td><?php echo $value['name'] ?></td>
                            <td><?php echo $value['title'] ?></td>
                            <td><?php echo $value['description'] ?></td>
                            <td style="width: 200px;">
                                <?php
                                if ($value['logo'] != '') {
                                    echo "<img height='15' src='images/" . $value['logo'] . "' />&nbsp;&nbsp;";
                                }
                                if ($value['logo2'] != '') {
                                    echo "<img height='15' src='images/" . $value['logo2'] . "' />&nbsp;&nbsp;";
                                }
                                if ($value['logo3'] != '') {
                                    echo "<img height='15' src='images/" . $value['logo3'] . "' />";
                                } else if ($value['logo'] == '' && $value['logo2'] == '' && $value['logo3'] == '') {
                                    echo "<img height='15' src='images/no-image.jpg' />";
                                }
                                ?>
                            </td>
                            <td>
                                <a href="index.php?p=category_edit&id=<?php echo $value['id'] ?>" class="icon-button edit">Edit</a> |
                                <a style="cursor: pointer;" class="icon-button delete" onclick="if(confirm('Delete this group?')){ window.location.href='index.php?p=category_del&id=<?php echo $value['id'] ?>'; }">Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>