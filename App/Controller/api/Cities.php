<?php
namespace App\Controller;

require DIR.'App/Model/Cities.php';

use App\Model\Cities as CitiesModel;

class Cities {
    public function get() :void {
        $response = CitiesModel::readCities();
        if(isset($response->result)) {
            echo json_encode($response->result, JSON_NUMERIC_CHECK);
        }
    }
}