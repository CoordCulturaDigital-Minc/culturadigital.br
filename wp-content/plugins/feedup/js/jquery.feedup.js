jQuery(function(){
  jQuery(".republish, .mark_as_readed").click(function(){
    jQuery.post(jQuery(this).attr("href"), {ajax:1, category_id:jQuery(this).parent().next().find("select[@name='category_id'] option:selected").val()}, function(data){
      if(!isNaN(data))
        jQuery("#feedup-"+data).fadeOut();
      else
        alert(data);
    });
    
    return false;
  });
});
