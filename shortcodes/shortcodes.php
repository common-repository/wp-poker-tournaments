<?php
/**
 * WP Pokertip shortcodes
 *
 * @package WP Poker Tournaments
 * @author Key Holdings Inc. <keyholding.randolf@gmail.com>
 * @license GPL-2.0+
 * @copyright 2013 Key Holdings Inc.
 */

add_shortcode('poker-freerolls','wp_pokertournaments_freerolls');
add_shortcode('poker-tournaments','wp_pokertournaments_tournaments');

/**
 *
 * Display freerolls tournament
 *
 */   
function wp_pokertournaments_freerolls($atts){
  //define array
  $poker = array();  
  $pokertournaments_active_hall = unserialize(get_option( 'pokertournaments_active_hall' ));
  
  if(empty($pokertournaments_active_hall)){
  $html = '<p>'.__('You must save plugin setting first!','wp-pokertournaments').'</p>'; 
  }else{
  
  $halls = '';
  
  if(isset($atts['limit'])){ $limit = $atts['limit']+1; }else{ $limit = 1000; }
  
  foreach($pokertournaments_active_hall as $key => $item){
    if($item == '1'){ $halls .= $key.','; }    
  }
  
  //Get data for table
  $url = 'http://www.pokertip.cz/phpturnaje/tourments.php?type=free&halls='.$halls.'&web='.$_SERVER['HTTP_HOST'].'&lang='.WPLANG;
  $resource = file_get_contents($url);
  
  
  //Get links option
  $links = unserialize(get_option('pokertournaments_affil_link'));
  //Get time_offset
  $time_offset = unserialize(get_option('pokertournaments_time_offset'));
  //Get nofollow option
  $nofollow = get_option('pokertournaments_nofollow');
  if($nofollow==1){$nofollow_text = 'rel="nofollow"';}else{$nofollow_text = '';}
    
  //Define ouput variable
  $html = '';
  //Get notice
  $note = explode('n@n',$resource);
  $notice = $note[0]; 
  $style = get_option( 'pokertournaments_style' );
  
  $html .= '<style>'.$style.'</style>';
  $html .= '<table class="pokertournaments">';
  $html .= '
            <tr class="table-top">
              <td class="table-top-time">'.__('Time','wp-pokertournaments').'</td>
              <td class="table-top-game">'.__('Game','wp-pokertournaments').'</td>
              <td class="table-top-site">'.__('Site','wp-pokertournaments').'</td>
              <td class="table-top-type">'.__('Game type','wp-pokertournaments').'</td>
            </tr>
  ';
  
  //Get table lines
  $lines = explode('|@|',$note[1]);
    $iter=1;
    foreach($lines as $line){
      if(trim($line)!=''){
      
      //Get current date
      $today = StrFTime("%Y-%m-%d", current_time( 'timestamp' ));
      $midnight = $today.' 23:59:59';
      //Get line items
      $line_items = explode('@',$line);
      if($line_items[2]=='PokerStars'){$line_items[2]='Pokerstars';}
      
      //If set time offset - create new time
      if(trim($time_offset[$line_items[2]]!='')){
      $set = $time_offset[$line_items[2]]*60*60;
      $line_items[0] = $line_items[0]+$set;
      }
      
      $line_time = StrFTime("%Y-%m-%d %H:%M:%S", $line_items[0]);
      $time_display = StrFTime("%H:%M", $line_items[0]);
      $time_now = StrFTime("%H:%M", current_time( 'timestamp' ));
      //Display tournaments before midnight
      if($line_time < $midnight){
      
      //Display only tournaments starts now (minus hour fix)
      if($time_display > $time_now){
      
      //Display only limit results
      if($iter<$limit){
      //Set all into array
            
      $poker[] = array(
                'time' => $time_display,
                'tournament' => $line_items[1],
                'game_hall' => $links[$line_items[2]],
                'game_link' => $line_items[2],                
                'game' => $line_items[3]                                
                );
          
          }
          $iter++;  
          }
        }      
      }
    } 
    
  foreach ($poker as $key => $row) {
    $time[$key]  = $row[0];
    $tournament[$key] = $row[1];
    $game_hall[$key] = $row[2];
    $game_link[$key] = $row[2];
    $game[$key] = $row[2];        
}

array_multisort($time, SORT_DESC, $poker);  
                
    
  foreach($poker as $key => $item){
  
  
  $html .= '<tr class="table-line">';
      
           $html .= '<td class="table-line-time">'.$item['time'].'</td>';
           $html .= '<td class="table-line-game">'.$item['tournament'].'</td>';
           $html .= '<td class="table-line-site"><a href="'.$item['game_hall'].'" target="_blank" '.$nofollow_text.'>'.$item['game_link'].'</a></td>';
           $html .= '<td class="table-line-type">'.$item['game'].'</td>';    
      $html .= '</tr>';
  
  
  }  
    
  $poker = array();
  $time = array();
  $tournament = array();
  $game_hall = array();
  $game_link = array();
  $game = array();
    
                                           
  $html .= '</table>';  
  
  }
  //Display notice
  $html .= '<p class="pt-notice">'.$notice.'</p>';
  
  return $html; 
    
}

/**
 *
 * Display paid tournament
 *
 */   
function wp_pokertournaments_tournaments($atts){
  $poker = array();
  $pokertournaments_active_hall = unserialize(get_option( 'pokertournaments_active_hall' ));
  
  if(empty($pokertournaments_active_hall)){
  $html = '<p>'.__('You must save plugin setting first!','wp-pokertournaments').'</p>'; 
  }else{
  
  $halls = '';
  
  if(isset($atts['limit'])){ $limit = $atts['limit']+1; }else{ $limit = 1000; }
  
  foreach($pokertournaments_active_hall as $key => $item){
    if($item == '1'){ $halls .= $key.','; }    
  }
  //Get data for table
  $url = 'http://www.pokertip.cz/phpturnaje/tourments.php?type=paid&halls='.$halls.'&web='.$_SERVER['HTTP_HOST'].'&lang='.WPLANG;
  $resource = file_get_contents($url);
   
  //Get links option
  $links = unserialize(get_option('pokertournaments_affil_link'));
  //Get time_offset
  $time_offset = unserialize(get_option('pokertournaments_time_offset'));
  //Get nofollow option
  $nofollow = get_option('pokertournaments_nofollow');
  if($nofollow==1){$nofollow_text = 'rel="nofollow"';}else{$nofollow_text = '';}
    
  //Define ouput variable
  $html = '';
  //Get notice
  $note = explode('n@n',$resource);
  $notice = $note[0]; 
  $style = get_option( 'pokertournaments_style' );
  
  $html .= '<style>'.$style.'</style>';
  $html .= '<table class="premium-pokertournaments">';
  $html .= '
            <tr class="table-top">
              <td class="table-top-time">'.__('Time','wp-pokertournaments').'</td>
              <td class="table-top-game">'.__('Game','wp-pokertournaments').'</td>
              <td class="table-top-site">'.__('Site','wp-pokertournaments').'</td>
              <td class="table-top-type">'.__('Game type','wp-pokertournaments').'</td>
            </tr>
  ';
  
  //Get table lines
  $lines = explode('|@|',$note[1]);
      $iter=1;
    foreach($lines as $line){
      if(trim($line)!=''){
      
      //Get current date
      $today = StrFTime("%Y-%m-%d", current_time( 'timestamp' ));
      $midnight = $today.' 23:59:59';
      
      //Get line items
      $line_items = explode('@',$line);
      
      if($line_items[2]=='PokerStars'){$line_items[2]='Pokerstars';}
      
      //If set time offset - create new time
      if(trim($time_offset[$line_items[2]]!='')){
      $set = $time_offset[$line_items[2]]*60*60;
      $line_items[0] = $line_items[0]+$set;
      }
      
      $line_time = StrFTime("%Y-%m-%d %H:%M:%S", $line_items[0]);
      $time_display = StrFTime("%H:%M", $line_items[0]);
      $time_now = StrFTime("%H:%M", current_time( 'timestamp' ));
      
      
      if($line_time < $midnight){
      
      //Display only tournaments starts now (minus hour fix)
      if($time_display > $time_now){
      
      if($iter<$limit){ 
      
           $poker[] = array(
                'time' => $time_display,
                'tournament' => $line_items[1],
                'game_hall' => $links[$line_items[2]],
                'game_link' => $line_items[2],
                'game' => $line_items[3]
                );
 
                        
        
          }
          $iter++;
          }
        }
      }
    } 
    
    
    
  foreach ($poker as $key => $row) {
    $time[$key]  = $row[0];
    $tournament[$key] = $row[1];
    $game_hall[$key] = $row[2];
    $game_link[$key] = $row[2];
    $game[$key] = $row[2];
}

array_multisort($time, SORT_DESC, $poker);  
    
    
  foreach($poker as $key => $item){
  
  
  $html .= '<tr class="table-line">';
      
           $html .= '<td class="table-line-time">'.$item['time'].'</td>';
           $html .= '<td class="table-line-game">'.$item['tournament'].'</td>';
           $html .= '<td class="table-line-site"><a href="'.$item['game_hall'].'" target="_blank" '.$nofollow_text.'>'.$item['game_link'].'</a></td>';
           $html .= '<td class="table-line-type">'.$item['game'].'</td>';    
      $html .= '</tr>';
  
  
  }  
  $poker = array();
  $time = array();
  $tournament = array();
  $game_hall = array();
  $game_link = array();
  $game = array();
                                
  $html .= '</table>';
  
  }
    
  $html .= '<p class="pt-notice">'.$notice.'</p>';
  return $html;
}