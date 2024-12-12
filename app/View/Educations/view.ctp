<div id="educations_ajax">
<?php echo $this->Session->flash();?>
<div class="nav panel panel-default">
<div class="educations form col-md-8">
<h4><?php echo __('View Education'); ?>
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>
<table class="table table-responsive">
		<tr><td><?php echo __('Sr No'); ?></td>
		<td>
			<?php echo h($education['Education']['sr_no']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($education['Education']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($education['Education']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Branch Ids'); ?></td>
		<td>
			<?php echo $this->Html->link($education['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $education['BranchIds']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Department Ids'); ?></td>
		<td>
			<?php echo $this->Html->link($education['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $education['DepartmentIds']['id'])); ?>
			&nbsp;
		</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $education['Education']['created_by'], 'recordId' => $education['Education']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#educations_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$education['Education']['id'] ,'ajax'),array('async' => true, 'update' => '#educations_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
