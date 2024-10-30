<?php
/*
Plugin Name: Jokerz Joke of the Day
Plugin URI: http://www.jokerz.com/
Description: This plugin allows you to put a Jokerz.com joke of the day on your wordpress blog in any sidebar. Jokerz.com has over 30,000 jokes and the widget allows you to specify the maximum joke rating (kid-friendly, office-friendly, or more mature jokes) as well as chose to have the joke of the day be either our site's joke of the day or a randomly selected joke from one of our more than 600 categories. And best of all, the widget is completely free to use.
Version: 1.0
Author: Jawad Arshad
Author URI: http://spijko.com/
License: GPL2
*/

function includejavascript() {
	wp_enqueue_script("jquery");
	define( 'MY_PLUGIN_VERSION', '1.0' );
	  wp_enqueue_script( 'cat_subcat_js', plugins_url( '/js/cat_subcat_js.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ), MY_PLUGIN_VERSION, true );
	  
    
	
}
add_action('init','includejavascript');



class TodayJoke extends WP_Widget
{
  function TodayJoke()
  {
    $widget_ops = array('classname' => 'todayjoke', 'description' => 'Displays Joke from Jokers.com' );
    $this->WP_Widget('todayjokewidget', "Jokerz Joke of the Day", $widget_ops);
	
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'height' => '' ) );
    $height = $instance['height'];
	
	$instance = wp_parse_args( (array) $instance, array( 'width' => '' ) );
    $width = $instance['width'];

    $instance = wp_parse_args( (array) $instance, array( 'cscheme' => '' ) );
    $cscheme = $instance['cscheme'];

    $instance = wp_parse_args( (array) $instance, array( 'rjt' => '' ) );
    $rjt = $instance['rjt'];

	$instance = wp_parse_args( (array) $instance, array( 'gjd' => '' ) );
    $gjd = $instance['gjd'];
	
	$instance = wp_parse_args( (array) $instance, array( 'mcat' => '' ) );
    $mcat = $instance['mcat'];
	
	$instance = wp_parse_args( (array) $instance, array( 'scat' => '' ) );
    $scat = $instance['scat'];
	
	
?>

<p>
  <label for="<?php echo $this->get_field_id('height'); ?>">Height:
    <input class="widefat widget_height" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo attribute_escape($height); ?>" onkeypress="return isNumberKey(event);"  autocomplete="off" />
    <span style="color:#0074A2;">The pixel height of plugin, Default to Auto -1.</span> </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('width'); ?>">Width:
    <input class="widefat widget_width" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo attribute_escape($width); ?>" onkeypress="return isNumberKey(event);"  autocomplete="off" />
    <span style="color:#0074A2;">The pixel width of plugin, Default to 300px. </span> </label>
</p>
<p>
  <label  for="<?php echo $this->get_field_id('cscheme'); ?>">Color Scheme:
    <select class="widefat" id="<?php echo $this->get_field_id('cscheme'); ?>" name="<?php echo $this->get_field_name('cscheme'); ?>">
      <option value="1" <?php echo $cscheme == '1' ? 'selected="selected"':''; ?>>Blue Color</option>
      <option value="2" <?php echo $cscheme == '2' ? 'selected="selected"':''; ?>>Gray Color</option>
    </select>
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('rjt'); ?>">Restricted Joke To:
    <select class="widefat" id="<?php echo $this->get_field_id('rjt'); ?>" name="<?php echo $this->get_field_name('rjt'); ?>">
      <option value="1" <?php echo $rjt == '1' ? 'selected="selected"':''; ?> >All Jokes</option>
      <option value="2" <?php echo $rjt == '2' ? 'selected="selected"':''; ?>>All Jokes except Highly Dirty / Offensive</option>
      <option value="3" <?php echo $rjt == '3' ? 'selected="selected"':''; ?>>Office Friendly Jokes</option>
      <option value="4" <?php echo $rjt == '4' ? 'selected="selected"':''; ?>>Kid Friendly Jokes</option>
      <option value="5" <?php echo $rjt == '5' ? 'selected="selected"':''; ?>>Highly Dirty / Offensive Jokes</option>
    </select>
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('gjd'); ?>">Joke of the Day Type:
    <select class="widefat catclass" id="<?php echo $this->get_field_id('gjd'); ?>" name="<?php echo $this->get_field_name('gjd'); ?>">
      <option value="0" <?php echo $gjd == '0' ? 'selected="selected"':''; ?> >Select Category</option>
      <option value="1" <?php echo $gjd == '1' ? 'selected="selected"':''; ?>>Standard Jokerz Site Jokes of the Day</option>
      <option value="3" <?php echo $gjd == '3' ? 'selected="selected"':''; ?>>Random Joke from Selected Category</option>
    </select>
  </label>
  <script type="text/javascript">
		
		
		var checkit = window.check_var;
		if(checkit === undefined)
		{ 
			//file never entered. the global var was not set.
			window.check_var = 1;
		}
		else 
		{
			var timer = 0  , settimer = setInterval(function(){
			if(jQuery('#<?php echo $this->get_field_id( 'gjd' ); ?>').length)
			{
				clearInterval(settimer);
				var catclass = jQuery('#<?php echo $this->get_field_id( 'gjd' ); ?> option:selected').val();
				
				if(catclass == 3)
				{
					jQuery(".main_categories").show();
					jQuery(".sub_categories").show();
				}
			}
				else if( timer === 1000)
				{
					clearInterval(settimer);
				}
			timer++;
		},5);
		
		
	}
</script> 
</p>
<p  style="display:none;" class="main_categories">
  <label for="<?php echo $this->get_field_id('mcat'); ?>">Main Category:
    <select class="widefat subcat" id="<?php echo $this->get_field_id('mcat'); ?>" name="<?php echo $this->get_field_name('mcat'); ?>" >
      <option value="" <?php echo $mcat == ' ' ? 'selected="selected"':''; ?>>Select Category</option>
      <?php 
	$string = file_get_contents("http://staging.jokerz.com/widget-categories/get-all-categories-json/0");
	$json = json_decode($string, true);
	foreach ($json['categories'] as $category) {
	?>
      <option value="<?php echo $category['jokecategory_id'];?>" 
    <?php echo $mcat == $category['jokecategory_id'] ? 'selected="selected"':''; ?>> <?php echo $category['jokecategory_name'];?> </option>
      <?php } ?>
    </select>
  </label>
</p>
<p style="display:none;" class="sub_categories" > 
  <script type="text/javascript">
 

  function isNumberKey(evt)
	{
	  var charCode = (evt.which) ? evt.which : event.keyCode;
	  if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
		 return false;
	
	  return true;
	}
  function serverResponse(data) 
   {
     var html = '<option>Select Sub Category</option>';
     	jQuery.each(data.categories,function(index,cat){
			var subcat_id = '<?php echo $scat; ?>';
			sel = '';
			if(cat.jokecategory_id == subcat_id)
			{
				sel = 'selected';
			}
			html += '<option value="'+cat.jokecategory_id+'" '+sel+'>'+cat.jokecategory_name+'</option>';
     });
	 jQuery('.subsubclass').html(html);
   }
 		
    

	jQuery(document).ready(function(e) {
    	jQuery(".catclass").change(function(e) {
        	var res = jQuery(this).val();
			if(res==3)
			{
				jQuery(".main_categories").show();
			}
			else
			{
				jQuery(".main_categories").hide();
				jQuery(".sub_categories").hide();
			}
  	});
		
	jQuery(".subcat").change(function(e) {
        var res = jQuery(this).val();
		var cat_id = res;
		jQuery.getJSON('http://staging.jokerz.com/widget-categories/get-all-categories-json/'+cat_id+'?callback=?', {
		 format: "jsonp",
		},serverResponse);
		if(res)
		{
			jQuery(".sub_categories").show();
		}
		else
		{	
			jQuery(".sub_categories").hide();
		}
   });
   
    jQuery('.widget-control-save').on('click', function() 
	{

		{
			var height = jQuery("#<?php echo $this->get_field_id('height'); ?>").val();
			var width = parseInt(jQuery("#<?php echo $this->get_field_id('width'); ?>").val());
			if((height<200 || height>3000) && height!='')
			{
				alert('The height you have entered is invalid.  Valid heights are between 200 and 3000 pixels, or leave blank for auto-height.');
				jQuery("#<?php echo $this->get_field_id('height'); ?>").val('');
				jQuery("#<?php echo $this->get_field_id('height'); ?>").focus();
				return false;
			}
			if((width<200 || width>2000) && width!='')
			{
				alert('The width you have entered is invalid.  Valid widths are between 200 and 2000 pixels.');
				jQuery("#<?php echo $this->get_field_id('width'); ?>").val('');
				jQuery("#<?php echo $this->get_field_id('width'); ?>").focus();
				return false;
			}
		}
			
	});
   
});
</script>
  <label for="<?php echo $this->get_field_id('scat'); ?>">Sub Category(Optional):
    <select  class="widefat subsubclass" id="<?php echo $this->get_field_id('scat'); ?>" name="<?php echo $this->get_field_name('scat'); ?>">
    </select>
  </label>
</p>
<script type="text/javascript">
if(jQuery('#<?php echo $this->get_field_id('mcat'); ?>').length)
{
	var cat_id = jQuery("#<?php echo $this->get_field_id('mcat'); ?>").val();
	if(cat_id != '')
	{		
		jQuery.getJSON('http://staging.jokerz.com/widget-categories/get-all-categories-json/'+cat_id+'?callback=?', {
		 format: "jsonp",
		},serverResponse);
		
	}
}
</script>
<?php
  }
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance; 
    $instance['height']  = $new_instance['height'];
    $instance['width']   = $new_instance['width'];
	$instance['cscheme'] = $new_instance['cscheme'];
	$instance['rjt']     = $new_instance['rjt'];
	$instance['gjd']     = $new_instance['gjd'];
	$instance['mcat']    = $new_instance['mcat'];
	$instance['scat']    = $new_instance['scat'];		
	return $instance;
	
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $height    =   empty($instance['height'])    ?   '-1'     : apply_filters('widget_title', $instance['height']);
	$width     =   empty($instance['width'])     ?   ' '      : apply_filters('widget_title', $instance['width']);
 	$cscheme   =   empty($instance['cscheme'])   ?   ' '      : apply_filters('widget_title', $instance['cscheme']);
	$rjt       =   empty($instance['rjt'])       ?   ' '      : apply_filters('widget_title', $instance['rjt']);
	$gjd       =   empty($instance['gjd'])       ?   ' '      : apply_filters('widget_title', $instance['gjd']);
	$mcat      =   empty($instance['mcat'])      ?   ' '      : apply_filters('widget_title', $instance['mcat']);
	$scat      =   empty($instance['scat'])      ?   ' '      : apply_filters('widget_title', $instance['scat']);
	
    if (!empty($height) && !empty($width) )
      echo $before_title . 'Jokerz Joke of the Day' . $after_title;
	  echo '<script type="text/javascript" src="http://staging.jokerz.com/js/widget.js"></script>
			<script type="text/javascript">
    			JokerzJS.jod({
    			jokerzContainer : "jokerz-joke-of-the-day"
    		});
    		</script>';
      echo '<div class="widget-body" id="jokerz-joke-of-the-day" data-width="'.$width.'" data-height="'.$height.'" data-skin="'.$cscheme.'" data-restriction="'.$rjt.'" data-recommendation="'.$gjd.'" data-usercommonid="'.$mcat.'" data-categoryid="'.$scat.'"></div>';
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("TodayJoke");') );
?>
