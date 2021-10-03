<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PalindromeController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

     /**
     * check string is palindrome.
     *
     * @return void
     */
    public function checkPalindrome()
    {
        $string = request()->input('string'); // get the request
        $string = str_replace(' ', '', $string); // replace all spaces
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);  // remove special characters
        $string = strtolower($string);  // make a string to lowercase
        $reverseString = strrev($string); // reverse string

        if ($string == $reverseString) {
            return "palindrome";
        } else {
            return "Bukan Palindrome";
        }
    }
}
