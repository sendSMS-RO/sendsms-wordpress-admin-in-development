<?php
if (!defined('WPINC')) {
    die;
}

class SendSMSFunctions
{
    /**
     * Get Plugin settings auth settings
     * 
     * @since 1.0.0
     */
    public function get_auth(&$username, &$password, &$label)
    {
        $username = $this->get_setting('username', '');
        $password = $this->get_setting('password', '');
        $label = $this->get_setting('label', '1898');
    }

    /**
     * Validates a phone number and concatenate the prefixe
     * 
     * @since 1.0.0
     */
    public function validate_phone($phone_number)
    {
        $phone_number = $this->clear_phone_number($phone_number);
        //Strip out leading zeros:
        $phone_number = ltrim($phone_number, '0');
        if ($this->get_setting("cc", "INT") === "INT") {
            return $phone_number;
        }
        $country_code = $this->country_codes[$this->get_setting("cc", "INT")];

        if (!preg_match('/^' . $country_code . '/', $phone_number)) {
            $phone_number = $country_code . $phone_number;
        }

        return $phone_number;
    }

    /**
     * It clears a phone number of maliciouse characters
     * 
     * @since 1.0.0
     */
    public function clear_phone_number($phone_number)
    {
        $phone_number = str_replace(['+', '-'], '', filter_var($phone_number, FILTER_SANITIZE_NUMBER_INT));
        //Strip spaces and non-numeric characters:
        $phone_number = preg_replace("/[^0-9]/", "", $phone_number);
        return $phone_number;
    }

    /**
     * Get an individual setting
     * 
     * @since 1.0.0
     */
    public function get_setting($setting, $default = "")
    {
        return isset(get_option('sendsms_dashboard_plugin_settings')["$setting"]) ? get_option('sendsms_dashboard_plugin_settings')["$setting"] : $default;
    }

    /**
     * Get an individual setting escaped
     * 
     * @since 1.0.0
     */
    public function get_setting_esc($setting, $default = "")
    {
        return esc_html(isset(get_option('sendsms_dashboard_plugin_settings')["$setting"]) ? get_option('sendsms_dashboard_plugin_settings')["$setting"] : $default);
    }

    /**
     * Add a user to the subscriber list
     * 
     * @since 1.0.0
     */
    public function add_subscriber_db($name, $phone_number, $ip_address)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sendsms_dashboard_subscribers';
        $browser = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
        $wpdb->query(
            $wpdb->prepare(
                "
                INSERT INTO $table_name
                (`phone`, `name`, `date`, `ip_address`, `browser`)
                VALUES ( %s, %s, %s, %s, %s)",
                $phone_number,
                $name,
                date('Y-m-d H:i:s'),
                $ip_address,
                $browser
            )
        );
        if (!$this->registered_ip_address_db($ip_address)) {
            $this->add_ip_address_db($ip_address);
        }
    }

    /**
     * Remove a subscriber based on his phone number
     * 
     * @since 1.0.0
     */
    public function remove_subscriber_db($phone_number, $ip_address = null)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sendsms_dashboard_subscribers';
        $result = $wpdb->query(
            $wpdb->prepare(
                "
                DELETE FROM $table_name
                WHERE phone = %s",
                $phone_number
            )
        );
        if ($ip_address == null) {
            return;
        }
        if (!$this->registered_ip_address_db($ip_address)) {
            $this->add_ip_address_db($ip_address);
        }
    }

    /**
     * Check if the number is already subscribed
     * 
     * @since 1.0.0
     */
    public function is_subscriber_db($phone_number)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sendsms_dashboard_subscribers';
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE phone = %s", $phone_number), ARRAY_A);
        return count($results) != 0 ? true : false;
    }

    /**
     * Check if the ip address exists before adding it in db
     * 
     * @since 1.0.0
     */
    public function registered_ip_address_db($ip_address)
    {
        return count($this->get_ip_address_db($ip_address)) != 0 ? true : false;
    }

    /**
     * Inser ip address in dba_close
     * 
     * @since 1.0.0
     */
    public function add_ip_address_db($ip_address)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sendsms_dashboard_ip_address';
        $wpdb->query(
            $wpdb->prepare(
                "
                INSERT INTO $table_name
                (`ip_address`, `date_cycle_start`, `request_no`)
                VALUES ( %s, %s, %s)",
                $ip_address,
                date('Y-m-d H:i:s'),
                "1",
            )
        );
    }

    /**
     * Get an ip address info from db
     * 
     * @since 1.0.0
     */
    public function get_ip_address_db($ip_address)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sendsms_dashboard_ip_address';
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE ip_address	 = %s", $ip_address), ARRAY_A);
        return $results;
    }
    /**
     * Get user ip address
     * 
     * @since 1.0.0
     */
    public function get_ip_address()
    {
        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            return sanitize_text_field(wp_unslash($_SERVER['HTTP_X_REAL_IP']));
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return (string) rest_is_ip_address(trim(current(preg_split('/,/', sanitize_text_field(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']))))));
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            return sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
        }
        return '';
    }

    /**
     * Check if the ip is restricted
     * 
     * @since 1.0.0
     */
    public function is_restricted_ip($ip_address, $restricted_ips)
    {
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $restricted_ips) as $restricted_ip) {
            if (rest_is_ip_address($restricted_ip)) {
                if ($ip_address === $restricted_ip) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Generate a random string
     * 
     * @since 1.0.0
     */
    public function random_str(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[wp_rand(0, $max)];
        }
        return implode('', $pieces);
    }
    /**
     * This will check if there are too many request made by this ip. If not, either add one to the request counter, or reset iterator_apply
     * 
     * @since 1.0.0
     */
    public function too_many_requests($ip_address)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sendsms_dashboard_ip_address';
        $ip_restrict = $this->get_setting('ip_limit', '');
        $ip_restrict = explode("/", $ip_restrict);
        if (count($ip_restrict) == 2 && is_numeric($ip_restrict[0]) && is_numeric($ip_restrict[1])) {
            if ($ip_restrict[1] != -1 && $ip_restrict[0] >= 0) {
                $details = $this->get_ip_address_db($ip_address);
                $attempts = $details[0]['request_no'];
                $timePassed = abs((new \DateTime($details[0]['date_cycle_start']))->getTimestamp() - (new \DateTime(date('Y-m-d H:i:s')))->getTimestamp()) / 60;
                if ($timePassed < $ip_restrict[1]) {
                    if ($attempts >= $ip_restrict[0]) {
                        return true;
                    } else {
                        $wpdb->query(
                            $wpdb->prepare(
                                "
                                UPDATE $table_name
                                SET request_no = %s
                                WHERE ip_address = %s",
                                $attempts + 1,
                                $ip_address
                            )
                        );
                    }
                } else {
                    $wpdb->query(
                        $wpdb->prepare(
                            "
                            UPDATE $table_name
                            SET date_cycle_start = %s, request_no = 1
                            WHERE ip_address = %s",
                            date('Y-m-d H:i:s'),
                            $ip_address
                        )
                    );
                }
            }
        }
        return false;
    }

    /**
     * This will generate a verification code. The code will be saved inside collator_sort_with_sort_keys
     * 
     * @since 1.0.0
     */
    function generateVerificationCode($phone_number)
    {
        $code = $this->random_str(5);
        $hashedCookie = wp_hash($code . $phone_number);
        setcookie('sendsms_subscribe_check', $hashedCookie, time() + 60 * 60, COOKIEPATH, COOKIE_DOMAIN, is_ssl());
        return $code;
    }

    /**
     * This will verify the verification token and delete it if it succeed
     */
    function verifyVerificationCode($phone_number)
    {
        if (!isset($_POST['code'])) {
            return false;
        }
        $code = $this->clearStringOfSpecialChars(sanitize_text_field($_POST['code']));
        $isValidToken = hash_equals($_COOKIE['sendsms_subscribe_check'], wp_hash($code . $phone_number));
        if ($isValidToken) {
            setcookie('sendsms_subscribe_check', "", time() - 1, COOKIEPATH, COOKIE_DOMAIN, is_ssl());
            return true;
        }
        return false;
    }
    function clearStringOfSpecialChars($string)
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
    public $country_codes = array(
        'AC' => '247',
        'AD' => '376',
        'AE' => '971',
        'AF' => '93',
        'AG' => '1268',
        'AI' => '1264',
        'AL' => '355',
        'AM' => '374',
        'AO' => '244',
        'AQ' => '672',
        'AR' => '54',
        'AS' => '1684',
        'AT' => '43',
        'AU' => '61',
        'AW' => '297',
        'AX' => '358',
        'AZ' => '994',
        'BA' => '387',
        'BB' => '1246',
        'BD' => '880',
        'BE' => '32',
        'BF' => '226',
        'BG' => '359',
        'BH' => '973',
        'BI' => '257',
        'BJ' => '229',
        'BL' => '590',
        'BM' => '1441',
        'BN' => '673',
        'BO' => '591',
        'BQ' => '599',
        'BR' => '55',
        'BS' => '1242',
        'BT' => '975',
        'BW' => '267',
        'BY' => '375',
        'BZ' => '501',
        'CA' => '1',
        'CC' => '61',
        'CD' => '243',
        'CF' => '236',
        'CG' => '242',
        'CH' => '41',
        'CI' => '225',
        'CK' => '682',
        'CL' => '56',
        'CM' => '237',
        'CN' => '86',
        'CO' => '57',
        'CR' => '506',
        'CU' => '53',
        'CV' => '238',
        'CW' => '599',
        'CX' => '61',
        'CY' => '357',
        'CZ' => '420',
        'DE' => '49',
        'DJ' => '253',
        'DK' => '45',
        'DM' => '1767',
        'DO' => '1809',
        'DO' => '1829',
        'DO' => '1849',
        'DZ' => '213',
        'EC' => '593',
        'EE' => '372',
        'EG' => '20',
        'EH' => '212',
        'ER' => '291',
        'ES' => '34',
        'ET' => '251',
        'EU' => '388',
        'FI' => '358',
        'FJ' => '679',
        'FK' => '500',
        'FM' => '691',
        'FO' => '298',
        'FR' => '33',
        'GA' => '241',
        'GB' => '44',
        'GD' => '1473',
        'GE' => '995',
        'GF' => '594',
        'GG' => '44',
        'GH' => '233',
        'GI' => '350',
        'GL' => '299',
        'GM' => '220',
        'GN' => '224',
        'GP' => '590',
        'GQ' => '240',
        'GR' => '30',
        'GT' => '502',
        'GU' => '1671',
        'GW' => '245',
        'GY' => '592',
        'HK' => '852',
        'HN' => '504',
        'HR' => '385',
        'HT' => '509',
        'HU' => '36',
        'ID' => '62',
        'IE' => '353',
        'IL' => '972',
        'IM' => '44',
        'IN' => '91',
        'IO' => '246',
        'IQ' => '964',
        'IR' => '98',
        'IS' => '354',
        'IT' => '39',
        'JE' => '44',
        'JM' => '1876',
        'JO' => '962',
        'JP' => '81',
        'KE' => '254',
        'KG' => '996',
        'KH' => '855',
        'KI' => '686',
        'KM' => '269',
        'KN' => '1869',
        'KP' => '850',
        'KR' => '82',
        'KW' => '965',
        'KY' => '1345',
        'KZ' => '7',
        'LA' => '856',
        'LB' => '961',
        'LC' => '1758',
        'LI' => '423',
        'LK' => '94',
        'LR' => '231',
        'LS' => '266',
        'LT' => '370',
        'LU' => '352',
        'LV' => '371',
        'LY' => '218',
        'MA' => '212',
        'MC' => '377',
        'MD' => '373',
        'ME' => '382',
        'MF' => '590',
        'MG' => '261',
        'MH' => '692',
        'MK' => '389',
        'ML' => '223',
        'MM' => '95',
        'MN' => '976',
        'MO' => '853',
        'MP' => '1670',
        'MQ' => '596',
        'MR' => '222',
        'MS' => '1664',
        'MT' => '356',
        'MU' => '230',
        'MV' => '960',
        'MW' => '265',
        'MX' => '52',
        'MY' => '60',
        'MZ' => '258',
        'NA' => '264',
        'NC' => '687',
        'NE' => '227',
        'NF' => '672',
        'NG' => '234',
        'NI' => '505',
        'NL' => '31',
        'NO' => '47',
        'NP' => '977',
        'NR' => '674',
        'NU' => '683',
        'NZ' => '64',
        'OM' => '968',
        'PA' => '507',
        'PE' => '51',
        'PF' => '689',
        'PG' => '675',
        'PH' => '63',
        'PK' => '92',
        'PL' => '48',
        'PM' => '508',
        'PR' => '1787',
        'PR' => '1939',
        'PS' => '970',
        'PT' => '351',
        'PW' => '680',
        'PY' => '595',
        'QA' => '974',
        'QN' => '374',
        'QS' => '252',
        'QY' => '90',
        'RE' => '262',
        'RO' => '40',
        'RS' => '381',
        'RU' => '7',
        'RW' => '250',
        'SA' => '966',
        'SB' => '677',
        'SC' => '248',
        'SD' => '249',
        'SE' => '46',
        'SG' => '65',
        'SH' => '290',
        'SI' => '386',
        'SJ' => '47',
        'SK' => '421',
        'SL' => '232',
        'SM' => '378',
        'SN' => '221',
        'SO' => '252',
        'SR' => '597',
        'SS' => '211',
        'ST' => '239',
        'SV' => '503',
        'SX' => '1721',
        'SY' => '963',
        'SZ' => '268',
        'TA' => '290',
        'TC' => '1649',
        'TD' => '235',
        'TG' => '228',
        'TH' => '66',
        'TJ' => '992',
        'TK' => '690',
        'TL' => '670',
        'TM' => '993',
        'TN' => '216',
        'TO' => '676',
        'TR' => '90',
        'TT' => '1868',
        'TV' => '688',
        'TW' => '886',
        'TZ' => '255',
        'UA' => '380',
        'UG' => '256',
        'UK' => '44',
        'US' => '1',
        'UY' => '598',
        'UZ' => '998',
        'VA' => '379',
        'VA' => '39',
        'VC' => '1784',
        'VE' => '58',
        'VG' => '1284',
        'VI' => '1340',
        'VN' => '84',
        'VU' => '678',
        'WF' => '681',
        'WS' => '685',
        'XC' => '991',
        'XD' => '888',
        'XG' => '881',
        'XL' => '883',
        'XN' => '857',
        'XN' => '858',
        'XN' => '870',
        'XP' => '878',
        'XR' => '979',
        'XS' => '808',
        'XT' => '800',
        'XV' => '882',
        'YE' => '967',
        'YT' => '262',
        'ZA' => '27',
        'ZM' => '260',
        'ZW' => '263',
    );
}
