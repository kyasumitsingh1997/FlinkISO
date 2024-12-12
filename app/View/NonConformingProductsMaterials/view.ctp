<div id="nonConformingProductsMaterials_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel">
        <div class="nonConformingProductsMaterials form col-md-8">
            <h4><?php echo __('View Non Conforming Report'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
                <?php echo $this->Html->link(__('Edit'), '#edit', array('id' => 'edit', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
            <table class="table table-responsive">
                <!-- <tr><td width="20%"><?php echo __('Procedure No.'); ?></td>
                    <td>
                        <?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['sr_no']); ?>
                        &nbsp;
                    </td>$preventiveActions
                </tr> -->
                <tr><td width="20%"><?php echo __('Name'); ?></td>
                    <td>
                        <?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['title']); ?>
                        &nbsp;
                    </td>
                </tr>
              
                <?php if(isset($nonConformingProductsMaterial['Material']['name'])) {?>
                <tr><td><?php echo __('Material'); ?></td>
                    <td>
                        <?php echo $this->Html->link($nonConformingProductsMaterial['Material']['name'], array('controller' => 'materials', 'action' => 'view', $nonConformingProductsMaterial['Material']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <?php } ?>
                <?php if(isset($nonConformingProductsMaterial['Product']['name'])){ ?>
                <tr><td><?php echo __('Product'); ?></td>
                    <td>
                        <?php echo $this->Html->link($nonConformingProductsMaterial['Product']['name'], array('controller' => 'products', 'action' => 'view', $nonConformingProductsMaterial['Product']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <?php } ?>
                <?php if(isset($nonConformingProductsMaterial['Process']['title'])){ ?>
                <tr><td><?php echo __('Process'); ?></td>
                    <td>
                        <?php echo $this->Html->link($nonConformingProductsMaterial['Process']['title'], array('controller' => 'processes', 'action' => 'view', $nonConformingProductsMaterial['Process']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <?php } ?>
                <?php if(isset($nonConformingProductsMaterial['RiskAssessment']['title'])){ ?>
                <tr><td><?php echo __('Risk'); ?></td>
                    <td>
                        <?php echo $this->Html->link($nonConformingProductsMaterial['RiskAssessment']['title'], array('controller' => 'risk_assessments', 'action' => 'view', $nonConformingProductsMaterial['RiskAssessment']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <?php } ?>
                 <tr><td><?php echo __('Date'); ?></td>
                    <td>
                        <?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['non_confirmity_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                 <tr><td><?php echo __('Violation Of Section'); ?></td>
                    <td>
                        <?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['violation_of_section']); ?>
                        &nbsp;
                    </td>
                </tr>
                  <tr><td><?php echo __('Details'); ?></td>
                    <td>
                        <?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['details']); ?>
                        &nbsp;
                    </td>
                </tr>
                    <tr><td><?php echo __('Recorded By'); ?></td>
                    <td>
                        <?php echo h($PublishedEmployeeList[$nonConformingProductsMaterial['NonConformingProductsMaterial']['reported_by']]); ?>
                        &nbsp;
                    </td>
                </tr>
                 <tr><td><?php echo __('Department'); ?></td>
                    <td>
                        <?php echo h($PublishedDepartmentList[$nonConformingProductsMaterial['NonConformingProductsMaterial']['department_id']]); ?>
                        &nbsp;
                    </td>
                </tr>
                
                <tr><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($nonConformingProductsMaterial['PreparedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($nonConformingProductsMaterial['ApprovedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Publish'); ?></td>
                    <td>
                        <?php if ($nonConformingProductsMaterial['NonConformingProductsMaterial']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                    &nbsp;
                </tr>
            </table>
            <?php if($correctiveActions)echo $this->element('capanc',array('correctivePreventiveAction'=>$correctiveActions));?>
            <?php echo $this->element('upload-edit', array('usersId' => $nonConformingProductsMaterial['NonConformingProductsMaterial']['created_by'], 'recordId' => $nonConformingProductsMaterial['NonConformingProductsMaterial']['id'])); ?>
        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#nonConformingProductsMaterials_ajax'))); ?>
    <?php $this->Js->get('#edit'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'edit', $nonConformingProductsMaterial['NonConformingProductsMaterial']['id'], 'ajax'), array('async' => true, 'update' => '#nonConformingProductsMaterials_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
