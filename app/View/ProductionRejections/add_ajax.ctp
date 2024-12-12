<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min','ckeditor/ckeditor')); ?>
<?php echo $this->fetch('script'); ?>
<div id="productionRejections_ajax">
<?php echo $this->Session->flash();?>	<div class="nav">
		<div class="productionRejections form col-md-8">
			<h4>Add Production Rejection</h4>
			<?php echo $this->Form->create('ProductionRejection',array('role'=>'form','class'=>'form','default'=>false)); ?>
			<div class="row">
					<div class="col-md-12">
						<h3><?php echo __('Rejection History');?></h3>
						<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
						<tr class="danger">
							<th><?php echo __('Inspection Template'); ?></th>
							<!-- <th><?php echo __('Performance Indicator'); ?></th>
							<th><?php echo __('Value Driver'); ?></th> -->
							<th><?php echo __('Defect Type'); ?></th>
							<th><?php echo __('Sample Quantity'); ?></th>
							<th><?php echo __('Quality Check Date'); ?></th>
							<th><?php echo __('Start Sr Number'); ?></th>
							<th><?php echo __('End Sr Number'); ?></th>
							<th><?php echo __('Number Of Rejections'); ?></th>
							<th><?php echo __('Employee'); ?></th>
							<th><?php echo __('Publish'); ?></th>

						
						</tr>
						<?php if($newProductionRejections){ 
							$total = 0;
							?>
						<?php foreach ($newProductionRejections as $newProductionRejection): ?>
							<tr>
								<td><?php echo $newProductionRejection['ProductionRejection']['name']; ?></td>
								<!-- <td><?php echo h($newProductionRejection['PerformanceIndicator']['name']); ?>&nbsp;</td>
								<td><?php echo h($newProductionRejection['ValueDriver']['name']); ?>&nbsp;</td> -->
								<td><?php echo h($newProductionRejection['DefectType']['name']); ?>&nbsp;</td>
								<td><?php echo h($newProductionRejection['ProductionRejection']['sample_quantity']); ?>&nbsp;</td>
								<td><?php echo h($newProductionRejection['ProductionRejection']['quality_check_date']); ?>&nbsp;</td>
								<td><?php echo h($newProductionRejection['ProductionRejection']['start_sr_number']); ?>&nbsp;</td>
								<td><?php echo h($newProductionRejection['ProductionRejection']['end_sr_number']); ?>&nbsp;</td>
								<td><?php echo h($newProductionRejection['RejectionDetail']['number_of_rejections']); ?>&nbsp;</td>
								<?php 
									$total = $newProductionRejection['RejectionDetail']['number_of_rejections'] + $total;
								?>
								<td>
									<?php echo $PublishedEmployeeList[$newProductionRejection['ProductionRejection']['employee_id']]; ?>
								</td>
								<td width="60">
									<?php if($newProductionRejection['RejectionDetail']['publish'] == 1) { ?>
									<span class="fa fa-check"></span>
									<?php } else { ?>
									<span class="fa fa-ban"></span>
									<?php } ?>&nbsp;</td>
							</tr>
						<?php endforeach; ?>
							<tr>
								<th colspan="5">&nbsp;</th>
								<th colspan="3" class="text-danger"><h3><?php echo __('Total Rejections');?></h3></th>
								<th colspan="2" class="text-danger"><h3><?php echo $total;?></h3></th>
							</tr>
						<?php }else{ ?>
							<tr><td colspan=90>No results found</td></tr>
						<?php } ?>
					</table>
				</div>
				</div>
			<div class="row">
			<fieldset>
					<?php
					echo "<div class='col-md-12'>".$this->Form->input('production_inspection_template_id',array()) . '</div>'; 
					echo "<div class='col-md-12'>".$this->Form->input('name',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('production_id',array('label'=>'Production/Batch#', 'default'=>$this->request->params['named']['production_id'])) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('product_id',array('default'=>$this->request->params['named']['product_id'])) . '</div>'; 
					
					echo "<div class='col-md-12'>".$this->Form->input('inspection_report',array()) . '</div>'; 
					?>
				</div>
				<div class="row">
					<div class="col-md-12">
						<h3><?php echo __('Rejection History');?></h3>
						<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
						<tr class="danger">
							<th><?php echo __('Inspection Template'); ?></th>
							<!-- <th><?php echo __('Performance Indicator'); ?></th>
							<th><?php echo __('Value Driver'); ?></th> -->
							<th><?php echo __('Defect Type'); ?></th>
							<th><?php echo __('Sample Quantity'); ?></th>
							<th><?php echo __('Quality Check Date'); ?></th>
							<th><?php echo __('Start Sr Number'); ?></th>
							<th><?php echo __('End Sr Number'); ?></th>
							<th><?php echo __('Number Of Rejections'); ?></th>
							<th><?php echo __('Employee'); ?></th>
							<th><?php echo __('Publish'); ?></th>		

						
						</tr>
						<?php if($newProductionRejections){ 
							$total = 0;
							?>
						<?php foreach ($newProductionRejections as $newProductionRejection): ?>
							<tr>
								<td><?php echo $newProductionRejection['ProductionRejection']['name']; ?></td>
								<!-- <td><?php echo h($newProductionRejection['PerformanceIndicator']['name']); ?>&nbsp;</td>
								<td><?php echo h($newProductionRejection['ValueDriver']['name']); ?>&nbsp;</td> -->
								<td><?php echo h($newProductionRejection['DefectType']['name']); ?>&nbsp;</td>
								<td><?php echo h($newProductionRejection['ProductionRejection']['sample_quantity']); ?>&nbsp;</td>
								<td><?php echo h($newProductionRejection['ProductionRejection']['quality_check_date']); ?>&nbsp;</td>
								<td><?php echo h($newProductionRejection['ProductionRejection']['start_sr_number']); ?>&nbsp;</td>
								<td><?php echo h($newProductionRejection['ProductionRejection']['end_sr_number']); ?>&nbsp;</td>
								<td><?php echo h($newProductionRejection['RejectionDetail']['number_of_rejections']); ?>&nbsp;</td>
								<?php 
									$total = $newProductionRejection['RejectionDetail']['number_of_rejections'] + $total;
								?>
								<td>
									<?php echo $PublishedEmployeeList[$newProductionRejection['ProductionRejection']['employee_id']]; ?>
								</td>
								<td width="60">
									<?php if($newProductionRejection['RejectionDetail']['publish'] == 1) { ?>
									<span class="fa fa-check"></span>
									<?php } else { ?>
									<span class="fa fa-ban"></span>
									<?php } ?>&nbsp;</td>
							</tr>
						<?php endforeach; ?>
							<tr>
								<th colspan="5">&nbsp;</th>
								<th colspan="3" class="text-danger"><h3><?php echo __('Total Rejections');?></h3></th>
								<th colspan="2" class="text-danger"><h3><?php echo $total;?></h3></th>
							</tr>
						<?php }else{ ?>
							<tr><td colspan=90>No results found</td></tr>
						<?php } ?>
					</table>
				</div>
				</div>
				<div class="row">
					<?php
					$i = 0;
					echo "<div class='col-md-4'>".$this->Form->input('quality_check_date',array()) . '</div>'; 
					echo "<div class='col-md-4'>".$this->Form->input('total_quantity',array('default'=>$actual_production_number)) . '</div>'; 
					echo "<div class='col-md-4'>".$this->Form->input('sample_quantity',array()) . '</div>'; 
					echo "<div class='col-md-4'>".$this->Form->input('start_sr_number',array()) . '</div>'; 
					echo "<div class='col-md-4'>".$this->Form->input('end_sr_number',array()) . '</div>'; 
					echo "<div class='col-md-4'>".$this->Form->input('employee_id',array()) . '</div>'; 
					echo $this->Form->input('production_weekly_plan_id',array('type'=>'hidden', 'default'=>$this->request->params['named']['production_weekly_plan_id'])); 
					?>
				<div id="rejectiondetails_ajax">
                	<div id="rejectiondetails_ajax<?php echo $i; ?>">
					<?php
					echo "<div class='col-md-8'>".$this->Form->input('RejectionDetail.'.$i.'.defect_type_id',array()) . '</div>'; 
					
					// echo "<div class='col-md-3'>".$this->Form->input('RejectionDetail.'.$i.'.performance_indicator_id',array()) . '</div>'; 
					echo "<div class='col-md-3'>".$this->Form->input('RejectionDetail.'.$i.'.number_of_rejections',array()) . '</div>'; 
					// echo "<div class='col-md-2'><br /><span class='text-danger glyphicon glyphicon-remove danger pull-right disabled' style='font-size:20px;background:none' type='button' ></span></div>"; 
					$i++;
				?>
					</div>
				</div>

				<?php 	
					// echo "<div class='col-md-3'>".$this->Form->input('supplier_registration_id',array()) . '</div>'; 
					// echo "<div class='col-md-3'>".$this->Form->input('customer_contact_id',array()) . '</div>'; 
	?>
			</div>
			<div>
			<div class="col-md-12"><br /><?php echo $this->Form->input('agendaNumber', array('type' => 'hidden', 'value' => $i)); ?></div>
            <?php echo $this->Form->button('Add Rejections', array('label' => false, 'type' => 'button', 'div' => false, 'class' => 'btn btn-md btn-info pull-right', 'onclick' => 'addAgendaDiv()')); ?>
            <div class="clearfix">&nbsp;</div>
			</fieldset>
			<?php
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
		}?>		
			<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#productionRejections_ajax','async' => 'false')); ?>
		<?php echo $this->Form->end(); ?>
		<?php echo $this->Js->writeBuffer();?>
		</div>
	</div>
<script> 
	$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      startDate: '<?php echo $startDate;?>',      
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
            if (
                
								$(element).attr('name') == 'data[ProductionRejection][production_id]' ||
								$(element).attr('name') == 'data[ProductionRejection][product_id]' ||
								$(element).attr('name') == 'data[ProductionRejection][production_inspection_template_id]' ||
								$(element).attr('name') == 'data[ProductionRejection][employee_id]' ||
								$(element).attr('name') == 'data[ProductionRejection][supplier_registration_id]' ||
								$(element).attr('name') == 'data[ProductionRejection][customer_contact_id]')
						{	
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function(form) {
        	CKEDITOR.instances['ProductionRejectionInspectionReport'].updateElement();
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#main',
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

		$("#ProductionRejectionNumberOfRejections").on('change',function(){
			var reject = $("#ProductionRejectionNumberOfRejections").val();
			var available = $("#ProductionRejectionTotalQuantity").val();

			if(Number(reject) > Number(available)){
				alert('Rejected Quality can not be more that total quantity.');
				$("#ProductionRejectionNumberOfRejections").val('0').focus();
				return false;
			}
		});

		$("#ProductionRejectionSampleQuantity").on('change',function(){
			var reject = $("#ProductionRejectionSampleQuantity").val();
			var available = $("#ProductionRejectionTotalQuantity").val();

			if(Number(reject) > Number(available)){
				alert('Sample Quality can not be more that total quantity.');
				$("#ProductionRejectionSampleQuantity").val('0').focus();
				return false;
			}
		});

		$("#ProductionRejectionProductionInspectionTemplateId").on('change',function(){
			$.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_template/id:" + $("#ProductionRejectionProductionInspectionTemplateId").val()  , function(data) {
				CKEDITOR.instances['ProductionRejectionInspectionReport'].setData(data);				
			});
		});

    	$("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        
        $('#ProductionRejectionAddAjaxForm').validate({
            rules: {
									"data[ProductionRejection][production_id]": {
                    	greaterThanZero: true,
									},
									"data[ProductionRejection][product_id]": {
                    	greaterThanZero: true,
									},
									"data[ProductionRejection][production_inspection_template_id]": {
                    	greaterThanZero: true,
									},
									"data[ProductionRejection][employee_id]": {
                    	greaterThanZero: true,
									},
									"data[ProductionRejection][supplier_registration_id]": {
                    	greaterThanZero: true,
									},
									"data[ProductionRejection][customer_contact_id]": {
                    	greaterThanZero: true,
									},
                
            }
        }); 

				$('#ProductionRejectionProductionId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#ProductionRejectionProductId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#ProductionRejectionProductionInspectionTemplateId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#ProductionRejectionEmployeeId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#ProductionRejectionSupplierRegistrationId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#ProductionRejectionCustomerContactId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});       
    });

	function addAgendaDiv(args) {		
        var i = parseInt($('#ProductionRejectionAgendaNumber').val());
        $('#ProductionRejectionAgendaNumber').val();
        $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_rejection_details/" + i, function(data) {
            $('#rejectiondetails_ajax').append(data);
        });
        i = i + 1;
        $('#ProductionRejectionAgendaNumber').val(i);
    }
    function removeAgendaDiv(i) {
    	var r = confirm("Are you sure to remove this rejection details?");
        if (r == true)
        {
            $('#rejectiondetails_ajax' + i).remove();
        }
    }
</script>
<script type="text/javascript">
    CKEDITOR.replace('ProductionRejectionInspectionReport', {
        filebrowserBrowseUrl: '<?php echo Router::url("/", true); ?>img/ckeditor/browse.php?dir=<?php echo WWW_ROOT ?>img/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>&path=<?php echo Router::url("/", true); ?>img/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>',
        filebrowserUploadUrl: '<?php echo Router::url("/", true); ?><?php echo $this->request->params["controller"] ?>/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>',
        toolbar: [
            { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'CopyFormatting' ] },
            { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
            { name: 'insert', items: [ 'Image', 'Table','HorizontalRule','PageBreak'] },
            { name: 'tools', items: ['Radio','Checkbox','TextField','Textarea','Selection', '-', 'Maximize','Source' ] },
            '/',
            { name: 'styles', items: [ 'Format', 'Font', 'FontSize', 'lineheight'] },
            { name: 'links', items: [ 'Link', 'Unlink' ] },
            { name: 'editing', items: [ 'Scayt' ] },
            {name: 'document', items: ['Preview', '-', 'Templates']},
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            
        ],
        customConfig: '',
        disallowedContent: 'img{width,height,float}',
        extraAllowedContent: 'img[width,height,align]',
        extraPlugins: 'tableresize,lineheight,autosave,imagerotate,pastefromexcel,htmlbuttons,forms,fakeobjects',
        height: 800,
        // contentsCss: [ '<?php echo Router::url("/", true); ?>css/contents.css', '<?php echo Router::url("/", true); ?>css/mystyles.css' ],
        bodyClass: 'document-editor',
        format_tags: 'p;h1;h2;h3;pre',
        removeDialogTabs: 'image:advanced;link:advanced',
        enterMode:2,forceEnterMode:false,shiftEnterMode:1,
        stylesSet: [
            /* Inline Styles */
            { name: 'Marker', element: 'span', attributes: { 'class': 'marker' } },
            { name: 'Cited Work', element: 'cite' },
            { name: 'Inline Quotation', element: 'q' },
            /* Object Styles */
            {
                name: 'Special Container',
                element: 'div',
                styles: {
                    padding: '5px 10px',
                    background: '#eee',
                    border: '1px solid #ccc'
                }
            },
            {
                name: 'Compact table',
                element: 'table',
                attributes: {
                    cellpadding: '5',
                    cellspacing: '0',
                    border: '1',
                    bordercolor: '#ccc'
                },
                styles: {
                    'border-collapse': 'collapse'
                }
            },
            { name: 'Borderless Table', element: 'table', styles: { 'border-style': 'hidden', 'background-color': '#E6E6FA' } },
            { name: 'Square Bulleted List', element: 'ul', styles: { 'list-style-type': 'square' } }
        ]
    });


</script>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
