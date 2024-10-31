<?php
/*
Plugin Name: print-tags
Plugin URI: http://yourdomain.com/
Description: Used to add a layout tag for news print
Version: 1.2
Author: Don Kukral
Author URI: http://yourdomain.com
License: GPL
*/

define( 'PRINT_TAGS_VERSION' , '1.0.2' );
define( 'PRINT_TAGS_URL' , plugins_url(plugin_basename(dirname(__FILE__)).'/') );

add_action('admin_menu', 'print_tags_add_custom_box');
add_action('admin_menu', 'print_tags_admin_menu');
add_action('admin_enqueue_scripts', 'print_tag_admin_scripts');
add_action('save_post', 'print_tags_update_tags');

function print_tag_admin_scripts( ) {
    wp_enqueue_style('print_tags-styles', PRINT_TAGS_URL.'/css/print_tags.css', false, false, 'all');
    wp_enqueue_script("jquery");
	
}

function print_tags_add_custom_box() {
    add_meta_box('printtagsdiv', __('Print Tags'), 'print_tags_meta_box', 'post', 'side', 'low');
}

function print_tags_admin_menu() {
    add_options_page('Print Tags', 'Print Tags', 'administrator',
        'print_tags', 'print_tags_settings_page');
}

function print_tags_settings_page() {
    
    $print_tag_options = get_option('print_tag_options');
    
    if ( isset($_POST['action']) && $_POST['action'] == 'update' ) {
        
        $print_tag_options['number_of_sections'] = $_POST['number_of_sections'];
        for ($i = 1; $i <= $print_tag_options['number_of_sections']; $i++) {
            $print_tag_options['section_name_' . $i] = $_POST['section_name_' . $i];
            $print_tag_options['page_count_' . $i] = $_POST['page_count_' . $i];            
        }
        update_option('print_tag_options', $print_tag_options);		
		echo '<div class="updated"><p>Print Tags Settings Updated</p></div>';
	}
?>
    

    <div class="wrap">
    <h2>Print Tags</h2>
    
    <form method="post">
    <?php wp_nonce_field('update-options'); ?>
    
    <table class="form-table">
    
    <tr valign="top">
    <th scope="row">Number of Sections</th>
    <td><input id="number_of_sections" type="text" name="number_of_sections" value="4" size="6" disabled="disabled"/></td>
    </tr>
    
    <tr valign="top">
    <th scrope="row">Section Name</th>
    <th scope="row">Pages</th>
    </tr>
    
    <tr valign="top">
    <td><input type="text" name="section_name_1" value="<?php echo $print_tag_options['section_name_1']; ?>" disabled="disabled"/></td>
    <td>
    <select name="page_count_1">
    <?php 
    $page_count_1 = $print_tag_options['page_count_1'];
    for ($i=1; $i <= 50; $i++) {
    ?>
        <option value="<?php echo $i; ?>"<?php if ($i == $page_count_1) { echo " selected"; }?>><?php echo $i; ?></option>
    <?php
    }
    ?>
    </select>
    </td>
    </tr>
    
    <tr valign="top">
    <td><input type="text" name="section_name_2" value="<?php echo $print_tag_options['section_name_2']; ?>" disabled="disabled"/></td>
    <td>
    <select name="page_count_2">
    <?php 
    $page_count_2 = $print_tag_options['page_count_2'];
    for ($i=1; $i <= 50; $i++) {
    ?>
    <option value="<?php echo $i; ?>"<?php if ($i == $page_count_2) { echo " selected"; }?>><?php echo $i; ?></option>
    <?php
    }
    ?>
    </select>
    </td>
    </tr>
    <tr valign="top">
    <td><input type="text" name="section_name_3" value="<?php echo $print_tag_options['section_name_3']; ?>" disabled="disabled"/></td>
    <td>
    <select name="page_count_3">
    <?php 
    $page_count_3 = $print_tag_options['page_count_3'];
    for ($i=1; $i <= 50; $i++) {
    ?>
    <option value="<?php echo $i; ?>"<?php if ($i == $page_count_3) { echo " selected"; }?>><?php echo $i; ?></option>
    <?php
    }
    ?>
    </select>
    </td>
    </tr>
    <tr valign="top">
    <td><input type="text" name="section_name_4" value="<?php echo $print_tag_options['section_name_4']; ?>" disabled="disabled"/></td>
    <td>
    <select name="page_count_4">
    <?php 
    $page_count_4 = $print_tag_options['page_count_4'];
    for ($i=1; $i <= 50; $i++) {
    ?>
       <option value="<?php echo $i; ?>"<?php if ($i == $page_count_4) { echo " selected"; }?>><?php echo $i; ?></option>
    <?php
    }
    ?>
    </select>
    </td>
    </tr>  
       
    
    </table>
    
    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="section_name_1,page_count_1,section_name_2,page_count_2,section_name_3,page_count_3,section_name_4,page_count_4" />
    <input type="hidden" name="number_of_sections" value="4"/>
    <input type="hidden" name="section_name_1" value="A"/>
    <input type="hidden" name="section_name_2" value="B"/>
    <input type="hidden" name="section_name_3" value="C"/>
    <input type="hidden" name="section_name_4" value="D"/>
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

    </form>
    </div>
    
    <script type="text/javascript">
        jQuery.noConflict();  
        jQuery(document).ready(function() {

            jQuery("input[name=number_of_sections]").change(function(event){
                alert(event.target.value);
            });
        });
    </script>
    
<?php
}


function print_tags_meta_box() {
    global $post;
    $print_tag_options = get_option('print_tag_options');

?>
    <div id="print-tags">
        <table id="print-tag-table">
<?php
        $print_tags = array();
        for ($i=1; $i<=$print_tag_options['number_of_sections']; $i++) {
?>
        <tr>
            <th class="print-tag-section">
                <?php echo $print_tag_options['section_name_' . $i]; ?>
            </th>
            
            <td class="print-tag-pages">
<?php
            for ($j=1; $j<=$print_tag_options['page_count_' . $i]; $j++) {
                $tag = $print_tag_options['section_name_' . $i] . $j;
                array_push($print_tags, $tag);
                echo "<span id=\"" . $tag . "\"> " . $j . " </span>";
            }
?>
            </td>
        </tr>
<?php
        }
?>  
        <?php 
        if (get_post_meta($post->ID, "e_section_date", true)) {
            $print_tag_date = strftime("%b %d %Y", strtotime(get_post_meta($post->ID, "e_section_date", true))); 
        } else {
            $print_tag_date = strftime("%b %d %Y", time());
        }
        ?>
        <tr>
        <td colspan="2">Print Date: <input type="text" class="date-pick" name="print-tag-date" value="<?php echo $print_tag_date; ?>"/></td>
        </tr>
        </table>
        
        <input type="hidden" name="print_tag" value=""/>
        
        <script type="text/javascript">
            jQuery.noConflict();
            jQuery(document).ready(function($){
                $('input[name=print-tag-date]').datepicker({
				dateFormat: 'M dd yy'
            			});
                $(".print-tag-pages span").click(function(e) {
                    $(".print-tag-pages span").css("color", "black");
                    $("#" + e.target.id.toUpperCase()).css("color", "red");
                    $(".print-tag-pages span").css("font-weight", "normal");
                    $("#" + e.target.id).css("font-weight", "bold");
                    $(":input[name=print_tag]").val(e.target.id);
                });
                
                <?php
                $post_tags = wp_get_post_terms($post->ID, 'post_tag');
                
                foreach ($post_tags as $post_tag) {
                    if (in_array(strtoupper($post_tag->name), $print_tags) == 1) {
                ?>
                $("#<?php echo strtoupper($post_tag->name); ?>").css("color", "red");
                $("#<?php echo strtoupper($post_tag->name); ?>").css("font-weight", "bold");
                $(":input[name=print_tag]").val("<?php echo $post_tag->name ?>");
                
                <?php
                    }
                }
                ?>
            });
        </script>
    </div>
<?php
}

function print_tags_update_tags($post_id) {
    if ($_POST['action'] == 'editpost') {
        if ((isset($_POST['print_tag'])) && ($_POST['print_tag'])) {
           $print_tag = $_POST['print_tag'];
           $form_tags = split(",", $_POST['tax_input']['post_tag']);
           $print_tags = print_tags_load();
           $cleaned_form_tags = array();
           foreach ($form_tags as $form_tag) {
               if (in_array($form_tag, $print_tags) != 1) {
                   array_push($cleaned_form_tags, $form_tag);                   
               }
           }
           array_push($cleaned_form_tags, $print_tag);
           
           foreach ($cleaned_form_tags as $form_tag) {
               if (!term_exists($form_tag, 'post_tag')) {
                   $term = wp_insert_term($form_tag, 'post_tag');
               }
           }
           wp_set_post_terms($post_id, $cleaned_form_tags, 'post_tag');
           $parts = preg_split('/(\d+)/', $print_tag, -1, PREG_SPLIT_DELIM_CAPTURE);
           update_post_meta($post_id, 'e_section', strtolower($parts[0]));
           update_post_meta($post_id, 'e_section_page', $parts[1]);
           
           if ((isset($_POST['print-tag-date'])) && ($_POST['print-tag-date'])) {
               update_post_meta($post_id, 'e_section_date', strftime("%Y-%m-%d", strtotime($_POST['print-tag-date'])));
            }
        }        
    }
}

function print_tags_load() {
    $print_tag_options = get_option('print_tag_options');
    $print_tags = array();
    for ($i=1; $i<=$print_tag_options['number_of_sections']; $i++) {
        for ($j=1; $j<=$print_tag_options['page_count_' . $i]; $j++) {
            $tag = $print_tag_options['section_name_' . $i] . $j;
            array_push($print_tags, $tag);
        }
    }
    return $print_tags;
}
?>
