<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<style type="text/css">
/*#cke_CompanyQcHeader{height: 300px !important}
.cke_contents{height: 180px !important}*/
</style>
<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {
            // if ($(element).attr('name') == 'data[MasterListOfFormat][prepared_by]' ||
            //         $(element).attr('name') == 'data[MasterListOfFormat][approved_by]' ||
            //         $(element).attr('name') == 'MasterListOfFormatBranch.branch_id[]' ||
            //         $(element).attr('name') == 'MasterListOfFormatDepartment.department_id[]') {
            //     $(element).next().after(error);
            // } else {
            //     $(element).after(error);
            // }
        },
    });

    $().ready(function() {
        // $( "#sharetabs" ).tabs();
        for (var i in CKEDITOR.instances) {
                
                CKEDITOR.instances[i].on('change', function() { 
                       $("#save_copy").prop('checked', true);
                 });
                
        }

        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#CompanyPdfHeaderForm').validate({
            rules: {
                // "MasterListOfFormatBranch.branch_id[]": {
                //     greaterThanZero: true,
                //     required: true,
                // },
                // "MasterListOfFormatDepartment.department_id[]": {
                //     greaterThanZero: true,
                //     required: true,
                // },
            }
        });
            $("#submit-indicator").hide();
            $("#submit_id").click(function(){
             if($('#CompanyPdfHeaderForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#CompanyPdfHeaderForm").submit();
             }
        });
        // $('#MasterListOfFormatPreparedBy').change(function() {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });
        // $('#MasterListOfFormatApprovedBy').change(function() {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });
        // $('#MasterListOfFormatBranchBranchId').change(function() {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });
        // $('#MasterListOfFormatDepartmentDepartmentId').change(function() {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });
    });
</script>
<div id="masterListOfFormats_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="masterListOfFormats form col-md-8">
            <h4><?php echo __('Edit Master List Of Format'); ?> <?php echo $this->Html->link(__('List'), array('controller'=>'dashboards','action' => 'mr'), array('id' => 'list', 'class' => 'label btn-info')); ?></h4>

            <?php echo $this->Form->create('Company', array('role' => 'form', 'class' => 'form')); ?>
            <fieldset>
                <div class="row">
                    <div class="col-md-12">
                    <?php // echo $this->Form->input('document_details'); ?>
                    <h5>PDF Header<small>You can add company logo and other required details</small></h5>
                    <textarea id="CompanyQcHeader" name="data[Company][qc_header]"><?php echo $pdf_header ?></textarea>
                    </div>                    
                </div>
                    
                <?php
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                ?>
                <?php 
                echo $this->Form->input('id');
                echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
                echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id'));?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
                <?php echo $this->Form->end(); ?>
                <?php echo $this->Js->writeBuffer(); ?>
            </fieldset>
        </div>

        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>            
        </div>
    </div>
    
</div>
<?php echo $this->Html->script(array('ckeditor/ckeditor')); ?>
<script type="text/javascript">
    CKEDITOR.replace('CompanyQcHeader', {
        filebrowserBrowseUrl: '<?php echo Router::url("/", true); ?>img/ckeditor/browse.php?dir=<?php echo WWW_ROOT ?>img/ckeditor/companies/header&path=<?php echo Router::url("/", true); ?>img/ckeditor/companies/header',
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
        height: 200,
        // An array of stylesheets to style the WYSIWYG area.
        // Note: it is recommended to keep your own styles in a separate file in order to make future updates painless.
        // contentsCss: [ '<?php echo Router::url("/", true); ?>css/contents.css', 'mystyles.css' ],
        // contentsCss: [ '<?php echo Router::url("/", true); ?>css/contents.css', '<?php echo Router::url("/", true); ?>css/mystyles.css' ],
        // This is optional, but will let us define multiple different styles for multiple editors using the same CSS file.
        bodyClass: 'document-editor',
        // Reduce the list of block elements listed in the Format dropdown to the most commonly used.
        format_tags: 'p;h1;h2;h3;pre',
        // Simplify the Image and Link dialog windows. The "Advanced" tab is not needed in most cases.
        extraPlugins: 'tableresize,lineheight,autosave,imagerotate',
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

    // CKEDITOR.replace('MasterListOfFormatWorkInstructions', {
    //     filebrowserBrowseUrl: '<?php echo Router::url("/", true); ?>img/ckeditor/browse.php?dir=<?php echo WWW_ROOT ?>img/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>&path=<?php echo Router::url("/", true); ?>img/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>',
    //     filebrowserUploadUrl: '<?php echo Router::url("/", true); ?><?php echo $this->request->params["controller"] ?>/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>',
    //     // Define the toolbar: http://docs.ckeditor.com/#!/guide/dev_toolbar
    //     // The full preset from CDN which we used as a base provides more features than we need.
    //     // Also by default it comes with a 3-line toolbar. Here we put all buttons in a single row.
    //     toolbar: [
    //         { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
    //         { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'CopyFormatting' ] },
    //         { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
    //         { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
    //         { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
    //         { name: 'insert', items: [ 'Image', 'Table','HorizontalRule','PageBreak'] },
    //         { name: 'tools', items: ['Radio','Checkbox','TextField','Textarea','Selection', '-', 'Maximize','Source' ] },
    //         '/',
    //         { name: 'styles', items: [ 'Format', 'Font', 'FontSize', 'lineheight'] },
    //         { name: 'links', items: [ 'Link', 'Unlink' ] },
    //         { name: 'editing', items: [ 'Scayt' ] },
    //         {name: 'document', items: ['Preview', '-', 'Templates']},
    //         ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            
    //     ],
    //     // Since we define all configuration options here, let's instruct CKEditor to not load config.js which it does by default.
    //     // One HTTP request less will result in a faster startup time.
    //     // For more information check http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-customConfig
    //     customConfig: '',
    //     // Sometimes applications that convert HTML to PDF prefer setting image width through attributes instead of CSS styles.
    //     // For more information check:
    //     //  - About Advanced Content Filter: http://docs.ckeditor.com/#!/guide/dev_advanced_content_filter
    //     //  - About Disallowed Content: http://docs.ckeditor.com/#!/guide/dev_disallowed_content
    //     //  - About Allowed Content: http://docs.ckeditor.com/#!/guide/dev_allowed_content_rules
    //     disallowedContent: 'img{width,height,float}',
    //     extraAllowedContent: 'img[width,height,align]',
    //     // Enabling extra plugins, available in the full-all preset: http://ckeditor.com/presets-all
    //     extraPlugins: 'tableresize,lineheight,autosave,imagerotate,pastefromexcel,htmlbuttons,forms,fakeobjects',
    //     /*********************** File management support ***********************/
    //     // In order to turn on support for file uploads, CKEditor has to be configured to use some server side
    //     // solution with file upload/management capabilities, like for example CKFinder.
    //     // For more information see http://docs.ckeditor.com/#!/guide/dev_ckfinder_integration
    //     // Uncomment and correct these lines after you setup your local CKFinder instance.
    //     // filebrowserBrowseUrl: 'http://example.com/ckfinder/ckfinder.html',
    //     // filebrowserUploadUrl: 'http://example.com/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    //     /*********************** File management support ***********************/
    //     // Make the editing area bigger than default.
    //     height: 800,
    //     // An array of stylesheets to style the WYSIWYG area.
    //     // Note: it is recommended to keep your own styles in a separate file in order to make future updates painless.
    //     // contentsCss: [ '<?php echo Router::url("/", true); ?>css/contents.css', 'mystyles.css' ],
    //     contentsCss: [ '<?php echo Router::url("/", true); ?>css/contents.css', '<?php echo Router::url("/", true); ?>css/mystyles.css' ],
    //     // This is optional, but will let us define multiple different styles for multiple editors using the same CSS file.
    //     bodyClass: 'document-editor',
    //     // Reduce the list of block elements listed in the Format dropdown to the most commonly used.
    //     format_tags: 'p;h1;h2;h3;pre',
    //     // Simplify the Image and Link dialog windows. The "Advanced" tab is not needed in most cases.
    //     removeDialogTabs: 'image:advanced;link:advanced',
    //     enterMode:2,forceEnterMode:false,shiftEnterMode:1,
    //     // Define the list of styles which should be available in the Styles dropdown list.
    //     // If the "class" attribute is used to style an element, make sure to define the style for the class in "mystyles.css"
    //     // (and on your website so that it rendered in the same way).
    //     // Note: by default CKEditor looks for styles.js file. Defining stylesSet inline (as below) stops CKEditor from loading
    //     // that file, which means one HTTP request less (and a faster startup).
    //     // For more information see http://docs.ckeditor.com/#!/guide/dev_styles
    //     stylesSet: [
    //         /* Inline Styles */
    //         { name: 'Marker', element: 'span', attributes: { 'class': 'marker' } },
    //         { name: 'Cited Work', element: 'cite' },
    //         { name: 'Inline Quotation', element: 'q' },
    //         /* Object Styles */
    //         {
    //             name: 'Special Container',
    //             element: 'div',
    //             styles: {
    //                 padding: '5px 10px',
    //                 background: '#eee',
    //                 border: '1px solid #ccc'
    //             }
    //         },
    //         {
    //             name: 'Compact table',
    //             element: 'table',
    //             attributes: {
    //                 cellpadding: '5',
    //                 cellspacing: '0',
    //                 border: '1',
    //                 bordercolor: '#ccc'
    //             },
    //             styles: {
    //                 'border-collapse': 'collapse'
    //             }
    //         },
    //         { name: 'Borderless Table', element: 'table', styles: { 'border-style': 'hidden', 'background-color': '#E6E6FA' } },
    //         { name: 'Square Bulleted List', element: 'ul', styles: { 'list-style-type': 'square' } }
    //     ]
    // });


</script>
