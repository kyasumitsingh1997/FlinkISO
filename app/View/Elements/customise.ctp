<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<style>
.sidebar-form{border:0px !important; margin: 0px !important; padding: 0px !important}
.sidebar-form textarea{background-color:#374850;border:0px !important;}
.sidebar-form textarea:focus{background-color:#fff;color: #666}
#customize_side_bar_form{display: block !important; }
</style>
<script>
    $().ready(function() {
    $("#submit-indicator-customize").hide();
    $("[name*='customize_form']").submit(function(){
      
          $("[name*='customize_form']").ajaxSubmit({
                url: "<?php echo Router::url('/', true) . $this->request->params['controller'] .'/send_customise'; ?>",
                type: 'POST',
                target: '#cust',
                beforeSend: function(){
                    
                    $("#submit_id").prop("disabled",true);
                    $("#submit-indicator-customize").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator-customize").hide();
                },
                error: function(request, status, error) {
                    //alert(request.responseText);
                    alert('Action failed!');
                }
	    });
    });
    });
</script>
<?php echo __('Send us your feedback'); ?>
    <?php
        echo $this->Form->create('customize', array('role' => 'form', 'class' => 'sidebar-form', 'id'=>'customize_side_bar_form', 'default' => false,'name'=>'customize_form')); 

        echo $this->Form->input('customization_title',array('label'=>false,'placeholder'=>'Add Suggestion Title here','required'=>'required'));
        echo $this->Form->hidden('company',array('value'=>$companyDetails['Company']['name']));
        echo $this->Form->hidden('branch_name',array('value'=>$this->Session->read('User.branch')));
        echo $this->Form->hidden('employee',array('value'=>$this->Session->read('User.username')));
        echo $this->Form->hidden('request_for',array('value'=>$this->request->url));
        echo $this->Form->input('customization_details',array('type'=>'textarea','label'=>false,'placeholder'=>'Add details here','required'=>'required'));
        echo $this->Form->submit('Submit', array('div' => false, 'class' => 'btn btn-success', 'style'=>'margin-top:10px', 'update' => '#cust', 'async' => 'false','id'=>'submit_id'));
        echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator-customize'));
        echo $this->Form->end();
    ?>
  <?php echo $this->Js->writeBuffer(); ?>  
  <h4><?php echo __("Need Help") ?> ?</h4>
        <p>FlinkISO is equipped with On-Page help, a unique Do-It-Yourself guide for users on how to use this application without having to rely on technical team. All the required help for the respective page is available in the section below. This help is specially designed to display only the help which you may need when you are on that particular page.</p>
                <h4 class="text-danger"><?php echo __('Having a problem?'); ?></h4>
                <p><?php echo __('We can help you! Just send your query / issue to the following email address.'); ?><br />
                <h5><a href="mailto:help@flinkiso.com" class="text-info">help@flinkiso.com</a></h5>
            