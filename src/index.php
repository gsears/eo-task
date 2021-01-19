<?php

require 'helpers.php';

$method = $_SERVER['REQUEST_METHOD'];

if($method === 'POST') {
    // Get form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $listId = $_POST['list_id'];
    // Validate email

    // Send to api
    $response = callAPI('POST', "https://emailoctopus.com/api/1.5/lists/$listId/contacts", [
        'api_key' => getenv("API_KEY"),
        'email_address' => $email,
        'fields' => [
            'FirstName' => $firstName,
            'LastName' => $lastName
        ]
    ]);

    if($response) {
        $responseData = json_decode($response['data'], true);
        $confirmedEmail = $responseData['email_address'];
        $flashMessage = "$confirmedEmail added!";
    } else {
        if(!$response) {
            $flashMessage = 'Oh dear, something went wrong... Check your connection!';
        } else {
            $responseData = json_decode($response['data'], true);
            $flashMessage =  $responseData['error']['message'];
        }
    }
}

$listsResponse = callAPI('GET', 'https://emailoctopus.com/api/1.5/lists', [
    'api_key' => getenv("API_KEY"),
]);

$listsResponseData = json_decode($listsResponse['data'], true);

// Get lists from EO account
$lists = array_map(function ($list) {
    return [
        'id' => $list['id'],
        'name' => $list['name']
    ];
}, $listsResponseData['data']);

view('list_form', [
    'flashMessage' => $flashMessage,
    'lists' => $lists
]);
