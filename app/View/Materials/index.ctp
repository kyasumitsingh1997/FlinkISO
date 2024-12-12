<?php echo $this->element('checkbox-script'); ?>

<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <div class="materials ">
        <?php echo $this->element('nav-header-lists', array('postData' => array('pluralHumanName' => 'Materials', 'modelClass' => 'Material', 'options' => array("sr_no" => "Sr No", "name" => "Name", "description" => "Description"), 'pluralVar' => 'materials'))); ?>

        <script type="text/javascript">
            $(document).ready(function() {
                $('table th a, .pag_list li span a').on('click', function() {
                    var url = $(this).attr("href");
                    $('#main').load(url);
                    return false;
                });
            });
        </script>
        <div class="table-responsive">
            <?php echo $this->Form->create(array('class' => 'no-padding no-margin no-background')); ?>
            <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th><?php echo $this->Paginator->sort('name'); ?>/<?php echo $this->Paginator->sort('item_code'); ?></th>
                    <th><?php echo $this->Paginator->sort('min_stock','Minimun Stock'); ?></th>
                    <th><?php echo $this->Paginator->sort('unit_id','Measuring Unit'); ?></th>
                    <th><?php echo __('Stock In Hand'); ?></th>
                    <th><?php echo __('Shelflife by Manufacturer'); ?></th>
                    <th><?php echo __('Shelflife by Company'); ?></th>
                    <th><?php echo $this->Paginator->sort('qc_required','Material QC Required?'); ?></th>
                    <th><?php echo __('#QC Steps'); ?></th>
                    <th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
                    <th><?php echo $this->Paginator->sort('approved_by'); ?></th>
                    <th><?php echo $this->Paginator->sort('publish'); ?></th>
                </tr>
                <?php
                    if ($materials) {
                        $x = 0;
                        foreach ($materials as $material):
                            if($material['Material']['nc_found'])$nc_class = 'text-danger';
                            else $nc_class = 'text-default';
                ?>
                <tr class="on_page_src <?php echo $nc_class; ?>">
                    <td class=" actions">
                        <?php echo $this->element('actions', array('created' => $material['Material']['created_by'], 'postVal' => $material['Material']['id'], 'softDelete' => $material['Material']['soft_delete'], 'qc_required'=>$material['Material']['qc_required'],'material_qc_status'=>$material['Material']['material_qc_status'])); ?>

                    </td>
                    <td><?php echo h($material['Material']['name']); ?><small> (<?php echo h($material['Material']['item_code']); ?>)</small>&nbsp;</td>
                    <td><?php echo h($material['Material']['min_stock']); ?>&nbsp;</td>
                    <td><?php echo h($material['Unit']['name']); ?>&nbsp;</td>
                    <td><?php 
                        //$stock = $this->requestAction(array('controller'=>'stocks','action'=>'get_stock_details',$material['Material']['id']));echo $stock['stock'];
                     echo $material['Material']['stock_in_hand'];
                    ?></td>
                    <td><?php echo h($material['MaterialListWithShelfLife'][0]['shelflife_by_manufacturer']); ?>&nbsp;</td>
                    <td><?php echo h($material['MaterialListWithShelfLife'][0]['shelflife_by_company']); ?>&nbsp;</td>
                    <td><?php echo h($material['Material']['qc_required']) ? __('Yes') : __('No'); ?>&nbsp;</td>
                    <td>
                        <?php 
                        // print_r($material);
                        // echo count($material['MaterialQualityCheck']);
                            if(count($material['MaterialQualityCheck']) > 0)echo count($material['MaterialQualityCheck']);
                            elseif($material['Material']['qc_required'] == 1) echo $this->Html->link('Add Steps',array('controller'=>'material_quality_checks','action'=>'lists',$material['Material']['id']),array('class'=>'btn btn-xs btn-info'));
                        ?>
                    </td>
                    <td><?php echo h($PublishedEmployeeList[$material['Material']['prepared_by']]); ?>&nbsp;</td>
                    <td><?php echo h($PublishedEmployeeList[$material['Material']['approved_by']]); ?>&nbsp;</td>
                    <td width="60">
                        <?php if ($material['Material']['publish'] == 1) { ?>
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
                <tr><td colspan=15>No results found</td></tr>
                <?php } ?>
            </table>
            <?php echo $this->Form->end(); ?>
        </div>
        <p>
            <?php
                echo $this->Paginator->options(array(
                    'update' => '#main',
                    'evalScripts' => true,
                    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
                    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
                ));

                echo $this->Paginator->counter(array(
                    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
                ));
            ?>
        </p>
        <ul class="pagination">
            <?php
                echo "<li class='previous'>" . $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled')) . "</li>";
                echo "<li>" . $this->Paginator->numbers(array('separator' => '')) . "</li>";
                echo "<li class='next'>" . $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled')) . "</li>";
            ?>
        </ul>
    </div>
</div>

<?php echo $this->element('export'); ?>
<?php echo $this->element('common'); ?>
<?php echo $this->element('advanced-search', array('postData' => array("sr_no" => "Sr No", "name" => "Name", "description" => "Description"), 'PublishedBranchList' => array($PublishedBranchList))); ?>
<?php echo $this->element('import', array('postData' => array("sr_no" => "Sr No", "name" => "Name", "description" => "Description"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->Js->writeBuffer(); ?>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
