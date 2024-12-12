<div class="modal fade" id="history_crs">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">History Details <small>Beta</small></h4>
		<small>Fields marks Red are changed. Change tracking currently works accuratly only for parent records. Click on arrows to navigate between records</small>
      </div>
      <div class="modal-body">
	  <?php if(isset($no_history)){ ?>
	  			<h5 class="text-warning">Data not available </h5>
				<p>You may see this if there were no edits made on after updating to FlinkISO ver 1.004</p>
	  <?php } else { ?> 
			<div id="histories_ajax">    
				<?php 
				$ignore_array = array('id','sr_no','system_table_id','company_id','branchid','departmentid');
				$ignore_keys = array('Company','SystemTable','BranchIds','DepartmentIds');
				?>
				<div class="col-md-12 hide">
					<div class="panel panel-info">
						<div class="panel-heading"><div class="panel-title"><?php echo __('Current Record'); ?></div></div>
						<div class="panel-body">
							<?php 
							foreach($current_record as $key=>$record):
								if(!in_array($key,$ignore_keys)){
									echo "<div class='row'><div class='col-md-12'><h5>".Inflector::singularize(Inflector::humanize((Inflector::tableize($key))))."</h5></div></div>";
									echo "<div class='row'>";
									foreach($record as $record_key => $record_value):
										if(!in_array($record_key,$ignore_array))echo "<div class='col-md-4'>".Inflector::humanize($record_key) . " </div><div class='col-md-8'> " . $record_value ." &nbsp;</div>";
									endforeach;
									echo "</div>";	
								}
							endforeach;
							 ?>	
						</div>
					</div>
				</div>
			<div class="">	 
				<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="false">
					<div class="carousel-inner" role="listbox">
						<?php 
							$i=0;
							
							foreach($old_records as $old_record): ?>
							<?php 
								if($i / 2 == 'odd') echo '<div class="col-md-12 no-margin no-padding item active">';
								else echo '<div class="col-md-12 no-margin no-padding item ">'; 
								$i++;
							?>
								<div class="col-md-6">
									<div class="panel panel-warning"><div class="panel-heading"><div class="panel-title"><?php echo __('Old Data'); ?></div></div>
										<div class="panel-body">			
											<?php	
												foreach($old_record['pre_post_values'] as $key=>$record):
													if(!in_array($key,$ignore_keys)){
														echo "<div class='row'><div class='col-md-12'><h5>".Inflector::singularize(Inflector::humanize((Inflector::tableize($key))))."</h5></div></div>";
														foreach($record as $record_key => $record_value):
															if(!in_array($record_key,$ignore_array))echo "<div class='row' id='pre-".$i."-".$record_key."'><div class='col-md-4'>".Inflector::humanize($record_key) . " </div><div class='col-md-8'> " . $record_value ." &nbsp;</div></div>";
														endforeach;
													}
													endforeach; 
											?>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="panel panel-warning"><div class="panel-heading"><div class="panel-title"><?php echo __('New Data'); ?></div></div>
										<div class="panel-body">			
											<?php	
												foreach($old_record['post_values'] as $key=>$record):
													if(!in_array($key,$ignore_keys)){
														echo "<div class='row'><div class='col-md-12'><h5>".Inflector::singularize(Inflector::humanize((Inflector::tableize($key))))."</h5></div></div>";
														$j = 0;	
														foreach($record as $record_key => $record_value):
															if(!in_array($record_key,$ignore_array))echo "<div class='row' id='post-".$i."-".$record_key."'><div class='col-md-4'>".Inflector::humanize($record_key) . " </div><div class='col-md-8'> " . $record_value ." &nbsp;</div></div>";
														?>
														<script>
					if($('#pre-<?php echo $i ?>-<?php echo $record_key ?>').html() != $('#post-<?php echo $i ?>-<?php echo $record_key ?>').html()){
						$('#post-<?php echo $i ?>-<?php echo $record_key ?>').addClass(' text-danger').css({'font-weight':'bold','font-size':'120%'});
						$('#pre-<?php echo $i ?>-<?php echo $record_key ?>').addClass(' text-danger').css({'font-weight':'bold','font-size':'120%'});
					};
			</script>
														<?php													
														endforeach;
													}
													endforeach; 
											?>
										</div>
									</div>
								</div>
						</div>
				<?php endforeach; ?>	
			</div>
			<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			  </a>
			  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			  </a>
			</div>
			</div>
			</div>
     	</div>
<?php } ?>		
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>$('#history_crs').modal();</script>
<style>#history_crs .modal-dialog{width:98%; height:100%}.glyphicon-chevron-right, .glyphicon-chevron-left{ color:#000000 !important}.carousel-control.left , .carousel-control.right{ background-image:none !important}.carousel-control{width:2%}</style>
