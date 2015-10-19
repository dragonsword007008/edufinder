<?php
namespace Edufinder\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Authentication\Adapter\DbTable as EdufinderAdapter;
use Edufinder\Model\Users;
use Edufinder\Form\LoginForm;
use Edufinder\Form\SearchForm;
use Edufinder\Form\SearchFilter;
use Zend\Db\TableGateway\TableGateway;

class IndexController extends AbstractActionController
{
	 protected $educatorTable = null;
	 
    public function indexAction()
    {
		$form = new SearchForm();
		$form->get('submit')->setValue('Login');
		return new ViewModel(array('form' => $form));
	}	
	
    public function loginAction()
	{
		$user = $this->identity();
		$form = new LoginForm();
		$form->get('submit')->setValue('Login');
		$messages = null;
		
		$request = $this->getRequest();
		
        if ($request->isPost()) {
			  /*var_dump($form->isValid());
			var_dump($form->getMessages());
			var_dump($form->getInputFilter()->getMessages());*/
			$usersFormFilters = new Users();
            $form->setInputFilter($usersFormFilters->getInputFilter());	
			$form->setData($request->getPost());
			 if ($form->isValid()) {
				$data = $form->getData();
				$sm = $this->getServiceLocator();
				$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');				
				$config = $this->getServiceLocator()->get('Config');
				$staticSalt = $config['static_salt'];
				$usersAdapter = new EdufinderAdapter($dbAdapter,
										   $data['role'], // there is a method setTableName to do the same
										   'email', // there is a method setIdentityColumn to do the same
										   'password', // there is a method setCredentialColumn to do the same
										   "MD5(CONCAT('$staticSalt', ?, password_salt)) AND active = 1" // setCredentialTreatment(parametrized string) 'MD5(?)'
										);
				$usersAdapter
					->setIdentity($data['email'])
					->setCredential($data['password'])
				;
				
				$auth = new AuthenticationService();
				
				$result = $auth->authenticate($usersAdapter);			
				
				switch ($result->getCode()) {
					case Result::FAILURE_IDENTITY_NOT_FOUND:
						// do stuff for nonexistent identity
						break;
					case Result::FAILURE_CREDENTIAL_INVALID:
						// do stuff for invalid credential
						break;
					case Result::SUCCESS:
						$storage = $auth->getStorage();
						$storage->write($usersAdapter->getResultRowObject(
							null,
							'password'
						));
						$time = 1209600; 
						if ($data['rememberme']) {
							$sessionManager = new \Zend\Session\SessionManager();
							$sessionManager->rememberMe($time);
						}
						$this->redirect()->toRoute('edufinder/default',array('controller'=>$data['role'],'action' => 'profile','id'=>$data['email']));
						break;
					default:
						// do stuff for other failure
						break;
				}				
				foreach ($result->getMessages() as $message) {
					$messages .= "$message\n"; 
				}			
			 }
		}
		return new ViewModel(array('form' => $form, 'messages' => $messages));
		
	}
	
	public function loginadminAction()
	{
		$user = $this->identity();
		$form = new LoginForm();
		$form->get('submit')->setValue('Login');
		$messages = null;
		$request = $this->getRequest();
        if ($request->isPost()) {
			$edufinderFormFilters = new Users();
            $form->setInputFilter($edufinderFormFilters->getInputFilter());	
			$form->setData($request->getPost());
			 if ($form->isValid()) {
				$data = $form->getData();
				$sm = $this->getServiceLocator();
				$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');				
				$config = $this->getServiceLocator()->get('Config');
				$staticSalt = $config['static_salt'];
				$edufinderAdapter = new EdufinderAdapter($dbAdapter,
										   'admin', // there is a method setTableName to do the same
										   'email', // there is a method setIdentityColumn to do the same
										   'password', // there is a method setCredentialColumn to do the same
										   "MD5(CONCAT('$staticSalt', ?, password_salt)) AND active = 1" // setCredentialTreatment(parametrized string) 'MD5(?)'
										);
				$edufinderAdapter
					->setIdentity($data['email'])
					->setCredential($data['password'])
				;
				
				$auth = new AuthenticationService();
				
				$result = $auth->authenticate($edufinderAdapter);			
				
				switch ($result->getCode()) {
					case Result::FAILURE_IDENTITY_NOT_FOUND:
						// do stuff for nonexistent identity
						break;
					case Result::FAILURE_CREDENTIAL_INVALID:
						// do stuff for invalid credential
						break;
					case Result::SUCCESS:
						$storage = $auth->getStorage();
						$storage->write($edufinderAdapter->getResultRowObject(
							null,
							'password'
						));
						$time = 1209600; // 14 days 1209600/3600 = 336 hours => 336/24 = 14 days
//						if ($data['rememberme']) $storage->getSession()->getManager()->rememberMe($time); // no way to get the session
						if ($data['rememberme']) {
							$sessionManager = new \Zend\Session\SessionManager();
							$sessionManager->rememberMe($time);
						}
						return $this->redirect()->toRoute('edufinder/default', array(
						    'controller' => 'admin',
						    'action' =>  'index'
						));
						break;
					default:
						// do stuff for other failure
						break;
				}				
				foreach ($result->getMessages() as $message) {
					$messages .= "$message\n"; 
				}			
			 }
		}
		return new ViewModel(array('form' => $form, 'messages' => $messages));
		
	}
	
	public function searchAction()
    {
		$form = new SearchForm();
		$request = $this->getRequest(); 
            $form->setData($request->getPost());
		
		 if ($request->isPost()) {
			$form->setInputFilter(new SearchFilter($this->getServiceLocator()));
			$form->setData($request->getPost());
			/*var_dump($form->isValid());
			var_dump($form->getMessages());
			var_dump($form->getInputFilter()->getMessages());*/
			 if ($form->isValid()) {
				$data = $form->getData();
				unset($data['submit']);
				$postcode = $data['postcode'];
				$curricular_name = $data['curricular_name'];
				$year_or_grade_curricular = $data['year_or_grade_curricular'];
				$array = null;
				if($postcode == 0 && $curricular_name == '' && $year_or_grade_curricular == '' ){				
					} else if($postcode == 0 && $curricular_name = '' && $year_or_grade_curricular != '' ){
						$array = array('year_or_grade_curricular' => $year_or_grade_curricular);
						}else if($postcode == 0 && $curricular_name != '' && $year_or_grade_curricular != '' ){
							$array = array('curricular_name' => $curricular_name,'year_or_grade_curricular' => $year_or_grade_curricular);
							}else if($postcode != 0 && $curricular_name != '' && $year_or_grade_curricular != '' ){
								$array = array('postcode' => $postcode,'curricular_name' => $curricular_name, 'year_or_grade_curricular' => $year_or_grade_curricular);
								}else if($postcode == 0 && $curricular_name != '' && $year_or_grade_curricular == ''){
									$array = array('curricular_name' => $curricular_name);
									}else if($postcode != 0 && $curricular_name != '' && $year_or_grade_curricular == ''){
										$array = array('postcode' => $postcode,'curricular_name' => $curricular_name);
										}else if($postcode != 0 && $curricular_name === '' && $year_or_grade_curricular == ''){
											$array = array('postcode' => $postcode);
											}
				$rowSet = array('rowset' => $this->getEducatorTable()->select($array));										
			}else{
				$rowSet = array('rowset' => $this->getEducatorTable()->select());	
				}					 
		}
		
		else {
			$form->setData($this->getEducatorTable()->select());
			$rowSet = array('rowset' => $this->getEducatorTable()->select());			
		}
		
		return new ViewModel($rowSet);									
	}
	
	public function getEducatorTable()
	{
		// I have a Table data Gateway ready to go right out of the box
		if (!$this->educatorTable) {
			$this->educatorTable = new TableGateway(
				'educator', 
				$this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')
			);
		}
		return $this->educatorTable;
	}
	
	public function logoutAction()
	{
		$auth = new AuthenticationService();
		// or prepare in the globa.config.php and get it from there
		// $auth = $this->getServiceLocator()->get('Zend\Edufinderentication\AuthenticationService');
		
		if ($auth->hasIdentity()) {
			$identity = $auth->getIdentity();
		}			
		
		$auth->clearIdentity();
//		$auth->getStorage()->session->getManager()->forgetMe(); // no way to get the sessionmanager from storage
		$sessionManager = new \Zend\Session\SessionManager();
		$sessionManager->forgetMe();
		
		return $this->redirect()->toRoute('edufinder/default', array('controller' => 'index', 'action' => 'login'));		
	}
	
		
}