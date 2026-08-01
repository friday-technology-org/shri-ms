<?php

namespace Cms\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Cms404Log extends Model
{
    protected $table = 'cms_404_logs';

    protected $fillable = [
        'url',
        'referrer',
        'ip_address',
        'user_agent',
        'count',
    ];
}
