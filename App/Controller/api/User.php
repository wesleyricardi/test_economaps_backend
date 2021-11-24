<?php 
namespace App\Controller;

require DIR."App/Model/User.php";
use App\Model\User as UserModel;

class User {
    public function login() {
        if($data = file_get_contents('php://input')):
            $data = json_decode($data);
            if(isset($data->login) && isset($data->password)) {
                $response = UserModel::login(login: $data->login, password: $data->password);

                echo json_encode($response);
            }
        endif;
    }

    public function authlogin() {
        if($token = file_get_contents('php://input')):
            if($token = json_decode($token)) {
                $response = UserModel::AuthLogin($token);
   
                echo json_encode($response);
            } 
        endif;
    }
}