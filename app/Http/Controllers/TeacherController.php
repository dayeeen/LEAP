<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTeacherRequest;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.teachers.index', [
            'teachers' => Teacher::orderByDesc('id')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.teachers.create'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        //
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();

        if(!$user) {
            return back()->with('error', 'Email not found');
        }

        if($user->hasRole('teacher')) {
            return redirect()->back()->with('error', 'User already has a teacher role');
        }

        DB::transaction(function () use ($user, $validated) {
            $validated['user_id'] = $user->id;
            $validated['is_active'] = true;

            Teacher::create($validated);

            if($user->hasRole('student')) {
                $user->removeRole('student');
            }
            $user->assignRole('teacher');
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher created successfully');




    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        //
        try {
            $teacher->delete();

            $user = \App\Models\User::find($teacher->user_id);
            $user->removeRole('teacher');
            $user->assignRole('student'); // Reassign to student role

            return redirect()->back()->with('success', 'Teacher deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            $error = ValidationException::withMessages([
                'error' => 'Failed to delete teacher: ' . $e->getMessage(),
            ]);
        }
    }
}
