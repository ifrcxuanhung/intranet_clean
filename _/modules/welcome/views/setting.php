<?php  
if(!$this->session->userdata('user_id')){
			redirect(base_url().'start');	
}?><!-- BEGIN PAGE HEADER-->
<h3 class="page-title"  style="font-weight: bold;"><?php trans('Derivatives Exchange - Setting'); ?></h3>
<div class="row-fluid">
	<div class="span12">
		<!-- END PAGE TITLE & BREADCRUMB-->
        <?php echo $hightlights; ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
        <div class="alert alert-success fade in" style="display: none;">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button>
        </div>
    </div>
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>Controls
                </div>
                <div class="tools">
                    <a class="collapse" href="javascript:;" data-original-title="" title="">
                    </a>
                    <!--a class="config" data-toggle="modal" href="#portlet-config" data-original-title="" title="">
                    </a-->
                </div>
            </div>
            <div class="portlet-body tabs-below">
                <div class="row">
                    <div class="col-md-12">
                    <table id="vdm_underlying_setting" class="table dataTable table-striped table-bordered table-hover table-full-width" name="vdm_underlying_setting">
                        <thead>
                            <tr role="row" class="heading">
                            <?php
                            $colWidth = 0;
                            $colNoWidth = 0;
                            $sumWidth = 0;
                            $sum = 0;
                            foreach ($headers_opt as $item) {
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
                            foreach ($headers_opt as $item) {
                                switch($item['align']) {
                                        case 'L':
                                            $align = ' class="align-left"';
                                            break;
                                        case 'R':
                                            $align = ' class="align-right"';
                                            break;
                                        default:
                                            $align = ' class="align-center"';
                                            break;
                                    }
                                    $width = (is_numeric($item['width']) && ($item['width'] >=0)) ? ($item['width'] / $sumWidth * $divWidth) : (1/$colNoWidth * $divNoWidth);
                                    echo '<th width="'.$width.'%"' .$align.'>'.trans($item['title'],TRUE).'</th>';
                            }
                            ?>
                            <th class="align-center" width="10%"><?php trans('Action') ?></th>
                        </tr>
                        <tr role="row" class="filter">
                            <?php
                            foreach ($headers_opt as $item) {
                                echo '<td>';
                                echo $item['filter'];
                                echo '</td>';
                            }
                            ?>
                            <td>
                                <center>
                                <div class="margin-bottom-5">
                                <button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i></button>
                                <button class="btn btn-sm red filter-cancel"><i class="fa fa-remove"></i></button> </div></center>
                            </td>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>
 <div class="row">
    <div class="col-md-12">
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>Content
                </div>
                <div class="tools">
                    <a class="collapse" href="javascript:;" data-original-title="" title="">
                    </a>
                    <!--a class="config" data-toggle="modal" href="#portlet-config" data-original-title="" title="">
                    </a-->
                </div>
            </div>
            <div class="portlet-body tabs-below">
                <div class="row">
                    <div class="col-md-12">
                    <table id="vdm_contracts_setting_exc" class="table dataTable table-striped table-bordered table-hover table-full-width" name="vdm_contracts_setting_exc">
                        <thead>  
                            <tr role="row" class="heading">
                                <?php
                                $colWidth = 0;
                                $colNoWidth = 0;
                                $sumWidth = 0;
                                $sum = 0;
                                foreach ($headers_fut as $item) {
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
                                foreach ($headers_fut as $item) {
                                    switch($item['align']) {
                                            case 'L':
                                                $align = ' class="align-left"';
                                                break;
                                            case 'R':
                                                $align = ' class="align-right"';
                                                break;
                                            default:
                                                $align = ' class="align-center"';
                                                break;
                                        }
                                        $width = (is_numeric($item['width']) && ($item['width'] >=0)) ? ($item['width'] / $sumWidth * $divWidth) : (1/$colNoWidth * $divNoWidth);
                                        echo '<th width="'.$width.'%"' .$align.'>'.trans($item['title'],TRUE).'</th>';
                                }
                                ?>
                                <th class="align-center" width="10%"><?php trans('Action') ?></th>
                            </tr>
                            <tr role="row" class="filter">
                                <?php
                                foreach ($headers_fut as $item) {
                                    echo '<td>';
                                    echo $item['filter'];
                                    echo '</td>';
                                }
                                ?>
                                <td>
                                    <center>
                                    <div class="margin-bottom-5">
                                    <button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i></button>
                                    <button class="btn btn-sm red filter-cancel"><i class="fa fa-remove"></i></button> </div></center>
                                </td>
                           </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>	
</div>
<div class="modal fade" id="tab_modal" role="basic" aria-hidden="true">
	<div class="page-loading page-loading-boxed">
		<img src="<?php echo template_url(); ?>global/img/loading-spinner-grey.gif" alt="" class="loading"/>
		<span>
		&nbsp;&nbsp;Loading... </span>
	</div>
	<div class="modal-dialog">
		<div class="modal-content">
		</div>
	</div>
</div>
