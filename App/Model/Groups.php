<?php
namespace App\Model;

use \Error;

require_once DIR.'vendor/firebase/php-jwt/src/JWT.php';
use FireBase\JWT\JWT;

require_once DIR.'App/Data/CRUD.php';
use App\Data\CRUD;

class Groups {
    public static function readGroups() :object {
        try {
            $groups = CRUD::Consult('SELECT * FROM `groups`', returnType: 'array');

            if($groups):
                foreach($groups as $group) {
                    $group->cities = CRUD::Consult('SELECT `id` FROM `group_cities` WHERE `group` LIKE ?', [$group->id], returnType: 'array');
                     foreach($group->cities as $key => $city) {
                       $response = Cities::readCity(id: $city->id);
                       $group->cities[$key] = $response->result;
                    } 
                }
                return (object) [
                    'result' => $groups,
                ];
            endif;
        } catch (Error $e) {
            return (object) [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function insert(string $name, array $cities) : object {
        
        try {
            CRUD::beginTransaction();

            CRUD::Query('INSERT INTO `groups` (`name`) VALUES (?)', [$name]);
            $group = CRUD::Consult('SELECT `id` FROM `groups` WHERE `name` LIKE ?', [$name]);
            if($group) {
                foreach($cities as $city) $insertValue[] = [$city, $group->id];
                CRUD::Query('INSERT INTO `group_cities` (`id`, `group`) VALUES (?, ?)', $insertValue);
            }

            CRUD::commit();
            http_response_code(201);
            return (object) [
                'message' => 'Group create successfully'
            ];

        } catch (Error $e) {
            if($e->getCode() != 1062) http_response_code(500);

            return (object) [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function update(int $id, string $name, array $cities) : object {
        
        try {
            CRUD::beginTransaction();

            CRUD::Query('UPDATE `groups` SET `name` = ? WHERE `groups`.`id` = ?', [$name, $id]);
            CRUD::Query('DELETE FROM `group_cities` WHERE `group` LIKE ?', [$id]);

            foreach($cities as $city) $insertValue[] = [$city, $id];
            CRUD::Query('INSERT INTO `group_cities` (`id`, `group`) VALUES (?, ?)', $insertValue);
            

            CRUD::commit();
            http_response_code(201);
            return (object) [
                'message' => 'Group updated successfully'
            ];

        } catch (Error $e) {
            if($e->getCode() != 1062) http_response_code(500);

            return (object) [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function delete(int $id) : object {
        try {
            CRUD::beginTransaction();

                CRUD::Query('DELETE FROM `group_cities` WHERE `group` LIKE ?', [$id]);
                CRUD::Query('DELETE FROM `groups` WHERE `id` LIKE ?', [$id]);
            
            CRUD::commit();
            return (object) [
                'message' => 'Successful delete'
            ];
        } catch (Error $e) {

            http_response_code(500);
            return (object) [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }

}
