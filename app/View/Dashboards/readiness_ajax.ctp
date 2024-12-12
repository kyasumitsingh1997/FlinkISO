<?php 	
	$previous_month = date('Y-m',strtotime('-1 month',strtotime($month)));
	$next_month = date('Y-m',strtotime('+1 month',strtotime($month)));
	$this_month = date('Y-m');
?>
<div class="row">
	<div class="col-md-7">
		<?php echo $readiness;?>% <span class=""><?php echo $this->Html->link(__('Readiness') . ' <span class="glyphicon glyphicon-hand-right"></span>', array('controller' => 'dashboards', 'action' => 'readiness'), array('escape' => false)); ?> (<?php echo __(date('M-y',strtotime($this->request->params['pass'][0]))); ?>)</span>
	</div>	
	<div class="col-md-5 text-right">
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator-readiness')); ?>
		<span class="pull-right btn-group btn-group-xs" role="group">
        <?php echo $this->Js->link('<span class="glyphicon glyphicon-step-backward"></span>', array('controller'=>'dashboards','action'=>'readiness_ajax',$previous_month), array('type'=>'button', 'class'=>'btn  btn-info', 'escape'=>false, 'update' => '#get_readyness','evalScripts' => true,'before' => $this->Js->get('#busy-indicator-readiness')->effect('fadeIn',array('buffer' => false)),'complete' => $this->Js->get('#busy-indicator-readiness')->effect('fadeOut',array('buffer' => false)),'htmlAttributes' => array('month' => date('Y-m')))); ?>
        <?php echo $this->Js->link('<span class="glyphicon glyphicon-pause"></span>', array('controller'=>'dashboards','action'=>'readiness_ajax',date('Y-m')), array('type'=>'button', 'class'=>'btn btn-info', 'escape'=>false, 'update' => '#get_readyness','evalScripts' => true,'before' => $this->Js->get('#busy-indicator-readiness')->effect('fadeIn',array('buffer' => false)),'complete' => $this->Js->get('#busy-indicator-readiness')->effect('fadeOut',array('buffer' => false)),'htmlAttributes' => array('month' => date('Y-m')))); ?>
        <?php echo $this->Js->link('<span class="glyphicon glyphicon-step-forward"></span>', array('controller'=>'dashboards','action'=>'readiness_ajax',$next_month), array('type'=>'button', 'class'=>'btn btn-info', 'escape'=>false, 'update' => '#get_readyness','evalScripts' => true,'before' => $this->Js->get('#busy-indicator-readiness')->effect('fadeIn',array('buffer' => false)),'complete' => $this->Js->get('#busy-indicator-readiness')->effect('fadeOut',array('buffer' => false)),'htmlAttributes' => array('month' => date('Y-m')))); ?>
        <?php echo $this->Js->writeBuffer(); ?>
      </span>
    </div>
    <div class="col-md-12">  
      <div class="progress">
        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $readiness ?>%;"> <?php echo $readiness;?>%<span class="sr-only"><?php echo __('60% Complete (warning)'); ?></span> </div>
      </div>
  </div>
</div>      
