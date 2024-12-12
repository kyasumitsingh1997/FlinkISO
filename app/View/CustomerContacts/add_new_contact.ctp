<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?><?php echo $this->fetch('script'); ?>
<style>
#contact .modal-dialog {
	width: 96% !important
}
.chosen-container, .chosen-container-single, .chosen-select {
	width: 100% !important;
	min-width:100px !important;
}
</style>
<div class="modal fade" id="contact">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php echo __('Add New Contact'); ?></h4>
      </div>
      <div class="modal-body">		
		
			<div id="customer_contact_ajax"> <?php echo $this->Session->flash(); ?>
				<div class="nav">
					<div class="customers form col-md-8">
					<?php echo $this->Form->create('Customer', array('role' => 'form', 'class' => 'form')); ?>
						<div class="row">
							<div class="col-md-6"><?php echo $this->Form->input('CustomerContact.name'); ?></div>
							<div class="col-md-6"><?php echo $this->Form->input('CustomerContact.email'); ?><label id="getEmail" class="error" ></label></div>
							<div class="col-md-6"><?php echo $this->Form->input('CustomerContact.phone'); ?></div>
							<div class="col-md-6"><?php echo $this->Form->input('CustomerContact.mobile'); ?></div>
							<div class="col-md-12"><?php echo $this->Form->input('CustomerContact.address'); ?></div>
						</div>
						<?php
                            if ($showApprovals && $showApprovals['show_panel'] == true) {
                                echo $this->element('approval_form');
                            } else {
                                echo $this->Form->input('CustomerContact.publish');
                            }
                        ?>
                                            <?php echo $this->Form->hidden('CustomerContact.customer_id',array('value'=>$this->request->params['pass'][0])); ?>
						
						<?php echo $this->Form->submit('Submit', array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#customer_contact_ajax', 'async' => 'false','id'=>'submit_id')); ?> <?php echo $this->Html->image('indicator.gif', array('id' => 'customer_contact_ajax_submit-indicator')); ?> <?php echo $this->Form->end(); ?> <?php echo $this->Js->writeBuffer(); ?> </div>
					
					<script>
    $.ajaxSetup({
        beforeSend: function () {
            $("#customer_contact_ajax_submit-indicator").show();
        },
        complete: function () {
            $("#customer_contact_ajax_submit-indicator").hide();
        }
    });
</script>
					<div class="col-md-4">
						<p><?php echo $this->element('helps'); ?></p>
					</div>
				</div>
			</div>		
			</div>
<script>
    $.validator.setDefaults({        
        errorPlacement: function (error, element) {
            
        },
        submitHandler: function (form) {
			$(form).ajaxSubmit({
                url: '<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax',
                type: 'POST',
                target: '#customer_contact_ajax',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#customer_contact_ajax_submit-indicator").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#customer_contact_ajax_submit-indicator").hide();
                },
            });
        }
    });

    $().ready(function () {
		$("#customer_contact_ajax_submit-indicator").hide();
        jQuery.validator.addMethod("notEqual", function (value, element, param) {
            return this.optional(element) || value != param;
        }, "Please select the value");

        $('#CustomerContactAddAjaxForm').validate({
            rules: {
                
            }
        })
        $('#CustomerContact.name').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CustomerContact.email').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    });
</script>      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>$('#contact').modal();</script>
