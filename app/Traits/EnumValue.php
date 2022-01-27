<?php 
namespace App\Traits;
use DB;

trait EnumValue
{
    /* 
    * List of all enum by TypeNAme : i.e. tax_type
    *
    * @param \Illuminate\Http\Request $request
	* @return \Illuminate\Http\JsonResponse
    */
    public function getEnumColumnValues($type_name)
    {
        $taxEnumValue = [];
        $sql = "SELECT enum.enumlabel AS value FROM pg_enum AS enum JOIN pg_type AS type ON (type.oid = enum.enumtypid) WHERE type.typname = '{$type_name}' GROUP BY enum.enumlabel, type.typname";
        $taxEnumValues =  DB::select($sql); 
        if(!empty($taxEnumValues)) {
            foreach($taxEnumValues as $enumValue) {
                $taxEnumValue[] = $enumValue->value;
            }
        }
        return $taxEnumValue;
    }

    /* 
    * Add new enum value : i.e. gst,tax,vat
    *
    * @param \Illuminate\Http\Request $request
	* @return \Illuminate\Http\JsonResponse
    */
    public function addEnumColumnValue($type_name,$enumValue)
    {
        return DB::statement("ALTER TYPE {$type_name} ADD VALUE  IF NOT EXISTS '{$enumValue}' ");
    }

}
