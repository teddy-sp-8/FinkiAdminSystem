<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\AskAiAgentAction;
use Throwable;

class AIAnalysisController extends Controller
{
    public function analyze(Request $request, AskAiAgentAction $aiAgent)
    {
        $request->validate([
            'description' => 'required|string',
            'request_type' => 'nullable|string',
        ]);

        $description = $request->input('description');
        $requestType = $request->input('request_type', 'општо барање');

        try {
            $suggestion = $aiAgent->execute($description, $requestType);

            return response()->json([
                'success' => true,
                'suggestion' => $suggestion
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'suggestion' => "Error: " . $e->getMessage() . "File: " . $e->getFile() . " line: " . $e->getLine()
            ]);
        }
    }
}
