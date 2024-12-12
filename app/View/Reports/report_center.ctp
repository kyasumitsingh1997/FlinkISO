<script>
    $('document').ready(function() {
        $("#month").change(function() {
            var date_value = $(this).val().replace("-", "/");
            window.location = '<?php echo Router::url('/', true); ?>reports/report_center/' + date_value;
        });
        $('#month').chosen();
    });


</script>
<?php
$curr_month = isset($this->request->params['pass'][0]) && ($this->request->params['pass'][1]) ? $this->request->params['pass'][1].'-'.$this->request->params['pass'][0] : '';

if ($curr_month) {
    $curr_month = $curr_month;
} else {
    $curr_month = date('Y-m');
}
?>
<div class="main">
    <div class="row">
        <div class="col-md-8">
            <h1><?php echo __("FlinkISO Report Center") ?></h1>
        </div>
        <div class="col-md-4"><h3 class="text-success"><?php echo __("Ready reports for "); ?><?php echo date('M-Y', strtotime($curr_month)) ?></h3></div>
        <div class="col-md-8">
            <div class="alert alert-info">
                <?php echo __('These are ready reports generated automatically based on the inputs by flinkISO users. These reports are auto-generated daily, weekly, quarterly & yearly.'); ?>
            </div>
        </div>
        <div class="col-md-4">
            <?php
            $end_date = date('Y-m-1');
            $date = date("Y-m-d", strtotime("-11 month", strtotime($end_date)));
            while ($date < $end_date) {
                $options[date('Y', strtotime($end_date))][date('m-Y', strtotime($end_date))] = date('M-Y', strtotime($end_date));
                $end_date = date("Y-m-d", strtotime("-1 month", strtotime($end_date)));
            }
            echo $this->Form->input('month', array('id' => 'month', 'label' => __('Change Month'), 'options' => $options));
            ?>
        </div>
    </div>
    <div class="reports ">
            <div id="tabs"> 
                <ul>
                    <li><?php echo $this->Html->link(__('More'), array('action' => 'specials','monthly',$curr_month)); ?></li>
                    <li><?php echo $this->Html->link(__('Monthly'), array('action' => 'show_reports','monthly',$curr_month)); ?></li>
                    <li><?php echo $this->Html->link(__('Weekly'), array('action' => 'show_reports','weekly',$curr_month)); ?></li>
                    <li><?php echo $this->Html->link(__('Daily'), array('action' => 'show_reports','daily',$curr_month)); ?></li>
                    <li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator-report','class'=>'pull-right')); ?></li>
                </ul>
            </div>
        
    <div id="reports_tab_ajax"></div>
    <script>
  $(function() {
    $( "#tabs" ).tabs({
      beforeLoad: function( event, ui ) {
        ui.jqXHR.error(function() {
            ui.panel.html(
            "Error Loading ... " +
            "Please contact administrator." );
        });
      },
    });
  });
</script>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator-report").show();},complete:function(){$("#busy-indicator-report").hide();}});</script>
</div>
