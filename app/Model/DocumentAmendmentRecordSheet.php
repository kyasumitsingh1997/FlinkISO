<?php
App::uses('AppModel', 'Model');
/**
 * DocumentAmendmentRecordSheet Model
 *
 * @property Branch $Branch
 * @property Department $Department
 * @property Employee $Employee
 * @property Customer $Customer
 * @property Meeting $Meeting
 * @property SuggestionForm $SuggestionForm
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 */
class DocumentAmendmentRecordSheet extends AppModel {

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
		'request_from' => array(
			'numeric' => array(
				'rule' => array('numeric'),
                        ),
		),
		'master_list_of_format' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'amendment_details' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'reason_for_change' => array(
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
		)
	);
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Branch' => array(
			'className' => 'Branch',
			'foreignKey' => 'branch_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Department' => array(
			'className' => 'Department',
			'foreignKey' => 'department_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'PreparedBy' => array(
			'className' => 'Employee',
			'foreignKey' => 'prepared_by',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'ApprovedBy' => array(
			'className' => 'Employee',
			'foreignKey' => 'approved_by',
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
		'Customer' => array(
			'className' => 'Customer',
			'foreignKey' => 'customer_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'SuggestionForm' => array(
			'className' => 'SuggestionForm',
			'foreignKey' => 'suggestion_form_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'SystemTable' => array(
			'className' => 'SystemTable',
			'foreignKey' => 'system_table_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'MasterListOfFormatID' => array(
			'className' => 'MasterListOfFormat',
			'foreignKey' => 'master_list_of_format',
			'conditions' => '',
			'fields' => array('id', 'title', 'document_number', 'issue_number', 'revision_number', 'revision_date'),
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
		'ChangeAdditionDeletionRequest' => array(
			'className' => 'ChangeAdditionDeletionRequest',
			'foreignKey' => 'change_addition_deletion_request_id',
			'conditions' => '',
			'fields' => array(),
			'order' => ''
		),
		'FileUpload' => array(
			'className' => 'FileUpload',
			'foreignKey' => 'file_upload_id',
			'conditions' => '',
			'fields' => array(),
			'order' => ''
		)
	);

        public $mergeFields = array(
                'request_from' => array(
                    'Branch' => array('branch_id', 'Branch'),
                    'Department' => array('department_id', 'Department'),
                    'Employee' => array('employee_id', 'Employee'),
                    'Customer' => array('customer_id', 'Customer'),
                    'SuggestionForm' => array('suggestion_form_id', 'Suggestion Form'),
                    'Other' => array('others', 'Others')
                )
        );
}
