<?php
error_reporting(E_ERROR);
 header('Access-Control-Allow-Origin: *');


class tmsapi

{

    public $restler;


 
   
 
 
 /**
      * @url POST class_add
      * @url GET what/ever/you/want
     */
 function class_add(){
  global $db;
 
 

 $CName = $db->escape($_POST['CourseName']);
 $ProfessorID = $db->escape($_POST['UserID']);
 
   $sql = "SELECT *  FROM tbl_courses  WHERE CName = '$CName'  ";
$user = $db->get_row($sql);

if ( $db->num_rows==0){
     $sql = "INSERT INTO `tbl_courses` ( `CName` ,  `ProfessorID` ) VALUES(  '$CName' ,  '$ProfessorID')"; 
 $course =  $db->query($sql);
 if($course){

   return array('error'=>-1);
 }else{
  return array('error'=>1,'msg'=>'error');
   }

}else{
return array('error'=>1,'msg'=>'Course Already exists');
}
}

 /**
      * @url POST student_login
      * @url GET what/ever/you/want
     */
 function student_login(){
  global $db;
 
 

  $Email = trim($db->escape($_POST['Email']));
 $Password = trim($db->escape($_POST['Password']));
  
  if ($Email==""){
return array('error'=>1,'msg'=>'Email is required');

 }
 if ($Password==""){
return array('error'=>1,'msg'=>'Password is required');

 }


   $sql = "SELECT *  FROM tbl_students  WHERE tbl_students.SEmail = '$Email' and tbl_students.SPassword = '$Password'  ";
$user = $db->get_row($sql);
 
if ( $db->num_rows==1){
  $userID = $user->StudentID;
  $code =time() .rand(2000);
   $code = sha1($code);
   $sql = "update `tbl_students`  set Code='$code' where StudentID = '$userID'"; 
   $updateCode =  $db->query($sql);
      $sql = "SELECT tbl_students.SEmail, tbl_students.SName, tbl_students.`Code` FROM tbl_students WHERE tbl_students.`Code` = '$code'  ";
$user = $db->get_row($sql);
 return array('error'=>-1,'row'=>$user);

}else{
return array('error'=>1,'msg'=>'Username password not correct');
}
}
 

/**
      * @url POST professor_login
      * @url GET what/ever/you/want
     */
 function professor_login(){
  global $db;
 
 

  $Email = trim($db->escape($_POST['Email']));
 $Password = trim($db->escape($_POST['Password']));
  
  if ($Email==""){
return array('error'=>1,'msg'=>'Email is required');

 }
 if ($Password==""){
return array('error'=>1,'msg'=>'Password is required');

 }


   $sql = "SELECT *  FROM tbl_professor  WHERE tbl_professor.SEmail = '$Email' and tbl_professor.SPassword = '$Password'  ";
$user = $db->get_row($sql);
 
if ( $db->num_rows==1){
  $userID = $user->ProfessorID;
  $code =time() .rand(2000);
   $code = sha1($code);
   $sql = "update `tbl_professor`  set Code='$code' where ProfessorID = '$userID'"; 
   $updateCode =  $db->query($sql);
      $sql = "SELECT tbl_professor.SEmail, tbl_professor.PName, tbl_professor.`Code` FROM tbl_professor WHERE tbl_professor.`Code` = '$code'  ";
$user = $db->get_row($sql);
 return array('error'=>-1,'row'=>$user);

}else{
return array('error'=>1,'msg'=>'Professor Username password not correct');
}
}
 




 /**
      * @url POST student_add
      * @url GET what/ever/you/want
     */
 function student_add(){
  global $db;
 
 

 $SName = trim($db->escape($_POST['Name']));
 $Email = trim($db->escape($_POST['Email']));
 $Password = trim($db->escape($_POST['Password']));
  
 if ($SName==""){
return array('error'=>1,'msg'=>'Name is required');

 }
 if ($Email==""){
return array('error'=>1,'msg'=>'Email is required');

 }
 if ($Password==""){
return array('error'=>1,'msg'=>'Password is required');

 }


   $sql = "SELECT *  FROM tbl_students  WHERE tbl_students.SEmail = '$Email'  ";
$user = $db->get_row($sql);

if ( $db->num_rows==0){
     $sql = "INSERT INTO `tbl_students` ( `SName` ,  `SEmail`,`SPassword` ) VALUES(  '$SName' ,  '$Email',  '$Password')"; 
 $course =  $db->query($sql);
 if($course){

   return array('error'=>-1);
 }else{
  return array('error'=>1,'msg'=>'error');
   }

}else{
return array('error'=>1,'msg'=>'Student Already exists');
}
}


 /**
      * @url POST student_enroll
      * @url GET what/ever/you/want
     */
 function student_enroll(){
  global $db; 
 
  $this->validateToken();


 $CourseID = $db->escape($_POST['CourseID']);
 $StudentID = $db->escape($_POST['StudentID']);

 $checkStudent = $this->checkStudent($StudentID);
 $checkCourse = $this->checkCourse($CourseID);



if ($checkStudent['error']==1){
  return $checkStudent;
}

if ($checkCourse['error']==1){
  return $checkCourse;
}

   $sql = "SELECT *  FROM tbl_studentcourses  WHERE StudentID = '$StudentID' and CourseID='$CourseID'  ";
$user = $db->get_row($sql);

if ( $db->num_rows==0){
     $sql = "INSERT INTO `tbl_studentcourses` ( `StudentID` ,  `CourseID` ) VALUES(  '$StudentID' ,  '$CourseID')"; 
 $course =  $db->query($sql);
 if($course){

   return array('error'=>-1,'msg'=>'Student enrolled succesfully');
 }else{
  return array('error'=>1,'msg'=>'error');
   }

}else{
return array('error'=>1,'msg'=>'Student Already exists in this course');
}
}

 /**
      * @url DELETE drop_student
      * @url GET what/ever/you/want
     */
 function drop_student($StudentID,$CourseID){
  global $db; 
  $this->validateToken();
 

 $CourseID = $db->escape($CourseID);
 $StudentID = $db->escape($StudentID);

 $checkStudent = $this->checkStudent($StudentID);
 $checkCourse = $this->checkCourse($CourseID);



if ($checkStudent['error']==1){
  return $checkStudent;
}

if ($checkCourse['error']==1){
  return $checkCourse;
}

   $sql = "SELECT *  FROM tbl_studentcourses  WHERE StudentID = '$StudentID' and CourseID='$CourseID'  ";
$user = $db->get_row($sql);

if ( $db->num_rows==1){
     $sql = "delete from  `tbl_studentcourses` where  `StudentID` ='$StudentID' and   `CourseID` =  '$CourseID'"; 
 $course =  $db->query($sql);
 if($course){

   return array('error'=>-1,'msg'=>'Student dropped succesfully');
 }else{
  return array('error'=>1,'msg'=>'error');
   }

}else{
return array('error'=>1,'msg'=>'Student does not  exists in this course');
}
}

function checkStudent($StudentID){

  global $db;

$sql = "SELECT *  FROM tbl_students  WHERE StudentID = '$StudentID' ";
$user = $db->get_row($sql);

if ( $db->num_rows==0){
return array('error'=>1,'msg'=>'Student ID is invalid');
}else{
  return array('error'=>-1);
}

}

function checkCourse($CourseID){

  global $db;

$sql = "SELECT *  FROM tbl_courses  WHERE CourseID = '$CourseID' ";
$user = $db->get_row($sql);

if ( $db->num_rows==0){
return array('error'=>1,'msg'=>'Course ID is invalid');
}else{
  return array('error'=>-1);
}

}



function validateToken(){

  global $db;

   $Code = $db->escape($_POST['Code']);


$sql = "SELECT Code  FROM tbl_students  WHERE Code = '$Code' ";
$user = $db->get_row($sql);

if ( $db->num_rows==0){
    return true;
/*echo json_encode(array('error'=>1,'msg'=>'Token Code is invalid'));
die();
*/
}else{
  return true;
}

}

/**
      * @url DELETE class_remove
      * @url GET what/ever/you/want
     */
 function class_remove($CourseID){
  global $db;
 
 
 $CourseID = $db->escape($CourseID);
 if ($CourseID==""){

  return array('error'=>1,'msg'=>'Course ID is required');

 }

  $sql = "SELECT *  FROM tbl_courses  WHERE CourseID = '$CourseID'  ";

$checkCourse = $db->get_row($sql);

if ( $db->num_rows==1){
   $sql = "delete from `tbl_courses` where `CourseID`  =  '$CourseID'"; 
 $course =  $db->query($sql);
 if($course){
   return array('error'=>-1,'msg'=>'Course deleted succesfully');
 }else{
  return array('error'=>1,'msg'=>'error');
   }

}else{
return array('error'=>1,'msg'=>'Invalid Course ID');
}
}
 

 
 

function course_list()
{
global $db;
 
$sql = "SELECT tbl_courses.CourseID, tbl_courses.CName AS Course, tbl_professor.PName AS Professor, tbl_courses.ProfessorID FROM tbl_courses INNER JOIN tbl_professor ON tbl_courses.ProfessorID = tbl_professor.ProfessorID AND tbl_courses.ProfessorID = tbl_professor.ProfessorID WHERE tbl_courses.`Status` = 1 ";
$courseList = $db->get_results($sql);
if ($db->num_rows==0){
return array("rows"=>array(),"error"=>1);
}else{
return array("rows"=>$courseList,"error"=>-1);
} 
}
 

function course_students($CourseID)
{
global $db;
  $CourseID = $db->escape($CourseID);

$sql = "SELECT tbl_students.SName, tbl_students.SEmail, tbl_students.`Status`, tbl_studentcourses.CourseID FROM tbl_students INNER JOIN tbl_studentcourses ON tbl_studentcourses.StudentID = tbl_students.StudentID AND tbl_students.StudentID = tbl_studentcourses.StudentID WHERE tbl_students.`Status` = 1 AND tbl_studentcourses.CourseID = '$CourseID' ";
$courseList = $db->get_results($sql);
if ($db->num_rows==0){
return array("rows"=>array(),"error"=>1);
}else{
 
return array("rows"=>$courseList,"error"=>-1);
} 

} 

function studentsInfo($StudentID)
{
global $db;
  $StudentID = $db->escape($StudentID);
 $checkStudent = $this->checkStudent($StudentID);
 


if ($checkStudent['error']==1){
  return $checkStudent;
}
$sql = "SELECT tbl_courses.CName AS Course, tbl_students.SName AS Student, tbl_students.StudentID, tbl_students.SEmail AS Email, tbl_professor.PName AS Professor, tbl_professor.ProfessorID, tbl_courses.CourseID FROM tbl_students INNER JOIN tbl_studentcourses ON tbl_studentcourses.StudentID = tbl_students.StudentID INNER JOIN tbl_courses ON tbl_studentcourses.CourseID = tbl_courses.CourseID INNER JOIN tbl_professor ON tbl_courses.ProfessorID = tbl_professor.ProfessorID WHERE tbl_students.`Status` = 1 AND tbl_students.StudentID = '$StudentID'";
$courseList = $db->get_results($sql);
if ($db->num_rows==0){
return array("rows"=>array(),"error"=>1);
}else{
 
return array("rows"=>$courseList,"error"=>-1);
} 

} 



function course_papers($CourseID)
{
global $db;
  $CourseID = $db->escape($CourseID);
 $checkCourse = $this->checkCourse($CourseID);
 


if ($checkCourse['error']==1){
  return $checkCourse;
}
$sql = "SELECT tbl_papers.PaperID, tbl_courses.CName, tbl_papers.Title, tbl_papers.dt, tbl_papers.`Week`, tbl_papers.Chapter, tbl_professor.PName FROM tbl_papers INNER JOIN tbl_courses ON tbl_papers.CourseID = tbl_courses.CourseID INNER JOIN tbl_professor ON tbl_courses.ProfessorID = tbl_professor.ProfessorID AND tbl_courses.ProfessorID = tbl_professor.ProfessorID WHERE tbl_papers.CourseID = '$CourseID'";
$courseList = $db->get_results($sql);
if ($db->num_rows==0){
return array("rows"=>array(),"error"=>1);
}else{
 
return array("rows"=>$courseList,"error"=>-1);
} 

} 



 /**
      * @url POST create_paper
      * @url GET what/ever/you/want
     */
 function create_paper(){
  global $db; 
 
  $this->validateToken();


 $CourseID = $db->escape($_POST['CourseID']);
 $Title = $db->escape($_POST['Title']);
 $dt = $db->escape($_POST['dt']);
 $dt = date('Y-m-d',strtotime($dt));
 $Week = $db->escape($_POST['Week']);
 $Chapter = $db->escape($_POST['Chapter']);

  $checkCourse = $this->checkCourse($CourseID);



if ($checkCourse['error']==1){
  return $checkCourse;
}
 

     $sql = "SELECT *  FROM tbl_papers  WHERE CourseID = '$CourseID' and `Week`='$Week'  ";
$user = $db->get_row($sql);

if ( $db->num_rows==0){
      $sql = "INSERT INTO `tbl_papers` ( `CourseID` ,  `Title` ,  `dt` ,  `Week` ,  `Chapter` ) VALUES(  '$CourseID' ,  '$Title',  '$dt',  '$Week',  '$Chapter')"; 
 $course =  $db->query($sql);
 if($course){

   return array('error'=>-1,'msg'=>'Paper inserted succesfully');
 }else{
  return array('error'=>1,'msg'=>'error');
   }

}else{
return array('error'=>1,'msg'=>'Paper already exists for this Week');
}
}


   
 


// misc functions 



function formatdate_display($dt,$includetime=true){


if ($dt!=''   && $dt!='0000-00-00' && strtotime($dt)){
if ($includetime){
return  date('d M Y h:i A',strtotime($dt));
}else{
return  date('d M Y',strtotime($dt));
}

}else{
return '';
}

}



function nf($number,$decimalplaces=0){
if ($decimalplaces==2){
return number_format($number,$decimalplaces,".",",");
}else{
$number = round($number);
return number_format($number,0,".",",");
}

}


 

function formatdate_edit($dt){  
 if ($dt!=''   && $dt!='0000-00-00' && strtotime($dt)){
return  date('m/d/Y',strtotime($dt));
}else{
return '';
}
}

function formatdate_db($dt){  
 if ($dt!=''   && $dt!='0000-00-00' && strtotime($dt)){
return  date('Y-m-d',strtotime($dt));
}else{
return '';
}
}

 
  
function commadelimited($arr){

$arrvalues ='';
if (is_array($arr)){
foreach ($arr as $ar){
$arrvalues = $arrvalues . $ar . ",";
}
return $arrvalues = rtrim($arrvalues,",");
}
return '';
}

}
