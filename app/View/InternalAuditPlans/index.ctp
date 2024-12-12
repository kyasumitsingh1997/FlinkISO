<style type="text/css">
.btn-group .btn-group{padding-top: 0px}
.box-title{font-weight: 800; font-size: 24px !important }
.badge{font-size: 10px}
</style>
<?php echo $this->element('checkbox-script'); ?>
<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <div class="internalAuditPlans ">
        <div class="col-md-12">
        <?php if(!$this->request->params['pass'][0]) { ?> 
          <div class="nav">
            <div id="tabs"> 
                <ul>
                    <li><?php echo $this->Html->link(__('On-Going Audits'), array('action' => 'index',2,'audit_category_id'=>$this->request->params['named']['audit_category_id'],'auditor_id'=>$this->request->params['named']['auditor_id'])); ?></li>
                    <li><?php echo $this->Html->link(__('Future Audits'), array('action' => 'index',3,'audit_category_id'=>$this->request->params['named']['audit_category_id'],'auditor_id'=>$this->request->params['named']['auditor_id'])); ?></li>
                    <li><?php echo $this->Html->link(__('Past Audits'), array('action' => 'index',1,'audit_category_id'=>$this->request->params['named']['audit_category_id'],'auditor_id'=>$this->request->params['named']['auditor_id'])); ?></li>
                    <li class="pull-right"><?php echo $this->Html->link(__('Audit Plan Calendar'), array('#'),array('id'=>'cal')); ?></li>
                    <li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
                </ul>
            </div>
        </div>
        <?php } else { ?> 
        <?php echo $this->element('nav-header-lists', array('postData' => array('pluralHumanName' => 'Internal Audit Plans', 'modelClass' => 'InternalAuditPlan', 'options' => array("sr_no" => "Sr No", "title" => "Title", "audit_date" => "Audit Date", "clauses" => "Clauses", "audit_from" => "Audit From", "audit_to" => "Audit To", "note" => "Note"), 'pluralVar' => 'internalAuditPlans'))); ?>

        <script type="text/javascript">
            $(document).ready(function() {
                $('table th a, .pag_list li span a').on('click', function() {
                    var url = $(this).attr("href");
                    $('#main').load(url);
                    return false;
                });
                $("#cal").click(function(){
                    window.location = "<?php echo Router::url('/', true); ?>dashboards/audit_cal/<?php echo date('Y-m-d');?>";
                    return false;
                });
            });
        </script>
        <div class="table-responsive">
            <?php echo $this->Form->create(array('class' => 'no-padding no-margin no-background')); ?>
            <div class="row">
                <?php if($internalAuditPlans){ ?> 
                    <?php foreach ($internalAuditPlans as $internalAuditPlan) { 
                        
                        debug($internalAuditPlan);
                        if($this->request->params['pass'][0] == 1)$class='box-default';
                        if($this->request->params['pass'][0] == 2)$class='box-success';
                        if($this->request->params['pass'][0] == 3)$class='box-info';
                        ?>
                           <div class="col-md-12">
                            <div class="box <?php echo $class;?>">
                            <div class="box-header with-border">
                              <h2 class="box-title">
                                <?php 
                                echo $this->Html->link($internalAuditPlan['Standard']['name'] .':'. $internalAuditPlan['InternalAuditPlan']['title'], array('controller' => 'internal_audit_plans', 'action' => 'edit', $internalAuditPlan['InternalAuditPlan']['id']), array());
                                // echo $internalAuditPlan['InternalAuditPlan']['title'];?>
                                <br /><small><?php echo date('d M Y',strtotime($internalAuditPlan['InternalAuditPlan']['schedule_date_from']));?> To <?php echo date('d M Y',strtotime($internalAuditPlan['InternalAuditPlan']['schedule_date_to']));?></small>
                              </h2>
                              <span class='pull-right'>
                                <?php // echo $this->Html->link('Edit',array('action'=>'edit',$internalAuditPlan['InternalAuditPlan']['id']),array('class'=>'btn btn-xs btn-warning'));
// echo ">> " .  $auditTypeMasterBefore['57a592cf-33ac-4cce-bf31-0b42db1e6cf9'];
// print_r($auditTypeMasterBefore);
                                ?>
                                <div class="btn-group">
                                    <!-- <div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle" type="button" aria-expanded="false">Actions <span class="caret"></span></button> 
                                    <ul class="dropdown-menu">

                                        <?php 
                                        if($internalAuditPlan['InternalAuditPlan']['subsidiary_id'] == -1){
                                            unset($auditTypeMasterBefore['Audit Letter']);
                                        }else{
                                            unset($auditTypeMasterBefore['Audit Memo']);
                                        }

                                        if($this->request->params['pass'][0] == 3 || $this->request->params['pass'][0] == 1){
                                            foreach ($auditTypeMasterBefore as $key => $value) {                                                
                                             echo '<li>'.$this->Html->link('Add '. $key . ' <span class="badge">'.count($internalAuditPlan[Inflector::Classify($value)]).'</span>' ,array('controller'=>$value, 'action'=>'lists',$internalAuditPlan['InternalAuditPlan']['id']),array('escape'=>false)).'</li>';
                                            }
                                        }
                                        ?>
                                        <?php if($this->request->params['pass'][0] != 3) { ?> 
                                            <?php foreach ($auditTypeMasterAfter as $key => $value) {     
                                             echo '<li>'.$this->Html->link('Add '. $key . ' <span class="badge">'.count($internalAuditPlan[Inflector::Classify($value)]).'</span>' ,array('controller'=>$value, 'action'=>'lists',$internalAuditPlan['InternalAuditPlan']['id']),array('escape'=>false)).'</li>';
                                         }?>
                                        <?php } ?>
                                        </ul>
                                    </div> -->
                                    <?php 
                                    // if(count($internalAuditPlan['AuditTimesheet'])==0)$timesheetClass='btn-danger';
                                    // else $timesheetClass = 'btn-success';
                                    // echo $this->Html->link(__('Timesheet <span class="badge">'. count($internalAuditPlan['AuditTimesheet']) .'</span>'), array('controller' => 'audit_timesheets', 'action' => 'lists', $internalAuditPlan['InternalAuditPlan']['id']), array('escape'=>false, 'class' => 'btn btn-sm '. $timesheetClass)); 
                                    //echo $this->Html->link(__('Edit this Plan'), array('controller' => 'internal_audit_plans', 'action' => 'edit', $internalAuditPlan['InternalAuditPlan']['id']), array('class' => 'btn btn-sm btn-warning')); 
                                    
                                    if(count($internalAuditPlan['InternalAuditPlanDepartment'])==0)$plandetailsClass = 'btn-danger';
                                    else $plandetailsClass = 'btn-success';

                                    echo $this->Html->link(__('Add/EditPlan Details <span class="badge">' . count($internalAuditPlan['InternalAuditPlanDepartment']).'</span>'), array('controller' => 'internal_audit_plans', 'action' => 'lists', $internalAuditPlan['InternalAuditPlan']['id']), array('escape'=>false, 'class' => 'btn btn-sm '. $plandetailsClass)); 
                                        
                                    if (count($internalAuditPlan['InternalAuditPlanDepartment'])) { 
                                        if (strtotime($internalAuditPlan['InternalAuditPlan']['schedule_date_from']) >= strtotime(date('Y-m-d H:i:s'))) { 
                                            echo $this->Html->link(__('Publish'), array('controller' => 'internal_audit_plans', 'action' => 'view', $internalAuditPlan['InternalAuditPlan']['id']), array('class' => 'btn btn-sm btn-default'));
                                        }
                                    } 
                                    if($internalAuditPlan['InternalAuditPlan']['publish'] == 0) { 
                                            echo $this->Html->link(__('Send for approval'), array('controller' => 'internal_audit_plans', 'action' => 'view', $internalAuditPlan['InternalAuditPlan']['id']), array('class' => 'btn btn-sm btn-default'));
                                    }
                                    
                                    if (strtotime($internalAuditPlan['InternalAuditPlan']['schedule_date_from']) <= strtotime(date('Y-m-d H:i:s'))) { 
                                        if(count($internalAuditPlan['InternalAudit']) == 0)$auditdetailsClass = 'btn-danger';
                                        else $auditdetailsClass = 'btn-success';
                                         
                                        echo $this->Html->link(__('Add Audit Findings <span class="badge badge-info">' . count($internalAuditPlan['InternalAudit']) ."</span>"), array('controller' => 'internal_audits', 'action' => 'lists', $internalAuditPlan['InternalAuditPlan']['id']), array('class' => 'btn btn-sm ' . $auditdetailsClass,'escape'=>false)); 
                                        
                                        echo $this->Html->link(__('View Report'), array('controller' => 'internal_audits', 'action' => 'report', $internalAuditPlan['InternalAuditPlan']['id']), array('class' => 'btn btn-sm btn-info')); 
                                        } else { 
                                            // echo $this->Html->link(__('Add Actual Audit Details <span class="badge badge-info">'. count($internalAuditPlan['InternalAudit'] ."</span>")), "#", array('class' => 'btn btn-sm btn-default', 'disabled' => true,'escape'=>false)); 
                                    } 
                                    ?>
                                </div>
                            </span>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body text-left">
                                <dl>
                                    <dt>Audit Category</dt><dd><h5><?php echo $internalAuditPlan['AuditTypeMaster']['name'];?></h5></dd>
                                    <?php if($internalAuditPlan['Division']['name']){ ?> <dt>Division</dt><dd><?php echo $internalAuditPlan['Division']['name'];?></dd><?php }?>
                                    <?php if($internalAuditPlan['Subsidiary']['name']){ ?><dt>Subsidiary</dt><dd><?php echo $internalAuditPlan['Subsidiary']['name'];?></dd> <?php } ?>
                                    <!-- <dt>Auditor</dt><dd><?php echo $internalAuditPlan['ListOfTrainedInternalAuditor']['name'];?></dd>
                                    <dt>Auditee</dt><dd><?php echo $internalAuditPlan['Employee']['name'];?></dd>
                                    <dt>Clauses</dt><dd><?php echo $internalAuditPlan['InternalAuditPlan']['clauses'];?></dd>
                                    <dt>Process</dt><dd><?php echo $internalAuditPlan['Process']['name'];?></dd> -->
                                </dl>
                                <div class="row"></div>
                                <?php if($internalAuditPlan['InternalAuditPlanDepartment']){ ?>
                                <h4 class="pull-left"><?php echo __('Schedule');?></h4>
                                <table class="table table-borderd">
                                    <tr>
                                        <th><?php echo __('Branch');?><th>
                                        <th><?php echo __('Department');?><th>
                                        <!-- <th><?php echo __('Process');?><th> -->
                                        <th><?php echo __('Clauses');?><th>
                                        <th><?php echo __('Auditee');?><th>
                                        <th><?php echo __('Auditor');?><th>
                                        <th><?php echo __('Start Time');?><th>
                                        <th><?php echo __('End Time');?><th>
                                    </tr>
                                    <?php 
                                    
                                    foreach ($internalAuditPlan['InternalAuditPlanDepartment'] as $plan) {
                                        debug($plan); 
                                    ?>
                                        <tr>
                                            <td><?php echo $PublishedBranchList[$plan['branch_id']];?><td>
                                            <td><?php echo $PublishedDepartmentList[$plan['department_id']];?><td>
                                            <!-- <td><?php echo $processes[$plan['process_id']];?><td> -->
                                            <td><?php echo $plan['clauses'];?><td>
                                            <td><?php echo $PublishedEmployeeList[$plan['employee_id']];?><td>
                                            <td><?php echo $auditors[$plan['list_of_trained_internal_auditor_id']];?><td>             
                                            <td><?php echo $plan['start_time'];?><td>
                                            <td><?php echo $plan['end_time'];?><td>
                                        </tr>

                                    <?php }?>
                                </table>
                                 <?php } ?>                                 
                            </div>
                            <!-- /.box-body -->
                          </div>
                           </div> 
                    <?php } ?>
                <?php }else{ ?>No Records Found<?php }?>
            </div>
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
    <?php } ?>
</div>
<script>
  $(function() {
    $( "#tabs" ).tabs({
      beforeLoad: function( event, ui ) {
    ui.jqXHR.error(function() {
      ui.panel.html(
        "Error Loading ... " +
        "Please contact administrator." );
    });
      }
    });
  });
</script>

<?php echo $this->element('advanced-search', array('postData' => array("sr_no" => "Sr No", "title" => "Title", "audit_date" => "Audit Date", "clauses" => "Clauses", "audit_from" => "Audit From", "audit_to" => "Audit To", "note" => "Note"), 'PublishedBranchList' => array($PublishedBranchList))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
<?php echo $this->Js->writeBuffer(); ?>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
