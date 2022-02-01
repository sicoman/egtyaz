<?php

namespace Tests\Feature\Front;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Traits\Curly ;

class Filter extends TestCase
{
    use Curly ;
    protected $del = "\r\n" ;

    public $index = [
        'Search By Any Origin' => '/api/v1/units?page=1&limit=1&origin_id=210' ,
        'Search By Type' => '/api/v1/units?page=1&limit=1&Types[]=210' ,
        'Search By User' => '/api/v1/units?page=1&limit=1&status=1&user=1' ,
        'Search By Amenties' => '/api/v1/units?page=1&limit=1&Amenities[]=1' ,
        'Search By Rest' => '/api/v1/units?Rest[]=1&page=1&limit=1' ,
        'Search By Price' => '/api/v1/units?page=1&limit=1&price_in[]=100&price_in[]=500' ,
        'Search By Origin , adults , childrens , checkin , checkout ' => '/api/v1/units?page=1&limit=1&origin_id=210&Units[rooms]=2&Units[beds]=2&Units[bathrooms]=1&Units[guests]=2&check_in=2020-03-20&check_out=2020-03-28' ,
    ];

    public $sort = [
        'DATE' , 'DATEASC' , 'PRICE' , 'PRICEASC' , 'FEATURED' , 'RATED' , 'POPULAR'
    ];

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $del = $this->del ;

        foreach( $this->index as $name => $parameters ) {
            $method = 'GET' ; $url = $parameters ; $post = [] ;
            if( is_array( $parameters ) ){
                $method = $parameters[0] ; $url = $parameters[1] ; $post = $parameters[2] ;
            }

            foreach( $this->sort as $t ) {
                
                $url_with_sort = $url ;

                $url_with_sort .= '&OrderBy='.$t ;

                $result = $this->fetch(  $method , getenv('APP_URL' , 'http://127.0.0.1:8000/') .$url_with_sort , $post ) ;

                if( $result[0] == 500 ){
                    echo $del . $url_with_sort ;
                }

                $this->assertEquals( 200 , $result[0] );
                
                echo $del." Filter Search { $name } With Sort { '.$t.' } ->  Ok." ;

            }
                        
        }

    }

}