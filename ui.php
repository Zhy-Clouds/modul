
const yt = "https://youtube.com/@zhy_08";

function get_ua() {
    $ver = "";

    // Method 1: pm dump - most accurate
    $cmds = [
        "pm dump com.android.chrome 2>/dev/null | grep -m1 versionName",
        "dumpsys package com.android.chrome 2>/dev/null | grep -m1 versionName",
        "pm dump com.chrome.beta 2>/dev/null | grep -m1 versionName",
        "pm dump com.google.android.webview 2>/dev/null | grep -m1 versionName",
    ];

    foreach ($cmds as $c) {
        $out = trim(shell_exec($c));
        if ($out && strpos($out, "versionName=") !== false) {
            $ver = trim(str_replace("versionName=", "", $out));
            break;
        }
        // dumpsys format: versionName=137.0.0.0
        if ($out && preg_match('/versionName=([0-9.]+)/', $out, $m)) {
            $ver = $m[1];
            break;
        }
    }

    // Method 2: fallback if pm fails
    if (!$ver) $ver = "137.0.0.0";

    // Google Reduced UA - same for all Android, only version is real
    return "Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/$ver Mobile Safari/537.36";
}

function wc() {
   system('clear');
   print str_repeat(c."═", 50).n;
   print m.'          ___                  _           _'.n;
   print m.'         /___\_   _ _ __ /\/\ (_)_ __   __| |'.n;
   print m.'        //  // | | | ‘__/    \| | ‘_ \ / _` |'.n;
   print p.'       / \_//| |_| | | / /\/\ \ | | | | (_| |'.n;
   print p.'       \___/  \__,_|_| \/    \/_|_| |_|\__,_|'.n.n;
   print str_repeat(c."═", 50).n; info(scr." ".ver);
   print pan1.p."Register: ".h.reg.n;
   print pan1.p."Captcha : ".h.tCap.n; l();
}

function ban() {
   $ap = json_decode(file_get_contents("http://ip-api.com/json"), 1);
   system('clear');
   if ($ap) {
      $tz = $ap["timezone"];
      date_default_timezone_set($tz);
      $hari = date('l'); $tanggal = date("d-M-Y"); $waktu = date("H:i");
      $panjang = 50-strlen($hari.$tanggal.$waktu); $spasi = floor($panjang/2);
      $lokasi = mp.$hari.str_repeat(" ", $spasi).$tanggal.str_repeat(" ", $spasi).$waktu;
      if ($panjang % 2 == 1) {
         $lokasi .= " ";
      }
      print $lokasi.n;
      print pm.str_pad($ap['city'].", ".$ap['regionName'].", ".$ap['country'], 50, " ", STR_PAD_BOTH).d.n;
      print b.str_repeat("═", 50); print n;
   } else {
      date_default_timezone_set("UTC");
   }
   print m.str_pad("_____         _____ _       _", 50, " ", STR_PAD_BOTH).n;
   print m.str_pad("|     |_ _ ___|     |_|___ _| |", 50, " ", STR_PAD_BOTH).n;
   print p.str_pad("|  |  | | |  _| | | | |   | . |", 50, " ", STR_PAD_BOTH).n;
   print p.str_pad("|_____|___|_| |_|_|_|_|_|_|___|", 50, " ", STR_PAD_BOTH).n;
   print b.str_repeat(o."─", 16).p." ❝ ".h."のスクリプト".p." ❞ ".str_repeat(o."─", 16).n;
   title(' '.scr); print n;
   print tb2."    \033[1;35mThanks To  :".n;
   print mp.str_pad("| Reyhan H | kakatoji |", 50, " ", STR_PAD_BOTH).d.n;
   print pm.str_pad("| IEWIL OFFICIAL | bintang catur |", 50, " ", STR_PAD_BOTH).d.n;
   print b.str_repeat("═", 16).c."元のバージョン ".ver.b.str_repeat("═", 16).n.d;
   system("termux-open-url ".yt); sleep(3);
}

function title($string) {
  $acssi = ["a" => ["┌─┐","├─┤","┴ ┴"],"b" => ["┌┐ ","├┴┐","└─┘"],"c" => ["┌─┐","│  ","└─┘"],"d" => ["┌┬┐"," ││","─┴┘"],"e" => ["┌─┐","├┤ ","└─┘"],"f" => ["┌─┐","├┤ ","└  "],"g" => ["┌─┐","│ ┬","└─┘"],"h" => ["┬ ┬","├─┤","┴ ┴"],"i" => ["┬","│","┴"],"j" => [" ┬"," │","└┘"],"k" => ["┬┌─","├┴┐","┴ ┴"],"l" => ["┬  ","│  ","┴─┘"],"m" => ["┌┬┐","│││","┴ ┴"],"n" => ["┌┐┌","│││","┘└┘"],"o" => ["┌─┐","│ │","└─┘"],"p" => ["┌─┐","├─┘","┴  "],"q" => ["┌─┐ ","│─┼┐","└─┘└"],"r" => ["┬─┐","├┬┘","┴└─"],"s" => ["┌─┐","└─┐","└─┘"],"t" => ["┌┬┐"," │ "," ┴ "],"u" => ["┬ ┬","│ │","└─┘"],"v" => ["┬  ┬","└┐┌┘"," └┘ "],"w" => ["┬ ┬","│││","└┴┘"],"x" => ["─┐ ┬","┌┴┬┘","┴ └─"],"y" => ["┬ ┬","└┬┘"," ┴ "],"z" => ["┌─┐","┌─┘","└─┘"]," "=>[" "," "," "],"1" => ["┬","│","┴"],  "2" => ["┌─┐","┌─┘","└─┘"],  "3" => ["┌─┐"," ├┤","└─┘"],"4" => ["┬ ┬","└─┤","  ┘"],"5" => ["┌─┐","└─┐","└─┘"],"6" => ["┌─┐","├─┐","└─┘"],"7" => ["┌─┐","  │","  ┘"],"8" => ["┌─┐","├─┤","└─┘"],"9" => ["┌─┐","└─┤","└─┘"],"0" => ["┌─┐","│ │","└─┘"]];

  $x = str_split($string); #print " ";
  foreach ($x as $data) {
    print o.$acssi[$data][0];
  }
  print " オンライン".n; #print " ";
  foreach ($x as $data) {
    print o2k.$acssi[$data][1];
  }
  print " クリエーター".bgb.p."Zhy_08".n; #print " ";
  foreach ($x as $data) {
    print k2o.$acssi[$data][2];
  }
  print " チャンネル  ".bgb.p."Zhy_08".n;
}

function cc() {
   status();
   pw:
   print str_repeat(p."—", 57).n;
   print tb2.p."         WARNING ⚠️".n;
   print str_repeat(p."—", 57).n;
   print pan2.mc."THIS SCRIPT IS FREE, NOT FOR SALE".n;
   print pan2.mc."You might be got banned, Do with your own RISK".n;
   print str_repeat(p."—", 57).n;

   $d = date("D");
   switch ($d) {
      case "Tue":
         $r = file_get_contents('https://pastebin.com/raw/UkgYPfKq');
         $link = explode('#', explode('Link: ', $r)[1])[0];
         $pw = explode(' #', explode('Pass: ', $r)[1])[0];
         $pwd = $pw;
         $read = file_get_contents("key.txt");
         sleep(1);
         if ($link == "") {
            print mc." Connection Lost, Please Check Your Connection\n"; exit;
         }
         if ($read == $pwd) {
            slow(r2.pan1.kcerah."Verifying Password!");
            sleep(2); z("Password Correct!", d);
            system("clear");
         } elseif ($read != $pwd) {
            $save = fopen("key.txt", "w");
            print pan.p."Link Password  : ".h.$link.n;
            print pan.p."Input Password : \033[1;32m";
            $p = trim(fgets(STDIN));
            if ($p == "") {
               print n.pan.mc."Input Password First!".n;
               sleep(2); system("clear"); goto pw;
            }
            print r."\n \033[1;97mChecking Password \033[1;31m▪\r";
            sleep(1);
            print r." \033[1;97mChecking Password \033[1;31m▪ \033[1;31m▪\r";
            sleep(1);
            print r." \033[1;97mChecking Password \033[1;31m▪ \033[1;31m▪ \033[1;31m▪ \r"; sleep(1);
            if ($pwd == $p) {
               fwrite($save, $p);
               z("Password Correct!", '     ');
               sleep(1); fclose($save);
               sleep(1); system("clear");
            } else {
               print x("Password Incorrect!", '     ');
               sleep(2); system("clear"); goto pw;
            }
         } break;
      case "Fri":
         $r = file_get_contents('https://pastebin.com/raw/rTR6vfiJ');
         $link = explode('#', explode('Link: ', $r)[1])[0];
         $pw = explode(' #', explode('Pass: ', $r)[1])[0];
         $pwd = $pw;
         $read = file_get_contents("key.txt");
         sleep(1);
         if ($link == "") {
            print mc." Connection Lost, Please Check Your Connection\n"; exit;
         }
         if ($read == $pwd) {
            slow(r2.pan1.kcerah."Verifying Password!");
            sleep(2); z("Password Correct!", d);
            system("clear");
         } elseif ($read != $pwd) {
            $save = fopen("key.txt", "w");
            print pan.p."Link Password  : ".h.$link.n;
            print pan.p."Input Password : \033[1;32m";
            $p = trim(fgets(STDIN));
            if ($p == "") {
               print n.pan.mc."Input Password First!".n;
               sleep(2); system("clear"); goto pw;
            }
            print r."\n \033[1;97mChecking Password \033[1;31m▪\r";
            sleep(1);
            print r." \033[1;97mChecking Password \033[1;31m▪ \033[1;31m▪\r";
            sleep(1);
            print r." \033[1;97mChecking Password \033[1;31m▪ \033[1;31m▪ \033[1;31m▪ \r"; sleep(1);
            if ($pwd == $p) {
               fwrite($save, $p);
               z("Password Correct!", '     ');
               sleep(1); fclose($save);
               sleep(1); system("clear");
            } else {
               print x("Password Incorrect!", '     ');
               sleep(2); system("clear"); goto pw;
            }
         } break;
      default:
         $r = file_get_contents('https://pastebin.com/raw/MusNk9Gm');
         $link = explode('#', explode('Link: ', $r)[1])[0];
         $pw = explode(' #', explode('Pass: ', $r)[1])[0];
         $pwd = $pw;
         $read = file_get_contents("key.txt");
         sleep(1);
         if ($link == "") {
            print mc." Connection Lost, Please Check Your Connection\n"; exit;
         }
         if ($read == $pwd) {
            slow(r2.pan1.kcerah."Verifying Password!");
            sleep(2); z("Password Correct!", d);
            system("clear");
         } elseif ($read != $pwd) {
            $save = fopen("key.txt", "w");
            print pan.p."Link Password  : ".h.$link.n;
            print pan.p."Input Password : \033[1;32m";
            $p = trim(fgets(STDIN));
            if ($p == "") {
               print n.pan.mc."Input Password First!".n;
               sleep(2); system("clear"); goto pw;
            }
            print r."\n \033[1;97mChecking Password \033[1;31m▪\r";
            sleep(1);
            print r." \033[1;97mChecking Password \033[1;31m▪ \033[1;31m▪\r";
            sleep(1);
            print r." \033[1;97mChecking Password \033[1;31m▪ \033[1;31m▪ \033[1;31m▪ \r";
            sleep(1);
            if ($pwd == $p) {
               fwrite($save, $p);
               z("Password Correct!", '     ');
               sleep(1); fclose($save);
               sleep(1); system("clear");
            } else {
               print x("Password Incorrect!", '     ');
               sleep(2); system("clear"); goto pw;
            }
         } break;
   }
}

function cok($cf = 0) {
   if ($cf) {
       // termux-open-url dimatiin
      print r.pan1.p."Paste on your browser:\n";
      print r.pan2.h.$web.n.d;
      cetak(r2.mc."Cloudflare Detected, Update Your Cookie".d, 15000); print n;
      unlink('cookie.txt'); unlink("Cookie"); sleep(1);
      $p = input("Press ENTER to continue");
      if ($p == "ENTER") {
         return 0;
      }system('clear');
   } else {
      cetak(r2.mc."Cookie Expired, Update Your Cookie".d, 15000); print n;
      unlink('cookie.txt'); unlink("Cookie"); sleep(1);
      // $p = input("Press ENTER to continue");
      //       if ($p == "ENTER") {
      //          return 0;
      //       }system('clear');
   }
}


/* function cok - versi lama, dijadiin komen biar gak redeclare
function cok($web, $cf = 0) {
   if ($cf) {
       // termux-open-url dimatiin
      print r.pan1.p."Paste on your browser:\n";
      print r.pan2.h.$web.n.d;
      cetak(r2.mc."Cloudflare Detected, Update Your Cookie".d, 15000); print n;
      unlink('cookie.txt'); unlink("Cookie"); sleep(1);
      $p = input("Press ENTER to continue");
      if ($p == "ENTER") {
         return 0;
      }system('clear');
   } else {
      cetak(r2.mc."Cookie Expired, Update Your Cookie".d, 15000); print n;
      unlink('cookie.txt'); unlink("Cookie"); sleep(1);
    #  $p = input("Press ENTER to continue");
  #    if ($p == "ENTER") {
    #     return 0;
   #   }system('clear');
   }
}

/* 
*/