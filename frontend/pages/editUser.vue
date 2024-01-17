<template>
  <v-layout align-center justify-center>
    <v-card elevation="16" width="600px" class="mx-auto mt-5" shaped>
      <v-card-title>
        <h2 class="mx-auto">ユーザー情報の編集</h2>
      </v-card-title>
      <v-card-text>
        <v-form>
          <v-text-field
            prepend-icon="mdi-account-circle"
            label="username"
            counter="16"
            :rules="usernameRules"
            v-model="user.username"
          ></v-text-field>
          <v-text-field
            prepend-icon="mdi-email-outline"
            label="email"
            placeholder="example@email.com"
            :rules="emailRules"
            v-model="user.email"
          />
          <br />
          <p>
          パスワードの編集は<nuxt-link to="/editPassword">こちら</nuxt-link>
          </p>
          <v-card-actions>
            <v-btn dark color="green" @click="updateUserData">編集</v-btn>
            <v-btn to="/list">戻る</v-btn>
          </v-card-actions>
        </v-form>
        <br />
        <v-btn  dark color="red darken-1" style="margin: 5px;"  min-width="100" @click="deleteUser()" >ユーザーの削除</v-btn>
      </v-card-text>
    </v-card>
  </v-layout>
</template>




<script>

export default {
  head() {
    return {
      title: 'User-Edit'
    }
  },
  data() {
    return {
      user: {
        username:'',
        email:'',
      },
      usernameRules: [
        v => !!v || 'ユーザー名は必須です',
        v => (v && v.length <= 16) || 'ユーザー名は16文字以内で入力してください',
      ],
      emailRules: [
        v => !!v || 'Eメールは必須です',
        v => /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(v) || '有効なEメールを入力してください',
      ],
    };
  },
  async mounted() {
    await this.fetchUserData();
  },
  methods: {
    async fetchUserData() {
      const response = await this.$axios.get(`${process.env.API_ENDPOINT}/getUser`);
      this.user = response.data;
    },
    async updateUserData() {
      try{
        await this.$axios.put(`${process.env.API_ENDPOINT}/update-user/`, this.user);
        alert('ユーザー情報を編集しました。');
      } catch(error){
        console.error(error);
        if(error.response && error.response.data && error.response.data.message) {
          alert(`エラー： ${error.response.data.message}`)
        }
      }
    },
    async deleteUser() {
      if(confirm('本当にユーザーを削除しますか？')) {
        try {
          await this.$axios.delete(`${process.env.API_ENDPOINT}/delete-user/`);
          alert('ユーザー情報を削除しました。トップページに戻ります。');
          this.$router.push('/');
        } catch(error) {
          alert('削除できませんでした。もう一度お試しください。');
        }
      } else {
        // ユーザーがキャンセルを選択した場合、何もしない
      }
    },
  }
}  
</script>

