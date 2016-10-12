<?php
/**
*
* @package phpBB Extension - Orthohin Adman
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace orthohin\adman\acp;

class main_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $template, $cache, $request, $table_prefix, $phpbb_log;

		$user->add_lang('acp/common');

		// Set up general vars
		$action	= $request->variable('action', '');
		$adman_id = $request->variable('adman', 0);
		$submit = $request->is_set_post('submit');

		$this->tpl_name = 'adman_body';
		$this->page_title = 'ADMAN_TITLE';
		$form_key = 'orthohin/adman';
		$adman_table = $table_prefix . 'orthohin_adman';

		add_form_key($form_key);

		if ($submit && !check_form_key($form_key))
		{
			trigger_error($user->lang['FORM_INVALID'] . adm_back_link($this->u_action), E_USER_WARNING);
		}

		// Set up mode-specific vars
		switch ($action)
		{
			case 'add':
				$event_name = $ad_code = '';
				$ad_enabled = 0;
			break;

			case 'edit':
				$sql = 'SELECT event_name, ad_code, ad_enabled
					FROM ' . $adman_table . '
					WHERE adman_id = ' . $adman_id;
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				if (!$row)
				{
					trigger_error($user->lang['ADCODE_NOT_EXIST'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				$event_name = $row['event_name'];
				$ad_code = htmlspecialchars($row['ad_code']);
				$ad_enabled = $row['ad_enabled'];
			break;

			case 'modify':
				$sql = 'SELECT adman_id, event_name
					FROM ' . $adman_table . '
					WHERE adman_id = ' . $adman_id;
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				if (!$row)
				{
					trigger_error($user->lang['ADCODE_NOT_EXIST'] . adm_back_link($this->u_action), E_USER_WARNING);
				}



			// No break here

			case 'create':
				$ad_enabled = $request->variable('ad_enabled', 0);

				$event_name = $request->variable('event_name', '');
				$ad_code = htmlspecialchars_decode(utf8_normalize_nfc($request->variable('ad_code', '', true)));
			break;
		}

		// Do major work
		switch ($action)
		{
			case 'edit':
			case 'add':

				$tpl_ary = array(
					'S_EDIT_ADMAN'		=> true,
					'U_BACK'			=> $this->u_action,
					'U_ACTION'			=> $this->u_action . '&amp;action=' . (($action == 'add') ? 'create' : 'modify') . (($adman_id) ? "&amp;adman=$adman_id" : ''),

					'L_ADMAN_USAGE_EXPLAIN'=> sprintf($user->lang['ADMAN_USAGE_EXPLAIN'], '<a href="#down">', '</a>'),
					'EVENT_NAME'			=> $event_name,
					'AD_CODE'				=> $ad_code,
					'AD_ENABLED'		=> $ad_enabled,
				);
				if ($action == 'add')
				{
					$tpl_ary['S_ADD_ADMAN']	=	true;
				}

				/**
				* Modify custom bbcode template data before we display the add/edit form
				*
				* @event core.acp_bbcodes_edit_add
				* @var	string	action			Type of the action: add|edit
				* @var	array	tpl_ary			Array with custom bbcode add/edit data
				* @var	int		adman_id		When editing: the bbcode id,
				*								when creating: 0
				* @var	array	bbcode_tokens	Array of bbcode tokens
				* @since 3.1.0-a3
				*/
				$template->assign_vars($tpl_ary);

				return;

			break;

			case 'modify':
			case 'create':

				$sql_ary = $hidden_fields = array();

				/**
				* Modify custom bbcode data before the modify/create action
				*
				* @event core.acp_bbcodes_modify_create
				* @var	string	action				Type of the action: modify|create
				* @var	array	sql_ary				Array with new bbcode data
				* @var	int		adman_id			When editing: the bbcode id,
				*									when creating: 0
				* @var	bool	ad_enabled	Display bbcode on posting form
				* @var	string	event_name		The bbcode usage string to match
				* @var	string	ad_code			The bbcode HTML replacement string
				* @var	string	bbcode_helpline		The bbcode help line string
				* @var	array	hidden_fields		Array of hidden fields for use when
				*									submitting form when $warn_text is true
				* @since 3.1.0-a3
				*/
				$vars = array(
					'action',
					'sql_ary',
					'adman_id',
					'ad_enabled',
					'event_name',
					'ad_code',
					'hidden_fields',
				);


				if ($action == 'modify')
				{
					$sql = 'SELECT adman_id as test
						FROM ' . $adman_table . "
						WHERE adman_id <> $adman_id and LOWER(event_name) = '" . $db->sql_escape(strtolower($event_name)) . "'";
					$result = $db->sql_query($sql);
					$info = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);

					if ($info['test'] > '0')
					{
						trigger_error($user->lang['ADMAN_INVALID_EVENT_NAME'] . adm_back_link($this->u_action), E_USER_WARNING);
					}
				}

				if ($action == 'create'){
					$sql = 'SELECT adman_id as test
						FROM ' . $adman_table . "
						WHERE LOWER(event_name) = '" . $db->sql_escape(strtolower($event_name)) . "'";
					$result = $db->sql_query($sql);
					$info = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);

					if ($info['test'] > '0')
					{
						trigger_error($user->lang['ADMAN_INVALID_EVENT_NAME'] . adm_back_link($this->u_action), E_USER_WARNING);
					}
				}

				$test = $event_name;

				if (strlen($event_name) > 400)
				{
					trigger_error($user->lang['EVENT_NAME_TOO_LONG'] . adm_back_link($this->u_action), E_USER_WARNING);
				}
				if (strlen($event_name) < 7)
				{
					trigger_error($user->lang['EVENT_NAME_TOO_SHORT'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				$sql_ary = array_merge($sql_ary, array(
					'event_name'				=> $event_name,
					'ad_code'				=> $ad_code,
					'ad_enabled'		=> $ad_enabled
				));

				if ($action == 'create')
				{
					$sql = 'SELECT MAX(adman_id) as max_adman_id
						FROM ' . $adman_table;
					$result = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);

					if ($row)
					{
						$adman_id = $row['max_adman_id'] + 1;

					}
					else
					{
						$adman_id = 0 + 1;
					}

					$sql_ary['adman_id'] = (int) $adman_id;

					$db->sql_query('INSERT INTO ' . $adman_table . $db->sql_build_array('INSERT', $sql_ary));
					$cache->destroy('sql', $adman_table);

					$lang = 'ADMAN_ADDED';
					$log_action = 'LOG_ADMAN_ADD';
				}
				else
				{
					$sql = 'UPDATE ' . $adman_table . '
						SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE adman_id = ' . $adman_id;
					$db->sql_query($sql);
					$cache->destroy('sql', $adman_table);

					$lang = 'ADMAN_EDITED';
					$log_action = 'LOG_ADMAN_EDIT';
				}

				$phpbb_log->add('admin', $log_action, $event_name);

				trigger_error($user->lang[$lang] . adm_back_link($this->u_action));

			break;

			case 'delete':

				$sql = 'SELECT event_name
					FROM ' . $adman_table . "
					WHERE adman_id = $adman_id";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				if ($row)
				{
					if (confirm_box(true))
					{
						$db->sql_query('DELETE FROM ' . $adman_table . " WHERE adman_id = $adman_id");
						$cache->destroy('sql', $adman_table);
						$phpbb_log('admin', 'LOG_ADMAN_DELETE', $row['event_name']);

						if ($request->is_ajax())
						{
							$json_response = new \phpbb\json_response;
							$json_response->send(array(
								'MESSAGE_TITLE'	=> $user->lang['INFORMATION'],
								'MESSAGE_TEXT'	=> $user->lang['ADMAN_DELETED'],
								'REFRESH_DATA'	=> array(
									'time'	=> 3
								)
							));
						}
					}
					else
					{
						confirm_box(false, $user->lang['CONFIRM_OPERATION'], build_hidden_fields(array(
							'adman'	=> $adman_id,
							'i'			=> $id,
							'mode'		=> $mode,
							'action'	=> $action))
						);
					}
				}

			break;
		}

		$u_action = $this->u_action;

		$template_data = array(
			'U_ACTION'		=> $this->u_action . '&amp;action=add',
		);

		$sql_ary = array(
			'SELECT'	=> 'b.*',
			'FROM'		=> array($adman_table => 'b'),
			'ORDER_BY'	=> 'b.adman_id',
		);

		$result = $db->sql_query($db->sql_build_query('SELECT', $sql_ary));

		$template->assign_vars($template_data);

		while ($row = $db->sql_fetchrow($result))
		{
			$adman_array = array(
				'ADMAN_ID'			=> $row['adman_id'],
				'AD_ENABLED'		=> $row['ad_enabled'],
				'EVENT_NAME'		=> $row['event_name'],
				'U_EDIT'			=> $u_action . '&amp;action=edit&amp;adman=' . $row['adman_id'],
				'U_DELETE'			=> $u_action . '&amp;action=delete&amp;adman=' . $row['adman_id'],
			);

			$template->assign_block_vars('adman', $adman_array);

		}
		$db->sql_freeresult($result);
	}

}
