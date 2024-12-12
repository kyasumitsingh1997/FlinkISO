<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

    var $actsAs= array('WhoDidIt');

    /*public $belongsTo = array( 'ApprovedBy' => array('className' => 'Employee','foreignKey' => 'approved_by','conditions' => '','fields' => '', 'order' => '' ), 'PreparedBy' => array('className' => 'Employee', 'foreignKey' => 'prepared_by', 'conditions' => '', 'fields' => '', 'order' => '' ),
        'ApproveBy' => array(
            'className' => 'Employee',
            'foreignKey' => 'approved_by',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'PreparedBy' => array(
            'className' => 'Employee',
            'foreignKey' => 'prepared_by',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
);*/
public function beforeSave($options = array()){
        
    if(!isset($_ENV['company_id'])){
        $s = $this->schema();
        foreach ($s as $field => $type) {
            if($type['type'] == 'date'){                
                    // var_dump($this->data);
                $this->data[$this->alias][$field] = date('Y-m-d',strtotime($this->data[$this->alias][$field]));               
            }elseif($type['type'] == 'datetime'){
                // $this->request->data[$this->alias][$field] = date('Y-m-d H:i:s',strtotime($this->request->data[$this->alias][$field]));
            }
        }        
        if (isset($_SESSION['User']['division_id']) && array_key_exists('division_id', $this->schema())) {
            $this->data[$this->alias]['division_id'] = $_SESSION['User']['division_id'];
        }
        return true;
    }
}

public function beforeFind($query){        

    if (isset($query)) {

        if (array_key_exists('company_id', $this->schema()) && ($this->alias != 'Company')) {
            if (isset($_ENV['company_id']) && $_ENV['company_id'] != null && array_key_exists('company_id', $this->schema()) && ($this->alias != 'Company')) {
                $query['conditions'] = array_merge(array($this->alias . '.company_id' =>$_ENV['company_id']), array($query['conditions']));
                        // $query['order'] = array_merge(array($this->alias . '.created' => 'asc'),array($query['order']));
                return $query;
            }else{
                // if (substr($_SERVER['REQUEST_URI'], -5) != 'login') {
                //     $query['conditions'] = array_merge(array($this->alias . '.company_id' =>$_SESSION['User']['company_id']), array($query['conditions']));

                //     if (array_key_exists('division_id', $this->schema())) {

                //         if (
                //             isset($_SESSION['User']['division_id']) && 
                //             $this->alias != 'InjuryType' && 
                //             $this->alias != 'Division' && 
                //             $this->alias != 'MasterListOfFormat' && 
                //             $this->alias != 'EmailTrigger' && 
                //             $this->alias != 'MasterListOfFormatDepartment' &&
                //             $_SESSION['User']['is_mr'] == false
                //             ) {

                //             if (substr($_SERVER['REQUEST_URI'], -24) != 'email_triggers/add_ajax' 
                //                 && substr($_SERVER['REQUEST_URI'], -13) != 'users/register' 
                //                 && substr($_SERVER['REQUEST_URI'], -13) != 'users/activate' 
                //                 && $this->alias != 'Employee') {
                //                 if(isset($_SESSION['User']))$query['conditions'] = array_merge(array($this->alias . '.division_id' =>$_SESSION['User']['division_id']), array($query['conditions']));
                //         }

                //     }
                // }

                //             // if($this->alias != 'UserSession'){
                //             //     if (array_key_exists('name', $this->schema())) {
                //             //         $query['order'] = array($this->alias.'.name' => ' ASC');
                //             //     }elseif (array_key_exists('title', $this->schema())) {
                //             //         $query['order'] = array($this->alias.'.title' => ' ASC');
                //             //     }

                //             // }
                //             //debug($query);
                //             // if(array_key_exists('created', $this->schema()))$query['order'] = array($this->alias.'.created' => ' DESC');
                //             // $query['order'] = array_merge(array($this->alias . '.created' => 'asc'),array($query['order']));
                // return $query;
            // }

        }

    }

}

}

public function afterSave($created, $options = array()){   
    if($this->alias != 'History' && $this->alias != 'UserSession'){         
        $req_data = Router::getRequest();
        // $req_data = $req_data->data;


        $this->EmailTrigger = ClassRegistry::init('EmailTrigger');
        if(isset($this->data[$this->alias]['system_table_id'])){
            $triggers = $this->EmailTrigger->find('first',array(
                'conditions'=>array('EmailTrigger.system_table'=>$this->data[$this->alias]['system_table_id'],'EmailTrigger.soft_delete' => 0,'EmailTrigger.publish' => 1,'EmailTrigger.branch_id'=>$_SESSION['User']['branch_id'])));
        }               

        if(isset($triggers) && $triggers){

            if($triggers['EmailTrigger']['if_added'] == 1){

                if($created) {
                    $this->send_email_trigger($triggers, $this->alias .' is added');
                    return true;
                }
            }

            if($triggers['EmailTrigger']['if_soft_delete'] == 1){


                if (isset($this->data[$this->alias]['model_action']) && ($this->data[$this->alias]['model_action'] =='delete')) {

                    $this->send_email_trigger($triggers ,  $this->alias .' is soft deleted');
                    return true;

                } else if (isset($this->data[$this->alias]['model_action']) && ($this->data[$this->alias]['model_action'] =='delete_all')) {

                    $this->send_email_trigger($triggers ,  'Some '. $this->alias .' are soft deleted');
                    return true;

                }  

            } 
            if($triggers['EmailTrigger']['if_approved'] == 1){

                if (isset($this->data[$this->alias]['model_action']) && ($this->data[$this->alias]['model_action'] =='approve_record')) {

                    $this->send_email_trigger($triggers ,  $this->alias .' is approved');
                    return true;

                }
            }

            if($triggers['EmailTrigger']['if_publish'] == 1){

                if(isset($req_data['History']['pre_post_values'])){
                    $pre_post_values = json_decode($req_data['History']['pre_post_values'],true);

                    if(($this->data[$this->alias]['publish'] != $pre_post_values[$this->alias]['publish']) && $this->data[$this->alias]['publish'] ==1)
                    {
                       $this->send_email_trigger($triggers ,  $this->alias .' is published');
                       return true;
                   }

               }else{    

                if (isset($this->data[$this->alias]['model_action']) && ($this->data[$this->alias]['model_action'] =='publish_record')) {

                   $this->send_email_trigger($triggers ,  $this->alias .' is published');
                   return true;

               } 
           }
       } 
       if($triggers['EmailTrigger']['if_edited'] == 1){

        if(isset($req_data['History']['pre_post_values'])){
            $this->send_email_trigger($triggers , $this->alias .' is changed');
            return true;
        }
    }






}


return true;

}
}

public function send_email_trigger($triggers = null, $send_message = null){

    $this->Employee = ClassRegistry::init('Employee');
    $this->User = ClassRegistry::init('User');
    $recipents = json_decode($triggers['EmailTrigger']['recipents']);
    $employees = $this->Employee->find('all',array('conditions'=>array('Employee.id'=>$recipents), fields=>array('id', 'office_email','personal_email'), 'recursive'=>-1,  'group' =>'id'));

    $branch = $this->User->Branch->find('first',array(
        'recursive'=>0,
        'fields'=>array('id','name'),    
        'conditions'=>array('Branch.id'=>$_SESSION['User']['branch_id'])));      

    $department = $this->User->Department->find('first',array(
        'recursive'=>0,
        'fields'=>array('id','name'),    
        'conditions'=>array('Department.id'=>$_SESSION['User']['department_id'])));                      

    foreach($employees as $employee){
        $officeEmailId = $employee['Employee']['office_email'];
        $personalEmailId = $employee['Employee']['personal_email'];
        if ($officeEmailId != '') {
            $email = $officeEmailId;
        } else if ($personalEmailId != '') {
            $email = $personalEmailId;
        }

        $users = $this->User->find('all',array('conditions'=>array('User.employee_id'=>$employee['Employee']['id'])));
        $new_session = $_SESSION;
        try{
            App::uses('CakeEmail', 'Network/Email');
            $new_session = $_SESSION;

            if($new_session['User']['is_smtp'] == 1)
                $EmailConfig = new CakeEmail("smtp");

            if($new_session['User']['is_smtp'] == 0)
                $EmailConfig = new CakeEmail("default");

            $EmailConfig->to($email);
            $EmailConfig->subject($triggers['EmailTrigger']['subject']);
            $EmailConfig->template('emailTrigger');
            $EmailConfig->viewVars(array(
                'date_time' => date('Y-m-d h:i:s'),
                'by_user'=>$_SESSION['User']['username'],
                'employee'=>$_SESSION['User']['name'],
                'branch' => $branch['Branch']['name'],
                'department' => $department['Department']['name'],
                'h2tag'=>$send_message,
                'msg_content'=>$triggers['EmailTrigger']['template']));
            $EmailConfig->emailFormat('html');
            $EmailConfig->send();
        } catch(Exception $e) {

        }
        foreach($users as $user){   
            $uData = $user['User']['id'];

            $this->Message = ClassRegistry::init('Message');        
            $m['Message']['Message_to'] = $email;
            $m['Message']['message'] = $triggers['EmailTrigger']['template'];
            $m['Message']['subject'] = $send_message;
            $m['Message']['user_id'] = $_SESSION['User']['id'];
            if ($email) {
                $this->Message->create();
                if ($this->Message->save($m)) {

                    $m['Message']['id'] = $this->Message->id;
                    $m['Message']['trackingid'] = $this->Message->id;
                    $this->Message->save($m);

                    $this->MessageUserInbox = ClassRegistry::init('MessageUserInbox');  

                    $this->MessageUserInbox->create();
                    $newData = array();
                    if ($uData) {
                        $newData['user_id'] = $uData;
                        $newData['message_id'] = $this->Message->id;
                        $newData['trackingid'] = $this->Message->id;
                        $newData['status'] = 0;
                        $this->MessageUserInbox->save($newData, false);
                    }
                    $this->MessageUserSent = ClassRegistry::init('MessageUserSent');    

                    $this->MessageUserSent->create();

                    if ($uData) {
                        $newData['user_id'] = $uData;
                        $newData['message_id'] = $this->Message->id;
                        $newData['trackingid'] = $this->Message->id;
                        $newData['status'] = 0;
                        $this->MessageUserSent->save($newData, false);
                        
                    }
                    
                } else {
                    //$this->Session->setFlash(__('The message could not be saved. Please, try again.'));
                }
                
            } else {
               // $this->Session->setFlash(__('No recipient selected. Please, try again.'));
               //  $this->redirect(array('controller'=>'users','action' => 'dashboard'));
            }
        }
    }
}
}
