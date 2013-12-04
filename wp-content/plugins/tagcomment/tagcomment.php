<?php
/*
Plugin Name: Tagcomment
Plugin URI: http://git.comum.org
Description: Tagging comments
Version: 0.1
Author: Thiago Silva
Author URI: http://git.comum.org
License: AGPLv3
*/

/*
test1:
  -save a comment with a list of tags
    -check each of the three tables
    -check for slug

test2:
  -save a comment with existing tags
    -check each of the three tables for duplicates
    -check that the new tags were inserted, and existing where used
    -check count++
*/

function tagcomment_make_slug($tag) {
  return preg_replace('/[^A-Za-z0-9]/','-',strtolower($tag));
}

function tagcomment_get_tag_by_name($name) {
  global $wpdb;

  $sql = $wpdb->prepare("
      SELECT term.*
      FROM {$wpdb->prefix}terms term,
          {$wpdb->prefix}term_taxonomy tax
      WHERE term.name = %s
            AND term.term_id = tax.term_id
            AND tax.taxonomy = 'comment_tag'", array($name));

  $res = $wpdb->get_results($sql);
  if (count($res) > 0) {
    return $res[0];
  } else {
    return null;
  }
}

function tagcomment_update_tag_count($term_id) {
  global $wpdb;
  $sql = $wpdb->prepare("SELECT * from {$wpdb->prefix}term_taxonomy where term_id=%d",array($term_id));
  $res = $wpdb->get_results($sql);
  $res[0]->count++;
  $wpdb->update("{$wpdb->prefix}term_taxonomy", $res[0], array('comment_ID' => $res[0]->comment_ID));
}

function tagcomment_create_tag($name) {
  global $wpdb;
  //1) create the new tag...
  $tag = array('name' => $name, 'slug' => tagcomment_make_slug($name));
  $wpdb->insert("{$wpdb->prefix}terms", $tag);

  $tag['term_id'] = $wpdb->insert_id;

  //2) create its taxonomy: 'comment_tag' with count=1
  $wpdb->insert("{$wpdb->prefix}term_taxonomy",
    array('term_id' => $tag['term_id'],
          'taxonomy' => 'comment_tag',
          'count' => 1));
}

function tagcomment_tag_comment($comment_id, $name) {
  //We assume $name is already a stored comment tag!

  global $wpdb;

  $sql = $wpdb->prepare("
      SELECT tax.term_taxonomy_id
      FROM {$wpdb->prefix}terms term,
           {$wpdb->prefix}term_taxonomy tax
      WHERE term.name = %s
            AND term.term_id = tax.term_id
            AND tax.taxonomy = 'comment_tag'", array($name));

  $res = $wpdb->get_results($sql);

  $tax_id = $res[0]->term_taxonomy_id;

  $wpdb->insert("{$wpdb->prefix}term_relationships",
    array('object_id' => $comment_id,
          'term_taxonomy_id' => $tax_id));
}


function tagcomment_store_comment($comment_id) {
  $names = explode(",", $_POST['tagcomment_tags']);
  if (count($names) == 0) {
    return;
  }

  global $wpdb;

  foreach ($names as $name) {
    $name = trim($name);
    $tag = tagcomment_get_tag_by_name($name);
    if ($tag) {
      tagcomment_update_tag_count($tag->term_id);
    } else {
      $tag = tagcomment_create_tag($name);
    }
    tagcomment_tag_comment($comment_id, $name);
  }
}
add_action('comment_post', 'tagcomment_store_comment', 0); //, [priority], [accepted_args] );
?>