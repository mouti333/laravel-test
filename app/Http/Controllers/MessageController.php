<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Message;
use App\Services\MessageServices\MessageService;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    private MessageService $messageService;
    private Message $messageModel;
    const MESSAGE_NOT_FOUND = "Message not found";

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
        $this->messageModel = new Message();
    }

    public function show(int $id): JsonResponse
    {
        $message = $this->messageService->getMessageById($id, $this->messageModel);

        if (!$message) {
            return response()->json(['message' => self::MESSAGE_NOT_FOUND], 404);
        }

        return response()->json($message);
    }

    public function getMessageMessageSend(int $id): JsonResponse
    {
        return response()->json($this->messageService->getUserMessagesSend($id, $this->messageModel));
    }

    public function getMessageMessageReciever(int $id): JsonResponse
    {
        return response()->json($this->messageService->getUserMessageRecieiver($id, $this->messageModel));
    }

    public function index(): JsonResponse
    {
        return response()->json($this->messageService->getAllMessages($this->messageModel));
    }


    public function store(StoreMessageRequest $request): JsonResponse
    {
        $message = $this->messageService->createMessage($request->validated(), $this->messageModel);

        return response()->json([
            'message' => "Message created successfully",
            'data' => $message
        ], 201);
    }

    public function update(UpdateMessageRequest $request, int $id): JsonResponse
    {
        $message = $this->messageService->getMessageById($id, $this->messageModel);

        $this->messageService->editMessage($message, $request->validated());

        return response()->json([
            'message' => "Message modified succesfully.",
            'data' => $message
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $message = $this->messageService->getMessageById($id, $this->messageModel);

        $this->messageService->deleteMessage($message);

        return response()->json(['message' => "Message deleted succesfully"], 202);
    }
}
