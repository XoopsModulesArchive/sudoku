<?php
/**
*  sudoku_block.php file for SFX module (for Xoops)
*
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 0.9
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com>
* 
* Contains functions to manage blocks 
*
* @package sudoku
*/
include_once(XOOPS_ROOT_PATH."/modules/sudoku/include/mes_fonctions.php");

/**
* 
* This defines data for block template
*
* @param array[] $options options from xoops
* @return array
*
* @since 12/11/2005
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com> 
*/
function b_sudoku_show($options) {
	global $HTTP_POST_VARS;
	if (isset($HTTP_POST_VARS["niveau"])) {
		$niveau=$HTTP_POST_VARS["niveau"];
	} else {
		$niveau=9;
	}
	$qtte=$HTTP_POST_VARS["qtte"];
	$block['lang_niveau'] = _MB_SUDOKU_NIVEAU;
	$block['lang_facile'] = _MB_SUDOKU_FACILE;
	$block['lang_moyen'] = _MB_SUDOKU_MOYEN;
	$block['lang_difficile'] = _MB_SUDOKU_DIFFICILE;
	$block['lang_tres_difficile'] = _MB_SUDOKU_TRES_DIFFICILE;
	$block['lang_hypotheses'] = _MB_SUDOKU_HYPOTHESES;
	$block['lang_imprimer'] = _MB_SUDOKU_IMPRIMER;
	$block['selected_'.$niveau] = 'selected';
	$block['qtte_selected_'.$qtte] = 'selected';
	$block['lang_qtte'] = _MB_SUDOKU_QTTE;
	return $block;
}
?>