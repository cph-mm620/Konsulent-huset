<?php

require('api/config/database.php');
require('api/objects/user.php');

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$user = new User($db);

// set product property values
$user->userId = $userId;
$user->firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
$user->lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
$user->email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

if (
    !empty($user->userId) &&
    !empty($user->firstName) &&
    !empty($user->lastName) &&
    !empty($user->email) &&

    $user->update()

) {

    // set response code
    http_response_code(200);
    
    // redirect to profile page
    header("Location: /konsulent-huset/profile");
    
    // display message: user was updated
    echo json_encode(array("message" => "User was updated."));

    // update session variables
    session_start();
    $_SESSION["firstName"] = $user->firstName;
    $_SESSION["lastName"] = $user->lastName;
    $_SESSION["email"] = $user->email;
}
// message if unable to update user
else {
    // set response code
    http_response_code(400);
    // display message: unable to update user
    echo json_encode(array("message" => "Unable to update user.", $user->email));
}
