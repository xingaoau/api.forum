<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Class VerificationCode.
 */
class VerificationCode
{
    const KEY_TEMPLATE = 'verify_code_of_%s';

    /**
     *
     * @param string $phone
     *
     * @return int
     */
    public function create($phone)
    {
        $code = mt_rand(1000, 9999);

        \Log::debug("verification:{$phone}:{$code}");

        Cache::put(sprintf(self::KEY_TEMPLATE, $phone), $code, 10);

        if (app()->environment('production')) {
            app('sms')->send($phone, [
                    'content' => "your verification is ：{$code}",
                    'code' => $code,
                ]);
        }

        return $code;
    }

    /**
     *
     * @param string $phone
     * @param int    $code
     *
     * @return bool
     */
    public function validate($phone, $code)
    {
        if (empty($phone) || empty($code)) {
            return false;
        }

        $key = sprintf(self::KEY_TEMPLATE, $phone);

        $cachedCode = Cache::get($key);

        \Log::debug('cached verify code', ['key' => $key, 'cached' => $cachedCode, 'input' => $code]);

        return strval($cachedCode) === strval($code);
    }
}
