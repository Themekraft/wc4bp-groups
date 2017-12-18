<?php

class wc4bp_loader_test extends WP_UnitTestCase {
	
	function test_is_load(){
		$instance = wc4bp_groups::get_instance();
		$this->assertNotEmpty($instance);
	}
}