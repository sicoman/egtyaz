 @include('frontend.includes.head')
<body class="bodyCpanel overflowH">
    <div class="menuCpanel active"> 
    	<i class="iconMenuCpanel"></i>
		@if( $CURRENT_USER_ROLE == 'teacher' )
			<ul class="linksCpanel">
				<li>
					<a href="{{ route('askTeacher') }}" class="@if( Route::is('askTeacher*') ) active @endif"><i class="iconColor9 flaticon-question-1"></i>اسئل معلم</a>
					<ul>
						@if( $CURRENT_USER_ROLE != 'teacher' )
						<li>
							<a href="{{ route('askTeacher') }}" class="@if( Route::is('askTeacher') ) active @endif"> اسئل معلم  </a>
						</li>
						<li>
							<a href="{{ route('askTeacherMine') }}" class="@if( Route::is('askTeacherMine') ) active @endif"> قائمة اسئلتى </a>
						</li>
						@else
						<li>
							<a href="{{ route('askTeacherList') }}" class="@if( Route::is('askTeacherList') ) active @endif" > قائمة الاسئلة </a>
						</li>
						@endif
					</ul>
				</li>
			</ul>
		@else
		<ul class="linksCpanel">
			<li><a href="{{ route('packages') }}"><i class="iconColor10 flaticon-group"></i>خطط الاشتراكات</a></li>
			<li><a href="{{ route('bank') }}" class="@if( Route::is('bank*') ) active @endif"><i class="iconColor1 flaticon-information"></i>بنك الأسئلة</a></li>
			<li><a href="{{ route('exam') }}"class="@if( Route::is('exam*') ) active @endif"><i class="iconColor2 flaticon-testing"></i>اختبار تجريبى </a>
				<ul>
				<li><a href="{{ route('exam' ) }}">اختبار تجريبى جديد </a></li>
				<li><a href="{{ route('exams') }}">انجازاتى </a></li>
				</ul>
			</li> 
			<li><a href="javascript:;"class="@if( Route::is('subjects*') ) active @endif"><i class="iconColor3 flaticon-idea-3"></i>اختبار القدرات </a>
				<ul> 
					<li><a href="{{ route('examsSubjects', ['id' => '23']) }}">كمي </a></li>
					<li><a href="{{ route('examsSubjects', ['id' => '24']) }}">لفظي </a></li>
					<li><a href="{{ route('examsSubjects', ['id' => '2']) }}">لفظي وكمي </a></li>
					{{-- <li><a href="{{ route('examsSubjects', ['id' => 'free']) }}">كثير التكرار </a></li>  --}}
				</ul>
			</li>
			<li><a href="{{ route('start') }}"><i class="iconColor4 flaticon-education"></i>التأسيس</a></li>
			<li><a href="{{ route('challenges') }}"><i class="iconColor5 flaticon-discussion"></i>المسابقات</a></li>
			<li><a href="{{ route('courses') }}"><i class="iconColor6 flaticon-online-education"></i>الدورات المتقدمة</a></li>
			<li><a href="{{ route('rate') }}"><i class="iconColor7 flaticon-rating"></i>التقييم</a></li>
			<li>
				<a href="{{ route('community') }}" class="@if( Route::is('community*') ) active @endif"><i class="iconColor9 flaticon-question-1"></i>المنتدى الاجتماعى</a>
				<ul>
				 <li>
				  <a href="{{ route('community') }}" class="@if( Route::is('community') ) active @endif" > مجتمع المنصه</a>
				 </li>
				 <li>
				  <a href="{{ route('community_new_post') }}" class="@if( Route::is('community_new_post') ) active @endif" >اضافة موضوع جديد</a>
				 </li>
				</ul>
			</li>
			<li>
				<a href="{{ route('askTeacher') }}" class="@if( Route::is('askTeacher*') ) active @endif"><i class="iconColor9 flaticon-question-1"></i>اسئل معلم</a>
				<ul>
 					@if( $CURRENT_USER_ROLE != 'teacher' )
					<li>
						<a href="{{ route('askTeacher') }}" class="@if( Route::is('askTeacher') ) active @endif"> اسئل معلم  </a>
					</li>
					<li>
						<a href="{{ route('askTeacherMine') }}" class="@if( Route::is('askTeacherMine') ) active @endif"> قائمة اسئلتى </a>
					</li>
					@else
					<li>
						<a href="{{ route('askTeacherList') }}" class="@if( Route::is('askTeacherList') ) active @endif" > قائمة الاسئلة </a>
					</li>
					@endif
				</ul>
			</li>
			<li><a href="{{ route('rewards') }}"><i class="iconColor10 flaticon-giftbox"></i>المكافئات</a></li>
			<li><a href="{{ route('wishlist') }}"  class="@if( Route::is('wishlist') ) active @endif"><i class="iconColor12 flaticon-save"></i>الأسئلة المحفوظة</a></li>
		</ul>
		@endif
		
    </div>

    @yield('Content')
    
    <div id="modalShare" class="modalShare modal fade order-box" role="dialog">
          <div class="modal-dialog">
                        
            <div class="modal-content">
    
                <div class="modal-body">
    
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="title">شارك :</h2>
					
					<div style="direction:ltr !important">
						<div class="sharethis-inline-share-buttons" data-url="{{ $shareThis ?? '' }}"></div>
					</div>
                </div>
            </div>
        </div>
    </div>
    
	@include('frontend.includes.dashboard-footer') 
    
<style>
	#st_gdpr_iframe {
		right: -5000px !important;
    	left: auto !important;
	}
	.modalQues img{
		max-width:100% !important;
	}

	@media (max-width: 1170px) {
		.Breadcrumb{
			padding: 27px 0 !important
		}
	}
</style>