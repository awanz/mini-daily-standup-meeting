<?php 

    function sendEmail($receiver, $subject, $body){
        print_r($receiver);
        print_r($subject);
        print_r($body);
    }

    sendEmail('1111', '222', '333');