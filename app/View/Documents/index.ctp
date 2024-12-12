<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="documents ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Documents','modelClass'=>'Document','options'=>array("sr_no"=>"Sr No","title"=>"Title","document_number"=>"Document Number","issue_number"=>"Issue Number","revision_number"=>"Revision Number","revision_date"=>"Revision Date","content"=>"Content"),'pluralVar'=>'documents'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<th><?php echo $this->Paginator->sort('document_number'); ?></th>
				<th><?php echo $this->Paginator->sort('issue_number'); ?></th>
				<th><?php echo $this->Paginator->sort('revision_number'); ?></th>
				<th><?php echo $this->Paginator->sort('revision_date'); ?></th>
				<th><?php echo $this->Paginator->sort('content'); ?></th>
				<th><?php echo $this->Paginator->sort('division_id'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($documents){ ?>
<?php foreach ($documents as $document): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $document['Document']['created_by'], 'postVal' => $document['Document']['id'], 'softDelete' => $document['Document']['soft_delete'])); ?>	</td>		<td><?php echo h($document['Document']['title']); ?>&nbsp;</td>
		<td><?php echo h($document['Document']['document_number']); ?>&nbsp;</td>
		<td><?php echo h($document['Document']['issue_number']); ?>&nbsp;</td>
		<td><?php echo h($document['Document']['revision_number']); ?>&nbsp;</td>
		<td><?php echo h($document['Document']['revision_date']); ?>&nbsp;</td>
		<td><?php echo h($document['Document']['content']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($document['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $document['Division']['id'])); ?>
		</td>
		<td><?php echo h($PublishedEmployeeList[$document['Document']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$document['Document']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($document['Document']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=72>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","document_number"=>"Document Number","issue_number"=>"Issue Number","revision_number"=>"Revision Number","revision_date"=>"Revision Date","content"=>"Content"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","document_number"=>"Document Number","issue_number"=>"Issue Number","revision_number"=>"Revision Number","revision_date"=>"Revision Date","content"=>"Content"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
