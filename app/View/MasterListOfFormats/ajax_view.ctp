<div id="masterListOfFormats_ajax" class="col-md-12">
<?php echo $this->data['ChangeAdditionDeletionRequest']['proposed_document_changes'] ?>
  <table class="table table-responsive">
            <caption><h4><?php echo $masterListOfFormat['MasterListOfFormat']['title'];?></h4></caption>
    <tr>
      <td><strong><?php echo __('Document Number'); ?></strong></td>
      <td><?php echo h($masterListOfFormat['MasterListOfFormat']['document_number']); ?> &nbsp; </td>
      <td><strong><?php echo __('Issue Number'); ?></strong></td>
      <td><?php echo h($masterListOfFormat['MasterListOfFormat']['issue_number']); ?> &nbsp; </td>
                </tr>
                <tr>
      <td><strong><?php echo __('Revision Number'); ?></strong></td>
      <td><?php echo h($masterListOfFormat['MasterListOfFormat']['revision_number']); ?> &nbsp; </td>
      <td><strong><?php echo __('Revision Date'); ?></strong></td>
      <td><?php echo h($masterListOfFormat['MasterListOfFormat']['revision_date']); ?> &nbsp; </td>
                </tr>
                <tr>
      <td><strong><?php echo __('Prepared By'); ?></strong></td>
      <td><?php echo h($masterListOfFormat['PreparedBy']['name']); ?> &nbsp; </td>
      <td><strong><?php echo __('Approved By'); ?></strong></td>
      <td><?php echo h($masterListOfFormat['ApprovedBy']['name']); ?> &nbsp; </td>
                </tr>
                <tr>
      <td><strong><?php echo __('Archived'); ?></strong></td>
      <td><?php echo h($masterListOfFormat['MasterListOfFormat']['archived']) ? __('Yes') : __('No'); ?> &nbsp; </td>
      <td><strong><?php echo __('Publish'); ?></strong></td>
      <td><?php if($masterListOfFormat['MasterListOfFormat']['publish'] == 1) { ?>
        <span class="fa fa-check text-success"></span>
        <?php } else { ?>
        <span class="fa fa-ban text-danger"></span>
        <?php } ?>
        &nbsp;</td>
    </tr>
    <tr>
  <td><strong><?php echo __('Branch'); ?></strong></td>
      <td><?php echo $this->Html->link($masterListOfFormat['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $masterListOfFormat['BranchIds']['id'])); ?> &nbsp; </td>
      <td><strong><?php echo __('Department'); ?></strong></td>
      <td><?php echo $this->Html->link($masterListOfFormat['Department']['name'], array('controller' => 'departments', 'action' => 'view', $masterListOfFormat['Department']['id'])); ?> &nbsp; </td>
    </tr>
    <?php if(isset($masterListOfFormat['ChangeAdditionDeletionRequest'][0]) && $masterListOfFormat['ChangeAdditionDeletionRequest'][0]['document_change_accepted'] == 2){
    ?>
    <tr>
      <td><strong><?php echo __('Reason For Change'); ?></strong></td>
      <td><?php echo $masterListOfFormat['ChangeAdditionDeletionRequest'][0]['reason_for_change'] ?> &nbsp; </td>
                        <td><strong><?php echo __('Document Changes Status'); ?></strong></td>
                        <td>
                            <?php if($crs['ChangeAdditionDeletionRequest']['document_change_accepted'] == 0 ) { ?>
                                <span class="text-danger">Rejected</span>
                            <?php } else if($crs['ChangeAdditionDeletionRequest']['document_change_accepted'] == 1 ) { ?>
                                <span class="text-success">Accepted</span>
                            <?php } else if($crs['ChangeAdditionDeletionRequest']['document_change_accepted'] == 2 ) { ?>
                                <span class="text-warning">Under consideration</span>
                            <?php }?>
                        </td>
    </tr>
    <?php } ?>
  </table>
</div>
<?php if ($referCheck == 'CAPA' && isset($crs['ChangeAdditionDeletionRequest']['id'])) { ?>
    <div class="row">
        <div class="col-md-12 text-center">
            <a href="#" id="showCR" class="btn btn-lg btn-success"><?php echo __('View CR'); ?></a>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'showModal-indicator', 'style' => 'display:none;')); ?>
        </div>
    </div>
    <div class="modal fade" id="crModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Change Request Details
                        <?php if ($crs['ChangeAdditionDeletionRequest']['document_change_accepted'] == 0) { ?>
                            <small class="label label-danger">Rejected</small>
                        <?php } else if ($crs['ChangeAdditionDeletionRequest']['document_change_accepted'] == 1) { ?>
                            <small class="label laebl-success">Accepted</small>
                        <?php } else if ($crs['ChangeAdditionDeletionRequest']['document_change_accepted'] == 2) { ?>
                            <small class="label label-warning">Under consideration</small>
                        <?php } ?>
                    </h4>
                </div>
                <div class="modal-body" id="crDetails"></div>
                <div class="modal-footer">
                    <p><small></small></p>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script>
            $('#showCR').click(function(){
                $("#showModal-indicator").show();
                $('#crModal').modal();
                $('#crDetails').load('<?php echo Router::url('/', true); ?>change_addition_deletion_requests/view/<?php echo $crs['ChangeAdditionDeletionRequest']['id']; ?>/ajaxCAPAModal:true');
            });
            $('#crModal').on('hidden.bs.modal', function (e) {
                $("#showModal-indicator").hide();
            });
    </script>
    <style>
    #crModal .modal-dialog{width:70%; height:100%}
    </style>

<?php } else { ?>

    <div class="row">
        <div class="col-md-12 text-center">
            <a href="#" id="openModel" class="btn btn-lg btn-success"><?php echo __('Click here to view or amend changes'); ?></a>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'showModal-indicator', 'style' => 'display:none;')); ?>
        </div>
    </div>
    <div class="modal fade" id="crs">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">Document Details</h4>
          </div>
          <div class="modal-body">
                    <div role="tabpanel">
                            <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#document_details_tab" aria-controls="proposed_document_changes_tab" role="tab" data-toggle="tab"><strong><?php echo __('Document Changes'); ?></strong></a></li>
                                    <li role="presentation"><a href="#proposed_work_instructions_tab" aria-controls="proposed_work_instructions_tab" role="tab" data-toggle="tab"><strong><?php echo __('Work Instructions'); ?></strong></a></li>
                            </ul>
                            <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active panel panel-default panel-body" id="document_details_tab">
                                            <!-- <div class="panel-group" id="documentDetails" role="tablist" aria-multiselectable="true">
                                            <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingOne">
                                              <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#documentDetails" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                  <?php echo __('Current Document Details'); ?>
                                                </a>
                                              </h4>
                                            </div>
                                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                              <div class="panel-body">
                                                <?php
                                                      if($masterListOfFormat['MasterListOfFormat']['document_details']){
                                                               if(empty($masterListOfFormat['MasterListOfFormat']['document_details'])) echo "<p><div class='alert alert-danger'>You have not updated your document details yet. Click on edit to update document details</div></p>";
                                                               else echo $masterListOfFormat['MasterListOfFormat']['document_details'];
                                                       }else{
                                                              echo $crs['MasterListOfFormat']['document_details'];
                                                       }
                                              ?>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingTwo">
                                              <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#documentDetails" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                  <?php echo __('Proposed Document Details'); ?>
                                                </a>
                                              </h4>
                                            </div>
                                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                              <div class="panel-body">
                                                <textarea name="data[ChangeAdditionDeletionRequest][proposed_document_changes]" id="ChangeAdditionDeletionRequestProposedDocumentChanges" >
                                                  <?php if(isset($crs['ChangeAdditionDeletionRequest']['proposed_document_changes'])){
                                                        echo $crs['ChangeAdditionDeletionRequest']['proposed_document_changes'];
                                                    }else{
                                                      echo $masterListOfFormat['MasterListOfFormat']['document_details'];
                                                    }  ?>
                                                  </textarea>
                                              </div>
                                            </div>
                                          </div>
                                        </div> -->
                                        <textarea name="data[ChangeAdditionDeletionRequest][proposed_document_changes]" id="ChangeAdditionDeletionRequestProposedDocumentChanges" >
                                                  <?php if(isset($crs['ChangeAdditionDeletionRequest']['proposed_document_changes'])){
                                                        echo $crs['ChangeAdditionDeletionRequest']['proposed_document_changes'];
                                                    }else{
                                                      echo $masterListOfFormat['MasterListOfFormat']['document_details'];
                                                    }  ?>
                                                  </textarea>
                                    </div>
                                    <div role="tabpanel" class="tab-pane panel panel-default panel-body" id="proposed_work_instructions_tab">
                                            <!-- <div class="panel-group" id="workDetails" role="tablist" aria-multiselectable="true">
                                              <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingOneWork">
                                                  <h4 class="panel-title">
                                                    <a role="button" data-toggle="collapse" data-parent="#workDetails" href="#collapseOneWork" aria-expanded="true" aria-controls="collapseOne">
                                                      <?php echo __('Current Work Instructions'); ?>
                                                    </a>
                                                  </h4>
                                                </div>
                                                <div id="collapseOneWork" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOneWork">
                                                  <div class="panel-body">
                                                    <?php
                                                      if($masterListOfFormat['MasterListOfFormat']['work_instructions']){
                                                               if(empty($masterListOfFormat['MasterListOfFormat']['work_instructions'])) echo "<p><div class='alert alert-danger'>You have not updated your work instructions yet. Click on edit to update document details</div></p>";
                                                               else echo $masterListOfFormat['MasterListOfFormat']['work_instructions'];
                                                      }else{
                                                              echo $crs['MasterListOfFormat']['work_instructions'];
                                                      }
                                                    ?>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingTwoWork">
                                                  <h4 class="panel-title">
                                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#workDetails" href="#collapseTwoWork" aria-expanded="false" aria-controls="collapseTwo">
                                                      <?php echo __('Proposed Work Instructions'); ?>
                                                    </a>
                                                  </h4>
                                                </div>
                                                <div id="collapseTwoWork" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwoWork">
                                                  <div class="panel-body">
                                                    <textarea name="data[ChangeAdditionDeletionRequest][proposed_work_instruction_changes]" id="ChangeAdditionDeletionRequestProposedWorkInstructionChanges">
                                                      <?php if(isset($crs['ChangeAdditionDeletionRequest']['proposed_work_instruction_changes'])){
                                                            echo $crs['ChangeAdditionDeletionRequest']['proposed_work_instruction_changes'];
                                                        }else{
                                                          echo $masterListOfFormat['MasterListOfFormat']['work_instructions'];
                                                        }  ?>
                                                    </textarea>        
                                                  </div>
                                                </div>
                                              </div>
                                            </div> -->
                                            <textarea name="data[ChangeAdditionDeletionRequest][proposed_work_instruction_changes]" id="ChangeAdditionDeletionRequestProposedWorkInstructionChanges">
                                                      <?php if(isset($crs['ChangeAdditionDeletionRequest']['proposed_work_instruction_changes'])){
                                                            echo $crs['ChangeAdditionDeletionRequest']['proposed_work_instruction_changes'];
                                                        }else{
                                                          echo $masterListOfFormat['MasterListOfFormat']['work_instructions'];
                                                        }  ?>
                                                    </textarea>    
                                    </div>
                            </div>
                    </div>
          </div>
          <div class="modal-footer">
                    <p><small>Close the panel after making the changes, add "Reason for change" and save the form.</small></p>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script>
            $('#openModel').click(function(){
                $("#showModal-indicator").show();
                $('#crs').modal();
            });
            $('#crs').on('hidden.bs.modal', function (e) {
                $("#showModal-indicator").hide();
            });
    </script>
    <style>
    #crs .modal-dialog{width:98%; height:100%}
    </style>
    <?php echo $this->Html->script(array('ckeditor/ckeditor')); ?>
    <script type="text/javascript">
        CKEDITOR.replace('ChangeAdditionDeletionRequestProposedDocumentChanges', {
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
            { name: 'tools', items: [ 'Maximize','Source' ] },
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
        extraPlugins: 'tableresize,lineheight,autosave',
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

        CKEDITOR.replace('ChangeAdditionDeletionRequestProposedWorkInstructionChanges', {
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
            { name: 'tools', items: [ 'Maximize','Source' ] },
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
        extraPlugins: 'tableresize,lineheight,autosave',
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
    <script>
            $('#reason_for_change').removeClass('hide');
    </script>
<?php } ?>
