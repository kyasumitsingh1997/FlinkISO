 <div id="projects_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="projects form col-md-8">
<h4><?php echo __('Approve Project'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('Project',array('role'=>'form','class'=>'form')); ?>
<div class="row">
<fieldset>
			<?php
		echo "<div class='col-md-6'>".$this->Form->input('title',array()) . '</div>'; 
                echo "<div class='col-md-6'>".$this->Form->input('customer_id',array()) . '</div>'; 
                // echo "<div class='col-md-12'>".$this->Form->input('users',array('label'=>'Project Team', 'name'=>'Project[users][]', 'options'=>$PublishedUserList, 'multiple')) . '</div>'; 
        		echo "<div class='col-md-12'>".$this->Form->input('goal',array()) . '</div>'; 
        		echo "<div class='col-md-12'>".$this->Form->input('scope',array()) . '</div>'; 
        		echo "<div class='col-md-12'>".$this->Form->input('success_criteria',array()) . '</div>'; 
        		echo "<div class='col-md-12'>".$this->Form->input('challenges',array()) . '</div>'; 
            	
                echo "<div class='col-md-4'>".$this->Form->input('employee_id',array('style'=>'','label'=>'Project Leader')) . '</div>';         
                echo "<div class='col-md-4'>".$this->Form->input('start_date',array('label'=>'Start Date - End Date')) . '</div>'; 
                // echo "<div class='col-md-6'>".$this->Form->input('end_date') . '</div>'; 
                echo "<div class='col-md-4'>".$this->Form->input('current_status',array()) . '</div>'; 
	?>
</fieldset>
<fieldset>
	<?php $i = 0;$j = 0;?>
    <div class="col-md-12"><br /><legend>Project Resource Cost</legend></div>
    <div id="purchaseOrderDetails_ajax">
    	<?php foreach ($this->request->data['ProjectResource'] as $projectResource) { ?>
    		<div id="purchaseOrderDetails_ajax<?php echo $i; ?>">
    				<?php echo $this->Form->input('ProjectResource.'.$j.'.id',array('default'=>$projectResource['id'])) ?>
                    <?php echo "<div class='col-md-3'>".$this->Form->input('ProjectResource.'.$j.'.user_id',array('options'=>$PublishedUserList,'default'=>$projectResource['user_id'])) . '</div>'; ?>
                    <?php echo "<div class='col-md-3'>".$this->Form->input('ProjectResource.'.$j.'.mandays',array('default'=>0, 'onchange'=>'cale(this.value,'.$j.')')) . '</div>'; ?>
                    <?php echo "<div class='col-md-3'>".$this->Form->input('ProjectResource.'.$j.'.resource_cost',array('default'=>0,'onchange'=>'cale(this.value,'.$j.')','label'=>'Resource cost/Manday')) . '</div>'; ?>    
                    <?php echo "<div class='col-md-2 subt'>".$this->Form->input('ProjectResource.'.$j.'.resource_sub_total',array('default'=>0)) . '</div>'; ?>                       
                    <?php echo "<div class='col-md-1'><br /><span class='btn btn-danger type='button' onclick='removeAgendaDiv(".$i.")'>-</span></div>"; ?>
                </div>
                <?php $j++;?>
		<?php } ?>
	</div>
	<?php $i++;?>
	<div class="col-md-12"><?php echo $this->Form->input('agendaNumber', array('type' => 'hidden', 'value' => $i)); ?></div>
    <?php echo $this->Form->button('+', array('label' => false, 'type' => 'button', 'div' => false, 'class' => 'btn btn-md btn-info pull-right', 'onclick' => 'addAgendaDiv()')); ?>
    <div class="clearfix">&nbsp;</div>
</fieldset>
<fieldset>
	<div class="col-md-12">
		<legend>Cost Estimation</legend>
		    <table class="table table-bordered table-responsive">
		        <tr>
		            <th>Cost Category</th>
		            <th>Estimated Cost</th>
		            <th>Description</th>            
		        </tr>
		        <?php $c = 0; foreach ($this->request->data['ProjectEstimate'] as $pe) {  ?>
		            <tr>
		                <td><?php echo "<strong>".$costCategories[$pe['cost_category_id']]."</strong>"; 		                
		                echo $this->Form->hidden('ProjectEstimate.'.$c.'.cost_category_id',array( 'label'=>false, 'default'=>$pe['cost_category_id']))
		                ?></td>                
		                <td><div class="subt"><?php echo $this->Form->input('ProjectEstimate.'.$c.'.cost',array('default'=>$pe['cost'],'label'=>false))?></div></td>
		                <td><?php echo $this->Form->input('ProjectEstimate.'.$c.'.details',array('default'=>$pe['details'], 'rows'=>1,'label'=>false))?></td>
		            </tr>
		        <?php $c++; } ?>

		        <?php 
		        foreach ($this->request->data['ProjectEstimate'] as $pe) {  
		        	foreach ($costCategories as $ckey => $cvalue) {
		        		if($ckey == $pe['cost_category_id'])unset($costCategories[$pe['cost_category_id']]);
		        	}
		        } 
		        unset($this->request->data['ProjectEstimate']);
		        ?>

		        <?php foreach ($costCategories as $ckey => $cvalue) {  ?>
		            <tr>
		                <td><?php echo "<strong>".$cvalue."</strong>"; 		                
		                echo $this->Form->hidden('ProjectEstimate.'.$c.'.cost_category_id',array( 'label'=>false, 'default'=>$ckey))
		                ?></td>                
		                <td><div class="subt"><?php echo $this->Form->input('ProjectEstimate.'.$c.'.cost',array('default'=>0,'label'=>false))?></div></td>
		                <td><?php echo $this->Form->input('ProjectEstimate.'.$c.'.details',array( 'rows'=>1,'label'=>false))?></td>
		            </tr>
		        <?php $c++; } ?>
		    </table>
	</div>

</fieldset>	

<div class="col-md-6 hide"><?php echo $this->Form->input('agendaNumber', array( 'value' => $i)); ?></div>
<!-- <div class="col-md-6 rcost subt"><?php echo $this->Form->input('estimated_resource_cost', array('default'=>0)); ?></div> -->

<!-- <div class="col-md-4 subt"><?php echo $this->Form->input('estimated_infra_cost',array('default'=>0))?></div>
<div class="col-md-4 subt"><?php echo $this->Form->input('estimated_material_cost',array('default'=>0))?></div>
<div class="col-md-4 subt"><?php echo $this->Form->input('estimated_equipment_cost',array('default'=>0))?></div>
<div class="col-md-4 subt"><?php echo $this->Form->input('estimated_services_cost',array('default'=>0))?></div>
<div class="col-md-4 subt"><?php echo $this->Form->input('estimated_software_cost',array('default'=>0))?></div>
<div class="col-md-4 subt"><?php echo $this->Form->input('estimated_hardware_cost',array('default'=>0))?></div>
<div class="col-md-4 subt"><?php echo $this->Form->input('estimated_other_cost',array('default'=>0))?></div> -->
<!-- <div class="col-md-8"><?php echo $this->Form->input('estimated_project_cost',array('default'=>0))?></div> -->
<div class="col-md-12"><h2>Estimated Project Cost : <span id="pcost"><?php echo $this->request->data['Project']['estimated_project_cost']?></span></h2></div>
<div class="col-md-12"><?php echo $this->Form->hidden('estimated_project_cost',array())?></div>
<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
		echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>

</div>
<div class="">
		

<?php

		
	if ($showApprovals && $showApprovals['show_panel'] == true) {
		
		echo $this->element('approval_form');
		
	} else {
		
		echo $this->Form->input('publish', array('label' => __('Publish')));
		
	}
?>
<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')); ?>
<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
<?php echo $this->Form->end(); ?>

<?php echo $this->Js->writeBuffer();?>
</div>
</div>
<script> $("#ProjectStartDate").daterangepicker({
        format: 'MM/DD/YYYY',
        startDate: '<?php echo date("yyyy-MM-dd",strtotime($this->data["Project"]["start_date"]))?>',
    	// endDate: '<?php echo date("yyyy-MM-dd",strtotime($this->data["Project"]["end_date"]))?>',
        locale: {
            format: 'MM/DD/YYYY'
        },
    // startDate: 'd',
    autoclose:true,
}); </script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#projects_ajax')));?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>

	function calc(){
        var subtotal = 0;
        $(".subt .form-control").each(function(){
            var i = this.id;
            var x = $("#"+i).val();
            console.log(i + "---" + x);
            if(x > 0)subtotal = parseInt(subtotal) + parseInt(x);
        });
        // subtotal = parseInt(subtotal) + parseInt(subtotal);
        $("#ProjectEstimatedProjectCost").val(subtotal);
        $("#pcost").html(subtotal);
    }

    function cale(val , i){        
        $("#ProjectResource"+i+"ResourceSubTotal").val(parseInt($("#ProjectResource"+i+"Mandays").val()) * parseInt($("#ProjectResource"+i+"ResourceCost").val()));
        calc();
    }

	    function addAgendaDiv(args) {
	        var i = parseInt($('#ProjectAgendaNumber').val());
	        $('#ProjectAgendaNumber').val();
	        $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_resource/" + i, function(data) {
	            $('#purchaseOrderDetails_ajax').append(data);
	        });
	        i = i + 1;
	        $('#ProjectAgendaNumber').val(i);
	    }
	    function removeAgendaDiv(i) {
	        var r = confirm("Are you sure to remove this order details?");
	        if (r == true)
	        {
	            $('#purchaseOrderDetails_ajax' + i).remove();
	            calc();
	        }
	    }

    $.validator.setDefaults();
    $().ready(function() {

    	$(".subt .form-control").each(function(){
            var i = this.id;
            $("#"+i).on('change',function(){
                calc();
            })
        });
    	

    	$('#ProjectStartDate').change(function(){
    		// $('#ProjectEndDate').val($('#ProjectStartDate').val());
    	});
        $('#ProjectApproveForm').validate();
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#ProjectApproveForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#ProjectApproveForm').submit();
            }

        });
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
