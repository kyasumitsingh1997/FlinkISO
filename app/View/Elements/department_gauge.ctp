<?php
    echo $this->Html->css(array('jquery.datepicker'));
    echo $this->Html->script(array('jquery.datepicker'));
    echo $this->fetch('script');
    echo $this->fetch('css');
    $this->request->params['pass'][0] = isset($this->request->params['pass'][0])? $this->request->params['pass'][0]:'';
    $this->request->params['pass'][1] = isset($this->request->params['pass'][1])? $this->request->params['pass'][1]:'';
    $this->request->params['pass'][2] = isset($this->request->params['pass'][2])? $this->request->params['pass'][2]:'';
    if($this->request->params['pass'][0])$newDate = $this->request->params['pass'][0];
    else $newDate = date('Y-m-d',strtotime('-1 day'));
?>

<div class="panel-heading"><h3 class="panel-title pull-left"><?php echo __('Department-wise'); ?></h3>
	<div style="padding: 0px 0px; overflow: auto">
        <div class="pull-right" style="max-width: 175px;">
                <div class="input-group">
                <?php echo $this->Html->link('<span class="glyphicon glyphicon-chevron-left"></span>', array('controller' => 'histories', 'action' => 'department_guage', date('Y-m-d', strtotime('-1 day', strtotime($newDate))), $this->request->params['pass'][1], $this->request->params['pass'][2]), array('class' => 'lgauge btn btn-sm btn-warning input-group-addon', 'style' => 'height:30px;color:#fff; background-color:#f0ad4e !important;', 'escape' => false)); ?>
                    <?php echo $this->Form->input('departments_guages_date', array('type' => 'text', 'id' => 'departments_guages_date', 'class' => 'form-control', 'label' => false, 'div' => false, 'style' => 'padding-left: 7px;')); ?>
                <?php echo $this->Html->link('<span class="btn-warning glyphicon glyphicon-chevron-right"></span>', array('controller' => 'histories', 'action' => 'department_guage', date('Y-m-d', strtotime('+1 day', strtotime($newDate))), $this->request->params['pass'][1], $this->request->params['pass'][2]), array('class' => 'lgauge btn btn-sm btn-warning input-group-addon', 'style' => 'height:30px;color:#fff; background-color:#f0ad4e !important;', 'escape' => false)); ?>
                </div>
	    </div>
    <div class="pull-right">
        <?php echo $this->Html->image('indicator.gif', array('id' => 'deptGauge-busyIndic', 'class' => 'pull-left', 'style' => 'margin-top: 10px; display:none;')); ?>
        </div>
	</div>
</div>
<div class="panel-body">
<script type="text/javascript">
    $(document).ready(function() {
        $('.lgauge').on('click', function() {
            var url = $(this).attr("href");
                $("#deptGauge-busyIndic").show();
            $('#department_guage').load(url);
            return false;
            });
            <?php if (!empty($this->request->params['pass'][0])) { ?>
                $("#departments_guages_date").val('<?php echo $newDate; ?>');
            <?php } else { ?>
                    var date = new Date(), day = date.getDate(), month = date.getMonth() + 1, year = date.getFullYear();
                    if (day < 10)
                        day = '0' + day;
                    var dateToday = year + '-' + month + '-' + (day-1);
                    $("#departments_guages_date").val(dateToday);
            <?php } ?>

            $("#departments_guages_date").datetimepicker({
            closeOnDateSelect : true,
            scrollMonth : false,
            scrollInput : false,
            format: 'Y-m-d',
            timepicker: false,
            maxDate: 0,
                onChangeDateTime: function (dp, userInput) {
                    $("#deptGauge-busyIndic").show();
                    $('#department_guage').load('<?php echo Router::url('/', true); ?><?php echo 'histories/department_guage/'; ?>' + userInput.val());
                    $("#departments_guages_date").datepicker('hide');
                }
        });
    });
</script>
<?php
 if(!isset($data)){?>
	<div class=""><div class="panel-body"><h4 class="text-danger">No data found</h4><p>Navigate to earlier dates to check historic data</p></div></div>
<?php }else{ ?>

<?php
    foreach($data as $branches => $counts){
        foreach($counts as $department => $count){
            $benchmark[$department]['benchmark'] = $count['benchmark'] + $benchmark[$department]['benchmark'];
            $benchmark[$department]['count'] = $count['daily_total'] + $benchmark[$department]['count'];
        }
        
    }
?>
<?php foreach($benchmark as $department => $count){ ?> 
    <div class="row">
        <div class="col-md-2"><?php echo $department; ?></div>
        <div class="col-md-10">
            <div class="progress">
                <div class="progress-bar progress-bar-success" aria-valuenow="<?php echo round($count['count']); ?>" style="width: <?php echo round($count['count']); ?>%"><?php echo round($count['count']); ?></div>
                <div class="progress-bar progress-bar-warning progress-bar-striped" style="width: <?php echo round($count['benchmark'] - $count['count']); ?>%"><?php echo round($count['benchmark']); ?></div>          
            </div>
        </div>
    </div>    
<?php } ?>

</div>
<?php }?>
</div>
