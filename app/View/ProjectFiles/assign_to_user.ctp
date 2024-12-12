<?php echo $this->Session->flash();?>
<?php 
$this->request->data['ProjectFile']['employee_id'] = -1;
$currProces = $this->request->data['ProjectFile']['project_process_plan_id'];

?>
	<?php echo $this->Form->create('ProjectFile',array('id'=>'ProjectFileChangeuserForm_'.$this->request->data['ProjectFile']['id'], 'role' => 'form', 'class' => 'form', 'default' => false));?>
	<div class="row">	
		<div class="col-md-6"><?php echo $this->Form->input('project_process_plan_id',array('options'=>$projectProcessPlans, 'selected'=>$currProces, 'onchange'=>'validate();'));?></div>		
		<div class="col-md-6"><?php echo $this->Form->input('employee_id',array('label'=>'New User', 'default'=>-1, 'onchange'=>'validate();', 'class'=>'form-control','options'=>$teamMembers));?>
			<div id="skillsetresult"></div>
		</div>
		<?php $this->request->data['ProjectFile']['change_user_comments'] = '';?>
		<div class="col-md-12"><?php echo $this->Form->input('change_user_comments',array('default'=>false, 'required'=>'required'));?></div>
		<?php echo $this->Form->input('id',array('default'=>$projectFile['ProjectFile']['id']));?>
		<div class="col-md-12"><br /><?php echo $this->Form->submit('Change',array('id'=>'plan_submit_'.$this->request->data['ProjectFile']['id'], 'class'=>'btn btn-md btn-success'));?></div>
		<div class="col-md-12">
		<p><strong>Note :</strong> Users who are already working on a file will not be shown in the list. If you wish to assign file to them. ask them to put the current file on hold.</p>
  		</div>
	</div>
<?php echo $this->Form->end();?>
<div class="box box-primary collapsed-box resizable">
    <div class="box-header with-border" data-widget="collapse"><h4>File Details</h4>
        <div class="btn-group box-tools pull-right">
            <button type="button" class="btn btn-xs btn-default" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div class="box-body" style="padding: 0px">
		<div id="loadFile"><i class="fa fa-refresh"></i></div>    	
  	</div>  	
</div>

<script type="text/javascript">

	function validate(){
		var emp = $("#ProjectFileEmployeeId").val();
		var plan = $("#ProjectFileProjectProcessPlanId").val();

		if(emp == -1 || plan == -1){
			$("#plan_submit_<?php echo $this->request->data['ProjectFile']['id'];?>").hide();
		}else{
			$("#plan_submit_<?php echo $this->request->data['ProjectFile']['id'];?>").show();
		}
	}

	$().ready(function(){
		$(".chosen-select").chosen();
		$("#plan_submit_<?php echo $this->request->data['ProjectFile']['id'];?>").hide();	
		$("#loadFile").load("<?php echo Router::url('/', true); ?>/project_files/view/<?php echo $projectFile['ProjectFile']['id'];?>");

		// $(".chosen-select").on('change',function(){
		// 	$.ajax({
		// 		url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/check_skillset/"+$("#ProjectFileEmployeeId").val()+"/"+$("#ProjectFileProjectProcessPlanId").val()+"/<?php echo $this->request->data['ProjectFile']['project_id'];?>/<?php echo $this->request->data['ProjectFile']['milestone_id'];?>",
		// 			success: function(data, result) {
		// 				if(data == 0){
		// 					$("#skillsetresult").html('This user does not have required skill set.').addClass('text-danger');
		// 					$("#ProjectFileEmployeeId").val('-1').trigger("chosen:updated");
		// 				}else{
		// 					$("#skillsetresult").html('');
		// 				}
		// 			},
		// 	});
		// })

	});


	$.validator.setDefaults({
    	ignore: null,
    	errorPlacement: function(error, element) {
    		$(element).after(error);
        },
    });

	$.validator.setDefaults({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?>project_files/assign_to_user",
                type: 'POST',                
                beforeSend: function(){
                   
                },error: function(request, status, error) {
                    alert('Action failed!');
                },success: function(data, status, xhr) {                    
                	// $(".modal-body").html(data);                   	
                	alert(data);
                },
                complete: function(data) {
                   $('#producionModal').modal('hide');
                   $("#project_files_<?php echo $this->request->data['ProjectFile']['milestone_id'];?>").load("<?php echo Router::url('/', true); ?>/projects/project_files/<?php echo $this->request->data['ProjectFile']['project_id'];?>/<?php echo $this->request->data['ProjectFile']['milestone_id'];?>");
                }
            });
        }
    });

	$().ready(function(){
		$("#plan_submit_<?php echo $this->request->data['ProjectFile']['id'];?>").click(function(){
            if($('#ProjectFileChangeuserForm_<?php echo $this->request->data['ProjectFile']['id'];?>').valid()){
				$("#plan_submit_<?php echo $this->request->data['ProjectFile']['id'];?>").prop("disabled",true);
				// $("#cust-submit-indicator").show();
				$('#ProjectFileChangeuserForm_<?php echo $this->request->data['ProjectFile']['id'];?>').submit();
            }

        });
	});
</script>



</script>