<?php

class ReceptionController{

    private ReceptionGateway $gateway;


    public function __construct(ReceptionGateway $gateway){
        $this->gateway = $gateway;
    }


    public function processRequest(string $method, string $service): void{
        $data_json = file_get_contents('php://input');
        
        $data = json_decode($data_json, true);

        $id = null;
        if(!empty($data['id'])){
            $id = $data['id'];
        }

        $errors = $this->getValidationErrors($data, $service, $method);
        if(!empty($errors)){
            $this->respondUnprocessableEntity($errors);
            return;
        }

        if($id == null){
            if($method === "POST"){
                if($service === "room"){
                    $output_data = $this->gateway->createNewRoom($data);
                    $this->respondRoomCreated($output_data);
                }
            }
            else if($method === "GET"){
                if($service === "room"){
                    $output_data = $this->gateway->getAllRooms($data);
                    echo json_encode($output_data);
                }
            }
            else{
                $this->respondServiceNotFound();
            }
        }
        else{
            if($method === "GET"){
                if($service === "room"){
                    $output_data = $this->gateway->getRoomByID($id);
                    if(empty($output_data)){
                        $this->respondRoomNotFound($id);
                    }
                    else{
                        echo json_encode($output_data);
                    }
                }
            }
            else if($method === "DELETE"){
                if($service === "room"){
                    $output_data = $this->gateway->deleteRoom($id);
                    if($output_data > 0){
                        $this->respondRoomDeleted($id);
                    }
                    else{
                        $this->respondRoomNotFound($id);
                    }
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

    private function respondServiceNotFound(){
        http_response_code(404);
        echo json_encode(["message" => "Service not found"]);
    }


    private function respondRoomNotFound(string $id): void{
        http_response_code(404);
        echo json_encode(["message" => "Room with id $id not found"]);
    }

    private function respondCustomerNotFound(string $id): void{
        http_response_code(404);
        echo json_encode(["message" => "Customer with id $id not found"]);
    }
    
    
    private function respondReservationNotFound(string $id): void{
        http_response_code(404);
        echo json_encode(["message" => "Reservation with id $id not found"]);
    }


    private function respondRoomCreated(string $id): void{
        http_response_code(201);
        echo json_encode(["message" => "Room created", "id" => $id]);
    }


    private function respondReservationCreated(string $id): void{
        http_response_code(201);
        echo json_encode(["message" => "Reservation created", "id" => $id]);
    }


    private function respondCustomerCreated(string $id): void{
        http_response_code(201);
        echo json_encode(["message" => "Customer created", "id" => $id]);
    }

    private function respondReservationDeleted(string $id): void{
        http_response_code(200);
        echo json_encode(["message" => "Reservation deleted", "id" => $id]);
    }

    private function respondRoomDeleted(string $id): void{
        http_response_code(200);
        echo json_encode(["message" => "Room deleted", "id" => $id]);
    }

    private function respondCustomerDeleted(string $id): void{
        http_response_code(200);
        echo json_encode(["message" => "Customer deleted", "id" => $id]);
    }

    private function respondReservationUpdated(string $id): void{
        http_response_code(200);
        echo json_encode(["message" => "Reservation updated", "id" => $id]);
    }

    private function respondRoomUpdated(string $id): void{
        http_response_code(200);
        echo json_encode(["message" => "Room updated", "id" => $id]);
    }

    private function respondCustomerUpdated(string $id): void{
        http_response_code(200);
        echo json_encode(["message" => "Customer updated", "id" => $id]);
    }

    private function respondEmptyData(){
        http_response_code(404);
        echo json_encode(["message" => "Empty data"]);
    }


    private function getValidationErrors(array $data, string $service, string $method){
        $errors = [];

        if($method === "POST"){
            if($service === "reservation"){
                if(empty($data["customer_id"])){
                    $errors[] = "customer_id is required";
                }
                else if(filter_var($data["customer_id"], FILTER_VALIDATE_INT) == false){
                    $errors[] = "customer_id invalid format";
                }

                if(empty($data["checkin"])){
                    $errors[] = "checkin is required";
                }
                else if(!preg_match("$^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$", $data["checkin"])){
                    $errors[] = "checkin invalid format";
                }

                if(empty($data["checkout"])){
                    $errors[] = "checkout is required";
                }
                else if(!preg_match("$^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$", $data["checkout"])){
                    $errors[] = "checkout invalid format";
                }

                if(empty($data["room_id"])){
                    $errors[] = "room_id is required";
                }
                else if(filter_var($data["room_id"], FILTER_VALIDATE_INT) == false){
                    $errors[] = "room_id invalid format";
                }

                if(empty($data["billed"])){
                    $errors[] = "billed is required";
                }
                else if(!$data["billed"] === "true" and !$data["billed"] === "false"){
                    $errors[] = "billed invalid format";
                }

                if(empty($data["breakfast"])){
                    $errors[] = "breakfast is required";
                }
                else if(!$data["breakfast"] === "true" and !$data["breakfast"] === "false"){
                    $errors[] = "breakfast invalid format";
                }

                if(empty($data["lunch"])){
                    $errors[] = "lunch is required";
                }
                else if(!$data["lunch"] === "true" and !$data["lunch"] === "false"){
                    $errors[] = "lunch invalid format";
                }

                if(empty($data["dinner"])){
                    $errors[] = "dinner is required";
                }
                else if(!$data["dinner"] === "true" and !$data["dinner"] === "false"){
                    $errors[] = "dinner invalid format";
                }
            }
            else if($service === "room"){
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
            }
            else if($service === "customer"){
                if(empty($data["firstname"])){
                    $errors[] = "firstname is required";
                }
    
                if(empty($data["lastname"])){
                    $errors[] = "lastname is required";
                }
            }

        }
        else if($method === "PATCH"){
            if($service === "reservation"){
                if(!empty($data["customer_id"])){
                    if(filter_var($data["customer_id"], FILTER_VALIDATE_INT) == false){
                        $errors[] = "customer_id invalid format";
                    }
                }
                if(!empty($data["checkin"])){
                    if(!preg_match("$^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$", $data["checkin"])){
                        $errors[] = "checkin invalid format";
                    }
                }
                if(!empty($data["checkout"])){
                    if(!preg_match("$^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$", $data["checkout"])){
                        $errors[] = "checkout invalid format";
                    }
                }
                if(!empty($data["room_id"])){
                    if(filter_var($data["room_id"], FILTER_VALIDATE_INT) == false){
                        $errors[] = "room_id invalid format";
                    }
                }
                if(!empty($data["billed"])){
                    if(filter_var($data["billed"], FILTER_VALIDATE_BOOLEAN) == false){
                        $errors[] = "billed invalid format";
                    }
                }
                if(!empty($data["breakfast"])){
                    if(filter_var($data["breakfast"], FILTER_VALIDATE_BOOLEAN) == false){
                        $errors[] = "breakfast invalid format";
                    }
                }
                if(!empty($data["lunch"])){
                    if(filter_var($data["lunch"], FILTER_VALIDATE_BOOLEAN) == false){
                        $errors[] = "lunch invalid format";
                    }
                }
                if(!empty($data["dinner"])){
                    if(filter_var($data["dinner"], FILTER_VALIDATE_BOOLEAN) == false){
                        $errors[] = "dinner invalid format";
                    }
                }
                
            }
            else if($service === "room"){
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
            }
            //customer table doen't need anything
        }

        return $errors;
    }
}