<!DOCTYPE html>
<html>
    <head>
        
    </head>
    <body onload="document.getElementById('form').submit();" style="display:none">

    <form id="form" action="https://{{ env('PAYPAL_MODE' , 'sandbox') }}.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="business" value="{{ env('paypalEmail' , 'black.query-facilitator@yahoo.com') }}"> <!-- AULL9SRZVKH88 -->
	<input type="hidden" name="lc" value="EG">
	<input type="hidden" name="item_name" value="{{ $data['description'] }}">
	<input type="hidden" name="item_number" value="{{ $data['id'] }}">
	<input type="hidden" name="amount" value="{{ round( $data['amount'] , 2 , PHP_ROUND_HALF_DOWN) }}">
	<input type="hidden" name="currency_code" value="{{ 'USD' }}">
	<input type="hidden" name="button_subtype" value="services">
	<input type="hidden" name="no_note" value="0">
	<input type="hidden" name="cn" value="Add special instructions to the seller:">
	<input type="hidden" name="no_shipping" value="1">
	<input type="hidden" name="rm" value="1">
	<input type="hidden" name="return" value="{{ route('pp.success') }}">
	<input type="hidden" name="cancel_return" value="{{ route('pp.cancel') }}">
	<input type="hidden" name="tax_rate" value="0.000">
	<input type="hidden" name="shipping" value="0.00">
	<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
	<input type="hidden" name="notify_url" value="{{ route('pp.ipn') }}">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal -  online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
        
    </body>
</html>