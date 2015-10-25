<?php
namespace Edufinder\Form;
use Zend\Form\Form;

class RegistrationFormPar extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('registrationPar');
        $this->setAttribute('method', 'post');
		  $this->setAttribute('role','form');
		  $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name' => 'email',
				'type'  => 'email',
            'attributes' => array(
					 'class'	=>	'form-control',
					 'id'		=>	'email',
					 'placeholder' => 'Email',
            ),
        ));	
		
        $this->add(array(
            'name' => 'password',
				'type'  => 'password',
            'attributes' => array(
					 'class'	=>	'form-control',
					 'id'		=>	'pwd',
					 'placeholder' => 'Password',
            ),
        ));
		
        $this->add(array(
            'name' => 'password_confirm',
				'type'  => 'password',
            'attributes' => array(
					 'class'	=>	'form-control',
					 'id'		=>	'pwd',
					 'placeholder' => 'Confirm Password',
            ),
        ));
		  
		  $this->add(array(
        	'type' => 'Zend\Form\Element\Captcha',
        	'name' => 'captcha',
			'attributes' => array(
				'class'	=>	'form-control',
				'id'		=>	'captcha',
			),
        	'options' => array(
        		'captcha' => new \Zend\Captcha\Figlet(),
				 'label' => 'Please key in Captha shown below',
        	),
        ));

		   $this->add(array(
            'name' => 'first_name',
				'type'  => 'text',
            'attributes' => array(
					 'class'	=>	'form-control',
					 'id'		=>	'fname',
					 'placeholder' => 'First Name',
            ),
        ));

        $this->add(array(
            'name' => 'last_name',
				'type'  => 'text',
            'attributes' => array(
					 'class'	=>	'form-control',
					 'id'		=>	'lname',
					 'placeholder' => 'Last Name',
            ),
        ));
		  
		   $this->add(array(
            'name' => 'student_name',
				'type'  => 'text',
            'attributes' => array(
					 'class'	=>	'form-control',
					 'id'		=>	'fname',
					 'placeholder' => 'Student Name',
            ),
        ));
		  
		  $this->add(array(
            'name' => 'gender',
				'type'  => 'Radio',
            'attributes' => array(
					 'id'		=>	'genderf',
					 'value' => 'f',
            ),
            'options' => array(
					  'value_options' => array(
						'f' => 'Female',
						'm' => 'Male',
				  ),
            ),
        ));
		  
		    $this->add(array(
            'name' => 'mobile_number',
				'type'  => 'Text',
            'attributes' => array(
					 'class'	=>	'form-control',
					 'id'		=>	'mobile',
					 'placeholder' => 'Mobile',
            ),
        ));
		  
		   $this->add(array(
            'name' => 'suburb',
				'type'  => 'text',
            'attributes' => array(              
					 'class'	=>	'form-control',
					 'id'		=>	'suburb',
					 'placeholder' => 'Suburb',
            ),
        ));
		  
		 $this->add(array(
            'name' => 'postcode',
				'type'  => 'Text',
            'attributes' => array(               
					 'class'	=>	'form-control',
					 'id'		=>	'postcode',
					 'placeholder' => 'PostCode',
            ),
        ));
		
		 $this->add(array(
            'name' => 'state',
				'type'  => 'text',
            'attributes' => array(               
					 'class'	=>	'form-control',
					 'id'		=>	'state',
					 'placeholder' => 'State',
            ),
        ));
      
		  $this->add(array(
            'name' => 'photo',
				'type'  => 'file',
            'attributes' => array(
					 'class'	=>	'form-control',
					 'id'		=>	'photo',
            ),
            'options' => array(
                'label' => 'Upload student document',
            ),
        ));
		  
		  $this->add(array(
            'name' => 'description',
				'type'  => 'Textarea',
            'attributes' => array(               
					 'class'	=>	'form-control',
					 'row'	=> '5',
					 'id'		=>	'profile',
					 'placeholder' => 'Please describe your child(ren)',
            ),
            'options' => array(
                'label' => 'Child Description',
            ),
        ));
		  
		  $this->add(array(
            'name' => 'curricular_area',
				'type' => 'Select',
            'attributes' => array(
					 'class'	=>	'form-control',
					 'id'		=>	'curricular',
            ),
            'options' => array(
					 'empty_option' => 'Currriculumn Areas',
					 'value_options' => array(
                             'Coding' => 'Coding',
                     ),
            ),
        ));
		  
		  $this->add(array(
            'name' => 'year_or_grade_parent',
				'type' => 'Select',
            'attributes' => array(
					 'class'	=>	'form-control',
					 'id'		=>	'yearc',
            ),
            'options' => array(
					 'empty_option' => 'Year/Grade',
					 'value_options' => array(
                             '9' => '9',
									  '10' => '10',
									  '11' => '11',
									  '12' => '12',
                     ),
            ),
        ));
		
		  
		 $this->add(array(
            'name' => 'agree_term',
				'type' => 'checkbox',
				'options' => array(
				 	  'use_hidden_element' => true,
					  'checked_value' => 1,
					  'unchecked_value' => 'no'
				 )
        ));
		
	
     $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit',
                'id' => 'submitbutton',
					 'class'	=>	'btn btn-danger col-md-1 pull-right',
            ),
        )); 
	 }
}