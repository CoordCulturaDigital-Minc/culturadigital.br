<?php

/* 
Plugin Name: Wordpress Thread Comment
Plugin URI: http://blog.2i2j.com/plugins/wordpress-thread-comment
Version: 1.4.9.4
Author: 偶爱偶家
Description: wp thread comment allow user can reply comment and same comment On display, can choose ajax or not. 允许用户回复某个特定的评论并集中显示相似评论, 可以自由选择是否使用ajax.
Author URI: http://blog.2i2j.com/
Donate link:  http://blog.2i2j.com/redir/donate-wpthreadcomment
*/

/*
ChangeLog:

2009-03-13
			1.4.9.4 发布

2009-03-05
			1. 修改js中的一个错误.
			2. 再次增加js的兼容性.

2009-03-04
			1. 兼容有wp_list_comments函数的模板.
			2. 修改js使ajax兼容性更好.
			3. 修改后台html框的大小.

2009-02-26
			1.4.9.3 发布

2009-02-26
			1. 修正initoption移动后的bug.
			2. 增加回复前后的[]可以自行删除和定义.

2009-01-16
			1. 将initoption移到do_action('init')中执行, 才能体现__的效果.

2009-01-15
			1. 删除COOKIEHASH.

2009-01-12
			1. 增加一个css
			2. 修改了一个单词的错误.

2008-12-31
			1. 删除 stripslashes.
			2. 修改 @author 的选项.

2008-12-26
			1. 修改 wp-thread-comment.js.php 文件, 适应更加广泛.

2008-12-15
			1.4.9.2 发布

2008-12-15
			1. 修复 wordpress 2.7 后台回复出现comment is empty的错误. 将comment 改为 wptc_comment, 兼容 wordpress 2.7

2008-12-05
			1. 修复当文章关闭评论时reply依然显示的bug.
			2. 修正一些字段的翻译

2008-11-26
			1.4.9.0 publish

2008-11-25
			1. 为兼容 wordpress 2.7 的后台回复, 修改 comment_post_ID 为 wptc_comment_post_ID, 同时为兼容 wordpress 2.7 后台的 css, 将 textarea 的 col 改成 50

2008-09-26
			1. 增加wpthreadcomment_threadedcomment函数, 允许主题制作者自己写子评论的函数.
			2. 增加css文件wpthreadcomment-css.css, 允许主题制作者自己写子评论的css.

2008-09-24
			1.4.8.1 publish

2008-09-24
			1. 修正 wordpress get_comment 带来的bug(在approve的时候无法发送邮件)

2008-09-18
			1.4.8.0 publish

2008-09-18
			1. 修正后台回复显示接收回复邮件的提示.

2008-09-16
			1. 修正comment_date的错误.
			2. 增加wp_set_comment_status, 在approve comment之后发送邮件(unapprove comment不发送邮件)
			3. 修正mail checked的一处bug

2008-09-10
			1. 修正一处词条.
			2. 增加默认回复mail的checked
			3. 把两处的div改回p

2008-08-29
			1. 修正一处语言

2008-08-28
			1. 去除了一个调试的die.
			2. 增加评论这自己选择是否接收mail(后台)
			3. 修正一个错误

2008-08-27
			1. 增加评论者自己可选是否接收回复mail(只增加了前台).

2008-08-26
			1. 取消回复自己评论的时候发送邮件.

2008-08-25
			1. 后台增加在回复前加入@author
			2. 修正js中class alt, 以便更正确匹配.

2008-08-21
			1. 删除兼容wp paged comments的代码, 将代码移入wp paged comments插件.

2008-08-19
			1. 增加仅仅允许 admin or poster 回复选项.
			2. 删除宽度自适应代码.
			3. 增加在回复的前面加入@author.

2008-08-07
			1. 修正在新评论递交过程中不允许回复.

2008-07-28
			1.4.7.8 发布

2008-07-28
			1. 修正一个弱智bug

2008-07-28
			1.4.7.7 发布

2008-07-28
			1. 关闭宽度自适应
			2. 修复不兼容php4的bug, php4用户不兼容wp ajax edit comments最新版

2008-07-27
			1. 后台增加支付宝捐赠链接.

2008-07-27
			1.4.7.6 发布

2008-07-26
			1. 删除click to cancel reply出的<small>参数.

2008-07-24
			1. 修正一处js的bug.

2008-07-14
			1. 增加email中pc_author参数.
			2. 兼容wp ajax edit comments最新版(增加Cookies设置)

2008-07-02
			1. 修正某些直接修改wp源码实现倒序评论的博客无法兼容本插件的问题.
			2. 采用user info方式, 放弃cookies方式来设定AJAX(兼容所有wp版本).
			3. 增加自定义取消评论文本.
			4. 微调了嵌套评论深度输入框的大小.
			5. 修正一处提示文本的错误.

2008-07-02
			1.4.7.5 发布

2008-06-30
			1. AJAX兼容wp 2.6 beta.

2008-06-19
			1. 修改主评论不存在时以主评论的方式显示子评论(原先为不显示, 致使使用wordpress导入功能后, 子评论无法显示).
			2. 增加css是否自动加入头部的选择, 对于某些有特殊癖好的人来说会方便很多.

2008-06-16
			1. 调整默认的html样式.

2008-06-12
			1. js增加宽度自适应代码, 默认不启用.

2008-05-29
			1.4.7.4 发布

2008-05-29
			1. 修正使用comment_type()之后$comment->comment_type被置为'comment'引起的回复评论不显示的bug

2008-05-28
			1.4.7.3 发布

2008-05-25
			1. 修改除comments外不出现回复按钮.
			2. 移动评论input设置自动size

2008-05-21
			1. 修改filter的优先级, 兼容wp ajax edit comments

2008-05-16
			1. 增加wpc=all参数, 兼容wp paged comments

2008-05-06
			1. js增加null检测.
			2. 后台回复增加对wp js spam的兼容.

2008-05-04
			1. 修正themes缺乏comments.php时, ajax无法评论的错误.
			2. 通过w3c strict 验证.

2008-04-30	1.4.7.2 发布

2008-04-30	1.4.7.1 发布

2008-04-30
			1. 修复&$兼容php4的bug(两处).
			2. 修复兼容IE错误(ajaxeditcomments)

2008-04-29	1.4.7 发布

2008-04-29
			1. 增加unset, 减少内存占用.
			2. 修改几处提示
			3. 修改redirect为home.

2008-04-26
			1. 修正blog address 与 wordpress address 不同时, 无法后台回复的bug.
			2. 增加回复按钮文本选项, 可以自行定义回复按钮显示的文字.

2008-04-24
			1. 增加通过判断评论数来判断评论的顺序(只有一条评论时, 永远判断为正序,算是一个不足)
			2. 激活switch_theme, 更换主题无忧.

2008-04-23
			1. js增加ajax对wp ajax edit comments插件兼容.
			2. 增加前台管理设置选项.

2008-04-22
			1. mailer函数增加判断comment_parent_ID === 0, 提高效率.
			2. 修改mail subject词条.
			3. 启用is_author 和 is_admin 函数.

2008-04-21
			1. 通过xhtml 1.0 strict验证
			2. 增加movecomment函数, 允许管理员自由移动评论, 只能朝上或者横向移动.
			3. 重新修改wp ajax edit comments兼容位置, 提高效率.
			4. Cancel词条多国语言化

2008-04-20	1.4.6 发布

2008-04-19
			1. 修改词条(submit reply -> nested reply)
			2. 修改sortflag的bug, 通过判断lstcomment来确定DESC和ASC.

2008-04-16
			1. 增加memory_limit选项, 可以自行设定内存限制.
			2. 默认html增加gravatar, 配合wp2.5
			3. 修正unset($this->comment_childs)的bug

2008-04-13
			1. 重新设计ajax的load位置, 完全解决评论框在评论前还是在评论后都可以AJAX评论.
			2. 重新修订所有的词条, 语义更加确切明晰(感谢extvia).

2008-04-12
			1. 重新设定lstcomment和sortflag变量的获取来源(从comment_text()中获取, 以便于适合所有的倒序评论).

2008-04-11
			1. 新增语言包(Persian).
			2. 修复跟wp paged comments同时使用, wp paged comments采取ASC顺序多页时无法使用AJAX, 重新获取lstcomment的值.
			3. 修正评论倒序时, 发表的评论置顶(只针对wp paged comments插件).
			4. 增加version变量, 最后一位是build.

2008-04-10
			1. 修改HTTPS, 使得判断更为准确(IIS以ISAPI运行PHP输出$_SERVER['HTTPS']为off)
			2. 修改$_SERVER['HTTP_HOST']为$_SERVER['SERVER_NAME']
			3. 增加后台回复功能.

2008-04-09
			1. 修改js一处bug(|| => &&)

2008-04-09	1.4.5 发布.

2008-04-07
			1. 新增切换themes时自动检测commentformid函数, 更换主题无忧(未启用)
			2. 新增检测插件冲突函数, 在有冲突的插件启用情况下本插件无法启用(未启用)

2008-04-01
			1. 修改js部分, 提高兼容性.

2008-03-26
			1. 增加needauthoremail参数, 配合wp后台设置的email选择.
			2. 修改js, 加强email检测.
			3. 减少一个focus, 如果是留言成功, 则不foucs到留言框.

2008-03-25
			1. 修正deactive的bug

2008-03-24
			1. 增加$_POST['comment_reply_ID']判断, 防止误读.

2008-03-23
			1. 修正js的错误, $s(commentformid).style.display='block'位置移前, IE下不会出现focus无法成功的错误.
			2. 改写部分js, 加强主题的兼容性.
			3. 修正class thdrpy的一个bug, 通过w3c验证.

2008-03-22
			1. 修正js中的一个错误,在IE下不再出错(r)改成 (r != 0 && r != "0")

2008-03-21
			1. 增加ajax功能(可以自由选择是否使用).

2008-03-16
			1. 修改class thdrpy位置, 防止出现空段落的bug

2008-03-13
			1. deepth 改成 depth
			2. 在[reply]前增加class thdrpy, 允许自定义样式

2008-03-12
			1. 修复mail bug, 原先在选择everyone mail时会出现[postname]空白(又是隐藏很深的Bug)
			2. 增加两个变量, 减少函数调用此处, 提高效率(可以少N次的get_option)[经过实际测试, wp自身实现了cache[不管是否启用cache], 用内存换速度, get_option对效率几乎没有影响)

2008-03-11
			1. 修正原先的delete按钮bug, 使得在最后一层也可以显示.
			2. 修改js, 提高js对主题的适应性.

2008-03-03
			1. 增加delete_comment action, 处理删除父评论时子评论的处理.
			2. 前台增加delete按钮, 直接在前台可以删除评论.

2008-02-29
			1. 修正重大bug, $deep参数控制出错.
			2. 增加$count参数, 用于控制同一层次中的回复数, 可以用于控制同一层次留言的不同样式.
			3. 修改is_admin()函数, 增加$cap变量, 提高性能.

2008-02-28
			1. 增加global $post in addchildcomment().
			2. 增加is_admin(), is_author()函数, 目前注释掉, 需要时可以直接启用.
			3. 重新设定了default options.

2008-02-27
			1. 修正js中的错误(原commentform位于firstchild时取消回复, commentform消失)
			2. 增加新的preprocess_comment filter, 减少数据库查询一次, 该方法由lot提供(http://blog.net2e.com/).
			3. reply this comment 改成 reply to this comment

2008-02-17
			1. 修正在没有评论回复时出现array_key_exists()函数出错的bug

2008-02-14
			1. 将运行php挪动到str_replace之前.
			2. 增加多层回复功能, 可以自行控制回复深度, 这个要感谢denis(http://fairyfish.net/), 感谢帮我找到这么好的方法.

2007-12-25
			1. 增加time参数, 可以在子评论中显示评论时间.
			2. 增加对php的支持, 可以在子评论样式中直接加入php源码.

2007-11-20
			1. 修正无法通过w3c的xhtml 1.0.

2007-11-14
			1. 增加focus(), 使得在点击回复评论后焦点自动位于comment输入框.

2007-11-13	
			1. 修正在多次点"回复"后, 取消回复无法返回原位置的bug.
			2. 彻底重写wp-thread-comment.js, 大幅度减少代码保证文件小巧, 如果你还觉得不够, 可以删除alert的函数(去除警告即可).
			3. 后台增加email通知内容的设置, 可以设置subject和message.
			4. 解决与wp ajax edit comments 的不兼容问题, 目前可以完全和wp ajax edit comments 协同工作.

2007-11-2
			1. 增加一行曾经遗漏的代码, 现在可以在更新配置后显示更新成功的信息了(一直没有发现这个错误, ft).

2007-11-1
			1. 只在有权评论的时候显示reply按钮;
			2. 修正原先当评论者姓名和日期出现在内容下方的样式中, 被回复者的姓名和日期显示为回复者的姓名和日期的bug;
			3. 增加回复email通知功能, 后台设置三种情况.

2007-10-30
			1. 增加info变量, 用来记录plugin的信息, 保证plugin无论在任何目录都能有效运行.
*/

if (!(isset($_POST['SaveCommentId']) && isset($_POST['SaveContent']) && isset($_POST['_wpnonce'])) && !($_GET['action'] === 'movecomment')) : //兼容wp ajax edit comments, 兼容movecomment
if(!class_exists('wp_thread_comment')):
class wp_thread_comment{
	var $version = '1.4.9.4.002';
	var $info = '';
	var $status = '';
	var $message = '';
	var $options = array();
	var $options_keys = array('cancel_reply', 'reply_text', 'memory_limit', 'comment_html', 'comment_css', 'comment_formid', 'comment_deep', 'comment_ajax',  'clean_option', 'mail_notify', 'mail_subject', 'mail_message', 'reply_admin', 'manage_front', 'no_css', 'dn_hide_note', 'reply_onlyadmin', 'at_reply');
	var $db_options = 'wpthreadcomment';
	var $comment_childs = array();
	var $cap = array('reply' => FALSE, 'delete' => FALSE, 'admin' => array(), 'sortflag' => '', 'lstcomment' => 0, 'programflag' => 0);
	//programflag 共 0/1/2 三个值, 0表示未设置过lstcomment, 1表示comment_text比comment_form前运行, 2表示comment_text比comment_form后运行
	var $replytext = array();

	var $donate_link = 'http://blog.2i2j.com/redir/donate-wpthreadcomment';

	function wp_thread_comment(){
		$this->initinfo();
		if(!is_admin() && (int)$this->options['memory_limit'] != 0){
			ini_set('memory_limit', $this->options['memory_limit'].'M');
		}
		add_action('init', array(&$this, 'init_textdomain'));
	}

	function defaultoption($key=''){
		if(empty($key))
			return false;

		if($key === 'cancel_reply'){
			return __('Click to cancel reply','wp-thread-comment');
		}elseif($key === 'reply_text'){
			return '[%' . __('Responder','wp-thread-comment') . '%]';
		}elseif($key === 'memory_limit'){
			return 0;
		}elseif($key === 'comment_html'){
			return __('<div class="comment-childs<?php echo $deep%2 ? \' chalt\' : \'\'; ?>" id="comment-[ID]"><?php if(function_exists("get_avatar")) echo get_avatar( $comment, 32 ); ?><p><cite>[author]</cite> Aten&ccedil;&atilde;o:[moderation]<br /><small class="commentmetadata">[date] &agrave;s [time]</small></p>[content]</div>','wp-thread-comment');
		}elseif($key === 'comment_css'){
			return '
.editComment, .editableComment, .textComment{
	display: inline;
}
.comment-childs{
	border: 1px solid #999;
	margin: 5px 2px 2px 4px;
	padding: 4px 2px 2px 4px;
	background-color: white;
}
.chalt{
	background-color: #ececec;
}
#newcomment{
	border:1px dashed #777;width:90%;
}
#newcommentsubmit{
	color:red;
}
.adminreplycomment{
	border:1px dashed #777;
	width:99%;
	margin:4px;
	padding:4px;
}
.mvccls{
	color: #999;
}
			';
		}elseif($key === 'comment_formid'){
			return (string)$this->getformidfromcommentfile();
		}elseif($key === 'comment_deep'){
			return 3;
		}elseif($key === 'comment_ajax'){
			return 'no';
		}elseif($key === 'clean_option'){
			return 'no';
		}elseif($key === 'mail_notify'){
			return 'none';
		}elseif($key === 'mail_subject'){
			return __('Your comment at [[blogname]] has a new reply','wp-thread-comment');
		}elseif($key === 'mail_message'){
			return __("<p><strong>[blogname]</strong>: Your comment on the post <strong>[postname]</strong> has a new reply</p>\n<p>Here is your original comment:<br />\n[pc_content]</p>\n<p>Here is the new reply:<br />\n[cc_content]</p>\n<p>You can see more information for the comment on this post here:<br />\n<a href=\"[commentlink]\">[commentlink]</a></p>\n<p><strong>Thank you for your commenting on <a href=\"[blogurl]\">[blogname]</a></strong></p>\n<p><strong>This email was sent automatically. Please don't reply to this email.</strong></p>",'wp-thread-comment');
		}elseif($key === 'reply_admin'){
			return 'no';
		}elseif($key === 'manage_front'){
			return 'no';
		}elseif($key === 'no_css'){
			return 'no';
		}elseif($key === 'dn_hide_note'){
			return 'no';
		}elseif($key === 'reply_onlyadmin'){
			return 'no';
		}elseif($key === 'at_reply'){
			return 'none';
		}else{
			return false;
		}
	}

	function resetToDefaultOptions(){
		$this->options = array();

		foreach($this->options_keys as $key){
			$this->options[$key] = $this->defaultoption($key);
		}
		update_option($this->db_options, $this->options);
	}

	function getformidfromcommentfile(){

		$commentfile = ABSPATH.'wp-content/themes/'.get_option('stylesheet').'/comments.php';
	
		if(!is_file($commentfile))
			$commentfile = ABSPATH.'wp-content/themes/default/comments.php';
		if(is_file($commentfile)){
			$context = file_get_contents($commentfile);

			if(!empty($context)){
				if(preg_match('/<(form.*?wp-comments-post\\.php.*?)>/ius', $context, $match)){
					$context = $match[1];

					if(preg_match('/id\s*?=\s*?"(.*?)"/ius', $context, $match)){
						unset($commentfile, $context);
						return $match[1];
					}
				}
			}
		}
		unset($commentfile, $context, $match);
		return '';
	}

	function initinfo(){
		$info['file'] = basename(__FILE__);
		$path = basename(str_replace('\\','/',dirname(__FILE__)));
		$info['siteurl'] = get_option('siteurl');
		$info['url'] = $info['siteurl'] . '/wp-content/plugins';
		$info['dir'] = 'wp-content/plugins';
		$info['path'] = '';
		if ( $path != 'plugins' ) {
			$info['url'] .= '/' . $path;
			$info['dir'] .= '/' . $path;
			$info['path'].= $path;
		}
		$this->info = array(
			'siteurl' 			=> $info['siteurl'],
			'url'		=> $info['url'],
			'dir'		=> $info['dir'],
			'path'		=> $info['path'],
			'file'		=> $info['file']
		);
		unset($info);
	}
	function initoption(){
		$optionsFromTable = get_option($this->db_options);
		if (empty($optionsFromTable)){
			$this->resetToDefaultOptions();
			return true;
		}

		$flag = FALSE;
		foreach($this->options_keys as $key) {
			if(isset($optionsFromTable[$key]) && !empty($optionsFromTable[$key])){
				$this->options[$key] = $optionsFromTable[$key];
			}else{
				$this->options[$key] = $this->defaultoption($key);
				$flag = TRUE;
			}
		}
		if($flag === TRUE){
			update_option($this->db_options, $this->options);
		}
		//处理reply_text
		preg_match("/([^%]*)%(.*)%([^%]*)/u", trim($this->options['reply_text']), $match);
		$this->replytext['reply_text_before'] = trim($match[1]);
		$match[2] = trim($match[2]);
		$this->replytext['reply_text'] = empty($match[2]) ? $this->options['reply_text'] : $match[2];
		$this->replytext['reply_text_after'] = trim($match[3]);
		//处理结束
		unset($optionsFromTable,$flag,$match);
	}

	function inithook(){
		add_action('delete_comment',array(&$this,'deletecomment'),9999);

		add_action('comment_post', array(&$this,'add_mail_reply'),9998);
		add_filter('preprocess_comment', array(&$this,'addreplyid'),9999);
		add_action('wp_set_comment_status', array(&$this,'status_change'),9999,2);
		add_action('comment_post', array(&$this,'email'),9999);

		if($this->options['comment_ajax'] === 'yes' && trim($_POST['wptcajax']) === 'wptcajax'){
			add_filter('comment_post_redirect', array(&$this,'commentpostredirect'),9999,2);
		}else{}

		if($this->options['reply_admin'] === 'yes' && trim($_POST['wptcadminajax']) === 'wptcadminajax')
			add_filter('comment_post_redirect', array(&$this,'admincommentpostredirect'),9999);

		if(!is_admin()){
			add_action('comment_form', array(&$this,'addreplyidformfield'),9999);
			add_action('wp_head', array(&$this,'wphead'),9999);
			add_filter('comment_text', array(&$this,'addchildcomment'),9999);	//此处优先级兼容wp ajax edit comments, 必须低于wp ajax edit comments优先级.
			add_filter('comments_array', array(&$this,'changecomment'),9998);
			if($this->options['comment_ajax'] === 'yes'){
				add_filter('comments_array', array(&$this,'lstcomment'),9999);
			}
		}

		if(is_admin()){
			if($this->options['reply_admin'] === 'yes'){
				add_action('admin_head', array(&$this,'wphead'),9999);
				add_action('admin_footer', array(&$this,'admincommentreply'),9999);
				add_filter('comment_text', array(&$this,'admincommenttext'),9999);
			}
			add_action('admin_menu', array(&$this,'wpadmin'));
			if((string) $this->options['clean_option'] === 'yes')
				add_action('deactivate_'.$this->info['path'].'/'.$this->info['file'], array(&$this,'deactivate'));
			//add_action('activate_'.$this->info['path'].'/'.$this->info['file'], array(&$this,'activate'));
			add_action('switch_theme', array(&$this, 'switchtheme'),9999);
		}
	}

	function admincommentpostredirect($location){
		die();exit();
	}

	function admincommenttext($text){
		global $comment;
		
		$comment_type = strtolower(trim($comment->comment_type));
		if($comment_type === 'pingback' || $comment_type === 'trackback')
			return $text;
		unset($comment_type);

		$text .= '<p>[ <a href="javascript:void(0)" onclick="popuptext(event,' . $comment->comment_post_ID . ',' . $comment->comment_ID . ',\'' . $this->encodejs(strip_tags($comment->comment_author)) . '\');">'. __('Responder','wp-thread-comment') . '</a> ]</p>';

		return $text;
	}

	function admincommentreply(){
?>
<form id="inlinereply" method="post" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" style="display:none" onsubmit="return wptcadminajaxsend()">
	<p>
	<textarea id="wptc_comment" name="wptc_comment" style="margin-top:1em;" cols="50" rows="5"></textarea><br />
	<input name="submitreply" id="submitreply" value="<?php _e('Nested Reply', 'wp-thread-comment'); ?>" type="submit" />
	<input name="submitcomment" id="submitcomment" value="<?php _e('Post Comment', 'wp-thread-comment'); ?>" type="submit" onclick="javascript:document.getElementById('comment_reply_ID').value=0" />
	<input name="cancel" id="cancel" value="<?php _e('Cancelar', 'wp-thread-comment'); ?>" type="button" onclick="javascript:document.getElementById('wptc_comment_post_ID').value=0;document.getElementById('inlinereply').style.display='none'" />
	<input type="hidden" id="wptc_comment_post_ID" name="wptc_comment_post_ID" value="0" />
	<input type="hidden" id="comment_reply_ID" name="comment_reply_ID" value="0" />
	</p>
<?php 		
	if($this->options['mail_notify'] === 'parent_check')
		echo '<p><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked" style="width: auto;" /><label for="comment_mail_notify" style="display: inline;">' . __('Avise-me por e-mail quando algu&eacute;m responder meu coment&aacute;rio.', 'wp-thread-comment') . '</label></p>';
	elseif($this->options['mail_notify'] === 'parent_uncheck')
		echo '<p><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" style="width: auto;" /><label for="comment_mail_notify" style="display: inline;">' . __('Avise-me por e-mail quando algu&eacute;m responder meu coment&aacute;rio.', 'wp-thread-comment') . '</label></p>';
	else{}
?>
</form>
<script type="text/javascript">
/* <![CDATA[ */
	var atreply = "<?php echo $this->options['at_reply']; ?>";
/* ]]> */
</script>
<script type="text/javascript" src="<?php echo $this->info['url']."/wp-thread-comment.js.php?jsver=adminajax"; ?>"></script>
<?php
	}

/*	function activate(){
		$needle = array('Ajax Comments-Reply' => 'comment-reply.php',
						'Paged Threaded Comments' => 'paged-threaded-comments.php',
						'TP-Guestbook' => 'tp-guestbook.php',
						'Brian\'s Threaded Comments' => 'briansthreadedcomments.php');
		$tcurrent = get_option('active_plugins');
		$current = array();
		foreach($tcurrent as $k => $v){
			$current[$k] = trim(strtolower(basename($v)));
		}
		unset($k, $v, $tcurrent);

		foreach($needle as $k => $v){
			if(in_array($v, $current)){
				$current = get_option('active_plugins');
				array_splice($current, array_search( $_GET['plugin'], $current), 1 );
				update_option('active_plugins', $current);
				unset($needle, $current, $v);
				wp_die(sprintf(__('wp thread comment can not activate because plugins "%s" is active, these two plugins are conflict.', 'wp-thread-comment'), $k));
			}
		}
		unset($needle, $k, $v, $current);
	}*/

	function switchtheme(){
		$this->options['comment_formid'] = $this->defaultoption('comment_formid');
		update_option($this->db_options, $this->options);
	}

	function init_textdomain(){
		load_plugin_textdomain('wp-thread-comment',$this->info['dir']);
		$this->initoption();
		$this->inithook();
	}

	function deactivate(){
		if($this->options['clean_option'] === 'yes')
			delete_option($this->db_options);
		return true;
	}

	function deletecomment($id){
		global $wpdb;
		
		$comment = get_comment($id);

		$comments_id = array();
		$comments_id = $wpdb->get_col("SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment->comment_post_ID' AND comment_parent = '$id'");
		
		$comment_parent = $comment->comment_parent;
		unset($comment);

		if(count($comments_id) > 0){
			foreach($comments_id as $comment_id){
				/*if(wp_delete_comment($comment_id) === false){
					exit();
					break;
				}*/
				if ($wpdb->query("UPDATE $wpdb->comments SET comment_parent = '$comment_parent'	WHERE comment_ID = '$comment_id'") === false){
					exit();
					break;
				}
			}
		}
		unset($comments_id);
		return $id;
	}

	function commentpostredirect($location,$c){
		global $comment, $user_ID, $wp_query;

		//此部分用于设置Cookies, 用于兼容wp ajax edit comments, 也可以兼容以后任何跟Cookies相关的.
		if(function_exists('headers_list')){
			$headers = headers_list();

			if(is_array($headers)){
				foreach($headers as $header){
					if(strpos($header, 'Set-Cookie') !== FALSE && strpos($header, 'WPAjaxEditCommentsComment') !== FALSE){
						$header = explode(';', $header);
						$header = explode(':', $header[0]);
						$header = explode('=', $header[1]);
						$_COOKIE[trim($header[0])] = trim($header[1]);
					}
				}
			}
			unset($headers, $header);
		}

		if($this->options['comment_ajax'] === 'yes' && trim($_POST['wptcajax']) === 'wptcajax'){
			$comment = $c;
		
			$this->cap['delete'] = current_user_can('edit_post', $_POST['comment_post_ID']);
			$this->cap['reply'] = (!get_option('comment_registration')) || (get_option('comment_registration') && $user_ID);

			if($comment->comment_parent >0){
				$deep = empty($_POST['comment_reply_dp']) ? 999999 : (int)$_POST['comment_reply_dp'];
				echo $this->commenttext($deep);
				unset($deep);
			}else{
				ob_start();
				$comments = array($comment,$comment);
				$wp_query->comments = $comments;
				$wp_query->comment_count = 1;
				$commentfile = TEMPLATEPATH.'/comments.php';
				if(!is_file($commentfile))
					$commentfile = ABSPATH.'wp-content/themes/default/comments.php';
				if(is_file($commentfile)){
					include($commentfile);
					$commentout = ob_get_clean();

					if(preg_match('#(<\w+\s+[^>]*id\s*=[^>a-zA-Z0-9]*comment-'.$c->comment_ID.'[^>]*>.*?)<\w+\s+[^>]*id\s*=[^>a-zA-Z0-9]*comment-'.$c->comment_ID.'[^>]*>#ius', $commentout, $matches)){
						echo $matches[1];
					}else{
						echo $commentout;
					}
				}else{
					wp_die('Can not find comments.php, check your theme please!');
				}
				unset($commentout, $comments, $matches, $commentfile);
			}
			die();exit();
		}else{
			return $location;
		}
	}


	function addreplyid($commentdata){
		if(isset($_POST['comment_reply_ID']))
			$commentdata['comment_parent'] = mysql_escape_string($_POST['comment_reply_ID']);

		return $commentdata;
	}

	function status_change($id,$status){
		$id = (int) $id;
		if(isset($GLOBALS['comment']) && ($GLOBALS['comment']->comment_ID == $id)){
			unset($GLOBALS['comment']);
			$comment = get_comment($id);
			$GLOBALS['comment'] = $comment;
		}

		if ($status== 'approve' && intval($comment->comment_parent)>0){
			$this->mailer($id,$comment->comment_parent,$comment->comment_post_ID);
		}

		return $id;
	}
	
	function email($id){

		if((int) mysql_escape_string($_POST['comment_reply_ID']) === 0 || (int) mysql_escape_string($_POST['comment_post_ID']) === 0){
			return $id;
		}

		if($this->options['mail_notify'] != 'none'){
			$this->mailer($id,mysql_escape_string($_POST['comment_reply_ID']),mysql_escape_string($_POST['comment_post_ID']));
		}

		return $id;

	}
	
	function add_mail_reply($id){
		global $wpdb;

		if(isset($_POST['comment_mail_notify'])){
			$i = 0;
			if($wpdb->query("Describe {$wpdb->comments} comment_mail_notify") == 0 && $i < 10){
				$wpdb->query("ALTER TABLE {$wpdb->comments} ADD COLUMN comment_mail_notify TINYINT NOT NULL DEFAULT 0;");
				$i++;
			}
			$wpdb->query("UPDATE {$wpdb->comments} SET comment_mail_notify='1' WHERE comment_ID='$id'");
		}

		return $id;
	}

	function mailer($id,$parent_id,$comment_post_id){
		global $wpdb, $user_ID, $userdata;

		$post = get_post($comment_post_id);

		if(empty($post)){
			unset($post);
			return false;
		}

		if($this->options['mail_notify'] == 'admin'){
			$cap = $wpdb->prefix . 'capabilities';
			if((strtolower((string) array_shift(array_keys((array)($userdata->$cap)))) !== 'administrator') && ((int)$post->post_author !== (int)$user_ID)){
				unset($post, $cap);
				return false;
			}
		}
		
		//$parent_email = trim($wpdb->get_var("SELECT comment_author_email FROM {$wpdb->comments} WHERE comment_ID='$parent_id'"));
		$pc = get_comment($parent_id);
		if(empty($pc)){
			unset($pc);
			return false;
		}

		if(intval($pc->comment_mail_notify) === 0 && ($this->options['mail_notify'] === 'parent_uncheck' || $this->options['mail_notify'] === 'parent_check')){
			unset($pc);
			return false;
		}

		$parent_email = trim($pc->comment_author_email);

		if(empty($parent_email) || !is_email($parent_email)){
			unset($pc, $parent_email);
			return false;
		}

		$cc = get_comment($id);
		if(empty($cc)){
			unset($pc,$cc);
			return false;
		}

		if ($cc->comment_approved != '1')
		{
			unset($pc,$cc);
			return false;
		}

		if($parent_email === trim($cc->comment_author_email)){ //如果自己回复自己的评论就不发送邮件
			unset($pc,$cc);
			return false;
		}

		$mail_subject = $this->options['mail_subject'];
		$mail_subject = str_replace('[blogname]', get_option('blogname'), $mail_subject);
		$mail_subject = str_replace('[postname]', $post->post_title, $mail_subject);

		$mail_message = $this->options['mail_message'];
		$mail_message = str_replace('[pc_date]', mysql2date( get_option('date_format'), $pc->comment_date), $mail_message);
		$mail_message = str_replace('[pc_content]', $pc->comment_content, $mail_message);
		$mail_message = str_replace('[pc_author]', $pc->comment_author, $mail_message);
		
		$mail_message = str_replace('[cc_author]', $cc->comment_author, $mail_message);
		$mail_message = str_replace('[cc_date]', mysql2date( get_option('date_format'), $cc->comment_date), $mail_message);
		$mail_message = str_replace('[cc_url]', $cc->comment_url, $mail_message);
		$mail_message = str_replace('[cc_content]', $cc->comment_content, $mail_message);

		$mail_message = str_replace('[blogname]', get_option('blogname'), $mail_message);
		$mail_message = str_replace('[blogurl]', get_option('home'), $mail_message);
		$mail_message = str_replace('[postname]', $post->post_title, $mail_message);

		$permalink = get_permalink($comment_post_id);
		
		if(get_option('wpcflag' === 'true')){
			if(strpos($permalink, '?') !== FALSE){
				$permalink .= '&wpc=all';
			}else{
				$permalink .= '?wpc=all';
			}
		}

		$mail_message = str_replace('[commentlink]', $permalink . "#comment-{$parent_id}", $mail_message);

		$wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
		$from = "From: \"".get_option('blogname')."\" <$wp_email>";

		$mail_headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";

		unset($wp_email, $from, $post, $pc, $cc, $cap, $permalink);

		$mail_message = apply_filters('comment_notification_text', $mail_message, $id);
		$mail_subject = apply_filters('comment_notification_subject', $mail_subject, $id);
		$mail_headers = apply_filters('comment_notification_headers', $mail_headers, $id);

		@wp_mail($parent_email, $mail_subject, $mail_message, $mail_headers);
		unset($mail_subject,$parent_email,$mail_message, $mail_headers);
		
		return true;
	}

	function outputjs(){
		$js = "<script type=\"text/javascript\">\n/* <![CDATA[ */\nvar lstcommentid=".$this->cap['lstcomment'].";\n";
		if(get_option('require_name_email'))
			$js .= "var needauthoremail=true;\n";
		else
			$js .= "var needauthoremail=false;\n";

		$js .= "var sortflag=\"".$this->cap['sortflag']."\";\n";
		$js .= "/* ]]> */\n</script>\n";
		echo $js;
		unset($js);

		echo "<script type=\"text/javascript\" src=\"".$this->info['url']."/wp-thread-comment.js.php?jsver=ajax&amp;wpajaxeditcomments=true\"></script>\n";
	}

	function addreplyidformfield(){
		$user = wp_get_current_user();
		$user = isset($user->display_name) ? "\"{$user->display_name}\"" : 'false';
      
		echo '<p><input type="hidden" id="comment_reply_ID" name="comment_reply_ID" value="0" />';
		echo '<input type="hidden" id="comment_reply_dp" name="comment_reply_dp" value="0" /></p>';
		echo '<div id="cancel_reply" style="display:none;"><a href="javascript:void(0)" onclick="movecfm(null,0,1,null);" style="color:red;">' . $this->options['cancel_reply'] . '</a></div>';

		if($this->options['mail_notify'] === 'parent_check')
			echo '<p><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked" style="width: auto;" /><label for="comment_mail_notify" style="display: inline;">' . __('Avise-me por e-mail quando algu&eacute;m responder meu coment&aacute;rio.', 'wp-thread-comment') . '</label></p>';
		elseif($this->options['mail_notify'] === 'parent_uncheck')
			echo '<p><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" style="width: auto;" /><label for="comment_mail_notify" style="display: inline;">' . __('Avise-me por e-mail quando algu&eacute;m responder meu coment&aacute;rio.', 'wp-thread-comment') . '</label></p>';
		else{}

		echo "<script type=\"text/javascript\">\n/* <![CDATA[ */\nvar commentformid = \"". $this->options['comment_formid'] . "\";\nvar USERINFO = {$user};\nvar atreply = \"". $this->options['at_reply'] . "\";\n/* ]]> */\n</script>\n";
		unset($user);

		echo "<script type=\"text/javascript\" src=\"". $this->info['url'] . "/wp-thread-comment.js.php?jsver=common\"></script>\n";

		if($this->options['comment_ajax'] === 'yes' && $this->cap['programflag'] === 0){
			$this->cap['programflag'] = 2;
		}elseif($this->options['comment_ajax'] === 'yes' && $this->cap['programflag'] === 1){
			$this->outputjs();
			unset($this->comment_childs);
		}else{}
	}

	function changecomment($comments){
		global $post, $user_ID;

		$id = '|';
		foreach($comments as $j => $v){
			$id .= trim($v->comment_ID) . '|';
		}
		unset($j, $v);

		foreach($comments as $i => $value){
			if($value->comment_parent > 0){
				if(strpos($id, '|' . trim($value->comment_parent) . '|') === FALSE){
					//$comments[] = $value;unset($comments[$i]); //此句用于将没有父评论的子评论移动到评论的末尾.
					continue;
				}

				$this->comment_childs[$value->comment_parent][] = $value;
				unset($comments[$i]);
			}
		}

		unset($id);

		$comments = array_values($comments);

		$this->cap['delete'] = current_user_can('edit_post', $post->ID);
		$this->cap['reply'] = ('open' == $post->comment_status) && ((!get_option('comment_registration')) || (get_option('comment_registration') && $user_ID)) && ($this->options['reply_onlyadmin'] !== 'yes' || ($this->options['reply_onlyadmin'] === 'yes' && $this->cap['delete']));

		return $comments;
	}

	function lstcomment($comments){

		if($this->options['comment_ajax'] === 'yes'){

			$count = (int) count($comments);

			$this->cap['lstcomment'] = (int)$comments[$count-1]->comment_ID;

			if($count > 1 && $this->cap['lstcomment'] < (int)$comments[0]->comment_ID){
				$this->cap['lstcomment'] = (int)$comments[0]->comment_ID;
			}

			if((int) $count === 1)
				$this->cap['sortflag'] = 'ASC';

			unset($count);
		}

		return $comments;
	}

	function addchildcomment($text){
		global $post, $comment;
		static $deep = 0;

		$comment_type = strtolower(trim($comment->comment_type));
		if($comment_type === 'pingback' || $comment_type === 'trackback')
			return $text;
		unset($comment_type);

		if(trim($_POST['wptcajax']) === 'wptcajax' && !empty($_POST['comment_reply_dp']))
			$deep = (int) $_POST['comment_reply_dp'];

		if((int)$comment->comment_parent === 0){
			$deep = 0;

			if($this->options['comment_ajax'] === 'yes'){
				if(empty($this->cap['sortflag'])){
					if((int)$comment->comment_ID === (int)$this->cap['lstcomment']){
						$this->cap['sortflag'] = 'DESC';
					}else{
						$this->cap['sortflag'] = 'ASC';
					}
				}

				if($this->cap['programflag'] === 0){
					$this->cap['programflag'] = 1;
				}

				if((int)$comment->comment_ID === (int)$this->cap['lstcomment'] && $this->cap['programflag'] === 2){
					$this->outputjs();
				}
			}

			/*if((int)$comment->comment_ID > (int)$this->cap['lstcomment']){
				$this->cap['lstcomment'] = (int)$comment->comment_ID;
			}elseif((int)$comment->comment_ID < (int)$this->cap['lstcomment']){
				$this->cap['sortflag'] = 'DESC';
			}else{}*/
		}		

		$orgcomment = $comment;

		if($this->cap['reply'] && $deep < (int) $this->options['comment_deep']){
			$text .= '<p class="thdrpy">'.$this->replytext['reply_text_before'].'<a href="javascript:void(0)" onclick="movecfm(event,' . $comment->comment_ID . ',' . ($deep+1) . ',\'' . $this->encodejs(strip_tags($comment->comment_author)) . '\');">'. $this->replytext['reply_text'] . '</a>'.$this->replytext['reply_text_after'].'</p>';
		}

		if($this->options['manage_front'] === 'yes' && $this->cap['delete']){
			$text .= '<p class="thdmang">[<a href="'. wp_nonce_url($this->info['siteurl'].'/wp-admin/comment.php?action=deletecomment&amp;p=' . $comment->comment_post_ID . '&amp;c=' . $comment->comment_ID, 'delete-comment_' . $comment->comment_ID) .'" onclick="return confirm(\''. __('Voc&ecirc; realmente quer apagar o coment&aacute;rio?','wp-thread-comment') . '\');">'. __('Delete','wp-thread-comment') . '</a>] | <input type="text" size="' . strlen($comment->comment_ID) . '" class="mvccls" value="' . $comment->comment_ID . '" onkeydown="javascript:if(event.keyCode==13) {window.location=\''. wp_nonce_url($this->info['url'].'/'.$this->info['file'].'?action=movecomment&amp;p=' . $comment->comment_post_ID . '&amp;c=' . $comment->comment_ID, 'move-comment_' . $comment->comment_ID) .'&amp;pc=\'+this.value; return false;}" onfocus="javascript:this.className = \'\'; this.value = \'\';" onblur="javascript:this.className = \'mvccls\'; this.value = \''.$comment->comment_ID.'\';" /></p>';
		}

		if(isset($this->comment_childs) && array_key_exists($comment->comment_ID, $this->comment_childs)){

			$deep++;

			$id_temp = $comment->comment_ID;

			$count = 0;
			foreach($this->comment_childs[$id_temp] as $comment){

				$count++;
				$text .= $this->commenttext($deep,$count);
			}
			unset($this->comment_childs[$id_temp]);

			$deep--;
		}
		$comment = $orgcomment;
		unset($orgcomment);
		if($deep < 0) $deep = 0;
		return $text;
	}

	function commenttext($deep=0,$count=0){
		global $post, $comment;

		if(function_exists('wpthreadcomment_threadedcomment')){
			ob_start();
			ob_clean();

			wpthreadcomment_threadedcomment($deep,$count);

			$p = ob_get_contents();
			ob_end_clean();
		}else{
			$p = $this->options['comment_html'];

			ob_start();
			ob_clean();
			//$p = str_replace('<'.'?php','<'.'?',$p);
			eval('?'.'>'.$p);
			$p = ob_get_contents();
			ob_end_clean();
								
			$p = str_replace('[ID]', get_comment_ID(), $p);
			$p = str_replace('[author]', get_comment_author_link(), $p);
			$p = str_replace('[date]', get_comment_date('j M, Y'), $p);
			$p = str_replace('[time]', get_comment_time('H:m'), $p);
			$p = str_replace('[moderation]', $comment->comment_approved == '0' ? '<em>Seu coment&aacute;rio est&aacute; aguardando modera&ccedil;&atilde;o.</em>' : '', $p);
				
			if(strpos($p,'[content]')){
				ob_start();
				ob_clean();
				comment_text();
				$text = ob_get_contents();
				ob_end_clean();
				$p = str_replace('[content]', $text, $p);
				unset($text);
			}
		}
			return $p;
	}

	function wphead(){
		$threadhead = "<!-- wp thread comment {$this->version} -->\n";

		if(@file_exists(TEMPLATEPATH.'/wpthreadcomment-css.css')){
			$threadhead .= '<link rel="stylesheet" href="'.get_stylesheet_directory_uri().'/wpthreadcomment-css.css" type="text/css" media="screen" />'."\n";
		}elseif($this->options['no_css'] !== 'yes'){
			$threadhead .= "<style type=\"text/css\" media=\"screen\">\n" . $this->options['comment_css'] . "\n</style>\n";
		}else{}

		echo $threadhead;

		unset($threadhead);
	}

	function displayMessage() {
		if ( $this->message != '') {
			$message = $this->message;
			$status = $this->status;
			$this->message = $this->status = '';
		}

		if ( $message ) {
?>
			<div id="message" class="<?php echo ($status != '') ? $status :'updated '; ?> fade">
				<p><strong><?php echo $message; ?></strong></p>
			</div>
<?php	
		}
		unset($message,$status);
	}

	function wpadmin(){
		add_options_page(__('WP Thread Comment Option','wp-thread-comment'), __('WP Thread Comment','wp-thread-comment'), 5, __FILE__, array(&$this,'options_page'));
	}

	function options_page(){

		if($_GET['dn_hide_note']){
			$this->options['dn_hide_note'] = stripslashes($_GET['dn_hide_note']);
			update_option($this->db_options, $this->options);
		}
		if(isset($_POST['updateoptions'])){
			foreach((array) $this->options as $key => $oldvalue) {
				//if($key === 'dn_hide_note') continue;
				$this->options[$key] = (isset($_POST[$key]) && !empty($_POST[$key])) ? stripslashes($_POST[$key]) : $this->defaultoption($key);
			}
			update_option($this->db_options, $this->options);
			$this->message = __('Options saved','wp-thread-comment');
			$this->status = 'updated';
		}elseif( isset($_POST['reset_options']) ){
			$this->resetToDefaultOptions();
			$this->message = __('Confrigura&ccedil;&atilde;o do Plugin foi redefinida para o padr&atilde;o!','wp-thread-comment');
		}else{}
		$this->displayMessage();
?>

<?php
		if($this->options['dn_hide_note'] !== 'yes'){
?>

<div class="updated">
	<strong><p><?php echo sprintf(__('Thanks for using this plugin! If it works and you are satisfied with the results, isn\'t it worth at least one dollar? <a href="%s" target="_blank">Donations</a> help me to continue support and development of this <i>free</i> software! <a href="%s" target="_blank">Sure, no problem!</a> <a href="%s" style="float:right; display:block; border:none;"><small style="font-weight:normal;">No thanks, please don\'t bug me anymore!</small></a>','wp-thread-comment'), $this->donate_link, $this->donate_link, get_option('siteurl').'/wp-admin/options-general.php?page=wp-thread-comment/wp-thread-comment.php&amp;dn_hide_note=yes'); ?></p></strong>
	<div style="clear:right;"></div>
</div>
<div class="updated">
	<strong><p>如果您不方便从paypal上捐赠的话, 你可以从支付宝捐点给我, <a href="https://www.alipay.com/cooperate/gateway.do?_input_charset=UTF-8&body=%E8%AF%B7%E6%82%A8%E5%8A%A1%E5%BF%85%E5%9C%A8%E6%8D%90%E8%B5%A0%E5%90%8E%E5%8F%91%E9%80%81%E9%82%AE%E4%BB%B6%E5%88%B0%E4%B8%8A%E9%9D%A2%E7%9A%84email%2C+%E7%95%99%E4%B8%8B%E6%82%A8%E7%9A%84%E5%8D%9A%E5%AE%A2%E5%9C%B0%E5%9D%80!&out_trade_no=121717167254300868&partner=2088002000229779&payment_type=4&return_url=http%3A%2F%2Fblog.2i2j.com%2Fdonate-success&seller_email=blog.2i2j.com%40gmail.com&service=create_donate_trade_p&subject=donate+for+wp+thread+comment&total_fee=30&sign=10a1d3e832f122054fcdd462cccf9ad6&sign_type=MD5">现在就捐!</a></p></strong>
	<div style="clear:right;"></div>
</div>

<?php
		}
?>

<div class="wrap">
	<style type="text/css">
		div.clearing{border-top:1px solid #2580B2 !important;clear:both;}
	</style>

	<h2>WP Thread Comment</h2>
	<p><small><?php echo sprintf(__('<a href="%s" target="_blank">Donations for this plugins</a>','wp-thread-comment'), $this->donate_link); ?></small></p>
	<form method="post" action="">
		<fieldset name="wp_basic_options"  class="options">
		<p>
			<strong><?php _e('Memory Limit Set','wp-thread-comment'); ?></strong>
			<br /><br />
			<label><?php _e('Memory limit(number only):','wp-thread-comment'); ?></label>
			<input type="text" name="memory_limit" id="memory_limit" value="<?php echo $this->options['memory_limit']; ?>" size="2" /><label>M</label>
			<br />
			<small><?php _e('if you cannot run this plugin due to low memory, try to increase the memory limit so you can run the plugin. If leave this value null or 0, this plugin will use your system\'s default memory limit.','wp-thread-comment'); ?></small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong><?php _e('Configuration Reply Rights','wp-thread-comment'); ?></strong>
			<br /><br />
			<label><?php _e('Only Admin or Poster can reply in front:','wp-thread-comment'); ?></label>
			<input type="checkbox" name="reply_onlyadmin" id="reply_onlyadmin" value="yes" <?php if ($this->options['reply_onlyadmin'] === 'yes') { ?> checked="checked"<?php } ?>/>
			<br />
			<small><?php _e('Check this box if you wish to allow only the admin or original poster to reply in front','wp-thread-comment'); ?></small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong><?php _e('Configuration AJAX','wp-thread-comment'); ?></strong>
			<br /><br />
			<label><?php _e('Enable Ajax Support:','wp-thread-comment'); ?></label>
			<input type="checkbox" name="comment_ajax" id="comment_ajax" value="yes" <?php if ($this->options['comment_ajax'] === 'yes') { ?> checked="checked"<?php } ?>/>
			<br />
			<small><?php _e('Check this box if you wish to use AJAX when people reply to comments','wp-thread-comment'); ?></small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong><?php _e('Edit Comment Form ID','wp-thread-comment'); ?></strong>
			<br /><br />
			<label>Comment Form ID:</label>
			<input type="text" name="comment_formid" id="comment_formid" value="<?php echo $this->options['comment_formid']; ?>" size="15" />
			<br />
			<small><?php _e('Change the commentform ID in comments.php according to your theme. In most cases you will not need to do anything as this can be detected automatically.','wp-thread-comment'); ?></small>
			<br />
		</p>
		<div class="clearing"></div>
		<p>
			<strong><?php _e('Edit Reply Text & Cancel Reply Text','wp-thread-comment'); ?></strong>
			<br /><br />
			<label><?php _e('Texto de Resposta', 'wp-thread-comment'); ?>:</label>
			<input type="text" name="reply_text" id="reply_text" value="<?php echo htmlspecialchars(stripslashes($this->options['reply_text'])); ?>" size="45" />
			<br />
			<label><?php _e('Cancel Reply Text', 'wp-thread-comment'); ?>:</label>
			<input type="text" name="cancel_reply" id="cancel_reply" value="<?php echo htmlspecialchars(stripslashes($this->options['cancel_reply'])); ?>" size="45" />
			<br />
			<small><?php _e('Edit Reply Button Text & Cancel Reply Text that you wish to show in the post comments.','wp-thread-comment'); ?></small>
			<br />
		</p>
		<div class="clearing"></div>
		<p>
			<strong><?php _e('Edit Comment HTML','wp-thread-comment'); ?></strong>
			<br /><br />
			<textarea style="font-size: 90%" name="comment_html" id="comment_html" cols="100%" rows="10" ><?php echo htmlspecialchars($this->options['comment_html']); ?></textarea>
			<br />
			<small><?php _e('HTML and PHP both can be used. As a easier way, you may use the following tags: <strong>[ID]</strong> for comment ID, <strong>[author]</strong> for comment author, <strong>[date]</strong> for comment date, <strong>[time]</strong> for comment time, <strong>[content]</strong> for comment content and <strong>[moderation]</strong> for comment moderation.','wp-thread-comment'); ?></small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong><?php _e('Edit Comment CSS','wp-thread-comment'); ?></strong>
			<br /><br />
			<textarea style="font-size: 90%" name="comment_css" id="comment_css" cols="100%" rows="20" ><?php echo htmlspecialchars(stripslashes($this->options['comment_css'])); ?></textarea>
			<br />
			<small><?php _e('Use CSS only, HTML and PHP cannot be used.','wp-thread-comment'); ?></small>
		</p>
		<p>
			<label><?php _e('Include CSS in style.css','wp-thread-comment'); ?></label>
			<input type="checkbox" name="no_css" id="no_css" value="yes" <?php if ($this->options['no_css'] === 'yes') { ?> checked="checked"<?php } ?>/>
			<br />
			<small><?php _e('If you want to use css in style.css, please check this box, then copy above css style to style.css file.(nomally dont checked it)','wp-thread-comment'); ?></small>
		</p>

		<div class="clearing"></div>
		<p>
			<strong><?php _e('Edit maximum nest level','wp-thread-comment'); ?></strong>
			<br /><br />
			<label><?php _e('Thread Nest level(number only):','wp-thread-comment'); ?></label>
			<input type="text" name="comment_deep" id="comment_deep" value="<?php echo $this->options['comment_deep']; ?>" size="8" />
			<br />
			<small><?php _e('Comments cannot be nested more deeply than this level.','wp-thread-comment'); ?></small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong><?php _e('Email notify the parent commenter when his comment was replied','wp-thread-comment'); ?></strong>
			<br /><br />
			<input type="radio" name="mail_notify" id="do_none" value="none" <?php if ($this->options['mail_notify'] !== 'admin' || $this->options['mail_notify'] !== 'everyone') { ?> checked="checked"<?php } ?>/><label><?php _e('Disabled','wp-thread-comment'); ?></label>
			<br />
			<input type="radio" name="mail_notify" id="do_admin" value="admin" <?php if ($this->options['mail_notify'] === 'admin') { ?> checked="checked"<?php } ?>/><label><?php _e('Replied by the author of the post or administrator ONLY','wp-thread-comment'); ?></label>
			<br />
			<input type="radio" name="mail_notify" id="do_everyone" value="everyone" <?php if ($this->options['mail_notify'] === 'everyone') { ?> checked="checked"<?php } ?>/><label><?php _e('Anyone replies','wp-thread-comment'); ?></label>
			<br />
			<input type="radio" name="mail_notify" id="do_parent_check" value="parent_check" <?php if ($this->options['mail_notify'] === 'parent_check') { ?> checked="checked"<?php } ?>/><label><?php _e('Commenter choose to do so(default checked)','wp-thread-comment'); ?></label>
			<br />
			<input type="radio" name="mail_notify" id="do_parent_uncheck" value="parent_uncheck" <?php if ($this->options['mail_notify'] === 'parent_uncheck') { ?> checked="checked"<?php } ?>/><label><?php _e('Commenter choose to do so(default unchecked)','wp-thread-comment'); ?></label>
			<br />
		</p>
		<div class="clearing"></div>
		<p>
			<strong><?php _e('Edit the subject of notification email','wp-thread-comment'); ?></strong>
			<br /><br />
			<input type="text" name="mail_subject" id="mail_subject" value="<?php echo $this->options['mail_subject']; ?>" size="80" />
			<br />
			<small><?php _e('Use TEXT only. As a easier way, you may use the following tags: <strong>[blogname]</strong> for blog name and <strong>[postname]</strong> for comment post name','wp-thread-comment'); ?></small>
			<br />
		</p>
		<div class="clearing"></div>
		<p>
			<strong><?php _e('Edit Notification Message','wp-thread-comment'); ?></strong>
			<br /><br />
			<textarea style="font-size: 90%" name="mail_message" id="mail_message" cols="100%" rows="10" ><?php echo $this->options['mail_message']; ?></textarea>
			<br />
			<small><?php _e('Use HTML only. As a easier way, you may use the following tags: <strong>[pc_author]</strong> for parent comment author, <strong>[pc_date]</strong> for parent comment date, <strong>[pc_content]</strong> for parent comment content, <strong>[cc_author]</strong> for child comment author, <strong>[cc_date]</strong> for child comment date, <strong>[cc_url]</strong> for child comment author url, <strong>[cc_content]</strong> for child comment content, <strong>[commentlink]</strong> for parent comment link, <strong>[blogname]</strong> for blog name, <strong>[blogurl]</strong> for blog url and <strong>[postname]</strong> for post name.','wp-thread-comment'); ?></small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong><?php _e('Reply in Wordpress Admin Panel','wp-thread-comment'); ?></strong>
			<br /><br />
			<label><?php _e('Reply in Wordpress Admin Panel:','wp-thread-comment'); ?></label>
			<input type="checkbox" name="reply_admin" id="reply_admin" value="yes" <?php if ($this->options['reply_admin'] === 'yes') { ?> checked="checked"<?php } ?>/>
			<br />
			<small><?php _e('Check this box if you want to be able to Reply directly from the Wordpress Admin Panel','wp-thread-comment'); ?></small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong><?php _e('Front-end comment management','wp-thread-comment'); ?></strong>
			<br /><br />
			<label><?php _e('Enabled In-the-post comment management:','wp-thread-comment'); ?></label>
			<input type="checkbox" name="manage_front" id="manage_front" value="yes" <?php if ($this->options['manage_front'] === 'yes') { ?> checked="checked"<?php } ?>/>
			<br />
			<small><?php _e('Check this box if you wish to allow the administrator or author to delete and move the comments directly in post and pages.','wp-thread-comment'); ?></small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong><?php _e('Add author in reply comment','wp-thread-comment'); ?></strong>
			<br /><br />
			<input type="radio" name="at_reply" id="at_reply" value="none" <?php if($this->options['at_reply'] !== 'author' || $this->options['at_reply'] !== 'authorlink') { ?> checked="checked"<?php } ?>/><label><?php _e('Disabled','wp-thread-comment'); ?></label>
			<br />
			<input type="radio" name="at_reply" id="at_reply" value="author" <?php if($this->options['at_reply'] === 'author') { ?> checked="checked"<?php } ?>/><label><?php _e('Only Show @Author(No link)','wp-thread-comment'); ?></label>
			<br />
			<input type="radio" name="at_reply" id="at_reply" value="authorlink" <?php if($this->options['at_reply'] === 'authorlink') { ?> checked="checked"<?php } ?>/><label><?php _e('Show @Author and Link','wp-thread-comment'); ?></label>
			<br />
			<small><?php _e('Add @author in reply comment.','wp-thread-comment'); ?></small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong><?php _e('Configuration action of deactivate','wp-thread-comment'); ?></strong>
			<br /><br />
			<label><?php _e('Delete options after deactivate:','wp-thread-comment'); ?></label>
			<input type="checkbox" name="clean_option" id="clean_option" value="yes" <?php if ($this->options['clean_option'] === 'yes') { ?> checked="checked"<?php } ?>/>
			<br />
			<small><?php _e('check box if you want to delete all of options of wp thread comment after deactivate this plugin','wp-thread-comment'); ?></small>
		</p>
		<div class="clearing"></div>
		<p class="submit">
			<input type="submit" name="updateoptions" value="<?php _e('Salvar Op&ccedil;&otilde;es','wp-thread-comment'); ?> &raquo;" />
			<input type="submit" name="reset_options" onclick="return confirm('<?php _e('Do you really want to reset your current configuration?','wp-thread-comment'); ?>');" value="<?php _e('Resetar Op&ccedil;&otilde;es','wp-thread-comment'); ?>" />
		</p>
		</fieldset>
	</form>
</div>
<?php
	}

//此部分为is_admin()和is_author()函数, 需要者可以自行启用
	function get_usermeta($meta_key, $meta_value = ''){
		global $wpdb;

		if (empty($meta_key))
			return false;

		$meta_key = preg_replace('|[^a-z0-9_]|i', '', $meta_key);

		if (!empty($meta_value)){
			$metas = $wpdb->get_col("SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '$meta_key' AND meta_value LIKE '$meta_value'");
		}else{
			$metas = $wpdb->get_col("SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '$meta_key'");
		}

		if (empty($metas)){
			return array();
		}

		return $metas;
	}

	function is_admin($user_id = 0){
		global $wpdb, $comment;

		if((int)func_num_args() === 0)
			$user_id = $comment->user_id;

		$user_id = (int) $user_id;

		if($user_id == 0)
			return false;

//		$cap = get_usermeta($user_id,$wpdb->prefix . 'capabilities');
//		if( is_array($cap) && ((int)$cap['administrator'] === 1) ){
//			return true;
//		}elseif(strpos($cap,'administrator')){
//			return true;
//		}else{}
//		return false;

		if(count($this->cap['admin']) < 1)
			$this->cap['admin'] = $this->get_usermeta($wpdb->prefix . 'capabilities', '%administrator%');

		if(in_array($user_id,$this->cap['admin']))
			return true;

		return false;
		
	}

	
	function is_author($user_id = 0){
		global $post, $comment;

		if((int)func_num_args() === 0)
			$user_id = $comment->user_id;

		$user_id = (int) $user_id;
		
		if($user_id == 0)
			return false;

		if((int)$post->post_author === $user_id)
			return true;

		return false;
	}
//到这里就结束了.

	function encodejs($text) {
		//Strip out new lines, because they break the javascript
		$text = str_replace("\r",'', $text);
		$text = str_replace("\n",'', $text);
		//Strip out 's and replace with \'
		$text = str_replace("'","\\'", $text);
		//Strip out "s and replace with \"
		$text = str_replace('"','&quot;', $text);
		//Strip out [ and ] and replace with their HTML equivalent
		//They screw up the jQuery for some reason
		//$text = str_replace('[','&#91;', $text);
		//$text = str_replace(']','&#93;', $text);
		//Escape \s, so the WP auto-link feature doesn't break the Quote link
		//$text = str_replace('/','\\/', $text);
		//Return the result
		return $text;
	}

}
endif;

$new_wp_thread_comment = new wp_thread_comment();

elseif($_GET['action'] === 'movecomment') :
	$curdir = getcwd();
	$path = explode('wp-content', $curdir);
	chdir($path[0].'wp-admin');
	require_once('admin.php');

	chdir($curdir);

	unset($path, $curdir);

	$ccomment = (int) $_GET['c'];
	$pcid = (int) $_GET['pc'];

	if (isset($_GET['noredir'])){
		$noredir = true;
	} else {
		$noredir = false;
	}

	if($ccomment !== $pcid){
		check_admin_referer('move-comment_' . $ccomment);

		if (! $ccomment = get_comment($ccomment))
			 wp_die(__('Oops, no comment with this ID.', 'wp-thread-comment'));

		if (!($pcid === 0) && ! $pcomment = get_comment($pcid))
			 wp_die(__('Oops, no comment with this Moved ID.', 'wp-thread-comment'));

		if (!current_user_can('edit_post', $ccomment->comment_post_ID))
			wp_die(__('You are not allowed to edit comments on this post.', 'wp-thread-comment'));

		$ccomment = (int) $ccomment->comment_ID;

		if ($pcid === 0){
			$wpdb->query("UPDATE {$wpdb->comments} SET comment_parent='0' WHERE comment_ID='$ccomment'");
		}else{
			for(; (int)$pcomment->comment_parent !== 0 && (int)$pcomment->comment_parent !== $ccomment; $pcomment = get_comment($pcomment->comment_parent));
			if((int)$pcomment->comment_parent === 0){
				$wpdb->query("UPDATE {$wpdb->comments} SET comment_parent='$pcid' WHERE comment_ID='$ccomment'");
			}else{
				wp_die( __('Oops, Can not move comment to its child\'s comment.', 'wp-thread-comment'));
			}
		}
	}

	unset($ccomment, $pcomment, $pcid);

	unset($_GET['action']);

	if((wp_get_referer() != '') && (false == $noredir)){
		wp_redirect(wp_get_referer());
	}else{
		wp_redirect(get_option('home'));
	}
	die();exit();

endif;
?>