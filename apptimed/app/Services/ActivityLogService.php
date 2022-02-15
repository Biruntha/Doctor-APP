<?php

namespace App\Services;

use App\Models\ActivityLog;
use Auth;
use DB;

class ActivityLogService{

    public function createActivity($activity,$order_id,$type,$activity_entity_id){

        $activity_log = new ActivityLog();
        $activity_log->activity_description = $activity;
        $activity_log->user = Auth::user()->id;
        $activity_log->order_id = $order_id;
        $activity_log->activity_type = $type;
        $activity_log->activity_entity_id = $activity_entity_id;
        $activity_log->save();
        return true;
    }
}
