****RESERVIERUNGSPLUGIN 1.0 BY ALEX


**
**Kann über misc.php?action=reservieren erreicht werden
**


**
TEMPLATES
**

reservierung
***
<html>
<head>
<title>{$mybb->settings['bbname']} - Reservierung</title>
{$headerinclude}
</head>
<body>
{$header}
<table border="0" cellspacing="{$theme['borderwidth']}" cellpadding="{$theme['tablespace']}" class="tborder">
<tr>
<td class="thead"><h1>Reservierungen</h1></td>
</tr>
	<tr><td class="trow1">
		<h2>Regeln</h2>

<blockquote>
<ul>
<li>Gäste können eine Avatarperson für <b>zwei Wochen</b> reservieren.</li>
<li>User können <b>maximal 2 Avatarpersonen</b> für <b>vier Wochen</b> reservieren lassen.</li>
<li>Nur <b>angenommene Use</b>r dürfen einen weiteren Avatar reservieren.</li>
<li>Gesuchreservierungen sind <b>erlaubt</b>. Sowohl Avatarpersonen, wie auch Posten! 
(müssen aber mit dem Gesuch verlinkt werden). Reservierungen für Besonderheiten sind hingegen NICHT erlaubt. Hier ist <b>1 Avatarperson bzw Posten pro User</b> erlaubt.
<li>Es ist <b><u>nicht</b></u> möglich sich Familiennamen bzw. gesamte Familienzweige vorab 
reservieren zu lassen. Hier greift einfach die alte Regel wer zuerst kommt, mahlt zuerst und 
ist dann auch derjenige mit dem man sich bei evtl. Verwandtschaften abstimmen muss.</li>
<li>User können ihre Reservierungen <b>2 x um 2 weitere Wochen</b> verlängern.</li>
</ul>

<center><b><font style="font-size: 30px;">Avatare für angemeldete Charaktere müssen NICHT mehr reserviert werden!</font></b>
	</blockquote></td></tr>
<tr>
<td class="trow1" align="center">
{$ava_exist}
{$claim}
<br /><table style="margin:auto; width:80%;"><tr><td width="50%" class="thead"><h2>Weibliche</h2></td><td class="thead"><h2>Männliche</h2></td></tr>
<tr><td class="smalltext" valign="center">{$female}</td>
<td class="smalltext"  valign="center">{$male}</td></tr>
<tr><td width="50%" class="thead"><h2>Quidditchposition</h2></td><td class="thead"><h2>Vertrauensschüler</h2></td></tr>
<tr><td class="smalltext" valign="center">{$claim_pos}</td>
<td class="smalltext"  valign="center">{$claim_vertrauen}</td></tr>
	<tr><td width="50%" class="thead"><h2>Schulsprecher</h2></td>
<td class="thead"><h2>Buchcharakter</h2></td></tr>
<tr><td class="smalltext" valign="center">{$claim_schulspr}</td>
<td class="smalltext"  valign="center">{$claim_buchchar}</td></tr>
		<tr><td width="50%" class="thead"><h2>Sonstige</h2></td>
<td class="thead"><h2>Gesuche</h2></td></tr>
<tr><td class="smalltext" valign="center">{$claim_sonstige}</td>
<td class="smalltext"  valign="center">{$claim_gesuche}</td></tr>
</table>
</td>
</tr>
</table>
{$footer}
</body>
</html>

**
reservierung_alert
**

<div class="red_alert">
    <strong>Deine Reservierung läuft in {$days} ab.</strong>
</div>
<br />

**
reservierung_alert_over
**
<div class="red_alert">
    <strong>Deine Reservierung ist abgelaufen.</strong>
</div>
<br />

**
reservierung_ava_exist
**

<div class="red_alert">
    <strong>Dein Gewähltes Avatar ist leider schon vergeben</strong>
</div>
<br />

**
reservierung_bit
**
<div class="smalltext">{$row['claim']} &raquo; {$username} ({$start_datum} - {$end_datum} &raquo; <b>{$days_left}</b>)  {$count} {$options} </div>

**
reservierung_bit_edit
**
<style>.infopop { position: fixed; top: 0; right: 0; bottom: 0; left: 0; background: hsla(0, 0%, 0%, 0.5); z-index: 1; opacity:0; -webkit-transition: .5s ease-in-out; -moz-transition: .5s ease-in-out; transition: .5s ease-in-out; pointer-events: none; } .infopop:target { opacity:1; pointer-events: auto; } .infopop > .pop { background: #242424; width: 300px; position: relative; margin: 10% auto; padding: 25px; z-index: 3; } .closepop { position: absolute; right: -5px; top:-5px; width: 100%; height: 100%; z-index: 2; }</style>
<div id="popinfo$row[rid]" class="infopop">
  <div class="pop"><form method="post" action=""><input type='hidden' value='{$row['rid']}' name='getrid'>
  <table width='100%' class='trow1'><tr><td colspan='2'><h2>Reservierung editieren</h2></td></tr>
 <tr><td class="trow1"><b>Username</b></td><td class="trow1"><input type="text" name="username" id="username" value="{$row['username']}" class="textbox" /></td></tr>
	   <tr><td class="trow1"><b>Userid</b></td><td class="trow1"><input type="text" name="uid" id="uid" value="{$row['uid']}" class="textbox" /></td></tr>
	<tr><td class="trow1"><b>Reservierung für</b></td><td class="trow1"><select name="cat"><option selected value="{$row['cat']}"><i>{$row['cat']}</i></option>
		<option>Reservierungsart</option>
		<option value="avatar">Avatarreservierung</option>
	<option value="position">Quidditchposition</option>
<option value="vertrauen">Vertrauensschüler</option>
		<option value="schulspr">Schulsprecher</option>
		<option value="buchchar">Buchcharakter</option>
		<option value="sonstige">Sonstige</option>
		<option value="wanted">Gesuchsreservierung</option>
	</select></td></tr>
	<tr><td class="trow1"><b>Reservierung</b></td><td class="trow1"><input type="text" name="claim" id="claim" value="{$row['claim']}" class="textbox" /></td></tr>
	<tr><td class="trow1"><b>Geschlecht</b></td><td class="trow1">
	<select name="sex"><option selected value="{$row['sex']}"><i>{$row['sex']}</i></option><option>Geschlecht</option>
	<option value="männlich">männlich</option>
<option value="weiblich">weiblich</option></select></td></tr>
	<tr><td class="trow1"><b>Link zum Gesuch</b></td><td class="trow1"><input type="text" name="wanted" id="wanted" value="{$row['wanted']}" class="textbox" /></td></tr>
<tr><td colspan='2' align='center'><input type="submit" name="edit" value="Reservierung editieren" id="submit" class="button"></td></tr></table>
	  </form>
		</div><a href="#closepop" class="closepop"></a>
</div>

<a href="#popinfo$row[rid]">[e]</a>

**
reservierung_bit_sex
**
<div class="smalltext"><b>{$row['claim']}</b> ({$row['sex']}) &raquo; {$username} ({$start_datum} - {$end_datum} &raquo; <b>{$days_left}</b>)  {$count} {$options} </div>

**
reservierung_bit_wanted
**
<div class="smalltext">{$row['claim']} {$wanted} &raquo; {$username} ({$start_datum} - {$end_datum} &raquo; <b>{$days_left}</b>)  {$count} {$options} </div>

**
reservierung_formular
**
<table border="0" cellspacing="5" cellpadding="{$theme['tablespace']}" class="tborder" style="width: 50%; margin:auto;">
	<form id="claim" method="post" action="misc.php?action=reservieren"><tr><td class="trow1"><b>Username</b></td><td class="trow1">{$username}</td></tr>
	<tr><td class="trow1"><b>Reservierung für</b></td><td class="trow1"><select name="cat"><option>Reservierungsart</option>
		<option value="avatar">Avatarreservierung</option>
	<option value="position">Quidditchposition</option>
<option value="vertrauen">Vertrauensschüler</option>
		<option value="schulspr">Schulsprecher</option>
		<option value="buchchar">Buchcharakter</option>
		<option value="sonstige">Sonstige</option>
		<option value="wanted">Gesuchsreservierung</option>
	</select></td></tr>
	<tr><td class="trow1"><b>Reservierung</b></td><td class="trow1"><input type="text" name="claim" id="claim" placeholder="Reservierung" class="textbox" /><br /> <smalltext>Bitte Bei Avatar <B>Nachname, Vorname</b> schreiben.</td></tr>
	<tr><td class="trow1"><b>Geschlecht</b></td><td class="trow1">
	<select name="sex"><option>Geschlecht</option>
	<option value="männlich">männlich</option>
<option value="weiblich">weiblich</option></select></td></tr>
	<tr><td class="trow1"><b>Link zum Gesuch</b></td><td class="trow1"><input type="text" name="wanted" id="wanted" placeholder="https://" class="textbox" /></td></tr>
		{$nh_spamprotect}
		<tr class="trow2">
<td colspan="2" align="center"><input type="submit" name="res" value="eintragen" id="submit" class="button"></td></tr></form></table>

**
reservierung_header
**
<li><a href="misc.php?action=reservieren">Reservierungen</a></li>

**
WENN NOCH NICHT VORHANDEN!!
**
nh_spamprotect
**

<tr>
<td class="trow1" valign="top"><strong>Sicherheitsfrage</strong></td>
<td class="trow1">
<legend><strong>Spamschutz!</strong></legend>
<table cellspacing="0" cellpadding="4">
<tr>
<td><span class="smalltext">Bitte <strong>"Ich bin kein Bot"</strong> in die Textbox eintragen. Ohne Anführungszeichen!<br />Die Eingabe ist notwendig um automatisierte Beitragserstellungen zu vermeiden.</span></td>
</tr>
<tr>
<td><input type="text" value="" size="0" class="textbox" id="captchain" name="captchain" /><input type="hidden" name="captchapostplus" value="Ich bin kein Bot" id="captchapostplus" /></td>
</tr>
</table>
</td>
</tr>


########################################

***
VARIABELN
***

**
Header
**
{$header_reservierung} //Link

{$global_reservierung_alert} //Benachrichtigung
