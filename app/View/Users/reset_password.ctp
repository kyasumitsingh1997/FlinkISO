<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<script>

    $().ready(function() {
        $('#UserSaveUserPasswordForm').validate({
            rules: {
                "data[User][password]": {
                    required: true,
                    passwordPolicy: true,
                },
                   'data[User][temppassword]': {
                    required: true,
                    equalTo: "#UserPassword",
           
                },
                
            }
        });

    });
</script>
<div  id="users_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav">
        
                <div class="nav panel">
                    <div class="col-md-12">      <h2><?php echo __('Reset your password'); ?></h2><hr/></div>
            <div class="col-md-5">
                   
                  <?php
                        echo $this->Form->create('User', array('controller' => 'user', 'action' => 'save_user_password', 'role' => 'form', 'class' => 'form form-signin pull-left', $token));
                        echo $this->Form->input('username', array('label' => __('Username'), 'div' => false, 'value' => $username, 'disabled' => true));
                        echo $this->Form->input('password', array('label' => __('New Password'), 'type' => 'password', 'div' => false, 'placeholder' => '*******'));
                        echo $this->Form->input('temppassword', array('label' => __('Confirm Password'), 'type' => 'password', 'div' => false, 'placeholder' => '*******'));
                        echo $this->Form->input('token', array('type' => 'hidden', 'div' => false, 'value' => $token));

                        echo $this->Form->submit(__('Reset'), array('div' => false, 'class' => 'btn btn-lg btn-primary btn-block','id'=>'submit_id'));
                        echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator'));
                        echo $this->Form->end();
                    ?>
                     <?php echo $this->Js->writeBuffer(); ?>
            </div>
                    <div class="col-md-7"><br/><br/>
                    <?php echo $this->element('display_policy', array(), array('plugin' => 'PasswordSettingManager'));
                    
               ?>
            </div>
        </div>
    </div>
</div>
<script>
    $("#submit-indicator").hide();
    $("#submit_id").click(function(){
         if($('#UserSaveUserPasswordForm').valid()){
             $("#submit_id").prop("disabled",true);
             $("#submit-indicator").show();
             $("#UserSaveUserPasswordForm").submit();
         }
    });
</script>