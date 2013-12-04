<?php
$load = '../../../../wp-config.php';
if (file_exists($load)){  //if it's >WP-2.6
  require_once($load);
  }
  else {
 wp_die('Error: Config file not found');
 }

$action = $_GET['img']; 
?><head>
  
   <link href="style/style.css" rel="stylesheet" type="text/css" />
   
<script language="javascript" type="text/javascript">
<!--
function toggle(o){
var e = document.getElementById(o);
e.style.display = (e.style.display == 'none') ? 'block' : 'none';
}

function goform()
{
	  if(document.forms.ajaxupload.myfile.value==""){
	  alert('Please choose an image');
	  return;
	  }

  document.ajaxupload.submit();
}
function goUpload(){

	  if(document.forms.ajaxupload.myfile.value==""){
	  return;
	  }

	  	
      document.getElementById('f1_upload_process').style.visibility = 'visible';
	  document.getElementById('f1_upload_process').style.display = '';
	  //document.getElementById('f1_upload_success').style.display = 'none';
      //document.getElementById('f1_upload_form').style.visibility = 'hidden';
      return true;
}

function noUpload(success, path, imgNumb){
      var result = '';
      if (success == 1){
         document.getElementById('f1_upload_process').style.display = 'none';
		  var theImage = parent.document.getElementById(imgNumb);
		  var  attachment_files = theImage.value;
		  var span_string = '';
		  var i=0;
		  var path1 = '';
		  if(attachment_files != '')
		  {
		  	var my_array=attachment_files.split(",");
			for(i=0;i<my_array.length;i++)
			{
				span_string = span_string + '<li id="li_'+i+'"> '+ my_array[i] +' [ <a href="javascript:void(0);remove_attachment(\''+i+'\');">remove</a> ] </li>';
				if(path1!='')
				{
					path1 = path1 + ',' + my_array[i];
				}else
				{
					path1 = my_array[i];
				}
			}
		  }
		  if(path1!='')
		  {
		   	path1 = path1 + ',' + path;
		  }else
		  {
		  	path1 = path;
		  }
		   theImage.value = path1;
		   span_string = span_string + '<li id="li_'+i+'"> '+ path +' [ <a href="javascript:void(0);remove_attachment(\''+i+'\');">remove</a> ] </li>';
		   
		  	parent.document.getElementById('attachmentfile_p_id').innerHTML = span_string;
		   document.getElementById('myfile').value = '';
		  // document.getElementById('f1_upload_success').style.display = '';
		   
          //parent.toggle(imgNumb + "_div");
         // parent.reloadFrame(imgNumb + "frame");
         // document.getElementById('f1_upload_form').style.display = 'none';  
          }
      else { 
          document.getElementById('f1_upload_process').style.display = 'none';
		  document.getElementById('f1_upload_form').style.display = 'none'; 
          document.getElementById('no_upload_form').style.display = '';
      }
      return true;     
}
//-->
</script>   
</head>



<style>

#upload_target
{
	 width:				100%;
	 height:			80px;
	 text-align: 		center;
	 border:			none;
	 background-color: 	#642864;	
	 margin:			0px auto;
}

</style>

 


<body>
                <form name="ajaxupload" action="<?php echo "upload.php?img=".$action."&nonce=".$_GET['nonce']; ?>" method="post" enctype="multipart/form-data" target="upload_target" onSubmit="goUpload();" >
                     <p id="f1_upload_process" style="margin-top: 20px;">Uploading Please wait ...<br/><img src="loader.gif" /><br/></p>
                      <div id="f1_upload_form" align="left"><!--Select Image You want to upload:-->
                         <table border="0" cellpadding="0" cellspacing="0"><tr><td><label><input name="myfile" id="myfile" class="textboxStyled" type="file" size="40" onChange="goform();goUpload();" tabindex="2" /></label>
                        <!-- <p id="f1_upload_success" style="display:none; font-weight:bold;"></p>-->
                         </td><td><!--<a href="javascript: goform()" onClick="goUpload();" tabindex="2"><input type="button"; name="Upload" value="Upload"></a>--></td></tr></table>
                     </div>
                     
                     <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0; border:5; background:#fff;" ></iframe>
                 </form>
                 <div id="yesupload" style="display: none;"><center><?php echo mkt_ADMIN_OPTIONS_UPLOAD_LOGO_SUCCESSFUL; ?></br><a href="#" onlcick="reload(<?php echo $_GET['img']; ?>)"><?php echo mkt_ADMIN_OPTIONS_UPLOAD_LOGO_DIFFERENT_IMG; ?></a></center></div>
             
</body>   