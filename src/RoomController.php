<?php

class RoomController{
    private ReceptionGateway $gateway;


    public function __construct(ReceptionGateway $gateway){
        $this->gateway = $gateway;
    }


    public function processRequest(string $method, ?int $id){
        $data_json = file_get_contents('php://input');
        
        $data = (array)json_decode($data_json, true);

        $errors = $this->getValidationErrors($data, $method);
        if(!empty($errors)){
            $this->respondUnprocessableEntity($errors);
            return;
        }

        if($id === null){
            if($method === "POST"){
                $output_data = $this->gateway->createNewRoom($data);
                $this->respondRoomCreated($output_data);
            }
            else if($method === "GET"){
                $output_data = $this->gateway->getAllRooms($data);
                echo json_encode($output_data);
            }
            else{
                $this->respondMethodNotAllowed("GET, POST");
            }
        }
        else{
            if($method === "GET"){
                $output_data = $this->gateway->getRoomByID($id);
                if($output_data === false){
                    $this->respondRoomNotFound($id);
                }
                else{
                    echo json_encode($output_data);
                }
            }
            else if($method === "PATCH"){
                $output_data = $this->gateway->updateRoomInfo($id, $data);
                if($output_data > 0){
                    $this->respondRoomUpdated($id);
                }
                else{
                    $this->respondRoomNotFound();
                }
            }
            else if($method === "DELETE"){
                $output_data = $this->gateway->deleteRoom($id);
                    if($output_data > 0){
                        $this->respondRoomDeleted($id);
                    }
                    else{
                        $this->respondRoomNotFound($id);
                    }
            }
            else{
                $this->respondMethodNotAllowed("GET, PATCH, DELETE");
            }
        }
    }


    private function respondUnprocessableEntity(array $errors): void{
        http_response_code(422);
        echo json_encode(["errors" => $errors]);
    }


    private function respondMethodNotAllowed(string $allowed_methods): void{
        http_response_code(405);
        header("Allow: $allowed_methods");
    }

  
    private function respondEmptyData(): void{
        http_response_code(404);
        echo json_encode(["message" => "Empty data"]);
    }


    private function respondRoomNotFound(string $id): void{
        http_response_code(404);
        echo json_encode(["message" => "Room with id $id not found"]);
    }


    private function respondRoomCreated(string $id): void{
        http_response_code(201);
        echo json_encode(["message" => "Room created", "id" => $id]);
    }


    private function respondRoomDeleted(string $id): void{
        http_response_code(200);
        echo json_encode(["message" => "Room deleted", "id" => $id]);
    }


    private function respondRoomUpdated(string $id): void{
        http_response_code(200);
        echo json_encode(["message" => "Room updated", "id" => $id]);
    }


    private function getValidationErrors(array $data, string $method){
        $errors = [];

        switch($method){
            case "POST":
                if(empty($data["number"])){
                    $errors[] = "number is required";
                }else if(filter_var($data["number"], FILTER_VALIDATE_INT) === false){
                    $errors[] = "room number must be an integer";
                }
    
                if(empty($data["type"])){
                    $errors[] = "type is required";
                }
    
                if(empty($data["beds"])){
                    $errors[] = "beds number required";
                }else if(filter_var($data["beds"], FILTER_VALIDATE_INT) === false){
                    $errors[] = "beds number must be an integer";
                }
    
                if(empty($data["cost_per_day"])){
                    $errors[] = "cost_per_day is required";
                }else if(filter_var($data["cost_per_day"], FILTER_VALIDATE_FLOAT) === false){
                    $errors[] = "cost must be a decimal number";
                }
                break;

            case "GET":
                break;

            case "PATCH":
                if(!empty($data["beds"])){
                    if(filter_var($data["beds"], FILTER_VALIDATE_INT) == false){
                        $errors[] = "beds invalid format";
                    }
                }
                if(!empty($data["cost_per_day"])){
                    if(filter_var($data["cost_per_day"], FILTER_VALIDATE_FLOAT) == false){
                        $errors[] = "cost_per_day invalid format";
                    }
                }
                if(!empty($data["number"])){
                    if(filter_var($data["number"], FILTER_VALIDATE_INT) == false){
                        $errors[] = "number invalid format";
                    }
                }
                break;

            case "DELETE":
                break;
        }

        return $errors;
    }
}