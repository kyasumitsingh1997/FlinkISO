<style>
  .ct-chart{height: 360px;}
  
  .chartist-tooltip {
    position: relative;
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
.ct-chart .ct-label {display: block !important; word-wrap:break-word !important; }
.ct-chart .ct-series.ct-series-b .ct-line{stroke:#66d215 !important;}
.ct-chart .ct-series.ct-series-a .ct-line{stroke:#d74702 !important;}
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

    if($this->request->data['PerformanceChart']['chart_type'] == 0){$bar_type = 'Bar';$tooltip_type = '.ct-bar';}
    elseif($this->request->data['PerformanceChart']['chart_type'] == 1){$bar_type = 'Line';$tooltip_type = '.ct-point';}
    else {$bar_type = 'Bar';$tooltip_type = '.ct-bar';}
?>
<div class="row">
    <div class="col-md-12">
        <?php if(isset($results))foreach ($results as $key => $value): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <?php echo strtolower(preg_replace('/[^A-Za-z0-9\-]/','', $key)) ?>
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    // echo json_encode($value['label']);
                    //     $data = null;
                    //     $data = "[['Day','Stock In Hand','Stock Sent for Production'],";
                    //     foreach ($value as $val):
                    //         $data .= "['" . date('d-m-y', strtotime($val['date'])) . "'," . $val['quantity'] . "," . $val['quantity_consumed'] . "],";
                    //     endforeach;
                    //     $data .= "]";
                    ?>
                    <script >
                    new Chartist.Line("#chart_div_<?php echo strtolower(preg_replace('/[^A-Za-z0-9\-]/','', $key)) ?>", {
                      labels: <?php echo json_encode($value['label']);?>,
                      series: [
                        <?php echo json_encode($value['series1']);?>,
                        <?php echo json_encode($value['series2']);?>                        
                      ]
                    }, {
                      fullWidth: true,
                      chartPadding: {                        
                        bottom: 40,                        
                      },
                      plugins: [
                          Chartist.plugins.ctAxisTitle({
                            axisX: {
                              axisTitle: 'Day',
                              axisClass: 'ct-axis-title',
                              // onlyInteger: true,
                              offset: {
                                x: 0,
                                y: 50
                              },
                              textAnchor: 'middle'
                            },
                            axisY: {
                              axisTitle: 'Qty',
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
                    <div id="chart_div_<?php echo strtolower(preg_replace('/[^A-Za-z0-9\-]/','', $key)) ?>" class="ct-chart"></div>
                    <div class="col-md-12"><br /><h4 class="text-center">Summery</h4>
                        <table class="table table-responsive table-bordered">
                            <tr>
                                <th>Date</th>
                                <?php foreach($value['label'] as $k=>$val){
                                    echo "<th>".$val."</th>";
                                } ?>                                
                            </tr>
                            <tr>
                                <th>Used</th>
                                <?php foreach($value['series1'] as $k=>$val){
                                    echo "<td>".$val."</td>";
                                } ?>
                            </tr>
                            <tr>
                                <th>In-Hand</th>
                                <?php foreach($value['series2'] as $k=>$val){
                                    echo "<td>".$val."</td>";
                                } ?>
                            </tr>
                        </table>
                    </div>
                 </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
