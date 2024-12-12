<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min','ckeditor/ckeditor')); ?>
<?php echo $this->fetch('script'); ?>
<?php if($this->data['ChangeAdditionDeletionRequest']['file_upload_id'] != null && $this->data['ChangeAdditionDeletionRequest']['file_upload_id'] != '-1'){  ?>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[ChangeAdditionDeletionRequest][prepared_by]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][approved_by]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][master_list_of_format]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][branch_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][department_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][employee_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][suggestion_form_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][customer_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        }
    });
    $().ready(function () {
        $("#submit-indicator").hide();
        $("#submit_id").click(function () {
            $('#ChangeAdditionDeletionRequestProposedDocumentChanges').val(CKEDITOR.instances.ChangeAdditionDeletionRequestProposedDocumentChanges.getData());
            $('#ChangeAdditionDeletionRequestProposedWorkInstructionChanges').val(CKEDITOR.instances.ChangeAdditionDeletionRequestProposedWorkInstructionChanges.getData());
            if ($('#ChangeAdditionDeletionRequestProposedDocumentChanges').val() == '') {
                alert("Please enter proposed document changes");
                return false;
            }

            // if ($('#ChangeAdditionDeletionRequestProposedWorkInstructionChanges').val() == '') {
            //     alert("Please enter proposed work instruction changes");
            //     return false;
            // }
            if ($('#ChangeAdditionDeletionRequestEditForm').valid()) {
                $("#submit_id").prop("disabled", true);
                $("#submit-indicator").show();
                $("#ChangeAdditionDeletionRequestEditForm").submit();
            }

        });
        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#ChangeAdditionDeletionRequestEditForm').validate({
            rules: {
                "data[ChangeAdditionDeletionRequest][master_list_of_format]": {
                    greaterThanZero: true,
                },
                "data[ChangeAdditionDeletionRequest][prepared_by]": {
                    greaterThanZero: true,
                }
            }
        });

        $('#ChangeAdditionDeletionRequestMasterListOfFormat').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ChangeAdditionDeletionRequestPreparedBy').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });


        functionalityChangeReq();
        $('#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeRequired').change(function () {
            functionalityChangeReq();
        });
    });

    function functionalityChangeReq() {
        if ($('#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeRequired').prop('checked') == false) {
            $("#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeDetails").prop("disabled", true);
            $("#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeDetails").val('');
        } else {
            $("#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeDetails").prop("disabled", false);
        }
    }
</script>
<?php
    if (isset($this->data['ChangeAdditionDeletionRequest']['branch_id']) && $this->data['ChangeAdditionDeletionRequest']['branch_id'] != -1) {
        $requestFrom = 'Branch';
    } elseif (isset($this->data['ChangeAdditionDeletionRequest']['department_id']) && $this->data['ChangeAdditionDeletionRequest']['department_id'] != -1) {
        $requestFrom = 'Department';
    } elseif (isset($this->data['ChangeAdditionDeletionRequest']['employee_id']) && $this->data['ChangeAdditionDeletionRequest']['employee_id'] != -1) {
        $requestFrom = 'Employee';
    } elseif (isset($this->data['ChangeAdditionDeletionRequest']['customer_id']) && $this->data['ChangeAdditionDeletionRequest']['customer_id'] != -1) {
        $requestFrom = 'Customer';
    } elseif (isset($this->data['ChangeAdditionDeletionRequest']['suggestion_form_id']) && $this->data['ChangeAdditionDeletionRequest']['suggestion_form_id'] != -1) {
        $requestFrom = 'SuggestionForm';
    } else {
        $requestFrom = 'Other';
    }
?>

<div id="changeAdditionDeletionRequests_ajax"> <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="changeAdditionDeletionRequests form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('Edit Change Request'); ?> <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
                <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view', $this->request->params['pass'][0] . '.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?> </h4>
            <?php echo $this->Form->create('ChangeAdditionDeletionRequest', array('role' => 'form', 'class' => 'form')); ?> <?php echo $this->Form->input('id'); ?>
            <div class="row">
                <div class="col-md-12">
                            <h4><?php echo __('Current Details'); ?></h4>
                               <table class="table bordered">
            <tr>
                <td colspan="4">
                    <h4>
                    <?php
                        $webroot = "/ajax_multi_upload";
                        $fullPath = Configure::read('MediaPath') . 'files' . DS . $this->Session->read('User.company_id'). DS . $file['FileUpload']['file_dir'];
                        $displayPath = base64_encode(str_replace(DS , '/', $file['FileUpload']['id']));
                        $baseEncFile = base64_encode($fullPath);
                        $delUrl = "$webroot/file_uploads/delete/".$file['FileUpload']['id'];
                        $permanentDelUrl = "$webroot/file_uploads/purge/".$file['FileUpload']['id'];

                        if($file['FileUpload']['file_status'] == 1 or $file['FileUpload']['file_status'] == 2) echo $this->Html->link('Download '.$file['FileUpload']['file_details'].'.'.$file['FileUpload']['file_type'],array(
                            'controller' => 'file_uploads',
                            'action' => 'view_media_file',
                            'full_base' => $displayPath
                        ), array('target'=>'_blank','escape'=>TRUE));
                    ?>
                    </h4>
                </td>
            </tr>
            <tr>
                <td><?php echo __('File Name'); ?></td><td><?php echo $file['FileUpload']['name']; ?></td>
                <td><?php echo __('Table'); ?></td>
                <td>
                    <?php 
                        if($file['SystemTable']['name'])echo $file['SystemTable']['name']; 
                        elseif($file['FileUpload']['system_table_id'] == 'clauses')echo $clause['Standard']['name'] .'-'.$clause['Clause']['title']; 
                        else echo '---';
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo __('Record'); ?></td><td>
                    <?php
                    if($file['FileUpload']['system_table_id'] == 'clauses'){                        
                        echo $clause['Clause']['clause'];
                    }else{
                        if(isset($record)){
                            echo $this->Html->link($record['displayName'],array(
                                'controller'=>Inflector::Variable($file['SystemTable']['name']),'action'=>'view',$file['FileUpload']['record']),
                            array('target'=>'_blank','class'=>'link text-primary'));
                        }
                    }
                        
                    ?>
                </td>
                <td><?php echo __('Archived?'); ?></td><td><?php echo $file['FileUpload']['archived']?'yes':'No'; ?></td>
            </tr>
            <tr>
                <td><?php echo __('Prepared By'); ?></td><td><?php echo $this->request->data['PreparedBy']['name']; ?></td>
                <td><?php echo __('Approved By'); ?></td><td><?php echo $this->request->data['ApprovedBy']['name']; ?></td>
            </tr>
            <tr>
                <td><?php echo __('Created'); ?></td><td><?php echo $this->request->data['ChangeAdditionDeletionRequest']['created']; ?></td>
                <?php if($file['MasterListOfFormat']['title']){ ?><td><?php echo __('Format'); ?></td><td><?php echo $file['MasterListOfFormat']['title']; ?></td><?php } ?>
            </tr>
        </table>
        </div>
        <div class="">
            <div class="col-md-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#currentdocumentdetails" aria-controls="currentdocumentdetails" role="tab" data-toggle="tab"><?php echo __('Current Document Details'); ?></a></li>
                    <li role="presentation"><a href="#proposedchanges" aria-controls="proposedchanges" role="tab" data-toggle="tab"><?php echo __('Proposed Document Changes'); ?></a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="currentdocumentdetails">
                        <div class="note text-info">Open the document and select and copy paste the contect you would like to change below.</div>
                            <textarea name="data[ChangeAdditionDeletionRequest][current_document_detail]" id="ChangeAdditionDeletionRequestCurrentDocumentDetail" >
                                <?php echo htmlspecialchars_decode($this->request->data['ChangeAdditionDeletionRequest']['current_document_details']) ?>
                            </textarea>                        
                    </div>
                    <div role="tabpanel" class="tab-pane" id="proposedchanges">
                        <div class="note text-info"><?php echo __('Add changed contect below'); ?></div>
                            <textarea name="data[ChangeAdditionDeletionRequest][proposed_document_change]" id="ChangeAdditionDeletionRequestProposedDocumentChange" >    
                                <?php echo htmlspecialchars_decode($this->request->data['ChangeAdditionDeletionRequest']['proposed_document_changes']) ?>                    
                            </textarea>                        
                    </div>        
                </div>
            </div>
        </div>  
                <div class="col-md-12"><?php echo $this->Form->input('title', array()); ?></div>
                <div class="col-md-12"><?php echo $this->Form->input('request_from', array('default' => $requestFrom, 'options' => array('Branch' => __('Branch'), 'Department' => __('Department'), 'Employee' => __('Employee'), 'Customer' => __('Customer'), 'SuggestionForm' => __('Suggestion'), 'Other' => __('Other')), 'type' => 'radio')); ?></div>
                <div class="col-md-6 hidediv" id="Branch"><?php echo $this->Form->input('branch_id', array('style' => 'width:100%', 'label' => __('Branch'))); ?></div>
                <div class="col-md-6 hidediv" id="Department"><?php echo $this->Form->input('department_id', array('style' => 'width:100%', 'label' => __('Department'))); ?></div>
                <div class="col-md-6 hidediv" id="Employee"><?php echo $this->Form->input('employee_id', array('style' => 'width:100%')); ?></div>
                <div class="col-md-6 hidediv" id="Customer"><?php echo $this->Form->input('customer_id', array('style' => 'width:100%')); ?></div>
                <div class="col-md-6 hidediv" id="SuggestionForm"><?php echo $this->Form->input('suggestion_form_id', array('style' => 'width:100%', 'label' => __('Suggestion Form'))); ?></div>
                <div class="col-md-6 hidediv" id="Other">
                    <?php
                        $capaCheck = strpos($this->data['ChangeAdditionDeletionRequest']['others'], "CAPA Number: ");
                        if ($capaCheck == 0) {
                            $capaNumber = str_replace("CAPA Number: ", '', $this->data['ChangeAdditionDeletionRequest']['others']);
                            ?>
                            <label id="ChangeAdditionDeletionRequestOthers">CAPA Number: </label>
                            <?php echo $capaNumber; ?>
                            <?php echo $this->Form->hidden('others'); ?>
                            <?php
                        } else {
                            echo $this->Form->input('others', array('label' => __('Other')));
                        }
                    ?>
                </div>
                
            </div>
            <!-- <div class="row">            
                       <div class="col-md-12"><h4><?php echo __('Current Document Details'); ?></h4>
                                    <textarea name="data[ChangeAdditionDeletionRequest][current_document_detail]" id="ChangeAdditionDeletionRequestCurrentDocumentDetail" >
                                        <?php echo htmlspecialchars_decode($this->request->data['ChangeAdditionDeletionRequest']['current_document_details']) ?>
                                    </textarea>
                                </div>
                        </div>
                        <h3><?php echo __('Change Request Details'); ?></h3>
                        <div class="row">            
                               <div class="col-md-12"><h4><?php echo __('Proposed Document Details'); ?> <small><?php echo __('Add or highlige changes below'); ?></small></h4>
                                    <textarea name="data[ChangeAdditionDeletionRequest][proposed_document_change]" id="ChangeAdditionDeletionRequestProposedDocumentChange" >
                                    <?php echo htmlspecialchars_decode($this->request->data['ChangeAdditionDeletionRequest']['proposed_document_changes']) ?>                        
                                    </textarea>
                                </div>
                        </div> -->
                                  
            <div class="row">
                <div class="col-md-12"><?php echo $this->Form->input('reason_for_change'); ?><span class="help"><?php echo __('Short description of changes required & reason for document change'); ?></span>
                    <?php echo $this->Form->hidden('request_details'); ?>
                </div>

                <?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?> <?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?>
            </div>

            <div class="row"><br />
            </div>
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><?php echo __('Accept/Reject?'); ?></h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            $options = array(0 => 'Rejected', 1 => 'Accepted', 2 => 'Open');
                            echo $this->Form->input('document_change_accepted', array('options' => $options, 'default' => 2, 'type' => 'radio', 'label' => __('Document Change Accepted')));
                            ?>
                        </div>
                        <div class="col-md-6"><?php echo $this->Form->input('meeting_id', array('label' => __('Approved in meeting'))); ?></div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <?php echo $this->Form->hidden('file_upload_id',array('value'=>$file['FileUpload']['id'])); ?>
                        <?php echo $this->Form->hidden('file_upload_system_table_id',array('value'=>$file['FileUpload']['system_table_id'])); ?>
                        <?php echo $this->Form->hidden('file_upload_record',array('value'=>$file['FileUpload']['record'])); ?>
                        <!--<div class="col-md-12">
                            <dl>
                                <dt><?php echo __('Title'); ?></dt>
                                <dd><?php echo $this->data['MasterListOfFormat']['title']; ?></dd>
                                <dt><?php echo __('Document Number'); ?></dt>
                                <dd><?php echo $this->data['MasterListOfFormat']['document_number']; ?></dd>
                                <dt><?php echo __('Issue Number'); ?></dt>
                                <dd><?php echo $this->data['MasterListOfFormat']['issue_number']; ?></dd>
                                <dt><?php echo __('Revision Number'); ?></dt>
                                <dd><?php echo $this->data['MasterListOfFormat']['revision_number']; ?></dd>
                                <dt><?php echo __('Revision Date'); ?></dt>
                                <dd><?php echo $this->data['MasterListOfFormat']['revision_date']; ?></dd>
                            </dl>
                        </div>                       
                        
                    </div>                    
                        <?php echo $this->Form->hidden('file_upload_id',array('value'=>$file['FileUpload']['id'])); ?> 
                        <?php echo $this->Form->hidden('FileUpload.title', array('value' => $this->data['FileUpload']['title'])); ?> 
                        <?php echo $this->Form->hidden('FileUpload.document_number'); ?> 
                        <?php echo $this->Form->hidden('FileUpload.issue_number'); ?> 
                        <?php echo $this->Form->hidden('FileUpload.revision_number'); ?> 
                        <?php echo $this->Form->hidden('FileUpload.revision_date'); ?> 
                        <?php echo $this->Form->hidden('FileUpload.proposed_document_changes'); ?> 
                        <?php echo $this->Form->hidden('FileUpload.proposed_work_instruction_changes'); ?> 
                        <?php echo $this->Form->hidden('FileUpload.prepared_by'); ?> 
                        <?php echo $this->Form->hidden('FileUpload.approved_by'); ?> 
                        <?php echo $this->Form->hidden('FileUpload.system_table_id', array('value' => $file->data['FileUpload']['system_table_id'])); ?>
                        <!--
                        <div class="col-md-8"><?php echo $this->Form->input('NewFileUpload.title', array('value' => $this->data['FileUpload']['title'])); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('NewFileUpload.document_number', array('value' => $this->data['FileUpload']['document_number'])); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('NewFileUpload.issue_number', array('value' => $this->data['FileUpload']['issue_number'])); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('NewFileUpload.revision_number', array('value' => $this->data['FileUpload']['revision_number'] + 1)); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('NewFileUpload.revision_date', array('value' => date('Y-m-d'))); ?></div>
                        

                        <div class="row">
                        <div class="col-md-12"><h4><?php echo __('Revised Details'); ?></h4></div>
                        <div class="col-md-6"><?php 
                            $file_details = explode("-ver-",$file['FileUpload']['file_details']);
                                echo $this->Form->input('file_details',array('label'=>'File Name', 'disabled', 'value'=>$file_details[0])); 
                               echo $this->Form->hidden('old_file_details',array('label'=>'File Name','value'=>$file['FileUpload']['file_dir'])); 
                               echo $this->Form->hidden('file_dir',array('label'=>'File Dir')); ?>       
                            </div>
                            
                            <div class="col-md-3"><br /><br /><?php echo "-ver-". ($file_details[1] + 1).".";
                                echo $file['FileUpload']['file_type'];
                                echo $this->Form->hidden('file_type'); ?>
                            </div>  
                            <div class="col-md-3"><?php 
                                    echo $this->Form->input('version',array('value'=>$file['FileUpload']['version'] + 1, 'min'=>1)); 
                            ?></div>
                            <div class="col-md-6"><?php echo $this->Form->input('prepared_by',array('options'=>$PublishedEmployeeList,'label'=>'Prepared By','style'=>'width:100%')); ?></div>              
                            <div class="col-md-6"><?php echo $this->Form->input('approved_by',array('options'=>$PublishedEmployeeList,'label'=>'Approved By','style'=>'width:100%')); ?></div>
                            -->
                        </div>    


                        <div class="row">    

                        <div class="col-md-12">
                            <div class="alert alert-info"> <?php echo '<strong>' . __('Note : ') . '</strong>' . __('If Document changes required additional database fields or change in functionality, send us the new requirements by adding details below.'); ?> </div>
                        </div>
                        <div class="col-md-12"><?php echo $this->Form->input('flinkiso_functionality_change_required', array('label' => 'FlinkISO functionality change required', 'type' => 'checkbox')); ?></div>
                        <div class="col-md-12"> <?php echo $this->Form->input('flinkiso_functionality_change_details', array('label' => false)); ?> <span class="help-text"><?php echo __('Add your new requirement here. If you check "flinkiso functionality change required", then these changes will be sent to FlinkISO customisation team for future review.'); ?></span> </div>
                    </div>
                </div>
            </div>
                <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }
            ?>
            <?php
            echo $this->Form->hidden('History.pre_post_values', array('value' => json_encode($this->data)));
            echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'id' => 'submit_id'));
            ?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>
        </div>
        <script>
            $("[name*='date']").datepicker({
                changeMonth: true,
                changeYear: true,
                format: 'yyyy-mm-dd',
      autoclose:true,
                'showTimepicker': false,
            }).attr('readonly', 'readonly');

            $(document).ready(function () {
                $('.hidediv').hide();
                $('#<?php echo $requestFrom; ?>').show();

                var $requestFrom = $('input:radio[name="data[ChangeAdditionDeletionRequest][request_from]"]:checked').val();

                if ($requestFrom != 'Other') {
                    $('#ChangeAdditionDeletionRequest' + $requestFrom + 'Id').rules('add', {
                        greaterThanZero: true
                    });
                    $('#ChangeAdditionDeletionRequest' + $requestFrom + 'Id').change(function () {
                        if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                            $(this).next().next('label').remove();
                        }
                    });
                } else {
                    $('#ChangeAdditionDeletionRequestOthers').rules('add', {
                        required: true
                    });
                }

                $("[name='data[ChangeAdditionDeletionRequest][request_from]']").click(function () {
                    $val = this.value;
                    $('.hidediv').hide();
                    $('#ChangeAdditionDeletionRequestBranchId').val(0).trigger('chosen:updated');
                    $('#ChangeAdditionDeletionRequestDepartmentId').val(0).trigger('chosen:updated');
                    $('#ChangeAdditionDeletionRequestEmployeeId').val(0).trigger('chosen:updated');
                    $('#ChangeAdditionDeletionRequestCustomerId').val(0).trigger('chosen:updated');
                    $('#ChangeAdditionDeletionRequestSuggestionFormId').val(0).trigger('chosen:updated');
                    $('#ChangeAdditionDeletionRequestOthers').val('');

                    $('.hidediv').find('select').prop('value', -1);
                    $('#' + $val).toggle();
                    $('#ChangeAdditionDeletionRequest' + $val + 'Id_chosen').width('100%');

                    $('#ChangeAdditionDeletionRequestBranchId').rules('remove');
                    $('#ChangeAdditionDeletionRequestDepartmentId').rules('remove');
                    $('#ChangeAdditionDeletionRequestEmployeeId').rules('remove');
                    $('#ChangeAdditionDeletionRequestCustomerId').rules('remove');
                    $('#ChangeAdditionDeletionRequestSuggestionFormId').rules('remove');
                    $('#ChangeAdditionDeletionRequestOthers').rules('remove');

                    if ($val != 'Other') {
                        $('#ChangeAdditionDeletionRequest' + $val + 'Id').rules('add', {
                            greaterThanZero: true
                        });
                        $('#ChangeAdditionDeletionRequest' + $val + 'Id').change(function () {
                            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                                $(this).next().next('label').remove();
                            }
                        });
                    } else {
                        $('#ChangeAdditionDeletionRequestOthers').rules('add', {
                            required: true
                        });
                    }
                });
            });
        </script>
        <div class="col-md-4">
            <p><?php echo $this->element('document_revisions'); ?></p>
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#changeAdditionDeletionRequests_ajax'))); ?> <?php echo $this->Js->writeBuffer(); ?> </div>

<script type="text/javascript">
        CKEDITOR.replace('ChangeAdditionDeletionRequestCurrentDocumentDetail', {
            filebrowserBrowseUrl: '<?php echo Router::url("/", true); ?>img/ckeditor/browse.php?dir=<?php echo WWW_ROOT ?>img/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>&path=<?php echo Router::url("/", true); ?>img/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>',
            filebrowserUploadUrl: '<?php echo Router::url("/", true); ?><?php echo $this->request->params["controller"] ?>/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>',
            toolbar: [
                { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'CopyFormatting' ] },
                { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
                { name: 'insert', items: [ 'Image', 'Table','HorizontalRule','PageBreak'] },
                { name: 'tools', items: [ 'Maximize' ] },
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
            extraPlugins: 'tableresize,lineheight',
            height: 800,
            contentsCss: [ '<?php echo Router::url("/", true); ?>css/contents.css', '<?php echo Router::url("/", true); ?>css/mystyles.css' ],
            bodyClass: 'document-editor',
            format_tags: 'p;h1;h2;h3;pre',
            removeDialogTabs: 'image:advanced;link:advanced',
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

        CKEDITOR.replace('ChangeAdditionDeletionRequestProposedDocumentChange', {
            filebrowserBrowseUrl: '<?php echo Router::url("/", true); ?>img/ckeditor/browse.php?dir=<?php echo WWW_ROOT ?>img/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>&path=<?php echo Router::url("/", true); ?>img/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>',
            filebrowserUploadUrl: '<?php echo Router::url("/", true); ?><?php echo $this->request->params["controller"] ?>/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>',
            toolbar: [
                { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'CopyFormatting' ] },
                { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
                { name: 'insert', items: [ 'Image', 'Table','HorizontalRule','PageBreak'] },
                { name: 'tools', items: [ 'Maximize' ] },
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
            extraPlugins: 'tableresize,lineheight',
            height: 800,
            contentsCss: [ '<?php echo Router::url("/", true); ?>css/contents.css', '<?php echo Router::url("/", true); ?>css/mystyles.css' ],
            bodyClass: 'document-editor',
            format_tags: 'p;h1;h2;h3;pre',
            removeDialogTabs: 'image:advanced;link:advanced',
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


<?php } else { ?> 
<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[ChangeAdditionDeletionRequest][prepared_by]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][approved_by]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][master_list_of_format]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][branch_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][department_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][employee_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][suggestion_form_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][customer_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        }
    });
    $().ready(function () {
        $("#submit-indicator").hide();
        $("#submit_id").click(function () {
            $('#ChangeAdditionDeletionRequestProposedDocumentChanges').val(CKEDITOR.instances.ChangeAdditionDeletionRequestProposedDocumentChanges.getData());
            $('#ChangeAdditionDeletionRequestProposedWorkInstructionChanges').val(CKEDITOR.instances.ChangeAdditionDeletionRequestProposedWorkInstructionChanges.getData());
            if ($('#ChangeAdditionDeletionRequestProposedDocumentChanges').val() == '') {
                alert("Please enter proposed document changes");
                return false;
            }

            // if ($('#ChangeAdditionDeletionRequestProposedWorkInstructionChanges').val() == '') {
            //     alert("Please enter proposed work instruction changes");
            //     return false;
            // }
            if ($('#ChangeAdditionDeletionRequestEditForm').valid()) {
                $("#submit_id").prop("disabled", true);
                $("#submit-indicator").show();
                $("#ChangeAdditionDeletionRequestEditForm").submit();
            }

        });
        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#ChangeAdditionDeletionRequestEditForm').validate({
            rules: {
                "data[ChangeAdditionDeletionRequest][master_list_of_format]": {
                    greaterThanZero: true,
                },
                "data[ChangeAdditionDeletionRequest][prepared_by]": {
                    greaterThanZero: true,
                }
            }
        });

        $('#ChangeAdditionDeletionRequestMasterListOfFormat').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ChangeAdditionDeletionRequestPreparedBy').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });


        functionalityChangeReq();
        $('#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeRequired').change(function () {
            functionalityChangeReq();
        });
    });

    function functionalityChangeReq() {
        if ($('#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeRequired').prop('checked') == false) {
            $("#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeDetails").prop("disabled", true);
            $("#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeDetails").val('');
        } else {
            $("#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeDetails").prop("disabled", false);
        }
    }
</script>
<?php
    if (isset($this->data['ChangeAdditionDeletionRequest']['branch_id']) && $this->data['ChangeAdditionDeletionRequest']['branch_id'] != -1) {
        $requestFrom = 'Branch';
    } elseif (isset($this->data['ChangeAdditionDeletionRequest']['department_id']) && $this->data['ChangeAdditionDeletionRequest']['department_id'] != -1) {
        $requestFrom = 'Department';
    } elseif (isset($this->data['ChangeAdditionDeletionRequest']['employee_id']) && $this->data['ChangeAdditionDeletionRequest']['employee_id'] != -1) {
        $requestFrom = 'Employee';
    } elseif (isset($this->data['ChangeAdditionDeletionRequest']['customer_id']) && $this->data['ChangeAdditionDeletionRequest']['customer_id'] != -1) {
        $requestFrom = 'Customer';
    } elseif (isset($this->data['ChangeAdditionDeletionRequest']['suggestion_form_id']) && $this->data['ChangeAdditionDeletionRequest']['suggestion_form_id'] != -1) {
        $requestFrom = 'SuggestionForm';
    } else {
        $requestFrom = 'Other';
    }
?>

<div id="changeAdditionDeletionRequests_ajax"> <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="changeAdditionDeletionRequests form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('Edit Change Request'); ?> 
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
                <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view', $this->request->params['pass'][0] . '.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?> </h4>
            <?php echo $this->Form->create('ChangeAdditionDeletionRequest', array('role' => 'form', 'class' => 'form')); ?> <?php echo $this->Form->input('id'); ?>
            <div class="row">
                <div class="col-md-12"><?php echo $this->Form->input('title', array()); ?></div>
                <div class="col-md-12"><?php echo $this->Form->input('request_from', array('default' => $requestFrom, 'options' => array('Branch' => __('Branch'), 'Department' => __('Department'), 'Employee' => __('Employee'), 'Customer' => __('Customer'), 'SuggestionForm' => __('Suggestion'), 'Other' => __('Other')), 'type' => 'radio')); ?></div>
                <div class="col-md-6 hidediv" id="Branch"><?php echo $this->Form->input('branch_id', array('style' => 'width:100%', 'label' => __('Branch'))); ?></div>
                <div class="col-md-6 hidediv" id="Department"><?php echo $this->Form->input('department_id', array('style' => 'width:100%', 'label' => __('Department'))); ?></div>
                <div class="col-md-6 hidediv" id="Employee"><?php echo $this->Form->input('employee_id', array('style' => 'width:100%')); ?></div>
                <div class="col-md-6 hidediv" id="Customer"><?php echo $this->Form->input('customer_id', array('style' => 'width:100%')); ?></div>
                <div class="col-md-6 hidediv" id="SuggestionForm"><?php echo $this->Form->input('suggestion_form_id', array('style' => 'width:100%', 'label' => __('Suggestion Form'))); ?></div>
                <div class="col-md-6 hidediv" id="Other">
                    <?php
                        $capaCheck = strpos($this->data['ChangeAdditionDeletionRequest']['others'], "CAPA Number: ");
                        if ($capaCheck == 0) {
                            $capaNumber = str_replace("CAPA Number: ", '', $this->data['ChangeAdditionDeletionRequest']['others']);
                            ?>
                            <label id="ChangeAdditionDeletionRequestOthers">CAPA Number: </label>
                            <?php echo $capaNumber; ?>
                            <?php echo $this->Form->hidden('others'); ?>
                            <?php
                        } else {
                            echo $this->Form->input('others', array('label' => __('Other')));
                        }
                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                        echo "<label>Master List of Formats: </label><p>" . $this->data['MasterListOfFormat']['title'] . "</p>";
                        echo $this->Form->hidden('master_list_of_format');
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="current_details"></div>
            </div>
            <div class="row">
                <div class="col-md-12"><?php echo $this->Form->input('reason_for_change'); ?><span class="help"><?php echo __('Short description of changes required & reason for document change'); ?></span>
                    <?php echo $this->Form->hidden('request_details'); ?>
                </div>

                <?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?> <?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?>
            </div>
            <div class="row"><br />
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><?php echo __('After changes were accepted'); ?></h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            $options = array(0 => 'Rejected', 1 => 'Accepted', 2 => 'Open');
                            echo $this->Form->input('document_change_accepted', array('options' => $options, 'default' => 2, 'type' => 'radio', 'label' => __('Document Change Accepted')));
                            ?>
                        </div>
                        <div class="col-md-6"><?php echo $this->Form->input('meeting_id', array('label' => __('Approved in meeting'))); ?></div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-12">
                            <dl>
                                <dt><?php echo __('Title'); ?></dt>
                                <dd><?php echo $this->data['MasterListOfFormat']['title']; ?></dd>
                                <dt><?php echo __('Document Number'); ?></dt>
                                <dd><?php echo $this->data['MasterListOfFormat']['document_number']; ?></dd>
                                <dt><?php echo __('Issue Number'); ?></dt>
                                <dd><?php echo $this->data['MasterListOfFormat']['issue_number']; ?></dd>
                                <dt><?php echo __('Revision Number'); ?></dt>
                                <dd><?php echo $this->data['MasterListOfFormat']['revision_number']; ?></dd>
                                <dt><?php echo __('Revision Date'); ?></dt>
                                <dd><?php echo $this->data['MasterListOfFormat']['revision_date']; ?></dd>
                            </dl>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <?php echo $this->Form->hidden('MasterListOfFormat.id'); ?> <?php echo $this->Form->hidden('MasterListOfFormat.title', array('value' => $this->data['MasterListOfFormat']['title'])); ?> <?php echo $this->Form->hidden('MasterListOfFormat.document_number'); ?> <?php echo $this->Form->hidden('MasterListOfFormat.issue_number'); ?> <?php echo $this->Form->hidden('MasterListOfFormat.revision_number'); ?> <?php echo $this->Form->hidden('MasterListOfFormat.revision_date'); ?> <?php echo $this->Form->hidden('MasterListOfFormat.proposed_document_changes'); ?> <?php echo $this->Form->hidden('MasterListOfFormat.proposed_work_instruction_changes'); ?> <?php echo $this->Form->hidden('MasterListOfFormat.prepared_by'); ?> <?php echo $this->Form->hidden('MasterListOfFormat.approved_by'); ?> <?php echo $this->Form->hidden('MasterListOfFormat.system_table_id', array('value' => $this->data['MasterListOfFormat']['system_table_id'])); ?>
                        <div class="col-md-8"><?php echo $this->Form->input('NewMasterListOfFormat.title', array('value' => $this->data['MasterListOfFormat']['title'])); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('NewMasterListOfFormat.document_number', array('value' => $this->data['MasterListOfFormat']['document_number'])); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('NewMasterListOfFormat.issue_number', array('value' => $this->data['MasterListOfFormat']['issue_number'])); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('NewMasterListOfFormat.revision_number', array('value' => $this->data['MasterListOfFormat']['revision_number'] + 1)); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('NewMasterListOfFormat.revision_date', array('value' => date('Y-m-d'))); ?></div>
                        <div class="col-md-12">
                            <div class="alert alert-info"> <?php echo '<strong>' . __('Note : ') . '</strong>' . __('If Document changes required additional database fields or change in functionality, send us the new requirements by adding details below.'); ?> </div>
                        </div>
                        <div class="col-md-12"><?php echo $this->Form->input('flinkiso_functionality_change_required', array('label' => 'FlinkISO functionality change required', 'type' => 'checkbox')); ?></div>
                        <div class="col-md-12"> <?php echo $this->Form->input('flinkiso_functionality_change_details', array('label' => false)); ?> <span class="help-text"><?php echo __('Add your new requirement here. If you check "flinkiso functionality change required", then these changes will be sent to FlinkISO customisation team for future review.'); ?></span> </div>
                    </div>
                </div>
            </div>
                <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }
            ?>
            <?php
            echo $this->Form->hidden('History.pre_post_values', array('value' => json_encode($this->data)));
            echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'id' => 'submit_id'));
            ?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>
        </div>
        <script>
            $("[name*='date']").datepicker({
                changeMonth: true,
                changeYear: true,
                format: 'yyyy-mm-dd',
      autoclose:true,
                'showTimepicker': false,
            }).attr('readonly', 'readonly');

            $(document).ready(function () {
                $('.hidediv').hide();
                $('#<?php echo $requestFrom; ?>').show();

                var $requestFrom = $('input:radio[name="data[ChangeAdditionDeletionRequest][request_from]"]:checked').val();

                if ($requestFrom != 'Other') {
                    $('#ChangeAdditionDeletionRequest' + $requestFrom + 'Id').rules('add', {
                        greaterThanZero: true
                    });
                    $('#ChangeAdditionDeletionRequest' + $requestFrom + 'Id').change(function () {
                        if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                            $(this).next().next('label').remove();
                        }
                    });
                } else {
                    $('#ChangeAdditionDeletionRequestOthers').rules('add', {
                        required: true
                    });
                }

                $("[name='data[ChangeAdditionDeletionRequest][request_from]']").click(function () {
                    $val = this.value;
                    $('.hidediv').hide();
                    $('#ChangeAdditionDeletionRequestBranchId').val(0).trigger('chosen:updated');
                    $('#ChangeAdditionDeletionRequestDepartmentId').val(0).trigger('chosen:updated');
                    $('#ChangeAdditionDeletionRequestEmployeeId').val(0).trigger('chosen:updated');
                    $('#ChangeAdditionDeletionRequestCustomerId').val(0).trigger('chosen:updated');
                    $('#ChangeAdditionDeletionRequestSuggestionFormId').val(0).trigger('chosen:updated');
                    $('#ChangeAdditionDeletionRequestOthers').val('');

                    $('.hidediv').find('select').prop('value', -1);
                    $('#' + $val).toggle();
                    $('#ChangeAdditionDeletionRequest' + $val + 'Id_chosen').width('100%');

                    $('#ChangeAdditionDeletionRequestBranchId').rules('remove');
                    $('#ChangeAdditionDeletionRequestDepartmentId').rules('remove');
                    $('#ChangeAdditionDeletionRequestEmployeeId').rules('remove');
                    $('#ChangeAdditionDeletionRequestCustomerId').rules('remove');
                    $('#ChangeAdditionDeletionRequestSuggestionFormId').rules('remove');
                    $('#ChangeAdditionDeletionRequestOthers').rules('remove');

                    if ($val != 'Other') {
                        $('#ChangeAdditionDeletionRequest' + $val + 'Id').rules('add', {
                            greaterThanZero: true
                        });
                        $('#ChangeAdditionDeletionRequest' + $val + 'Id').change(function () {
                            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                                $(this).next().next('label').remove();
                            }
                        });
                    } else {
                        $('#ChangeAdditionDeletionRequestOthers').rules('add', {
                            required: true
                        });
                    }
                });
            });
        </script>
        <div class="col-md-4">
            <p><?php echo $this->element('document_revisions'); ?></p>
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#changeAdditionDeletionRequests_ajax'))); ?> <?php echo $this->Js->writeBuffer(); ?> </div>
<script>
    $().ready(function () {
        $('#current_details').load("<?php echo Router::url('/', true); ?>/master_list_of_formats/ajax_view/<?php echo $this->data['MasterListOfFormat']['id']; ?>/1/<?php echo $this->data['ChangeAdditionDeletionRequest']['id']; ?>");
            });
</script>
<?php } ?>
