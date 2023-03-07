<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Models\Specialist;
use App\Repositories\SpecialistRepository;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SpecialistController extends Controller
{

    public function __construct
    (
        SpecialistRepository $specialistRepo
    )
    {
        $this->specialistRepo = $specialistRepo;
    }

    public function index(Request $request)
    {
        $specialists = $this->specialistRepo->get()
            ->when($request->search, function ($query) use($request){
                $query->where('name', 'like', '%'.$request->search.'%');
            })
            ->paginate($request->perPage);

        $metaData = [
            'total' => $specialists->total(),
            'perPage' => $specialists->perPage(),
            'page' => $specialists->currentPage()
        ];

        $result = $specialists->map(function ($item){
            return [
                'specialist_id' => $item->id,
                'specialist_name' => $item->name
            ];
        });

        return Response::success($result, 'success', $metaData, 200);

    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);

            if ($validator->fails()) {
                return Response::fail($validator->errors(), 422);
            }

            $result = $this->specialistRepo->save($request);
            DB::commit();

            return Response::success($result, 'Success Insert Specialist', null, 200);
        }catch (Exception){
            DB::rollBack();

            return Response::fail('Internal Server Error', 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);

            if ($validator->fails()) {
                return Response::fail($validator->errors(), 422);
            }

            $specialist = Specialist::query()->where('id', $id)->first();
            $result = $this->specialistRepo->save($request, $specialist);
            DB::commit();

            return Response::success($result, 'Success Update Specialist', null, 200);
        }catch (Exception){
            DB::rollBack();

            return Response::fail('Internal Server Error', 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $result = $this->specialistRepo->delete($id);
            DB::commit();

            return Response::success($result, 'Success Delete Specialist', null, 200);
        }catch (Exception){
            DB::rollBack();

            return Response::fail('Internal Server Error', 500);
        }
    }

}
