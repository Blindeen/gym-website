<?php
require_once "../db-connection.php";
require_once "../utils.php";

$conn = db_connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = [];
    $serializer = [
        "first-name" => [
            "filter" => FILTER_VALIDATE_REGEXP,
            "options" => ["regexp" => "/^([A-Z\p{L}][a-zA-Z]\p{L}*)([-\s][A-Z][a-zA-Z]\p{L}*)*$/u"],
        ],
        "last-name" => [
            "filter" => FILTER_VALIDATE_REGEXP,
            "options" => ["regexp" => "/^([A-Z\p{L}][a-zA-Z]\p{L}*)([-\s][A-Z][a-zA-Z]\p{L}*)*$/u"],
        ],
        "email" => [
            "filter" => FILTER_VALIDATE_EMAIL,
        ],
        "password" => [
            "filter" => FILTER_VALIDATE_REGEXP,
            "options" => ["regexp" => "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/"],
        ],
        "birthdate" => FILTER_FLAG_NONE,
        "pass" => FILTER_FLAG_NONE,
        "payment-method" => FILTER_FLAG_NONE,
        "address-line" => [
            "filter" => FILTER_FLAG_NONE,
        ],
        "city" => [
            "filter" => FILTER_VALIDATE_REGEXP,
            "options" => ["regexp" => "/^([A-Z\p{L}]*)([\s][a-z]\p{L}*)*$/u"],
        ],
        "zip-code" => [
            "filter" => FILTER_VALIDATE_REGEXP,
            "options" => ["regexp" => "/^\d{2}[-\s]\d{3}$/"],
        ],
        "phone-number" => [
            "filter" => FILTER_VALIDATE_REGEXP,
            "options" => ["regexp" => "/^[0-9]{9}$/"],
        ],
    ];

    $serialized_data = filter_var_array($_POST, $serializer);
    if (in_array(null, $serialized_data)) {
        foreach ($serialized_data as $key => $value) {
            if ($value == null) {
                if ($key == "password") {
                    $response["message"] = "Password has to contain at least 8 characters, one uppercase letter, one lowercase letter, one digit and special character (#?!@$%^&*-)";
                } else {
                    $response["message"] = "Incorrect " . str_replace("-", " ", $key);
                }
                $response["fields"] = [$key];
                break;
            }
        }

        response(400, $response);
    }

    foreach ($serialized_data as $key => $value) {
        $serialized_data[$key] = htmlspecialchars(trim($value));
    }

    $serialized_data["password"] = password_hash($serialized_data["password"], PASSWORD_DEFAULT);

    $query = "INSERT INTO Members (FirstName, LastName, Email, Password, Birthdate, PassID, PaymentMethodID) 
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $params = array_values(array_slice([...$serialized_data], 0, 7));
    try {
        perform_query($conn, $query, $params, "sssssss");
    } catch (mysqli_sql_exception $exception) {
        $status_code = null;
        if ($exception->getCode() == CONSTANTS["DUPLICATE_ERROR"]) {
            $status_code = 400;
            $response["message"] = "User already exists";
            $response["fields"] = ["email"];
        } else {
            $status_code = 500;
            $response["message"] = "Server internal error";
        }

        response($status_code, $response);
    }

    $query = "SELECT ID, FirstName FROM Members WHERE Email = ?";
    try {
        $result = perform_query($conn, $query, [$serialized_data["email"]], "s");
        $member_data = $result->fetch_assoc();
    } catch (mysqli_sql_exception $exception) {
        $response["message"] = "Server internal error";
        response(500, $response);
    }

    $query = "INSERT INTO AddressesContacts (AddressLine, City, PostalCode, PhoneNumber, MemberID) VALUES (?, ?, ?, ?, ?)";
    try {
        $params = array_values(array_slice([...$serialized_data, $member_data["ID"]], 7));
        perform_query($conn, $query, $params, "sssss");
    } catch (mysqli_sql_exception $exception) {
        $response["message"] = "Server internal error";
        response(500, $response);
    }

    session_start();
    $_SESSION["id"] = $member_data["ID"];
    $_SESSION["first-name"] = $member_data["FirstName"];
    $_SESSION["role"] = CONSTANTS["CLIENT"];

    $response["message"] = "User has been signed up";
    response(201, $response);
}
