<?php
namespace Edufinder\Model;

// the object will be hydrated by Zend\Db\TableGateway\TableGateway
class Marker
{
    public $postcode;
    public $suburb;
    public $state;
    public $dc;
	 public $type;
	 public $lat;
	 public $lon;
	 
    // Hydration
    // ArrayObject, or at least implement exchangeArray. For Zend\Db\ResultSet\ResultSet to work
    public function exchangeArray($data) 
    {
        $this->postcode     = (!empty($data['postcode'])) ? $data['postcode'] : null;
        $this->suburb = (!empty($data['suburb'])) ? $data['suburb'] : null;
        $this->state = (!empty($data['state'])) ? $data['state'] : null;
        $this->dc = (!empty($data['dc'])) ? $data['dc'] : null;
        $this->type = (!empty($data['type'])) ? $data['type'] : null;
        $this->lat = (isset($data['lat'])) ? $data['lat'] : null;
        $this->lon = (!empty($data['lon'])) ? $data['lon'] : null;
        
    }   

}