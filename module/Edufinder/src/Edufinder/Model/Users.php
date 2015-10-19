<?php
namespace Edufinder\Model;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
// the object will be hydrated by Zend\Db\TableGateway\TableGateway
class Users implements InputFilterAwareInterface
{
    public $id;
    public $first_name;
    public $last_name;
    public $password;
    public $email;   
    public $active; 
    public $picture;    
    public $password_salt;
    public $registration_date;
    public $registration_token; 
    public $email_confirmed;  
    public $suburb;  
    public $state;
	 public $postcode;
    public $mobile_number; 
	 public $gender;   
	 //public $child_protection_number; 
	 //public $verified_child_protection_number; 
	 public $profile_description; 
	 public $curricular_name; 
	 public $year_or_grade_curricular; 
	 public $tuition_service_curricular; 
	 public $hourly_rate_curricular; 
	 public $specialisation_name; 
	 public $year_or_grade_specialisation; 
	 public $tuition_service_specialisation; 
	 public $hourly_rate_specialisation; 
	 public $student_name;
	 public $curricular_area; 
	 public $year_or_grade_parent; 
	 public $description;
	 public $agree_term;      
    // Hydration
    // ArrayObject, or at least implement exchangeArray. For Zend\Db\ResultSet\ResultSet to work
    public function exchangeArray($data) 
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->first_name = (!empty($data['first_name'])) ? $data['first_name'] : null;
        $this->last_name = (!empty($data['last_name'])) ? $data['last_name'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->active = (isset($data['active'])) ? $data['active'] : null;
        $this->picture = (!empty($data['picture'])) ? $data['picture'] : null;
        $this->password_salt = (!empty($data['password_salt'])) ? $data['password_salt'] : null;
        $this->registration_date = (!empty($data['registration_date'])) ? $data['registration_date'] : null;
        $this->registration_token = (!empty($data['registration_token'])) ? $data['registration_token'] : null;
        $this->email_confirmed = (isset($data['email_confirmed'])) ? $data['email_confirmed'] : null;
		  $this->suburb = (isset($data['suburb'])) ? $data['suburb'] : null;
		  $this->state = (isset($data['state'])) ? $data['state'] : null;
		  $this->postcode = (isset($data['postcode'])) ? $data['postcode'] : null;
		  $this->mobile_number = (isset($data['mobile_number'])) ? $data['mobile_number'] : null;
		  $this->gender = (isset($data['gender'])) ? $data['gender'] : null;
		  //$this->child_protection_number = (isset($data['child_protection_number'])) ? $data['child_protection_number'] : null;
		  //$this->verified_child_protection_number = (isset($data['verified_child_protection_number'])) ? $data['verified_child_protection_number'] : null;
		  $this->profile_description = (isset($data['profile_description'])) ? $data['profile_description'] : null;
		  $this->curricular_name = (isset($data['curricular_name'])) ? $data['curricular_name'] : null;
		  $this->year_or_grade_curricular = (isset($data['year_or_grade_curricular'])) ? $data['year_or_grade_curricular'] : null;
		  $this->tuition_service_curricular = (isset($data['tuition_service_curricular'])) ? $data['tuition_service_curricular'] : null;
		  $this->hourly_rate_curricular = (isset($data['hourly_rate_curricular'])) ? $data['hourly_rate_curricular'] : null;
		  $this->specialisation_name = (isset($data['specialisation_name'])) ? $data['specialisation_name'] : null;
		  $this->year_or_grade_specialisation = (isset($data['year_or_grade_specialisation'])) ? $data['year_or_grade_specialisation'] : null;
		  $this->tuition_service_specialisation = (isset($data['tuition_service_specialisation'])) ? $data['tuition_service_specialisation'] : null;
		  $this->hourly_rate_specialisation = (isset($data['hourly_rate_specialisation'])) ? $data['hourly_rate_specialisation'] : null;
		  $this->student_name = (isset($data['student_name'])) ? $data['student_name'] : null;
		  $this->curricular_area = (isset($data['curricular_area'])) ? $data['curricular_area'] : null;
		  $this->year_or_grade_parent = (isset($data['year_or_grade_parent'])) ? $data['year_or_grade_parent'] : null;
		  $this->description = (isset($data['description'])) ? $data['description'] : null;
		  $this->agree_term = (isset($data['agree_term'])) ? $data['agree_term'] : null;
    }   

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