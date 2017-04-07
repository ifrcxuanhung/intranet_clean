<?php  
if(!$this->session->userdata('user_id')){
			redirect(base_url().'start');	
}?>
<div class="page-content">
	<div class="col-md-12">
        <div class="alert alert-success fade in" style="display: none;">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
        </div>
        <div class="alert alert-danger fade in" style="display: none;">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
        </div>
        
        
        
        
     
    </div>
    <div class="row" >
        <div class="col-md-12 col-sm-12">
            <div class="table-group-actions pull-right">
            <?php if($table == 'ifrc_articles'){?>
				<a class="btn btn-sm blue add-modal add-articles-order" href="<?php echo base_url() ?>ajax/add_modal_intranet/" keys="" table_name="<?php echo $table; ?>">
				    <i class="fa fa-plus"></i> Add </a>
            <?php }else{?>
            	<a class="btn btn-sm blue show-modal" href="javascript:;" type-modal="insert" keys="" table_name="<?php echo $table; ?>"  data-target="#modal" data-toggle="modal">
				    <i class="fa fa-plus"></i> <?php echo trans('add') ?> </a>
           	<?php }?>
                <?php if($table=='documents'){ ?>
                <a class="btn btn-sm green" href="<?php echo base_url().'portfolio/'.$table ?>"><i class="fa fa-camera"></i><?php echo trans('Portfolio') ?></a>
                <?php } ?>
                <?php if($table=='query'){ ?>
                <a class="btn btn-sm green runquery" href="javascript:;"><i class="fa fa-play"></i> <?php echo trans('Run Query') ?></a>
                <?php } ?>
                <?php if($table=='data_report'){ ?>
                <a class="btn btn-sm green runDataReport" href="javascript:;"><i class="fa fa-play"></i> <?php echo trans('Run Data Report') ?></a>
                <?php } ?>
                <button class="btn btn-sm green exportTxt" >
                        TXT 
                    </button>
                 <button class="btn btn-sm red exportCsv" >
                        CSV 
                    </button> 
                <button class="btn btn-sm yellow exportXls" >
                        Excel 
                </button>
            </div>
        </div>
    </div>
	<div class="row" id="blockui_sample_4_portlet_body">
        <form id="form_tab" action ="" method="post">
        <div class="col-md-12 col-sm-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->    
    		<table class="table table-striped table-bordered table-hover dataTable" id="table" name="<?php echo $table ?>" category="<?php echo $category?>">
               <thead>
					<tr role="row" class="heading">
                        <?php if($table == 'query' || $table == 'data_report' ){ ?>
                        <th width="2%">
							<input type="checkbox" class="group-checkable" data-set="#table .checkboxes">
						</th>
                        <?php } ?>
						<?php
						$colWidth = 0;
						$colNoWidth = 0;
						$sumWidth = 0;
						$sum = 0;
						foreach ($headers as $item) {
							if(is_numeric($item['width']) && ($item['width'] >=0)) {
								$colWidth ++;
								$sumWidth += $item['width'];
							}
							else {
								$colNoWidth ++;
							}
							$sum ++;
						}
						$divWidth = $colWidth/$sum*90;
						$divNoWidth = $colNoWidth / $sum * 90;
                        $i=0;
						//echo "<pre>";print_r($headers);exit;
						foreach ($headers as $item) {
						  $i++;
							switch($item['align']) {
								case 'L':
									$align = ' class="align-left '.($item['hide_mobile'] == '1' ? 'hidden-sm hidden-xs' : '') .'" ';;
									break;
								case 'R':
									$align = ' class="align-right '.($item['hide_mobile'] == '1' ? 'hidden-sm hidden-xs' : '') .'" ';;
									break;
								default:
									$align = ' class="align-center '.($item['hide_mobile'] == '1' ? 'hidden-sm hidden-xs' : '') .'" ';;
									break;
							}
							$width = (is_numeric($item['width']) && ($item['width'] >=0)) ? ($item['width'] / $sumWidth * $divWidth) : (1/$colNoWidth * $divNoWidth);
							echo "<th width='".$width."%'" .$align. ($item["hide_mobile"] == '1' ? "data-hide='hidden-sm hidden-xs'" : "") ."><b data-toggle='tooltip' data-placement='". ($i < 3 ? "right" : "top") ."' title='".(isset($item["tips_en"]) ? strip_tags($item["tips_en"]): '' )."'><font color='#fff'>".trans($item['title'],TRUE)."</font></b></th>";
						}
						?>
						<th class="align-center" width="10%"><font color="#fff"><?php trans('Action') ?></font></th>
					</tr>
					<tr role="row" class="filter">
                        <?php if($table == 'query' || $table == 'data_report'){ ?>
                        <td></td>
                        <?php } ?>
						<?php
						foreach ($headers as $item) {
							echo '<td '.($item['hide_mobile'] == '1' ? 'class="hidden-sm hidden-xs"' : '') .'>';
							echo $item['filter'];
							echo '</td>';
						}
						?>
						<td>
                            <center>
							<div class="margin-bottom-5">
							<button class="btn btn-icon-only yellow filter-submit margin-bottom"><i class="fa fa-search"></i></button>
							<button class="btn btn-icon-only red filter-cancel"><i class="fa fa-times"></i></button> </div></center>
						</td>
					</tr>
				</thead>
                <tbody style="font-size: 13px !important;"></tbody>
            </table>
             <input type="hidden" id="text_note" name="text_note" value="<?php echo $text_note; ?>" />
             <input type="hidden"  value="<?php echo $value_filter?>" id="value_filter" name="value_filter" />
             <input type="hidden" value="<?php echo isset($iDisplayStart) ? $iDisplayStart : 0  ?>" name="iDisplaystart" id="iDisplaystart" />
             <input type="hidden" value="" name="actexport" id="actexport" />
             <input type="hidden" value="<?php echo $table ?>" name="table_name_export" id="table_name_export" />
        </div>
        </form>
    </div>
	<!-- END EXAMPLE TABLE PORTLET-->
    <?php if($table=='websites'){ ?>
    <div class="row">
        <div class="col-md-12 align-right margin-top-10">
               <a class="btn btn-xs default btn-local" disabled="">Local</a>
               <a class="btn btn-xs default btn-linux" disabled="">Linux</a>
               <a class="btn btn-xs default btn-vps" disabled="">VPS</a>
               <a class="btn btn-xs default btn-server" disabled="">Server</a>
               <a class="btn btn-xs default btn-1and1" disabled="">1 and 1</a>
        </div>
    </div>
    <?php } ?>
</div>
<style type="text/css">
	.fancybox-custom .fancybox-skin {
		box-shadow: 0 0 50px #222;
	}
    .no-sort::after { display: none!important; }
</style>

