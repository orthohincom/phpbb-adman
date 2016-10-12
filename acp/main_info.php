<?php
/**
*
* @package phpBB Extension - Orthohin Adman
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace orthohin\adman\acp;

class main_info
{
	function module()
	{
		return array(
			'filename'	=> '\orthohin\adman\acp\main_module',
			'title'		=> 'ACP_DEMO_TITLE',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'ACP_DEMO',
					'auth'	=> 'ext_orthohin/adman && acl_a_board',
					'cat'	=> array('ACP_DEMO_TITLE')
				),
			),
		);
	}
}
