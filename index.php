<?php
/**
* index.php file for SFX module (for Xoops)
*
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 0.9
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com>
* 
* Main module file
*
* @package sudoku
*/

include_once "header.php";
include_once (XOOPS_ROOT_PATH."/header.php");
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
include_once('include/mes_fonctions.php');

$myts =& MyTextSanitizer::getInstance();// MyTextSanitizer object

$config_handler =& xoops_gethandler('config');
$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);

$eh = new ErrorHandler; //ErrorHandler object

/**
* 
* This function defines data and template used to choose 
* parameters of the sudoku
*
* @return void
*
* @since 12/11/2005
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com> 
*/
function sudoku() {
	global $xoopsOption,$xoopsTpl;
	$xoopsOption['template_main'] = 'sudoku_choix.html';
	
	$xoopsTpl->assign('lang_title', _MD_SUDOKU_PARAM_TITLE);
	$xoopsTpl->assign('lang_qtte', _MD_SUDOKU_NOMBRE_SUDOKU);
	$xoopsTpl->assign('lang_niveau', _MD_SUDOKU_NIVEAU);
	$xoopsTpl->assign('lang_hypotheses', _MD_SUDOKU_HYPOTHESES);
	$xoopsTpl->assign('lang_facile', _MD_SUDOKU_FACILE);
	$xoopsTpl->assign('lang_moyen', _MD_SUDOKU_MOYEN);
	$xoopsTpl->assign('lang_difficile', _MD_SUDOKU_DIFFICILE);
	$xoopsTpl->assign('lang_tres_difficile', _MD_SUDOKU_TRES_DIFFICILE);
	
	$xoopsTpl->assign('lang_generer',_MD_SUDOKU_GENERER);
	$xoopsTpl->assign('lang_annuler',_CANCEL);
}

/**
* 
* This function defines data and template used to display sudoku grid 
*
* @param integer $print if true, sfx will generate sudoku grids to print
* @return void
*
* @since 12/11/2005
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com> 
*/
function sudoku_play($print=false) {

	global $xoopsOption,$xoopsTpl,$HTTP_POST_VARS;
	$xoopsOption['template_main'] = 'sudoku.html';

	
	if (isset($HTTP_POST_VARS["niveau"])) {
		$niveau=$HTTP_POST_VARS["niveau"];
	} else {
		$niveau=9;
	}
	
	if (isset($HTTP_POST_VARS["qtte"])) {
		$qtte=$HTTP_POST_VARS["qtte"];
	} else {
		$qtte=1;
	}
	
	if (isset($HTTP_POST_VARS["hypotheses"])) {
		$hypotheses=true;
	} else {
		$hypotheses=false;
	}
	
	
	switch($qtte) {
		case 1 :	$liste_x=array(1);
					$liste_y=array(1);
					$x=1;
					break;
		case 2 :	$liste_x=array(1,2);
					$liste_y=array(1);
					$x=2;
					break;
		case 4 :	$liste_x=array(1,2);
					$liste_y=array(1,2);
					$x=2;
					break;
		case 9 :	$liste_x=array(1,2,3);
					$liste_y=array(1,2,3);
					$x=3;
					break;
	}

	foreach($liste_y as $keyy => $valuey) {
		foreach($liste_x as $keyx => $valuex) {
			$aux_sudoku = generer_sudoku($niveau);
			if ($hypotheses) {
				while (!sans_hypothese($aux_sudoku)) {
					$aux_sudoku = generer_sudoku($niveau);
				}
			} 
			$sudokus[$valuex][$valuey] = $aux_sudoku;
		}
	}
	$xoopsTpl->assign('x', $x);
	$xoopsTpl->assign('liste_x', $liste_x);
	$xoopsTpl->assign('liste_y', $liste_y);
	$xoopsTpl->assign('lang_title', _MD_SUDOKU_TITLE);
	$xoopsTpl->assign('grilles', $sudokus);
	$xoopsTpl->assign('lang_valider',_SUBMIT);
	$xoopsTpl->assign('lang_zero',_MD_SUDOKU_ZERO);
	$xoopsTpl->assign('lang_annuler',_CANCEL);
	$xoopsTpl->assign('niveau',$niveau);
	$xoopsTpl->assign('qtte',$qtte);
	if ($print) {
		ob_end_clean();
		//mettre le chemin en relatif !!!
		$xoopsTpl->display(XOOPS_ROOT_PATH."/modules/sudoku/templates/sudoku_print.html");
		exit();
	}
}

/**
* 
* This function defines data and template used to check sudoku grid 
*
* @return void
*
* @since 12/11/2005
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com> 
*/
function sudoku_validate() {
	global $xoopsOption,$xoopsTpl,$HTTP_POST_VARS;
	$xoopsOption['template_main'] = 'sudoku_validate.html';
	
	$qtte=$HTTP_POST_VARS["qtte"];
	$sudoku=$HTTP_POST_VARS["sudoku"];
	
	switch($qtte) {
	case 1 :	$liste_x=array(1);
				$liste_y=array(1);
				$x=1;
				break;
	case 2 :	$liste_x=array(1,2);
				$liste_y=array(1);
				$x=2;
				break;
	case 4 :	$liste_x=array(1,2);
				$liste_y=array(1,2);
				$x=2;
				break;
	case 9 :	$liste_x=array(1,2,3);
				$liste_y=array(1,2,3);
				$x=3;
				break;
	}
	
	foreach($liste_y as $keyy => $valuey) {
		foreach($liste_x as $keyx => $valuex) {
			$sudoku_string="";
			for($l=0;$l<=80;$l++) {
				if (!empty($sudoku[$valuex][$valuey][$l])) {
					$sudoku_string.=$sudoku[$valuex][$valuey][$l];
				} else {
					$sudoku_string.="0";
				}
			}
			if (verifier_sudoku($sudoku_string)) {
				$validates[$valuex][$valuey]=_MD_SUDOKU_VALIDE;
			} else {
				$validates[$valuex][$valuey]=_MD_SUDOKU_INVALIDE;
			}
		}
	}
	
	$xoopsTpl->assign('x', $x);
	$xoopsTpl->assign('liste_x', $liste_x);
	$xoopsTpl->assign('liste_y', $liste_y);
	$xoopsTpl->assign('lang_title', _MD_SUDOKU_TITLE);
	$xoopsTpl->assign('validate', $validates);
	$xoopsTpl->assign('lang_valider',_SUBMIT);
	$xoopsTpl->assign('lang_annuler',_CANCEL);
}

if(!isset($HTTP_POST_VARS['op'])) {
   $op = isset($HTTP_GET_VARS['op']) ? $HTTP_GET_VARS['op'] : 'main';
} else {
   $op = $HTTP_POST_VARS['op'];
}

switch ($op) {
	case "play":
		sudoku_play();
		break;	
	case "validate":
		sudoku_validate();
		break;
	case "print":
		sudoku_play(true);
		break;
	default:
		sudoku();
		break;
}
   include_once XOOPS_ROOT_PATH.'/footer.php';



?>