<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('reports', function ($user) {
    return true;
});
