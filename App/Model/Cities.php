<?php
namespace App\Model;

use \Error;

require_once DIR."/App/Data/CRUD.php";
use App\Data\CRUD;

class Cities {
    public static function readCity(int $id) :object {
        try {
            $city = CRUD::Consult('SELECT * FROM `cities` WHERE `id` LIKE ?', [$id]);

            if($city):
                $city->uf = CRUD::Consult('SELECT `name` FROM `uf` WHERE `id` LIKE ?', [$city->uf]);
                
                return (object) [
                    'result' => $city,
                ];
            else: {
                return (object) [
                    'message' => "not found any result"
                ];
            }
            endif;
        } catch (Error $e) {
            return (object) [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function readCities() :object {
        try {
            $cities = CRUD::Consult('SELECT * FROM `cities`', returnType: 'array');

            if($cities):
                foreach($cities as $city) $city->uf = CRUD::Consult('SELECT `name` FROM `uf` WHERE `id` LIKE ?', [$city->uf]);
                
                return (object) [
                    'result' => $cities,
                ];
            endif;
        } catch (Error $e) {
            return (object) [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }
}