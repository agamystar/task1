<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public $collect_data=[];

    /* you can add new file e.g DataProviderZ that refer to DataProviderZ.json */
    public $json_providers=[
        "DataProviderX",
        "DataProviderY"
    ];
    /* DataProviderX interface transfer */
    public $DataProviderX=[
        "balance"=>"parentAmount",
        "currency"=>"Currency",
        "email" => "parentEmail",
        "status"=>"statusCode",
        "created_at"=>"registerationDate",
        "id"=>"parentIdentification"
    ];
    /* DataProviderY interface transfer  */
    public $DataProviderY=[
        "balance"=>"balance",
        "currency"=>"currency",
        "email"=> "email",
        "status"=>"status",
        "created_at"=> "created_at",
        "id"=> "id" 
    ];
    public $status=[
          "1"=>"authorised",
          "100"=>"authorised",
          "2"=>"decline",
          "200"=>"decline",
          "3"=>"refunded",
          "300"=>"refunded"
    ];
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transformObject($object,$provider,$request){
       
        $obj=[
            "balance"=>$object[$this->$provider["balance"]],
            "currency"=>$object[$this->$provider["currency"]],
            "email"=> $object[$this->$provider["email"]],
            "status"=>$this->status[$object[$this->$provider["status"]]],
            "created_at"=> $object[$this->$provider["created_at"]],
            "id"=> $object[$this->$provider["id"]] ,
            "provider"=>$provider
        ];
         $result=$obj;

        if($request->statusCode){
            if($obj['status']!=$request->statusCode){
                $result=[];
            }
        }
        if($request->currency){
            if($obj['currency']!=$request->currency){
                $result=[];
            }
        }
        if($request->balanceMin){
            if($obj['balance'] >= $request->balanceMin){
            }else{
                $result=[];
            }
        }
        if($request->balanceMax){
            if($obj['balance'] <= $request->balanceMax){
            }else{
                $result=[];
            }
        }
        
        return $result;
    }
    public function index(Request $request)
    {
        try{
            $search_files=$this->json_providers;
            $filter=[];
            if($request->provider){
                $search_files=[$request->provider];
            }
        
            foreach($search_files as $p){
               $f_json = file_get_contents(public_path().'/files/'.$p.'.json');
               $f_arr  =json_decode($f_json,true);
               foreach($f_arr as $obj){
                $custom_obj=$this->transformObject($obj,$p,$request);
                if(!empty($custom_obj)){
                    $this->collect_data[]=$custom_obj;
                }
               }
            }
        }catch(\Exception $ex){
            dd($ex);
        }

        return Response()->json($this->collect_data);
    }

    
  
}
