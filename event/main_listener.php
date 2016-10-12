<?php
/**
*
* @package phpBB Extension - Orthohin Adman
* @copyright (c) 2016 
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace orthohin\adman\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class main_listener implements EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'	=> 'load_language_on_setup',
			'core.page_header'	=> 'add_ad_codes',
		);
	}

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper	$helper		Controller helper object
	* @param \phpbb\template\template	$template	Template object
	*/
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template)
	{
		$this->helper = $helper;
		$this->template = $template;
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'orthohin/adman',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function add_ad_codes($event)
	{
		global $db, $table_prefix;
		$adman_table = $table_prefix . 'orthohin_adman';
		$sql = 'SELECT adman_id, event_name, ad_code
					FROM ' . $adman_table . '
					WHERE ad_enabled = 1';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$adman_array[strtoupper($row['event_name'])] =  $row['ad_code'];
		}

		$db->sql_freeresult($result);

		if (isset($adman_array)) {
			$this->template->assign_vars($adman_array);
		}

	}
}
