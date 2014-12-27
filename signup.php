<!DOCTYPE HTML>
<html>
 <head>
  <title> Login Page </title>
   <style> 
   .error {color:red};
 
   </style>
 </head>

 <body>
/*
 <?php
$con=mysqli_connect('localhost','root','','testdb');
 if(mysqli_connect_errno()){

   echo "failed to connect:" .mysqli_connect_error();
 }
 mysqli_close($con);
$sql="create DATABASE testdb";
if(mysqli_query($con,$sql))
{
	echo "error creating database" .mysqli_error($con);
}

$sql="create table student (username varchar(20),name varchar(20),password varchar(20),email varchar(30),contact varchar(20))";

if(mysqli_query($con,$sql)){
   echo "table created successfully";
}
else
{
echo "error in table query". mysqli_error($con);

}

 ?>
 
 */
 <?php
     $usernameerr= $nameErr=$pwdErr=$conpwdErr=$emailErr=$contactErr="";
    $username=$name=$pwd=$conpwd=$email=$contact="";
	if ($_SERVER ["REQUEST_METHOD"]=="POST")
	{
		if(empty($_POST["username"]))
		{
			$usernameerr="username is required";
		}
		else{
		 $username = test_input($_POST["username"]);
		 if(!preg_match("/^[a-zA-Z ]*$/",$username)){
			$usernameerr="Only letter and white space allowed";
			}
		
		}
		if(empty($_POST["name"]))
		{
			$nameErr=" name is required";
		}
		else{
		 $name = test_input($_POST["name"]);
		 if(!preg_match("/^[a-zA-Z ]*$/",$name)){
			$nameErr="Only letter and white space allowed";
			}
		
		}
	  if(empty($_POST["pwd"]))
		{	
		  $pwdErr ="password is required";
		
		}
		else{
	  $pwd = test_input($_POST["pwd"]);
		}

        if(empty($_POST["conpwd"]))          //confirm password 
        {
		   $conpwdErr="password is required";

		}
		else{
			$conpwd=test_input($_POST["conpwd"]);
          if($conpwd!=$pwd)
			{
           $conpwdErr="password does not match";

			}
		}
		  
		if(empty($_POST["email"]))
		{	
		  $emailErr ="email id is required";
		}
		else{
		$email = test_input($_POST["email"]);
	    if(!filter_var($email,FILTER_VALIDATE_EMAIL))
			{
			$emailErr="invalid email format";
			}
		} 
	  if(empty($_POST["contact"]))
		{
		  $contactErr="contact no. is required";
		}
		else{
	       $contact = test_input($_POST["contact"]);
	    if(!preg_match("/^[0-9\_]/",$contact)){
			$contactErr="you must enter a numeric value";
		}
		}

		
	}
    function test_input($data )
	 {
	   $data=trim($data);
	   $data=stripslashes($data);
	   $data=htmlspecialchars($data);
	   return $data;
	 }

	
	?>



<form input="name" method="post" action= " <?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?> " >
  
<fieldset>
  <p>  UserName:<input type="text" name ="username" value="<?php echo $username ?>"> 
   <span class ="error" >* <?php echo $usernameerr; ?> </span>  </p>

 <p>  Name:<input type="text" name ="name" value="<?php echo $name ?>"> 
   <span class ="error" >* <?php echo $nameErr; ?> </span>  </p>


 
<p> Password: <input type ="password" name="pwd">
    <span class ="error" >* <?php echo $pwdErr; ?> </span>  
</p>
<p>Confirm Password: <input type ="password" name="conpwd">
    <span class ="error" >* <?php echo $conpwdErr; ?> </span>  
</p>

<p> E-mail: <input type ="text" name="email">
   <span class ="error" >* <?php echo $emailErr; ?> </span>  
</p>
<p>Contact No. :<input type="text" name="contact" > 
    <span class ="error" >* <?php echo $contactErr; ?> </span>  
</p>

 
 <p>  <input type ="submit" value="SUBMIT"> </p>
 
</fieldset>
  </form>
  <h1>your output is : </h1>
<?php
echo $username;echo "<br>";
 echo $name; echo "<br>";
 echo $pwd;  echo  "<br>";
 echo $email;  echo  "<br>";
 echo $contact;  echo  "<br>";

?>



 </body>
</html>
