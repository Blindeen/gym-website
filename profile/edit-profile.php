<?php
require_once "../utils.php";
require_once "../db-connection.php";

session_start();
$conn = db_connection();

$validated_id = filter_var($_SESSION["id"] ?? null, FILTER_VALIDATE_INT);
if (!$validated_id) {
    response(401, [
        "message" => "Unauthorized access",
    ]);
}

$response = [];
$serializer = [
    "first-name" => [
        "filter" => FILTER_VALIDATE_REGEXP,
        "options" => ["regexp" => "/^([A-Z][a-zA-Z]\p{L}*)([-\s][A-Z][a-zA-Z]\p{L}*)*$/mu"],
    ],
    "last-name" => [
        "filter" => FILTER_VALIDATE_REGEXP,
        "options" => ["regexp" => "/^([A-Z][a-zA-Z]\p{L}*)([-\s][A-Z][a-zA-Z]\p{L}*)*$/mu"],
    ],
    "email" => [
        "filter" => FILTER_VALIDATE_EMAIL,
    ],
    "pass" => FILTER_VALIDATE_INT,
    "payment-method" => FILTER_VALIDATE_INT,
    "phone-number" => [
        "filter" => FILTER_VALIDATE_REGEXP,
        "options" => ["regexp" => "/^[0-9]{9}$/"],
    ],
    "address-line" => [
        "filter" => FILTER_FLAG_NONE,
    ],
    "city" => [
        "filter" => FILTER_VALIDATE_REGEXP,
        "options" => ["regexp" => "/^([A-Z]\p{L}*)([\s][a-z]\p{L}*)*$/u"],
    ],
    "zip-code" => [
        "filter" => FILTER_VALIDATE_REGEXP,
        "options" => ["regexp" => "/^\d{2}[-\s]\d{3}$/"],
    ],
];

$serialized_data = filter_var_array($_POST, $serializer);
if (in_array(null, $serialized_data)) {
    foreach ($serialized_data as $key => $value) {
        if ($value == null) {
            $response["message"] = "Incorrect " . str_replace("-", " ", $key);
            $response["fields"] = [$key];
            break;
        }
    }

    response(400, $response);
}

$validated_password = null;
if (!empty($_POST["password"])) {
    $options =  [
        "filter" => FILTER_VALIDATE_REGEXP,
        "options" => ["regexp" => "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/"],
    ];
    $validated_password = filter_var($_POST["password"], options: $options);
    if (!$validated_password) {
        response(400, [
            "message" => "Password has to contain at least 8 characters, one uppercase letter, one lowercase letter, one digit and special character (#?!@$%^&*-)",
            "fields" => ["password"],
        ]);
    }
}

$params = array_values(array_slice($serialized_data, 0, 5));
$query = "UPDATE Members SET FirstName = ?, LastName = ?, Email = ?, PassID = ?, PaymentMethodID = ?";
if (!is_null($validated_password)) {
    $query .= ", Password = ?";
    $params[] = $validated_password;
}
$query .= " WHERE ID = $validated_id";

try {
    perform_query($conn, $query, $params, str_repeat("s", count($params)));
} catch (mysqli_sql_exception $e) {
    response(500, [
        "message" => "Server internal error",
    ]);
}

$query = "UPDATE AddressesContacts SET PhoneNumber = ?, AddressLine = ?, City = ?, PostalCode = ? WHERE MemberID = $validated_id";
$params = array_values(array_slice($serialized_data, 5));

try {
    perform_query($conn, $query, $params, str_repeat("s", count($params)));
} catch (mysqli_sql_exception $e) {
    response(500, [
        "message" => "Server internal error",
    ]);
}

response(204);
