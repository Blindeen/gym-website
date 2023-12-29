<?php
require_once 'constants.php';

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>FitSphere</title>
</head>
<body>
<div class="mobile-menu">
    <a href="/">Home</a>
    <a href="/classes/index.php">Classes</a>
    <a href="#">Trainers</a>
    <a href="#">Contact</a>
    <?php
    if (!isset($_SESSION['id'])) {
        echo '<a href="/register/index.php">Buy pass</a>';
        echo '<a href="/login/index.php">Login</a>';
    }

    if (isset($_SESSION['role'])) {
        switch ($_SESSION['role']) {
            case CONSTANTS['TRAINER']:
                echo '<a class="link" href="/trainer-dashboard/index.php">Dashboard</a>';
                break;
            case CONSTANTS['CLIENT']:
                echo '<a class="link" href="/client-dashboard.php">My activities</a>';
        }
    }
    ?>
    <div class="hamburger-close-button">X</div>
</div>
<div class="first-section">
    <header>
        <img src="assets/img/logo.png" alt="logo"/>
        <a class="link" href="/">Home</a>
        <a class="link" href="/classes/index.php">Classes</a>
        <a class="link" href="#">Trainers</a>
        <a class="link" href="#">Contact</a>
        <?php
        if (!isset($_SESSION['id'])) {
            echo '<a id="link-register-button" class="link-button" href="/register/index.php">Buy pass</a>';
            echo '<a class="link-button" href="/login/index.php">Login</a>';
        }

        if (isset($_SESSION['role'])) {
            switch ($_SESSION['role']) {
                case CONSTANTS['TRAINER']:
                    echo '<a class="link" href="/trainer-dashboard/index.php">Dashboard</a>';
                    break;
                case CONSTANTS['CLIENT']:
                    echo '<a class="link" href="/client-dashboard.php">My activities</a>';
            }
        }
        ?>
    </header>
    <header class="mobile-header">
        <h2>FitSphere</h2>
        <div class="hamburger-open-button">
            <div class="dash"></div>
            <div class="dash"></div>
            <div class="dash"></div>
        </div>
    </header>
    <div class="container">
        <div class="left-pane">
            <h2>FitSphere</h2>
            <h4>Get Fit, Stay Fit</h4>
            <p class="reveal">
                We're more than just a place to break a sweat. We're more than
                just a place to break a sweat.
                We're your fitness sanctuary and your second home, whether
                you're an athlete or just a beginner.
                We've got something for everyone.
            </p>
        </div>
        <div class="right-pane">
            <img class="reveal" src="assets/img/white-woman-workout.png"
                 alt="fitness-class-banner">
        </div>
    </div>
</div>
<h2>Our Services</h2>
<div class="services">
    <div class="service">
        <h3>Personal Training</h3>
        <img src="assets/img/personal-training.png" alt="personal-training"/>
        <p>One-on-one training with our experienced trainers.</p>
    </div>
    <div class="service">
        <h3>Group Classes</h3>
        <img src="assets/img/group-classes.png" alt="gym-classes"/>
        <p>Join our group fitness classes for a fun workout.</p>
    </div>
    <div class="service">
        <h3>Nutrition Guidance</h3>
        <img src="assets/img/protein-shake.png" alt="protein-shake"/>
        <p>Get expert advice on your diet and nutrition.</p>
    </div>
</div>
<div class="scroll-button">
    <div class="rectangle"></div>
</div>
<footer>
    <div class="footer-columns">
        <div class="footer-column">
            <h3>
                Information
            </h3>
            <a href="#">Trainers</a>
            <a href="#">Classes</a>
        </div>
        <div class="footer-column">
            <h3>
                Services
            </h3>
            <a href="/register/index.php">Buy pass</a>
            <a href="/login/index.php">Login</a>
        </div>
        <div class="footer-column">
            <h3>
                Help
            </h3>
            <a href="#">Contact</a>
        </div>
    </div>
    <p>© Copyright 2023 FitSphere S.A.</p>
</footer>
<script src="scripts.js"></script>
</body>
</html>
