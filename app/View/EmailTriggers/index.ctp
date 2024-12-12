<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="emailTriggers ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Email Triggers','modelClass'=>'EmailTrigger','options'=>array("sr_no"=>"Sr No","name"=>"Name","details"=>"Details","system_table"=>"System Table","changed_field"=>"Changed Field","if_added"=>"If Added","if_edited"=>"If Edited","if_publish"=>"If Publish","if_approved"=>"If Approved","if_soft_delete"=>"If Soft Delete","recipents"=>"Recipents","cc"=>"Cc","bcc"=>"Bcc","subject"=>"Subject","template"=>"Template"),'pluralVar'=>'emailTriggers'))); ?>

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
				<!--<th><?php echo $this->Paginator->sort('details'); ?></th>-->
				<th><?php echo $this->Paginator->sort('system_table'); ?></th>
				<th><?php echo $this->Paginator->sort('branch_id'); ?></th>
				<th><?php echo $this->Paginator->sort('recipents'); ?></th>
				<th><?php echo $this->Paginator->sort('if_added'); ?></th>
				<th><?php echo $this->Paginator->sort('if_edited'); ?></th>
				<th><?php echo $this->Paginator->sort('if_publish'); ?></th>
				<th><?php echo $this->Paginator->sort('if_approved'); ?></th>
				<th><?php echo $this->Paginator->sort('if_soft_delete'); ?></th>
				
				<!--<th><?php echo $this->Paginator->sort('cc'); ?></th>
				<th><?php echo $this->Paginator->sort('bcc'); ?></th>
				<th><?php echo $this->Paginator->sort('subject'); ?></th>
				<th><?php echo $this->Paginator->sort('template'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>	-->	
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($emailTriggers){ ?>
<?php foreach ($emailTriggers as $emailTrigger): ?>
	<tr>
		<td class=" actions">	
			<?php echo $this->element('actions', array('created' => $emailTrigger['EmailTrigger']['created_by'], 'postVal' => $emailTrigger['EmailTrigger']['id'], 'softDelete' => $emailTrigger['EmailTrigger']['soft_delete'])); ?>	
		</td>		
		<td><?php echo h($emailTrigger['EmailTrigger']['name']); ?>&nbsp;</td>
		<!--<td><?php echo h($emailTrigger['EmailTrigger']['details']); ?>&nbsp;</td>-->
		<td><?php echo h($emailTrigger['System']['name']); ?>&nbsp;</td>
		<td><?php echo h($PublishedBranchList[$emailTrigger['EmailTrigger']['branch_id']]); ?>&nbsp;</td>
		<td><?php 
			echo "<strong>To :</strong>";
			$recipents = json_decode($emailTrigger['EmailTrigger']['recipents'],true);
			foreach($recipents as $emmployee)
			{
				echo $PublishedEmployeeList[$emmployee] .' , ';
			}
		?><br /><?php 
			echo "<strong>cc :</strong>";
			$recipents = json_decode($emailTrigger['EmailTrigger']['cc'],true);
			foreach($recipents as $emmployee)
			{
				echo $PublishedEmployeeList[$emmployee] .' , ';
			}
		?><br /><?php 
			echo "<strong>bcc :</strong>";
			$recipents = json_decode($emailTrigger['EmailTrigger']['bcc'],true);
			foreach($recipents as $emmployee)
			{
				echo $PublishedEmployeeList[$emmployee] .' , ';
			}
		?>&nbsp;</td>
		<td>
			<?php 
				if($emailTrigger['EmailTrigger']['if_added'])echo "<span class='fa fa-check'></span>"; 
				else echo "<span class='glyphicon glyphicon-remove-sign'></span>"; 
			?>&nbsp;
		</td>
		<td>
			<?php 
				if($emailTrigger['EmailTrigger']['if_edited'])echo "<span class='fa fa-check'></span>"; 
				else echo "<span class='glyphicon glyphicon-remove-sign'></span>"; 
			?>&nbsp;
		</td>
		
		<td>
			<?php 
				if($emailTrigger['EmailTrigger']['if_publish'])echo "<span class='fa fa-check'></span>"; 
				else echo "<span class='glyphicon glyphicon-remove-sign'></span>"; 
			?>&nbsp;
		</td>
		
		<td>
			<?php 
				if($emailTrigger['EmailTrigger']['if_approved'])echo "<span class='fa fa-check'></span>"; 
				else echo "<span class='glyphicon glyphicon-remove-sign'></span>"; 
			?>&nbsp;
		</td>
		
		<td>
			<?php 
				if($emailTrigger['EmailTrigger']['if_soft_delete'])echo "<span class='fa fa-check'></span>"; 
				else echo "<span class='glyphicon glyphicon-remove-sign'></span>"; 
			?>&nbsp;
		</td>
		
		
		<!--<td><?php echo h($emailTrigger['EmailTrigger']['cc']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['bcc']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['subject']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['template']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$emailTrigger['EmailTrigger']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$emailTrigger['EmailTrigger']['approved_by']]); ?>&nbsp;</td>-->

		<td width="60">
			<?php if($emailTrigger['EmailTrigger']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=93>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","details"=>"Details","system_table"=>"System Table","changed_field"=>"Changed Field","if_added"=>"If Added","if_edited"=>"If Edited","if_publish"=>"If Publish","if_approved"=>"If Approved","if_soft_delete"=>"If Soft Delete","recipents"=>"Recipents","cc"=>"Cc","bcc"=>"Bcc","subject"=>"Subject","template"=>"Template"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","details"=>"Details","system_table"=>"System Table","changed_field"=>"Changed Field","if_added"=>"If Added","if_edited"=>"If Edited","if_publish"=>"If Publish","if_approved"=>"If Approved","if_soft_delete"=>"If Soft Delete","recipents"=>"Recipents","cc"=>"Cc","bcc"=>"Bcc","subject"=>"Subject","template"=>"Template"))); ?>
<?php echo $this->element('export'); ?>
<?php echo $this->element('approvals'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
