
@extends('layouts.dashboard') 
@section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')


	<div class="askTeacher pilotDetails materials support minHeight600">
    		
	    		<div class="savedQues rewards">
	    			<div class="headSaved clearfix ">
	    				<h2 class="title">{{ $showAsk->user->name }}</h2>
	    				<ul class="listRemove">
	    					<li><span class="remove">{{ $showAsk->created_at->diffForHumans() }}</span></li>
	    				</ul>
					</div>

					<div class="desc boxStyle">
						{{ $showAsk->question }}
					</div>


					<form class="sendAsk" action="{{ route('answerAsk' , $showAsk->id ) }}" method="post">
						@csrf 
						<div class="row">
							<div class="col-md-12">
								<span class="title"> الاجابة على السؤال </span>
								<textarea name="answer" placeholder="اكتب تفاصيل الاجابة" class="inputStyle"></textarea>
							</div>
						</div>
						<button type="submit" class="btnForm">ارسل الآن</button>
						<input type="hidden" name="ask_id" value="{{ $showAsk->id }}" />
					</form>

					<div class="row">
						<br class="clearfix clear" />
						<br class="clearfix clear" />
						<br class="clearfix clear" />
						<br class="clearfix clear" />
					</div>
					
					@if( count($answers) > 0 || 1 == 1 )
						<div class="row">
							<hr class="clearfix clear" />
						</div>
						
						<div class="headSaved  ">
							<h2 class=" text-center"> اجابات على السؤال  </h2>
							
						</div>
						<br />

						<div class="comments">
							<div class="media">
								<div class="media-body" id="comments">
									@foreach($answers as $answer)
											<div class="comment-card">
												<div class="media">
													<div class="media-left">
														<img src="{{ $answer->user->avatar }}" class="img-fluid" alt="">
													</div>
													<div class="media-body">
														<h4 class="card-title text-dark font-weight-bold font-16">
															{{ $answer->user->name }} <small>{{ $answer->created_at->diffForHumans() }}</small>                                                        
														</h4>
														<hr />
														<p>{{ $answer->answer }}</p>							
													</div>
												</div>
											</div>
											<br>
									@endforeach
								</div>
							</div>
						</div>
	    			@endif
	    		</div>
	
    		
	</div>
	
	<style>
		
		.comment-card,.comment-card .media .media-left img {
			-moz-background-clip:padding-box;
			-webkit-background-clip:padding-box;
			background-clip:padding-box ;
			background:#fff !important; 
			border:1px solid #ddd ;
			padding:7px;border-radius:7px
		}
		.comment-card{
			padding-top:17px !important
		}
		.comment-card .media .media-left img {
			width:60px;
			height:60px;
			-webkit-border-radius:50%;
			-moz-border-radius:50%;
			border-radius:50%
		}
		.comment-card .media .media-body {
			padding:0 10px
		}
		.comment-card .media .media-body p {
			color:#999;
			margin:0
		}


	</style>

@endsection