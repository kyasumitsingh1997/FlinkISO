<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
<tr>
    <th><input type="checkbox" id="selectAll"></th>
    <th><?php echo Inflector::Humanize('title'); ?></th>
    <th><?php echo Inflector::Humanize('document_number'); ?></th>
    <th><?php echo Inflector::Humanize('issue_number'); ?></th>
    <th><?php echo Inflector::Humanize('revision_number'); ?></th>
    <th><?php echo Inflector::Humanize('created'); ?></th>
    <th><?php echo Inflector::Humanize('Prepared By'); ?></th>
    <th><?php echo Inflector::Humanize('Approved By'); ?></th>
    <th><?php echo Inflector::Humanize('archived'); ?></th>
    <th><?php echo Inflector::Humanize('publish', __('Publish')); ?></th>
</tr>
<?php
    if ($issues) {
        $x = 0;
        foreach ($issues as $masterListOfFormat):
            if($masterListOfFormat['MasterListOfFormat']['id']==$this->request->params['pass'][0])$class = ' info';
            else $class = '';
?>
<tr class="on_page_src <?php echo $class;?>">
    <td class=" actions">
        <?php echo $this->element('actions', array('created' => $masterListOfFormat['MasterListOfFormat']['created_by'], 'postVal' => $masterListOfFormat['MasterListOfFormat']['id'], 'softDelete' => $masterListOfFormat['MasterListOfFormat']['soft_delete'])); ?>
    </td>
    <td><?php echo $this->Html->link($masterListOfFormat['MasterListOfFormat']['title'],array('action'=>'view',$masterListOfFormat['MasterListOfFormat']['id'])); ?>&nbsp;</td>
    <td><?php echo h($masterListOfFormat['MasterListOfFormat']['document_number']); ?>&nbsp;</td>
    <td><?php echo h($masterListOfFormat['MasterListOfFormat']['issue_number']); ?>&nbsp;</td>
    <td><?php echo h($masterListOfFormat['MasterListOfFormat']['revision_number']); ?>&nbsp;</td>
    <td><?php echo h($masterListOfFormat['MasterListOfFormat']['created']); ?>&nbsp;</td>
    <td><?php echo h($masterListOfFormat['CreatedBy']['name']); ?>&nbsp;</td>
    <td><?php echo h($masterListOfFormat['ApprovedBy']['name']); ?>&nbsp;</td>
    <td><?php echo h($masterListOfFormat['MasterListOfFormat']['archived']) ? __('No') : __('Yes'); ?>&nbsp;</td>

    <td width="60">
        <?php if ($masterListOfFormat['MasterListOfFormat']['publish'] == 1) { ?>
            <span class="fa fa-check"></span>
        <?php } else { ?>
            <span class="fa fa-ban"></span>
        <?php } ?>&nbsp;</td>
</tr>
<?php
    $x++;
    endforeach;
    } else {
?>
<tr><td colspan=19><?php echo __('No results found'); ?></td></tr>
<?php } ?>
</table>