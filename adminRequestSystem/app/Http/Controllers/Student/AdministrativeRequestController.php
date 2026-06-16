<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\RequestType;
use App\Models\AdministrativeRequest;
use Exception;
use Illuminate\Http\Request;

class AdministrativeRequestController extends Controller
{
    public function index()
    {
        $myRequests = auth()->user()->requests()
            ->with('requestType')
            ->latest()
            ->get();

        return view('student.requests.index', compact('myRequests'));
    }

    public function create(Request $request)
    {
        $requestTypes = RequestType::all();

        if ($requestTypes->isEmpty()) {
            return redirect()->route('student.requests.index')
                ->with('error', 'Нема достапни типови на барања.');
        }

        $selectedTypeId = $request->query('type');

        return view('student.requests.create', compact('requestTypes', 'selectedTypeId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_type_id' => 'required|exists:request_types,id',
            'description' => 'required|string|min:10',
            'student_attachment' => 'nullable|file|mimes:pdf,jpeg,png,jpg,heic|max:7168', // Поправено на реални 7MB (7168 KB)
            'ai_feedback' => 'nullable|string',
        ]);

        try {
            $attachmentPath = null;
            if ($request->hasFile('student_attachment')) {
                $attachmentPath = $request->file('student_attachment')->store('attachments', 'public');
            }

            $adminRequest = AdministrativeRequest::create([
                'user_id' => auth()->id(),
                'request_type_id' => $validated['request_type_id'],
                'description' => $validated['description'],
                'student_attachment' => $attachmentPath, // Ова се мапира со базата
                'status' => 'pending',
                'ai_feedback' => $request->input('ai_feedback'),
            ]);

            return redirect()->route('student.requests.index')
                ->with('success', '✅ Барањето е успешно поднесено! ID: ' . $adminRequest->id);

        } catch (Exception $e) {
            return back()->with('error', 'Грешка при зачувување: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $request = auth()->user()->requests()->findOrFail($id);

        if (!in_array($request->status, ['pending', 'processing'])) {
            return redirect()->route('student.requests.index')
                ->with('error', 'Не можете да менувате барање кое веќе е финализирано.');
        }

        $requestTypes = RequestType::all();
        return view('student.requests.edit', compact('request', 'requestTypes'));
    }

    public function update(Request $request, $id)
    {
        $studentRequest = auth()->user()->requests()->findOrFail($id);

        $validated = $request->validate([
            'description' => 'required|string|min:10',
            'student_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $studentRequest->description = $validated['description'];

        if ($request->hasFile('student_attachment')) {
            $path = $request->file('student_attachment')->store('attachments', 'public');
            $studentRequest->student_attachment = $path;
        }

        $studentRequest->save();

        return redirect()->route('student.requests.show', $studentRequest->id)
            ->with('success', 'Барањето е успешно ажурирано.');
    }

    public function show($id)
    {
        $request = auth()->user()->requests()
            ->with('requestType')
            ->findOrFail($id);

        return view('student.requests.show', compact('request'));
    }

    public function simulatePayment($id)
    {
        $request = auth()->user()->requests()->findOrFail($id);

        if ($request->payment_status === 'paid') {
            return back()->with('error', 'Ова барање веќе е платено.');
        }

        $request->update([
            'payment_status' => 'paid',
        ]);

        return back()->with('success', 'Плаќањето е успешно.');
    }
}
