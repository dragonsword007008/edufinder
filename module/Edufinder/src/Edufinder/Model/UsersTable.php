<?php
namespace Edufinder\Model;
use Zend\Db\TableGateway\TableGateway;

class UsersTable
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
    public function getUser($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    public function getUserByToken($token)
    {
        $rowset = $this->tableGateway->select(array('registration_token' => $token));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $token");
        }
        return $row;
    }
    
    public function activateUser($id)
    {
        $data['active'] = 1;
        $data['email_confirmed'] = 1;
        $this->tableGateway->update($data, array('id' => (int)$id));
    }   
    public function getUserByEmail($email)
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
    
    public function saveUser(Edufinder $users)
    {
        // for Zend\Db\TableGateway\TableGateway we need the data in array not object
        $data = array(
            'first_name'              => $users->first_name,
            'last_name'              => $users->last_name,
            'password'          => $users->password,
            'email'             => $users->email,
            'l_id'               => $users->l_id,
            'active'            => $users->active,
            'picture'           => $users->picture,
            'password_salt'     => $users->password_salt,
            'registration_date' => $users->registration_date,
            'registration_token'=> $users->registration_token,
            'email_confirmed'   => $users->email_confirmed,
            'suburb'              => $users->suburb,
            'state'              => $users->state,
            'mobile_number'              => $users->mobile_number,
        );
        // If there is a method getArrayCopy() defined in Edufinder you can simply call it.
        // $data = $users->getArrayCopy();
        $id = (int)$users->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
    
    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }   
}