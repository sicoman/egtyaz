
  @extends('layouts.dashboard') 
  @section('Content')

	@include('frontend.includes.topPartDashboard')
	@include('frontend.includes.breadCrumbDashboard')	    
	    <div class="ratePage  minHeight600">
    		
			<div class="itemRate">
				<div class="rateHead clearfix">
					<div class="rightSection">
						<h2 class="title">مادة اللفظي</h2>
						<ul class="listRate clearfix">
							<li><img src="images/medal-of-honor.png" /></li>
							<li><img src="images/medal-of-honor.png" /></li>
							<li><img src="images/medal-of-honor.png" /></li>
							<li><img src="images/medal-of-honor.png" /></li>
						</ul>
					</div>
					<div class="leftSection">
					    <div data-size="120" data-value="0.5" data-fill="{&quot;color&quot;: &quot;#00C29B&quot;}" class="first circleNormal circle">
					      <strong></strong>
					    </div>
	    				<span class="textRate">نسبة الانجاز</span>
				    </div>
				</div>
				<div class="rateBody">
					<span class="progressTitle">اكمل الجمل الآتية</span>
					<div class="progress clearfix"><div progress-width="70" class="color1 line"><span class="numb">70%</span></div></div>
				
					<span class="progressTitle">ضع علامة صح ام خطاً</span>
					<div class="progress clearfix"><div progress-width="90" class="color2 line"><span class="numb">90%</span></div></div>
				
					<span class="progressTitle">اكمل النقط التالية</span>
					<div class="progress clearfix"><div progress-width="30" class="color3 line"><span class="numb">30%</span></div></div>
			
					<span class="progressTitle">بم تفسر</span>
					<div class="progress clearfix">
						<div progress-width="85" class="color4 line"><span class="numb">85%</span></div>
					</div>

				</div>
				
			</div>
    		
			<div class="itemRate">
				<div class="rateHead clearfix">
					<div class="rightSection">
						<h2 class="title">مادة الكمي</h2>
						<ul class="listRate clearfix">
							<li><img src="images/medal-of-honor.png" /></li>
							<li><img src="images/medal-of-honor.png" /></li>
							<li><img src="images/medal-of-honor.png" /></li>
							<li><img src="images/medal-of-honor.png" /></li>
						</ul>
					</div>
					<div class="leftSection">
					    <div data-size="120" data-value="0.5" data-fill="{&quot;color&quot;: &quot;#6662E2&quot;}" class="third circleNormal circle">
					      <strong></strong>
					    </div>
	    				<span class="textRate">نسبة الانجاز</span>
				    </div>
				</div>
				<div class="rateBody">
					<span class="progressTitle">اكمل الجمل الآتية</span>
					<div class="progress clearfix"><div progress-width="70" class="color1 line"><span class="numb">70%</span></div></div>
				
					<span class="progressTitle">ضع علامة صح ام خطاً</span>
					<div class="progress clearfix"><div progress-width="90" class="color2 line"><span class="numb">90%</span></div></div>
				
					<span class="progressTitle">اكمل النقط التالية</span>
					<div class="progress clearfix"><div progress-width="30" class="color3 line"><span class="numb">30%</span></div></div>
			
					<span class="progressTitle">بم تفسر</span>
					<div class="progress clearfix">
						<div progress-width="85" class="color4 line"><span class="numb">85%</span></div>
					</div>
				</div>
			</div>
    
			<div class="itemRate">
				<div class="rateBody">
					<h2 class="bodyTitle">اختبار القدرات</h2>
					<div class="progress clearfix">
						<div progress-width="70" class="color1 line"><span class="numb">70%</span></div>
					</div>
				</div>
			</div>
    
			<div class="itemRate finishRate">
				<div class="rateBody">
					<h2 class="bodyTitle">التقييم النهائي</h2>
					<div class="progress">
						<div style="width:20%" class="color5 line"><span class="numb">15%</span><span class="Text">المستوى الأول</span></div>
						<div style="width:25%" class="color6 line"><span class="numb">10%</span><span class="Text">المستوى الأول</span></div>
						<div style="width:10%" class="color7 line"><span class="numb">5%</span><span class="Text">المستوى الأول</span></div>
						<div style="width:30%" class="color8 line"><span class="numb">70%</span><span class="Text">المستوى الأول</span></div>	
					</div>
				</div>
			</div>
    
    		<div class="clearfix">
    			<a href="#" class="btnForm">إعادة جميع التقييمات</a>
    		</div>
    		
	    </div>

  @endsection