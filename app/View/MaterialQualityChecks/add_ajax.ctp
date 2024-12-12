<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min','ckeditor/ckeditor')); ?>
<?php echo $this->fetch('script'); ?>

<script>
    $.validator.setDefaults({
        submitHandler: function(form) {
            for(var instanceName in CKEDITOR.instances){
                var a = CKEDITOR.instances[instanceName];
                var b = a.name;
                CKEDITOR.instances[b].updateElement();    
            }

            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#main',
                beforeSend: function(){
                    for(var instanceName in CKEDITOR.instances){
                        var a = CKEDITOR.instances[instanceName];
                        var b = a.name;
                        CKEDITOR.instances[b].updateElement();    
                    }
                    
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
        $('#MaterialQualityCheckAddAjaxForm').validate({
            rules: {
                "data[MaterialQC][0][name]": {
                    required: true
                },
                "data[MaterialQC][0][details]": {
                    required: true
                }
            }
        });
        $('#MaterialQualityCheckMaterialId').change(function() {
            var a = $(this).val();
            var i = parseInt($('#MaterialQualityCheckQualityCheckNumber').val());
            $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_material_check/" + $(this).val(), function(data) {
                $('#material_QC_ajax').html(data);
            });
            i = i + 1;

            $('#MaterialQualityCheckQualityCheckNumber').val(i);

        });

    });

    function addMaterialQualityCheckDiv() {
        var i = parseInt($('#MaterialQualityCheckQualityCheckNumber').val());

        $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_process/" + i, function(data) {
            $('#material_QC_ajax').append(data);
        });
        i = i + 1;
        $('#MaterialQualityCheckQualityCheckNumber').val(i);
    }
    function removeMaterialQualityCheckDiv(i) {
            var j = parseInt($('#MaterialQualityCheckQualityCheckNumber').val());

        if (j > 2){
            var r = confirm("Are you sure you want to remove this 'Material Quality Check'?");

            if (r == true && j > 2) {
                $('#material_QC_ajax' + i).remove();
            for(var k=i+1; k<j;k++){
                var l = k-1;
                $("#material_QC_ajax"+k).attr('id', "material_QC_ajax"+l);
                $("#MaterialQC"+k+"Name").attr('id', "MaterialQC"+l+"Name");
                $("#MaterialQC"+l+"Name").attr('name','data[MaterialQC]['+l+'][name]');
                $("#MaterialQC"+k+"Details").attr('id', "MaterialQC"+l+"Details");
                $("#MaterialQC"+l+"Details").attr('name','data[MaterialQC]['+l+'][details]');
                $("#MaterialQC"+k+"ActiveStatus_").attr('id', "MaterialQC"+l+"ActiveStatus_");
                $("#MaterialQC"+l+"ActiveStatus_").attr('name','data[MaterialQC]['+l+'][active_status]');
                $("#MaterialQC"+k+"ActiveStatus").attr('id', "MaterialQC"+l+"ActiveStatus");
                $("#MaterialQC"+l+"ActiveStatus").attr('name','data[MaterialQC]['+l+'][active_status]');

//                $("#MaterialQC"+k+"IsLastStep_").attr('id', "MaterialQC"+l+"IsLastStep_");
//                $("#MaterialQC"+l+"IsLastStep_").attr('name','data[MaterialQC]['+l+'][is_last_step]');
//                $("#MaterialQC"+k+"IsLastStep").attr('id', "MaterialQC"+l+"IsLastStep");
//                $("#MaterialQC"+l+"IsLastStep").attr('name','data[MaterialQC]['+l+'][is_last_step]');

                $("#MaterialQC"+k+"Details").attr('id', "MaterialQC"+l+"Details");
                $("#MaterialQC"+l+"Details").attr('name','data[MaterialQC]['+l+'][details]');

                 $("#panel"+k).attr('id',"panel"+l);

                var data = 'Step - '+l+'<span class="alert-danger glyphicon glyphicon-remove danger pull-right" onclick="removeMaterialQualityCheckDiv('+l+')" type="button" style="font-size:20px;background:none"></span>';

                $("#panel"+l).html(data);
               // $("#panel"+l).attr('html',k);
            }
            $('#MaterialQualityCheckQualityCheckNumber').val(j-1);
        }
        } else {
            $('#mqc_delete_warning').show();
        }
    }
</script>

<div id="materialQualityChecks_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav">
        <div class="materialQualityChecks form col-md-8">
            <h4><?php echo __('Add Material Quality Check Process'); ?></h4>
            <?php echo $this->Form->create('MaterialQualityCheck', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
            <div class="row">
                <?php if(isset($materialId)){ $default = $materialId; } else { $default = '';} ?>
                <div class="col-md-12"><?php echo $this->Form->input('material_id', array('default' => $default,'style'=>'width:100%')); ?></div>

                <?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?>
                <?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?>
                <?php echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id'])); ?>
            </div>

            <div class="clearfix">&nbsp;</div>

            <div id="mqc_delete_warning" class="alert alert-warning alert-dismissible" role="alert" style="display:none;">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h5>Minimum one 'Material Quality Check' is required, therefore, this quality check can not be deleted!</h5>
            </div>

            <?php $i = 1; ?>
            <div id="material_QC_ajax">
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
            </div>
            <div class="col-md-6"><?php echo $this->Form->input('QualityCheckNumber', array('type' => 'hidden', 'value' => $i+1)); ?></div>
            <div class="row">
                <div class="pull-right"><span class="btn btn-info" id="plus" onclick='addMaterialQualityCheckDiv()'>Add Next Step</span></div>
            </div>

            <?php
//                if ($showApprovals && $showApprovals['show_panel'] == true) {
//                    echo $this->element('approval_form');
//                } else {
//                    echo $this->Form->input('publish', array('label' => __('Publish')));
//                }
            ?>
            <?php echo $this->Form->submit('Submit', array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#materialQualityChecks_ajax', 'async' => 'false','id'=>'submit_id')); ?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>

        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
</div>
<script type="text/javascript">
    CKEDITOR.replace('MaterialQC<?php echo $i?>QcTemplate', {
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
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
