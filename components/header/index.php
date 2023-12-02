<?php
$first_name = $_SESSION["first-name"];
echo
"<header>
    <div id='logout'>
      <span>Hi, <span id='user-name'>$first_name</span></span>
      <a href='/index.php'>Home</a>
      <a href='../../login/logout.php'>Logout</a>
    </div>
</header>";
