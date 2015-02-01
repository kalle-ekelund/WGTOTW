<?php
/**
 * Bootstrapping functions, essential and needed for Anax to work together with some common helpers. 
 *
 */



/**
 * Utility for debugging.
 *
 * @param mixed $array values to print out
 *
 * @return void
 */
function dump($array) 
{
    echo "<pre>" . htmlentities(print_r($array, 1)) . "</pre>";
}



/**
 * Sort array but maintain index when compared items are equal.
 * http://www.php.net/manual/en/function.usort.php#38827
 *
 * @param array    &$array       input array
 * @param callable $cmp_function custom function to compare values
 *
 * @return void
 *
 */
function mergesort(&$array, $cmp_function) 
{
    // Arrays of size < 2 require no action.
    if (count($array) < 2) return;
    // Split the array in half
    $halfway = count($array) / 2;
    $array1 = array_slice($array, 0, $halfway);
    $array2 = array_slice($array, $halfway);
    // Recurse to sort the two halves
    mergesort($array1, $cmp_function);
    mergesort($array2, $cmp_function);
    // If all of $array1 is <= all of $array2, just append them.
    if (call_user_func($cmp_function, end($array1), $array2[0]) < 1) {
        $array = array_merge($array1, $array2);
        return;
    }
    // Merge the two sorted arrays into a single sorted array
    $array = array();
    $ptr1 = $ptr2 = 0;
    while ($ptr1 < count($array1) && $ptr2 < count($array2)) {
        if (call_user_func($cmp_function, $array1[$ptr1], $array2[$ptr2]) < 1) {
            $array[] = $array1[$ptr1++];
        } else {
            $array[] = $array2[$ptr2++];
        }
    }
    // Merge the remainder
    while ($ptr1 < count($array1)) $array[] = $array1[$ptr1++];
    while ($ptr2 < count($array2)) $array[] = $array2[$ptr2++];
    return;
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}

/**
 * trims text to a space then adds ellipses if desired
 * @param string $input text to trim
 * @param int $length in characters to trim to
 * @param bool $ellipses if ellipses (...) are to be added
 * @param bool $strip_html if html tags are to be stripped
 * @param bool $strip_style if css style are to be stripped
 * @return string
 *
 * function found here: http://stackoverflow.com/a/26098951
 */
function trim_text($input, $length, $ellipses = true, $strip_tag = true,$strip_style = true) {
//strip tags, if desired
    if ($strip_tag) {
        $input = strip_tags($input);
    }

//strip tags, if desired
    if ($strip_style) {
        $input = preg_replace('/(<[^>]+) style=".*?"/i', '$1',$input);
    }

    if($length=='full')
    {

        $trimmed_text=$input;

    }
    else
    {
//no need to trim, already shorter than trim length
        if (strlen($input) <= $length) {
            return $input;
        }

//find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);

//add ellipses (...)
        if ($ellipses) {
            $trimmed_text .= '...';
        }
    }

    return $trimmed_text;
}
