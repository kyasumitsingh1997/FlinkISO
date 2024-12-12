<?php
    echo $this->Html->script(array('chartist/chartist.min','chartist/chartist-plugin-axistitle.min','chartist/chartist-plugin-tooltip.min'));    
    echo $this->fetch('script');
    echo $this->Html->css(array('chartist/chartist.min','tooltip.min'));
    echo $this->fetch('css');
?>
<style type="text/css">
#chart1,#chart2{height: 350px;}
</style>
<div id="main">
    <div class="">
        <h4><?php echo __('Environmental Safety Dashboard'); ?>
        <div class="row"><div class="col-md-12">&nbsp;</div></div>
        <div class="row"> 
            <div class="col-md-3 col-sm-12">
                <div class="btn btn-warning col-md-12 col-sm-12 col-xs-12">
                    <strong>Criterias</strong>
                    <h1><?php echo $evaluationCriterias ;?></h1>
                </div>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="btn btn-danger col-md-12 col-sm-12 col-xs-12">
                    <strong>Identifications</strong>
                    <h1><?php echo $envIdentifications ;?></h1>
                </div>		
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="btn btn-success col-md-12 col-sm-12 col-xs-12">
                    <strong>Evaluation</strong>
                    <h1><?php echo $envEvaluations ;?></h1>
                </div>
            </div>	
            <div class="col-md-3 col-sm-12 col-xs-12">						
                <div class="btn btn-default col-md-12 col-sm-12 col-xs-12">
                    <strong>Categories</strong>
                    <h1><?php echo $environmentQuestionnaireCategories ;?></h1>
                </div>
            </div>
            <!-- <div class="col-md-3 col-sm-12 col-xs-12">                      
                <div class="btn btn-default col-md-12 col-sm-12 col-xs-12">
                    <strong>Total</strong>
                    <h1>0 <small style="color:#000"><span class="glyphicon glyphicon-thumbs-up"></span></small></h1>
                </div>
            </div> -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-center">
            <h3><?php echo "Criteria-wise activities";?></h3>
            <div class="ct-chart" id="chart1"></div>
            <br />
            <table class="table table-responsive table-condensed text-left">
                <tr>
                    <th><?php echo __('Criteria');?></th>
                    <th><?php echo __('Activities');?></th>
                </tr>
                <?php foreach ($scors as $key => $value) { ?>
                <tr>
                    <td><?php echo $key;?></td>
                    <td><?php echo $value;?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    
        <div class="col-md-6 text-center">
            <h3><?php echo "Evaluations Score-wise";?></h3>
            <div class="ct-chart" id="chart2"></div>
            <br />
            <table class="table table-responsive table-condensed text-left">
                <tr>
                    <th><?php echo __('Evaluations');?></th>
                    <th><?php echo __('Count');?></th>
                </tr>
                <?php foreach ($envEvaluation as $key => $value) { ?>
                <tr>
                    <td>Score <= <?php echo $key;?></td>
                    <td><?php echo $value;?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    
</div>
<div class="row">
    <div class="col-md-12">
        <h3><?php echo __('Corrective Preventive Actions');?></h3>
        <table class="table table-responsive table-condensed text-left">
            <tr>
                <th><?php echo __('CAPA'); ?></th>
                <th><?php echo __('#'); ?></th>
                <th><?php echo __('Source'); ?></th>
                <th><?php echo __('Categoty'); ?></th>
                <th><?php echo __('Activity'); ?></th>
                <th><?php echo __('Identifications'); ?></th>
                <th><?php echo __('Current Status'); ?></th>
            </tr>
            <?php foreach ($capas as $capa) { ?>
                <tr>
                    <td><?php echo $capa['CorrectivePreventiveAction']['name']; ?></td>
                    <td><?php echo $capa['CorrectivePreventiveAction']['number']; ?></td>
                    <td><?php echo $capa['CapaSource']['name']; ?></td>
                    <td><?php echo $capa['CapaCategory']['name']; ?></td>
                    <td><?php echo $capa['EnvActivity']['title']; ?></td>
                    <td><?php echo $capa['EnvIdentification']['title']; ?></td>
                    <td><?php echo $capa['CorrectivePreventiveAction']['current_status']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>


<div class="row">

    <div class="col-md-3">
        <div class="thumbnail">
            <div class="caption">
                <h4><?php echo __('Add Checklist'); ?></h4>
                <p>
                    <?php
                    echo __('Before you add checklist make sure you have added ');
                    echo $this->Html->link(__('Questionnaire Categories'), array('controller' => 'environment_questionnaire_categories', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                    echo $this->Html->link(__('Questions'), array('controller' => 'environment_questionnaires', 'action' => 'index'), array('class' => 'text-primary'));                    
                    ?>
                </p>
                <div class="btn-group">
                    <?php echo $this->Html->link(__('Add'), array('controller' => 'environment_checklists', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                    <?php echo $this->Html->link(__('See All'), array('controller' => 'environment_checklists', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                    <?php echo $this->Html->link(' ' . $countCustomers, array('controller' => 'environment_checklists', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('Checklist'))); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="thumbnail">
            <div class="caption">
                <h4><?php echo __('Activities'); ?></h4>
                <p>
                    <?php
                    echo __('Before you add activities make sure you have added ');
                    echo $this->Html->link(__('Department'), array('controller' => 'departments', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                    echo $this->Html->link(__('Branches'), array('controller' => 'branches', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                    echo $this->Html->link(__('Employees'), array('controller' => 'employees', 'action' => 'index'), array('class' => 'text-primary'));
                    ?>
                </p>
                <div class="btn-group">
                    <?php echo $this->Html->link(__('Add'), array('controller' => 'env_activities', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                    <?php echo $this->Html->link(__('See All'), array('controller' => 'env_activities', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                    <?php echo $this->Html->link(' ' . $countClientProposals, array('controller' => 'env_activities', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('Activities'))); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="thumbnail">
            <div class="caption">
                <h4><?php echo __('Identifications'); ?></h4>
                <p>
                    <?php
                    echo __('Before you add Identifications make sure you have added ');
                    echo $this->Html->link(__('Activities'), array('controller' => 'env_activities', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                    echo $this->Html->link(__('Impacts'), array('controller' => 'env_impacts', 'action' => 'index'), array('class' => 'text-primary'));
                    ?>
                </p>
                <div class="btn-group">
                    <?php echo $this->Html->link(__('Add'), array('controller' => 'env_identifications', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                    <?php echo $this->Html->link(__('See All'), array('controller' => 'env_identifications', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                    <?php echo $this->Html->link(' ' . $countProposalFollowups, array('controller' => 'env_identifications', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('Identifications'))); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="thumbnail">
            <div class="caption">
                <h4><?php echo __('Evaluations'); ?></h4>
                <p>
                    <?php
                    echo __('Before you add Evaluations make sure you have added ');
                    echo $this->Html->link(__('Activities'), array('controller' => 'env_activities', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                    echo $this->Html->link(__('Identifications'), array('controller' => 'env_identifications', 'action' => 'index'), array('class' => 'text-primary'));
                    ?>
                </p>
                <div class="btn-group">
                    <?php echo $this->Html->link(__('Add'), array('controller' => 'env_evaluations', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                    <?php echo $this->Html->link(__('See All'), array('controller' => 'env_evaluations', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                    <?php echo $this->Html->link(' ' . $countProposalFollowups, array('controller' => 'env_evaluations', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('Evaluations'))); ?>
                </div>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
var labels = <?php echo json_encode(array_keys($scors));?>;
var data = {
    series: <?php echo json_encode(array_values($scors));?>
};
var sum = function(a, b) { return a + b };
var chart = new Chartist.Pie('#chart1', data, {
    donut: true,showLabel: true,labelDirection: 'explode',chartPadding: {left: 0, right:0},labelOffset: 10,
    labelInterpolationFnc: function(value, idx) {
        var percentage = Math.round(value / data.series.reduce(sum) * 100) + '%';
        return  percentage  + '-' +labels[idx] + '('+ value + ')';
    }
});
chart.on('draw', function(data) {    
  if(data.type === 'slice') {
    // Get the total path length in order to use for dash array animation
    var pathLength = data.element._node.getTotalLength();
    // Set a dasharray that matches the path length as prerequisite to animate dashoffset
    data.element.attr({
      'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
    });
    // Create animation definition while also assigning an ID to the animation for later sync usage
    var animationDefinition = {
      'stroke-dashoffset': {
        id: 'anim' + data.index,
        dur: 2000,
        from: -pathLength + 'px',
        to:  '0px',
        easing: Chartist.Svg.Easing.easeOutQuint,
        // We need to use `fill: 'freeze'` otherwise our animation will fall back to initial (not visible)
        fill: 'freeze'
      }
    };
    // If this was not the first slice, we need to time the animation so that it uses the end sync event of the previous animation
    if(data.index !== 0) {
      animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
    }
    // We need to set an initial value before the animation starts as we are not in guided mode which would do that for us
    data.element.attr({
      'stroke-dashoffset': -pathLength + 'px'
    });
    // We can't use guided mode as the animations need to rely on setting begin manually
    // See http://gionkunz.github.io/chartist-js/api-documentation.html#chartistsvg-function-animate
    data.element.animate(animationDefinition, true);
  }
});
</script>

<script type="text/javascript">
var labels2 = <?php echo json_encode(array_keys($envEvaluation));?>;
var data2 = {
    series: <?php echo json_encode(array_values($envEvaluation));?>
};
var sum = function(a, b) { return a + b };
var chart2 = new Chartist.Pie('#chart2', data2, {
    donut: true,showLabel: true,labelDirection: 'explode',chartPadding: {left: 0, right:0},labelOffset: 10,
    labelInterpolationFnc: function(value, idx) {
        var percentage = Math.round(value / data2.series.reduce(sum) * 100) + '%';
        return '<=' + labels2[idx] + ' ('+ value + ') (' + percentage + ') ';
    }
});
chart2.on('draw', function(data2) {    
  if(data2.type === 'slice') {
    // Get the total path length in order to use for dash array animation
    var pathLength = data2.element._node.getTotalLength();
    // Set a dasharray that matches the path length as prerequisite to animate dashoffset
    data2.element.attr({
      'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
    });
    // Create animation definition while also assigning an ID to the animation for later sync usage
    var animationDefinition = {
      'stroke-dashoffset': {
        id: 'anim' + data2.index,
        dur: 2000,
        from: -pathLength + 'px',
        to:  '0px',
        easing: Chartist.Svg.Easing.easeOutQuint,
        // We need to use `fill: 'freeze'` otherwise our animation will fall back to initial (not visible)
        fill: 'freeze'
      }
    };
    // If this was not the first slice, we need to time the animation so that it uses the end sync event of the previous animation
    if(data2.index !== 0) {
      animationDefinition['stroke-dashoffset'].begin = 'anim' + (data2.index - 1) + '.end';
    }
    // We need to set an initial value before the animation starts as we are not in guided mode which would do that for us
    data2.element.attr({
      'stroke-dashoffset': -pathLength + 'px'
    });
    // We can't use guided mode as the animations need to rely on setting begin manually
    // See http://gionkunz.github.io/chartist-js/api-documentation.html#chartistsvg-function-animate
    data2.element.animate(animationDefinition, true);
  }
});
</script>