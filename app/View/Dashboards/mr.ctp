<style type="text/css">
.ui-tabs .ui-tabs-nav li.ui-tabs-active{padding-bottom: 0px !important}
</style>
<script>
    $().load(function() {
    });
</script>
<div class="">
    <h4><?php echo __('Management Team Dashboard'); ?></h4>
</div>
<div class="main nav panel">
    <div class="nav panel-body">
        <div class="row  panel-default">
            <div class="col-md-8">


                <div class="row">

                    <div class="col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4><?php echo __('Document Change Requests'); ?></h4>
                                <p><?php
                                        echo __('Create these request from ADD. These requests will be then available in MR meetings as topic.');
                                        echo '&nbsp;' . $this->Html->link(__('Document Amendment Record Sheet'), array('controller' => 'document_amendment_record_sheets', 'action' => 'index'), array('class' => 'text-primary'));
                                        echo '&nbsp;' . __('will be generated automatically.');
                                    ?></p>
                                <br />
                                <div class="btn-group">
                                    <?php echo $this->Html->link(__('Add'), array('controller' => 'change_addition_deletion_requests', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(__('See All'), array('controller' => 'change_addition_deletion_requests', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/ChangeAdditionDeletionRequest/count'), array('controller' => 'change_addition_deletion_requests', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Change Requests'))); ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4><?php echo __('Meetings'); ?></h4>
                                <p>
                                    <?php
                                        echo __('Make sure you have added ');
                                        echo $this->Html->link(__('Branches'), array('controller' => 'branches', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Departments'), array('controller' => 'departments', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Employees'), array('controller' => 'employees', 'action' => 'index'), array('class' => 'text-primary'));
                                        echo '&nbsp;' . __('to create Meetings and send invites to employees.');
                                    ?>
                                    <strong class="text-info"><?php echo __('As per the standard you should have at-least one meeting per month.'); ?></strong>
                                </p>
                                <div class="btn-group">
                                    <?php echo $this->Html->link(__('Add'), array('controller' => 'meetings', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(__('See All'), array('controller' => 'meetings', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/Meeting/count'), array('controller' => 'meetings', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Meetings'))); ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4><?php echo __('Meeting Details'); ?></h4>
                                <p>
                                    <?php
                                        echo __('Make sure you have added ');
                                        echo $this->Html->link(__('Branches'), array('controller' => 'branches', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Departments'), array('controller' => 'departments', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Employees'), array('controller' => 'employees', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Meetings'), array('controller' => 'meetings', 'action' => 'index'), array('class' => 'text-primary'));
                                        echo '&nbsp;' . __('to add Meetings details.');
                                    ?>
                                    <br /><strong class="text-info"><?php echo __('You can add meeting details after meeting dates.'); ?></strong>
                                </p>
                                <div class="btn-group">
                                    <?php echo $this->Html->link(__('Add'), array('controller' => 'meetings', 'action' => 'meeting_detail_lists'), array('class' => 'btn btn-default')); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="cleafix">&nbsp;</div>
                <div class="row">

                    <div class="col-md-8">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4><?php echo __('Manage Audits'); ?></h4>
                                <p>
                                    <?php
                                        echo __('Make sure you have added ');
                                        echo $this->Html->link(__('Branches'), array('controller' => 'branches', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Employees'), array('controller' => 'employees', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Trained Auditors'), array('controller' => 'list_of_trained_internal_auditors', 'action' => 'index'), array('class' => 'text-primary'));
                                        echo '&nbsp;' . __('to Prepare your Audit Plan for the year.');
                                    ?>
                                    <br /><span class="text-info"><b><?php echo __('You need to create a schedule / plan first and then add actual audit details by choosing the plan from existing schedules.'); ?></b></span>
                                </p>

                                <div class="btn-group">
                                    <?php echo $this->Html->link(__('Add Schedule/Plan'), array('controller' => 'internal_audit_plans', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(__('Select Plan & add Audit Details'), array('controller' => 'internal_audit_plans', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                    <?php
                                    if (!($countNcs == 0)) {
                                        echo $this->Html->link(' ' . $countNcs, array('controller' => 'internal_audit_plans', 'action' => 'index'), array('type' => 'button', 'class' => 'btn  btn-danger', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Non Conformity Actions Required')));
                                    }
                                    ?>
                                    <?php echo $this->Html->link(' ' . $countInternalAudits, array('controller' => 'internal_audit_plans', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'top', 'data-toggle' => 'tooltip', 'title' => __('No. of Audits'))); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4><?php echo __('CAPA'); ?></h4>
                                <p>
                                    <?php
                                        echo __('Make sure you have added ');
                                        echo $this->Html->link(__('CAPA Sources'), array('controller' => 'capa_sources', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('CAPA Category'), array('controller' => 'capa_categories', 'action' => 'index'), array('class' => 'text-primary'));
                                        echo '&nbsp;' . __('to Add CAPA Plan');
                                    ?><br><br /></p>
                                <div class="btn-group">
                                    <?php echo $this->Html->link(__('Add'), array('controller' => 'corrective_preventive_actions', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(__('See All'), array('controller' => 'corrective_preventive_actions', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/CorrectivePreventiveAction/count'), array('controller' => 'corrective_preventive_actions', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'top', 'data-toggle' => 'tooltip', 'title' => __('No. of CAPAs'))); ?>
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="cleafix">&nbsp;</div>
                <div class="row">

                    <div class="col-md-8">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4><?php echo __('Task'); ?></h4>
                                <p>
                                    <?php echo __('As per your standard procedure, you might have assigned various ISO related tasks to your employees. You can assign those tasks to specific employees by clicking ADD below.'); ?><br/><br/>
                                    <?php echo __('Once the task is assigned to the respective employees, based on the schedule, the employee will receive alerts on those tasks each time they login.'); ?><br />                                    
                                </p>
                                <div class="btn-group">
                                    <?php echo $this->Html->link(__('Add'), array('controller' => 'tasks', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(__('See All'), array('controller' => 'tasks', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/Task/count'), array('controller' => 'tasks', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Tasks'))); ?><script>$('.btn').tooltip();</script>
                                    <?php echo $this->Html->link(__('Task Monitor'), array('controller' => 'tasks', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-danger')); ?>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 hide">
                        <div class="panel bs-callout bs-callout-warning">
                            <h4><?php echo __('Custom Templates'); ?></h4>
                            <p><?php echo __('Template creation as per requirements. Also add schedules & auto generate reports.') ?></p>
                            <div class="btn-group">
                                <?php echo $this->Html->link(__('Add'), array('controller' => 'custom_templates', 'action' => 'lists'), array('class' => 'btn btn-primary')); ?>
                                <?php echo $this->Html->link(__('See All'), array('controller' => 'custom_templates', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-success')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                            <h4><?php echo __('Benchmarking'); ?></h4>
                            <p>
                                <?php echo __('For each Department, you need to assess the required data entry input for ISO related activities, on a daily / weekly / monthly basis.'); ?><br /><br />
                                <?php echo __('Based on the benchmark set, FlinkISO will automatically update you on the over all readiness.'); ?>
                            </p>
                            <?php echo $this->Html->link(__('Define Benchmarks'), array('controller' => 'benchmarks', 'action' => 'index'), array('class' => 'btn btn-primary')); ?>
                        </div>
                    </div>
                        </div>
                </div>

                <div class="cleafix">&nbsp;</div>
                <div class="row">
                    <div class="col-md-4" >
                        <div class="thumbnail" style="width:100%">
                            <div class="caption">
                                <h4><?php echo __('Work in progress'); ?></h4>
                                 Branch
                                   <div class="progress">
                                    <div class="progress-bar progress-bar-success" aria-valuenow="<?php echo round($branchData); ?>" style="width: <?php echo round($branchData); ?>%"><?php echo round($branchData); ?>%</div>
                                    </div>
                                    Departments
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-success" aria-valuenow="<?php echo round($departmentData); ?>" style="width: <?php echo round($departmentData); ?>%"><?php echo round($departmentData); ?>%</div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4><?php echo __('Continual improvement'); ?></h4>
                                <p>
                                    <?php
                                        echo __('Make sure you have added ');
                                        echo $this->Html->link(__('CAPA'), array('controller' => 'corrective_preventive_actions', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Processes'), array('controller' => 'processes', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Audits'), array('controller' => 'internal_audits', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo '&nbsp;' . __('Continual improvement');
                                    ?><br><br></p>
                                <div class="btn-group">
                                    <?php echo $this->Html->link(__('Add'), array('controller' => 'continual_improvements', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(__('See All'), array('controller' => 'continual_improvements', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/continual_improvements/count'), array('controller' => 'continual_improvements', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'top', 'data-toggle' => 'tooltip', 'title' => __('No. of CI'))); ?>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(function() {
                            $("#from").datepicker({
                                defaultDate: "-2m",
                                changeMonth: true,
                                numberOfMonths: 3,
                                onClose: function(selectedDate) {
                                    $("#to").datepicker("option", "minDate", selectedDate);
                                }
                            });
                            $("#to").datepicker({
                                changeMonth: true,
                                numberOfMonths: 3,
                                onClose: function(selectedDate) {
                                    $("#from").datepicker("option", "maxDate", selectedDate);
                                }
                            });
                        });
                    </script>
                    <div class="col-md-4">
                        <div class="panel panel-info no-margin">
                            <div class="panel-heading"><h5><?php echo __('Non Conformities Report'); ?></h5></div>
                            <div class="panel-body">
                                <?php echo $this->Form->create('reports', array('action' => 'nc_report', 'role' => 'form', 'class' => 'form no-padding no-margin')); ?>
                                <p>Select start date and date to generate the report.</p>
                                <div class="row">
                                    <div class="col-md-6"><?php echo $this->Form->input('from', array('id' => 'from', 'label' => false, 'class' => 'btn', 'div' => false)); ?></div>
                                    <div class="col-md-6"><?php echo $this->Form->input('to', array('id' => 'to', 'label' => false, 'class' => 'btn', 'div' => false)); ?></div>
                                    <div class="col-md-12"><?php echo $this->Form->Submit('Submit', array('class' => 'btn btn-success ', 'div' => false)); ?></div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="display: none">
                    <div class="col-md-12">
                        <div class="alert alert-info  fade in message"><h4><?php echo __('Why do we need this?'); ?></h4>
                            <p>
                                <?php echo __('Some Management Representative notes on this subject should appear here.'); ?><br />
                                <?php echo __('We can extract these from Helps section'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <?php echo $this->element('helps'); ?>

            </div>
        </div>
<!--
		<div class="nav">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="list-group-item-heading"><?php echo __('Available Quality Documents (MR Department)'); ?><span class="glyphicon glyphicon-eye-open pull-right"></span></h3>
                        <p class="list-group-item-text"><?php echo __('You can add/view your company Quality Manuals / Procedures / Objectives / Records / Policies for MR department by clicking on the links below. <br /> These documents are available for all users.'); ?></p>
                    </div>
                    <div class="panel-body">
                    	<?php echo $this->Element('files',array('filesData' => array('files'=>$files,'action'=>$this->action))); ?></div>
                </div>
            </div>
        </div>
-->
        <div class="nav hide">
            <div class="row" id="qcdocuments">
              <div  class="col-md-12">
                <h2><?php echo __('Available Quality Documents'); ?></h2>
                <div id='standards' class='btn-group'>
                  <?php foreach ($standards as $key => $value) {
                    if(isset($standard_id) && $standard_id == $key){
                      $class = ' btn-info';
                    }else{
                      $class = ' btn-default';
                    }
                    echo $this->Js->link($value, array('controller'=>'file_uploads','action'=>'quality_documents','standard_id'=>$key,'jqload'=>1), array(
                        'update' => '#qcdocuments',
                        'htmlAttributes' => array('class'=>'btn btn-xs ' . $class),            
                    ));
                    echo $this->Js->writeBuffer();
                    // echo $this->Html->link($value,array('controller'=>'file_uploads','action'=>'quality_documents','standard_id'=>$key,'jqload'=>1),array('class'=>'btn btn-sm' . $class));                
                  }?>
                </div>
                  <div id="files-tabs-<?php echo $this->request->params['named']['standard_id'];?>">
                      <ul>
                        <?php foreach ($masterListOfFormatCategories as $key => $value) {
                          echo '<li>'.$this->Html->link($value, array('controller'=>'master_list_of_formats', 'action' => 'categorywise_files','category_id'=> $key, NULL,NULL,'standard_id'=> $standard_id,'jqload'=>0), array('escape' => false)).'</li>';
                        }?>                          
                              <li><?php echo $this->Html->image('indicator.gif', array('id' => 'file-cat-busy-indicator', 'class' => 'pull-right')); ?></li>
                          </ul>
                      </div>
                  </div>

            <script>
            $(document).ready(function() {

            $.ajaxSetup({
                cache:false,
               // success: function() {$("#message-busy-indicator").hide();}
                });
                $( "#files-tabs-<?php echo $this->request->params['named']['standard_id'];?>" ).tabs({
                     load: function( event, ui ) {
                       $("#file-cat-busy-indicator").hide();
                    },
                    ajaxOptions: {
                        error: function( xhr, status, index, anchor ) {
                            $( anchor.hash ).html(
                                "Couldn't load this tab. We'll try to fix this as soon as possible. " +
                                "If this wouldn't be a demo." );
                        }
                    }
                });

                $( "#files-tabs li" ).click(function() {
                    $("#file-cat-busy-indicator").show();
                });
            });
            </script>
            </div>
            
    </div>

</div>
