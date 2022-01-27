<?php

namespace App\Helpers\API;

use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class General extends Exception
{

    public function __construct()
    {

    }

    public static function filterRequest($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = self::filterRequest($value);
                } else {
                    $data[$key] = trim(strip_tags($value));
                }
            }
        }
        return $data;
    }


    public static function from_camel_case($tests)
    {
        $poArray = [];
        foreach ($tests as $test => $result) {
            $output = self::inner_camel_case($test);
            $poArray[$output] = $result;
        }
        return $poArray;
    }

    public static function inner_camel_case($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }


    public static function camelCase($apiResponseArray, $isSingleArr = false)
    {
        $finalArr = array();
        if (!$isSingleArr) {
            foreach ($apiResponseArray as $key => $val) {
                foreach ($val as $key1 => $testval) {
                    if (is_null($testval)) {
                        $apiResponseArray[$key][$key1] = "";
                    }
                }
            }
            foreach ($apiResponseArray as $key1 => $val1) {
                $keys = array_map(function ($i) {
                    $parts = explode('_', $i);
                    return array_shift($parts) . implode('', array_map('ucfirst', $parts));
                }, array_keys($val1));
                array_push($finalArr, array_combine($keys, $val1));
            }
        } else {

            $keys = array_map(function ($i) {
                $parts = explode('_', $i);
                return array_shift($parts) . implode('', array_map('ucfirst', $parts));
            }, array_keys($apiResponseArray));
            array_push($finalArr, array_combine($keys, $apiResponseArray));
        }
        return $finalArr;
    }

    public static function getEnumValues($table, $column)
    {
        $type = DB::select(DB::raw("SHOW COLUMNS FROM $table WHERE Field = '{$column}'"))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            $enum = array_add($enum, $v, $v);
        }
        return $enum;
    }

         // public static function exceptionHandler(\Exception $exception) {
    //     $class = get_class($exception);
    //     switch ($class) {
    //         case NotFoundHttpException::class:
    //             return General::setResponse('NOT_FOUND', $exception->getMessage());
    //             break;
    //         case AccessDeniedException::class:
    //             return General::setResponse('FORBIDDEN', $exception->getMessage());
    //             break;
    //         default:
    //             return General::setResponse('other_error', $exception->getMessage());
    //             break;
    //     }
    // }

}
