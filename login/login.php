<?php
require_once "../db-connection.php";
require_once "../utils.php";

session_start();
$conn = db_connection();

["SERVER_ERROR_MESSAGE" => $error_message, "INDEX_PAGE" => $index] = CONSTANTS;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serializer = [
        "email" => [
            "filter" => FILTER_VALIDATE_EMAIL,
        ],
        "password" => [
            "filter" => FILTER_FLAG_NONE,
        ],
    ];

    $serialized_data = filter_var_array($_POST, $serializer);
    if (in_array(null, $serialized_data)) {
        if (!$serialized_data["email"]) {
            echo json_encode([
                "statusCode" => 400,
                "message" => "Invalid email",
            ]);
        }

        if (!$serialized_data["password"]) {
            echo json_encode([
                "statusCode" => 400,
                "message" => "Password cannot be empty",
            ]);
        }
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

                echo json_encode([
                    "statusCode" => 200,
                    "message" => "User has been signed in",
                ]);
            } else {
                echo json_encode([
                    "statusCode" => 400,
                    "message" => "Incorrect password",
                ]);
            }
        } else {
            echo json_encode([
                "statusCode" => 400,
                "message" => "User not found",
            ]);
        }
    }
}
