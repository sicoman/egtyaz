
@extends('layouts.app') 
@section('Content')
	
 <div class="askTeacher support minHeight600"> 

    <div class="container">
    <br />
    <ul class="breadCpanel">
	    	<li><a href="/">الرئيسية</a></li>
	    	<li>{{ $page->name ?? $page->title ?? '' }}</li>
    </ul>
    <br />
    		
			<div class="sendAsk">
	    		<div class="h1 text-center">
					{{ $page->name ?? $page->title ?? '' }}
				</div>
			</div>

    		<div class="sendAsk">
	    			<div class="row">

						@if( isset($page->file[5]) )
							<div class="col-md-12">
								{!! embed_code( $page->file ) !!}
							</div>
						@endif

	    				<div class="col-md-12">
							{!! $page->description ?? $page->content ?? '' !!}
						</div>
	    			</div>
	    	</div>
        </div>
        
</div>  	   
@stop
