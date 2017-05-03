<?php
require_once('includes/config.php');

$errors = array();


function removeHTMLChars($str){
    $str = nl2br($str);
    $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
    return $str;
}


//Check input and add Bootstrap Class error to the fields to complete
function checkAddCD($array){
    global $fields;

    foreach($array as $key => $value)
    {

        if($value !== '')
        {
            $fields[$key] = $value;
            if (isset($fields['price']) && $fields['price'] !== '') {
                $fields['price']='';
                if (!is_numeric($array['price']) || $array['price'] <= 0) {
                    $fields['price'] = 'has-error';
                    $error[] = 'Veuillez saisir un prix de vente supérieur à 0';
                } else {
                    $fields['price'] = $array['price'];
                }
            } else if (isset($fields['quantity']) && $fields['quantity'] !== '' ) {
                $fields['quantity']='';
                if (!is_int($array['quantity']) && $array['quantity'] < 0) {
                    $fields['quantity'] = 'has-error';
                    $error[] = 'Veuillez saisir une quantité supérieur ou égale à 0. ';
                } else {
                    $fields['quantity'] = $array['quantity'];
                }
            } else if($array['coverURL'] !== '' && $array['coverURL'] !== 'has-error'){
                $fields['coverURL'] = $array['coverURL'];
                $fields['cover-display']='disabled';
            }
        }
        else {
            $error[] = "Veuillez compléter les champs manquant ( $key )" ;
            $fields[$key] = 'has-error';
        }

    }

    $fields['error'] =  issetor($error);

    return $fields;
}



//Display flash message (css bootstrap)
function flash($type, $message){
    $url = explode('?', $_SERVER['REQUEST_URI'], 2);

   if(!empty($message)) {
         $flash  = "<div class='alert alert-$type'><a href='$url[0]' class='close' data-dismiss='alert'>&times;</a>$message</div>";

      return $flash;
   } else {
     return "" ;
   }
}


//If isset
function issetor(&$var, $default = false) {
    return isset($var) ? $var : $default;
}

//Add Bootstrap Class success or error to the fields to complete after Amazon API request
function checkAmazonRequest($cd) {
    global $fields;

        $fields['title'] = ($cd->title == '' ? 'has-error' : $cd->title);
        $fields['artist'] = ($cd->artist == '' ? 'has-error' : $cd->artist);
        $fields['cover-display'] = ($cd->cover !== '' ? 'disabled' : '');
        $fields['category'] = 'has-error';
        $fields['quantity'] = 'has-error';
        $fields['price'] = 'has-error';
        $fields['statut'] = 'has-error';



    return $fields;
}

function redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
        header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}


//Make date for sql insert
function makeDate(){
  return strftime("%Y-%m-%d %H:%M:%S", time());
}


//Amazon API for found CD info
function getMetadataFromAmazon($ean) {
    $awsAccessKeyID = awsAccessKeyID;
    $awsSecretKey = awsSecretKey;
    $awsAssociateTag = awsAssociateTag;
    $host = 'ecs.amazonaws.com';
    $path = '/onca/xml';
    $args = array(
        'AssociateTag' => $awsAssociateTag,
        'AWSAccessKeyId' => $awsAccessKeyID,
        'IdType' => 'EAN',
        'ItemId' => $ean,
        'Operation' => 'ItemLookup',
        'ResponseGroup' => 'Medium',
        'SearchIndex' => 'Music',
        'Service' => 'AWSECommerceService',
        'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
        'Version'=> '2009-01-06'
    );

    ksort($args);
    $parts = array();
    foreach(array_keys($args) as $key) {
        $parts[] = $key . "=" . $args[$key];
    }


    $stringToSign = "GET\n" . $host . "\n" . $path . "\n" . implode("&", $parts);
    $stringToSign = str_replace('+', '%20', $stringToSign);
    $stringToSign = str_replace(':', '%3A', $stringToSign);
    $stringToSign = str_replace(';', urlencode(';'), $stringToSign);

    // Sign the request
    $signature = hash_hmac("sha256", $stringToSign, $awsSecretKey, TRUE);
    $signature = base64_encode($signature);
    $signature = str_replace('+', '%2B', $signature);
    $signature = str_replace('=', '%3D', $signature);
    // Construct the URL
    $url = 'http://' . $host . $path . '?' . implode("&", $parts) . "&Signature=" . $signature;
    $rawData = file_get_contents($url);

    $metadata = simplexml_load_string($rawData);

    if (isset($metadata->Items->Request->Errors)) {
        return null;
    } else {
        $cd = array('cover'=>'', 'title'=>'', 'artist'=>'');
        $cd['cover'] = (string) $metadata->Items->Item->LargeImage->URL;
        $cd['title'] = (string) $metadata->Items->Item->ItemAttributes->Title;
        $cd['artist'] = (string) $metadata->Items->Item->ItemAttributes->Artist;

        return json_encode($cd);

    }
}


?>
