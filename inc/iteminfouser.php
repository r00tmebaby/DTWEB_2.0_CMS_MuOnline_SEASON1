<?php
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {header("Location:../error.php");}else{

function season(){
	require $_SERVER['DOCUMENT_ROOT']."/configs/config.php";
	    switch($option['item_hex_lenght']){
			case 20:return array(20,2400,1080,120); break;
			case 32:return array(32,3840,3776,240); break;
			case 64:return array(64,7680,7584,480); break;
			default: return false; break;
		}
}
function check_admin($user)
{ 
	 $is_admin = mssql_fetch_array(mssql_query("Select * from DTweb_GM_Accounts where [Name] = '".$user."'"));
         array();
         if($is_admin['name'] != null){
			if($is_admin['ip'] != ip($user)){
				return false;
			}
			else{
				return array($is_admin['name'],$is_admin['gm_level'],$is_admin['ip']);	
				}  
			}
			else{
				return false;
			}   
}
function itemimage($level, $level2, $level3,$ancient=0,$imgfix = false) {
	
	if(season()){
		$item_conf = season();
		if($item_conf[0] === 20){
		$level1 = hexdec(substr($level, 0, 1));

        if (($level1 % 2) <> 0) {
            $level2 = "1" . $level2;
            $level1--;
        }

        if (hexdec($level3) >= 128) {
            $level1 += 16;
        }

        $level1 /= 2;
        $level2 = hexdec($level2);

		}
		else{
			$level1= $level;
		}
	
		if($imgfix){$image   = "imgs/items/special/".$imgfix.".gif";} 
		else{$image   = "imgs/items/{$level1}/{$level2}.gif";	}
	
	  }
	
	  if(file_exists($image)){
		  return $image;
	  }
	  else{		  
		  return "imgs/items/no.gif";
	  }
    }
	
function fix_id($id, $type)
	{
		if (128 <= $type) {
			$id += 256;
		}
		return $id;
	}
	
function Iteminfouser($_item,$level_req=0) {
    require $_SERVER['DOCUMENT_ROOT']."/configs/config.php";
	    $nocolor           = false;
		$socket            = array();
		$nolevel           = 1;
		$plusche           = false;
		$exl               = false;
		$option            = false;
	    $itemexl           = false;
		$socketoption      = false;
		$harmony           = false;
		$itemoption        = false;
		$sockets           = false;
		$refine            = false;
		$anc_opt           = false;
		$iteminfoadmin     = false;
		$itemanc           = false;
		$item_for          = false;
		$rowinfo           = false;
		$addinfo           = false;
		$item_for          = false;
		$item_req          = false;
		$special           = false;
		$imgfix            = false;
		$exl_opts          = array();
		$show_bonus_socket = false;
		
        if (substr($_item, 0, 2) == '0x') {
            $_item = substr($_item, 2);
        }
        if(season()){
			$season_settings = season();
            if ((strlen($_item) != $season_settings[0]) || preg_match('/[^\W_ ] /',$_item) || ($_item == str_repeat('F',$season_settings[0])) || ($_item == str_repeat('3F',($season_settings[0])/2))) {
                return false;
            }
        }
		else{
			return false;
		}
// Get the hex contents
        $id         = hexdec(substr($_item, 0, 2)) ;  // Item ID
		$ids        = hexdec(substr($_item, 1, 1)) ;  // Item ID
        $lvl        = hexdec(substr($_item, 2, 2)) ;  // Level,Option,Skill,Luck
        $itemdur    = hexdec(substr($_item, 4, 2)) ;  // Item Durability
        $ex         = hexdec(substr($_item,14, 2)) ;  // Item Excellent Info/ Option
        $serial	    = hexdec(substr($_item, 6,8))  ;  // Item Serial
        $anc	    = hexdec(substr($_item,16,2))  ;  // Item Ancient
		if($season_settings[0] == 64){
			$serial = hexdec(substr($_item,32,8));
		}
		if ($lvl < 128) {
            $skill = '';
			$srch_skill = 0;
        } else {
            $skill = 'This weapon has a special skill</br>';
            $lvl = $lvl - 128;
			$srch_skill = 1;
        }
        
        $itemlevel = floor($lvl / 8);
        $lvl = $lvl - $itemlevel * 8;

        if ($lvl < 4) {
            $luck = '';
			$srch_luck = 0;
        } else {
            $luck = "Luck (success rate of jewel of soul +25%)<br>Luck (critical damage rate +5%)";
            $lvl = $lvl - 4;
			$srch_luck = 1;
        }


        if ($ex - 128 >= 0) {
            $ex = $ex - 128;
        }
        if ($ex >= 64) {
            $lvl+=4;
            $ex+=-64;
        }
        if ($ex < 32) {
            $exc6 = 0;
        } else {
            $exc6 = 1;
            $ex+=-32;
        }
        if ($ex < 16) {
            $exc5 = 0;
        } else {
            $exc5 = 1;
            $ex+=-16;
        }
        if ($ex < 8) {
            $exc4 = 0;
        } else {
            $exc4 = 1;
            $ex+=-8;
        }
        if ($ex < 4) {
            $exc3 = 0;
        } else {
            $exc3 = 1;
            $ex+=-4;
        }
        if ($ex < 2) {
            $exc2 = 0;
        } else {
            $exc2 = 1;
            $ex+=-2;
        }
        if ($ex < 1) {
            $exc1 = 0;
        } else {
            $exc1 = 1;
            $ex+=-1;
        }
       if($season_settings[0] == 20){
		 $level    = substr($_item, 0, 1);
         $level2   = substr($_item, 1, 1);
         $level3   = substr($_item, 14, 2);
         $AA       = $level;
         $BB       = $level2;
         $CC       = $level3; 
		 $refinery = 0;
		 $level1   = hexdec(substr($level, 0, 1));
		 
		if (($level1 % 2) <> 0) {
            $level2 = "1" . $level2;
            $level1--;
        }
        if (hexdec($level3) >= 128) {
            $level1 += 16;
        }
        $level1 /= 2;
        $level2 = hexdec($level2);
        $harmonyoption = 0;
	   }
	   else{	
		 $level1        =   hexdec(substr($_item,18,1));	   
		 $level2        =   fix_id(hexdec( substr( $_item, 0, 2 ) ),hexdec( substr( $_item, 14, 2 ) ) );
         $level3        =   substr($_item, 14, 2);  
		 $AA            =   $level1;
         $BB            =   strval(intval($level2));
         $CC            =   $level3;
		 $refinery		=	hexdec(substr($_item,19,1));
		 $harmonyoption	=	hexdec(substr($_item,20,1));		
		 $harmonyvalue	=	hexdec(substr($_item,21,1));		 
		 $socket[1]     =   hexdec(substr($_item,22,2));
		 $socket[2]     =   hexdec(substr($_item,24,2));
		 $socket[3]     =   hexdec(substr($_item,26,2));
		 $socket[4]     =   hexdec(substr($_item,28,2));
		 $socket[5]     =   hexdec(substr($_item,30,2));
         $bonus_socket  =   hexdec(substr($_item,20,2)); 
	    }

 
        $rows         = mssql_fetch_array(mssql_query("SELECT * FROM [DTweb_JewelDeposit_Items] WHERE [id]={$level2} AND [type]={$level1}"));
		$invview      = mssql_fetch_array(mssql_query("SELECT * FROM [DTweb_AllSeasons_Items] WHERE [id]={$level2} AND [type]={$level1}"));
        $iopxltype    = $invview['exeopt'];
        $itemname     = $invview['Name'];
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
// WEAPONS AND ARMOR ADITIONAL INFO  /////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
       if($level1 < 12){
		    if	($invview['ReqLevel']	      >	    0){	 $item_req	.=	"Level Requirement:    ". $invview['ReqLevel']	    ." <br>";	}
		    if	($invview['ReqStrength']	  >	    0){	 $item_req	.=	"Strength Requirement: ". $invview['ReqStrength']	." <br>";	}
	        if	($invview['ReqDexterity']	  >	    0){	 $item_req	.=	"Agility Requirement:  ". $invview['ReqDexterity']	." <br>";	}
	        if	($invview['ReqVitality']	  >	    0){	 $item_req	.=	"Vitality Requirement: ". $invview['ReqVitality']	." <br>";	}
	        if	($invview['ReqEnergy']		  >	    0){	 $item_req	.=	"Energy Requirement:   ". $invview['ReqEnergy']		." <br>";	}
	        if	($invview['ReqCommand']		  >	    0){	 $item_req	.=	"Command Requirement:  ". $invview['ReqCommand']	." <br>";	}	    
		    if	($invview['GrowLancer']       ==	1){  $item_for	.=  "Can be equipped by Grow Lancer                        <br>";   }
		    if	($invview['RageFighter']	  ==	1){  $item_for	.=  "Can be equipped by Rage Fighter                       <br>";   }
		    if	($invview['DarkKnight']       ==	1){  $item_for	.=  "Can be equipped by Dark Knight                        <br>";   }
	        if	($invview['DarkKnight']	      ==	2){  $item_for	.=  "Can be equipped by Blade Knight                       <br>";   }
	        if	($invview['DarkKnight']	      ==	3){  $item_for	.=  "Can be equipped by Blade Master                       <br>";   }
	        if	($invview['DarkWizard']	      ==	1){  $item_for	.=  "Can be equipped by Dark Wizard                        <br>";   }
	        if	($invview['DarkWizard']	      ==	2){  $item_for	.=  "Can be equipped by Soul Master                        <br>";   }
	        if	($invview['DarkWizard']	      ==	3){  $item_for	.=  "Can be equipped by Grand Master                       <br>";   }
	        if	($invview['FairyElf']	      ==	1){  $item_for	.=  "Can be equipped by Fairy Elf                          <br>";   }
	        if	($invview['FairyElf']	      ==	2){  $item_for	.=  "Can be equipped by Muse Elf                          <br>";   }
	        if	($invview['FairyElf']	      ==	3){  $item_for	.=  "Can be equipped by Height elf                         <br>";   }
	        if	($invview['MagicGladiator']	  ==	1){  $item_for	.=  "Can be equipped by Magic Gladiator                    <br>";   }
	        if	($invview['MagicGladiator']	  ==	2){  $item_for	.=  "Can be equipped by Duel Master                        <br>";   }
	        if	($invview['MagicGladiator']	  ==	3){  $item_for	.=  "Can be equipped by Duel Master                        <br>";   }
	        if	($invview['DarkLord']         ==	1){  $item_for	.=  "Can be equipped by Dark Lord                          <br>";   }
	        if	($invview['DarkLord']	      ==	2){  $item_for	.=  "Can be equipped by Lord Emperor                       <br>";   }
	        if	($invview['DarkLord']	      ==	3){  $item_for	.=  "Can be equipped by Lord Emperor                       <br>";   }
	        if	($invview['Summoner']	      ==	1){  $item_for	.=  "Can be equipped by Summoner                           <br>";   }
	        if	($invview['Summoner']	      ==	2){  $item_for	.=  "Can be equipped by Bloody Summoner                    <br>";   }
	        if	($invview['Summoner']	      ==	3){  $item_for	.=  "Can be equipped by Dimension Master                   <br>";   }
			if(strlen($item_for) == 464){
			   $item_for_fix = "Can be equipped by any class";
			   }
		       else{
			   $item_for_fix = $item_for;
		   }
	   }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// EXCELLENT OPTIONS  ////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        switch ($iopxltype) {
			      //All Weapons
                   case 0 :
		               $op1     = 'Increase Mana per kill +8';
		               $op2     = 'Increase hit points per kill +8';
		               $op3     = 'Increase attacking(wizardly)speed+7';
		               $op4     = 'Increase wizardly damage +2%';
		               $op5     = 'Increase Damage +level/20';
		               $op6     = 'Excellent Damage Rate +10%';
		               $inf     = 'Additional Damage';
                       break;
		          //All Armors
                   case 1:
                       $op1     = 'Increase Zen After Hunt +40%';
                       $op2     = 'Defense success rate +10%';
                       $op3     = 'Reflect damage +5%';
                       $op4     = 'Damage Decrease +4%';
                       $op5     = 'Increase MaxMana +4%';
                       $op6     = 'Increase MaxHP +4%';
                       $inf     = 'Additional Defense';			
                       $skill   = '';
                       $nocolor = false;
                       break;
		          //Wings Only ** DB Equal to DarkMaster Server files Item.kor - 97/99XT 
                   case 2:
 		               $op1	    = ' Ignore Def +5% / HP +'.(50+(5*$itemlevel));
		               $op2	    = ' Return Attack +5% / Mana +'.(50+(5*$itemlevel));
		               $op3	    = " Life Recovery + 5% / Ignore 3% of your opponent armor";
		               $op4	    = ' Mana Recovery +5% / Increase Stamina +50';
		               $op5	    = ' Increase Attacking Speed +5';
		               $op6	    = '';
		               $inf	    = 'Additional Damage';
		               $skill	= '';
		               $nocolor = true;
		          	break;
		          //Rings Only ** DB Equal to DarkMaster Server files Item.kor - 97/99XT 
		          case 3:
                       $op1     = ' + HP 4%';
                       $op2     = ' + MANA 4%';
                       $op3     = ' Reduce DMG +4%';
                       $op4     = ' Reflect DMG + 5%';
                       $op5     = ' Defense Rate + 10%';
                       $op6     = ' Zen After Hunting +40%';
                       $inf     = ' Additional Damage';
                       $skill   = '';
                       $nocolor = true;
		          	break;
		          //Pendants Only **  DB Equal to DarkMaster Server files Item.kor - 97/99XT 
		          case 4:
                       $op1     = ' Excellent Damage Rate +10%';
                       $op2     = ' + Damage +Level /20';
                       $op3     = ' + Damage 2%';
                       $op4     = ' Wizard Speed +7';
                       $op5     = ' +LIFE After Hunting (LIFE/8)';
                       $op6     = ' +MANA After Hunting (MANA/8)';
                       $inf     = ' Additional Damage';
                       $skill   = '';
                       $nocolor = true;
		           break;
				 //Fenrir
		          case 5:
				     $skill	    = " Plasma storm skill (Mana:50)<br>";
                       $op1     = ' Black Fenrir </br>Plasma storm skill (Mana:50)<br>Increase final damage 10%';
                       $op2     = ' Blue Fenrir </br>Plasma storm skill (Mana:50)<br>Absorb final damage 10%<br>Increase speed';
                       $op3     = ' Golden Fenrir</br>Plasma storm skill (Mana:50)<br>Increase speed'; 
                       $op4     = '';
                       $op5     = '';
                       $op6     = '';
                       $inf     = '';
                       $skill   = '';
                       $nocolor = false;
					   $c       = "red";
		             break;
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
         if ($exc1 == 1) {
            $itemexl .= '<br>' . $op1;
	     
			$exl_opts[] = 1;
        }
        if ($exc2 == 1) {
            $itemexl .= '<br>' . $op2;

			$exl_opts[] = 2;
        }
        if ($exc3 == 1) {
            $itemexl .= '<br>' . $op3;
	
			$exl_opts[] = 3;
        }
        if ($exc4 == 1) {
            $itemexl .= '<br>' . $op4;
	
			$exl_opts[] = 4;
        }
        if ($exc5 == 1) {
            $itemexl .= '<br>' . $op5;
		
			$exl_opts[] = 5;
        }
        if ($exc6 == 1) {
            $itemexl .= '<br>' . $op6;		
			$exl_opts[] = 6;
        }

        if ($rows['exeopt'] == 0) {
            $itemoption = $lvl * 4;
        } else if ($rows['exeopt'] == 4) {
            $itemoption = ($lvl) . '%';
            $inf = ' Automatic HP Recovery rate ';
        } else {
            $itemoption = $lvl * 5;
            $inf = 'Additional Defense rate ';
        }
		
        $c = '#FFFFFF'; // White -> Normal Item
        if (($lvl > 1) || ($luck != '')) {
            $c = '#8CB0EA';
        }
        if ($itemlevel > 6) {
            $c = '#F4CB3F';
        }

        if ($itemexl != '') {
            $c = '#ccff99';
        } // Green -> Excellent Item 
        if ($nocolor) {
            $c = '#F4CB3F';
        }
        if ($itemoption == 0) {
			$itm        = 0;
            $itemoption = '';
        } else {
			$itm   = $itemoption;
            $itemoption = $inf . " +" . $itemoption;
			
        }
        if (($itemexl != '') && ($itemname) && (!$nocolor)) {
            $itemname = 'Excellent ' . $itemname;
			$rowinfo = '</br><font style=color:#40ff00>Excellent Options</font>';
        }


        if ($nolevel == 1) {
            $ilvl = 0;
        } else {
            $ilvl = $itemlevel;
        }
		

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
// WEAPONS DAMAGE CALCULATION  ///////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($level1 <= 5)
		{
			$min_dmg  = $invview["DamageMin"];
			$max_dmg  = $invview["DamageMax"];

				if ($itemlevel == 10) {
						$min_dmg += 1;
						$max_dmg += 1;
					}

					if ($itemlevel == 11) {
						$min_dmg += 3;
						$max_dmg += 3;
					}

					if ($itemlevel == 12) {
						$min_dmg += 6;
						$max_dmg += 6;
					}

					if ($itemlevel == 13) {
						$min_dmg += 10;
						$max_dmg += 10;
					}

					if ($itemlevel == 14) {
						$min_dmg += 15;
						$max_dmg += 15;
					}

					if ($itemlevel == 15) {
						$min_dmg += 21;
						$max_dmg += 21;
					}

					if ((count($exl_opts) > 0) && ($anc != 0)) {
						$min_dmg += 35;
						$max_dmg += 35;
					}
					else if ((count($exl_opts) <= 0) && ($anc != 0)) {
						$min_dmg += 35;
						$max_dmg += 35;
					}
					else if ((count($exl_opts) > 0) && ($anc == 0)) {
						$min_dmg += 25;
						$max_dmg += 25;
					}
					if($min_dmg > $invview["DamageMin"]){
						$colors   ="#99ccff";
					}
					else{
						$colors   ="";
					}
			if($invview["TwoHand"] === 1){			
				$addinfo .= "<span style='color:".$colors.";'>Two handed attack power: " . $min_dmg . "~" .  $max_dmg. "</span></br>";	
			}				
			else{
				$addinfo .= "<span style='color:".$colors.";'>One handed attack power: " . $min_dmg . "~" .  $max_dmg."</span></br>"; 
			}
			if($invview["MagicPower"] > 0){
					$addinfo .= "</br>Magic Power: " . $invview["MagicPower"]. "</br>";
			}
		}
		
		if($level1 == 10 || $level1 <= 5){
			$addinfo .= "Attack Speed: " .$invview["AttackSpeed"]. "</br>";
		}
		if($level1 === 11){
			$addinfo .= "Walk Speed: " .$invview["WalkSpeed"]. "</br>";
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
//GEARS/ARMOR CALCULATION ////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($level1 > 5 && $level1 < 12)
		{
					if ($level1 != 6) {
						$default_def = $invview["Defense"] + ($itemlevel * 3);

						if ($itemlevel == 10) {
							$default_def += $itemlevel - 9;
						}

						if ($itemlevel == 11) {
							$default_def += $itemlevel - 8;
						}

						if ($itemlevel == 12) {
							$default_def += $itemlevel - 6;
						}

						if ($itemlevel == 13) {
							$default_def += $itemlevel - 3;
						}

						if ($itemlevel == 14) {
							$default_def += $itemlevel + 1;
						}

						if ($itemlevel == 15) {
							$default_def += $itemlevel + 6;
						}
					    if ((count($exl_opts) > 0) && ($anc <> 0)) {
					    	$default_def += 25;
					    }
					    else if ((count($exl_opts) <= 0) && ($anc <> 0)) {
					    	$default_def += 25;
					    }
					    else if ((count($exl_opts) > 0) && ($anc == 0)) {
					    	$default_def += 15;
                        
					    }
						if($default_def > ($invview["Defense"] + ($itemlevel * 3))){
						$colors   ="#99ccff";
					     }
					     else{
					     	$colors   ="";
					     }
                      $addinfo .= "<span style='color:".$colors.";'>Armor: " .$default_def. "</span></br>";
					}
					else {
                        $default_def   = $invview["Defense"] + $itemlevel;
						$success_block = $invview["SuccessfulBlocking"] + ($itemlevel * 3);

						if ($itemlevel == 10) {
							$success_block += $itemlevel - 9;
						}

						if ($itemlevel == 11) {
							$success_block += $itemlevel - 8;
						}

						if ($itemlevel == 12) {
							$success_block += $itemlevel - 6;
						}

						if ($itemlevel == 13) {
							$success_block += $itemlevel - 3;
						}

						if ($itemlevel == 14) {
							$success_block += $itemlevel + 1;
						}

						if ($itemlevel == 15) {
							$success_block += $itemlevel + 6;
						}

					    if ((count($exl_opts) > 0) && ($anc <> 0)) {
					    	$success_block += 30;
					    }
					    else if ((count($exl_opts) <= 0) && ($anc <> 0)) {
					    	$success_block += 30;
					    }
					    else if ((count($exl_opts) > 0) && ($anc == 0)) {
					    	$success_block += 30;
                        
					    }
						
						if($success_block > ($invview["SuccessfulBlocking"] + ($itemlevel * 3))){
						$colors   ="#99ccff";
					     }
					     else{
					     	$colors   ="";
					     }
						 $addinfo .= "Armor: " .$default_def. "</br>";
						 $addinfo .= "<span style='color:".$colors.";'>Defense Rate: " .$success_block . "</span></br>";	
					}
				}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
// DURABILITY CALCULATIONS ///////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$dur_steps = array(6,8,10,12,14,17,21,26,32,39,47);

		if ($level2 == 5) {
				$default_dur = $invview['MagicDurability'];
			}
		else{
				$default_dur = $invview['Durability'];
			}
			
        
		if($itemlevel < 5){
			$default_dur += $itemlevel;
		}
		else{
		    $default_dur += $dur_steps[$itemlevel - 5];
		}	
  
	    if ($anc > 0) {
						$default_dur += $anc_sum ;
		}
		if ($itemexl != "") {
						$default_dur += 15;
		}
		
		if($invview['Durability'] > 0){
             $output['dur']         = "Durability:[". $itemdur ."/". $default_dur."]</br>";
		}
		elseif($invview['MagicDurability'] > 0){
			 $output['dur']         = "Magic Durability:[". $itemdur ."/". $default_dur."]</br>";
		}
		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
//SPECIAL ITEMS //////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  

		if($level1 == 14){
			switch ($level2){
				case 11:
				
			      $addinfo .= "Drop this to receive a gift</br><font color=red> Can not be sold!</font>";
			      $special = true;
			      $c = '#ffb299';
			      $imgfix = "";
                 switch($itemlevel){
			     	case 0 :  $itemname = "Box of Luck";                       $imgfix ='luck';                     break;
			     	case 1 :  $itemname = "Star of Xmas";                      $imgfix ='star';                     break;
			     	case 2 :  $itemname = "Firecracker";                       $imgfix ='fire';                     break;
			     	case 3 :  $itemname = "Heart of Love";                     $imgfix ='love';                     break;
			     	case 5 :  $itemname = "Silver Medalion";                   $imgfix ='silver';                   break;
			     	case 6 :  $itemname = "Golden Medalion";                   $imgfix ='gold';                     break;
			     	case 7 :  $itemname = "Box of Heaven";                     $imgfix ='heaven';                   break;
			     	default:  $itemname = "Box of Kundun +" .($itemlevel - 7); $imgfix ='box'.($itemlevel - 7);     break;
			     } 
				break;
				case 21:
				   switch($itemlevel){
					   case 1:  $itemname = "Stone";  $imgfix ='stone'; $itemlevel = 0;break; 				   
				   }
				break;
				case 32:
					if($itemlevel  == 0){
				       $itemname= "Pink Chocolate Box";			
				    } else {
					   $itemname="Lilac Candy Box";
					   $itemlevel=0;				
					}
				break;
				
				case 33:
					if($itemlevel  == 0){
				       $itemname= "Red Chocolate Box";			
				    } else {
					   $itemname="Orange Candy Box";
					   $itemlevel=0;				
					}
				break;
				
				case 23:
					if($itemlevel > 0){
				       $itemname= "Ring of Honor Quest Item";
                       $imgfix    = 'honor';					   
				    } 
				break;	
				case 24:
					if($itemlevel > 0){
				       $itemname= "Dark Stone";
                       $imgfix    = 'darkstone';					   
				    } 
				break;	
			
			}			
		}

// MUUN's AND PETS
    $mlist  = range(409,464,1);
	$mlist1 = range(480,503,1);
	$pclist = range(469,479,1);
	
	if($level1 == 13){
	        if(in_array($level2,$mlist) || in_array($level2,$mlist1)){
	        	$plusche       = "";
	        	$itemlevel     = "";
	        	$rowinfo       = "";
	        	$output['dur'] = "Life:" . $itemdur . "</br>Normal Type";
	        	$itemexl       = "";
	        	$luck          = "";		
	        	$anc_opt       = "";
	        	$itemanc       = "";
	        	$anc           = 0 ;
                $itemoption    = "";
	        	$skill         = "<font color=#79ff4c>Level0 / Level3</font>";
	        	$itemname      = $invview['Name'] ."</br> <img src='".itemimage($AA, $BB, $CC,$anc,$imgfix)."'>";
	        	$premium       = "</br><font color=#ffbfbf>You can not wear same type of premium Muun at a time</font>";
	        	if($level2%2==0){
	        		$skill       = "<font color=#79ff4c>Level Max</font>";
	        	}
                
	        	switch($level2){
	        		case 502: case 503: $luck = "</br>If Mon,Tue,Wen,Thu Attack Skill (Level 4) (Non-PVP)";  break;
	        		case 500: case 501: $luck = "</br>If 1PM~12PM Critical Damage +8 (Non-PVP)";             break;
	        		case 498: case 499: $luck = "</br>If We,Thu,Fri,Sat,Sun Increases Skill Damage by +19 (Non-PVP)</br>Applies to PVP when Evolved</br></br><font color=#ffbfbf>You can not wear same type of premium Muun at a time</font>" ;break;
	        		case 496: case 497: $luck = "</br>If 1PM~12PM Attack Skill +4 (Non-PVP)";               break;
	        		case 494: case 495: $luck = "</br>If 201~ Master Level, Increases Excellent Damage by +8 (Non-PVP)";               break;
	        		case 492: case 493: $luck = "</br>If We,Thu,Fri,Sat,Sun Increases Damage/Magical Damage/Curse by 24 (PVP)
	        		                             </br><font color=#ffbfbf>You can not wear same type of premium Muun at a time</font>";               break;
	        		case 490: case 491: $luck = "</br>If 201~ Master Level, Attack Skill (Level 4) (Non-PVP)";               break;
	        		case 488: case 489: $luck = "</br>If 1PM~12PM Decrease Item Repair fee by 4% ";               break;
	        		case 486: case 487: $luck = "</br>If We,Thu,Fri,Sat,Sun Increases Skill Damage by +19 (Non-PVP)</br>Applies to PVP when Evolved</br>";               break;
	        		case 484: case 485: $luck = "</br>If 1PM~12PM Attack Skill (Level 4) (Non-PVP)";              break;
	        		case 482: case 483: $luck = "</br>Thur,Fri,Sat,Sun Increases Defense +14";               break;
	        		case 480: case 481: $luck = "</br>If We,Thu,Fri,Sat,Sun Increases Max Damage/Magical Damage/Curse by 24 (PVP)</br><font color=#ffbfbf>You can not wear same type of premium Muun at a time</font>";              break;
	        		case 478: case 479: $luck = "</br>If 1~12PM Critical Damage +8 (Non-PVP)";break;
	        		case 476: case 477: $luck = "</br>If 1~12PM Critical Damage +8 (Non-PVP)";break;
	        		case 474: case 475: $luck = "</br>If 1~12PM Critical Damage +8 (Non-PVP)";break;
                   // Add all muun ID-s and their options here		
	        	}
			}
            elseif(in_array($level2,$pclist)){
				  $plusche       = "";
	        	  $itemlevel     = "";
				  $def_info      = "</br>Durability:[". $itemdur ."/". $invview['Durability']."]</br>Minimum Level Required 1";
				  $output['dur'] = "<font color=#ffff00>Item is for Internet cafe. Only useable at Internet cafe</font>";
				  $last          = "</br><font color=#ffbfbf>Unable to equip while wearing a different transformation ring</font>";
				  $pandas        = "</br>Use Pet Skeletons and Pet Pandas at the same time to acquire additional EXP50%";
	              switch($level2){
					  case 479: $output['dur'] .= "</br>Ticket for one additional entry into event map.</br>Automatically used upon event map entry attempt";  break;
				      case 478: $output['dur'] .= "</br>Ticket for one additional entry into event map.</br>Automatically used upon event map entry attempt";  break;
                      case 477: $output['dur'] = "Life:" . $itemdur . "</br>Normal Type";$itemname      = $invview['Name'] ."</br> <img src='".itemimage($AA, $BB, $CC,$anc,$imgfix)."'>";$skill         = "<font color=#79ff4c>Level0 / Level3</font></br>";$luck = "If you have enable mun. Increases Skill Damage (Non-PVP)";if($level2%2 == 0){$skill= "<font color=#79ff4c>Level Max</font>";}break;
				      case 476: $luck = "Idol</br>Earning an additional 50% EXP</br>Right Click on this item in your inventory to use it";  break;
					  case 475: $output['dur'] .= $def_info; $luck = "Equip this to transform into a Great Heavently Mage</br> Attack Wizardly/Curse +40" . $pandas . $last ;  break;
					  case 474: $output['dur'] .= $def_info; $luck = "Equip this to transform into a Mini Robot.</br>Attack/Wizardry/Curse + 30</br>Increases Attack Speed by 7" .$pandas.$last;  break;
					  case 473: $output['dur'] .= $def_info; $luck = "Equip this to transform into a Robot Knight.</br>Attack/Wizardry/Curse + 30</br>Increases Defense by 10" .$pandas.$last;  break;
					  case 472: $output['dur'] .= $def_info; $luck = "Equip this to transform into a Skeleton Warrior.</br>Attack/Wizardry/Curse + 40".$pandas.$last;   break;
					  case 471: $output['dur'] .= $def_info; $luck = "Equip this to transform into a Panda.</br>Increases Zen by 50%<br>Attack/Wizardry/Curse + 30".$pandas.$last;  break;
					  case 470: $luck = "Auto-collects zen around you.</br>Attack/Wizardry/Curse + 20</br>Increases attack speed by 10</br>Increases EXP gain rate by 30%</br><font color=#FFF>HP:".$itemdur ."</br>Minimum Level Required 1</font></br>Increases Exp gain by 50% when equipped along with a Transformation ring"; break;
					  case 469: $luck = "Auto-collects zen around you.</br>Increases Defensive Skill +50</br>Increases EXP gain rate by 50%</br><font color=#FFF>HP:".$itemdur ."</br>Minimum Level Required 1</font></br>Increases Exp gain by 50% when equipped along with a Transformation ring";  break;
				  }
			}		
	}
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
// RAFINERY INFO -> Taken from IGCN Item Editor! -> may not be the same with other editors/servers
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$ref_data = array(
		                "Additional Dmg +200 (PvP)</br> Attack Success Rate Increase +10 (PvP)",       //Swords
					    "",                                                                            //Axes
					    "Additional Dmg +200 (PvP)</br> Attack Success Rate Increase +10 (PvP)",       //Maces
					    "",                                                                            //Spears
					    "Additional Dmg +200 (PvP)</br> Attack Success Rate Increase +10 (PvP)",       //Crossbows & Bows
					    "Additional Dmg +200 (PvP)</br> Attack Success Rate Increase +10 (PvP)",       //Staffs
					    "",                                                                            //Shields
					    "SD Recovery Rate Increase +20 </br> Defense Success Rate Increase +10 (PvP)", //Helmets
					    "SD Auto Recovery</br> Defense Success Rate Increase +10 (PvP)",               //Armors
					    "Defense Skill +200 (PvP)</br>Defense Success Rate Increase +10 (PvP)",        //Pants
					    "Max HP Increase +200</br>Defense Success Rate Increase +10 (PvP)",            //Gloves
					    "Max SD Increase +700</br>Defense Success Rate Increase +10 (PvP)"             //Boots                                                    //
					); 
					
		if ($refinery	>	0)
		{
			@$refine	=	"<br><font color=#FF1493><strong>".$ref_data[$rows['type']]."</strong></font><br>";
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// HARMONY OPTIONS	//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
        $harmony_opt = array(
            1=>array(
               1=>array('name'=>'Minimum Attack Power Increase +','opt'=>array('2','3','4','5','6','7','9','11','12','14','15','16','17','20','23','26')),
               2=>array('name'=>'Maximum Attack Power Increase +','opt'=>array('3','4','5','6','7','8','10','12','14','17','20','23','26','29','32','35')),
               3=>array('name'=>'Require Strength Decrease -','opt'=>array('6','8','10','12','14','16','20','23','26','29','32','35','37','40','43','46')),
               4=>array('name'=>'Require Agility Decrease -','opt'=>array('6','8','10','12','14','16','20','23','26','29','32','35','37','40','43','46')),
               5=>array('name'=>'Attack Power Increase (Min, Max) +','opt'=>array('0','0','0','0','0','0','7','8','9','11','12','14','16','19','22','25')),
               6=>array('name'=>'Critical Damage Increase +','opt'=>array('0','0','0','0','0','0','12','14','16','18','20','22','24','30','33','36')),
               7=>array('name'=>'Skill Attack Power Increase +','opt'=>array('0','0','0','0','0','0','0','0','0','12','14','16','18','20','22','24','26')),
               8=>array('name'=>'Attack Success Rate (PVP) +','opt'=>array('0','0','0','0','0','0','0','0','0','5','7','9','11','14','16','18')),
               9=>array('name'=>'SD Decrease Rate Increase +','opt'=>array('0','0','0','0','0','0','0','0','0','3','5','7','9','10','11','12')),
               10=>array('name'=>'SD Ignore Rate +','opt'=>array('0','0','0','0','0','0','0','0','0','0','0','0','0','10','12','14')),
            ),
            2=>array(
               1=>array('name'=>'Magic Power Increase +','opt'=>array('6','8','10','12','14','16','17','18','19','21','23','25','27','31','33','35')),
               2=>array('name'=>'Require Strength Decrease -','opt'=>array('6','8','10','12','14','16','20','23','26','29','32','35','37','40','43','46')),
               3=>array('name'=>'Require Agility Decrease -','opt'=>array('6','8','10','12','14','16','20','23','26','29','32','35','37','40','43','46')),
               4=>array('name'=>'Skill Attack Power Increase +','opt'=>array('0','0','0','0','0','0','7','10','13','16','19','22','25','30','33','36')),
               5=>array('name'=>'Critical Damage Increase +','opt'=>array('0','0','0','0','0','0','10','12','14','16','18','20','22','28','30','32')),
               6=>array('name'=>'SD Decrease Rate Increase +','opt'=>array('0','0','0','0','0','0','0','0','0','4','6','8','10','13','15','17')),
               7=>array('name'=>'Attack Success Rate (PVP) +','opt'=>array('0','0','0','0','0','0','0','0','0','5','7','9','11','14','15','16')),
               8=>array('name'=>'SD Ignore Rate +','opt'=>array('0','0','0','0','0','0','0','0','0','0','0','0','0','15','17','19')),
            ),
            3=>array(
               1=>array('name'=>'Defense Power Increase +','opt'=>array('3','4','5','6','7','8','10','12','14','16','18','20','22','25','28','31')),
               2=>array('name'=>'Maximum AG Increase +','opt'=>array('0','0','0','4','6','8','10','12','14','16','18','20','22','25','28','31')),
               3=>array('name'=>'Maximum HP Increase +','opt'=>array('0','0','0','7','9','11','13','15','17','19','21','23','25','30','32','34')),
               4=>array('name'=>'HP Automatic Increase +','opt'=>array('0','0','0','0','0','0','1','2','3','4','5','6','7','8','9','10')),
               5=>array('name'=>'MP Automatic Increase +','opt'=>array('0','0','0','0','0','0','0','0','0', '1','2','3','4','5','6','7')),
               6=>array('name'=>'Defense Success rate Increase (PVP)','opt'=>array('0','0','0','0','0','0','0','0','0', '3','4','5','6','8','10','12')),
               7=>array('name'=>'Damage Decrementing Increase +','opt'=>array('0','0','0','0','0','0','0','0','0','1%','1%','2%','3%','4%','8%','9%')),
               8=>array('name'=>'SD Rate Increase +','opt'=>array('0','0','0','0','0','0','0','0','0', '0','0','0','0','5','6','7')),
            ),
        );	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
// SPHERE SEEDS //////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($level1 == 12) 
		{	
		    $sphereoptions = array
		    (
		        100 => array(20,7,30,20,20,'40%'),
		        106 => array(19,8,32,22,22,'41%'),
		        112 => array(18,9,35,25,25,'42%'),
		        118 => array(17,10,40,30,30,'43%'),
		        124 => array(14,11,50,35,35,'44%'),
		        101 => array('10%',30,'7%','4%','5%'),
		        107 => array('11%',33,'10%','5%','6%'),
		        113 => array('12%',36,'15%','6%','7%'),
		        119 => array('13%',39,'20%','7%','8%'),
		        125 => array('14%',42,'30%','8%','9%'),
		        102 => array(8,8,37,25,'30%'),
		        108 => array(7,7,40,27,'32%'),
		        114 => array(6,6,45,30,'34%'),
		        120 => array(5,5,50,35,'36%'),
		        126 => array(4,4,60,40,'38%'),
		        103 => array(8,'4%','4%',7,50,5),
		        109 => array(10,'5%','5%',14,70,7),
		        115 => array(13,'6%','6%',21,90,9),
		        121 => array(16,'7%','7%',28,110,11),
		        127 => array(20,'8%','8%',35,120,13),
		        104 => array(15,'10%',30,'8%'),
		        110 => array(20,'11%',32,'9%'),
		        116 => array(25,'12%',34,'10%'),
		        122 => array(30,'13%',40,'11%'),
		        128 => array(40,'14%',50,'12%'),
		        105 => array( 2 => 30 ),
		        111 => array( 2 => 32 ),
		        117 => array( 2 => 34 ),
		        123 => array( 2 => 36 ),
		        129 => array( 2 => 38 )
		    );
           if(in_array($level2,$sphereoptions)){
			   $shereadd = $sphereoptions[$level2][$itemlevel];
		   }
		   else{
			   $shereadd = "Unknown Value";
		   }
		    
	 
			
			if (in_array( $level2, array(60,100,106,112,118,124))) {
				$sphereopt = array(
					'(level type) Attack/Wizardy increase',
					'Attack speed Increase',
					'Maximum Attack/Wizardry Increase',
					'Minimum Attack/Wizardry Increase',
					'Attack/Wizardry Increase',
					'AG cost decrease'
				);
				$sphereinfo = '';

				if (60 < $level2) {
					$sphereinfo = ' + ' . $shereadd;
				}

				$seedopt = '<div>Fire attribute</div><div style=color:#80B2FF;font-family: tahoma;font-size: 12px;>Element: ' . $sphereopt[$itemlevel] . $sphereinfo . '</div>';
			}

			if (in_array( $level2, array(61,101,107,113,119,125) )) {
				$sphereopt = array(
					'Block rating Increase',
					'Defense Increase',
					'Shield protection Increase',
					'Damage reduction',
					'Damage reflection'
				);
				$sphereinfo = '';

				if (60 < $level2) {
					$sphereinfo = ' + ' . $shereadd;
				}

				$seedopt = '<div>Water attribute</div><div style=color:#80B2FF;font-family: tahoma;font-size: 12px;>Element: ' . $sphereopt[$itemlevel] . $sphereinfo . '</div>';
			}

			if (in_array( $level2, array(62,102,108,114,120,126) )) {
				$sphereopt = array(
					'Monster destruction for the Life Increase',
					'Monster destruction for the Mana Increase',
					'Skill Attack Increase',
					'Attack rating Increase',
					'Item durability Increase'
				);
				$sphereinfo = '';

				if (60 < $level2) {
					$sphereinfo = ' + ' . $shereadd;
				}

				$seedopt = '<div>Ice attribute</div><div style=color:#80B2FF;font-family: tahoma;font-size: 12px;>Element: ' . $sphereopt[$itemlevel] . $sphereinfo . '</div>';
			}

			if (in_array( $level2, array(63,103,109,115,121,127) )) {
				$sphereopt = array(
					'Automatic Life recovery Increase',
					'Maximum Life Increase',
					'Maximum Mana Increase',
					'Automatic Mana recovery Increase',
					'Maximum AG Increase',
					'AG value Increase'
				);
				$sphereinfo = '';

				if (60 < $level2) {
					$sphereinfo = ' + ' . $shereadd;
				}

				$seedopt = '<div>Wind attribute</div><div style=color:#80B2FF;font-family: tahoma;font-size: 12px;>Element: ' . $sphereopt[$itemlevel] . $sphereinfo . '</div>';
			}

			if (in_array( $level2, array(64,104,110,116,122,128) )) {
				$sphereopt = array(
					'Excellent damage Increase',
					'Excellent damage rate Increase',
					'Critical damage Increase',
					'Critical damage rate Increase'
				);
				$sphereinfo = '';

				if (60 < $level2) {
					$sphereinfo = ' + ' . $shereadd;
				}

				$seedopt ='<div>Lightening attribute</div><div style=color:#80B2FF;font-family: tahoma;font-size: 12px;>Element: ' . $sphereopt[$itemlevel] . $sphereinfo . '</div>';
			}

			if (in_array( $level2, array(65,105,111,117,123,129) )) {
				$sphereopt = array( 2 => 'Health Increase' );
				$sphereinfo = '';

				if (60 < $level2) {
					$sphereinfo = ' + ' . $shereadd;
				}

				$seedopt = '<div>Earth attribute</div><div style=color:#80B2FF;font-family: tahoma;font-size: 12px;>Element: ' . $sphereopt[$itemlevel] . $sphereinfo . '</div>';
			}
		}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
// SOCKETS ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if ($season_settings[0] <> 20)
		    {
	
				for ($i=1;$i<=5;$i++)
				{
					
					// Weapon Only Sockets -->
					if ($socket[$i]==254)       { $socketoption.="<br>6>Socket {$i}: Empty</font>"; }
					elseif ($socket[$i]==0)     { $socketoption.="<br>Socket {$i}:(Fire)\n (+1)\n (Level Type)(Attack/Wizardry Increase +lvl/20)"; }                        
					elseif ($socket[$i]==50)    { $socketoption.="<br>Socket {$i}:(Fire)\n (+2)\n (Level Type)(Attack/Wizardry Increase +lvl/19)"; }
					elseif ($socket[$i]==100)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+3)\n (Level Type)(Attack/Wizardry Increase +lvl/18)"; }
					elseif ($socket[$i]==150)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+4)\n (Level Type)(Attack/Wizardry Increase +lvl/17)"; }
					elseif ($socket[$i]==200)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+5)\n (Level Type)(Attack/Wizardry Increase +lvl/14)"; }
					elseif ($socket[$i]==1)     { $socketoption.="<br>Socket {$i}:(Fire)\n (+1)\n (Level Type)(Attack Speed Increase +7)"; }
					elseif ($socket[$i]==51)    { $socketoption.="<br>Socket {$i}:(Fire)\n (+2)\n (Level Type)(Attack Speed Increase +8)"; }
					elseif ($socket[$i]==101)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+3)\n (Level Type)(Attack Speed Increase +9)"; }
					elseif ($socket[$i]==151)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+4)\n (Level Type)(Attack Speed Increase +10)"; }
					elseif ($socket[$i]==201)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+5)\n (Level Type)(Attack Speed Increase +11)"; }
					elseif ($socket[$i]==2)     { $socketoption.="<br>Socket {$i}:(Fire)\n (+1)\n (Level Type)(Maximum Attack/Wizardry Increase +30)"; }
					elseif ($socket[$i]==52)    { $socketoption.="<br>Socket {$i}:(Fire)\n (+2)\n (Level Type)(Maximum Attack/Wizardry Increase +32)"; }
					elseif ($socket[$i]==102)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+3)\n (Level Type)(Maximum Attack/Wizardry Increase +35)"; }
					elseif ($socket[$i]==152)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+4)\n (Level Type)(Maximum Attack/Wizardry Increase +40)"; }
					elseif ($socket[$i]==202)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+5)\n (Level Type)(Minimum Attack/Wizardry Increase +50)"; }
					elseif ($socket[$i]==3)     { $socketoption.="<br>Socket {$i}:(Fire)\n (+1)\n (Level Type)(Minimum Attack/Wizardry Increase +20)"; }
					elseif ($socket[$i]==53)    { $socketoption.="<br>Socket {$i}:(Fire)\n (+2)\n (Level Type)(Minimum Attack/Wizardry Increase +22)"; }
					elseif ($socket[$i]==103)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+3)\n (Level Type)(Minimum Attack/Wizardry Increase +25)"; }
					elseif ($socket[$i]==153)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+4)\n (Level Type)(Minimum Attack/Wizardry Increase +30)"; }
					elseif ($socket[$i]==203)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+5)\n (Level Type)(Minimum Attack/Wizardry Increase +35)"; }
					elseif ($socket[$i]==4)     { $socketoption.="<br>Socket {$i}:(Fire)\n (+1)\n (Attack/Wizardry Increase +20)"; }                        
					elseif ($socket[$i]==54)    { $socketoption.="<br>Socket {$i}:(Fire)\n (+2)\n (Attack/Wizardry Increase +22)"; }
					elseif ($socket[$i]==104)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+3)\n (Attack/Wizardry Increase +25)"; }
					elseif ($socket[$i]==154)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+4)\n (Attack/Wizardry Increase +30)"; }
					elseif ($socket[$i]==204)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+5)\n (Attack/Wizardry Increase +35)"; }					
					elseif ($socket[$i]==5)     { $socketoption.="<br>Socket {$i}:(Fire)\n (+1)\n (AG Cost Decrease +40%)"; }                        
					elseif ($socket[$i]==55)    { $socketoption.="<br>Socket {$i}:(Fire)\n (+2)\n (AG Cost Decrease +41%)"; }
					elseif ($socket[$i]==105)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+3)\n (AG Cost Decrease +42%)"; }
					elseif ($socket[$i]==155)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+4)\n (AG Cost Decrease +43%)"; }
					elseif ($socket[$i]==205)   { $socketoption.="<br>Socket {$i}:(Fire)\n (+5)\n (AG Cost Decrease +44%)"; }					
					elseif ($socket[$i]==16)    { $socketoption.="<br>Socket {$i}:(Ice)\n (+1)\n (Monster destruction for the Life Increase +hp/8)"; }                        
					elseif ($socket[$i]==66)    { $socketoption.="<br>Socket {$i}:(Ice)\n (+2)\n (Monster destruction for the Life Increase +hp/7)"; }
					elseif ($socket[$i]==116)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+3)\n (Monster destruction for the Life Increase +hp/6)"; }
					elseif ($socket[$i]==166)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+4)\n (Monster destruction for the Life Increase +hp/5)"; }
					elseif ($socket[$i]==216)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+5)\n (Monster destruction for the Life Increase +hp/4)"; }				
					elseif ($socket[$i]==17)    { $socketoption.="<br>Socket {$i}:(Ice)\n (+1)\n (Monster destruction for the Mana Increase +mp/8)"; }                        
					elseif ($socket[$i]==67)    { $socketoption.="<br>Socket {$i}:(Ice)\n (+2)\n (Monster destruction for the Mana Increase +mp/7)"; }
					elseif ($socket[$i]==117)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+3)\n (Monster destruction for the Mana Increase +mp/6)"; }
					elseif ($socket[$i]==167)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+4)\n (Monster destruction for the Mana Increase +mp/5)"; }
					elseif ($socket[$i]==217)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+5)\n (Monster destruction for the Mana Increase +mp/4)"; }				
					elseif ($socket[$i]==18)    { $socketoption.="<br>Socket {$i}:(Ice)\n (+1)\n (Skill Attack Increase +37)"; }                        
					elseif ($socket[$i]==68)    { $socketoption.="<br>Socket {$i}:(Ice)\n (+2)\n (Skill Attack Increase +40)"; }
					elseif ($socket[$i]==118)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+3)\n (Skill Attack Increase +45)"; }
					elseif ($socket[$i]==168)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+4)\n (Skill Attack Increase +50)"; }
					elseif ($socket[$i]==218)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+5)\n (Skill Attack Increase +60)"; }						
					elseif ($socket[$i]==19)    { $socketoption.="<br>Socket {$i}:(Ice)\n (+1)\n (Attack Rating Increase +25)"; }                        
					elseif ($socket[$i]==69)    { $socketoption.="<br>Socket {$i}:(Ice)\n (+2)\n (Attack Rating Increase +27)"; }
					elseif ($socket[$i]==119)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+3)\n (Attack Rating Increase +30)"; }
					elseif ($socket[$i]==169)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+4)\n (Attack Rating Increase +35)"; }
					elseif ($socket[$i]==219)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+5)\n (Attack Rating Increase +40)"; }						
					elseif ($socket[$i]==20)    { $socketoption.="<br>Socket {$i}:(Ice)\n (+1)\n (Item Durability Increase +30%)"; }                        
					elseif ($socket[$i]==70)    { $socketoption.="<br>Socket {$i}:(Ice)\n (+2)\n (Item Durability Increase +32%)"; }
					elseif ($socket[$i]==120)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+3)\n (Item Durability Increase +34%)"; }
					elseif ($socket[$i]==170)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+4)\n (Item Durability Increase +36%)"; }
					elseif ($socket[$i]==220)   { $socketoption.="<br>Socket {$i}:(Ice)\n (+5)\n (Item Durability Increase +38%)"; }					
					elseif ($socket[$i]==29)    { $socketoption.="<br>Socket {$i}:(Lighting)\n (+1)\n (Excellent Damage Increase +15)"; }                        
					elseif ($socket[$i]==79)    { $socketoption.="<br>Socket {$i}:(Lighting)\n (+2)\n (Excellent Damage Increase +20)"; }
					elseif ($socket[$i]==129)   { $socketoption.="<br>Socket {$i}:(Lighting)\n (+3)\n (Excellent Damage Increase +25)"; }
					elseif ($socket[$i]==179)   { $socketoption.="<br>Socket {$i}:(Lighting)\n (+4)\n (Excellent Damage Increase +30)"; }
					elseif ($socket[$i]==229)   { $socketoption.="<br>Socket {$i}:(Lighting)\n (+5)\n (Excellent Damage Increase +40)"; }					
					elseif ($socket[$i]==30)    { $socketoption.="<br>Socket {$i}:(Lighting)\n (+1)\n (Excellent Damage Rate Increase +10%)"; }                        
					elseif ($socket[$i]==80)    { $socketoption.="<br>Socket {$i}:(Lighting)\n (+2)\n (Excellent Damage Rate Increase +11%)"; }
					elseif ($socket[$i]==130)   { $socketoption.="<br>Socket {$i}:(Lighting)\n (+3)\n (Excellent Damage Rate Increase +12%)"; }
					elseif ($socket[$i]==180)   { $socketoption.="<br>Socket {$i}:(Lighting)\n (+4)\n (Excellent Damage Rate Increase +13%)"; }
					elseif ($socket[$i]==230)   { $socketoption.="<br>Socket {$i}:(Lighting)\n (+5)\n (Excellent Damage Rate Increase +14%)"; }	
					elseif ($socket[$i]==31)    { $socketoption.="<br>Socket {$i}:(Lighting)\n (+1)\n (Critical Damage Increase +30)"; }                        
					elseif ($socket[$i]==81)    { $socketoption.="<br>Socket {$i}:(Lighting)\n (+2)\n (Critical Damage Increase +32)"; }
					elseif ($socket[$i]==131)   { $socketoption.="<br>Socket {$i}:(Lighting)\n (+3)\n (Critical Damage Increase +35)"; }
					elseif ($socket[$i]==181)   { $socketoption.="<br>Socket {$i}:(Lighting)\n (+4)\n (Critical Damage Increase +40)"; }
					elseif ($socket[$i]==231)   { $socketoption.="<br>Socket {$i}:(Lighting)\n (+5)\n (Critical Damage Increase +50)"; }
					elseif ($socket[$i]==32)    { $socketoption.="<br>Socket {$i}:(Lighting)\n (+1)\n (Critical Damage Rate Increase +8%)"; }                        
					elseif ($socket[$i]==82)    { $socketoption.="<br>Socket {$i}:(Lighting)\n (+2)\n (Critical Damage Rate Increase +9%)"; }
					elseif ($socket[$i]==132)   { $socketoption.="<br>Socket {$i}:(Lighting)\n (+3)\n (Critical Damage Rate Increase +10%)"; }
					elseif ($socket[$i]==182)   { $socketoption.="<br>Socket {$i}:(Lighting)\n (+4)\n (Critical Damage Rate Increase +11%)"; }
					elseif ($socket[$i]==232)   { $socketoption.="<br>Socket {$i}:(Lighting)\n (+5)\n (Critical Damage Rate Increase +12%)"; }				
                    // Armor Only Sockets  -->
					elseif ($socket[$i]==10)  { $socketoption.="<br>Socket {$i}:(Water)\n (+1)\n (Block Rating Increasee +10%)"; }
					elseif ($socket[$i]==60)  { $socketoption.="<br>Socket {$i}:(Water)\n (+2)\n (Block Rating Increasee +11%)"; }
					elseif ($socket[$i]==110) { $socketoption.="<br>Socket {$i}:(Water)\n (+3)\n (Block Rating Increasee +12%)"; }
					elseif ($socket[$i]==160) { $socketoption.="<br>Socket {$i}:(Water)\n (+4)\n (Block Rating Increasee +13%)"; }
					elseif ($socket[$i]==210) { $socketoption.="<br>Socket {$i}:(Water)\n (+5)\n (Block Rating Increasee +14%)"; }
					elseif ($socket[$i]==11)  { $socketoption.="<br>Socket {$i}:(Water)\n (+1)\n (Defence Increasee +30)"; }
					elseif ($socket[$i]==61)  { $socketoption.="<br>Socket {$i}:(Water)\n (+2)\n (Defence Increasee +33)"; }
					elseif ($socket[$i]==111) { $socketoption.="<br>Socket {$i}:(Water)\n (+3)\n (Defence Increasee +36)"; }
					elseif ($socket[$i]==161) { $socketoption.="<br>Socket {$i}:(Water)\n (+4)\n (Defence Increasee +39)"; }
					elseif ($socket[$i]==211) { $socketoption.="<br>Socket {$i}:(Water)\n (+5)\n (Defence Increasee +42)"; }
					elseif ($socket[$i]==12)  { $socketoption.="<br>Socket {$i}:(Water)\n (+1)\n (Shield Protection Increasee +7%)"; }
					elseif ($socket[$i]==62)  { $socketoption.="<br>Socket {$i}:(Water)\n (+2)\n (Shield Protection Increasee +10%)"; }
					elseif ($socket[$i]==112) { $socketoption.="<br>Socket {$i}:(Water)\n (+3)\n (Shield Protection Increasee +15%)"; }
					elseif ($socket[$i]==162) { $socketoption.="<br>Socket {$i}:(Water)\n (+4)\n (Shield Protection Increasee +20%)"; }
					elseif ($socket[$i]==212) { $socketoption.="<br>Socket {$i}:(Water)\n (+5)\n (Shield Protection Increasee +30%)"; }
					elseif ($socket[$i]==13)  { $socketoption.="<br>Socket {$i}:(Water)\n (+1)\n (Damage Reduction +4%)"; }
					elseif ($socket[$i]==63)  { $socketoption.="<br>Socket {$i}:(Water)\n (+2)\n (Damage Reduction +5%)"; }
					elseif ($socket[$i]==113) { $socketoption.="<br>Socket {$i}:(Water)\n (+3)\n (Damage Reduction +6%)"; }
					elseif ($socket[$i]==163) { $socketoption.="<br>Socket {$i}:(Water)\n (+4)\n (Damage Reduction +7%)"; }
					elseif ($socket[$i]==213) { $socketoption.="<br>Socket {$i}:(Water)\n (+5)\n (Damage Reduction +8%)"; }
					elseif ($socket[$i]==14)  { $socketoption.="<br>Socket {$i}:(Water)\n (+1)\n (Damage Reflection +5%)"; }
					elseif ($socket[$i]==64)  { $socketoption.="<br>Socket {$i}:(Water)\n (+2)\n (Damage Reflection +6%)"; }
					elseif ($socket[$i]==114) { $socketoption.="<br>Socket {$i}:(Water)\n (+3)\n (Damage Reflection +7%)"; }
					elseif ($socket[$i]==164) { $socketoption.="<br>Socket {$i}:(Water)\n (+4)\n (Damage Reflection +8%)"; }
					elseif ($socket[$i]==214) { $socketoption.="<br>Socket {$i}:(Water)\n (+5)\n (Damage Reflection +9%)"; }
					elseif ($socket[$i]==21)  { $socketoption.="<br>Socket {$i}:(Wind)\n (+1)\n (Automatic Life Recovery Increase +8)"; }
					elseif ($socket[$i]==71)  { $socketoption.="<br>Socket {$i}:(Wind)\n (+2)\n (Automatic Life Recovery Increase +10)"; }
					elseif ($socket[$i]==121) { $socketoption.="<br>Socket {$i}:(Wind)\n (+3)\n (Automatic Life Recovery Increase +13)"; }
					elseif ($socket[$i]==171) { $socketoption.="<br>Socket {$i}:(Wind)\n (+4)\n (Automatic Life Recovery Increase +16)"; }
					elseif ($socket[$i]==221) { $socketoption.="<br>Socket {$i}:(Wind)\n (+5)\n (Automatic Life Recovery Increase +20)"; }
					elseif ($socket[$i]==22)  { $socketoption.="<br>Socket {$i}:(Wind)\n (+1)\n (Maximum Life Increase +4%)"; }
					elseif ($socket[$i]==72)  { $socketoption.="<br>Socket {$i}:(Wind)\n (+2)\n (Maximum Life Increase +5%)"; }
					elseif ($socket[$i]==122) { $socketoption.="<br>Socket {$i}:(Wind)\n (+3)\n (Maximum Life Increase +6%)"; }
					elseif ($socket[$i]==172) { $socketoption.="<br>Socket {$i}:(Wind)\n (+4)\n (Maximum Life Increase +7%)"; }
					elseif ($socket[$i]==222) { $socketoption.="<br>Socket {$i}:(Wind)\n (+5)\n (Maximum Life Increase +8%)"; }
					elseif ($socket[$i]==23)  { $socketoption.="<br>Socket {$i}:(Wind)\n (+1)\n (Maximum Mana Increase +4%)"; }
					elseif ($socket[$i]==73)  { $socketoption.="<br>Socket {$i}:(Wind)\n (+2)\n (Maximum Mana Increase +5%)"; }
					elseif ($socket[$i]==123) { $socketoption.="<br>Socket {$i}:(Wind)\n (+3)\n (Maximum Mana Increase +6%)"; }
					elseif ($socket[$i]==173) { $socketoption.="<br>Socket {$i}:(Wind)\n (+4)\n (Maximum Mana Increase +7%)"; }
					elseif ($socket[$i]==223) { $socketoption.="<br>Socket {$i}:(Wind)\n (+5)\n (Maximum Mana Increase +8%)"; }					
					elseif ($socket[$i]==24)  { $socketoption.="<br>Socket {$i}:(Wind)\n (+1)\n (Automatic Mana Recovery Increase +7)"; }
					elseif ($socket[$i]==74)  { $socketoption.="<br>Socket {$i}:(Wind)\n (+2)\n (Automatic Mana Recovery Increase +14)"; }
					elseif ($socket[$i]==124) { $socketoption.="<br>Socket {$i}:(Wind)\n (+3)\n (Automatic Mana Recovery Increase +21)"; }
					elseif ($socket[$i]==174) { $socketoption.="<br>Socket {$i}:(Wind)\n (+4)\n (Automatic Mana Recovery Increase +28)"; }
					elseif ($socket[$i]==224) { $socketoption.="<br>Socket {$i}:(Wind)\n (+5)\n (Automatic Mana Recovery Increase +35)"; }					
					elseif ($socket[$i]==25)  { $socketoption.="<br>Socket {$i}:(Wind)\n (+1)\n (Maximum AG Increase +50)"; }
					elseif ($socket[$i]==75)  { $socketoption.="<br>Socket {$i}:(Wind)\n (+2)\n (Maximum AG Increase +70)"; }
					elseif ($socket[$i]==125) { $socketoption.="<br>Socket {$i}:(Wind)\n (+3)\n (Maximum AG Increase +90)"; }
					elseif ($socket[$i]==175) { $socketoption.="<br>Socket {$i}:(Wind)\n (+4)\n (Maximum AG Increase +110)"; }
					elseif ($socket[$i]==225) { $socketoption.="<br>Socket {$i}:(Wind)\n (+5)\n (Maximum AG Increase +130)"; }						
					elseif ($socket[$i]==26)  { $socketoption.="<br>Socket {$i}:(Wind)\n (+1)\n (AG Value Increase +5)"; }
					elseif ($socket[$i]==76)  { $socketoption.="<br>Socket {$i}:(Wind)\n (+2)\n (AG Value Increase +7)"; }
					elseif ($socket[$i]==126) { $socketoption.="<br>Socket {$i}:(Wind)\n (+3)\n (AG Value Increase +9)"; }
					elseif ($socket[$i]==176) { $socketoption.="<br>Socket {$i}:(Wind)\n (+4)\n (AG Value Increase +11)"; }
					elseif ($socket[$i]==226) { $socketoption.="<br>Socket {$i}:(Wind)\n (+5)\n (AG Value Increase +13)"; }						
                    //Statistics Enhancement
					elseif ($socket[$i]==36)  { $socketoption.="<br>Socket {$i}:(Earth)\n (+1)\n (Health Increase +30)"; }
					elseif ($socket[$i]==76)  { $socketoption.="<br>Socket {$i}:(Earth)\n (+2)\n (Health Increase +32)"; }
					elseif ($socket[$i]==126) { $socketoption.="<br>Socket {$i}:(Earth)\n (+3)\n (Health Increase +34)"; }
					elseif ($socket[$i]==176) { $socketoption.="<br>Socket {$i}:(Earth)\n (+4)\n (Health Increase +36)"; }
					elseif ($socket[$i]==226) { $socketoption.="<br>Socket {$i}:(Earth)\n (+5)\n (Health Increase +38)"; }						
                    elseif ($socket[$i]==255) { $socketoption.=""; }
					else{$socketoption = false;}						
				}
					
				if($socketoption){	
					//Bonus Sockets IGCN / Season 12 <!-- Bonus options 0-5 are dedicated only for Seeds level 1-3, options 6-11 dedicated for Seed level 4 -->
					switch($bonus_socket)
					{
		                	case 0 : $show_bonus_socket = "<br><font color=#ffbfff>(Seed Lv1 - Lv3)Increases Attack +11</font>" ;                         break;
		                	case 1 : $show_bonus_socket = "<br><font color=#ffbfff>(Seed Lv1 - Lv3)Increase Skill Damage +11</font>" ;                    break;
		                	case 2 : $show_bonus_socket = "<br><font color=#ffbfff>(Seed Lv1 - Lv3)Increases Attack Power and Magical Damage +5</font>" ; break;
		                	case 3 : $show_bonus_socket = "<br><font color=#ffbfff>(Seed Lv1 - Lv3)Increase Skill Damage +11</font>" ;                    break;
		                	case 4 : $show_bonus_socket = "<br><font color=#ffbfff>(Seed Lv1 - Lv3)Increases Defense +24</font>" ;                        break;
		                	case 5 : $show_bonus_socket = "<br><font color=#ffbfff>(Seed Lv1 - Lv3)Increases MAX Life +29</font>" ;                       break;
		                	case 6 : $show_bonus_socket = "<br><font color=#ffbfff>(Seed Lv4) Increases Attack +22</font>" ;                              break;
		                	case 7 : $show_bonus_socket = "<br><font color=#ffbfff>(Seed Lv4) Increase Skill Damage +22</font>" ;                         break;
		                	case 8 : $show_bonus_socket = "<br><font color=#ffbfff>(Seed Lv4) Increases Attack Power and Magical Damage +10</font>" ;     break;
		                	case 9 : $show_bonus_socket = "<br><font color=#ffbfff>(Seed Lv4) Increase Skill Damage +22</font>" ;                         break;
		                	case 10: $show_bonus_socket = "<br><font color=#ffbfff>(Seed Lv4) Increases Defense +27</font>" ;                             break;
		                	case 11: $show_bonus_socket = "<br><font color=#ffbfff>(Seed Lv4) Increases MAX Life +31</font>" ;                            break;
		                	default: $show_bonus_socket = false;                                                                                          break;
		            }
					$socketoption	=	"<br><strong><u><font color=#FF19FF>Socket Option</font></u></strong><font color=#efbfff>".$socketoption. "</font>";
				}
					
		    }
		if($show_bonus_socket){
			
			$show_bonus_socket	=	"<br><strong><u><font color=#FF19FF>Bonus Socket Option</font></u></strong>".$show_bonus_socket;
		}			
		
        $output['srch_skill']  = $srch_skill;
		$output['refinery']    = $refine;
        $output['srch_luck']   = $srch_luck;
        $output['clearname']   = $invview['Name'];
        $output['name']        = $itemname;
		$output['id']          = $level1;
		$output['type']        = $level2;
        $output['opt']         = $itemoption;
		$output['opts']        = $itm;
        $output['exl']         = $rowinfo  . $itemexl;
		$output['hex']         = substr($_item,0,4);
        $output['luck']        = $luck;
        $output['skill']       = $skill;
        $output['x']           = $invview['x'];
        $output['y']           = $invview['y'];
        $output['color']       = $c;
        $output['thumb']       = itemimage($AA, $BB, $CC,$anc,$imgfix);
		$output['sockets']     = $socketoption;
        $output['level']       = $itemlevel;
		$output['full_hex']    = $_item;
		$output['ids']         = $ids;
		$output['item_type']   = $rows['exeopt']; 
        $output['harmony']     = @$harmony_opt[$rows['harmony']][$harmonyoption]['name'];
        $output['harmony_lvl'] = @$harmony_opt[$rows['harmony']][$harmonyoption]['opt'][$harmonyvalue];


		if(isset($_SESSION['dt_username'])){
		   if(check_admin($_SESSION['dt_username'])){
				   	$iteminfoadmin         = '<div style=color:#bdbdae;>Type: [<span style=\'font-weight:300;text-shadow:1px 1px #000;color:#FFF\'>'.$level1.'</span>] ID: [<span style=\'font-weight:300;text-shadow:1px 1px #000;color:#FFF\'>'.$level2.'</span>] Serial: [<span style=\'font-weight:300;text-shadow:1px 1px #000;color:#FFF\'>'.$serial.'</span>]</br>Item Hex '.$season_settings[0].'</br></div>';			  
		   }
		}
		if($output['harmony_lvl'] > 0){
			$harmony_info = "</br>".$output['harmony'].$output['harmony_lvl'];
		}
	
        $itemformat = '
		<div align=center style=\'font-size:5pt\'>
		<p style=\'font-weight:bold;font-size: 13px;\'>[Name] </p>
		[GM_Info]
		[Item_Info] 
		[Durability]		
		[ItemReq]
		[ItemFor]
		[Skill] 
		[Luck] 
		[Options]
		[Sphere]
		[Excellent]		 	
		[Harmony]
		[Sockets]	
        [Bonus Socket]	
		[Refinery]			
		</div>';
     
        if ($output['level'] && $special === false) { $plusche = '+' . $output['level'];}

		$overlib = str_replace('[Name]', '<span style=font-weight:900;color:' . $output['color'] . '>'.$output['name'] . ' ' . $plusche . ' </span>', addslashes($itemformat));
		
		if ($output['opt']) {$options = '<br><font color=#8CB0EA>' . $output['opt'] . '</font>';}
		
        $overlib           = @str_replace('[Bonus Socket]',$show_bonus_socket, $overlib);
	    $overlib           = @str_replace('[Item_Info]',$addinfo, $overlib);
		$overlib	       = @str_replace('[Luck]','<font style=color:#8CB0EA>'.$output['luck'].'</font>', $overlib);
		$overlib	       = @str_replace('[Skill]','<font style=color:#8CB0EA>'.$output['skill'].'</font>', $overlib);		
	    $overlib           = @str_replace('[GM_Info]',$iteminfoadmin, $overlib); 	
        $overlib           = @str_replace('[Options]',$options, $overlib); 	
		$overlib           = @str_replace('[Sphere]',$seedopt, $overlib); 
        $overlib           = @str_replace('[ItemReq]','<font style=color:#ff2626>'.$item_req.'</font>', $overlib);
        $overlib           = @str_replace('[ItemFor]','<font style=color:#bdbdae></br>'.$item_for_fix.'</font>', $overlib);				
		$overlib	       = @str_replace('[Sockets]','<font style=color:#FF19FF>'.$socketoption.'</font>', $overlib);
        $overlib	       = @str_replace('[Refinery]','<font style=color:#ffbf00>'.$output['refinery'].'</font>', $overlib);
		$overlib	       = @str_replace('[Harmony]','<font style=color:#ffc926>'.$harmony_info.'</font>', $overlib);
		$overlib	       = @str_replace('[Excellent]','<font style=color:#8CB0EA>'.$output['exl'].'</font>', $overlib);
		$overlib           = @str_replace('[Durability]',$output['dur'],$overlib);
		$output['overlib'] = $overlib;

        return $output;	
    }

function smartsearch($warehouse, $_x, $_y) {
        $slots = str_repeat(0, 120);   //Creating 120 slots of 0's
        for ($i = 0; $i < 120; $i++) { //Setting all taken slots to 1
            $hex = substr($warehouse, $i * 20, 20);
            if (strtoupper($hex) != str_repeat('F', 20)) {
                $decode = Iteminfouser($hex);
                $slots = substr_replace($slots, str_repeat(1, $decode['x']), $i, $decode['x']);
                if ($decode['y'] > 1) {
                    for ($r = 0; $r < $decode['y'] - 1; $r++) {
                        $slots = substr_replace($slots, str_repeat(1, $decode['x']), $i + 8 + $r * 8, $decode['x']);
                    }
                }
            }
        }
 
        for ($i = 0; $i < 120; $i++) { //Going through $slots and checking if there are any empty slots that matches our item
            if ((ceil(($i + 1) / 8) * 8) >= ($i + $_x) && substr($slots, $i, $_x) === str_repeat(0, $_x)) { //First empty row found
                if ($_y > 1) { //If the item is bigger than 1 slot vertically (Y)
                    if ((ceil(($i + 1) / 8) + $_y - 1) <= 15) {
                        $row = 0;
                        for ($r = 0; $r < $_y - 1; $r++) {
                            if (substr($slots, $i + 8 + $r * 8, $_x) === str_repeat(0, $_x)) {
                                $row++;
                            }
                        }
 
                        if ($row == $_y - 1) {
                            return $i;
                        }
                    }
                } else {
                    return $i;
                }
            }
        }
        return 1337;
    }
/*
function smartsearch($whbin,$itemX,$itemY) {
		if(season()){
			$item_config = season();
		}
		else{
			return 1337;
		}

	if (substr($whbin,0,2)=='0x') $whbin=substr($whbin,2);	
	$items 	= str_repeat('0', $item_config[3]);
	$itemsm = str_repeat('1', $item_config[3]);
	$i	= 0; 
	while ($i<$item_config[3]) {
		$_item	= substr($whbin,($item_config[0]*$i), $item_config[0]);
		$check_ref = hexdec(substr($_item, 19,1))/16;	
		$check_wid = hexdec(substr($_item, 14,2));		
			if($check_ref == 0.5)
				$type	= floor(hexdec(substr($_item,18,2))/16);
			else
				$type	= round(hexdec(substr($_item,18,2))/16);

			if ($check_wid <= '127')     { $last_try = hexdec(substr($_item,0,2)); $check_two = $last_try; }
			elseif ($check_wid >= '128') { $last_try = hexdec(substr($_item,0,2)); $check_two = $last_try +'256'; }

		if($item_config[0] === 20) {
			$type = hexdec(substr($_item, 0,2))/32; 
			$exc = hexdec(substr($_item, 14,2));
			$ids = hexdec(substr($_item, 0,2));	$idss = $ids / 32; $syfd = $ids - (floor($idss) * 32); 
			if($exc >= 128) { $type = $type + 8; }; $type = round($type); $check_two = $syfd;
		};
				
		$res = mssql_fetch_array(mssql_query("select [x],[y] from [DTweb_AllSeasons_Items] where [id]='".$check_two."' and [type]='" .$type ."'"));
		$y	 = 0;
        while ($y < $res['y']) {
			$y++;$x=0;
			while($x<$res['x']) {
				$items	= substr_replace($items, '1', ($i+$x)+(($y-1)*8), 1);
				$x++;	
			} 
		}	
		$i++;
	}
				
	$y	= 0;
	while($y<$itemY) {
		$y++;$x=0;
		while($x<$itemX) {
			$x++;
			$spacerq[$x+(8*($y-1))] = true;
		} 
	}
	$walked	= 0;
	$i	= 0;
	while($i<$item_config[3]) {
		if (isset($spacerq[$i])) {
			$itemsm	= substr_replace($itemsm, '0', $i-1, 1);
			$last	= $i;
			$walked++;
		}
		if ($walked==count($spacerq)) $i=$item_config[3];
		$i++;
	}
	$useforlength	= substr($itemsm,0,$last);
	$findslotlikethis= '/'.str_replace('++','+',str_replace('1','+[0-1]+', $useforlength)) . '/i';
	//$findslotlikethis= str_replace('++','+',str_replace('1','+[0-1]+', $useforlength));
	$i=0;$nx=0;$ny=0;
	while ($i < $item_config[3]) {	
		if ($nx==8) { $ny++; $nx=0; }
		   if ((preg_match($findslotlikethis,substr($items, $i, strlen($useforlength)))) && ($itemX+$nx<9) && ($itemY+$ny<16)){
		   	return $i;			
		   }
		   $i++;
		   $nx++;	   
	}
	return 1337;
}
*/

function jw_deposit(){	
$jewels_name = "";
$jewels_column ="";
$jewels_hex = "";
$jewel_count = 0;
$k=0;
$jewel_full_hex  = "";
$jewels_bas = mssql_query("Select * from [DTweb_Deposit_Settings] where [active]='1'");
  $warehouse = market_w($_SESSION['dt_username']);
        for( $i= 0 ; $i < mssql_num_rows($jewels_bas); $i++ ){
			while($jewels_base = mssql_fetch_array($jewels_bas)){
				if(isset($_POST["type"])){
                    if ($_POST["type"] == $jewels_base['ItemFour']){
          	              $jewels_name    = $jewels_base['ItemName'];
          	              $jewels_column  = $jewels_base['ItemColumn'];
          	              $jewels_hex     = $jewels_base['ItemFour'];
						  $jewel_full_hex = $jewels_base['ItemHex'];
                    }	
                }	
		    }
		}
		foreach($warehouse as $item){
		  $k++;
		  $items = substr($item,0,4);
	        if($items == $jewels_hex){
		      $jewel_count++;
	        }			
	    }
     	
	return array($jewels_hex,$jewels_name,$jewels_column,$jewel_count,$jewel_full_hex);
}

function market_w($account)
{
	if(season()){
	$item_conf = season();
	$items	   = all_items($account);
    $output    = str_split($items,$item_conf[0]);
    return $output;
	}
	else{
		return false;
	}
}


function market_warehouse($account)
{
	if(season()){
	$output = array();	
	$item_conf = season();
	$items	= all_items($account);
	$item_position	= -1;
	while($item_position < 119)
	{
		$item_position++;
		$item 	= ItemInfouser(substr($items,($item_conf[0] * $item_position), $item_conf[0]));
		if ($item)
		{
			$item['item_position'] = $item_position;
			$output[] = $item; 		
		}		
	}
	return $output;
	}
    else {
    	return false;
    }	
}

function getSerial() {
    return substr(md5(uniqid(mt_rand(), true)), 0, 8);
}


function decode_class($value){
      $class = array( 0 => "Dark Wizard", 1 => "Soul Master", 2 => "Grand Master", 3 => "Grand Master", 16 => "Dark Knight", 17 => "Blade Knight", 18 => "Blade Master", 19 => "Blade Master", 32 => "Fairy Elf", 33 => "Muse Elf", 34 => "High Elf", 35 => "High Elf", 48 => "Magic Gladiator", 50 => "Duel Master", 64 => "Dark Lord", 66 => "Lord Emperor", 80 => "Summoner", 81 => "Bloody Summoner", 82 => "Dimension Master", 83 => "Dimension Master" );
      return isset( $class[$value] ) ? $class[$value] : "Unknown";   
    }

function equipment($char){	
    if(!season()){
		return false;
	}
      $ver       = season();
	  $style2    = "";
      $char      = stripslashes($char);
      $char      = str_replace(";","",$char);
      $char      = str_replace("'","",$char);
      $inventory = mssql_fetch_array(mssql_query("SELECT * FROM Character WHERE Name='".$char."'"));
      $items     = all_items($char,"Character","Name","Inventory");

      if($inventory['Class'] >= 48 && $inventory['Class'] < 64) { $invimage = 'imgs/inventorymg.jpg';}
      else { $invimage = 'imgs/inventory.jpg';}

	  $output = " <div style = 'border:none;width:330px; height:750px; background:url(".$invimage.")'>";
	  
	      $output .= "<div style='position: absolute;border:none;height:200px;padding-top:80px;'>";
              //Imp
      {
      $item = substr($items,8*$ver[0],$ver[0]);
	       
      if(!iteminfouser($item)) { 
	       $output .= "";}
      else {
	 	 $item   = iteminfouser($item);
	     $output .= "<div style='position: absolute;margin-left:40px;margin-top:30px;'><img style= '' class=\"someClass\" title=\"<br>".$item['overlib']."\" src=".$item['thumb']."></div>"; }      } 
	  
             //Helm 
      {
      $item = substr($items,2*$ver[0],$ver[0]);
      if(!iteminfouser($item)) { $output .= ""; }
      else { 
	  $item   = iteminfouser($item);	
	  $output .= "<div style='position: absolute;margin-left:128px;'><img  style= '' class=\"someClass\" title=\"<br>".$item['overlib']."\" src=".$item['thumb']."></div>"; }
      }
	        //Wings
      {
      $item = substr($items,7*$ver[0],$ver[0]);
      if(!iteminfouser($item)) { $output .= ""; }
      else {
		   $item    = iteminfouser($item);	   
	       $output .= "<div style='position: absolute;margin-left:200px;'><img  style= 'max-width:120px' class=\"someClass\" title=\"<br>".$item['overlib']."\" src=".$item['thumb']."></div>"; }
      }
      $output .= "</div>";
	 
	 $output .= "<div style='position: absolute;height:200px;margin-top:160px;margin-left:10px'>";
	 
            //Left Hand
      {
      
      $item = substr($items,0*$ver[0],$ver[0]);
      if(!iteminfouser($item)) { $output .= ""; }
      else { $item   = iteminfouser($item); 
	  $output .= "<div style=' position: absolute;padding-left:20px;'><img style= 'max-width:80px' class=\"someClass\" title=\"<br>".$item['overlib']."\" src=".$item['thumb']."></div>"; }
      }	
           //Pendant
      {
      $item = substr($items,9*$ver[0],$ver[0]);
      if(!iteminfouser($item)) { $output .= ""; }
      else { $item   = iteminfouser($item); 
	  $output .= "<div style='position:absolute;margin-left:85px;'><img  style= '' class=\"someClass\" title=\"<br>".$item['overlib']."\" src=".$item['thumb']."></div>"; }
      }
          //Armor
      {
      $item = substr($items,3*$ver[0],$ver[0]);
      if(!iteminfouser($item)) { $output .= ""; }
      else { $item   = iteminfouser($item); 
	  $output .= "<div style='position:absolute;margin-left:122px;margin-top:10px'><img  style= 'max-width:80px' class=\"someClass\" title=\"<br>".$item['overlib']."\" src=".$item['thumb']."></div>"; }
      }      
          //Shield
      {
      $item = substr($items,1*$ver[0],$ver[0]);
      if(!iteminfouser($item)) { $output .= ""; }
      else { $item   = iteminfouser($item);

	  $output .= "<div style='position:absolute;margin-left:230px'><img style= 'max-width:80px' class=\"someClass\" title=\"<br>".$item['overlib']."\" src=".$item['thumb']."></div>"; }
      }
     $output .="</div>";

	 $output .= "<div style='width:300px; position: absolute;height:200px;margin-top:260px;'>";
          //Gloves
      {
      $item = substr($items,5*$ver[0],$ver[0]);
      if(!iteminfouser($item)) { $output .= ""; }
      else { $item   = iteminfouser($item); 
        $output .= "<div style='position:absolute;margin-left:25px;'><img style= '' class=\"someClass\" title=\"<br>".$item['overlib']."\" src=".$item['thumb']."></div>"; }
      }

          //Left Ring
      {
      $item = substr($items,10*$ver[0],$ver[0]);
      if(!iteminfouser($item)) { $output .= ""; }
      else { $item   = iteminfouser($item); 
        $output .= "<div style='position:absolute;margin-left:98px;margin-top:5px;'><img style= '' class=\"someClass\" title=\"<br>".$item['overlib']."\" src=".$item['thumb']."></div>"; }
      }	  
         //Pants
      {
      $item = substr($items,4*$ver[0],$ver[0]);
      if(!iteminfouser($item)) { $output .= ""; }
      else { $item   = iteminfouser($item); 
        $output .= "<div style='position:absolute;margin-left:132px;'><img style= '' class=\"someClass\" title=\"<br>".$item['overlib']."\" src=".$item['thumb']."></div>"; }
      }
         //Right Ring
      {
      $item = substr($items,11*$ver[0],$ver[0]);
      if(!iteminfouser($item)) { $output .= ""; }
      else { $item   = iteminfouser($item); 
        $output .= "<div style='position:absolute;margin-left:205px;margin-top:5px;'><img style= '' class=\"someClass\" title=\"<br>".$item['overlib']."\" src=".$item['thumb']."></div>"; }
      }      
         //Boots
      {
      $item = substr($items,6*$ver[0],$ver[0]);
      if(!iteminfouser($item)) { $output .= ""; }
      else { $item   = iteminfouser($item); 
        $output .= "<div style='position:absolute;margin-left:243px;'><img style= '' class=\"someClass\" title=\"<br>".$item['overlib']."\" src=".$item['thumb']."></div>"; }
      }	 
    $itemsa = substr($items, 384);
  	$output .= '<table style="margin-top:83px;margin-left:23px;border:none;">'; 
    $check = '011111111';
    $xx = 0;
    $yy = 1;
    $line = 1;
    $onn = 0;
    $i = -1;
    while ($i < 63) {
	$i++;
	if ($xx == 8) {
	    $xx = 1;
	    $yy++;
	}
	else
	$xx++;
$key = "";

	$TT = substr($check, $xx, 1);
	if ((round($i / 8) == $i / 8) && ($i != 0)) {
	$output .= "<td  width=\"35\" height=\"35\" align=center><b></b></td></tr><tr>";
	$line++;
	}
	$l = $i;
	$item2 = substr($itemsa, ($ver[0]* $i), $ver[0]);
	$item = ItemInfoUser(substr($itemsa, ($ver[0] * $i), $ver[0]));

	if (!$item['y'])
	    $InsPosY = 1;
	else
	    $InsPosY=$item['y'];

	if (!$item['x'])
	    $InsPosX = 1;
	else {
		
	    $InsPosX = $item['x'];
	    $xxx = $xx;
	    $InsPosXX = $InsPosX;
	    $InsPosYY = $InsPosY;
	    while ($InsPosXX > 0) {
		$check = substr_replace($check, $InsPosYY, $xxx, 1);
		$InsPosXX = $InsPosXX - 1;
		$InsPosYY = $InsPosY + 1;
		$xxx++;
	    }
	} 
	$item['name'] = addslashes($item['name']);
	if ($TT > 1)

	    $check = substr_replace($check, $TT - 1, $xx, 1);
	else {
	    unset($plusche, $rqs, $luck, $skill, $option, $exl);
	    if ($item['name']) {
		for($k = 1; $k < 5;++$k){
			$key .= $k;		
		}
            $output .= "<td  style='border:none;' align=\"center\" colspan='" . $InsPosX . "' rowspan='" . $InsPosY . "' style='background:rgba(0,0,0,0.3);width:" . (35 * $InsPosX) . "px;height:" . (35 * $InsPosY - $InsPosY - 1) . "px;' > ";
	        $output .= "<a class=\"someClass\" title=\"<br>".$item['overlib']."\"  href=\"javascript:void(0)\" onclick=\"fireMyPopup{$i}()\" style='width:" . (35 * $InsPosX) . "px;height:" . (35 * $InsPosY - $InsPosY - 1) . "px;'><img style='width:" . (35 * $InsPosX) . "px;height:" . (35 * $InsPosY - $InsPosY - 1) . "px;' src='" . $item['thumb'] . "' class='m' /></td>";
	    }
		
		else {
			$output .= "<td colspan='0' rowspan='1' style='width:30px;height:30px;border:0px;margin:0px;padding:0px;'><div style='height: 35px;width: 35px;'></div></td>";

		}
	  }
	}
	
        $output .="</div>
           
		   <table style='border:none;width:193px;position:absolute;top:0;margin-top:368px;border:none;margin-left:88px;text-shadow:1px 1px #000000;font-size:11pt;font-weight:900;color:#e07525'><tr><tD style='text-align:right;padding:2px 2px;'>".number_format($inventory['Money'])."</td></tr></table>
         <div style='top:0;position:absolute;margin-left:25px;margin-top:420px;'><a href='?p=accountedit&account='".$inventory['AccountID']."'><img onmouseover=\"this.src='imgs/w_button.jpg';\" onmouseout=\"this.src='imgs/w_buttona.jpg';\" src='imgs/w_buttona.jpg'/></a>
		 </div>
		 
	  </div>";
    return $output;
   }
}

?>