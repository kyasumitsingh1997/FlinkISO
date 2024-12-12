<?php
App::uses('AppModel', 'Model');
/**
 * Meeting Model
 *
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property DocumentAmendmentRecordSheet $DocumentAmendmentRecordSheet
 * @property MeetingBranch $MeetingBranch
 * @property MeetingDepartment $MeetingDepartment
 * @property MeetingEmployee $MeetingEmployee
 * @property MeetingTopic $MeetingTopic
 */
class Meeting extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'title' => array('notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required' => true,
			),
		),
		'meeting_details' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
                'scheduled_meeting_from' => array(
			'notBlank' => array(
				'rule' => array('date'),
			),
		),
                'scheduled_meeting_to' => array(
			'notBlank' => array(
				'rule' => array('date'),
			),
		),

		'employee_by' => array(
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
		'EmployeeBy' => array(
			'className' => 'Employee',
			'foreignKey' => 'employee_by',
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
		'Standard' => array(
			'className' => 'Standard',
			'foreignKey' => 'standard_id',
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
		'SupplierRegistration' => array(
			'className' => 'SupplierRegistration',
			'foreignKey' => 'supplier_registration_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'MeetingBranch' => array(
			'className' => 'MeetingBranch',
			'foreignKey' => 'meeting_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => array('id', 'branch_id'),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'MeetingDepartment' => array(
			'className' => 'MeetingDepartment',
			'foreignKey' => 'meeting_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => array('id', 'department_id'),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'MeetingEmployee' => array(
			'className' => 'MeetingEmployee',
			'foreignKey' => 'meeting_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => array('id', 'employee_id'),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'MeetingAttendee' => array(
			'className' => 'MeetingAttendee',
			'foreignKey' => 'meeting_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => array('id', 'employee_id'),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'MeetingTopic' => array(
			'className' => 'MeetingTopic',
			'foreignKey' => 'meeting_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public $customArray = array(
		'meeting_status' => array(
			'0' => 'Scheduled',
			'1' => 'Cancled',
			'2' => 'Completed',
			'3' => 'Open'			
	    ),
	    'meeting_type' => array(
			'0' => 'Internal',
			'1' => 'External'			
	    )
	    );
}
