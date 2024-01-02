<?php
require_once "../db-connection.php";
require_once "../utils.php";

$conn = db_connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = [];
    $serializer = [
        "first-name" => [
            "filter" => FILTER_VALIDATE_REGEXP,
            "options" => ["regexp" => "/^([A-Z][a-zA-Z]*)([-\s][A-Z][a-zA-Z]*)*$/m"],
        ],
        "last-name" => [
            "filter" => FILTER_VALIDATE_REGEXP,
            "options" => ["regexp" => "/^([A-Z][a-zA-Z]*)([-\s][A-Z][a-zA-Z]*)*$/m"],
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
        "phone-number" => [
            "filter" => FILTER_VALIDATE_REGEXP,
            "options" => ["regexp" => "/^[0-9]{9}$/"],
        ],
        "address-line" => [
            "filter" => FILTER_FLAG_NONE,
        ],
        "city" => [
            "filter" => FILTER_VALIDATE_REGEXP,
            "options" => ["regexp" => "/^[A-Z][a-z]+$/"],
        ],
        "zip-code" => [
            "filter" => FILTER_VALIDATE_REGEXP,
            "options" => ["regexp" => "/^\d{2}[-\s]\d{3}$/"],
        ],
    ];

    $serialized_data = filter_var_array($_POST, $serializer);
    if (in_array(null, $serialized_data)) {
        $response["statusCode"] = 400;
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

        exit(json_encode($response));
    }

    $serialized_data["password"] = password_hash($serialized_data["password"], PASSWORD_DEFAULT);

    $query = "INSERT INTO Members (FirstName, LastName, Email, Password, Birthdate, PassID, PaymentMethodID) 
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $params = array_values(array_slice([...$serialized_data], 0, 7));
    try {
        perform_query($conn, $query, $params, "sssssss");
    } catch (mysqli_sql_exception $exception) {
        if ($exception->getCode() == CONSTANTS["DUPLICATE_ERROR"]) {
            $response["statusCode"] = 400;
            $response["message"] = "User already exists";
            $response["fields"] = ["email"];
        } else {
            $response["statusCode"] = 500;
            $response["message"] = "Server internal error";
        }

        exit(json_encode($response));
    }

    $query = "SELECT ID, FirstName FROM Members WHERE Email = ?";
    try {
        $result = perform_query($conn, $query, [$serialized_data["email"]], "s");
        $member_data = $result->fetch_assoc();
    } catch (mysqli_sql_exception $exception) {
        $response["statusCode"] = 500;
        $response["message"] = "Server internal error";
        exit(json_encode($response));
    }

    $query = "INSERT INTO AddressesContacts (AddressLine, City, PostalCode, PhoneNumber, MemberID) VALUES (?, ?, ?, ?, ?)";
    try {
        $params = array_values(array_slice([...$serialized_data, $member_data["ID"]], 7));
        perform_query($conn, $query, $params, "sssss");
    } catch (mysqli_sql_exception $exception) {
        $response["statusCode"] = 500;
        $response["message"] = "Server internal error";
        exit(json_encode($response));
    }

    session_start();
    $_SESSION["id"] = $member_data["ID"];
    $_SESSION["first-name"] = $member_data["FirstName"];
    $_SESSION["role"] = CONSTANTS["CLIENT"];

    $response["statusCode"] = 201;
    $response["message"] = "User has been signed up";
    echo json_encode($response);
}
