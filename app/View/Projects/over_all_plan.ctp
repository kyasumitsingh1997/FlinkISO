<div id="pro_o_plan"> 
  hello
  <?php

  $process_weightage = $this->requestAction(array('action'=>'process_weightage',$milestone['Milestone']['project_id']));
    echo $this->Html->css(array(
        // 'cake.generic',
        // 'bootstrap/css/bootstrap.min',
        // 'dist/css/AdminLTE.min',
        // 'dist/css/skins/_all-skins.min',
        // 'plugins/iCheck/flat/blue',
        // // 'plugins/morris/morris.min',
        // 'plugins/jvectormap/jquery-jvectormap-1.2.2',
        // 'plugins/datepicker/datepicker3',
        // 'plugins/daterangepicker/daterangepicker-bs3',
        // 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min',
        // 'jquery.countdown',
        // 'jquery-ui-1.9.2.custom.min',
        // 'bootstrap-chosen.min',
        // 'jquery.datepicker',
        // 'custom',
        // 'font-awesome.min','icons'
    ));

  echo $this->fetch('css');
  ?>
  <?php
  echo $this->Html->script(array(
      // 'js/bootstrap.min','js/npm',
      // 'plugins/jQuery/jQuery-2.2.0.min',
      // 'plugins/jQueryUI/jquery-ui.min',
      // // 'jquery-form.min',
      // // 'jquery.validate.min',
      // 'js/bootstrap.min',
      // 'validation',
      // 'chosen.min',
      // 'tooltip.min',
      // 'plugins/daterangepicker/moment.min',
      // 'jquery.datepicker',    
      // 'plugins/daterangepicker/daterangepicker',
      // 'plugins/datepicker/bootstrap-datepicker',
  ));
  echo $this->fetch('script');
  ?>

  <script type="text/javascript">
    $().ready(function(){
      // $('.chosen').attr('width:100%');
      // $("select").chosen({width: "100%"})
      $(".chosen-select").chosen('destroy');
      $(".chosen-select").chosen({width: "100%"});
      // $("#<?php echo $milestone["Milestone"]["id"];?>-busy-indicator").hide();
    });
  </script>
  <style type="text/css">
    .nre {padding-left: 20px}
    .nre .radio input[type="radio"]{ margin: 3px 10px 0 -15px !important}
  </style>  
        <h4>Overall Plan
              <?php                         
                echo $this->Html->link('Add New Plan',"javascript:void(0);",
                    array(
                      'class'=>'btn btn-xs btn-default pull-right',
                      'onclick'=>'openmodel(
                        "project_overall_plans",
                        "add_ajax",
                        "",
                        "'.$milestone['Milestone']['project_id'].'",
                        "'.$milestone['Milestone']['id'].'",
                        "'.$activity['ProjectActivity']['id'].'"
                      )'
                    )); 
              ?>
            </h4>
              <table class="table table-responsive table-condensed table-bordered">
                <tr class="warning">                    
                  <th><?php echo __('Type'); ?></th>
                  <th><?php echo __('Lot/Process'); ?></th>
                  <th><?php echo __('Process Type'); ?></th>
                  <th><?php echo __('Est Units'); ?></th>
                  <th><?php echo __('Overall Metrics'); ?></th>
                  <th><?php echo __('Days')?></th>
                  <th><?php echo __('Start'); ?></th>
                  <th><?php echo __('End'); ?></th>
                  <th><?php echo __('Est Resource'); ?></th>
                  <th><?php echo __('Est Manhours'); ?></th>
                  <!-- <th><?php echo __('Effort Weightage'); ?></th>    -->
                  <!-- <th><?php echo __('approved_by'); ?></th>   
                  <th><?php echo __('publish'); ?></th> -->
                  <th></th>
                </tr>
                <?php foreach ($planResult as $projectOverallPlan) { 
                  ?>
                    <tr class="warning">
                      <?php $types = array(0=>'Lot',1=>'Process'); ?>
                      <td><?php echo h($types[$projectOverallPlan['ProjectOverallPlan']['plan_type']]); ?>&nbsp;</td>
                      <td><?php echo h($projectOverallPlan['ProjectOverallPlan']['lot_process']); ?>&nbsp;</td>
                      <td><?php echo h($projectOverallPlan['ProjectOverallPlan']['qc']); ?>&nbsp;</td>
                      <td><?php echo h($projectOverallPlan['ProjectOverallPlan']['estimated_units']); ?>&nbsp;
                        <small><p><?php echo h($projectOverallPlan['ProjectOverallPlan']['actual_units']); ?>*&nbsp;</p></small>
                      </td>
                      <td><?php echo h($projectOverallPlan['ProjectOverallPlan']['overall_metrics']); ?>&nbsp;</td>
                      <td><?php echo h($projectOverallPlan['ProjectOverallPlan']['days']); ?>&nbsp;</td>
                      <td><?php echo h($projectOverallPlan['ProjectOverallPlan']['start_date']); ?>&nbsp;</td>
                      <td><?php echo h($projectOverallPlan['ProjectOverallPlan']['end_date']); ?>&nbsp;</td>
                      <td><?php echo h($projectOverallPlan['ProjectOverallPlan']['estimated_resource']); ?>&nbsp;</td>
                      <td><?php echo h($projectOverallPlan['ProjectOverallPlan']['estimated_manhours']); ?>&nbsp;</td>                                            
                      <td width="90">
                        <div class="btn-group">
                          <?php                         
                            echo $this->Html->link('Edit',"javascript:void(0);",
                                array(
                                  'class'=>'btn btn-xs btn-default',
                                  'onclick'=>'openmodel(
                                    "project_overall_plans",
                                    "edit",
                                    "'.$projectOverallPlan['ProjectOverallPlan']['id'].'",
                                    "'.$milestone['Milestone']['project_id'].'",
                                    "'.$milestone['Milestone']['id'].'",
                                    "'.$activity['ProjectActivity']['id'].'"
                                  )'
                                )); 
                            // echo $this->Html->link('Edit',array('action'=>'project_','model'=>'ProjectEstimate','id'=>$head['ProjectEstimate']['id']),array('class'=>'btn btn-warning btn-xs'));
                            // echo $this->Html->link('Details',array('action'=>'add_details',
                            //   'model'=>'ProjectProcessPlan',
                            //   'id'=>$projectOverallPlan['ProjectOverallPlan']['id'],
                            //   'project_id'=>$milestone['Milestone']['project_id']
                            // ),array('class'=>'btn btn-info btn-xs'));

                                            
                            // echo $this->Html->link('Add Details',"javascript:void(0);",
                            //   array(
                            //     'class'=>'btn btn-xs btn-default',
                            //     'onclick'=>'openmodel(
                            //       "project_process_plans",
                            //       "add_ajax",
                            //       "",
                            //       "'.$milestone['Milestone']['project_id'].'",
                            //       "'.$milestone['Milestone']['id'].'",
                            //       "",
                            //       "'.$projectOverallPlan['ProjectOverallPlan']['id'].'"
                            //     )'
                            //   )); 
                          
                          
                            echo $this->Html->link(' - ',array('action'=>'delete_childrecs',
                              'model'=>'ProjectOverallPlan',
                              'id'=>$projectOverallPlan['ProjectOverallPlan']['id'],
                              'milestone_id'=>$milestone['Milestone']['id'],
                              'project_id'=>$milestone['Milestone']['project_id']
                            ),array('confirm'=>'Are you sure you want to delete this plan? ', 'class'=>'btn btn-danger btn-xs'));
                          
                          // echo $this->Html->link('Edit',array(),array('class'=>'btn btn-warning btn-xs'));
                          // echo $this->Html->link('Delete',array(),array('class'=>'btn btn-danger btn-xs'));
                          ?>

                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="12"><small>* Total units count from the Project Files</small></td>
                    </tr>

                    <?php if($projectOverallPlan['DetailedPlan']){ ?>
  <style type="text/css">
    /*.chosen-select{min-width: 100px; max-width: 101px}*/
    .nre {padding-left: 20px}
    .nre .radio input[type="radio"]{ margin: 3px 10px 0 -15px !important}
  </style>                    
                      <tr><td colspan="12">
                        <h4>Detailed Plan</h4>
                          <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
                            <tr> 
                              <th><?php echo __('Seq#'); ?></th>                               
                              <th><?php echo __('Process'); ?></th>
                              <th><?php echo __('Process Type'); ?></th>
                              <th><?php echo __('Software'); ?></th>
                              <th><?php echo __('Est Units'); ?>
                                <?php if($projectOverallPlan['DetailedPlan']['cal_type'] == 0)echo "Units/Hr";
                                else echo "Hrs/Unit";?>
                              </th>
                              <th><?php echo __('Unit Rate'); ?></th>
                              <th><?php echo __('Overall Metrics'); ?></th>
                              <th><?php echo __('Start'); ?></th>
                              <th><?php echo __('End'); ?></th>
                              <th><?php echo __('Est Resource'); ?></th>
                              <th><?php echo __('Est Manhours'); ?></th>
                              <th><?php echo __('Effort Weightage'); ?></th>
                              <th><?php echo __('Dependancy'); ?></th>
                              
                              <!-- <th><?php echo __('prepared_by'); ?></th>   
                              <th><?php echo __('approved_by'); ?></th>    -->
                              <!-- <th><?php echo __('publish'); ?></th>    -->
                              <th width="100"></th>
                            </tr>
                      <?php 
                      $eu_total = 0;
                      $er_total = 0;
                      $emr_total = 0;
                      if($projectOverallPlan['DetailedPlan']){

                          foreach ($projectOverallPlan['DetailedPlan'] as $projectProcessPlan) { 
                            $eu_total = $eu_total + $projectProcessPlan['ProjectProcessPlan']['estimated_units'];
                            $er_total = $er_total + $projectProcessPlan['ProjectProcessPlan']['estimated_resource'];
                            // $emr_total = $emr_total + $projectProcessPlan['ProjectProcessPlan']['estimated_manhours'];
                          ?>
                                <tr>
                                    <!-- <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['sequence']); ?>&nbsp;</td> -->
                                    <td><a href="#" id="evi-<?php echo $projectProcessPlan['ProjectProcessPlan']['id'] ?>"><?php echo h($projectProcessPlan['ProjectProcessPlan']['sequence']); ?>&nbsp;</a>&nbsp;</td>
                                    <script>
                                    
                                    $(document).ready(function() {$('#evi-<?php echo $projectProcessPlan['ProjectProcessPlan']['id'] ?>').editable({
                                         type:  'text',
                                         pk:    '<?php echo $projectProcessPlan['ProjectProcessPlan']['id'] ?>',
                                         name:  'data.ProjectProcessPlan.sequence',
                                         url:   '<?php echo Router::url('/', true);?>project_process_plans/inplace_edit_sequence',  
                                         title: 'Change Sequence',
                                         placement : 'right'
                                      });
                                    });
                                    
                                    </script>

                                    <td>
                                      <!-- <?php echo $projectProcessPlan['ProjectProcessPlan']['id'];?> -  -->
                                      <?php echo h($projectProcessPlan['ProjectProcessPlan']['process']); ?>&nbsp;</td>
                                    <td  style="min-width: 120px; padding-left: 20px" class="nre"><?php 
                                    $qc  = array(0=>'General',1=>'QC',2=>'Merging');
                                    echo h($qc[$projectProcessPlan['ProjectProcessPlan']['qc']]); ?>&nbsp;</td>
                                    <td><?php echo h($listOfSoftwares[$projectProcessPlan['ProjectProcessPlan']['list_of_software_id']]); ?>&nbsp;</td>
                                    <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['estimated_units']); ?>&nbsp;</td>
                                    <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['unit_rate']); ?>&nbsp;</td>
                                    <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['overall_metrics']); ?>
                                      <?php echo ($projectOverallPlan['ProjectOverallPlan']['cal_type']?'Hr/Units':'Units/Hr')?>&nbsp;</td>
                                    <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['start_date']); ?>&nbsp;</td>
                                    <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['end_date']); ?>&nbsp;</td>
                                    <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['estimated_resource']); ?>&nbsp;</td>
                                    
                                    <td>
                                      <?php 
                                      if($projectOverallPlan['ProjectOverallPlan']['cal_type'] == 0){
                                        echo h($projectProcessPlan['ProjectProcessPlan']['estimated_units'] / $projectProcessPlan['ProjectProcessPlan']['overall_metrics']);
                                        $emr_total = $emr_total + ($projectProcessPlan['ProjectProcessPlan']['estimated_units'] / $projectProcessPlan['ProjectProcessPlan']['overall_metrics']);
                                      }else{
                                        echo h($projectProcessPlan['ProjectProcessPlan']['estimated_units'] * $projectProcessPlan['ProjectProcessPlan']['overall_metrics']);
                                        $emr_total = $emr_total + ($projectProcessPlan['ProjectProcessPlan']['estimated_units'] * $projectProcessPlan['ProjectProcessPlan']['overall_metrics']);
                                      }


                                      ?>
                                      &nbsp;</td>
                                      <td><?php echo h($process_weightage[$projectProcessPlan['ProjectProcessPlan']['id']]); ?>%&nbsp;</td>

                                    <td>
                                      <?php
                                      // if($projectProcessPlan['ProjectProcessPlan']['dependancy_id']){
                                      //   echo $existingprocesses[$projectProcessPlan['ProjectProcessPlan']['dependancy_id']];
                                      // }else{
                                        echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.dependancy_id',array('onchange'=>'updatedepedancy(this.value,"'.$projectProcessPlan['ProjectProcessPlan']['id'].'")', 'label'=>false, 'default'=>$projectProcessPlan['ProjectProcessPlan']['dependancy_id'], 'id'=>false,  'options'=>$existingprocesses[$projectOverallPlan['ProjectOverallPlan']['id']]));
                                      // }
                                        

                                       ?></td>
                                    <!-- <td><?php echo h($PublishedEmployeeList[$projectProcessPlan['ProjectProcessPlan']['prepared_by']]); ?>&nbsp;</td>
                                    <td><?php echo h($PublishedEmployeeList[$projectProcessPlan['ProjectProcessPlan']['approved_by']]); ?>&nbsp;</td>
                                    <td width="60">
                                      <?php if($projectProcessPlan['ProjectProcessPlan']['publish'] == 1) { ?>
                                      <span class="fa fa-check"></span>
                                      <?php } else { ?>
                                      <span class="fa fa-ban"></span>
                                      <?php } ?>&nbsp;
                                    </td> -->
                                    <td>
                                      <div class="btn-group">
                                      <?php
                                        echo $this->Html->link('Edit',"javascript:void(0);",
                                            array(
                                              'class'=>'btn btn-xs btn-default',
                                              'onclick'=>'openmodel(
                                                "project_process_plans",
                                                "edit",
                                                "'.$projectProcessPlan['ProjectProcessPlan']['id'].'",
                                                "'.$milestone['Milestone']['project_id'].'",
                                                "'.$milestone['Milestone']['id'].'",
                                                "'.$activity['ProjectActivity']['id'].'",
                                                "'.$projectProcessPlan['ProjectProcessPlan']['project_overall_plan_id'].'"
                                              )'
                                            )); 
                                        // echo $this->Html->link('Edit',array('action'=>'project_','model'=>'ProjectEstimate','id'=>$head['ProjectEstimate']['id']),array('class'=>'btn btn-warning btn-xs'));
                                        $cnt = $this->requestAction(array('action'=>'plan_del_check',$projectProcessPlan['ProjectProcessPlan']['id']));
                                        if($cnt == 0){
                                          echo $this->Html->link('Delete',array('action'=>'delete_childrecs',
                                            'model'=>'ProjectProcessPlan',
                                            'id'=>$projectProcessPlan['ProjectProcessPlan']['id'],
                                            'project_id'=>$milestone['Milestone']['project_id']
                                          ),array('confirm'=>'Are you sure you want to delete this plan?', 'class'=>'btn btn-danger btn-xs'));  
                                        }
                                        
                                      ?>
                                    </div>
                                    </td>
                                </tr>

                              
                          <?php } ?>
                      <tr>
                        <th colspan="4">Sub Total : </th>
                        <th>
                          <?php echo $eu_total ?> / <small><?php echo h($projectOverallPlan['ProjectOverallPlan']['estimated_units']); ?></small> 
                          <div>
                            <small>
                              <?php echo round($eu_total * 100 / $projectOverallPlan['ProjectOverallPlan']['estimated_units']) ?>% 
                              <?php if($eu_total * 100 / $projectOverallPlan['ProjectOverallPlan']['estimated_units'] > 100) { ?>
                                <i class="fa fa-long-arrow-up text-danger pull-right" aria-hidden="true"></i>
                              <?php }else { ?>
                                <i class="fa fa-long-arrow-down text-success pull-right" aria-hidden="true"></i>
                              <?php } ?>
                            </small>
                          </div> 
                        </th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <!-- <th></th> -->
                        <!-- <th></th>
                        <th></th> -->
                        <th>
                          <?php echo $er_total ?> / <small><?php echo h($projectOverallPlan['ProjectOverallPlan']['estimated_resource']); ?></small> 
                          <div>
                            <small>
                              <?php echo round($er_total * 100 / $projectOverallPlan['ProjectOverallPlan']['estimated_resource']) ?>% 
                              <?php if($er_total * 100 / $projectOverallPlan['ProjectOverallPlan']['estimated_resource'] > 100) { ?>
                                <i class="fa fa-long-arrow-up text-danger pull-right" aria-hidden="true"></i>
                              <?php }else { ?>
                                <i class="fa fa-long-arrow-down text-success pull-right" aria-hidden="true"></i>
                              <?php } ?>
                            </small>
                          </div> 
                        </th>
                        <th><?php echo $emr_total ?> / <small> <?php echo h($projectOverallPlan['ProjectOverallPlan']['estimated_manhours']); ?></small> 
                          <div>
                            <small>
                              <?php echo round($emr_total * 100 / $projectOverallPlan['ProjectOverallPlan']['estimated_manhours']) ?>% 
                              <?php if($emr_total * 100 / $projectOverallPlan['ProjectOverallPlan']['estimated_manhours'] > 100) { ?>
                                <i class="fa fa-long-arrow-up text-danger pull-right" aria-hidden="true"></i>
                              <?php }else { ?>
                                <i class="fa fa-long-arrow-down text-success pull-right" aria-hidden="true"></i>
                              <?php } ?>
                            </small>
                          </div> 
                          
                        </th>
                        
                        
                        
                        
                        <th></th>
                        <th><?php echo $this->Html->link('Delete All',array('action'=>'delete_overall_plans','project_id'=>$milestone['Milestone']['project_id'], 'milestone_id'=>$milestone['Milestone']['id']),array('confirm'=>'Are you sure you want to delete all plans? ', 'class'=>'btn btn-danger btn-xs'))?></th>
                      </tr>
                      </table>
                    <?php } ?>
                  <?php }else{ ?>
                        <tr><td colspan="13">No Plan Found

                          <span class="pull-right btn btn-xs btn-success" onclick="addpoprow()">Add new detailed plan</span>
  <?php 
  $eu_total = 0;
  $er_total = 0;
  $emr_total = 0;
  ?>
                        </td>
                      </tr>
                      <?php } ?>
                      <tr><td colspan="13">
                         <?php echo $this->Form->create('ProjectProcessPlan',array('controller'=>'project_process_plans','action'=>'add_ajax'),array('id'=>'ProjectOverallPlan'.$pop, 'role'=>'form','class'=>'form','default'=>true)); ?> 
                              <table class="table table-condensed table-bordered">
                                  <thead>
                                      <tr>
                                          <th width="90">Seq#</th>
                                          <th>Process</th>
                                          <th>Process Type</th>
                                          <th>Software</th>
                                          <th>Est Units</th>
                                          <th>Units Rate</th>
                                          <th>Overall Metrics</th>
                                          <th>Hours</th>
                                          <th>Days</th>                                            
                                          <th>Est Resource</th>
                                          <!-- <th width="195">Date Range</th> -->
                                          <th>Start Date</th>
                                          <th>End Date</th>
                                          <th>Dependancy</th>
                                          <th></th>               
                                      </tr>
                                  </thead>
                                  <tbody>
                                    
            <tr>
            <?php $planTypes = array(0=>'Field',1=>'Production'); ?>
            <td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.sequence',array('default'=>$por, 'label'=>false))?></td>
            <td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.process',array('label'=>false))?></td>
            <td  style="min-width: 120px; padding-left: 20px" class="nre">
              <?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.qc',array(
              'separator'=>'<br />', 'label'=>false,'legend'=>false,'options'=>array(0=>'General',1=>'QC',2=>'Merging'),'type'=>'radio','default'=>0))?></td>
            <td  style="min-width: 160px"><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.list_of_software_id',array('label'=>false))?></td>
            <td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.estimated_units',array('label'=>false, 
              // 'onchange'=>'calcet'.$pop.'('.$pop.')'
            ))?></td>
            <td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.unit_rate',array('label'=>false))?></td>
            <td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.overall_metrics',array('label'=>false))?></td>
            <td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.hours',array('label'=>false))?></td>
            <td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.days',array('label'=>false))?></td>            
            <td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.estimated_resource',array('default'=>$er_total, 'label'=>false, 'onchange'=>'calcer'.$pop.'('. $projectOverallPlan['ProjectOverallPlan']['estimated_resource'].','.$er_total.','.$pop.')'))?></td>
            <td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.start_date',array('label'=>false))?></td>
            <td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.end_date',array('label'=>false))?></td>
            <td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.dependancy_id',array('label'=>false,'type'=>'text'))?></td>
            <!-- <td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.estimated_manhours',array('label'=>false))?></td> -->

            <td></td>
                                      </tr>
                                      
                                      <tr id="addpophere-<?php echo $pop;?>-<?php echo $por;?>"></tr>
                                  </tbody>
                              </table>
                              
                              <?php echo $this->Form->hidden('ProjectProcessPlan.'.$pop.'.'.$por.'.project_overall_plan_id',array('default'=>$projectOverallPlan['ProjectOverallPlan']['id']))?>
                              <?php echo $this->Form->hidden('ProjectProcessPlan.'.$pop.'.'.$por.'.project_id',array('default'=>$milestone['Milestone']['project_id']))?>
                              <?php echo $this->Form->hidden('ProjectProcessPlan.'.$pop.'.'.$por.'.milestone_id',array('default'=>$milestone['Milestone']['id']))?>
                              
                          <?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#projectProcessPlans_ajax','async' => 'false')); ?>
                          <div id="extraunitalert<?php echo $pop?>" class="hide text-danger"></div>
                          <div id="extraresalert<?php echo $pop?>" class="hide text-danger"></div>
                          <span class="btn btn-success btn-xs pull-right" onclick="addpoprow<?php echo $pop?>(<?php echo $pop;?>,<?php echo $por;?>);" > + </span>
                          <?php echo $this->Form->end(); ?>
                          <?php echo $this->Js->writeBuffer();?>

                        
                          


                          <?php echo $this->Form->hidden('popcount',array('id'=>'popcount'.$pop, 'default'=>$por));?>
                              

                              <script type="text/javascript">
                                 
                                $().ready(function(){
                                  // $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").addClass('addtotal<?php echo $pop?>');
                                  $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").addClass('dayscal<?php echo $pop?>');
                                  $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").addClass('addtotal<?php echo $pop?> ers<?php echo $pop?>');
                                  $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedUnits").addClass('addtotal<?php echo $pop?> units<?php echo $pop?>');
                                  $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>OverallMetrics").addClass('addtotal<?php echo $pop?>');
                                  $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").addClass('addtotal<?php echo $pop?>');
                                  
                                  $(".addtotal<?php echo $pop?>").on('change',function(){
                                      
                                      <?php if($projectOverallPlan['ProjectOverallPlan']['cal_type'] == 0){  ?>
                                        var hours = (parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedUnits").val()) / parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>OverallMetrics").val()));
                                      <?php }else{ ?>
                                        var hours = (parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedUnits").val()) * parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>OverallMetrics").val()));

                                      <?php }?>
                                      
                                      $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val(hours);

                                      var total = parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val() * $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val())
                                      
                                      $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedManhours").val(total);
                                      

                                      var mp = parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val()) / 7 / parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val());
                                      
                                      $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val(Math.round(mp));

                                  });                                  

                                $(".dayscal<?php echo $pop?>").on('change',function(){
                                      var mp = parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val()) / 7 / parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val());
                                      // console.log(">>" + mp);
                                      $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val(Math.round(mp));


                                      $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_holidays/null/<?php echo $milestone['Milestone']['project_id']?>/start_date:" + btoa($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>StartDate").val()) +'/days:'+ $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val() , function(data) {
                                            console.log(data);
                                            $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EndDate").val(data);

                                            // var mp = parseInt($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val()) / 7 / parseInt($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val());
                                            // $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val(Math.round(mp));
                                      });

                                  });

                                $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>StartDate").on('change',function(){
                                  $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_holidays/null/<?php echo $milestone['Milestone']['project_id']?>/start_date:" + btoa($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>StartDate").val()) +'/days:'+ $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val() , function(data) {
                                            console.log(data);
                                            $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EndDate").val(data);

                                            // var mp = parseInt($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val()) / 7 / parseInt($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val());
                                            // $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val(Math.round(mp));
                                    });
                                });

                                });


                                $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>StartDate").datepicker({
                                    changeMonth: true,
                                    changeYear: true,
                                    // dateFormat:'YYYY-MM-DD', 
                                    minDate : '<?php echo date("Y-m-d",strtotime($projectOverallPlan['ProjectOverallPlan']['start_date'])) ;?>',
                                    maxDate : '<?php echo date("Y-m-d",strtotime($projectOverallPlan['ProjectOverallPlan']['end_date'])) ;?>',
                                    locale: {
                                        format: 'YYYY-MM-DD'
                                    },
                                    autoclose:true,                                      
                                  });

                                

                                  // function calcet<?php echo $pop?>(pop){
                                  //   // var i = 0;
                                  //   // $(".units<?php echo $pop?>").each(function(){
                                  //   //     i = i + parseFloat(this.value);
                                  //   // });
                                  //   // var total = <?php echo $eu_total?> + parseFloat(i);
                                    
                                  //   // if(parseFloat(total) > <?php echo $projectOverallPlan['ProjectOverallPlan']['estimated_units']?>){
                                  //   //   $("#extraunitalert<?php echo $pop?>").html('Total units exceeding estimated units').removeClass('hide').addClass('show');
                                  //   // }else{
                                  //   //   $("#extraunitalert<?php echo $pop?>").removeClass('show').addClass('hide');
                                  //   // }  
                                  // }

                                  function calcer<?php echo $pop?>(pop){
                                    var i = 0;
                                    $(".ers<?php echo $pop?>").each(function(){
                                        i = i + parseFloat(this.value);                                        
                                    });
                                    var total = <?php echo $er_total?> + parseFloat(i);
                                    
                                    if(parseFloat(total) > <?php echo $projectOverallPlan['ProjectOverallPlan']['estimated_resource']?>){
                                      $("#extraresalert<?php echo $pop?>").html('Total units exceeding estimated resource').removeClass('hide').addClass('show');
                                    }else{
                                      $("#extraresalert<?php echo $pop?>").removeClass('show').addClass('hide');
                                    }  
                                  }

                                  function addpoprow<?php echo $pop?>(pop,por){
                                      var por = parseFloat($("#popcount<?php echo $pop;?>").val());
                                      
                                      var bunitspre = <?php echo $projectOverallPlan['ProjectOverallPlan']['estimated_units']?>-<?php echo $eu_total ?>;
                                      var i = 0;
                                      $(".units<?php echo $pop?>").each(function(){
                                          i = i + parseFloat(this.value);                                          
                                      });
                                      var bunits = bunitspre - i;

                                      // resource
                                      var bresspre = <?php echo $projectOverallPlan['ProjectOverallPlan']['estimated_resource']?>-<?php echo $er_total ?>;
                                      var i = 0;
                                      $(".ers<?php echo $pop?>").each(function(){
                                          i = i + parseFloat(this.value);                                          
                                      });
                                      var bers = bresspre - i;

                                      // console.log(pop + "- " + por);
            $("#addpophere-<?php echo $pop;?>-"+por).load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/addpor/' + pop + '/' + por + '/<?php echo $projectOverallPlan['ProjectOverallPlan']['cal_type']?>/start:<?php echo $projectOverallPlan['ProjectOverallPlan']['start_date']?>/end:<?php echo $projectOverallPlan['ProjectOverallPlan']['end_date']?>/project_id:<?php echo $milestone['Milestone']['project_id']?>/milestone_id:<?php echo $milestone['Milestone']['id']?>/op:<?php echo $projectOverallPlan['ProjectOverallPlan']['id']?>/overall_metrics:<?php echo $projectOverallPlan['ProjectOverallPlan']['overall_metrics']?>/bunits:'+bunits + '/bers:'+bers + '/est_units:<?php echo $projectOverallPlan['ProjectOverallPlan']['estimated_units'];?>', function(response, status, xhr) {                                    
            if (response != "") {
            $('#addpophere-<?php echo $pop;?>-'+por).html(response).after('<tr id="addpophere-<?php echo $pop?>-'+(por+1)+'"></tr>');
            $("#popcount<?php echo $pop;?>").val(por+1);
            } else {               
            }
            });
                                  }

                                  function delpoprow(pop,por){
                                      console.log('delpoprow'+pop+'-'+(por-1));
                                      $('#addpophere-'+pop+'-'+(por-1)).remove();
                                  }

                                      // $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EndDate").datepicker({
                                      //   changeMonth: true,
                                      //   changeYear: true,
                                      //   dateFormat:'yy-mm-dd',                                          
                                      // }).on("changeDate", function() {
                                      //   var por = parseInt($("#popcount<?php echo $pop;?>").val());
                                      //   chkholiday(<?php echo $pop ?>,por,this);
                                      //   return false;
                                      // });
                                  
                                  // function adddetail(pop,pop){
                                  //     console.log('adddetail'+pop);
                                  //     $("#adddetails-<?php echo $pop;?>-"+(pop)).load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/addpor/' + pop + '/' + (pop), function(response, status, xhr) {                                    
                                  //         if (response != "") {                                        
                                  //             $('#adddetails-<?php echo $pop;?>-'+(pop)+'').html(response).after('<tr id="addpophere-<?php echo $pop?>-'+(pop+1)+'">'+pop+'</tr>');
                                  //             // $("#popcount<?php echo $pop;?>").val(pop+1);
                                  //         } else {               
                                  //         }
                                  //     });
                                  // }

                                  // function addporrow<?php echo $pop?><?php echo $pop?>(pop,pop,por){
                                  //     console.log('addporrow'+pop);
                                  //     var por = parseInt($("#porcount<?php echo $pop;?>"+pop).val());
                                  //     $("#addporhere-<?php echo $pop;?>-"+(por)).load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/addpor/' + key + '/' + pop + por , function(response, status, xhr) {                                    
                                  //         if (response != "") {                                        
                                  //             $('#addporhere-<?php echo $pop;?>-'+pop+'-'+por).html(response).after('<tr id="addporhere-<?php echo $pop?>-'+ pop + (por+1)+'"></tr>');
                                  //             $("#porcount<?php echo $pop;?>"+pop).val(por+1);
                                  //         } else {
                                              
                                  //         }
                                  //     });
                                  // }

                                  // function delporrow(key,por){
                                  //     console.log('delporrow'+por);
                                  //     $('#addporhere-'+key+'-'+ por).remove();
                                  // }  

                              </script>


                      </td></tr>
                 <?php $eu_total = $er_total = $emr_total  = 0; ?>   
                <?php $pop++; } ?>
                  <tr class="warning">
                  <th colspan="12" class="text-right"><h4>Total : <?php echo $this->Number->currency($subT,'INR. ');?></h4></th>                    
                </tr>
              </table>

  <script type="text/javascript">
    // $(".chosen-select").chosen();
    $().ready(function(){
      $(".chosen-select").chosen('destroy');
      $(".chosen-select").chosen({width: "100%"});  
    });
    
  </script>    

</div>
    