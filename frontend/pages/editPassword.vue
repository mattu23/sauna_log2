<template>
  <v-layout align-center justify-center>
    <v-card elevation="16" width="600px" class="mx-auto mt-5" shaped>
      <v-card-title>
        <h2 class="mx-auto">パスワード変更</h2>
      </v-card-title>
      <v-card-text>
        <v-form>
          <v-text-field
            label="現在のパスワード"
            v-model="password"
          ></v-text-field>
          <v-text-field
            label="新しいパスワード"
            v-model="newPassword"
            :rules="passwordRules"
          ></v-text-field>
          <v-text-field
            label="新しいパスワードの確認"
            v-model="newPasswordConfirm"
            :type="showNewPasswordConfirm ? 'text' : 'password'"
            :append-icon="showNewPasswordConfirm ? 'mdi-eye' : 'mdi-eye-off'"
            @click:append="showNewPasswordConfirm = !showNewPasswordConfirm"
            :rules="confirmPasswordRules"
          ></v-text-field>
          <br />
          <v-card-actions>
            <v-btn dark color="green" @click="updateUserPassword">編集</v-btn>
            <v-btn to="/list">戻る</v-btn>
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
      title: 'Password-Edit'
    }
  },
  data() {
    return {
      user: {
        password:'',
      },
      password: '',
      newPassword: '',
      newPasswordConfirm: '',
      showPassword: false,
      showNewPassword: false,
      showNewPasswordConfirm: false,
      passwordRules: [
        v => !!v || 'パスワードは必須です',
        v => v.length >= 8 || 'パスワードは8文字以上です',
        v => (v && v.length <= 32) || 'パスワードは32文字以内で入力してください',
        v => /[A-Z]/.test(v) || '少なくとも1つの大文字が必要です',
        v => /[a-z]/.test(v) || '少なくとも1つの小文字が必要です',
        v => /[0-9]/.test(v) || '少なくとも1つの数字が必要です',
        v => /^[A-Za-z0-9]+$/.test(v) || 'パスワードは半角英数字のみで入力してください',
      ],
      confirmPasswordRules: [
        v => !!v || 'パスワードの確認は必須です',
        v => v === this.newPassword || 'パスワードが一致しません',
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
      this.user.password = '';
    },
    async updateUserPassword() {
      if(this.newPassword !== this.newPasswordConfirm) {
        alert('新しいパスワードと確認用パスワードが一致しません。');
        return;
      }
        try{
          await this.$axios.put(`${process.env.API_ENDPOINT}/update-password/`, {
            password: this.password,
            newPassword: this.newPassword,
          });
          alert('パスワードを編集しました。');
          this.$router.push('/editUser');
        } catch(error){
          console.error(error);
          if(error.response && error.response.data && error.response.data.message) {
          alert(`エラー： ${error.response.data.message}`)
          }
        }
    },
  }
}  
</script>

