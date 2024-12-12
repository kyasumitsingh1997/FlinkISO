<style type="text/css">
.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td{border: 1px solid #ccc9c9}
.dark-border{border-bottom: 4px double #ccc !important;}
.dark-border-right{border-right: 4px double #ccc !important;}
</style>
<div class="main">
	<div class="row">
		<div class="col-md-12"><h2><?php echo __('Employee Compliance Report'); ?></h2></div>
		<div class="col-md-12">
			<table class="table table-responsive table-bordered">
				<tr>
					<th rowspan="2" class="text-left dark-border-right"><h4><?php echo __('Employee'); ?></h4></th>
					<th colspan="2" class="text-center dark-border-right"><?php echo __('Approvals'); ?></th>
					<th colspan="3" class="text-center dark-border-right"><?php echo __('Customer Complaints'); ?></th>
					<th colspan="3" class="text-center dark-border-right"><?php echo __('Tasks'); ?></th>
					<th colspan="3" class="text-center dark-border-right"><?php echo __('Meeting Actions'); ?></th>
					<th colspan="3" class="text-center dark-border-right"><?php echo __('CAPA Investigations'); ?></th>
					<th colspan="3" class="text-center dark-border-right"><?php echo __('CAPA Root Cause'); ?></th>
					<th rowspan="2" class="text-center dark-border-right"><?php echo __('Incedent Investigations'); ?></th>					
				</tr>
				<tr>
					<th class="text-center"><?php echo __('Total'); ?></th>
					<th class="text-center dark-border-right"><?php echo __('Pending'); ?></th>

					<th class="text-center"><?php echo __('Total'); ?></th>
					<th class="text-center"><?php echo __('Pending'); ?></th>
					<th class="text-center dark-border-right"><?php echo __('Delayed'); ?></th>
					
					<th class="text-center"><?php echo __('Total'); ?></th>
					<th class="text-center"><?php echo __('Pending'); ?></th>
					<th class="text-center dark-border-right"><?php echo __('Delayed'); ?></th>
					
					<th class="text-center"><?php echo __('Total'); ?></th>
					<th class="text-center"><?php echo __('Pending'); ?></th>
					<th class="text-center dark-border-right"><?php echo __('Delayed'); ?></th>

					<th class="text-center"><?php echo __('Total'); ?></th>
					<th class="text-center"><?php echo __('Pending'); ?></th>
					<th class="text-center dark-border-right"><?php echo __('Delayed'); ?></th>

					<th class="text-center"><?php echo __('Total'); ?></th>
					<th class="text-center"><?php echo __('Pending'); ?></th>
					<th class="text-center dark-border-right"><?php echo __('Delayed'); ?></th>
					
				</tr>
				<?php foreach ($results as $employee => $result) { ?>
				<tr>
					<td class="text-left dark-border-right"><?php echo $employee ?></td>
					
					<td class="text-center " ><?php if(array_key_exists('AllApprovals', $result))echo "<strong>".$result['AllApprovals']."</strong>";else echo 0; ?></td>
					<td class="text-center dark-border-right" ><?php if(array_key_exists('PendingApprovals', $result)) echo "<strong>".$result['PendingApprovals']."</strong>"; else echo 0; ?></td>
					
					<td class="text-center" ><?php if(array_key_exists('all_ccs', $result)) echo "<strong>".$result['all_ccs']."</strong>"; else echo 0; ?></td>
					<td class="text-center" ><?php if(array_key_exists('pending_ccs', $result)) echo "<strong>".$result['pending_ccs']."</strong>"; else echo 0; ?></td>
					<td class="text-center dark-border-right" ><?php if(array_key_exists('delayed_ccs', $result)) echo "<strong class='text-danger'>".$result['delayed_ccs']."</strong>"; else echo 0; ?></td>

					<td class="text-center" ><?php if(array_key_exists('all_tasks', $result)) echo "<strong>".$result['all_tasks']."</strong>"; else echo 0; ?></td>
					<td class="text-center" ><?php if(array_key_exists('pending_tasks', $result)) echo "<strong>".$result['pending_tasks']."</strong>"; else echo 0; ?></td>
					<td class="text-center dark-border-right" ><?php if(array_key_exists('delayed_tasks', $result)) echo "<strong class='text-danger'>".$result['delayed_tasks']."</strong>"; else echo 0; ?></td>

					<td class="text-center" ><?php if(array_key_exists('all_topics', $result)) echo "<strong>".$result['all_topics']."</strong>"; else echo 0; ?></td>
					<td class="text-center" ><?php if(array_key_exists('pending_topics', $result)) echo "<strong>".$result['pending_topics']."</strong>"; else echo 0; ?></td>
					<td class="text-center dark-border-right" ><?php if(array_key_exists('delayed_topics', $result)) echo "<strong class='text-danger'>".$result['delayed_topics']."</strong>"; else echo 0; ?></td>

					<td class="text-center" ><?php if(array_key_exists('all_capas', $result)) echo "<strong>".$result['all_capas']."</strong>"; else echo 0; ?></td>
					<td class="text-center" ><?php if(array_key_exists('pending_capas', $result)) echo "<strong>".$result['pending_capas']."</strong>"; else echo 0; ?></td>
					<td class="text-center dark-border-right" ><?php if(array_key_exists('delayed_capas', $result)) echo "<strong class='text-danger'>".$result['delayed_capas']."</strong>"; else echo 0; ?></td>

					<td class="text-center" ><?php if(array_key_exists('all_root', $result)) echo "<strong>".$result['all_root']."</strong>"; else echo 0; ?></td>
					<td class="text-center" ><?php if(array_key_exists('pending_root', $result)) echo "<strong>".$result['pending_root']."</strong>"; else echo 0; ?></td>
					<td class="text-center dark-border-right" ><?php if(array_key_exists('delayed_root', $result)) echo "<strong class='text-danger'>".$result['delayed_root']."</strong>"; else echo 0; ?></td>
					
					<td class="text-center" ><?php if(array_key_exists('pending_investigations', $result)) echo "<strong>".$result['pending_investigations']."</strong>"; else echo 0; ?></td>
				</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>