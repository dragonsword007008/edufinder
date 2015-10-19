<?php
namespace Edufinder\Form;
use Zend\Form\Form;

class SearchForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('search');
        $this->setAttribute('method', 'post');
		  $this->setAttribute('role', 'form');
        $this->add(array(
            'name' => 'postcode',
				'type'  => 'text',
            'attributes' => array(               
					 'class'	=>	'form-control',
					 'id'		=>	'postcode',
					 'placeholder' => 'Postcode'
            ),
        ));
       $this->add(array(
            'name' => 'curricular_name',
				'type' => 'Select',
            'attributes' => array(
					 'class'	=>	'form-control col-sm-offset-2',
					 'id'		=>	'curricular',
            ),
            'options' => array(
                'label' => 'Subject Area',
					 'empty_option' => 'Currriculumn Areas',
					 'value_options' => array(
                             'Coding' => 'Coding',
                     ),
            ),
        ));
        $this->add(array(
            'name' => 'year_or_grade_curricular',
				'type' => 'Select',
            'attributes' => array(
					 'class'	=>	'form-control col-sm-offset-4',
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
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
					 'class' => 'btn btn-danger col-md-1 pull-right',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        )); 
    }
}