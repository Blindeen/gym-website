<?php
function table(mysqli $conn, array $columns, string $query, int $page, int $per_page, string $pagination_query): void
{
    $lower_bound = ($page - 1) * $per_page;
    $upper_bound = $page * $per_page;
    $query = $query . " LIMIT $lower_bound, $upper_bound";

    echo '<div id="table-wrapper">
            <table>
                <thead>
                    <tr>
    ';

    foreach ($columns as $column) {
        echo "<th>$column</th>";
    }

    echo '          </tr>
                </thead>
                <tbody>';

    $result = $conn->query($query);
    if (!$result->num_rows) {
        $columns_quantity = count($columns);
        echo '<tr>';
            echo "<td id='empty-row' colspan='$columns_quantity'>No data available</td>";
        echo '</tr>';
    }

    while ($row = $result->fetch_row()):
        echo '<tr>';
        $len = count($row);
        for ($i = 1; $i < $len; $i++) {
            echo "<td>$row[$i]</td>";
        }
        echo "<td>
                <a class='remove-button' href='delete-activity.php?id=$row[0]'>Delete</a>
              </td>";
        echo '</tr>';
    endwhile;

    echo '      </tbody>
            </table>
        </div>     ';

    echo '<div id="pagination-wrapper">
               <div id="pagination">';

    $result = $conn->query($pagination_query);
    $row = $result->fetch_row();
    $pages_quantity = ceil($row[0] / $per_page);
    if ($page > 1) {
        $previous_page = $page - 1;
        echo "<a href='{$_SERVER['PHP_SELF']}?page=$previous_page'> < </a>";
    }
    if ($pages_quantity != 0) {
        echo $page;
    }
    if ($page < $pages_quantity) {
        $next_page = $page + 1;
        echo "<a href='{$_SERVER['PHP_SELF']}?page=$next_page'> > </a>";
    }

    echo '    </div>
          </div>';
}
