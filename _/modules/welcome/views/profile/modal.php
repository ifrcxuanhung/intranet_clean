<div class="modal-header" style="background-color: #E4AD36;">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
      <h4 class="modal-title"><?php echo trans('edit'); ?></h4>
</div>
<form id="form_view_user" role="form" class="form-horizontal" action="" method="post">
    <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-body">
                <div class="form-group">
                  <label class="col-md-3 control-label"><?php echo $field; ?>: </strong></td> </label>
                  <div class="col-md-9">
                        <?php echo $html_code; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn default" data-dismiss="modal"><?php echo trans('Cancel'); ?></a>
        <a href="javascript:;" class="btn green save_edit" data-table="<?php echo $table ?>" data-key="<?php echo $id ?>" data-field="<?php echo $field ?>"><?php echo trans('Save'); ?></a>
    </div>
</form>