<?php
require_once "components/badge/index.php";

function menu():void
{
    $output = "<a class='link' href='/'>Home</a>";
    $output .= badge("<a class='link' href='/activities/index.php'>Activities</a>");
    $output .= "<a class='link' href='#'>Trainers</a><a class='link' href='#'>Contact</a>";

    if (!isset($_SESSION["id"])) {
        $output .= "<a id='link-register-button' class='link-button' href='/register/index.php'>Sign up</a>
        <a class='link-button' href='/login/index.php'>Sign in</a>";
    }

    if (isset($_SESSION["role"])) {
        switch ($_SESSION["role"]) {
            case CONSTANTS["TRAINER"]:
                $output .= "<a class='link' href='/trainer-dashboard/index.php'>Dashboard</a>";
                break;
            case CONSTANTS["CLIENT"]:
                $output .= "<a class='link' href='/client-dashboard.php'>My activities</a>";
        }
        $output .= "<a class='link' href='/login/logout.php'>Sign out</a>";
    }

    echo $output;
}
