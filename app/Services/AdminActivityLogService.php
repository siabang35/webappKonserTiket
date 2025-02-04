<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AdminActivityLogService
{
    public function log($action, $description, $admin_id = null, $metadata = [])
    {
        return DB::table('admin_activity_logs')->insert([
            'admin_id' => $admin_id,
            'action' => $action,
            'description' => $description,
            'metadata' => json_encode($metadata),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now()
        ]);
    }
}
