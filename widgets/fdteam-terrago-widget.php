<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FD_TeamBox_Pro_Widget extends Widget_Base {

	public function get_name() { 		//Function for get the slug of the element name.
		return 'fd-team-box-widget';
	}

	public function get_title() { 		//Function for get the name of the element.
		return __( 'Team Box', 'fd-teambox-widget' );
	}
	public function get_icon() { 		//Function for get the icon of the element.
		return 'eicon-person';
	}	
	public function get_categories() { 		//Function for include element into the category.
		return [ 'fd-name-category-box' ];
	}	
    /* 
	 * Adding the controls fields for the countdown timer pro
	*/
	protected function register_controls() {
		$this->start_controls_section(
			'fdtb_pro_section',
			[
				'label' => __( 'Team Box', 'fd-teambox-widget' ),
			]
		);
		$this->add_control(  
			'fdtb_image',
			[
				'label' => __( 'Choose Image', 'fd-teambox-widget' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'fdtb_name',
			[
				'label'			=> __('Name', 'fd-teambox-widget'),
				'type'			=> Controls_Manager::TEXT,
				'default'		=> __('Dave Basil','fd-teambox-widget'),							
			]
		);
		$this->add_control(
			'fdtb_designation',
			[
				'label'			=> __('Designation', 'fd-teambox-widget'),
				'type'			=> Controls_Manager::TEXT,
				'default'		=> __('President & CEO','fd-teambox-widget'),							
			]
		);
		$this->add_control(
			'fdtb_description',
			[
				'label'			=> __('Description', 'fd-teambox-widget'),
				'type'			=> Controls_Manager::TEXTAREA,
				'default'		=> __('Dave Basil has more than 30 years of experience as a successful technology entrepreneur with a track record of building innovative, high-growth, market-leading software companies. He previously held senior executive positions at RedCloud Security (acquired by Avigilon), Metron Aviation (acquired by Airbus), ERA (acquired by SRA International), MainControl acquired by MRO Software), and General Electric.','fd-teambox-widget'),								
			]
		);		
		
		$this->end_controls_section(); 
		
	
		
		
		// Style Box
		$this->start_controls_section(   
		'fdtb_box_style_section',
			[
				'label' => __( 'General Style', 'fd-teambox-widget' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
            'fdtb_box_align',
                [
                    'label'         => esc_html__( 'Alignment', 'fd-teambox-widget' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options'       => [
                        'left'      => [
                            'title'=> esc_html__( 'Left', 'fd-teambox-widget' ),
                            'icon' => 'fa fa-align-left',
                            ],
                        'center'    => [
                            'title'=> esc_html__( 'Center', 'fd-teambox-widget' ),
                            'icon' => 'fa fa-align-center',
                            ],
                        'right'     => [
                            'title'=> esc_html__( 'Right', 'fd-teambox-widget' ),
                            'icon' => 'fa fa-align-right',
                            ],
                        ],
                    'toggle'        => false,
                    'default'       => 'left',
                    'selectors'     => [
                        '{{WRAPPER}} .fdtb_box-image-box-content' => 'text-align: {{VALUE}};',
                        
                    ],
                ]
        );		
		$this->add_control(
			'fdtb_box_bg_color',
			[
				'label' => __( 'Background Color', 'fd-teambox-widget' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
				],
				'default'       => '#088EB7',
				'selectors' => [
					'{{WRAPPER}} .team-box .fdtb_box-image-box-content' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .team-box .fdtb_box-widget-html' => 'background-color: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);
		$this->add_control(
			'fdtb_box_text_color',
			[
				'label' => __( 'Text Color', 'fd-teambox-widget' ),
				'type' => Controls_Manager::COLOR,
				'default'       => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .fdtb_box-image-box-content h3' => 'color: {{VALUE}};',
					'{{WRAPPER}} .fdtb_box-image-box-content p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'fdtb_box_border_color',
			[
				'label' => __( 'Border Color', 'fd-teambox-widget' ),
				'type' => Controls_Manager::COLOR,
				'default'       => '#56CCF2',
				'selectors' => [
					'{{WRAPPER}} .fdtb_box-image-box-description' => 'border-top: 1px solid {{VALUE}};',					
				],
			]
		);		
		
		$this->end_controls_section();		
		
	}
	
	/**
	 * Render countdown timer pro widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		$name = $settings['fdtb_name'];
		$designation = $settings['fdtb_designation'];
		$description = $settings['fdtb_description'];
		$this->add_render_attribute( 'wrapper', 'class', 'fd-image-box' );
		if ( ! empty( $settings['fdtb_image']['url'] ) ) {
			$this->add_render_attribute( 'fdtb_image', 'src', $settings['fdtb_image']['url'] );
		}
		?>
		<div id="fdtb-<?php echo esc_attr($this->get_id()); ?>" class="team-box fdtb_box-column fdcol-33" >
			<div class="fdtb_box-column-wrap  fdtb_box-element-populated">
				<div class="fdtb_box-widget-wrap">
					<div class="fdtb_box-widget-image-box">
						<div class="fdtb_box-widget-container">
							<div class="fdtb_box-image-box-wrapper">
								<figure class="fdtb_box-image-box-img">
									<img class="teambox-image" <?php echo $this->get_render_attribute_string( 'fdtb_image' ); ?>>
								</figure>
								<div class="fdtb_box-image-box-content">
									<h3 class="fdtb_box-image-box-title"><?php echo ' '.$name; ?></h3>
									<p class="fdtb_box-image-box-description"><?php echo ' '.$designation; ?></p>
								</div>
							</div>		
						</div>
					</div>
						
						
					<div class="fdtb_box-widget-html">
						<div class="fdtb_box-widget-container">
							<div class="fdtb_box-image-box-content fd-image-box-hover">
								<h3 class="fdtb_box-image-box-title"><?php echo ' '.$name; ?></h3>
								<p class="fdtb_box-image-box-description"><?php echo ' '.$designation; ?></p>
								<p class="fdtb_box-image-box-small-description"><?php echo ' '.$description; ?></p>
							</div>		
						</div>
					</div>
				</div>
			</div>
		</div>
				
		<?php
	}

    /**
	 * Render countdown widget pro output in the editor.	 
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function content_template() { 
		 
	}	
}
Plugin::instance()->widgets_manager->register( new FD_TeamBox_Pro_Widget() );