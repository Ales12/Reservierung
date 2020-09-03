<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 22.10.2017
 * Time: 10:10
 */


// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
    die("Direct initialization of this file is not allowed.");
}


//hooks
$plugins->add_hook('misc_start', 'reservieren');
$plugins->add_hook('global_intermediate', 'reservieren_header');
$plugins->add_hook('global_intermediate', 'reservieren_alert_global');

function claims_info()
{
    return array(
        "name"			=> "Reservierung 1.0",
        "description"	=> "Dieser Plugin erlaubt das automatische Reservieren.",
        "website"		=> "",
        "author"		=> "Ales",
        "authorsite"	=> "",
        "version"		=> "1.0",
        "guid" 			=> "",
        "codename"		=> "",
        "compatibility" => "*"
    );
}

function claims_install()
{
    global $db, $mybb;

    if($db->engine=='mysql'||$db->engine=='mysqli')
    {
        $db->query("CREATE TABLE `".TABLE_PREFIX."claims` (
          `rid` int(10) NOT NULL auto_increment,
           `uid` int(10) NOT NULL,
          `type` varchar(255) NOT NULL,
          `cat` varchar(255) NOT NULL,
          `username` varchar(255) NOT NULL,
          `claim` varchar(255) NOT NULL,
          `sex` varchar(255) NOT NULL,
          `wanted` varchar(255) NOT NULL,
          `start` int(11) NOT NULL,
          `end` int(11) NOT NULL,
          `count` int(11) NOT NULL default '0',
          PRIMARY KEY (`rid`)
        ) ENGINE=MyISAM".$db->build_create_table_collation());
    }

    $setting_group = array(
        'name' => 'claim',
        'title' => 'Reservierung',
        'description' => 'Hier kannst du alles zu deinen Plugin einstellen.',
        'disporder' => 5, // The order your setting group will display
        'isdefault' => 0
    );
    $gid = $db->insert_query ("settinggroups", $setting_group);

    $setting_array = array(
        'name' => 'user_res',
        'title' => 'Länge Userreservierung',
        'description' => 'Wie lange dürfen User Reservieren?',
        'optionscode' => 'numeric',
        'value' => '30',
        'disporder' => 1,
        "gid" => (int)$gid
    );
    $db->insert_query ('settings', $setting_array);

    $setting_array = array(
        'name' => 'user_player',
        'title' => 'Username',
        'description' => 'Gib hier die Profilfeld ID an, worin der Username gespeichert wird. fid dort stehen lassen!',
        'optionscode' => 'text',
        'value' => 'fid2',
        'disporder' => 1,
        "gid" => (int)$gid
    );
    $db->insert_query ('settings', $setting_array);

    $setting_array = array(
        'name' => 'user_extend',
        'title' => 'Userreservierung verlängern',
        'description' => 'Wie oft dürfen User verlängern?',
        'optionscode' => 'numeric',
        'value' => '2',
        'disporder' => 2,
        "gid" => (int)$gid
    );
    $db->insert_query ('settings', $setting_array);

    $setting_array = array(
        'name' => 'user_extend_days',
        'title' => 'Tageanzahl',
        'description' => 'Wie lange dürfen User verlängern?',
        'optionscode' => 'numeric',
        'value' => '14',
        'disporder' => 2,
        "gid" => (int)$gid
    );
    $db->insert_query ('settings', $setting_array);

    $setting_array = array(
        'name' => 'guest_allow',
        'title' => 'Gästereservierung',
        'description' => 'Dürfen Gäste reservieren? (Standard auf Nein)',
        'optionscode' => 'yesno',
        'value' => '0',
        'disporder' => 3,
        "gid" => (int)$gid
    );
    $db->insert_query ('settings', $setting_array);

    $setting_array = array(
        'name' => 'guest_res',
        'title' => 'Länge Gästereservierung',
        'description' => 'Wie lange dürfen Gäste reservieren?',
        'optionscode' => 'numeric',
        'value' => '14',
        'disporder' => 4,
        "gid" => (int)$gid
    );
    $db->insert_query ('settings', $setting_array);

    $setting_array = array(
        'name' => 'guest_extend',
        'title' => 'Gästereservierung verlängern',
        'description' => 'Wie oft dürfen Gäste verlängern?',
        'optionscode' => 'numeric',
        'value' => '1',
        'disporder' => 5,
        "gid" => (int)$gid
    );
    $db->insert_query ('settings', $setting_array);

    $setting_array = array(
        'name' => 'guest_extend_days',
        'title' => 'Tageanzahl',
        'description' => 'Wie lange dürfen Gäste verlängern?',
        'optionscode' => 'numeric',
        'value' => '14',
        'disporder' => 5,
        "gid" => (int)$gid
    );
    $db->insert_query ('settings', $setting_array);

    $setting_array = array(
        'name' => 'avatar_exist_control',
        'title' => 'Existierende Avatar',
        'description' => 'Gib hier die Profilfeld ID ein, worin der User sein Avatar angibt.',
        'optionscode' => 'yesno',
        'value' => '0',
        'disporder' => 6,
        "gid" => (int)$gid
    );
    $db->insert_query ('settings', $setting_array);

    $setting_array = array(
        'name' => 'avatar_exist',
        'title' => 'Existierende Avatar',
        'description' => 'Gib hier die Profilfeld ID ein, worin der User sein Avatar angibt.',
        'optionscode' => 'text',
        'value' => 'fid1',
        'disporder' => 6,
        "gid" => (int)$gid
    );
    $db->insert_query ('settings', $setting_array);

    rebuild_settings();


}

function claims_is_installed()
{
    global $db;
    if($db->table_exists("claims"))
    {
        return true;
    }
    return false;
}

function claims_uninstall()
{

    global $db;

    //Settings Löschen
    $db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='claim'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='user_res'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='user_player'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='user_extend'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='user_extend_days'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='guest_allow'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='guest_res'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='guest_extend'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='guest_extend_days'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='avatar_exist_control'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='avatar_exist'");

    //Tabelle aus Datenbank löschen
    if($db->table_exists("claims"))
    {
        $db->drop_table("claims");
    }
    rebuild_settings();
}

function claims_activate()
{
    global $db;

   
}

function claims_deactivate()
{
    global $db;
  require MYBB_ROOT."/inc/adminfunctions_templates.php";

}



// In the body of your plugin
function reservieren()
{
    global $mybb, $templates, $lang, $header, $headerinclude, $footer, $page, $db, $claim, $username,$user_player, $wanted, $start_datum, $days_left,$options,$edit,$count  ;

    if($mybb->get_input('action') == 'reservieren')
    {
        // Do something, for example I'll create a page using the hello_world_template

        // Add a breadcrumb
        add_breadcrumb('Reservierungen', "misc.php?action=reservieren");

        //Einstellungen ziehen
        $avatar_exist  = $mybb->settings['avatar_exist'];
        $avatar_exist_c  = $mybb->settings['avatar_exist_control'];
        $res_user = intval($mybb->settings['user_res']);
        $res_guest = intval($mybb->settings['guest_res']);
        $guest_allow = intval($mybb->settings['guest_allow']);
        $guest_extend = intval($mybb->settings['guest_extend']);
        $user_extend = intval($mybb->settings['user_extend']);
        $guest_extend_days = intval($mybb->settings['guest_extend_days']);
        $user_extend_days = intval($mybb->settings['user_extend_days']);
        $user_player = $mybb->settings['user_player'];


        if($guest_allow != 0 OR $mybb->user['uid'] != 0 OR $mybb->user['uid'] != ''){
            if($mybb->user[$user_player] == ''){
                $username = $mybb->user['username'];
            } else {
                $username = $mybb->user[$user_player];
            }
            if($mybb->user['uid'] == 0 OR $mybb->user['uid'] == '') {
                $username = "<input type=\"text\" name=\"username\" id=\"username\" value=\"Spielername\" class=\"textbox\" />";
            }
			
			if($mybb->user['uid'] == 0) {
    eval("\$nh_spamprotect = \"".$templates->get("nh_spamprotect")."\";");
    }
	
            eval("\$claim = \"".$templates->get("reservierung_formular")."\";");
        } else {
            $claim = "Gäste Dürfen nicht Reservieren";
        }

    
	
        if(isset($_POST['res'])){
            $uid = $mybb->user['uid'];
            if($_POST['username']) {
                $username = $_POST['username'];
            } else {
                    $username = $username;
            }
            $cat = $_POST['cat'];
        $claim = $_POST['claim'];
        $sex = $_POST['sex'];
        $wanted = $_POST['wanted'];
        $start = TIME_NOW;
        if($mybb->user['uid'] != 0 OR $mybb->user['uid'] != ''){
            $end = $res_user;
        } else{
            $end = $res_guest;
        }
            if($avatar_exist_c != 0){
            $avaselect = $db->query("SELECT *
            FROM ".TABLE_PREFIX."userfields
            WHERE UPPER(".$avatar_exist.") = UPPER('$claim')
            ");
                $row_cnt = mysqli_num_rows($avaselect);
            } else {
                $row_cnt = 0;
            }

        $res = array(
            "type" => "reservierung",
            "uid" => $db->escape_string($uid),
            "cat" => $db->escape_string($cat),
            "username" => $db->escape_string($username),
            "claim" => $db->escape_string($claim),
            "sex" => $db->escape_string($sex),
            "wanted" => $db->escape_string($wanted),
            "start" => $db->escape_string($start),
            "end" => $db->escape_string($end)
        );

	if($mybb->input['captchain'] != 'Ich bin kein Bot' && !$mybb->user['uid']) {
        error('Du hast die Sicherheitsfrage leider falsch beantwortet!<br /><a href="javascript:history.back()">Zur&uuml;ck</a>');
	}
            if ($row_cnt != 0) {
                eval("\$ava_exist = \"".$templates->get("reservierung_ava_exist")."\";");

                eval("\$claim = \"".$templates->get("reservierung_formular")."\";");
            }
            else {
                $db->insert_query ("claims", $res);

                redirect ("misc.php?action=reservieren");
            }
        }


        //Ausgeben

        $select = $db->query("SELECT *
        FROM ".TABLE_PREFIX."claims
        WHERE type = 'reservierung'
        ");

        while($row = $db->fetch_array($select)){
			$count = "";
			$options = ""
			;
             //Optionen werden hier erzeugt
            eval("\$edit = \"".$templates->get("reservierung_bit_edit")."\";");
			
            if($mybb->usergroup['canmodcp'] == 1) {
				if($row['count'] != $guest_extend OR $row['count'] != $user_extend ){
					 $options =  "<a href=\"misc.php?action=reservieren&renew=$row[rid]\">[v]</a> <a href=\"misc.php?action=reservieren&del=$row[rid]\">[x]</a> {$edit}";
				} else{
						 $options =  "<a href=\"misc.php?action=reservieren&del=$row[rid]\">[x]</a> {$edit}";
				}
               
            }
        if($row['uid'] != 0){
           $username = "<a href='member.php?action=profile&uid=$row[uid]' target='_blank'>$row[username]</a>";
            } else {
                $username = $row['username']." (Gast)";
            }

			//$username = $row['username'];
            //Gesuchslinks wird erstellt
            $wanted = "<a href='$row[wanted]' target='_blank'>(Gesuche)</a>";

            //Tage Berechnung
            $faktor = 86400;
            $end = $row['end'] * $faktor;
            $ende =$row['start'] + $end;
            $start_datum = date ('d.m.Y', $row['start']);
            $end_datum = date('d.m.Y', $ende );
            $days = round(($ende - TIME_NOW)/$faktor);

            if($days > 0){
                $days_left = "noch <b>$days</b> Tage";
            } else {
                $days_left = "<b>abgelaufen</b>";

            }

            //Verlängerungen
            if($row['count'] > 0){
				$counter = $row['count'];
                $count = "<b>".$counter.". Verl.</b>";
            }


//Und hier ist mal die komplette Ausgabe

            if($row['cat'] == 'avatar'){
                if($row['sex'] == 'weiblich'){
                    eval("\$female .= \"".$templates->get("reservierung_bit")."\";");

                } elseif($row['sex'] == 'männlich') {
                    eval("\$male .= \"".$templates->get("reservierung_bit")."\";");
                }
            }
			elseif ($row['cat'] == 'serie')
            {
                eval("\$claim_serie .= \"".$templates->get("reservierung_bit")."\";");
            }
            else
            {
                eval("\$claim_gesuche .= \"".$templates->get("reservierung_bit_wanted")."\";");
            }

        }

        if(isset($mybb->input['edit'])){
            $getrid = $mybb->input['getrid'];
            $uid = $mybb->input['uid'];
            $username = $mybb->input['username'];
            $claim = $mybb->input['claim'];
            $cat = $mybb->input['cat'];
            $sex = $mybb->input['sex'];
            $wanted = $mybb->input['wanted'];


            $db->query("UPDATE ".TABLE_PREFIX."claims SET uid = '".$uid."', username = '".$username."', claim = '".$claim."', cat = '".$cat."', sex = '".$sex."', wanted = '".$wanted."' WHERE rid = '$getrid'");

            redirect('misc.php?action=reservieren');
        }

        $del = $mybb->input['del'];
        if($del){
			
			$teamuid = $mybb->user['uid'];
            $select = $db->query("SELECT * FROM ".TABLE_PREFIX."claims WHERE rid = '$del' ");
            $row = $db->fetch_array($select);
            $user = $row['uid'];

			if($user != 0){
            require_once MYBB_ROOT."inc/datahandlers/pm.php";
            $pmhandler = new PMDataHandler();

            $pm_change = array(
                "subject" => "Deine Reservierung wurde gelöscht.",
                "message" => "Deine Reservierung wurde vom Team gelöscht. Grund hierfür könnte entweder die Abgelaufene Reservierungszeit oder das Missachten unserer Regeln sein. <br /> Bei Fragen kannst du dich gerne an das Team wenden.",
                //to: wer muss die anfrage bestätigen
                "fromid" => $teamuid,
                //from: wer hat die anfrage gestellt
                "toid" => $user
            );
            // $pmhandler->admin_override = true;
            $pmhandler->set_data($pm_change);
            if(!$pmhandler->validate_pm())
                return false;
            else
            {
                $pmhandler->insert_pm();
            }
			}
            $db->delete_query("claims", "rid = '$del'");
            redirect("misc.php?action=reservieren");
        }

        $renew = $mybb->input['renew'];
        if($renew){
            $select = $db->query("SELECT *
            FROM ".TABLE_PREFIX."claims
            WHERE rid = $renew
            ");
            $row = $db->fetch_array($select);
            if($row['uid'] != '0'){
                if($row['count'] < $user_extend){
                    $new_end = $row['end'] +  $user_extend_days;
                    $db->query("UPDATE ".TABLE_PREFIX."claims SET end = '".$new_end."', count = count + 1 WHERE rid = '".$renew."'");
                    redirect("misc.php?action=reservieren");
                }

            } else {
                if($row['count'] < $guest_extend){
                    $new_end = $row['end'] +  $guest_extend_days;
                    $db->query("UPDATE ".TABLE_PREFIX."claims SET end = '".$new_end."', count = count + 1 WHERE rid = '".$renew."' ");
                    redirect("misc.php?action=reservieren");
                }
            }




        }


        // Using the misc_help template for the page wrapper
        eval("\$page = \"".$templates->get("reservierung")."\";");
        output_page($page);
    }

}

    function reservieren_header(){
    global $templates, $header_reservierung;
    eval("\$header_reservierung = \"".$templates->get("reservierung_header")."\";");
}

function reservieren_alert_global(){
        global $mybb, $db, $templates, $days, $global_reservierung_alert;



    $select = $db->query("SELECT *
        FROM ".TABLE_PREFIX."claims
        WHERE type = 'reservierung'
        ");
   while($row = $db->fetch_array($select)) {
       //Tage Berechnung
       $faktor = 86400;
       $end = $row['end'] * $faktor;
       $ende = $row['start'] + $end;
       $start_datum = date ('d.m.Y', $row['start']);
       $end_datum = date ('d.m.Y', $ende);
       $days = round (($ende - TIME_NOW) / $faktor);
	 
	
       if ($row['uid'] != 0 && $row['uid'] == $mybb->user['uid']) {
           if ($days <= 8 AND $days > 0) {
               if($days > 1){
                   $days = $days."Tagen";
               }else {
                   $days = $days."Tag";
               }

               eval("\$global_reservierung_alert = \"" . $templates->get ("reservierung_alert") . "\";");
           } elseif ($days <= 0) {
               eval("\$global_reservierung_alert = \"" . $templates->get ("reservierung_alert_over") . "\";");
           }
       }
   }
}
?>