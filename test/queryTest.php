<?php

use PHPUnit\Framework\TestCase;

class queryTest extends myUnit {

	public function __construct() {
		//require_once __DIR__ . '/c.php';
		require_once 'query.php';
		$this->a = new Advertikon\Query();
	}

	public function test() {

		$this->clean();

		// Query::escape_db_name
		$this->assertEquals( $this->a->escape_db_name( 'foo' ), '`foo`' );
		$this->assertEquals( $this->a->escape_db_name( "`foo`" ), '`foo`' );
		$this->assertEquals( $this->a->escape_db_name( 'foo.bar' ), '`foo`.`bar`' );
		$this->assertEquals( $this->a->escape_db_name( 'foo.*.bar' ), '`foo`.*.`bar`' );
		$this->assertEquals( $this->a->escape_db_name( "foo'bar" ), "`foo\'bar`" );
		$this->assertEquals( $this->a->escape_db_name( array( 'foo', 'bar.*', "baz'") ), array( '`foo`', '`bar`.*', "`baz\'`" ) );

		// Query::escape_db_value
		$this->assertEquals( $this->a->escape_db_value( 'foo' ), "'foo'" );
		$this->assertEquals( $this->a->escape_db_value( "'foo'" ), "'foo'" );
		$this->assertEquals( $this->a->escape_db_value( '"foo"' ), "'foo'" );
		$this->assertEquals( $this->a->escape_db_value( '12' ), "'12'" );
		$this->assertEquals( $this->a->escape_db_value( "foo'bar" ), "'foo\'bar'" );
		$this->assertEquals( $this->a->escape_db_value( array( 'foo', 'bar.*', "baz'") ), array( "'foo'", "'bar.*'", "'baz\''" ) );
		$this->assertEquals( $this->a->escape_db_value( 'NULL' ), 'NULL' );

		// Query::escape_db
		$this->assertEquals( $this->a->escape_db( 'foo' ), "'foo'" );
		$this->assertEquals( $this->a->escape_db( "`foo`" ), "`foo`" );
		$this->assertEquals( $this->a->escape_db( '`foo.bar`' ), '`foo`.`bar`' );
		$this->assertEquals( $this->a->escape_db( "'foo'" ), "'foo'" );
		$this->assertEquals( $this->a->escape_db( array( 'foo', "'bar'", "`baz`") ), array( "'foo'", "'bar'", "`baz`" ) );
		$this->assertEquals( $this->a->escape_db( 'foo()' ), "'foo()'" );
		$this->assertEquals( $this->a->escape_db( 'count(*)' ), "COUNT( * )" );
		$this->assertEquals( $this->a->escape_db( "foo'bar" ), "'foo\'bar'" );
		$this->assertEquals( $this->a->escape_db( 'null' ), 'NULL' );

		// Query::is_db_function()
		$this->assertEquals( $this->a->is_db_function( 'count()' ), 'COUNT()' );
		$this->assertEquals( $this->a->is_db_function( 'count( * )' ), 'COUNT( * )' );
		$this->assertEquals( $this->a->is_db_function( 'now()' ), 'NOW()' );
		$this->assertEquals( $this->a->is_db_function( 'ASCII(2)' ), "ASCII( 2 )" );
		$this->assertEquals( $this->a->is_db_function( 'HEX(CHAR(256))' ), 'HEX( CHAR( 256 ) )' );
		$this->assertEquals( $this->a->is_db_function( 'CONCAT(\'My\', \'S\', \'QL\')' ), 'CONCAT( \'My\', \'S\', \'QL\' )' );

		$this->clean_end();

		$this->assertFalse( $this->a->is_db_function( 'foo()' ) );

		$this->clean();

		// Query::create_where_clause()
		$this->assertEquals(
			"WHERE `foo` = 'bar'",
			$this->a->create_where_clause(
				array(
					'field'     => 'foo',
					'operation' => '=',
					'value'     => 'bar',
				)
			)
		);

		$this->assertEquals(
			"WHERE `foo` = ''",
			$this->a->create_where_clause(
				array(
					'field'     => 'foo',
					'operation' => '=',
					'value'     => '',
				)
			)
		);

		$this->assertEquals(
			"WHERE `foo` = 'bar' AND `bar` = 'baz'",
			$this->a->create_where_clause(
				array(
					array(
						'field'     => 'foo',
						'operation' => '=',
						'value'     => 'bar',
					),
					array(
						'field'     => 'bar',
						'operation' => '=',
						'value'     => 'baz',
					)
				)
			)
		);

		$this->assertEquals(
			"WHERE `foo` = 'bar' OR `bar` = 'baz'",
			$this->a->create_where_clause(
				array(
					array(
						'field'     => 'foo',
						'operation' => '=',
						'value'     => 'bar',
					),
					array(
						'field'     => 'bar',
						'operation' => '=',
						'value'     => 'baz',
					),
					'glue' => 'or'
				)
			)
		);

		$this->assertEquals(
			"WHERE `foo` = 'bar' AND `bar` = 'baz'",
			$this->a->create_where_clause(
				array(
					array(
						'field'     => 'foo',
						'operation' => '=',
						'value'     => 'bar',
					),
					array(
						'field'     => 'bar',
						'operation' => '=',
						'value'     => 'baz',
					),
					'glue' => 'and',
				)
			)
		);

		$this->assertEquals(
			"WHERE `foo` <> NOW()",
			$this->a->create_where_clause(
				array(
					'field'     => 'foo',
					'operation' => '<>',
					'value'     => 'now()',
				)
			)
		);

		$this->assertEquals(
			"WHERE `foo` IN (1, 'a', COUNT( * ))",
			$this->a->create_where_clause(
				array(
					'field'     => 'foo',
					'operation' => 'in',
					'value'     => array( 1, 'a', 'count(*)' ),
				)
			)
		);

		$this->assertEquals(
			"WHERE `foo` IS NOT NULL",
			$this->a->create_where_clause(
				array(
					'field'     => 'foo',
					'operation' => '<>',
					'value'     => 'null',
				)
			)
		);

		$this->assertEquals(
			"WHERE `bar` IS NULL",
			$this->a->create_where_clause(
				array(
					'field'     => 'bar',
					'operation' => '=',
					'value'     => 'null',
				)
			)
		);

		$this->clean_end();

		$this->assertException( function() {
			$this->a->create_where_clause(
				array(
					'operation' => '',
					'field'     => 'foo',
					'value'     => 'bar',
				)
			);
		}, 'Advertikon\Exception' );

		$this->assertException( function() {
			$this->a->create_where_clause(
				array(
					'operation' => 'foo',
					'field'     => '',
					'value'     => 'bar',
				)
			);
		}, 'Advertikon\Exception' );

		$this->assertException( function() {
			$this->a->create_where_clause(
				array(
					'operation' => 'foo',
					'field'     => 'foo',
				)
			);
		}, 'Advertikon\Exception' );


		// Query::array_merge()
		$this->assertException( function() {
			$this->a->merge_where( '', array() );
		}, 'Advertikon\Exception' );

		$this->assertException( function() {
			$this->a->merge_where( array(), '' );
		}, 'Advertikon\Exception' );

		$this->assertException( function() {
			$this->a->merge_where( '', '' );
		}, 'Advertikon\Exception' );

		$this->clean();

		$this->assertEquals(
			$this->a->merge_where(
				array( 'foo' => 'bar' ),
				array( 'bar' => 'foo' )
			),
			array(
				array( 'foo' => 'bar' ),
				array( 'bar' => 'foo' ),
			)
		);

		$this->assertEquals(
			$this->a->merge_where(
				array(
					array( 'foo' => 'bar' ),
					array( 'baz' => 'boo' )
				),
				array( 'bar' => 'foo' )
			),
			array(
				array( 'foo' => 'bar' ),
				array( 'baz' => 'boo' ),
				array( 'bar' => 'foo' ),
			)
		);

		$this->assertEquals(
			$this->a->merge_where(
				array( 'bar' => 'foo' ),
				array(
					array( 'foo' => 'bar' ),
					array( 'baz' => 'boo' )
				)
			),
			array(
				array( 'bar' => 'foo' ),
				array( 'foo' => 'bar' ),
				array( 'baz' => 'boo' ),
			)
		);

		$this->assertEquals(
			$this->a->merge_where(
				array(
					array( 'foo' => 'bar' ),
					array( 'baz' => 'boo' )
				),
				array(
					array( 'boo' => 'bar' ),
					array( 'faz' => 'boo' )
				)
			),
			array(
				array( 'foo' => 'bar' ),
				array( 'baz' => 'boo' ),
				array( 'boo' => 'bar' ),
				array( 'faz' => 'boo' ),
			)
		);

		// Query::db_escape()
		$this->assertEquals( $this->a->db_escape( "'\r\n" ), "\'\\r\\n" );

		$this->clean_end();

		// Query::create_set_clause()
		$this->assertException( function() {
			$this->a->create_set_clause( 'foo' );
		}, 'Advertikon\Exception' );

		$this->assertEquals(
			"SET `foo`.`bar` = 'bar', `baz` = NOW()",
			$this->a->create_set_clause( array( 'foo.bar' => 'bar', 'baz' => 'now()' ) )
		);

		// Query::crate_on_clause()
		$this->assertException( function() {
			$this->a->create_on_clause( 'foo' );
		}, 'Advertikon\Exception' );

		$this->assertException( function() {
			$this->a->create_on_clause( array(
				'operation' => '',
				'left'      => '',
				'right'     => '',
			) );
		}, 'Advertikon\Exception' );

		$this->assertException( function() {
			$this->a->create_on_clause( array(
				'operation' => 'foo',
				'left'      => '',
				'right'     => '',
			) );
		}, 'Advertikon\Exception' );

		$this->assertException( function() {
			$this->a->create_on_clause( array(
				'operation' => 'foo',
				'left'      => 'bar',
			) );
		}, 'Advertikon\Exception' );

		$this->assertException( function() {
			$this->a->create_on_clause( array(
				'operation' => '',
				'left'      => '',
				'right'     => '',
			) );
		}, 'Advertikon\Exception' );

		$this->assertException( function() {
			$this->a->create_on_clause( array(
				'operation' => 'foo',
				'left'      => 'bar',
				'right'     => 'baz',
			) );
		}, 'Advertikon\Exception' );

		$this->clean();

		$this->assertEquals(
			$this->a->create_on_clause(
				array(
					'left'      => 'foo.foo',
					'operation' => '=',
					'right'     => '`bar`.`bar`',
				)
			),
			"ON(`foo`.`foo` = `bar`.`bar`)"
		);

		$this->assertEquals(
			$this->a->create_on_clause( array(
				array(
					'left'      => 'foo.foo',
					'operation' => '=',
					'right'     => '`bar`.`bar`',
				),
				array(
					'left'      => '`baz`.`baz`',
					'operation' => '<>',
					'right'     => '',
				)
			) ),
			"ON(`foo`.`foo` = `bar`.`bar` AND `baz`.`baz` <> '')"
		);

		$this->assertEquals(
			$this->a->create_on_clause( array(
				'glue' => 'OR',
				array(
					'left'      => 'foo.foo',
					'operation' => '=',
					'right'     => '`bar`.`bar`',
				),
				array(
					'left'      => '`baz`.`baz`',
					'operation' => '<>',
					'right'     => '',
				),
			) ),
			"ON(`foo`.`foo` = `bar`.`bar` OR `baz`.`baz` <> '')"
		);

		$this->assertEquals(
			$this->a->create_on_clause( array(
				'glue'      => 'BAR',
				array(
					'left'      => 'foo.foo',
					'operation' => '=',
					'right'     => '`bar`.`bar`',
				),
				array(
					'left'      => '`baz`.`baz`',
					'operation' => '<>',
					'right'     => '',
				)
			) ),
			"ON(`foo`.`foo` = `bar`.`bar` AND `baz`.`baz` <> '')"
		);

		// Query::get_calc_rows()
		ADK()->db->query( "SELECT SQL_CALC_FOUND_ROWS * FROM `" . DB_PREFIX . "order` LIMIT 0, 1" );
		$this->assertTrue( is_numeric( $this->a->get_calc_rows() ) );

		// Query::parse_sql_date()
		$str = '2000-10-12 10:00:01';
		$this->assertEquals(
			$this->a->parse_sql_date( $str ),
			array(
				'y' => 2000,
				'm' => 10,
				'd' => 12,
				'h' => 10,
				'i' => 0,
				's' => 1,
			)
		);

		$str = '2000-10-12 10:10';
		$this->assertEquals(
			$this->a->parse_sql_date( $str ),
			array(
				'y' => 2000,
				'm' => 10,
				'd' => 12,
				'h' => 10,
				'i' => 10,
				's' => 0,
			)
		);

		$str = '2000-10-12';
		$this->assertEquals(
			$this->a->parse_sql_date( $str ),
			array(
				'y' => 2000,
				'm' => 10,
				'd' => 12,
				'h' => 0,
				'i' => 0,
				's' => 0,
			)
		);

		$str = '2000-10-12 10';
		$this->assertEquals(
			$this->a->parse_sql_date( $str ),
			array(
				'y' => 0,
				'm' => 0,
				'd' => 0,
				'h' => 0,
				'i' => 0,
				's' => 0,
			)
		);

		// Query::sql_date_to_str()
		$arr = array(
			'y' => 200,
			'm' => 1,
			'd' => 2,
			'h' => 1,
			'i' => 3,
			's' => 2,
		);
		$this->assertEquals( $this->a->sql_date_to_str( $arr ), '0200-01-02 01:03:02' );

		// Query::sql_date_to_str()
		$arr = array();
		$this->assertEquals( $this->a->sql_date_to_str( $arr ), '0000-00-00 00:00:00' );

		// Query::compare_sql_date()
		$this->assertTrue(
			$this->a->compare_sql_date(
				array(
					'y' => 2000,
					'm' => 10,
					'd' => 2,
					'h' => 10,
					'i' => 12,
					's' => 2,
				),
				array(
					'y' => 2000,
					'm' => 10,
					'd' => 2,
					'h' => 10,
					'i' => 12,
					's' => 22,
				)
			) < 0
		);

		$this->assertTrue(
			$this->a->compare_sql_date(
				array(
					'y' => 2000,
					'm' => 10,
					'd' => 20,
					'h' => 10,
					'i' => 12,
					's' => 2,
				),
				array(
					'y' => 2000,
					'm' => 10,
					'd' => 2,
					'h' => 10,
					'i' => 12,
					's' => 22,
				)
			) > 0
		);

		$this->assertTrue(
			$this->a->compare_sql_date(
				array(
					'y' => 2000,
					'm' => 10,
					'd' => 2,
					'h' => 10,
					'i' => 12,
					's' => 22,
				),
				array(
					'y' => 2000,
					'm' => 10,
					'd' => 2,
					'h' => 10,
					'i' => 12,
					's' => 22,
				)
			) === 0
		);

		$this->assertTrue(
			$this->a->compare_sql_date(
				array(
					'y' => 2000,
					'm' => 10,
					'd' => 2,
					'i' => 12,
					's' => 2,
				),
				array(
					'y' => 2000,
					'm' => 10,
					'd' => 2,
					'h' => 10,
					'i' => 12,
					's' => 22,
				)
			) < 0
		);

		$this->assertTrue(
			$this->a->compare_sql_date(
				array(
					'y' => 2000,
					'm' => 10,
					'd' => 2,
					'h' => '10',
					'i' => 12,
					's' => 22,
				),
				array(
					'y' => 2000,
					'm' => 10,
					'd' => 2,
					'h' => 10,
					'i' => 12,
					's' => 22,
				)
			) === 0
		);

		$this->assertTrue(
			$this->a->compare_sql_date(
				array(
					'y' => 2000,
					'm' => 10,
					'd' => 'a',
					'h' => 10,
					'i' => 12,
					's' => 2,
				),
				array(
					'y' => 2000,
					'm' => 10,
					'd' => 2,
					'h' => 10,
					'i' => 12,
					's' => 22,
				)
			) < 0
		);

		$this->assertTrue(
			$this->a->compare_sql_date(
				array(
					'y' => 2000,
					'm' => 10,
					'd' => 2,
					'h' => 10,
					'i' => 12,
					's' => '22a',
				),
				array(
					'y' => 2000,
					'm' => 10,
					'd' => 2,
					'h' => 10,
					'i' => 12,
					's' => 22,
				)
			) === 0
		);

		// Query::create_value_set()
		$this->assertEquals(
			"('foo1', 'bar1')",
			implode( ', ',
				$this->a->create_value_set(
					array( 'foo1', ),
					array( 'bar1', )
				)
			)
		);

		$this->assertEquals(
			"('foo1', 'bar1', 'baz1')",
			implode( ', ',
				$this->a->create_value_set(
					array( 'foo1', ),
					array( 'bar1', ),
					array( 'baz1', )
				)
			)
		);

		$this->assertEquals(
			"('foo1', 'bar1', 'baz1'), ('foo2', 'bar2', 'baz2'), ('foo3', 'bar3', 'baz3')",
			implode( ', ',
				$this->a->create_value_set(
					array( 'foo1', 'foo2', 'foo3', ),
					array( 'bar1', 'bar2', 'bar3', ),
					array( 'baz1', 'baz2', 'baz3', )
				)
			)
		);

		$this->assertEquals(
			"('foo1', 'bar1', 'baz1'), ('foo2', 'bar2', 'baz2'), ('foo3', 'bar2', 'baz3')",
			implode( ', ',
				$this->a->create_value_set(
					array( 'foo1', 'foo2', 'foo3', ),
					array( 'bar1', 'bar2', ),
					array( 'baz1', 'baz2', 'baz3', )
				)
			)
		);

		$this->assertEquals(
			"('foo1', 'bar1', 'baz1'), ('foo1', 'bar2', 'baz2'), ('foo1', 'bar3', 'baz2')",
			implode( ', ',
				$this->a->create_value_set(
					array( 'foo1', ),
					array( 'bar1', 'bar2', 'bar3', ),
					array( 'baz1', 'baz2', )
				)
			)
		);

		$this->clean_end();

		// Query::create_values_clause()
		$this->assertException( function() {
			$this->a->create_values_clause( '' );
		}, 'Advertikon\Exception' );

		$this->assertException( function() {
			$this->a->create_values_clause( array() );
		}, 'Advertikon\Exception' );

		$this->assertException( function() {
			$this->a->create_values_clause( array( 'foo' => 'bar', 'boo', ) );
		}, 'Advertikon\Exception' );

		$this->clean();

		$this->assertEquals(
			"(`foo`) VALUES ('bar')",
			$this->a->create_values_clause(
				array(
					'foo' => 'bar',
				)
			)
		);

		$this->assertEquals(
			"(`foo`, `bazz`) VALUES ('bar', 'boo')",
			$this->a->create_values_clause(
				array(
					'foo'  => 'bar',
					'bazz' => 'boo',
				)
			)
		);

		$this->assertEquals(
			"(`foo`, `bazz`) VALUES ('bar', 'boo'), ('bar', 'bim')",
			$this->a->create_values_clause(
				array(
					'foo' => 'bar',
					'bazz' => array( 'boo', 'bim' ),
				)
			)
		);

		// Query::create_select_clause()
		$this->assertEquals(
			'*',
			$this->a->create_select_clause()
		);

		$this->assertEquals(
			'`foo`',
			$this->a->create_select_clause( '`foo`' )
		);

		$this->assertEquals(
			'`bar` as `foo`, NOW() as `boo`, (select * from foo) as `boom`, (select * form poo) as `foom`',
			$this->a->create_select_clause(
				array(
					'foo'  => '`bar`',
					'boo'  => 'now()',
					'boom' => '(select * from foo)',
					'foom' => 'select * form poo',
				)
			)
		);

		// Query::create_query()
		$this->assertEquals(
			"SELECT `baz` as `bar`, `boo`, COUNT( * ) FROM `". DB_PREFIX . "foo`",
			$this->a->create_query(
				array(
					'table'  => 'foo',
					'query'  => 'select',
					'field' => array(
						'bar' => '`baz`',
						'`boo`',
						'count(*)'
					),
				)
			)
		);

		$this->assertEquals(
			"SELECT * FROM `". DB_PREFIX . "foo`",
			$this->a->create_query(
				array(
					'table'  => 'foo',
					'query'  => 'select',
				)
			)
		);

		$this->assertEquals(
			"SELECT `baz` as `bar`, `boo` FROM `". DB_PREFIX . "foo` as `fuz`",
			$this->a->create_query(
				array(
					'table'  => array( 'fuz' => 'foo' ),
					'query'  => 'select',
					'field' => array(
						'bar' => '`baz`',
						'`boo`',
					),
				)
			)
		);

		$this->assertEquals(
			"SELECT `baz` as `bar`, `boo` FROM `". DB_PREFIX . "foo` as `fuz` RIGHT JOIN `" . DB_PREFIX . "joo` `aloo` USING(`joom`)" ,
			$this->a->create_query(
				array(
					'table'  => array( 'fuz' => 'foo' ),
					'query'  => 'select',
					'field' => array(
						'bar' => '`baz`',
						'`boo`',
					),
					'join' => array(
						'type'  => 'right',
						'table' => 'joo',
						'using' => 'joom',
						'alias' => 'aloo',
					)
				)
			)
		);

		$this->assertEquals(
			"SELECT `baz` as `bar`, `boo` FROM `". DB_PREFIX . "fo` as `f` RIGHT JOIN `" . DB_PREFIX . "joo` `aloo` ON(`l` = `r`) WHERE `baz` > NOW() OR `boo` IS NOT NULL ORDER BY `doo` DESC, `moo` ASC GROUP BY `boo` LIMIT 0, 1",
			$this->a->create_query(
				array(
					'table'  => array( 'f' => 'fo' ),
					'query'  => 'select',
					'field' => array(
						'bar' => '`baz`',
						'`boo`',
					),
					'join' => array(
						'type'  => 'right',
						'table' => 'joo',
						'on' => array(
							'left'      => 'l',
							'operation' => '=',
							'right'     => '`r`',
						),
						'alias' => 'aloo',
					),
					'where' => array(
						'glue' => 'OR',
						array(
							'field'     => 'baz',
							'operation' => '>',
							'value'     => 'now()',
						),
						array(
							'field'     => 'boo',
							'operation' => '<>',
							'value'     => 'null',
						),
					),
					'limit' => 1,
					'start' => 0,
					'order_by' => array(
						'doo' => 'desc',
						'moo',
					),
					'group_by' => 'boo',
				)
			)
		);

		$this->assertEquals(
			"INSERT INTO `". DB_PREFIX . "foo` (`foo`, `bar`) VALUES ('foo1', 'bar1'), ('foo2', 'bar2')",
			$this->a->create_query(
				array(
					'table' => 'foo',
					'query' => 'insert',
					'values' => array(
						'foo' => array( 'foo1', 'foo2' ),
						'bar' => array( 'bar1', 'bar2' ),
					),
				)
			)
		);

		$this->assertEquals(
			"INSERT INTO `". DB_PREFIX . "foo` (`foo`, `bar`) VALUES ('foo1', 'bar1'), ('foo2', 'bar2') ON DUPLICATE KEY UPDATE `foo` = 5, `bar` = `foos`, `dom` = NOW()",
			$this->a->create_query(
				array(
					'table' => 'foo',
					'query' => 'insert',
					'values' => array(
						'foo' => array( 'foo1', 'foo2' ),
						'bar' => array( 'bar1', 'bar2' ),
					),
					'on_duplicate' => array(
						'foo' => 5,
						'bar' => '`foos`',
						'dom' => 'now()'
					),
				)
			)
		);

		$this->assertEquals(
			"DELETE FROM `" . DB_PREFIX . "foo` WHERE `foo` = 'bar'",
			$this->a->create_query(
				array(
					'table' => 'foo',
					'query' => 'delete',
					'where' => array(
						'field'     => 'foo',
						'operation' => '=',
						'value'     => 'bar'
					),
				)
			)
		);

		$this->assertEquals(
			"UPDATE `" . DB_PREFIX . "foo` SET `foo` = 'bar', `foom` = `baz`",
			$this->a->create_query(
				array(
					'table' => 'foo',
					'query' => 'update',
					'set'   => array(
						'foo'  => 'bar',
						'foom' => '`baz`',
					),
				)
			)
		);

		$this->clean_end();

		// Query::run_query()
		$this->assertException( function() {
			$this->a->run_query( array() );
		}, 'Advertikon\Exception' );

		$this->clean();

		$this->assertInstanceOf( 'Advertikon\Db_Result', $this->a->run_query( "SHOW TABLES" ) );
		$this->assertInstanceOf(
			'Advertikon\Db_Result',
			$this->a->run_query(
				array(
					'table' => 'order',
					'query' => 'select',
					'limit' => 1,
				)
			)
		);

		$this->assertEquals(
			$this->a->run_query(
				array(
					'table' => 'order',
					'query' => 'insert',
					'values' => array( 'invoice_no' => '100' ),
				)
			),
			1,
			'Failed insert row in order table'
		);

		$id = ADK()->db->getLastId();

		$this->assertTrue( is_int( $id ), 'Failed assert that order ID is integer number' );

		$this->assertEquals(
			$this->a->run_query(
				array(
					'table' => 'order',
					'query' => 'delete',
					'where' => array(
						'field'     => 'order_id',
						'operation' => '=',
						'value'     => $id,
					),
				)
			),
			1,
			'Failed delete row from order table'
		);

		$this->assertNull(
			$this->a->run_query(
				array(
					'table' => 'order',
					'query' => 'delete',
					'where' => array(
						'field'     => 'order_id',
						'operation' => '=',
						'value'     => ( $id + 1 ),
					),
				)
			),
			'Failed to assert that attempt to delete unexisted row returns null'
		);

		$this->clean_end();
	}
}
