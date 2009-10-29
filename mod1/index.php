<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Jan Gebauer <mail@jan-gebauer.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */


$LANG->includeLLFile('EXT:jg_clubmanager/mod1/locallang.xml');
require_once(PATH_t3lib . 'class.t3lib_scbase.php');
$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]



/**
 * Module 'Club Manager' for the 'jg_clubmanager' extension.
 *
 * @author	Jan Gebauer <mail@jan-gebauer.de>
 * @package	TYPO3
 * @subpackage	tx_jgclubmanager
 */
class  tx_jgclubmanager_module1 extends t3lib_SCbase {
				var $pageinfo;

				/**
				 * Initializes the Module
				 * @return	void
				 */
				function init()	{
					global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

					parent::init();

					/*
					if (t3lib_div::_GP('clear_all_cache'))	{
						$this->include_once[] = PATH_t3lib.'class.t3lib_tcemain.php';
					}
					*/
				}

				/**
				 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
				 *
				 * @return	void
				 */
				function menuConfig()	{
					global $LANG;
					$this->MOD_MENU = Array (
						'function' => Array (
							'1' => $LANG->getLL('test'),
							'2' => $LANG->getLL('function2'),
							'3' => $LANG->getLL('function3'),
						)
					);
					parent::menuConfig();
				}

				/**
				 * Main function of the module. Write the content to $this->content
				 * If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
				 *
				 * @return	[type]		...
				 */
				function main()	{
					global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

					// Access check!
					// The page will show only if there is a valid page and if this page may be viewed by the user
					$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
					$access = is_array($this->pageinfo) ? 1 : 0;
				
						// initialize doc
					$this->doc = t3lib_div::makeInstance('template');
					$this->doc->setModuleTemplate(t3lib_extMgm::extPath('jg_clubmanager') . 'mod1//mod_template.html');
					$this->doc->backPath = $BACK_PATH;
					$docHeaderButtons = $this->getButtons();

					if (($this->id && $access) || ($BE_USER->user['admin'] && !$this->id))	{

							// Draw the form
						$this->doc->form = '<form action="" method="post" enctype="multipart/form-data">';

							// JavaScript
						$this->doc->JScode = '
							<script language="javascript" type="text/javascript">
								script_ended = 0;
								function jumpToUrl(URL)	{
									document.location = URL;
								}
							</script>
						';
						$this->doc->postCode='
							<script language="javascript" type="text/javascript">
								script_ended = 1;
								if (top.fsMod) top.fsMod.recentIds["web"] = 0;
							</script>
						';
							// Render content:
						$this->moduleContent();
					} else {
							// If no access or if ID == zero
						$docHeaderButtons['save'] = '';
						$this->content.=$this->doc->spacer(10);
					}

						// compile document
					$markers['FUNC_MENU'] = t3lib_BEfunc::getFuncMenu(0, 'SET[function]', $this->MOD_SETTINGS['function'], $this->MOD_MENU['function']);
					$markers['CONTENT'] = $this->content;

							// Build the <body> for the module
					$this->content = $this->doc->startPage($LANG->getLL('title'));
					$this->content.= $this->doc->moduleBody($this->pageinfo, $docHeaderButtons, $markers);
					$this->content.= $this->doc->endPage();
					$this->content = $this->doc->insertStylesAndJS($this->content);
				
				}

				/**
				 * Prints out the module HTML
				 *
				 * @return	void
				 */
				function printContent()	{

					$this->content.=$this->doc->endPage();
					echo $this->content;
				}

				/**
				 * Generates the module content
				 *
				 * @return	void
				 */
				function moduleContent()	{
					global $LANG;
					switch((string)$this->MOD_SETTINGS['function'])	{
						case 1:
							$content='<div align="center"><strong>Hello World!</strong></div><br />
								The "Kickstarter" has made this module automatically, it contains a default framework for a backend module but apart from that it does nothing useful until you open the script '.substr(t3lib_extMgm::extPath('jg_clubmanager'),strlen(PATH_site)).'mod1/index.php and edit it!
								<hr />
								<p><b>Test</b>';
							foreach ($this->getGroupMembers(8,'username') as $member)
							{
  			       $user = t3lib_BEfunc::getRecord('fe_users', $member, '*');
               $icon = t3lib_iconWorks::getIconImage('fe_users', $user, $GLOBALS['BACK_PATH'], 'alt="uid: '.$user['uid'].'" title="uid: '.$user['uid'].'"');
							$actions = '';
               if($GLOBALS['BE_USER']->isAdmin()) {
                    $altText = 'Edit';
                	$actions.= '<a href="#" onclick="'.t3lib_BEfunc::editOnClick('&edit[fe_users]['.$user['uid'].']=edit', $BACK_PATH).'">';
                	$actions.= '<img'.t3lib_iconWorks::skinImg($GLOBALS['$BACK_PATH'], 'gfx/edit2.gif').'alt="'.$altText.'" title="'.$altText.'" /></a>';
                }
               
               
               $table[] = array ($icon, $user['username'],$actions);               
   						}
							$content.='<b>Test</b></p>
								<br />This is the GET/POST ss vars sent to the script:<br />'.
								'GET:'.t3lib_div::view_array($_GET).'<br />'.
								'POST:'.t3lib_div::view_array($_POST).'<br />'.
								'';
							$this->content .= '<p>Test1: '.$LANG->getLL('','Kleiner Test')."</p>";
							$this->content.=$this->doc->section('Message #1:',$content,0,1);
							$this->content.=$this->doc->section('User:',$this->doc->table($table),0,1);
						break;
						case 2:
							$content='<div align=center><strong>Menu item #2...</strong></div>';
							$this->content.=$this->doc->section('Message #2:',$content,0,1);
						break;
						case 3:
							$content='<div align=center><strong>Menu item #3...</strong></div>';
							$this->content.=$this->doc->section('Message #3:',$content,0,1);
						break;
					}
				}
				

				/**
				 * Create the panel of buttons for submitting the form or otherwise perform operations.
				 *
				 * @return	array	all available buttons as an assoc. array
				 */
				protected function getButtons()	{

					$buttons = array(
						'csh' => '',
						'shortcut' => '',
						'save' => ''
					);
						// CSH
					$buttons['csh'] = t3lib_BEfunc::cshItem('_MOD_web_func', '', $GLOBALS['BACK_PATH']);

						// SAVE button
					$buttons['save'] = '<input type="image" class="c-inputButton" name="submit" value="Update"' . t3lib_iconWorks::skinImg($GLOBALS['BACK_PATH'], 'gfx/savedok.gif', '') . ' title="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:rm.saveDoc', 1) . '" />';


						// Shortcut
					if ($GLOBALS['BE_USER']->mayMakeShortcut())	{
						$buttons['shortcut'] = $this->doc->makeShortcutIcon('', 'function', $this->MCONF['name']);
					}

					return $buttons;
				}
				


		 /**
     * Returns an array of fe/beusers uid's for the specified group.
     *
     * @param integer $groupId: fe/be_groups uid
     * @return array
     */
	   function getGroupMembers($groupId,$orderby) {
	     #  $dbResult = $TYPO3_DB->sql_query("SELECT uid, usergroup FROM ".fe_users WHERE deleted=0");
	    
	    $query = $GLOBALS['TYPO3_DB']->SELECTquery(
                'uid, usergroup',         // SELECT ...
                'fe_users',     // FROM ...
                'deleted=0',    // WHERE...
                '',            // GROUP BY...
                $orderby,    // ORDER BY...
                ''            // LIMIT ...

            );
      $dbResult = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, $query);  
	    if($dbResult) {
	        if($GLOBALS['TYPO3_DB']->sql_num_rows($dbResult)==0) {
	            return false;
	        }
	        else {

	            while(list($uid, $usergroup) = $GLOBALS['TYPO3_DB']->sql_fetch_row($dbResult)) {
    	            if($this->searchCSL($groupId,$usergroup)) {
    	                $members[] = $uid;
    	            }
	            }

	            return $members;
	        }
	    }
	    else {
	        $TYPO3_DB->debug('sql_query');
	    }
	}


		/**
	 * Removes a user from the specified fe_group
	 *
	 * @param integer $groupId: fe_groups uid
	 * @param integer $userId: fe_users uid
	 * @return boolean true on success false on failure
	 */
	 function DeleteMembership($userId) {
        
      $membergroups = array (8,6,11);
     #$group = t3lib_BEfunc::getRecord('fe_groups', intval($groupId));

	    
	    $user = t3lib_BEfunc::getRecord('fe_users', $userId);
	    $usergroups = t3lib_div::trimExplode(',', $user['usergroup']);

	    foreach ($membergroups as $delete)
	    {
	    	in_array ($delete, $usergroups) ? true : array_splice ($usergroups, array_search ($delete, $usergroups),1);
      }
 // TODO continue //     
#	    foreach($usergroups as $group) if($group!=$groupId)    $array[] = $group;

#	    $usergroups = (is_array($array)) ? implode(',', $array) : '';

            $dbResult = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('fe_users',
                                                    "uid='".intval($userId)."'",
                                                    array('usergroup' => $usergroups));

            if($dbResult) {
                return $user['username'];
            }
            else {
                $GLOBALS['$TYPO3_DB']->debug('exec_UPDATEquery');
            }
	  
	}
	function searchCSL ($item, $list)	{
	
    		
    		$list = split(',', $list);
		
		if (in_array ($item, $list)) {
     return true;
    } 
   return false;
	}
	

	
	
	
			}
	
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/jg_clubmanager/mod1/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/jg_clubmanager/mod1/index.php']);
}




// Make instance:
$SOBE = t3lib_div::makeInstance('tx_jgclubmanager_module1');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();

?>