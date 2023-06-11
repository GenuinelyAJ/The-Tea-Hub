<?php
require_once 'functions.php';
require_once 'send_code.php';


if(isset($_GET['test'])){
 
}

if(isset($_GET['deletepost'])){
    $post_id = $_GET['deletepost'];
      if(deletePost($post_id)){
          header("location:{$_SERVER['HTTP_REFERER']}");
      }else{
          echo "Something went wrong :(";
      }
  
    
}

// Statement to manage signup
if(isset($_GET['signup'])){
$response=validateSignupForm($_POST);
if($response['status']){
    if(createUser($_POST)){
    header('location:../../?login&newuser');
    }else{
        echo "<script>alert('Something is wrong :(')</script>";
    }
   

}else{
    $_SESSION['error']=$response;
    $_SESSION['formdata']=$_POST;
    header("location:../../?signup");
}
    
}



// Statement to manage log in
if(isset($_GET['login'])){

  
    $response=validateLoginForm($_POST);
  
    if($response['status']){
     $_SESSION['Auth'] = true;
     $_SESSION['userdata'] = $response['user'];

     if($response['user']['ac_status']==0){
     $_SESSION['code']=$code = rand(100000,999999);
     sendCode($response['user']['email'],'Verify Your Email',$code);
     }

     header("location:../../");

    }else{
        $_SESSION['error']=$response;
        $_SESSION['formdata']=$_POST;
        header("location:../../?login");
    }
        
    }


    if(isset($_GET['resend_code'])){
       
            $_SESSION['code']=$code = rand(10000,999999);
            sendCode($_SESSION['userdata']['email'],'Verify Your Email',$code);
            header('location:../../?resended');
    }

    if(isset($_GET['verify_email'])){
       $user_code = $_POST['code'];
       $code = $_SESSION['code'];
       if($code==$user_code){
       if(verifyEmail($_SESSION['userdata']['email'])){
        header('location:../../');
       }else{
           echo "Something is wrong :(";
       }

       }else{
           $response['msg']='The verification code is incorrect.';
           if(!$_POST['code']){
            $response['msg']='Enter the correct OTP.';

           }
           $response['field']='email_verify';
        $_SESSION['error']=$response;
        header('location:../../');

       }
       
}


if(isset($_GET['forgotpassword'])){
    if(!$_POST['email']){
        $response['msg']="No Email inputted.";
        $response['field']='email';
        $_SESSION['error']=$response;
        header('location:../../?forgotpassword');

    }elseif(!isEmailRegistered($_POST['email'])){
        $response['msg']="Email not registered.";
        $response['field']='email';
        $_SESSION['error']=$response;
        header('location:../../?forgotpassword');

    }else{
          $_SESSION['forgot_email']=$_POST['email'];
           $_SESSION['forgot_code']=$code = rand(111111,999999);
            sendCode($_POST['email'],'Forgot Your Password?',$code);
            header('location:../../?forgotpassword&resended');
    }


}



// Statement to logout user
if(isset($_GET['logout'])){
    session_destroy();
    header('location:../../');

}


// Statement to verify code for forgot password
if(isset($_GET['verifycode'])){
    $user_code = $_POST['code'];
    $code = $_SESSION['forgot_code'];
    if($code==$user_code){
    $_SESSION['auth_temp']=true;
     header('location:../../?forgotpassword');
    }else{
        $response['msg']='The verification code is incorrect.';
        if(!$_POST['code']){
         $response['msg']='Please enter a 6-Digit code.';

        }
        $response['field']='email_verify';
     $_SESSION['error']=$response;
     header('location:../../?forgotpassword');

    }
    
}

if(isset($_GET['changepassword'])){
    if(!$_POST['password']){
        $response['msg']="Enter your new Password.";
        $response['field']='password';
        $_SESSION['error']=$response;
        header('location:../../?forgotpassword');
    }else{
        resetPassword($_SESSION['forgot_email'],$_POST['password']);
        session_destroy();
        header('location:../../?reseted');
    }


}


if(isset($_GET['updateprofile'])){

    $response=validateUpdateForm($_POST,$_FILES['profile_pic']);

    if($response['status']){
       
        if(updateProfile($_POST,$_FILES['profile_pic'])){
            header("location:../../?editprofile&success");

        }else{
            echo "Something went wrong :(";
        }
       
    
    }else{
        $_SESSION['error']=$response;
        header("location:../../?editprofile");
    }
     
}

// Statement to manage add post
if(isset($_GET['addpost'])){
   $response = validatePostImage($_FILES['post_img']);

   if($response['status']){
if(createPost($_POST,$_FILES['post_img'])){
    header("location:../../?new_post_added");
}else{
    echo "Something went wrong :(";
}
   }else{
    $_SESSION['error']=$response;
    header("location:../../");
   }
}