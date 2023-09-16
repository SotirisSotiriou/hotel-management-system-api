# Hotel Management System - REST API
REST API for hotel management system with PHP

Postman documentation [here](https://web.postman.co/workspace/My-Workspace~2d5bfd48-91b9-4b5b-84f5-1f92fbe7fcf4/api/d86144a3-c1e0-4feb-a531-3592470dfcff)

<h4>Room Management</h4>

| Method                                               | URL                                              | Parameters                                                                                                                                                                                             | Description                            |
|:----------------------------------------------------:|:------------------------------------------------:|:------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|:--------------------------------------:|
| **GET**                                              | http://{host}/api/room                           |                                                                                                                                                                                                        | Show all rooms in JSON format          |
| **GET**                                              | http://{host}/api/room/{id}                      |                                                                                                                                                                                                        | Show selected room data in JSON format |
| **POST**                                             | http://{host}/api/room                           |**beds:** integer <span style="color:red;"> * </span><br>**type:** string <span style="color:red;"> * </span><br>**cost_per_day:** number <span style="color:red;"> * </span><br>**number:** integer    | Add room in the database               |
| **PATCH**                                            | http://{host}/api/room/{id}                      |**beds:** integer <br>**type:** string <br>**cost_per_day:** number <br>**number:** integer                                                                                                             | Update room info                       |
| **DELETE**                                           | http://{host}/api/room/{id}                      |                                                                                                                                                                                                        | Delete room from database              |








<br>
<br>


<h4>Customer Management</h4>

<table>
    <tr>
        <th>Method</th>
        <th>URL</th>
        <th>Parameters</th>
        <th>Description</th>
    </tr>
    <tr>
        <td>GET</td>
        <td>http://{host}/api/customer</td>
        <td></td>
        <td>Show all customers in JSON format</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>http://{host}/api/customer/{id}</td>
        <td></td>
        <td>Show selected customer data in JSON format</td>
    </tr>
    <tr>
        <td>POST</td>
        <td>http://{host}/api/customer</td>
        <td>
            <strong>firstname</strong>: string *<br>
            <strong>lastname</strong>: string *<br>
            <strong>phone</strong>: string
        </td>
        <td>Add customer in the database</td>
    </tr>
    <tr>
        <td>PATCH</td>
        <td>http://{host}/api/customer/{id}</td>
        <td>
            <strong>firstname</strong>: string<br>
            <strong>lastname</strong>: string<br>
            <strong>phone</strong>: string
        </td>
        <td>Update customer info</td>
    </tr>
    <tr>
        <td>DELETE</td>
        <td>http://{host}/api/customer/{id}</td>
        <td></td>
        <td>Delete customer from database</td>
    </tr>
</table>
<br>
<br>

<h4>Reservation Management</h4>

<table>
    <tr>
        <th>Method</th>
        <th>URL</th>
        <th>Parameters</th>
        <th>Description</th>
    </tr>
    <tr>
        <td>GET</td>
        <td>http://{host}/api/reservation</td>
        <td></td>
        <td>Show all reservations in JSON format</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>http://{host}/api/reservation/{id}</td>
        <td></td>
        <td>Show selected reservation data in JSON format</td>
    </tr>
    <tr>
        <td>POST</td>
        <td>http://{host}/api/reservation</td>
        <td>
            <strong>customer_id</strong>: integer *<br>
            <strong>checkin</strong>: string *<br>
            <strong>checkout</strong>: string *<br>
            <strong>room_id</strong>: integer *<br>
            <strong>billed</strong>: boolean *<br>
            <strong>breakfast</strong>: boolean *<br>
            <strong>lunch</strong>: boolean *<br>
            <strong>dinner</strong>: boolean *
        </td>
        <td>Add reservation in the database</td>
    </tr>
    <tr>
        <td>PATCH</td>
        <td>http://{host}/api/reservation/{id}</td>
        <td>
            <strong>customer_id</strong>: integer<br>
            <strong>checkin</strong>: string<br>
            <strong>checkout</strong>: string<br>
            <strong>room_id</strong>: integer<br>
            <strong>billed</strong>: boolean<br>
            <strong>breakfast</strong>: boolean<br>
            <strong>lunch</strong>: boolean<br>
            <strong>dinner</strong>: boolean
        </td>
        <td>Update reservation info</td>
    </tr>
    <tr>
        <td>DELETE</td>
        <td>http://{host}/api/reservation/{id}</td>
        <td></td>
        <td>Delete reservation from database</td>
    </tr>
</table>