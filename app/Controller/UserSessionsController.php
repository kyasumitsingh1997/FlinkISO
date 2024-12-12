<?php
App::uses('AppController', 'Controller');
/**
 * UserSessions Controller
 *
 * @property UserSession $UserSession
 */
class UserSessionsController extends AppController {
	public function _get_system_table_id() {

        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $this->request->params['controller'])));
        return $systemTableId['SystemTable']['id'];
    }

	public function index() {
		$userId = $this->request->query['user_id'];
		if($userId){
			$conditions = array('UserSession.user_id'=>$userId);

		$this->paginate = array('conditions'=>$conditions,'order'=>array('UserSession.sr_no'=>'DESC'),'limit'=>10);

		$this->UserSession->recursive = 0;
		$this->set('userSessions', $this->paginate());
		$this->set('userId', $userId);
		}
                $users = $this->get_usernames();
		$this->set(compact('users'));
	}

	public function view($id = null) {
	   $userId = $id;
            $this->UserSession->History->recursive = 0;

		$userSession = $this->UserSession->History->find('all',array(
				'conditions'=>array('History.user_session_id'=>$id),
				'order'=>array('History.created'=>'DESC')
				));

		$this->set('userSession', $userSession);
		$this->set('selectedUserId', $selectedUserId);
                $users = $this->get_usernames();
		
		
		$this->set(compact('users'));
	}

	public function user_login_report(){
		$project_id = $this->request->data['UserSession']['project_id'];

		if($project_id){
			$allmembers = $this->requestAction(array('controller'=>'projects','action'=>'pro_meb_details',
				'project_id'=>$project_id,
				base64_encode($startTime),
				base64_encode($endTime))
			);

			$this->UserSession->virtualFields = array(
				'time'=>'select TIMEDIFF(end_time,start_time)'
			);
			// Configure::Write('debug',1);
			// debug($this->request->data);
			
			$x = 0;
			foreach($allmembers as $member){
				$sessions[$x] = $this->UserSession->find('first',array(
					'conditions'=>array(
						'DATE(UserSession.start_time)'=>DATE('Y-m-d',strtotime($this->request->data['UserSession']['date'])),
						'UserSession.employee_id'=>$member['Employee']['id'])));
				$sessions[$x]['Member'] = $member;
				debug($session);
				$x++;
			}

			$this->set('sessions',$sessions);
		}
		
		$this->loadModel('Project');
		$projects = $this->Project->find('list');


		
		$this->set('projects',$projects);
		debug($allmembers);
		// exit;
	}
}

