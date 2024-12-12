<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min','ckeditor/ckeditor')); ?>
<?php echo $this->fetch('script'); ?>
<script>
	$.validator.setDefaults({
		ignore: null,
		errorPlacement: function(error, element) {
	        if(  $(element).attr('name') == 'data[MaterialQualityCheckDetail][employee_id]'){
	             $(element).next().after(error);
	        }else{
	               $(element).after(error);
	        }
	    },
	});
	$().ready(function() {
		$("#MaterialQualityCheckDetailCheckPerformedDate").datepicker();
        $("#submit_id").click(function(){
        	CKEDITOR.instances['MaterialQualityCheckDetailQcTemplate'].updateElement();

        	if(parseInt($('#MaterialQualityCheckDetailQuantityReceived').val()) < parseInt($('#MaterialQualityCheckDetailQuantityAccepted').val())){
				alert('Incorrect Quality Accepted');
				return false;
			}
            if($('#MaterialQualityCheckDetailEditForm').valid()){
                $("#submit_id").prop("disabled",true);
                $("#submit-indicator").show();
                $('#MaterialQualityCheckDetailEditForm').submit();
            }
          });
		jQuery.validator.addMethod("greaterThanZero", function(value, element) {
			return this.optional(element) || (parseFloat(value.length) == 36);
		}, "Please select the value");

        $('#MaterialQualityCheckDetailAddQualityCheckForm').validate({
            rules: {
                "data[MaterialQualityCheckDetail][employee_id]" : {
                   greaterThanZero:true,
                 },
                "data[MaterialQualityCheckDetail][quantity_accepted]": {
                    required: true,
                    number:true,
                    // max: parseInt($('#MaterialQualityCheckDetailQuantityReceived').val())
                }
            }

        });
        $('#MaterialQualityCheckDetailEmployeeId').change(function () {
            if( $( this ).val()!=-1 && $(this).next().next('label').hasClass("error")){
                $(this).next().next('label').remove();
            }
        });
	});
</script>
<div id="materialQualityCheckDetails_ajax">
	<?php echo $this->Session->flash();?>	
	<div class="nav panel panel-default">
		<div class="materialQualityCheckDetails form col-md-12">
			<?php echo $this->Form->create('MaterialQualityCheckDetail',array('role'=>'form','class'=>'form')); ?>
			<?php echo $this->Form->input('id', array('type'=>'hidden','value'=>$materialQualityCheckDetail['MaterialQualityCheckDetail']['id']));?>
        	<?php echo $this->Form->input('delivery_challan_id',array('type'=>'hidden','value'=>$deliveryChallan['DeliveryChallan']['id'])); ?>
        	<?php echo $this->Form->input('material_quality_check_id',array('type'=>'hidden','value'=>$materialQualityChecks['MaterialQualityCheck']['id'])); ?>
        	<?php echo $this->Form->input('material_id',array('type'=>'hidden','value'=>$materialQualityChecks['Material']['id'])); ?>
            <?php echo $this->Form->input('purchase_order_id',array('type'=>'hidden','value'=>$deliveryChallan['PurchaseOrder']['id'])); ?>
                <?php echo $this->Form->input('purchase_order_details_id',array('type'=>'hidden','value'=>$deliveryChallan['PurchaseOrder']['purchase_order_details_id'])); ?>
        	<div class="row">
				<div class="col-md-12"><h4><label><?php echo $materialQualityChecks['MaterialQualityCheck']['name']; ?></label></h4></div>
			</div>
			<hr />
			<div class="row">
				<div class="col-md-2"><strong>Details</strong></div>
				<div class="col-md-10"><?php echo ':&nbsp;&nbsp;' . $materialQualityChecks['MaterialQualityCheck']['details']; ?></div>
			</div>
            <!-- <div class="row">
                    <div class="col-md-2"><strong>QC Template</strong></div>
                    <div class="col-md-10"><?php echo ':&nbsp;&nbsp;' . $materialQualityChecks['MaterialQualityCheck']['qc_template']; ?></div>
                </div>     -->
        	<div class="row">
				<div class="col-md-2"><strong>Material</strong></div>
				<div class="col-md-10"><?php echo ':&nbsp;&nbsp;' . $materialQualityChecks['Material']['name']; ?></div>
			</div>
			<div class="row">
				<div class="col-md-2"><strong>Delivery Challan</strong></div>
				<div class="col-md-10"><?php echo ':&nbsp;&nbsp;' . $deliveryChallan['DeliveryChallan']['name']; ?></div>
			</div>
			<div class="row">&nbsp;</div>
			<div class="row">
				<div class="col-md-6"><?php echo $this->Form->input('employee_id', array('value'=>$materialQualityCheckDetail['MaterialQualityCheckDetail']['employee_id'],'options'=>$PublishedEmployeeList)); ?></div>
				<div class="col-md-6"><?php echo $this->Form->input('check_performed_date', array('value'=>$materialQualityCheckDetail['MaterialQualityCheckDetail']['check_performed_date'])); ?></div>
			</div>
			<div class="row">
				<div class="col-md-6"><?php echo $this->Form->input('quantity_received', array('value'=> $qtyRecd, 'readonly'=>'readonly')); ?></div>
				<div class="col-md-6"><?php echo $this->Form->input('quantity_accepted', array('value'=>$materialQualityCheckDetail['MaterialQualityCheckDetail']['quantity_accepted'])); ?></div>
			</div>
			<div class="row">
				<div class="col-md-12"><?php echo $this->Form->input('qc_report', array('type'=>'textarea', 'value'=>$materialQualityCheckDetail['MaterialQualityCheckDetail']['qc_report'])); ?><br /></div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?php echo $this->Form->hidden('delivery_challan_detail_id', array('value'=>$deliveryChallanDetailId)); ?>
                    <h3><?php echo __('Update Quality Check Template Below');?></h3>
                    <?php
                    // Configure::write('debug',1) ;
                    // debug($materialQualityCheckDetail);
                        if($this->request->data['MaterialQualityCheckDetail']['qc_template']){
                        	$temp = $this->request->data['MaterialQualityCheckDetail']['qc_template'];
                        }else{
                        	$temp = $materialQualityChecks['MaterialQualityCheck']['qc_template'];
                        }
                    ?>
                    <textarea id="MaterialQualityCheckDetailQcTemplate" name="data[MaterialQualityCheckDetail][qc_template]"><?php echo $temp ?></textarea>     
                    </div>   
			</div>
			<div class="row">
				<div class="col-md-6"><?php echo $this->Form->input('branchid',array('type'=>'hidden','value'=>$this->Session->read('User.branch_id'))); ?></div>
				<div class="col-md-6"><?php echo $this->Form->input('departmentid',array('type'=>'hidden','value'=>$this->Session->read('User.department_id'))); ?></div>
			</div>
        	<?php if($show_approvals && $show_approvals['show_panel'] == true ) { ?>
        	<?php echo $this->element('approval_form'); ?>
        	<?php } else {echo $this->Form->input('publish'); } ?>
            
            <?php if($active_status == 'disabled'){?>
            	<script>
            	   $('div *').prop('disabled',true);
            	</script>
            	<div class="alert alert-danger">You can not save this step until all previous quality check steps are done.</div>
            <?php } ?>
			<?php 
				echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
				echo $this->Form->submit(__('Submit'),array('id'=>'submit_id', 'div'=>false,'class'=>'btn btn-primary btn-success')); ?>
			<?php echo $this->Form->end(); ?>
			<?php echo $this->Js->writeBuffer();?>
		</fieldset>
	</div>
	
		<script>
    $(function() {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });
</script>
	
</div>
<?php echo $this->Js->writeBuffer();?>
</div>

<script type="text/javascript">
    CKEDITOR.replace('MaterialQualityCheckDetailQcTemplate', {
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
