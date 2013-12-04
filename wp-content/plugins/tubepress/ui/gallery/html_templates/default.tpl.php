<?php 
/**
 * Copyright 2006 - 2010 Eric D. Hough (http://ehough.com)
 * 
 * This file is part of TubePress (http://tubepress.org)
 * 
 * TubePress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * TubePress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with TubePress.  If not, see <http://www.gnu.org/licenses/>.
 *
 * 
 * Uber simple/fast template for TubePress. Idea from here: http://seanhess.net/posts/simple_templating_system_in_php
 * Sure, maybe your templating system of choice looks prettier but I'll bet it's not faster :)
 */
?>

<div class="tubepress_container" id="tubepress_gallery_<?php echo ${org_tubepress_template_Template::GALLERY_ID}; ?>">
  <?php echo ${org_tubepress_template_Template::PRE_GALLERY}; ?>
  
  <div id="tubepress_gallery_<?php echo ${org_tubepress_template_Template::GALLERY_ID}; ?>_thumbnail_area" class="tubepress_thumbnail_area">
  
    <?php if (isset(${org_tubepress_template_Template::PAGINATION_TOP})) : echo ${org_tubepress_template_Template::PAGINATION_TOP}; endif; ?>

    <div class="tubepress_thumbs">
        <?php foreach (${org_tubepress_template_Template::VIDEO_ARRAY} as $video): ?>
     
      <div class="tubepress_thumb">
        <a id="tubepress_image_<?php echo $video->getId(); ?>_<?php echo ${org_tubepress_template_Template::GALLERY_ID}; ?>" rel="tubepress_<?php echo ${org_tubepress_template_Template::EMBEDDED_IMPL_NAME}; ?>_<?php echo ${org_tubepress_template_Template::PLAYER_NAME}; ?>_<?php echo ${org_tubepress_template_Template::GALLERY_ID}; ?>"> 
          <img alt="<?php echo htmlspecialchars($video->getTitle(), ENT_QUOTES, "UTF-8"); ?>" src="<?php echo $video->getThumbnailUrl(); ?>" width="<?php echo ${org_tubepress_template_Template::THUMBNAIL_WIDTH}; ?>" height="<?php echo ${org_tubepress_template_Template::THUMBNAIL_HEIGHT}; ?>" />
        </a>
        <dl class="tubepress_meta_group" style="width: <?php echo ${org_tubepress_template_Template::THUMBNAIL_WIDTH}; ?>px">
          
          <?php if (${org_tubepress_template_Template::META_SHOULD_SHOW}[org_tubepress_options_category_Meta::TITLE]): ?>      
          <dt class="tubepress_meta tubepress_meta_title"><?php echo ${org_tubepress_template_Template::META_LABELS}[org_tubepress_options_category_Meta::TITLE]; ?></dt><dd class="tubepress_meta tubepress_meta_title"><a id="tubepress_title_<?php echo $video->getId(); ?>_<?php echo ${org_tubepress_template_Template::GALLERY_ID}; ?>" rel="tubepress_<?php echo ${org_tubepress_template_Template::EMBEDDED_IMPL_NAME}; ?>_<?php echo ${org_tubepress_template_Template::PLAYER_NAME}; ?>_<?php echo ${org_tubepress_template_Template::GALLERY_ID}; ?>"><?php echo htmlspecialchars($video->getTitle(), ENT_QUOTES, "UTF-8"); ?></a></dd>
          <?php endif; ?>
      
          <?php if (${org_tubepress_template_Template::META_SHOULD_SHOW}[org_tubepress_options_category_Meta::LENGTH]): ?>
          
          <dt class="tubepress_meta tubepress_meta_runtime"><?php echo ${org_tubepress_template_Template::META_LABELS}[org_tubepress_options_category_Meta::LENGTH]; ?></dt><dd class="tubepress_meta tubepress_meta_runtime"><?php echo $video->getDuration(); ?></dd>
          <?php endif; ?>
              
          <?php if (${org_tubepress_template_Template::META_SHOULD_SHOW}[org_tubepress_options_category_Meta::AUTHOR]): ?>
          
          <dt class="tubepress_meta tubepress_meta_author"><?php echo ${org_tubepress_template_Template::META_LABELS}[org_tubepress_options_category_Meta::AUTHOR]; ?></dt><dd class="tubepress_meta tubepress_meta_author"><a rel="external nofollow" href="http://www.youtube.com/profile?user=<?php echo $video->getAuthor(); ?>"><?php echo $video->getAuthor(); ?></a></dd>
          <?php endif; ?>
    
          <?php if (${org_tubepress_template_Template::META_SHOULD_SHOW}[org_tubepress_options_category_Meta::TAGS]): ?>
          
          <dt class="tubepress_meta tubepress_meta_keywords"><?php echo ${org_tubepress_template_Template::META_LABELS}[org_tubepress_options_category_Meta::TAGS]; ?></dt><dd class="tubepress_meta tubepress_meta_keywords"><a rel="external nofollow" href="http://youtube.com/results?search_query=<?php echo rawurlencode(implode(" ", $video->getKeywords())); ?>&amp;search=Search"><?php echo $raw = htmlspecialchars(implode(" ", $video->getKeywords()), ENT_QUOTES, "UTF-8"); ?></a></dd>
          <?php endif; ?>
          
          <?php if (${org_tubepress_template_Template::META_SHOULD_SHOW}[org_tubepress_options_category_Meta::URL]): ?>
          
          <dt class="tubepress_meta tubepress_meta_url"><?php echo ${org_tubepress_template_Template::META_LABELS}[org_tubepress_options_category_Meta::URL]; ?></dt><dd class="tubepress_meta tubepress_meta_url"><a rel="external nofollow" href="<?php echo $video->getHomeUrl(); ?>"><?php echo ${org_tubepress_template_Template::META_LABELS}[org_tubepress_options_category_Meta::URL]; ?></a></dd>
          <?php endif; ?>
          
          <?php if (${org_tubepress_template_Template::META_SHOULD_SHOW}[org_tubepress_options_category_Meta::CATEGORY]): ?>
          
          <dt class="tubepress_meta tubepress_meta_category"><?php echo ${org_tubepress_template_Template::META_LABELS}[org_tubepress_options_category_Meta::CATEGORY]; ?></dt><dd class="tubepress_meta tubepress_meta_category"><?php echo htmlspecialchars($video->getCategory(), ENT_QUOTES, "UTF-8"); ?></dd>
          <?php endif; ?>
        
          <?php if (${org_tubepress_template_Template::META_SHOULD_SHOW}[org_tubepress_options_category_Meta::RATINGS]): ?>
           
          <dt class="tubepress_meta tubepress_meta_ratings"><?php echo ${org_tubepress_template_Template::META_LABELS}[org_tubepress_options_category_Meta::RATINGS]; ?></dt><dd class="tubepress_meta tubepress_meta_ratings"><?php echo $video->getRatingCount(); ?></dd>
          <?php endif; ?>
        
          <?php if (${org_tubepress_template_Template::META_SHOULD_SHOW}[org_tubepress_options_category_Meta::RATING]): ?>
          
          <dt class="tubepress_meta tubepress_meta_rating"><?php echo ${org_tubepress_template_Template::META_LABELS}[org_tubepress_options_category_Meta::RATING]; ?></dt><dd class="tubepress_meta tubepress_meta_rating"><?php echo $video->getRatingAverage(); ?></dd>
          <?php endif; ?>
        
          <?php if (${org_tubepress_template_Template::META_SHOULD_SHOW}[org_tubepress_options_category_Meta::ID]): ?>
          
          <dt class="tubepress_meta tubepress_meta_id"><?php echo ${org_tubepress_template_Template::META_LABELS}[org_tubepress_options_category_Meta::ID]; ?></dt><dd class="tubepress_meta tubepress_meta_id"><?php echo $video->getId(); ?></dd>
          <?php endif; ?>
        
          <?php if (${org_tubepress_template_Template::META_SHOULD_SHOW}[org_tubepress_options_category_Meta::VIEWS]): ?>
          
          <dt class="tubepress_meta tubepress_meta_views"><?php echo ${org_tubepress_template_Template::META_LABELS}[org_tubepress_options_category_Meta::VIEWS]; ?></dt><dd class="tubepress_meta tubepress_meta_views"><?php echo $video->getViewCount(); ?></dd>
          <?php endif; ?>
        
          <?php if (${org_tubepress_template_Template::META_SHOULD_SHOW}[org_tubepress_options_category_Meta::UPLOADED]): ?>
          
          <dt class="tubepress_meta tubepress_meta_uploaddate"><?php echo ${org_tubepress_template_Template::META_LABELS}[org_tubepress_options_category_Meta::UPLOADED]; ?></dt><dd class="tubepress_meta tubepress_meta_uploaddate"><?php echo $video->getTimePublished(); ?></dd>
          <?php endif; ?>
          
          <?php if (${org_tubepress_template_Template::META_SHOULD_SHOW}[org_tubepress_options_category_Meta::DESCRIPTION]): ?>
          
          <dt class="tubepress_meta tubepress_meta_description"><?php echo ${org_tubepress_template_Template::META_LABELS}[org_tubepress_options_category_Meta::DESCRIPTION]; ?></dt><dd class="tubepress_meta tubepress_meta_description"><?php echo htmlspecialchars($video->getDescription(), ENT_QUOTES, "UTF-8"); ?></dd>
          <?php endif; ?>
          
        </dl>
      </div>
      <?php endforeach; ?>
    
    </div>
    <?php if (isset(${org_tubepress_template_Template::PAGINATION_BOTTOM})) : echo ${org_tubepress_template_Template::PAGINATION_BOTTOM}; endif; ?>
  </div>

  <?php if (isset(${org_tubepress_template_Template::SHORTCODE})): ?>
     <script type="text/javascript">function getUrlEncodedShortcodeForTubePressGallery<?php echo ${org_tubepress_template_Template::GALLERY_ID}; ?>(){return "<?php echo ${org_tubepress_template_Template::SHORTCODE}; ?>";}jQuery(document).ready(function(){TubePress.ajaxifyPaginationForGallery(<?php echo ${org_tubepress_template_Template::GALLERY_ID}; ?>);})</script>
  <?php endif; ?>

  <script type="text/javascript">
    jQuery(document).ready(function(){
        TubePress.centerThumbs("#tubepress_gallery_<?php echo ${org_tubepress_template_Template::GALLERY_ID}; ?>");
    });
  </script>
</div>
