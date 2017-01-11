<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $primaryKey = 'domain_id';

    public function queue()
    {
        return $this->hasOne('App\QueueList');
    }
}
