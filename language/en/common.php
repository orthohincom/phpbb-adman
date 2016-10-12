<?php
/**
*
* @package phpBB Extension - Orthohin Adman
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(

	'ADMAN_TITLE'		=> 'Advertise Management',
	'ACP_ADMAN'		=> 'Advertise Management',
	'ACP_DEMO_TITLE'	=>	'Advertise Management',
	'ACP_DEMO'	=>	'Settings',
	'AD_EDIT'	=> 'Edit Advertisement',
	'ACP_ADEDIT_EXPLAIN'	=>	'Make sure you have the event_name.html file present in the event directory',
	'AD_ADD'	=> 'ADD Advertisement',
	'ACP_ADADD_EXPLAIN'	=>	'Make sure you have uploaded the event_name.html file in the event directory. You may need to clear cache after adding a new template event file',

	'EVENT_NAME'	=> 'Name of the Event:',
	'EVENT_NAME_EXPLAIN'	=>	'Exact name of the Template event where you want to put the html code. Example: overall_header_page_body_before',

	'ACP_ADMAN_EXPLAIN'		=> 'Here you can mage Advertisement codes or tracking codes to be inserted in your template events.',
	'ADD_ADMAN'				=> 'Add a new Advertisement',

	'ADMAN_ADDED'				=> 'Added successfully.',
	'ADMAN_EDITED'				=> 'Edited successfully.',
	'ADMAN_DELETED'			=>	'Removed successfully.',
	'ADMAN_NOT_EXIST'			=> 'The ID you selected does not exist.',

	'ADMAN_INVALID_EVENT_NAME'	=> 'The event name that you entered already exists.',
	'ADMAN_INVALID'			=> 'Your BBCode is constructed in an invalid form.',
	'ADMAN_OPEN_ENDED_EVENT'		=> 'Your custom BBCode must contain both an opening and a closing tag.',
	'ADMAN_ADMAN'				=> 'Tag',
	'ADMAN_ADMAN_TOO_LONG'		=> 'The event name you selected is too long.',
	'ADMAN_ADMAN_DEF_TOO_LONG'	=> 'The event name that you have entered is too long, please shorten your tag definition.',

));
