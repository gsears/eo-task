<?php

// Contains callAPI() and view() helpers
require 'helpers.php';

// Get function, which fetches the user's lists from EO for a dropdown select
function get() {

    $listsResponse = callAPI('GET', 'https://emailoctopus.com/api/1.5/lists', [
        'api_key' => getenv("API_KEY"),
    ]);

    $listsResponseData = json_decode($listsResponse['data'], true);
    $lists = $listsResponseData['data'];

    // Map to extract id / names
    $filteredLists = array_map(function ($list) {
        return [
            'id' => $list['id'],
            'name' => $list['name']
        ];
    }, $lists);

    return [
        'lists' => $filteredLists
    ];
}

// Post function, which sends the info to the api and returns
// a 'flashmessage' parameter for the view on success / failure
function post()
{
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $listId = $_POST['list_id'];

    // Validate email, return early if failse
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [
            'flashMessage' => 'Please type a valid email'
        ];
    }

    $response = callAPI('POST', "https://emailoctopus.com/api/1.5/lists/$listId/contacts", [
        'api_key' => getenv("API_KEY"),
        'email_address' => $email,
        'fields' => [
            'FirstName' => $firstName,
            'LastName' => $lastName
        ]
    ]);

    if($response && $response['code'] === 200) {
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

    return [
        'flashMessage' => $flashMessage
    ];
}

// Render the view with the correct parameters

$params = $_SERVER['REQUEST_METHOD'] === 'POST' ?
    array_merge(get(), post()) :
    get();

view('list_form', $params);
