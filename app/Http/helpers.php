<?php 
use Carbon\Carbon;


function generateUsername($_fname, $_lname) {
    $fname = explode(' ', $_fname);
    $username = cleanStr($fname[0]) . '.' . cleanStr($_lname);
    
    while (\DB::table('users')->where('username', $username)->count()) {
        // A 2 digit suffix for already existing username
        $suffix = str_pad(rand(0, pow(10, 2) - 1), 2, '0', STR_PAD_LEFT);
        $username = $username . $suffix;
    }
    
    return $username;
}

function cleanStr($str) {
    $string = str_replace(' ', '-', $str); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $str); // Removes special chars.

}

