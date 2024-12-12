<div class="row">
<div class="col-md-12">
  <h1>8.Measurement, analysis and
    improvement </h1>
  <h3>8.1. General </h3>
  <p>This requirement should not be
    equated with the requirement for managing equipment for monitoring and
    measuring from clause 7.6 of the standard. This is about a wider aspect of
    monitoring and measuring. Information derived from monitoring, measurement, and
    analysis represents input in the process of improvement and management review. </p>
  <h3>8.2. Monitoring and measurement </h3>
  <p><strong>8.2.1. Customer satisfaction</strong> <?php echo $this->Html->link('Customer Feedbacks',array('controller'=>'customer_feedbacks','action'=>'index',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?></p>
  <p>Here it is required to measure your
    own performance as a supplier in order to get information about users'
    observations, and the extent to which you fulfilled their requirements.
    Monitoring customer satisfaction level must be a constant activity in order to
    determine trends, and because opinion about your performance is changeable.
    Information about customer satisfaction can be collected via phone, interview
    and questionnaire, direct contact with the user on the field, etc. </p>
  <p><strong>8.2.2. Internal audit </strong> <?php echo $this->Html->link('Internal Audits',array('controller'=>'internal_audit_plans','action'=>'lists',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?></p>
  <p>The goal of an internal audit is
    not to determine nonconformity; its goal is to check whether your QMS: </p>
  <p> a) &nbsp;Complies with the requirements of ISO 9001 and requirements of your
    organization &#8232;</p>
  <p> b) &nbsp;Is effectively implemented and maintained &#8232;</p>
  <p>At the end of the audit you will
    get audit results by evaluating data you collected during the audit. Audit
    results can be manifested as: praise, recommendations for improvements, and
    nonconformities (major and minor). Verification of taken actions can be needed,
    and in that case the next step is a follow-up audit. </p>
  <p>A documented
    procedure for internal audit must be established and records about internal audits must be
    kept. </p>
  <p><strong>8.2.3. Monitoring and measurement of processes </strong>(?)</p>
  <p>By planning a process, you
    determine the results to be achieved during process realization. To show the
    capability of the process to achieve planned results, you must define suitable
    methods for monitoring and measuring of process performance. </p>
  <p>For measuring a process, it is
    necessary to clearly define the performance of the process. </p>
  <p><strong>8.2.4. Monitoring and measurement of product </strong>(?)</p>
  <p>Clearly defined characteristics of
    a product to monitor and measure may be included in project documentation,
    product specification, product description, user's requirements, etc.
    Monitoring and measurement of a product sometimes can be conducted during
    monitoring and measurement of a process. In each case,
    measuring of the product (dimensions, microbiological and chemical analyses,
    safety requirements, etc.) must be supported by calibrated measuring equipment
    and proper devices for monitoring. Monitoring can be done visually in
    cases like comparison of color, etc. </p>
<p>When deviation from defined product
    characteristics is identified, the delivery will be approved
    by a relevant authority, and eventually, by the customer, or you will
    follow the requirements of clause 8.3 Control of nonconforming product. </p>
  <h3>8.3. Control of nonconforming product (?)</h3>
  <p>Product that does not conform to
    product requirements can be detected during the realization process (while is
    still at the supplier) &#8211; delivery must be stopped, or after delivery
    (when it is at the customer or on the market) &#8211; undesirable use must be
    prevented. For managing products that have a nonconformity,
    an appropriate procedure must be established that suits the needs of the organization,
    and appropriate records should be kept. </p>
  <p>Non-conforming
    product must be treated by one or more of the following ways: </p>
  <p><strong>1) Taking actions to remove detected
    nonconformity </strong>(?)</p>
  <p>Options for removal of detected
    nonconformity can be: </p>
  <p> a) &nbsp;Correction as a measure to remove a detected nonconformity may involve
    modification, to conform product to the requirements, or correction as a
    measure to affect parts of nonconforming products or their replacement to make
    it acceptable for the intended use. &#8232;</p>
  <p> b) &nbsp;Approving its use, release, or acceptance based on subsequent approval
    from the relevant institution and, where applicable, from the user. &#8232;</p>
  <p>Subsequent approval is approval for
    use or acceptance of product that does not conform to the specified
    requirements. If it is determined that requirements for product safety,
    microbiological and chemical parameters are above determined values, product
    use can't be subsequently approved. </p>
  <p><strong>2) Taking actions to stop its
    original planned use or application. </strong>(?)</p>
  <p>By applying this action, the
    product is given write-off status and a decision for its recycling or
    destruction is made. </p>
<p>If there is the assessment that
    nonconforming product will result in serious consequences for the user (for
    example: injury, disease, death) or can affect a wider geographical area, a
    decision about informing the public and product withdrawal must be made. </p>
  <h3>8.4. Analysis of data </h3>
  <p>During planning and maintaining
    your QMS, you will create a variety of different data; you must group and
    analyze them in such a way that you can discover some trends that may indicate
    problems in your QMS, and show you space to improve. Results can be input in a
    management review. </p>
  <h3>8.5. Improvement </h3>
  <p><strong>8.5.1. Continual improvement </strong>(?)</p>
  <p>Appropriate activities must be
    taken in order to ensure continual improvement of your QMS. Those activities
    represent the process of taking actions based on quality policy and objectives,
    audit results, data analyses, corrective and preventive actions, and management
    review. </p>
  <p><strong>8.5.2. Corrective actions </strong><?php echo $this->Html->link('Corrective Actions',array('controller'=>'corrective_actions','action'=>'index',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?></p>
  <p>Corrective action is taken when a nonconformity is discovered &#8211; internal (on product
    or QMS), and external nonconformity &#8211; from external sources, like
    <?php echo $this->Html->link('Customer Complaints',array('controller'=>'customer_complaints','action'=>'index',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?>, reports of relevant institutions, etc. A corrective action
    is intended to remove the cause of the nonconformity and prevent its
    reoccurring, and records about corrective action must be kept. </p>
  <p>A documented
    procedure must define requirements for: </p>
  <p>Reviewing nonconformity (including
    customer complaints).Reviewing a nonconformity involves consideration of the place where the
    nonconformity is discovered and occurred, origin of the nonconformity,
    consequences, etc. That is how you will decide whether to take corrective
    action or just correction. Customer complaints could be reviewed, for example
    &#8211; from the standpoint of their merits. </p>
  <p>Determining the causes of
    nonconformities. It is very useful to have some
    mechanism to determine the cause of a nonconformity.
    The point is to take actions to prevent recurrence of nonconformities. There
    can be more than one cause of nonconformities. In that case, you must
    prioritize in which order you will remove them, and depend on the consequences
    to define suitable corrective actions. </p>
  <p>Evaluating need for
    actions that will prevent nonconformity recurrence. The scope of corrective actions and
    resources for their implementation will depend on the consequences that a nonconformity has on business results, products,
    processes, and especially on customer satisfaction. When taking corrective
    actions, priorities should be defined. </p>
  <p>Defining and
    application of necessary actions. When defining corrective action or priorities for implementing
    more corrective actions, you should apply it, define the person responsible for
    its implementation, and ensure the objectives prove that the action is
    implemented (bill, report, photo, etc.). </p>
  <p>Recording results
    of actions taken. Appropriate records about the results of actions taken must be
    kept. Based on these results you can define the status of a corrective action
    (for example: implemented partially, in whole, etc.); also, you should take into
    account the results and status of previous corrective and preventive actions. </p>
  <p>Reviewing effectiveness of actions
    taken. Actions taken will be effective if there is no
    reoccurrence of nonconformity. </p>
  <p><strong>8.5.3. Preventive actions </strong><?php echo $this->Html->link('Corrective Actions',array('controller'=>'corrective_actions','action'=>'index',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?></p>
  <p>To define preventive actions, data
    sources that indicate potential nonconformities must be identified. Data
    sources could be connected to data analyses, identified trends, statistical
    results, etc. </p>
  <p>Procedure for preventive actions must be documented along with appropriate records. </p>

</div>
</div>
<div class="row">
  <div class="col-md-12"><hr /></div>
<div class="col-md-6 text-left">
  <?php echo $this->Html->link('7 : Product Realization',array('controller'=>'clauses','action'=>'product_realization'),array('class'=>'btn btn-lg btn-default'));?>
</div>
<div class="col-md-6 text-right"></div>
</div>