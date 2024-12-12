<div class="row">
    <div class="col-md-8 col-sm-6">
        <h4><?php echo h($postData["pluralHumanName"]); ?>
            <span class=""></span>
            <span class=""></span>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
        </h4>
    </div>
    <div class="col-md-4 col-sm-3">

        <h4>From : <?php echo date('Y-m',strtotime($postData['from'])); $month = date('m',strtotime($postData['from']));?>
            To : <?php echo date('Y-m',strtotime($postData['to'])) ?> </h4>            
    </div>
    <div class="col-md-4  col-sm-3">
        <?php 
          $month = date('Y-m',strtotime($postData['from'])); 
          $previous_month = date('Y-m',strtotime('-1 month',strtotime($month)));
          $next_month = date('Y-m',strtotime('+1 month',strtotime($month)));
          $this_month = date('Y-m');
        ?>
        <span class="btn-group btn-group-xs" role="group">
        <?php         
        echo $this->Js->link('<span class="glyphicon glyphicon-step-backward"></span>', array('controller'=>'reports','action'=>'nc_report',$previous_month), array('type'=>'button', 'class'=>'btn  btn-info', 'escape'=>false, 'update' => '#main','evalScripts' => true,'before' => $this->Js->get('#busy-indicator-readiness')->effect('fadeIn',array('buffer' => false)),'complete' => $this->Js->get('#busy-indicator-readiness')->effect('fadeOut',array('buffer' => false)),'htmlAttributes' => array('month' => date('Y-m')))); ?>
        <?php echo $this->Js->link('<span class="glyphicon glyphicon-pause"></span>', array('controller'=>'reports','action'=>'nc_report',date('Y-m')), array('type'=>'button', 'class'=>'btn btn-info', 'escape'=>false, 'update' => '#main','evalScripts' => true,'before' => $this->Js->get('#busy-indicator-readiness')->effect('fadeIn',array('buffer' => false)),'complete' => $this->Js->get('#busy-indicator-readiness')->effect('fadeOut',array('buffer' => false)),'htmlAttributes' => array('month' => date('Y-m')))); ?>
        <?php echo $this->Js->link('<span class="glyphicon glyphicon-step-forward"></span>', array('controller'=>'reports','action'=>'nc_report',$next_month), array('type'=>'button', 'class'=>'btn btn-info', 'escape'=>false, 'update' => '#main','evalScripts' => true,'before' => $this->Js->get('#busy-indicator-readiness')->effect('fadeIn',array('buffer' => false)),'complete' => $this->Js->get('#busy-indicator-readiness')->effect('fadeOut',array('buffer' => false)),'htmlAttributes' => array('month' => date('Y-m')))); ?>
        <?php echo $this->Js->writeBuffer(); ?>
      </span>
    </div>
</div>

