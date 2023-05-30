<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatAction
{
    public function getByStudentId (string $student_id)
    {
        return collect(
            DB::select("
                SELECT
                (
                    SELECT
                           count(`id`)
                    FROM
                        `survey_categories`
                    WHERE `type` = 'student' and not exists (
                        SELECT
                               *
                        FROM `survey_answers`
                        WHERE `survey_categories`.`id` = `survey_answers`.`survey_category_id` and `participant_id` = $student_id
                    ) and `is_active` = true
                ) AS `unanswered_active_survey_categories_count`,
                (
                    SELECT
                           count(`id`)
                    FROM
                         `message_receiver_pivot`
                    WHERE
                        `receiver_type` = 'App\\Models\\StudentModel' AND receiver_id = '$student_id' AND `is_seen` = 0
                ) AS `unread_messages_count`
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
