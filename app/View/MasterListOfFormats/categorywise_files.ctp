<?php 
if(isset($this->request->params['named']['jqload']) && $this->request->params['named']['jqload'] == 1){
  echo $this->Html->script(array('plugins/jQuery/jQuery-2.2.0.min','plugins/jQueryUI/jquery-ui.min')); 
  echo $this->fetch('script'); 
}
?>

<?php echo $this->fetch('script'); ?>
<div id="files-tabs-dept-<?php echo $this->request->params['named']['category_id'];?>" class="nav-tabs-info">
    <ul class="nav nav-tabs">
        <?php
            foreach ($PublishedDepartmentList as $department_id => $department_name) {
              echo "<li>".$this->Html->link(__($department_name), array('controller' => 'master_list_of_format_departments', 'action' => 'listing',
                'category_id'=>$this->request->params['named']['category_id'],
                'standard_id' => $this->request->params['named']['standard_id'],
                $department_id), array('escape' => false)) ."</li>";
            }
         ?> 
        <!-- <li><?php echo $this->Html->image('indicator.gif', array('id' => 'file-dept-busy-indicator', 'class' => 'pull-right')); ?></li> -->
    </ul>
</div>
<script>
    $(document).ready(function () {
        $("#file-dept-busy-indicator").hide();
        $.ajaxSetup({
            cache: false,            
            // success: function() {$("#message-busy-indicator").hide();}
        });
        $("#files-tabs-dept-<?php echo $this->request->params['named']['category_id'];?>").tabs({
            load: function (event, ui) {
                $("#file-dept-busy-indicator").hide();
            },
            ajaxOptions: {
                error: function (xhr, status, index, anchor) {
                    $(anchor.hash).html(
                            "<?php echo __('Error loading resource.')?> " +
                            "<?php echo __('Contact Administrator.')?>" );
                }
            }
        });

        $("#files-tabs-dept li").click(function () {
            $("#file-dept-busy-indicator").show();
        });
    });
</script>