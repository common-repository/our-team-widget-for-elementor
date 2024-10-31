<?php
namespace Elementor;   

// Create countdown timer category into elementor.
function init_fdterrago_team_box_category(){
    Plugin::instance()->elements_manager->add_category(
        'fd-name-category-box',
        [
            'title'  => 'Flickdevs',
            'icon' => 'font'
        ], 
		1
    );
}
add_action('elementor/init','Elementor\init_fdterrago_team_box_category');


?>