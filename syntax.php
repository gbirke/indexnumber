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

    protected $idxnumbers = array();
    protected $idxrefs = array();

    const TYPE_UNKNOWN = 0;
    const TYPE_IDXNUM  = 1;
    const TYPE_IDXREF  = 2;
    const TYPE_WRONG_IDX = 3;
    const TYPE_WRONG_REF = 4;

    /**
     * What about paragraphs?
     */
    function getPType(){
        return 'normal';
    }

    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'substition';
    }

    /**
     * Where to sort in?
     */
    function getSort(){
        return 200;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('<idx(?:num|ref) .*?>',$mode,'plugin_indexnumber');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler) {
        if($state !== DOKU_LEXER_SPECIAL ) {
            return array();
        }

        if(preg_match('/<idxnum ([^\d]*)(\d*)\s*>/', $match, $matches)) {
            $idxId = trim($matches[1]);
            if(empty($this->idxnumbers[$idxId])) {
                $this->idxnumbers[$idxId] = 1;
            }
            else {
                $this->idxnumbers[$idxId]++;
            }
            if($matches[2] !== '') {
                $this->idxrefs[$idxId][$matches[2]] = $this->idxnumbers[$idxId];
            }
            return array(self::TYPE_IDXNUM, $idxId, $this->idxnumbers[$idxId]);
        }
        elseif(preg_match('/<idxref ([^\d]*)(\d+)\s*>/', $match, $matches)) {
            $idxId = trim($matches[1]);
            if(!isset($this->idxrefs[$idxId])) {
                return array(self::TYPE_WRONG_IDX, $idxId);
            }
            elseif (empty($this->idxrefs[$idxId][$matches[2]])) {
                return array(self::TYPE_WRONG_REF, $idxId, $matches[2]);
            }
            else {
                return array(self::TYPE_IDXREF, $idxId, $this->idxrefs[$idxId][$matches[2]]);
            }
        }
        else {
            return array(self::TYPE_UNKNOWN, $match);
        }
    }

    /**
     * Create output
     */
    function render($format, &$R, $data) {
        if($format == 'xhtml'){
            switch ($data[0]) {
                case self::TYPE_IDXNUM:
                    $anchor = preg_replace('/[^a-z]/', '_', $data[1]).'_'.$data[2];
                    $R->doc .= '<span class="idxnum" id="'.$anchor.'">'.$data[1].' '.$data[2].':</span>';
                    break;
                case self::TYPE_IDXREF:
                    $anchor = preg_replace('/[^a-z]/', '_', $data[1]).'_'.$data[2];
                    $R->doc .= '<a class="idxref" href="#'.$anchor.'">'.$data[1].' '.$data[2].'</a>';
                    break;
                case self::TYPE_WRONG_IDX:
                    $R->doc .= '<span class="idxref noidx">'.sprintf($this->getLang('idxnotfound'), $data[1]).'</span>';
                    break;
                case self::TYPE_WRONG_REF:
                    $R->doc .= '<span class="idxref noref">'.$data[1].' ???</span>';
                    break;
                case self::TYPE_UNKNOWN:
                    $R->doc .= $data[1];
                    break;
            }
            return true;
        }
        return false;
    }

}



