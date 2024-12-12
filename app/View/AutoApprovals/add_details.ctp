<script>
    $(".chosen-select").chosen();
</script>
<div id="AutoApprovalDetails_ajax">
	<div id="AutoApprovalDetails_ajax<?php echo $i; ?>">
		<?php 
			echo "<div class=''>";
			echo "<div class='col-md-8'>" . $this->Form->input('AutoApprovalStep.Department.'.$x.'.department_id',array(
				'name'=>'data[AutoApprovalStep][Department]['.$i.'][department_id][]',
				'multiple','onchange'=>'addd('.$i.')','options'=>$departments)) . '</div>'; 
			echo "<div class='col-md-4'>".$this->Form->input('AutoApprovalStep.Department.'.$x.'.user_id',array(
				'label'=>'Send record to ',
				'options'=>$PublishedUserList)) . '</div>'; 
			echo "</div>";
			$x++; 
			$i++; 
		?>
	</div>
</div>
<?php $i++; ?>
<script type="text/javascript">
// function addd(i){
// 		i = parseInt($('#AutoApprovalStepAgendaNumber').val());
// 		for(x=0;x<=i;x++){
// 			var output = output + "," + ($("#AutoApprovalStepDepartment"+x+"DepartmentId").val() || []).join(', '); 	
// 		}
		
// 		// var existing = $("#AutoApprovalStepSdepartments").val();
// 		// alert(existing);
// 		$("#AutoApprovalStepSdepartments").val(output);
// 	}
</script>
