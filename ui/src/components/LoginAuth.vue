<!-- ui/src/components/Login.vue -->

<template>
    <div>
      <h2>Login</h2>
      <form @submit.prevent="login">
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="email" required>
  
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="password" required>
  
        <button type="submit">Login</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';

  export default {
    data() {
      return {
        email: '',
        password: '',
      };
    },
    methods: {
      login() {
        // Implement login logic and API call using axios
        const formData = {
          email: this.email,
          password: this.password,
        };
  
        // Make an API call to login endpoint
        // You may want to show loading spinner and handle errors
        axios.post('/login', formData)
          .then(response => {
            console.log(response.data);
            // Optionally, you can redirect the user or perform other actions after successful login
          })
          .catch(error => {
            console.error('Login failed:', error.response.data);
            // Handle login error, e.g., show an error message
          });
      },
    },
  };
  </script>
  