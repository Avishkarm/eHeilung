
  <?php


session_start();
require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php");
require_once("../../models/userModel.php");

//database connection handling
  $conn = createDbConnection($servername, $username, $password, $dbname);
  $returnArr=array();
  if(noError($conn)){
    $conn = $conn["errMsg"];
  }
  else{
    printArr("Database Error");
    exit;
  }

$user = "";
if(isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)){
  $user = $_SESSION["user"];
  $user_type=$_SESSION["user_type"];

  $userInfo = getUserInfoWithUserType($user,$user_type,$conn);
  if(noError($userInfo)){
    $userInfo = $userInfo["errMsg"];  
  
  } else {
    printArr("Error fetching user info".$userInfo["errMsg"]);
    exit;
  }
} else {
   $redirectURL ="../../index.php?luser=doctor";
  header("Location:".$redirectURL); 
  exit;
}
$title="KnowledgeCenter";
$activeHeader="knowledge_center";
$search=$_GET['search'];
$id=$_GET['id'];

$blogurl="https://eheilung.com/ehblog";


function httpGet($url)
{
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//  curl_setopt($ch,CURLOPT_HEADER, false); 
 
    $output=curl_exec($ch);
 
    curl_close($ch);

//    print_r(curl_strerror(curl_errno($ch)));
    return $output;
}
$category = httpGet($blogurl."/?json=get_category_index");
$category = json_decode($category, TRUE);
/*echo "<pre>";
print_r($category);
echo "</pre>";*/
$getPost = httpGet($blogurl."/?json=get_post&id=".$id);
$getPost = json_decode($getPost, TRUE);

/*echo "<pre>";
print_r($getPost);
echo "</pre>";*/

$baseurl="http://www.hansinfotech.in/staging/eheilung/views/KnowledgeCenter/post.php?id=".$id;

/*echo "<pre>";
print_r($_SERVER);
echo "</pre>";*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once("../metaInclude.php"); ?>
  <style type="text/css">
      .topborder{
        border-top:1px solid grey;
      }
      .bottomborder{
        border-bottom:1px solid grey;
      }
      .search{
         margin-bottom: 30px;
         margin-top: 15%;
         padding: 0;
      }
      .has-feedback{
          border-bottom: 1px solid grey;
          padding-bottom: 20px;
          font-size: 19px;
      }
      .form-control-feedback{
        top: 0;
        color: grey;
        font-size: 30px;
        font-weight: 100;
        padding-right: 10px;
      }
      .blogtype  {
        border-bottom: 1px solid grey;
        padding: 10px 0;
        font-size: 19px;
        color: #666;
        display: inline-block;
        margin-right: 10%;
        padding-bottom: 0;
        letter-spacing: 3px;
        font-weight: 600;
      }
      @media(max-width: 477px){
        .blogtype{
        font-size: 12px!important; 
        margin-right: 2%!important;   
        }
        .blogtype-head ul{
          padding:0;
        }
      }
      @media(max-width: 661px){
        .blogtype{
        font-size: 17px; 
        margin-right: 5%;   
        }
        .blogtype-head ul{
          padding:0;
        }
      }
       .blogtype a{
        color: #666;
       }
      .bullet{
        margin-right: 15px;margin-left: 15px;display:inline-block; background-color:#ff9900;border: 1px solid #ff9900;width:10px;height:10px; border-radius: 50%
      }
      .text{
        margin-top: 30px;
        font-size: 18px;
        color: #666;
        line-height: 26px;
        letter-spacing: 1px;
      }
      .blogDetail {
        margin-top: 8%;
        margin-bottom: 8%;
      }
      .blogDetail h1{
        letter-spacing: 2px;
        font-weight: 700;
        word-spacing: 1px;
        color: #555;
        text-align: center;
        margin-top:40px;
        margin-bottom:20px;
      }
      .blogtype-head{
        margin-top: 3%;
      }
      .blogDetail .post-img{
        max-height: 600px;
        object-fit: cover;
        width:100%;
      } 
      .blogDetail video{
        max-height: 600px;
        object-fit: initial;
        width:100%;
        border: 1px solid lightgrey;
      } 
      .social-icon ul {
        overflow: auto;
        padding: 0;
      }

      .social-icon ul li {
        list-style-type: none;
        float: left;
      }
      .social-icon ul li a i {
        color: #666;
        width: 50px;
        font-size: 30px;
        text-align: center;
        margin-right: 15px;
        padding-top: 25px;
      }
      @media screen and (max-width: 525px) {
       .form-control-feedback {
          font-size: 18px !important;
        }
          .has-feedback input{
            font-size: 12px!important;
          }
      }
      .active-category
      {
        border-bottom:1px solid #ff9900;
       /* background-color: #f9daab;
    border-bottom: none;
    padding: 10px;*/
      }
  </style> 
</head>
  <body> 
  <main class="container" style="min-height: 100%;">
  <?php  include_once("../header.php"); ?> 
    <section>
      <!-- main-container-->
      <div class="main-container">
        <div class="row">
          <div class="col-md-12">
            <div class="topborder"></div>
          </div>
        </div>
       <!--  <div class="row">
          <div class="col-md-12">
            <div class="col-md-8 col-sm-8 col-xs-6">            
            </div>
            <div class="col-md-4 col-sm-4 col-xs-6" style="padding:0">
              <div class="col-md-12 search">
                <form action="" class="search-form">
                  <div class="form-group has-feedback">
                    <label for="search" class="sr-only">Search for something</label>
                    <input type="text" class="" name="search" id="search" placeholder="Search for something" style="border:0;outline:0;">
                      <span class="form-control-feedback"><p class="fa fa-search" aria-hidden="true"></p></span>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div> -->
        <div class="row">
          <div class="col-md-12 blogtype-head">
            <ul style="list-style-type: none;text-align: center;">
            <?php foreach ($category['categories'] as $key => $value) { 
              $categorytitle=$value['id'];
             ?>
              <li class="blogtype <?php echo $active ;?>"><a href="article.php?category=<?php echo $categorytitle ;?>"><?php echo strtoupper($value['title']);?></a></li><!-- 
              <li class="blogtype">Videos<span style="float: right;">5</span></li>
              <li class="blogtype">Presentations<span style="float: right;">2</span></li
              <li class="blogtype">Webinars<span style="float: right;">0</span></li> -->
              <?php } ?>
            </ul>
          </div> 
        </div>
        <div class="row">
            <div class="col-md-12 blogDetail">
              <?php 
                        if($getPost['post']['thumbnail_images']['full']['url']==""){
                         $imgsrc=$getPost['post']['attachments'][0]['url'];
                        }else{
                           $imgsrc=$getPost['post']['thumbnail_images']['full']['url'];
                        }

                      ?>
                      <div class="col-md-12" style="margin-bottom: 7%;">
                       <div class="">
                        <?php if($getPost['post']['categories'][0]['title']=='Videos' || $getPost['post']['categories'][0]['slug']=='featured-video' ){
                          if(!empty($getPost['post']['attachments'])){
                          foreach ($getPost['post']['attachments'] as $key1 => $value1) {
                          $mime_type=substr($value1['mime_type'], 0,5);
                          if($mime_type=='video'){?>
                          <video controls src="<?php echo $value1['url'] ;?>"></video>
                        <?php break; }}}else{ ?>
                            <img  class="img-responsive post-img"  src="<?php echo $imgsrc;?>">
                         <?php  }} else{ ?>
                          <img  class="img-responsive post-img"  src="<?php echo $imgsrc;?>">
                        <?php } ?>
                      </div> 
                      <div class="text-center">
                        <a href="post.php?id=<?php echo $getPost['post']['id'];?>"><h1><?php echo $getPost['post']['title'] ;?></h1></a>
                      </div>
                      <div class="text-center">
                        <li style="display:inline-block;color:#ddd;"><?php echo date("F j, Y", strtotime($getPost['post']['date']));?></li>
                        <?php if($getPost['post']['categories'][0]['title']!="" && $getPost['post']['categories'][0]['title']!="Uncategorized"){ ?>
                        <li class="bullet" style=""></li><!-- <li style="display:inline-block;color:#ddd;">3 comments</li><li class="bullet" style="display:inline-block;color:#ddd;"> </li>--><li style="display:inline-block;color:#ddd;"><?php echo $getPost['post']['categories'][0]['title'] ;?></li><?php } ?>
                      </div>
                      <div class="text" style="margin-bottom:2%;">
                       <p class=""><?php echo $getPost['post']['content'];?></p>
                      </div>
                      </div>
              <?php  ?>
               
            </div> 
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="bottomborder"></div>
          </div>
        </div>
        <div class="row social-icon">
          <div class="col-md-12">
            <ul style="">
              <li><a href="http://www.facebook.com/sharer.php?u=<?php echo $baseurl; ?>" title="Share on Facebook." onclick="javascript:window.open(this.href,
                  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank"><i class="fa fa-facebook"></i></a></li>
              <li><a href="https://twitter.com/share?url=<?php echo $baseurl; ?>" onclick="javascript:window.open(this.href,
                  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"  target="_blank"><i class="fa fa-twitter"></i></a></li>
              <li><a href="https://plus.google.com/share?url=<?php echo $baseurl; ?>" onclick="javascript:window.open(this.href,
                  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank"><i class="fa fa-google-plus"></i></a></li>
              <li><a href="http://pinterest.com/pin/create/button/?url=<?php echo $baseurl; ?>" onclick="javascript:window.open(this.href,
                  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank"><i class="fa fa-pinterest-p"></i></a></li>
            </ul>
          </div>
        </div>


      </div>
      <!-- main-container-->
    </section>  
  </main> 
  <?php include("../modals.php"); ?>
  <?php include('../footer.php'); ?>
  <!-- footer-->
  <script type="text/javascript">
  </script>
  </body>
  </html>

<!-- <div class="">
                        <?php if($getPost['post']['categories'][0]['title']=='Videos'){
                          foreach ($getPost['post']['attachments'] as $key1 => $value1) {
                          $mime_type=substr($value1['mime_type'], 0,5);
                          if($mime_type=='video'){?>
                          <video controls src="<?php echo $value1['url'] ;?>"></video>
                        <?php break; }}} else{ ?>
                          <img  class="img-responsive post-img"  src="<?php echo $imgsrc;?>">
                        <?php } ?>
                      </div>  -->



<!-- 
if($getPost['post']['categories'][0]['slug']=='videos' || $getPost['post']['categories'][0]['slug']=='featured-video') -->

<!-- 
<div class="">
                        <?php 
                        if(!empty($getPost['post']['attachments'])){
                          foreach ($getPost['post']['attachments'] as $key1 => $value1) {
                          $mime_type=substr($value1['mime_type'], 0,5);
                          if($mime_type=='video'){?>
                          <video controls src="<?php echo $value1['url'] ;?>"></video>
                        <?php break; }else{ ?>
                          <img  class="img-responsive post-img"  src="<?php echo $imgsrc;?>">
                        <?php } }}else{?>
                        <img  class="img-responsive post-img"  src="<?php echo $imgsrc;?>">
                        <?php }?>
                      </div>  -->

