# Hotel Management System - REST API
REST API for hotel management system with PHP

Postman documentation [here](https://web.postman.co/workspace/My-Workspace~2d5bfd48-91b9-4b5b-84f5-1f92fbe7fcf4/api/d86144a3-c1e0-4feb-a531-3592470dfcff)

<br>
<br>

### User Management
| Method | URL | Parameters (form) | Description |
| ------------------ | ------------------- | --------------------- | -------------------- |
| **POST** | http://{host}/api/login.php | **username**: string * <br> **password**: string * | Returns a JWT if login successfully |

<br>
<br>

### Room Management

| Method | URL | Parameters (JSON) | Description |
| -------------------- | ------------------ | ----------------------- | --------------------- |
| **GET** | http://{host}/api/room | | Show all rooms in JSON format |
| **GET** | http://{host}/api/room/{id} |  | Show selected room data in JSON format |
| **POST** | http://{host}/api/room | **beds:** integer * <br> **type:** string <br> **cost_per_day:** number * <br> **number:** integer | Add room in the database |
| **PATCH** | http://{host}/api/room/{id} | **beds:** integer <br>**type:** string <br>**cost_per_day:** number <br> **number:** integer | Update room info |
| **DELETE** | http://{host}/api/room/{id} |  | Delete room from database |


<br>
<br>


### Customer Management

| Method | URL | Parameters (JSON) | Description |
| -------------------- | ------------------- | ------------------ | ----------------- |
| **GET** | http://{host}/api/customer/ |  | Show all customers in JSON fomrat |
| **GET** | http://{host}/api/customer/{id} |  | Show selected customer data in JSON format  |
| **POST** | http://{host}/api/customer/{id} | **firstname**: string * <br> **lastname**: string * <br> **phone**: string * | Add customer in the database |
| **PATCH** | http://{host}/api/customer/{id} | **firstname**: string <br> **lastname**: string <br> **phone**: string | Update customer info |
| **DELETE** | http://{host}/api/customer/{id} |  | Delete customer from database |


<br>
<br>


### Reservation Management</h4>

| Method | URL | Parameters (JSON) | Description |
| -------------------- | ------------------- | ------------------ | ----------------- |
| **GET** | http://{host}/api/reservation/ |  | Show all customers in JSON fomrat |
| **GET** | http://{host}/api/reservation/{id} |  | Show selected customer data in JSON format  |
| **POST** | http://{host}/api/reservation/{id} | **checkin**: string * <br> **checkout**: string * <br> **customer_id**: integer * <br> **room_id**: integer * <br> **billed**: boolean * <br> **breakfast**: boolean * <br> **lunch**: boolean * <br> **dinner**: boolean * | Add customer in the database |
| **PATCH** | http://{host}/api/reservation/{id} | **checkin**: string <br> **checkout**: string <br> **customer_id**: integer <br> **room_id**: integer <br> **billed**: boolean <br> **breakfast**: boolean <br> **lunch**: boolean <br> **dinner**: boolean | Update customer info |
| **DELETE** | http://{host}/api/reservation/{id} |  | Delete customer from database |


<br>
<br>




