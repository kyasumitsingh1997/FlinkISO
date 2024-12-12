<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="userAccessControls ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'User Access Controls','modelClass'=>'UserAccessControl','options'=>array("sr_no"=>"Sr No","name"=>"Name","description"=>"Description","user_access"=>"User Access"),'pluralVar'=>'userAccessControls'))); ?>

<script type="text/javascript">
$(document).ready(function(){
$('table th a, .pag_list li span a').on('click', function() {
	var url = $(this).attr("href");
	$('#main').load(url);
	return false;
});
});
</script>	
		<div class="table-responsive">
		<?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>				
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th ><input type="checkbox" id="selectAll"></th>
					
				<th><?php echo $this->Paginator->sort('name'); ?></th>
				<th><?php echo $this->Paginator->sort('description'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('user_access'); ?></th> -->
				<!-- <th><?php echo $this->Paginator->sort('division_id'); ?></th> -->
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($userAccessControls){ ?>
<?php foreach ($userAccessControls as $userAccessControl): ?>
	<tr>
		<td class=" actions">	
			<?php echo $this->element('actions', array('created' => $userAccessControl['UserAccessControl']['created_by'], 'postVal' => $userAccessControl['UserAccessControl']['id'], 'softDelete' => $userAccessControl['UserAccessControl']['soft_delete'])); ?>	
		</td>		
		<td><?php echo h($userAccessControl['UserAccessControl']['name']); ?>&nbsp;</td>
		<td><?php echo h($userAccessControl['UserAccessControl']['description']); ?>&nbsp;</td>
		<!-- <td>
			<//?php echo h(json_encode(array_keys(json_decode($userAccessControl['UserAccessControl']['user_access']['user_access'],true)))); ?>&nbsp;
		
			<?php
// Check if the user_access is set and not null before decoding
if (isset($userAccessControl['UserAccessControl']['user_access']['user_access']) && !empty($userAccessControl['UserAccessControl']['user_access']['user_access'])) {
    // Decode JSON data safely
    $userAccessData = json_decode($userAccessControl['UserAccessControl']['user_access']['user_access'], true);

    // Check if the decoded data is an array and process it
    if (is_array($userAccessData)) {
        echo h(json_encode(array_keys($userAccessData)));
    } else {
        // Display a fallback message if the data is invalid
        echo 'Invalid data';
    }
} else {
    // Fallback if user_access is not set or empty
    echo 'No user access data available';
}
?>
&nbsp;

		</td> -->
		<!-- <td>
			<?php echo $this->Html->link($userAccessControl['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $userAccessControl['Division']['id'])); ?>
		</td> -->
		<td><?php echo h($PublishedEmployeeList[$userAccessControl['UserAccessControl']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$userAccessControl['UserAccessControl']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($userAccessControl['UserAccessControl']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=63>No results found</td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>			
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#main',
			'evalScripts' => true,
			'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
			'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
			));
			
			echo $this->Paginator->counter(array(
			'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
			));
			?>			</p>
			<ul class="pagination">
			<?php
		echo "<li class='previous'>".$this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'))."</li>";
		echo "<li>".$this->Paginator->numbers(array('separator' => ''))."</li>";
		echo "<li class='next'>".$this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'))."</li>";
	?>
			</ul>
		</div>
	</div>
	</div>	

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","description"=>"Description","user_access"=>"User Access"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","description"=>"Description","user_access"=>"User Access"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
