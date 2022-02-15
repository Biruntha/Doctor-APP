<?php

namespace App\Imports;

use App\Models\Site;
use App\Models\Field;
use App\Models\Language;
use App\Models\Country;
use App\Models\SiteField;
use App\Models\SiteSpecialField;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Validator;
use DB;
use Log;

class SiteImport implements WithHeadingRow, ToCollection
{
    public function collection(Collection $rows)
    {
        ini_set('max_execution_time', 1800);
        $customMessages = [
            'code.unique' => 'This code has already been taken. Please try with another.',
            'url.required' => 'URL cannot be empty',
            'url.unique' => 'This url has already been taken. Please try with another.',
            'fields.required' => 'Fields cannot be empty',
            'price.required' => 'Price cannot be empty',
            'special_price.required' => 'Special Price cannot be empty',
            'language.required' => 'Language cannot be empty'
        ];
        $row_no = 2;
        $error_arr=array();

        //Making Site map ======================================
        $siteArray = Site::withTrashed()->get();
        $siteUrlObjMap = array();
        foreach($siteArray as $s){
            $siteUrlObjMap[self::formatURL($s->unmasked_url)] = $s;
        }
        
        //Making Field map ====================================
        $fieldArray = Field::all();
        $fieldNameObjMap = array();
        foreach($fieldArray as $s){
            $fieldNameObjMap[self::formatStringsForKeys($s->name)] = $s;
        }

        //Making Language map =================================
        $langArray = Language::all();
        $langNameObjMap = array();
        foreach($langArray as $s){
            $langNameObjMap[self::formatStringsForKeys($s->name)] = $s;
        }

        //Making Country map ==================================
        $countryArray = Country::all();
        $countryNameObjMap = array();
        foreach($countryArray as $s){
            $countryNameObjMap[self::formatStringsForKeys($s->name)] = $s;
        }


        //Log::info(print_r($siteUrlObjMap));
        foreach ($rows as $row) {
            $isAnExistingSite = isset($siteUrlObjMap[self::formatURL($row["url"])]);
            //Log::info(print_r("========================="));
            //Log::info(print_r($row["url"]."----".self::formatURL($row["url"]) . " === " .$isAnExistingSite));
            if ($isAnExistingSite) {
                $site = $siteUrlObjMap[self::formatURL($row["url"])];
                $rules = array(
                    'url' => 'required|unique:sites,url,' . $site->id,
                    'fields' => 'required',
                    'price' => 'required|numeric|min:0',
                    'language' => 'required',
                );
            } else {
                $rules = array(
                    'url' => 'required|unique:sites',
                    'fields' => 'required',
                    'price' => 'required|numeric|min:0',
                    'language' => 'required',
                );
                $site = new Site();
            }

            $validator = Validator::make($row->toArray(), $rules, $customMessages);

            if ($validator->fails()) {
                foreach($validator->errors()->getMessages() as $validationErrors) {
                    if (is_array($validationErrors)) {
                        foreach($validationErrors as $validationError) {
                            $response_msg = "<tr><td>".$row_no."</td><td>".$row["url"]."</td><td>".$validationError."</td></tr>";
                            array_push($error_arr, $response_msg);
                        }  
                    } else {
                        $response_msg = "<tr><td>".$row_no."</td><td>".$row["url"]."</td><td>".$validationErrors."</td></tr>";
                        array_push($error_arr, $response_msg);  
                    }
                }
            } else {
                //$language = Language::where("name", $row["language"])->first();
                $isValidLanguage = isset($langNameObjMap[self::formatStringsForKeys($row["language"])]);
                if (!$isValidLanguage) {
                    $response_msg = "<tr><td>".$row_no."</td><td>".$row["url"]."</td><td>Invalid language ".$row["language"]."</td></tr>";
                    array_push($error_arr, $response_msg); 
                } 

                //Fields
                $fields = explode(',', $row["fields"]);
                $invalid_fields = array();
                foreach ($fields as $field) {
                    $field = trim($field);
                    //$field_id = Field::where("name", $field)->first();                    
                    if (!isset($fieldNameObjMap[self::formatStringsForKeys($field)])) {
                        //$fieldObj = $fieldNameObjMap[self::formatStringsForKeys($field)];
                        array_push($invalid_fields, $field);
                    }
                }
                if (count($invalid_fields) > 0) {
                    $response_msg = "<tr><td>".$row_no."</td><td>".$row["url"]."</td><td> Invalid Field names: ".join(", ", $invalid_fields)."</td></tr>";
                    array_push($error_arr, $response_msg);   
                }
                
                //country
                if (!is_null($row["country"])) {
                    //$country = Country::where("code", $row["country"])->first();
                    if (!isset($countryNameObjMap[self::formatStringsForKeys($row["country"])])) {
                        $response_msg = "<tr><td>".$row_no."</td><td>".$row["url"]."</td><td>Invalid country: ".$row["country"]."</td></tr>";
                        array_push($error_arr, $response_msg);
                    }
                }
                                    
                //Special fields
                $sfields = explode(',', $row["special_fields"]);
                $invalid_sfields = array();
                foreach ($sfields as $sfield) {
                    if($sfield != "") {
                        $sfield = trim($sfield);
                        //$special_field_id = Field::where("name", explode('=', $sfield)[0])->first();
                        $sfieldStr = explode('=', $sfield)[0];  
                        $sfieldStr = trim($sfieldStr);
                        $sfieldStr = self::formatStringsForKeys($sfieldStr);  
                        if (!isset($fieldNameObjMap[$sfieldStr])) {
                            array_push($invalid_sfields, $sfieldStr);
                        }
                    }
                }
                if (count($invalid_sfields) > 0) {
                    $response_msg = "<tr><td>".$row_no."</td><td>".$row["url"]."</td><td> Invalid Special Field names: ".join(", ", $invalid_sfields)."</td></tr>";
                    array_push($error_arr, $response_msg);   
                }
            }
            $row_no++;
        }
        if (count($error_arr) > 0) {
            return redirect()->back()->withErrors($error_arr);
        } else {
            $newSites = 0;
            $updatedSites = 0;
            $row_no = 0;

            foreach ($rows as $row) {
                try {
                    $row_no++;
                    //$site = Site::where("url", $row["url"])->first();
                    if (!isset($siteUrlObjMap[self::formatUrl($row["url"])])) {
                        $site = new Site();
                        $newSites++;
                    } else {
                        $site = $siteUrlObjMap[self::formatUrl($row["url"])];
                        $updatedSites++;
                    }
    
                    //$country = Country::where("code", $row["country"])->first();
                    $country = $countryNameObjMap[self::formatStringsForKeys($row["country"])];
                    if (!is_null($country)) {
                        $site->country = $country->id;
                    }

                    $language = $langNameObjMap[self::formatStringsForKeys($row["language"])];
                    if (!is_null($language)) {
                        $site->language = $language->id;
                    }
                
                    $site->title = $row["title"];
                    $site->unmasked_url = self::formatURL($row["url"]);
                    $site->url = self::maskURL($site->unmasked_url);
                    $site->price = $row["price"];
                    $site->internal_price = $row["internal_price"];
                    $site->description = $row["description"];
                    $site->article_length = $row["article_length"];
                    $site->writer_instruction = $row["writer_instruction"];
                    $site->manager_instruction = $row["manager_instruction"];
                    $site->admin_instruction = $row["admin_instruction"];
                    $sponsorship = $row["sponsorship"];
                    if (strtolower($sponsorship) == 'marked') {
                        $sponsorship = 'Marked';
                    } else {
                        $sponsorship = 'Not Marked';
                    }
                    $site->sponsorship = $sponsorship;
                    $df_nf = $row["df_nf"];
                    if (strtolower($df_nf) == 'df') {
                        $df_nf = 'DF';
                    } elseif (strtolower($df_nf) == 'nf') {
                        $df_nf = 'NF';
                    }elseif (strtolower($df_nf) == 'sponsored') {
                        $df_nf = 'Sponsored';
                    } else {
                        $df_nf = 'Unknown';
                    }
                    $site->df_nf =  $df_nf;
                    
                    $site->deleted_at = null;
                    $site->save();
                    $site->code = "#".sprintf("%04s", $site->id);
                    $site->save();

                    $siteUrlObjMap[$site->unmasked_url] = $site;

                    $siteFields = DB::table('site_fields')->where('site', $site->id);
                    if (!empty($siteFields) and !is_null($siteFields)) {
                        $siteFields->delete();
                    }
                
                    //Fields
                    $fields = explode(',', $row["fields"]);
                    foreach ($fields as $field) {
                        $field = trim($field);
                        $field_id = Field::where("name", $field)->first();
                        $siteField = new SiteField();
                        $siteField->site = $site->id;
                        $siteField->field = $field_id->id;
                        $siteField->save();
                    }
                
                    //Special fields
                    $sfields = explode(',', $row["special_fields"]);
                    $siteSpecialFields = DB::table('site_special_fields')->where('site', $site->id);
                    if (!empty($siteSpecialFields) and !is_null($siteSpecialFields)) {
                        $siteSpecialFields->delete();
                    }
                
                    foreach ($sfields as $sfield) {
                        $sfield = trim($sfield);
                        $arr = explode('=', $sfield);
                        $sfield_name = trim($arr[0]);
                        $sfield_price = 0;
                        if (count($arr) > 1) {
                            $sfield_price = trim($arr[1]);
                        }

                        //$special_field_id = Field::where("name", $sfield_name)->first();
                        if (isset($fieldNameObjMap[self::formatStringsForKeys($sfield_name)]) and !empty($sfield_name)) {
                            $special_field_id = $fieldNameObjMap[self::formatStringsForKeys($sfield_name)];
                            $siteSpecialField = new SiteSpecialField();
                            $siteSpecialField->site = $site->id;
                            $siteSpecialField->special_field = $special_field_id->id;
                            $siteSpecialField->special_price = $sfield_price;
                            $siteSpecialField->save();
                        }
                    }
                }
                catch(\Exception $e) {
                    $response_msg = "<tr><td>".$row_no."</td><td>".$row["url"]."</td><td>Error while saving the site: ".$e->getMessage()."</td></tr>";
                    array_push($error_arr, $response_msg);   
                }
            }

            if (count($error_arr) > 0) {
                return redirect()->back()->withErrors($error_arr);
            }
            else{
                return redirect()->back()->with('message', 'Site Records have been successfully imported. New Sites: '.$newSites.' | Updated Sites: '.$updatedSites);
            }
        }
    }

    function formatURL($url)
    {
        $url = trim(strtolower($url));
        $url = str_replace("https://", "", $url);
        $url = str_replace("http://", "", $url);
        $url = str_replace("www.", "", $url);
        return $url;
    }

    function formatStringsForKeys($str)
    {
        return trim(strtolower($str));
    }

    function maskURL($url)
    {
        $urlParts = explode(".", $url);
        $x = 0;
        $maskedUrl = "";

        foreach($urlParts as $p)
        {
            $x++;

            if($x > 1)
                $maskedUrl = $maskedUrl.".";

            if($x == sizeof($urlParts)) //ignore masking if it the last part
            {
                $maskedUrl = $maskedUrl.$p;
            }
            else //mask
            {
                $maskedUrl = $maskedUrl.self::mask($p, 1, 1);
            }
        }

        return $maskedUrl;
    }

    function mask($str, $first, $last) {
        $len = strlen($str);
        $toShow = $first + $last;
        return substr($str, 0, $len <= $toShow ? 0 : $first).str_repeat("*", $len - ($len <= $toShow ? 0 : $toShow)).substr($str, $len - $last, $len <= $toShow ? 0 : $last);
    }
}
