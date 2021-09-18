<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;

use  Tests\TestCase;

class ProvidersTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_files_exist()
    {
        $json_providers=[
            "DataProviderX",
            "DataProviderY"
        ];
        
        parent::setUp();
        $counter=0;
        foreach($json_providers as $p){
           if(file_exists(public_path() .'/files/'.$p.'.json')){
            $counter++;
            }
        }
        if(count($json_providers)==$counter){
        $this->assertTrue(true);
        }else{
        $this->assertFalse(true,"Some files  not found !");
        }
    }
 
}
