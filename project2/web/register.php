<?php
    include 'hash_methods.php';
    
    $username=$_POST['username'];
    $salt = openssl_random_pseudo_bytes(40, $was_strong);
    $pwd =$_POST['password'];// no need to store
    $r=2000;
    $secure_pwd=hash_m3($pwd,$salt,$r);
    
    $dbhandle = new PDO("sqlite:user.sqlite") or die("Failed to open DB");
    if (!$dbhandle) die ($error);
    
    // print("SELECT username from user WHERE username='".$username."'");
    $statement = $dbhandle->prepare("SELECT username from user WHERE username='".$username."'");
    $statement->execute();
    $results1 = $statement->fetch(PDO::FETCH_ASSOC);
    
    
    if (!$results1){
    // echo "INSERT INTO user ('username','hash','salt','stretch') 
    //                              VALUES('".$username."','".$secure_pwd."','".bin2hex($salt)."','".$r."')";
      $statement = $dbhandle->prepare("INSERT INTO user ('username','hash','salt','stretch') 
                                     VALUES('".$username."','".$secure_pwd."','".bin2hex($salt)."','".$r."')");
      $statement->execute();
      $results2 = $statement->fetch(PDO::FETCH_ASSOC);
    //   echo json_encode($results2);
        print("success");
        header("Location: login.html?register_success=1");
    }
    else{
        print('user exist');
        header("Location: register.html?register_success=0");
    }
?>