<?php
include('database.php');
$obj=new query();

$name='';
$email='';
$mobile='';
$image='';
$id='';
$msg='';
$image_required='required';

if(isset($_GET['id']) && $_GET['id']!=''){
	$image_required='';
	$id=$obj->get_safe_str($_GET['id']);
	$condition_arr=array('id'=>$id);
	$result=$obj->getData('user','*',$condition_arr);
	$name=$result['0']['name'];
	$email=$result['0']['email'];
	$mobile=$result['0']['mobile'];
	$image=$result['0']['image']; 
	
	$hobbyArr = array(); 
	$hobbyArr[] = $result['0']['hobbies']; 
	foreach($hobbyArr as $value){
		$hobbyArr = $value;
	}
	$hobbyArr = explode(",",$hobbyArr) ;
}

if(isset($_POST['submit'])){
	$name=$obj->get_safe_str($_POST['name']);
	$email=$obj->get_safe_str($_POST['email']);
	$mobile=$obj->get_safe_str($_POST['mobile']);
	$hobbies=$_POST['hobbies'];
	$hobbies_string = implode(",", $hobbies);
	
	
	if($_FILES['image']['type']!=''){
			if($_FILES['image']['type']!='image/png' && $_FILES['image']['type']!='image/jpg' && $_FILES['image']['type']!='image/jpeg'){
			$msg="Please select only png,jpg and jpeg image format";
		}
	}
	
	if($msg==""){
		
		if($id==''){
			$image=$_FILES['image']['name'];
			move_uploaded_file($_FILES['image']['tmp_name'],PRODUCT_IMAGE_SERVER_PATH.$image);
			$condition_arr=array('name'=>$name,'email'=>$email,'mobile'=>$mobile,'image'=>$image,'hobbies'=>$hobbies_string);
			$obj->insertData('user',$condition_arr);
		}else{
			if($_FILES['image']['name']!=''){
				$image=$_FILES['image']['name'];
				move_uploaded_file($_FILES['image']['tmp_name'],PRODUCT_IMAGE_SERVER_PATH.$image);
				$condition_arr=array('name'=>$name,'email'=>$email,'mobile'=>$mobile,'image'=>$image,'hobbies'=>$hobbies_string);
				$obj->updateData('user',$condition_arr,'id',$id);
			}else{
				$condition_arr=array('name'=>$name,'email'=>$email,'mobile'=>$mobile,'hobbies'=>$hobbies_string);
				$obj->updateData('user',$condition_arr,'id',$id);
			}
			
			
		}?>
		<script>
			window.location.href='index.php';
		</script>
		<?php
	}
}
	//header('location:users.php');
	?>
<!doctype html>
<html lang="en-US">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>OOPS CRUD</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
	  <style>
		.container{margin-top:100px;}
	  </style>
   </head>
   <body>
      
      <div class="container">
         <div class="card">
            <div class="card-header"><i class="fa fa-fw fa-plus-circle"></i> <strong>Add User</strong> <a href="index.php" class="float-right btn btn-dark btn-sm"><i class="fa fa-fw fa-globe"></i> Browse Users</a></div>
            <div class="card-body">
               <div class="col-sm-6">
                  <h5 class="card-title">Fields with <span class="text-danger">*</span> are mandatory!</h5>
                  <form method="post" enctype="multipart/form-data">
                     <div class="form-group">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter name" required value="<?php echo $name?>">
                     </div>
                     <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" required value="<?php echo $email?>">
                     </div>
                     <div class="form-group">
                        <label>Mobile <span class="text-danger">*</span></label>
                        <input type="tel" class="tel form-control" name="mobile" id="mobile"  placeholder="Enter mobile" pattern="[6-9]{1}[0-9]{9}" required value="<?php echo $mobile?>">
                     </div>
					 <div class="form-group">
                        <label>Image <span class="text-danger">*</span></label>
						<?php 
							if($id!=''){
								?>
								<img src="<?php echo PRODUCT_IMAGE_SITE_PATH.$image ?>" width="150px" height="150px"/>
								<?php
							}
						?>
                        <input type="file" name="image" id="image" class="form-control" <?php echo  $image_required?>>
                     </div>
					 <div class="form-group">
                        <label>Hobbies</label>
                        <select class="form-control" name="hobbies[]" id="hobbies" multiple="multiple">
						<option value="Reading" <?php echo (isset($hobbyArr) && in_array('Reading', $hobbyArr))?'selected="selected"':"" ?>>Reading</option>
						<option value="Travelling" <?php echo (isset($hobbyArr) && in_array('Travelling', $hobbyArr))?'selected="selected"':"" ?>>Travelling</option>
						<option value="Watching Movies" <?php echo (isset($hobbyArr) && in_array('Watching Movies', $hobbyArr))?'selected="selected"':"" ?>>Watching Movies</option>
						<option value="Playing" <?php echo (isset($hobbyArr) && in_array('Playing', $hobbyArr))?'selected="selected"':"" ?>>Playing</option>
						<option value="Writing" <?php echo (isset($hobbyArr) && in_array('Writing', $hobbyArr))?'selected="selected"':"" ?>>Writing</option>
						</select>
                     </div>
                     <div class="form-group">
                        <button type="submit" name="submit" value="submit" id="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i> Manage User</button>
                     </div>
					 <div class="field_error"><?php echo $msg?></div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
      <script src="https://cdn.jsdelivr.net/jquery.caret/0.1/jquery.caret.js"></script>
   </body>
</html>
