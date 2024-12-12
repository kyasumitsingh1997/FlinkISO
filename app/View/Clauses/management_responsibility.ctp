<div class="row">
<div class="col-md-12">

  <h1>5.Management responsibility </h1>
  <h3>5.1. Management commitment</h3>
  <p>QMS implementation is your
    strategic decision that demonstrates your commitment to development and
    application of the QMS and continual improvement of its effectiveness. This
    commitment must be demonstrated through informing the organization about the importance
    of fulfilling customer requirements, compliance with legal and other
    requirements, establishing a quality policy and objectives, conducting
    management reviews, and providing needed resources. </p>
  <h3>5.2. Customer focus </h3>
  <p>In the QMS process model, the users
    make requirements for a product on one side, and demonstrate their reaction by
    expressing their satisfaction with the product on the other side. Those
    requirements must be identified and fulfilled in a way that increases customer
    satisfaction. (
    <?php echo $this->Html->link('Customer Feedbacks',array('controller'=>'customer_feedbacks','action'=>'index',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?>,
    <?php echo $this->Html->link('Customer Compaints',array('controller'=>'customer_complaints','action'=>'index',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?>)
	</p>
  <h3>5.3. Quality policy <small><?php echo $this->Html->link('Add Quality Policy',array('controller'=>'companies','action'=>'index',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?></small></h3>
  <p>The quality
    policy is a high-level document containing statements about the
    general direction of the organization, and its commitment to quality and
    customer satisfaction. It provides a framework for quality objectives and must
    be communicated to employees in a way they understand. </p>
  <h3>5.4. Planning</h3>
  <p><strong>5.4.1. Quality objectives </strong><small><?php echo $this->Html->link('Add Quality Objectives',array('controller'=>'companies','action'=>'index',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?></small></p>
  <p>The standard requires top-level
    management to establish quality objectives for appropriate functions and
    departments in the organization (HR, production, purchase, etc.). </p>
  <p>Quality objectives must be measurable, quantitative, and timed. They must be in
    line with the quality policy so it can be determined whether objectives are
    met, and if not, what should be done. </p>
  <p><strong>5.4.2. Quality management system planning </strong></p>
  <p>The top-level management must plan
    the quality management system in order to: </p>
  <ul>
    <li><strong>Fulfill requirements of clause 4.1
      ISO 9001:2008 standard. </strong>Most of these activities are
      performed &#8232;during implementation of the QMS; new needs for planning can
      emerge from changes to a process or &#8232;product/service,
    identifying possibilities for improvement, audits, etc. &#8232;</li>
    <li><strong>Accomplish quality objectives.</strong> In order to accomplish quality objectives, the organization must
    plan &#8232;resources, deadlines, responsibilities,
    and appropriate evidences. Since the objectives are changeable, this planning
    is a continuous process. &#8232;Also, the top-level management is
    required to maintain the integrity of the QMS when changes are planned and
    implemented in the quality management system. &#8232;</li>
  </ul>
<h3>5.5. Responsibility, authority and
    communication </h3>
  <p><strong>5.5.1. Responsibility and authority </strong></p>
  <p>Responsibilities and authorities
    must be precisely defined and communicated to all hierarchical levels of the
    organization. In specific situations (seasonal fluctuation of labor force,
    emergency situations, etc.), it is necessary to precisely document and
    communicate authorities, and especially the responsibilities of temporarily
    employed workers. </p>
  <p><strong>5.5.2. Management representative </strong></p>
  <p>The top-level management must
    appoint one of its members to be the management representative who will,
    besides his regular duties, perform activities related to the QMS. The management
    representative can't be someone outside the organization, and if the
    organization has multiple locations, it can appoint management representatives
    for each location who are subordinate to one head management representative. </p>
  <p><strong>5.5.3. Internal communication </strong></p>
  <p>Top-level management must establish
    communication processes in the organization. Basic directions of organizational
    communication are:</p>
  <ul>
    <li>Communication downwards
      (from manager to employee) &#8211; It is used for giving orders,
      coordination, and evaluation of employees; it can be performed by any means of
    interpersonal communication. &#8232;</li>
    <li>Communication upwards (from
    employee to manager) &#8211; In this kind of communication, managers &#8232;find out what employees think about their workplace, colleagues,
    organization, and ideas for business improvement. Some examples of this
    communications are reports, suggestion boxes, etc. &#8232;</li>
  </ul>
<h3>5.6. Management review <small><?php echo $this->Html->link('Meetings',array('controller'=>'meetings','action'=>'index',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?></small></h3>
  <p><strong>5.6.1. General </strong></p>
  <p>At least once a year, the top-level
    management must review the QMS in order to determine its: </p>
  <ul>
    <li><strong>Appropriateness &#8211; </strong>does it
    serve its purpose and satisfy the needs of the organization? &#8232;</li>
    <li><strong>Adequacy &#8211; </strong>does the QMS
    conform to standard requirements? &#8232;</li>
    <li><strong>Applicability &#8211; </strong>are
    activities performed according to procedures?</li>
    <li><strong>Effective &#8211; </strong>does it
    accomplish planned results?&#8232;This review must
    evaluate possibilities for improvement and needs for changing the QMS, quality
    policy, and &#8232;objectives. &#8232;The difference between the
    management review and an audit is that results from an audit represent input
    elements for the management review, just like data analysis (clause 8.4 of ISO
    9001:2008). &#8232;</li>
  </ul>
<p><strong>5.6.2. Review input </strong></p>
  <p>Sources of information for the
    review are: </p>
  <p><strong>Audit results.</strong> Audit results (both external and internal) are usually contained
    in an audit report, and are defined as commendations (identified best
    practices), recommendations that don't have the status of a
    nonconformity, and nonconformities (minor and major). </p>
  <p><strong>Customer reaction.</strong> One of the best indicators of the successfulness of your QMS is
    customer reaction (both positive and negative). Expressed discontent with some
    parts of your QMS and/or product is sufficient reason to ask yourself what
    you're doing wrong, and make improvements. ( <?php echo $this->Html->link('Customer Feedbacks',array('controller'=>'customer_feedbacks','action'=>'index',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?>,
    <?php echo $this->Html->link('Customer Compaints',array('controller'=>'customer_complaints','action'=>'index',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?>)</p>
  <p><strong>Process performance
    and product conformity.</strong> You need
    to establish key performance indicators for each process, i.e., process
    objectives. Level of fulfillment of these objectives is a good basis for
    improvement of the process and the whole QMS. </p>
  <p><strong>Additional actions derived from
    previous management reviews.</strong> If you haven't completely
    or adequately performed actions determined on a previous management review, you
    need to define new (additional) actions on the current review. This includes
    analysis of reasons for not performing actions from the previous management
    review. </p>
  
  <p><strong>Changes that can
    influence the QMS. </strong>Management
    review can be undertaken several times if changes in a process or product are
    planned or implemented. This review must ensure that your QMS is adequate and
    applied before and after the change. </p>
  <p><strong>Recommendations for
    improvement. </strong>Recommendations
    for improvement can come as the result of an audit, or can be stated by a
    process owner and/or employees. The top-level management should consider those
    recommendations, taking into account the time and financial aspects. </p>
  <p><strong>5.6.3. Review output </strong></p>
  <p>The results of a management review
    are conclusions, and the actions emerging from those conclusions. The
    management review should result in conclusions regarding: </p>
  <p><strong>Improvement of
    effectiveness of the QMS and its processes. </strong>The
    effectiveness of the QMS and its processes can be improved by determining
    whether the processes produce the required results and taking actions to make
    processes provide satisfying results. </p>
  <p><strong>Product improvement related to
    customer requirements.</strong> Customer requirements can easily be
    implemented in a product in individual production (for familiar customers,
    according to project). In the case of mass production, systematic market
    research will be needed. </p>
  <p><strong>Resources needed. </strong>The management review is a good opportunity to review the need
    for resources. If you determine new needs for resources, then you should
    conduct an emergency management review to analyze these needs, considering the
    financial aspects. </p>
<p>The standard requires you to keep records about management review considering
    inputs and outputs, together with actions that should be taken. </p>
</div>
</div>
<div class="row">
  <div class="col-md-12"><hr /></div>
<div class="col-md-6 text-left">
  <?php echo $this->Html->link('4 : Quality Management System',array('controller'=>'clauses','action'=>'quality_management_system'),array('class'=>'btn btn-lg btn-default'));?>
</div>
<div class="col-md-6 text-right">
  <?php echo $this->Html->link('6 : Resource Management',array('controller'=>'clauses','action'=>'resource_management'),array('class'=>'btn btn-lg btn-default'));?>
</div>
</div>