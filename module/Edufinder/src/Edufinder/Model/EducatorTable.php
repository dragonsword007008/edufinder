<?php
namespace Edufinder\Model;
use Zend\Db\TableGateway\TableGateway;

class EducatorTable
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
    

	 
    public function saveUsers(Users $educator)
    {
        // for Zend\Db\TableGateway\TableGateway we need the data in array not object
        $data = array(
            'first_name'              => $educator->first_name,
            'last_name'              => $educator->last_name,
            'password'          => $educator->password,
            'email'             => $educator->email,
            'active'            => $educator->active,
            'picture'           => $educator->picture,
            'password_salt'     => $educator->password_salt,
            'registration_date' => $educator->registration_date,
            'registration_token'=> $educator->registration_token,
            'email_confirmed'   => $educator->email_confirmed,
            'suburb'              => $educator->suburb,
				'postcode'              => $educator->postcode,
            'state'              => $educator->state,
            'mobile_number'              => $educator->mobile_number,
				'gender'              => $educator->gender,
				//'child_protection_number'              => $educator->child_protection_number,
				//'verified_child_protection_number'              => $educator->verified_child_protection_number,
				'profile_description'              => $educator->profile_description,
				'curricular_name'              => $educator->curricular_name,
				'year_or_grade_curricular'              => $educator->year_or_grade_curricular,
				'tuition_service_curricular'              => $educator->tuition_service_curricular,
				'hourly_rate_curricular'              => $educator->hourly_rate_curricular,
				'specialisation_name'              => $educator->specialisation_name,
				'year_or_grade_specialisation'              => $educator->year_or_grade_specialisation,
				'tuition_service_specialisation'              => $educator->tuition_service_specialisation,
				'hourly_rate_specialisation'              => $educator->hourly_rate_specialisation,
				'agree_term'										=> $educator->agree_term,
				'role'					=>	$educator->role,
        );

        $id = (int)$educator->id;
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