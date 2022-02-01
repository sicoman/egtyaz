 @include('frontend.includes.head')
<body class="bodyCpanel overflowH">

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
</style>