<?php
App::uses('AppModel', 'Model');
/**
 * ProposalFollowup Model
 *
 * @property Proposal $Proposal
 * @property Customer $Customer
 * @property Client $Client
 * @property Employee $Employee
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 */
class ProposalFollowup extends AppModel {

      public $displayField = "followup_date";
      
    	public $validate = array(
		'sr_no' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'followup_heading' => array('notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required' => true,
			),
		),
		'followup_date' => array(
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
		'Proposal' => array(
			'className' => 'Proposal',
			'foreignKey' => 'proposal_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'Customer' => array(
			'className' => 'Customer',
			'foreignKey' => 'customer_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'CustomerMeeting' => array(
			'className' => 'CustomerMeeting',
			'foreignKey' => 'customer_meeting_id',
			'conditions' => '',
			'fields' => array('id', 'customer_id', 'name'),
			'order' => ''
		),
		'CustomerContact' => array(
			'className' => 'CustomerContact',
			'foreignKey' => 'customer_contact_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Employee' => array(
			'className' => 'Employee',
			'foreignKey' => 'employee_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'FollowupAssignedTo' => array(
			'className' => 'Employee',
			'foreignKey' => 'followup_assigned_to',
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
		)
	);

}
