<?php
namespace Edufinder\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class RegistrationFilterEdu extends InputFilter
{
	public function __construct($sm)
	{
		// self::__construct(); // parnt::__construct(); - trows and error
		
		        $this->add(array(
		            'name'       => 'email',
		            'required'   => true,
		            'validators' => array(
		               array(
		                    'name' => 'EmailAddress'
		                ),
							 array(
								 'name'		=> 'Zend\Validator\Db\NoRecordExists',
								 'options' => array(
									 'table'   => 'educator',
									 'field'   => 'email',
									 'adapter' => $sm->get('Zend\Db\Adapter\Adapter'),
								 ),
							 ),
		            ),
		        ));
				  
				  $this->add(array(
		            'name'       => 'postcode',
		            'required'   => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
							array('name' => 'Int'),
						),
						'error_message' => 'Please enter 4 digits postcode',
		            'validators' => array(
							 array(
									'name' => 'Regex',
									'options' => array(
										'pattern' => '/^[0-9]{4}$/',
									),
							  ),
							 
		            ),
		        ));
				  
				  $this->add(array(
		            'name'       => 'mobile_number',
		            'required'   => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
							array('name' => 'Int'),
						),
						'error_message' => 'Wrong format!',
		            'validators' => array(
							 array(
									'name' => 'Regex',
									'options' => array(
										'pattern' => '/\d[9,10]/',
									)
							  ),
							 array(
								 'name'		=> 'Zend\Validator\Db\NoRecordExists',
								 'options' => array(
									 'table'   => 'educator',
									 'field'   => 'mobile_number',
									 'adapter' => $sm->get('Zend\Db\Adapter\Adapter'),
								 ),
							 ),
		            ),
		        ));

				  $this->add(array(
		            'name'       => 'curricular_name',
		            'required'   => false,
						'allow_empty' => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
						),
		            'validators' => array(
		               array(
		                    'name' => 'Alpha',
		                ),
							 
		            ),
		        ));
				  
				  $this->add(array(
		            'name'       => 'tuition_service_curricular',
		            'required'   => false,
						'allow_empty' => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
						),
						'disable_inarray_validator' => true,
		        ));
				  
				  $this->add(array(
		            'name'       => 'year_or_grade_curricular',
		            'required'   => false,
						'allow_empty' => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
						),
		            'validators' => array(
		               array(
		                    'name' => 'Digits',
		                ),
							 
		            ),
		        ));
				  
				  $this->add(array(
		            'name'       => 'agree_term',
		            'required'   => true,
						'error_message' => 'You must agree to the terms of use.',
		            'validators' => array(
							 array(
									'name' => 'Digits',
									'break_chain_on_failure' => true,
							  ),
							 
		            ),
		        ));
			  
			  $this->add(array(
		            'name'       => 'hourly_rate_curricular',
		            'required'   => false,
						'allow_empty' => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
							array('name' => 'Digits'),
						),
		            'validators' => array(
							 array(
									'name' => 'GreaterThan',
									'options' => array(
									  'min'      => 0,
								 ),
							  ),
		            ),
		        ));
				  
				  $this->add(array(
		            'name'       => 'hourly_rate_specialisation',
		            'required'   => false,
						'allow_empty' => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
							array('name' => 'Digits'),
						),
		            'validators' => array(
							 array(
									'name' => 'GreaterThan',
									'options' => array(
									  'min'      => 0,
								 ),
							  ),							
		            ),
		        ));
			  
			   	$this->add(array(
		            'name'       => 'suburb',
		            'required'   => false,
						'allow_empty' => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
						),
		            'validators' => array(
		               array(
		                    'name' => 'StringLength',
								  'options' => array(
								  		'min' => 3,
								  )
		                ),							
		            ),
		        ));
				  
				  $this->add(array(
		            'name'       => 'state',
		            'required'   => false,
						'allow_empty' => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
						),
		            'validators' => array(
		               array(
		                    'name' => 'Alpha',
		                ),							 
		            ),
		        ));
				  
			  	  $this->add(array(
		            'name'       => 'photo',
		            'required'   => false,
						'allow_empty' => true,
						'validators' => array(
							array(
								 'name' => 'Zend\Validator\File\Extension',
								 'options' => array(
									  'extension' => array('png', 'jpg', 'gif'),
								 ),
							),
					  ),
		        ));
				  
				  $this->add(array(
		            'name'       => 'profile_description',
		            'required'   => false,
						'allow_empty' => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
						),
		            'validators' => array(
		               array(
								 'name'    => 'StringLength',
								 'options' => array(
									  'encoding' => 'UTF-8',
									  'min'      => 1,
									  'max'      => 500,
								 ),
							),
							
		            ),
		        ));
			  		  
				  $this->add(array(
		            'name'       => 'specialisation_name',
		            'required'   => false,
						'allow_empty' => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
						),
		            'validators' => array(
		               array(
		                    'name' => 'Alpha',
		                ),
							 
		            ),
		        ));
				  
				  $this->add(array(
		            'name'       => 'tuition_service_specialisation',
		            'required'   => false,
						'allow_empty' => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
						),
						'disable_inarray_validator' => true,
		            
		        ));
				  
				  $this->add(array(
		            'name'       => 'year_or_grade_specialisation',
		            'required'   => false,
						'allow_empty' => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
						),
		            'validators' => array(
		               array(
		                    'name' => 'Digits',
		                ),
							 
		            ),
		        ));
				  
				   $this->add(array(
		            'name'       => 'first_name',
		            'required'   => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
						),
		            'validators' => array(
		               array(
		                    'name' => 'Alpha',
		                ),
							 
		            ),
		        ));
		
		 			$this->add(array(
		            'name'       => 'last_name',
		            'required'   => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
						),
		            'validators' => array(
		               array(
		                    'name' => 'Alpha',
		                ),
							 
		            ),
		        ));
		
				$this->add(array(
					'name'     => 'password',
					'required' => true,
					'filters'  => array(
						array('name' => 'StripTags'),
						array('name' => 'StringTrim'),
					),
					'validators' => array(
						array(
							'name'    => 'StringLength',
							'options' => array(
								'encoding' => 'UTF-8',
								'min'      => 6,
								'max'      => 8,
							),
						),
					),
				));
					
				$this->add(array(
					'name'     => 'password_confirm',
					'required' => true,
					'filters'  => array(
						array('name' => 'StripTags'),
						array('name' => 'StringTrim'),
					),
					'validators' => array(
						array(
							'name'    => 'StringLength',
							'options' => array(
								'encoding' => 'UTF-8',
								'min'      => 6,
								'max'      => 12,
							),
						),
					  array(
							'name'    => 'Identical',
							'options' => array(
								 'token' => 'password',
							),
					  ),
					),
				));		
	}
}