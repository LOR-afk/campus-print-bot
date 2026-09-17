<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessengerWebhookController extends Controller
{
    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode') ?? $request->query('hub.mode');
        $token = $request->query('hub_verify_token') ?? $request->query('hub.verify_token');
        $challenge = $request->query('hub_challenge') ?? $request->query('hub.challenge');

        if (
            $mode === 'subscribe' &&
            hash_equals(
                (string) config('services.meta.verify_token'),
                (string) $token
            )
        ) {
            return response($challenge, 200)
                ->header('Content-Type', 'text/plain');
        }

        return response('Forbidden', 403);
    }

public function receive(Request $request)
{
    $payload = $request->all();

    Log::info('Messenger webhook', [
        'payload' => $payload,
    ]);

    foreach ($payload['entry'] ?? [] as $entry) {
        Log::info('Messenger entry', [
            'entry' => $entry,
        ]);

        foreach ($entry['messaging'] ?? [] as $event) {
            Log::info('Messenger event', [
                'event' => $event,
            ]);

            $senderId = $event['sender']['id'] ?? null;

            Log::info('Messenger sender', [
                'sender_id' => $senderId,
                'has_text' => isset($event['message']['text']),
                'text' => $event['message']['text'] ?? null,
                'has_postback' => isset($event['postback']['payload']),
            ]);

            if (!$senderId) {
                continue;
            }

            if (isset($event['postback']['payload'])) {
                $this->handlePostback(
                    $senderId,
                    $event['postback']['payload']
                );

                continue;
            }

            if (isset($event['message']['text'])) {
                $this->handleMessage(
                    $senderId,
                    $event['message']['text']
                );
            }
        }
    }

    return response('EVENT_RECEIVED', 200);
}

    private function handleMessage(string $senderId, string $text): void
    {
        $text = strtolower(trim($text));

        if (in_array($text, ['status', 'printer status', 'check printer', 'check status'])) {
            $this->sendPrinterStatus($senderId);
            return;
        }

        $this->sendMainMenu($senderId);
    }

    private function handlePostback(string $senderId, string $payload): void
    {
        match ($payload) {
            'GET_STARTED' => $this->sendMainMenu($senderId),
            'CHECK_PRINTER_STATUS' => $this->sendPrinterStatus($senderId),
            'CHECK_AGAIN' => $this->sendPrinterStatus($senderId),
            'NOTIFY_ME' => $this->sendText(
                $senderId,
                'Notification setup will be available next. Please choose a printer you want to monitor.'
            ),
            default => $this->sendMainMenu($senderId),
        };
    }

    private function sendMainMenu(string $senderId): void
    {
        $this->sendButtonMessage(
            $senderId,
            'Welcome to Campus Print Bot! What would you like to do?',
            [
                [
                    'type' => 'postback',
                    'title' => 'Check Printer Status',
                    'payload' => 'CHECK_PRINTER_STATUS',
                ],
                [
                    'type' => 'postback',
                    'title' => 'Notify Me',
                    'payload' => 'NOTIFY_ME',
                ],
            ]
        );
    }

    private function sendPrinterStatus(string $senderId): void
    {
        $printers = Printer::where('is_active', true)
            ->orderBy('name')
            ->get();

        if ($printers->isEmpty()) {
            $this->sendText(
                $senderId,
                'No active printers are currently available.'
            );

            return;
        }

        $message = $printers->map(function (Printer $printer) {
            $icon = match ($printer->status) {
                'available' => '🟢',
                'maintenance' => '🟡',
                default => '🔴',
            };

            $status = ucfirst($printer->status);
            $paper = ucfirst($printer->paper_status);
            $toner = ucfirst($printer->toner_status);

            $lines = [
                "{$icon} {$printer->name}",
                "Status: {$status}",
                "Paper: {$paper}",
                "Toner: {$toner}",
                "Queue: {$printer->queue_count}",
                "Estimated wait: {$printer->estimated_wait_minutes} mins",
            ];

            if ($printer->issue_reason) {
                $lines[] = "Issue: {$printer->issue_reason}";
            }

            return implode("\n", $lines);
        })->implode("\n\n");

        $this->sendText(
            $senderId,
            "Latest printer status:\n\n{$message}"
        );

        $this->sendButtonMessage(
            $senderId,
            'What would you like to do next?',
            [
                [
                    'type' => 'postback',
                    'title' => 'Check Again',
                    'payload' => 'CHECK_AGAIN',
                ],
                [
                    'type' => 'postback',
                    'title' => 'Notify Me',
                    'payload' => 'NOTIFY_ME',
                ],
            ]
        );
    }

    private function sendText(string $senderId, string $text): void
    {
        $this->sendToMessenger([
            'recipient' => [
                'id' => $senderId,
            ],
            'messaging_type' => 'RESPONSE',
            'message' => [
                'text' => $text,
            ],
        ]);
    }

    private function sendButtonMessage(
        string $senderId,
        string $text,
        array $buttons
    ): void {
        $this->sendToMessenger([
            'recipient' => [
                'id' => $senderId,
            ],
            'messaging_type' => 'RESPONSE',
            'message' => [
                'attachment' => [
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'button',
                        'text' => $text,
                        'buttons' => $buttons,
                    ],
                ],
            ],
        ]);
    }

private function sendToMessenger(array $payload): void
{
    $pageId = config('services.meta.page_id');
    $token = config('services.meta.page_access_token');
    $version = config('services.meta.graph_version');

    $response = Http::withToken($token)
        ->post(
            "https://graph.facebook.com/{$version}/{$pageId}/messages",
            $payload
        );

    if ($response->failed()) {
        Log::error('Messenger API error', [
            'status' => $response->status(),
            'response' => $response->json(),
        ]);

        return;
    }

    Log::info('Messenger reply sent', [
        'response' => $response->json(),
    ]);
}
}