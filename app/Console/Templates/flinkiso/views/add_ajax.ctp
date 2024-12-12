<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<?php echo "<?php echo \$this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>\n";?>
<?php echo "<?php echo \$this->fetch('script'); ?>\n"; ?>
<div id="<?php echo $pluralVar; ?>_ajax">
<?php echo "<?php echo \$this->Session->flash();?>"; ?>
	<div class="nav">
		<div class="<?php echo $pluralVar; ?> form col-md-8">
			<h4><?php echo __('Add ' .$singularHumanName); ?></h4>
			<?php echo "<?php echo \$this->Form->create('{$modelClass}',array('role'=>'form','class'=>'form','default'=>false)); ?>\n"; ?>
			<div class="row">
			<fieldset>
				<?php
				echo "\t<?php\n";
				foreach ($fields as $field)
				{
					if (strpos($action, 'add') !== false && $field == $primaryKey || $field =='sr_no')
						{
						continue;
						} elseif (!in_array($field, array('created', 'modified', 'updated','created_by','modified_by','soft_delete','system_table_id','master_list_of_format_id','publish','record_status','status_user_id','branchid','departmentid','company_id','prepared_by','approved_by')))
						{
						
							if (strpos($field, '_id') !== false){
								$model_name_new = explode("_id",$field);
								 echo "\t\t\t\t\techo \"<div class='col-md-6'>\".\$this->Form->input('{$field}',array()) . '</div>'; \n";						
							}else{						
									echo "\t\t\t\t\techo \"<div class='col-md-6'>\".\$this->Form->input('{$field}',array()) . '</div>'; \n";						
								}					
							}
						}
				if (!empty($associations['hasAndBelongsToMany']))
				{
					foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData)
					{
						echo "\t\t\t\t\techo \$this->Form->input('{$assocName}');\n";
					}
				}
				
				echo "\t?>\n";
			?>
			</fieldset>
			<?php echo"<?php
			    echo \$this->Form->input('branchid', array('type' => 'hidden', 'value' => \$this->Session->read('User.branch_id')));
			    echo \$this->Form->input('departmentid', array('type' => 'hidden', 'value' => \$this->Session->read('User.department_id')));
			    echo \$this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => \$documentDetails['MasterListOfFormat']['id']));
			?>\n";
			?>
		</div>
		<div class="">
<?php echo "<?php\n
	\tif (\$showApprovals && \$showApprovals['show_panel'] == true) {
	\t\techo \$this->element('approval_form');
	\t} else {
	\t\techo \$this->Form->input('publish', array('label' => __('Publish')));
	\t}?>";

	echo "\n<?php echo \$this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#".$pluralVar."_ajax','async' => 'false')); ?>\n";
	echo "<?php echo \$this->Form->end(); ?>\n";
	echo "<?php echo \$this->Js->writeBuffer();?>\n";
?>
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
	<p><?php echo "<?php echo \$this->element('helps'); ?>" ?></p>
</div>
</div>
<?php 
	foreach ($fields as $field)
		{
			if (strpos($action, 'add') !== false && $field == $primaryKey || $field =='sr_no'){
				continue;
			}elseif(!in_array($field, array('created', 'modified', 'updated','created_by','modified_by','soft_delete','system_table_id','master_list_of_format_id','publish','record_status','status_user_id','branchid','departmentid','company_id','prepared_by','approved_by'))){
				if (strpos($field, '_id') !== false){
					$model_name_new = explode("_id",$field);
					// echo "\t\t\t\t\techo \"<div class='col-md-6'>\".\$this->Form->input('{$field}',array()) . '</div>'; \n";
					$attrs .=  "\n\t\t\t\t\t\t\t\t$(element).attr('name') == 'data[{$modelClass}][{$field}]' ||";
					$rules .= "\n\t\t\t\t\t\t\t\t\t\"data[{$modelClass}][{$field}]\": {
                    	greaterThanZero: true,
									},";
					$com .= 
				
				"\n\t\t\t\t$('#{$modelClass}".Inflector::camelize($field)."').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass(\"error\")) {
						$(this).next().next('label').remove();
					}
				});";
				}else{						
				}					
			}
		}
		$attrs = substr($attrs, 0, -3);
?>
<?php echo
"<script>
    $.validator.setDefaults({
    	ignore: null,
    	errorPlacement: function(error, element) {
            if (
                ".$attrs.")
						{	
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: \"<?php echo Router::url('/', true); ?><?php echo \$this->request->params['controller'] ?>/add_ajax\",
                type: 'POST',
                target: '#main',
                beforeSend: function(){
                   $(\"#submit_id\").prop(\"disabled\",true);
                    $(\"#submit-indicator\").show();
                },
                complete: function() {
                   $(\"#submit_id\").removeAttr(\"disabled\");
                   $(\"#submit-indicator\").hide();
                },
                error: function(request, status, error) {                    
                    alert('Action failed!');
                }
	    			});
        }
    });
		$().ready(function() {
    	$(\"#submit-indicator\").hide();
        jQuery.validator.addMethod(\"greaterThanZero\", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, \"Please select the value\");
        
        $('#{$modelClass}AddAjaxForm').validate({
            rules: {".$rules."
                
            }
        }); 
".$com."       
    });
</script>"; ?>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
