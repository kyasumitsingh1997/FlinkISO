<?php echo $this->element('checkbox-script'); ?>
<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <div class="correctivePreventiveActions " style="padding: 20px">

        <?php echo $this->element('nav-header-lists', array('postData' => array('pluralHumanName' => 'Corrective Preventive Actions', 'modelClass' => 'CorrectivePreventiveAction', 'options' => array("sr_no" => "Sr No", "number" => "Number", "raised_by" => "Raised By", "assigned_to" => "Assigned To", "target_date" => "Target Date", "initial_remarks" => "Initial Remarks", "proposed_immidiate_action" => "Proposed Immediate Action", "completed_by" => "Completed By", "completed_on_date" => "Completed On Date", "completion_remarks" => "Completion Remarks", "root_cause_analysis_required" => "Root Cause Analysis Required", "root_cause" => "Root Cause", "determined_by" => "Determined By", "determined_on_date" => "Determined On Date", "root_cause_remarks" => "Root Cause Remarks", "proposed_longterm_action" => "Proposed Longterm Action", "action_assigned_to" => "Action Assigned To", "action_completed_on_date" => "Action Completed On Date", "action_completion_remarks" => "Action Completion Remarks", "effectiveness" => "Effectiveness", "closed_by" => "Closed By", "closed_on_date" => "Closed On Date", "closure_remarks" => "Closure Remarks", "document_changes_required" => "Document Changes Required", "capa_type" => $type), 'pluralVar' => 'correctivePreventiveActions'))); ?>

        <script type="text/javascript">
        $(document).ready(function() {
            $('table th a, .pag_list li span a').on('click', function() {
                var url = $(this).attr("href");
                $('#main').load(url);
                return false;
            });
        });
        </script>

        <div class="table-responsive">
            <?php echo $this->Form->create(array('class' => 'no-padding no-margin no-background')); ?>
            <table cellpadding="0" cellspacing="0" class="table table-bordered">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th><?php echo $this->Paginator->sort('name', __('Name')); ?></th>
                    <th><?php echo $this->Paginator->sort('capa_rating_id', __('Ratings')); ?></th>
                    <th><?php echo $this->Paginator->sort('number', __('CAPA Number')); ?></th>
                    <th><?php echo $this->Paginator->sort('capa_source_id', __('CAPA Source')); ?></th>
                    <th><?php echo $this->Paginator->sort('capa_category_id', __('CAPA Category')); ?></th>
                    <th><?php echo $this->Paginator->sort('raised_by', __('Raised By')); ?></th>
                    <th><?php echo __('Capa Investigation'); ?></th>
                    <th><?php echo __('Capa Root Cause Analysis', __('Capa Root Cause Analysis')); ?></th>
                    <th><?php echo __('Capa Revised Dates', __('Capa Revised Dates')); ?></th>
                    <!-- <th><?php echo $this->Paginator->sort('root_cause_analysis_required', __('Root Cause')); ?></th>
                    <th><?php echo $this->Paginator->sort('document_changes_required', __('Document Changes Required')); ?></th> -->
                    <th><?php echo $this->Paginator->sort('publish', __('Publish')); ?></th>
                </tr>
                <?php if ($correctivePreventiveActions) {
                    $x = 0;
                    
                    foreach ($correctivePreventiveActions as $correctivePreventiveAction):
                        debug($correctivePreventiveAction);
                    ?>
                    <?php if(!$correctivePreventiveAction['CorrectivePreventiveAction']['current_status']){ ?>
                    <tr class="text-danger on_page_src">
                        <?php } else{ ?>
                        <tr class="on_page_src"> <?php } ?>
                            <td class=" actions">
                                <?php echo $this->element('actions', array('created' => $correctivePreventiveAction['CorrectivePreventiveAction']['created_by'], 'postVal' => $correctivePreventiveAction['CorrectivePreventiveAction']['id'], 'softDelete' => $correctivePreventiveAction['CorrectivePreventiveAction']['soft_delete'])); ?>
                            </td>
                            <td><?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['name']; ?>&nbsp;</td>
                            <td><?php echo $correctivePreventiveAction['CapaRating']['name']; ?>&nbsp;</td>
                            </td>
                            <td><?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['number']; ?>&nbsp;</td>
                            <td>
                                <?php echo $this->Html->link($correctivePreventiveAction['CapaSource']['name'], array('controller' => 'capa_sources', 'action' => 'view', $correctivePreventiveAction['CapaSource']['id'])); ?>
                            </td>
                            <td>
                                <?php echo $this->Html->link($correctivePreventiveAction['CapaCategory']['name'], array('controller' => 'capa_categories', 'action' => 'view', $correctivePreventiveAction['CapaCategory']['id'])); ?>
                            </td>
                            <td>
                                <?php
                                $sorce = json_decode($correctivePreventiveAction['CorrectivePreventiveAction']['raised_by'], true);
                                if ($sorce == null)
                                    echo $correctivePreventiveAction['CorrectivePreventiveAction']['raised_by'];
                                else
                                    echo $this->Html->link($sorce['Soruce'], array('controller' => strtolower(str_replace(' ', '_', Inflector::pluralize($sorce['Soruce']))), 'action' => 'view', $sorce['id']));
                                ?>&nbsp;
                            </td>
                            <td>
                                <?php if ($correctivePreventiveAction['CorrectivePreventiveAction']['publish'] == 1){ ?>
                                <div class="btn-group">

                                   <a href="#" id="add_investigation<?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['id']; ?>" class="btn btn-xs btn-info"><?php echo __('Add'); ?></a>
                                   <?php
                     //   echo $this->Html->link("Add", array('controller' => "capa_investigations", 'action' => 'lists', $correctivePreventiveAction['CorrectivePreventiveAction']['id']),array('class'=>"btn btn-xs btn-info")); ?> 

                     <?php 
                     $capa_investigation_cnt =  $this->requestAction('corrective_preventive_actions/capa_investigation_count/'.$correctivePreventiveAction['CorrectivePreventiveAction']['id']);
                     echo $this->Html->link($capa_investigation_cnt,
                        array('#'),
                        array('class'=> ( $capa_investigation_cnt>0)? "btn btn-xs btn-success  dropdown-toggle" : "btn btn-xs btn-danger  dropdown-toggle", 'data-toggle'=>'dropdown', 'aria-haspopup'=>'true', 'aria-expanded'=>'false', 'escape'=>false)
                        );  ?>
                        <ul class="dropdown-menu">
                            <?php foreach ($correctivePreventiveAction['CapaInvestigation'] as $inv) {
                                if($inv['current_status']==0)$span = '<span class="badge label-danger">Open</span>';
                                else $span = '<span class="badge label-success">Close</span>';
                                echo "<li>".$this->Html->link($assigned_to[$inv['employee_id']] .'&nbsp;'. $span ,array('controller'=>'capa_investigations','action'=>'view',$inv['id']),array('escape'=>false))."</li>";
                            }?>
                        </ul>
                    </div>
                    <?php } ?>
                </td>

                <td> <div class="btn-group">
                    <?php if ($correctivePreventiveAction['CorrectivePreventiveAction']['publish'] == 1){ ?>   
                    <a href="#" id="add_root_cause_analysis<?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['id']; ?>" class="btn btn-xs btn-info"><?php echo __('Add'); ?></a>
                    <?php
                     //   echo $this->Html->link("Add", array('controller' => "capa_root_cause_analysis", 'action' => 'lists', $correctivePreventiveAction['CorrectivePreventiveAction']['id']),array('class'=>"btn btn-xs btn-info"));  
                    $capa_root_cuase_analysis_cnt =  $this->requestAction('corrective_preventive_actions/capa_root_cuase_analysis_count/'.$correctivePreventiveAction['CorrectivePreventiveAction']['id']);
                    echo $this->Html->link($capa_root_cuase_analysis_cnt,
                        array('#'),
                        array('class'=> ( $capa_root_cuase_analysis_cnt>0)? "btn btn-xs btn-success  dropdown-toggle" : "btn btn-xs btn-danger  dropdown-toggle", 'data-toggle'=>'dropdown', 'aria-haspopup'=>'true', 'aria-expanded'=>'false', 'escape'=>false)
                        );  ?>
                        <ul class="dropdown-menu">
                            <?php foreach ($correctivePreventiveAction['CapaRootCauseAnalysi'] as $root) {
                                if($root['current_status']==0)$span = '<span class="badge label-danger">Open</span>';
                                else $span = '<span class="badge label-success">Close</span>';
                                echo "<li>".$this->Html->link($assigned_to[$root['action_assigned_to']] .'&nbsp;'. $span ,array('controller'=>'capa_root_cause_analysis','action'=>'view',$root['id']),array('escape'=>false))."</li>";
                            }?>
                        </ul>
                    </div>
                    </div>

                    <?php } ?>
                </td>

                <td>
                    <?php if ($correctivePreventiveAction['CorrectivePreventiveAction']['publish'] == 1){ ?> 
                    <div class="btn-group">
                     <a href="#" id="add_revised_dates<?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['id']; ?>" class="btn btn-xs btn-info"><?php echo __('Add'); ?></a>
                     <?php
                      //  echo $this->Html->link("Add", array('controller' => "capa_revised_dates", 'action' => 'lists', $correctivePreventiveAction['CorrectivePreventiveAction']['id']),array('class'=>"btn btn-xs btn-info"));  
                     $capa_revised_dates_cnt =  $this->requestAction('corrective_preventive_actions/capa_revised_dates_count/'.$correctivePreventiveAction['CorrectivePreventiveAction']['id']);
                     echo $this->Html->link($capa_revised_dates_cnt,
                        array('controller'=>'capa_revised_dates','action'=>'index',$correctivePreventiveAction['CorrectivePreventiveAction']['id']),
                        array('class'=> ( $capa_revised_dates_cnt>0)? "btn btn-xs btn-success" : "btn btn-xs btn-danger", 'escape'=>false)
                        );  ?>
                    </div>
                    <?php } ?>
                </td>

                    <!-- <td><?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['root_cause_analysis_required'] ? __('Yes') : __('No'); ?>&nbsp;</td>
                    <td><?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['document_changes_required'] ? __('Yes') : __('No'); ?>&nbsp;</td> -->

                    <td width="60">
                        <?php if ($correctivePreventiveAction['CorrectivePreventiveAction']['publish'] == 1) { ?>
                        <span class="fa fa-check"></span>
                        <?php } else { ?>
                        <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                    </tr>
                    <?php
                    $x++; ?>
                    <script>

                    $().ready(function(){
                        $.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});   

                        $('#add_root_cause_analysis<?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['id']; ?>').click(function(){
                            $("#showModal-indicator").show();
                            $('#rootCauseModal').modal();
                            $('#rootCauseDetails').load('<?php echo Router::url('/', true); ?>capa_root_cause_analysis/add_ajax/<?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['id']; ?>/1');
                        });
                        $('#rootCauseModal').on('hidden.bs.modal', function (e) {
                            $("#showModal-indicator").hide();
                        });

                        $('#add_investigation<?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['id']; ?>').click(function(){
                            $("#showModal-indicator").show();
                            $('#investigationModal').modal();
                            $('#investigationDetails').load('<?php echo Router::url('/', true); ?>capa_investigations/add_ajax/<?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['id']; ?>/1');
                        });
                        $('#investigationModal').on('hidden.bs.modal', function (e) {
                            $("#showModal-indicator").hide();
                        });
                        $('#add_revised_dates<?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['id']; ?>').click(function(){
                            $("#showModal-indicator").show();
                            $('#revisedDateModal').modal();
                            $('#revisedDateDetails').load('<?php echo Router::url('/', true); ?>capa_revised_dates/add_ajax/<?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['id']; ?>/1');
                        });
                        $('#revisedDateModal').on('hidden.bs.modal', function (e) {
                            $("#showModal-indicator").hide();
                        });

                    });        
</script>
<?php    endforeach;
} else {
    ?>
    <tr><td colspan=37><?php echo __('No results found'); ?></td></tr>
    <?php } ?>
</table>
<?php echo $this->Form->end(); ?>
</div>
<p>
    <?php
    echo $this->Paginator->options(array(
        'update' => '#main',
        'evalScripts' => true,
        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
        ));

    echo $this->Paginator->counter(array(
        'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
        ?>
    </p>
    <ul class="pagination">
        <?php
        echo "<li class='previous'>" . $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled')) . "</li>";
        echo "<li>" . $this->Paginator->numbers(array('separator' => '')) . "</li>";
        echo "<li class='next'>" . $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled')) . "</li>";
        ?>
    </ul>
</div>
</div>

<?php echo $this->element('export'); ?>
<?php echo $this->element('capa_advanced_search_modal',array('postData' => array('pluralHumanName' => 'Corrective Preventive Actions', 'modelClass' => 'CorrectivePreventiveAction', 'options' => array("sr_no" => "Sr No", "number" => "Number", "raised_by" => "Raised By", "assigned_to" => "Assigned To", "target_date" => "Target Date", "initial_remarks" => "Initial Remarks", "proposed_immidiate_action" => "Proposed Immediate Action", "completed_by" => "Completed By", "completed_on_date" => "Completed On Date", "completion_remarks" => "Completion Remarks", "root_cause_analysis_required" => "Root Cause Analysis Required", "root_cause" => "Root Cause", "determined_by" => "Determined By", "determined_on_date" => "Determined On Date", "root_cause_remarks" => "Root Cause Remarks", "proposed_longterm_action" => "Proposed Longterm Action", "action_assigned_to" => "Action Assigned To", "action_completed_on_date" => "Action Completed On Date", "action_completion_remarks" => "Action Completion Remarks", "effectiveness" => "Effectiveness", "closed_by" => "Closed By", "closed_on_date" => "Closed On Date", "closure_remarks" => "Closure Remarks", "document_changes_required" => "Document Changes Required"), 'pluralVar' => 'correctivePreventiveActions','capa_type'=>$type,'action'=>'add'))); ?>
<?php echo $this->element('import', array('postData' => array("sr_no" => "Sr No", "number" => "Number", "raised_by" => "Raised By", "assigned_to" => "Assigned To", "target_date" => "Target Date", "initial_remarks" => "Initial Remarks", "proposed_immidiate_action" => "Proposed Immediate Action", "completed_by" => "Completed By", "completed_on_date" => "Completed On Date", "completion_remarks" => "Completion Remarks", "root_cause_analysis_required" => "Root Cause Analysis Required", "root_cause" => "Root Cause", "determined_by" => "Determined By", "determined_on_date" => "Determined On Date", "root_cause_remarks" => "Root Cause Remarks", "proposed_longterm_action" => "Proposed Longterm Action", "action_assigned_to" => "Action Assigned To", "action_completed_on_date" => "Action Completed On Date", "action_completion_remarks" => "Action Completion Remarks", "effectiveness" => "Effectiveness", "closed_by" => "Closed By", "closed_on_date" => "Closed On Date", "closure_remarks" => "Closure Remarks", "document_changes_required" => "Document Changes Required"))); ?>

<div class="modal fade" id="rootCauseModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Add Root Cause Analysis

                </h4>
            </div>
            <div class="modal-body" id="rootCauseDetails"></div>
            <div class="modal-footer">
                <p><small></small></p>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="investigationModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Assign Investigation

                </h4>
            </div>
            <div class="modal-body" id="investigationDetails"></div>
            <div class="modal-footer">
                <p><small></small></p>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="revisedDateModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Add Revised Date

                </h4>
            </div>
            <div class="modal-body" id="revisedDateDetails"></div>
            <div class="modal-footer">
                <p><small></small></p>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
