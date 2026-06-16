<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdministrativeRequest;
use App\Models\RequestType;
use App\Models\User;
use Illuminate\Http\Request;

class RequestController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $typeId = $request->input('type_id');
        $status = $request->input('status');

        $query = AdministrativeRequest::with(['user', 'requestType']);

        if (!empty($search)) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('id', 'LIKE', '%' . ltrim(substr($search, 2), '0') . '%');
            });
        }

        if (!empty($typeId)) {
            $query->where('request_type_id', $typeId);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $requests = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $requestTypes = RequestType::orderBy('name', 'asc')->get();

        return view('admin.requests.index', compact('requests', 'requestTypes', 'search', 'typeId', 'status'));
    }


    public function show($id)
    {
        $request = AdministrativeRequest::with(['user', 'requestType'])->findOrFail($id);

        return view('admin.requests.show', compact('request'));
    }


    public function create()
    {
        $students = User::where('is_admin', false)->orderBy('name', 'asc')->get();
        $requestTypes = RequestType::orderBy('name', 'asc')->get();

        return view('admin.requests.create', compact('students', 'requestTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'request_type_id' => 'required|exists:request_types,id',
            'description' => 'required|string',
        ]);

        $newRequest = AdministrativeRequest::create([
            'user_id' => $validated['user_id'],
            'request_type_id' => $validated['request_type_id'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return redirect()->route('admin.requests.show', $newRequest->id)
            ->with('success', 'Барањето е успешно креирано во име на студентот.');
    }

    public function updateStatus(Request $request, $adminRequest)
    {
        $administrativeRequest = AdministrativeRequest::findOrFail($adminRequest);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,approved,rejected',
            'admin_note' => 'nullable|string|max:1000',
            'issued_document' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = [
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? null,
        ];

        if ($request->hasFile('issued_document')) {
            $data['issued_document'] = $request->file('issued_document')->store('issued_docs', 'public');
        }

        $administrativeRequest->update($data);

        return redirect()->route('admin.requests.index')
            ->with('success', 'Промените се успешно зачувани!');
    }


    public function typesIndex()
    {
        $requestTypes = RequestType::withCount('administrativeRequests')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.requests.service_config', compact('requestTypes'));
    }


    public function typesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:request_types,name',
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0',
        ]);

        RequestType::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
        ]);

        return redirect()->route('admin.service_config.index')
            ->with('success', 'Новиот тип на барање е успешно додаден!');
    }

    public function typesDestroy($id)
    {
        $type = RequestType::findOrFail($id);

        if ($type->administrativeRequests()->count() > 0) {
            return redirect()->back()->with('error', 'Овој тип не може да се избрише бидејќи веќе постојат студентски барања поднесени со него.');
        }

        $type->delete();
        return redirect()->route('admin.service_config.index')
            ->with('success', 'Услугата е успешно отстранета од системот.');
    }
}
