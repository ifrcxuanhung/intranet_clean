
<form enctype="multipart/form-data" method="POST"  id="check_modal" class="form-horizontal" action=""> 
<div class="modal-header" style="background-color: #44b6ae; color: #fff">
	<div class="modal-title">
        <div class="caption">
            <?php echo $name_desc; ?>
        </div>
        <div class="tools">
           <button type="button" class="btn blue save-modal">Save changes</button>
           <button type="button" class="btn red" data-dismiss="modal">Close</button>
        </div>
    </div>
	
    
</div>
    <div class="modal-body">
    	<div class="row">
    		<div class="col-md-12">
                <div class="form-body">
                    <div class="alert alert-danger display-hide" style="display: none;">
                        <button data-close="alert" class="close"></button>
                        D? li?u hi?n t?i không dúng!
                    </div>
                    <div class="alert alert-success display-hide" style="display: none;">
                        <button data-close="alert" class="close"></button>
                        Luu thành công!
                    </div>
                    <div class="form-body">
                    <?php
                        foreach ($headers as $item) { ?>
                        <div class="form-group">
							<label class="col-md-3 control-label text-uppercase"><strong><?php echo $item['Field'] ?></strong></label>
							
								<?php echo $item['filter']?>
							
						</div>
                    <?php } ?>
                    </div>	
                 </div>
    		</div>
    	</div>
    </div>
    <div class="modal-footer">    	
    	<button type="button" class="btn blue save-modal">Save changes</button>
        <button type="button" class="btn red" data-dismiss="modal">Close</button>
        <input type="hidden" id="table_name_parent" name="table_name_parent" value="<?php echo $name_table ?>" />
        <input type="hidden" id="keys_value" name="keys_value" value="<?php echo $keys ?>" />
    </div>
</form>

<style>

.date-picker { z-index: 1151 !important;  }

</style>
<script>
//$('.ckeditor').each(function(index, value){
//	CKEDITOR.replace( $(this).attr('name'), {
//		height : 150,
//		colorButton_foreStyle : {
//			element: 'font',
//			attributes: { 'color': '#(color)' }
//		},
//		colorButton_backStyle : {
//			element: 'font',
//			styles: { 'background-color': '#(color)' }
//		}
//	});
//});
</script>