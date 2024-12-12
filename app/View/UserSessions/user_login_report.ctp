<div class="main">
	<div class="row">
		<?php echo $this->Form->create('UserSession');
		echo '<div class="col-md-5">'. $this->Form->input('project_id') . '</div>';
		echo '<div class="col-md-5">'.  $this->Form->input('date') . '</div>';
		echo '<div class="col-md-2"><br />'.  $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')) . '</div>';
		echo $this->form->end();
		?>
		</div>
		<div class="row">
		<div class="col-md-12">
			<table class="table table-responsive table-bordered table-stripped"> 
				<tr>
					<th>User/Employee</th>
					<th>Code</th>
					<th>Project</th>
					<th>Start</th>
					<th>End</th>
					<th>Duration</th>
					<!-- <th></th> -->
				</tr>
				<?php foreach($sessions as $session){ ?>
					<tr>
						<td><?php echo $session['Member']['Employee']['name'];?></td>
						<td><?php echo $session['Member']['Employee']['employee_number'];?></td>
						<td><?php echo $projects[$this->request->data['UserSession']['project_id']];?></td>
						<td><?php if($session['UserSession']['start_time'])echo DATE('Y-m-d H:i:s',strtotime($session['UserSession']['start_time']));?></td>
						<td><?php if($session['UserSession']['end_time'])echo DATE('Y-m-d H:i:s',strtotime($session['UserSession']['end_time']));?></td>
						<td><?php echo $session['UserSession']['time'];?></td>
						<!-- <td></td> -->
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	$().ready(function(){
		$(".chosen-select").chosen();
		$("#UserSessionDate").datepicker();
	});
</script>