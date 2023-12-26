<template>
  <div>
    <h2>Register</h2>
    <form @submit.prevent="register">
      <label for="firstName">First Name:</label>
      <input type="text" id="firstName" v-model="firstName" required>

      <label for="lastName">Last Name:</label>
      <input type="text" id="lastName" v-model="lastName" required>

      <label for="email">Email:</label>
      <input type="email" id="email" v-model="email" required>

      <label for="password">Password:</label>
      <input type="password" id="password" v-model="password" required>

      <button type="submit">Register</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      firstName: '',
      lastName: '',
      email: '',
      password: '',
    };
  },
  methods: {
    register() {
      axios.post('/api/register', {
        firstName: this.firstName,
        lastName: this.lastName,
        email: this.email,
        password: this.password,
      })
      .then(response => {
        console.log(response.data.message);
        // Optionally, you can redirect the user or show a success message
      })
      .catch(error => {
        console.error('Error during registration:', error);
        // Optionally, you can display an error message to the user
      });
    },
  },
};
</script>
