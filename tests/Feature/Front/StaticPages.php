<?php

namespace Tests\Feature\Front;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StaticPages extends TestCase
{
    protected $del = "\r\n" ;

    protected $types = ['news' , 'about' , 'checkin' , 'contact' , 'faq' , 'how_work' , 'partiners' , 'payment_methods' , 'trust_saftey' , 'cancle' ,'why_ezuru' , 'blog' ,'careers' , 'privacy' , 'terms' ] ;

    protected $pages = ['slider' , 'tourist_places' , 'popular_places'] ;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $del = $this->del ;
        $this->withoutExceptionHandling();
        $this->withoutMiddleware();

        // /api/static/

        foreach( $this->types as $type ) {
            // Get $type List
            $response = $this->json('GET' , '/api/static/'.$type , []);
            if( $type == 'news' ){
                $response->assertStatus(200)->assertJson(['category_count' => true]);
            }else{
                $response->assertStatus(200)->assertJson(['title' => true]);
            }
            
            echo $del."Static Page { $type } ->  Ok." ;
        }

    }
}
