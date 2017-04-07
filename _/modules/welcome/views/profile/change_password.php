<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?php echo trans('change_password') ?></h4>
</div>
<form id="change_password" role="form" class="form-horizontal" action="" method="post">
    <div class="modal-body">
        <div class="scroller" style="height:250px" data-always-visible="1" data-rail-visible1="1">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-body">
                        <div class="alert alert-danger display-hide" style="display: none;">
                            <button data-close="alert" class="close"></button>
                            <?php echo trans('Your password incorrect !') ?>
                        </div>
                        <div class="alert alert-success display-hide" style="display: none;">
                            <button data-close="alert" class="close"></button>
                            <?php echo trans('Your password saved !') ?>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php echo trans('Present_password') ?></label>
                            <div class="col-md-8">
                                <input type="password" id="old_password" name="old_password" placeholder="<?php echo trans('Present_password') ?>" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php echo trans('New_password') ?></label>
                            <div class="col-md-8">
                                <input type="password" id="new_password" name="new_password" placeholder="<?php echo trans('New_password') ?>" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"><?php echo trans('Confirm_new_password') ?></label>
                            <div class="col-md-8">
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="<?php echo trans('Confirm_new_password') ?>" class="form-control"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="clear-change btn default" data-dismiss="modal"><?php echo trans('Cancel') ?></a>
        <input type="submit" class="btn green" value="<?php echo trans('save') ?>"/>
    </div>
</form>