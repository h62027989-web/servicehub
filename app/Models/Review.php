<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class Review extends Model{protected $fillable=['booking_id','customer_id','service_id','rating','comment'];public function booking(){return $this->belongsTo(Booking::class);}public function customer(){return $this->belongsTo(User::class);}public function service(){return $this->belongsTo(Service::class);}}
