<?php
App::uses('AppModel', 'Model');
/**
 * CustomerComplaint Model
 *
 * @property Customer $Customer
 * @property Product $Product
 * @property DeliveryChallan $DeliveryChallan
 * @property Employee $Employee
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property CorrectivePreventiveAction $CorrectivePreventiveAction
 */
class CustomerComplaint extends AppModel {

	public $virtualFields = array(
	    'name' => 'CONCAT(CustomerComplaint.complaint_number, " dated : ", CustomerComplaint.complaint_date)'
	);
    public $displayField = 'complaint_number';

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
		'type' => array(
			'boolean' => array(
				'rule' => array('boolean'),
			),
		),
		'customer_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'complaint_date' => array(
			'date' => array(
				'rule' => array('date'),
			),
		),
		'target_date' => array(
			'date' => array(
				'rule' => array('date'),
			),
		),
		'details' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'employee_id' => array(
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
        ),'Customer' => array(
			'className' => 'Customer',
			'foreignKey' => 'customer_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'DeliveryChallan' => array(
			'className' => 'DeliveryChallan',
			'foreignKey' => 'delivery_challan_id',
			'conditions' => '',
			'fields' => array('id', 'challan_number', 'name'),
			'order' => ''
		),
		'Employee' => array(
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
                'AuthorisedBy' => array(
			'className' => 'Employee',
			'foreignKey' => 'authorized_by',
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

	public $customArray = array(
		    'complaint_source' => array(
			'0' => 'Product',
			'1' => 'Service',
			'2' => 'Delivery Challan',
			'3' => 'Customer Care'
		    ),

	    'current_status' => array(
			'0' => 'Open',
			'1' => 'Close'
		),

	     'type' => array(
			'0' => 'Complaint',
			'1' => 'Feedback'
		),
	);

    public $mergeFields = array(
            'complaint_source' => array(
                'Product' => array('product_id', 'Product'),
                'Delivery Challan' => array('delivery_challan_id', 'Delivery Challan'),
                'Service' => array(),
                'Customer Care' => array()
            )
    );

    public $report = array(
    	'Complaint_Source' => array(
	    		'Product_Wise' => array('model'=>'Product','key_field'=>'product_id'),
	    		'Delivery_Challan' =>array('model'=>'DeliveryChallan','key_field'=>'delivery_challan_id')
    		)
    	);
        

    public $hasMany = array(
            'CorrectivePreventiveAction' => array(
			'className' => 'CorrectivePreventiveAction',
			'foreignKey' => 'customer_complaint_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => array('id','customer_complaint_id','name','current_status'),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),'Task' => array(
			'className' => 'Task',
			'foreignKey' => 'customer_complaint_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => array(),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
