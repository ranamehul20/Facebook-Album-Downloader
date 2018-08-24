<?php
 require_once 'fbconfig.php';
 $access_token=$_SESSION['fb_access_token'];
if(isset($access_token)) {
        try {
            $response = $fb->get('/me',$access_token);
            $fb->setDefaultAccessToken($_SESSION['fb_access_token']);
            $fb_user = $response->getGraphUser();

            $id=$fb_user['id'];
            $name=$fb_user['name'];
            $str="http://graph.facebook.com/".$id."/picture?type=square";


            //  var_dump($fb_user);
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            echo  'Graph returned an error: ' . $e->getMessage();
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
        }
    }
?>    
    <!DOCTYPE html>
    <html lang="en">

      <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Facebook Album</title>

        <!-- Bootstrap core CSS-->
        <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template-->
        <link href="lib/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

        <!-- Custom styles for this template-->
         
        <link href="assets/css/sb-admin.css" rel="stylesheet">
         <link href="assets/css/mainpage.css" rel="stylesheet">
<style>
* {
  box-sizing: border-box;
}

/* Position the image container (needed to position the left and right arrows) */
.container {
  position: relative;
}

/* Hide the images by default */
.mySlides {
  display: none;
}

/* Add a pointer when hovering over the thumbnail images */
.cursor {
  cursor: pointer;
}

/* Next & previous buttons */
.prev,
.next {
  cursor: pointer;
  position: absolute;
  top: 24%;
  width: auto;
  padding: 16px;
  margin-top: -50px;
  color: white;
  font-weight: bold;
  font-size: 20px;
  border-radius: 0 3px 3px 0;
  user-select: none;
  -webkit-user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 16px;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover,
.next:hover {
  background-color: rgba(0, 0, 0, 0.8);
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

/* Container for image text */
.caption-container {
  text-align: center;
  background-color: #222;
  padding: 2px 16px;
  color: white;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Six columns side by side */
.column {
  float: left;
  width: 16.66%;
}

/* Add a transparency effect for thumnbail images */
.demo {
  opacity: 0.6;
}

.active,
.demo:hover {
  opacity: 1;
}
</style>

      </head>

      <body id="page-top">

        <div id="wrapper">

          <!-- Sidebar -->

          <div id="content-wrapper">

            <div class="container-fluid">
<!---slide show -->                
<div class="container">
<?php $uid=$_GET['useralbumid'];
if (isset($_GET['useralbumid'])) {
    $url="https://graph.facebook.com/v3.1/".$uid."/photos?fields=images%2Calbum&access_token=".$access_token;
    $pic=file_get_contents($url);
    $pictures=json_decode($pic);
    $url1=$url;
    $page=(array)$pictures->paging;
    //print_r($page);
    do{
        
            foreach($pictures->data as $my)
            {

?>
  <div class="mySlides">
    <div class="numbertext"><?php //echo $cnt.'/'.$total; ?></div>
    <img src="<?php echo $my->images[0]->source; //echo $photo['picture']; ?>" style="width:100%;
  vertical-align: middle;">
  </div>
    
  <a class="prev" onclick="plusSlides(-1)">❮</a>
  <a class="next" onclick="plusSlides(1)">❯</a>
<?php 
}
        if(array_key_exists("next",$page)){
            $url=$page["next"];
            $pic=file_get_contents($url);
            $pictures=json_decode($pic);
            $page=(array)$pictures->paging;
           
        }
        else
        {
            $url='none';       
        }
        
    }while($url!='none');
} 
else 
{
    header("location:./");
}
    ?>

<!--
  <div class="row">
<?php// $i=0;
      //foreach($photos as $photo){  
            //$i++; ?>
    <div class="column">
      <img class="demo cursor" src="<?php //echo $photo['picture']; ?>" style="width:100%;vertical-align: middle;" onclick="currentSlide(<?php //echo $i; ?>)">
    </div>
<?php //} ?>
      </div> -->
</div>
            </div> 
          </div>
          <!-- /.content-wrapper -->
          </div>
        <!-- /#wrapper -->
<script>
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");
  var captionText = document.getElementById("caption");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
  captionText.innerHTML = dots[slideIndex-1].alt;
}
</script>


        <!-- Bootstrap core JavaScript-->
        <script src="lib/jquery/jquery.min.js"></script>
        <script src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="lib/jquery-easing/jquery.easing.min.js"></script>

        <!-- Page level plugin JavaScript-->
        <script src="lib/chart.js/Chart.min.js"></script>
        <script src="lib/datatables/jquery.dataTables.js"></script>
        <script src="lib/datatables/dataTables.bootstrap4.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="assets/js/sb-admin.min.js"></script>

        <!-- Demo scripts for this page-->
        <script src="assets/js/demo/datatables-demo.js"></script>
        <script src="assets/js/demo/chart-area-demo.js"></script>
        <script type="text/javascript">
      </body>

    </html>