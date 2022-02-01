	    <div class="linksCompetitions">
		    	<ul>
			    	<li><a class="@if(Route::currentRouteName() == "challenges") active @endif" href="{{route("challenges")}}">المسابقات الفردية</a></li>
			    	<li><a class="@if(Route::currentRouteName() == "collective") active @endif" href="{{route("collective")}}">المسابقات الجماعية</a></li>
			    	<li><a class="@if(Route::currentRouteName() == "old_challenges") active @endif" href="{{route("old_challenges")}}">التحديات السابقة</a></li>
			    	
		    	</ul>
	    </div>