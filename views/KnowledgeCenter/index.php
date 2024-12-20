
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
$blogurl="https://eheilung.com/ehblog";

$search=$_GET['search'];

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
   // print_r(curl_exec($ch));




    curl_close($ch);
    return $output;
}
 
$data = httpGet($blogurl."/?json=1");
$data = json_decode($data, TRUE);
//echo "<pre>";
//print_r($data);
//echo "</pre>";
foreach ($data['posts'] as $key => $value) {
 /* echo "<pre>";
print_r($value);
echo "</pre>";*/
}
$recentPost = httpGet($blogurl."/?json=get_recent_posts");
$recentPost = json_decode($recentPost, TRUE);
/*echo "<pre>";
print_r($recentPost);
echo "</pre>";*/

/*foreach ($recentPost['posts'] as $key => $value) {
  //print_r($value);
  $i=0;
  if($value['thumbnail_images']['full']['url']==""){
            
 
  echo $i ."--". $value['attachments'][0]['url'] . "<br>";
}
else
{
   echo $i ."--". $value['thumbnail_images']['full']['url']. "<br>";
}
  $i++;

}*/
foreach ($recentPost['posts'] as $key => $value) {
  //print_r($value);
 
  if($value['thumbnail_images']['full']['url']==""){
   
   $imgsrc=$value['attachments'][0]['url'] . "<br>";
  }else{
     $imgsrc=$value['thumbnail_images']['full']['url']. "<br>";
  }

 /* foreach ($value['attachments'] as $key => $value) {
    echo substr($value['mime_type'], 0,5);
  }*/

}


$count=$recentPost['count_total'];
$recentpost_imagediv1="col-md-12";
$recentpost_imagediv2="col-md-6";
$recentpost_imagediv3="col-md-4";
/*if($recentPost['count_total']==1){
  $recentpost_imagediv1="col-md-12";
  $count=1;
}else if($recentPost['count_total']==2){
  $count=2;
  $recentpost_imagediv1="col-md-6";
}else if($recentPost['count_total']==3){
  $count=3;
  $recentpost_imagediv1="col-md-12";
  $recentpost_imagediv2="col-md-6";
}else if($recentPost['count_total']==4){
  $count=4;
  $recentpost_imagediv1="col-md-6";
  $recentpost_imagediv2="col-md-6";
}else if($recentPost['count_total']==5){
  $count=5;
  $recentpost_imagediv1="col-md-6";
  $recentpost_imagediv2="col-md-4";
}*/

$category = httpGet($blogurl."/?json=get_category_index");
$category = json_decode($category, TRUE);
/*echo "<pre>";
print_r($category);
echo "</pre>";*/


$searchResult = httpGet($blogurl."/?json=get_search_results&search=".$search);
$searchResult = json_decode($searchResult, TRUE);
/*echo "<pre>";
print_r($searchResult);
echo "</pre>";*/

/*echo "<pre>";
print_r($_SERVER);
echo "</pre>";*/

/*if($data['count_total']!=0){
  echo "highlight_string(str)";
  }
  else{
    echo "bye";
  }*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once("../metaInclude.php"); ?>
  <style type="text/css">
.OverlayText{
    bottom: 25%;
    color: #fff;
    left: 15%;
    position: absolute;
    width: 70%;
    text-align: center;
}
.row{
}
.banner{
    max-height: 300px;
    min-height: 300px;
    object-fit: cover;
    width:100%;
}
.wordpress-data{
  margin-bottom: 30px;
}
 .wordpress-data video{
    max-height: 295px;
    min-height: 295px;
    width:100%;
    object-fit: initial;
}
a{
  color:#ddc78c;
}
a:hover{
  color:#ddc78c;
  outline: 0;
}
.blogDetail video{
    width:100%;
    object-fit: initial;
    border: 1px solid lightgrey;
}
.blogDetail .post img {
    width:100%;
    object-fit: cover;
    max-height: 400px;
}
.type{
  color:#ff9900;
}
.title{
  font-weight: 300;
  margin-top: 0px;
}
.OverlayMask
{
  background-color: transparent;
  height:250px;
}
.img-overlay{ 
background-color:#000;
height:300px;
margin-top : -300px;
opacity : 0.3;
}
.blogDetail{
  margin-top: 30px;
  margin-bottom: 30px;
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
.search{
   margin-bottom: 30px;
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
.blogtype{
  border-bottom: 1px solid grey;
  padding:10px 0;
  font-size:19px;
}
.blogtype a{
  color:#666;
}
.category{
  color:#ff9900;
  margin-bottom: 40px;
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
.wordpress-data a{
  color:#ffffff!important;
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

@media screen and (max-width: 768px) and (min-width: 992px) {
 .form-control-feedback {
    font-size: 22px !important;
  }
    .has-feedback input{
      font-size: 14px!important;
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


  </style> 

</head>
  <body> 
  <main class="container" style="min-height: 100%;">
  <?php  include_once("../header.php"); ?> 
    <section>
      <!-- main-container-->
      <?php if($data['count_total']!=0){ ?>
      <div class="main-container">
        <div class="row">
            <div class="col-md-12">
             <?php foreach ($recentPost['posts'] as $key => $value) { 
              if($value['thumbnail_images']['full']['url']==""){
   
               $imgsrc=$value['attachments'][0]['url'];
              }else{
                 $imgsrc=$value['thumbnail_images']['full']['url'];
              }
              if($key==0){
              ?>
               <div class="<?php if($count==1 || $count==3){echo $recentpost_imagediv1;}if($count==2 || $count>=5 || $count==4 ){echo $recentpost_imagediv2;}?> wordpress-data">

               <?php if($value['categories'][0]['slug']=='videos' || $value['categories'][0]['slug']=='featured-video'){ 
               if(!empty($value['attachments'])){

                      foreach ($value['attachments'] as $key1 => $value1) {
                        $mime_type=substr($value1['mime_type'], 0,5);
                        if($mime_type=='video'){
                ?>

                  <a href="post.php?id=<?php echo $value['id'];?>"><video controls  src="<?php echo $value1['url'] ;?>"></video></a>

               <?php break; }} }else{ ?> 
               <a href="post.php?id=<?php echo $value['id'];?>"><img  class="banner img-responsive"  src="<?php echo $imgsrc;?>"></a>
               <?php } }else{ ?>

               <a href="post.php?id=<?php echo $value['id'];?>"><img  class="banner img-responsive"  src="<?php echo $imgsrc;?>"></a>
                
               <?php }?>
                <div class="img-overlay"></div>
                <div class="OverlayText">
                  <div class="col-md-12">
                    <h4 class="type"><?php echo $value['categories'][0]['title'] ;?></h4>
                  </div>
                  <div class="col-md-12">
                    <a href="post.php?id=<?php echo $value['id'];?>"><h2 class="title"><?php echo $value['title'] ;?></h2></a>
                  </div>
                </div>
              </div>
             <?php }if($key==1){ ?>
              <div class="<?php if($count==2 || $count==3 || $count==4 || $count>=5 ){echo $recentpost_imagediv2;}?> wordpress-data">

                <?php  if($value['categories'][0]['slug']=='videos' || $value['categories'][0]['slug']=='featured-video'){ 
                if(!empty($value['attachments'])){

                  foreach ($value['attachments'] as $key1 => $value1) {
                        $mime_type=substr($value1['mime_type'], 0,5);
                        if($mime_type=='video'){ ?>
                  <a href="post.php?id=<?php echo $value['id'];?>"><video controls src="<?php echo $value1['url'] ;?>"></video></a>
               <?php break; }}}else{ ?>
               <a href="post.php?id=<?php echo $value['id'];?>"><img  class="banner img-responsive"  src="<?php echo $imgsrc;?>"></a>
               <?php }}else{ ?>

               <a href="post.php?id=<?php echo $value['id'];?>"><img  class="banner img-responsive"  src="<?php echo $imgsrc;?>"></a>

                
               <?php }?>
                <div class="img-overlay"></div>
                <div class="OverlayText">
                  <div class="col-md-12">
                    <h4 class="type"><?php echo $value['categories'][0]['title'] ;?></h4>
                  </div>
                  <div class="col-md-12">
                    <a href="post.php?id=<?php echo $value['id'];?>"><h2 class="title"><?php echo $value['title'] ;?></h2></a>
                  </div>
                </div>
              </div>
               <?php }if(($key>=2 && $key<=4)){ ?>
              <div class="<?php if($count==3 || $count==4){echo $recentpost_imagediv2;}if($count>=5){echo $recentpost_imagediv3;}?> wordpress-data">

              <?php if($value['categories'][0]['slug']=='videos' || $value['categories'][0]['slug']=='featured-video'){ 
               if(!empty($value['attachments'])){

                foreach ($value['attachments'] as $key1 => $value1) {
                        $mime_type=substr($value1['mime_type'], 0,5);
                        if($mime_type=='video'){ ?>
                  <a href="post.php?id=<?php echo $value['id'];?>"><video controls src="<?php echo $value1['url'] ;?>"></video></a>

               <?php break; }}}else{ ?><a href="post.php?id=<?php echo $value['id'];?>"><img  class="banner img-responsive"  src="<?php echo $imgsrc;?>"></a> <?php }}else{ ?>

               <a href="post.php?id=<?php echo $value['id'];?>"><img  class="banner img-responsive"  src="<?php echo $imgsrc;?>"></a>
                
               <?php }?>
               <div class="img-overlay"></div>
                <div class="OverlayText">
                  <div class="col-md-12">
                    <h4 class="type"><?php echo $value['categories'][0]['title'] ;?></h4>
                  </div>
                  <div class="col-md-12">
                    <a href="post.php?id=<?php echo $value['id'];?>"><h2 class="title"><?php echo $value['title'] ;?></h2></a>
                  </div>
                </div>
              </div>
               <?php } }?>
            </div>
          </div>
          <div class="row blogDetail">
            <div class="col-md-12" style="padding-left: 0;padding-right: 0;">
              <div class="col-md-8 col-sm-8 col-xs-12">
                <?php
              if(isset($search) && !empty($search)){
                $searchpaginate="search=".$search."&";
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
                    if($key==$i){ ?>
                      <div class="col-md-12 post">

                        <?php if($value['categories'][0]['slug']=='videos' || $value['categories'][0]['slug']=='featured-video'){ 
                         if(!empty($value['attachments'])){

                        foreach ($value['attachments'] as $key1 => $value1) {
                        $mime_type=substr($value1['mime_type'], 0,5);
                        if($mime_type=='video'){
                          ?>
                          <video controls src="<?php echo $value1['url'] ;?>"></video>

                        <?php break; }}} else{ ?>

                          <img  class="img-responsive"  src="<?php echo $imgsrc;?>">
                        <?php }}else{ ?>
                          <img  class="img-responsive"  src="<?php echo $imgsrc;?>">
                        <?php }?>
                      </div>
                      <div class="col-md-12">
                        <a href="post.php?id=<?php echo $value['id'];?>"><h1><?php echo $value['title'] ;?></h1></a>
                      </div>
                      <div class="col-md-12 text-center">
                        <li style="display:inline-block;color:#ddd;"><?php echo date("F j, Y", strtotime($value['date']));?></li>
                        <?php if($value['categories'][0]['title']!="" && $value['categories'][0]['title']!="Uncategorized"){ ?>
                        <li class="bullet" style=""></li><!-- <li style="display:inline-block;color:#ddd;">3 comments</li><li class="bullet" style="display:inline-block;color:#ddd;"> </li>--><li style="display:inline-block;color:#ddd;"><?php echo $value['categories'][0]['title'] ;?></li><?php } ?>
                      </div>
                      <div class="col-md-12 text" style="margin-bottom: 15%;">
                       <p class=""><?php echo $value['excerpt'];?></p>
                      </div>
                <?php }}} ?>
                <div class="col-md-12 paginate" style="">
                   <div class="col-md-6 col-sm-6 col-xs-6 text-left"><a href="index.php?<?php echo $searchpaginate; ?>pageNo=<?php if($pageNo==1){echo $pageNo;}else{echo $pageNo-1;}?>" style="<?php if($pageNo==1){echo 'display:none';}?>"><img src="../../assets/images/leftarrow.png"  style="padding-right: 10px;" />Older posts</a></div>
                   <div class="col-md-6 col-sm-6 col-xs-6 text-right" ><a href="index.php?<?php echo $searchpaginate; ?>pageNo=<?php if($pageNo==$totalPage){echo $pageNo;}else{echo $pageNo+1;}?>" style="<?php if($pageNo==$totalPage){echo 'display:none';}?>">Newest posts<img src="../../assets/images/rightarrow.png" style="padding-left: 10px;" /></a></div>
                </div> 

                <?php }else{?>
                      <div class="col-md-12" style="margin-top: 5%;margin-bottom: 20%;"><h1>No matched data found!</h1></div>
                <?php }} else{
               foreach ($data['posts'] as $key => $value) {
                if($value['thumbnail_images']['full']['url']==""){
   
                 $imgsrc=$value['attachments'][0]['url'];
                }else{
                   $imgsrc=$value['thumbnail_images']['full']['url'];
                }
                $noOfPostInPage=5; 
                $j=($pageNo-1)*$noOfPostInPage;
                 $totalPage=ceil($data['count_total']/$noOfPostInPage);
                for($i=$j;$i<($j+$noOfPostInPage);$i++){
                    if($key==$i){ ?>
                      <div class="col-md-12 post">

                        <?php if($value['categories'][0]['slug']=='videos' || $value['categories'][0]['slug']=='featured-video'){ 
                        if(!empty($value['attachments'])){

                          foreach ($value['attachments'] as $key1 => $value1) {
                        $mime_type=substr($value1['mime_type'], 0,5);
                        if($mime_type=='video'){
                          ?>
                          <video controls src="<?php echo $value1['url'] ;?>"></video>

                        <?php break; }}} else{ ?>
                           <img  class="img-responsive"  src="<?php echo $imgsrc;?>">
                         <?php }}else{ ?>

                          <img  class="img-responsive"  src="<?php echo $imgsrc;?>">
                        <?php }?>
                      </div>
                      <div class="col-md-12">
                        <a href="post.php?id=<?php echo $value['id'];?>"><h1><?php echo $value['title'] ;?></h1></a>
                      </div>
                      <div class="col-md-12 text-center">
                        <li style="display:inline-block;color:#ddd;"><?php echo date("F j, Y", strtotime($value['date']));?></li>
                        <?php if($value['categories'][0]['title']!="" && $value['categories'][0]['title']!="Uncategorized"){ ?>
                        <li class="bullet" style=""></li><!-- <li style="display:inline-block;color:#ddd;">3 comments</li><li class="bullet" style="display:inline-block;color:#ddd;"> </li>--><li style="display:inline-block;color:#ddd;"><?php echo $value['categories'][0]['title'] ;?></li><?php } ?>
                      </div>
                      <div class="col-md-12 text" style="margin-bottom: 15%;">
                       <p class=""><?php echo $value['excerpt'];?></p>
                      </div>
                <?php }}} ?>
                <div class="col-md-12 paginate" style="">
                   <div class="col-md-6 col-sm-6 col-xs-6 text-left"><a href="index.php?<?php echo $searchpaginate; ?>pageNo=<?php if($pageNo==1){echo $pageNo;}else{echo $pageNo-1;}?>" style="<?php if($pageNo==1){echo 'display:none';}?>"><img src="../../assets/images/leftarrow.png"  style="padding-right: 10px;" />Newest posts</a></div>
                   <div class="col-md-6 col-sm-6 col-xs-6 text-right" ><a href="index.php?<?php echo $searchpaginate; ?>pageNo=<?php if($pageNo==$totalPage){echo $pageNo;}else{echo $pageNo+1;}?>" style="<?php if($pageNo==$totalPage){echo 'display:none';}?>">Older posts<img src="../../assets/images/rightarrow.png" style="padding-left: 10px;" /></a></div>
                </div> 

                <?php }?>
              </div>
              
              <div class="col-md-4 col-sm-4 col-xs-12" >
                <div class="col-md-12 search">
                  <form action="" class="search-form">
                      <div class="form-group has-feedback">
                      <label for="search" class="sr-only">Search for something</label>
                      <input type="text" class="" name="search" id="search" placeholder="Search for something" style="border:0;outline:0;">
                        <span class="form-control-feedback"><p class="glyphicon glyphicon-search" aria-hidden="true"></p></span>
                    </div>
                  </form>
                </div>
                <div class="col-md-12">
                  <h3 class="category text-center" >CATEGORIES</h3>
                  <ul style="list-style-type: none;padding-left: 0px;">
                  <?php foreach ($category['categories'] as $key => $value) { 
                    $category=$value['id'];
                   ?>
                    <li class="blogtype"><a href="article.php?category=<?php echo $category ;?>"><?php echo $value['title'];?><span style="float: right;"><?php echo $value['post_count'];?></span></a></li><!-- 
                    <li class="blogtype">Videos<span style="float: right;">5</span></li>
                    <li class="blogtype">Presentations<span style="float: right;">2</span></li
                    <li class="blogtype">Webinars<span style="float: right;">0</span></li> -->
                    <?php } ?>
                  </ul>
                </div>
              </div>
            </div>  
          </div>
         </div>
         <?php } ?> 
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
