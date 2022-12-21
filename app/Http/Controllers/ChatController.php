<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Chat;
use App\Models\Jawaban;
use Faker\Provider\Base;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show(Request $request)
    {
        $chat = Chat::where(['nasabah_id' => $request->clientId])->get();
        if ($chat->count() == 0) {
            $this->sendChat($request->clientId, 'Halo kk, ada yang bisa saya bantu?');
        }
        return response()->json([
            'data' => $this->formatChat(Chat::where(['nasabah_id' => $request->clientId])->get())
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            'data' => [
                'chat' => $this->sendChat($request->clientId, $request->message,  true),
                // Jika chat tidak dipahami
                // 'jawaban' => $this->sendChat($request->clientId, 'Maaf, saya tidak dapat memahami pertanyaan kakak nih.')
                // Jika barang nggak ada
                'jawaban' => $this->sendChat($request->clientId, 'Maaf, barang yang kakak cari mungkin masih belum tersedia.')
                // 'jawaban' => $this->answer($request->clientId, $request->message)
            ]
        ]);
    }

    private function answer($clientId, $message)
    {
        $maxMatch = 1;
        $search = explode(" ", preg_replace('/[^A-Za-z0-9\-]/', ' ', strtolower($message)));
        foreach (Jawaban::get() as $botReply) {
            foreach (Barang::get() as $barang) {
                foreach (explode(',', strtolower($botReply->keyword . ',' . preg_replace('/[^A-Za-z0-9\-]/', ',', $barang->nama_barang))) as $keyword) {
                    foreach ($search as $key) {
                        // Rumus Utamanya
                        if ($key == $keyword) {
                            if (isset($justMatch[$botReply->id . '#' . $barang->id])) {
                                $justMatch[$botReply->id . '#' . $barang->id]['match']++;
                                if ($maxMatch < $justMatch[$botReply->id . '#' . $barang->id]['match']) {
                                    $maxMatch++;
                                }
                            } else {
                                $justMatch[$botReply->id . '#' . $barang->id] = [
                                    'id_jawaban' => $botReply->id,
                                    'id_barang' => $barang->id,
                                    'nama' => $barang->nama_barang,
                                    'jumlah_keyword' => count($search),
                                    'match' => 1,
                                ];
                            }
                        }
                        // End Rumus Utamanya
                    }
                }
            }
        }

        if (isset($justMatch)) {
            $bestMatch = [];
            $bestMatchItem = [];
            foreach ($justMatch as $index => $match) {
                if ($match['match'] == $maxMatch) {
                    $bestMatch[] = $match;
                }
            }

            if (count($bestMatch) == 1) {
                $barang = Barang::find($bestMatch[0]['id_barang'])->toArray();
                $barang['harga_jual_formated'] = number_format($barang['harga_jual'], '0', '.', '.');
                $jawaban = Jawaban::find($bestMatch[0]['id_jawaban'])->jawaban;
                foreach ($barang as $index => $row) {
                    $jawaban = str_replace('{barang.' . $index . '}', $row, $jawaban);
                }
                return $this->sendChat($clientId, $jawaban);
            } else {
                foreach ($bestMatch as $row) {
                    $bestMatchItem[] = $row['nama'];
                }
                return $this->sendChat($clientId, 'Ada ' . count($bestMatch) . ' barang yang sesuai dengan pencaraian kamu : ' . implode(', ', $bestMatchItem));
            }
        } else {
            return $this->sendChat($clientId, 'Maaf, barang yang anda cari mungkin masih belum tersedia.');
        }
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
