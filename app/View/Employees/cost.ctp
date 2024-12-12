<div class="main">
	<div class="">
		<div class="col-md-12"><?php echo $this->Session->flash();?></div>
		<div class="col-md-12">
			<?php 
			echo $this->Form->create('Employee');
			echo $this->Form->input('data',array('type'=>'textarea','rows'=>10,'label'=>'Add Data in CSV Format seperated by Enter per row'));
			echo "<span class='help'>employee_number,cost_per_hr<br />employee_number,cost_per_hr<br />employee_number,cost_per_hr<br />employee_number,cost_per_hr<br /></span>";
			echo $this->Form->submit('Submit',array('class'=>'btn btn-sm btn-info'));
			echo $this->Form->end();
			?>
		</div>
	</div>
</div>