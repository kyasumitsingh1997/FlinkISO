<?php 
	echo $this->Html->script(array(
		'plugins/chartjs/Chart-2.min',
		// 'timeknots-master/src/d3.v2.min',
  //   	'timeknots-master/src/timeknots-min',
  //   	'Lightweight-jQuery-Timeline-Plugin-jqtimeline/js/jquery.jqtimeline',
  //   	'PapaParse-5.0.2/papaparse.min',
  //   	'bootstrap-editable.min'
    )); 
    echo $this->fetch('script'); 

  //   echo $this->Html->css(array(
		// 'Lightweight-jQuery-Timeline-Plugin-jqtimeline/css/jquery.jqtimeline',
		// 'bootstrap-editable'
  //   )); 
  //   echo $this->fetch('css'); 

    Configure::write('debug',1);
    debug($files);

   ?>

   <div class="row">
       <div class="col-md-12 draggable">
        <ul class="list-group">
          <li class="list-group-item"><h4 class="text-center">Resource cost</h4></li>
            <li class="list-group-item"><canvas width="800" height="190" id="resourcecost<?php echo $milestone['Milestone']['id']?>"></canvas></li>
          </ul>

          
          <script>
            var resourcecostconfig = {
              type: 'bar',
              data: {
                datasets: [{
                  data: <?php echo json_encode($resourceGraph['mandaysdata'],JSON_NUMERIC_CHECK);?>,
                  backgroundColor:<?php echo json_encode($resourceGraph['colors1']);?>,
                  label: 'Dataset 1'
                }],             
                labels: <?php echo json_encode($resourceGraph['labels']);?>
              },
              options: {
                responsive: true,
                legend: {
                  display: false,
                  // fullWidth : true,
                  // display: true,
                  // position: 'right',
                  // labels: {
                  //   // fontColor: 'rgb(255, 99, 132)'
                  // }
                },
                scales: {
                  yAxes: [{
                    ticks: {
                      min: 0,                        
                    }
                  }],
                }
              }
            };

            // $().ready(function(){
              var resourcecostctx = document.getElementById('resourcecost<?php echo $milestone['Milestone']['id']?>').getContext('2d');
              window.resourcecostgraph = new Chart(resourcecostctx, resourcecostconfig);
            // });
            // window.onload = function() {

              
            // };

            
          </script>
      </div>
   </div>