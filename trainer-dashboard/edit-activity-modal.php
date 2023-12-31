<?php
require_once "../constants.php";
require_once "../components/modal/index.php";

if (isset($_COOKIE["errors"])) {
    $serializer = [
        "activity-name" => FILTER_FLAG_NONE,
        "time" => FILTER_FLAG_NONE,
        "weekday" => FILTER_FLAG_NONE,
        "room" => FILTER_FLAG_NONE,
    ];
    $serialized_errors = filter_var_array(json_decode($_COOKIE["errors"], true), $serializer);
    ["activity-name" => $activity_name, "time" => $time, "weekday" => $weekday, "room" => $room] = $serialized_errors;
}

$modal_content = "
            <form id='modal-form' action='edit-activity.php' method='POST'>
                <div class='field'>
                    <label for='activity-name'>Activity name</label>
                    <input id='activity-name' name='activity-name' type='text' required/>
                </div>
                <div class='form-row-wrapper'>
                    <div class='form-row'>
                        <div class='field'>
                            <label for='start-hour'>Start hour</label>
                            <input id='start-hour' name='start-hour' type='time' min='06:00' max='21:00' required/>
                        </div>
                        <div class='field'>
                            <label for='end-hour'>End hour</label>
                            <input id='end-hour' name='end-hour' type='time' min='06:00' max='21:00' required/>
                        </div>
                    </div>
                </div>
                <div class='form-row'>
                    <div class='field'>
                        <label for='weekday'>Weekday</label>
                        <select id='weekday' name='weekday'>
                            <option selected hidden></option>
                            <option value='Monday'>Monday</option>
                            <option value='Tuesday'>Tuesday</option>
                            <option value='Wednesday'>Wednesday</option>
                            <option value='Thursday'>Thursday</option>
                            <option value='Friday'>Friday</option>
                        </select>
                    </div>
                    <div class='field'>
                        <label for='room'>Room</label>
                        <select id='room' name='room'>
                            <option selected hidden></option>
";

try {
    $result = $conn->query("SELECT * FROM Rooms");
} catch (mysqli_sql_exception $exception) {
    exit(CONSTANTS["SERVER_ERROR_MESSAGE"]);
}

while ($row = $result->fetch_array(MYSQLI_ASSOC)):
    $id = $row["ID"];
    $room_number = $row["RoomNumber"];
    $modal_content .= "<option value='$id'>$room_number</option>";
endwhile;

$modal_content .= "</select></div></div><button type='submit'>Edit</button></form><p id='form-message'></p>";

modal("Edit activity", $modal_content);
