# Hotel Management System - REST API
REST API for hotel management system with PHP

Postman documentation [here](https://web.postman.co/workspace/My-Workspace~2d5bfd48-91b9-4b5b-84f5-1f92fbe7fcf4/api/d86144a3-c1e0-4feb-a531-3592470dfcff)

<style>
    th {
        background-color: lightgrey
    }

    td.method {
        text-align: center;
        font-weight: bold;
    }

    .variable {
        color: red;
    }

    tr td{
        font-family: console;
    }

    .required {
        color: red;
    }
</style>

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
        <td>http://<span class="variable">{host}</span>/api/room</td>
        <td></td>
        <td>Show all rooms in JSON format</td>
    </tr>
    <tr>
        <td class="method">GET</td>
        <td>http://<span class="variable">{host}</span>/api/room/<span class="variable">{id}</span></td>
        <td></td>
        <td>Show selected room data in JSON data</td>
    </tr>
    <tr>
        <td class="method">POST</td>
        <td>http://<span class="variable">{host}</span>/api/room</td>
        <td>
            <strong>beds</strong>: integer <span class="required">*</span><br>
            <strong>type</strong>: string <span class="required">*</span><br>
            <strong>cost_per_day</strong>: number <span class="required">*</span><br>
            <strong>number</strong>: integer <span class="required">*</span>
        </td>
        <td>Add room in the database</td>
    </tr>
    <tr>
        <td class="method">PATCH</td>
        <td>http://<span class="variable">{host}</span>/api/room/<span class="variable">{id}</span></td>
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
        <td>http://<span class="variable">{host}</span>/api/room/<span class="variable">{id}</span></td>
        <td></td>
        <td>Delete room from database</td>
    </tr>
    
</table>