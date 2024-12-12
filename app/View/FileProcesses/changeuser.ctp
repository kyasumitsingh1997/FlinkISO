<?php echo $this->Session->flash();?>
<?php 
$this->request->data['FileProcess']['employee_id'] = -1;
$currProces = $this->request->data['ProjectFile']['project_process_plan_id'];

?>
	<?php echo $this->Form->create('FileProcess',array('id'=>'FileProcessChangeuserForm_'.$this->request->data['FileProcess']['id'], 'role' => 'form', 'class' => 'form', 'default' => false));?>
	<div class="row">	
		<div class="col-md-6"><?php echo $this->Form->input('project_process_plan_id',array('options'=>$projectProcessPlans, 'selected'=>$currProces, 'onchange'=>'validate();'));?></div>		
		<div class="col-md-6"><?php echo $this->Form->input('employee_id',array('label'=>'New User', 'default'=>-1, 'onchange'=>'validate();', 'class'=>'form-control','options'=>$teamMembers));?>
			<div id="skillsetresult"></div>
		</div>
		<?php $this->request->data['FileProcess']['change_user_comments'] = '';?>
		<div class="col-md-12"><?php echo $this->Form->input('change_user_comments',array('default'=>false, 'required'=>'required'));?></div>
		<?php echo $this->Form->input('id');?>
		<div class="col-md-12"><br /><?php echo $this->Form->submit('Change',array('id'=>'plan_submit_'.$this->request->data['FileProcess']['id'], 'class'=>'btn btn-md btn-success'));?></div>
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
		var emp = $("#FileProcessEmployeeId").val();
		var plan = $("#FileProcessProjectProcessPlanId").val();

		if(emp == -1 || plan == -1){
			$("#plan_submit_<?php echo $this->request->data['FileProcess']['id'];?>").hide();
		}else{
			$("#plan_submit_<?php echo $this->request->data['FileProcess']['id'];?>").show();
		}
	}

	$().ready(function(){
		$(".chosen-select").chosen();
		$("#plan_submit_<?php echo $this->request->data['FileProcess']['id'];?>").hide();	
		$("#loadFile").load("<?php echo Router::url('/', true); ?>/project_files/view/<?php echo $this->request->data['ProjectFile']['id'];?>");

		$(".chosen-select").on('change',function(){
			$.ajax({
				url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/check_skillset/"+$("#FileProcessEmployeeId").val()+"/"+$("#FileProcessProjectProcessPlanId").val()+"/<?php echo $this->request->data['FileProcess']['project_id'];?>/<?php echo $this->request->data['FileProcess']['milestone_id'];?>",
					success: function(data, result) {
						if(data == 0){
							$("#skillsetresult").html('This user does not have required skill set.').addClass('text-danger');
							$("#FileProcessEmployeeId").val('-1').trigger("chosen:updated");
						}else{
							$("#skillsetresult").html('');
						}
					},
			});
		})

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
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/changeuser",
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
                   $("#project_files_<?php echo $this->request->data['FileProcess']['milestone_id'];?>").load("<?php echo Router::url('/', true); ?>/projects/project_files/<?php echo $this->request->data['FileProcess']['project_id'];?>/<?php echo $this->request->data['FileProcess']['milestone_id'];?>");
                }
            });
        }
    });

	$().ready(function(){
		$("#plan_submit_<?php echo $this->request->data['FileProcess']['id'];?>").click(function(){
            if($('#FileProcessChangeuserForm_<?php echo $this->request->data['FileProcess']['id'];?>').valid()){
				$("#plan_submit_<?php echo $this->request->data['FileProcess']['id'];?>").prop("disabled",true);
				// $("#cust-submit-indicator").show();
				$('#FileProcessChangeuserForm_<?php echo $this->request->data['FileProcess']['id'];?>').submit();
            }

        });
	});
</script>



</script>