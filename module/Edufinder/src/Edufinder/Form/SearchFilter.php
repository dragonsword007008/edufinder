<?php
namespace Edufinder\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class SearchFilter extends InputFilter
{
	public function __construct($sm)
	{
		// self::__construct(); // parnt::__construct(); - trows and error
				  
				  $this->add(array(
		            'name'       => 'postcode',
		            'required'   => true,
						'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
							array('name' => 'Int'),
						),
		            'validators' => array(
							 array(
									'name' => 'Regex',
									'options' => array(
										'pattern' => '/\d{4}/',
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
	}
}