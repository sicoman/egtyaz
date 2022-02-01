<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

   @foreach( $urls as $url ) 
        <url>

            <loc>{{ getenv('VUE' , 'http://demo.ezuru.com') . $url->url }}</loc>

            <lastmod>{{ $url->updated_at }}</lastmod>

            <changefreq>daily</changefreq>

            <priority>1</priority>

        </url>
        @if( isset($url->url_ar) )
            <url>

                <loc>{{ getenv('VUE' , 'http://demo.ezuru.com') .'ar/'. $url->url_ar }}</loc>

                <lastmod>{{ $url->updated_at }}</lastmod>

                <changefreq>daily</changefreq>

                <priority>1</priority>

            </url>
        @endif
   @endforeach

</urlset> 
