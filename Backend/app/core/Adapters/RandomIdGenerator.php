<?php

namespace App\Core\Adapters;

use App\Core\Ports\IDgenerator;
use Illuminate\Support\Str;

class RandomIdGenerator implements IDgenerator {
    public function generate(): string {
       return Str::uuid();
    }
}
