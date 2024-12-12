<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="projectResources_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav">
	<div class="projectResources form col-md-8">
		<?php
		$qucipro = $this->requestAction('projects/projectdates/'.$this->request->params['named']['project_id']);
		echo $this->element('projectdates',array('qucipro'=>$qucipro));
		?>			
		<h4>Add Project Resource</h4>
		<?php echo $this->Form->create('ProjectResource',array('role'=>'form','class'=>'form','default'=>false)); ?>
		<div class="row">  
			<?php echo $this->Form->hidden('project_id',array('default'=>$this->request->params['named']['project_id'])); ?>
		    <?php echo $this->Form->hidden('milestone_id',array('default'=>$this->request->params['named']['milestone_id'])); ?>
		    <?php $x = 1; ?>
		    <div class="col-md-12"><br /><legend>Project Resource Cost</legend></div>
		    <!-- <div id="purchaseOrderDetails_ajax"> -->
		        <div id="purchaseOrderDetails_ajax_<?php echo $x;?>">  
		        	
		            <?php echo "<div class='col-md-3'>".$this->Form->input('ProjectResource.'.$x.'.user_id',array('options'=>$PublishedUserList)) . '</div>'; ?>
		            <?php echo "<div class='col-md-3'>".$this->Form->input('ProjectResource.'.$x.'.mandays',array('default'=>0, 'onchange'=>'cale(this.value,'.$x.')')) . '</div>'; ?>
		            <?php echo "<div class='col-md-3'>".$this->Form->input('ProjectResource.'.$x.'.resource_cost',array('default'=>0,'onchange'=>'cale(this.value,'.$x.')','label'=>'Resource cost/Manday')) . '</div>'; ?>    
		            <?php echo "<div class='col-md-2 subt'>".$this->Form->input('ProjectResource.'.$x.'.resource_sub_total',array('label'=>'Sub Total', 'default'=>0)) . '</div>'; ?>
		        </div>
		    <!-- </div> -->
		    <?php echo $this->Form->button('+', array('label' => false, 'type' => 'button', 'div' => false, 'class' => 'btn btn-md btn-info pull-right', 'style'=>'margin-top:24px', 'onclick' => 'addAgendaDiv('.$x.')')); ?>
		    <div class="clearfix">&nbsp;</div>
		</div>
		<?php $x++;?>
		<?php echo $this->Form->input('agendaNumber', array( 'value' => $x)); ?>
			
			<?php
			    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
			    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
			    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
			?>
		
		<div class="">
<?php

		if ($showApprovals && $showApprovals['show_panel'] == true) {
			echo $this->element('approval_form');
		} else {
			echo $this->Form->input('publish', array('label' => __('Publish')));
		}?>
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#projectResources_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
		</div>
	</div>
<script> 
	$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat:'yy-mm-dd',
    }); 
</script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<script>
    $.validator.setDefaults({
    	ignore: null,
    	errorPlacement: function(error, element) {
            if ($(element).attr('name') == 'data[ProjectResource][user_id]' ||
				$(element).attr('name') == 'data[ProjectResource][project_id]' ||
				$(element).attr('name') == 'data[ProjectResource][milestone_id]'
			){	
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#loadhear',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                },
                error: function(request, status, error) {                    
                    alert('Action failed!');
                }
	    			});
        }
    });

    function addAgendaDiv(x) {
	    var i = parseInt($('#ProjectResourceAgendaNumber').val());
	    // console.log(i);
	    // $('#ProjectAgendaNumber').val();
	    $.get("<?php echo Router::url('/', true); ?>milestones/add_resource/" + i , function(data) {
	        $('#purchaseOrderDetails_ajax_' + x).append(data);
	    });
	    i = i + 1;
	    $('#ProjectResourceAgendaNumber').val(i);
	}
	function removeAgendaDiv(key,x) {
	    var r = confirm("Are you sure to remove this order details?");
	    if (r == true)
	    {
	        $('#purchaseOrderDetails_ajax'+ key + '_' + x).remove();
	        calc();
	    }
	}


	function calc(){
	    var subtotal = 0;
	    $(".subt .form-control").each(function(){
	        var i = this.id;
	        // var x = $("#"+i).val();
	        var x = this.value;
	        // console.log(i + "---" + x);
	        if(x > 0)subtotal = parseInt(subtotal) + parseInt(x);
	    });
	    // subtotal = parseInt(subtotal) + parseInt(subtotal);
	    $("#ProjectEstimatedProjectCost").val(subtotal);
	    $("#pcost").html(subtotal);


	    var subtotalm = 0;
	    $(".subt .form-control").each(function(){
	        var i = this.id;
	        // var x = $("#"+i).val();
	        var x = this.value;
	        // console.log(i + "---" + x);
	        if(x > 0)subtotalm = parseInt(subtotalm) + parseInt(x);
	    });
	    // subtotal = parseInt(subtotal) + parseInt(subtotal);
	    $("#MilestoneEstimatedCost").val(subtotalm);
	    $("#sub-m-<?php echo $key;?>").html(subtotalm);
	}

	function cale(val , i){        
	    $("#ProjectResource"+i+"ResourceSubTotal").val(parseInt($("#ProjectResource"+i+"Mandays").val()) * parseInt($("#ProjectResource"+i+"ResourceCost").val()));
	    calc();
	}

	$().ready(function() {

		$("#ProjectResourceMandays").on('change',function(){
    		$("#ProjectResourceResourceSubTotal").val(parseInt($("#ProjectResourceMandays").val()) * parseInt($("#ProjectResourceResourceCost").val()))
    	});

    	$("#ProjectResourceResourceCost").on('change',function(){
    		$("#ProjectResourceResourceSubTotal").val(parseInt($("#ProjectResourceMandays").val()) * parseInt($("#ProjectResourceResourceCost").val()))
    	});

    	$("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        
        $('#ProjectResourceAddAjaxForm').validate({
            rules: {
				"data[ProjectResource][user_id]": {
					greaterThanZero: true,
				},
				"data[ProjectResource][project_id]": {
					greaterThanZero: true,
				},
				"data[ProjectResource][milestone_id]": {
					greaterThanZero: true,
				},
                
            }
        }); 

				$('#ProjectResourceUserId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#ProjectResourceProjectId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#ProjectResourceMilestoneId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});       
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
