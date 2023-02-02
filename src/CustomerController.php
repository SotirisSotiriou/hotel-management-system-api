<?php

class CustomerController{
    private ReceptionGateway $gateway;

    public function __construct(ReceptionGateway $gateway){
        $this->gateway = $gateway;
    }


    public function processRequest(string $method): void{
        //TODO
        $data_json = file_get_contents('php://input');
        
        $data = (array)json_decode($data_json, true);

        $id = null;
        if(!empty($data['id'])){
            $id = $data['id'];
        }

        $errors = $this->getValidationErrors($data, $method);
        if(!empty($errors)){
            $this->respondUnprocessableEntity($errors);
            return;
        }

        if($id === null){
            if($method === "POST"){
                $output_data = $this->gateway->newCustomer($data);
                $this->respondCustomerCreated($id);
            }
            else if($method === "GET"){
                $output_data = $this->gateway->getAllCustomers();
                echo json_encode($output_data);
            }
            else{
                $this->respondMethodNotAllowed("GET, POST");
            }
        }
        else{
            if($method === "GET"){
                $output_data = $this->gateway->getCustomerByID($id);
                if($output_data === false){
                    $this->respondCustomerNotFound($id);
                }
                else{
                    echo json_encode($output_data);
                }
            }
            else if($method === "PATCH"){
                $output_data = $this->gateway->updateCustomerInfo($id, $data);
                if($output_data > 0){
                    $this->respondCustomerUpdated($id);
                }
                else{
                    $this->respondCustomerNotFound($id);
                }
            }
            else if($method === "DELETE"){
                $output_data = $this->gateway->deleteCustomer($id);
                if($output_data > 0){
                    $this->respondCustomerDeleted($id);
                }
                else{
                    $this->respondCustomerNotFound($id);
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


    private function respondCustomerNotFound(string $id): void{
        http_response_code(404);
        echo json_encode(["message" => "Customer with id $id not found"]);
    }


    private function respondCustomerCreated(string $id): void{
        http_response_code(201);
        echo json_encode(["message" => "Customer created", "id" => $id]);
    }


    private function respondCustomerDeleted(string $id): void{
        http_response_code(200);
        echo json_encode(["message" => "Customer deleted", "id" => $id]);
    }


    private function respondCustomerUpdated(string $id): void{
        http_response_code(200);
        echo json_encode(["message" => "Customer updated", "id" => $id]);
    }


    private function getValidationErrors(array $data, string $method): array{
        $errors = [];

        switch($method){
            case "POST":
                break;

            case "GET":
                break;

            case "PATCH":
                break;

            case "DELETE":
                break;
        }

        return $errors;
    }
}