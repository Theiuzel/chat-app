<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // app/Models/User.php

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friend_user', 'user_id', 'friend_id')
            ->withPivot('status') // You may want to track the status of the friend request
            ->withTimestamps();
    }
}

class User extends Model {
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friend_user', 'user_id', 'friend_id')
            ->withPivot('status') // You may want to track the status of the friend request
            ->withTimestamps();
    }

    public function sentFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'sender_id');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'receiver_id');
    }
    
    public function sendFriendRequestTo(User $friend)
    {
        $this->friends()->attach($friend, ['status' => 'pending']);
    }

    public function acceptFriendRequestFrom(User $friend)
    {
        $this->friends()->updateExistingPivot($friend->id, ['status' => 'accepted']);
        $friend->friends()->attach($this, ['status' => 'accepted']);
    }

    public function rejectFriendRequestFrom(User $friend)
    {
        $this->friends()->detach($friend);
    }
}
