<?php
/**
*  header.php file for SFX module (for Xoops)
*
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 0.9
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com>
* 
* header file
*
* @package sudoku
*/
include_once "../../mainfile.php";
include_once "include/mes_fonctions.php";

if (strcmp(basename($_SERVER["PHP_SELF"]),"index.php")) {
	redirect_header(XOOPS_URL."/",3,_NOPERM);
	exit();
}

?>