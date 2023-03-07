<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Models\Doctor;
use App\Repositories\ConsultationRepository;
use App\Repositories\DoctorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    public function  __construct
    (
        ConsultationRepository $consultationRepo,
        DoctorRepository $doctorRepo
    )
    {
        $this->consultationRepo = $consultationRepo;
        $this->doctorRepo = $doctorRepo;
    }

    public function index(Request $request)
    {
         $doctors = $this->doctorRepo->get()
            ->when($request->name, function ($query) use($request){
                $query->where('name', 'like', '%'.$request->name.'%');
            })
            ->when($request->specialist, function ($query) use($request){
                $query->whereHas('specialist', function ($query) use($request){
                    $query->where('name', 'like', '%'.$request->specialist.'%');
                });
            })
            ->paginate($request->perPage);

         if(!$doctors){
             return  Response::fail('Doctor Not Found', 404);
         }

        $metaData = [
            'total' => $doctors->total(),
            'perPage' => $doctors->perPage(),
            'page' => $doctors->currentPage()
        ];

        $result = $doctors->map( function ($item) {
            return [
                'doctor_id' => $item->id,
                'doctor_name' => $item->name,
                'doctor_specialist' => $item->specialist->name ?? null
            ];
        });

        return Response::success($result, 'success', $metaData, 200);
    }

    public function indexPatients(Request $request, $id)
    {
        $doctors = $this->consultationRepo->getById('doctor_id', $id)->with('user')->paginate($request->perPage);
        $metaData = [
            'total' => $doctors->total(),
            'perPage' => $doctors->perPage(),
            'page' => $doctors->currentPage()
        ];
        $result = $doctors->map( function ($item) {
            return [
                'consultation_id' => $item->id,
                'consultation_animal_type' => $item->animal_type,
                'consultation_date' => $item->date,
                'consultation_user_name' => $item->user->name ?? null,
                'consultation_user_email' => $item->user->email ?? null
            ];
        });
        return Response::success($result, 'success', $metaData, 200);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'specialist_id' => 'required',
                'birth_date'    => 'required',
                'birth_place'   => 'required',
                'gender'        => 'required',
                'address'       => 'required'
            ]);

            if ($validator->fails()) {
                return Response::fail($validator->errors(), 422);
            }

            $result = $this->doctorRepo->save($request);
            DB::commit();

            return Response::success($result, 'Success Insert Doctor', null, 200);
        }catch (Exception $e) {
            DB::rollBack();

            return Response::fail('Internal Server Error'.$e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $doctor = $this->doctorRepo->get()->where('id', $id)->first();
        $result = [
            'doctor_id' => $doctor->id,
            'doctor_name' => $doctor->name,
            'doctor_birth_place' => $doctor->birth_place,
            'doctor_birth_date' => $doctor->birth_date,
            'doctor_gender' => $doctor->gender,
            'doctor_address' => $doctor->address
        ];

        return Response::success($result, 'Success', null, 200);

    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'specialist_id' => 'required',
                'birth_date'    => 'required',
                'birth_place'   => 'required',
                'gender'        => 'required',
                'address'       => 'required'
            ]);

            if ($validator->fails()) {
                return Response::fail($validator->errors(), 422);
            }

            $doctor = Doctor::query()->where('id', $id)->first();
            if(!$doctor) {
                return Response::fail('Doctor Not Found', 404);
            }
            $request->merge([
                'id' => $doctor->id,
                'name' => $request->name
            ]);
            $result = $this->doctorRepo->save($request, $doctor);
            DB::commit();
            return Response::success($result, 'Success Update Doctor', null, 200);
        }catch (Exception) {
            DB::rollBack();

            return Response::fail('Internal Server Error', 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $doctor = $this->doctorRepo->delete($id);

            DB::commit();
            return Response::success($doctor, 'success', null, 200);
        } catch (Exception) {
            DB::rollBack();

            return Response::fail('Internal Server Error', 500);
        }
    }

}
