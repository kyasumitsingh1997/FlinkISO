<style type="text/css">
/*.chart-container{overflow: auto; }*/
/*.content-wrapper{width: 10000px !important; overflow: visible}*/
.orgchart{background-image: none !important}
.orgchart .node .title{height: auto !important}
.pagination {margin-bottom: 10px !important;}
.h4class{text-align: center;}
.orgchart .node{width: auto !important; }
.orgchart table{width: 100% !important; }
.chart-container{float: left;}
.chart-container .content{min-height: 0px !important; float: left;}
.orgchartcontainer{
	width: 100%;
}

/*.orgchart .top-level .title {
  background-color: #006699 !important;
}
.orgchart .top-level .content {
  border-color: #006699 !important ;
}
.orgchart .middle-level .title {
  background-color: #009933 !important ;
}
.orgchart .middle-level .content {
  border-color: #009933 !important ;
}
.orgchart .bottom-level .title {
  background-color: #993366 !important ;
}
.orgchart .bottom-level .content {
  border-color: #993366 !important ;
}*/
</style>
<?php
	echo $this->Html->css(array('jquery.orgchart'));
	echo $this->fetch('css');
	echo $this->Html->script(array('jquery.orgchart'));
	echo $this->fetch('script');
?>

<h4 class="h4class">Organizational Chart</h4>
<div class="orgchartcontainer" style="overflow:auto">	
	<center>
<?php 
$i = 0;
$w = count($employees_orgchart);
foreach ($employees_orgchart as $orgchart) { 
	$t = $t + count($orgchart);
}

foreach ($employees_orgchart as $orgchart) { 
		$p = (100 * (count($orgchart)) / $t);
		// echo $p;	
	?>
    <div id="chart-container-<?php echo $i;?>" class="chart-container" style="overflow:auto;"></div>
        <script type="text/javascript">
        (function($){
            $(function() {
                var datascource = <?php echo json_encode($orgchart)?>;
                    $('#chart-container-<?php echo $i;?>').orgchart({
                        'data' : datascource,
                        'toggleSiblingsResp': false,
                        'nodeContent': 'title',
                        'nodeID': 'id',
                        'verticalDepth': 3,                          
                    });

                });
        })(jQuery);
        </script>    
<?php $i++; } ?>
</center>
</div>
</div>