Test
<div id="fileCategories_ajax">
<?php echo $this->Session->flash();?>
<div class="table-responsive">
	<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
		<tr>
			<th>Name</th>
			<th>Priority</th>
			<th>Action</th>
			<th>Publish</th>		
		</tr>
	<?php if($fileCategories){ ?>
	<?php foreach ($fileCategories as $fileCategory): ?>
	<tr>
			<td><?php echo $this->Html->link($fileCategory['FileCategory']['name'],'javascript:void(0)',array('id'=>'name-'.$fileCategory['FileCategory']['id'])) ?>&nbsp;</td>
			<td><?php echo $this->Html->link($fileCategory['FileCategory']['priority'],'javascript:void(0)',array('id'=>'priority-'.$fileCategory['FileCategory']['id'])) ?>&nbsp;</td>

			<script type="text/javascript">
	            $(document).ready(function() {$('#name-<?php echo $fileCategory['FileCategory']['id'] ?>').editable({
	                   type:  'text',
	                   pk:    '<?php echo $fileCategory['FileCategory']['id'] ?>',
	                   name:  'data.FileCategory.name',
	                   url:   '<?php echo Router::url('/', true);?>file_categories/inplace_edit',  
	                   title: 'Change',
	                   placement : 'right'
	                });
	            });

	            $(document).ready(function() {$('#priority-<?php echo $fileCategory['FileCategory']['id'] ?>').editable({
	                   type:  'text',
	                   pk:    '<?php echo $fileCategory['FileCategory']['id'] ?>',
	                   name:  'data.FileCategory.priority',
	                   url:   '<?php echo Router::url('/', true);?>file_categories/inplace_edit',  
	                   title: 'Change',
	                   placement : 'right'
	                });
	            });
          </script>
          <td><?php 
          if($fileCategory['FileCategory']['status'] == 0){
          	$h = 'Hold';
          	$htype = "1";
          	$hclass = ' btn-danger';
          }else{
          	$h = 'Release';
          	$htype = "0";
          	$hclass = ' btn-success';
          }
          echo $this->Html->link($h,'javascript:void(0)',array('onclick'=>'holdcat("'.$fileCategory['FileCategory']['id'].'","'.$htype.'")' , 'class'=>'btn btn-xs '. $hclass, 'id'=>'hold-'.$fileCategory['FileCategory']['id'])) ?>&nbsp;

          <?php 
          echo $this->Html->link('Delete','javascript:void(0)', array('class'=>'btn btn-xs btn-danger', 'onClick'=>'removeCat("'.$fileCategory['FileCategory']['id'].'")', 'confirm'=>'Text'));
          ?>
      </td>
		<td width="60">
			<?php if($fileCategory['FileCategory']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	<?php }else{ ?>
		<tr><td colspan=60>No results found</td></tr>
	<?php } ?>
	</table>
</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#fileCategories_ajax',
			'evalScripts' => true,
			'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
			'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
			));
			
			echo $this->Paginator->counter(array(
			'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
			));
			?>			</p>
			<ul class="pagination">
			<?php
		echo "<li class='previous'>".$this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'))."</li>";
		echo "<li>".$this->Paginator->numbers(array('separator' => ''))."</li>";
		echo "<li class='next'>".$this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'))."</li>";
	?>
			</ul>


<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

<script type="text/javascript">
	function removeCat(id) {
	    var r = confirm("Are you sure to remove this Category?");
	    if (r == true)
	    {
	        $.ajax({
                url: "<?php echo Router::url('/', true); ?>file_categories/delete/" + id,
                // get: $('#InternalAuditPlanDepartmentDepartmentId').val(),
                success: function(data, result) {
                    // $('#InternalAuditPlanDepartmentClauses').val(data);
                    alert(data);
                }
            });
	    }
	}
</script>
	<div class="nav">
		<div class="fileCategories form col-md-12">
			<h4>Add File Category</h4>
			<?php echo $this->Form->create('FileCategory',array('role'=>'form','class'=>'form','default'=>false)); ?>
			<div class="row">
			<fieldset>
					<?php
					echo "<div class='col-md-6'>".$this->Form->input('name',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('priority',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->hidden('project_id',array('default'=>$this->request->params['pass'][0])) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->hidden('milestone_id',array('default'=>$this->request->params['pass'][1])) . '</div>'; 
	?>
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
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#fileCategories_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
		</div>
	</div>
<script> 
	$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat:'yy-mm-dd',
    }); 
</script>

<script>
    $.validator.setDefaults({
    	ignore: null,
    	errorPlacement: function(error, element) {
            
                $(element).after(error);
            
        },
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#fileCategories_ajax',
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
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        
        $('#FileCategoryAddAjaxForm').validate({
            
        }); 
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
<script type="text/javascript">
	function holdcat(cat_id,fstatus){
  		$.ajax({
            url: "<?php echo Router::url('/', true); ?>projects/holdcat/"+cat_id+"/"+fstatus,
            success: function(data, result) {
                // alert(data);
                $("#hold-"+cat_id).html(data);
            }
        });
  	}
</script>

</div>