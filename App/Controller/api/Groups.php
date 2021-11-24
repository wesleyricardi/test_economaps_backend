<?php
namespace App\Controller;

require DIR."App/Model/Groups.php";
use App\Model\Groups as GroupsModel;

class Groups {

    public function get() :void {
        $response = GroupsModel::readGroups();
        if(isset($response->result)) {
            echo json_encode($response->result, JSON_NUMERIC_CHECK);
        }
        else {
            var_dump($response);
        }
    }

    public function create() :void {
        if($data = file_get_contents('php://input')):
            $data = json_decode($data);
            
            $response = GroupsModel::insert(name: $data->name, cities: $data->cities);

            echo json_encode($response);
            
        endif;
    }

    public function change() :void {
        if($data = file_get_contents('php://input')):
            $data = json_decode($data);
            
            $response = GroupsModel::update(id: $data->id, name: $data->name, cities: $data->cities);

           echo json_encode($response);
            
        endif;
    }

    public function delete(array $data) :void {
        $response = GroupsModel::delete(id: $data['id']);

        echo json_encode($response);
    }
/* 
    public function editGroup() :void {
        if( $data = file_get_contents('php://input')):
            $data = json_decode($data);
            

            $response = GroupsModel::insertItens(Groups_id: $data->Groups, user_id: $data->user, itens: $data->itens);

            echo json_encode($response);
            
        endif; 
    }

    public function deleteGroup() :void {
        if($data = file_get_contents('php://input')):
            $data = json_decode($data);

            $response = GroupsModel::closeGroupsRequest(Groups_id: $data->Groups);

            echo json_encode($response);
        endif; 
    } */
}
