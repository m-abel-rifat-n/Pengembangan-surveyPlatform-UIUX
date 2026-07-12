<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    protected $baseUrl = 'https://api.groq.com/openai/v1/chat/completions';

    public function generateSurveyRecommendation($methodType, $resumeDescription, $surveyTheme)
    {
        try {
            $prompt = $this->buildRecommendationPrompt($methodType, $resumeDescription, $surveyTheme);

            $response = Http::withToken(config('services.groq.api_key'))
                ->timeout(60)
                ->post($this->baseUrl, [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Anda adalah seorang ahli UX/UI dan peneliti pengalaman pengguna yang berpengalaman. Berikan analisis mendalam, solusi praktis, dan rekomendasi yang dapat ditindaklanjuti dalam bahasa Indonesia yang profesional dan mudah dipahami.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 3000,
                    'temperature' => 0.7
                ]);

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'];

                // Clean the response from any think tags
                $cleanedContent = $this->cleanAiResponse($content);

                return $cleanedContent;
            }

            throw new \Exception('Groq API request failed: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Groq AI Recommendation Error: ' . $e->getMessage());
            return 'Maaf, rekomendasi AI sementara tidak tersedia. Silakan coba lagi nanti.';
        }
    }

    private function cleanAiResponse($content)
    {
        // Remove <think> tags and their content
        $cleaned = preg_replace('/<think>.*?<\/think>/is', '', $content);

        // Remove any remaining think tags
        $cleaned = preg_replace('/<\/?think>/i', '', $cleaned);

        // Clean up extra whitespace
        $cleaned = preg_replace('/\n{3,}/', "\n\n", $cleaned);

        return trim($cleaned);
    }

    private function buildRecommendationPrompt($methodType, $resumeDescription, $surveyTheme)
    {
        $methodNames = [
            'SUS'      => 'System Usability Scale (SUS)',
            'TAM'      => 'Technology Acceptance Model (TAM)',
            'NASA-TLX' => 'Raw NASA Task Load Index (NASA-TLX)',
            'VisAWI-S' => 'Visual Aesthetics of Websites Inventory Short (VisAWI-S)',
        ];

        $methodName = $methodNames[$methodType] ?? $methodType;

        $prompt = "Berilah solusi dan saran dari hasil survey dengan metode {$methodName} berikut ini:\n\n";
        $prompt .= "Tema Survey: {$surveyTheme}\n\n";
        $prompt .= "Hasil Analisis:\n{$resumeDescription}\n\n";

        if ($methodType === 'SUS') {
            $prompt .= "Berdasarkan hasil analisis SUS di atas, berikan:\n\n";
            $prompt .= "1. **Analisis Mendalam**\n";
            $prompt .= "   - Interpretasi skor SUS dan kategori yang dicapai\n";
            $prompt .= "   - Identifikasi area yang perlu diperbaiki berdasarkan rata-rata jawaban\n\n";

            $prompt .= "2. **Rekomendasi Perbaikan UI/UX**\n";
            $prompt .= "   - Solusi spesifik untuk meningkatkan usability\n";
            $prompt .= "   - Perbaikan desain interface yang disarankan\n";
            $prompt .= "   - Prioritas perbaikan (high, medium, low)\n\n";

            $prompt .= "3. **Saran Pengujian Lanjutan**\n";
            $prompt .= "   - Target skor SUS yang realistis untuk iterasi berikutnya\n";
        } elseif ($methodType === 'TAM') {
            $prompt .= "Berdasarkan hasil analisis TAM di atas, berikan:\n\n";
            $prompt .= "1. **Analisis Hubungan Variabel**\n";
            $prompt .= "   - Identifikasi faktor yang paling berpengaruh terhadap penerimaan teknologi\n";
            $prompt .= "   - Analisis area yang perlu diperkuat\n\n";

            $prompt .= "2. **Rekomendasi Desain dan Fitur**\n";
            $prompt .= "   - Perbaikan fitur berdasarkan feedback pengguna\n";
            $prompt .= "   - Optimisasi user experience untuk meningkatkan kemudahan penggunaan\n";
            $prompt .= "   - Penambahan fitur yang dapat meningkatkan perceived usefulness\n\n";

            $prompt .= "3. **Strategi Adopsi Pengguna**\n";
            $prompt .= "   - Pendekatan untuk meningkatkan actual system use\n";
            $prompt .= "   - Program pelatihan atau onboarding yang disarankan\n";
            $prompt .= "   - Komunikasi value proposition yang lebih efektif\n";
        } elseif ($methodType === 'NASA-TLX') {
            $prompt .= "Berdasarkan hasil analisis NASA-TLX di atas, berikan:\n\n";
            $prompt .= "1. **Analisis Beban Kerja**\n";
            $prompt .= "   - Interpretasi tingkat beban kerja keseluruhan (rendah/sedang/tinggi)\n";
            $prompt .= "   - Identifikasi dimensi dengan beban tertinggi yang perlu menjadi prioritas\n";
            $prompt .= "   - Analisis hubungan antar dimensi (misal: frustrasi tinggi vs performa rendah)\n\n";

            $prompt .= "2. **Rekomendasi Pengurangan Beban Kerja**\n";
            $prompt .= "   - Solusi spesifik untuk dimensi dengan skor tinggi (beban berat)\n";
            $prompt .= "   - Perbaikan desain interaksi untuk mengurangi mental demand dan effort\n";
            $prompt .= "   - Optimisasi alur kerja untuk mengurangi temporal demand\n";
            $prompt .= "   - Prioritas perbaikan (high, medium, low)\n\n";

            $prompt .= "3. **Saran Peningkatan Pengalaman Pengguna**\n";
            $prompt .= "   - Strategi untuk meningkatkan skor performa pengguna\n";
            $prompt .= "   - Cara mengurangi tingkat frustrasi pada sistem\n";
            $prompt .= "   - Target skor NASA-TLX yang realistis untuk iterasi berikutnya\n";
        } elseif ($methodType === 'VisAWI-S') {
            $prompt .= "Berdasarkan hasil analisis VisAWI-S di atas, berikan:\n\n";
            $prompt .= "1. **Analisis Estetika Visual**\n";
            $prompt .= "   - Interpretasi skor estetika visual keseluruhan\n";
            $prompt .= "   - Identifikasi dimensi visual yang paling lemah dan paling kuat\n";
            $prompt .= "   - Analisis dampak estetika terhadap pengalaman pengguna\n\n";

            $prompt .= "2. **Rekomendasi Perbaikan Desain Visual**\n";
            $prompt .= "   - Solusi spesifik untuk meningkatkan simplicity (kesederhanaan tata letak)\n";
            $prompt .= "   - Saran untuk memperbaiki diversity (keberagaman elemen visual)\n";
            $prompt .= "   - Rekomendasi penggunaan warna (colorfulness) yang lebih efektif\n";
            $prompt .= "   - Peningkatan craftsmanship (profesionalisme desain)\n";
            $prompt .= "   - Prioritas perbaikan (high, medium, low)\n\n";

            $prompt .= "3. **Strategi Peningkatan Daya Tarik Visual**\n";
            $prompt .= "   - Panduan desain untuk mencapai estetika yang konsisten\n";
            $prompt .= "   - Referensi pendekatan desain yang dapat diterapkan\n";
            $prompt .= "   - Target skor VisAWI-S yang realistis untuk iterasi berikutnya\n";
        }

        $prompt .= "\nBerikan jawaban yang terstruktur, praktis, dan dapat ditindaklanjuti. Gunakan bahasa Indonesia yang profesional dan mudah dipahami.";

        return $prompt;
    }
}
