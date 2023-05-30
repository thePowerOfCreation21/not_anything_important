<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatAction
{
    public function get ()
    {
        return collect(
            DB::select("
                SELECT
                (
                    SELECT
                        count(`id`)
                    FROM `products`
                    WHERE `deleted_at` IS NULL
                ) AS `products_count`
            ")
        )->first();
    }

    public function get_stats_for_user (User $user)
    {
        return collect(
            DB::select("
                SELECT
                (
                    SELECT
                        count(`id`)
                    FROM `notification_users`
                    WHERE `is_seen` = 0 AND `user_id` = '{$user->id}'
                ) AS `unseen_notifications_count`,
                (
                    SELECT
                        count(`id`)
                    FROM `cart_contents`
                    WHERE `user_id` = '{$user->id}'
                ) AS `cart_contents_count`,
                (
                    SELECT
                        count(`id`)
                    FROM `tickets`
                    WHERE `user_seen` = 0 AND `user_id` = '{$user->id}'
                ) AS `new_tickets_count`
            ")
        )->first();
    }
}
