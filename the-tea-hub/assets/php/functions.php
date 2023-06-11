<?php
require_once 'config.php';
$db = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die("No database connected!");

// Function to show pages
function showPage($page,$data=""){
include("assets/pages/$page.php");
}

// Function to check Like status
function checkLikeStatus($post_id){
    global $db;
    $current_user = $_SESSION['userdata']['id'];
    $query="SELECT count(*) as row FROM likes WHERE user_id=$current_user && post_id=$post_id";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_assoc($run)['row'];
}

// Function to like post
function like($post_id){
    global $db;
    $current_user=$_SESSION['userdata']['id'];
    $query="INSERT INTO likes(post_id,user_id) VALUES($post_id,$current_user)";
   $poster_id = getPosterId($post_id);
   
    return mysqli_query($db,$query);
    
}

// Function to create comments
function addComment($post_id,$comment){
    global $db;
 $comment = mysqli_real_escape_string($db,$comment);

    $current_user=$_SESSION['userdata']['id'];
    $query="INSERT INTO comments(user_id,post_id,comment) VALUES($current_user,$post_id,'$comment')";
    $poster_id = getPosterId($post_id);

    return mysqli_query($db,$query);
    
}


// Function to get comment count
function getComments($post_id){
    global $db;
    $query="SELECT * FROM comments WHERE post_id=$post_id ORDER BY id DESC";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_all($run,true);
}

// Function to show time
function show_time($time){
    return '<time style="font-size:small" class="timeago text-muted text-small" datetime="'.$time.'"></time>';
}

// Function to get like count
function getLikes($post_id){
    global $db;
    $query="SELECT * FROM likes WHERE post_id=$post_id";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_all($run,true);
}

// Function to unlike posts
function unlike($post_id){
    global $db;
    $current_user=$_SESSION['userdata']['id'];
    $query="DELETE FROM likes WHERE user_id=$current_user && post_id=$post_id";
  
    return mysqli_query($db,$query);
}

// Function to show errors
function showError($field){
    if(isset($_SESSION['error'])){
    $error =$_SESSION['error'];
        if(isset($error['field']) && $field==$error['field']){
            ?>
            <div class="alert alert-danger my-2" role="alert">
            <?=$error['msg']?>
            </div>
            <?php
        }
    }
}

// Function to show previous form data
function showFormData($field){
    if(isset($_SESSION['formdata'])){
        $formdata =$_SESSION['formdata'];
        return $formdata[$field];
    }
}

// Function to check if the Email is registered to other accounts
function isEmailRegistered($email){
    global $db;
    $query="SELECT count(*) as row FROM users WHERE email='$email'";
    $run=mysqli_query($db,$query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

// Function to check if the username is registered to other accounts
function isUsernameRegistered($username){
    global $db;
    $query="SELECT count(*) as row FROM users WHERE username='$username'";
    $run=mysqli_query($db,$query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

// Function to check if the username is registered to other accounts
function isUsernameRegisteredByOther($username){
    global $db;
    $user_id=$_SESSION['userdata']['id'];
    $query="SELECT count(*) as row FROM users WHERE username='$username' && id!=$user_id";
    $run=mysqli_query($db,$query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

// Function to validate the signup form
function validateSignupForm($form_data){
    $response=array();
    $response['status']=true;
    if(!$form_data['password']){
        $response['msg']="password is not given";
        $response['status']=false;
        $response['field']='password';
    }
    if(!$form_data['username']){
        $response['msg']="username is not given";
        $response['status']=false;
        $response['field']='username';
    }   
    if(!$form_data['email']){
        $response['msg']="email is not given";
        $response['status']=false;
        $response['field']='email';
    }  
    if(!$form_data['last_name']){
        $response['msg']="last name is not given";
        $response['status']=false;
        $response['field']='last_name';
    }
    if(!$form_data['first_name']){
        $response['msg']="first name is not given";
        $response['status']=false;
        $response['field']='first_name';
    }
    if(isEmailRegistered($form_data['email'])){
        $response['msg']="email id is already registered";
        $response['status']=false;
        $response['field']='email';
    }
    if(isUsernameRegistered($form_data['username'])){
        $response['msg']="username is already registered";
        $response['status']=false;
        $response['field']='username';
    }
    return $response;
}

// Function to validate the login form
function validateLoginForm($form_data){
    $response=array();
    $response['status']=true;
    $blank=false;
    if(!$form_data['password']){
        $response['msg']="password is not given";
        $response['status']=false;
        $response['field']='password';
            $blank=true;
    }
    if(!$form_data['username_email']){
        $response['msg']="username/email is not given";
        $response['status']=false;
        $response['field']='username_email';
        $blank=true;
    }
    if(!$blank && !checkUser($form_data)['status'] ){
        $response['msg']="something is incorrect, we can't find you";
        $response['status']=false;
        $response['field']='checkuser';
    }else{
        $response['user']=checkUser($form_data)['user'];
    }
    return $response;
}

// Function to check the users
function checkUser($login_data){
    global $db;
    $username_email = $login_data['username_email'];
    $password=md5($login_data['password']);
    $query = "SELECT * FROM users WHERE (email='$username_email' || username='$username_email') && password='$password'";
    $run = mysqli_query($db,$query);
    $data['user'] = mysqli_fetch_assoc($run)??array();
    if(count($data['user'])>0){
        $data['status']=true;
    }else{
        $data['status']=false;
    }
 return $data;
}

// Function to get userdata via ID
function getUser($user_id){
    global $db;
    $query = "SELECT * FROM users WHERE id=$user_id";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_assoc($run);
}

// Function to filter the suggestion list
function filterFollowSuggestion(){
    $list = getFollowSuggestions();
    $filter_list  = array();
    foreach($list as $user){
        if(!checkFollowStatus($user['id']) && !checkBS($user['id']) && count($filter_list)<5){
        $filter_list[]=$user;
        }
    }
    return $filter_list;
}

// Function to show users other users
function getFollowSuggestions(){
    global $db;
    $current_user = $_SESSION['userdata']['id'];
    $query = "SELECT * FROM users WHERE id!=$current_user";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_all($run,true);
}

// Function to get post by ID
function getPostById($user_id){
    global $db;
    $query = "SELECT * FROM posts WHERE user_id=$user_id ORDER BY id DESC";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_all($run,true);
}

// Function to get poster ID
function getPosterId($post_id){
    global $db;
    $query = "SELECT user_id FROM posts WHERE id=$post_id";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_assoc($run)['user_id'];
}

// Function to search for users
function searchUser($keyword){
    global $db;
    $query = "SELECT * FROM users WHERE username LIKE '%".$keyword."%' || (first_name LIKE '%".$keyword."%' || last_name LIKE '%".$keyword."%') LIMIT 5";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_all($run,true);
}

// Function to get userdata from username
function getUserByUsername($username){
    global $db;
    $query = "SELECT * FROM users WHERE username='$username'";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_assoc($run);
}

// Function to get posts
function getPost(){
    global $db;
    $query = "SELECT users.id as uid,posts.id,posts.user_id,posts.post_img,posts.post_text,posts.created_at,users.first_name,users.last_name,users.username,users.profile_pic FROM posts JOIN users ON users.id=posts.user_id ORDER BY id DESC";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_all($run,true);
}

// Function to delete post from row
function deletePost($post_id){
    global $db;
    $user_id=$_SESSION['userdata']['id'];
    $dellike = "DELETE FROM likes WHERE post_id=$post_id && user_id=$user_id";
    mysqli_query($db,$dellike);
    $delcom = "DELETE FROM comments WHERE post_id=$post_id && user_id=$user_id";
    mysqli_query($db,$delcom);
    $query = "DELETE FROM posts WHERE id=$post_id";
    return mysqli_query($db,$query);
}

// Function to get dynamic post wall
function filterPosts(){
    $list = getPost();
    $filter_list  = array();
    foreach($list as $post){
         $filter_list[]=$post;
    }
    
    return $filter_list;
}

// Function to create new user row
function createUser($data){
    global $db;
    $first_name = mysqli_real_escape_string($db,$data['first_name']);
    $last_name = mysqli_real_escape_string($db,$data['last_name']);
    $gender = $data['gender'];
    $email = mysqli_real_escape_string($db,$data['email']);
    $username = mysqli_real_escape_string($db,$data['username']);
    $password = mysqli_real_escape_string($db,$data['password']);
    $password = md5($password);
    $query = "INSERT INTO users(first_name,last_name,gender,email,username,password) ";
    $query.="VALUES ('$first_name','$last_name',$gender,'$email','$username','$password')"; 
    return mysqli_query($db,$query);
}

// Function to verify email
function verifyEmail($email){
    global $db;
    $query="UPDATE users SET ac_status=1 WHERE email='$email'";
    return mysqli_query($db,$query);
}

// Function to reset password
function resetPassword($email,$password){
    global $db;
    $password=md5($password);
    $query="UPDATE users SET password='$password' WHERE email='$email'";
    return mysqli_query($db,$query);
}

// Function to validate the edit profile page
function validateUpdateForm($form_data,$image_data){
    $response=array();
    $response['status']=true;
      

        if(!$form_data['username']){
            $response['msg']="Username is not entered.";
            $response['status']=false;
            $response['field']='username';
        }
        
        if(!$form_data['last_name']){
            $response['msg']="Surname is not entered.";
            $response['status']=false;
            $response['field']='last_name';
        }
        if(!$form_data['first_name']){
            $response['msg']="First Name is not entered.";
            $response['status']=false;
            $response['field']='first_name';
        }
  
        if(isUsernameRegisteredByOther($form_data['username'])){
            $response['msg']=$form_data['username']." is already registered.";
            $response['status']=false;
            $response['field']='username';
        }
    
       if($image_data['name']){
           $image = basename($image_data['name']);
           $type = strtolower(pathinfo($image,PATHINFO_EXTENSION));
           $size = $image_data['size']/1000;

           if($type!='jpg' && $type!='jpeg' && $type!='png'){
            $response['msg']="Only jpg,jpeg,png images are permitted.";
            $response['status']=false;
            $response['field']='profile_pic';
        }

        if($size>1000){
            $response['msg']="Upload an image thats less than 1MB.";
            $response['status']=false;
            $response['field']='profile_pic';
        }
    }

    return $response;    
}

// Function to update the users information
function updateProfile($data,$imagedata){
    global $db;
    $first_name = mysqli_real_escape_string($db,$data['first_name']);
    $last_name = mysqli_real_escape_string($db,$data['last_name']);
    $username = mysqli_real_escape_string($db,$data['username']);
    $password = mysqli_real_escape_string($db,$data['password']);

    if(!$data['password']){
        $password = $_SESSION['userdata']['password'];
    }else{
        $password = md5($password);
        $_SESSION['userdata']['password']=$password;
    }

    $profile_pic="";
    if($imagedata['name']){
        $image_name = time().basename($imagedata['name']);
        $image_dir="../images/profile/$image_name";
        move_uploaded_file($imagedata['tmp_name'],$image_dir);
        $profile_pic=", profile_pic='$image_name'";
    }

    $query = "UPDATE users SET first_name = '$first_name', last_name='$last_name',username='$username',password='$password' $profile_pic WHERE id=".$_SESSION['userdata']['id'];
    return mysqli_query($db,$query);
}


// Function for validating the post parameters
function validatePostImage($image_data){
    $response=array();
    $response['status']=true;
    if(!$image_data['name']){
        $response['msg']="No image was chosen.";
        $response['status']=false;
        $response['field']='post_img';
    }
    if($image_data['name']){
        $image = basename($image_data['name']);
        $type = strtolower(pathinfo($image,PATHINFO_EXTENSION));
        $size = $image_data['size']/1000;

        if($type!='jpg' && $type!='jpeg' && $type!='png'){
            $response['msg']="Only jpg,jpeg,png images are permitted.";
            $response['status']=false;
            $response['field']='post_img';
        }

        if($size>2000){
            $response['msg']="Upload an image less then 1MB.";
            $response['status']=false;
            $response['field']='post_img';
        }
    }
    return $response;
}

// Function to create new post
function createPost($text,$image){
    global $db;
    $post_text = mysqli_real_escape_string($db,$text['post_text']);
    $user_id = $_SESSION['userdata']['id'];
    $image_name = time().basename($image['name']);
    $image_dir="../images/posts/$image_name";
    move_uploaded_file($image['tmp_name'],$image_dir);
    $query = "INSERT INTO posts(user_id,post_text,post_img)";
    $query.="VALUES ($user_id,'$post_text','$image_name')"; 
    return mysqli_query($db,$query);
}

// Redundant code
function checkFollowStatus(){
}

function checkBS(){
}
   
?>