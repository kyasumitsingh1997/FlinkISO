<style>
  #performance{height: 310px;}
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
?>
<div class="row">
  <div class="col-md-10">
    <div class='ct-chart' id="performance"></div>
  </div>
  <script>
      $(function() {
          $("#from").datepicker({
              defaultDate: "-2m",
              changeMonth: true,
              numberOfMonths: 3,
              onClose: function(selectedDate) {
                  $("#to").datepicker("option", "minDate", selectedDate);
              }
          });
          $("#to").datepicker({
              changeMonth: true,
              numberOfMonths: 3,
              onClose: function(selectedDate) {
                  $("#from").datepicker("option", "maxDate", selectedDate);
              }
          });
      });
  </script>
  <div class="col-md-2">
      <div class="panel panel-info">
          <div class="panel-heading"><h5><?php echo __('Non Conformities Report'); ?></h5></div>
          <div class="panel-body">
              <?php echo $this->Form->create('reports', array('action' => 'nc_report', 'role' => 'form', 'class' => 'form no-padding no-margin')); ?>
              <p>Select start date and date to generate the report.</p>
              <div class="row">
                  <div class="col-md-12"><?php echo $this->Form->input('from', array('id' => 'from', 'label' => false)); ?></div>
                  <div class="col-md-12"><?php echo $this->Form->input('to', array('id' => 'to', 'label' => false)); ?></div>
                  <div class="col-md-12"><?php echo $this->Form->Submit('Submit', array('class' => 'btn btn-success ')); ?></div>
              </div>
              <?php echo $this->Form->end(); ?>
          </div>
      </div>
  </div>
</div>
<?php 
  foreach ($data as $key => $value) {
    $material[] = $value['Material'][$key];
    $product[] = $value['Product'][$key];
    $process[] = $value['Process'][$key];
    $procedure[] = $value['Procedure'][$key];
    $capa[] = $value['CAPA'][$key];
  }
debug($material);
?>
<script type="text/javascript">    
    new Chartist.Bar('#performance', {
        labels: <?php echo json_encode(array_keys($data));?>,
        series: [ 
            {'name':'Material','data':<?php echo json_encode($material)?>},
            {'name':'Product','data':<?php echo json_encode($product)?>},
            {'name':'Process','data':<?php echo json_encode($process)?>},
            {'name':'Procedure','data':<?php echo json_encode($procedure)?>},
            {'name':'CAPA','data':<?php echo json_encode($capa)?>},
        ]
    }, {      
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
                y: 0
              },
              textAnchor: 'middle',
              flipTitle: false
            }
          }),
          Chartist.plugins.tooltip()
        ]
        
});
</script>