<?php

namespace App\Actions;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AskAiAgentAction
{
    public function execute(string $description, string $requestTypeName): string
    {
        $prompt = "Context: You are an expert administrative validator at FINKI University.\n"
            . "Task: Analyze the student's request description and provide feedback strictly in Macedonian language.\n\n"
            . "Student Request Type: $requestTypeName\n"
            . "Student Input Description: \"$description\"\n\n"
            . "CRITICAL INSTRUCTIONS:\n"
            . "1. Do NOT write generic greetings, intros, or polite filler text (e.g., 'Здраво колега', 'Јас сум асистент').\n"
            . "2. Use the exact Markdown template provided below.\n"
            . "3. Keep sentences short, logical, and direct.\n\n"
            . "4. No user info is necessary, the users are logged in.\n\n"
            . "5. Use Macedonian not serbian or bulgarian, .\n\n"
            . "OUTPUT TEMPLATE (STRICTLY IN MACEDONIAN):\n"
            . "###  Оценка на барањето\n"
            . "[Овде напиши 1-2 кратки реченици дали описот е јасен и дали ги содржи потребните информации за ова барање]\n\n"
            . "### Што недостига / Треба да се смени\n"
            . "- [Недостиг 1 или 'Описот е комплетен']\n"
            . "- [Недостиг 2]\n\n"
            . "### 💡 Конкретен предлог-текст\n"
            . "\"Текстот што студентот треба да го копира и поднесе\"";

        try {
            $response = Http::timeout(60)->post('http://127.0.0.1:11434/api/generate', [
                'model' => 'qwen2.5:7b',
                'prompt' => $prompt,
                'stream' => false,
                'options' => [
                    'temperature' => 0.3,
                    'top_p' => 0.9
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['response'])) {
                    return trim($data['response']);
                }
            }

            return "Ве молиме обидете се повторно.";

        } catch (Exception $e) {
            Log::error('Настана грешка: ' . $e->getMessage());

            return "Не може да се воспостави конекција со асистентот.\n\nПробајте повторно за неколку минути.";
        }
    }
}
