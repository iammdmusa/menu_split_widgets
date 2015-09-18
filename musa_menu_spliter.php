<?php

/*
Plugin Name: Menu Spliter
Plugin URI: https://github.com/iammdmusa/menu_split_widgets
Description: Split you custom widgets menu using this plugins
Author: Shuvo Musa
Version: 0.1
Author URI: http://www.shuvomusa.me/
*/

class musaCustomMenuSplitWidget extends WP_Widget{
    function __construct() {
        parent::__construct(
            'musaCustomMenuSplitWidget',__('musa: Footer Menu', 'musa'),
            array( 'description' => __( 'Use Secondary Menu For this Menu Split', 'musa' ), )
        );
    }
    //Widget Front View
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        $menu_name = $instance['menu_name'];

        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo '<h3 class="under-border">'.$title.'</h3>';

        $menu_name = $menu_name;
        if(($locations = get_nav_menu_locations()) && isset($locations[$menu_name])){
            $menu = wp_get_nav_menu_object($locations[$menu_name]);
            $menu_items = wp_get_nav_menu_items($menu->term_id);

            //Create a new array with just the top level objects
            $newMenu = array();
            foreach($menu_items as $item){
                if($item->menu_item_parent != 0) continue;
                array_push($newMenu, $item);
            }

            //Split menu array in half
            $len = count($newMenu);
            $firsthalf = array_slice($newMenu, 0, $len / 2);
            $secondhalf = array_slice($newMenu, $len / 2);

            //Create left menu
            echo '<div class="row third-footer-tag">';
                echo '<div class="col-xs-12 col-md-6"><ul>';
                    foreach($firsthalf as $item){
                        echo '<li><a href="'.$item->url.'">'.$item->title.'</a></li>';
                    }
                echo '</ul></div>';
                echo '<div class="col-xs-12 col-md-6"><ul>';
                    foreach($secondhalf as $item){
                        echo '<li><a href="'.$item->url.'">'.$item->title.'</a></li>';
                    }
                echo '</ul></div>';
            echo '</div>';
        }


        echo $args['after_widget'];
    }

    // Widget Backend
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'wpb_widget_domain' );
        }
        $menu_name = 'secondary_menu';
        if(isset($instance['menu_name'])) {
            $menu_name = $instance['menu_name'];
        }

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','musa'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_name( 'menu_name' ); ?>"><?php _e('Menu Name :','musa'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'menu_name' ); ?>" name="<?php echo $this->get_field_name( 'menu_name' ); ?>" type="text" value="<?php echo esc_attr($menu_name);?>" />
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['menu_name'] = strip_tags($new_instance['menu_name']);
        return $instance;
    }


}
function musa_custom_menu_split_register(){
    register_widget('musaCustomMenuSplitWidget');
}
add_action('widgets_init','musa_custom_menu_split_register');