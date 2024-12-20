
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

$blogurl="https://eheilung.com/ehblog";

$categoryId=$_GET['category'];


if($_GET['pageNo']=="" || $_GET['pageNo']==0){
  $pageNo=1;
}else{
  $pageNo=$_GET['pageNo'];
}

function httpGet($url)
{
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//  curl_setopt($ch,CURLOPT_HEADER, false); 
 
    $output=curl_exec($ch);
 
    curl_close($ch);

    //print_r($output);
    return $output;
}
 
$data = httpGet($blogurl."/?json=1");
$data = json_decode($data, TRUE);
/*echo "<pre>";
//print_r($data);
echo "</pre>";*/
foreach ($data['posts'] as $key => $value) {
  /*echo "<pre>";
print_r($value);
echo "</pre>";*/
}
$recentPost = httpGet($blogurl."/?json=get_recent_posts");
$recentPost = json_decode($recentPost, TRUE);
/*echo "<pre>";
print_r($recentPost);
echo "</pre>";*/

foreach ($recentPost['posts'] as $key => $value) {
  //print_r($value);
}

$category = httpGet($blogurl."/?json=get_category_index");
$category = json_decode($category, TRUE);
/*echo "<pre>";
print_r($category);
echo "</pre>";*/

$categoryPost = httpGet($blogurl."/?json=get_category_posts&id=".$categoryId);
$categoryPost = json_decode($categoryPost, TRUE);
/*echo "<pre>";
print_r($categoryPost);
echo "</pre>";*/

$searchResult = httpGet($blogurl."/?json=get_search_results&search=".$search);
$searchResult = json_decode($searchResult, TRUE);
/*echo "<pre>";
print_r($searchResult);
echo "</pre>";*/


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
      a{
        color:#ddc78c;
      }
      a:hover{
        color: #ddc78c;
        outline: 0;
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
      .link-more a{
        color:#ddc78b!important;
      }
       .link-more {
        margin-top: 5%;
      }
      .paginate a{
        color: #ddc78b;
        font-size: 25px;
        letter-spacing: 1px;
      }
      .paginate a i:before {
      content: "\2190";
      font-size: 70px;
      margin-top: 20px;
      }
      .paginate .col-md-6{
        padding:0;
      }
      .paginate{
        margin-bottom: 50px;
      }

      
      @media screen and (max-width: 525px) {
       .form-control-feedback {
          font-size: 18px !important;
        }
          .has-feedback input{
            font-size: 12px!important;
          }
      }
      @media(max-width: 786px){
       .paginate a{
          font-size: 19px;
      }
        .paginate img{
         width:40px;
         height:19px;
        }
      }
      @media(max-width: 435px){
        .paginate a{
          font-size: 15px;
      }
      .paginate img{
         width:35px!important;
         height:15px!important;
        }
      }
      @media(max-width: 320px){
       .paginate a{
          font-size: 14px;
      }
        .paginate img{
         width:25px!important;
         height:14px!important;
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
        <div class="row">
          <div class="col-md-12">
            <div class="col-md-8 col-sm-8 col-xs-6">            
            </div>
            <div class="col-md-4 col-sm-4 col-xs-7" style="float: right;padding:0">
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
        </div>
        <div class="row">
          <div class="col-md-12 blogtype-head">
            <ul style="list-style-type: none;text-align: center;">
            <?php foreach ($category['categories'] as $key => $value) { 
              $categorytitle=$value['id'];
              if($categoryId==$value['id'])
              {
                $active="active-category";
              }
              else
              {
                $active="";
              }
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
              <?php if(isset($search) && !empty($search)){
                if($searchResult['count_total']!=0){
                      foreach ($searchResult['posts'] as $key => $value) { 
                        if($value['thumbnail_images']['full']['url']==""){
                         $imgsrc=$value['attachments'][0]['url'];
                        }else{
                           $imgsrc=$value['thumbnail_images']['full']['url'];
                        }
                        $noOfPostInPage=5; 
                        $j=($pageNo-1)*$noOfPostInPage;
                         $totalPage=ceil($searchResult['count_total']/$noOfPostInPage);
                        for($i=$j;$i<($j+$noOfPostInPage);$i++){
                            if($key==$i){ 
                      ?>
                      <div class="col-md-12" style="margin-bottom: 7%;">
                      <div class="">

                        <?php if($value['categories'][0]['slug']=='videos' || $value['categories'][0]['slug']=='featured-video'){
                        if(!empty($value['attachments'])){

                          foreach ($value['attachments'] as $key2 => $value2) {
                          $mime_type=substr($value2['mime_type'], 0,5);
                          if($mime_type=='video'){
                          ?>
                          <video controls src="<?php echo $value2['url'] ;?>"></video>

                        <?php break; }}}else{ ?>
                           <img  class="img-responsive post-img"  src="<?php echo $imgsrc;?>">
                          <?php
                          }}else{ ?>

                          <img  class="img-responsive post-img"  src="<?php echo $imgsrc;?>">
                        <?php } ?>
                      </div>
                      <div class="text-center">
                        <a href="post.php?id=<?php echo $value['id'];?>"><h1><?php echo $value['title'] ;?></h1></a>
                      </div>
                      <div class="text-center">
                        <li style="display:inline-block;color:#ddd;"><?php echo date("F j, Y", strtotime($value['date']));?></li><li class="bullet" style=""></li><!-- <li style="display:inline-block;color:#ddd;">3 comments</li><li class="bullet" style="display:inline-block;color:#ddd;"> </li>--><li style="display:inline-block;color:#ddd;"><?php echo $value1['title'] ;?></li>
                      </div>
                      <div class="text" style="margin-bottom:2%;">
                       <p class=""><?php echo $value['excerpt'];?></p>
                      </div>
                      </div>
              <?php }}} //}} ?>
                <div class="col-md-12 paginate" style="">
                   <div class="col-md-6 col-sm-6 col-xs-6 text-left"><a href="article.php?category=<?php echo $categoryId ;?>&<?php echo $searchpaginate; ?>pageNo=<?php if($pageNo==1){echo $pageNo;}else{echo $pageNo-1;}?>" style="<?php if($pageNo==1){echo 'display:none';}?>"><img src="../../assets/images/leftarrow.png"  style="padding-right: 10px;" />Newest posts</a></div>
                   <div class="col-md-6 col-sm-6 col-xs-6 text-right" ><a href="article.php?category=<?php echo $categoryId ;?>&<?php echo $searchpaginate; ?>pageNo=<?php if($pageNo==$totalPage){echo $pageNo;}else{echo $pageNo+1;}?>" style="<?php if($pageNo==$totalPage){echo 'display:none';}?>">Older posts<img src="../../assets/images/rightarrow.png" style="padding-left: 10px;" /></a></div>
                </div> 

                <?php }else{?>
                          <div class="col-md-12" style="margin-top: 5%;margin-bottom: 20%;"><h1>No matched data found!</h1></div>
               <?php }}else{
                      foreach ($categoryPost['posts'] as $key => $value) {
                        //echo "<br>".$key."<br><br>  ";
                        //echo "1<br>"; 
                        if($value['thumbnail_images']['full']['url']==""){
                         $imgsrc=$value['attachments'][0]['url'];
                        }else{
                           $imgsrc=$value['thumbnail_images']['full']['url'];
                        }

                        $noOfPostInPage=5; 

                        $j=($pageNo-1)*$noOfPostInPage;
                         $totalPage=ceil($categoryPost['count']/$noOfPostInPage);
                        for($i=$j;$i<($j+$noOfPostInPage);$i++){
                            if($key==$i){ ?>

                                <div class="col-md-12" style="margin-bottom: 7%;">
                                <div class="">
                                <?php if($categoryPost['category']['slug']=='featured-video' || $categoryPost['category']['slug']=='videos') {
                                if(!empty($value['attachments'])){
                                        foreach ($value['attachments'] as $key2 => $value2) {
                                          $mime_type=substr($value2['mime_type'], 0,5);
                                          if($mime_type=='video'){ ?>
                                            <video controls src="<?php echo $value2['url'] ;?>"></video>

                                <?php    break;
                                         } }}else{ ?>
                                         <img  class="img-responsive post-img"  src="<?php echo $imgsrc;?>">
                                          <?php 
                                          }}  else{ ?>
                                    <img  class="img-responsive post-img"  src="<?php echo $imgsrc;?>">
                                  <?php } ?>
                                </div>  
                                <div class="text-center">
                                  <a href="post.php?id=<?php echo $value['id'];?>"><h1><?php echo $value['title'] ;?></h1></a>
                                </div>
                                <div class="text-center">
                                  <li style="display:inline-block;color:#ddd;"><?php echo date("F j, Y", strtotime($value['date']));?></li>
                                  <li class="bullet" style=""></li><!-- <li style="display:inline-block;color:#ddd;">3 comments</li><li class="bullet" style="display:inline-block;color:#ddd;"> </li>--><li style="display:inline-block;color:#ddd;"><?php echo $categoryPost['category']['title'] ;?></li>
                                </div>
                                <div class="text" style="margin-bottom:2%;">
                                 <p class=""><?php echo $value['excerpt'];?></p>
                                </div>
                                </div>
                              <?php

                      }}}?>
                <div class="col-md-12 paginate" style="">
                   <div class="col-md-6 col-sm-6 col-xs-6 text-left"><a href="article.php?category=<?php echo $categoryId ;?>&<?php echo $searchpaginate; ?>pageNo=<?php if($pageNo==1){echo $pageNo;}else{echo $pageNo-1;}?>" style="<?php if($pageNo==1){echo 'display:none';}?>"><img src="../../assets/images/leftarrow.png"  style="padding-right: 10px;" />Newest posts</a></div>
                   <div class="col-md-6 col-sm-6 col-xs-6 text-right" ><a href="article.php?category=<?php echo $categoryId ;?>&<?php echo $searchpaginate; ?>pageNo=<?php if($pageNo==$totalPage){echo $pageNo;}else{echo $pageNo+1;}?>" style="<?php if($pageNo==$totalPage){echo 'display:none';}?>">Older posts<img src="../../assets/images/rightarrow.png" style="padding-left: 10px;" /></a></div>
                </div> 

                <?php }?>
            </div> 
        </div>
      <!--   <div class="row">
          <div class="col-md-12">
            <div class="bottomborder"></div>
          </div>
        </div>
        <div class="row social-icon">
          <div class="col-md-12">
            <ul style="">
              <li><a href=""><i class="fa fa-facebook"></i></a></li>
              <li><a href=""><i class="fa fa-twitter"></i></a></li>
              <li><a href=""><i class="fa fa-google-plus"></i></a></li>
              <li><a href=""><i class="fa fa-pinterest-p"></i></a></li>
            </ul>
          </div>
        </div> -->
      </div>
      <!-- main-container-->
    </section>
  </main>
  <?php include("../modals.php"); ?> 
  <?php include('../footer.php'); ?>
  <!-- footer-->
  <script type="text/javascript">
  /*$('#search').keyup(function(){
    filterorder();
  });*/
   $(".more-link").each(function(){
      var href=$(this).attr('href');
      var postId=href.substring(href.indexOf("="));
      $(this).html("Continue reading <img src='../../assets/images/rightarrow.png' style='width: 45px;height: 20px; padding-left: 15px;' />");
      $(this).attr("href","post.php?id"+postId);

  });
  $('.search-form').submit(function(e){
    var QUERY_STRING="<?php echo $_SERVER['QUERY_STRING'];?>"
    var keyword=$("#search").val();
    //alert(search);
    var res = QUERY_STRING.split('search',1)[0];
    var res = res.split('&',1)[0];
    //alert(res);
    if(QUERY_STRING=="")
    {
      var search='?search='+keyword;
    }
    else
    {
      if(res!=""){
        var search='?'+res+'&search='+keyword;
      }
      else{
        var search='?search='+keyword;
      }
    }

    location.href="<?php echo $_SERVER['PHP_SELF'];?>"+search;
    e.preventDefault();
    
  });
  </script>
  </body>
  </html>



<!-- <div class="">
                        <?php 
                        if(!empty($value['attachments'])){
                          foreach ($value['attachments'] as $key2 => $value2) {
                          $mime_type=substr($value2['mime_type'], 0,5);
                          if($mime_type=='video'){
                          ?>
                          <video controls src="<?php echo $value2['url'] ;?>"></video>
                        <?php break; }else{ ?>
                        <img  class="img-responsive post-img"  src="<?php echo $imgsrc;?>">
                        <?php }}}else{ ?>
                          <img  class="img-responsive post-img"  src="<?php echo $imgsrc;?>">
                        <?php } ?>
                      </div>



                      <div class="">
                                <?php if(!empty($value['attachments'])){
                                        foreach ($value['attachments'] as $key2 => $value2) {
                                          $mime_type=substr($value2['mime_type'], 0,5);
                                          if($mime_type=='video'){ ?>
                                            <video controls src="<?php echo $value2['url'] ;?>"></video>

                                <?php    break;
                                         }else{ ?>
                                          <img  class="img-responsive post-img"  src="<?php echo $imgsrc;?>">
                                         <?php }
                                        }
                                      } else{ ?>
                                    <img  class="img-responsive post-img"  src="<?php echo $imgsrc;?>">
                                  <?php } ?>
                                </div>   -->

