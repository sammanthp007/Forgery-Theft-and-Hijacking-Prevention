<?php

// Returns a random string suitable for a CSRF token
function csrf_token() {
    // Requires PHP 7 or later
    return bin2hex(random_bytes(64));
}

// Returns HTML for a hidden form input with a CSRF token as the value
function csrf_token_tag() {
    // DONE needs to set a token and put in an HTML tag
    $token = create_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $token . '" />';
}

function create_csrf_token() {
    $token = csrf_token();
    $_SESSION['csrf_token'] = $token;
    $_SESSION['csrf_token_start'] = time();
    return $token;
}

// Returns true if form token matches session token, false if not.
function csrf_token_is_valid() {
    if(!isset($_POST['csrf_token'])) { return false; }
    if(!isset($_SESSION['csrf_token'])) { return false; }
    return ($_POST['csrf_token'] === $_SESSION['csrf_token']);
}

// Determines if the form token should be considered "recent"
// by comparing it to the time a token was last generated.
function csrf_token_is_recent() {
    // DONE add code to determine if csrf token is recent
    if (!isset($_SESSION['csrf_token_start'])) { return false; }
    $max_csrf_token = 10 * 60;
    return ($_SESSION['csrf_token_start'] + $max_csrf_token >= time());
}

function request_is_from_same_domain() {
    if(!isset($_SERVER['HTTP_REFERER'])) { return false; }
    $referer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    return ($referer_host === $_SERVER['HTTP_HOST']);
}

?>
