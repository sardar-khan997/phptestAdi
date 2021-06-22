<?php
// Inititating Gzip
ob_start("ob_gzhandler");

// ******* SERIALIZATION ********  //
// Object will be converted to binary form and will be Strod in a file obj.txt


// including the object class

include 'classSt.php';

$obj=new test1("CSII");
//Serializing the object
$str=serialize($obj);

// if we do not want to store it in a file remove line 19,20,21. line 15 has serialized the object 
// storing in a file will help us in accessing this string in another file
$fd=fopen("obj.txt","w");
fwrite($fd, $str);
fclose($fd);


// ******* ENCRYPTION ********  //
// Function for cypher Encryption


  function encrypt($message, $encryption_key){
    $key = hex2bin($encryption_key);
    $nonceSize = openssl_cipher_iv_length('aes-256-ctr');
    $nonce = openssl_random_pseudo_bytes($nonceSize);
    $ciphertext = openssl_encrypt(
      $message, 
      'aes-256-ctr', 
      $key,
      OPENSSL_RAW_DATA,
      $nonce
    );
    return base64_encode($nonce.$ciphertext);
  }


 // Function for cypher decryption
  function decrypt($message,$encryption_key){
    $key = hex2bin($encryption_key);
    $message = base64_decode($message);
    $nonceSize = openssl_cipher_iv_length('aes-256-ctr');
    $nonce = mb_substr($message, 0, $nonceSize, '8bit');
    $ciphertext = mb_substr($message, $nonceSize, null, '8bit');
    $plaintext= openssl_decrypt(
      $ciphertext, 
      'aes-256-ctr', 
      $key,
      OPENSSL_RAW_DATA,
      $nonce
    );
    return $plaintext;
  }
  
  $private_secret_key = '1f4276388ad3214c873428dbef42243f' ; 

// encrypting serialized object

  $encrypted = encrypt($str,$private_secret_key);

  // Output
  print_r($obj) ;
  echo '<h3>Original Serialized String : '.$str.'</h3>';
  echo '<h3>After Encryption : '.$encrypted.'</h3>';
  echo '<h3>After Decryption of Serialized String : '.decrypt($encrypted,$private_secret_key).'</h3>';
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $name = htmlspecialchars($_REQUEST['fname']);
    if (empty($name)) {
        echo "Name is empty";
    } else {
        echo $name;
    }
}
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>


  

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
  Name: <input type="text" name="fname">
  <input type="submit">
</form>

</body>
</html>
<?php
  // FLushing Gzip encoding
  ob_end_flush()
  ?>
