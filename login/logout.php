<?php
require_once "../constants.php";

session_start();
session_unset();
$_SESSION["role"] = CONSTANTS["GUEST"];

header("Location: " . CONSTANTS["INDEX_PAGE"]);
