<?php

if (! function_exists('settings')) {
    /**
     * Get / set the specified setting.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function settings($key, $default = null)
    {
        if (is_array($key)) {
            $res = true;

            foreach ($key as $k => $value) {
                $settingHash = crc32($k);
                $setting = \App\Models\Setting::query()->where('setting_hash', '=', $settingHash)->first();

                if (!isset($setting)) {
                    $setting = new \App\Models\Setting();
                    $setting->setting_hash = $settingHash;
                }

                $setting->value = $value;

                $res = $res && $setting->save();
            }

            return $res;
        }

        $setting = \App\Models\Setting::query()->where('setting_hash', '=', crc32($key))->first();

        return isset($setting) ? $setting->value : $default;
    }
}
