<html><head><script src="https://www.google.com/recaptcha/api.js"></script></head>
<body>
<form action="" method="POST"><div class="g-recaptcha" data-sitekey="6LdJ3dMZAAAAAFmpini8LeXIZFNfIdHtOOa35UwX"></div><br><input type="submit" value="Submit"></form>
<?php
if(isset($_POST['submit']))
{
function CheckCaptcha($userResponse) {
        $fields_string = '';
        $fields = array(
            'secret' => 6LdJ3dMZAAAAAFGzFdo2-sxzhJySRFQEPLcR2KRX
            'response' => $userResponse
        );
        foreach($fields as $key=>$value)
        $fields_string .= $key . '=' . $value . '&';$fields_string = rtrim($fields_string, '&');$ch = curl_init();curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');curl_setopt($ch, CURLOPT_POST, count($fields));curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);$res = curl_exec($ch);curl_close($ch);return json_decode($res, true);
    }
    $result = CheckCaptcha($_POST['g-recaptcha-response']);
    if ($result['success']) {echo "<a href=\\"https://donno2048.github.io/index\\">Click here</a>";;} else {echo "Error, try again";}
}?></body></html>
