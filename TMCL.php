<?php

//////// TRACKMANIA COMMAND LADDER v0.2 ////////
        /**   Created by RedShadow    **/

/** Command line tool which can access the TrackMania Web Services with PHP.
 *
 * Credits
 *  - BigBang112 (https://bigbang1112.cz/) - Wouldn't have gotten access to the API without you
 *  - Kripke (https://kripke.club/) - Shared useful code
 *  - fabimaniak - Testing
 * 
 * Patch Notes
 *  - v0.2
 *     - Added solo leaderboard searching for specific challenges using UIDs.
 *     - Removed the credits option.
 *     - Some bugfixes.
 *  - v0.1
 *     - Initial release!
 * 
**/

// Prints out the program name, version and creator.
echo "\n\x1b[37;40;1;4mTrackMania Command Ladder v0.2\x1b[0m\n© \x1b[31;40;1mRedShadow \x1b[0m2024\n\n";

// Prints out all the available options.
echo "\x1b[31;1m/i \x1b[30m- \x1b[0mGeneral information about TMF.\n";
echo "\x1b[32;1m/p \x1b[30m- \x1b[0mCheck player information.\n";
echo "\x1b[33;1m/s \x1b[30m- \x1b[0mCheck the solo leaderboard. (Skill Points)\n";
echo "\x1b[34;1m/m \x1b[30m- \x1b[0mCheck the multiplayer leaderboard. (Ladder Points)\n";
echo "\x1b[35;1m/c \x1b[30m- \x1b[0mCheck the solo leaderboard about a specific track. (UID) \x1b[31;1m(SP display is incorrect!)\n";

// Opens up an input field for the user to choose an option.
echo "\x1b[37;1;4m\nInput the chosen command to start!\x1b[0m\n";
$opt = trim(fgets(STDIN));

// The "switch" statement runs "cases" according to the command typed in above.
switch ($opt) {
	
	// If "/i" is chosen...
	case "/i":
		
		// Sets variable for the URL needed.
		$i_url_r = "http://ws.trackmania.com/tmf/registrations/";
		
		// Sets variable for the cURL command.
		$i_curl_r = curl_init($i_url_r);
		
		// Sets options for the cURL command.
		curl_setopt($i_curl_r, CURLOPT_URL, $i_url_r);
		curl_setopt($i_curl_r, CURLOPT_RETURNTRANSFER, true);
		
		// Sets an authentication header for cURL command.
		$headers = array(
		   "Authorization: Basic dG1mX3JlZHNoYWRvd19fX19fMjoxMjM0NQ==",
		);
		
		// Sets some more options for the cURL command!
		curl_setopt($i_curl_r, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($i_curl_r, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($i_curl_r, CURLOPT_SSL_VERIFYPEER, false);
		
		// Executes cURL and decodes it's response and also removes it's quotation marks.
		$i_reg = curl_exec($i_curl_r) ;
		$i_reg_trim = trim($i_reg, '"');
		
		// Closes cURL.
		curl_close($i_curl_r);
		
		// Parses the response of "$i_arr_r" into a clean response.
		echo "\n\n\nThere is currently \x1b[33;1m".$i_reg_trim." \x1b[0mregistered drivers in TMF!\n";
		
		break;

	// If "/p" is chosen...
	case "/p":
		
		// Open up an input field for a player's login.
		echo "\x1b[37;1;4m\nInput a player's login.\x1b[0m\n";
		$login = trim(fgets(STDIN));
		
		// Sets variables for all URLs needed.
		$p_url_i = "http://ws.trackmania.com/tmf/players/".$login."/";
		$p_url_s = "http://ws.trackmania.com/tmf/players/".$login."/rankings/solo/";
		$p_url_m = "http://ws.trackmania.com/tmf/players/".$login."/rankings/multiplayer/";

		// Sets variables for all cURL commands.
		$p_curl_i = curl_init($p_url_i);
		$p_curl_s = curl_init($p_url_s);
		$p_curl_m = curl_init($p_url_m);

		// Sets options for the cURL commands.
		curl_setopt($p_curl_i, CURLOPT_URL, $p_url_i);
		curl_setopt($p_curl_i, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($p_curl_s, CURLOPT_URL, $p_url_s);
		curl_setopt($p_curl_s, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($p_curl_m, CURLOPT_URL, $p_url_m);
		curl_setopt($p_curl_m, CURLOPT_RETURNTRANSFER, true);

		// Sets an authentication header for cURL command.
		$headers = array(
		   "Authorization: Basic dG1mX3JlZHNoYWRvd19fX19fMjoxMjM0NQ==",
		);

		// Sets some more options for the cURL commands!
		curl_setopt($p_curl_i, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($p_curl_i, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($p_curl_i, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($p_curl_s, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($p_curl_s, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($p_curl_s, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($p_curl_m, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($p_curl_m, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($p_curl_m, CURLOPT_SSL_VERIFYPEER, false);

		// Executes cURL and decodes it's JSON response.
		$p_json_i = curl_exec($p_curl_i);
		$p_arr_i = json_decode($p_json_i, true);
		$p_json_s = curl_exec($p_curl_s);
		$p_arr_s = json_decode($p_json_s, true);
		$p_json_m = curl_exec($p_curl_m);
		$p_arr_m = json_decode($p_json_m, true);

		// Closes cURL.
		curl_close($p_curl_i);
		curl_close($p_curl_s);
		curl_close($p_curl_m);
		
		// Parses the "united" value into "TRUE!" or "FALSE!"
		$p_arr_u = $p_arr_i['united'] ? "\x1b[32mTRUE!\x1b[0m" : "\x1b[31mFALSE!\x1b[0m";

		// Parses the JSON response of "$p_arr_i" into a clean list.
		echo "\n\n\n\x1b[37;1;4m\nPlayer\x1b[0m\n";
		echo "\x1b[3mLogin: \x1b[0m".$p_arr_i['login']."\n";
		echo "\x1b[3mNickname: \x1b[0m".$p_arr_i['nickname']."\n";
		echo "\x1b[3mID: \x1b[0m".$p_arr_i['id']."\n";
		echo "\x1b[3mLocation: \x1b[0m".$p_arr_i['path']."\n";
		echo "\x1b[3mOwns United? \x1b[0m".$p_arr_u."\n\n"; 

		// Parses the JSON response of "$p_arr_s" into a clean list.
		echo "\x1b[37;1;4mSolo\x1b[0m\n";
		echo "\x1b[3mPosition: \x1b[0m".$p_arr_s['ranks']['0']['rank']." \x1b[3m(".$p_arr_s['ranks']['0']['path'].")\x1b[0m\n";
		echo "\x1b[3m".$p_arr_s['unit'].": \x1b[0m\x1b[33;1m".$p_arr_s['points']." \x1b[0m\x1b[3m(".$p_arr_s['environment'].")\x1b[0m\n\n";

		// Parses the JSON response of "$p_arr_m" into a clean list.
		echo "\x1b[37;1;4mMultiplayer\x1b[0m\n";
		echo "\x1b[3mPosition: \x1b[0m".$p_arr_m['ranks']['0']['rank']." \x1b[3m(".$p_arr_m['ranks']['0']['path'].")\x1b[0m\n";
		echo "\x1b[3mPosition: \x1b[0m".$p_arr_m['ranks']['1']['rank']." \x1b[3m(".$p_arr_m['ranks']['1']['path'].")\x1b[0m\n";
		echo "\x1b[3m".$p_arr_m['unit'].": \x1b[0m\x1b[33;1m".$p_arr_m['points']." \x1b[0m\x1b[3m(".$p_arr_m['environment'].")\x1b[0m\n";
		
		// Breaks the god gamer file :(
		break;

	// If "/s" is chosen...
	case "/s":
	
		// Open up an input field for the leaderboard path.
		echo "\x1b[37;1;4m\nInput the leaderboard path.\x1b[0m \x1b[3m(ex. World|France) \x1b[31;1m(If you have a path with spaces, replace them with %20!)\x1b[0m\n";
		$path = trim(fgets(STDIN));
		
		// Open up an input field for the link offset.
		echo "\x1b[37;1;4m\nInput the leaderboard offset.\x1b[0m \x1b[3m(ex. If offset is 10, the first player on the list will be 11th.) (Default: 0)\x1b[0m\n";
		$offset = trim(fgets(STDIN));
		
		// Open up an input field for the link length.
		echo "\x1b[37;1;4m\nInput the leaderboard length.\x1b[0m \x1b[3m(ex. If length is 5, there will be five players on the list.) (Default & Max: 10)\x1b[0m\n";
		$length = trim(fgets(STDIN));
		
		// Sets variable for the URL needed.
		$s_url_lb = "http://ws.trackmania.com/tmf/rankings/solo/players/".$path."/?offset=".$offset."&length=".$length;

		// Sets variable for the cURL command.
		$s_curl_lb = curl_init($s_url_lb);
		
		// Sets options for the cURL command.
		curl_setopt($s_curl_lb, CURLOPT_URL, $s_url_lb);
		curl_setopt($s_curl_lb, CURLOPT_RETURNTRANSFER, true);
		
		// Sets an authentication header for cURL command.
		$headers = array(
		   "Authorization: Basic dG1mX3JlZHNoYWRvd19fX19fMjoxMjM0NQ==",
		);
		
		// Sets some more options for the cURL command!
		curl_setopt($s_curl_lb, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($s_curl_lb, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($s_curl_lb, CURLOPT_SSL_VERIFYPEER, false);
		
		// Executes cURL and decodes it's JSON response.
		$s_json_lb = curl_exec($s_curl_lb);
		$s_arr_lb = json_decode($s_json_lb, true);
		
		// Closes cURL.
		curl_close($s_curl_lb);
		
		// Parses the JSON response of "$s_arr_lb" into a clean list.
		echo "\n\n\n\x1b[37;1;4mSolo Leaderboard\x1b[0m \x1b[3m(".$path.")\x1b[0m\n";
		
		// This "for" loop creates as much players as there is "$length".
		for ($i = 0; $i < $length; $i++) {
			
			// Sets variables for the individual statistics.
			$rank = $s_arr_lb['players'][$i]['rank'];
			$login = $s_arr_lb['players'][$i]['player']['login'];
			$points = $s_arr_lb['players'][$i]['points'];
			
			// Sets width for the individual statistics.
			$rankWidth = 20;
			$pointsWidth = 20;
			$loginWidth = 20;
			
			// Pads each value to the fixed width above.
			$rankPad = str_pad($rank, $rankWidth, " ", STR_PAD_RIGHT);
			$pointsPad = str_pad($points." SP", $pointsWidth, " ", STR_PAD_RIGHT);
			$loginPad = str_pad($login, $loginWidth, " ", STR_PAD_RIGHT);
			
			// Combines all to create and print the string.
			$output = "\x1b[0m".$rankPad . "\x1b[33;1m".$pointsPad . "\x1b[0m".$loginPad . "\n";
			echo $output;
		}
		
		// Breaks the god gamer file :(
		break;

	// If "/m" is chosen...
	case "/m":
	
		// Open up an input field for the leaderboard path.
		echo "\x1b[37;1;4m\nInput the leaderboard path.\x1b[0m \x1b[3m(ex. World|France) \x1b[31;1m(If you have a path with spaces, replace them with %20!)\x1b[0m\n";
		$path = trim(fgets(STDIN));
		
		// Open up an input field for the link offset.
		echo "\x1b[37;1;4m\nInput the leaderboard offset.\x1b[0m \x1b[3m(ex. If offset is 10, the first player on the list will be 11th.) (Default: 0)\x1b[0m\n";
		$offset = trim(fgets(STDIN));
		
		// Open up an input field for the link length.
		echo "\x1b[37;1;4m\nInput the leaderboard length.\x1b[0m \x1b[3m(ex. If length is 5, there will be five players on the list.) (Default & Max: 10)\x1b[0m\n";
		$length = trim(fgets(STDIN));
		
		// Sets variable for the URL needed.
		$m_url_lb = "http://ws.trackmania.com/tmf/rankings/multiplayer/players/".$path."/?offset=".$offset."&length=".$length;

		// Sets variable for the cURL command.
		$m_curl_lb = curl_init($m_url_lb);
		
		// Sets options for the cURL command.
		curl_setopt($m_curl_lb, CURLOPT_URL, $m_url_lb);
		curl_setopt($m_curl_lb, CURLOPT_RETURNTRANSFER, true);
		
		// Sets an authentication header for cURL command.
		$headers = array(
		   "Authorization: Basic dG1mX3JlZHNoYWRvd19fX19fMjoxMjM0NQ==",
		);
		
		// Sets some more options for the cURL command!
		curl_setopt($m_curl_lb, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($m_curl_lb, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($m_curl_lb, CURLOPT_SSL_VERIFYPEER, false);
		
		// Executes cURL and decodes it's JSON response.
		$m_json_lb = curl_exec($m_curl_lb);
		$m_arr_lb = json_decode($m_json_lb, true);
		
		// Closes cURL.
		curl_close($m_curl_lb);
		
		// Parses the JSON response of "$m_arr_lb" into a clean list.
		echo "\n\n\n\x1b[37;1;4mMultiplayer Leaderboard\x1b[0m \x1b[3m(".$path.")\x1b[0m\n";
		
		// This "for" loop creates as much players as there is "$length".
		for ($i = 0; $i < $length; $i++) {
			
			// Sets variables for the individual statistics.
			$rank = $m_arr_lb['players'][$i]['rank'];
			$login = $m_arr_lb['players'][$i]['player']['login'];
			$points = $m_arr_lb['players'][$i]['points'];
			
			// Sets width for the individual statistics.
			$rankWidth = 20;
			$pointsWidth = 20;
			$loginWidth = 20;
			
			// Pads each value to the fixed width above.
			$rankPad = str_pad($rank, $rankWidth, " ", STR_PAD_RIGHT);
			$pointsPad = str_pad($points." LP", $pointsWidth, " ", STR_PAD_RIGHT);
			$loginPad = str_pad($login, $loginWidth, " ", STR_PAD_RIGHT);
			
			// Combines all to create and print the string.
			$output = "\x1b[0m".$rankPad."\x1b[33;1m".$pointsPad."\x1b[0m".$loginPad."\n";
			echo $output;
			}
		
		// Breaks the god gamer file :(
		break;

	// If "/c" is chosen...
	case "/c":

		// Open up an input field for the challenge UID.
		echo "\x1b[37;1;4m\nInput the challenge UID.\x1b[0m \x1b[3m(ex. KK8GilfJAgzqG_NFkyls9TAGiO3 (BayA1)) (You can find them using several GBX dumping tools.)\x1b[0m\n";
		$uid = trim(fgets(STDIN));

		// Open up an input field for the leaderboard path.
		echo "\x1b[37;1;4m\nInput the leaderboard path.\x1b[0m \x1b[3m(ex. World|France) \x1b[31;1m(If you have a path with spaces, replace them with %20!)\x1b[0m\n";
		$path = trim(fgets(STDIN));

		// Open up an input field for the link offset.
		echo "\x1b[37;1;4m\nInput the leaderboard offset.\x1b[0m \x1b[3m(ex. If offset is 10, the first player on the list will be 11th.) (Default: 0)\x1b[0m\n";
		$offset = trim(fgets(STDIN));
		
		// Open up an input field for the link length.
		echo "\x1b[37;1;4m\nInput the leaderboard length.\x1b[0m \x1b[3m(ex. If length is 5, there will be five players on the list.) (Default & Max: 10)\x1b[0m\n";
		$length = trim(fgets(STDIN));

		// Sets variable for the URL needed.
		$c_url_lb = "http://ws.trackmania.com/tmf/rankings/solo/challenges/".$uid."/players/".$path."/?offset=".$offset."&length=".$length;

		// Sets variable for the cURL command.
		$c_curl_lb = curl_init($c_url_lb);

		// Sets options for the cURL command.
		curl_setopt($c_curl_lb, CURLOPT_URL, $c_url_lb);
		curl_setopt($c_curl_lb, CURLOPT_RETURNTRANSFER, true);

		// Sets an authentication header for cURL command.
		$headers = array(
			"Authorization: Basic dG1mX3JlZHNoYWRvd19fX19fMjoxMjM0NQ==",
		);
		 
		// Sets some more options for the cURL command!
		curl_setopt($c_curl_lb, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($c_curl_lb, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($c_curl_lb, CURLOPT_SSL_VERIFYPEER, false);
		 
		// Executes cURL and decodes it's JSON response.
		$c_json_lb = curl_exec($c_curl_lb);
		$c_arr_lb = json_decode($c_json_lb, true);
		 
		// Closes cURL.
		curl_close($c_curl_lb);

		// Parses the JSON response of "$m_arr_lb" into a clean list.
		echo "\n\n\n\x1b[37;1;4mChallenge Leaderboard\x1b[0m \x1b[3m(".$uid.") (".$path.")\x1b[0m\n";
		
		// This "for" loop creates as much players as there is "$length".
		for ($i = 0; $i < $length; $i++) {
			
			// Sets variables for the individual statistics.
			$rank = $c_arr_lb['players'][$i]['rank'];
			$login = $c_arr_lb['players'][$i]['player']['login'];
			$points = $c_arr_lb['players'][$i]['points'];
			
			// Sets width for the individual statistics.
			$rankWidth = 20;
			$pointsWidth = 20;
			$loginWidth = 20;
			
			// Pads each value to the fixed width above.
			$rankPad = str_pad($rank, $rankWidth, " ", STR_PAD_RIGHT);
			$pointsPad = str_pad($points." SP", $pointsWidth, " ", STR_PAD_RIGHT);
			$loginPad = str_pad($login, $loginWidth, " ", STR_PAD_RIGHT);
			
			// Combines all to create and print the string.
			$output = "\x1b[0m".$rankPad . "\x1b[33;1m".$pointsPad . "\x1b[0m".$loginPad . "\n";
			echo $output;
			}
		
		// Breaks the god gamer file :(
		break;

}