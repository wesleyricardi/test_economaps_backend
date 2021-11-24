<?php
namespace App\Model;
require_once DIR.'vendor/firebase/php-jwt/src/JWT.php';
require_once DIR.'App/Data/CRUD.php';

use FireBase\JWT\JWT;

use App\Data\CRUD as CRUD;


use \Error;

class User {

    public static function login(string $login, string $password) :object {
        try {
            $result = CRUD::Consult('SELECT `id`, `password` FROM `users` WHERE `login` LIKE ?', [$login]);
            if($result):
                if($result->password == $password):
                    return (object) [
                        'token' => JWT::encode(['id' => $result->id], key: JWT_KEY),
                        'token_type' => 'JWT',
                        'id' => $result->id,
                    ];
                else:
                    http_response_code(203);
                    return (object) [
                        'message' => 'Wrong password',
                    ];
                endif;
            else:
                http_response_code(204);
                return (object) [
                    'message' => 'Account does not exist',
                ];
            endif;
        } catch (Error $e) {
            return (object) [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function AuthLogin(string $token) :object {
        $tokendecode = JWT::decode(jwt: $token, key: JWT_KEY, allowed_algs: array('HS256'));
        try {
            $response = CRUD::Consult('SELECT `id` FROM `users` WHERE `id` LIKE ?', [1 => $tokendecode->id]);

            if($response)
            return (object) [
                'id' => $tokendecode->id
            ];
        } catch(Error $e) {
            return (object) [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }
}