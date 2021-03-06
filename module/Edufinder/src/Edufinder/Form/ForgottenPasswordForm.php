<?php
namespace Edufinder\Form;
use Zend\Form\Form;
class ForgottenPasswordForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('forgottenpassword');
        $this->setAttribute('method', 'post');
		
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
            ),
            'options' => array(
                'label' => 'Email',
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