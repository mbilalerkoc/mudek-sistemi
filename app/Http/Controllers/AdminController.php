<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\Messages\UserMessages;
use App\Enums\Messages\CourseMessages;
use App\Enums\Messages\StudentMessages;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\AcademicTitleRepositoryInterface;
use App\Services\CourseService;
use App\Services\UserService;
use App\Repositories\Interfaces\StudentCourseRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Services\DashboardService;

class AdminController extends Controller
{
    public function __construct(
    private UserRepositoryInterface $userRepository,
    private CourseRepositoryInterface $courseRepository,
    private AcademicTitleRepositoryInterface $academicTitleRepository,
    private CourseService $courseService,
    private UserService $userService,
    private StudentCourseRepositoryInterface $studentCourseRepository,
    private StudentRepositoryInterface $studentRepository,
    private DashboardService $dashboardService,
) {}
    
    public function dashboard()
{
    $data = $this->dashboardService->getAdminDashboardData();

    return view('admin.dashboard', $data);
}
     public function loginHistory()
{
    // Tüm 'auth' loglarını kronolojik sırada çekiyoruz
    $logs = \Spatie\Activitylog\Models\Activity::where('log_name', 'auth')
                    ->with('causer')
                    ->oldest()
                    ->get();

    $sessions = [];
    $activeLogins = [];

    foreach ($logs as $log) {
        $userId = $log->causer_id;
        if (!$userId) continue;

        if ($log->description === 'Sisteme giriş yaptı') {
            $activeLogins[$userId] = [
                'user' => $log->causer,
                'login_at' => $log->created_at,
                'ip' => $log->properties['ip'] ?? '-',
                'user_agent' => $log->properties['user_agent'] ?? '-',
                'logout_at' => null,
                'duration' => 'Devam ediyor...'
            ];
        } elseif ($log->description === 'Sistemden çıkış yaptı') {
            if (isset($activeLogins[$userId])) {
                $loginTime = $activeLogins[$userId]['login_at'];
                $logoutTime = $log->created_at;
                
                // Süre hesaplama (Saniye cinsinden fark)
                $diffInSeconds = $loginTime->diffInSeconds($logoutTime);
                $duration = $this->formatDuration($diffInSeconds);

                $activeLogins[$userId]['logout_at'] = $logoutTime;
                $activeLogins[$userId]['duration'] = $duration;

                $sessions[] = $activeLogins[$userId];
                unset($activeLogins[$userId]);
            } else {
                // Giriş kaydı bulunamayıp direkt çıkış yapılan durumlar
                $sessions[] = [
                    'user' => $log->causer,
                    'login_at' => null,
                    'ip' => $log->properties['ip'] ?? '-',
                    'user_agent' => $log->properties['user_agent'] ?? '-',
                    'logout_at' => $log->created_at,
                    'duration' => '-'
                ];
            }
        }
    }

    foreach ($activeLogins as $active) {
        $sessions[] = $active;
    }

    $sessions = collect($sessions)->sortByDesc(function ($session) {
        return $session['login_at'] ?? $session['logout_at'];
    });

    return view('admin.logs.login_history', compact('sessions'));
}

private function formatDuration($seconds)
{
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds / 60) % 60);
    $secs = $seconds % 60;

    $result = [];
    if ($hours > 0) $result[] = "{$hours} sa";
    if ($minutes > 0) $result[] = "{$minutes} dk";
    if ($secs > 0 || empty($result)) $result[] = "{$secs} sn";

    return implode(' ', $result);
}
    // ==========================================
    // USER MANAGEMENT
    // ==========================================

    public function userIndex()
    {
        $users = $this->userRepository->getAllWithTitles();
        return view('admin.users.index', compact('users'));
    }

    public function userCreate()
    {
        $academicTitles = $this->academicTitleRepository->all();
        return view('admin.users.ekle', compact('academicTitles'));
    }

    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'surname'           => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:8',
            'role'              => 'required|in:super_admin,user,student',
            'academic_title_id' => 'nullable|exists:academic_titles,id',
        ]);

        $this->userService->createUser($validated);

        return redirect()->route('admin.users.index')
                         ->with('success', UserMessages::CREATED->value);
    }

    public function userEdit($id)
    {
        $user = $this->userRepository->find($id);
        $academicTitles = $this->academicTitleRepository->all();
        return view('admin.users.edit', compact('user', 'academicTitles'));
    }

    public function userUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'surname'           => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $id,
            'password'          => 'nullable|min:8',
            'role'              => 'required|in:super_admin,user',
            'academic_title_id' => 'nullable|exists:academic_titles,id',
        ]);

        $this->userService->updateUser($id, $validated);

        return redirect()->route('admin.users.index')
                         ->with('success', UserMessages::UPDATED->value);
    }

    public function userDestroy($id)
    {
        $this->userRepository->delete($id);
        return redirect()->route('admin.users.index')
                         ->with('success', UserMessages::DELETED->value);
    }

    // ==========================================
    // COURSE MANAGEMENT
    // ==========================================

    public function courseIndex()
    {
        $courses = $this->courseRepository->allWithUsers();
        $users = $this->userRepository->getByRole('user');
        return view('admin.courses.index', compact('courses', 'users'));
    }

    public function courseStore(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'code'    => 'required|string|unique:courses,code',
            'credits' => 'nullable|integer|min:1|max:10',
            'semester'=> 'nullable|string|max:50',
        ]);

        $this->courseService->createCourse($validated);

        return redirect()->back()
                         ->with('success', CourseMessages::CREATED->value);
    }

    public function courseEdit($id)
    {
        $course = $this->courseRepository->find($id);
        $users = $this->userRepository->getByRole('user');
        return view('admin.courses.edit', compact('course', 'users'));
    }

    public function courseUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'code'    => 'required|string|unique:courses,code,' . $id,
            'credits' => 'nullable|integer|min:1|max:10',
            'semester'=> 'nullable|string|max:50',
        ]);

        $this->courseService->updateCourse($id, $validated);

        return redirect()->route('admin.courses.index')
                         ->with('success', CourseMessages::UPDATED->value);
    }

    public function courseDestroy($id)
    {
        $this->courseRepository->delete($id);
        return redirect()->route('admin.courses.index')
                         ->with('success', CourseMessages::DELETED->value);
    }

    // ==========================================
    // COURSE - USER ASSIGNMENTS
    // ==========================================

    public function assignTeacher(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id'   => 'required|exists:users,id',
        ]);

        $this->courseService->assignTeacher($validated['course_id'], $validated['user_id']);

        return redirect()->back()
                         ->with('success', CourseMessages::USER_ASSIGNED->value);
    }

    public function removeTeacher(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id'   => 'required|exists:users,id',
        ]);

        $this->courseService->removeTeacher($validated['course_id'], $validated['user_id']);

        return redirect()->back()
                         ->with('success', CourseMessages::USER_REMOVED->value);
    }

    // ==========================================
    // COURSE - STUDENT MANAGEMENT
    // ==========================================

    public function dersOgrencileri($id)
    {
        $course = $this->courseRepository->find($id);
        $kayitliOgrenciler = $this->studentCourseRepository->getByCourse($id);
        $kayitliIds = $kayitliOgrenciler->pluck('student_id');
        $tumOgrenciler = $this->studentRepository->all();
        $kayitsizOgrenciler = $tumOgrenciler->whereNotIn('id', $kayitliIds);

        return view('admin.courses.ogrenciler', compact('course', 'kayitliOgrenciler', 'kayitsizOgrenciler'));
    }

    public function dersOgrenciEkle(Request $request, $id)
    {
        $request->validate([
            'student_ids'   => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        foreach ($request->student_ids as $studentId) {
            $this->courseService->enrollStudent($id, $studentId);
        }

        return redirect()->back()
                         ->with('success', count($request->student_ids) . ' ' . CourseMessages::STUDENT_ENROLLED->value);
    }

    public function dersOgrenciCikar($id, $student_id)
    {
        $this->courseService->unenrollStudent($id, $student_id);

        return redirect()->back()
                         ->with('success', CourseMessages::STUDENT_REMOVED->value);
    }

    public function dersOgrenciCikarToplu(Request $request, $id)
    {
        $request->validate([
            'student_ids'   => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        foreach ($request->student_ids as $studentId) {
            $this->courseService->unenrollStudent($id, $studentId);
        }

        return redirect()->back()
                         ->with('success', count($request->student_ids) . ' ' . CourseMessages::STUDENT_UNENROLLED->value);
    }
}