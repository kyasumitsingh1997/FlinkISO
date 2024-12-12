<?php echo $this->Session->flash();?>
<div class="row">
	<div class="col-md-12"><?php echo $this->Session->flash();?></div>
	<div class="col-md-12 text-center">
		<?php echo $this->Html->link(
			'Refresh',
				array('controller'=>'projects','action'=>'view',$this->request->params['pass'][0]),
				array('class'=>'btn btn-xl btn-success')
			);?>
	</div>
</div>