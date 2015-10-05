<?php
namespace Edufinder\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class RegistrationFilter extends InputFilter
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
					'table'   => 'users',
					'field'   => 'email',
					'adapter' => $sm->get('Zend\Db\Adapter\Adapter'),
				),
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
						'max'      => 8,
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