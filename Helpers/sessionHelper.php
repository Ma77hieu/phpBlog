<?php

/**
 * Manage the csrf token creation
 *
 * @throws Exception
 * @return void
 */
function manageCsrf()
{
    $_SESSION['csrfToken'] = bin2hex(random_bytes(35));
    echo("<div style=\"display: none;\">" . $_SESSION['csrftoken'] . "</div>");
}