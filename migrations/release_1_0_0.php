<?php
/**
*
* @package phpBB Extension - Orthohin Adman
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace orthohin\adman\migrations;

class release_1_0_0 extends \phpbb\db\migration\migration
{
	// public function effectively_installed()
	// {
	// 	return $this->db_tools->sql_column_exists($this->table_prefix . 'orthohin_adman', 'adman_id');
	// }

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v31x\v314');
	}


	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'orthohin_adman'	=> array(
					'COLUMNS'		=> array(
						'adman_id'				=> array('UINT', null, 'auto_increment'),
						'event_name'		=> array('VCHAR:255', 'unique'),
						'ad_code'			=> array('VCHAR:1000', ''),
						'ad_enabled'		=> array('BOOL', 0),
					),
					'PRIMARY_KEY'	=> 'adman_id',
				),
			),
			'add_columns'	=> array(
				$this->table_prefix . 'users'			=> array(
					'user_orthohin'				=> array('UINT', 0),
				),
			),
		);
	}


	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'users'			=> array(
					'user_orthohin',
				),
			),
			'drop_tables'		=> array(
				$this->table_prefix . 'orthohin_adman',
			),
		);
	}

	public function update_data()
	{
		return array(

			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_DEMO_TITLE'
			)),
			array('module.add', array(
				'acp',
				'ACP_DEMO_TITLE',
				array(
					'module_basename'	=> '\orthohin\adman\acp\main_module',
					'modes'				=> array('settings'),
				),
			)),
			array('custom', array(array($this, 'add_default_values'))),
		);
	}

	public function add_default_values()
	{
		$data	 =	array(
				array(
					"adman_id"			=> 1,
					"event_name"		=> "overall_header_body_before",
					"ad_code"			=> "",
					"ad_enabled"		 => 0,
				),
				array(
					"adman_id"			=> 2,
					"event_name"		=> "overall_header_page_body_before",
					"ad_code"			=> "<center><img src='http://placehold.it/728x90'></center>",
					"ad_enabled"		 => 1,
				),
				array(
					"adman_id"			=> 3,
					"event_name"		=> "sidebar_top",
					"ad_code"			=> "<center><img src='http://placehold.it/250x200'></center>",
					"ad_enabled"		 => 1,
				),
				array(
					"adman_id"			=> 4,
					"event_name"		=> "overall_footer_after",
					"ad_code"			=> "",
					"ad_enabled"		 => 0,
				),
				array(
					"adman_id"			=> 5,
					"event_name"		=> "overall_footer_page_body_after",
					"ad_code"			=> "",
					"ad_enabled"		 => 0,
				),
		);

		foreach ($data as $row )
		{
			$query = 'INSERT INTO ' . $this->table_prefix . 'orthohin_adman' . ' ' . $this->db->sql_build_array('INSERT', $row);
			
			$this->db->sql_query($query);
		}
	}

}
