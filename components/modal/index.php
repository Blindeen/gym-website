<?php
function modal(string $title, string $content): void
{
    echo "
    <div id='modal-background'>
        <div id='modal-form-wrapper'>
            <div class='close-button'>X</div>
            <h2>$title</h2>
            $content
        </div>
    </div>
    ";
}
