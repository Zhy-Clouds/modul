
function timer($t) {
   static $udah = false;
   if (!$udah) { date_default_timezone_set("UTC"); $udah = true; }
   print r;
   $timr = time()+$t;
   while (true):
   $res = $timr-time();
   if ($res < 1) {
      break;
   }
   print mc."\t 次のクレーム ".date("i", $res).abuglp.":".p.date("s", $res)." 請求する秒数 \r".d; sleep(1); endwhile;
}
function input($msg, $fgets = 0) {
   echo c."╭{  ".kcerah."Input ".$msg.c."  }".n.c."╰┈➤ ".h;
   if ($fgets == 1) return trim(fgets(STDIN));
   if (function_exists("readline")) {
      $x = readline("");
      if ($x !== false) return trim($x);
   }
   return trim(fgets(STDIN));
}
function save($new, $univ = 0) {
   $path = $univ ? "../$new" : $new;
   if (file_exists($path)) {
      $cfg = trim(file_get_contents($path));
      if ($cfg !== "") return $cfg;
   }
   do {
      $cfg = trim(input($new)); print n;
      if ($cfg === "") echo "Jangan kosong bre, isi dulu!".n;
   } while ($cfg === "");
   file_put_contents($path, $cfg);
   return $cfg;
}
function menu($no, $title) {
   print kb.p."$no".kt.u." $title".d.n;
}
function z($z, $l, $cetak = 0) {
   $lenstr = max(0, 8 - strlen($z));
   if ($cetak) return cetak(r2.bp."[✓]".d." ".p.$z.str_repeat(" ", $lenstr).sd.h.$l.d, 30000);
   return slow(r2.p."[".h."✓".p."]".d." ".h.$z.$l.d);
}
function x($x, $l) {
   return r.p."[".m."!".p."] ".m.$x.$l.d;
}

# ===== Cara Pakai ===== #

// baris(0, "Balance", "100");
// baris(1, "Profit", "+10");
// baris(2, "Fee", "-1");

// baris("ori", "Balance", "100");
// baris("pls", "Profit", "+10");
// baris("krg", "Fee", "-1");

function baris($tipe, $b, $v, $tab = 0) {
   if ($tipe == 1 || $tipe == "pls" || $tipe == "plus" || $tipe == "+") $simbol = "+";
   elseif ($tipe == 2 || $tipe == "krg" || $tipe == "min" || $tipe == "-") $simbol = "-";
   else $simbol = "≠";
   $len = $tab ? 10 : 8;
   return slow(r2.bp."[".$simbol."]".d." ".p.$b.str_repeat(" ", max(0, $len - strlen($b))).sd.h.$v.d);
}
function t($label, $msg, $tab = 0) {
   $len = $tab ? 10 : 8;
   return $label.str_repeat(" ", max(0, $len - strlen($label))).sd.$msg.d;
}
function limit($msg, $coin, $sd = 0) {
   $lenstr = max(0, 19 - strlen($msg));
   $warna = $sd ? $coin : sd.$coin;
   return r.p."[".m."!".p."] ".mc.$msg.str_repeat(" ", $lenstr).$warna.d;
}
function slow($str) {
   foreach (mb_str_split($str) as $rt) { echo $rt; usleep(30000); }
   print n;
}
function cetak($str, $t) {
   foreach (mb_str_split($str) as $c) { echo $c; usleep($t); }
}
function email($encodedString){
   $k = hexdec(substr($encodedString, 0, 2));
   for ($i = 2, $email = ''; $i < strlen($encodedString); $i += 2) {
      $email .= chr(hexdec(substr($encodedString, $i, 2)) ^ $k);
   }
   return $email;
}
function uf() {
   return md5(uniqid(mt_rand(), true));
}
function space($teks, $msg) {
   return $teks.str_repeat(" ", max(0, 15 - strlen($teks))).p."| ".$msg.d;
}
function his($newdata, $data = []) {
   return array_merge($data, $newdata);
}
function akhir($bal = 0) {
   return $bal ? c." added to your account".d : c." sent to your FaucetPay".d;
}
function info($msg) {
   print (mp.str_pad(strtoupper($msg), 50, " ", STR_PAD_BOTH).d.n); l();
}
function l() {
   print p.str_repeat("─", 50).d.n;
}