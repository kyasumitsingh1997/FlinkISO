<div id="emailTriggers_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="emailTriggers form col-md-8">
<h4><?php echo __('View Email Trigger'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($emailTrigger['EmailTrigger']['name']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Details'); ?></td>
		<td>
			<?php echo h($emailTrigger['EmailTrigger']['details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('System Table'); ?></td>
		<td>
			<?php echo h($emailTrigger['System']['name']); ?>
			&nbsp;
		</td></tr>
		<!--<tr><td><?php echo __('Changed Field'); ?></td>
		<td>
			<?php echo h($emailTrigger['EmailTrigger']['changed_field']); ?>
			&nbsp;
		</td></tr>-->
		<tr><td><?php echo __('If Added'); ?></td>
		<td>
			<?php if($emailTrigger['EmailTrigger']['if_added'])echo "<span class='fa fa-check'></span>"; 
				else echo "<span class='glyphicon glyphicon-remove-sign'></span>"; 
			?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('If Edited'); ?></td>
		<td>
			<?php if($emailTrigger['EmailTrigger']['if_edited'])echo "<span class='fa fa-check'></span>"; 
				else echo "<span class='glyphicon glyphicon-remove-sign'></span>"; 
			?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('If Publish'); ?></td>
		<td>
			<?php if($emailTrigger['EmailTrigger']['if_publish'])echo "<span class='fa fa-check'></span>"; 
				else echo "<span class='glyphicon glyphicon-remove-sign'></span>"; 
			?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('If Approved'); ?></td>
		<td>
			<?php if($emailTrigger['EmailTrigger']['if_approved'])echo "<span class='fa fa-check'></span>"; 
				else echo "<span class='glyphicon glyphicon-remove-sign'></span>"; 
			?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('If Soft Delete'); ?></td>
		<td>
			<?php if($emailTrigger['EmailTrigger']['if_soft_delete'])echo "<span class='fa fa-check'></span>"; 
				else echo "<span class='glyphicon glyphicon-remove-sign'></span>"; 
			?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Recipents'); ?></td>
		<td>
		<?php 
			$recipents = json_decode($emailTrigger['EmailTrigger']['recipents'],true);
				foreach($recipents as $emmployee)
				{
					echo $PublishedEmployeeList[$emmployee] .' , ';
				}
		?>
		&nbsp;
		</td></tr>
		<tr><td><?php echo __('Cc'); ?></td>
		<td>
			<?php 
			$recipents = json_decode($emailTrigger['EmailTrigger']['cc'],true);
				foreach($recipents as $emmployee)
				{
					echo $PublishedEmployeeList[$emmployee] .' , ';
				}
		?>
		&nbsp;
		</td></tr>
		<tr><td><?php echo __('Bcc'); ?></td>
		<td>
			<?php 
			$recipents = json_decode($emailTrigger['EmailTrigger']['bcc'],true);
				foreach($recipents as $emmployee)
				{
					echo $PublishedEmployeeList[$emmployee] .' , ';
				}
		?>
		&nbsp;
		</td></tr>
		<tr><td><?php echo __('Subject'); ?></td>
		<td>
			<?php echo h($emailTrigger['EmailTrigger']['subject']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Template'); ?></td>
		<td>
			<?php echo h($emailTrigger['EmailTrigger']['template']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($emailTrigger['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($emailTrigger['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($emailTrigger['EmailTrigger']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($emailTrigger['EmailTrigger']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->Form->create('Upload',array('role'=>'form','class'=>'form')); ?>
	<fieldset>		<?php 
			echo $this->Upload->edit('upload',$this->Session->read('User.id').'/'.$this->request->params['controller'].'/'.$emailTrigger['EmailTrigger']['id']);
			echo $this->Form->end(); ?>
	</fieldset></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#emailTriggers_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$emailTrigger['EmailTrigger']['id'] ,'ajax'),array('async' => true, 'update' => '#emailTriggers_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
