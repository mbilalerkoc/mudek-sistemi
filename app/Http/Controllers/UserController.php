<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Enums\Messages\UserMessages;
use App\Services\DashboardService;

class UserController extends Controller
{
   public function __construct(
    private CourseRepositoryInterface $courseRepository,
    private DashboardService $dashboardService
) {}

    public function index()
    {
        $data = $this->dashboardService->getUserDashboardData(auth()->user());

        return view('user.dashboard', $data);
    }

    public function profile()
    {
        return view('user.profile');
    }

    public function dersler()
    {
        $courses = $this->courseRepository->getCoursesByTeacher(auth()->user());
        return view('user.dersler.index', compact('courses'));
    }

    public function profileUpdate(Request $request)
{
    $user = auth()->user();

    $validated = $request->validate([
        'name'             => 'required|string|max:255',
        'surname'          => 'required|string|max:255',
        'email'            => 'required|email|unique:users,email,' . $user->id,
        'current_password' => 'nullable|required_with:new_password|current_password',
        'new_password'     => 'nullable|min:8|confirmed', // confirmed kuralı 'new_password_confirmation' alanını otomatik doğrular
    ]);

    $user->name = $validated['name'];
    $user->surname = $validated['surname'];
    $user->email = $validated['email'];

    // Eğer yeni şifre girildiyse güncellemeyi yap
    if (!empty($validated['new_password'])) {
        $user->password = Hash::make($validated['new_password']);
    }

    $user->save();

    return redirect()->route('user.profile')->with('success', UserMessages::PROFILE_UPDATED->value);
}
}