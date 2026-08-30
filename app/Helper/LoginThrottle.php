<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Model;

class LoginThrottle
{
    const MAX_ATTEMPTS = 5;
    const LOCK_MINUTES = 15;

    /**
     * Whether the account is currently locked (and the lock is still active).
     */
    public static function isLocked(Model $model): bool
    {
        if (empty($model->locked_until)) {
            return false;
        }
        if (now()->greaterThanOrEqualTo($model->locked_until)) {
            return false;
        }
        return true;
    }

    /**
     * Minutes remaining until the lock expires (for the error message).
     */
    public static function lockMinutesRemaining(Model $model): int
    {
        if (empty($model->locked_until)) {
            return 0;
        }
        $minutes = now()->diffInMinutes($model->locked_until, false);
        return max(1, (int) ceil($minutes));
    }

    /**
     * Register a failed attempt. Locks the account when the threshold is reached.
     */
    public static function registerFailure(Model $model): void
    {
        $attempts = (int) $model->login_attempts + 1;
        $model->login_attempts = $attempts;

        if ($attempts >= self::MAX_ATTEMPTS) {
            $model->locked_until = now()->addMinutes(self::LOCK_MINUTES);
            $model->login_attempts = 0;
        }

        $model->save();
    }

    /**
     * Clear counters after a successful login.
     */
    public static function reset(Model $model): void
    {
        $model->login_attempts = 0;
        $model->locked_until = null;
        $model->save();
    }
}
