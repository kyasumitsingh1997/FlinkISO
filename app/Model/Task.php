<?php
App::uses('AppModel', 'Model');
/**
 * Task Model
 *
 * @property MasterListOfFormat $MasterListOfFormat
 * @property User $User
 * @property Schedule $Schedule
 * @property SystemTable $SystemTable
 * @property TaskStatus $TaskStatus
 */
class Task extends AppModel {

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
		'employee_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'description' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'sequence' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'priority' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'schedule_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'start_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'end_date' => array(
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
		),'MasterListOfFormat' => array(
			'className' => 'MasterListOfFormat',
			'foreignKey' => 'master_list_of_format_id',
			'conditions' => '',
			'fields' => array('id', 'title', 'system_table_id'),
			'order' => ''
		),'Process' => array(
			'className' => 'Process',
			'foreignKey' => 'process_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),'ProcessTeam' => array(
			'className' => 'ProcessTeam',
			'foreignKey' => 'process_team_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),'CustomerComplaint' => array(
			'className' => 'CustomerComplaint',
			'foreignKey' => 'customer_complaint_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),'Milestone' => array(
			'className' => 'Milestone',
			'foreignKey' => 'milestone_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),'ProjectActivity' => array(
			'className' => 'ProjectActivity',
			'foreignKey' => 'project_activity_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Schedule' => array(
			'className' => 'Schedule',
			'foreignKey' => 'schedule_id',
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
/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'TaskStatus' => array(
			'className' => 'TaskStatus',
			'foreignKey' => 'task_id',
			'dependent' => false,
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
		    'rag_status' => array(
				'0' => 'Danger',
				'1' => 'Warning',
				'2' => 'Success',
				'3' => 'Default',
			),'task_status' => array(
				'0' => 'On going', '1' => 'Completed','2'=>'Not Started','3'=>'Canceled'
			),'task_type' => array(
				0=>'General',1=>'Process Related',2=>'Project Related',3=>'Customer Complaint'
			),'priority' => array(
				'0' => 'High', '1' => 'Medium','2'=>'Low'
			)
	    );

}
