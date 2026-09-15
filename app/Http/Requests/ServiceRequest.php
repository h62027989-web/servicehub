<?php
namespace App\Http\Requests;use Illuminate\Foundation\Http\FormRequest;
class ServiceRequest extends FormRequest{public function authorize():bool{return auth()->check();}public function rules():array{return ['category_id'=>['required','exists:categories,id'],'name'=>['required','string','max:150'],'description'=>['nullable','string','max:3000'],'price'=>['required','numeric','min:1','max:999999'],'duration'=>['required','string','max:50'],'image'=>['nullable','image','max:2048']];}}
