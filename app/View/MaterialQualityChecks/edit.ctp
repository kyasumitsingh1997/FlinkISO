<?php echo $this->Html->script(array('ckeditor/ckeditor')); ?>
<?php echo $this->fetch('script'); ?>
<script>
    $().ready(function () {
        $('#MaterialQualityCheckEditForm').validate({
            rules: {
                "data[MaterialQC][0][name]": {
                    required: true
                },
                "data[MaterialQC][0][details]": {
                    required: true
                }
            }
        });
          $("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#MaterialQualityCheckEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#MaterialQualityCheckEditForm").submit();
             }
        });
        $('#MaterialQualityCheckMaterialId').change(function () {
            var a = $(this).val();
 			var i = parseInt($('#MaterialQualityCheckQualityCheckNumber').val());
            $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_material_check/" + $(this).val(), function (data) {
                $('#material_QC_ajax').html(data);
            });
            i = i + 1;
            $('#MaterialQualityCheckNumber').val(i);

        });

    });

    function addMaterialQualityCheckDiv() {
        var i = parseInt($('#MaterialQualityCheckQualityCheckNumber').val());
        $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_process/" + i, function (data) {
            $('#material_QC_ajax').append(data);
        });
        i = i + 1;
        $('#MaterialQualityCheckQualityCheckNumber').val(i);
    }

    function removeMaterialQualityCheckDiv(i) {
            var j = parseInt($('#MaterialQualityCheckQualityCheckNumber').val());

        if (j > 2) {
            var r = confirm("Are you sure you want to remove this 'Material Quality Check'?");

            if (r == true) {
                $('#material_QC_ajax' + i).remove();
            for(var k=i+1; k<j;k++){
                var l = k-1;
                $("#material_QC_ajax"+k).attr('id', "material_QC_ajax"+l);
                $("#MaterialQC"+k+"Name").attr('id', "MaterialQC"+l+"Name");
                $("#MaterialQC"+l+"Name").attr('name','data[MaterialQC]['+l+'][name]');
                $("#MaterialQC"+k+"Details").attr('id', "MaterialQC"+l+"Details");
                $("#MaterialQC"+l+"Details").attr('name','data[MaterialQC]['+l+'][details]');
                $("#MaterialQC"+k+"QcTemplate").attr('id', "MaterialQC"+l+"QcTemplate");
                $("#MaterialQC"+l+"QcTemplate").attr('name','data[MaterialQC]['+l+'][qc_template]');
                $("#MaterialQC"+k+"ActiveStatus_").attr('id', "MaterialQC"+l+"ActiveStatus_");
                $("#MaterialQC"+l+"ActiveStatus_").attr('name','data[MaterialQC]['+l+'][active_status]');
                $("#MaterialQC"+k+"ActiveStatus").attr('id', "MaterialQC"+l+"ActiveStatus");
                $("#MaterialQC"+l+"ActiveStatus").attr('name','data[MaterialQC]['+l+'][active_status]');
                $("#MaterialQC"+k+"MaterialQualityCheckId").attr('id', "MaterialQC"+l+"MaterialQualityCheckId");
                $("#MaterialQC"+l+"MaterialQualityCheckId").attr('name','data[MaterialQC]['+l+'][material_quality_check_id]');

//                $("#MaterialQC"+k+"IsLastStep_").attr('id', "MaterialQC"+l+"IsLastStep_");
//                $("#MaterialQC"+l+"IsLastStep_").attr('name','data[MaterialQC]['+l+'][is_last_step]');
//                $("#MaterialQC"+k+"IsLastStep").attr('id', "MaterialQC"+l+"IsLastStep");
//                $("#MaterialQC"+l+"IsLastStep").attr('name','data[MaterialQC]['+l+'][is_last_step]');
//                $("#MaterialQC"+k+"Details").attr('id', "MaterialQC"+l+"Details");
//                $("#MaterialQC"+l+"Details").attr('name','data[MaterialQC]['+l+'][details]');

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
    <div class="nav panel panel-default">
        <div class="materialQualityChecks form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('Edit Material Quality Check'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
            </h4>
            <?php echo $this->Form->create('MaterialQualityCheck', array('role' => 'form', 'class' => 'form')); ?>
            <fieldset>

                <div class="row">
                <div class="col-md-6"><?php echo $this->Form->input('material_id', array('selected' => $this->request->data['MaterialQualityCheck']['material_id'], 'disabled' => 'disabled')); ?></div>
                    <?php echo $this->Form->hidden('material_used', array('value' => $this->request->data['MaterialQualityCheck']['material_id'])); ?>
                    <?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?>
                    <?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?>
                </div>

                <div class="clearfix">&nbsp;</div>

                <div id="mqc_delete_warning" class="alert alert-warning alert-dismissible" role="alert" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h5>Minimum one 'Material Quality Check' is required, therefore, this quality check can not be deleted!</h5>
                </div>

                <?php $i = 1; ?>

                <div id="material_QC_ajax">
                    <?php foreach ($MaterialQualityChecks as $val) { ?>
                        <div id ="material_QC_ajax<?php echo $i; ?>">
                            <div class="row">
                                <div class="panel panel-default">
                                    <div class="panel-heading" id="panel<?php echo $i; ?>">
                                        <?php echo __('Step') . ' - ' . $i; ?>
                                        <?php if(!$val['MaterialQualityCheck']['check_performed']){ ?>
                                            <span class="alert-danger glyphicon glyphicon-remove danger pull-right" style="font-size:20px;background:none" type="button" onclick="removeMaterialQualityCheckDiv(<?php echo $i; ?>)"></span>
                                        <?php } ?>
                                    </div>

                                    <div class="panel-body">
                                        <fieldset>
                                            <div class="col-md-12"><?php echo $this->Form->input('MaterialQC.' . $i . '.name', array('label' => 'Name', 'value' => $val['MaterialQualityCheck']['name'])); ?></div>
                                            <div class="col-md-12"><?php echo $this->Form->input('MaterialQC.' . $i . '.details', array('label' => 'Details', 'type' => 'textarea', 'value' => $val['MaterialQualityCheck']['details'])); ?>
                                            </div>
                                            <div class="col-md-12"><?php echo $this->Form->input('MaterialQC.' . $i . '.qc_template', array('label' => 'Details', 'type' => 'textarea', 'value' => $val['MaterialQualityCheck']['qc_template'])); ?>
                                            </div>
                                            <?php
                                                if ($val['MaterialQualityCheck']['active_status']) {
                                                    $checked = true;
                                                } else {
                                                    $checked = false;
                                                }
                                            ?>
                                            <div class="col-md-3"><?php echo $this->Form->input('MaterialQC.' . $i . '.active_status', array('label' => 'Active', 'type' => 'checkbox', 'checked' => $checked)); ?></div>
                                            <?php echo $this->Form->input('MaterialQC.' . $i . '.material_quality_check_id', array('type' => 'hidden', 'value' => $val['MaterialQualityCheck']['id'])); ?>
                                        </fieldset>
                                    </div>
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
                    <?php $i++; } ?>

                </div>
                <div class="col-md-6"><?php echo $this->Form->input('QualityCheckNumber', array('type' => 'hidden', 'value' => $i)); ?></div>
                <div class="row">
                    <div class="pull-right"><span class="btn btn-info" id="plus" onclick='addMaterialQualityCheckDiv()'>Add Next Step</span></div>
                </div>

                <?php
                   // if ($showApprovals && $showApprovals['show_panel'] == true) {
                   //     echo $this->element('approval_form');
                   // } else {
                   //     echo $this->Form->input('publish');
                   // }
                ?>

                <?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
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
<?php $this->Js->get('#list'); ?>
<?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#materialQualityChecks_ajax'))); ?>
<?php echo $this->Js->writeBuffer(); ?>
