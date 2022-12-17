<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'data' => $this->formatChat(Chat::where(['nasabah_id' => $request->clientId])->get())
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            'data' => [
                'chat' => $this->sendChat($request->clientId, $request->message,  true),
                'jawaban' => $this->jawaban($request->clientId, $request->message)
            ]
        ]);
    }

    private function jawaban($clientId, $message)
    {
        return $this->sendChat($clientId, $message);
    }

    private function sendChat($clientId, $message, $fromMe = false)
    {
        $chat = Chat::create([
            'nasabah_id' => $clientId,
            'chat' => $message,
            'fromMe' => $fromMe
        ]);

        return [
            "id" => $chat->id,
            "chat" => $chat->chat,
            "fromMe" => $fromMe,
            "created_at" => $chat->created_at->format('H:i')
        ];
    }

    private function formatChat($chats)
    {
        foreach ($chats as $row) {
            $chat[] = [
                'id' => $row->id,
                'chat' => $row->chat,
                'fromMe' => $row->fromMe == 1 ? true : false,
                'identity' => $row->identity,
                'created_at' => $row->created_at->format('H:i'),
            ];
        }
        return $chat ?? [];
    }
}
