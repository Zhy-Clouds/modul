
class Shortlinks {
    public $provider_sl;
    public $host;
    public $key;

    private $cache_file = "/sdcard/Zhy/lib/sl_cache.json";
    private $unsupported_file = "/sdcard/Zhy/lib/unsupported_sl.txt";
    private $cache = ["support" => [], "unsupported" => []];
    private $manual_unsupported = [];

    private function normal($s) {
        $s = strtolower(trim($s));
        $s = str_replace(["https://", "http://", "www."], "", $s);
        $s = rtrim($s, "/");
        return $s;
    }
    private function pecah($line) {
        $line = $this->normal($line);
        if ($line === "" || str_starts_with($line, "#")) return [];
        $line = str_replace("=>", " ", $line);
        $parts = preg_split("/[\s,;]+/", $line);
        $out = [];
        foreach ($parts as $p) {
            $p = $this->normal($p);
            if ($p !== "") $out[] = $p;
        }
        return $out;
    }
    private function is_blacklist($nama, $hostnya = "") {
        $cek_list = $this->pecah($nama." ".$hostnya);
        foreach ($cek_list as $c) {
            if (in_array($c, $this->manual_unsupported)) return true;
        }
        foreach ($this->cache["unsupported"] ?? [] as $saved) {
            $saved_parts = $this->pecah($saved);
            foreach ($cek_list as $c) {
                if (in_array($c, $saved_parts)) return true;
            }
        }
        return false;
    }
    private function is_support_cache($nama, $hostnya = "") {
        $cek_list = $this->pecah($nama." ".$hostnya);
        foreach ($this->cache["support"] ?? [] as $saved) {
            $saved_parts = $this->pecah($saved);
            foreach ($cek_list as $c) {
                if (in_array($c, $saved_parts)) return $saved;
            }
        }
        return false;
    }
    public function __construct() {
        $folder = dirname($this->cache_file);
        if (!file_exists($folder)) mkdir($folder, 0777, true);
        if (file_exists($this->cache_file)) {
            $tmp = json_decode(file_get_contents($this->cache_file), true);
            if (is_array($tmp)) $this->cache = array_merge($this->cache, $tmp);
        }
        if (file_exists($this->unsupported_file)) {
            $lines = file($this->unsupported_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                foreach ($this->pecah($line) as $tok) $this->manual_unsupported[] = $tok;
            }
            $this->manual_unsupported = array_unique($this->manual_unsupported);
        }
        menu(1, "Syid");
        menu(2, "Tertuyul"); l();
        print r.pan2.k."ENTER = ".h.sl.n;
        $pil = input("Provider Shortlinks");
        if ($pil == 1) {
            $this->host = "https://syid.my.id/";
            print pan2.p."Register".sd.h."https://syid.my.id/".d.n; l();
            $this->key = save("Syid_Apikey");
            $this->provider_sl = "Syid";
        } elseif ($pil == 2) {
            $this->host = "http://api.tertuyul.my.id/";
            print pan2.p."Register".sd.h."https://tertuyul.my.id/apikey".d.n; l();
            $this->key = save("Tertuyul_Apikey");
            $this->provider_sl = "Tertuyul";
        } else {
            if (sl == "[Tertuyul]" || preg_match("/tuyul/i", $sl)) {
                $this->host = "http://api.tertuyul.my.id/";
                print pan2.p."Register".sd.h."https://tertuyul.my.id/apikey".d.n; l();
                $this->key = save("Tertuyul_Apikey");
                $this->provider_sl = "Tertuyul";
            } else {
                $this->host = "https://syid.my.id/";
                print pan2.p."Register".sd.h."https://syid.my.id/".d.n; l();
                $this->key = save("Syid_Apikey");
                $this->provider_sl = "Syid";
            }
        }
    }
    public function balsl() {
        if ($this->provider_sl == "Syid") {
            $url = $this->host."in.php?apikey=".$this->key."&saldo=token";
            $r = run($url)[1] ?? '0';
            slow(cb.p.t("Api_SL", k."𓊈𒆜 ".h.$r.k." 𒆜𓊉")); l();
        } else {
            $post = ["key" => $this->key, "action" => "userinfo"];
            $ch = curl_init($this->host."res.php");
            curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => $post]);
            $resp = curl_exec($ch);
            curl_close($ch);
            $r = json_decode($resp, true);
            $bal = $r["balance"] ?? $r["request"] ?? '0';
            slow(cb.p.t("Api_SL", k."𓊈𒆜 ".h.$bal.k." 𒆜𓊉")); l();
        }
    }
    public function learn($nama, $is_support, $hostnya = "") {
        $n = $this->normal($nama);
        $h = $this->normal($hostnya);
        if ($n === "") return;
        $gabung = $h !== "" && $h !== $n ? $n." => ".$h : $n;
        if ($is_support) {
            $ada = false;
            foreach ($this->cache["support"] as $s) {
                if ($this->normal($s) == $this->normal($gabung)) $ada = true;
            }
            if (!$ada) $this->cache["support"][] = $gabung;
            $baru = [];
            foreach ($this->cache["unsupported"] as $s) {
                $sp = $this->pecah($s);
                $cp = $this->pecah($gabung);
                $sama = false;
                foreach ($cp as $c) if (in_array($c, $sp)) $sama = true;
                if (!$sama) $baru[] = $s;
            }
            $this->cache["unsupported"] = $baru;
        } else {
            $ada = false;
            foreach ($this->cache["unsupported"] as $s) {
                if ($this->normal($s) == $this->normal($gabung)) $ada = true;
            }
            if (!$ada) $this->cache["unsupported"][] = $gabung;
        }
        file_put_contents($this->cache_file, json_encode($this->cache));
    }
    public function check($nama, $hostnya = "") {
        $check = $this->normal($nama);
        $host_cek = $this->normal($hostnya);
        $tampil = $check.($host_cek ? " => ".$host_cek : "");
        print r.k."--[".p."?".k."] ".o."Checking ".$tampil;
        if ($this->is_blacklist($nama, $hostnya)) {
            print x("Skip! Ada di unsupported_sl.txt / cache", d);
            sleep(1); print r;
            return ["status" => 0, "message" => "di unsupported_sl.txt / cache"];
        }
        $dari_cache = $this->is_support_cache($nama, $hostnya);
        if ($dari_cache !== false) {
            print r.centang.h."Support (dari cache)";
            sleep(1);print r;
            return ["status" => 1, "shortlink_name" => $check, "shortlink_host" => $host_cek, "cd" => rand(60, 90), "from_cache" => true];
        }
        $supported = [
            "ouo" => ["sl" => "ouo.io", "syid" => [30, 90], "tuyul" => [30, 90]],
            "ouo.io" => ["sl" => "ouo.io", "syid" => [30, 90], "tuyul" => [30, 90]],
            "clk" => ["sl" => "Clksh", "syid" => [60, 90], "tuyul" => [90, 120]],
            "clksh" => ["sl" => "Clksh", "syid" => [60, 90], "tuyul" => [90, 120]],
            "clk.sh" => ["sl" => "Clksh", "syid" => [60, 90], "tuyul" => [90, 120]],
            "chainfo" => ["sl" => "chainfo.xyz", "syid" => [90, 120], "tuyul" => [120, 150]],
            "chainfo.xyz" => ["sl" => "chainfo.xyz", "syid" => [90, 120], "tuyul" => [120, 150]],
            "try2link" => ["sl" => "try2link", "syid" => [60, 90], "tuyul" => [80, 100]],
            "trylink" => ["sl" => "try2link", "syid" => [60, 90], "tuyul" => [80, 100]],
            "linkpay" => ["sl" => "linkpay", "syid" => [60, 90], "tuyul" => [90, 120]],
            "linkpay.top" => ["sl" => "linkpay.top", "syid" => [60, 90], "tuyul" => [90, 120]],
            "urlcut" => ["sl" => "Urlcutpo", "syid" => [90, 120], "tuyul" => [120, 150]],
            "urlcutpo" => ["sl" => "Urlcutpo", "syid" => [90, 120], "tuyul" => [120, 150]],
            "urlcutpro" => ["sl" => "Urlcutpo", "syid" => [90, 120], "tuyul" => [120, 150]],
            "paycut" => ["sl" => "paycut.pro", "syid" => [90, 120], "tuyul" => [90, 120]],
            "paycut.pro" => ["sl" => "paycut.pro", "syid" => [90, 120], "tuyul" => [90, 120]],
            "pay cut" => ["sl" => "paycut.pro", "syid" => [90, 120], "tuyul" => [90, 120]],
            "linksly" => ["sl" => "linksly.co", "syid" => [60, 120], "tuyul" => [60, 120]],
            "linksly.co" => ["sl" => "linksly.co", "syid" => [60, 120], "tuyul" => [60, 120]],
            "linksfly" => ["sl" => "LinksFly", "syid" => [90, 120], "tuyul" => [120, 150]],
            "linksfly.me" => ["sl" => "LinksFly", "syid" => [90, 120], "tuyul" => [120, 150]],
            "cut-urls" => ["sl" => "cuturl", "syid" => [60, 90], "tuyul" => 0],
            "cbshort" => ["sl" => "cbshort", "syid" => [60, 90], "tuyul" => 0],
            "ez4short" => ["sl" => "ez4short", "syid" => [60, 90], "tuyul" => 0],
            "clks" => ["sl" => "clks", "syid" => 0, "tuyul" => [150, 180]],
            "clks.pro" => ["sl" => "clks", "syid" => 0, "tuyul" => [150, 180]],
            "clks pro" => ["sl" => "clks", "syid" => 0, "tuyul" => [150, 180]],
            "revcut" => ["sl" => "revcut", "syid" => 0, "tuyul" => [120, 150]],
            "revcut.net" => ["sl" => "revcut", "syid" => 0, "tuyul" => [120, 150]],
            "exe" => ["sl" => "exe", "syid" => 0, "tuyul" => [80, 100]],
            "exe.io" => ["sl" => "exe", "syid" => 0, "tuyul" => [80, 100]],
            "gplinks" => ["sl" => "gplinks", "syid" => 0, "tuyul" => [90, 120]],
        ];
        sleep(1);
        $filter = $supported[$check] ?? ($host_cek ? ($supported[$host_cek] ?? null) : null);
        if ($filter) {
            $key = ($this->provider_sl == "Syid") ? "syid" : "tuyul";
            $cd_range = $filter[$key] ?? 0;
            if (!$cd_range || $cd_range === 0) {
                print x("Doesn't support di ".$this->provider_sl, d); sleep(1); print r;
                return ["status" => 0, "message" => "not supported di ".$this->provider_sl];
            }
            $cd = rand($cd_range[0], $cd_range[1]);
            print r.centang.h."Support to bypass";
            sleep(1);print r;
            return ["status" => 1, "shortlink_name" => $filter["sl"], "shortlink_host" => $host_cek, "cd" => $cd];
        }
        if ($this->provider_sl == "Syid") {
            print x("Doesn't support to bypass", d);
            sleep(1); print r;
            return ["status" => 0, "message" => "not supported shortlink"];
        }
        print x("Belum tau, coba bypass 1x!", n);
        sleep(1);
        return ["status" => 2, "shortlink_name" => $check, "shortlink_host" => $host_cek, "cd" => rand(60, 90), "unknown" => true];
    }
    public function Bypass($name, $shortlink) {
        if ($this->provider_sl == "Syid") {
            if (empty($name)) {
                print x("Nama SL kosong, harus lewat check() dulu!", n);
                return ["status" => "fail", "url" => null, "i" => 0];
            }
            $r = run($this->host."in.php?apikey=".$this->key."&name=$name&url=$shortlink&reff=53");
            if (!$r || empty($r[1])) {
                unlink("cookie.txt");
                print x("Server Down", n); sleep(1);
                return ["status" => "fail", "url" => null, "reason" => "server_down", "i" => 0];
            }
            $html = $r[1];
            $url = null;
            if (str_contains($html, 'url:"')) {
                $url = explode('"&', explode('url:"', $html)[1])[0] ?? null;
            }
            if ($url) {
                return ["url" => $url, "status" => "success"];
            } elseif (preg_match("/not support link/i", $html)) {
                unlink("cookie.txt"); print x($html, d); l();
                return ["status" => "fail", "url" => null, "reason" => "unsupported", "i" => 0];
            } elseif (preg_match("/bypass error/i", $html)) {
                unlink("cookie.txt");
                print x($html, n); l(); sleep(1);
                return ["status" => "fail", "url" => null, "reason" => "bypass_error", "i" => 0];
            } else {
                unlink("cookie.txt"); print x($html, n); l();
                return ["status" => "fail", "url" => null, "reason" => "unknown", "i" => 0];
            }
        }
        $post = ["key" => $this->key, "method" => "shortlink", "pageurl" => $shortlink, "json" => "1"];
        $ch = curl_init($this->host."in.php");
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => $post]);
        $resp = curl_exec($ch);
        curl_close($ch);
        $task = json_decode($resp, true);
        if (($task["status"] ?? 0) != 1) {
            print x("Submit error: ".($task["request"] ?? $resp), n); l();
            return ["status" => "fail", "url" => null, "reason" => "submit_error", "i" => 0];
        }
        $taskId = $task["request"];
        // echo "Task ID: $taskId\n";
        $coba = 0;
        while (true) {
            sleep(rand(3, 5));
            $coba++;
            if ($coba > 60) {
                print x("Timeout nunggu Tertuyul", n);
                return ["status" => "fail", "url" => null, "reason" => "timeout", "i" => 0];
            }
            $post = ["key" => $this->key, "action" => "get", "id" => $taskId, "json" => "1"];
            $ch = curl_init($this->host."res.php");
            curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => $post]);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            if (($res["status"] ?? 0) == 1) {
                // echo "Solved: ".$res["request"]."\n";
                return ["url" => $res["request"], "status" => "success"];
            }
            if (($res["request"] ?? "") === "CAPCHA_NOT_READY") continue;
            $err = $res["request"] ?? "unknown";
            if ($err == "ERROR_SHORTLINK_UNSUPPORTED") {
                print x("Gak support di Tertuyul, skip!", n); l();
                return ["status" => "fail", "url" => null, "reason" => "unsupported", "i" => 0];
            }
            if ($err == "ERROR_SHORTLINK_UNSOLVABLE") {
                print x("Gak bisa di-solve sekarang, skip dulu!", n); l();
                return ["status" => "fail", "url" => null, "reason" => "unsolvable", "i" => 0];
            }
            print x("Error Tertuyul: ".$err, n); l();
            return ["status" => "fail", "url" => null, "reason" => $err, "i" => 0];
        }
    }
}