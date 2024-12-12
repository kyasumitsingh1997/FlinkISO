<div class="container">
	<?php 
		$i = 1;
		foreach($reports as $report_name => $report){ 
			if($report != null){
			$total = 0;?>
				<?php if($i % 3 == 0)echo "<div class='row'>"; ?>
					<div class="col-md-4">
						<table class="table table-responsive table-bordered">
							<tr>
								<th colspan="2" class="text-center"><h4><?php echo $report_name; ?> <span class="badge label-warning"><?php echo $report['count']; ?></span></h4></th>				
							</tr>
							<?php foreach($report as $report_wise => $report_details){ ?> 
								<?php foreach ($report_details as $entity_name => $report_detail) { 
									if($entity_name != 'count'){
									$total = 0; 
									?>
									<tr><td colspan="2" class="text-center"><strong><?php echo $entity_name; ?> <?php //echo $report_detail['count']; ?></strong></td></tr>
									<?php foreach ($report_detail as $key => $value) { ?>
										<tr><td width="85%"><?php echo $key; ?></td><td><?php echo $value; ?><?php $total = $total + $value; ?></td></tr>
									<?php }  ?> <tr><td><strong>Total</strong></td><td><stron><?php echo $total; ?></strong></td></tr> <?php }?>
								<?php } ?>						
							<?php } ?>
						</table>
					</div>
				<?php if($i % 3 == 0)echo "</div>"; ?>	
		<?php $i ++; ?>
	<?php } }?>
</div>