<?php echo $this->Html->script(array('ckeditor/ckeditor')); ?>
<?php echo $this->fetch('script'); ?>
<div id="helps_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="helps form col-md-8">
            <h4><?php echo __('Edit Help'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
            </h4>
            <?php $new_langs = array_keys($languages);?>
            <?php echo $this->Form->create('Help', array('role' => 'form', 'class' => 'form')); ?>
            <div class="row">
                    <div class="col-md-6"><?php echo $this->Form->input('table_name',array('value'=>$this->request->data['Help'][$new_langs[0]]['Help']['table_name'])); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('action_name',array('value'=>$this->request->data['Help'][$new_langs[0]]['Help']['action_name'])); ?></div>
                </div>
            <?php 
            foreach ($this->request->data['Help'] as $language_id => $data) { 
                ?>
                <?php echo $this->Form->hidden('Help.Translations.'.$language_id.'.id',array('value'=>$data['Help']['id'])); ?>
                <?php echo $this->Form->hidden('Help.Translations.'.$language_id.'.language_id',array('value'=>$data['Help']['language_id'])); ?>
                    <div class="row">
                        <div class="col-md-12"><h4><?php echo $languages[$language_id];?></h4></div>
                        <div class="col-md-12">
                            <?php 
                            if(!$data['Help']['title'])$data['Help']['title'] = $this->request->data['Help'][$new_langs[0]]['Help']['title'];
                            echo $this->Form->input('Help.Translations.'.$language_id.'.title',array('value'=>$data['Help']['title'])); ?>
                        </div>
                        
                        <div class="col-md-12"><br />
                            <?php if(!$data['Help']['help_text'])$data['Help']['help_text'] = $this->request->data[$new_langs[0]]['Help']['help_text']; ?>
                            <textarea name="data[Help][Translations][<?php echo $language_id;?>][help_text]" id="HelpHelpText<?php echo $language_id;?>">
                                <?php echo $data['Help']['help_text'];?>
                            </textarea>
                        </div>
                    
                    <script type="text/javascript">
                        CKEDITOR.replace('HelpHelpText<?php echo $language_id;?>', {toolbar: [
                                ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
                                {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
                                {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
                                {name: 'document', items: ['Preview', '-', 'Templates','Source']},
                                '/',
                                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
                                {name: 'basicstyles', items: ['Bold', 'Italic']},
                                {name: 'styles', items: ['Format', 'FontSize']},
                                {name: 'colors', items: ['TextColor', 'BGColor']},
                            ]
                        });
                    </script>  
                   </div>   
                
            <?php } ?>
            <div class="row">
                <div class="col-md-6"><?php echo $this->Form->input('sequence',array('value'=>$this->request->data['Help'][$new_langs[0]]['Help']['sequence'])); ?></div>
            </div>

            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }
            ?>
            <?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success'));?>
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
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#helps_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>
