<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<style type="text/css">
#MasterListOfFormatDocumentStatus_chosen{min-width: auto !important}
</style>
<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {
            if (    $(element).attr('name') == 'MasterListOfFormatBranch.branch_id[]' ||
                    $(element).attr('name') == 'MasterListOfFormatDepartment.department_id[]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function(form) {
            CKEDITOR.instances['MasterListOfFormatDocumentDetails'].updateElement();
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
                    //alert(request.responseText);
                    alert('Action failed!');
                }
            });
        }
    });
    function distributionlist(n){

            var branches = $("#MasterListOfFormatBranchBranchId").val();
            var departments = $("#MasterListOfFormatDepartmentDepartmentId").val();
            $("#distribute").load("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/mlfuserlist/"+branches+"/"+departments);

        }

    $().ready(function() {

        

        $("#MasterListOfFormatStandardId").change(function(){
            $.ajax({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_categories/" + $('#MasterListOfFormatStandardId').val(),
                success: function(data, result) {                    
                    $('#MasterListOfFormatMasterListOfFormatCategoryId').find('option').remove().end().append(data).trigger('chosen:updated');
                }
            });
            $.ajax({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_clauses/" + $('#MasterListOfFormatStandardId').val(),
                success: function(data, result) {                    
                    $('#MasterListOfFormatClauseId').find('option').remove().end().append(data).trigger('chosen:updated');
                }
            });
        });


        $("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#MasterListOfFormatAddAjaxForm').validate({
            rules: {
                "MasterListOfFormatBranch.branch_id[]": {
                    greaterThanZero: true,
                    required: true,
                },
                "MasterListOfFormatDepartment.department_id[]": {
                    greaterThanZero: true,
                    required: true,
                },
            }
        });
        
        $('#MasterListOfFormatBranchBranchId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#MasterListOfFormatDepartmentDepartmentId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

        $("#MasterListOfFormatDateCreated").datepicker("setDate", new Date());
    });
</script>
<?php echo $this->Html->script(array('ckeditor/ckeditor')); ?>
<script type="text/javascript">
    CKEDITOR.replace('MasterListOfFormatDocumentDetails', {
        filebrowserBrowseUrl: '<?php echo Router::url("/", true); ?>img/ckeditor/browse.php?dir=<?php echo WWW_ROOT ?>img/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>&path=<?php echo Router::url("/", true); ?>img/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>',
        filebrowserUploadUrl: '<?php echo Router::url("/", true); ?><?php echo $this->request->params["controller"] ?>/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>',
        // Define the toolbar: http://docs.ckeditor.com/#!/guide/dev_toolbar
        // The full preset from CDN which we used as a base provides more features than we need.
        // Also by default it comes with a 3-line toolbar. Here we put all buttons in a single row.
        toolbar: [
            { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'CopyFormatting' ] },
            { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
            { name: 'insert', items: [ 'Image', 'Table','HorizontalRule','PageBreak'] },
            { name: 'tools', items: ['Radio','Checkbox','TextField','Textarea','Selection', '-', 'Maximize','Source' ] },
            '/',
            { name: 'styles', items: [ 'Format', 'Font', 'FontSize', 'lineheight'] },
            { name: 'links', items: [ 'Link', 'Unlink' ] },
            { name: 'editing', items: [ 'Scayt' ] },
            {name: 'document', items: ['Preview', '-', 'Templates']},
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            
        ],
        // Since we define all configuration options here, let's instruct CKEditor to not load config.js which it does by default.
        // One HTTP request less will result in a faster startup time.
        // For more information check http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-customConfig
        customConfig: '',
        // Sometimes applications that convert HTML to PDF prefer setting image width through attributes instead of CSS styles.
        // For more information check:
        //  - About Advanced Content Filter: http://docs.ckeditor.com/#!/guide/dev_advanced_content_filter
        //  - About Disallowed Content: http://docs.ckeditor.com/#!/guide/dev_disallowed_content
        //  - About Allowed Content: http://docs.ckeditor.com/#!/guide/dev_allowed_content_rules
        disallowedContent: 'img{width,height,float}',
        extraAllowedContent: 'img[width,height,align]',
        // Enabling extra plugins, available in the full-all preset: http://ckeditor.com/presets-all
        extraPlugins: 'tableresize,lineheight,autosave,imagerotate,pastefromexcel,htmlbuttons,forms,fakeobjects',
        /*********************** File management support ***********************/
        // In order to turn on support for file uploads, CKEditor has to be configured to use some server side
        // solution with file upload/management capabilities, like for example CKFinder.
        // For more information see http://docs.ckeditor.com/#!/guide/dev_ckfinder_integration
        // Uncomment and correct these lines after you setup your local CKFinder instance.
        // filebrowserBrowseUrl: 'http://example.com/ckfinder/ckfinder.html',
        // filebrowserUploadUrl: 'http://example.com/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        /*********************** File management support ***********************/
        // Make the editing area bigger than default.
        height: 800,
        // An array of stylesheets to style the WYSIWYG area.
        // Note: it is recommended to keep your own styles in a separate file in order to make future updates painless.
        // contentsCss: [ '<?php echo Router::url("/", true); ?>css/contents.css', 'mystyles.css' ],
        contentsCss: [ '<?php echo Router::url("/", true); ?>css/contents.css', '<?php echo Router::url("/", true); ?>css/mystyles.css' ],
        // This is optional, but will let us define multiple different styles for multiple editors using the same CSS file.
        bodyClass: 'document-editor',
        // Reduce the list of block elements listed in the Format dropdown to the most commonly used.
        format_tags: 'p;h1;h2;h3;pre',
        // Simplify the Image and Link dialog windows. The "Advanced" tab is not needed in most cases.
        removeDialogTabs: 'image:advanced;link:advanced',
        enterMode:2,forceEnterMode:false,shiftEnterMode:1,
        // Define the list of styles which should be available in the Styles dropdown list.
        // If the "class" attribute is used to style an element, make sure to define the style for the class in "mystyles.css"
        // (and on your website so that it rendered in the same way).
        // Note: by default CKEditor looks for styles.js file. Defining stylesSet inline (as below) stops CKEditor from loading
        // that file, which means one HTTP request less (and a faster startup).
        // For more information see http://docs.ckeditor.com/#!/guide/dev_styles
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

<div id="masterListOfFormats_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav">
        <div class="masterListOfFormats form col-md-8">
            <h4><?php echo __('Add New Document'); ?></h4>
            <?php echo $this->Form->create('MasterListOfFormat', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>

            <div class="row">
                <div class="col-md-4"><?php echo $this->Form->input('document_number'); ?></div>
                <div class="col-md-8"><?php echo $this->Form->input('title'); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('standard_id'); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('master_list_of_format_category_id'); ?></div>
                <div class="col-md-12"><?php echo $this->Form->input('clause_id'); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('date_created'); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('issue_number',array('label'=>'Issue #','default'=>1)); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('revision_number',array('label'=>'Revision #','default'=>0)); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('revision_date'); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('document_status',array('default'=>0)); ?></div>
                <div class="col-md-9"><?php echo $this->Form->input('parent_document_id',array('label'=>'Parent Document')); ?></div>
                <div class="col-md-12"><?php echo $this->Form->input('linked_formats',array('options'=>$parents,'multiple')); ?></div>
                <div class="col-md-12">
                    <h5>Document Details <small>You can copy-paste your existing document details here below (any text/image format)</small></h5>
                    <?php  echo $this->Form->input('document_details',array('label'=>false,'div'=>false)); ?>                    
                    <!-- <textarea id="MasterListOfFormatDocumentDetails" name="data[MasterListOfFormat][document_details]"></textarea> -->
                    </div>
                    
                <div class="col-md-12"><h4><?php echo __('Distribution/ Copy Holders');?></h4></div>

                
                <div class="col-md-6"><?php echo $this->Form->input('MasterListOfFormatBranch.branch_id', array('onChange'=>'distributionlist(this.value)', 'name' => 'MasterListOfFormatBranch.branch_id[]', 'type' => 'select', 'multiple', 'options' => $PublishedBranchList, 'style' => 'width:100%')); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('MasterListOfFormatDepartment.department_id', array('onChange'=>'distributionlist(this.value)','name' => 'MasterListOfFormatDepartment.department_id[]', 'type' => 'select', 'multiple', 'options' => $PublishedDepartmentList, 'style' => 'width:100%')); ?></div>
                
                <div class="col-md-12">
                    <div id="distribute"></div>
                </div>
                
                <div class="col-md-6"><?php echo $this->Form->input('system_table_id'); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('archived'); ?> If the format is old please mark it as "Archived"</div>
                <?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?>
                <?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?>
                <?php echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id'])); ?>
            </div>

            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }
            ?>
            <?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#masterListOfFormats_ajax', 'async' => 'false','id'=>'submit_id')); ?>
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
</script>

        <div class="col-md-4">
            <ul class="list-group">
                <li class="list-group-item active"><h3>Using HTML Editor</h3></li>
                <li class="list-group-item">To remove word formatting, copy text from word to <strong>NOTEPAD</strong> first and then copy it into the editor.</li>
                <li class="list-group-item">Do not copy bulleted lists as it is. Copy it into the <strong>NOTEPAD</strong> and then remove the numbering/bullet dots and then paste each bullet/number like one-by-one</li>
                <li class="list-group-item">Use <strong>SHIFT+ENTER</strong> to add text under same bullet/number</li>
                <li class="list-group-item">For tables, once you copy the tables, right click on table and click Table Properties. Then add "100%" value in width textbox</li>
                <li class="list-group-item">You can also use : <a href="https://word2cleanhtml.com/" target="_blank">https://word2cleanhtml.com/</a> to get clean HTML from word</li>
            </ul>
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
</div>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
