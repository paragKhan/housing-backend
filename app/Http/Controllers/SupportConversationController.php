<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupportConversation;
use App\Http\Requests\StoreSupportMessage;
use App\Models\SupportConversation;
use App\Models\SupportMessage;
use Illuminate\Http\Request;

class SupportConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSupportConversation $request)
    {
        $validated = $request->validated();

        $conversation = auth()->user()->support_conversations()->create([
            'language' => $validated['language'],
            'fname' => $validated['fname'],
            'lname' => $validated['lname'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
        ]);

        SupportMessage::create([
            'support_conversation_id' => $conversation->id,
            'message' => $validated['description'],
            'sender_type' => 'user'
        ]);

        return response()->json($conversation);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SupportConversation $supportConversation)
    {
        //todo add auth check for specific convo

        $messages = $supportConversation->support_messages;

        return response()->json(['messages' => $messages, 'conversation' => $supportConversation]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function sendMessage(StoreSupportMessage $request, $conversation_id){
        $conversation = SupportConversation::find($conversation_id);
        if(auth()->id() != $conversation->user_id){
            abort(403);
        }

        $message = SupportMessage::create([
            'support_conversation_id' => $conversation_id,
            'message' => $request->message,
            'sender_type' => 'user'
        ]);

        $conversation->updated_at = now();
        $conversation->status = 'active';
        $conversation->save();

        return response()->json($message);
    }

    public function getUsers($conversation){
        $conversation = SupportConversation::find($conversation);
        if(!$conversation) abort(404);
        if($conversation->user_id != auth()->id()) abort(403);

        $user['name'] = $conversation->user->fname . " " . $conversation->user->lname;
        $user['photo'] = $conversation->user->getFirstMediaUrl('photo');


        return response()->json($user);
    }

    public function myHistory(){
        return response()->json(auth()->user()->support_conversations()->paginate(20));
    }

    public function resolveConversation($conversation){
        $conversation = SupportConversation::find($conversation);
        if(!$conversation) abort(404);
        if($conversation->user_id != auth()->id()) abort(403);

        $conversation->status = 'solved';
        $conversation->save();

        return response()->json(['Solved']);
    }
}
