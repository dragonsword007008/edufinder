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
				'student_name'              => $parent->student_name,
				'curricular_area'              => $parent->curricular_area,
				'description'              => $parent->description,
				'year_or_grade_parent'              => $parent->year_or_grade_parent,
				'agree_term'										=> $parent->agree_term,
				'role'					=> $parent->role,
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