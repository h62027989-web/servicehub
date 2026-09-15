<?php
namespace App\Http\Requests;use Illuminate\Foundation\Http\FormRequest;
class BookingRequest extends FormRequest{public function authorize():bool{return auth()->check();}public function rules():array{return ['service_id'=>['required','exists:services,id'],'booking_date'=>['required','date','after_or_equal:today'],'booking_time'=>['required','date_format:H:i'],'address'=>['required','string','min:5','max:1000'],'customer_note'=>['nullable','string','max:1000']];}}
