<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package WP Poker Tournaments
 * @author Key Holdings Inc. <keyholding.randolf@gmail.com>
 * @license GPL-2.0+
 * @copyright 2013 Key Holdings Inc.
 */
 
//Save general setting 
if(isset($_POST['update_main_setting'])){

  if(!empty($_POST['pokertournaments_nofollow_link'])){
      if($_POST['pokertournaments_nofollow_link'] == 1){
        update_option( 'pokertournaments_nofollow', '1' );  
      }
  }else{
        update_option( 'pokertournaments_nofollow', '0' );
  }
  
  if(!empty($_POST['pokertournaments_table_style'])){
    $table_style = sanitize_text_field( $_POST['pokertournaments_table_style'] ); 
    $table_style = trim($table_style);
    update_option( 'pokertournaments_style', $table_style );
  }


  wp_redirect(home_url().'/wp-admin/options-general.php?page=wp-pokertournaments');

} 
 
 
//Save table setting 
if(isset($_POST['update_setting'])){

     $pokertip_active_hall = array();
      $pokertip_affil_link = array();
        $pokertip_time_offset = array();             
              
              $iterator = 1;
              $halls = get_option( 'poker_halls' );
                $hall_explode = explode('$',$halls);
                
                foreach($hall_explode as $hall_item){
                 $hall_line = explode(',',$hall_item);
                    if(trim($hall_line[0])!=''){
                    if(!empty($hall_line[2])){
                      $pokertournaments_affil_link[$hall_line[0]] = $hall_line[2];
                        $pokertournaments_active_hall[$hall_line[0]] = 1;
                        $pokertournaments_time_offset[$hall_line[0]] = sanitize_text_field( $_POST['hall_time'][$hall_line[0]] );
                    }else{
                      $pokertournaments_affil_link[$hall_line[0]] =  sanitize_text_field( $_POST['hall_affil_link'][$hall_line[0]] );
                        $pokertournaments_active_hall[$hall_line[0]] = sanitize_text_field( $_POST['hall_active'][$hall_line[0]] );
                        $pokertournaments_time_offset[$hall_line[0]] = sanitize_text_field( $_POST['hall_time'][$hall_line[0]] );
                        }
                    }
                 $iterator++; 
                }

  update_option( 'pokertournaments_active_hall', serialize($pokertournaments_active_hall) );
  update_option( 'pokertournaments_affil_link',  serialize($pokertournaments_affil_link) );
  update_option( 'pokertournaments_time_offset', serialize($pokertournaments_time_offset) );

  wp_redirect(home_url().'/wp-admin/options-general.php?page=wp-pokertournaments');

} 
 
 
 
 
 
 
?>

<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
  <div id="dashboard-widgets" class="metabox-holder"> 
    <div id="post-body"> 
      <div class="postbox-container" id="main-container" style="width:100%;height:57px;">      
        <div id="left-sortables" class="meta-box-sortables ui-sortable" style="height:47px;">
          <div class="postbox " style="display: block;">
            <h3><span><?php _e('Get our pro plugin with more game halls:  ','wp-pokertournaments'); ?></span> <a href="http://www.pokertip.cz/wp-poker-tournaments-en/" style="margin:0 0 0px 10px;text-decoration:none;" class="button-primary" ><?php _e('WP Pokertournaments PRO','wp-pokertournaments'); ?></a></h3>
          </div>
        </div>
      </div>
    </div>
  </div>
            
  <div id="dashboard-widgets" class="metabox-holder"> 
    <div id="post-body"> 
      <div class="postbox-container" id="main-container" style="width:100%">      
        <div id="left-sortables" class="meta-box-sortables ui-sortable">
          <div class="postbox " style="display: block;">
            <h3><span><?php _e('Poker rooms setting','wp-pokertournaments'); ?></span></h3>
            <div class="inside">
            <form action="" method="post">
              <table class="wp-list-table widefat fixed posts dad-list">
                <tr>
                  <th><?php _e('Poker room','wp-pokertournaments'); ?></th>
                  <th><?php _e('Affiliate link','wp-pokertournaments'); ?></th>
                  <th><?php _e('Active','wp-pokertournaments'); ?></th>
                  <th><?php _e('Affiliate program','wp-pokertournaments'); ?></th>
                  <th><?php _e('Timezone setting','wp-pokertournaments'); ?></th>
                </tr>
               
                <?php
                $iter = 1;
  $pokertournaments_active_hall = unserialize(get_option( 'pokertournaments_active_hall' ));
  $pokertournaments_affil_link = unserialize(get_option( 'pokertournaments_affil_link' ));
  $pokertournaments_time_offset = unserialize(get_option( 'pokertournaments_time_offset' ));
  
                $halls = get_option( 'poker_halls' );
                $hall_string =  explode('$',$halls);
                
                foreach($hall_string as $hall_line){
                $hall_explode = explode(',',$hall_line);
                if(trim($hall_explode[0]) !=''){
                ?>
                <tr>
                  <td><?php echo $hall_explode[0]; ?></td>
                  
                  <td><input type="text" placeholder="<?php _e('Set your affiliate link','wp-pokertournaments'); ?>" name="hall_affil_link[<?php echo $hall_explode[0]; ?>]" id="hall_affil_link[<?php echo $hall_explode[0]; ?>]" value="<?php echo $pokertournaments_affil_link[$hall_explode[0]]; ?>" /></td>
                  
                  <td><input type="checkbox" name="hall_active[<?php echo $hall_explode[0]; ?>]" id="hall_active[<?php echo $hall_explode[0]; ?>]" value="1" <?php if($pokertournaments_active_hall[$hall_explode[0]]=="1"){ echo 'checked';} ?> /></td>
                
                  
                  <td>
                  <?php if(trim($hall_explode[1])!=''){ ?>
                    <a href="<?php echo $hall_explode[1]; ?>">Join affiliate program</a>
                  <?php } ?>  
                  </td>
                  
                  <td><input type="text" placeholder="<?php _e(' Format: +1 or -1 hour','wp-pokertournaments'); ?>" name="hall_time[<?php echo $hall_explode[0]; ?>]" id="hall_time[<?php echo $hall_explode[0]; ?>]" value="<?php echo $pokertournaments_time_offset[$hall_explode[0]]; ?>" /></td>
                </tr>
                <?php
                $iter++;
                    }
                  }
                ?>    
              </table>
              <input type="hidden" name="update_setting" value="ok" />
              <p><input type="submit" name="pokertournaments-setting" id="pokertournaments-setting" class="button-primary" value="<?php _e('Save setting','wp-pokertournaments'); ?>"></p>
            </form>  
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  

<?php 
  
  $tab_style = get_option( 'pokertournaments_style' );
  $pokertournaments_nofollow = get_option( 'pokertournaments_nofollow' );
?>
  
  <div id="dashboard-widgets" class="metabox-holder"> 
    <div id="post-body"> 
      <div class="postbox-container" id="main-container" style="width:100%">      
        <div id="left-sortables" class="meta-box-sortables ui-sortable">
          <div class="postbox " style="display: block;">
            <h3><span><?php _e('Global setting','wp-pokertournaments'); ?></span></h3>
            <div class="inside">
              <form method="post" action="">
                <p style="margin-top:30px;margin-bottom:20px;"><input type="checkbox" name="pokertournaments_nofollow_link" id="pokertournaments_nofollow_link" value="1" <?php if(!empty($pokertournaments_nofollow) && $pokertournaments_nofollow == '1'){ echo 'checked="checked"'; } ?> />&nbsp;<?php _e('Add rel="nofollow" attribute hyperlinks','wp-pokertournaments'); ?></p>
                <p style="font-weight:bold;"><?php _e('Custom table styles (add css style in textarea)','wp-pokertournaments'); ?></p>
                <textarea name="pokertournaments_table_style" id="pokertournaments_table_style" style="width:100%;height:200px;">
                <?php echo $tab_style; ?>
                </textarea>
                              
                <input type="hidden" name="update_main_setting" value="ok" />
              <p><input type="submit" name="pokertournaments-setting" id="pokertournaments-setting" class="button-primary" value="<?php _e('Save setting','wp-pokertournaments'); ?>"></p>              
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  
  
  
  <div id="dashboard-widgets" class="metabox-holder"> 
    <div id="post-body"> 
      <div class="postbox-container" id="main-container" style="width:100%">      
        <div id="left-sortables" class="meta-box-sortables ui-sortable">
          <div class="postbox " style="display: block;">
            <h3><span><?php _e('Shortcodes and styling','wp-pokertournaments'); ?></span></h3>
            <div class="inside">
              <p><strong><?php _e('Post and page usage','wp-pokertournaments'); ?>:</strong></p>
              <p>[poker-freerolls] <?php _e('and','wp-pokertournaments'); ?> [poker-tournaments] (<?php _e('on posts or pages','wp-pokertournaments'); ?>)</p>
              <p><?php _e('Use limit attribute','wp-pokertournaments'); ?> [poker-freerolls limit="20"]</p>
              <p><strong><?php _e('Theme usage','wp-pokertournaments'); ?>:</strong></p>
              <p> echo do_shortcode('[poker-freerolls]'); <?php _e('and','wp-pokertournaments'); ?> echo do_shortcode('[poker-tournaments]');</p>
              <p><strong><?php _e('Use classes for custom styling tables','wp-pokertournaments'); ?>:</strong></p>
              <p><?php _e('Table','wp-pokertournaments'); ?>: class="pokertournaments/premium-pokertournaments"</p>
              <p><?php _e('Table head','wp-pokertournaments'); ?>:<br />
                 tr class="table-top"<br />
                 td class="table-top-time"<br />
                 td class="table-top-game"<br />
                 td class="table-top-site"<br />
                 td class="table-top-type"
               </p>
               <p><?php _e('Table line','wp-pokertournaments'); ?>:<br />
                 tr class="table-line"<br />
                 td class="table-line-time"<br />
                 td class="table-line-game"<br />
                 td class="table-line-site"<br />
                 td class="table-line-type"
               </p>
              <p><?php _e('Notice','wp-pokertournaments'); ?>: class="pt-notice"</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  
  
  
  
  
  
  
  
  
  
  

</div>
