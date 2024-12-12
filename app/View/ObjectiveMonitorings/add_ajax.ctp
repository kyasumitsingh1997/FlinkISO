<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="objectiveMonitorings_ajax">
  <?php echo $this->Session->flash();?>
  <div class="nav">
    <div class="objectiveMonitorings form col-md-8">      
      <?php if($this->request->params['named']['process_id']){ ?> 
        <h3><?php echo __('Process Related Task\'s Status'); ?></h3>
        <?php echo $this->element('related_tasks'); ?>
      <?php } ?>
      <div class="">
          <?php if($objective['Objective']['list_of_kpi_id']){ ?>
            <div id="objective-details"></div>
          <?php } ?>
        </div>
      <h4>
        <?php echo __('Add Objective Monitoring');?> 
          <?php if(isset($last_monitoring)){
            echo " <small>" . __("Last Monitoring was performed on ") . $last_monitoring['ObjectiveMonitoring']['created'] . "</small>";
          }else{
            echo " <small>" . __("This is your first Monitoring for this process.") . "</small>";
          }
        ?>
      </h4>      
      <?php echo $this->Form->create('ObjectiveMonitoring',array('role'=>'form','class'=>'form','default'=>false)); ?>    
      <div class="row">        
		    <fieldset>
			   <?php
          if(!isset($result) or $result == '')$result = 0;
          $currentStatus = array(0=>'Open',1=>'Close');
      		echo "<div class='col-md-12'>".$this->Form->input('objective_id',array('default'=>$this->request->params['named']['objective_id'])) . '</div>'; 
      		if($this->request->params['named']['process_id'])echo "<div class='col-md-12'>".$this->Form->input('process_id',array('default'=>$this->request->params['named']['process_id'])) . '</div>'; 
      		echo "<div class='col-md-4'>".$this->Form->input('employee_id',array('default'=>$objective['Objective']['employee_id'])) . '</div>'; 
          echo "<div class='col-md-4'>".$this->Form->input('target_date',array('default'=>$objective['Objective']['target_date'])) . '</div>'; 
          echo "<div class='col-md-4'>".$this->Form->input('current_status',array('type'=>'radio','options'=>$currentStatus,'default'=>0)) . '</div>'; 
          echo "<div class='col-md-12'>".$this->Form->input('analysis',array()) . '</div>'; 
      		echo "<div class='col-md-12'>".$this->Form->input('improvements_required',array()) . '</div>';
          if($this->request->params['named']['process_id'])echo "<div class='col-md-12'>".$this->Form->input('process_team_id',array()) . '</div>'; 
	       ?>
          <?php if($this->request->params['named']['objective_id'] != '' && $this->request->params['named']['process_id'] != ''){ ?>
          
        <?php } else { ?> 
        <script type="text/javascript">
        $(function() {
          $('#ObjectiveMonitoringObjectiveId').on('change',function(){
            $('#objectiveMonitorings_ajax').load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax/objective_id:' + $('#ObjectiveMonitoringObjectiveId').val());
          });
          $('#ObjectiveMonitoringProcessId').on('change',function(){
            $('#objectiveMonitorings_ajax').load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax/objective_id:' + $('#ObjectiveMonitoringObjectiveId').val() + '/process_id:' + $('#ObjectiveMonitoringProcessId').val());
          });
          });
        </script>
        <?php } ?>
        <?php if($this->request->params['named']['objective_id'] != '' && $this->request->params['named']['process_id'] != ''){ ?>
          
        <?php }else{ ?> 
            <!-- <div class="col-md-12" id = "">Select Process First</div> -->
        <?php } ?> 
        <div class="col-md-12"><h4><?php echo __('Task Completion'); ?></h4></div>  
          <div class="col-md-10">
            <p><?php echo $this->Form->hidden('completion',array()) ; ?></p>
              <div id="completion_slider"></div>
            </div>
            <div class="col-md-2" id = "ObjectiveMonitoringCompletionh1"><h1><?php echo $result ?>%</h1></div>     
      </fieldset>
      <?php
        echo $this->Form->input('schedule_id', array('type' => 'hidden', 'value' => $process['Process']['schedule_id']));
        echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
        echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
        echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
      ?>
    </div>
    <div class="">
		  <?php
      if ($showApprovals && $showApprovals['show_panel'] == true) {
        echo $this->element('approval_form');
      } else {
        echo $this->Form->input('publish', array('label' => __('Publish')));
      }?>
      <?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#objectiveMonitorings_ajax','async' => 'false')); ?>
      <?php echo $this->Form->end(); ?>
      <?php echo $this->Js->writeBuffer();?>
    </div>
  </div>
  <script>
            $(function() {
              $( "#completion_slider" ).slider({
                range: "max",
                min: 1,
                max: 100,
                value:<?php echo $result ?>,       
                slide: function( event, ui ) {
                $( "#ObjectiveMonitoringCompletion" ).val( ui.value );
                $( "#ObjectiveMonitoringCompletionh1" ).html("<h1>" + ui.value + "%</h1>");
              }
            });
            $( "#ObjectiveMonitoringCompletion" ).val( $( "#completion_slider" ).slider( "value" ) );
          });
        </script>
<script> $("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,      
    }); </script>
<div class="col-md-4">
  <p><?php echo $this->element('objectiv_details'); ?></p>
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<script>
    $.validator.setDefaults({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#main',
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
      $("#ObjectiveMonitoringTargetDate").datepicker( "setDate" , "<?php echo $objective['Objective']['target_date']; ?>" );
      $('#objective-details').load("<?php echo Router::url('/', true); ?>objectives/view/<?php echo $objective['Objective']['id']?> #onlyobjective");;
    $("#submit-indicator").hide();
        $('#ObjectiveMonitoringAddAjaxForm').validate();        
    });
</script>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
