<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueList extends Model
{
    protected $primaryKey = 'queue_id';

    public function domain()
    {
        return $this->belongsTo('App\Domain');
    }
}
