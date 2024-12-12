<?php
App::uses('AppModel', 'Model');
/**
 * Message Model
 *
 * @property User $User
 * @property MessageUserInbox $MessageUserInbox
 * @property MessageUserSent $MessageUserSent
 * @property MessageUserThrash $MessageUserThrash
 */
class Message extends AppModel {
//var $actsAs = array('WhoDidIt');
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'subject' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'message' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'user_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'sent_to' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
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
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'MessageUserInbox' => array(
			'className' => 'MessageUserInbox',
			'foreignKey' => 'message_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'MessageUserSent' => array(
			'className' => 'MessageUserSent',
			'foreignKey' => 'message_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'MessageUserThrash' => array(
			'className' => 'MessageUserThrash',
			'foreignKey' => 'message_id',
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
}
