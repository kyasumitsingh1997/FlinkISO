<div class="alert alert-warning">This change request is for evidence/other documents only. To add change requests for quality documents, click <?php echo $this->Html->link('here',array('controller'=>'change_addition_deletion_requests','action'=>'lists'));?>.</div>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min','ckeditor/ckeditor')); ?>
<?php echo $this->fetch('script');?>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {
            if ($(element).attr('name') == 'data[ChangeAdditionDeletionRequest][prepared_by]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][approved_by]' ||                    
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][branch_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][department_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][employee_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][suggestion_form_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][customer_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function(form) {
            $('#ChangeAdditionDeletionRequestProposedDocumentChange').val(CKEDITOR.instances.ChangeAdditionDeletionRequestProposedDocumentChange.getData());
            
                        if($('#ChangeAdditionDeletionRequestProposedDocumentChange').val() == '' ){
                             alert("Please enter proposed document changes");
                             return false;
                        }else{
                            $('#ChangeAdditionDeletionRequestProposedDocumentChanges').val($('#ChangeAdditionDeletionRequestProposedDocumentChange').val());
                        }

            $('#ChangeAdditionDeletionRequestCurrentDocumentDetail').val(CKEDITOR.instances.ChangeAdditionDeletionRequestCurrentDocumentDetail.getData());
            
                        if($('#ChangeAdditionDeletionRequestCurrentDocumentDetail').val() == '' ){
                             alert("Please enter current document details");
                             return false;
                        }else{
                            $('#ChangeAdditionDeletionRequestCurrentDocumentDetails').val($('#ChangeAdditionDeletionRequestCurrentDocumentDetail').val());
                        }            

            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_document_cr",
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
                    //alert(request.responseText);
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

        $('#ChangeAdditionDeletionRequestAddDocumentCrForm').validate({
            rules: {
                
                "data[ChangeAdditionDeletionRequest][branch_id]": {
                    greaterThanZero: true,
                },
                "data[ChangeAdditionDeletionRequest][prepared_by]": {
                    greaterThanZero: true,
                },
            }
        });
        $('#ChangeAdditionDeletionRequestPreparedBy').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

        
        $('#ChangeAdditionDeletionRequestBranchId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ChangeAdditionDeletionRequestDepartmentId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ChangeAdditionDeletionRequestEmployeeId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ChangeAdditionDeletionRequestCustomerId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ChangeAdditionDeletionRequestSuggestionFormId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        
        $('#ChangeAdditionDeletionRequestOthers').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

    	functionalityChangeReq ();
	$('#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeRequired').change(function() {
	    functionalityChangeReq ();
	});
    });

    function functionalityChangeReq () {
	if ($('#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeRequired').prop('checked') == false) {
	    $("#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeDetails").prop("disabled", true);
	    $("#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeDetails").val('');
	} else {
	    $("#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeDetails").prop("disabled", false);
	}
    }

    
</script>

<div id="changeAdditionDeletionRequests_ajax"> <?php echo $this->Session->flash(); ?>
	<div class="nav">
        <div class="changeAdditionDeletionRequests form col-md-8">
            <h3><?php echo __('Document Details'); ?></h3>
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
                <td><?php echo __('Prepared By'); ?></td><td><?php echo $file['PreparedBy']['name']; ?></td>
                <td><?php echo __('Approved By'); ?></td><td><?php echo $file['ApprovedBy']['name']; ?></td>
            </tr>
            <tr>
                <td><?php echo __('Created'); ?></td><td><?php echo $file['FileUpload']['created']; ?></td>
                <td><?php echo __('Format'); ?></td><td><?php echo ($file['MasterListOfFormat']['title']?$file['MasterListOfFormat']['title']:'--' ); ?></td>
            </tr>
        </table>
        <div class="row">
            <!-- <div class="col-md-12"> -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#currentdocumentdetails" aria-controls="currentdocumentdetails" role="tab" data-toggle="tab"><?php echo __('Current Document Details'); ?></a></li>
                    <li role="presentation"><a href="#proposedchanges" aria-controls="proposedchanges" role="tab" data-toggle="tab"><?php echo __('Proposed Document Changes'); ?></a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="currentdocumentdetails">
                        <div class="note text-info">Open the document and select and copy paste the contect you would like to change below.</div>
                            <textarea name="data[ChangeAdditionDeletionRequest][current_document_detail]" id="ChangeAdditionDeletionRequestCurrentDocumentDetail" >
                                <?php echo htmlspecialchars_decode($file['FileUpload']['file_content']) ?>
                            </textarea>                        
                    </div>
                    <div role="tabpanel" class="tab-pane" id="proposedchanges">
                        <div class="note text-info"><?php echo __('Add changed contect below'); ?></div>
                            <textarea name="data[ChangeAdditionDeletionRequest][proposed_document_change]" id="ChangeAdditionDeletionRequestProposedDocumentChange" >                        
                            </textarea>                        
                    </div>        
                </div>
            <!-- </div> -->
        </div>
               
			
			<?php echo $this->Form->create('ChangeAdditionDeletionRequest', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
			<div class="row">
                <div class="col-md-12"><?php echo $this->Form->input('title', array('value'=>'Change Request For ' . $file['FileUpload']['name'])); ?></div>
				<div class="col-md-12"><?php echo $this->Form->input('request_from', array('default' => 'Branch', 'options' => array('Branch' => __('Branch'), 'Department' => __('Department'), 'Employee' => __('Employee'), 'Customer' => __('Customer'), 'SuggestionForm' => __('Suggestion'),'Other' => __('Other')), 'type' => 'radio')); ?></div>
				<div class="col-md-6 hidediv" id="Branch"><?php echo $this->Form->input('branch_id', array('style' => 'width:100%', 'label' => __('Branch'))); ?></div>
				<div class="col-md-6 hidediv" id="Department"><?php echo $this->Form->input('department_id', array('style' => 'width:100%', 'label' => __('Department'))); ?></div>
				<div class="col-md-6 hidediv" id="Employee"><?php echo $this->Form->input('employee_id', array('style' => 'width:100%')); ?></div>
				<div class="col-md-6 hidediv" id="Customer"><?php echo $this->Form->input('customer_id', array('style' => 'width:100%')); ?></div>
				<div class="col-md-6 hidediv" id="SuggestionForm"><?php echo $this->Form->input('suggestion_form_id', array('style' => 'width:100%', 'label' => __('Suggestion Form'))); ?></div>
                

				<div class="col-md-6 hidediv" id="Other"><?php echo $this->Form->input('others', array('label' => __('Other'))); ?></div>
				
			</div>
			<div class="row">
				
			</div>
			<div class="row">
				<div class="col-md-12 " id="reason_for_change"><?php echo $this->Form->input('reason_for_change'); ?>
                    <span class="help"><?php echo __('Short description of changes required & reason for document change'); ?></span>
                </div>
				<?php
                                    echo $this->Form->hidden('current_document_details');
                                    echo $this->Form->hidden('proposed_document_changes');
                                    echo $this->Form->hidden('file_upload_id',array('value'=>$file['FileUpload']['id']));
                                    echo $this->Form->hidden('meeting_id');
                                    echo $this->Form->hidden('branchid', array('value' => $this->Session->read('User.branch_id')));
                                    echo $this->Form->hidden('departmentid', array('value' => $this->Session->read('User.department_id')));
                                    echo $this->Form->hidden('master_list_of_format_id', array('value' => $documentDetails['MasterListOfFormat']['id']));
                                    echo $this->Form->hidden('master_list_of_format', array('value' => $file['FileUpload']['master_list_of_format_id'])); ?>
			</div>
			<?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }
            ?>
			<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#changeAdditionDeletionRequests_ajax', 'async' => 'false','id'=>'submit_id')); ?> <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?> <?php echo $this->Form->end(); ?> 
            <?php echo $this->Js->writeBuffer(); ?> </div>
		<script>
    $("[name*='date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly');

    $(document).ready(function() {
        $('.hidediv').hide();
        $('#Branch').show();

        $("[name='data[ChangeAdditionDeletionRequest][request_from]']").click(function() {            
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

            $('#ChangeAdditionDeletionRequestBranchId').next().next('label').remove();
            $('#ChangeAdditionDeletionRequestBranchId').val(0).trigger('chosen:updated');

            if ($val != 'Other') {
                $('#ChangeAdditionDeletionRequest' + $val + 'Id').rules('add', {
                    greaterThanZero: true
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
			<p><?php echo $this->element('helps'); ?></p>
		</div>
	</div>


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

<?php
    // $filer = $fullPath;
    // $filer = new File($fullPath);
    // $contents = $filer->read();
    // // print_r($contents);
    // $filer->close(); // Be sure to close the file when you're done
?>
</div>
<script type="text/javascript">
// CKEDITOR.instances.ChangeAdditionDeletionRequestCurrentDocumentDetail.on("instanceReady", function(event){      
//           CKEDITOR.instances.ChangeAdditionDeletionRequestCurrentDocumentDetail.setData('<?php echo $contents;?>');
//         });
</script>
