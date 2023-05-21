# Hotel Management System - REST API
REST API for hotel management system with PHP

Postman documentation [here](https://web.postman.co/workspace/My-Workspace~2d5bfd48-91b9-4b5b-84f5-1f92fbe7fcf4/api/d86144a3-c1e0-4feb-a531-3592470dfcff)


<h4>Room Management</h4>

<table>
    <tr>
        <th>Method</th>
        <th>URL</th>
        <th>Parameters</th>
        <th>Description</th>
    </tr>
    <tr>
        <td class="method">GET</td>
        <td>http://{host}/api/room</td>
        <td></td>
        <td>Show all rooms in JSON format</td>
    </tr>
    <tr>
        <td class="method">GET</td>
        <td>http://{host}/api/room/{id}</td>
        <td></td>
        <td>Show selected room data in JSON data</td>
    </tr>
    <tr>
        <td class="method">POST</td>
        <td>http://{host}/api/room</td>
        <td>
            <strong>beds</strong>: integer *<br>
            <strong>type</strong>: string *<br>
            <strong>cost_per_day</strong>: number *<br>
            <strong>number</strong>: integer 
        </td>
        <td>Add room in the database</td>
    </tr>
    <tr>
        <td class="method">PATCH</td>
        <td>http://{host}/api/room/{id}</td>
        <td>
            <strong>beds</strong>: integer<br>
            <strong>type</strong>: string<br>
            <strong>cost_per_day</strong>: number<br>
            <strong>number</strong>: integer<br>
        </td>
        <td>Update room info</td>
    </tr>
    <tr>
        <td class="method">DELETE</td>
        <td>http://{host}/api/room/{id}</td>
        <td></td>
        <td>Delete room from database</td>
    </tr>
    
</table>