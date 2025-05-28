<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SubscribeTransaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSubscribeTransactionRequest;

class FrontController extends Controller
{
    //
    public function index() {

        $courses = Course::with(['category', 'teacher', 'students'])
            ->orderByDesc('id')
            ->get();

        return view('front.index', compact('courses'));
    }
    public function details(Course $course) {

        return view('front.details', compact('course'));
    }

    public function category(Category $category) {

        $courses = $category->courses()->get();
        return view('front.category', compact('courses'));
    }

    public function learning(Course $course, $courseVideoId) {

        $user = Auth::user();
        // Cek apakah user sudah berlangganan
        if(!$user->hasActiveSubscription()) {
            return redirect()->route('front.pricing');
        }

        $video = $course->course_videos->firstWhere('id', $courseVideoId);

        $user->courses()->syncWithoutDetaching($course->id);

        return view('front.learning', compact('course', 'video'));
    }

    public function pricing() {
        $user = Auth::user();

        // Cek apakah user sudah berlangganan
        if($user->hasActiveSubscription()) {
            return redirect()->route('front.index')
                ->with('success', 'You already have an active subscription.');
        }
        return view('front.pricing');
    }

    public function checkout() {
        $user = Auth::user();

        // Cek apakah user sudah berlangganan
        if($user->hasActiveSubscription()) {
            return redirect()->route('front.index')
                ->with('success', 'You already have an active subscription.');
        }
        return view('front.checkout');
    }

    public function checkout_store(StoreSubscribeTransactionRequest $request) {
        $user = Auth::user();

        // Cek apakah user sudah berlangganan
        if($user->hasActiveSubscription()) {
            return redirect()->route('front.learning', ['course' => $request->course_id, 'courseVideoId' => 1])
                ->with('success', 'You already have an active subscription.');
        }

        // Simpan transaksi berlangganan
        DB::transaction(function () use ($request, $user) {

            $validated = $request->validated();

            if($request->hasFile('proof')) {
                $proofPath = $request->file('proof')->store('proof', 'public');
                $validated['proof'] = $proofPath;
            }

            $validated['user_id'] = $user->id;
            $validated['total_amount'] = 429000; // 429.000 statis
            $validated['is_paid'] = false;



            $transaction = SubscribeTransaction::create($validated);

        });
        return redirect()->route('dashboard');
    }
}
