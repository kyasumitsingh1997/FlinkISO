<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<style type="text/css">
/*.chosen-container-single .chosen-single, .element.style {width: 100% !important};*/
.fieldsetboder{border: 1px solid #ccc !important; padding: 10px; float: left; display: block;}
</style>

<div id="autoApprovals_ajax">
   <?php echo $this->Session->flash();?><div class="nav">
      <div class="autoApprovals form col-md-8">
         <h4>Add Auto Approval</h4>
         <?php echo $this->Form->create('AutoApproval',array('role'=>'form','class'=>'form','default'=>false)); ?>
         <div class="row">
            <fieldset>
			   <?php
		          echo "<div class='col-md-6'>".$this->Form->input('system_table',array()) . '</div>'; 	
		          echo "<div class='col-md-6'>".$this->Form->input('name',array()) . '</div>'; 
		          echo "<div class='col-md-12'>".$this->Form->input('details',array()) . '</div>'; 
	           ?>
            </fieldset>
         </div>
         <div class="hide">
            <h2>Add Details</h2>
         <?php
            $i = 0;?>
            <div id="mainPanel_ajax" class="hide">
                <div id="mainPanel_ajax<?php echo $i; ?>">
                    <div>
                        <div class="panel panel-default">
                            <div class="panel-heading"><?php echo __('Details'); ?>
                              <span class="text-danger glyphicon glyphicon-remove danger pull-right" style="font-size:20px;background:none"type="button" onclick='removeAgendaDiv(<?php echo $i; ?>)'>
                              </span>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                 <?php 
// echo "<div class='col-md-12'><h4> Step " . $i ."</h4></div>";
echo "<div class='col-md-6'>".$this->Form->input($key . '.AutoApprovalStep.'.$i.'.department_id',array('options'=>$PublishedDepartmentList)) . '</div>'; 
echo "<div class='col-md-6'>".$this->Form->input($key . '.AutoApprovalStep.'.$i.'.branch_id',array('options'=>$PublishedBranchList)) . '</div>'; 
echo "<div class='col-md-2'>". $this->Form->input($key . '.AutoApprovalStep.'.$i.'.step_number',array('value'=>$i,'label'=>'Step No.')) . '</div>'; 
echo "<div class='col-md-5'>".$this->Form->input($key . '.AutoApprovalStep.'.$i.'.name',array('value'=>'Step '. $i)) . '</div>'; 
echo "<div class='col-md-5'>".$this->Form->input($key . '.AutoApprovalStep.'.$i.'.user_id',array('style'=>'width:100%','class'=>'chosen-select','label'=>'User to send','options'=>$fwd_users)) . '</div>';
echo "<div class='col-md-12'>".$this->Form->input($key . '.AutoApprovalStep.'.$i.'.details',array('label'=>'Any other details/notes  <small>(Optional)</small>')) . '</div>'; 
echo "<div class='col-md-7'>".$this->Form->input($key . '.AutoApprovalStep.'.$i.'.allow_approval',array('label'=>'Allow approval at this stage and skip rest of the stages?')) . '</div>';
echo "<div class='col-md-5'>".$this->Form->input($key . '.AutoApprovalStep.'.$i.'.show_details',array('label'=>'Share details/notes with the user?')) . '</div>';
                                    

                                 ?>
                                                               </div>
                                                            </div>                        
                                                      </div>
                                                   </div>
                                                </div>
                                                
                                          <?php
                                              echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                                              echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
                                              echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
                                          ?>       
                                       </div>

                                       <div class="row">
                                          <?php $i++;?>
                                          
                                       </div>
         </div>
         <?php
           echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
           echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); echo $this->Form->input('state_id', array('type' => 'hidden', 'value' => $this->Session->read('User.state_id')));
           echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
         ?>
      <div class="row">
         <div class="hide"><?php echo $this->Form->input('publish', array('style'=>'display:none', 'checked', 'label' => __('Publish'))); ?></div>
         <?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#autoApprovals_ajax','async' => 'false')); ?>
         <?php echo $this->Form->end(); ?>
         <?php echo $this->Js->writeBuffer();?>
      </div>
   </div>
   <div class="col-md-4">
   	<p><?php echo $this->element('helps'); ?></p>
   </div>
</div>
<script>
  $(function() {
    $(".chosen-container").width('100%');
    $(".chosen-container-single").width('100%');
   
  });

    $.validator.setDefaults({
        submitHandler: function(form) {
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
    $("#submit-indicator").hide();
        $('#AutoApprovalAddAjaxForm').validate();        
   });

   function addAgendaDiv(args) {
        var i = parseInt($('#AutoApprovalAgendaNumber').val());
        $('#AutoApprovalAgendaNumber').val();
        $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_details/" + i +"/page:add_ajax", function(data) {
            $('#mainPanel_ajax').append(data);
        });
        i = i + 1;
        $('#AutoApprovalAgendaNumber').val(i);
    }
    function removeAgendaDiv(i) {
        var r = confirm("Are you sure to remove this order details?");
        if (r == true)
        {
            $('#mainPanel_ajax' + i).remove();
        }        
    }

</script>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});
</script>
