<style type="text/css">
.ui-tabs .ui-tabs-nav li.ui-tabs-active, .ui-tabs .ui-tabs-nav li.ui-tabs{margin-bottom:-1px;}
.ui-tabs .ui-tabs-nav li{margin-bottom:-1px;}
</style>
<div id="products_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="products form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('View Product'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
                <?php echo $this->Html->link(__('Edit'), '#edit', array('id' => 'edit', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->link(__('Add'), '#add', array('id' => 'add', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
            <table class="table table-responsive">
                <tr><td><?php echo __('Sr. No'); ?></td>
                    <td>
                        <?php echo h($product['Product']['sr_no']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Name'); ?></td>
                    <td>
                        <?php echo h($product['Product']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Category Name'); ?></td>
                    <td>
                        <?php echo h($product['ProductCategory']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Description'); ?></td>
                    <td>
                        <?php echo nl2br($product['Product']['description']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Branch'); ?></td>
                    <td>
                        <?php echo $this->Html->link($product['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $product['Branch']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Department'); ?></td>
                    <td>
                        <?php echo $this->Html->link($product['Department']['name'], array('controller' => 'departments', 'action' => 'view', $product['Department']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($product['PreparedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($product['ApprovedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Publish'); ?></td>
                    <td>
                        <?php if ($product['Product']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                    &nbsp;
                </tr>
                <tr><td colspan="2"><?php echo __('Required Material'); ?></td></tr>
                <tr>
                    <td colspan="2">
                        <div class="row">
                    <!-- <div class="col-md-6"><?php echo $this->Form->input('ProductMaterial.material_id', array('name' => 'ProductMaterial.material_id[]', 'type' => 'select',  'options' => $PublishedMaterialList, 'label' => __('Add Required Material'), 'style' => 'width:100%')); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('ProductMaterial.quantity', array('name' => 'ProductMaterial.material_id[]', 'type' => 'select',  'options' => $PublishedMaterialList, 'label' => __('Add Required Material'), 'style' => 'width:100%')); ?></div> -->
                    <div class="col-md-12">
                        <h3><?php echo __('Material required with scale (Qty per product)');?></h3>
                        <table class="table table-responsive table-bordered table-condensed">
                            <tr>
                                <th><?php echo __('Material');?></th>
                                <th><?php echo __('Quantity');?></th>
                                <th><?php echo __('Unit');?></th>                                
                            </tr>
                            <?php 
                            $x = 0;
                            foreach($productMaterials as $productMaterial){ 
                                print_r($material);
                                ?>
                            <tr>
                                <td><?php echo $productMaterial['Material']['Material']['name'];?></td>
                                <td><?php echo $productMaterial['ProductMaterial']['quantity'];?></td>
                                <td><?php echo $productMaterial['Material']['Unit']['name'];?></td>
                            </tr>
                            <?php $x++; } ?>
                        </table>
                    </div>
                </div>
                    </td>
                </tr>
            </table>
            <?php if (!empty($product['NonConformingProductsMaterial'])) { ?>
                <h4><?php echo __('Non Conformity Details');?></h4>
                <table class="table table-responsive">
                    <?php foreach ($product['NonConformingProductsMaterial'] as $ncs): ?>
                    
                        <tr>
                            <td colspan="4"><strong><?php echo h($ncs['title']); ?></strong>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><?php echo __('Indentification Details'); ?></td>
                            <td width="80%"><?php echo h($ncs['action_taken']); ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><?php echo __('Resolution Details'); ?></td>
                            <td width="80%"><?php echo h($ncs['resolution_details']); ?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><?php echo __('Action Taken'); ?></td>
                            <td width="80%"><?php echo ($ncs['action_taken']); ?>&nbsp;</td>
                        </tr>
                        
                        <tr>
                            <td><?php echo __('current Status'); ?></td>
                            <td><?php echo ($ncs['status']?'Open':'Close'); ?>&nbsp;</td>
                        </tr>                    
                        <tr>
                            <td><?php echo __('Date'); ?></td>
                            <td><?php echo h($ncs['non_confirmity_date']); ?>&nbsp;</td>
                        </tr>                    
            <?php endforeach;}?>
        </table>
        <h3><?php echo __('Weekly Production Plan');?></h3>
        <div class="table-responsive">
        <?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>             
            <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
                <tr>                    
                    <th><?php echo __('Week'); ?></th>
                    <th><?php echo __('Production Planned'); ?></th>
                    <th><?php echo __('Prepared By'); ?></th>
                    <th><?php echo __('Approved By'); ?></th>
                    <th><?php echo __('Publish'); ?></th>
                </tr>
        <?php if($product['ProductionWeeklyPlan']){ ?>
            <?php foreach ($product['ProductionWeeklyPlan'] as $productionWeeklyPlan): ?>
                <tr>
                    <td><?php echo h($productionWeeklyPlan['week']); ?>&nbsp;</td>                    
                    <td><?php echo h($productionWeeklyPlan['production_planned']); ?>&nbsp;</td>
                    <td><?php echo h($PublishedEmployeeList[$productionWeeklyPlan['prepared_by']]); ?>&nbsp;</td>
                    <td><?php echo h($PublishedEmployeeList[$productionWeeklyPlan['approved_by']]); ?>&nbsp;</td>
                    <td width="60">
                        <?php if($productionWeeklyPlan['publish'] == 1) { ?>
                        <span class="fa fa-check"></span>
                        <?php } else { ?>
                        <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php }else{ ?>
            <tr><td colspan=60>No results found</td></tr>
        <?php } ?>
        </table>
        </div>
        <h3><?php echo __('On Going Production') ;?></h3>
        <div class="table-responsive">            
            <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
                <tr>
                    <th><?php echo __('Week'); ?></th>
                    <!-- <th><?php echo __('end_date'); ?></th> -->
                    <th><?php echo __('Batch Number'); ?></th>
                    <th><?php echo __('Planned'); ?></th>
                    <th><?php echo __('Actual'); ?></th>
                    <th><?php echo __('Balance');?></th>
                    <th><?php echo __('Rejections'); ?></th>
                    <th><?php echo __('Branch'); ?></th>
                    <th><?php echo __('Prepared By'); ?></th>
                    <th><?php echo __('Approved By'); ?></th>
                    <th><?php echo __('Current Status'); ?></th>
                    <th><?php echo __('Actions'); ?></th>                    
                </tr>
                <?php if ($productions) {
                        $x = 0;
                        foreach ($productions as $production):
                ?>
                <tr class="on_page_src">                    
                    <td><?php echo h($production['ProductionWeeklyPlan']['week']); ?>&nbsp;</td>
                    <!-- <td><?php echo h(date('d M Y',strtotime($production['Production']['end_date']))); ?>&nbsp;</td> -->
                    <td><?php echo h($production['Production']['batch_number']); ?>&nbsp;</td>

                    <td><?php echo h($production['ProductionWeeklyPlan']['production_planned']); ?>&nbsp;</td>
                    <td><?php echo h($production['Production']['actual_production_number']); ?>&nbsp;</td>
                    <td><?php echo h($production['Production']['production_planned'] - $production['Production']['actual_production_number']); ?>&nbsp;</td>
                    <td><?php echo h($production['Production']['rejections']); ?>&nbsp;</td>

                    <td>
                        <?php echo $this->Html->link($production['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $production['Branch']['id'])); ?>
                    </td>
                    <td><?php echo h($production['PreparedBy']['name']); ?>&nbsp;</td>
                    <td><?php echo h($production['ApprovedBy']['name']); ?>&nbsp;</td>
                    <td><?php echo h($currentStatus[$production['Production']['current_status']]); ?>&nbsp;</td>
                    
                    <td><?php 
                        if($production['Production']['actual_production_number'] > 0){
                            echo $this->Html->link('Add QC/Rejections',array('controller'=>'production_rejections','action'=>'lists','production_id'=>$production['Production']['id'],'product_id'=>$production['Production']['product_id']),array('class'=>'btn btn-xs btn-danger')); 
                        }else{
                            echo $this->Html->link('Add Actual Production',array('controller'=>'productions','action'=>'edit',$production['Production']['id']),array('class'=>'btn btn-xs btn-info')); 
                        }
                    ?>
                        &nbsp;</td>
                </tr>
                <?php
                    $x++;
                    endforeach;
                    } else {
                ?>
                <tr><td colspan=19>No results found</td></tr>
                <?php } ?>
            </table>
            
        </div>
        </div>

        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
 <div  class="col-md-12">
            <h2><?php echo __('Upload your product design related files below') ?></h2>
            <div id="product-tabs">
                <ul>
                <?php 
                foreach($product_doc_types as $key=>$product_doc_type){ 
                        $value =  str_replace(' ', '', $product_doc_type); ?>
                   <li style="width:100%"><?php echo $this->Html->link(__($product_doc_type) . ' <span class="badge btn-default">' . "${$value}" . "</span>", array('action' => 'product_design',$value, $product['Product']['id'], $product['Product']['created_by']), array('escape' => false,'style'=>'width:100%')); ?></li>
                <?php }?>
                </ul>
            </div>
        </div>

<script>
    $(function() {
        $("#product-tabs").tabs({
            beforeLoad: function(event, ui) {
                ui.jqXHR.error(function() {
                    ui.panel.html(
                            "Error Loading ... " +
                            "Please contact administrator.");
                });
            }
        });
    });
    $.ajaxSetup({beforeSend: function() {
            $("#product-busy-indicator").show();
        }, complete: function() {
            $("#product-busy-indicator").hide();
        }});
</script>

    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#products_ajax'))); ?>
    <?php $this->Js->get('#edit'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'edit', $product['Product']['id'], 'ajax'), array('async' => true, 'update' => '#products_ajax'))); ?>
    <?php $this->Js->get('#add'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'lists', null, 'ajax'), array('async' => true, 'update' => '#products_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
