<?php
App::uses('AppModel', 'Model');
/**
 * MaterialQualityCheckDetail Model
 *
 * @property Employee $Employee
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property Company $Company
 */
class MaterialQualityCheckDetail extends AppModel {

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
		'material_quality_check' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'employee_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'check_performed_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'quantity_received' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'quantity_accepted' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
                'MaterialQualityCheck' => array(
			'className' => 'MaterialQualityCheck',
			'foreignKey' => 'material_quality_check_id',
			'conditions' => '',
			'fields' => array('id', 'name','material_id'),
			'order' => ''
		),
                'DeliveryChallan' => array(
			'className' => 'DeliveryChallan',
			'foreignKey' => 'delivery_challan_id',
			'conditions' => '',
			'fields' => array('id', 'challan_number', 'name','supplier_registration_id'),
			'order' => ''
		),
		'PurchaseOrder' => array(
			'className' => 'PurchaseOrder',
			'foreignKey' => 'purchase_order_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'PurchaseOrderDetail' => array(
			'className' => 'PurchaseOrderDetail',
			'foreignKey' => 'purchase_order_details_id',
			'conditions' => '',
			'fields' => array('id', 'item_number'),
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
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
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
		'StatusUserId' => array(
			'className' => 'User',
			'foreignKey' => 'status_user_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		)
	);
}
