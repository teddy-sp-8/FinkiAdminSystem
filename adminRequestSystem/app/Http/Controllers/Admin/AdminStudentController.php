<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdministrativeRequest;
use Illuminate\Http\Request;

class AdminStudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::where('is_admin', false);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('id', 'LIKE', '%' . ltrim(substr($search, 2), '0') . '%');
            });
        }

        $students = $query->withCount('requests')
            ->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.students.index', compact('students', 'search'));
    }

    public function show($id)
    {
        $student = User::where('is_admin', false)->findOrFail($id);

        $requests = AdministrativeRequest::where('user_id', $student->id)
            ->with('requestType')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.students.show', compact('student', 'requests'));
    }
}
