<?php

namespace App\Helpers;

use App\Models\UserDetail;

class Helper
{
    public static function generateUniqueUserId()
    {
        do {
            $randomId = mt_rand(1000000, 9999999);
        } while (UserDetail::where('user_id', $randomId)->exists());

        return $randomId;
    }
}
