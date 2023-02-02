<?php

class ReceptionGateway{
    private PDO $conn;

    public function __construct(Database $database){
        $this->conn = $database->getConnection();
    }

//Room features

    public function getAllRooms(){
        $sql = "SELECT *
                FROM room
                ORDER BY number ASC";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        $data = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }

        if(empty($data)){
            return false;
        }

        return $data;
    }


    public function getRoomByID(int $id): array | false{
        $sql = "SELECT * 
                FROM room 
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }

    public function getAllAvailableRooms(array $data): array | false{
        $sql = "SELECT * 
                FROM room
                WHERE id NOT IN (
                    SELECT room_id AS id
                    FROM room_reservation
                    WHERE (:start_date1 >= checkin AND :start_date2 <= checkout) OR
                    (:end_date1 >= checkin AND :end_date2 <= checkout) OR
                    (:start_date3 <= checkin AND :end_date3 >= checkout)
                )
                ORDER BY number ASC";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":start_date1", $data["start_date"], PDO::PARAM_STR);
        $stmt->bindValue(":start_date2", $data["start_date"], PDO::PARAM_STR);
        $stmt->bindValue(":end_date1", $data["end_date"], PDO::PARAM_STR);
        $stmt->bindValue(":end_date2", $data["end_date"], PDO::PARAM_STR);
        $stmt->bindValue(":start_date3", $data["start_date"], PDO::PARAM_STR);
        $stmt->bindValue(":end_date3", $data["end_date"], PDO::PARAM_STR);

        $stmt->execute();

        $output = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $output[] = $row;
        }

        if(empty($output)){
            return false;
        }

        return $output;
    }


    public function createNewRoom(array $data): string{
        $sql = "INSERT INTO room (beds, type, cost_per_day, number) 
                VALUES (:beds, :type, :cost_per_day, :number)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":beds", $data["beds"], PDO::PARAM_INT);
        $stmt->bindValue(":type", $data["type"], PDO::PARAM_STR);
        $stmt->bindValue(":cost_per_day", $data["cost_per_day"], PDO::PARAM_STR);
        $stmt->bindValue(":number", $data["number"], PDO::PARAM_INT);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    
    public function deleteRoom(int $id): int{
        $sql = "DELETE FROM room WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();

        return $stmt->rowCount();
    }


    public function updateRoomInfo(int $id, array $data): int{
        $fields = [];

        if(array_key_exists("beds", $data)){
            $fields["beds"] = [$data["beds"], PDO::PARAM_INT];
        }

        if(array_key_exists("number", $data)){
            $fields["number"] = [$data["number"], PDO::PARAM_INT];
        }

        if(array_key_exists("type", $data)){
            $fields["type"] = [$data["type"], PDO::PARAM_STR];
        }

        if(array_key_exists("cost_per_day", $data)){
            $fields["cost_per_day"] = [$data["cost_per_day"], PDO::PARAM_STR];
        }

        if(empty($fields)){
            return 0;
        }else{
            $sets = array_map(function($value){
                return "$value = :$value";
            }, array_keys($fields));

            $sql = "UPDATE room SET " . implode(", ", $sets) . " WHERE id = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(":id", $id);

            foreach($fields as $name => $values){
                $stmt->bindValue(":$name", $values[0], $values[1]);
            }

            $stmt->execute();

            return $stmt->rowCount();
        }
    }


//Reservation features

    public function getAllReservations(): array | false{
        $sql = "SELECT *
                FROM room_reservation
                ORDER BY checkin DESC";
        
        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }

        if(empty($data)){
            return false;
        }
        
        return $data;
    }
    

    public function getReservationInfo(int $id): array | false{
        $sql = "SELECT * 
                FROM room_reservation 
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }     


    public function addReservation(array $data): string{
        $sql = "INSERT INTO room_reservation (customer_id, checkin, checkout, room_id, billed, breakfast, lunch, dinner) 
                VALUES (:customer_id, :checkin, :checkout, :room_id, :billed, :breakfast, :lunch, :dinner)";

        $stmt = $this->conn->prepare($sql);

        $data["billed"] = $data["billed"] === "true" ? true : false;
        $data["breakfast"] = $data["breakfast"] === "true" ? true : false;
        $data["lunch"] = $data["lunch"] === "true" ? true : false;
        $data["dinner"] = $data["dinner"] === "true" ? true : false;

        $stmt->bindValue(":customer_id", $data["customer_id"], PDO::PARAM_INT);
        $stmt->bindValue(":checkin", date("Y-m-d", strtotime($data["checkin"])), PDO::PARAM_STR);
        $stmt->bindValue(":checkout", date("Y-m-d", strtotime($data["checkout"])), PDO::PARAM_STR);
        $stmt->bindValue(":room_id", $data["room_id"], PDO::PARAM_INT);
        $stmt->bindValue(":billed", (bool)$data["billed"] ?? false, PDO::PARAM_BOOL);
        $stmt->bindValue(":breakfast", (bool)$data["breakfast"] ?? false, PDO::PARAM_BOOL);
        $stmt->bindValue(":lunch", (bool)$data["lunch"] ?? false, PDO::PARAM_BOOL);
        $stmt->bindValue(":dinner", (bool)$data["dinner"] ?? false, PDO::PARAM_BOOL);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }


    public function deleteReservation(int $id): int{
        $sql = "DELETE FROM room_reservation WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }


    public function updateReservationInfo(int $id, array $data): int{
        $fields = [];

        if(array_key_exists("customer_id", $data)){
            $fields["customer_id"] = [$data["customer_id"], PDO::PARAM_INT];
        }

        if(array_key_exists("checkin", $data)){
            $fields["checkin"] = [date("Y-m-d", $data["checkin"]), PDO::PARAM_STR];
        }

        if(array_key_exists("checkout", $data)){
            $fields["checkout"] = [date("Y-m-d", $data["checkout"]), PDO::PARAM_STR];
        }

        if(array_key_exists("room_id")){
            $fields["room_id"] = [$data["room_id"], PDO::PARAM_INT];
        }

        if(array_key_exists("billed", $data)){
            $fields["billed"] = [$data["billed"], PDO::PARAM_BOOL];
        }

        if(array_key_exists("breakfast", $data)){
            $fields["breakfast"] = [$data["breakfast"], PDO::PARAM_BOOL];
        }

        if(array_key_exists("lunch", $data)){
            $fields["lunch"] = [$data["lunch"], PDO::PARAM_BOOL];
        }

        if(array_key_exists("dinner", $data)){
            $fields["dinner"] = [$data["dinner"], PDO::PARAM_BOOL];
        }

        if(empty($fields)){
            return 0;
        }else{
            $sets = array_map(function($value){
                return "$value = :$value";
            }, array_keys($fields));

            $sql = "UPDATE room_reservation SET " . implode(", ", $sets) . " WHERE id = :id";

            $stmt = $this->prepare($sql);

            $stmt->bindValue(":id", $id);

            foreach($fields as $name => $values){
                $stmt->bindValue(":$name", $values[0], $values[1]);
            }

            $stmt->execute();

            return $stmt->rowCount();
        }
    }

//Customer features

    public function getAllCustomers(): array{
        $sql = "SELECT * 
                FROM customer
                ORDER BY id ASC";

        $stmt - $this->conn->prepare($sql);

        $stmt->execute();

        $data = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }

        return $data;
    }


    public function getCustomerByID(int $id): array | false{
        $sql = "SELECT * FROM customer WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id);
        
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }


    public function newCustomer(array $data): string{
        $sql = "INSERT INTO customer (fisrtname, latname, phone) VALUES (:firstname, :lastname, :phone)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":firstname", $data["firstname"], PDO::PARAM_STR);
        $stmt->bindValue(":lastname", $data["lastname"], PDO::PARAM_STR);
        $stmt->bindValue(":phone", $data["phone"], PDO::PARAM_STR);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }


    public function deleteCustomer(int $id): int{
        $sql = "DELETE FROM customer WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id);

        $stmt->execute();

        return $stmt->rowCount();
    }


    public function updateCustomerInfo(int $id, array $data): int{
        $fields = [];

        if(array_key_exists("firstname", $data)){
            $fields["firstname"] = [$data["firstname"], PDO::PARAM_STR];
        }

        if(array_key_exists("lastname", $data)){
            $fields["lastname"] = [$data["lastname"], PDO::PARAM_STR];
        }

        if(array_key_exists("phone", $data)){
            $fields["phone"] = [$data["phone"], PDO::PARAM_STR];
        }

        if(empty($fields)){
            return 0;
        }else{
            $sets = array_map(function($value){
                return "$value = :$value";
            }, array_keys($fields));

            $sql = "UPDATE customer SET " . implode(", ", $sets) . " WHERE id = :id";

            $stmt = $this->prepare($sql);

            $stmt->bindValue(":id", $id);

            foreach($fields as $name => $values){
                $stmt->bindValue(":$name", $values[0], $values[1]);
            }

            $stmt->execute();

            return $stmt->rowCount();
        }
    }
}