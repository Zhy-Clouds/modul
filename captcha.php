class Captcha {
   public $provider_api;
   private $url,
   $key,
   $provider,
   $function;
   public function __construct() {
      wc();
      menu(1, "Xevil");
      menu(2, "Multibot"); l();
      print r.pan2.k."ENTER = ".h.rc.n;
      $pil = input("Provider Apikey"); l();
      if ($pil == 1) {
         $this->url = 'https://sctg.xyz/';
         print pan2.p."Register".sd.h.'t.me/Xevil_check_bot?start=2124530010'.d.n;
         $this->key = save("Xevil_Apikey", 1)."|SOFTID2124530010";
         $this->provider_api = "Xevil";
      } elseif ($pil == 2) {
         $this->url = 'http://api.multibot.in/';
         print pan2.p."Register".sd.h.'https://multibot.in/'.d.n;
         $this->key = save("Multibot_Apikey", 1);
         $this->provider_api = "Multibot";
      } else {
         if (rc == "[Xevil]") {
            $this->url = 'https://sctg.xyz/';
            print pan2.p."Register".sd.h.'t.me/Xevil_check_bot?start=2124530010'.d.n;
            $this->key = save("Xevil_Apikey", 1)."|SOFTID2124530010";
            $this->provider_api = "Xevil";
         } else {
            $this->url = 'http://api.multibot.in/';
            print pan2.p."Register".sd.h.'https://multibot.in/'.d.n;
            $this->key = save("Multibot_Apikey", 1);
            $this->provider_api = "Multibot";
         }
      }
   }
   private function in_api($content, $method, $header = 0) {
      $param = "key=".$this->key."&json=1&".$content; if ($method == "GET")return json_decode(file_get_contents($this->url.'in.php?'.$param), 1); $opts['http']['method'] = $method; if ($header) $opts['http']['header'] = $header; $opts['http']['content'] = $param; return file_get_contents($this->url.'in.php', false, stream_context_create($opts));
   }
   private function res_api($api_id) {
      $params = "?key=".$this->key."&action=get&id=".$api_id."&json=1"; return json_decode(file_get_contents($this->url."res.php".$params), 1);
   }
   private function getResult($data, $method, $header = 0) {
      $cap = $this->filter(explode('&', explode("method=", $data)[1])[0]);
      $get_res = $this->in_api($data, $method, $header);
      if (is_array($get_res)) {
         $get_in = $get_res;
      } else {
         $get_in = json_decode($get_res, 1);
      }
      if (!$get_in["status"]) {
         $msg = $get_in["request"];
         if ($msg) {
            print r.p."[".m."!".p."] ".o.t($this->provider_api, mc.$msg.d.n);
         } elseif ($get_res) {
            print x($get_res, n);
         } else {
            print r.p."[".m."!".p."] ".o.t($this->provider_api, mc."Something went wrong".n);
         }return 0;
      }
      while (true) {
         $get_res = $this->res_api($get_in["request"]);
         print r.k."Solving $cap."; usleep(300000);
         print r.k."Solving $cap.."; usleep(300000);
         if ($get_res["request"] == "CAPCHA_NOT_READY") {
            print r.k."Solving $cap...";
            sleep(rand(3, 5)); continue;
         }
         if ($get_res["status"]) {
            print r.h."$cap Bypassed"; usleep(400000);
            return $get_res["request"];
         }
         print r.p."[".m."!".p."] ".o.t($this->provider_api, mc.$get_res["request"].n);
         sleep(5); return 0;
      }
   }
   private function filter($method) {
      if ($method == "userrecaptcha")return "RecaptchaV2"; if ($method == "hcaptcha")return "Hcaptcha"; if ($method == "turnstile")return "Turnstile"; if ($method == "universal" || $method == "base64")return "Ocr"; if ($method == "antibot")return "Antibot"; if ($method == "authkong")return "Authkong"; if ($method == "teaserfast")return "Teaserfast";
   }
   public function getBalance() {
      $res = json_decode(file_get_contents($this->url."res.php?action=userinfo&key=".$this->key), 1); return $res["balance"];
   }
   public function RecaptchaV2($sitekey, $pageurl) {
      $data = http_build_query(["method" => "userrecaptcha", "sitekey" => $sitekey, "pageurl" => $pageurl]); return $this->getResult($data, "GET");
   }
   public function Hcaptcha($sitekey, $pageurl) {
      $data = http_build_query(["method" => "hcaptcha", "sitekey" => $sitekey, "pageurl" => $pageurl]); return $this->getResult($data, "GET");
   }
   public function Turnstile($sitekey, $pageurl) {
      $data = http_build_query(["method" => "turnstile", "sitekey" => $sitekey, "pageurl" => $pageurl]); return $this->getResult($data, "GET");
   }
   public function Authkong($sitekey, $pageurl) {
      $data = http_build_query(["method" => "authkong", "sitekey" => $sitekey, "pageurl" => $pageurl]); return $this->getResult($data, "GET");
   }
   public function Ocr($img) {
      if ($this->provider_api == "Xevil") {
         $data = "method=base64&body=".$img;
      } else {
         $data = http_build_query(["method" => "universal", "body" => $img]);
      }return $this->getResult($data, "POST");
   }
   public function AntiBot($source) {
      $main = explode('"', explode('data:image/png;base64,', explode('Bot links', $source)[1])[1])[0];
      if (!$main)return 0;
      if ($this->provider_api == "Xevil") {
         $data = "method=antibot&main=$main";
      } else {
         $data["method"] = "antibot"; $data["main"] = $main;
      }
      $src = explode('rel=\"', $source);
      foreach ($src as $x => $sour) {
         if ($x == 0)continue;
         $no = explode('\"', $sour)[0];
         if ($this->provider_api == "Xevil") {
            $img = explode('\"', explode('data:image/png;base64,', $sour)[1])[0]; $data .= "&$no=$img";
         } else {
            $img = explode('\"', explode('src=\"', $sour)[1])[0]; $data[$no] = $img;
         }
      }
      if ($this->provider_api == "Xevil") {
         $res = $this->getResult($data, "POST");
      } else {
         $data = http_build_query($data); $ua = "Content-type: application/x-www-form-urlencoded"; $res = $this->getResult($data, "POST", $ua);
      }
      if ($res)return "+".str_replace(",", "+", $res); return 0;
   }
   public function Teaserfast($Display, $small) {
      if ($this->provider_api == "Multibot") {
         return ["error" => true,
            "msg" => "not support key!"];
      }$data = http_build_query(["method" => "teaserfast", "Display_photo" => $Display, "task" => $small]); $ua = "Content-type: application/x-www-form-urlencoded"; return $this->getResult($data, "POST", $ua);
   }
   public function bala($api, $dot = 0) {
      if ($dot) {
         return slow(cb.p.t("Apikey_Bal", k."𓊈𒆜 ".h.self::getBalance().k." 𒆜𓊉", 1)); l();
      }
      return slow(cb.p.t("Api_Bal", k."𓊈𒆜 ".h.self::getBalance().k." 𒆜𓊉".d)); l();
   }
   public function bal($dot = 0) {
      if ($dot) {
         return slow(cb.p.t("Apikey_Bal", k."𓊈𒆜 ".h.self::getBalance().k." 𒆜𓊉", 1)); l();
      }
      return slow(cb.p.t("Api_Bal", k."𓊈𒆜 ".h.self::getBalance().k." 𒆜𓊉".d)); l();
   }
}

class FreeCaptcha {
   static function Antibot($source) {
      $src = explode('rel=\"', $source);
      print_r($src); exit;
   }
   static function widgetId() {
      $uuid = '';
      for ($n = 0; $n < 32; $n++) {
         if ($n == 8 || $n == 12 || $n == 16 || $n == 20) {
            $uuid .= '-';
         }
         $e = mt_rand(0, 15);

         if ($n == 12) {
            $e = 4;
         } elseif ($n == 16) {
            $e = ($e & 0x3) | 0x8;
         }
         $uuid .= dechex($e);
      }
      return $uuid;
   }
   static function iconBypass($token, $icon_header, $theme = "light", $sub = "icaptcha/req") {

      $retry = 0;

      $icon_header[] = "origin: ".host;
      $icon_header[] = "referer: ".host;
      $icon_header[] = "x-iconcaptcha-token: ".$token;
      $icon_header[] = "x-requested-with: XMLHttpRequest";

      bypass_icon:
      if ($retry == 10)return;
      $timestamp = round(microtime(true) * 1000);
      $initTimestamp = $timestamp - 2000;
      $widgetID = self::widgetId();

      $data = ["payload" =>
         base64_encode(json_encode([
            "widgetId" => $widgetID,
            "action" => "LOAD",
            "theme" => $theme,
            "token" => $token,
            "timestamp" => $timestamp,
            "initTimestamp" => $initTimestamp
         ]))
      ];
      sleep(2);
      print "\r                        \r";
      print "---[1] Bypass....";
      $r = json_decode(base64_decode(Requests::post(host.$sub, $icon_header, $data)[1]), 1);
      $retry++;
      if (isset($r["challenge"])) {
         $base64Image = $r["challenge"];
         $challengeId = $r["identifier"];
      } else {
         sleep(2);
         print "\r                        \r";
         print "---[x] load img failed";
         sleep(2);
         print "\r                        \r";
         goto bypass_icon;
      }
      $captcha = [20,
         60,
         100,
         140,
         180,
         220,
         260,
         300];
      foreach ($captcha as $x) {
         $timestamp = round(microtime(true) * 1000);
         $initTimestamp = $timestamp - 2000;
         $data = ["payload" =>
            base64_encode(json_encode([
               "widgetId" => $widgetID,
               "challengeId" => $challengeId,
               "action" => "SELECTION",
               "x" => $x,
               "y" => 24,
               "width" => 320,
               "token" => $token,
               "timestamp" => $timestamp,
               "initTimestamp" => $initTimestamp
            ]))
         ];
         sleep(2);
         print "\r                        \r";
         print "---[2] Bypass..";
         $r = json_decode(base64_decode(Requests::post(host.$sub, $icon_header, $data)[1]), 1);

         if (($r['completed'] ?? false) === false) {
            sleep(2);
            print "\r                        \r";
            print "---[3] Bypass failed";
            sleep(2);
            print "\r                        \r";
            continue;
         } elseif (isset($r['completed'])) {
            break;
         } else {
            goto bypass_icon;
         }
      }
      sleep(2);
      print "\r                        \r";
      print "---[3] Bypass success";
      sleep(2);
      print "\r                        \r";
      $data = [];
      $data['captcha'] = "icaptcha";
      $data['_iconcaptcha-token'] = $token;
      $data['ic-rq'] = 1;
      $data['ic-wid'] = $widgetID;
      $data['ic-cid'] = $challengeId;
      $data['ic-hp'] = '';
      return $data;
   }
   static function EmotCaptcha($headers) {

      $result = [];

      bypass_icon:
      $r = json_decode(Requests::get(host."ecaptcha/get_token", $headers)[1], 1);

      if (!$r["token"]) {
         exit("Captcha:: token expired\n");
      }
      $result["captcha_token"] = $r["token"];

      $r = json_decode(Requests::get(host."ecaptcha/get_captcha", $headers)[1], 1);
      $result["captcha_key"] = $r["captcha_key"];
      $question = strtolower(trim(explode(':', $r["question"])[1]));

      $data = http_build_query([
         "selected" => "$question.gif",
         "key" => $result["captcha_key"],
         "token" => $result["captcha_token"]
      ]);
      $r = json_decode(Requests::post(host."ecaptcha/validate_icon", $headers, $data)[1], 1);
      if ($r["status"] == "valid") {
         $result["captcha"] = "emoji_captcha";
         $result["selected_icon"] = "$question.gif";
         return $result;
      }
      goto bypass_icon;
   }
   static function Icon_hash($header) {
      $hash = [];
      $data_array = [];

      $url = host.'system/libs/captcha/request.php';
      $head = array_merge($header, ["X-Requested-With: XMLHttpRequest"]);
      $getCap = json_decode(Requests::post($url, $head, "cID=0&rT=1&tM=light")[1], 1);
      if (!$getCap) {
         $url = host.'src/captcha-request.php';
         $getCap = json_decode(Requests::post($url, $head, "cID=0&rT=1&tM=light")[1], 1);
      }
      $head2 = array_merge($header, ["accept: image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8"]);
      foreach ($getCap as $c) {
         $data[$c] = base64_encode(Requests::get($url.'?cid=0&hash='.$c, $head2)[1]);
      }

      foreach ($data as $value => $name) {
         $hash[] = $value;
         $data_array[] = strlen($name);
      }
      for ($i = 0; $i < count($data_array); $i++) {
         $current_size = $data_array[$i];
         $is_duplicate = false;
         for ($j = 0; $j < count($data_array); $j++) {
            if ($i != $j && $current_size == $data_array[$j]) {
               $is_duplicate = true;
               break;
            }
         }
         if (!$is_duplicate) {
            return $hash[$i];
         }
      }

      return $hash[-1];
   }
}