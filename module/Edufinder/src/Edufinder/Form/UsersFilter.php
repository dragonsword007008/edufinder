<?php
namespace Edufinder\Form;
use Zend\Form\Form;

class UserForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'first_name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'First name',
            ),
        ));

        $this->add(array(
            'name' => 'last_name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Last name',
            ),
        ));
        
	
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
            ),
            'options' => array(
                'label' => 'E-mail',
            ),
        ));	
        $this->add(array(
            'name' => 'l_id',
			'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Role',
				'value_options' => array(
					'1' => 'Admin',
					'2' => 'Educator',
					'3' => 'Parent',
				),
            ),
        ));	
		
        $this->add(array(
            'name' => 'active',
			'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Active',
				'value_options' => array(
					'0' => 'No',
					'1' => 'Yes',
				),
            ),
        ));

		
        $this->add(array(
            'name' => 'picture',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Picture URL',
            ),
        ));
		
        $this->add(array(
            'name' => 'password_salt',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Password Salt',
            ),
        ));
		
        $this->add(array(
            'name' => 'registration_date',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Registration Date',
            ),
        ));	
        $this->add(array(
            'name' => 'registration_token',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Registration Token',
            ),
        ));			
        $this->add(array(
            'name' => 'email_confirmed',
			'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'E-mail was confirmed?',
				'value_options' => array(
					'0' => 'No',
					'1' => 'Yes',
				),
            ),
        ));
		
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        )); 
    }
}