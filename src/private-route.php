<?php
require_once 'src/constants.php';

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['id'])) {
    header('Location: ' . INDEX_PAGE);
}
