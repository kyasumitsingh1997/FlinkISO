 <div id="productionWeeklyPlans_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="productionWeeklyPlans form col-md-8">
	<h4><?php echo __('Planned');?></h4>
                <div class="table-responsive">                        
                        <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
                            <tr>
                                <th><?php echo __('Start Date'); ?></th>
                                <th><?php echo __('End Date'); ?></th>
                                <th><?php echo __('Production Planned'); ?></th>
                                <th><?php echo __('Prepared By'); ?></th>
                                <th><?php echo __('Approved By'); ?></th>
                                <th><?php echo __('Publish'); ?></th>
                            </tr>
                    <?php if($productionWeeklyPlans){ ?>
                        <?php foreach ($productionWeeklyPlans as $productionWeeklyPlan): ?>
                            <tr>
                                <td><?php echo h($productionWeeklyPlan['ProductionWeeklyPlan']['start_date']); ?>&nbsp;</td>
                                <td><?php echo h($productionWeeklyPlan['ProductionWeeklyPlan']['end_date']); ?>&nbsp;</td>
                                <td><?php echo h($productionWeeklyPlan['ProductionWeeklyPlan']['production_planned']); ?>&nbsp;</td>
                                <td><?php echo h($PublishedEmployeeList[$productionWeeklyPlan['ProductionWeeklyPlan']['prepared_by']]); ?>&nbsp;</td>
                                <td><?php echo h($PublishedEmployeeList[$productionWeeklyPlan['ProductionWeeklyPlan']['approved_by']]); ?>&nbsp;</td>
                                <td width="60">
                                    <?php if($productionWeeklyPlan['ProductionWeeklyPlan']['publish'] == 1) { ?>
                                    <span class="fa fa-check"></span>
                                    <?php } else { ?>
                                    <span class="fa fa-ban"></span>
                                    <?php } ?>&nbsp;
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php }else{ ?>
                        <tr><td colspan=60>No results found</td></tr>
                    <?php } ?>
                    </table>
                    </div>			
<h4><?php echo __('Approve Production Weekly Plan'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
		<?php echo $this->Form->create('ProductionWeeklyPlan',array('role'=>'form','class'=>'form')); ?>
		<div class="col-md-12" id="weekplanhistory"></div>
		<div class="row">
			<?php
                echo "<div class='col-md-6'>".$this->Form->input('dates',array()) . '</div>'; 
                echo "<div class='col-md-6'>".$this->Form->input('product_id',array('default'=>$this->request->params['named']['product_id'])) . '</div>'; 
                echo "<div class='col-md-6'>".$this->Form->input('production_planned',array()) . '</div>';
                echo "<div class='col-md-6'>".$this->Form->input('current_status',array('legend'=>'Plan Status','options'=>$currentStatus, 'type'=>'radio',  'default'=>0)) . '</div>';
                // echo "<div class='col-md-6'>".$this->Form->input('current_status',array('legend'=>'Plan Status','options'=>$currentStatus, 'type'=>'radio',  'default'=>0))."</div>";
            ?>
		<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
		echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>
	<div class="col-md-12">
                <h2><?php echo __('Material Availablity');?></h2>
                <small class="pull-right">Balance = Available Quantity - Minimum Stock - Required</small>
                <table class="table table-bordered table-responsive table-condensed">
                    <tr>
                        <th><?php echo __('Material');?></th>
                        <th><?php echo __('Available Quantity');?></th>
                        <th><?php echo __('Minimum Stock');?></th>
                        <th><?php echo __('Unit');?></th>
                        <th><?php echo __('Scale');?></th>
                        <th><?php echo __('Required');?></th>
                        <th><?php echo __('Balance');?></th>
                        <th><?php echo __('Action');?></th>
                    </tr>
                <?php $i = 0; foreach ($stocks as $stock) { ?>
                     <tr>
                        <td><?php echo $stock['stock']['material']['name'];?></td>
                        <td id="stock_<?php echo $i?>"><?php echo $stock['stock']['stock'];?></td>
                        <td id="min_stock_<?php echo $i?>"><?php echo $stock['stock']['material']['min_stock'];?></td>
                        <td><?php echo $units[$stock['stock']['material']['unit_id']];?></td>
                        <td id="qty_<?php echo $i?>"><?php echo $stock['ProductMaterial']['quantity'];?></td>
                        <td id="req_<?php echo $i?>">
                            <?php echo $this->request->data['ProductionWeeklyPlan']['production_planned'] * $stock['ProductMaterial']['quantity'];?>
                        </td>
                        <td id="bal_<?php echo $i?>">
                        	<?php echo $stock['stock']['stock'] - $stock['stock']['material']['min_stock'] - ($this->request->data['ProductionWeeklyPlan']['production_planned'] * $stock['ProductMaterial']['quantity'])?>
                        </td>
                        <td><?php echo $this->Html->link('Place Order',array('controller'=>'purchase_orders','action'=>'lists','material_id'=>$stock['material']['id'],'type'=>'1'),array('class'=>'btn btn-xs btn-danger'));?></td>
                    </tr>
                    <script type="text/javascript">
	                    $("#ProductionWeeklyPlanProductionPlanned").keyup(function(){
	                        var stock = $("#stock_<?php echo $i?>").html();
	                        var min_stock = $("#min_stock_<?php echo $i?>").html();
	                        var qty = $("#qty_<?php echo $i?>").html();
	                        var pro = $("#ProductionWeeklyPlanProductionPlanned").val();
	                        var req = 0;
	                        var bal = 0;
	                        req = parseFloat(qty) * parseFloat(pro);
	                        $("#req_<?php echo $i?>").html(parseFloat(req));

	                        bal = parseFloat(stock) - parseFloat(min_stock) - req;
	                        $("#bal_<?php echo $i?>").html(parseFloat(bal));
	                        if(bal < 0){
	                            $("#bal_<?php echo $i?>").addClass('text-danger');
	                        }else{
	                            $("#bal_<?php echo $i?>").addClass('text-success');
	                        }

	                    });
                </script>   
                    <?php  $i ++ ;} ?>
                </table>                
            </div>  
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
<script> 
    $("#ProductionWeeklyPlanDates").daterangepicker({
    "autoApply": true,
     "showWeekNumbers": true,
     "startDate" : '<?php echo date("yyyy-MM-dd",strtotime($this->data["ProductionWeeklyPlan"]["start_date"]));?>',
     "endDate" : '<?php echo date("yyyy-MM-dd",strtotime($this->data["ProductionWeeklyPlan"]["end_date"]));?>',
    format: 'MM/DD/YYYY',
        // minDate : '<?php echo $start_date;?>',
        // maxDate : '<?php echo $end_date;?>',
        locale: {
            // format: 'MM/DD/YYYY'
        },
        autoclose:true,
    }); 
</script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#productionWeeklyPlans_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/approve/<?php echo $this->request->params['pass'][0] ?>/<?php echo $this->request->params['pass'][1] ?>",
                type: 'POST',
                target: '#productionWeeklyPlans_ajax',
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
		$().ready(function() {
    $("#submit-indicator").hide();
        $('#ProductionWeeklyPlanApproveForm').validate();        
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
