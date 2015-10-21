<?php
namespace Edufinder\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Edufinder\Model\Users;
use Edufinder\Form\RegistrationFormEdu;
use Edufinder\Form\RegistrationFilterEdu;
use Edufinder\Form\RegistrationFormPar;
use Edufinder\Form\RegistrationFilterPar;
use Edufinder\Form\ForgottenPasswordForm;
use Edufinder\Form\ForgottenPasswordFilter;
use Zend\Mail\Message;
use Zend\Validator\File\Size;

class RegistrationController extends AbstractActionController
{
    protected $usersTable;  
	 
    public function indexAction()
    {   
		  $id = $this->params()->fromRoute('id');
		  if($id == 'educator') {
			  $form = new RegistrationFormEdu();
			  $registrationFilter = new RegistrationFilterEdu($this->getServiceLocator());
			  $role = 'educator';
			  }else if($id == 'parent') {
				  $form = new RegistrationFormPar();
				  $registrationFilter = new RegistrationFilterPar($this->getServiceLocator());
				  $role = 'parent';
				  } else{
					  }
        $form->get('submit')->setValue('Submit');        
        $request = $this->getRequest();
        if ($request->isPost()) {
			  	$picture = 'url';
            $form->setInputFilter($registrationFilter);
				$File    = $this->params()->fromFiles('photo');
				//file upload
            $data = array_merge_recursive(
						$request->getPost()->toArray(),
						$request->getFiles()->toArray()
				  );
            $form->setData($data);
             if ($form->isValid()) {
					 //$size = new Size(array('min'=>20000000)); //minimum bytes filesize 
					 $adapter = new \Zend\File\Transfer\Adapter\Http(); 
					 $adapter->setDestination('/var/www/html/edufinder/public/img/uploads'); 
						  if ($adapter->receive($File['name'])) {
								echo 'Profile picture uploaded';
								$picture = 'http://ec2-52-25-173-169.us-west-2.compute.amazonaws.com/img/uploads/'.$File['name'];
						  }			             
                $data = $form->getData();
                $data = $this->prepareData($data);
                $data['picture'] = $picture;
					 $users = new Users();
                $users->exchangeArray($data);
                $this->getUsersTable()->saveUsers($users);
                $this->sendConfirmationEmail($users,$role);
                $this->flashMessenger()->addMessage($users->email);
                return $this->redirect()->toRoute('edufinder/default', array('controller'=>'registration', 'action'=>'registration-success'));                   
            }            
        }
		  	$view = new ViewModel(array('form' => $form));
		   if($id == 'educator') {
			  $view->setTemplate('edufinder/registration/educator/index.phtml');
			  }else if($id == 'parent') {
				  $view->setTemplate('edufinder/registration/parent/index.phtml');
				  } else{
					  }
        return $view;
    }
    
    public function registrationSuccessAction()
    {
        $email = null;
        $flashMessenger = $this->flashMessenger();
        if ($flashMessenger->hasMessages()) {
            foreach($flashMessenger->getMessages() as $key => $value) {
                $email .=  $value;
            }
        }
        return new ViewModel(array('email' => $email));
    }
	    
    public function confirmEmailAction()
    {
        $params = $this->params()->fromRoute('id');
		  $params = explode('&',$params);
		  $token = $params[0];
		  $role = $params[1];
		  $email = $params[2];
        $viewModel = new ViewModel(array('token' => $token,'email' => $email));
		  try {
			  $sm = $this->getServiceLocator();
			  if($role == 'educator'){
				  $users = $sm->get('Edufinder\Model\EducatorTable')->getUsersByToken($token);
				  $users_id = $users->id;
				  $sm->get('Edufinder\Model\EducatorTable')->activateUsers($users_id);
				  $viewModel->setTemplate('edufinder/registration/educator/confirm-email.phtml');				  
				  }else if($role = 'parent'){
					  $users = $sm->get('Edufinder\Model\ParentTable')->getUsersByToken($token);
					  $users_id = $users->id;
					  $sm->get('Edufinder\Model\ParentTable')->activateUsers($users_id);
					  $viewModel->setTemplate('edufinder/registration/parent/confirm-email.phtml');
					  }else{}
        }
        catch(\Exception $e) {
            $viewModel->setTemplate('edufinder/registration/confirm-email-error.phtml');
        }
        return $viewModel;
    }
	 
    public function forgottenPasswordAction()
    {
        $form = new ForgottenPasswordForm();
        $form->get('submit')->setValue('Send');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter(new ForgottenPasswordFilter($this->getServiceLocator()));
            $form->setData($request->getPost());
             if ($form->isValid()) {
                $data = $form->getData();
                $email = $data['email'];
                $usersTable = $this->getUsersTable();
                $users = $usersTable->getUsersByEmail($email);
                $password = $this->generatePassword();
                $users->password = $this->encriptPassword($this->getStaticSalt(), $password, $users->password_salt);
                $usersTable->saveUsers($users);
                $this->sendPasswordByEmail($email, $password);
                $this->flashMessenger()->addMessage($email);
                return $this->redirect()->toRoute('edufinder/default', array('controller'=>'registration', 'action'=>'password-change-success'));
            }                   
        }       
        return new ViewModel(array('form' => $form));           
    }
    
    public function passwordChangeSuccessAction()
    {
        $email = null;
        $flashMessenger = $this->flashMessenger();
        if ($flashMessenger->hasMessages()) {
            foreach($flashMessenger->getMessages() as $key => $value) {
                $email .=  $value;
            }
        }
        return new ViewModel(array('email' => $email));
    }   
    
    public function prepareData($data)
    {
        $data['active'] = 0;
        $data['password_salt'] = $this->generateDynamicSalt();              
        $data['password'] = $this->encriptPassword(
            $this->getStaticSalt(), 
            $data['password'], 
            $data['password_salt']
        );
        $date = new \DateTime();
        $data['registration_date'] = $date->format('Y-m-d H:i:s');
        $data['registration_token'] = md5(uniqid(mt_rand(), true)); // $this->generateDynamicSalt();
        $data['email_confirmed'] = 0;
        return $data;
    }
    public function generateDynamicSalt()
    {
        $dynamicSalt = '';
        for ($i = 0; $i < 100; $i++) {
            $dynamicSalt .= chr(rand(33, 126));
        }
        return $dynamicSalt;
    }
    
    public function getStaticSalt()
    {
        $staticSalt = '';
        $config = $this->getServiceLocator()->get('Config');
        $staticSalt = $config['static_salt'];       
        return $staticSalt;
    }
    public function encriptPassword($staticSalt, $password, $dynamicSalt)
    {
        return $password = md5($staticSalt . $password . $dynamicSalt);
    }
    
    public function generatePassword($l = 10, $c = 0, $n = 0, $s = 0) {
         // get count of all required minimum special chars
         $count = $c + $n + $s;
         $out = '';
         // sanitize inputs; should be self-explanatory
         if(!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
              trigger_error('Argument(s) not an integer', E_USER_WARNING);
              return false;
         }
         elseif($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
              trigger_error('Argument(s) out of range', E_USER_WARNING);
              return false;
         }
         elseif($c > $l) {
              trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
              return false;
         }
         elseif($n > $l) {
              trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
              return false;
         }
         elseif($s > $l) {
              trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
              return false;
         }
         elseif($count > $l) {
              trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);
              return false;
         }
     
         $chars = "abcdefghijklmnopqrstuvwxyz";
         $caps = strtoupper($chars);
         $nums = "0123456789";
         $syms = "!@#$%^&*()-+?";
     
         // build the base password of all lower-case letters
         for($i = 0; $i < $l; $i++) {
              $out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
         }
     
         // create arrays if special character(s) required
         if($count) {
              // split base password to array; create special chars array
              $tmp1 = str_split($out);
              $tmp2 = array();
     
              // add required special character(s) to second array
              for($i = 0; $i < $c; $i++) {
                   array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
              }
              for($i = 0; $i < $n; $i++) {
                   array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
              }
              for($i = 0; $i < $s; $i++) {
                   array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
              }
     
              // hack off a chunk of the base password array that's as big as the special chars array
              $tmp1 = array_slice($tmp1, 0, $l - $count);
              // merge special character(s) array with base password array
              $tmp1 = array_merge($tmp1, $tmp2);
              // mix the characters up
              shuffle($tmp1);
              // convert to string for output
              $out = implode('', $tmp1);
         }
     
         return $out;
    }
    
    public function getUsersTable()
    {
        $id = $this->params()->fromRoute('id');
		  if($id == 'educator') {
				if (!$this->usersTable) {
						$sm = $this->getServiceLocator();
						$this->usersTable = $sm->get('Edufinder\Model\EducatorTable');
				  }
			  }else if($id == 'parent') {
					if (!$this->usersTable) {
							$sm = $this->getServiceLocator();
							$this->usersTable = $sm->get('Edufinder\Model\ParentTable');
						}
				  } else{
					  }

        return $this->usersTable;
    }
	 
    public function sendConfirmationEmail($users,$role)
    {
        // $view = $this->getServiceLocator()->get('View');
        $transport = $this->getServiceLocator()->get('mail.transport');
        $message = new Message();
        $this->getRequest()->getServer();  //Server vars
        $message->addTo($users->email)
                ->addFrom('dragonsword007008@gmail.com')
                ->setSubject('Please, confirm your registration!')
                ->setBody("Please, click the link to confirm your registration => " . 
                    $this->getRequest()->getServer('HTTP_ORIGIN') .
                    $this->url()->fromRoute('edufinder/default', array(
                        'controller' => 'registration', 
                        'action' => 'confirm-email', 
                        'id' => $users->registration_token.'&'.$role.'&'.$users->email)));
        $transport->send($message);
    }
	 
    public function sendPasswordByEmail($email, $password)
    {
        $transport = $this->getServiceLocator()->get('mail.transport');
        $message = new Message();
        $this->getRequest()->getServer();  //Server vars
        $message->addTo($email)
                ->addFrom('dragonsword007008@gmail.com')
                ->setSubject('Your password has been changed!')
                ->setBody("Your password at  " . 
                    $this->getRequest()->getServer('HTTP_ORIGIN') .
                    ' has been changed. Your new password is: ' .
                    $password
                );
        $transport->send($message);     
    }   
}