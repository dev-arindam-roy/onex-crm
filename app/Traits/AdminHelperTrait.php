<?php

namespace App\Traits;
use App\Models\Admin;
use DB;

trait AdminHelperTrait
{
    public static function adminByEmail($email, $authID = '')
    {
        $query = Admin::where('email_id', $email);
        if ($authID != '' && $authID != null) {
            $query = $query->where('id', '!=', $authID);
        }
        return $query->first();
    }

    public static function adminByMobile($mobile, $authID = '')
    {
        $query = Admin::where('mobile_number', $mobile)->whereNotNull('mobile_verified_at');
        if ($authID != '' && $authID != null) {
            $query = $query->where('id', '!=', $authID);
        }
        return $query->first();
    }
}