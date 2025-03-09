<?php

namespace App\Core\Adapters;

use App\Core\Ports\IDgenerator;

class FixedIdGenerator implements IDgenerator {
    public function generate(): string {
        return "id-1";
    }
}
