<?php echo $this->element('checkbox-script'); ?>
<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <h3><small><?php echo $this->Html->link('Quality Management',array('controller'=>'dashboards','action'=>'mr'));?> / </small><?php echo __('Non Conformity Graph');?></h3>
    <?php echo $this->element('nc_report_graph',array('data'=>$monthly));?>
    <h3><?php echo __('Non Conformity Report Details');?></h3><br />
    <div class="correctivePreventiveActions ">
        <div class="nav-tabs-custom">
            <?php $i=0; ?>
            <ul class="nav nav-tabs">
                <?php foreach ($nonConformingProductsMaterial as $month => $data) { 
                        if($i == 0)$class = 'active';
                        else $class = '';
                    ?>
                    <li class="<?php echo $class;?>"><a data-toggle="tab" href="#<?php echo date('M-y',strtotime($month)); ?>"><?php echo date('M-y',strtotime($month)); ?> <?php $i=1;?>
                        <?php foreach ($data as $type => $nonConformingProductsMaterials) { ?><?php }?>
                        <span class="label label-danger"><?php echo count($data)?></span>
                        </a></li>
                <?php } ?>         
            </ul>
        <div class="tab-content">    
       <?php 
       $i=0;
       foreach ($nonConformingProductsMaterial as $month => $data) { 
        
       if($i == 0)$class = 'active';
       else $class = '';
       $i;
        ?>

        <div id="<?php echo date('M-y',strtotime($month)); ?>" class="tab-pane <?php echo $class;?>">
        <?php $i=1;
        foreach ($data as $type => $nonConformingProductsMaterials) { ?>
        <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
                    <tr>
                        <th width="20%"><?php echo __($type);?></th>
                        <th width="10%"><?php echo __('Title'); ?></th>
                        <th width="5%"><?php echo __('Status'); ?></th>
                        <!-- <th width="5%"><?php echo __('Violation Of Section');?> -->
                        <th><?php echo __('Description'); ?></th>
                        <th width="5%"><?php echo __('Date'); ?></th>
                        <th width="5%"><?php echo __('Publish'); ?></th>
                    </tr>
            <h4><?php echo __($type); ?></h4>
                <?php foreach ($nonConformingProductsMaterials as $nonConformingProductsMaterial) { ?>
                   <tr class="on_page_src">
                        <td>
                            <?php if ($nonConformingProductsMaterial['Material']['id'] != '-1' && $nonConformingProductsMaterial['Material']['id'] != null){ echo '<span class="badge alert-info">M</span> ' . $this->Html->link($nonConformingProductsMaterial['Material']['name'], array('controller' => 'materials', 'action' => 'view', $nonConformingProductsMaterial['Material']['id'])); ?>
                            <?php }else if ($nonConformingProductsMaterial['Product']['id'] != '-1' && $nonConformingProductsMaterial['Product']['id'] != null){ echo '<span class="badge alert-info">P</span> ' . $this->Html->link($nonConformingProductsMaterial['Product']['name'], array('controller' => 'products', 'action' => 'view', $nonConformingProductsMaterial['Product']['id'])); ?>
                            <?php }else if ($nonConformingProductsMaterial['Process']['id'] != '-1' && $nonConformingProductsMaterial['Process']['id'] != null){ echo '<span class="badge alert-info">PS</span> ' . $this->Html->link($nonConformingProductsMaterial['Process']['name'], array('controller' => 'processes', 'action' => 'view', $nonConformingProductsMaterial['Process']['id'])); ?>
                            <?php }else if ($nonConformingProductsMaterial['Procedure']['id'] != '-1' && $nonConformingProductsMaterial['Procedure']['id'] != null){ echo '<span class="badge alert-info">PS</span> ' . $this->Html->link($nonConformingProductsMaterial['Procedure']['name'], array('controller' => 'procedures', 'action' => 'view', $nonConformingProductsMaterial['Procedure']['id'])); ?>  
                            <?php }else {
                               echo "Procedure";
                            } ?>
                        </td>
                        <td><?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['title']); ?>&nbsp;</td>
                        <td><?php echo ($nonConformingProductsMaterial['NonConformingProductsMaterial']['status'])?'Open':'Close'; ?>&nbsp;</td>
                        <!-- <td><?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['violation_of_Section']); ?>&nbsp;</td> -->
                        <td><?php echo h(substr($nonConformingProductsMaterial['NonConformingProductsMaterial']['details'],0,100)); ?>...&nbsp;</td>
                        <td><?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['non_confirmity_date']); ?>&nbsp;</td>
                        <td width="60">
                            <?php if ($nonConformingProductsMaterial['NonConformingProductsMaterial']['publish'] == 1) { ?>
                                <span class="fa fa-check"></span>
                            <?php } else { ?>
                                <span class="fa fa-ban"></span>
                            <?php } ?>&nbsp;</td>
                    </tr>
                <?php } ?>
                </table>
       <?php } ?>
   </div>
       <?php } ?>   
        </div></div>       </div>  

        


</div>


<?php echo $this->Js->writeBuffer(); ?>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
