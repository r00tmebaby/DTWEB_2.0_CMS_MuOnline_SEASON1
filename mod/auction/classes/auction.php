<?php
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {header("Location:../error.php");}else{
class Auction {

    /**
     *
     * @var Auction
     */
    public static $inst = null;
    private $time;
    private $auction_id;

    private function __construct() {
        $db = Database::$inst;
        $checkCurrentAuction = $db->q("Select TOP 1 * From [DTWeb_Auctions] order by [id] desc", 2);
        $this->time = $checkCurrentAuction['time'];
        $this->auction_id = $checkCurrentAuction['id'];
        $this->checkAuction($checkCurrentAuction['time'], $checkCurrentAuction['id']);
    }

    public function checkAuction($time, $id) {
        if ($time <= time()) {
            if (empty($id)) {
                $this->startNewAuction();
            } else {
                $this->endAuction();
                $this->startNewAuction();
            }
        }
    }

    public function startNewAuction() {
        $items_arr = $this->randomItem();
        $itemhex = $this->generateItem($items_arr[1], $items_arr[0]);
        Database::$inst->q("INSERT INTO [DTWeb_Auctions] ([item], [time],[start_time]) VALUES ('{$itemhex}', " . (time() + Config::$inst->getAuctionConfig("time")) . ",".time().")");
    }
    public function ip() {
        $ipaddress = '0.0.0.0';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    public function randomItem() {
        $allitems = Database::$inst->q("Select [id], [type] From [DTweb_JewelDeposit_Items] Where [auction]=1");
        $items_arr = array();
        while ($r = Database::$inst->fa($allitems)) {
            $items_arr[] = array($r['type'], $r['id']);
        }

        shuffle($items_arr);

        return $items_arr[0];
    }

    public function endAuction() {
		
		$check_opt = mssql_fetch_array(mssql_query("Select * from DTweb_Auction_Settings"));
		$jd = json_decode($check_opt['resources']);
		$exclude = array("0");	
		$orig = array_diff($jd,$exclude);
	    mssql_query("Update [DTweb_Auction_Settings] set [column] = '".$orig[array_rand($orig)]."'");	
	
        $db = Database::$inst;
        $winner = $db->q("Select [DTweb_Auction_Bets].[user] From [DTweb_Auction_Bets], [DTWeb_Auctions]
                Where [auction_id]={$this->auction_id} and [DTWeb_Auctions].[id]={$this->auction_id} and [DTWeb_Auctions].[winner]=NULL
                order by [count] desc", 2);
        if ($winner['user']) {
            $item = $db->q("Select [item] From [DTWeb_Auctions] Where [id]={$this->auction_id}", 2);
            $iinfo = $this->itemInfo($item['item']);
            $this->insertItem($item['item'], $iinfo['X'], $iinfo['Y'], $winner['user']);
            $db->q("UPDATE [DTWeb_Auctions] Set [winner]='{$winner['user']}',ip='".$this->ip()."',end_time='".time()."' Where [id]={$this->auction_id}");
            $not_winner = $db->q("Select * from [DTweb_Auction_Bets] where [auction_id]={$this->auction_id} and [user] !='{$winner['user']}'");
			if($check_opt['return_res'] == 1){
			while($upd = mssql_fetch_array($not_winner)){
				$db->q("Update DTweb_JewelDeposit set [".$upd['resource']."] = [".$upd['resource']."] + ".$upd['count']." where [memb___id]='{$upd['user']}'");
			}
		  }
		}    
    }

    public function whbin($account,$lenght =1200,$table = "warehouse",$user_col="AccountId",$item_col="items") {
            if(phpversion() >= "5.3"){
        		$itemsa = mssql_fetch_array(mssql_query("SELECT [".$item_col."] FROM [".$table."] WHERE [".$user_col."]='".$account."'"));
        		  return strtoupper(bin2hex($itemsa[$item_col]));
        	}
        	else{
        		  return substr(mssql_get_last_message(mssql_query("declare @items varbinary(".$lenght."); set @items=(select [".$item_col."] from [".$table."] where [".$user_col."]='".$account."'); print @items;")),2);	        	
        	}
        }

    public function generateItem($id, $type) {
		
        $query = "Select * From [DTweb_JewelDeposit_Items] Where [id]={$id} and [type]={$type}";
        $i_info = mssql_fetch_array(mssql_query($query));

        //option create
        if ($i_info['exeopt'] == 4) {
            $excopt = Config::$inst->getAuctionConfig("excopt_wings");
            $level = Config::$inst->getAuctionConfig("level_wings");
        } else {
            if ($type < 7) {
                $excopt = Config::$inst->getAuctionConfig("excopt_wep");
            } else {
                $excopt = Config::$inst->getAuctionConfig("excopt_gear");
            }
            $level = Config::$inst->getAuctionConfig("level");
        }

        $BB += $level * 8;

        if (Config::$inst->getAuctionConfig("option") >= 4) {
            $BB += Config::$inst->getAuctionConfig("option") - 4;
            $HH += 64;
        } else {
            $BB += Config::$inst->getAuctionConfig("option");
        }

        if ($i_info['skill'] == 1 && Config::$inst->getAuctionConfig("skill") == 1) {
            $BB += 128;
        }

        if ($i_info['luck'] == 1 && Config::$inst->getAuctionConfig("luck") == 0) {
            $BB += 4;
        }

        if (Config::$inst->getAuctionConfig("randomoptions") != 0 && Config::$inst->getAuctionConfig("randomoptions") <= count($excopt)) {
            shuffle($excopt);
            for ($oid = 0; $oid < Config::$inst->getAuctionConfig("randomoptions"); $oid++) {
                $HH += $excopt[$oid];
            }
        } 
		elseif (Config::$inst->getAuctionConfig("randomoptions") == 0) {
            shuffle($excopt);
            for ($oid = 0; $oid < Config::$inst->getAuctionConfig("randomoptions"); $oid++) {
                $HH += 0;
            }
        }
		else {
            foreach ($excopt as $value) {
                $HH += $value;
            }
        }

        //end of option create

        $first = $type * 2;
        $second = $id;

        if ($first >= 16) {
            $HH += 128;
            $first -= 16;
        }

        if ($second > 15) {
            $first += 1;
            $second -= 16;
        }

        $names = ($type * 32) + $id;
        $AA = sprintf("%02X", $names, 00);

        if ($names >= 255) {
            $AA = substr($AA, 1, 2);
        } else {
            $AA = sprintf("%02X", $names, 00);
        }

        $BB = sprintf("%02X", $BB, 00);
        $CC = sprintf("%02X", 150, 00);
        $DDEE = sprintf("%08X", $this->getSerial(), 00000000);
        $GG = sprintf("%02X", $HH, 00);
        $ZZ = sprintf("%02X", 0, 0);
        $FF = sprintf("%02X", 0, 00);
        //($type * 16)

        $itemhex = $AA . $BB . $CC . $DDEE . $GG . $ZZ . $FF;
        return $itemhex;
    }
	
public function getSerial() {
    return substr(md5(uniqid(mt_rand(), true)), 0, 8);
}


    public function insertItem($itemHex, $x, $y, $user) {
        $mycuritems = $this->whbin($user);
        $slot = $this->smartsearch($mycuritems, $x, $y);
		$items = $this->itemInfos($itemHex);
        $mynewitems = substr_replace($mycuritems, $itemHex, (($slot * 20) + 2), 20);
		if($slot == 1337){
		mssql_query("Insert into DTweb_Storage ([item_type],[item_id], [item],[seller],[start_date],[seller_ip],[level],[skill],[luck],[options],[excellent],[name]) VALUES('".$items['type']."','".$items['id']."', '".$itemHex."','".$user."','".time()."','".$this->ip()."','".$items['level']."','".$items['skill']."','".$items['luck']."','".$items['opt']."','from auction','".$items['name']."')");	
		}
		else{
        mssql_query("update [warehouse] set [Items]={$mynewitems} where [AccountId]='{$user}'");
		}
    }

    public function itemimage($level, $level2, $level3) {
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
        $xx = "00";

        $images = "{$level1}{$xx}{$level2}.gif";
        return $images;
    }
    public function itemInfos($_item) {
        if (substr($_item, 0, 2) == '0x') {
            $_item = substr($_item, 2);
        }

        if ((strlen($_item) != 20) || (!preg_match("/(^[a-zA-Z0-9])/", $_item)) || ($_item == 'FFFFFFFFFFFFFFFFFFFF')) {
            return false;
        }

        // Get the hex contents
        $id = hexdec(substr($_item, 0, 2)); // Item ID
        $lvl = hexdec(substr($_item, 2, 2)); //Level,Option,Skill,Luck
        $itemdur = hexdec(substr($_item, 4, 2)); // Item Durability
        $ex = hexdec(substr($_item, 14, 2)); // Item Excellent Info/ Option

        if ($lvl < 128) {
            $skill = '';
        } else {
            $skill = 'This weapon has a special skill';
            $lvl = $lvl - 128;
        }

        $itemlevel = floor($lvl / 8);
        $lvl = $lvl - $itemlevel * 8;

        if ($lvl < 4) {
            $luck = '';
        } else {
            $luck = "Luck (success rate of jewel of soul +25%)<br>Luck (critical damage rate +5%)";
            $lvl = $lvl - 4;
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

        $level = substr($_item, 0, 1);
        $level2 = substr($_item, 1, 1);
        $level3 = substr($_item, 14, 2);
        $AA = $level;
        $BB = $level2;
        $CC = $level3;
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

        $invview = mssql_query("SELECT * FROM [DTweb_JewelDeposit_Items] WHERE [id]={$level2} AND [type]={$level1}");
        $rows = mssql_fetch_array($invview);

        $iopxltype = $rows['exeopt'];
        $itemname = $rows['name'];
        $itemexl = "";
        switch ($iopxltype) {
            case 0 :
                $op1 = 'Increase Mana per kill +8';
                $op2 = 'Increase hit points per kill +8';
                $op3 = 'Increase attacking(wizardly) speed +7';
                $op4 = 'Increase damage +2%';
                $op5 = 'Increase Damage +level/20';
                $op6 = 'Excellent Damage Rate +10%';
                $inf = 'Additional Damage';
                break;
            case 1:
                $op1 = 'Increase Zen After Hunt +40%';
                $op2 = 'Defense success rate +10%';
                $op3 = 'Reflect damage +5%';
                $op4 = 'Damage Decrease +4%';
                $op5 = 'Increase MaxMana +4%';
                $op6 = 'Increase MaxHP +4%';
                $inf = 'Additional Defense';
                $skill = '';
                $nocolor = false;
                break;
            case 4:
                $op1 = ' Life +' . (50 + ($itemlevel * 5)) . ' Increased';
                $op2 = ' Mana +' . (50 + ($itemlevel * 5)) . ' Increased';
                $op3 = ' 10% Mana loss instead of Life';
                $op4 = ' +50 of damage transfered as Life';
                $op5 = ' Increase Attacking(wizardry)speed +5';
                $op6 = '';
                $inf = 'Additional Damage';
                $skill = '';
                $nocolor = true;
        }

        if ($exc1 == 1) {
            $itemexl .= '<br>' . $op1;
        }
        if ($exc2 == 1) {
            $itemexl .= '<br>' . $op2;
        }
        if ($exc3 == 1) {
            $itemexl .= '<br>' . $op3;
        }
        if ($exc4 == 1) {
            $itemexl .= '<br>' . $op4;
        }
        if ($exc5 == 1) {
            $itemexl .= '<br>' . $op5;
        }
        if ($exc6 == 1) {
            $itemexl .= '<br>' . $op6;
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
            $c = '#2FF387';
        } // Green -> Excellent Item 
        if ($nocolor) {
            $c = '#F4CB3F';
        }
        if ($itemoption == 0) {
            $itemoption = '';
        } else {
            $itemoption = $inf . " +" . $itemoption;
        }
        if (($itemexl != '') && ($itemname) && (!$nocolor)) {
            $itemname = 'Excellent ' . $itemname;
        }

        if ($nolevel == 1) {
            $ilvl = 0;
        } else {
            $ilvl = $itemlevel;
        }

        $output['clearname'] = $rows['name'];
        $output['name'] = $itemname;
		$output['id']   = $level1;
		$output['type'] = $level2;
        $output['level'] = $ilvl;
        $output['opt'] = $itemoption;
        $output['exl'] = $itemexl;
        $output['luck'] = $luck;
        $output['skill'] = $skill;
        $output['dur'] = $itemdur;
        $output['X'] = $rows['x'];
        $output['Y'] = $rows['y'];
        $output['color'] = $c;
        $output['thumb'] = $this->itemimage($AA, $BB, $CC);

        $itemformat = '<div align=center style=\'background:#FF4000;cursor:pointer;border:2px solid #FF9326;margin:10px 10px;border-radius:5px 5px 5px 5px;padding-left: 6px; padding-right:6px;font-family:arial;font-size: 8px;\'><span style=\'font-weight:bold;font-size: 8px;\'>[Name]</span><br><br>[Image]<br>Durability<br>[Skill] [Luck] [Excellent]</span></div>';

        if ($output['level']) {
            $plusche = '+' . $output['level'];
        }

        $overlib = str_replace('[Name]', '<span style=color:' . $output['color'] . '>' . $output['name'] . ' ' . $plusche . '</span>', addslashes($itemformat));
        $overlib = str_replace('Durability','<font color=white>'. $output['dur'] . ' durability </font>', $overlib);

        if ($output['opt']) {
            $option = '<br><font color=#9aadd5>' . $output['opt'] . '</font>';
        }

        if ($output['luck']) {
            $luck = '<br><font color=#9aadd5>' . $output['luck'] . '';
        }
        $overlib = str_replace('[Luck]', $luck . $option . '</font>', $overlib);

        if ($output['skill']) {
            $skill = '<br><font color=#9aadd5>' . $output['skill'] . '</font>';
        }
        $overlib = str_replace('[Skill]', $skill, $overlib);

        if ($output['exl']) {
            $exl = '<font color=#8CB0EA>' . str_replace('^^', '<br>', $output['exl']);
        }

        $overlib = str_replace('[Excellent]', $exl, $overlib);
        $overlib = str_replace('[Image]', "<img src='template/items/" . $output['thumb'] . "' />", $overlib);

        $output['overlib'] = $overlib;

        return $output;
    }
    public function itemInfo($_item) {
        if (substr($_item, 0, 2) == '0x') {
            $_item = substr($_item, 2);
        }

        if ((strlen($_item) != 20) || (!preg_match("/(^[a-zA-Z0-9])/", $_item)) || ($_item == 'FFFFFFFFFFFFFFFFFFFF')) {
            return false;
        }

        // Get the hex contents
        $id = hexdec(substr($_item, 0, 2)); // Item ID
        $lvl = hexdec(substr($_item, 2, 2)); //Level,Option,Skill,Luck
        $itemdur = hexdec(substr($_item, 4, 2)); // Item Durability
        $ex = hexdec(substr($_item, 14, 2)); // Item Excellent Info/ Option

        if ($lvl < 128) {
            $skill = '';
        } else {
            $skill = 'This weapon has a special skill';
            $lvl = $lvl - 128;
        }

        $itemlevel = floor($lvl / 8);
        $lvl = $lvl - $itemlevel * 8;

        if ($lvl < 4) {
            $luck = '';
        } else {
            $luck = "Luck (success rate of jewel of soul +25%)<br>Luck (critical damage rate +5%)";
            $lvl = $lvl - 4;
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

        $level = substr($_item, 0, 1);
        $level2 = substr($_item, 1, 1);
        $level3 = substr($_item, 14, 2);
        $AA = $level;
        $BB = $level2;
        $CC = $level3;
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

        $invview = mssql_query("SELECT * FROM [DTweb_JewelDeposit_Items] WHERE [id]={$level2} AND [type]={$level1}");
        $rows = mssql_fetch_array($invview);

        $iopxltype = $rows['exeopt'];
        $itemname = $rows['name'];
        $itemexl = "";
        switch ($iopxltype) {
            case 0 :
                $op1 = 'Increase Mana per kill +8';
                $op2 = 'Increase hit points per kill +8';
                $op3 = 'Increase attacking(wizardly) speed +7';
                $op4 = 'Increase damage +2%';
                $op5 = 'Increase Damage +level/20';
                $op6 = 'Excellent Damage Rate +10%';
                $inf = 'Additional Damage';
                break;
            case 1:
                $op1 = 'Increase Zen After Hunt +40%';
                $op2 = 'Defense success rate +10%';
                $op3 = 'Reflect damage +5%';
                $op4 = 'Damage Decrease +4%';
                $op5 = 'Increase MaxMana +4%';
                $op6 = 'Increase MaxHP +4%';
                $inf = 'Additional Defense';
                $skill = '';
                $nocolor = false;
                break;
            case 4:
                $op1 = ' Life +' . (50 + ($itemlevel * 5)) . ' Increased';
                $op2 = ' Mana +' . (50 + ($itemlevel * 5)) . ' Increased';
                $op3 = ' 10% Mana loss instead of Life';
                $op4 = ' +50 of damage transfered as Life';
                $op5 = ' Increase Attacking(wizardry)speed +5';
                $op6 = '';
                $inf = 'Additional Damage';
                $skill = '';
                $nocolor = true;
        }

        if ($exc1 == 1) {
            $itemexl .= '<br>' . $op1;
        }
        if ($exc2 == 1) {
            $itemexl .= '<br>' . $op2;
        }
        if ($exc3 == 1) {
            $itemexl .= '<br>' . $op3;
        }
        if ($exc4 == 1) {
            $itemexl .= '<br>' . $op4;
        }
        if ($exc5 == 1) {
            $itemexl .= '<br>' . $op5;
        }
        if ($exc6 == 1) {
            $itemexl .= '<br>' . $op6;
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
            $c = '#2FF387';
        } // Green -> Excellent Item 
        if ($nocolor) {
            $c = '#F4CB3F';
        }
        if ($itemoption == 0) {
            $itemoption = '';
        } else {
            $itemoption = $inf . " +" . $itemoption;
        }
        if (($itemexl != '') && ($itemname) && (!$nocolor)) {
            $itemname = 'Excellent ' . $itemname;
        }

        if ($nolevel == 1) {
            $ilvl = 0;
        } else {
            $ilvl = $itemlevel;
        }

        $output['clearname'] = $rows['name'];
        $output['name'] = $itemname;
        $output['level'] = $ilvl;
        $output['opt'] = $itemoption;
        $output['exl'] = $itemexl;
        $output['luck'] = $luck;
        $output['skill'] = $skill;
        $output['dur'] = $itemdur;
        $output['X'] = $rows['x'];
        $output['Y'] = $rows['y'];
        $output['color'] = $c;
        $output['thumb'] = $this->itemimage($AA, $BB, $CC);

        $itemformat = '<div align=center style=\'padding-left: 6px; padding-right:6px;font-family:arial;font-size: 10px;\'><span style=\'font-weight:bold;font-size: 11px;\'>[Name]</span><br><br>[Image]<br>Durability<br>[Skill] [Luck] [Excellent]</span></div>';

        if ($output['level']) {
            $plusche = '+' . $output['level'];
        }

        $overlib = str_replace('[Name]', '<span style=color:' . $output['color'] . '>' . $output['name'] . ' ' . $plusche . '</span>', addslashes($itemformat));
        $overlib = str_replace('Durability', $output['dur'] . ' durability', $overlib);

        if ($output['opt']) {
            $option = '<br><font color=#9aadd5>' . $output['opt'] . '</font>';
        }

        if ($output['luck']) {
            $luck = '<br><font color=#9aadd5>' . $output['luck'] . '';
        }
        $overlib = str_replace('[Luck]', $luck . $option . '</font>', $overlib);

        if ($output['skill']) {
            $skill = '<br><font color=#9aadd5>' . $output['skill'] . '</font>';
        }
        $overlib = str_replace('[Skill]', $skill, $overlib);

        if ($output['exl']) {
            $exl = '<font color=#8CB0EA>' . str_replace('^^', '<br>', $output['exl']);
        }

        $overlib = str_replace('[Excellent]', $exl, $overlib);
        $overlib = str_replace('[Image]', "<img src='./template/items/" . $output['thumb'] . "' />", $overlib);

        $output['overlib'] = $overlib;

        return $output;
    }

    public function smartsearch($whbin, $itemX, $itemY) {
        if (substr($whbin, 0, 2) == '0x') {
            $whbin = substr($whbin, 2);
        }

        $items = str_repeat('0', 120);
        $itemsm = str_repeat('1', 120);
        $i = 0;
        while ($i < 120) {
            $_item = substr($whbin, (20 * $i), 20);
            $level = substr($_item, 0, 1);
            $level2 = substr($_item, 1, 1);
            $level3 = substr($_item, 14, 2);
            $level1 = hexdec(substr($level, 0, 1));
            if (($level1 % 2) <> 0) {
                $level2 = "1" . $level2;
                $level1--;
            }
            if (hexdec($level3) >= 128) {
                $level1 += 16;
            }
            $level1 /= 2;
            $id = hexdec($level2);

            $result = mssql_query("select [x],[y],[Name] from [DTweb_JewelDeposit_Items] where [id]={$id} and [type]={$level1}");
            $res = mssql_fetch_array($result);


            $y = 0;
            while ($y < $res['y']) {
                $y++;
                $x = 0;
                while ($x < $res['x']) {
                    $items = substr_replace($items, '1', ($i + $x) + (($y - 1) * 8), 1);
                    $x++;
                }
            }
            $i++;
        }
        $y = 0;
        while ($y < $itemY) {
            $y++;
            $x = 0;
            while ($x < $itemX) {
                $x++;
                $spacerq[$x + (8 * ($y - 1))] = true;
            }
        }
        $walked = 0;
        $i = 0;
        while ($i < 120) {
            if (isset($spacerq[$i])) {
                $itemsm = substr_replace($itemsm, '0', $i - 1, 1);
                $last = $i;
                $walked++;
            }
            if ($walked == count($spacerq)) {
                $i = 119;
            }
            $i++;
        }
        $useforlength = substr($itemsm, 0, $last);
        $findslotlikethis = '^' . str_replace('++', '+', str_replace('1', '+[0-1]+', $useforlength));
        $i = 0;
        $nx = 0;
        $ny = 0;
        while ($i < 120) {
            if ($nx == 8) {
                $ny++;
                $nx = 0;
            }
            if ((preg_match("/{$findslotlikethis}/", substr($items, $i, strlen($useforlength)))) && ($itemX + $nx < 9) && ($itemY + $ny < 16)) {
                return $i;
            }
            $i++;
            $nx++;
        }
        return 1337;
    }

    public function betRenas($count) {
	    if(isset($_SESSION['lang'])){
			require $_SERVER['DOCUMENT_ROOT']."/lang/".$_SESSION['lang'].".php";
		}
		else{
			require $_SERVER['DOCUMENT_ROOT']."/lang/en.php";
		}
		$check_opt = mssql_fetch_array(mssql_query("Select * from DTweb_Auction_Settings"));
        if ($count > 0) {
            if ($count > User::getInstance()->updateRenas()) {
				
                echo "<span class='error'>".phrase_not_enough . $check_opt['column']."!</span>";
            } else {
				require $_SERVER['DOCUMENT_ROOT']."/configs/config.php";
				if($check_opt['column'] == "credits"){
					$table    = $option['cr_db_table'];
					$tbl_user = $option['cr_db_check_by'];
					$column   = $option['cr_db_column'];
				}	
				else{
					$table    = $check_opt['table'];
					$tbl_user = $check_opt['user'] ;
					$column   = $check_opt['column'];
				}
                $chk = Database::$inst->q("Select * from [DTweb_Auction_Bets] Where [auction_id]={$this->auction_id} and [user]='" . User::$inst->getUser() . "'", 1);
                Database::$inst->q("Update [".$table."] Set [".$column."]=[".$column."]-{$count} Where [".$tbl_user."]='" . User::$inst->getUser() . "'");

                if ($chk == 1) {
                    Database::$inst->q("Update [DTweb_Auction_Bets] Set ip='".$this->ip()."',time='".time()."',[count]=[count]+{$count} Where [auction_id]={$this->auction_id} and [user]='" . User::$inst->getUser() . "'");
                } else {
                    Database::$inst->q("INSERT INTO [DTweb_Auction_Bets] ([auction_id], [user], [count],[ip],[time],[resource]) VALUES ({$this->auction_id}, '" . User::$inst->getUser() . "', {$count},'".$this->ip()."','".time()."','".$column."')");
                }

                echo "<span class='success'>".phrase_you_have_bet .$count . "&nbsp;".$column."!</span>";
            }
        } else {
            echo "<span class='error'> ".phrase_must_bet_at_least .$column."!</span>";
        }
    }

    public function getTime() {



        return $this->time - time();
    }

    /**
     * 
     * @return Auction
     */
    public static function getInstance() {
        if (self::$inst == null) {
            self::$inst = new Auction();
        }

        return self::$inst;
    }

}
}