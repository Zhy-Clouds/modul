<?php
#Api
function CheckApi() {
   print pan2.p."Register".sd.h.provider_ref.n;
   l();
   $apikey = save(provider_api."_Apikey", 1);
   if (provider_api == "Xevil") {
      $api = New ApiXevil($apikey);
   }
   if (provider_api == "Multibot") {
      $api = New ApiMultibot($apikey);
   }
}

function MenuApi() {
   wc();
   menu(1, "Xevil");
   menu(2, "Multibot"); l();
   print r.pan2.k."ENTER = ".h.rc.n;
   $pil = input("Provider Apikey");
   if ($pil == 1) {
      define("provider_api", "Xevil");
      define("provider_ref", "t.me/Xevil_check_bot?start=2124530010");
      $apikey = CheckApi();
   } elseif ($pil == 2) {
      define("provider_api", "Multibot");
      define("provider_ref", "https://multibot.in/");
      $apikey = CheckApi();
   } else {
      if (rc == "[Xevil]") {
         define("provider_api", "Xevil");
         define("provider_ref", "t.me/Xevil_check_bot?start=2124530010");
         $apikey = CheckApi();
      } else {
         define("provider_api", "Multibot");
         define("provider_ref", "https://multibot.in/");
         $apikey = CheckApi();
      }
   }
   return $apikey;
}

function bal($api, $dot = 0) {
   if ($dot) {
      return slow(cb.p.t("Apikey_Bal", k."𓊈𒆜 ".h.$api->getBalance().k." 𒆜𓊉", 1));
   }
   return slow(cb.p.t("Api_Bal", k."𓊈𒆜 ".h.$api->getBalance().k." 𒆜𓊉".d));
}

Class RequestApi {
   function in_api($content, $method, $header = 0) {
      $param = "key=".$this->apikey."&json=1&".$content;
      if ($method == "GET")return json_decode(file_get_contents($this->host.'/in.php?'.$param), 1);
      $opts['http']['method'] = $method;
      if ($header) $opts['http']['header'] = $header;
      $opts['http']['content'] = $param;
      return json_decode(file_get_contents($this->host.'/in.php', false, stream_context_create($opts)), 1);
   }
   function res_api($api_id) {
      $params = "?key=".$this->apikey."&action=get&id=".$api_id."&json=1";
      return json_decode(file_get_contents($this->host."/res.php".$params), 1);
   }
   function getBalance() {
      $res = json_decode(file_get_contents($this->host."/res.php?action=userinfo&key=".$this->apikey), 1);
      return $res["balance"];
   }
   function getResult($data, $method, $header = 0) {
      $get_in = $this->in_api($data, $method, $header);
      if (!$get_in["status"]) {
         print r.p."[".m."!".p."] ".o.t(provider_api, mc.$get_in["request"].n);
         return 0;
      }
      while (true) {
         $get_res = $this->res_api($get_in["request"]);
         print r.k."Bypassing."; usleep(300000);
         print r.k."Bypassing.."; usleep(300000);
         if ($get_res["request"] == "CAPCHA_NOT_READY") {
            print r.k."Bypassing...";
            sleep(1); continue;
         }
         if ($get_res["status"]) {
            print r.h."Bypassing..."; usleep(500000);
            return $get_res["request"];
         }
         print r.p."[".m."!".p."] ".o.t(provider_api, mc.$get_res["request"].n);
         sleep(5); return 0;
      }
   }
}

Class ApiMultibot extends RequestApi {
   function __construct($apikey) {
      $this->host = "http://api.multibot.in";
      $this->provider_api = "multibot";
      $this->apikey = save('Multibot_Apikey', 1);
   }
   function RecaptchaV2($sitekey, $pageurl) {
      $data = http_build_query([
         "method" => "userrecaptcha",
         "sitekey" => $sitekey,
         "pageurl" => $pageurl
      ]);
      return $this->getResult($data, "GET");
   }
   function Hcaptcha($sitekey, $pageurl) {
      $data = http_build_query([
         "method" => "hcaptcha",
         "sitekey" => $sitekey,
         "pageurl" => $pageurl
      ]);
      return $this->getResult($data, "GET");
   }
   function Turnstile($sitekey, $pageurl) {
      $data = http_build_query([
         "method" => "turnstile",
         "sitekey" => $sitekey,
         "pageurl" => $pageurl
      ]);
      return $this->getResult($data, "GET");
   }
   function Authkong($sitekey, $pageurl) {
      $data = http_build_query([
         "method" => "authkong",
         "sitekey" => $sitekey,
         "pageurl" => $pageurl
      ]);
      return $this->getResult($data, "GET");
   }
   function Ocr($img) {
      $data = http_build_query([
         "method" => "universal",
         "body" => $img
      ]);
      return $this->getResult($data, "POST");
   }
   function Upsidedown($img) {
      $data = http_build_query([
         "method" => "upside",
         "body" => $img
      ]);
      return $this->getResult($data, "POST");
   }
   function RsCap($img) {
      $data = http_build_query([
         "method" => "rscaptcha",
         "body" => $img
      ]);
      return $this->getResult($data, "POST");
   }
   function AntiBot($source) {
      $main = explode('"', explode('src="', explode('Bot links', $source)[1])[1])[0];
      if (!$main)return 0;
      $data["method"] = "antibot";
      $data["main"] = $main;
      $src = explode('rel=\"', $source);
      foreach ($src as $x => $sour) {
         if ($x == 0)continue;
         $no = explode('\"', $sour)[0];
         $img = explode('\"', explode('src=\"', $sour)[1])[0];
         $data[$no] = $img;
      }
      $data = http_build_query($data);
      //print_r($data);
      $ua = "Content-type: application/x-www-form-urlencoded";
      $res = $this->getResult($data, "POST", $ua);
      return "+".str_replace(",", "+", $res);
   }
}

Class ApiXevil extends RequestApi {
   function __construct($apikey) {
      #$apikey = save('Xevil_Apikey', 1);
      $this->host = "https://sctg.xyz";
      $this->apikey = $apikey."|SOFTID2124530010";
      $this->apikey = save('Xevil_Apikey', 1)."|SOFTID2124530010";
   }
   function RecaptchaV2($sitekey, $pageurl) {
      $data = http_build_query([
         "method" => "userrecaptcha",
         "sitekey" => $sitekey,
         "pageurl" => $pageurl
      ]);
      return $this->getResult($data, "GET");
   }
   function Hcaptcha($sitekey, $pageurl) {
      $data = http_build_query([
         "method" => "hcaptcha",
         "sitekey" => $sitekey,
         "pageurl" => $pageurl
      ]);
      return $this->getResult($data, "GET");
   }
   function Turnstile($sitekey, $pageurl) {
      $data = http_build_query([
         "method" => "turnstile",
         "sitekey" => $sitekey,
         "pageurl" => $pageurl
      ]);
      return $this->getResult($data, "GET");
   }
   function Authkong($sitekey, $pageurl) {
      $data = http_build_query([
         "method" => "authkong",
         "sitekey" => $sitekey,
         "pageurl" => $pageurl
      ]);
      return $this->getResult($data, "GET");
   }
   function Ocr($img) {
      $data = "method=base64&body=".$img;
      return $this->getResult($data, "POST");
   }
   function AntiBot($source) {
      $main = explode('"', explode('data:image/png;base64,', explode('Bot links', $source)[1])[1])[0];
      if (!$main)return 0;
      $data = "key=".$this->apikey."&json=1&method=antibot&main=$main";
      $src = explode('rel=\"', $source);
      foreach ($src as $x => $sour) {
         if ($x == 0)continue;
         $no = explode('\"', $sour)[0];
         $img = explode('\"', explode('data:image/png;base64,', $sour)[1])[0];
         $data .= "&$no=$img";
      }
      $res = $this->getResult($data, "POST");
      if ($res)return "+".str_replace(",", "+", $res);
      return 0;
   }
   function Teaserfast($main, $small) {
      $data = http_build_query([
         "method" => "teaserfast",
         "main_photo" => $main,
         "task" => $small
      ]);
      return $this->getResult($data, "GET");
   }
}