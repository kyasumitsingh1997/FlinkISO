<style type="text/css">
	.cke_contents{ height: 400px !important}
	ul{ padding: 0px !important}	
</style>
<div id="clauses_ajax">
<div class="row">   
    <div class="clauses form col-md-12">
    	<?php echo "<h4><small class='pull-right btn-group'>";
    	echo $this->Html->link('View',array('action'=>'view',$this->data['Clause']['id']),array('class'=>'btn btn-xs btn-info'));
    	echo $this->Html->link('Edit',array('action'=>'edit',$this->data['Clause']['id']),array('class'=>'btn btn-xs btn-warning')) . "</small></h4>"; ?>

    </div>
    <div class="col-md-12">
            <?php echo $this->Form->create('Clause',array('action'=>'edit',$this->request->data['Clause']['id']), array('role'=>'form','class'=>'form')); ?>
    <!-- <textarea id="ClauseAdditionalDetails_<?php echo $this->request->data['Clause']['id'];?>" name="data[Clause][additional_details]"> -->
        <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Guidance : <?php echo $this->data['Clause']['title'];?></h3>
            </div>
            <div class="box-body">
                <?php echo $this->data['Clause']['details'] ?> 
            </div>            
          </div>
          <?php if($this->data['Clause']['additional_details']){ ?>
              <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">Notes</h3>
                </div>
                <div class="box-body">
                    <?php echo $this->data['Clause']['additional_details'] ?>
                </div>            
              </div>
        <?php } ?>    
            
        
        

        
        
        
    <!-- </textarea> -->
    <?php            
        echo $this->Form->input('id');
        echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
        echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
        echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
        echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
           
        // if ($showApprovals && $showApprovals['show_panel'] == true) {
        //     echo $this->element('approval_form');
        // } else {
        //     echo $this->Form->input('publish', array('label' => __('Publish')));
        // }
    ?>
    <?php // echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')); ?>
    <?php // echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
    <?php echo $this->Form->end(); ?>

    <?php echo $this->Js->writeBuffer();?>
    </div>
    <?php echo $this->Js->get('#list');?>
    <?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#clauses_ajax')));?>
    <?php echo $this->Js->writeBuffer();?>
    </div>
</div>    
<script>
    $.validator.setDefaults();
    $().ready(function() {
        $("#submit-indicator").hide();
        $('#ClauseEditForm').validate();
        // $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#ClauseEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#ClauseEditForm').submit();
            }

        });
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

<br/><br/>
<h4><?php echo __('Add Related Documents Below'); ?></h4>
<div class="row">
	<div class='col-md-12'>
<div id="tabs">	
			<ul>
<?php 
	$tabs = explode(',', $this->data['Clause']['tabs']); ?>
	<div id="clause_tabs_<?php echo $this->request->data['Clause']['id'];?>">	
		<ul>
	<?php foreach ($tabs as $tab) { ?>
			<li><?php echo $this->Html->link(__($tab), array('action' => 'clausefiles',$this->data['Clause']['id'],$tab)); ?></li>
	<?php } ?>
			<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
		</ul>
	</div>
	<div id="documents_tabs_<?php echo $this->request->data['Clause']['id'];?>"></div>
<script>
  $(function() {
    $( "#clause_tabs_<?php echo $this->request->data['Clause']['id'];?>" ).tabs({
      beforeLoad: function( event, ui ) {
	ui.jqXHR.error(function() {
	  ui.panel.html(
	    "Error Loading ... " +
	    "Please contact administrator." );
	});
      }
    });
  }); 
</script>
<?php if($tables){ ?> 
<h4><?php echo __('Linked Tables'); ?></h4>
<div class="row">
    <div class='col-md-12'>
        <?php foreach ($tables as $table) {
            echo $this->Html->link($table['SystemTable']['name'],array('controller'=>$table['SystemTable']['system_name'],'action'=>'index'),array('class'=>'btn btn-sm btn-info'));
        } ?>
    </div>
</div>
<?php }?>
<?php if($masterListOfFormats){ ?> 
<div class="row">
    <div class="col-md-12">
        <h4><?php echo __('Linked Quality Documents');?></h4>
            <?php foreach ($masterListOfFormats as $masterListOfFormat) { ?>
                <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
                        <tr>
                            <th><?php echo __('Title'); ?></th>
                            <th><?php echo __('Category'); ?></th>
                            <th><?php echo __('Document #'); ?></th>
                            <th><?php echo __('Issue #'); ?></th>
                            <th><?php echo __('Revision #'); ?></th>
                            <th><?php echo __('Revision Date'); ?></th>
                            <th><?php echo __('Prepared By'); ?></th>
                            <th><?php echo __('Approved By'); ?></th>
                            <th><?php echo __('Archived?'); ?></th>
                            <th><?php echo __('publish', __('Publish')); ?></th>
                        </tr>
                        <?php
                            if ($masterListOfFormats) {
                                $x = 0;
                                foreach ($masterListOfFormats as $masterListOfFormat):
                        ?>
                        <tr class="on_page_src">
                            <td><?php echo $this->Html->link($masterListOfFormat['MasterListOfFormat']['title'],array('controller'=>'master_list_of_formats','action'=>'view',$masterListOfFormat['MasterListOfFormat']['id'])); ?>&nbsp;</td>
                            <td><?php echo h($masterListOfFormat['MasterListOfFormatCategory']['name']); ?>&nbsp;</td>
                            <td><?php echo h($masterListOfFormat['MasterListOfFormat']['document_number']); ?>&nbsp;</td>
                            <td><?php echo h($masterListOfFormat['MasterListOfFormat']['issue_number']); ?>&nbsp;</td>
                            <td><?php echo h($masterListOfFormat['MasterListOfFormat']['revision_number']); ?>&nbsp;</td>
                            <td><?php echo h($masterListOfFormat['MasterListOfFormat']['Departments']); ?>&nbsp;</td>
                            <td><?php echo h($masterListOfFormat['PreparedBy']['name']); ?>&nbsp;</td>
                            <td><?php echo h($masterListOfFormat['ApprovedBy']['name']); ?>&nbsp;</td>
                            <td><?php echo h($masterListOfFormat['MasterListOfFormat']['archived']) ? __('Yes') : __('No'); ?>&nbsp;</td>

                            <td width="60">                                
                                <?php if ($masterListOfFormat['MasterListOfFormat']['publish'] == 1) { ?>
                                    <span class="fa fa-check"></span>
                                <?php } else { ?>
                                    <span class="fa fa-ban"></span>
                                <?php } ?>&nbsp;
                            </td>                        
                        </tr>
                        <?php
                            $x++;
                            endforeach;
                            } else {
                        ?>
                        <tr><td colspan=10><?php echo __('No results found'); ?></td></tr>
                        <?php } ?>
                    </table>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<div class="row">
    <div class="col-md-12"><h4><?php echo __('External Links'); ?></h4></div>
    <div class="col-md-12">
        External Link 1 : <?php if($this->request->data['Clause']['external_link_1'])echo $this->Html->link($this->request->data['Clause']['external_link_1'],$this->request->data['Clause']['external_link_1'],array('target'=>'_blank'));?></div>
    <div class="col-md-12">External Link 2 : <?php echo $this->request->data['Clause']['external_link_2'];?></div>
    <div class="col-md-12">External Link 3 : <?php echo $this->request->data['Clause']['external_link_3'];?></div>
    <div class="col-md-12">External Link 4 : <?php echo $this->request->data['Clause']['external_link_4'];?></div>
    <div class="col-md-12">External Link 5 : <?php echo $this->request->data['Clause']['external_link_5'];?></div>
</div>
</div>