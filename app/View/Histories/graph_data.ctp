<?php
       echo $this->Html->css(array('jquery.datepicker','chartist/chartist.min'));
    echo $this->Html->script(array('jquery-1.11.3','jquery.datepicker','chartist/chartist.min','chartist/chartist-plugin-axistitle.min','chartist/chartist-plugin-tooltip.min','tooltip.min'));
    // echo $this->Html->script(array('flinkiso', 'chartist/chartist.min','chartist/chartist-plugin-axistitle.min','chartist/chartist-plugin-tooltip.min','tooltip.min'))
    echo $this->fetch('script');
    echo $this->fetch('css');
?>
<style>
  #chart_div{height: 310px;}
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
<div id="recordGraph">
    <script type="text/javascript">
    $(document).ready(function(){
    $('.lk').on('click', function() {
            var tab_id = $("#subtabs .ui-tabs-panel:visible").attr("id");
      	    var url = $(this).attr("href");
            $("#recGraph-busyIndic").show();
	    $('#'+tab_id).load(url);
	    return false;
    });
        <?php if (isset($this->request->params['pass'][0])) { ?>
            $("#record_graph_date").val('<?php echo $this->request->params['pass'][0]; ?>');
        <?php } else { ?>
                var date = new Date(), day = date.getDate(), month = date.getMonth() + 1, year = date.getFullYear();
                if (day < 10)
                    day = '0' + day;
                var dateToday = year + '-' + month + '-' + day;
                $("#record_graph_date").val(dateToday);
        <?php } ?>

    $("#record_graph_date").datetimepicker({
            closeOnDateSelect : true,
            scrollMonth : false,
            scrollInput : false,
            format: 'Y-m-d',
            timepicker: false,
            maxDate: 0,
            onChangeDateTime: function (dp, userInput) {
                var tab_id = $("#subtabs .ui-tabs-panel:visible").attr("id");
                $("#recGraph-busyIndic").show();
        <?php if($this->request->params['pass'][1] && $this->request->params['pass'][2]) { ?>
                $('#' + tab_id).load('<?php echo Router::url('/', true); ?><?php echo 'histories/graph_data/'; ?>' + userInput.val() + '/<?php echo $this->request->params['pass'][1] ?>/<?php echo $this->request->params['pass'][2] ?>');
        <?php } else { ?>
        $('#' + tab_id).load('<?php echo Router::url('/', true); ?><?php echo 'histories/graph_data/'; ?>' + userInput.val());
        <?php } ?>
                $("#record_graph_date").datetimepicker('hide');
            }
        });

    });
    </script>
    <?php
    $this->request->params['pass'][0] = isset($this->request->params['pass'][0])? $this->request->params['pass'][0]:'';
    $this->request->params['pass'][1] = isset($this->request->params['pass'][1])? $this->request->params['pass'][1]:'';
    $this->request->params['pass'][2] = isset($this->request->params['pass'][2])? $this->request->params['pass'][2]:'';

    if($this->request->params['pass'][0]) $newDate = $this->request->params['pass'][0];
	    else $newDate = date('Y-m-d');
    ?>

    <div style="padding: 0px 10px; overflow: auto">
	    <div class="btn-group pull-left">
		<?php foreach ($PublishedBranchList as $key=>$value): ?>
		    <?php if($this->request->params['pass'][2] == $value) $class = 'btn-info disabled'; else $class = 'btn-default' ;?>
		    <?php echo $this->Html->link($value, array('controller'=>'histories','action'=>'graph_data',$newDate,'Branch',$value) , array('class'=>'lk btn btn-sm '.$class));  ?>
		<?php endforeach ?>
		<?php foreach ($PublishedDepartmentList as $key=>$value): ?>
		    <?php if($this->request->params['pass'][2] == $value) $class = 'btn-info disabled'; else $class = 'btn-default' ;?>
		    <?php echo $this->Html->link($value, array('controller'=>'histories','action'=>'graph_data',$newDate,'Department',$value) ,  array('class'=>'lk btn btn-sm '.$class));  ?>
		<?php endforeach ?>
	</div>

        <div class="pull-right" style="max-width: 160px;">
                <div class="input-group">
		<?php echo $this->Html->link('<span class="glyphicon glyphicon-chevron-left"></span>', array('controller'=>'histories','action'=>'graph_data',date('Y-m-d',strtotime('-1 day',strtotime($newDate))),$this->request->params['pass'][1],$this->request->params['pass'][2]),array('class'=>'lk btn btn-sm btn-warning input-group-addon','style'=>'height:30px;color:#fff; background-color:#f0ad4e !important;','escape'=>false)); ?>
                    <?php echo $this->Form->input('record_graph_date', array('type' => 'text', 'id' => 'record_graph_date', 'class' => 'form-control', 'label' => false, 'div' => false, 'style' => 'padding-left: 7px;')); ?>
		<?php echo $this->Html->link('<span class="glyphicon glyphicon-chevron-right"></span>', array('controller'=>'histories','action'=>'graph_data',date('Y-m-d',strtotime('+1 day',strtotime($newDate))),$this->request->params['pass'][1],$this->request->params['pass'][2]),array('class'=>'lk btn btn-sm btn-warning input-group-addon','style'=>'height:30px;color:#fff; background-color:#f0ad4e !important;','escape'=>false)); ?>
                </div>
	    </div>
        <div class="pull-right">
            <?php echo $this->Html->image('indicator.gif', array('id' => 'recGraph-busyIndic', 'class' => 'pull-left', 'style' => 'margin-top: 10px; display:none;')); ?>
</div>
    </div>

	<?php      
    if($PublishedBranchList && isset($series1) && $series1 != false) { ?>
    <script type="text/javascript">
        new Chartist.Line('#chart_div', {
            labels: [<?php echo $labels; ?>],
            series: [{'name':'Actual data', 'data':[<?php echo $series1; ?>]},{'name':'Benchmark', 'data':[<?php echo $series2; ?>]}]
        }, {
          high: 500,
          low: 0,
          showArea: true,  
          fullWidth: true,
          chartPadding: {
    top: 20,
    right: 0,
    bottom: 0,
    left: 10,

  },
            plugins: [
                    Chartist.plugins.ctAxisTitle({
                       axisX: {
                            axisTitle: 'Date',
                            axisClass: 'ct-axis-title',
                            offset: {
                              x: 0,
                              y: 30
                            },
                            textAnchor: 'middle'
                          },
                        axisY: {
                            axisTitle: 'Target Achieved',
                            axisClass: 'ct-axis-title',
                           offset: {
                                x: 0,
                                y: -10
                            },
                          onlyInteger: true,
                          textAnchor: 'middle',
                          offset: 10,
                            
                        }
                    }),
          Chartist.plugins.tooltip()
                    
                ] 
        });
        var $chart = $('.ct-chart');
$.noConflict();
    </script>
    <div class="ct-chart " id="chart_div"></div>      
    </div>

    <?php echo $this->Js->writeBuffer();?>
    <script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
  <?php }else {  ?>
  <div class="">
    <div class="panel-body">
	<h4 class="text-danger">No data found</h4>
	<p>These graphs are generated as per the benchmarks you have set for each departments in each branch.</p>
	<p>System collects the data entered in each branch and then creates a report at end of the day.</p>
    </div>
  </div>
  <?php } ?>
  </div>
