<?php
require_once '../constants.php';

session_start();
session_unset();
header('Location: ' . CONSTANTS['INDEX_PAGE']);
