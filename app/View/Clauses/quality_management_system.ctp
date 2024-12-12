<div class="row">
<div class="col-md-12">

<h1>4.Quality Management System </h1>
  <h3>4.1. General </h3>
  <p>According to the requirements of
    ISO 9001:2008, an organization must: Establish. Establishing a QMS entails the planning phase, which includes:</p>
  <ul>
    <li><strong>Defining the purpose of the organization </strong> : The
      organization should identify its users and other interested parties; as well as
      their requirements, needs, and expectations in order to determine its intended
    output elements. </li>
    <li><strong>Defining policy and objectives of
    the organization </strong> : The organization's policy should be based on analysis
    of requirements, needs, and expectations. The policy should provide the
    framework for establishing the organization's objectives. &#8232;</li>

    <li><strong>Document</strong> :  The organization must determine
    process documentation, i.e., determine which processes need to be documented.
    The main purpose of documentation is to enable consistent and stable process
    execution. &#8232;Determining which processes should be documented should be based
    on: 
    <ul>
      <li>Size of organization and type of its activities &#8232;</li>
      <li>Complexity of its processes and their interaction &#8232;</li>
      </ul>
    </li>
    <li><strong>Employees competence &#8232;Process documenting can be done using several different methods</strong> :
        graphical, written instructions, control charts, flowcharts, etc. &#8232;Apply and maintain quality management system. Once you have documented your QMS, you must behave in the way
        you defined within your QMS documentation. </li>
     <li><strong>Continually improve QMS effectiveness </strong>  : 
        Continual improvement is ongoing
        activity to increase capability to fulfill planned requirements set by the QMS. &#8232;Further, this clause requires the organization to: &#8232;Determine processes necessary for the quality management system
        and apply them throughout the organization. These processes
        include management, resources, realization and measurement, analyzing, and
        improvement. The organization must manage these processes and appoint a process
        owner for each process. Top-level management must determine individual roles
        and responsibilities to ensure application, maintenance, and improvement of
        each process and its interaction with other processes. &#8232;Determine order and interaction between processes. While determining order and interactions between processes, the
        following should be considered: &#8232;
        <ul>
          <li>User for each process &#8232;</li>
          <li>Inputs and outputs of each process</li>
          <li>Which processes are related? &#8232;</li>
          <li>Logical sequence and order of related processes </li>
          <li>Effectiveness and efficiency of each process &#8232;Determine criteria and methods needed to ensure process
            execution and effectiveness of process management. (see chapters 7 and 8) &#8232;Ensure availability of resources and information needed for
            support of processes and their monitoring. (see chapter 6) &#8232;Monitor, measure
            and, when appropriate, analyze the processes. (see chapter 8) Apply actions
            needed for accomplishing planned results and continuous process &#8232;improvement. &#8232;</li>
      </ul>
  </li></ul>
  <h3>4.2. Document requirements </h3>
  <p><strong>4.2.1. General </strong></p>
<p>The QMS must be documented and the
    volume of documentation suited to the organization's needs, size and type of
    activities, processes, and employees' competence. </p>
  <p><strong>QMS documentation contains: </strong></p>
  <p>a)
    &nbsp;Documents explicitly required by ISO
    9001:2008 &#8211; Quality Policy, Quality Objectives, Quality Manual &#8232;and 6 mandatory procedures and 21
    mandatory records. &#8232;</p>
  <p> <strong>6 mandatory procedures</strong></p>
  <ol>
   	<li><?php echo $this->Html->link('Control of documents (4.2.3)',array('controller'=>'evidences','action'=>'lists'),array('class'=>'text-primary'));?></li>
    <li>Control of records (4.2.4)</li>
    <li><?php echo $this->Html->link('Internal audit (8.2.2)',array('controller'=>'internal_audits','action'=>'lists'),array('class'=>'text-primary'));?></li>
    <li>Control of nonconforming product (8.3)</li>
    <li><?php echo $this->Html->link('Corrective action (8.5.2)',array('controller'=>'corrective_preventive_actions','action'=>'lists'),array('class'=>'text-primary'));?></li>
    <li><?php echo $this->Html->link('Preventive action (8.5.3)',array('controller'=>'corrective_preventive_actions','action'=>'lists'),array('class'=>'text-primary'));?></li>
  </ol>
  <p><strong>21
  mandatory records</strong></p>
  <ol>
  	<li><strong>5.6.1</strong> : <?php echo $this->Html->link('Management Review, Management review minutes',array('controller'=>'meetings','action'=>'index'),array('class'=>'text-primary'));?></li>
    <li><strong>6.2.2e</strong> : 
    	<?php echo $this->Html->link('Education',array('controller'=>'employees','action'=>'index'),array('class'=>'text-primary'));?>, 
        <?php echo $this->Html->link('Trainings',array('controller'=>'courses','action'=>'index'),array('class'=>'text-primary'));?>, 
        <?php echo $this->Html->link('Skills and experiences',array('controller'=>'competency_mappings','action'=>'index'),array('class'=>'text-primary'));?>, 
        <?php echo $this->Html->link('Training Record & matrix',array('controller'=>'trainings','action'=>'index'),array('class'=>'text-primary'));?>, 
        <?php echo $this->Html->link('Resumes',array('controller'=>'employees','action'=>'index'),array('class'=>'text-primary'));?>
	</li>
    <li><strong>7.1d</strong> : Evidence of realization process - Project quality plan</li>
    <li><strong>7.2.2</strong> : Results of the review of requirements related to the product and actions arising from the review	Change review</li>
    <li><strong>7.3.2</strong> : Design and development inputs relating to product requirements	Customer specifications</li>
    <li><strong>7.3.4</strong> : Results of design and development reviews and any necessary actions	Design development minutes</li>
    <li><strong>7.3.5</strong> : Results of design and development verification and any necessary actions	Design plans, test plans </li>
    <li><strong>7.3.6</strong> : Results of design and development validation and any necessary actions	User acceptance test plans, acceptance records </li>
    <li><strong>7.3.7</strong> : Results of the review of design and development changes and any necessary actions	Design review minutes, change requests</li>
    <li><strong>7.4.1</strong> : Results of supplier evaluations and any necessary actions arising from the evaluations -	
    	<?php echo $this->Html->link('Supplier evaluation',array('controller'=>'summery_of_supplier_evaluations','action'=>'index'),array('class'=>'text-primary'));?>, 
		<?php echo $this->Html->link('approved suppliers list',array('controller'=>'list_of_acceptable_suppliers','action'=>'index'),array('class'=>'text-primary'));?>
   	  </li>
    <li><strong>7.5.2d</strong> : Demonstrate the validation of processes where the resulting output cannot be verified by subsequent monitoring or measurement - Schedule of achieved results</li>
    <li><strong>7.5.3</strong> : The unique identification of the 
    	<?php echo $this->Html->link('product',array('controller'=>'products','action'=>'index'),array('class'=>'text-primary'));?>, where traceability is a requirement - 
    	<?php echo $this->Html->link('Equipment register',array('controller'=>'devices','action'=>'index'),array('class'=>'text-primary'));?></li>
    <li><strong>7.5.4</strong> : Customer property that is lost, damaged or otherwise found to be unsuitable for use - Delivery notes, site visits, defective materials report</li>
    <li><strong>7.6</strong> : Results of calibration and verification of measuring equipment 
    	<?php echo $this->Html->link('Calibration certificates',array('controller'=>'calibrations','action'=>'index'),array('class'=>'text-primary'));?>, </li>
    <li><strong>7.6 Validity</strong> : of the previous measuring results when the measuring equipment is found not to conform to requirements - Test plan results</li>
    <li><strong>7.6a</strong> Basis used for calibration or verification of measuring equipment where no international or national measurement standards exist - Customer specification, corporate standards</li>
    <li><strong>8.2.2</strong> : Internal audit results and follow-up action
    	<?php echo $this->Html->link('Internal audit report',array('controller'=>'internal_audits','action'=>'index'),array('class'=>'text-primary'));?>, </li>
    	</li>
    <li><strong>8.2.4</strong> : Indication of the person(s) authorising release of product	Product acceptance/release report</li>
    <li><strong>8.3</strong> :  Nature of the product nonconformities and any subsequent actions taken, including concessions obtained	Non conformance report, consession report</li>
    <li><strong>8.5.2</strong> : 	
    	<?php echo $this->Html->link('Results of corrective action',array('controller'=>'corrective_preventive_actions','action'=>'index'),array('class'=>'text-primary'));?>Corrective action report</li>
    <li><strong>8.5.3</strong> : 
    	<?php echo $this->Html->link('Results of preventative action',array('controller'=>'corrective_preventive_actions','action'=>'index'),array('class'=>'text-primary'));?>Preventative action report</li>
  </ol>
<p>b)
  &nbsp;Documents and records defined by the
    organization as necessary &#8211; procedures describing processes, &#8232;instructions for some activities, flowcharts, quality plans,
    records of monitoring and measurement, etc. &#8232;</p>
  <p><strong>4.2.2. Quality Manual </strong><small><?php echo $this->Html->link('Upload Quality Manual',array('controller'=>'companies','action'=>'index',$this->Session->read('User.company_id')),array('class'=>'text-primary'));?></small></p>
<p>The standard requires the
    organization to establish and maintain a quality manual. This is a high-level document, and it contains: </p>
  <ul>
    <li>Purpose and scope &#8211; defines
      the organization and its organizational structure, responsibilities and
    authorities, location, and its business &#8232;</li>
    <li>Details about exclusions and their
    justification (exclusions can be made only in clause 7) &#8232;</li>
    <li>Procedures or reference to them
    &#8211; the Quality Manual can contain all procedures or refer to procedures &#8232;</li>
    <li>Description of interactions between
    processes &#8211; this is usually given through a process model or &#8232;process map, which can be part of the Quality Manual or given as
    a separate document &#8232;</li>
  </ul>
<p><strong>4.2.3. Control of documents </strong></p>
  <p>The standard requires you to
    establish a documented procedure that defines
    control of documents. </p>
  <p>The documents will be reviewed
    periodically and updated with new information regarding processes. All changes
    must be identified, and if they change the essence of the document, then a new
    version of the document is issued. You must ensure that the old document is
    removed from the place of use and replaced with the new version. </p>
  <p><strong>4.2.4. Control of records </strong></p>
<p>Record control should be determined
    with an appropriate procedure that prescribes the method
    of identifying, preserving, and protecting records. Usually, the records are
    kept on a custom form you define based on standard requirements and your needs.
    Once filled in and signed, these forms become important documents that serve as
    evidence of performing certain activities, and they demonstrate conformity with
    standard requirements and the effectiveness of your QMS. </p>
</div>
</div>
<div class="row">
  <div class="col-md-12"><hr /></div>
<div class="col-md-6 text-left">
  <?php echo $this->Html->link('3 : Customer Focus',array('controller'=>'clauses','action'=>'customer_focus'),array('class'=>'btn btn-lg btn-default'));?>
</div>
<div class="col-md-6 text-right">
  <?php echo $this->Html->link('5 : Management Resposibility',array('controller'=>'clauses','action'=>'management_responsibility'),array('class'=>'btn btn-lg btn-default'));?>
</div>
</div>