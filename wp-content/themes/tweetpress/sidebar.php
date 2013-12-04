<?php
/**
 * @package WordPress
 * @subpackage TweetPress
 */
?>
	<td id="sidebar" role="complementary" class="round-right column">
		<?php if(!isset($_GET['sidebar'])) : ?>
			<?php global $t; echo $t; ?>
			<div id="side">
				<div id="profile" class="section profile-side">
					<?php if(is_user_logged_in()) : ?>
						<div class="user_icon">
							<a class="url" title="<?php echo $t->name; ?>" rel="contact" href="http://twitter.com/<?php echo $t->screen_name; ?>">
								<img class="side_thumb photo fn" height="48" width="48" alt="<?php echo $t->name; ?>" src="<?php echo $t->profile_image_url; ?>" />
								<span id="me_name"><?php echo $t->screen_name; ?></span>
								<span id="me_tweets">
									<span id="update_count"><?php echo $t->statuses_count; ?></span> tweets
								</span>
							</a>
						</div>
					<?php else : ?>
						<ul class="about vcard entry-author">
							<li>
								<span class="label">Name</span>
								<span class="fn"><?php echo $t->name; ?></span>
							</li>
							<li>
								<span class="label">Location</span>
								<span class="adr"><?php echo $t->location; ?></span>
							</li>
							<li>
								<span class="label">Twitter</span>
								<a class="url" target="_blank" rel="me nofollow" href="<?php echo 'http://twitter.com/'.$t->screen_name; ?>"><?php echo $t->screen_name; ?></a>
							</li>
							<li id="bio">
								<span class="label">Bio</span>
								<span class="bio"><?php echo $t->description; ?></span>
							</li>
						</ul>
					<?php endif; ?>
					<div class="stats">
						<table>
							<tr>
								<td>
									<a id="following_count_link" 
									class="link-following_page" 
									title="See who <?php echo $t->screen_name ?> is following" 
									rel="me" 
									href="http://twitter.com/<?php echo $t->screen_name; ?>/following">
										<span id="following_count" class="stats_count numeric"><?php echo $t->friends_count; ?></span>
										<span class="label">Following</span>
									</a>
								</td>
								<td>
									<a id="follower_count_link"
									class="link-followers_page"
									title="See who's following <?php echo $t->screen_name; ?>"
									rel="me"
									href="http://twitter.com/<?php echo $t->screen_name; ?>/followers">
										<span id="follower_count" class="stats_count numeric"><?php echo $t->followers_count; ?></span>
										<span class="label">Followers</span>
									</a>
								</td>
								<td>
									<a id="reader_count_link"
									class="link-reader_page"
									title="Subscribe to <?php echo $t->screen_name; ?> feed"
									href="<?php feedburner_url(); ?>">
										<span id="reader_count" class="stats_count numeric"><?php feedburner_count(); ?></span>
										<span class="label">Readers</span>
									</a>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<ul id="primary_nav" class="sidebar-menu">
					<li <?php if(is_home()) echo "class='current_page_item'"; ?>>
						<a href="<?php bloginfo('home'); ?>">
							<span id="post_count" class="stat_count"><?php echo wp_count_posts()->publish; ?></span>
							<span>Articles</span>
						</a>
					</li>
					<?php wp_list_pages('title_li=' ); ?>
				</ul>
				<hr />
				<div id="custom_search" <?php if(is_search()) echo "class='current_page_item'"; ?>>
					<?php get_search_form(); ?>
				</div>
				<div id="following">
					<h2 id="fm_menu" class="sidebar-title"><span>Following</span></h2>
					<div class="sidebar-menu">
						<ul id="following_list">
							<?php echo get_twitter_friends(); ?>
						</ul>
						<a class="view-all-link" href="http://twitter.com/<?php echo $t->screen_name; ?>/following" title="View all followers">View all...</a>
					</div>
				</div>
				<ul id="dynamic_sidebar">
					<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar()) : ?>
						
					<?php endif; ?>
				</ul>
				<hr />
				<?php
					if(is_single()) {
						$url = get_permalink();
						$title = get_the_title();
					} else {
						$url = get_bloginfo('home');
						$title = get_bloginfo('name');
					}
				?>
				<ul class="social-icons">
					<li><a type="application/rss+xml" rel="alternate" href="<?php feedburner_url(); ?>"><img src="<?php bloginfo('template_url'); ?>/images/feed.png" title="RSS" width="16px" height="16px" alt="RSS" /></a></li>
					<li><a href="http://digg.com/submit?phase=2&url=<?php echo $url; ?>"><img src="<?php bloginfo('template_url'); ?>/images/digg.png" title="Digg" width="16px" height="16px" alt="Digg" /></a></li>
					<li><a href="http://del.icio.us/post?url=<?php echo $url; ?>"><img src="<?php bloginfo('template_url'); ?>/images/delicious.png" title="Delicious" width="16px" height="16px" alt="Delicious" /></a></li>
					<li><a href="http://www.facebook.com/sharer.php?u=<?php echo $url; ?>"><img src="<?php bloginfo('template_url'); ?>/images/facebook.png" title="Facebook" width="16px" height="16px" alt="Facebook" /></a></li>
					<li><a href="http://www.stumbleupon.com/submit?url=<?php echo $url; ?>&title=<?php echo $title ?>"><img src="<?php bloginfo('template_url'); ?>/images/stumble.png" title="Stumble Upon" width="16px" height="16px" alt="Stumble Upon" /></a></li>
					<li><a href="http://reddit.com/submit?url=<?php echo $url; ?>&title=<?php echo $title ?>"><img src="<?php bloginfo('template_url'); ?>/images/reddit.png" title="Reddit" width="16px" height="16px" alt="Reddit" /></a></li>
					<li><a href="http://www.mixx.com/submit?page_url=<?php echo $url; ?>"><img src="<?php bloginfo('template_url'); ?>/images/mixx.png" title="Mixx" width="16px" height="16px" alt="Mixx" /></a></li>
					<li><a href="http://technorati.com/faves?add=<?php echo $url; ?>"><img src="<?php bloginfo('template_url'); ?>/images/technorati.png" title="Technorati" width="16px" height="16px" alt="Technorati" /></a></li>
					<li><a href="http://ma.gnolia.com/bookmarklet/add?url=<?php echo $url; ?>&title=<?php echo $title ?>"><img src="<?php bloginfo('template_url'); ?>/images/magnolia.png" title="Magnolia" width="16px" height="16px" alt="Magnolia" /></a></li>
				</ul>
			</div>
		<?php endif; ?>
	</td>

