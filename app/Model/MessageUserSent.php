<?php
App::uses('AppModel', 'Model');
/**
 * MessageUserSent Model
 *
 * @property Message $Message
 * @property User $User
 */
class MessageUserSent extends AppModel {
//var $actsAs = array('WhoDidIt');
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'message_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'trackingid' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'user_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'status' => array(
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
		'Message' => array(
			'className' => 'Message',
			'foreignKey' => 'message_id',
			'conditions' => '',
			'fields' => array('id', 'subject', 'message', 'trackingid'),
			'order' => ''
		),
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
}
