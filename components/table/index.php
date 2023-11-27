<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Weekday</th>
        <th>Time</th>
        <th>Room</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $query = "SELECT Activities.ID, Name, DayOfWeek, StartTime, EndTime, RoomNumber FROM Activities
                INNER JOIN gym.Rooms R on Activities.RoomID = R.ID
                ORDER BY
                    CASE DayOfWeek
                        WHEN 'Monday' THEN 1
                        WHEN 'Tuesday' THEN 2
                        WHEN 'Wednesday' THEN 3
                        WHEN 'Thursday' THEN 4
                        WHEN 'Friday' THEN 5
                    END, StartTime";
    $result = $conn->query($query);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)):
        ?>
        <tr>
            <td><?php echo $row['Name']; ?></td>
            <td><?php echo $row['DayOfWeek']; ?></td>
            <td>
                <?php echo date('H:i', strtotime($row['StartTime'])) . ' - ' . date('H:i', strtotime($row['EndTime'])); ?>
            </td>
            <td><?php echo $row['RoomNumber']; ?></td>
            <td>
                <a class="remove-button" href="<?php echo 'delete-activity.php?id=' . $row['ID']; ?>">Delete</a>
            </td>
        </tr>
    <?php
    endwhile;
    ?>
    </tbody>
</table>
