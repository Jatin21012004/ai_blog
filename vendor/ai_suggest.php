<?php
// ai_blog/vendor/ai_suggest.php

header("Content-Type: text/plain");

$data = json_decode(file_get_contents("php://input"), true);
$type = $data['type'] ?? '';
$content = trim($data['content'] ?? '');

if ($content === '') {
    echo "Please enter some content first.";
    exit;
}

// Define prompt based on request
switch ($type) {
    case 'title':
        $prompt = "Suggest a catchy blog title for the following content:\n\n$content";
        break;
    case 'summary':
        $prompt = "Summarize the following blog content in 2-3 sentences:\n\n$content";
        break;
    case 'keywords':
        $prompt = "Generate 5-10 SEO-friendly keywords (comma separated) for this blog:\n\n$content";
        break;
    default:
        echo "Invalid request.";
        exit;
}

// Call Ollama (make sure Ollama is running locally)
$ch = curl_init("http://localhost:11434/api/generate");
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    CURLOPT_POSTFIELDS => json_encode([
        "model" => "llama3",  // or "llama2", depending on installed model
        "prompt" => $prompt
    ])
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo "Error connecting to AI server.";
    exit;
}
curl_close($ch);

// Ollama streams JSON lines → extract "response"
$lines = explode("\n", trim($response));
$output = "";
foreach ($lines as $line) {
    $json = json_decode($line, true);
    if (isset($json['response'])) {
        $output .= $json['response'];
    }
}

echo trim($output);
