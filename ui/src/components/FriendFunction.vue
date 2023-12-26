<template>
    <div>
      <h2>Friend Functions</h2>
  
      <!-- Search Friends -->
      <div>
        <label for="friendSearch">Search Friends:</label>
        <input type="text" id="friendSearch" v-model="friendSearch">
        <button @click="searchFriends">Search</button>
        <!-- Display search results for friends -->
        <ul>
          <li v-for="user in friendSearchResults" :key="user.id">
            {{ user.name }}
            <button @click="sendFriendRequest(user.id)">Send Friend Request</button>
          </li>
        </ul>
      </div>
  
      <!-- Friend Requests -->
      <div>
        <h3>Friend Requests</h3>
        <!-- Display pending friend requests -->
        <ul>
          <li v-for="request in friendRequests" :key="request.id">
            {{ request.name }}
            <button @click="acceptFriendRequest(request.id)">Accept</button>
            <button @click="rejectFriendRequest(request.id)">Reject</button>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        friendSearch: '',
        friendSearchResults: [],
        friendRequests: [], // Assuming you have a structure for friend requests
      };
    },
    methods: {
        searchFriends() {
      // Implement logic to search for friends and update friendSearchResults
      axios.get(`/api/search-friends?query=${this.friendSearch}`)
        .then(response => {
          this.friendSearchResults = response.data.friends;
        })
        .catch(error => {
          console.error('Error searching for friends:', error);
        });
    },
    sendFriendRequest(friendId) {
      // Implement logic to send a friend request
      axios.post(`/api/send-friend-request/${friendId}`)
        .then(response => {
          console.log(response.data.message);
          // Optionally, update the UI or show a confirmation message
        })
        .catch(error => {
          console.error('Error sending friend request:', error);
        });
    },
    acceptFriendRequest(friendId) {
      // Implement logic to accept a friend request
      axios.post(`/api/accept-friend-request/${friendId}`)
        .then(response => {
          console.log(response.data.message);
          // Optionally, update the UI or show a confirmation message
        })
        .catch(error => {
          console.error('Error accepting friend request:', error);
        });
    },
    rejectFriendRequest(friendId) {
      // Implement logic to reject a friend request
      axios.post(`/api/reject-friend-request/${friendId}`)
        .then(response => {
          console.log(response.data.message);
          // Optionally, update the UI or show a confirmation message
        })
        .catch(error => {
          console.error('Error rejecting friend request:', error);
        });
    },
    },
  };
  </script>
  