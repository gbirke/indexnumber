<?php
/**
 * @package    indexnumber
 * @author     Gabriel Birke <gb@birke-software.de>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
 
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');
 
class action_plugin_indexnumber extends DokuWiki_Action_Plugin {
 
  /*
   * Register the handlers with the dokuwiki's event controller
   */
  function register(&$controller) {
    $controller->register_hook('TOOLBAR_DEFINE', 'AFTER',  $this, 'add_button');
  }
 
  /**
   * Parse the configured index names and add the button to the toolbar.
   */
  function add_button(&$event, $param) {
    $indexnames = $this->getConf('indexnames');
    $index_list = array_map("trim", explode("\n", $indexnames));
    $index_list = array_filter($index_list);
    if (!empty($index_list))
    {
      $event->data[] = array(
                  'type'   => 'indexnumberpicker',
                  'title'  => $this->getLang('toolbar_title'),
                  'icon'   => '../../plugins/indexnumber/indexnumber_icon.png',
                  'list'   => $index_list,
                  'idxrefs' => false
                  );
    }
  }
  
}
