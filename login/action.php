<?php
require_once "../db-connection.php";
require_once "../utils.php";

session_start();
$conn = db_connection();

["SERVER_ERROR_MESSAGE" => $error_message, "INDEX_PAGE" => $index] = CONSTANTS;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serializer = array(
        "email" => array(
            "filter" => FILTER_VALIDATE_EMAIL,
        ),
        "password" => FILTER_FLAG_NONE,
    );

    $serialized_data = filter_var_array($_POST, $serializer);
    if (in_array(null, $serialized_data)) {
        $errors["email"] = "<p class='error'>Email is invalid</p>";
    } else {
        $email = trim($serialized_data["email"]);
        $password = trim($serialized_data["password"]);

        try {
            $query = "SELECT ID, FirstName, Password, RoleID FROM Members WHERE Email = ?";
            $user_result = perform_query($conn, $query, [$email], "s");
            $user_info = $user_result->fetch_assoc();

            if ($user_result->num_rows) {
                $query = "SELECT Name FROM Roles WHERE ID = ?";
                $role_result = perform_query($conn, $query, [$user_info["RoleID"]], "s");
                $role = $role_result->fetch_assoc();
            }
        } catch (mysqli_sql_exception $exception) {
            exit($error_message);
        }

        if ($user_result->num_rows) {
            if (password_verify($password, $user_info["Password"])) {
                $_SESSION["id"] = $user_info["ID"];
                $_SESSION["first-name"] = $user_info["FirstName"];
                $_SESSION["role"] = $role["Name"];

                header("Location: " . $index);
            } else {
                $errors["password"] = "<p class='error'>Incorrect password</p>";
            }
        } else {
            $errors["email"] = "<p class='error'>User not found</p>";
        }
    }
}
