<?php
function run($u, $h = 0, $p = 0, $cookie = 0, $lewat = 0) {
   while (true) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $u);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      curl_setopt($ch, CURLOPT_COOKIE, TRUE);
      if ($cookie) {
         curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
         curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
      }
      if ($p) {
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
      }
      if ($h) {
         curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
      }
      curl_setopt($ch, CURLOPT_HEADER, true);
      $r = curl_exec($ch);
      if ($lewat) {
         return 0;
      }
      $c = curl_getinfo($ch);
      if (!$c) return "Curl Error : ".curl_error($ch); else {
         $hd = substr($r, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
         $bd = substr($r, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
         curl_close($ch);
         if (!$bd) {
            print r.mc."Check your Connection!";
            sleep(2); print r; continue;
         }
         return array($hd, $bd);
      }
   }
}

function roll($u, $h = 0, $p = 0, $c = 0, $px = 0) {
   while (true) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $u);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      curl_setopt($ch, CURLOPT_COOKIE, TRUE);
      if ($c) {
         curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
         curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
      }
      if ($p) {
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
      }
      if ($h) {
         curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
      }
      if ($px) {
         curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
         curl_setopt($ch, CURLOPT_PROXY, $px);
         //curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
      }
      curl_setopt($ch, CURLOPT_HEADER, true);
      $response = curl_exec($ch);
      $httpcode = curl_getinfo($ch);
      if (!$httpcode) return "Curl Error : ".curl_error($ch); else {
         $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
         $body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
         curl_close($ch);
         #return array($header, $body)[1];
         return array($header, $body);
      }
   }
}