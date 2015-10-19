<?php
namespace Edufinder\Form;
use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('login');
        $this->setAttribute('method', 'post');
		  $this->setAttribute('role','form');
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
					 'class'	=>	'form-control',
					 'placeholder' => 'Email'
            ),       
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
					 'class'	=>	'form-control',
					 'placeholder' => 'Password'
            ),
        ));
		   $this->add(array(
            'name' => 'role',
				'type'  => 'Radio',
            'attributes' => array(
					 'id'		=>	'role',
					 'value' => 'role',
            ),
            'options' => array(
					  'value_options' => array(
						'educator' => 'Educator',
						'parent' => 'Parent',
				  ),
            ),
        ));
        $this->add(array(
            'name' => 'rememberme',
            'type' => 'checkbox', // 'Zend\Form\Element\Checkbox',          
//            'attributes' => array( // Is not working this way
//                'type'  => '\Zend\Form\Element\Checkbox',
//            ),
            'options' => array(
                'label' => 'Remember Me?',
//              'checked_value' => 'true', without value here will be 1
//              'unchecked_value' => 'false', // witll be 1
            ),
        ));         
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
					 'class'	=>	'btn btn-danger pull-left',
            ),
        )); 
    }
}