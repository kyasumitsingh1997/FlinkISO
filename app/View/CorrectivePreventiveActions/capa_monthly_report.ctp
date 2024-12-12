<script type="text/javascript">
	$().ready(function(){
		// $('#branches').width('100%');
		// $('#branches').chosen();
		$('#employees').width('100%');
		$('#employees').chosen();
		// $('#month').width('80%');
		// $('#month').chosen();
		$('#capasource').width('100%');
		$('#capasource').chosen();

		$('#capacategory').width('100%');
		$('#capacategory').chosen();	
	});
	
</script>
<script type="text/javascript">
	$().ready(function(){
		$("#ReportDates").daterangepicker({
	        format: 'MM/DD/YYYY',
	        locale: {
	            format: 'MM/DD/YYYY'
	        },
	        autoclose:true,
	    }); 
	});
		

</script>
<?php
$curr_month = ($this->request->data['Report']['month'])?  $this->request->data['Report']['month'] : ''  ;
if ($curr_month) {
	$curr_month = date('Y-m',strtotime($this->request->data['Report']['month']));	
} else {
	$curr_month = date('Y-m');
}
?>
<?php 
	$date1 = strtotime(date("Y-m-d", strtotime($curr_month)) . " -1 month"); 
	$date2 = strtotime(date("Y-m-d", strtotime($curr_month)) . " -2 month"); 
	
?>
<div class="row" id="src-panel">
	<?php echo $this->Form->create('Report', array('role' => 'form', 'class' => 'form')); ?>
        <div class="col-md-6">
        	<?php 
        		echo $this->Form->input('capa_source_id[]',array(
						'label'=>'Capa Source',
						'id'=> 'capasource',
						'name'=>'capa_source_id[]',
						'options'=>$capaSources,
						'selected'=>$selected_source,
						'multiple'
					)) ; 
			?> 
        </div>
        <div class="col-md-6">
        	<?php 
        		echo $this->Form->input('capa_category_id[]',array(
						'label'=>'Capa Category',
						'id'=> 'capacategory',
						'name'=>'capa_category_id[]',
						'options'=>$capaCategories,
						'selected'=>$selected_cats,
						'multiple'
					)) ; 
			?>
        </div>
        <div class="col-md-8">
			<?php 
        		echo $this->Form->input('employee_id[]',array(
						'label'=>'Employee',
						'id'=> 'employees',
						'name'=>'employee_id[]',
						'options'=>$employees,
						'selected'=>$selected_employees,'multiple'
					)) ; 
			?>        	
        </div>
        <div class="col-md-2">
            
            <?php
            // $end_date = date('Y-m-1');
            // $date = date("Y-m-d", strtotime("-36 month", strtotime($end_date)));
            // while ($date < $end_date) {
            //     $options[date('Y', strtotime($end_date))][date('Y-m', strtotime($end_date))] = date('M-Y', strtotime($end_date));
            //     $end_date = date("Y-m-d", strtotime("-1 month", strtotime($end_date)));
            // }
            // echo $this->Form->input('month', array('id' => 'month', 'label' => __('Change Month'), 'options' => $options,'default'=>date('m-Y')));
            echo $this->Form->input('dates');
            ?>
        </div>        
        
        <div class="col-md-2">
        	<div class="" role="group">  
        	<br />      		
        	<?php echo $this->Form->submit('Reload',array('class'=>'btn btn-sm btn-primary','div'=>false)); ?>
        	<?php //echo $this->Html->Link('Download Excel',array('controller'=>'reports','action'=>'download_report_summery',base64_encode(json_encode($this->request->data))),array('class'=>'btn btn-default btn-sm')); ?>
        </div>
        </div>
        
    </div>
    <?php echo $this->Form->end(); ?>
<h2><?php echo __('CAPA Monthly Report');?></h2>
<div class="">
	<table class="table table-bordered table-responsive">
		<tr>
			<th rowspan="2"><?php echo __('Employee');?></th>
			<th colspan="4"><?php echo __('Investigations');?></th>
			<th colspan="4"><?php echo __('Root Cause Analysis');?></th>
		</tr>
		<tr>			
			<th><?php echo __('Total');?></th>
			<th><?php echo __('Closed');?></th>
			<th><?php echo __('Open');?></th>
			<th><?php echo __('Delayed');?></th>
			<th><?php echo __('Total');?></th>
			<th><?php echo __('Closed');?></th>
			<th><?php echo __('Open');?></th>
			<th><?php echo __('Delayed');?></th>
		</tr>
	<?php foreach ($result as $employee=>$values) { ?>
		<tr>
			<td><?php echo $employee;?></td>
			<?php foreach ($values as $type=>$value) { $x = 0;?>
				<?php foreach ($value as $v) { ?>
				<td><?php echo $v;?></td>
			<?php $x = $x + $v;}?>
			<?php }?>
		</tr>	
	<?php }?>
	</table>
	<table class="table table-bordered table-responsive">
		<tr>
			<th><h2><?php echo __('Source-Wise');?></h2></th>
			<th><h2><?php echo __('Category-Wise');?></h2></th>
		</tr>
		<tr>
			<td>
				<?php if(isset($capaSourcesWise)){ ?>
					<table class="table table-bordered table-responsive">
						<?php foreach ($capaSourcesWise as $key => $values) { ?>
							<tr><td colspan="2"><strong><?php echo $capaSources[$key];?></strong></td></tr>
								<?php foreach ($values as $value) { ?>
									<tr><td></td><td><?php echo $value;?></td></tr>
								<?php } ?>
						<?php } ?>
					</table>	
				<?php } ?>
			</td>
			<td>
				<?php if(isset($capaCategoryWise)){ ?>
					<table class="table table-bordered table-responsive">
						<?php foreach ($capaCategoryWise as $key => $values) { ?>
							<tr><td colspan="2"><strong><?php echo $capaCategories[$key];?></strong></td></tr>
								<?php foreach ($values as $value) { ?>
									<tr><td></td><td><?php echo $value;?></td></tr>
								<?php } ?>
						<?php } ?>
					</table>	
				<?php } ?>
			</td>			
		</tr>
	</table>
</div>