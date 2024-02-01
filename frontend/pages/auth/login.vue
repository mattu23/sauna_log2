<template>
  <v-layout align-center justify-center>
    <v-card elevation="15" width="600px" class="mx-auto mt-5" shaped>
      <v-card-title>
        <h2 class="mx-auto">ログイン</h2>
      </v-card-title>
      <v-card-text>
        <v-form ref="form" v-model="valid">
          <v-text-field
            v-model="user.email"
            :rules="emailRules"
            prepend-icon="mdi-account-circle"
            label="email"
            placeholder="example@email.com"
          ></v-text-field>
          <v-text-field
            v-bind:type="showPassword ? 'text' : 'password'"
            v-bind:append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
            @click:append="showPassword = !showPassword"
            v-model="user.password"
            :rules="passwordRules"
            prepend-icon="mdi-lock"
            label="password"
            placeholder="大文字・小文字・記号を含んで下さい"
            counter="32"
          ></v-text-field>
          <v-card-actions>
            <v-btn :disabled="!valid"  class="primary" @click="login" >ログイン</v-btn>
            <v-btn to="/" >戻る</v-btn>
          </v-card-actions>
        </v-form>
      </v-card-text>
    </v-card>
  </v-layout>
</template>

<script>
  export default {
  head() {
    return {
      title: 'Login'
    }
  },
  data() {
    return {
      valid: true,
      user: {
        email: '',
        password: '',
      },
      showPassword: false,
      emailRules: [
        v => !!v || 'Eメールは必須です',
        v => /.+@.+\..+/.test(v) || '有効なEメールを入力してください'
      ],
      passwordRules: [
        v => !!v || 'パスワードは必須です',
        v => v.length >= 8 || 'パスワードは8文字以上です',
        v => /[A-Z]/.test(v) || '少なくとも1つの大文字が必要です',
        v => /[a-z]/.test(v) || '少なくとも1つの小文字が必要です',
        v => /[0-9]/.test(v) || '少なくとも1つの数字が必要です',
      ]
    };
  },
  methods: {
     async login() {
       try {
         await this.$auth.loginWith('local', {data: this.user});
         alert('ログインに成功しました!');
       } catch (error) {
         console.error(error);
         if(error.response && error.response.data && error.response.data.message) {
            alert(`エラー： ${error.response.data.message}`)
         }
       }
    },
  },
}
</script>
