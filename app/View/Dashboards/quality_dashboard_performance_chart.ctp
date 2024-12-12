<style>
  #performance{height: 340px  !important; border:none !important; display: block; padding: 0px}
  #performance_charts{height: 380px  !important; border:none !important; display: block;}
  .ct-chart .ct-bar{stroke-width: 18px !important}
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

/*# sourceMappingURL=chartist-plugin-tooltip.css.map */
</style>
<div id="performance_charts">
<?php if(isset($no_reports)){ ?> 

<?php }else { ?>
<?php
    if($this->request->data){
        echo $this->Html->script(array('chartist/chartist.min','chartist/chartist-plugin-axistitle.min','chartist/chartist-plugin-tooltip.min','tooltip.min'));    
        echo $this->fetch('script');
    }else{
      echo $this->Html->script(array('chartist/chartist.min','chartist/chartist-plugin-axistitle.min','chartist/chartist-plugin-tooltip.min','tooltip.min'));    
      echo $this->fetch('script');
    
    }
    echo $this->Html->css(array('chartist/chartist.min','tooltip.min'));
    echo $this->fetch('css');

    if($this->request->data['PerformanceChart']['chart_type'] == 0){$bar_type = 'Bar';$tooltip_type = '.ct-bar';}
    elseif($this->request->data['PerformanceChart']['chart_type'] == 1){$bar_type = 'Line';$tooltip_type = '.ct-point';}
    else {$bar_type = 'Bar';$tooltip_type = '.ct-bar';}
?>
<?php 
    echo $this->Form->create('PerformanceChart', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
<div class="row">
  <div class="col-md-10">
    <div class="ct-chart panel panel-body panel-default" id="performance"></div>  
  </div>
  <div class="col-md-2"><br /><br />
    <?php echo $this->Form->input('chart_type', array('id' => 'chart_type', 'label' => __('Change Chart Type'), 'options' => array('Bar','Line')));?>
    <?php 
        for($i = 2 ; $i < 24; $i = $i+1) {
            $options[$i] = $i . '-months';                
        }
        echo $this->Form->input('months', array('id' => 'month', 'label' => __('Change Month'), 'options' => $options,'default'=>4));
        ?>
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
                'Complaints' => 'Complaints'
        ),'default'=>array('CAPA',
                'NC',
                'Change Requests',
                'Complaints'
                )
    ));
      echo "<br />".$this->Js->submit('Reload Graph', array(
         'url' => array(
              'controller' => 'dashboards',
              'action' => 'quality_dashboard_performance_chart'
          ),
          'before'=>$this->Js->get('#sending')->effect('fadeIn'),
          'success'=>$this->Js->get('#sending')->effect('fadeOut'),
          'update'=>'#performance_charts',
          'class'=>'btn btn-sm btn-info'
         ));      
  ?>
  <?php echo $this->Form->end(); ?>
     <div id="sending" style="display: none;">reloading...</div>
</div>
</div>


</div>
<?php 
if(
  isset($open_capas) or 
  isset($open_ncs) or 
  isset($complaints)
  ){?> 
  <script type="text/javascript">    
    new Chartist.<?php echo $bar_type;?>('#performance', {
        labels: [<?php echo $label; ?>],
        series: [ 
            <?php if(isset($open_capas)){ ?>
                {'name': 'Open CAPA', 'data':  [<?php echo $open_capas; ?>]},
            <?php $x .= 'Open CAPA ,';} ?>
            <?php if(isset($close_capas)){ ?>
                {'name': 'Close CAPA', 'data':  [<?php echo $close_capas; ?>]},
            <?php $x .= 'Close CAPA ,';} ?>

            <?php if(isset($open_ncs)){ ?>
                {'name': 'Open NC', 'data': [<?php echo $open_ncs; ?>]},
            <?php $x .= 'Open NC ,';} ?>
            <?php if(isset($close_ncs)){ ?>
                {'name': 'Close NC', 'data': [<?php echo $close_ncs; ?>]},
            <?php $x .= 'Close NC ,';} ?>
            
            <?php if(isset($complaints)){ ?>
                {'name': 'Complaints',  'data': [<?php echo $complaints; ?>]},
            <?php $x .= 'Complaints ,';} ?> 
        ]
    }, {
      <?php if($chart_type == 'Bar')echo 'stackBars: true,seriesBarDistance: 15,distributeSeries: true'; ?>
        fullWidth: true,
        plugins: [
          Chartist.plugins.ctAxisTitle({
            axisX: {
              axisTitle: 'Month',
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
                y: -10
              },
              textAnchor: 'middle',
              flipTitle: false
            }
          }),
          Chartist.plugins.tooltip()
        ]
        
  });
</script>

<?php } ?>  
    
<?php } ?>
<script type="text/javascript">
$('#month').chosen();
$('#chart_type').chosen();
</script>
</div>
<?php echo $this->Js->writeBuffer(); ?>
