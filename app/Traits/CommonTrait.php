<?php
namespace App\Traits;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;

trait CommonTrait {

    /**
     * Clean string or array.
     *
     * @param string|array
     *
     * @return string|array
     */
    public function cleanString($badString){
        if(is_array($badString))
        {
          foreach($badString as &$badStringO)
          {
            $badStringO = filter_var($badStringO, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
            $badStringO = trim(strip_tags(mb_convert_encoding(utf8_encode($badStringO), 'UTF-8', 'UTF-8')));
          }
        }
        elseif(!empty($badString))
        {
          $badString = filter_var($badString, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
          $badString = trim(strip_tags(mb_convert_encoding(utf8_encode($badString), 'UTF-8', 'UTF-8')));
        }
        return $badString;
    }

    /**
     * clening input value
     * @param input value
     * @return clear input value
     */
    function cleanInput($input) {
        $search = array(
            '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
        );
        $output = preg_replace($search, '', $input);
        return $output;
    }

    function sqlPrevents($input) {
        if (is_array($input)) {
            foreach($input as $var=>$val) {
                $output[$var] = $this->sqlPrevents($val);
            }
        }
        else {
            if (get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }
            $input  = $this->cleanInput($input);
            $output = mysql_real_escape_string($input);
        }
        return $output;
    }

    /**
     * Make Sql WhereRaw Query From Filter data
     * this function is for sql prevention from raw query
     * @param 'text','date','number','set' $type
     * @param key of array which match column
     * @param array of filter data
     * @return $where return sql where condition
     */
    public function getWhereRawAndValue($type,$key,$filterArray): array
    {
        $whereRaw = [];
        $valuesRaw = [];
        if(empty($filterArray)) { return []; }

        $match = $filterArray['type'] ?? '';

        if(isset($filterArray['filter_type']) && $filterArray['filter_type'] == "date"){
            $filterArray['filter'] = 0;
        }

        if(isset($filterArray['filter_type']) && $filterArray['filter_type'] == "set"){
            $filterArray['filter'] = $filterArray['values'];
        }

        $value = ( isset($filterArray['filter']) && ( !empty($filterArray['filter']) || $filterArray['filter'] == 0 )) ? $filterArray['filter'] : "";
        // $value = $this->sqlPrevents($value);

        $value_to = $filterArray['filter_to'] ?? "";

        switch ($type) {
            case "text": //if filter type will be text so it goes here

                // $value =  strip_tags($value);
                // $value =  str_replace('`','',$value);
                // $value =  str_replace("'","",$value);
                if($match  == "contains"){
                    $whereRaw[] = " $key LIKE ? ";
                    $valuesRaw[] = "%{$value}%";
                }else if($match  == "notContains"){
                    $whereRaw[] = " $key NOT LIKE ? ";
                    $valuesRaw[] = "%{$value}%";
                }else if($match  == "equals"){
                    $whereRaw[] = " $key = ? ";
                    $valuesRaw[] = "{$value}";
                }else if($match  == "notEqual"){
                    $whereRaw[] = " $key != ? ";
                    $valuesRaw[] = "{$value}";
                }else if($match  == "startsWith"){
                    $whereRaw[] = " $key LIKE ? ";
                    $valuesRaw[] = "{$value}%";
                }else if($match  == "endsWith"){
                    $whereRaw[] = " $key LIKE ? ";
                    $valuesRaw[] = "%{$value}";
                }

            break;
            case "number"://if filter type will be number so it goes here

                $value = (!empty($filterArray['filter'])) ? $filterArray['filter'] : 0;
                $value_to = (!empty($filterArray['filter_to'])) ? $filterArray['filter_to'] : 0;
                if($match  == "equals"){
                    $whereRaw[] = " $key = ? ";
                    $valuesRaw[] = "$value";
                }else if($match  == "notEqual"){
                    $whereRaw[] = " $key != ? ";
                    $valuesRaw[] = "$value";
                }else if($match  == "lessThan"){
                    $whereRaw[] = " $key < ? ";
                    $valuesRaw[] = "$value";
                }else if($match  == "lessThanOrEqual"){
                    $whereRaw[] = " $key <= ? ";
                    $valuesRaw[] = "$value";
                }else if($match  == "greaterThan"){
                    $whereRaw[] = " $key > ? ";
                    $valuesRaw[] = "$value";
                }else if($match  == "greaterThanOrEqual"){
                    $whereRaw[] = " $key >= ? ";
                    $valuesRaw[] = "$value";
                }else if($match  == "inRange"){
                    $whereRaw[] = " $key BETWEEN ? AND ?";
                    $valuesRaw[] = "$value";
                    $valuesRaw[] = "$value_to";
                }

            break;

            case "date":

                $date_from = date('Y-m-d', strtotime($filterArray['date_from']));
                $date_to = date('Y-m-d', strtotime($filterArray['date_to']));
                if($match  == "equals"){
                    $whereRaw[] = " ( $key BETWEEN ? AND ? ) ";
                    $valuesRaw[] = "{$date_from} 00:00:00";
                    $valuesRaw[] = "{$date_from} 23:59:59";
                }else if($match  == "notEqual"){
                    $whereRaw[] = " ( $key NOT BETWEEN ? AND ? ) ";
                    $valuesRaw[] = "{$date_from} 00:00:00";
                    $valuesRaw[] = "{$date_from} 23:59:59";
                }else if($match  == "lessThan"){
                    $whereRaw[] = " $key < ? ";
                    $valuesRaw[] = "{$date_from} 00:00:00";
                }else if($match  == "greaterThan"){
                    $whereRaw[] = " $key > ? ";
                    $valuesRaw[] = " {$date_from} 23:59:59 ";
                }else if($match  == "inRange"){
                    $whereRaw[] = " ( $key BETWEEN ? AND ? ) ";
                    $valuesRaw[] = "{$date_from} 00:00:00";
                    $valuesRaw[] = "{$date_to} 23:59:59";
                }

            break;

            case "set":

                $null = false;
                $condition = "(";
                if(in_array(null,$value,true)){
                    // USE THIRD ARG STRICT = TRUE BECUASE OF 0 AND NULL 
                    $null = true;
                    $condition .= " $key IS NULL ";
                }
                $value = array_values(array_filter($value,'is_numeric')); // re index with array_values

                if($value){
                    if($null){
                        $condition .= " OR ";
                    }
                    
                    $condition .= " $key in (";

                    foreach ($value as $k => $v) {
                        if($k){
                            $condition .= ",";
                        }
                        $condition .= "?";
                        $valuesRaw[] = $v;
                    }

                    $condition .= ") ";
                }

                $condition .= ") ";                

                $whereRaw[] = $condition;
                // if($match  == "contains"){
                //     $whereRaw[] = " $key LIKE '%{$value}%' ";
                // }else if($match  == "notContains"){
                //     $whereRaw[] = " $key NOT LIKE '%{$value}%' ";
                // }else if($match  == "equals"){
                //     $whereRaw[] = " $key = '{$value}' ";
                // }else if($match  == "notEqual"){
                //     $whereRaw[] = " $key != '{$value}' ";
                // }else if($match  == "startsWith"){
                //     $whereRaw[] = " $key LIKE '{$value}%' ";
                // }else if($match  == "endsWith"){
                //     $whereRaw[] = " $key LIKE '%{$value}' ";
                // }

            break;
        }
        if(isset($whereRaw) && !empty($whereRaw)){
            return [$whereRaw[0],$valuesRaw];
        } else {
            return [];
        }
    }

    /**
    * Custumize Validation message for array validation attribute 
    */
    public function customizeAttributeValidation($error, $attribute = []){
        $c = [];
        foreach($error->toArray() as $key => $value) {
            $attribute_check = false;
            foreach ($attribute as $a_key => $a_value) {
                if( strpos($key, $a_key) !== false){
                    $c[$a_value] = $value;
                    $attribute_check = true;
                }
            }
            if(!$attribute_check){
                $c[$key] = $value;
            }
        }

        return $c;
    }

    public function validateDateFormat($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    public function between($val_min, $val_max, $min, $max){
        if(($val_min >= $min && $val_min <= $max) || ($val_max >= $min && $val_max <= $max)) {
            return true;
        } else {
            return false;
        }
    }

    public function dateCheckInRange($start_date, $end_date, $date)
    {
        if(!$date){
            return false;
        }

        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $date_ts = strtotime($date);

        // Check that user date is between start & end
        return (($date_ts >= $start_ts) && ($date_ts <= $end_ts));
    }

    public function betweenWithUsingArray($validate_condition_array, $value, $sub_value){
        $count = count($validate_condition_array);
        $return = false;
        if(($value[$validate_condition_array[0]] >= $sub_value[$validate_condition_array[0]] || $value[$validate_condition_array[0]] >= $sub_value[$validate_condition_array[1]]) || ($value[$validate_condition_array[1]] >= $sub_value[$validate_condition_array[0]] || $value[$validate_condition_array[1]] >= $sub_value[$validate_condition_array[1]]) ) {
            $condition = 2;

            $all_condition_return = [];
            while($count > $condition){
                if($value[$validate_condition_array[$condition]] == $sub_value[$validate_condition_array[$condition]] ){
                    $all_condition_return[] = true;
                }
                ++$condition;
            }

            if(count($all_condition_return) && count($all_condition_return) == ($count - 2)){
                $return = true;
            }
        }
        return $return;
    }
}
