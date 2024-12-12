<?php  if(count($selected_branches) > 0) { 
if(isset($sel_users))$sel_users = json_decode($sel_users);
    ?> 
    <div class="row">
        <div id="distribute" class="col-md-12">                        

            <?php // echo $this->Form->create('Share', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
            <ul>
                <?php foreach ($branches as $key => $value) {                     
                    if($key){?>
                    <li><a href="#<?php echo $key;?>"><?php echo $value['Name']; ?></a></li>
                <?php } }?>          
            </ul>
        <?php 
        $i = 0;
        foreach ($branches as $key => $value) { 
            if($key){?>
            <div id="<?php echo $key?>" >
                <fieldset>                          
                    <?php 
                        echo "<div class='col-md-12'>".$this->Form->input('MasterListOfFormat.user_id.'.$i.'.Everyone',array(
                            'label'=>'<h4 class="no-margin">Everyone <small>Open file, any user can acess the file in <strong>'.$value['Name'].'</strong> branch</small></h4>', 
                            'type'=>'checkbox',
                            'id' => 'MasterListOfFormat_'. $key.'-'.$i.'-Everyone',
                            'options'=>array('all'=>0))) . '</div>';                                
                        echo 
                            "<div class='col-md-12' 
                            id='".$key."_".$i."_check_".$key."'>".$this->Form->input('MasterListOfFormat.user_id.'.$i.'.user_id',array(
                            'label'=>'<h4>Or Strict Access <small>Only selected users will get access to the file</small></h4>', 
                            'options'=>$value['Users'],
                            'multiple'=>'checkbox',
                            'type'=>'select',
                            'default'=>$sel_users)) . '</div>'; 
                        
                        // echo $this->Form->hidden('FileUpload.'.$i.'.branch_id',array('value'=>$key));
                        
                        // echo $this->Form->hidden('FileUpload.'.$i.'.file_upload_id',array('value'=>$this->request->params['pass'][0]));
                    ?>
                </fieldset>
            </div>
            <script type="text/javascript">
                $('#MasterListOfFormat_<?php echo $key; ?>-<?php echo $i; ?>-Everyone').on('click', function(){
                    $("#<?php echo $key ?>_<?php echo $i ?>_check_<?php echo $key; ?>").find(':checkbox').prop('checked', this.checked);                           
                });
            </script>
            <?php                   
            $i++;
            } 
        }
                // echo $this->Js->submit('Apply Permissions',array(
                //     'before'=>$this->Js->get('#sending_'. $this->request->params['pass'][0])->effect('fadeIn'),
                //     'success'=>$this->Js->get('#sending_'. $this->request->params['pass'][0])->effect('fadeOut'),
                //     'update'=>'#share_model_alert_'.$this->request->params['pass'][0],
                //     'class'=>'btn btn-sm btn-info'
                //      ));
                // echo $this->Form->end();
            ?>

    </div>

<?php } ?>
<?php
    
        echo $this->Html->script(array('plugins/jQuery/jQuery-2.2.0.min','plugins/jQueryUI/jquery-ui.min'));
        echo $this->fetch('script');
?>
<script type="text/javascript">
    jQuery.noConflict();
    jQuery().ready(function(){
        jQuery( "#distribute" ).tabs();
    });
</script>

