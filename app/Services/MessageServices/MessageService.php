<?php

namespace App\Services\MessageServices;

use App\Models\Message;

class MessageService
{
    public function getMessageById(int $id, Message $messageModel)
    {
        return $messageModel::where('id', $id)->first();
    }

    public function getUserMessagesSend(int $id, Message $messageModel)
    {
        return $messageModel::where('sender', $id)->get();
    }

    public function getUserMessageRecieiver(int $id, Message $messageModel)
    {
        return $messageModel::where('receiver', $id)->get();
    }

    public function getAllMessages(Message $messageModel)
    {
        return $messageModel::orderByDesc('id')->get();
    }

    public function createMessage(array $data, Message $messageModel): Message
    {
        return $messageModel::create($this->messageData($data));
    }

    public function editMessage(Message $Message, array $data): void
    {
         $Message->update($this->messageData($data));
    }

    public function deleteMessage(Message $Message): void
    {
        $Message->delete();
    }

    private function messageData(array $data)
    {
        return [
            'sender' => $data['sender'],
            'receiver' =>$data['receiver'],
            'subject' =>$data['subject'],
            'content' =>$data['content'],
        ];
    }
}
