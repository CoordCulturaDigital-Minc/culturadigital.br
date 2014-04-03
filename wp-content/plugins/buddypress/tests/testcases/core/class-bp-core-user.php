<?php

/**
 * @group core
 */
class BP_Tests_BP_Core_User_TestCases extends BP_UnitTestCase {
	protected $old_current_user = 0;

	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function test_get_users_with_exclude_querystring() {
		$u1 = $this->create_user();
		$u2 = $this->create_user();
		$u3 = $this->create_user();

		$exclude_qs = $u1 . ',junkstring,' . $u3;

		$users = BP_Core_User::get_users( 'active', 0, 1, 0, false, false, true, $exclude_qs );
		$user_ids = wp_parse_id_list( wp_list_pluck( $users['users'], 'id' ) );

		$this->assertEquals( array( $u2 ), $user_ids );
	}

	public function test_get_users_with_exclude_array() {
		$u1 = $this->create_user();
		$u2 = $this->create_user();
		$u3 = $this->create_user();

		$exclude_array = array(
			$u1,
			'junkstring',
			$u3,
		);

		$users = BP_Core_User::get_users( 'active', 0, 1, 0, false, false, true, $exclude_array );
		$user_ids = wp_parse_id_list( wp_list_pluck( $users['users'], 'id' ) );

		$this->assertEquals( array( $u2 ), $user_ids );
	}

	public function test_get_users_with_include_querystring() {
		$u1 = $this->create_user( array(
			'last_activity' => gmdate( 'Y-m-d H:i:s' ),
		) );
		$u2 = $this->create_user( array(
			'last_activity' => gmdate( 'Y-m-d H:i:s', time() - 1000 ),
		) );
		$u3 = $this->create_user( array(
			'last_activity' => gmdate( 'Y-m-d H:i:s', time() - 50 ),
		) );

		$include_qs = $u1 . ',junkstring,' . $u3;

		$users = BP_Core_User::get_users( 'active', 0, 1, 0, $include_qs );
		$user_ids = wp_parse_id_list( wp_list_pluck( $users['users'], 'id' ) );

		$this->assertEquals( array( $u1, $u3 ), $user_ids );
	}

	public function test_get_users_with_include_array() {
		$u1 = $this->create_user( array(
			'last_activity' => gmdate( 'Y-m-d H:i:s' ),
		) );
		$u2 = $this->create_user( array(
			'last_activity' => gmdate( 'Y-m-d H:i:s', time() - 1000 ),
		) );
		$u3 = $this->create_user( array(
			'last_activity' => gmdate( 'Y-m-d H:i:s', time() - 50 ),
		) );


		$include_array = array(
			$u1,
			'junkstring',
			$u3,
		);

		$users = BP_Core_User::get_users( 'active', 0, 1, 0, $include_array );
		$user_ids = wp_list_pluck( $users['users'], 'id' );

		// typecast...ugh
		$user_ids = array_map( 'intval', $user_ids );

		$this->assertEquals( array( $u1, $u3 ), $user_ids );
	}

	public function test_get_specific_users() {
		$u1 = $this->create_user();
		$u2 = $this->create_user();
		$u3 = $this->create_user();

		$include_array = array(
			$u1,
			'junkstring',
			$u3,
		);

		$users = BP_Core_User::get_specific_users( $include_array );
		$user_ids = wp_parse_id_list( wp_list_pluck( $users['users'], 'id' ) );

		$this->assertEquals( array( $u1, $u3 ), $user_ids );
	}



}
