<?php echo $this->Html->script(array(
    'js-xlsx-master/dist/xlsx.core.min', 
    'Blob.js-master/Blob.min', 
    'FileSaver.js-master/FileSaver.min', 
    'TableExport-master/src/stable/js/tableexport.min')); ?>
  <?php echo $this->fetch('script'); ?>

<style type="text/css">
    .modal-dialog{width: 95%}
    #dailyprotabs li{min-width: 19.2%; margin: 5px !important}
    #dailyprotabs li > a{border-radius:4px !important;}    
    .box-body{ background: #f8f8f8 }
    .box-header, .box-footer{background: #e7e8e8}
    .progress.xs, .progress-xs, .progress.xs .progress-bar, .progress-xs .progress-bar{background: #f98484}
    .nav-tabs-custom>.nav-tabs>li>a {background: #ffffff;}
    .nav-tabs-custom>.nav-tabs{background: #d8d8d8}
    .nav-tabs-custom>.tab-content{background: #efeded}
    .nav-tabs-custom>.nav-tabs>li{margin-bottom: 4px !important; margin-right: 0px !important}
    .nav-tabs-custom>.nav-tabs>li.active>a{
      border: 0px !important
    }

    .ui-tabs-panel{
      z-index: 1;
    }
    .box-header h3:before{display: none;}
    /*.fa-plus:before{display: none;}*/

    /*.chosen-select{width: 100% !important}*/
</style>
<script type="text/javascript">
  $().ready(function(){
    // $('.chosen').attr('width:100%');
    // $("select").chosen({width: "100%"})
    $(".chosen-select").chosen('destroy');
    $(".chosen-select").chosen({width: "100%"});
    $("#<?php echo $milestone["Milestone"]["id"];?>-busy-indicator").hide();
  });
</script>
<style type="text/css">
 
</style>
<h2 style="margin-left:10px ">Milestones</h2>
<!-- <div class="row"> -->
<?php $pop = 0; ?>
<?php 
$key = 0;
foreach ($project_details as $milestone) { 
  
$por = 0;  
$timesheetGraph = array();
$resourceGraph = array();
$eu_total = 0; 
$er_total = 0; 
$emr_total = 0;
?>
  <!-- <div class="col-md-12"> -->
    <?php if($milestone['Milestone']['current_status'] > 0){ ?>
      <?php if($milestone['Milestone']['current_status'] == 1){ ?>
          <div class="box box-success resizable">
      <?php }else{ ?>
          <div class="box box-primary resizable collapsed-box">
      <?php } ?>
    <?php }else{ ?>
      <!-- <div class="box box-primary resizable"> -->
      <div class="box box-primary resizable collapsed-box">
    <?php } ?>
  
    
            <div class="box-header with-border" data-widget="collapse" onclick="membertabsopenclose_<?php echo str_replace('-','_',$milestone['Milestone']['id']);?>();">
              <h3 class="box-title"><?php echo $milestone['Milestone']['title']?>
                <small class="text-right"><?php echo $milestone['Milestone']['start_date']?> - <?php echo $milestone['Milestone']['end_date']?></small>                
              </h3>
              <br />
              <small class="label label-success"><?php echo $currentStatuses[$milestone['Milestone']['current_status']]?> stage</small>
              <small class="label label-info"><?php if($milestone['ProjectResource'])echo count($milestone['ProjectResource'])?></small>
              <small class="label label-danger"><?php echo $this->Number->currency($milestone['Estimated_milestone_cost']['ProjectEstimate']['milestone_estimate'],'INR. ')?></small>
              <small class="label label-success"><?php echo $this->requestAction(array('action'=>'project_files_milestone_count',$milestone['Milestone']['project_id'],$milestone['Milestone']['id']));?></small>
              <div class="btn-group box-tools pull-right">
                <button type="button" class="btn btn-xs btn-default" data-widget="collapse"><i class="fa fa-plus"></i></button>
                <?php                         
                  echo $this->Html->link('Edit',"javascript:void(0);",
                      array(
                        'class'=>'btn btn-xs btn-warning',
                        'onclick'=>'openmodel(
                          "milestones",
                          "edit",
                          "'.$milestone['Milestone']['id'].'",
                          "'.$project_id.'",
                          null,
                          null
                        )'
                      )); 
                ?>
                <?php                         
                  echo $this->Html->link('Add Change Request',"javascript:void(0);",
                      array(
                        'class'=>'btn btn-xs btn-info',
                        'onclick'=>'openmodel(
                          "milestones",
                          "edit",
                          "'.$milestone['Milestone']['id'].'",
                          "'.$project_id.'",
                          null,
                          null
                        )'
                      )); 
                ?>
                
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="">
              <?php if(!$milestone['Milestone']['publish']){ ?>                
                <div class="alert alert-danger">This milestone is not publushed. Files will not be visible on users dashboard unless milestone is published</div>
              <?php }else{
                // echo "2";
              }?>
              <table class="table table-responsive table-bordered">
                <tr><td><?php echo __('Title'); ?></td>
                <td>
                  <?php echo h($milestone['Milestone']['title']); ?>
                  &nbsp;
                </td></tr>
                <!-- <tr><td><?php echo __('Details'); ?></td>
                <td>
                  <?php echo h($milestone['Milestone']['details']); ?>
                  &nbsp;
                </td></tr>
                <tr><td><?php echo __('Challenges'); ?></td>
                <td>
                  <?php echo h($milestone['Milestone']['challenges']); ?>
                  &nbsp;
                </td></tr> -->
                <tr><td><?php echo __('Estimated Cost'); ?></td>
                <td>
                  <?php echo $currencies[$milestone['Milestone']['currency_id']]; ?>.<?php echo h($milestone['Milestone']['estimated_cost']); ?>
                  &nbsp;
                </td></tr>
                <tr><td><?php echo __('Start Date'); ?></td>
                <td>
                  <?php echo h($milestone['Milestone']['start_date']); ?>
                  &nbsp;
                </td></tr>
                <tr><td><?php echo __('End Date'); ?></td>
                <td>
                  <?php echo h($milestone['Milestone']['end_date']); ?>
                  &nbsp;
                </td></tr>
                <tr><td><?php echo __('Current Status'); ?></td>
                <td>
                  <?php echo h($currentStatuses[$milestone['Milestone']['current_status']]); ?>
                  &nbsp;
                </td></tr>
                <tr><td><?php echo __('Unit'); ?></td>
                <td>
                  <?php 
                  $units = array('KLM','GRID','PO');
                  echo h($deliverableUnits[$milestone['Milestone']['unit_id']]); ?>
                  &nbsp;
                </td></tr>
                <tr><td><?php echo __('Type'); ?></td>
                <td>
                  <?php 
                  // $milestoneTypes = array('KLM','Area','Files','Process','Tower');
                  echo h($milestoneTypes[$milestone['Milestone']['milestone_type_id']]); ?>
                  &nbsp;
                </td></tr>
                <tr><td><?php echo __('File Type'); ?></td>
                <td>
                  <?php 
                  // $milestoneTypes = array('KLM','Area','Files','Process','Tower');
                  echo h($milestone['Milestone']['single_batch']?'Batch':'Single'); ?>
                  &nbsp;
                </td></tr>
              </table>
          <table class="table table-responsive table-bordered">
            <tr>
              <th>Actual Start Date</th>
              <th>Actual End Date</th>
              <th>Actual Man Hrs</th>
              <th>Actual Units</th>
              <th>Unit/ time</th>
            </tr>
            <tr>
              <th><?php echo h($milestone['Milestone']['start_date']); ?></th>
              <th><?php echo h($milestone['Milestone']['end_date']); ?></th>
              <th>340</th>
              <th>10</th>
              <th>34</th>
            </tr>
          </table>  

      <div clasa="row">
        <div class="col-md-12 no-padding no-margin">
          <br /><br />          
          <div id="<?php echo $milestone['Milestone']['id'];?>_tabs" class="nav-tabs-custom">
            <ul class="">
    <li aria-controls='over_all_plan_<?php echo $milestone['Milestone']['id'];?>'><?php echo $this->Html->link('Overall Plan',array('action'=>'over_all_plan',$milestone['Milestone']['project_id'],$milestone['Milestone']['id'],$pop,$por), array('escape' => false));?></li>    
    <li aria-controls='team_board_<?php echo $milestone['Milestone']['id'];?>'><?php echo $this->Html->link('Team Board',array('action'=>'team_board',$milestone['Milestone']['project_id'],$milestone['Milestone']['id'],$pop,$por), array('escape' => false));?></li>    
    <li aria-controls='assign_process_<?php echo $milestone['Milestone']['id'];?>'><?php echo $this->Html->link('Assign Process',array('action'=>'assign_process',$milestone['Milestone']['project_id'],$milestone['Milestone']['id'],$pop,$por), array('escape' => false));?></li>
    <li aria-controls='file_categories_<?php echo $milestone['Milestone']['id'];?>'><?php echo $this->Html->link('File Categories',array('controller'=>'file_categories', 'action'=>'add_ajax',$milestone['Milestone']['project_id'],$milestone['Milestone']['id'],$pop,$por), array('escape' => false));?></li>
    <li aria-controls='project_files_<?php echo $milestone['Milestone']['id'];?>'><?php echo $this->Html->link('Files',array('controller'=>'project_files', 'action'=>'project_files',$milestone['Milestone']['project_id'],$milestone['Milestone']['id'],$pop,$por), array('escape' => false));?></li>
    <li aria-controls='estimated_cost_<?php echo $milestone['Milestone']['id'];?>'><?php echo $this->Html->link('Estimated Costs',array('action'=>'est_costs',$milestone['Milestone']['project_id'],$milestone['Milestone']['id'],$pop,$por), array('escape' => false));?></li>
    <li aria-controls='inbound_pos_<?php echo $milestone['Milestone']['id'];?>'><?php echo $this->Html->link('POs-IN',array('action'=>'inbound_pos',$milestone['Milestone']['project_id'],$milestone['Milestone']['id'],$pop,$por), array('escape' => false));?></li>
    <li aria-controls='outbound_pos_<?php echo $milestone['Milestone']['id'];?>'><?php echo $this->Html->link('POs-Out',array('action'=>'outbound_pos',$milestone['Milestone']['project_id'],$milestone['Milestone']['id'],$pop,$por), array('escape' => false));?></li>
    <li aria-controls='invoices_<?php echo $milestone['Milestone']['id'];?>'><?php echo $this->Html->link('Invoices',array('action'=>'invoices',$milestone['Milestone']['project_id'],$milestone['Milestone']['id'],$pop,$por), array('escape' => false));?></li>
    <li aria-controls='payment_received_<?php echo $milestone['Milestone']['id'];?>'><?php echo $this->Html->link('Payment Received',array('action'=>'payment_received',$milestone['Milestone']['project_id'],$milestone['Milestone']['id'],$pop,$por), array('escape' => false));?></li>
    <li aria-controls='project_errors_<?php echo $milestone['Milestone']['id'];?>'><?php echo $this->Html->link('Errors',array('action'=>'project_errors',$milestone['Milestone']['project_id'],$milestone['Milestone']['id'],$pop,$por), array('escape' => false));?></li>
    <li aria-controls='project_checklists_<?php echo $milestone['Milestone']['id'];?>'><?php echo $this->Html->link('Checklist',array('action'=>'project_checklists',$milestone['Milestone']['project_id'],$milestone['Milestone']['id'],$pop,$por), array('escape' => false));?></li>

              <!-- <li class="active"><a href="#tab_<?php echo $milestone['Milestone']['id']?>over_all_plan" data-toggle="tab" aria-expanded="false">Over All Plan</a></li>
              <li class=""><a href="#tab_<?php echo $milestone['Milestone']['id']?>_project_team_board" data-toggle="tab" aria-expanded="false">Project Team Board</a></li>
              <li class=""><a href="#tab_<?php echo $milestone['Milestone']['id']?>_file" data-toggle="tab" aria-expanded="false">File Allocation</a></li>
              <li class=""><a href="#tab_<?php echo $milestone['Milestone']['id']?>_est_costs" data-toggle="tab" aria-expanded="false">Estimated Costs</a></li>
              <li class=""><a href="#tab_<?php echo $milestone['Milestone']['id']?>_in_po" data-toggle="tab" aria-expanded="false">POs-IN</a></li>
              <li class=""><a href="#tab_<?php echo $milestone['Milestone']['id']?>_out_po" data-toggle="tab" aria-expanded="false">POs-Out</a></li>
              <li class=""><a href="#tab_<?php echo $milestone['Milestone']['id']?>_invoices" data-toggle="tab" aria-expanded="false">Invoices</a></li>
              <li class=""><a href="#tab_<?php echo $milestone['Milestone']['id']?>_charts" data-toggle="tab" aria-expanded="false">Payment Received</a></li>
              <li class=""><a href="#tab_<?php echo $milestone['Milestone']['id']?>_err" data-toggle="tab" aria-expanded="false">Errors</a></li>
              <li class=""><a href="#tab_<?php echo $milestone['Milestone']['id']?>_checklist" data-toggle="tab" aria-expanded="false">Checklist</a></li> -->
              <li><?php echo $this->Html->image('indicator.gif', array('id' => $milestone["Milestone"]["id"].'-busy-indicator', 'class' => 'pull-right')); ?></li>
            </ul>
            </div>

            
<script>

function membertabsopenclose_<?php echo str_replace('-','_',$milestone['Milestone']['id']);?>(){
    $( "#<?php echo $milestone['Milestone']['id'];?>_tabs" ).tabs({  
        beforeLoad: function(index,ui){          
            $(ui.panel).siblings('.ui-tabs-panel').empty();
            var id = ui.panel[0].id;
            $("#"+id).html('Loading data. Please wait...');
          },
         load: function( event, ui ) {              
             $("#<?php echo $milestone["Milestone"]["id"];?>-busy-indicator").hide();
        },
        ajaxOptions: {          
          beforeLoad: function(index,ui){
            console.log("hello");
            console.log(ui.panel.id);
          },
            error: function( xhr, status, index, anchor ) {
                $( anchor.hash ).html(
                    "<?php echo __('Error loading resource.')?> " +
                    "<?php echo __('Contact Administrator.')?>" );
            }
        }
    });
}

$(document).ready(function() {
$.ajaxSetup({cache:false,});
  $( "#<?php echo $milestone['Milestone']['id'];?>_tabs li" ).click(function() {
    $(".chosen-select").chosen('destroy');
    $(".chosen-select").chosen({width: "100%"});
      $("#<?php echo $milestone["Milestone"]["id"];?>-busy-indicator").show();
  });
});
</script> 

              

              

                    <!-- /.box-body -->
                    <div class="box-footer text-right" style="">
                      <?php echo $this->Html->link('Delete Milestone',
                      array('action'=>'deletemilestone',$milestone['Milestone']['id'],'project_id'=>$project_id),array(
                        'confirm' => 'Are you sure you wish to delete this milestone?',
                        'class'=>'btn btn-sm btn-danger pull-left')
                    );?>
                      <div class="btn-group">
                        <?php 
                        echo $this->Html->link('Add Invoice',array('controller'=>'invoices','action'=>'lists',
                          'project_id'=>$project['Project']['id'],
                          'milestone_id'=>$milestone['Milestone']['id'],
                        // 'customer_id'=>$project['Project']['customer_id'],
                          'type'=>1,
                        ),array('class'=>'btn btn-sm btn-default '));
                        ?>
                        <?php 
                        echo $this->Html->link('Add Payment Reveived',array('controller'=>'project_payments','action'=>'lists',
                          'project_id'=>$project['Project']['id'],
                          'milestone_id'=>$milestone['Milestone']['id'],
                        // 'customer_id'=>$project['Project']['customer_id'],
                          'type'=>1,
                        ),array('class'=>'btn btn-sm btn-default '));
                        ?>
                        <?php 
                        echo $this->Html->link('Add Vendor PO',array('controller'=>'purchase_orders','action'=>'lists',
                          'project_id'=>$project['Project']['id'],
                          'milestone_id'=>$milestone['Milestone']['id'],
                        // 'customer_id'=>$project['Project']['customer_id'],
                          'type'=>1,
                        ),array('class'=>'btn btn-sm btn-default '));

                        echo $this->Html->link('Add New Activity','#',
                        array('class'=>'btn btn-sm btn-default','onclick'=>'openmodel("project_activities","add_ajax",null,"'.$project_id.'","'.$milestone['Milestone']['id'].'")')
                        ) 

                      ?>
                    </div>
                    </div>
                    <!-- /.box-footer -->
                  </div>
                </div>   
              </div>
             </div>                 
<?php $key++;  } ?>
<!-- </div> -->
<script type="text/javascript">

  function chkholiday(pop,por,obj){
    
    // var startdate = $("#ProjectProcessPlan"+pop+por+"StartDate").val();
    // var enddate = $("#ProjectProcessPlan"+pop+por+"EndDate").val();
    // var range = btoa(startdate + ":" + enddate);
    // console.log(range)
    var range = $("#ProjectProcessPlan"+pop+por+"StartDate").val();
    // var range = btoa($("#ProjectProcessPlan"+pop+por+"StartDate").val());
    console.log(range);
    $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_holidays/null/" + range , function(data) {
            // $('#mainPanel_ajax').append(data);
    });
    // alert('aaa');
  }

  $().ready(function(){
    // $( ".draggable" ).draggable();
    // $( ".resizable" ).resizable({
    //   helper: "ui-resizable-helper",
    //   ghost: true,
    //   animate: true,
    //   aspectRatio:true, 
    // });
    // $("#<?php echo $milestone["Milestone"]["id"];?>-busy-indicator").hide();
  });
  
</script>