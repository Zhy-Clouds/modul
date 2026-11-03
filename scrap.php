function cfw($source) {
   (preg_match('/logout/i', $source))? $data['out'] = true:$data['out'] = false;
   (preg_match('/just a moment.../i', $source))? $data['cf'] = true:$data['cf'] = false;
   (preg_match('/firewall/i', $source))? $data['fw'] = true:$data['fw'] = false;

   $csrf = explode('"', explode('name="csrf_token_name" id="token" value="', $source)[1])[0];
   $tk = explode('"', explode('name="token" value="', $source)[1])[0];
   $token = explode("'", explode("name='_iconcaptcha-token' value='", $source)[1])[0];
   $rv2 = explode('"', explode('class="g-recaptcha" data-sitekey="', $source)[1])[0];
   $ts = explode('"', explode('class="cf-turnstile" data-sitekey="', $source)[1])[0];
   $hc = explode('"', explode('class="h-captcha" data-sitekey="', $source)[1])[0];
   if ($csrf) {
      $data['csrf'] = $csrf;
   }
   if ($tk) {
      $data['tk'] = $tk;
   }
   if ($token) {
      $data['token'] = $token;
   }
   if ($rv2) {
      $data['rv2'] = $rv2;
   }
   if ($ts) {
      $data['ts'] = $ts;
   }
   if ($hc) {
      $data['hc'] = $hc;
   }
   return $data;
}
class HtmlScrap {
   protected $captcha;
   protected $input;
   protected $limit;
   protected $option;

   function __construct() {
      $this->captcha = '/class=["\']([^"\']+)["\'][^>]*data-sitekey=["\']([^"\']+)["\'][^>]*>/i';
      $this->input = '/<input[^>]*name=["\'](.*?)["\'][^>]*value=["\'](.*?)["\'][^>]*>/i';
      $this->limit = '/(\d{1,})\/(\d{1,})/';
      $this->option = '/<option\s+value=["\']([^"\']+)["\'][^>]*>/i';
   }

   private function scrap($pattern, $html) {
      preg_match_all($pattern, $html, $matches);
      return $matches;
   }

   private function getCaptcha($html) {
      $data = []; // Inisialisasi untuk menghindari undefined
      $scrap = $this->scrap($this->captcha, $html);
      for ($i = 0; $i < count($scrap[1]); $i++) {
         $data[$scrap[1][$i]] = $scrap[2][$i];
      }
      return $data;
   }

   private function getOptionValues($html) {
      $data = [];
      $scrap = $this->scrap($this->option, $html);
      foreach ($scrap[1] as $val) {
         $data[] = $val;
      }
      return $data;
   }

   private function getInput($html, $form = 1) {
      $data = [];
      $forms = explode('<form', $html);
      if (!isset($forms[$form])) {
         return $data; // form tidak ditemukan
      }
      $formContent = $forms[$form];
      $scrap = $this->scrap($this->input, $formContent);
      for ($i = 0; $i < count($scrap[1]); $i++) {
         $data[$scrap[1][$i]] = $scrap[2][$i];
      }
      return $data;
   }

   public function Result($html, $form = 1) {
      $data = []; // ← inisialisasi untuk menghindari warning

      // Title
      if (str_contains($html, '<title>')) {
         $titlePart = explode('<title>', $html);
         if (isset($titlePart[1])) {
            $data['title'] = explode('</title>', $titlePart[1])[0] ?? '';
         }
      } else {
         $data['title'] = '';
      }

      $data['cloudflare'] = str_contains($html, 'Just a moment...');
      $data['firewall'] = str_contains($html, 'Firewall');
      $data['locked'] = str_contains($html, 'Locked');
      $data['captcha'] = $this->getCaptcha($html);
      $data['options'] = $this->getOptionValues($html);

      // Input
      $input = $this->getInput($html, $form);
      $data['input'] = !empty($input) ? $input : $this->getInput($html, 2);
      $data['faucet'] = $this->scrap($this->limit, $html);

      // Success Message
      $data["response"] = [
         "success" => false,
         "warning" => null,
         "unset" => false,
         "exit" => false
      ];

      if (str_contains($html, "icon: 'success',")) {
         $suksesPart = explode("icon: 'success',", $html);
         if (isset($suksesPart[1])) {
            $successHtml = explode("html: '", $suksesPart[1])[1] ?? null;
            if ($successHtml) {
               $data["response"]["success"] = strip_tags(explode("'", $successHtml)[0] ?? '');
            }
         }
      } else {
         $warning = null;

         if (str_contains($html, "html: '")) {
            $warningPart = explode("html: '", $html);
            $warning = explode("'", $warningPart[1])[0] ?? null;
         }

         $data["response"]["warning"] = "Not Found"; // default
         $data["response"]["exit"] = false;
         $data["response"]["unset"] = false;

         if (str_contains($html, 'Your account')) {
            $ban = explode('</div>', explode('<div class="alert text-center alert-danger"><i class="fas fa-exclamation-circle"></i> Your account', $html)[1] ?? '')[0] ?? false;
            if ($ban) {
               $data["response"]["warning"] = $ban;
               $data["response"]["exit"] = true;
            }
         } elseif (str_contains($html, 'invalid amount')) {
            $data["response"]["warning"] = "You are sending an invalid amount";
            $data["response"]["unset"] = true;
         } elseif (str_contains($html, 'Invalid API Key used')) {
            $data["response"]["warning"] = "Invalid API Key used";
            $data["response"]["unset"] = true;
         } elseif (str_contains($html, 'Shortlink in order to claim from the faucet!') ||
            str_contains($html, 'Shortlink must be completed')) {
            $data["response"]["warning"] = $warning ?? "Shortlink required";
            $data["response"]["exit"] = true;
         } elseif (str_contains($html, 'sufficient funds')) {
            $data["response"]["warning"] = "Sufficient funds";
            $data["response"]["unset"] = true;
         } elseif (str_contains($html, 'Daily claim limit')) {
            $data["response"]["warning"] = "Daily claim limit";
            $data["response"]["unset"] = true;
         } elseif ($warning) {
            $data["response"]["warning"] = $warning;
         }
      }

      return $data;
   }
}

// ============================================================
// [REASON HANDLER] - Buat nampung alasan kenapa coin ke-skip
// File: scrap.php
// Cara pake:
//  1. File ini harus di-require di file utama, contoh:
//     require_once('/storage/emulated/0/Zhy/scrap.php');
//  2. Di dalam loop faucet, tiap dapet $r (html), cek dulu:
//     if($reason = get_reason($r)){ bye($coins, $a, $reason); continue; }
//  3. Di akhir, pas semua coin abis, baru print pesannya 1x:
//     print x($pesan_all[$last_reason] ?? $pesan_all['daily'], n);
// JANGAN print di dalam bye(), nanti ngeprint berkali-kali!
// ============================================================

// $last_reason = alasan TERAKHIR kenapa coin kehapus.
// Default 'daily', biar kalau loop kelar tanpa alasan jelas, tetep ada pesan.
// Variabel ini GLOBAL, jadi harus dipanggil pake 'global $last_reason' di fungsi lain.
$last_reason = 'daily';

// $pesan_all = kamus pesan yang ditampilin ke user.
// Key-nya HARUS sama persis sama return dari get_reason().
// 'shortlink' => user disuruh kelarin shortlink dulu
// 'daily'     => jatah claim harian abis
// 'funds'     => faucetnya lagi bokek / funds abis
// 'banned'    => akun ke-ban / suspend
$pesan_all = [
    'shortlink' => 'Complete Atleast 1 Shortlinks to claim more',
    'daily' => 'You have reached daily claim limit',
    'funds' => 'Sufficient funds',
    'banned' => 'Your account banned',
];

// get_reason($r)
// Fungsi buat nebak alasan dari isi HTML ($r).
// Input : $r = html mentah dari run(host.'faucet/...') atau run(host.'faucet/verify')
// Output: string key ('banned' / 'shortlink' / 'daily' / 'funds') atau '' (string kosong)
// Kalau return '' berarti BUKAN error limit/ban, jadi LANJUT claim kayak biasa.
// Cara kerja: html dibersihin dulu (strip_tags + strtolower) biar gampang dicari,
// terus dicek satu-satu pake str_contains().
// URUTAN PENTING: 'banned' dicek paling pertama, biar gak ketuker sama yang lain.
function get_reason($r){
    global $sl_needed;
    $sl_needed = 0;
    $low = strtolower($r);

    // 1. CEK SUKSES DULU, PALING PENTING!
    // Kalau udah ada kata sent / claimed / success, jangan di cap shortlink lagi
    // Ini yang bikin lu muter-muter, padahal di web udah bisa claim
    if (str_contains($low, 'sent to your') || str_contains($low, 'successfully claimed') || str_contains($low, 'has been sent') || str_contains($low, 'claim successful') || str_contains($low, 'successfully sent')) {
        return false; // false = gak ada halangan, langsung claim / langsung beres
    }

    // 2. CEK SHORTLINK YANG BENERAN NGAMBAT, BUKAN CUMA ADA KATA NYA DOANG
    // Harus ada kalimat yang nyuruh: "you need to complete", "must complete", "complete ... to claim", "required"
    $is_blocked_by_sl = false;
    if (preg_match('/you need to complete.*?short\s*links?/is', $low)) $is_blocked_by_sl = true;
    if (preg_match('/must complete.*?short\s*links?/is', $low)) $is_blocked_by_sl = true;
    if (preg_match('/complete.*?short\s*links?.*?to (claim|continue|unlock)/is', $low)) $is_blocked_by_sl = true;
    if (preg_match('/please complete.*?short\s*links?/is', $low)) $is_blocked_by_sl = true;
    if (preg_match('/short\s*links?\s*(required|needed)/is', $low)) $is_blocked_by_sl = true;
    if (preg_match('/you have to complete.*?short/is', $low)) $is_blocked_by_sl = true;

    if ($is_blocked_by_sl) {
        $clean = str_ireplace(['99faucet', '99 faucet'], 'faucet', $r);
        $clean = preg_replace('/https?:\/\/[^\s"\'<>]+/i', ' ', $clean);
        $clean = strip_tags($clean);
        $clean = preg_replace('/\s+/', ' ', $clean);

        if (preg_match('/(?:complete|need|do|finish)\D{0,20}?(\d+)\s*(more\s+)?short\s*links?/i', $clean, $m)) {
            $sl_needed = (int)$m[1];
        }
        else if (preg_match('/(\d+)\s*short\s*links?\s*(remaining|left|to\s+complete|more)/i', $clean, $m)) {
            $sl_needed = (int)$m[1];
        }
        else if (preg_match('/(\d+)\s*\/\s*(\d+)\s*short/i', $clean, $m)) {
            $sl_needed = (int)$m[2] - (int)$m[1];
            if ($sl_needed < 0) $sl_needed = (int)$m[2];
        }
        if ($sl_needed == 99) $sl_needed = 0;

        return 'shortlink';
    }

    // Kalau cuma ada kata shortlink di menu / footer doang, BUKAN di cap shortlink
    // Lanjut ke cek yang lain
    if (str_contains($low, 'daily claim') || str_contains($low, 'daily limit') || str_contains($low, 'reached daily') || str_contains($low, 'daily claim limit')) {
        return 'daily';
    }
    if (str_contains($low, 'banned') || str_contains($low, 'you are banned') || str_contains($low, 'account banned')) {
        return 'banned';
    }
    if (str_contains($low, 'insufficient funds') || str_contains($low, 'not enough funds') || str_contains($low, 'faucet empty') || str_contains($low, 'insufficient balance')) {
        return 'funds';
    }
    if (str_contains($low, 'rate-limited') || str_contains($low, 'rate limited') || str_contains($low, 'too many requests') || str_contains($low, 'try again later')) {
        return 'rate-limited';
    }

    return false;
}


function bye(&$coins, $a, $reason){
    // &$coins = PAKE & BRE, INI PENTING, BIAR UNSET NYA BENERAN NGEHAPUS DI fct() NYA, GAK NGELOOP
    // $a = key / index coin nya, misal 0 buat LTC, 1 buat BTC, dll
    // $reason = alasan nya, yang di kirim dari get_reason(), misal 'shortlink' / 'daily' / 'banned'
    // $sl_needed = angka SL yang udah di tangkep di get_reason() tadi
    global $last_reason, $pesan_all, $sl_needed;

    // $coin = nama coin nya, misal 'ltc', kalau gak ketemu pake '???' biar gak error
    $coin = $coins[$a] ?? '???';
    // $coin_up = versi gede nya, misal 'ltc' jadi 'LTC', biar print nya cakep
    $coin_up = strtoupper($coin);

    // ===== PRINT SESUAI REASON NYA, LANGSUNG PAKE $reason, JANGAN CEK ULANG DARI HTML =====
    // Ini yang bikin kemaren ketuker jadi daily terus, soalnya nge cek ulang dari HTML
    if ($reason == 'shortlink') {
        // Kalau shortlink, cek ada angka nya gak
        // Kalau ada, print pake angka: "LTC : You need to complete 5 shortlink"
        if (!empty($sl_needed) && $sl_needed > 0) {
            print x("$coin_up : You need to complete $sl_needed shortlink", n);
        } else {
            // Kalau angka nya gak ketemu (regex nya gak kena), print biasa aja biar gak error
            // "LTC : You need to complete shortlink"
            print x("$coin_up : You need to complete shortlink", n);
        }
    } else if ($reason == 'daily') {
        // Kalau daily, berarti jatah hari ini abis
        print x("$coin_up : You have reached daily claim limit", n);
    } else if ($reason == 'banned') {
        // Kalau banned
        print x("$coin_up : You are banned", n);
    } else if ($reason == 'funds') {
        // Kalau saldo faucet abis
        print x("$coin_up : Insufficient funds", n);
    } /* else if ($reason == 'rate-limited') {
        // Kalau kecepetan claim nya
        print x("$coin_up : You have been rate-limited", n);
    } */ else {
        // Kalau alasan nya aneh-aneh, yang gak ada di atas, print mentah aja
        print x("$coin_up : $reason", n);
    }

    // Simpen reason terakhir ke global, biar bisa di baca di tempat lain kalau perlu
    $last_reason = $reason;
    // Simpen ke $pesan_all, biar nanti di akhir bisa di rekap semua coin yang gagal
    $pesan_all[] = "$coin_up : $reason";
    // Hapus coin ini dari list $coins, biar gak di coba lagi di looping berikut nya
    // Ini beneran ke hapus soalnya $coins nya pake &, bukan copy an
    unset($coins[$a]);
}


/*class HtmlScrap {
   function __construct() {
      $this->captcha = '/class=["\']([^"\']+)["\'][^>]*data-sitekey=["\']([^"\']+)["\'][^>]*>/i';
      $this->input = '/<input[^>]*name=["\'](.*?)["\'][^>]*value=["\'](.*?)["\'][^>]*>/i';
      $this->limit = '/(\d{1,})\/(\d{1,})/';
   }
   private function scrap($pattern, $html) {
      preg_match_all($pattern, $html, $matches); return $matches;
   }
   private function getCaptcha($html) {
      $scrap = $this->scrap($this->captcha, $html); for ($i = 0; $i < count($scrap[1]); $i++) {
         $data[$scrap[1][$i]] = $scrap[2][$i];
      }return $data;
   }
   private function getInput($html, $form = 1) {
      $form = explode('<form', $html)[$form]; $scrap = $this->scrap($this->input, $form); for ($i = 0; $i < count($scrap[1]); $i++) {
         $data[$scrap[1][$i]] = $scrap[2][$i];
      }return $data;
   }
   public function Result($html, $form = 1) {
      $data['title'] = explode('</title>', explode('<title>', $html)[1])[0];
      $data['cloudflare'] = (preg_match('/Just a moment.../', $html))? true:false;
      $data['firewall'] = (preg_match('/Firewall/', $html))? true:false;
      $data['locked'] = (preg_match('/Locked/', $html))? true:false;
      $data["captcha"] = $this->getCaptcha($html);

      $input = $this->getInput($html, $form);
      $data["input"] = ($input)? $input:$this->getInput($html, 2);
      $data["faucet"] = $this->scrap($this->limit, $html);

      $sukses = explode("icon: 'success',", $html)[1];
      if ($sukses) {
         $data["response"]["success"] = strip_tags(explode("'", explode("html: '", $sukses)[1])[0]);
      } else {
         $warning = explode("'", explode("html: '", $html)[1])[0];
         $ban = explode('</div>', explode('<div class="alert text-center alert-danger"><i class="fas fa-exclamation-circle"></i> Your account', $html)[1])[0];
         $invalid = (preg_match('/invalid amount/', $html))? "You are sending an invalid amount":false;
         $shortlink = (preg_match('/Shortlink in order to claim from the faucet!/', $html))? $warning:false;
         $sufficient = (preg_match('/sufficient funds/', $html))? "Sufficient funds":false;
         $daily = (preg_match('/Daily claim limit/', $html))? "Daily claim limit":false;
         $data["response"]["unset"] = false;
         $data["response"]["exit"] = false;
         if ($ban) {
            $data["response"]["warning"] = $ban;
            $data["response"]["exit"] = true;
         } elseif ($invalid) {
            $data["response"]["warning"] = $invalid;
            $data["response"]["unset"] = true;
         } elseif ($shortlink) {
            $data["response"]["warning"] = $shortlink;
            $data["response"]["exit"] = true;
         } elseif ($sufficient) {
            $data["response"]["warning"] = $sufficient;
            $data["response"]["unset"] = true;
         } elseif ($warning) {
            $data["response"]["warning"] = $warning;
         } else {
            $data["response"]["warning"] = "Not Found";
         }
      }
      return $data;
   }
}*/