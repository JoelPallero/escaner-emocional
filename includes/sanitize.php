<?php


//Sanitize data from Forms
function sanitizeData($data){
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    return $data;    
}
