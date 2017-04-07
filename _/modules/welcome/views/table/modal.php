
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
<script type="text/javascript">
$(document).on('click', '.upload_image_multi', function(e){	
var id = $(this).attr("id");
var splits = id.slice(6);

    window.KCFinder = {
        callBack: function(url) {
            window.KCFinder = null;
            
            //var div = $('div.thumbnail');
             //console.log(divs);
           //var div = divs.parents('div');
            
            //div.innerHTML = '<div style="margin:5px">Loading...</div>';
            var img = new Image();
            img.src = url;
            //divs.val(url);
            //console.log(divs.child('fileinput-exists').find('input').val(url));
            //$('#'+field).attr('value',url);
            img.onload = function() {
                //$('#'+id+' > .avatar').attr('value',url.replace($base_url,''));
				 $(".avatar_"+splits).attr('value',url.replace($base_url,''));
                //divs.innerHTML = '<input name="'+field+'" id="'+field+'" value="'+url+'" >';
                //$('#'+id+' > #img').attr('src',url);
				$(".img_upload_"+splits).attr('src',url);
                var img = document.getElementById('img');
                var div = $('div.thumbnail');
                //var img = $('div.thumbnail').find('#img');
                var o_w = img.offsetWidth;
                var o_h = img.offsetHeight;
                var f_w = div.offsetWidth;
                var f_h = div.offsetHeight;
                if ((o_w > f_w) || (o_h > f_h)) {
                    if ((f_w / f_h) > (o_w / o_h))
                        f_w = parseInt((o_w * f_h) / o_h);
                    else if ((f_w / f_h) < (o_w / o_h))
                        f_h = parseInt((o_h * f_w) / o_w);
                    img.style.width = f_w + "px";
                    img.style.height = f_h + "px";
                } else {
                    f_w = o_w;
                    f_h = o_h;
                }
                img.style.marginLeft = parseInt((div.offsetWidth - f_w) / 2) + 'px';
                img.style.marginTop = parseInt((div.offsetHeight - f_h) / 2) + 'px';
                img.style.visibility = "visible";
            }
        }
    };
    window.open($base_url+'assets/bundles/kcfinder/browse.php?type=images&dir=images/public',
        'kcfinder_image', 'status=0, toolbar=0, location=0, menubar=0, ' +
        'directories=0, resizable=1, scrollbars=0, width=800, height=600'
    );
});
</script>