<?php
namespace App\Http\Controllers\Api;use App\Http\Controllers\Controller;use App\Models\Service;use Illuminate\Http\Request;
class ServiceApiController extends Controller{public function index(Request $r){$q=Service::with(['category','provider'])->where('is_active',true)->when($r->filled('q'),fn($x)=>$x->where('name','like','%'.$r->q.'%'))->when($r->filled('category_id'),fn($x)=>$x->where('category_id',$r->category_id))->paginate(15);return response()->json($q);}public function show(Service $service){return response()->json($service->load('category','provider','reviews.customer'));}}
