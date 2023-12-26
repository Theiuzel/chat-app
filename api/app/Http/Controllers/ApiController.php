<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// api/app/Http/Controllers/ApiController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Friend;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    //user
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('MyApp')->accessToken;

        return response()->json(['user' => $user, 'access_token' => $token]);
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = $this->create($request->all());

        // Optionally, you can generate a token and return it for immediate login
        $token = $user->createToken('MyApp')->accessToken;

        return response()->json(['user' => $user, 'access_token' => $token], 201);
    }

    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function searchUsers(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'like', "%$query%")->get();

        return response()->json(['users' => $users]);
    }

    //rooms
    public function createChatroom(Request $request)
    {
        $name = $request->input('name');
        $isGroup = $request->input('is_group', false);

        $chatroom = Chatroom::create([
            'name' => $name,
            'is_group' => $isGroup,
        ]);

        return response()->json(['chatroom' => $chatroom]);
    }

    public function searchRooms(Request $request)
    {
        $query = $request->input('query');
        $rooms = Room::where('name', 'like', "%$query%")->get();

        return response()->json(['rooms' => $rooms]);
    }

    public function getChatrooms()
    {
        $chatrooms = Chatroom::all();

        return response()->json(['chatrooms' => $chatrooms]);
    }

    public function getChatMessages(Request $request)
    {
        $roomId = $request->input('room_id');
        $messages = ChatMessage::where('room_id', $roomId)->get();

        return response()->json(['messages' => $messages]);
    }

    public function sendMessage(Request $request)
    {
        $user = Auth::user(); // Assuming user is authenticated
        $roomId = $request->input('room_id');
        $content = $request->input('content');

        $message = new ChatMessage([
            'user_id' => $user->id,
            'room_id' => $roomId,
            'content' => $content,
        ]);
        $message->save();

        // Optionally, broadcast the message to other users in the room using Laravel Echo and WebSocket

        return response()->json(['message' => $message]);
    }

    public function addFriend($id)
{
    $friend = User::find($id);

    if (!$friend) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Implement logic to send a friend request
    Auth::user()->sendFriendRequest($friend);

    return response()->json(['message' => 'Friend request sent']);
}


    public function acceptFriend($id)
{
    $friend = User::find($id);

    if (!$friend) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Implement logic to accept a friend request
    Auth::user()->acceptFriendRequest($friend);

    return response()->json(['message' => 'Friend request accepted']);
}

public function rejectFriend($id)
{
    $friend = User::find($id);

    if (!$friend) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Implement logic to reject a friend request
    Auth::user()->rejectFriendRequest($friend);

    return response()->json(['message' => 'Friend request rejected']);
}

public function sendFriendRequest($friendId)
    {
        $friend = User::find($friendId);

        if (!$friend) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Check if a friend request already exists
        if ($friend->receivedFriendRequests()->where('sender_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Friend request already sent']);
        }

        // Send friend request
        $friendRequest = new FriendRequest([
            'sender_id' => Auth::id(),
            'receiver_id' => $friendId,
            'status' => 'pending',
        ]);
        $friendRequest->save();

        return response()->json(['message' => 'Friend request sent']);
    }

    public function getFriendRequests()
    {
        $friendRequests = Auth::user()->receivedFriendRequests;

        return response()->json(['friend_requests' => $friendRequests]);
    }

    public function acceptFriendRequest($friendRequestId)
    {
        $friendRequest = FriendRequest::find($friendRequestId);

        if (!$friendRequest) {
            return response()->json(['error' => 'Friend request not found'], 404);
        }

        // Check if the authenticated user is the receiver of the friend request
        if ($friendRequest->receiver_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Accept friend request
        $friendRequest->status = 'accepted';
        $friendRequest->save();

        return response()->json(['message' => 'Friend request accepted']);
    }

    public function rejectFriendRequest($friendRequestId)
    {
        $friendRequest = FriendRequest::find($friendRequestId);

        if (!$friendRequest) {
            return response()->json(['error' => 'Friend request not found'], 404);
        }

        // Check if the authenticated user is the receiver of the friend request
        if ($friendRequest->receiver_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Reject friend request
        $friendRequest->delete();

        return response()->json(['message' => 'Friend request rejected']);
    }


public function getFriends()
{
    $friends = Auth::user()->friends;

    return response()->json(['friends' => $friends]);
}


public function getMessages($friendId)
{
    $friend = User::find($friendId);

    if (!$friend) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Implement logic to fetch chat messages between the authenticated user and the specified friend
    $messages = Message::where(function ($query) use ($friendId) {
        $query->where('user_id', Auth::id())->where('friend_id', $friendId);
    })->orWhere(function ($query) use ($friendId) {
        $query->where('user_id', $friendId)->where('friend_id', Auth::id());
    })->orderBy('created_at', 'asc')->get();

    return response()->json(['messages' => $messages]);
}

}

