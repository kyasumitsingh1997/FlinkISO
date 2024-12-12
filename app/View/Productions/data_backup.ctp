<div id="for-report">
<style type="text/css">
.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td{border: 1px solid #ccc9c9}
.dark-border{border-bottom: 4px double #ccc !important;}
.dark-border-right{border-right: 4px double #ccc !important;}
</style>
	<?php echo $this->element('nav-header-lists', array('postData' => array('pluralHumanName' => 'Productions', 'modelClass' => 'Production', 'options' => array("sr_no" => "Sr No", "batch_number" => "Batch Number", "details" => "Details", "remarks" => "Remarks"), 'pluralVar' => 'productions'))); ?>
	<div class="row">
		<?php echo $this->Form->create('Production',array('role'=>'form','class'=>'form','default'=>false));?>
			<div class="col-md-6"><?php echo $this->Form->input('product_id',array('multiple','name'=>'data[Production][product_id][]'));?></div>
			<div class="col-md-2"><?php echo $this->Form->input('date_range');?></div>
			<div class="col-md-4">

				<br /><?php echo $this->Js->submit(__('Submit'), array('url' => array('controller' => 'productions', 'action' => 'data_backup'), 'div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#for-report','id'=>'for-report-submit', 'async' => 'false'));?>
			</div>			
			<?php echo $this->Form->end(); ?>        			
		<?php echo $this->Js->writeBuffer(); ?>
	<div>
	<?php if($results){ 
		$total_planned = $total_actual = $total_rejected = 0;
	?> 
		<div class="">
			<div class="col-md-12" style="overflow:scroll;padding-right:50px">
				<h3><?php echo __('Production Report');?></h3>
				<table class="table table-condensed table-bordered" style="padding-right:50px;margin-right:50px">
					<?php foreach ($results as $product => $months) { ?>
						<!-- echo $product; -->
						<tr><th colspan="3"><h4><?php echo $product;?></h4></th></tr>
							<?php foreach ($months as $month => $weekplan) {?>
							<tr><th></th><th colspan="2"><?php echo $month;?></th></tr>
								<!-- // echo $month; -->
								
								<?php foreach ($weekplan as $plan => $days) {?>
								<tr>
									<th></th>
									<th colspan="2"><?php echo $plan;?></th>
										<?php foreach ($days as $day => $values) {?>
										<td><?php echo $day;?></td>
									<?php }?>
									</tr>
								</tr>	
								<tr>
									<td></td>
									<td></td>
									<td>Actual</td>
										<?php foreach ($days as $day => $values) {?>
										<td><?php echo $values['actual'];?></td>
									<?php }?>								
								</tr>	
								<tr>
									<td></td>
									<td></td>
									<td>Rejected</td>
										<?php foreach ($days as $day => $values) {?>
										<td><?php if(!$values['rejections'])echo 0 ; else echo $values['rejections'];?></td>
									<?php }?>								
								</tr>	
							<?php }?>
						<?php }?>
					<?php }?>
					
					
					
				</table>
			</div>
		</div>
	<?php } ?>
</div>
<script type="text/javascript">
$().ready(function(){
	$("#ProductionProductId").chosen();
});
$("#ProductionDateRange").daterangepicker({
	"autoApply": true,
	 "showWeekNumbers": true,
	 "startDate" : '<?php echo date("m/1/Y");?>',
	 "endDate" : '<?php echo date("m/t/Y",strtotime("+3 months"));?>',
	format: 'MM/DD/YYYY',
	    // minDate : '<?php echo $start_date;?>',
	    // maxDate : '<?php echo $end_date;?>',
	    locale: {
	        // format: 'MM/DD/YYYY'
	    },
	    autoclose:true,
	}); 
</script>
