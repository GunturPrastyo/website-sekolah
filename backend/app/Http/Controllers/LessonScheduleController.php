<?php

namespace App\Http\Controllers;

use App\Models\LessonSchedule;
use Illuminate\Http\Request;

class LessonScheduleController extends Controller
{
    public function index()
    {
        // Mengambil jadwal beserta relasi untuk frontend
        $schedules = LessonSchedule::with(['schoolClass', 'staff', 'curriculumSubject'])->get();
        
        return response()->json([
            'data' => $schedules->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'class_id' => $schedule->school_class_id,
                    'className' => $schedule->schoolClass->name ?? 'Tidak Diketahui',
                    'day' => $schedule->day,
                    'startTime' => substr($schedule->start_time, 0, 5), // Ambil format H:i
                    'endTime' => substr($schedule->end_time, 0, 5),
                    'subject_id' => $schedule->curriculum_subject_id,
                    'subject' => $schedule->curriculumSubject->name ?? 'Tidak Diketahui',
                    'teacher_id' => $schedule->staff_id,
                    'teacher' => $schedule->staff->name ?? 'Tidak Diketahui',
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'staff_id' => 'required|exists:staff,id',
            'curriculum_subject_id' => 'required|exists:curriculum_subjects,id',
            'day' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $schedule = LessonSchedule::create($validatedData);

        return response()->json([
            'message' => 'Jadwal pelajaran berhasil ditambahkan',
            'data' => $schedule
        ], 201);
    }

    public function update(Request $request, LessonSchedule $lessonSchedule)
    {
        $validatedData = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'staff_id' => 'required|exists:staff,id',
            'curriculum_subject_id' => 'required|exists:curriculum_subjects,id',
            'day' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $lessonSchedule->update($validatedData);

        return response()->json(['message' => 'Jadwal pelajaran berhasil diperbarui']);
    }

    public function destroy(LessonSchedule $lessonSchedule)
    {
        $lessonSchedule->delete();
        return response()->json(['message' => 'Jadwal pelajaran berhasil dihapus']);
    }
}