<?php

namespace App;

use App\Models\Application;
use App\Models\RentToOwnApplication;

class Constants
{
    public const GENDERS = ['male', 'female', 'other'];
    public const APPLICATION_STATUSES = [
        Application::STATUS_SUBMITTED,
        Application::STATUS_REVIEWING,
        Application::STATUS_RESUBMIT,
        Application::STATUS_APPROVED,
        Application::STATUS_DECLINED
    ];
    public const RTO_APPLICATION_STATUSES = [
        RentToOwnApplication::STATUS_SUBMITTED,
        RentToOwnApplication::STATUS_REVIEWING,
        RentToOwnApplication::STATUS_RESUBMIT,
        RentToOwnApplication::STATUS_APPROVED,
        RentToOwnApplication::STATUS_DECLINED
    ];
    public const SUPPORT_STATUSES = [
        'waiting',
        'active',
        'solved'
    ];


}
