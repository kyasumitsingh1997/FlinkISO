<style type="text/css">
.dd{margin-top: -1.5em}
</style>
<div class="row">

<?php
        if ($internalAuditPlans) {
            $x = 0;
            foreach ($internalAuditPlans as $internalAuditPlan):
    ?>
<div class="col-md-12">
<div class="box box-default color-palette-box no-margin">
    <div class="box-header with-border">
      <h3 class="box-title"><?php echo $internalAuditPlan['InternalAuditPlan']['title']; ?></h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="overlay">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
      <dl>
        <dt><b><?php echo (__('Scheduled From') . ' : '); ?></b></dt><dd><?php echo date('d M Y',strtotime($internalAuditPlan['InternalAuditPlan']['schedule_date_from'])); ?></dd>
        <dt><b><?php echo (__('Scheduled To') . ' : '); ?></b></dt><dd><?php echo date('d M Y',strtotime($internalAuditPlan['InternalAuditPlan']['schedule_date_to'])); ?></dd>
        <dt>Employee</dt><dd><?php echo $internalAuditPlan['Employee']['name']?></dd>
        <dt>Branches</dt><dd><?php echo $internalAuditPlan['Branch']['name']?></dd>
        <dt>Departments</dt><dd><?php echo $internalAuditPlan['Department']['name']?></dd>
        <dt><?php echo (__('Auditor') . ' : '); ?></dt><dd><?php echo $internalAuditPlan['ListOfTrainedInternalAuditor']['name'] ?></b></dd>
        <dt>&nbsp;</dt><dd class="pull-right">
            <div class="btn-group pull-right">
                <?php echo $this->Html->link('View This',array('controller'=>'internal_audit_plan_departments','action'=>'view',$internalAuditPlan['InternalAuditPlanDepartment']['id']),array('class'=>'btn btn-sm btn-default','target'=>'_blank')); ?>
                <?php echo $this->Html->link('View Full Plan',array('action'=>'view',$internalAuditPlan['InternalAuditPlan']['id']),array('class'=>'btn btn-sm btn-default','target'=>'_blank')); ?>
            </div>
        </dd>
        
      </dl>
    </div>
    <!-- /.box-body -->
</div>
<br />
</div>

    <?php
        $x++;
        endforeach;
        } else {
    ?>
    <?php echo __('No results found'); ?>
<?php } ?>
</div>