<?php
use PHPUnit\Framework\TestCase;

class rendererTest extends myUnit {

	public function __construct() {
		//require_once __DIR__ . '/c.php';
		require_once 'renderer.php';
		$this->a = new Advertikon\Renderer();
	}

	public function test() {

		$this->clean();

		// Renderer::render_panels_headers()
		$this->assertTrue( is_string( $this->a->render_panels_headers( array() ) ) );

		// Renderer::render_panel_header()
		$this->assertTrue( is_string( $this->a->render_panel_header( array() ) ) );

		// Renderer::render_element()
		$this->assertTrue( is_string( $this->a->render_element( array() ) ) );

		// Renderer::fetch_element_data()
		$this->assertTrue( is_array( $this->a->fetch_element_data( array() ) ) );

		// Renderer::render_input()
		$this->assertTrue( is_string( $this->a->render_input( array() ) ) );

		// Renderer::render_select()
		$this->assertTrue( is_string( $this->a->render_select( array() ) ) );

		// Renderer::render_button()
		$this->assertTrue( is_string( $this->a->render_button( array() ) ) );

		// Renderer::render_checkbox()
		$this->assertTrue( is_string( $this->a->render_checkbox( array() ) ) );

		// Renderer::render_form_group()
		$this->assertTrue( is_string( $this->a->render_form_group( array() ) ) );

		// Renderer::render_info_box()
		$this->assertTrue( is_string( $this->a->render_info_box( '' ) ) );

		// Renderer::render_tooltip()
		$this->assertTrue( is_string( $this->a->render_tooltip( array() ) ) );

		// Renderer::render_popover()
		$this->assertTrue( is_string( $this->a->render_popover( array() ) ) );

		// Renderer::render_input_group()
		$this->assertTrue( is_string( $this->a->render_input_group( array() ) ) );

		// Renderer::render_button_group()
		$this->assertTrue( is_string( $this->a->render_button_group( array() ) ) );

		// Renderer::render_addon()
		$this->assertTrue( is_string( $this->a->render_addon( array() ) ) );

		// Renderer::render_color()
		$this->assertTrue( is_string( $this->a->render_color( array() ) ) );

		// Renderer::render_image()
		$this->assertTrue( is_string( $this->a->render_image( array() ) ) );

		// Renderer::render_elfinder_image()
		$this->assertTrue( is_string( $this->a->render_elfinder_image( array() ) ) );

		// Renderer::render_textarea()
		$this->assertTrue( is_string( $this->a->render_textarea( array() ) ) );

		// Renderer::render_fancy_checkbox()
		$this->assertTrue( is_string( $this->a->render_fancy_checkbox( array() ) ) );

		// Renderer::render_dimension()
		$this->assertTrue( is_string( $this->a->render_dimension( array() ) ) );

		// Renderer::render_lang_set()
		$this->assertTrue( is_string( $this->a->render_lang_set( array() ) ) );

		// Renderer::compare_select_value()
		$this->assertTrue( $this->a->compare_select_value( '1', array( 1, 2, 3) ) );
		$this->assertTrue( $this->a->compare_select_value( 1, array( 1, 2, 3) ) );
		$this->assertFalse( $this->a->compare_select_value( '4', array( 1, 2, 3) ) );
		$this->assertFalse( $this->a->compare_select_value( 4, array( 1, 2, 3) ) );
		$this->assertFalse( $this->a->compare_select_value( '', array() ) );

		$this->clean_end();
	}
}
