<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min','ckeditor/ckeditor')); ?>
<?php echo $this->fetch('script'); ?>


<div id="helps_ajax">
    <?php echo $this->Session->flash(); ?><div class="nav">
        <div class="helps form col-md-8">
            <h4><?php echo __('Add Help'); ?></h4>
            <?php echo $this->Form->create('Help', array('role' => 'form', 'class' => 'form')); ?>

            <div class="row">
                <div class="col-md-6"><?php echo $this->Form->input('table_name'); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('action_name'); ?></div>
            </div>
            <?php foreach ($languages as $id => $value) { ?>
                <div class="row">
                    <div class="col-md-12"><h4><?php echo $value;?></h4></div>
                    <div class="col-md-12"><?php echo $this->Form->input('Help.Translations.'.$id.'.title'); ?></div>
                    <?php echo $this->Form->hidden('Help.Translations.'.$id.'.language_id'); ?>
                    <div class="col-md-12"><br />
                        <textarea name="data[Help][Translations][<?php echo $id;?>][help_text]" id="HelpHelpText<?php echo $id;?>"></textarea>
                    </div>
                </div>  
                <script type="text/javascript">
                    CKEDITOR.replace('HelpHelpText<?php echo $id;?>', {toolbar: [
                            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
                            {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
                            {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
                            {name: 'document', items: ['Preview', '-', 'Templates']},
                            '/',
                            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
                            {name: 'basicstyles', items: ['Bold', 'Italic']},
                            {name: 'styles', items: ['Format', 'FontSize']},
                            {name: 'colors', items: ['TextColor', 'BGColor']},
                        ]
                    });
                </script>  
            <?php } ?>
            

            <div class="row">
                <div class="col-md-6"><?php echo $this->Form->input('sequence'); ?></div>
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
            <?php echo $this->Form->submit(__('Submit')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>
        </div>
<script>
    $("[name*='date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
    });
</script>
        <div class="col-md-4">
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
