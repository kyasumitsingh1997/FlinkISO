<?php
App::uses('AppModel', 'Model');
/**
 * Customer Model
 *
 * @property Branch $Branch
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property CustomerComplaint $CustomerComplaint
 * @property CustomerFeedback $CustomerFeedback
 * @property DeliveryChallan $DeliveryChallan
 * @property DocumentAmendmentRecordSheet $DocumentAmendmentRecordSheet
 * @property PurchaseOrder $PurchaseOrder
 */
class Customer extends AppModel {

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
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'customer_code' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'customer_since_date' => array(
			'date' => array(
				'rule' => array('date'),
			),
		),

		'phone' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'mobile' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
			),
		),
		'branch_id' => array(
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
		'lead_type' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		)
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
                'order' => '' 
		),'PreparedBy' => array(
                'className' => 'Employee',
                'foreignKey' => 'prepared_by',
                'conditions' => '',
                'fields' => array('id', 'name'),
                'order' => ''
		),'Branch' => array(
			'className' => 'Branch',
			'foreignKey' => 'branch_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),'Employee' => array(
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
		)
	);

	public $hasMany = array(
            'CustomerContact' => array(
			'className' => 'CustomerContact',
			'foreignKey' => 'customer_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),'Proposal' => array(
			'className' => 'Proposal',
			'foreignKey' => 'customer_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),'ProposalFollowup' => array(
			'className' => 'ProposalFollowup',
			'foreignKey' => 'customer_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),'CustomerMeeting' => array(
			'className' => 'CustomerMeeting',
			'foreignKey' => 'customer_id',
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

	public $customArray = array(
		    'customer_type' => array(
				'0' => 'Company',
				'1' => 'Individual',
		    ),'lead_type' => array(
				'0' => 'New',
				'1' => 'Current',
		    ),
	    );
}
