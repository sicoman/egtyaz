Hello <i> Ezuru Admin </i>,
 <p>You got message from contact us Page. <b>{{ $data->type }}</b></p>
  <hr />  
 <div>
 <p><b>name:</b>&nbsp;{{ $data->name }}</p>
 <p><b>email:</b>&nbsp;{{ $data->email }}</p>
 <p><b>mobile:</b>&nbsp;{{ $data->mobile }}</p>

 </div>
  
 <p><u>Message IS:</u></p>
  
 <div>
 <p>{{ $data->message }}</p>
 </div>
  
 Thank You,
 <br/>
 <i>Ezuru Contact Sender</i>