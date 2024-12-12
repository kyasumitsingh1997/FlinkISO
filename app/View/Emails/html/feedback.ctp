<?php 
if(FULL_BASE_URL == 'https://sit-qms.tranzport.com')$env =  "SIT";
elseif(FULL_BASE_URL == 'https://qa-qms.tranzport.com')$env =  "QA";
elseif(FULL_BASE_URL == 'https://dev-qms.tranzport.com')$env = "DEV";
else $env = "None";


echo $message = "
User Friendly : " . $this->request->data['User']['user_friendly'] ."<br />".
"Coverage : " . $this->request->data['User']['coverage']  ."<br />".
"Message : " . $this->request->data['User']['feedback']  ."<br />".
"By : " . $company . "<br /> User " . $this->Session->read('User.username');
?>
