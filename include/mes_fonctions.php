<?php
/**
*  mes_fonctions.php file for SFX module (for Xoops)
*
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @version 0.9
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com>
* 
* This file contains all the functions to create sudoku grids
*
* @package sudoku
*/

// Rendons à César ce qui lui appartient, ce code a été réalisé en partie 
// grâce à l'étude d'un code produit par VbLover

/**
* 
* This function creates a grid and the corresponding mask
*
* @param integer[] &$grille (by ref) main grid
* @param boolean[] &$masque (by ref) mask of the main grid
* @param integer $visible number of cell to hide (this number is added to 21)
* @return void
*
* @since 12/11/2005
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com> 
*/
function grille_base(&$grille, &$masque, $visible) {
	
/**
* The sudoku grid will be create from this unmixed grid
*/	
	for ($i=1;$i<=9;$i++) {
		$grille[1][$i] = $i;
		for ($j=2;$j<=9;$j++) {
			$decalage = floor(($j-1) / 3) ;
			$grille[$j][$i] = ((3 * ($j-1) ) + $i - 1 + $decalage) % 9 + 1;	
		}
	}
    
/**
* Mask choice
*/    
    $choix_masques = array (
    								1 => 	"001000010100001000000110001100011000000100010001000100000010100001000001011100000",
                         	2 =>  "000011001010100000001010100011100000000010100000000001001000010100000010010100001",
                         	3 =>  "100000000100001010010100001000001010010100000001000100001100001000000110010001010",
                         	4 =>	"010010001000100000010001100001001011100000001000110000000000000010001100100010001"
    								);
    $chaine_masque = $choix_masques[rand(1,4)]; 

/**
* Add some cells to the mask
*/
    for($i=1;$i<=$visible;$i++) {
        $j = rand(0, 80);
        while ($chaine_masque[$j] != "0") { 
            $j = ($j+1) % 81;
        }
        $chaine_masque[$j] = "1";
    }

/**
* Generate mask grid
*/
    for($i=1;$i<=9;$i++) {
        for($j=1;$j<=9;$j++) {
            $masque[$i][$j] = ($chaine_masque[$j - 1 + 9 * ($i - 1) ] == "1");
        }
    }
}

/**
* 
* This function swaps two rows or cols
*
* @param integer[] &$grille (by ref) grid with cells to swap
* @param boolean[] &$masque (by ref) mask of the grid
* @param integer $index1 index of the first row or col
* @param integer $index2 index of the second row or col
* @return void
*
* @since 12/11/2005
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com> 
*/
function echange(&$grille, &$masque, $ligne_colonne, $index1, $index2) {
	for($k=1;$k<=9;$k++) {
        if ($ligne_colonne) {
            $i1 = $index1; $i2 = $index2; $j1 = $k; $j2 = $k;
        } else {
            $i1 = $k; $i2 = $k; $j1 = $index1; $j2 = $index2;
        }
        $aux = $grille[$i1][$j1];
        $grille[$i1][$j1] = $grille[$i2][$j2];
        $grille[$i2][$j2] = $aux;
        
        $aux = $masque[$i1][$j1];
        $masque[$i1][$j1] = $masque[$i2][$j2];
        $masque[$i2][$j2] = $aux;
   }
}

/**
* 
* This function mix the grid
*
* @param integer[] &$grille (by ref) grid 
* @param boolean[] &$masque (by ref) corresponding mask 
* @return void
*
* @since 12/11/2005
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com> 
*/
function melange(&$grille,&$masque) {

/**
* we swap cols or rows inside blocks of 3 cols or 3 rows
*/
    for($i=1;$i<=20;$i++) {
    	  $liste_possibilites = array(1, 2, 3);
    	  $bloc = rand(0, 2);
        $choix = array_rand($liste_possibilites,2);
        $ligne_colonne = (rand(0,1) == 0); 
        echange($grille, $masque, $ligne_colonne, ($bloc * 3) + $liste_possibilites[$choix[0]], ($bloc * 3) + $liste_possibilites[$choix[1]]);
    }
    
/**
* we swap whole blocks (3 cols or rows)
*/   
    for($i=1;$i<=10;$i++) {
        $a = rand(0,2);
        $b = ($a + rand(1,2)) % 3;
        $ligne_colonne = (rand(0,1) == 0);
        for($j=1;$j<=3;$j++) {
            echange($grille, $masque, $ligne_colonne, 3 * $a + $j, 3 * $b + $j);
        }
    }
    
    
/**
* We swap numbers in order to mix a bit more
*/ 
    $chiffres = array(1,2,3,4,5,6,7,8,9);
    shuffle($chiffres);
    for($i=1;$i<=9;$i++) {
        for($j=1;$j<=9;$j++) {
            $grille[$i][$j] = $chiffres[$grille[$i][$j]-1]; 
        }
    } 
}

/**
* 
* This function creates sudoku grid and mask
*
* @param integer $visible number of cell to hide (this number is added to 21)
* @return void
*
* @since 12/11/2005
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com> 
*/
function generer_sudoku($visible) {

	grille_base($grille,$masque,$visible);
	melange($grille,$masque);
	
	for($i=1;$i<=9;$i++) {
		for($j=1;$j<=9;$j++) {
			if ($masque[$i][$j]) {
				$sudoku[$i][$j]["value"] = $grille[$i][$j];
			} else {
				$sudoku[$i][$j]["value"] = "";
			}
			if (($j % 3)==0) {
				$sudoku[$i][$j]["border"] = 1;
			}
			if (($i % 3)==0) {
				$sudoku[$i][$j]["borderbottom"] = 1;
			}
			$sudoku[$i][$j]["indice"]= (($i-1) * 9) + $j - 1; 
		}
	}

	return ($sudoku);
	
}

function choix($liste1, $liste2, $liste3) {
	for($i=1;$i<=9;$i++) {
		if (strpos($liste1,"$i")===false) {
			$ensemble1[]=$i;
		}
		if (strpos($liste2,"$i")===false) {
			$ensemble2[]=$i;
		}
		if (strpos($liste3,"$i")===false) {
			$ensemble3[]=$i;
		}
	}
	$resultat = array_intersect($ensemble1, $ensemble2, $ensemble3);
	reset($resultat);
	if (count($resultat)==1) return current($resultat);
	return false;
}


/**
* 
* solve grid without assumption
*
* @param array $sudoku grid to solve without assumption
* @return void
*
* @since 25/12/2005
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com> 
*/
function sans_hypothese($grille_sudoku) {
	
	$sudoku="";
	for ($i=1;$i<=9;$i++) {
		for ($j=1;$j<=9;$j++) {
			if ($grille_sudoku[$i][$j]["value"]) {
				$aux_value = $grille_sudoku[$i][$j]["value"];
				$sudoku .= $aux_value;
			} else {
				$sudoku .= "0";
			}
			
		}
	}
	$change=true;
	while($change) {
		$change=false;
		for ($i=0;$i<=80;$i++) {
			if (substr($sudoku,$i,1)==0) {
				$y = floor($i / 9);
				$x = $i % 9;
				$ligne="";
				$ligne = substr($sudoku,$y*9,$i-($y*9)).substr($sudoku,$i+1,(($y+1)*9)-1-$i);
				$colonne="";
				for($a=$x;$a<=80;$a+=9) {
					if ($a!=$i) $colonne.=substr($sudoku,$a,1);
				}
				$min_x=floor($x/3)*3;
				$max_x=$min_x+2;
				$min_y=floor($y/3)*3;
				$max_y=$min_y+2;
				$carre="";
				for ($k=$min_y;$k<=$max_y;$k++) {
					for ($l=$min_x;$l<=$max_x;$l++) {
						$a=$k*9+$l;
						if ($a!=$i) $carre.=substr($sudoku,$a,1);
					}
				}
				$choix = choix($ligne,$colonne,$carre);
				if ($choix) {
					$change=true;
					$sudoku=substr($sudoku,0,$i).$choix.substr($sudoku,$i+1,strlen($sudoku)-$i-1);
				}
			}
	/*		if (($sudoku[$i]>9) || ($sudoku[$i]<1) || 
				(strpos($ligne,$sudoku[$i])!==false) || 
				(strpos($colonne,$sudoku[$i])!==false) ||
				(strpos($carre,$sudoku[$i]))!==false) {
				$valide=false;	
			}*/
		}
	}
	return (strlen($sudoku)==81);
}


/**
* 
* This function check sudoku grid
*
* @param integer $visible number of cell to hide (this number is added to 21)
* @return boolean true if ok or false if an error is detected
*
* @since 12/11/2005
* @author {@link http://www.sollib.com SOLLIB} (Mathieu LEFRANCOIS) <sudoku@sollib.com> 
*/
function verifier_sudoku($sudoku) {
	
	$valide=true;
	for ($i=0;(($i<=80) && ($valide));$i++) {
		$y = floor($i / 9);
		$x = $i % 9;
		$ligne="";
		$ligne = substr($sudoku,$y*9,$i-($y*9)).substr($sudoku,$i+1,(($y+1)*9)-1-$i);
		$colonne="";
		for($a=$x;$a<=80;$a+=9) {
			if ($a!=$i) $colonne.=$sudoku[$a];
		}
		$min_x=floor($x/3)*3;
		$max_x=$min_x+2;
		$min_y=floor($y/3)*3;
		$max_y=$min_y+2;
		$carre="";
		for ($k=$min_y;$k<=$max_y;$k++) {
			for ($l=$min_x;$l<=$max_x;$l++) {
				$a=$k*9+$l;
				if ($a!=$i) $carre.=$sudoku[$a];
			}
		}

		if (($sudoku[$i]>9) || ($sudoku[$i]<1) || 
			(strpos($ligne,$sudoku[$i])!==false) || 
			(strpos($colonne,$sudoku[$i])!==false) ||
			(strpos($carre,$sudoku[$i]))!==false) {
			$valide=false;	
		}
	}
	return ($valide);
}
?>
