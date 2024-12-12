<?php
$milestone_cost = 0;
  $balance_cost = 0;
  foreach ($project_milestones as $milestone) 
  { 
      $milestone_cost = $milestone['Milestone']['estimated_cost'] + $milestone_cost;      
  }
  $balance_cost = $project['Project']['project_cost'] - $milestone_cost;  
  ?>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="projectActivities_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<div class="projectActivities form col-md-8">
  <style type="text/css">
    .summary td{
      padding: 12px !important;
      font-weight: 800;
      font-size: 18px;
    }
  </style>
<?php 
$qucipro = $this->requestAction('projects/projectdates/'.$project['Project']['id']);
echo $this->element('projectdates',array('qucipro'=>$qucipro));?>
<h4>Add Project Activity</h4>
<?php echo $this->Form->create('ProjectActivity',array('role'=>'form','class'=>'form','default'=>true)); ?>
<div class="row">
		<fieldset>
			
      <?php
      if($this->request->params['named']['project_id']){
        echo "<div class='col-md-6'>".$this->Form->input('project_id',array('default'=>$this->request->params['named']['project_id'])) . '</div>'; 
      }else echo "<div class='col-md-6'>".$this->Form->input('project_id',array()) . '</div>'; 
    if($this->request->params['named']['milestone_id']){      
      echo "<div class='col-md-6'>".$this->Form->input('milestone_id',array('default'=>$this->request->params['named']['milestone_id'])) . '</div>'; 
    }else{
      echo "<div class='col-md-6'>".$this->Form->input('milestone_id',array()) . '</div>'; 
    }
    echo "<div class='col-md-12'>".$this->Form->input('title',array()) . '</div>'; 
    echo "<div class='col-md-12'>".$this->Form->input('details',array()) . '</div>';     
    // echo "<div class='col-md-12'>".$this->Form->input('users',array('name'=>'ProjectActivity[users][]', 'options'=>$PublishedUserList, 'multiple')) . '</div>'; 
    echo "<div class='col-md-12'>".$this->Form->input('user_id',array('options'=>$PublishedUserList)) . '</div>'; 
    echo "<div class='col-md-3'>".$this->Form->input('estimated_cost',array()) . '</div>'; 
    echo "<div class='col-md-3'>".$this->Form->input('start_date',array('label'=>'Date Range')) . '</div>'; 
    // echo "<div class='col-md-6'>".$this->Form->input('end_date',array()) . '</div>'; 
    echo "<div class='col-md-3'>".$this->Form->input('sequence',array()) . '</div>'; 
    echo "<div class='col-md-3'>".$this->Form->input('current_status',array('options'=>array('Open','Close'))) . '</div>'; 
    echo "<div class='col-md-12'><div class='alert alert-danger hide' id='zero_balance'>You do not have sufficient balace for this activity. Try adjusting project and milestone cost.</div></div>"; ?>
    
    <div id="cost" class="hide"></div>
</fieldset>
<?php
    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?></div>
<div class="">
<?php
  if ($showApprovals && $showApprovals['show_panel'] == true) {
    echo $this->element('approval_form');
  } else {
    echo $this->Form->input('publish', array('label' => __('Publish')));
  }?>
  <?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#projectActivities_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
</div>
</div>
<script> 
	  
    $("#ProjectActivityMilestoneId").change(function(){
      // alert('a');
    $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_cost/" + $("#ProjectActivityMilestoneId").val(), function(data) {
      var obj = jQuery.parseJSON(data);
      // alert(obj.endDate);
      // alert(obj.startDate);
      $("#ProjectActivityEstimatedCost").val(obj.cost);
      // $("#ProjectActivityStartDate").val(obj.startDate);
      // $("#ProjectActivityEndDate").val(obj.endDate);
      // alert(obj.startDate);
      $("#ProjectActivityStartDate").daterangepicker({
              format: 'MM/DD/YYYY',
              minDate: obj.startDate,
              maxDate: obj.endDate,              
          // startDate: 'd',
          autoclose:true,
      }); 
      // $("#ProjectActivityStartDate").datepicker({
      //   changeMonth: true,
      //   changeYear: true,
      //   format: 'yyyy-mm-dd',
      //   autoclose:true,
      //   startDate: obj.startDate,
      //   endDate: obj.endDate,
      // });
      // $("#ProjectActivityEndDate").datepicker({
      //   changeMonth: true,
      //   changeYear: true,
      //   format: 'yyyy-mm-dd',
      //   autoclose:true,
      //   startDate: obj.startDate,
      //   endDate: obj.endDate,
      // });
      $("#ProjectActivitySequence").val(obj.sequence);
      
      if($("#ProjectActivityEstimatedCost").val() <= 0)$("#zero_balance").removeClass('hide').addClass('show');
      else $("#zero_balance").removeClass('show').addClass('hide');
    });
    
  
    
  });
</script>
<div class="col-md-4">  
  <p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<script>
    $.validator.setDefaults({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#loadhear',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                },
                error: function(request, status, error) {                    
                    alert('Action failed!');
                }
	    });
        }
    });
		$().ready(function() {
    $("#submit-indicator").hide();
        $('#ProjectActivityAddAjaxForm').validate();        
    });
</script>
<?php
    // $startDate = $project["Project"]["start_date"];
    // $endtDate = $project["Project"]["end_date"];  
?>

<script> 

  $().ready(function(){
    $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_cost/" + $("#ProjectActivityMilestoneId").val(), function(data) {
      var obj = jQuery.parseJSON(data);
      // alert(obj.endDate);
      // alert(obj.startDate);
      $("#ProjectActivityEstimatedCost").val(obj.cost);
      // $("#ProjectActivityStartDate").val(obj.startDate);
      // $("#ProjectActivityEndDate").val(obj.endDate);
      // alert(obj.startDate);
      $("#ProjectActivityStartDate").daterangepicker({
              format: 'MM/DD/YYYY',
              minDate: obj.startDate,
              maxDate: obj.endDate,              
          // startDate: 'd',
          autoclose:true,
      }); 
      // $("#ProjectActivityStartDate").datepicker({
      //   changeMonth: true,
      //   changeYear: true,
      //   format: 'yyyy-mm-dd',
      //   autoclose:true,
      //   startDate: obj.startDate,
      //   endDate: obj.endDate,
      // });
      // $("#ProjectActivityEndDate").datepicker({
      //   changeMonth: true,
      //   changeYear: true,
      //   format: 'yyyy-mm-dd',
      //   autoclose:true,
      //   startDate: obj.startDate,
      //   endDate: obj.endDate,
      // });
      $("#ProjectActivitySequence").val(obj.sequence);
      
      if($("#ProjectActivityEstimatedCost").val() <= 0)$("#zero_balance").removeClass('hide').addClass('show');
      else $("#zero_balance").removeClass('show').addClass('hide');
    });
  });

  $("#ProjectActivityStartDate").daterangepicker({
              format: 'MM/DD/YYYY',
              // startDate: '<?php echo date("yyyy-MM-dd",strtotime($this->data["ProjectActivity"]["start_date"]))?>',
              // endDate: '<?php echo date("yyyy-MM-dd",strtotime($this->data["ProjectActivity"]["end_date"]))?>',
              minDate: '<?php echo date("yyyy-MM-dd",strtotime($project["Project"]["start_date"]))?>',
              maxDate: '<?php echo date("yyyy-MM-dd",strtotime($project["Project"]["end_date"]))?>',
          // startDate: 'd',
          autoclose:true,
      }); 

  // $("#ProjectActivityStartDate").datepicker({
  //     changeMonth: true,
  //     changeYear: true,
  //     format: 'yyyy-mm-dd',
  //     autoclose:true,
  //     todayHighlight:true,
  //     startDate: '<?php echo date("Y-m-d",strtotime($startDate)); ?>',
  //   });
  // $("#ProjectActivityStartDate").datepicker({
  //     changeMonth: true,
  //     changeYear: true,
  //     format: 'yyyy-mm-dd',
  //     autoclose:true,
  //     startDate: '<?php echo date("Y-m-d",strtotime($endDate)); ?>',
  //     endDate: '<?php echo date("Y-m-d",strtotime($project["Project"]["end_date"])); ?>',
  //   });
  
  // $("#ProjectActivityStartDate").val("<?php echo $startDate; ?>");
  // $("#ProjectActivityStartDate").val("<?php echo $endDate; ?>");
</script>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
