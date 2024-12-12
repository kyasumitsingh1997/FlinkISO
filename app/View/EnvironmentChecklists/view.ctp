<div id="environmentChecklists_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="environmentChecklists form col-md-8">
<h4><?php echo __('View Environment Checklist'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Date Created'); ?></td>
		<td>
			<?php echo h($environmentChecklist['EnvironmentChecklist']['date_created']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $this->Html->link($environmentChecklist['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $environmentChecklist['Branch']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Department'); ?></td>
		<td>
			<?php echo $this->Html->link($environmentChecklist['Department']['name'], array('controller' => 'departments', 'action' => 'view', $environmentChecklist['Department']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($environmentChecklist['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $environmentChecklist['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($environmentChecklist['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($environmentChecklist['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($environmentChecklist['EnvironmentChecklist']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($environmentChecklist['EnvironmentChecklist']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<table class="table table-responsive">
<?php	   
    foreach ($questions as $key => $question) {
      echo "<tr><th colspan='2'><h3>".$question['name']."</h3></th></tr>";
      foreach ($question['questions'] as $q) {
      debug($q);      
        echo "<tr>";
        echo "<td>".$q['EnvironmentQuestionnaire']['title']."</td>";
        echo "<td><strong>".($q['EnvironmentChecklistAnswer']['answer']?'Yes':'No')."</td>";
        if($q['EnvironmentChecklistAnswer']['details'])echo "</tr><tr><td colspan='2'>".$q['EnvironmentChecklistAnswer']['details'] . '</td>'; 
        echo "</tr>";
        $i++;
      }      
    
    }    
?>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $environmentChecklist['EnvironmentChecklist']['created_by'], 'recordId' => $environmentChecklist['EnvironmentChecklist']['id'])); ?>
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#environmentChecklists_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$environmentChecklist['EnvironmentChecklist']['id'] ,'ajax'),array('async' => true, 'update' => '#environmentChecklists_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
