<?php
namespace Edufinder\Model;
use Zend\Db\TableGateway\TableGateway;

class ParentTable
{
    protected $tableGateway;
	 
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    public function getUsers($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    public function getUsersByToken($token)
    {
        $rowset = $this->tableGateway->select(array('registration_token' => $token));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $token");
        }
        return $row;
    }
    
    public function activateUsers($id)
    {
        $data['active'] = 1;
        $data['email_confirmed'] = 1;
        $this->tableGateway->update($data, array('id' => (int)$id));
    } 
	   
    public function getUsersByEmail($email)
    {
        $rowset = $this->tableGateway->select(array('email' => $email));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $email");
        }
        return $row;
    }
    public function changePassword($id, $password)
    {
        $data['password'] = $password;
        $this->tableGateway->update($data, array('id' => (int)$id));
    }
    

	 
    public function saveUsers(Users $parent)
    {
        // for Zend\Db\TableGateway\TableGateway we need the data in array not object
        $data = array(
            'first_name'              => $parent->first_name,
            'last_name'              => $parent->last_name,
            'password'          => $parent->password,
            'email'             => $parent->email,
            'active'            => $parent->active,
            'picture'           => $parent->picture,
            'password_salt'     => $parent->password_salt,
            'registration_date' => $parent->registration_date,
            'registration_token'=> $parent->registration_token,
            'email_confirmed'   => $parent->email_confirmed,
            'suburb'              => $parent->suburb,
				'postcode'              => $parent->postcode,
            'state'              => $parent->state,
            'mobile_number'              => $parent->mobile_number,
				'gender'              => $parent->gender,
				'child_protection_number'              => $parent->child_protection_number,
				'verified_child_protection_number'              => $parent->verified_child_protection_number,
				'profile_description'              => $parent->profile_description,
				'curricular_name'              => $parent->curricular_name,
				'year_or_grade_curricular'              => $parent->year_or_grade_curricular,
				'tuition_service_curricular'              => $parent->tuition_service_curricular,
				'hourly_rate_curricular'              => $parent->hourly_rate_curricular,
				'specialisation_name'              => $parent->specialisation_name,
				'year_or_grade_specialisation'              => $parent->year_or_grade_specialisation,
				'tuition_service_specialisation'              => $parent->tuition_service_specialisation,
				'hourly_rate_specialisation'              => $parent->hourly_rate_specialisation,
				'agree_term'										=> $parent->agree_term,
        );

        $id = (int)$parent->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUsers($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
    
    public function deleteUsers($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }   
}