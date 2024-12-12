<?php echo $this->element('checkbox-script'); ?>
<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <div class="nonConformingProductsMaterials ">
        <?php echo $this->element('nav-header-lists', array('postData' => array('pluralHumanName' => 'Non Conforming Report', 'modelClass' => 'NonConformingProductsMaterial', 'options' => array("sr_no" => "Sr No", "title" => "Title", "description" => "Description"), 'pluralVar' => 'nonConformingProductsMaterials'))); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('table th a, .pag_list li span a').on('click', function () {
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
                    <th><?php echo $this->Paginator->sort('title', __('Title')); ?></th>
                    <th><?php echo $this->Paginator->sort('material_id', __('Material')); ?> / <?php echo $this->Paginator->sort('product_id', __('Product')); ?></th>
                    <th><?php echo $this->Paginator->sort('description', __('Description')); ?></th>
                    <th><?php echo $this->Paginator->sort('date', __('Date')); ?></th>
                  
                    <th><?php echo $this->Paginator->sort('publish', __('Publish')); ?></th>
                </tr>
                <?php if ($nonConformingProductsMaterials) {
                        $x = 0;
                        foreach ($nonConformingProductsMaterials as $nonConformingProductsMaterial):
                ?>
                <tr class="on_page_src">
                    <td class=" actions">
                        <?php echo $this->element('actions', array('created' => $nonConformingProductsMaterial['NonConformingProductsMaterial']['created_by'], 'postVal' => $nonConformingProductsMaterial['NonConformingProductsMaterial']['id'], 'softDelete' => $nonConformingProductsMaterial['NonConformingProductsMaterial']['soft_delete'])); ?>
                    </td>
                    <td><?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['title']); ?>&nbsp;</td>
                    <td>
                        <?php if ($nonConformingProductsMaterial['Material']['id'] != '-1' && $nonConformingProductsMaterial['Material']['id'] != null){ echo '<span class="badge alert-info">M</span> ' . $this->Html->link($nonConformingProductsMaterial['Material']['name'], array('controller' => 'materials', 'action' => 'view', $nonConformingProductsMaterial['Material']['id'])); ?>
                        <?php }else if ($nonConformingProductsMaterial['Product']['id'] != '-1' && $nonConformingProductsMaterial['Product']['id'] != null){ echo '<span class="badge alert-info">P</span> ' . $this->Html->link($nonConformingProductsMaterial['Product']['name'], array('controller' => 'products', 'action' => 'view', $nonConformingProductsMaterial['Product']['id'])); ?>
                        <?php }else if ($nonConformingProductsMaterial['Process']['id'] != '-1' && $nonConformingProductsMaterial['Process']['id'] != null){ echo '<span class="badge alert-info">P</span> ' . $this->Html->link($nonConformingProductsMaterial['Process']['title'], array('controller' => 'processes', 'action' => 'view', $nonConformingProductsMaterial['Process']['id'])); ?>
                        <?php }else if ($nonConformingProductsMaterial['RiskAssessment']['id'] != '-1' && $nonConformingProductsMaterial['RiskAssessment']['id'] != null){ echo '<span class="badge alert-info">R</span> ' . $this->Html->link($nonConformingProductsMaterial['RiskAssessment']['title'], array('controller' => 'risk_assessments', 'action' => 'view', $nonConformingProductsMaterial['RiskAssessment']['id'])); ?>
                        <?php }else {
                           echo "Other";
                        } ?>
                    </td>
                    <td><?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['details']); ?>&nbsp;</td>
                      <td><?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['non_confirmity_date']); ?>&nbsp;</td>
                

                    <td width="60">
                        <?php if ($nonConformingProductsMaterial['NonConformingProductsMaterial']['publish'] == 1) { ?>
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
                <tr><td colspan='7'><?php echo __('No results found'); ?></td></tr>
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
<?php echo $this->element('advanced-search', array('postData' => array("sr_no" => "Sr No", "title" => "Title", "description" => "Description"), 'PublishedBranchList' => array($PublishedBranchList))); ?>
<?php echo $this->element('import', array('postData' => array("sr_no" => "Sr No", "title" => "Title", "description" => "Description"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
<?php echo $this->Js->writeBuffer(); ?>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
