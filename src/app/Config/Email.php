<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = (defined('A5F2C9E4B7D1G6H3J8K0L2M5N1P4Q9R3') ? A5F2C9E4B7D1G6H3J8K0L2M5N1P4Q9R3 : 'email_padrao@example.com');
    public string $fromName   = 'Habilidade .Com';
    public string $recipients = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Hostname
     */
    public string $SMTPHost = (defined('C1F5B3E8A6D2G9H0J4K7L1M6N3P8Q0R5') ? C1F5B3E8A6D2G9H0J4K7L1M6N3P8Q0R5 : 'smtp.example.com');

    /**
     * SMTP Username
     */
    public string $SMTPUser = (defined('A5F2C9E4B7D1G6H3J8K0L2M5N1P4Q9R3') ? A5F2C9E4B7D1G6H3J8K0L2M5N1P4Q9R3 : 'email_padrao@example.com');

    /**
     * SMTP Password
     */
    public string $SMTPPass = (defined('E3C8A1F6B4D9G2H7J0K5L8M3N6P9Q2R7') ? E3C8A1F6B4D9G2H7J0K5L8M3N6P9Q2R7 : 'unknown_password');

    /**
     * SMTP Port
     */
    public int $SMTPPort = 587;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 5;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     *
     * @var string '', 'tls' or 'ssl'. 'tls' will issue a STARTTLS command
     *             to the server. 'ssl' means implicit SSL. Connection on port
     *             465 should set this to ''.
     */
    public string $SMTPCrypto = 'tls';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = true;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;
}
