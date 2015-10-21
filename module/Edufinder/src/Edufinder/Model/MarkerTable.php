<?php
namespace Edufinder\Model;
use Zend\Db\TableGateway\TableGateway;

class MarkerTable
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
    public function getMarker($postcode)
    {
        $postcode  = (int) $postcode;
        $rowset = $this->tableGateway->select(array('postcode' => $postcode));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $postcode");
        }
        return $row;
    }
  
}