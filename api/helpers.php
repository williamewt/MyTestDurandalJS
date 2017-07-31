<?php

function validateEmail($email){

    $pattern = "/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/";

    if (preg_match($pattern, $email)):
        return true;
    else:
        return false;
    endif;
}

function validatePassword($password){

    $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d!@#$%&*()-_+=]{6,}$/";

    if (preg_match($pattern, $password)):
        return true;
    else:
        return false;
    endif;
}

function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = []) {
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5(strtolower(trim($email)));
    $url .= "?s=$s&d=$d&r=$r";

    if($img):
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val ):
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
        endforeach;
    endif;
    
    return $url;
}

