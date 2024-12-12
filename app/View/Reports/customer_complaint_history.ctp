<style>
  #proposal_graph{height: 310px;}
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
<div id="proposal_graph">
<?php 
    echo $this->Html->css(array('chartist/chartist.min'));
    echo $this->fetch('css');
   
    echo $this->Html->script(array('jquery-1.11.3'));
  
    echo $this->fetch('script');
    echo $this->Html->script(array('chartist/chartist.min'));
    echo $this->fetch('script');
?>
<style>
	#performance{height: 310px;}
</style>
<div class="row">
  <div class="col-md-10">
    <div class="ct-chart panel panel-body panel-default" id="performance"></div>  
  </div>
  <script>
    $(function() {
        $("#ccfrom").datepicker({
            defaultDate: "-3m",
            changeMonth: true,
            numberOfMonths: 3,
            onClose: function(selectedDate) {
                $("#ccto").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#ccto").datepicker({
            changeMonth: true,
            numberOfMonths: 3,
            onClose: function(selectedDate) {
                $("#ccfrom").datepicker("option", "maxDate", selectedDate);
            }
        });
    });
</script>
<div class="col-md-2">
    <div class="panel panel-info no-margin">
        <div class="panel-heading"><?php echo __('Customer Complaints'); ?></div>
        <div class="panel-body">
            <?php echo $this->Form->create('reports', array('action' => 'customer_complaint_report', 'role' => 'form', 'class' => 'form no-padding no-margin')); ?>
            <div class="row">
                <div class="col-md-12"><p>Select start date and date to generate the report.</p></div>
                <div class="col-md-12">
                    <?php echo $this->Form->input('from', array('id' => 'ccfrom', 'label' => false, 'class' => 'btn')); ?>
                </div>
                <div class="col-md-12">
                    <?php echo $this->Form->input('to', array('id' => 'ccto', 'label' => false, 'class' => 'btn')); ?>
                </div>
                <div class="col-md-12">
                    <?php echo $this->Form->Submit('Submit', array('class' => 'btn btn-success ')); ?>                                        
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div> 


</div>

  <script type="text/javascript">
    new Chartist.Line('#performance', {
        labels: [<?php echo $labels; ?>],
        series: [ 
            <?php if(isset($series1)){ ?>
                {'name': 'Total', 'data': [ <?php echo $series1; ?>]},
            <?php } ?>
            <?php if(isset($series2)){ ?>
                {'name': 'Open', 'data': [ <?php echo $series2; ?>]},
            <?php } ?>
            <?php if(isset($series3)){ ?>
                {'name': 'Close', 'data': [ <?php echo $series3; ?>]},
            <?php } ?>
            <?php if(isset($series4)){ ?>
                {'name': 'Setteled in time', 'data': [ <?php echo $series4; ?>]},
            <?php } ?>

          
        ]
    }, {
        fullWidth: true,
        chartPadding: {
        right: 40
    }
});

var $chart = $('.ct-chart');

var $toolTip = $chart
  .append('<div class="tooltip"></div>')
  .find('.tooltip')
  .hide();

$chart.on('mouseenter', '.ct-point', function() {
  var $point = $(this),
    value = $point.attr('ct:value'),
    seriesName = $point.parent().attr('ct:series-name');
  $toolTip.html(seriesName + '<br>' + value).show();
});

$chart.on('mouseleave', '.ct-point', function() {
  $toolTip.hide();
});

$chart.on('mousemove', function(event) {
  $toolTip.css({
    left: (event.offsetX || event.originalEvent.layerX) - $toolTip.width() / 2 - 10,
    top: (event.offsetY || event.originalEvent.layerY) - $toolTip.height() - 40
  });
});
$.noConflict();
  </script>
 
    <?php echo $this->Js->writeBuffer(); ?>

</div>    
 
