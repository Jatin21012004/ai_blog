<?php
// AI Helper for Ollama (local LLaMA models)

function ai_generate($prompt, $model = "llama2") {
    $data = [
        "model" => $model,
        "prompt" => $prompt,
        "stream" => false
    ];

    $ch = curl_init("http://localhost:11434/api/generate");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($response, true);
    return $json['response'] ?? "AI error.";
}
