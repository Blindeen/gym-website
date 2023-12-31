<?php

function badge(string $children):string
{
    $content = "<div class='badge-wrapper'>";
    $content .= $children . "<span class='badge'></span>" . "</div>";

    return $content;
}
