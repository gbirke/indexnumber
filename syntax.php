<?php
/**
 * indexnumber-Plugin: Create independent, referencable counters on a page
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Gabriel Birke <gb@birke-software.de>
 */


if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_indexnumber extends DokuWiki_Syntax_Plugin {

    /**
     * Current counter value for each counter
     * @var type array
     */
    protected $idxnumbers = array();

    /**
     * Stack for avoiding nested tags
     * @var type SplStack
     */
    protected $tag_stack;
    
    /**
     * Separator between counter and description
     * @var type string
     */
    protected $separator;

    public function __construct(){
        $this->tag_stack = new SplStack();
        $this->separator = $this->getConf('separator');
    }

    /**
     * What about paragraphs?
     */
    function getPType(){
        return 'block';
    }

    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'container';
    }

    /**
     * Where to sort in?
     */
    function getSort(){
        return 200;
    }

    function getAllowedTypes() { return array('container', 'substition', 'protected', 'disabled', 'formatting', 'paragraphs'); }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<idxnum .*?>',$mode,'plugin_indexnumber');
    }

    function postConnect() {
        $this->Lexer->addExitPattern('</idxnum>', 'plugin_indexnumber');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler) {
        if($state == DOKU_LEXER_ENTER && preg_match('/<idxnum ([^#]+)(?:#(\d+)(.*))?>/', $match, $matches)) {
            $idxId = trim($matches[1]);
            if(empty($this->idxnumbers[$idxId])) {
                $this->idxnumbers[$idxId] = 1;
            }
            else {
                $this->idxnumbers[$idxId]++;
            }
            $description = trim($matches[3]);
            $description = str_replace(array('<', '>'), array('&lt;', '&gt;'), $description); // Replace angle brackets
            $tagData = array($state, $idxId, $this->idxnumbers[$idxId], $matches[2], $description);
            if($this->tag_stack->isEmpty()) {
                $this->tag_stack->push($tagData);
                return $tagData;
            }
        }
        elseif($state == DOKU_LEXER_EXIT) {
            if(!$this->tag_stack->isEmpty()) {
                $tagData = $this->tag_stack->pop();
                $tagData[0] = $state;
                return $tagData;
            }
        }
        elseif($state == DOKU_LEXER_UNMATCHED) {
            return array($state, $match);
        }

        // Ignore errors
        return array();

    }

    /**
     * Create output
     * 
     * The data is an array with with following keys:
     * 
     * 0 - state (DOKU_LEXER_ENTER, DOKU_LEXER_EXIT, DOKU_LEXER_UNMATCHED)
     * 1 - Counter name
     * 2 - Counter value
     * 3 - Counter reference id, without # 
     * 4 - Description text
     */
    function render($format, &$R, $data) {
        if($format != 'xhtml'){
            return false;
        }
        if($data[0] == DOKU_LEXER_ENTER) {
            $anchor = preg_replace('/[^a-z]/i', '_', $data[1]).'_'.$data[2];
            $R->doc .= '<div id="'.$anchor.'" class="idxnum_container">';
            return true;
        }
        elseif($data[0] == DOKU_LEXER_EXIT) {
            $R->doc .= '<p class="idxnum"><span class="counter">'.$data[1].' '.$data[2].$this->separator.'</span>';
            $R->doc .= $data[4].'</p></div>';
            return true;
        }
        elseif($data[0] == DOKU_LEXER_UNMATCHED) {
            $R->doc .= $R->_xmlEntities($data[1]);
        }
        return false;
    }

}



