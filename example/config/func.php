<?php
    // Two-dimensional array de-weighting
    function second_array_unique_bykey($arr, $key) {
        $tmp_arr = array();
        foreach($arr as $k => $v) {
            if(in_array($v[$key], $tmp_arr))   // Search for whether $v [$key] exists in the $tmp_arr array and return true if it exists
            {
                unset($arr[$k]); // Destroy a variable and delete it if the same value already exists in $tmp_arr
            }
            else {
                $tmp_arr[$k] = $v[$key];  // Save different values in the array
            }
        }
        return $arr;
    }