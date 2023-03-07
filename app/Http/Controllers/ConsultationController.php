<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Models\Consultation;
use App\Repositories\ConsultationRepository;
use App\Repositories\DoctorRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class ConsultationController extends Controller
{
    public function __construct
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
         $consultations = $this->doctorRepo->get()
                ->when($request->name, function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->name.'%');
                })
                ->whereHas('consultations')
                ->withCount(['consultations as consultation_count']);

         if ($request->specialist) {
            $consultations->whereHas('specialist', function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->specialist.'%');
            });
         }

         $consultations = $consultations->paginate($request->perPage);

        $metaData = [
            'total' => $consultations->total(),
            'perPage' => $consultations->perPage(),
            'page' => $consultations->currentPage()
        ];

        $result = $consultations->map(function ($item){
            return [
                'consultation_doctor_id' => $item->id,
                'consultation_doctor_name' => $item->name,
                'consultation_doctor_specialist' => $item->specialist->name,
                'consultation_patient_count' => $item->consultation_count
            ];
        });

        return Response::success($result, 'success', $metaData, 200);
    }

    public function indexPatients(Request $request)
    {
        $consultations = $this->consultationRepo->get()
                ->whereHas('user');


        if ($request->doctorName) {
            $consultations->whereHas('doctor', function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->doctorName.'%');
            });
        }

        if ($request->userName) {
            $consultations->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->userName.'%');
            });
        }

        if ($request->date) {
            $consultations->where('date', $request->date);
        }

        $consultations =  $consultations->paginate($request->perPage);

        $metaData = [
            'total' => $consultations->total(),
            'perPage' => $consultations->perPage(),
            'page' => $consultations->currentPage()
        ];

        $result = $consultations->map(function ($item){
            return [
                'consultation_id' => $item->id,
                'consultation_user_id' => $item->user_id,
                'consultation_user_name' => $item->user->name ?? null,
                'consultation_user_email' => $item->user->email ?? null,
                'consultation_doctor_id' => $item->doctor_id,
                'consultation_doctor_name' => $item->doctor->name ?? null,
                'consultation_doctor_specialist' => $item->doctor->specialist->name ?? null,
                'consultation_date' => $item->date,
                'consultation_created_at' => $item->created_at,
                'consultation_updated_at' => $item->updated_at
            ];
        });

        return Response::success($result, 'success', $metaData, 200);

    }

    public function show($id)
    {
        $consultation = $this->consultationRepo->get()->where('id', $id)->first();
        $result = [
            'consultation_id' => $consultation->id,
            'consultation_user_id' => $consultation->user_id,
            'consultation_user_name' => $consultation->user->name ?? null,
            'consultation_user_email' => $consultation->user->email ?? null,
            'consultation_user_birth_date' => $consultation->user->birth_date ?? null,
            'consultation_user_birth_place' => $consultation->user->birth_place ?? null,
            'consultation_user_address' => $consultation->user->address ?? null,
            'consultation_user_gender' => $consultation->user->gender ?? null,
            'consultation_doctor_id' => $consultation->doctor_id,
            'consultation_doctor_name' => $consultation->doctor->name ?? null,
            'consultation_doctor_specialist' => $consultation->doctor->specialist->name ?? null,
            'consultation_date' => $consultation->date,
            'consultation_created_at' => $consultation->created_at,
            'consultation_updated_at' => $consultation->updated_at
        ];
        return Response::success($result, 'success', null, 200);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'user_id'   => 'required',
                'doctor_id' => 'required',
                'date'      => 'required',
                'animal_type' => 'required'
            ]);

            if ($validator->fails()) {
                return Response::fail($validator->errors(), 422);
            }

            // check user already exist
            $consultation = Consultation::query()->where('user_id', $request->user_id)->first();
            if($consultation->doctor_id == $request->doctor_id) {
                return Response::fail('Already Consultation Submission', 409);
            }

            // check last time
            $consultation = Consultation::query()->latest()->first();
            if($consultation) {
                $latestTime = Carbon::parse($consultation->date)->format('Y-m-d H:i:s');
                $currentTime = Carbon::parse($request->date)->format('Y-m-d H:i:s');
                $additionalTime = Carbon::parse($consultation->date)->addMinutes(30)->format('Y-m-d H:i:s');

                if($currentTime >= $latestTime && $currentTime <= $additionalTime || $currentTime <= $latestTime) {
                    return Response::fail('Input Date And Time More Than 35 Minute From: '.$latestTime);
                }
            }

            $result = $this->consultationRepo->save($request);
            DB::commit();

            return Response::success($result, 'Success Consultation Submission', null, 200);
        }catch (Exception) {
            DB::rollBack();

            return Response::fail('Internal Server Error', 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required',
                'date'      => 'required',
                'animal_type' => 'required'
            ]);

            if ($validator->fails()) {
                return Response::fail($validator->errors(), 422);
            }

            $consultation = Consultation::query()->where('id', $id)->first();
            if(!$consultation) {
                return Response::fail('Data Not Found', 404);
            }

            // check last time
            $consultation = Consultation::query()->latest()->first();
            if($consultation) {
                $latestTime = Carbon::parse($consultation->date)->format('Y-m-d H:i:s');
                $currentTime = Carbon::parse($request->date)->format('Y-m-d H:i:s');
                $additionalTime = Carbon::parse($consultation->date)->addMinutes(30)->format('Y-m-d H:i:s');

                if($currentTime >= $latestTime && $currentTime <= $additionalTime || $currentTime <= $latestTime) {
                    return Response::fail('Input Date And Time More Than 35 Minute From: '.$latestTime);
                }
            }

            $request->merge([
                'user_id' => $consultation->user_id,
                'doctor_id' => $request->doctor_id,
                'date' => $consultation->date,
                'animal_type' => $request->animal_type
            ]);

            $result = $this->consultationRepo->save($request, $consultation);
            DB::commit();

            return Response::success($result, 'Success Update Consultation Submission', null, 200);
        }catch (Exception $e) {
            DB::rollBack();

            return Response::fail('Internal Server Error'.$e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $result = $this->consultationRepo->delete($id);
            DB::commit();
            return Response::success($result, 'success', null, 200);
        }catch (Exception) {
            DB::rollBack();

            return Response::fail('Internal Server Error', 500);
        }
    }
}
