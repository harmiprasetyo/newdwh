<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterTpmbRequest;
use App\Http\Resources\MasterTpmbResource;
use App\Models\MasterTpmb;
use Illuminate\Http\Request;
class MasterTpmbController extends Controller {
 public function index(Request $request) {
  $v=$request->validate(['search'=>'nullable|string|max:255','kodeFaskes'=>'nullable|string|max:50','status_tpmb'=>'nullable|in:aktif,tidak','per_page'=>'nullable|integer|min:1|max:100']);
  $q=MasterTpmb::query();
  if (!empty($v['search'])) { $s='%'.$v['search'].'%'; $q->where(fn($x)=>$x->where('kode_tpmb','like',$s)->orWhere('nama_tpmb','like',$s)->orWhere('fhir_id','like',$s)); }
  foreach (['kodeFaskes','status_tpmb'] as $key) if (isset($v[$key])) $q->where($key,$v[$key]);
  return MasterTpmbResource::collection($q->orderBy('nama_tpmb')->orderBy('id')->paginate($v['per_page']??20)->withQueryString());
 }
 public function store(MasterTpmbRequest $request) {
  $tpmb=MasterTpmb::create($request->validated());
  return (new MasterTpmbResource($tpmb->refresh()))->response()->setStatusCode(201);
 }
 public function show(MasterTpmb $tpmb) { return new MasterTpmbResource($tpmb); }
 public function update(MasterTpmbRequest $request, MasterTpmb $tpmb) {
  $tpmb->update($request->validated()); return new MasterTpmbResource($tpmb->refresh());
 }
 public function destroy(MasterTpmb $tpmb) { $tpmb->delete(); return response()->noContent(); }
}
