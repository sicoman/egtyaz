<?php

namespace Tests\Feature\Front;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Traits\Curly ;

class Home extends TestCase
{

    use Curly ;

    protected $del = "\r\n" ;

    public $index = [
        'Slider' => '/api/v1/posts?page=1&limit=1&type=slider' ,
        'Filter City' => '/api/front/filterCity?type=style2&search=egyp&locale=en' ,
        'Department Do You Prefer' => '/api/v1/taxonomy?page=1&limit=1&type=category' ,
        'Featured Unit' => '/api/v1/units?page=1&limit=1&status=1&featured=1' ,
        'Tourist Places' => '/api/v1/posts?page=1&limit=1&type=tourist_places' ,
        'Recomended for you' => '/api/v1/units?recomended=1&page=1&limit=1' ,
        'Popular Places' => '/api/v1/posts?page=1&limit=1&type=popular_places' ,
        'Top Rated Experiences' => '/api/v1/units?OrderBy=RATED&page=1&limit=1' ,
        'Subscribe to mail list' => ['POST' , '/api/subscribe' , [ 'name' => ' Test Name' , 'email' => 'momaiz.net@ezuru.net'] ] ,
    ];

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $del = $this->del ;
        //$this->withoutExceptionHandling();
        //$this->withoutMiddleware();

        foreach( $this->index as $name => $parameters ) {

            $method = 'GET' ; $url = $parameters ; $post = [] ;
            if( is_array( $parameters ) ){
                $method = $parameters[0] ; $url = $parameters[1] ; $post = $parameters[2] ;
            }

            $result = $this->fetch(  $method , getenv('APP_URL' , 'http://127.0.0.1:8000/') .$url , $post ) ;

            $this->assertEquals( 200 , $result[0] );
        
            echo $del."Index Components { $name } ->  Ok." ;
                        
        }

    }
}
