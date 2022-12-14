<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show(Request $request)
    {
        $chat = [];
        foreach (Chat::where(['identity' => $request->clientId])->get() as $row) {
            $chat[] = [
                'id' => $row->id,
                'chat' => $row->chat,
                'fromMe' => $row->fromMe == 1 ? true : false,
                'identity' => $row->identity,
                'created_at' => $row->created_at->format('H:i'),
            ];
        }

        return response()->json([
            'data' => $chat
        ]);
    }

    public function store(Request $request)
    {
        $chat = Chat::create([
            'identity' => $request->clientId,
            'chat' => $request->message,
            'fromMe' => 1
        ]);

        return response()->json([
            'data' => [
                'chat' => [
                    "id" => $chat->id,
                    "chat" => $chat->chat,
                    "fromMe" => true,
                    "created_at" => $chat->created_at->format('H:i')
                ],
                'jawaban' => $this->jawaban($request->clientId, $request->message),
            ]
        ]);
    }

    private function jawaban($clientId, $message)
    {
        $chat = Chat::create([
            'identity' => $clientId,
            'chat' => 'Harapanya ini nanti berisi jawaban dari bot',
            'fromMe' => 0
        ]);

        return [
            "id" => $chat->id,
            "chat" => $chat->chat,
            "fromMe" => false,
            "created_at" => $chat->created_at->format('H:i')
        ];
    }
}
