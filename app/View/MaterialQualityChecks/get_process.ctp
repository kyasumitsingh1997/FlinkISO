<?php echo $this->Html->script(array('ckeditor/ckeditor')); ?>
<?php echo $this->fetch('script'); ?>
<div id="material_QC_ajax<?php echo $i; ?>">
    <div class="">
        <div class="panel panel-default">
            <div class="panel-heading" id="panel<?php echo $i; ?>"><?php echo __('Step') . ' - ' . ($i); ?> <span class="text-danger glyphicon glyphicon-remove danger pull-right" style="font-size:20px;background:none"type="button" onclick='removeMaterialQualityCheckDiv(<?php echo $i; ?>)'></span></div>
            <div class="panel-body">
                <fieldset>
                    <div class="col-md-12"><?php echo $this->Form->input('MaterialQC.' . $i . '.name', array('label' => 'Name', 'placeholder' => 'Verify Document Accuracy & Completness.')); ?></div>
                    <div class="col-md-12"><?php echo $this->Form->input('MaterialQC.' . $i . '.details', array('label' => 'Details', 'type' => 'textarea', 'placeholder' => 'Document accuracy & completness must be verified after initial material inspection')); ?></div>
                    <div class="col-md-12">
                                        <h5>Quality Check Template <small>You can copy-paste your existing qc template here below (any text/image format)</small></h5>
                                        <?php  echo $this->Form->input('MaterialQC.' . $i . '.qc_template',array('label'=>false,'div'=>false,'type'=>'textarea')); ?>
                                    </div>
                    <div class="col-md-3"><?php echo $this->Form->input('MaterialQC.' . $i . '.active_status', array('label' => 'Active', 'type' => 'checkbox', 'checked' => true)); ?></div>
                     
                </fieldset>
            </div>
        </div>
    </div>
</div>
<?php $i++; ?>
<?php echo $this->Js->writeBuffer(); ?>
<script type="text/javascript">
    CKEDITOR.replace('MaterialQC<?php echo $i-1?>QcTemplate', {
        filebrowserBrowseUrl: '<?php echo Router::url("/", true); ?>img/ckeditor/browse.php?dir=<?php echo WWW_ROOT ?>img/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>&path=<?php echo Router::url("/", true); ?>img/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>',
        filebrowserUploadUrl: '<?php echo Router::url("/", true); ?><?php echo $this->request->params["controller"] ?>/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>',
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
        customConfig: '',
        disallowedContent: 'img{width,height,float}',
        extraAllowedContent: 'img[width,height,align]',
        extraPlugins: 'tableresize,lineheight,autosave,imagerotate,pastefromexcel,htmlbuttons,forms,fakeobjects',
        height: 800,
        contentsCss: [ '<?php echo Router::url("/", true); ?>css/contents.css', '<?php echo Router::url("/", true); ?>css/mystyles.css' ],
        bodyClass: 'document-editor',
        format_tags: 'p;h1;h2;h3;pre',
        removeDialogTabs: 'image:advanced;link:advanced',
        enterMode:2,forceEnterMode:false,shiftEnterMode:1,
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
