<template>
    <div>
      <!-- User Search -->
      <div>
        <label for="userSearch">Search Users:</label>
        <input type="text" id="userSearch" v-model="userSearch">
        <button @click="searchUsers">Search Users</button>
        <!-- Display search results for users -->
        <ul>
          <li v-for="user in userSearchResults" :key="user.id">
            {{ user.name }}
            <!-- Add a button to send a friend request or initiate a chat -->
          </li>
        </ul>
      </div>
  
      <!-- Room Search -->
      <div>
        <label for="roomSearch">Search Rooms:</label>
        <input type="text" id="roomSearch" v-model="roomSearch">
        <button @click="searchRooms">Search Rooms</button>
        <!-- Display search results for rooms -->
        <ul>
          <li v-for="room in roomSearchResults" :key="room.id">
            {{ room.name }}
            <!-- Add a button to join the room or initiate a chat -->
          </li>
        </ul>
      </div>
  
      <!-- Send Message -->
      <div>
        <label for="message">Type a message:</label>
        <textarea id="message" v-model="newMessage"></textarea>
        <button @click="sendMessage">Send Message</button>
      </div>
  
      <!-- Display Chat Messages -->
      <div>
        <h3>Chat Messages</h3>
        <ul>
          <li v-for="message in chatMessages" :key="message.id">
            {{ message.sender }}: {{ message.content }}
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        userSearch: '',
        userSearchResults: [],
        roomSearch: '',
        roomSearchResults: [],
        newMessage: '',
        chatMessages: [],
      };
    },
    methods: {
      searchUsers() {
        axios.get(`/api/search-users?query=${this.userSearch}`)
          .then(response => {
            this.userSearchResults = response.data.users;
          })
          .catch(error => {
            console.error('Error searching for users:', error);
          });
      },
      searchRooms() {
        axios.get(`/api/search-rooms?query=${this.roomSearch}`)
          .then(response => {
            this.roomSearchResults = response.data.rooms;
          })
          .catch(error => {
            console.error('Error searching for rooms:', error);
          });
      },
      sendMessage() {
        // Assuming you have a selected user or room
        const recipientId = 1; // Replace with the actual recipient ID
  
        axios.post('/api/send-message', {
          recipientId,
          content: this.newMessage,
        })
        .then(response => {
          this.chatMessages.push(response.data.message);
          this.newMessage = ''; // Clear the input field after sending
        })
        .catch(error => {
          console.error('Error sending message:', error);
        });
      },
    },
  };
  </script>
  