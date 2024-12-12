<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {
            if ($(element).attr('name') == 'data[MasterListOfFormat][prepared_by]' ||
                    $(element).attr('name') == 'data[MasterListOfFormat][approved_by]' ||
                    $(element).attr('name') == 'MasterListOfFormatBranch.branch_id[]' ||
                    $(element).attr('name') == 'MasterListOfFormatDepartment.department_id[]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
    });
    function distributionlist(n){
            // alert('aa');
            var branches = $("#MasterListOfFormatBranchBranchId").val();
            var departments = $("#MasterListOfFormatDepartmentDepartmentId").val();
            $("#distribute").load("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/mlfuserlist/"+branches+"/"+departments+"/format_id:<?php echo $this->data['MasterListOfFormat']['id']?>");

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
        
        $( "#usersharetabs" ).tabs();
        for (var i in CKEDITOR.instances) {
                
                CKEDITOR.instances[i].on('change', function() { 
                       $("#save_copy").prop('checked', true);
                 });
                
        }

        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#MasterListOfFormatApproveForm').validate({
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
            $("#submit-indicator").hide();
            $("#submit_id").click(function(){
             if($('#MasterListOfFormatApproveForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#MasterListOfFormatApproveForm").submit();
             }

        });
        $('#MasterListOfFormatPreparedBy').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#MasterListOfFormatApprovedBy').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
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

        $("#MasterListOfFormatRevisionDate").datepicker("setDate", new Date());
    });
</script>

<div id="masterListOfFormats_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="masterListOfFormats form col-md-8">
            <h4><?php echo __('Approve Master List Of Format'); ?> <?php echo $this->Html->link(__('List'), array('controller'=>'dashboards','action' => 'mr'), array('id' => 'list', 'class' => 'label btn-info')); ?></h4>

            <?php echo $this->Form->create('MasterListOfFormat', array('role' => 'form', 'class' => 'form')); ?>
            <fieldset>
                <div class="row">
                    <div class="col-md-4"><?php echo $this->Form->input('document_number'); ?></div>
                    <div class="col-md-8"><?php echo $this->Form->input('title'); ?><?php echo $this->Form->hidden('pre_title',array('default'=>$this->request->data['MasterListOfFormat']['title'])); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('standard_id'); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('master_list_of_format_category_id'); ?></div>
                    <div class="col-md-12"><?php echo $this->Form->input('clause_id'); ?></div>
                    <div class="col-md-3"><?php echo $this->Form->input('date_created'); ?></div>
                    <div class="col-md-3"><?php echo $this->Form->hidden('issue_number'); ?><br /><strong>Issue # : </strong><?php echo $this->request->data['MasterListOfFormat']['issue_number']?></div>
                    <div class="col-md-3"><?php echo $this->Form->hidden('revision_number'); ?><br /><strong>Revision # : </strong><?php echo $this->request->data['MasterListOfFormat']['revision_number']?></div>
                    <?php if($this->request->data['MasterListOfFormat']['revision_date'] == '1970-01-01'){
                        // unset($this->data['MasterListOfFormat']['revision_date']);
                        // unset($this->request->data['MasterListOfFormat']['revision_date']);
                        $this->request->data['MasterListOfFormat']['revision_date'] = '';
                    }?>
                    <div class="col-md-3"><?php echo $this->Form->input('revision_date'); ?></div>
                    <div class="col-md-3"><?php echo $this->Form->input('document_status'); ?></div>
                    <div class="col-md-9"><?php echo $this->Form->input('parent_document_id',array('label'=>'Parent Document')); ?></div>
                    <?php unset($parents[$this->data['MasterListOfFormat']['parent_document_id']]);?>
                    <div class="col-md-12">
                        <?php $linked =  json_decode($this->data['MasterListOfFormat']['linked_formats']);?>
                        <?php echo $this->Form->input('linked_formats',array('name'=>'data[MasterListOfFormat][linked_formats][]', 'options'=>$parents,'multiple','selected'=>array_values($linked))); ?></div>

                    <?php echo $this->Form->hidden('pre_document_details',array('value'=>$this->data['MasterListOfFormat']['document_details'])); ?>
                    <?php echo $this->Form->hidden('pre_work_instructions',array('value'=>$this->data['MasterListOfFormat']['work_instructions'])); ?>
                    <div class="col-md-12">
                    
                    <h5>Document Details <small>You can copy-paste your existing document details here below (any text format)</small></h5>
                    <textarea id="MasterListOfFormatDocumentDetails" name="data[MasterListOfFormat][document_details]"><?php echo $this->data['MasterListOfFormat']['document_details'] ?></textarea>
                    </div>
                    
                    <div class="col-md-12"><h3><?php echo 'Distribution'?></h3></div>
                    <div class="col-md-6"><?php echo $this->Form->input('MasterListOfFormatBranch.branch_id', array('onChange'=>'distributionlist(this.value)', 'name' => 'MasterListOfFormatBranch.branch_id[]', 'type' => 'select', 'multiple', 'options' => $PublishedBranchList, 'style' => 'width:100%', 'default' => $selected_branches)); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('MasterListOfFormatDepartment.department_id', array('onChange'=>'distributionlist(this.value)','name' => 'MasterListOfFormatDepartment.department_id[]', 'type' => 'select', 'multiple', 'options' => $PublishedDepartmentList, 'style' => 'width:100%', 'default' => $selected_depts)); ?></div>
                    
                    <div class="col-md-12">     
               <div id="distribute"> 
                <?php    
                
                if(count($selected_branches) > 0) { 
                if(isset($sel_users))$sel_users = json_decode($sel_users);
                else $sel_users = json_decode($this->data['MasterListOfFormat']['user_id']);
                    ?> 
                    <div class="">
                        <div id="usersharetabs" class="">                        
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
                                            'id' => 'MasterListOfFormat_'. $this->request->params["pass"][0].'-'.$i.'-Everyone',
                                            'options'=>array('all'=>0))) . '</div>';                                
                                        echo 
                                            "<div class='col-md-12' 
                                            id='".$key."_".$i."_check_".$this->request->params["pass"][0]."'>".$this->Form->input('MasterListOfFormat.user_id.'.$i.'.user_id',array(
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
                                $('#MasterListOfFormat_<?php echo $this->request->params["pass"][0]; ?>-<?php echo $i; ?>-Everyone').on('click', function(){
                                    $("#<?php echo $key ?>_<?php echo $i ?>_check_<?php echo $this->request->params['pass'][0]; ?>").find(':checkbox').prop('checked', this.checked);                           
                                });
                            </script>
                            <?php                   
                            $i++;
                            } 
                        }                                
                            ?>

                    </div>

                    <?php } ?>
                    </div>
                </div>


                    <div class="col-md-6 hide"><?php echo $this->Form->input('system_table_id'); ?></div>
                    <div class="col-md-6 hide"><?php echo $this->Form->input('archived'); ?>If the format is old please mark it as "Archived"</div>
                    <div class="col-md-12 hide">
                        <?php echo $this->Html->link('View Records', array('controller' => $this->data['SystemTable']['system_name']), array('class' => 'btn btn-info')); ?>
                    </div>
                </div>
                <div class="row hide">
                    <?php $options = array(0 => 'No', 1 => 'Yes'); ?>
                    <div class="col-md-6"><?php echo $this->Form->input('evidence_required', array('options' => $options, 'selected' => $this->data['SystemTable']['evidence_required'], 'style' => 'width:100%')); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('approvals_required', array('options' => $options, 'selected' => $this->data['SystemTable']['approvals_required'], 'style' => 'width:100%')); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><div class="box box-danger panel-body"><?php echo $this->Form->input('revision_update',array('id'=>'revision_update', 'label'=>'Update Revision Number?','type'=>'checkbox'));?></div></div>
                    <div class="col-md-6"><div class="box box-danger panel-body"><?php echo $this->Form->input('save_copy',array('id'=>'save_copy', 'label'=>'Save old copy?','type'=>'checkbox','checked'));?></div>
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
                echo $this->Form->input('id');
                echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
                echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id'));?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
                <?php echo $this->Form->end(); ?>
                <?php echo $this->Js->writeBuffer(); ?>
            </fieldset>
        </div>

<script>
    $("#MasterListOfFormatDateCreated").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly');
    $("#MasterListOfFormatRevisionDate").datepicker({
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
            <p><?php echo $this->element('document_revisions'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('controller'=>'dashboards','action' => 'mr','ajax'), array('async' => true, 'update' => '#masterListOfFormats_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>
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

    CKEDITOR.replace('MasterListOfFormatWorkInstructions', {
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
