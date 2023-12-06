<?php
echo "
    <div id='modal-background'>
        <div id='modal-form-wrapper'>
            <div class='close-button'>X</div>
            <h2>Edit activity</h2>
            <form id='modal-form' method='POST'>
                <div class='field'>
                    <label for='activity-name'>Activity name</label>
                    <input id='activity-name' name='activity-name' type='text' required/>
                </div>
                <div id='form-row-wrapper'>
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
$result = $conn->query("SELECT * FROM Rooms");
while ($row = $result->fetch_array(MYSQLI_ASSOC)):
    $id = $row["ID"];
    $room_number = $row["RoomNumber"];
    echo "<option value='$id'>$room_number</option>";
endwhile;
echo "</select></div></div><button type='submit'>Edit</button></form></div></div>";
