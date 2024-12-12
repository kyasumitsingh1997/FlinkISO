<?php
      echo $this->Html->css(array('jquery.datepicker','chartist/chartist.min'));
    echo $this->Html->script(array('jquery.datepicker','chartist/chartist.min','chartist/chartist-plugin-axistitle.min','chartist/chartist-plugin-tooltip.min'));
    echo $this->fetch('script');
    echo $this->fetch('css');
?>

<script type="text/javascript">
    $(document).ready(function(){
        $('.lk').on('click', function() {
            var tab_id = $("#subtabs .ui-tabs-panel:visible").attr("id");
            var url = $(this).attr("href");
            $("#deptGraph-busyIndic").show();
             $('#'+tab_id).load(url);
            return false;
        });
        <?php if (isset($this->request->params['pass'][0])) { ?>
            $("#graph_departments_date").val('<?php echo $this->request->params['pass'][0]; ?>');
        <?php } else { ?>
                var date = new Date(), day = date.getDate(), month = date.getMonth() + 1, year = date.getFullYear();
                if (day < 10)
                    day = '0' + day;
                var dateToday = year + '-' + month + '-' + day;
                $("#graph_departments_date").val(dateToday);
        <?php } ?>

        $("#graph_departments_date").datetimepicker({
            closeOnDateSelect : true,
            scrollMonth : false,
            scrollInput : false,
            format: 'Y-m-d',
            timepicker: false,
            maxDate: 0,
            onChangeDateTime: function (dp, userInput) {
                var tab_id = $("#subtabs .ui-tabs-panel:visible").attr("id");
                $("#deptGraph-busyIndic").show();
                <?php if($this->request->params['pass'][1] && $this->request->params['pass'][2]) { ?>
                    $('#' + tab_id).load('<?php echo Router::url('/', true); ?><?php echo 'histories/graph_data_departments/'; ?>' + userInput.val() + '/<?php echo $this->request->params['pass'][1] ?>/<?php echo $this->request->params['pass'][2] ?>');
                <?php } else { ?>
                $('#' + tab_id).load('<?php echo Router::url('/', true); ?><?php echo 'histories/graph_data_departments/'; ?>' + userInput.val());
                <?php } ?>
                $("#graph_departments_date").datepicker('hide');
            }
        });
    });
</script>

<?php
    $this->request->params['pass'][0] = isset($this->request->params['pass'][0])? $this->request->params['pass'][0]:'';
    $this->request->params['pass'][1] = isset($this->request->params['pass'][1])? $this->request->params['pass'][1]:'';
    $this->request->params['pass'][2] = isset($this->request->params['pass'][2])? $this->request->params['pass'][2]:'';
    $departmentData = isset($departmentData)? $departmentData : '';
  	$departmentData = str_replace('<benchmark>',10,$departmentData);

  if($this->request->params['pass'][0])$newDate = $this->request->params['pass'][0];
  else $newDate = date('Y-m-d');
?>

<div style="padding: 0px 10px; overflow: auto">
    <div class="pull-right" style="max-width: 160px;">
            <div class="input-group">
            <?php echo $this->Html->link('<span class="glyphicon glyphicon-chevron-left"></span>', array('controller'=>'histories','action'=>'graph_data_departments',date('Y-m-d',strtotime('-1 day',strtotime($newDate))),$this->request->params['pass'][1],$this->request->params['pass'][2]),array('class'=>'lk btn btn-sm btn-warning input-group-addon','style'=>'height:30px;color:#fff; background-color:#f0ad4e !important;','escape'=>false)); ?>
                <?php echo $this->Form->input('graph_departments_date', array('type' => 'text', 'id' => 'graph_departments_date', 'class' => 'form-control', 'label' => false, 'div' => false, 'style' => 'padding-left: 7px;')); ?>
            <?php echo $this->Html->link('<span class="btn-warning glyphicon glyphicon-chevron-right"></span>', array('controller'=>'histories','action'=>'graph_data_departments',date('Y-m-d',strtotime('+1 day',strtotime($newDate))),$this->request->params['pass'][1],$this->request->params['pass'][2]),array('class'=>'lk btn btn-sm btn-warning input-group-addon','style'=>'height:30px;color:#fff; background-color:#f0ad4e !important;','escape'=>false)); ?>
            </div>
        </div>
    <div class="pull-right">
        <?php echo $this->Html->image('indicator.gif', array('id' => 'deptGraph-busyIndic', 'class' => 'pull-left', 'style' => 'margin-top: 10px; display:none;')); ?>
    </div>
	    </div>

<?php if($labels != false) { ?>
<div style="clear:both">
    <script type="text/javascript">

new Chartist.Bar('#department_chart_div', {
  labels: [<?php echo $labels; ?>],
  series: [[<?php echo $series1; ?>],[<?php echo $series2; ?>]]
  },{
  high: 100,
  low: 0,
  showArea: true,  
    //axisY: {onlyInteger: true, offset: 5} 
  plugins: [
                    Chartist.plugins.ctAxisTitle({
                       axisX: {
                            axisTitle: 'Department',
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
                                y: -5
                            },
                           onlyInteger: true,
                            textAnchor: 'middle',
                            flipTitle: false 
                        }
                    }),
                     Chartist.plugins.tooltip()
                ]
});


</script>

<div class="ct-chart" id="department_chart_div" style="width: 100%; height: 400px;"></div>
</div>
<?php }else {  ?>
  <div class="">
    <div class="panel-body">
	<h4 class="text-danger">No data found</h4>
	<p>These graphs are generated as per the benchmarks you have set for each departments in each branch.</p>
	<p>System collects the data entered in each branch and then creates a report at end of the day.</p>
    </div>
  </div>
  <?php } ?>
