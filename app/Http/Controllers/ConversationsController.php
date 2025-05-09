<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConversationsController extends Controller
{
    public function create(Request $request){

        $validation = Validator::make($request->all(),[
            'seller_id' => 'required|exists:users,user_id',
            'msg_content' => 'required|string|min:1|max:65535',
        ]);

        if($validation->fails()){
            return response()->json([
                'errors' => $validation->errors()
            ], 422);
        }

        $validated = $validation->validated();

        $conv = Conversation::create([
            'conv_user_a' => auth()->user()->user_id,
            'conv_user_b' => $validated['seller_id'],
            'conv_user_a_last_read' => null,
            'conv_user_b_last_read' => null,
        ]);

        $msg = Message::create([
            'conv_id' => $conv->conv_id,
            'msg_sender' => auth()->user()->user_id,
            'msg_content' => $validated['msg_content']
        ]);

        $conv->conv_user_a_last_read = $msg->msg_id;
        $conv->save();

        return response()->json([
            'message' => "Conversation created and message sent successfully!"
        ]); 
    }

    public function message(Request $request, $convId){
        $validation = Validator::make($request->all(),[
            'msg_content' => 'required|string|min:1|max:65535',
        ]);

        if($validation->fails()){
            return response()->json([
                'errors' => $validation->errors()
            ], 422);
        }

        $validated = $validation->validated();

        Message::create([
            'conv_id' => $convId,
            'msg_sender' => auth()->user()->user_id,
            'msg_content' => $validated['msg_content']
        ]);

        return response()->json([
            'message' => "Message sent successfully!"
        ]); 
    }

    public function readMessage(Request $request, $convId){
        $conv = Conversation::find($convId);

        $lastMessage = Message::where('conv_id', $convId)->orderBy('msg_sent_at', 'desc')->first();

        if(auth()->user()->user_type == 1){
            $conv->conv_user_b_last_read = $lastMessage->msg_id;
        }else{
            $conv->conv_user_a_last_read = $lastMessage->msg_id;
        }

        $conv->save();

        return response()->json([
            'message' => "Message read successfully!"
        ]); 
    }

    public function messages(Request $request, $convId){
        return datatablesAssist($request->query(),[
            'searchFrom' => ["msg_description"],
            'orderBy' => 'msg_sent_at',
            'orderDir' => 'desc'
        ], function() use($convId){
            return Message::where('conv_id', $convId);
        });
    }
}
