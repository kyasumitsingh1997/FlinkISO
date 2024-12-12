<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min','ckeditor/ckeditor')); ?>
<?php echo $this->fetch('script'); ?>
<div id="supplierEvaluationTemplates_ajax">
    <?php echo $this->Session->flash();?>	<div class="nav">
		<div class="supplierEvaluationTemplates form col-md-8">
			<h4>Add Supplier Evaluation Template</h4>
			<?php echo $this->Form->create('SupplierEvaluationTemplate',array('role'=>'form','class'=>'form','default'=>false)); ?>
			<div class="row">
                <fieldset>
				<?php
					echo "<div class='col-md-12'>".$this->Form->input('title',array()) . '</div>'; 
					// echo "<div class='col-md-12'>".$this->Form->input('details',array()) . '</div>'; 
                ?>

                <div class="col-md-12">
                    <h5>Supplier Evaluation Template <small>You can copy-paste your existing evaluation template here below (any text/image format)</small></h5>
                    <?php  echo $this->Form->input('details',array('label'=>false,'div'=>false,'type'=>'textarea')); ?>				
                </div>
			     </fieldset>
                <?php
    			    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
    			    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
    			    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
			     ?>
		      </div>
		      <div class="">
                <?php
                    if ($showApprovals && $showApprovals['show_panel'] == true) {
						echo $this->element('approval_form');
					} else {
						echo $this->Form->input('publish', array('label' => __('Publish')));
					}?>		
                    <?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#supplierEvaluationTemplates_ajax','async' => 'false')); ?>
                    <?php echo $this->Form->end(); ?>
                    <?php echo $this->Js->writeBuffer();?>
                </div>
	       </div>
            <script> 
            	$("[name*='date']").datepicker({
                  changeMonth: true,
                  changeYear: true,
                  dateFormat:'yy-mm-dd',
                }); 
            </script>
            <div class="col-md-4">
	           <p><?php echo $this->element('helps'); ?></p>
            </div>
        </div>
    <script>
        $.validator.setDefaults({
    	   ignore: null,
    	   errorPlacement: function(error, element) {
            if ($(element).attr('name') == 'data[SupplierEvaluationTemplate][division_id]')
			{	
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
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
            jQuery.validator.addMethod("greaterThanZero", function(value, element) {
                return this.optional(element) || (parseFloat(value) > 0);
            }, "Please select the value");
        
        $('#SupplierEvaluationTemplateAddAjaxForm').validate({
            rules: {}}); 
    });
</script>
<script type="text/javascript">
    CKEDITOR.replace('SupplierEvaluationTemplateDetails', {
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
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>