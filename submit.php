<?php
// Requires PHP hosting. Configure AMP_REQUEST_EMAIL in your hosting environment.
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
function reply($code,$msg,$ok=false){http_response_code($code);echo json_encode(['ok'=>$ok,'message'=>$msg]);exit;}
if($_SERVER['REQUEST_METHOD']!=='POST') reply(405,'Method not allowed.');
if(!empty($_POST['website'])) reply(200,'Request received.',true); // honeypot
$required=['name','phone','email','city','service','message'];
foreach($required as $key){if(!isset($_POST[$key])||!is_string($_POST[$key])||trim($_POST[$key])==='') reply(422,'Please complete all required fields.');}
if(($_POST['consent']??'')!=='yes') reply(422,'Please acknowledge that we may contact you.');
$email=filter_var($_POST['email'],FILTER_VALIDATE_EMAIL);
if(!$email) reply(422,'Please enter a valid email address.');
if(strlen($_POST['message'])>3000) reply(422,'Your message is too long.');
$phone=preg_replace('/\D/','',$_POST['phone']);
if(strlen($phone)<10||strlen($phone)>15) reply(422,'Please enter a valid phone number.');
$allowed=['Furnace Repair','Furnace Installation','AC Repair','AC Installation','Maintenance','Other'];
if(!in_array($_POST['service'],$allowed,true)) reply(422,'Please select a valid service.');
$target=getenv('AMP_REQUEST_EMAIL');
if(!$target||!filter_var($target,FILTER_VALIDATE_EMAIL)) reply(503,'Online requests are not configured yet.');
$keys=['name'=>'Name','phone'=>'Phone','email'=>'Email','address'=>'Street address','city'=>'City','zip'=>'ZIP','service'=>'Service','property'=>'Property type','date'=>'Preferred date','time'=>'Preferred time','urgency'=>'Urgency','message'=>'Issue'];
$lines=[];foreach($keys as $key=>$label){$value=$_POST[$key]??'';if(!is_string($value))continue;$value=trim(preg_replace('/[\r\n]+/',' ',substr($value,0,3000)));$lines[]="$label: $value";}
$body=implode("\n",$lines);$subject='New AMP Service Request';
// Use a verified sender domain configured at your hosting provider.
$sender=getenv('AMP_SENDER_EMAIL');
if(!$sender||!filter_var($sender,FILTER_VALIDATE_EMAIL))reply(503,'Email sender is not configured yet.');
$headers=['From'=>$sender,'Reply-To'=>$sender,'Content-Type'=>'text/plain; charset=UTF-8'];
$success=mail($target,$subject,$body,$headers);
if(!$success) reply(503,'Unable to deliver your request right now.');
reply(200,'Your request was received.',true);
