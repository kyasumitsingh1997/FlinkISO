<div class="row">
<div class="col-md-12">

  <h1>6.Resource management </h1>
  <h3>6.1. Provision of resources </h3>
  <p>You must provide resources (people,
    finance, infrastructure, etc.) in order to apply and maintain the QMS and
    continually improve its effectiveness, and increase the level of customer
    satisfaction through fulfillment of their requirements. Resources need to be
    reviewed periodically (especially if you increase business volume) to determine
    whether the available resources are enough or if you need to provide more. </p>
  <h3>6.2. Human resources </h3>
  <p><strong>6.2.1. General </strong></p>
  <p>It is necessary to have a list of
    all jobs and their descriptions with necessary <?php echo $this->Html->link('competence',array('controller'=>'competency_mappings','action'=>'index'),array('class'=>'text-primary'));?> and defined
    responsibilities for the entire organization. </p>
  <p><strong>6.2.2. <?php echo $this->Html->link('Competence',array('controller'=>'competency_mapping','action'=>'index',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?>, <?php echo $this->Html->link('Training and Awareness',array('controller'=>'trainings','action'=>'index',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?></strong></p>
  <p>In order to reach necessary
    competence, the standard allows you to, besides training, take other actions.
    Such action can be, for example, to hire already trained and competent
    employees or to outsource some activities and processes. </p>
  <p>Also, you must evaluate the
    effectiveness of undertaken actions. Criteria for effectiveness can be the
    number of employees who successfully completed training, whether the training
    is performed according to plan, etc. </p>
<p>Each training must be backed with appropriate records (record of attendance, certificates,
    etc.) and entered into the employee's personnel file. </p>
  <h3>6.3. Infrastructure</h3>
  <p>The infrastructure includes
    buildings, workspace, equipment, process equipment (hardware and software), and
    support services. Many requirements for infrastructure could be included in
    legislation. </p>
  <h3>6.4. Work environment</h3>
<p>Working conditions (humidity,
    noise, light, temperature, vibration, etc.) are also, in most cases, defined by
    legislation. </p>
</div>
</div>
<div class="row">
  <div class="col-md-12"><hr /></div>
<div class="col-md-6 text-left">
  <?php echo $this->Html->link('5 : Management Responsibility',array('controller'=>'clauses','action'=>'management_responsibility'),array('class'=>'btn btn-lg btn-default'));?>
</div>
<div class="col-md-6 text-right">
  <?php echo $this->Html->link('7 : Product Realization',array('controller'=>'clauses','action'=>'product_realization'),array('class'=>'btn btn-lg btn-default'));?>
</div>
</div>