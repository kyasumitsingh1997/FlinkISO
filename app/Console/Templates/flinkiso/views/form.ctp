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
<?php
echo "<?php echo \$this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>\n";
echo "<?php echo \$this->fetch('script'); ?>";?>
<div id="<?php echo $pluralVar; ?>_ajax">
<?php echo "<?php echo \$this->Session->flash();?>"; ?>	
<div class="nav panel panel-default">
<div class="<?php echo $pluralVar; ?> form col-md-8">
<h4><?php printf("<?php echo __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?>
		<?php printf("\n\t\t<?php echo \$this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>\n"); ?>		
		</h4>
<?php echo "<?php echo \$this->Form->create('{$modelClass}',array('role'=>'form','class'=>'form')); ?>\n"; ?>
<div class="row">
		<?php
		echo "\t<?php\n";
		foreach ($fields as $field)
		{
			if (strpos($action, 'add') !== false && $field == $primaryKey || $field =='sr_no' || $field == 'id')
				{
				continue;
				} elseif (!in_array($field, array('created', 'modified', 'updated','created_by','modified_by','soft_delete','system_table_id','master_list_of_format_id','publish','record_status','status_user_id','branchid','departmentid','company_id','prepared_by','approved_by')))
				{
				
					if (strpos($field, '_id') !== false){
						$model_name_new = explode("_id",$field);
						 echo "\t\techo \"<div class='col-md-6'>\".\$this->Form->input('{$field}',array('style'=>'')) . '</div>'; \n";						
					}else{
							if($schema[$field]['type'] == 'text')						
								echo "\t\techo \"<div class='col-md-12'>\".\$this->Form->input('{$field}') . '</div>'; \n";
							else
								echo "\t\techo \"<div class='col-md-6'>\".\$this->Form->input('{$field}') . '</div>'; \n";

						}					
					
			}
		}
		if (!empty($associations['hasAndBelongsToMany']))
		{
			foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData)
			{
				echo "\t\techo \$this->Form->input('{$assocName}');\n";
			}
		}
		
		echo "\t?>\n";
?>
<?php echo"<?php
		echo \$this->Form->input('id');
		echo \$this->Form->hidden('History.pre_post_values', array('value'=>json_encode(\$this->data)));
		echo \$this->Form->input('branchid', array('type' => 'hidden', 'value' => \$this->Session->read('User.branch_id')));
		echo \$this->Form->input('departmentid', array('type' => 'hidden', 'value' => \$this->Session->read('User.department_id')));
		echo \$this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => \$documentDetails['MasterListOfFormat']['id']));
		?>
";?>

</div>
<div class="row">
<?php echo "<?php
\tif (\$showApprovals && \$showApprovals['show_panel'] == true) {
\t\techo \$this->element('approval_form');
\t} else {
\t\techo \$this->Form->input('publish', array('label' => __('Publish')));
\t}
?>";
?>
<?php
	
	echo "\n<?php echo \$this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')); ?>";
	echo "\n<?php echo \$this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>";
	echo "\n<?php echo \$this->Form->end(); ?>\n";
	echo "\n<?php echo \$this->Js->writeBuffer();?>\n";
?>
</div>
</div>
<script> 
	$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat:'yy-mm-dd',
    }); </script>
<div class="col-md-4">
	<p><?php echo "<?php echo \$this->element('helps'); ?>" ?></p>
</div>
</div>
<?php echo "<?php echo \$this->Js->get('#list');?>\n" ?>
<?php echo "<?php echo \$this->Js->event('click',\$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#".$pluralVar."_ajax')));?>\n"?>
<?php echo "<?php echo \$this->Js->writeBuffer();?>\n" ?>
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
				$attrs .=  "\n\t\t\t$(element).attr('name') == 'data[{$modelClass}][{$field}]' ||";
				$rules .= "\n\t\t\t\t\"data[{$modelClass}][{$field}]\": {
                		greaterThanZero: true,
					},";
$com .= 
		"\n\t\t$('#{$modelClass}".Inflector::camelize($field)."').change(function() {
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
    });
    
    $().ready(function() {
    	jQuery.validator.addMethod(\"greaterThanZero\", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, \"Please select the value\");

        $('#{$modelClass}".Inflector::humanize($action)."Form').validate({        	
            rules: {".$rules."
                
            }
        }); 
			
        $(\"#submit-indicator\").hide();
        $(\"#submit_id\").click(function(){
            if($('#{$modelClass}".Inflector::humanize($action)."Form').valid()){
                 $(\"#submit_id\").prop(\"disabled\",true);
                 $(\"#submit-indicator\").show();
                $('#{$modelClass}".Inflector::humanize($action)."Form').submit();
            }

        });
".$com."	

    });
</script>"; ?>
