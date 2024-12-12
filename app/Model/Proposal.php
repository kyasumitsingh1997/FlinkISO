<?php
App::uses('AppModel', 'Model');
/**
 * Proposal Model
 *
 * @property Customer $Customer
 * @property Client $Client
 * @property Employee $Employee
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property ProposalFollowup $ProposalFollowup
 */
class Proposal extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'sr_no' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'title' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'customer_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'proposal_heading' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'branchid' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'departmentid' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'created_by' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'modified_by' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ApprovedBy' => array(
				'className' => 'Employee',
				'foreignKey' => 'approved_by',
				'conditions' => '',
				'fields' => array('id', 'name'),
				'order' => '' ),
		'PreparedBy' => array(
				'className' => 'Employee',
				'foreignKey' => 'prepared_by',
				'conditions' => '',
				'fields' => array('id', 'name'),
				'order' => ''
			),
		'Customer' => array(
			'className' => 'Customer',
			'foreignKey' => 'customer_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'CustomerContact' => array(
			'className' => 'CustomerContact',
			'foreignKey' => 'customer_contact_id',
			'conditions' => '',
			'fields' => array('id', 'name','email'),
			'order' => ''
		),
		'Employee' => array(
			'className' => 'Employee',
			'foreignKey' => 'employee_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'ProposalAssignedTo' => array(
			'className' => 'Employee',
			'foreignKey' => 'employee_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'SystemTable' => array(
			'className' => 'SystemTable',
			'foreignKey' => 'system_table_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'MasterListOfFormat' => array(
			'className' => 'MasterListOfFormat',
			'foreignKey' => 'master_list_of_format_id',
			'conditions' => '',
			'fields' => array('id', 'title', 'system_table_id'),
			'order' => ''
		),
		'BranchIds' => array(
			'className' => 'Branch',
			'foreignKey' => 'branchid',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'DepartmentIds' => array(
			'className' => 'Department',
			'foreignKey' => 'departmentid',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'StatusUserId' => array(
			'className' => 'User',
			'foreignKey' => 'status_user_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'ProposalFollowupRule' => array(
			'className' => 'ProposalFollowupRule',
			'foreignKey' => 'proposal_followup_rule_id',
			'conditions' => '',
			'fields' => array('id', 'rule','number_of_followups_required','followup_sequence'),
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ProposalFollowup' => array(
			'className' => 'ProposalFollowup',
			'foreignKey' => 'proposal_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
}
