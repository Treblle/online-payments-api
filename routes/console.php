<?php

use Illuminate\Support\Facades\Schedule;


Schedule::command('app:random-api-call')->everyMinute();
