<?php
function table(
    mysqli $conn,
    array  $columns,
    string $query,
    int    $page,
    int    $per_page,
    string $action = null,
    bool   $pagination = false,
    string $pagination_query = null): void
{
    $offset = ($page - 1) * $per_page;
    $data_query = $query . " LIMIT $per_page OFFSET $offset";

    echo "<div id='table-wrapper'><table><thead><tr>";

    foreach ($columns as $column) {
        echo "<th>$column</th>";
    }

    echo "</tr></thead><tbody>";

    $data_per_page = $conn->query($data_query);
    if (!$data_per_page->num_rows) {
        $columns_quantity = count($columns);
        echo "<tr><td id='empty-row' colspan='$columns_quantity'>No data available</td></tr>";
    }

    while ($row = $data_per_page->fetch_row()):
        echo "<tr>";
        $len = count($row);
        for ($i = 1; $i < $len; $i++) {
            $value = htmlspecialchars($row[$i]);
            echo "<td>$value</td>";
        }
        if ($action) {
            echo "
                <td class='action'>
                    <a class='button edit-button' data-id='$row[0]'>Edit</a>
                    <a class='button remove-button' href='$action$row[0]'>Delete</a>
                </td></tr>";
        }
    endwhile;

    echo "</tbody></table></div>";

    if ($pagination && $pagination_query) {
        echo "<div id='pagination-wrapper'><div id='pagination'>";

        $all_data = $conn->query($pagination_query);
        $rows_quantity = $all_data->fetch_row()[0];
        $pages_quantity = ceil($rows_quantity / $per_page);
        if ($page > 1 && $data_per_page->num_rows) {
            $previous_page = $page - 1;
            echo "<a id='left-arrow' href='{$_SERVER['PHP_SELF']}?page=$previous_page'></a>";
        } else {
            echo "<div id='left-arrow' style='border-color: grey'></div>";
        }

        if ($pages_quantity != 0) {
            echo "<div id='page-number'>$page</div>";
        }

        if ($page < $pages_quantity) {
            $next_page = $page + 1;
            echo "<a id='right-arrow' href='{$_SERVER['PHP_SELF']}?page=$next_page'></a>";
        } else {
            echo "<div id='right-arrow' style='border-color: grey'></div>";
        }

        echo "</div></div>";
    }
}
