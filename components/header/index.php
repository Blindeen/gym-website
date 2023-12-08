<?php
function navbar(): void
{
    $first_name = $_SESSION["first-name"] ?? null;
    echo
    "<header>
        <div id='items'>
            <span>Hi, <span id='user-name'>$first_name</span></span>
            <a href='/index.php'>Home</a>
            <a href='../../login/logout.php'>Logout</a>
        </div>
    </header>";
}
