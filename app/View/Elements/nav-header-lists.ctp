<style type="text/css">
.input-group-addon{ background-color: #1f801f; border-color: #1b741b}
</style>
<?php
    if ($unpublished == null)
        $unpublished = 0;
    if ($count == null)
        $count = 0;
    if ($published == null)
        $published = 0;
    if ($deleted == null)
        $deleted = 0;
?>
<?php
    unset($postData['options']['sr_no']);
    unset($postData['options']['user_access']);
    unset($postData['options']['soft_delete']);
    unset($postData['options']['publish']);
?>
<?php
    if (isset($this->params['named'])) {
        if (isset($this->params['named']['soft_delete'])) {?>
            <div class="nav">
                <div class="col-md-7 col-sm-6">
                    <h4><?php echo $this->element('breadcrumbs'); ?><?php echo h($postData["pluralHumanName"]); ?></h4>
                    <h4>
                        <?php if ($this->action != 'advanced_search' && $this->action != 'search') { ?>
                            <?php echo '<span class="badge btn-success">' . $this->Html->link($published, '#', array('id' => 'published', 'class' => 'btn-success', 'data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'data-original-title' => 'Published Records')) . '</span>'; ?><script>$('#published').tooltip();</script>
                            <?php echo '<span class="badge btn-success">' . $this->Html->link($count, '#', array('id' => 'count', 'class' => 'btn-success', 'data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'data-original-title' => 'Total')) . '</span>'; ?><script>$('#count').tooltip();</script>
                            <?php echo '<span class="badge btn-warning">' . $this->Html->link($unpublished, '#', array('id' => 'unpublished', 'class' => 'btn-warning', 'data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'data-original-title' => 'Unpublished Records')) . '</span>'; ?><script>$('#unpublished').tooltip();</script>
                            <?php echo '<span class="badge btn-danger">' . $this->Html->link($deleted, '#', array('id' => 'deleted', 'class' => 'btn-danger', 'data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'data-original-title' => 'Deleted Records')) . '</span>'; ?><script>$('#deleted').tooltip();</script>
                        <?php }
                        if($this->request->params['controller'] == 'stocks'){
                            if(isset($postData['sType'])){
                                $type = $postData['sType'];
                            }else{
                                $type = $this->request->params['pass'][0];
                        }
                    
                    $this->Js->get('#addrecord');
                    $this->Js->event('click', $this->Js->request(array('action' => 'lists',$type), array('async' => true, 'update' => '#main')));
                    $this->Js->get('#gridcall');
                    if (isset($this->request->params['named']['published'])){
                        $this->Js->event('click', $this->Js->request(array('action' => 'index',$type, 'published' => $this->request->params['named']['published']), array('async' => true, 'update' => '#main')));
                    }else{
                        $this->Js->event('click', $this->Js->request(array('action' => 'index',$type), array('async' => true, 'update' => '#main')));
                    }
                    $this->Js->get('#count');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',$type, NULL), array('async' => true, 'update' => '#main')));
                    $this->Js->get('#published');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',$type, 'published' => 1), array('async' => true, 'update' => '#main')));
                    $this->Js->get('#unpublished');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',$type, 'published' => 0), array('async' => true, 'update' => '#main')));
                    $this->Js->get('#deleted');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',$type, 'soft_delete' => 1), array('async' => true, 'update' => '#main')));
                }else{
                            $this->Js->get('#count');
                            $this->Js->event('click', $this->Js->request(array('action' => $this->action, NULL), array('async' => true, 'update' => '#main')));
                            $this->Js->get('#published');
                            $this->Js->event('click', $this->Js->request(array('action' => $this->action, 'published' => 1), array('async' => true, 'update' => '#main')));
                            $this->Js->get('#unpublished');
                            $this->Js->event('click', $this->Js->request(array('action' => $this->action, 'published' => 0), array('async' => true, 'update' => '#main')));
                            $this->Js->get('#deleted');
                            $this->Js->event('click', $this->Js->request(array('action' => $this->action, 'soft_delete' => 1), array('async' => true, 'update' => '#main')));
                }
                            echo $this->Js->writeBuffer();
                        ?>
                        <span class=""></span>
                        <?php echo $this->Html->link(__('Restore All'), '#restoreAll', array('class' => 'label btn-success', 'data-toggle' => 'modal', 'onClick' => 'getVals()')); ?>
                        <?php echo $this->Html->link(__('Purge All'), '#purgeAll', array('class' => 'label btn-danger', 'data-toggle' => 'modal', 'onClick' => 'getVals()')); ?>
                        <span class=""></span>
                        <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
                    </h4>
	</div>
</div>
<?php } else {?>
<div class="row">
	<div class="col-md-7 col-sm-6">
		<h4><?php echo $this->element('breadcrumbs'); ?>
            <?php 
                if($postData["pluralHumanName"] == 'Products')echo "Services/Products"; 
                if($postData["pluralHumanName"] == 'Devices')echo "Asset Register"; 
                if($postData["pluralHumanName"] == 'Delivery Challans')echo "Delivery Channals"; 
                else echo h($postData["pluralHumanName"]); ?>
        </h4>
		<h4>
			<?php if ($this->action != 'advanced_search' && $this->action != 'search') { ?>
			
			<?php echo '<span class="badge btn-success">' . $this->Html->link($published, '#', array('id' => 'published', 'class' => 'btn-success', 'data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'data-original-title' => 'Published Records')) . '</span>'; ?><script>$('#published').tooltip();</script> 
			<?php echo '<span class="badge btn-info">' . $this->Html->link($count, '#', array('id' => 'count', 'class' => 'btn-info', 'data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'data-original-title' => 'Total')) . '</span>'; ?><script>$('#count').tooltip();</script> 
            <?php echo '<span class="badge btn-warning">' . $this->Html->link($unpublished, '#', array('id' => 'unpublished', 'class' => 'btn-warning', 'data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'data-original-title' => 'Unpublished Records')) . '</span>'; ?><script>$('#unpublished').tooltip();</script> 
			<?php echo '<span class="badge btn-danger">' . $this->Html->link($deleted, '#', array('id' => 'deleted', 'class' => 'btn-danger', 'data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'data-original-title' => 'Deleted Records')) . '</span>'; ?><script>$('#deleted').tooltip();</script>
			<?php } ?>
			<span class=""></span>
			<?php if (!(
                ($this->request->params['controller'] == 'milestones') || 
                ($this->request->params['controller'] == 'project_overall_plans') || 
                ($this->request->params['controller'] == 'project_process_plans') || 
                ($this->request->params['controller'] == 'file_processes') || 
                ($this->request->params['controller'] == 'project_resources') || 
                ($this->request->params['controller'] == 'project_employees') || 
                ($this->request->params['controller'] == 'project_checklists') || 
                ($this->request->params['controller'] == 'project_queries') || 
                ($this->request->params['controller'] == 'file_uploads') || ($this->request->params['controller'] == 'reports') || ($this->request->params['controller'] == 'list_of_measuring_devices_for_calibrations') || ($this->request->params['controller'] == 'document_amendment_record_sheets') || (($this->request->params['controller'] == 'stocks') && $this->request->params['pass'][0] == 1))) { ?>
			<?php
                                echo $this->Html->link(__('Add'), array('action' => 'lists'), array('id' => 'addrecord', 'class' => 'label btn-primary', 'data-original-title' => 'Add New Record'));
                            ?>			
			<?php } ?>
			<?php
        //                     if (($this->request->params['controller'] == 'employees') || 
								// ($this->request->params['controller'] == 'branches') || 
								// ($this->request->params['controller'] == 'customers') || 
								// ($this->request->params['controller'] == 'products') || 
								// ($this->request->params['controller'] == 'devices') || 
								// ($this->request->params['controller'] == 'trainers') || 
								// ($this->request->params['controller'] == 'fireExtinguishers') || 
								// ($this->request->params['controller'] == 'ListOfComputers') || 
								// ($this->request->params['controller'] == 'ListOfSoftwares') || 
								// ($this->request->params['controller'] == 'supplier_registrations') || 
								// ($this->request->params['controller'] == 'housekeeping_checklists') || 
								// ($this->request->params['controller'] == 'housekeeping_responsibilities') || 
								// ($this->request->params['controller'] == 'customer_feedback_questions') || 
								// ($this->request->params['controller'] == 'list_of_computers') || 
								// ($this->request->params['controller'] == 'courses') || 
								// ($this->request->params['controller'] == 'list_of_acceptable_suppliers') || 
								// ($this->request->params['controller'] == 'summery_of_supplier_evaluations') || 
								//  ($this->request->params['controller'] == 'materials') || 
								// ($this->request->params['controller'] == 'internal_audit_questions') || 
								// ($this->request->params['controller'] == 'fire_safety_equipment_lists') || 
								// ($this->request->params['controller'] == 'corrective_preventive_actions') || 
								// ($this->request->params['controller'] == 'designations') || 
								// ($this->request->params['controller'] == 'competency_mappings') || 
								// ($this->request->params['controller'] == 'trainings')  || 
								// ($this->request->params['controller'] == 'list_of_trained_internal_auditors') ||
								// ($this->request->params['controller'] == 'customer_complaints') ||
								// ($this->request->params['controller'] ==  'customer_feedbacks') || 
								// ($this->request->params['controller'] ==  'customer_meetings') || 
								// ($this->request->params['controller'] ==  'device_maintenances')|| 
								// ($this->request->params['controller'] ==  'fire_extinguishers') || 
								// ($this->request->params['controller'] ==  'fire_safety_equipment_lists') || 
								// ($this->request->params['controller'] ==  'list_of_softwares') || 
								// ($this->request->params['controller'] ==  'proposals') || 
								// ($this->request->params['controller'] ==  'document_amendment_record_sheets') || 
								// ($this->request->params['controller'] ==  'change_addition_deletion_requests') || 
        //                         ($this->request->params['controller'] ==  'hazard_types') || 
        //                         ($this->request->params['controller'] ==  'injury_types') || 
        //                         ($this->request->params['controller'] ==  'body_areas') || 
        //                         ($this->request->params['controller'] ==  'hazard_sources') ||
        //                         ($this->request->params['controller'] ==  'severiry_types') ||
								// ($this->request->params['controller'] ==  'appraisal_questions') ||
        //                         ($this->request->params['controller'] ==  'non_conforming_products_materials') || 
        //                         ($this->request->params['controller'] ==  'clauses') ||
        //                         ($this->request->params['controller'] ==  'audit_type_masters') || 
        //                         ($this->request->params['controller'] ==  'objectives') || 
        //                         ($this->request->params['controller'] ==  'processes') ||
        //                         ($this->request->params['controller'] ==  'tasks' &&  
        //                             $this->action == 'advanced_search') 
        //                     ) {
                                echo $this->Html->link(__('Import/Export'), '#import', array('id' => 'imp', 'class' => 'label btn-success', 'data-toggle' => 'modal', 'data-original-title' => 'Import Records', 'onClick' => 'show_hide();'));
                            // }
                        ?>
			<script>$('#imp').tooltip();</script>
			<?php if (!(($this->request->params['controller'] == 'file_uploads'))){ ?>
			<?php if($this->request->params['controller'] != 'designations' && $this->request->params['controller'] != 'production_rejections'){ ?>
			<?php echo $this->Html->link(__('Delete All'), '#deleteAll', array('class' => 'label btn-danger', 'data-toggle' => 'modal', 'onClick' => 'getVals()')); ?>
			<?php }?>
			<?php }?>
            <?php if(!$this->request->params['controller'] == 'clauses'){ ?>
    			<span class="label btn btn-info"><?php echo $this->Html->link('', array('action' => 'index'), array('id' => 'gridcall', 'class' => 'fa fa-list', 'data-original-title' => 'Back to index')); ?> </span> 
    			<script>$('#gridcall').tooltip();</script>
            <?php }else{ ?>            
                <span class="label btn btn-info"><?php echo $this->Html->link('', array('action' => 'home'), array('id' => 'gridcall', 'class' => 'fa fa-list', 'data-original-title' => 'Back to index')); ?> </span> 
                <script>$('#gridcall').tooltip();</script>
            <?php } ?>
            <?php if($this->request->params['controller'] == 'customer_complaints'){ ?>&nbsp;
                <?php echo $this->Html->link('Open', array('action' => 'home',0), array('id' => 'gridcallccopen', 'class' => 'btn btn-xs btn-danger', 'data-original-title' => 'Open Customer Complaints')); ?> 
                <script>$('#gridcallccopen').tooltip();</script>
                <?php echo $this->Html->link('Close', array('action' => 'home',1), array('id' => 'gridcallccclose', 'class' => 'btn btn-xs btn-success', 'data-original-title' => 'Closed Customer Complaints')); ?>
                <script>$('#gridcallccclose').tooltip();</script>

                <?php 
                    $this->Js->get('#gridcallccopen');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',0), array('async' => true, 'update' => '#main')));

                    $this->Js->get('#gridcallccclose');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',1), array('async' => true, 'update' => '#main')));
                    
                ?>
            <?php }?>
            <?php if($this->request->params['controller'] == 'corrective_preventive_actions'){ ?>&nbsp;
                
                <?php echo $this->Html->link('New', array('action' => 'index',0), array('id' => 'capanew', 'class' => 'btn btn-xs btn-info', 'data-original-title' => 'New CAPA')); ?> 
                <script>$('#capanew').tooltip();</script>
                
                <?php echo $this->Html->link('CA', array('action' => 'index',1), array('id' => 'capacapa', 'class' => 'btn btn-xs btn-info', 'data-original-title' => 'Corrective Actions')); ?>
                <script>$('#capacapa').tooltip();</script>

                <?php echo $this->Html->link('PA', array('action' => 'index',1), array('id' => 'capaprev', 'class' => 'btn btn-xs btn-info', 'data-original-title' => 'Preventive Actions')); ?>
                <script>$('#capaprev').tooltip();</script>

                <?php echo $this->Html->link('CAPA', array('action' => 'index',1), array('id' => 'capacapaprev', 'class' => 'btn btn-xs btn-info', 'data-original-title' => 'Corrective Preventive Actions')); ?>
                <script>$('#capacapaprev').tooltip();</script>

                <?php echo $this->Html->link('Open', array('action' => 'index',1), array('id' => 'opencapa', 'class' => 'btn btn-xs btn-danger', 'data-original-title' => 'Open CAPA')); ?>
                <script>$('#opencapa').tooltip();</script>

                <?php echo $this->Html->link('Close', array('action' => 'index',1), array('id' => 'closecapa', 'class' => 'btn btn-xs btn-success', 'data-original-title' => 'Cloae CAPA')); ?>
                <script>$('#closecapa').tooltip();</script>

                <?php 
                    $this->Js->get('#capanew');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',3), array('async' => true, 'update' => '#main')));

                    $this->Js->get('#capacapa');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',0), array('async' => true, 'update' => '#main')));

                    $this->Js->get('#capaprev');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',1), array('async' => true, 'update' => '#main')));

                    $this->Js->get('#capacapaprev');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',2), array('async' => true, 'update' => '#main')));

                    $this->Js->get('#opencapa');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',5), array('async' => true, 'update' => '#main')));

                    $this->Js->get('#closecapa');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',4), array('async' => true, 'update' => '#main')));
                    
                ?>
            <?php }?>
		</h4>        
	</div>
    
    <div class="col-md-3">
        <?php if($this->action == 'index' || $this->action == 'advance_search' || $this->action == 'quick_search' || $this->action == 'advanced_search'){ ?> 
        <form id="live-search" action="" class="no-padding no-margin" method="post">
                <div class="input-group">
                    <input type="text" class="form-control" id="filter" value="<?php if(isset($this->request->params['named']['search']))echo str_replace(' ','+',$this->request->params['named']['search']); ?>"  placeholder="Enter keyword and press tab"/> 
                    <span id="filter-count" class="input-group-addon"><span class="fa fa-search" id="quick_src_button"></span></span>
                </div>                    
        </form>
        <?php } ?>
    </div>    
    <div class="col-md-2">
	<?php if (!(($this->request->params['controller'] == 'stocks'))){?>
	<!--<?php echo $this->Html->link('Advanced Search', '#advanced_search', array('id' => 'ad_src', 'class' => 'btn btn-success', 'data-toggle' => 'modal', 'data-original-title' => 'Advanced Search')); ?> -->
    <?php echo $this->Html->link('Advanced Search', '#advanced_search', array('id' => 'ad_src', 'class' => 'btn btn-success')); ?> 
    
	<?php }?>
</div>
	<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?> </div>
<?php

                if($this->request->params['controller'] == 'stocks'){
                    if(isset($postData['sType'])){
                            $type = $postData['sType'];
                        }else{
                            $type = $this->request->params['pass'][0];
                        }
                    $this->Js->get('#addrecord');
                    $this->Js->event('click', $this->Js->request(array('action' => 'lists',$type), array('async' => true, 'update' => '#main')));
                    $this->Js->get('#gridcall');
                    if (isset($this->request->params['named']['published'])){
                        $this->Js->event('click', $this->Js->request(array('action' => 'index',$type, 'published' => $this->request->params['named']['published']), array('async' => true, 'update' => '#main')));
                    }else{
                        $this->Js->event('click', $this->Js->request(array('action' => 'index',$type), array('async' => true, 'update' => '#main')));
                    }
                    $this->Js->get('#count');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',$type, NULL), array('async' => true, 'update' => '#main')));
                    $this->Js->get('#published');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',$type, 'published' => 1), array('async' => true, 'update' => '#main')));
                    $this->Js->get('#unpublished');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',$type, 'published' => 0), array('async' => true, 'update' => '#main')));
                    $this->Js->get('#deleted');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index',$type, 'soft_delete' => 1), array('async' => true, 'update' => '#main')));
                }else{
                    $this->Js->get('#addrecord');
                    $this->Js->event('click', $this->Js->request(array('action' => 'lists'), array('async' => true, 'update' => '#main')));
                    $this->Js->get('#gridcall');
                    if (isset($this->request->params['named']['published'])){
                        $this->Js->event('click', $this->Js->request(array('action' => 'index', 'published' => $this->request->params['named']['published']), array('async' => true, 'update' => '#main')));
                    }else{
                        if($this->request->params['controller'] == 'clauses'){
                            $this->Js->event('click', $this->Js->request(array('action' => 'home'), array('async' => true, 'update' => '#main')));
                        }else{
                            $this->Js->event('click', $this->Js->request(array('action' => 'index'), array('async' => true, 'update' => '#main')));    
                        }
                        
                    }
                      
                    $this->Js->get('#count');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index', NULL), array('async' => true, 'update' => '#main')));
                    $this->Js->get('#published');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index', 'published' => 1), array('async' => true, 'update' => '#main')));
                    $this->Js->get('#unpublished');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index', 'published' => 0), array('async' => true, 'update' => '#main')));
                    $this->Js->get('#deleted');
                    $this->Js->event('click', $this->Js->request(array('action' => 'index', 'soft_delete' => 1), array('async' => true, 'update' => '#main')));
                }
                    echo $this->Js->writeBuffer();

        }
    }
?>
<script type="text/javascript">
$(document).ready(function(){
    $("#ad_src").on('click',function(){
        $("#ad_src_result").load("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/advance_search");
    });
    $("#filter").change(function(){
        $("#filter").val($("#filter").val().replace(/ /g,"+"));
    });
    $("#filter").focus();
    $("#quick_src_button").attr('tabindex',0);

    $("#quick_src_button").focus(function(){
        $('#main').load("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/quick_search/search:" + $("#filter").val());
        var numberItems = count;
        return false;
    });
});</script>

<div id="ad_src_result"></div>