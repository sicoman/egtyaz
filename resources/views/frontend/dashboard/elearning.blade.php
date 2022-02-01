
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	
	    <div class="vids minHeight600">
	    	<div class="tabsVideo hide">
	    		<ul class="list">
				  
				  @foreach ($cats as $cat)
	    			<li><a href="{{route("elearning.category", ["parent" => $parent ?? 0 ,"category" => $cat->id])}}" style="line-height:0;"><img src="{{$cat->photo}}" style="width: 70px; height: 70px;" /></a></li> 
				  @endforeach	
	    		</ul>
    		</div>
    		<div class="row">
            @foreach ($posts as $post)
    			<div class="col-md-4">
    				<a href="{{ route('elearning.show' , $post->id ) }}" data-id="{{$post->id}}" class="itemVid" data-target="#postContent">
						<img src="{{$post->photo}}" alt=""  />
    					<span class="details">
							@if( isset($post->file[5]) && ( strrchr($post->file , '.')  == '.mp4' || strstr( $post->file , 'youtube') || strstr( $post->file , 'vimeo') )  )
	    					<i class="fa fa-play"></i>
							@endif
	    					<h2 class="titleVideo">{{$post->title}}</h2>
    					</span>
    				</a>
    			</div>
            @endforeach
    		</div>		
		    <div class="pagiDiv clearfix">
    		    {{$posts->links()}}
    		</div>
	    </div>
       <div class="modal fade" id="postContent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postContentLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="postContentBody">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="CloseCustomModal"  data-dismiss="modal"> اغلاق </button>
                </div>
                </div>
            </div>
        </div>	
  @endsection   
@section('custom_javascript')
	<script>
       $(function(){
		   $('.itemVidx').on('click', function(){
			   $.get("{{route("singleAjax")}}", {id : $(this).attr('data-id')}, function(data){
				 $('#postContentLabel').text(data.title);  
				 $('#postContentBody').html(data.description);  
		  	     $('#postContent').modal('show') ;
			   });
		   });
	   });
	</script>
  @endsection  