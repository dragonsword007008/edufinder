<?php
namespace Edufinder\Model;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
// the object will be hydrated by Zend\Db\TableGateway\TableGateway
class Edufinder implements InputFilterAwareInterface
{
    public $id;
    public $first_name;
    public $last_name;
    public $password;
    public $email;  
    public $l_id;    
    public $active; 
    public $question;   
    public $answer; 
    public $picture;    
    public $password_salt;
    public $registration_date;
    public $registration_token; 
    public $email_confirmed;  
    public $suburb;  
    public $state;
    public $mobile_number;      
    // Hydration
    // ArrayObject, or at least implement exchangeArray. For Zend\Db\ResultSet\ResultSet to work
    public function exchangeArray($data) 
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->first_name = (!empty($data['first_name'])) ? $data['first_name'] : null;
        $this->last_name = (!empty($data['last_name'])) ? $data['last_name'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->l_id = (!empty($data['l_id'])) ? $data['l_id'] : null;
        $this->active = (isset($data['active'])) ? $data['active'] : null;
        $this->picture = (!empty($data['picture'])) ? $data['picture'] : null;
        $this->password_salt = (!empty($data['password_salt'])) ? $data['password_salt'] : null;
        $this->registration_date = (!empty($data['registration_date'])) ? $data['registration_date'] : null;
        $this->registration_token = (!empty($data['registration_token'])) ? $data['registration_token'] : null;
        $this->email_confirmed = (isset($data['email_confirmed'])) ? $data['email_confirmed'] : null;
           $this->suburb = (isset($data['suburb'])) ? $data['suburb'] : null;
          $this->state = (isset($data['state'])) ? $data['state'] : null;
         $this->mobile_number = (isset($data['mobile_number'])) ? $data['mobile_number'] : null;
    }   
    // Extraction. The Registration from the tutorial works even without it.
    // The standard Hydrator of the Form expects getArrayCopy to be able to bind
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    
    protected $inputFilter;
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
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
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
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
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }   
}