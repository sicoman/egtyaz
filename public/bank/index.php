<?php
  // Here is the data we will be sending to the service
 $orderid = uniqid();
  $some_data['apiOperation'] =  'CREATE_CHECKOUT_SESSION';
  $some_data['interaction']["operation"] =  'PURCHASE';
  $some_data['order']["id"] =  $orderid;
  $some_data['order']["amount"] =  '100.00';
  $some_data['order']["currency"] =  'EGP';
  $some_data['order']["description"] =  'payment method';

  $curl = curl_init();
  // You can also set the URL you want to communicaate with by doing this:
  // $curl = curl_init('http://localhost/echoservice');
  $some_data = json_encode($some_data,true);


  curl_setopt( $curl, CURLOPT_POST, 1);
  // [Snippet] howToPost - end
  curl_setopt( $curl, CURLOPT_POSTFIELDS, $some_data);
  // [Snippet] howToSetHeaders - start
  curl_setopt( $curl, CURLOPT_HTTPHEADER, array("Content-Length: " . strlen($some_data)));
  //curl_setopt( $curl, CURLOPT_HTTPHEADER, array("Content-Type: Application/json;charset=UTF-8"));
		// [Snippet] howToSetURL - start
    // call the function below to construct the URL for sending the transaction
    curl_setopt( $curl, CURLOPT_URL, "https://banquemisr.gateway.mastercard.com/api/rest/version/57/merchant/TESTMERCHTST_EGP/session");
		// [Snippet] howToSetURL - end

    // [Snippet] howToSetCredentials - start
    // set the API Password in the header authentication field.
    curl_setopt( $curl, CURLOPT_USERPWD, "merchant.TESTMERCHTST_EGP" . ":" . "26c176246ea2389bea43649c5e1d426e");
    // [Snippet] howToSetCredentials - end

    // tells cURL to return the result if successful, of FALSE if the operation failed
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE);
  // You can also bunch the above commands into an array if you choose using: curl_setopt_array

  // Send the request
  $result = curl_exec($curl);
  $result = json_decode($result,true);
  echo "<pre>";
  print_r($result);
  echo "</pre>";
  // Get some cURL session information back
  $info = curl_getinfo($curl);  

  // Free up the resources $curl is using
  curl_close($curl);

?>


<html>
    <head>
        <script src="https://banquemisr.gateway.mastercard.com/checkout/version/57/checkout.js" data-error="errorCallback" data-afterRedirect="complete" data-cancel="cancelCallback"></script>
        <script type="text/javascript">

            function errorCallback(error) {
                  console.log(JSON.stringify(error));
            }

            function cancelCallback() {
                  console.log('Payment cancelled');
            }

            Checkout.configure({
              session: { 
            	id: '<?=$result["session"]["id"];?>'
       			},
              interaction: {
                    merchant: {
                        name: 'MERCHANT NAME',
                        address: {
                            line1: '200 Sample St',
                            line2: '1234 Example Town'            
                        }    
                    }
               }
            });

	    function complete(result) { 
                  console.log(result);
		  alert("sss");
            }

        </script>
    </head>
    <body>
       ...
      <input type="button" value="Pay with Lightbox" onclick="Checkout.showLightbox();" />
      <input type="button" value="Pay with Payment Page" onclick="Checkout.showPaymentPage();" />
       ...
    </body>
</html>
