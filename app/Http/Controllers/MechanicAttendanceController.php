<?php

namespace App\Http\Controllers;

use App\Models\MechanicAttendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MechanicAttendanceController extends Controller
{
    public function __construct()
    {
        view()->share('title', 'Attendance');
    }

    public function index()
    {
        $user = backend_user();

        if (! Schema::hasTable('mechanic_attendances')) {
            return view('attendance.index', [
                'attendances' => collect(),
                'mechanics' => collect(),
                'todayAttendance' => null,
                'attendanceTableMissing' => true,
            ]);
        }

        $attendances = MechanicAttendance::with('user')
            ->when($user->role === 'mekanik', fn ($query) => $query->where('user_id', $user->id))
            ->latest('attendance_date')
            ->latest('id')
            ->get();

        $mechanics = User::where('role', 'mekanik')->get();
        $todayAttendance = $user->role === 'mekanik'
            ? MechanicAttendance::where('user_id', $user->id)->whereDate('attendance_date', today())->first()
            : null;

        return view('attendance.index', compact('attendances', 'mechanics', 'todayAttendance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit',
            'notes' => 'nullable|string',
            'face_photo' => 'required|string',
        ]);

        MechanicAttendance::updateOrCreate(
            [
                'user_id' => backend_user()?->id,
                'attendance_date' => today()->toDateString(),
            ],
            [
                'status' => $request->status,
                'notes' => $request->notes,
                'face_photo' => $request->face_photo,
            ]
        );

        return redirect()->to(backend_route('admin.attendance.index'))->with('success', 'Absensi hari ini berhasil disimpan.');
    }
}
