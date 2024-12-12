<style>
  #performance{
    height: 310px;
    padding: 0px 0px 0px 15px;
  }
  .chartist-tooltip {
    position: absolute;
    display: inline-block ;
    opacity: 0;
    min-width: 5em;
    padding: .5em;
    background: #F4C63D;
    color: #453D3F;
    font-family: Oxygen,Helvetica,Arial,sans-serif;
    font-weight: 700;
    text-align: center;
    pointer-events: none;
    z-index: 1;
    -webkit-transition: opacity .2s linear;
    -moz-transition: opacity .2s linear;
    -o-transition: opacity .2s linear;
    transition: opacity .2s linear; 
}
  .chartist-tooltip:before {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    width: 0;
    height: 0;
    margin-left: -15px;
    border: 15px solid transparent;
    border-top-color: #F4C63D; }
  .chartist-tooltip.tooltip-show {
    opacity: 1; }
.ct-chart .ct-bar{stroke-width: 18px !important}
/*# sourceMappingURL=chartist-plugin-tooltip.css.map */
</style>
<div id="performance_charts">
<?php if(isset($no_reports)){ ?> 

<?php }else { ?>
<?php
    if($this->request->data){
        echo $this->Html->script(array('chosen.min', 'chartist/chartist.min','chartist/chartist-plugin-axistitle.min','chartist/chartist-plugin-tooltip.min'));    
        echo $this->fetch('script');
    }else{
      echo $this->Html->script(array('chosen.min', 'chartist/chartist.min','chartist/chartist-plugin-axistitle.min','chartist/chartist-plugin-tooltip.min'));    
      echo $this->fetch('script');
    
    }
    echo $this->Html->css(array('chartist/chartist.min'));
    echo $this->fetch('css');

    if($this->request->data['PerformanceChart']['chart_type'] == 0){$bar_type = 'Bar';$tooltip_type = '.ct-bar';}
    elseif($this->request->data['PerformanceChart']['chart_type'] == 1){$bar_type = 'Line';$tooltip_type = '.ct-point';}
    else {$bar_type = 'Bar';$tooltip_type = '.ct-bar';}
?>
<?php 
    echo $this->Form->create('PerformanceChart', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
<div class="row">
  <div class="col-md-2">
    <?php echo $this->Form->input('chart_type', array('id' => 'chart_type', 'label' => __('Change Chart Type'), 'options' => array('Bar','Line')));?>
  </div>
  <div class="col-md-2">
    <?php 
        for($i = 2 ; $i < 24; $i = $i+1) {
            $options[$i] = $i . '-months';                
        }
        echo $this->Form->input('months', array('id' => 'month', 'label' => __('Change Month'), 'options' => $options,'default'=>4));
        ?>
  </div>
  <div class="col-md-8"></div>
  <div class="col-md-10">
    <div class="ct-chart panel panel-body panel-default" id="performance"></div>  
  </div>
  <div class="col-md-2">
<div class="btn-group">
  <?php
        echo $this->Form->input('Sections', array(
        'label'=>false,
        'type' => 'select',
        'class'=>'checkbox selector',
        'multiple' => 'checkbox',
        'options' => array(
                'CAPA' => 'CAPA',
                'NC' => 'NC',
                'Change Requests' => 'Change Requests',
                // 'Users' => 'Users',
                'Trainings' => 'Trainings',
                'Suppliers' => 'Suppliers',
                'Incidents' => 'Incidents',
                'Objectives' => 'Objectives',
                'Complaints' => 'Complaints'
        ),'default'=>array('CAPA',
                'NC',
                'Change Requests',
                // 'Users',
                'Trainings',
                'Suppliers',
                'Incidents',
                'Objectives',
                'Complaints'
                )
    ));
      echo "<br />".$this->Js->submit('Reload Graph', array(
         'url' => array(
              'controller' => 'histories',
              'action' => 'performance_chart'
          ),
          'before'=>$this->Js->get('#sending')->effect('fadeIn'),
          'success'=>$this->Js->get('#sending')->effect('fadeOut'),
          'update'=>'#performance_charts',
          'class'=>'btn btn-sm btn-info'
         ));      
  ?>
  <?php echo $this->Form->end(); ?>
     <div id="sending" style="display: none;"><?php echo __('reloading')?>...</div>
</div>
</div>


</div>
<?php 
if(
  isset($open_capas) or 
  isset($open_ncs) or 
  isset($open_change_reqs) or 
  //isset($users) or 
  isset($trainings) or 
  isset($suppliers) or 
  isset($complaints) or 
  isset($objectives) or 
  isset($incidents)
  ){?> 
  <script type="text/javascript">    
    new Chartist.<?php echo $bar_type;?>('#performance', {
        labels: [<?php echo $label; ?>],
        series: [ 
            <?php if(isset($open_capas)){ ?>
                {'name': '<?php echo __("Open CAPA")?>', 'data':  [<?php echo $open_capas; ?>]},
            <?php $x .= 'Open CAPA ,';} ?>
            <?php if(isset($close_capas)){ ?>
                {'name': '<?php echo __("Close CAPA")?>', 'data':  [<?php echo $close_capas; ?>]},
            <?php $x .= 'Close CAPA ,';} ?>

            <?php if(isset($open_ncs)){ ?>
                {'name': '<?php echo __("Open NC");?>', 'data': [<?php echo $open_ncs; ?>]},
            <?php $x .= 'Open NC ,';} ?>
            <?php if(isset($close_ncs)){ ?>
                {'name': '<?php echo __("Close NC")?>', 'data': [<?php echo $close_ncs; ?>]},
            <?php $x .= 'Close NC ,';} ?>

            <?php if(isset($open_change_reqs)){ ?>      
                {'name': '<?php echo __("Open Change Requests");?>', 'data':   [<?php echo $open_change_reqs; ?>]},
            <?php $x .= 'Open CRs ,';} ?>
            <?php if(isset($close_change_reqs)){ ?>      
                {'name': '<?php echo __("Close Change Requests");?>', 'data':   [<?php echo $close_change_reqs; ?>]},
            <?php $x .= 'Close CRs ,';} ?>

            // <?php if(isset($users)){ ?>
            //     {'name': 'Users', 'data': [<?php echo $users; ?>,<?php echo $employees; ?>]},                
            // <?php $x .= 'Users ,';} ?>

            <?php if(isset($trainings)){ ?>  
                {'name': '<?php echo __("Trainings")?>', 'data':  [<?php echo $trainings; ?>]},
            <?php $x .= 'Trainings ,';} ?>
            <?php if(isset($evaluations)){ ?>  
                {'name': '<?php echo __("Training Evaluations")?>', 'data':  [<?php echo $evaluations; ?>]},
            <?php $x .= 'Traning Evaluations ,';} ?>

            <?php if(isset($suppliers)){ ?>
            {'name': '<?php echo __("Suppliers")?>', 'data':  [<?php echo $suppliers; ?>]},
            <?php $x .= 'Suppliers ,';} ?>
            <?php if(isset($supplier_evaluations)){ ?>
            {'name': '<?php echo __("Suppliers")?>', 'data':  [<?php echo $supplier_evaluations; ?>]},
            <?php $x .= 'Supplier Evaluations ,';} ?>

            <?php if(isset($incidents)){ ?>
                 {'name': '<?php echo __("Incidents")?>', 'data': [<?php echo $incidents; ?>]},
            <?php $x .= 'Incidents ,';} ?>
            <?php if(isset($incident_investigations)){ ?>
                 {'name': '<?php echo __("Investigations")?>', 'data': [<?php echo $incident_investigations; ?>]},
            <?php $x .= 'Investigations ,';} ?>

            <?php if(isset($objectives)){ ?>
                {'name': '<?php echo __("Objectives")?>',  'data': [<?php echo $objectives; ?>]},
            <?php $x .= 'Objectives ,';} ?> 

            <?php if(isset($complaints)){ ?>
                {'name': '<?php echo __("Complaints")?>',  'data': [<?php echo $complaints; ?>]},
            <?php $x .= 'Complaints ,';} ?> 
        ]
    }, {
      <?php if($chart_type == 'Bar')echo 'stackBars: true,seriesBarDistance: 15,distributeSeries: true'; ?>
        fullWidth: true,
        plugins: [
          Chartist.plugins.ctAxisTitle({
            axisX: {
              axisTitle: '<?php echo __("Days");?>',
              axisClass: 'ct-axis-title',
              onlyInteger: true,
              offset: {
                x: 0,
                y: 30
              },
              textAnchor: 'middle'
            },
            axisY: {
              axisTitle: '<?php echo $x; ?>',
              axisClass: 'ct-axis-title',
              offset: {
                x: 0,
                y: 0
              },
              textAnchor: 'middle',
              flipTitle: false
            }
          }),
          Chartist.plugins.tooltip()
        ]
        
});

// var $chart = $('.ct-chart');

// var $toolTip = $chart
//   .append('<div class="tooltip"></div>')
//   .find('.tooltip')
//   .hide();

// $chart.on('mouseenter', '<?php echo $tooltip_type; ?>', function() {
//   var $point = $(this),
//     value = $point.attr('ct:value'),
//     seriesName = $point.parent().attr('ct:series-name');
//   $toolTip.html(seriesName + '<br>' + value).show();
// });

// $chart.on('mouseleave', '<?php echo $tooltip_type; ?>', function() {
//   $toolTip.hide();
// });

// $chart.on('mousemove', function(event) {
//   $toolTip.css({
//     left: (event.offsetX || event.originalEvent.layerX) - $toolTip.width() / 2 - 10,
//     top: (event.offsetY || event.originalEvent.layerY) - $toolTip.height() - 40
//   });
// });
// $.noConflict();
  </script>
<?php } ?>  
    
<?php } ?>
<script type="text/javascript">
$('#month').chosen();
$('#chart_type').chosen();
</script>
</div>
<?php echo $this->Js->writeBuffer(); ?>
