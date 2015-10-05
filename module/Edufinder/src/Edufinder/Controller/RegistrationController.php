<?php
namespace Edufinder\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Edufinder\Model\Edufinder;
use Edufinder\Form\RegistrationForm;
use Edufinder\Form\RegistrationFilter;
use Edufinder\Form\ForgottenPasswordForm;
use Edufinder\Form\ForgottenPasswordFilter;
use Zend\Mail\Message;

class RegistrationController extends AbstractActionController
{
    protected $usersTable;  
    
    public function indexAction()
    {
        // A test instantiation to make sure it works. Not used in the application. You can remove the next line
        // $myValidator = new ConfirmPassword();
        $form = new RegistrationForm();
        $form->get('submit')->setValue('Register');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter(new RegistrationFilter($this->getServiceLocator()));
            $form->setData($request->getPost());
             if ($form->isValid()) {             
                $data = $form->getData();
                $data = $this->prepareData($data);
                $edufinder = new Edufinder();
                $edufinder->exchangeArray($data);

                $this->getUsersTable()->saveUser($edufinder);
                
                $this->sendConfirmationEmail($edufinder);
                $this->flashMessenger()->addMessage($edufinder->email);
                return $this->redirect()->toRoute('edufinder/default', array('controller'=>'registration', 'action'=>'registration-success'));                   
            }            
        }
        return new ViewModel(array('form' => $form));
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
        $token = $this->params()->fromRoute('id');
        $viewModel = new ViewModel(array('token' => $token));
        try {
            $user = $this->getUsersTable()->getUserByToken($token);
            $id = $user->id;
            $this->getUsersTable()->activateUser($id);
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
                $edufinder = $usersTable->getUserByEmail($email);
                $password = $this->generatePassword();
                $edufinder->password = $this->encriptPassword($this->getStaticSalt(), $password, $edufinder->password_salt);
//              $usersTable->changePassword($edufinder->id, $password);
//              or
                $usersTable->saveUser($edufinder);
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
//      $data['registration_date'] = date('Y-m-d H:i:s');
        $date = new \DateTime();
        $data['registration_date'] = $date->format('Y-m-d H:i:s');
        $data['registration_token'] = md5(uniqid(mt_rand(), true)); // $this->generateDynamicSalt();
//      $data['registration_token'] = uniqid(php_uname('n'), true); 
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
     
         // all inputs clean, proceed to build password
     
         // change these strings if you want to include or exclude possible password characters
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
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('Edufinder\Model\UsersTable');
        }
        return $this->usersTable;
    }
    public function sendConfirmationEmail($edufinder)
    {
        // $view = $this->getServiceLocator()->get('View');
        $transport = $this->getServiceLocator()->get('mail.transport');
        $message = new Message();
        $this->getRequest()->getServer();  //Server vars
        $message->addTo($edufinder->email)
                ->addFrom('dragonsword007008@gmail.com')
                ->setSubject('Please, confirm your registration!')
                ->setBody("Please, click the link to confirm your registration => " . 
                    $this->getRequest()->getServer('HTTP_ORIGIN') .
                    $this->url()->fromRoute('edufinder/default', array(
                        'controller' => 'registration', 
                        'action' => 'confirm-email', 
                        'id' => $edufinder->registration_token)));
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