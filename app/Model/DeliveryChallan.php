<?php
App::uses('AppModel', 'Model');
/**
 * DeliveryChallan Model
 *
 * @property Branch $Branch
 * @property Department $Department
 * @property Customer $Customer
 * @property SupplierRegistration $SupplierRegistration
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property OrderDetailsForm $OrderDetailsForm
 */
class DeliveryChallan extends AppModel {

public $virtualFields = array(
    'name' => 'CONCAT(DeliveryChallan.challan_number, " / ", DeliveryChallan.challan_date)'
);
    public $displayField = 'name';
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
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'purchase_order_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'challan_number' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'challan_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'challan_details' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'prices' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
                'ship_by' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
                'shipping_details' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
                'insurance' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
                'shipping_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
                'ship_to' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
                'payment_details' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),

                'invoice_to' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
                'acknowledgement_details' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
                'acknowledgement_date' => array(
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
                        'order' => ''
                ),
                'PreparedBy' => array(
                        'className' => 'Employee',
                        'foreignKey' => 'prepared_by',
                        'conditions' => '',
                        'fields' => array('id', 'name'),
                        'order' => ''
                ),
		'PurchaseOrder' => array(
			'className' => 'PurchaseOrder',
			'foreignKey' => 'purchase_order_id',
			'conditions' => '',
			'fields' => array('id', 'title', 'purchase_order_number', 'name','expected_delivery_date'),
			'order' => ''
		),
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

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
                'DeliveryChallanDetail' => array(
			'className' => 'DeliveryChallanDetail',
			'foreignKey' => 'delivery_challan_id',
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
		    'type' => array(
			'0' => 'Inbound',
			'1' => 'Outbound',
			'2' => 'Other',
		    ),
                   
	    );
}
