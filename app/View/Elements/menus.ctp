<?php if($this->Session->read('Administrator.type_id') == 1) { ?>
<div class="actions">
	<h3><?php echo __('Super Administrator'); ?></h3>
	<ul>
		<ul>
                <li><?php echo $this->Html->link('Dashboard',array('controller'=>'administrators','action'=>'dashboard')); ?></li>
                <li><?php echo $this->Html->link('Administrators',array('controller'=>'administrators','action'=>'index')); ?></li>
                <li><?php echo $this->Html->link('Add New Administrator',array('controller'=>'administrators','action'=>'add')); ?></li>
            	<li><?php echo $this->Html->link('User Details',array('controller'=>'uploads','action'=>'index')); ?></li>
				<li><?php echo $this->Html->link('Question Bank',array('controller'=>'question_banks','action'=>'index')); ?></li>                
            	<li><?php echo $this->Html->link('Logout',array('controller'=>'administrators','action'=>'logout')); ?></li>
            </ul>
	</ul>
</div>
<?php } else { ?>
<div class="actions">
	<h3><?php echo __('Administrator'); ?></h3>
	<ul>
		<ul>
                <li><?php echo $this->Html->link('Dashboard',array('controller'=>'administrators','action'=>'dashboard')); ?></li>
            	<li><?php echo $this->Html->link('User Details',array('controller'=>'uploads','action'=>'index')); ?></li>
				<li><?php echo $this->Html->link('Question Bank',array('controller'=>'question_banks','action'=>'index')); ?></li>                
            	<li><?php echo $this->Html->link('Logout',array('controller'=>'administrators','action'=>'logout')); ?></li>
            </ul>
	</ul>
</div>

<?php } ?>
