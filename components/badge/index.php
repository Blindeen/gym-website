<?php

function badge(string $children):void
{
    $content = "<div class='badge-wrapper'>";
    $content .= $children . "<span class='badge'></span>" . "</div>";

    echo $content;
}
