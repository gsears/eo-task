<?php

function isStringEmpty($string) {
    return trim($string) === '';
}

// View render helper function
function view($name, $params = [])
{
    // Explode $params to variables
    extract($params);
    return require "views/$name.view.php";
}

function callAPI($method, $url, $data = false)
{
    $curl = curl_init();

    if($method === 'POST') {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        if ($data) {
            // Encode as json
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } else {
        if ($data) {
            $url = sprintf('%s?%s', $url, http_build_query($data));
        }
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    // Show data on successful receipt / false if not
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    return $result ? [
        'data' => $result,
        'code' => $code
    ] : false;
}
