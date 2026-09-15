<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('queue:work --stop-when-empty')->everyMinute();
