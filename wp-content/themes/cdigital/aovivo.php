<?php
/*
  Template Name: Ao Vivo
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="js" dir="ltr" xml:lang="pt-br" xmlns="http://www.w3.org/1999/xhtml" lang="pt-br"><head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title><?php the_title(); ?> - CulturaDigitalBr</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/i/favicon.ico" type="image/x-icon">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/node.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/defaults.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/system.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/system-menus.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/user.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/content-module.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/date.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/datepicker.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/timeentry.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/fckeditor.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/filefield.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/calendar.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/farbtastic.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/fieldgroup.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/comment.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/html-elements.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/tabs.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/messages.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/block-editing.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/wireframes.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/layout.css">
<link type="text/css" rel="stylesheet" media="print" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/print.css">
<link type="text/css" href="http://culturadigital.br/wp-content/themes/cultura_digital/css/home.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/jquery_004.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/drupal.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/pt-br_33a0afeced0b8bcb9f6d0702d755d4ac.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/script.js"></script>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
jQuery.extend(Drupal.settings, { "basePath": "/" });
//--><!]]>
</script>
	<!--script type="text/javascript" src="/sites/default/themes/cpfl/js/jquery.js"></script-->
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/thickbox.js"></script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/jquery.js"></script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/jquery_002.js"></script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/jquery_003.js"></script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/pluginpage.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/thickbox.css">
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/global.css">

    <script type="text/javascript">

	$(document).ready(function() {

		inicializar();		

		if(($.browser.msie==true && $.browser.version>=7) || $.browser.msie!=true) {

		} else {
						if ($("#main-inner").css("height").replace("px", "") < 580) {
				$("#main-inner").css("height", "580px");
			}
						
			$('#formcalendario .comboboxItem').click(function () {
				document.location="/calendario/" + $("#formcalendario_termos").attr("value") + "/" + $("#formcalendario_ano").attr("value") + "-" + $("#formcalendario_mes").attr("value") + "?mes=" + $("#formcalendario_mes").attr("value") + "&ano=" + $("#formcalendario_ano").attr("value");
			});

		}

	});
    </script>
</head><body class="not-front not-logged-in page-node node-type-aovivo one-sidebar sidebar-left page-aovivo-2009-10-05-eugenio-bucci-humano-descartavel section-aovivo">
    <div id="linksHeader">
        <div class="dia"><?php print __(date('l')).", ".date('j')." de ".__(date('F'))." de ".date('Y'); ?></div> 
            <div class="outrasFuncoes">
            <div class="twitter"><a href="http://twitter.com/CulturaGovBr" target="_blank" title="twitter">Twitter</a></div>
            <div class="rss"><a href="http://culturadigital.br/feed/" title="RSS">RSS</a></div>
        </div>
    </div>
    <div id="cabecalho">
        <h1 class="logo"><a title="Cultura Digital" href="http://culturadigital.br">Cultura Digital</a></h1>
        <div class="estruturaHeader">
            <div class="menu">
                <div>
                    <ul id="nav">
                        <li class="btHome"><a title="home" href="http://culturadigital.br">home</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="breadcrumb">Você está em » Home » <strong>Ao vivo</strong></div>
    
    <div id="page"><div id="page-inner">

    <a name="top" id="navigation-top"></a>
          <div id="skip-to-nav"><a href="#navigation">Skip to Navigation</a></div>
        <div id="header"><div id="header-inner" class="clear-block">
            <div id="logo-title">
            <!--
			<div style="float:left; margin:20px 0 0 44px;"><img src="http://cpflcultura.com.br/live/language3.jpg" alt="choose language" usemap="#Map" />
			<map name="Map" id="Map">
			  <area shape="rect" coords="98,-3,201,74" href="http://cpflcultura.com.br/aovivo/" target="_blank" alt="" />
			<area shape="rect" coords="207,15,272,47" href="http://cpflcultura.com.br/live" target="_blank" alt="" />
			</map> -->
        <div id="site-name">
        	<strong>
                <a href="<?php bloginfo('home'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a>
            </strong>
        </div>
        </div> <!-- /#logo-title -->
      
      
    </div></div> <!-- /#header-inner, /#header -->

    <div id="main"><div id="main-inner" class="clear-block with-navbar">



      <div id="content"><div id="content-inner">

        
        
                  <div id="content-header">
                                      <h1 class="title"><?php the_title(); ?></h1>
                                                          </div> <!-- /#content-header -->
        
        <div id="content-area">
          

<img src="<?php $key="imagem"; echo get_post_meta($post->ID, $key, true); ?>"  alt=" " style="float: right; position: absolute; right: 0pt; top: -70px;" />


<!--<div class="aovivo_menu">
    <ul>
        <li class="comoassistir"><a href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/paginas/como_assistir.htm?height=350&amp;width=600" title="COMO ASSISTIR" class="thickbox"><span>COMO ASSISTIR</span></a></li>
        <li class="sobre"><a href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/paginas/sobre.htm?height=350&amp;width=600" title="SOBRE" class="thickbox"><span>SOBRE</span></a></li>
        <li class="programacao"><a href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/paginas/programacao.htm?height=350&amp;width=600" title="PROGRAMAÇÃO" class="thickbox"><span>PROGRAMAÇÃO</span></a></li>
        <li class="acervo"><a href="http://www.cpflcultura.com.br/site/?s=video" target="_blank" title="ACERVO"><span>ACERVO</span></a></li>
		





		
    </ul>
</div> -->

<div class="aovivo_header">
		<?php if (have_posts()) : ?>
			<?php while(have_posts()) : the_post(); ?>

        <h2><?php the_title(); ?></h2>
        
        <h3>
		<a href="<?php $key="link"; echo get_post_meta($post->ID, $key, true); ?>"><span><?php $key="nomepalestrante"; echo get_post_meta($post->ID, $key, true); ?></span> </a>
		<a href="<?php $key="link"; echo get_post_meta($post->ID, $key, true); ?>" onclick="javascript:window.open(this.href);return false;"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/icon_mais.gif" alt="?"></a>
	</h3>
        <!--<div class="datahora"><?php the_time('j F'); ?> | <?php the_time('g'); ?>h<?php the_time('i'); ?></div>-->
</div>


<div class="canvas_aovivo">

    <div class="video">
	<h4 class="titulo_aovivo"><span class="hidden">Ao vivo</span></h4>

<?php $key="embedvideo"; echo get_post_meta($post->ID, $key, true); ?>


    </div>
    
    <div class="twitter">
	
    
    
    <a href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/paginas/twitter.htm?height=350&amp;width=600" class="thickbox" style="left: 80px; position: absolute; top: 24px;"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/icon_interr_2.gif" alt="?"></a>
    <h4>Twitter</h4>
    
    
    
    
    
        <div class="msgTwitter">
		<ul id="twitter_update_list"><li><span class="imagem"><a href="http://www.twitter.com/culturadigital"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/Aniversrio_F_2008_022_normal.jpg" alt="brunoscarto" title="culturadigital"></a></span><span class="autor"><a href="http://www.twitter.com/culturadigital">brunoscarto</a></span><span class="tweet">texto. <a href="http://www.cpflcultura.com.br/site/">http://www.cpflcultura.com.br/site/</a></span><span class="timestamp"><a href="http://twitter.com/brunoscarto/statuses/5128291117">2 dias atrás</a></span></li></ul>
	</div>
    <div style="float: right;">
        <a href="http://twitter.com/culturadigital" target="_blank" style="font-size: 10px; color: rgb(51, 51, 51);">
        Siga o CulturaDigitalBr <img src="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/icon_twitter.png" alt="@culturadigital">
        </a>
    </div>
    </div>
    
	<div class="oggHtml">
    	<p>Utilizando o navegador <a href="http://download.mozilla.org/?os=win&lang=pt-BR&product=firefox-3.5.5">Firefox 3.5</a> para acessar esse endereço, você terá acesso ao streaming em formato OGG utilizando a nova tag de vídeo do HTML 5.</p>
        <a href="http://download.mozilla.org/?os=win&lang=pt-BR&product=firefox-3.5.5">Download do Mozilla Firefox 3.5</a>
    </div>
    
    <div class="chat">
	<h4>Interaja</h4>
    <a href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/paginas/interaja.htm?height=350&amp;width=600" class="thickbox chat_help"><span>?</span></a>
    <div style="clear: both;"></div>


	<?php the_content(); ?>					
					
					
			<?php endwhile; ?>		
		<?php endif; ?>
	




    </div>
    
    
    <!--<a href="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/paginas/termos.htm?height=350&amp;width=600" class="thickbox" style="position: absolute; bottom: 20px; right: 12px; font-size: 11px; text-decoration: none; color: rgb(51, 51, 51);"><strong>termos de uso</strong></a> -->

</div>

        </div>

        
        
      </div></div> 


      

      
      
    </div></div> 

    
  </div></div> 

	<!--<div style="margin: auto; display: block; clear: both; text-align: center;" id="menuRodape">
    	<p><img src="<?php bloginfo('stylesheet_directory'); ?>/img/aovivo/rodapeInteiro.gif" usemap="#rodape"></p>
    </div>

	<map name="rodape">
	  <area shape="rect" coords="0, 0, 64, 48" href="http://www.cpfl.com.br" target="_blank" alt="CPFL Energia">
	</map> -->


      <div id="closure-blocks" class="region region-closure"><div id="block-menu-menu-menurodape" class="block block-menu region-odd even region-count-1 count-2"><div class="block-inner">

  
  <!--<div class="content">
    <ul class="menu"><li class="leaf first"><a href="<?php print get_page_link(14); ?>" title="Imprensa">Imprensa</a></li>
<li class="leaf"><a href="<?php print get_page_link(16); ?>" title="Dúvidas">Dúvidas</a></li>
<li class="leaf last"><a href="<?php print get_page_link(40); ?>" title="Fale conosco">Fale conosco</a></li>
    </ul>  
  </div> -->

  
</div></div> 
</div>
  

	<script type="text/javascript">
	function twitterCallback2(twitters) {
	  document.getElementById('twitter_update_list').innerHTML = "atualizando...";
	  var statusHTML = [];
	  //for (var i=0; i<twitters.results.length; i++){
		i = 0;
	    var username = twitters.results[i].from_user;
		var image = twitters.results[i].profile_image_url;
	    var status = twitters.results[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
	      return '<a href="'+url+'">'+url+'</a>';
	    }).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
	      return  reply.charAt(0)+'<a href="http://www.twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
	    });
	    statusHTML.push('<li><span class="imagem"><a href="http://www.twitter.com/'+username+'"><img src="'+image+'" alt="'+username+'" title="'+username+'" /></a></span><span class="autor"><a href="http://www.twitter.com/'+username+'">'+username+'</a></span><span class="tweet">'+status+'</span><span class="timestamp"><a href="http://twitter.com/'+username+'/statuses/'+twitters.results[i].id+'">'+relative_time(twitters.results[i].created_at)+'</a></span></li>');
	  //}
	  document.getElementById('twitter_update_list').innerHTML = statusHTML.join('');
	}
	
	function relative_time(time_value) {
	  var values = time_value.split(" ");
	  time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
	  var parsed_date = Date.parse(time_value);
	  var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
	  var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
	  delta = delta + (relative_to.getTimezoneOffset() * 60);
	
	  if (delta < 60) {
	    return 'menos de um minuto atrás';
	  } else if(delta < 120) {
	    return 'cerca de um minuto atrás';
	  } else if(delta < (60*60)) {
	    return (parseInt(delta / 60)).toString() + ' minutos atrás';
	  } else if(delta < (120*60)) {
	    return 'cerca de uma hora atrás';
	  } else if(delta < (24*60*60)) {
	    return 'cerca de ' + (parseInt(delta / 3600)).toString() + ' horas atrás';
	  } else if(delta < (48*60*60)) {
	    return '1 dia atrás';
	  } else {
	    return (parseInt(delta / 86400)).toString() + ' dias atrás';
	  }

	}

function atualizatimeline() {
	var ttdata = document.createElement("script");
	ttdata.src = "http://search.twitter.com/search.json?callback=twitterCallback2&q=culturadigital OR culturadigitalbr&amp;my_count=1&amp;rtt=1&amp;suppress_response_codes";
	document.body.appendChild(ttdata);
}

atualizatimeline();

$(document).ready(function () {
	setInterval(atualizatimeline, 5000);
});
</script>
</body></html>