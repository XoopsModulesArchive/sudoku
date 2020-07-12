<?php
/**
*  xoops_version.php file for SFX module (for Xoops)
*
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 0.9
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com>
* 
* This file contains information for xoops management
*
* @package sudoku
*/

$modversion['name'] = _MI_SUDOKU_NAME;
$modversion['version'] = 0.9;
$modversion['author'] = "Mathieu LEFRANCOIS";
$modversion['description'] = _MI_SUDOKU_DESC;
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "images/sudoku_slogo.png";
$modversion['dirname'] = "sudoku";

/**
* blocks
*/
$modversion['blocks'][1]['file'] = "sudoku_block.php";
$modversion['blocks'][1]['name'] = _MI_SUDOKU_BNAME1;
$modversion['blocks'][1]['description'] = "Obtenir rapidement une grille";
$modversion['blocks'][1]['show_func'] = "b_sudoku_show";
$modversion['blocks'][1]['template'] = 'sudoku_block_top.html';

/**
* Menu
*/
$modversion['hasMain'] = 1;

/**
* Templates. If adding extra templates adjust/submit here
*/
$modversion['templates'][1]['file'] = 'sudoku_choix.html';
$modversion['templates'][1]['description'] = 'Param&eacute;trage du Sudoku';
$modversion['templates'][2]['file'] = 'sudoku.html';
$modversion['templates'][2]['description'] = 'Grille de Sudoku &agrave; remplir';
$modversion['templates'][3]['file'] = 'sudoku_validate.html';
$modversion['templates'][3]['description'] = 'Correction du Sudoku';
$modversion['templates'][4]['file'] = 'sudoku_print.html';
$modversion['templates'][4]['description'] = 'Imprimer des grilles de Sudoku';
?>