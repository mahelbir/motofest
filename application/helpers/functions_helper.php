<?php

function sets(string $key){
    global $_ci;
    return $_ci->sets->get($key);
}

function rand_weighted(array $weighted_values)
{
    $rand = mt_rand(1, (int)array_sum($weighted_values));

    foreach ($weighted_values as $key => $value) {
        $rand -= $value;
        if ($rand <= 0) {
            return $key;
        }
    }

    return null;
}

function rand_number(int $digit = 6): int
{
    if ($digit <= 1) {
        $min = 0;
    } else {
        $min = pow(10, $digit - 1);
    }
    return mt_rand($min, pow(10, $digit) - 1);
}

function rand_time(string $str = '', string $format = 'dmYH'): string
{
    return md5(date_format(date_create('', timezone_open('UTC')), $format) . $str);
}

function rand_uniq(): string
{
    return md5(uniqid(microtime() . mt_rand(), true) . microtime() . mt_rand());
}

function clear(string $str): string
{
    global $_ci;
    return trim(addslashes(strip_tags(htmlentities($_ci->security->xss_clean($str)))));
}

function find_between(string $string, string $start, string $end): string
{
    $array = explode($start, $string);
    if (!empty($array[1])) {
        return trim(explode($end, $array[1])[0]);
    }

    return '';
}

function get_ip(): string
{
    $HTTP_X_FORWARDED_FOR = $_SERVER["HTTP_X_FORWARDED_FOR"] ?? getenv('HTTP_X_FORWARDED_FOR');
    $HTTP_CLIENT_IP = $_SERVER["HTTP_CLIENT_IP"] ?? getenv('HTTP_CLIENT_IP');
    $HTTP_CF_CONNECTING_IP = $_SERVER["HTTP_CF_CONNECTING_IP"] ?? getenv('HTTP_CF_CONNECTING_IP');
    $REMOTE_ADDR = $_SERVER["REMOTE_ADDR"] ?? getenv('REMOTE_ADDR');
    $all_ips = explode(",", "$HTTP_X_FORWARDED_FOR,$HTTP_CLIENT_IP,$HTTP_CF_CONNECTING_IP,$REMOTE_ADDR");
    foreach ($all_ips as $ip) {
        if ($ip = filter_var($ip, FILTER_VALIDATE_IP))
            break;
    }
    return $ip ?? '';
}

function my_404(): void
{
    set_status_header(404);
    global $_ci;
    $_ci->blade->load("errors.my_404");
}

function curl(string $url, $post = null, array $extra_headers = [], string $proxy = null, bool $advanced = false)
{
    $parsed = parse_url($url);
    $headers[] = 'Accept: */*';
    $headers[] = 'Accept-Language: en-US';
    $headers[] = 'Cache-Control: max-age=0';
    $headers[] = 'Sec-Ch-Ua: \"Chromium\";v=\"102\", \"Opera GX\";v=\"88\", \";Not A Brand\";v=\"99\"';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
    $headers[] = 'Sec-Fetch-Dest: document';
    $headers[] = 'Sec-Fetch-Mode: navigate';
    $headers[] = 'Sec-Fetch-Site: none';
    $headers[] = 'Sec-Fetch-User: ?1';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.115 Safari/537.36 OPR/88.0.4412.65';
    $headers[] = 'Cookie: curl_time=' . time();
    $headers = array_merge($headers, $extra_headers);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, $advanced);
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_REFERER, $parsed['scheme'] . '://' . $parsed['host']);
    if (!empty($post)) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    $data = curl_exec($ch);
    if ($advanced) {
        $header_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $data = [
            "body" => trim(substr($data, $header_len) ?? ''),
            "header" => trim(substr($data, 0, $header_len) ?? ''),
            "info" => curl_getinfo($ch),
            "error" => curl_errno($ch) ? curl_error($ch) : null
        ];
    }
    curl_close($ch);
    return $data;
}