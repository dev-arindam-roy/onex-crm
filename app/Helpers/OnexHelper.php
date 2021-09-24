<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Traits\OnexTrait;
use Session;
use DB;

class OnexHelper 
{
    use OnexTrait;

    public static function getConfigValue($configKey) 
    {
        $configValue = '';
        $configs = DB::table('configurations')->get();
        if (count($configs)) {
            foreach ($configs as $config) {
                if ($config->key == trim($configKey)) {
                    $configValue = trim($config->value);
                    break;
                }
            }
        }
        return $configValue;
    }

    public static function generateAccountID() 
    {
        $lastID = self::getConfigValue('last_account');
        $nextID = $lastID + rand(2, 9);
        $accountID = config('onex.account_prefix') . str_pad($nextID, 10, 0, STR_PAD_LEFT);
        DB::table('configurations')
            ->where('key', 'last_account')
            ->update(['value' => $nextID]);
        return $accountID;
    }

    public static function generateHashID($limit = 60) 
    {
        return md5(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit));
    }

    public static function generateToken($uniqueID = '') 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $token = $uniqueID;
        for ($i = 0; $i < 60; $i++) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }
        return base64_encode($token);
    }
}