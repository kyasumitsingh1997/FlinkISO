<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <div class="employees ">
        <?php echo $this->element('nav-header-lists', array('postData' => array('pluralHumanName' => 'Employees', 'modelClass' => 'Employee', 'options' => array("sr_no" => "Sr No", "name" => "Name", "employee_number" => "Employee Number", "qualification" => "Qualification", "joining_date" => "Joining Date", "date_of_birth" => "Date Of Birth", "pancard_number" => "Pancard Number", "personal_telephone" => "Personal Telephone", "office_telephone" => "Office Telephone", "mobile" => "Mobile", "personal_email" => "Personal Email", "office_email" => "Office Email", "residence_address" => "Residence Address", "permenant_address" => "Permanent Address", "maritial_status" => "Marital Status", "driving_license" => "Driving License"), 'pluralVar' => 'employees'))); ?>


<style type="text/css">
/*.chart-container{overflow: auto; }*/
/*.content-wrapper{width: 10000px !important; overflow: visible}*/
#orgc{background-color: #fff;}
.orgchart{background-image: none !important}
.orgchart .node .title{height: auto !important}
.pagination {margin-bottom: 10px !important;}
.h4class{text-align: center;}
.orgchart .node{width: auto !important; }
.orgchart table{width: 100% !important; }
.chart-container{float: left;}
.chart-container .content{min-height: 0px !important; float: left;}
.orgchartcontainer{width: 100%;}
#print-this{display: block; overflow: auto}

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
<h4 class="h4class">Organizational Chart <br />
<small>
    <div class="text-center btn-group">
      <?php 
        if(isset($this->request->params['named']['render']) && $this->request->params['named']['render'] == 'v'){
          $vclass = ' btn-success';
        }else{
          $vclass = ' btn-default';
        }

        if(isset($this->request->params['named']['render']) && $this->request->params['named']['render'] == 'h'){
          $hclass = ' btn-success';
        }else{
          $hclass = ' btn-default';
        }
      ?>
    <?php // echo $this->Html->link('Export PDF','#',array('class'=>'btn btn-success','onClick'=>'getpdf();','escape'=>false));?>
    <?php echo $this->Html->link('Verticle',array('action'=>'org_chart','render'=>'v'),array('class'=>'btn' . $vclass));?>
    <?php echo $this->Html->link('Horizontal',array('action'=>'org_chart','render'=>'h'),array('class'=>'btn'. $hclass));?>
  </div>
</small>
</h4>
<div class="orgchartcontainer" style="overflow:auto" id="orgc">

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
                        <?php                           
                            if(isset($this->request->params['named']['render']) && $this->request->params['named']['render'] == 'v'){ ?>
                              'verticalDepth': 3,
                            <?php }else{ ?>
                              
                          <?php } 
                        ?>                        
                          'createNode': function($node, data) {
                            var secondMenuIcon = $('<i>', {
                              'class': 'fa fa-info-circle second-menu-icon',
                              click: function() {
                                $(this).siblings('.second-menu').toggle();
                              }
                            });
                            var secondMenu = '<div class="second-menu"><img class="avatar" src="<?php echo Router::url('/', true) . "img" . DS . $this->Session->read("User.company_id") . DS ?>avatar/'+data.id+'/avatar.png"></div>';
                            $node.append(secondMenuIcon).append(secondMenu);
                          }
                    })
                });
        })(jQuery);
        </script>    
<?php $i++; } ?>
</center>
</div>
</div>
<div id="print-this" style="2480">
  <img id="screenshot-img" width="100%"/>
</div>
<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search', array('postData' => array("name" => "Name", "employee_number" => "Employee Number", "qualification" => "Qualification", "joining_date" => "Joining Date", "date_of_birth" => "Date Of Birth", "pancard_number" => "Pancard Number", "personal_telephone" => "Personal Telephone", "office_telephone" => "Office Telephone", "mobile" => "Mobile", "personal_email" => "Personal Email", "office_email" => "Office Email", "residence_address" => "Residence Address", "permenant_address" => "Permanent Address", "maritial_status" => "Marital Status", "driving_license" => "Driving License"), 'PublishedBranchList' => array($PublishedBranchList))); ?>
<?php echo $this->element('import', array('postData' => array("sr_no" => "Sr No", "name" => "Name", "employee_number" => "Employee Number", "qualification" => "Qualification", "joining_date" => "Joining Date", "date_of_birth" => "Date Of Birth", "pancard_number" => "Pancard Number", "personal_telephone" => "Personal Telephone", "office_telephone" => "Office Telephone", "mobile" => "Mobile", "personal_email" => "Personal Email", "office_email" => "Office Email", "residence_address" => "Residence Address", "permenant_address" => "Permanent Address", "maritial_status" => "Marital Status", "driving_license" => "Driving License"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
<?php echo $this->Js->writeBuffer(); ?>

<script>
    function takeHighResScreenshot(srcEl, destIMG, scaleFactor) {
        // Save original size of element
        var originalWidth = srcEl.offsetWidth;
        var originalHeight = srcEl.offsetHeight;
        // Force px size (no %, EMs, etc)
        srcEl.style.width = originalWidth + "px";
        srcEl.style.height = originalHeight + "px";

        // Position the element at the top left of the document because of bugs in html2canvas. The bug exists when supplying a custom canvas, and offsets the rendering on the custom canvas based on the offset of the source element on the page; thus the source element MUST be at 0, 0.
        // See html2canvas issues #790, #820, #893, #922
        srcEl.style.position = "absolute";
        srcEl.style.top = "0";
        srcEl.style.left = "0";

        // Create scaled canvas
        var scaledCanvas = document.createElement("canvas");
        scaledCanvas.width = originalWidth * scaleFactor;
        scaledCanvas.height = originalHeight * scaleFactor;
        scaledCanvas.style.width = originalWidth + "px";
        scaledCanvas.style.height = originalHeight + "px";
        var scaledContext = scaledCanvas.getContext("2d");
        scaledContext.scale(scaleFactor, scaleFactor);

        html2canvas(srcEl, { canvas: scaledCanvas })
        .then(function(canvas) {
            destIMG.src = canvas.toDataURL("image/png");
            srcEl.style.display = "none";
        });
    }
    
    // function getpdf(){
    //   var src = document.getElementById("orgc");
    //   var img = document.getElementById("screenshot-img");
    //   canvas = takeHighResScreenshot(src, img, 2);
    //   // print_png(); 
     
    // }

    function getpdf(){
      // alert('hey');
      // var blob = new Blob(["Hello, world!"], {type: "text/plain;charset=utf-8"});
      // saveAs(blob, "hello world.txt");
      
      var src = document.getElementById("orgc");
      var img = document.getElementById("screenshot-img");
      canvas = takeHighResScreenshot(src, img, 4);
      $("#screenshot-img").load(function(){
        $("#print-this").width = $("#screenshot-img").width();
        $("#print-this").height = $("#screenshot-img").height();
         
         html2canvas($("#print-this"), {
          width : $("#screenshot-img").width(),
          height : $("#screenshot-img").height(),
          background : '#fff',
          onrendered: function(canvas) {
                theCanvas = canvas;
                // document.body.appendChild(canvas);

                canvas.toBlob(function(blob) {
                saveAs(blob, "org_chart.png"); 
              });
            }
        });
      });
      
    }

    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
</div>
