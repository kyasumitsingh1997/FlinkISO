<strong>Select Indetficaition Details</strong>
	<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
		<tr>
			
			<!-- <th><?php echo $this->Paginator->sort('title'); ?></th> -->
			<th><?php echo $this->Paginator->sort('env_activity_id'); ?></th>
			<th><?php echo $this->Paginator->sort('aspect_number'); ?></th>
			<th><?php echo $this->Paginator->sort('aspect_details'); ?></th>
			<th><?php echo $this->Paginator->sort('impact_details'); ?></th>
			<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
			<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
			<th><?php echo $this->Paginator->sort('publish'); ?></th>
		</tr>		
	<?php foreach ($envIdentifications as $envIndentification): ?>
		<tr>
			<td>
				<!-- <input type="radio" name="env_indentification_id" value="<?php echo $envIndentification['EnvIdentification']['id']; ?>"> -->
				<?php //echo $this->Form->input('env_indentificationid',array('type'=>'radio','legend'=>false,'options'=>false,'value'=>$envIndentification['EnvIdentification']['id'],'label'=>false,'div'=>false)); ?>

				<?php
					echo $this->Form->input('EnvEvaluation.env_indentification_id', array(
					    'type' => 'radio',
					    'value' => $envIndentification['EnvIdentification']['id'],
					    'options'=>array($envIndentification['EnvIdentification']['id']=>$envIndentification['EnvIdentification']['title']),
					    'before' => false,
					    'after' => false,
					    'div'=>false,
					    'label'=>false,
					    'legend'=>false,
					    'fieldset'=>false,					    
					));
				?>
			
		<!-- <td><?php echo h($envIndentification['EnvIdentification']['title']); ?>&nbsp;</td> -->
		<?php // echo $this->Html->link($envIndentification['EnvActivity']['title'], array('controller' => 'env_activities', 'action' => 'view', $envIndentification['EnvActivity']['id'])); ?></td>
		<td><?php echo h($envIndentification['EnvIdentification']['aspect_number']); ?>&nbsp;</td>
		<td><?php echo h($envIndentification['EnvIdentification']['aspect_details']); ?>&nbsp;</td>
		<td><?php echo h($envIndentification['EnvIdentification']['impact_details']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$envIndentification['EnvIdentification']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$envIndentification['EnvIdentification']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($envIndentification['EnvIdentification']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
</table>
<?php // echo $this->Form->input('env_indentification_id',array('type'=>'radio','legend'=>false)); ?>