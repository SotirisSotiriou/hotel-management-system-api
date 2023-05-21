<?php

class ReservationController{
    private ReceptionGateway $gateway;


    public function __construct(ReceptionGateway $gateway){
        $this->gateway = $gateway;
    }


    public function processRequest(string $method, ?int $id): void{
        $data_json = file_get_contents('php://input');
        
        $data = (array)json_decode($data_json, true);

        $errors = $this->getValidationErrors($data, $method);
        if(!empty($errors)){
            $this->respondUnprocessableEntity($errors);
            return;
        }

        if($id === null){
            if($method === "POST"){
                $output_data = $this->gateway->addReservation($data);
                $this->respondReservationCreated($output_data);
            }
            else if($method === "GET"){
                $output_data = $this->gateway->getAllReservations();
                echo json_encode($output_data);
            }
            else{
                $this->respondMethodNotAllowed("GET, POST");
            }
        }
        else{
            if($method === "GET"){
                $output_data = $this->gateway->getreservationInfo($id);
                if($output_data === false){
                    $this->respondReservationNotFound($id);
                }
                else{
                    echo json_encode($output_data);
                }
            }
            else if($method === "PATCH"){
                $output_data = $this->gateway->updateReservationInfo($id, $data);
                if($output_data > 0){
                    $this->respondReservationUpdated($id);
                }
                else{
                    $this->respondReservationNotFound($id);
                }
            }
            else if($method === "DELETE"){
                $output_data = $this->gateway->deleteReservation($id);
                if($output_data > 0){
                    $this->respondReservationDeleted($id);
                }
                else{
                    $this->respondReservationNotFound($id);
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

    private function respondServiceNotFound(): void{
        http_response_code(404);
        echo json_encode(["message" => "Service not found"]);
    }


    private function respondReservationNotFound(string $id): void{
        http_response_code(404);
        echo json_encode(["message" => "Reservation with id $id not found"]);
    }


    private function respondReservationCreated(string $id): void{
        http_response_code(201);
        echo json_encode(["message" => "Reservation created", "id" => $id]);
    }


    private function respondReservationDeleted(string $id): void{
        http_response_code(200);
        echo json_encode(["message" => "Reservation deleted", "id" => $id]);
    }


    private function respondReservationUpdated(string $id): void{
        http_response_code(200);
        echo json_encode(["message" => "Reservation updated", "id" => $id]);
    }


    public function getValidationErrors(array $data, string $method): array{
        $errors = [];

        switch($method){
            case "POST":
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
                break;

            case "GET":
                break;

            case "PATCH":
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
                break;

            case "DELETE":
                break;
        }

        return $errors;
    }
}