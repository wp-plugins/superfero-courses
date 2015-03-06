<?php
/*
* Superfero_Campaign_Widget Class
*/
class Superfero_Campaign_Widget extends WP_Widget
{
  /** 
   * constructor 
  */
  function Superfero_Campaign_Widget()
  {
    $widget_ops = array( 'classname' => 'Superfero_Campaign_Widget', 'description' => SUPERFERO_DESCRIPTION );
    $this->WP_Widget( 'Superfero_Campaign_Widget', SUPERFERO_TITLE, $widget_ops );
    
    add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
    add_action( 'wp_head', array( $this, 'add_css' ) );
  }
  
  /**
   * Print the widget's CSS in the HEAD section of the frontend
   *
   * @since 1.0
   */
  function add_css () {
    print '<style type="text/css">';
    print "\n";
    print '.superfero { margin: 0 0 .75em 0; }';
    print "\n";
    print '.superfero .title { font-size: 1em; margin: 0; }';
    print "\n";
    print '.superfero img { display: inline-block; float: left; margin: .3em .5em .5em 0; width: 100%; }';
    print "\n";
    print '</style>';
  }


  /** 
   * @see WP_Widget::form 
  */
  function form($instance)
  {
    global $option_number , $option_language;
    
    $instance = wp_parse_args( (array) $instance, array( 'title' => SUPERFERO_FORM_TITLE, 'author' => '', 'number' => SUPERFERO_FORM_NUMBER_COURSES, 'lang' => SUPERFERO_FORM_LANGUAGE_COURSE, 'view' => false ) );
    
    $title = $instance['title'];
    $number = $instance['number'];
    $lang = $instance['lang'];
    $author = $instance['author'];
    $view = $instance['view'];
?>
  <input class="widefat" id="<?php echo $this->get_field_id('lang'); ?>" name="<?php echo $this->get_field_name('lang'); ?>" type="hidden" value="" />
  <p>
    <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('author'); ?>">Author:</label>
    <input class="widefat" id="<?php echo $this->get_field_id('author'); ?>" name="<?php echo $this->get_field_name('author'); ?>" type="text" value="<?php echo attribute_escape($author); ?>" />
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('number'); ?>">Number of courses to show:</label> 
    <select name="<?php echo $this->get_field_name('number'); ?>" id="<?php echo $this->get_field_id('number'); ?>">
      <?php
      foreach ( $option_number as $option ) {
          echo '<option value="' . $option . '" id="' . $option . '"' , $number == $option ? ' selected="selected"' : '' , '>' , $option , '</option>';
      }
      ?>
    </select>
  </p>
  <p>
      <input id="<?php echo $this->get_field_id( 'view' ); ?>" name="<?php echo $this->get_field_name( 'view' ); ?>" type="checkbox" value="1" <?php checked( '1', $view ); ?>/>
      <label for="<?php echo $this->get_field_id( 'view' ); ?>">Show items in thumbnails view</label> 
  </p>
<?php
  }

  /** 
   * @see WP_Widget::update 
  */
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['number'] = $new_instance['number'];
    $instance['lang'] = $new_instance['lang'];
    $instance['view'] = $new_instance['view'];
    $instance['author'] = $new_instance['author'];
    
    $this->flush_widget_cache();

    return $instance;
  }
 
  /** 
   * @see WP_Widget::widget 
  */
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    
    $title = empty( $instance['title'] ) ? SUPERFERO_TITLE : apply_filters('widget_title', $instance['title']);
    $number = empty( $instance['number'] ) ? '5' : $instance['number'];
    $lang = empty( $instance['lang'] ) ? 'EN' : $instance['lang'];
    $view = empty( $instance['view'] ) ? '0' : $instance['view'];
    $author = empty( $instance['author'] ) ? '' : $instance['author'];
 
    if ( !empty( $title ) ) {
      echo $before_title . $title . $after_title;
    }
    
    $this->superfero_list( $author, $view, $lang, $number, $title, $view );
    
    echo $after_widget;
  }

  /** 
   * delete superfero widget cache 
  */
  function flush_widget_cache() {
    wp_cache_delete( 'superfero-courses', 'widget' );
  }

  /** 
   * superfero courses listing 
  */
  function superfero_list($author, $view, $lang, $num, $title) 
  {
    $superfero_api_url = SUPERFERO_URL . 'api/campaignwordpress?author=' . $author ;
    $response = wp_remote_retrieve_body( wp_remote_get( $superfero_api_url, array( 'sslverify' => false ) ) );
    
    if( !is_wp_error( $response ) ) {
      $data = json_decode( $response, true );
      
      if ( !empty( $data ) ) {
        if ( $data['result'] ) {
          
          if ( $view != 1 ) echo '<ul>';
          $i = 0;
          foreach( $data['courses'] as $item ) {
              $name = $item['name'];
              $deadline = $item['deadline'];
              $thumb = $item['thumb_url'];
              $i++;
              
              if ( empty( $item['availability'] ) ) {
                $url = SUPERFERO_URL . 'course/' . $item['slug'];
              } else {
                if ( $item['availability'] == 'programme' ) {
                  $url = SUPERFERO_URL . 'programme/' . $item['slug'];
                } else {
                  $url = SUPERFERO_URL . 'course/' . $item['slug'];
                }
              }
              if ( $i <= $num ) {
                if ( empty( $name ) ) $name = 'New course';
                if ( $view != 1 ) {
                  echo '<li>';
                  echo "<a href='$url' title='$title - $name' target='_blank'>" . $name . "</a> ";
                  echo '</li>';
                } else {
                  echo "<p class='superfero'>";
                  if ( !empty( $thumb ) ) {
                    echo "<a href='$url' title='' target='_blank'><img src='$thumb' alt='$title - $name' border='0' /></a> ";
                  }
                  echo "<a href='$url' title='$title - $name' target='_blank' class='title'>" . $name . "</a> ";
                  echo '</p>'; 
                } 
              }
          }
          
          if ( $view != 1 ) echo '</ul>';
        }
      }
    }
  }
}
?>