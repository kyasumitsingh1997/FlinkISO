<script>
    $().ready(function() {
        $("#submit-indicator").hide();
        $("#submit_id").click(function() {
            if ($('#CompanyEditForm').valid()) {
                $("#submit_id").prop("disabled", true);
                $("#submit-indicator").show();
                $("#CompanyEditForm").submit();
            }
        });
        <?php if ($this->data['Company']['logo'] == 0){ ?>
            $("#customLogo").hide();
        <?php } else { ?>
             $("#customLogo").show();
        <?php } ?>
        $('input:radio[name="data[Company][logo]"]').change(function(){
            var logoType = $('input:radio[name="data[Company][logo]"]:checked').val();
            if(logoType == 0){
                $("#customLogo").hide();
            }else{
                $("#customLogo").show();
            }
        });
    });

</script>
<div id="companies_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="companies form col-md-8 panel">
            <h4><?php echo __('Edit Company'); ?>
                <?php echo $this->Html->link(__('View / Upload Files'), array('action' => 'view', $this->data['Company']['id']), array('class' => 'label btn-info')); ?>
            </h4>
            <?php echo $this->Form->create('Company', array('role' => 'form', 'class' => 'form', 'type' => 'file')); ?>
            <?php echo $this->Form->input('id'); ?>
            <div class="row">
                <div class="col-md-12"><?php echo $this->Form->input('name', array('disabled')); ?></div>
            </div>
            <div class="row hide">
                <div class="col-md-12">
                    <label>Company Logo</label>
                    <?php echo $this->Form->input('logo', array('type' => 'radio', 'options' => array(0 => 'Default', 1 => 'Custom Logo'), 'label' => false, 'legend' => false,  'div' => false, 'style' => 'float:none',)); ?>
                </div>
                <p>&nbsp;</p>
                <div id="customLogo" class="col-md-12">
                    <label>Upload Your Company Logo</label>
                    <p class="text-info">(Acceptable image formats are 'jpg/jpeg, gif, png')</p>
                    <?php echo $this->Form->file('company_logo', array('style' => 'box-shadow: none !important; border: none !important;')); ?>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-12">
                    <h4><?php echo __('Company Description / Message form MR or Director') ?></h4>
                    <textarea id="CompanyDescription" name="data[Company][description]"><?php echo $this->data['Company']['description'] ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4><?php echo __('Company Welcome Message') ?></h4>
                    <textarea id="CompanyWelcomeMessage" name="data[Company][welcome_message]">
                        <?php echo $this->data['Company']['welcome_message'] ?>
                    </textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4><?php echo __('Quality Policy') ?></h4>
                    <textarea id="CompanyQualityPolicy" name="data[Company][quality_policy]"><?php echo $this->data['Company']['quality_policy'] ?></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4><?php echo __('Vision Statement') ?></h4>
                    <textarea id="CompanyVisionStatement" name="data[Company][vision_statement]"><?php echo $this->data['Company']['vision_statement'] ?></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4><?php echo __('Mission Statement') ?></h4>
                    <textarea id="CompanyMissionStatement" name="data[Company][mission_statement]"><?php echo $this->data['Company']['mission_statement'] ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4><?php echo __('Scope Of QMS') ?></h4>
                    <textarea id="CompanyScopeOfQms" name="data[Company][scope_of_qms]"><?php echo $this->data['Company']['scope_of_qms'] ?></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4><?php echo __('Copy - Paste your existing audit plan') ?></h4>
                    <textarea id="CompanyAuditPlan" name="data[Company][audit_plan]">
                        <?php echo $this->data['Company']['audit_plan'] ?>
                    </textarea>
                </div>
            </div>

            <?php if ($show_approvals && $show_approvals['show_panel'] == true) { ?>
                <?php echo $this->element('approval_form'); ?>
            <?php } else {
                echo $this->Form->input('publish', array('label' => __('Publish')));
            } ?>
            <?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id'));?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

        </div>
        <script> $("[name*='date']").datepicker({
                changeMonth: true,
                changeYear: true,
                format: 'yyyy-mm-dd',
      autoclose:true,
            });</script>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php echo $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#companies_ajax'))); ?>

<?php echo $this->Js->writeBuffer(); ?>
</div>
<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo __('Import from file (excel & csv formats only)'); ?></h4>
            </div>
            <div class="modal-body"><?php echo $this->element('import'); ?></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
            </div></div></div></div>
<?php echo $this->Html->script(array('ckeditor/ckeditor')); ?>
<script type="text/javascript">
    CKEDITOR.replace('CompanyDescription', {toolbar: [
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

    CKEDITOR.replace('CompanyWelcomeMessage', {toolbar: [
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
            {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
            {name: 'document', items: ['Preview', '-', 'Templates']},
            '/',
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
            {name: 'basicstyles', items: ['Bold', 'Italic']},
            {name: 'styles', items: ['Format', 'FontSize']},
            {name: 'colors', items: ['TextColor', 'BGColor']},
        ]});

    CKEDITOR.replace('CompanyAuditPlan', {toolbar: [
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
            {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
            {name: 'document', items: ['Preview', '-', 'Templates']},
            '/',
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
            {name: 'basicstyles', items: ['Bold', 'Italic']},
            {name: 'styles', items: ['Format', 'FontSize']},
            {name: 'colors', items: ['TextColor', 'BGColor']},
        ]});

    CKEDITOR.replace('CompanyQualityPolicy', {toolbar: [
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
            {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
            {name: 'document', items: ['Preview', '-', 'Templates']},
            '/',
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
            {name: 'basicstyles', items: ['Bold', 'Italic']},
            {name: 'styles', items: ['Format', 'FontSize']},
            {name: 'colors', items: ['TextColor', 'BGColor']},
        ]});

    CKEDITOR.replace('CompanyVisionStatement', {toolbar: [
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
            {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
            {name: 'document', items: ['Preview', '-', 'Templates']},
            '/',
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
            {name: 'basicstyles', items: ['Bold', 'Italic']},
            {name: 'styles', items: ['Format', 'FontSize']},
            {name: 'colors', items: ['TextColor', 'BGColor']},
        ]});

    CKEDITOR.replace('CompanyMissionStatement', {toolbar: [
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
            {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
            {name: 'document', items: ['Preview', '-', 'Templates']},
            '/',
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
            {name: 'basicstyles', items: ['Bold', 'Italic']},
            {name: 'styles', items: ['Format', 'FontSize']},
            {name: 'colors', items: ['TextColor', 'BGColor']},
        ]});

    CKEDITOR.replace('CompanyScopeOfQms', {toolbar: [
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
            {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
            {name: 'document', items: ['Preview', '-', 'Templates']},
            '/',
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
            {name: 'basicstyles', items: ['Bold', 'Italic']},
            {name: 'styles', items: ['Format', 'FontSize']},
            {name: 'colors', items: ['TextColor', 'BGColor']},
        ]});

</script>
