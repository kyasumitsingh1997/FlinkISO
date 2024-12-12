
<?php 
//echo $this->Html->css(array('jquery.datepicker','chartist/chartist.min'));
//    echo $this->Html->script(array('jquery.datepicker','chartist/chartist.min'));
//    echo $this->fetch('script');
//    echo $this->fetch('css');
    echo $this->Html->css(array('chartist/chartist.min'));
    echo $this->fetch('css');
   
   echo $this->Html->script(array('jquery-1.11.3'));
  
    echo $this->fetch('script');
    echo $this->Html->script(array('chartist/chartist.min','chartist/chartist-plugin-axistitle.min'));
    echo $this->fetch('script');
   
?>
<style>
	#proposal{height: 310px;}
</style>
<div id="proposal_graph">

<div class="row">
  <div class="col-md-12">
    <div class="ct-chart panel panel-body panel-default" id="proposal"></div>  
  </div>



</div>

  <script type="text/javascript">
    new Chartist.Line('#proposal', {
        labels: [<?php echo implode(",",$data['labels']); ?>],
        series: [ 
            <?php if(isset($data['Customers_data'])){ ?>
                {'name': 'Customers', 'data': [ <?php echo implode(",",$data['Customers_data']); ?>]},
            <?php } ?>
            <?php if(isset($data['Proposals_data'])){ ?>
                {'name': 'Proposals', 'data': [ <?php echo implode(",",$data['Proposals_data']); ?>]},
            <?php } ?>
            <?php if(isset($data['Customers_data'])){ ?>
                {'name': 'Proposal Follow Ups', 'data': [ <?php echo implode(",",$data['ProposalFollowUps_data']); ?>]},
            <?php } ?>
            <?php if(isset($data['MissedCount'])){ ?>
                {'name': 'Missed Follow Ups', 'data': [ <?php echo implode(",",$data['MissedCount']); ?>]},
            <?php } ?>

          
        ]
    }, {
        fullWidth: true,
          plugins: [
          Chartist.plugins.ctAxisTitle({
            axisX: {
              axisTitle: 'Date',
              axisClass: 'ct-axis-title',
              offset: {
                x: 0,
                y: 50
              },
              textAnchor: 'middle'
            },
            axisY: {
              axisTitle: 'Number of records',
              axisClass: 'ct-axis-title',
              offset: {
                x: 0,
                y: -5
              },
              textAnchor: 'middle',
              flipTitle: false
            }
          })
        ],
        chartPadding: {
    top: 20,
    right: 0,
    bottom: 45,
    left: 0
  },
      
 
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
  <?php
 
     echo $this->Js->writeBuffer(); ?>

</div>    
 
