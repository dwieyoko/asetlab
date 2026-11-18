<?php
/**
 * AsetLab Platform - Pure PHP Email & SMTP Service
 * Mendukung SMTP SSL/TLS cPanel DomaiNesia (tanpa Composer/PHPMailer) + fallback mail().
 */

class MailService
{
    public static function sendOrderPaidEmail(array $order, array $items, array $licenses): bool
    {
        $toEmail = trim((string) ($order['buyer_email'] ?? ''));
        if ($toEmail === '' || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $invoice    = (string) ($order['invoice_number'] ?? '');
        $token      = (string) ($order['access_token'] ?? '');
        $buyerName  = htmlspecialchars((string) ($order['buyer_name'] ?? 'Pelanggan'), ENT_QUOTES, 'UTF-8');
        $invoiceUrl = APP_URL . '/transaksi/' . rawurlencode($invoice) . '?token=' . rawurlencode($token);
        $totalFmt   = 'Rp ' . number_format((float) ($order['total_amount'] ?? 0), 0, ',', '.');

        $licenseRowsHtml = '';
        foreach ($licenses as $lic) {
            $title = htmlspecialchars((string) ($lic['product_title'] ?? 'Produk Digital'), ENT_QUOTES, 'UTF-8');
            $key   = htmlspecialchars((string) ($lic['license_key'] ?? ''), ENT_QUOTES, 'UTF-8');
            $dlUrl = APP_URL . '/api/download.php?license=' . rawurlencode((string) ($lic['license_key'] ?? '')) . '&token=' . rawurlencode($token);

            $licenseRowsHtml .= "
            <tr>
              <td style=\"padding:12px;border-bottom:1px solid #1e293b;color:#f1f5f9;font-size:14px;\">
                <strong>{$title}</strong><br/>
                <span style=\"font-family:monospace;font-size:12px;color:#4edea3;\">License: {$key}</span>
              </td>
              <td style=\"padding:12px;border-bottom:1px solid #1e293b;text-align:right;\">
                <a href=\"{$dlUrl}\" style=\"display:inline-block;padding:8px 16px;background:#00f2fe;color:#0a0e17;text-decoration:none;border-radius:999px;font-weight:700;font-size:12px;\">Unduh File</a>
              </td>
            </tr>";
        }

        $subject = "[AsetLab] Pembayaran Lunas — Akses Unduhan {$invoice}";
        $html = "<!DOCTYPE html>
<html lang=\"id\">
<body style=\"margin:0;padding:24px;background:#0a0e17;font-family:Arial,Helvetica,sans-serif;color:#dfe2ef;\">
  <div style=\"max-width:600px;margin:0 auto;background:#131926;border:1px solid #1e293b;border-radius:16px;overflow:hidden;\">
    <div style=\"padding:24px;background:#0f131c;border-bottom:1px solid #1e293b;\">
      <div style=\"font-size:22px;font-weight:800;color:#00f2fe;\">aset<span style=\"color:#4edea3;\">lab</span>.id</div>
      <p style=\"margin:6px 0 0;font-size:13px;color:#94a3b8;\">Konfirmasi Pembayaran &amp; Akses Produk Digital</p>
    </div>
    <div style=\"padding:24px;\">
      <h2 style=\"margin:0 0 12px;color:#ffffff;font-size:18px;\">Halo {$buyerName}, pembayaran Anda berhasil!</h2>
      <p style=\"margin:0 0 18px;font-size:14px;color:#cbd5e1;line-height:1.6;\">
        Terima kasih telah berbelanja di AsetLab. Invoice <strong style=\"color:#00f2fe;\">{$invoice}</strong> sebesar <strong>{$totalFmt}</strong> telah terverifikasi lunas.
      </p>
      <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"background:#0a0e17;border:1px solid #1e293b;border-radius:12px;margin-bottom:20px;\">
        {$licenseRowsHtml}
      </table>
      <div style=\"text-align:center;margin:24px 0;\">
        <a href=\"{$invoiceUrl}\" style=\"display:inline-block;padding:12px 28px;background:linear-gradient(90deg,#00f2fe,#4edea3);color:#0a0e17;text-decoration:none;border-radius:999px;font-weight:700;font-size:14px;\">
          Buka Halaman Invoice &amp; Unduhan
        </a>
      </div>
      <p style=\"margin:16px 0 0;font-size:12px;color:#94a3b8;line-height:1.5;\">
        Tips: Simpan email ini atau daftar akun di AsetLab menggunakan email <strong>{$toEmail}</strong> agar seluruh lisensi dan pembaruan produk tersimpan otomatis di Library Anda.
      </p>
    </div>
    <div style=\"padding:16px 24px;background:#0a0e17;border-top:1px solid #1e293b;font-size:11px;color:#64748b;text-align:center;\">
      &copy; " . date('Y') . " AsetLab Platform. Email otomatis, mohon tidak membalas langsung.
    </div>
  </div>
</body>
</html>";

        return self::send($toEmail, $subject, $html);
    }

    public static function send(string $to, string $subject, string $htmlBody): bool
    {
        $smtpEnabled = (bool) appConfig('SMTP_ENABLED', false);
        $fromEmail   = (string) appConfig('SMTP_FROM_EMAIL', 'noreply@asetlab.web.id');
        $fromName    = (string) appConfig('SMTP_FROM_NAME', 'AsetLab Digital Store');

        if ($smtpEnabled) {
            $sent = self::sendViaSmtp($to, $subject, $htmlBody, $fromEmail, $fromName);
            if ($sent) {
                return true;
            }
            error_log("[AsetLab Mail] SMTP gagal mengirim ke {$to}, mencoba fallback mail()");
        }

        // Fallback PHP mail() pada cPanel DomaiNesia
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            "From: {$fromName} <{$fromEmail}>",
            "Reply-To: {$fromEmail}",
            'X-Mailer: AsetLab-Mailer/2.0',
        ];

        $ok = @mail($to, $encodedSubject, $htmlBody, implode("\r\n", $headers));
        if (!$ok) {
            error_log("[AsetLab Mail] Fallback mail() tidak aktif atau gagal untuk {$to}");
        }
        return $ok;
    }

    private static function sendViaSmtp(
        string $to,
        string $subject,
        string $htmlBody,
        string $fromEmail,
        string $fromName
    ): bool {
        $host       = (string) appConfig('SMTP_HOST', '');
        $port       = (int) appConfig('SMTP_PORT', 465);
        $encryption = strtolower((string) appConfig('SMTP_ENCRYPTION', 'ssl'));
        $user       = (string) appConfig('SMTP_USER', '');
        $pass       = (string) appConfig('SMTP_PASS', '');

        if ($host === '' || $user === '' || $pass === '') {
            return false;
        }

        $remote = ($encryption === 'ssl' ? 'ssl://' : 'tcp://') . $host . ':' . $port;
        $socket = @stream_socket_client($remote, $errno, $errstr, 12);
        if (!$socket) {
            error_log("[AsetLab SMTP] Connect failed: {$errstr} ({$errno})");
            return false;
        }

        stream_set_timeout($socket, 12);

        $read = static function () use ($socket): string {
            $data = '';
            while (($line = fgets($socket, 515)) !== false) {
                $data .= $line;
                if (isset($line[3]) && $line[3] === ' ') {
                    break;
                }
            }
            return $data;
        };

        $cmd = static function (string $command, array $expectCodes) use ($socket, $read): bool {
            fwrite($socket, $command . "\r\n");
            $resp = $read();
            $code = (int) substr($resp, 0, 3);
            return in_array($code, $expectCodes, true);
        };

        // Banner
        $banner = $read();
        if ((int) substr($banner, 0, 3) !== 220) {
            fclose($socket);
            return false;
        }

        $ehloHost = parse_url(APP_URL, PHP_URL_HOST) ?: 'localhost';
        if (!$cmd("EHLO {$ehloHost}", [250])) {
            fclose($socket);
            return false;
        }

        if ($encryption === 'tls') {
            if (!$cmd('STARTTLS', [220])) {
                fclose($socket);
                return false;
            }
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                fclose($socket);
                return false;
            }
            $cmd("EHLO {$ehloHost}", [250]);
        }

        if (!$cmd('AUTH LOGIN', [334])
            || !$cmd(base64_encode($user), [334])
            || !$cmd(base64_encode($pass), [235])
        ) {
            fclose($socket);
            return false;
        }

        if (!$cmd("MAIL FROM:<{$fromEmail}>", [250])
            || !$cmd("RCPT TO:<{$to}>", [250, 251])
            || !$cmd('DATA', [354])
        ) {
            fclose($socket);
            return false;
        }

        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $message = "From: {$fromName} <{$fromEmail}>\r\n"
            . "To: <{$to}>\r\n"
            . "Subject: {$encodedSubject}\r\n"
            . "MIME-Version: 1.0\r\n"
            . "Content-Type: text/html; charset=UTF-8\r\n"
            . "Content-Transfer-Encoding: base64\r\n"
            . "Date: " . date('r') . "\r\n"
            . "\r\n"
            . chunk_split(base64_encode($htmlBody))
            . "\r\n.";

        $ok = $cmd($message, [250]);
        $cmd('QUIT', [221]);
        fclose($socket);
        return $ok;
    }
}
