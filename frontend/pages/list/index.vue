<template>
  <div id="app">
    <v-layout justify-center>
      <v-card width="600px">
        <h1> サウナリスト一覧</h1>
        <h2 v-if="user">ようこそ、{{ user.username }} さん</h2>
        <v-btn to="/register"  class="primary my-5" min-width="200" style="margin: 10px;">サウナログの新規登録</v-btn>
        <v-container>
          <v-list dense>
            <v-list-item-group>
              <v-list-item v-for="log in saunaLogs" :key="log.id">
                <v-list-item-content>
                  <v-list-item-title style="font-size: 1.0em;">施設名：{{ log.name }}</v-list-item-title>
                  <v-list-item-subtitle>エリア：{{ log.area }}</v-list-item-subtitle>
                  <v-list-item-subtitle>評価：{{ log.rank }}</v-list-item-subtitle>
                  <v-list-item-subtitle>コメント：{{ log.comment }}</v-list-item-subtitle>
                </v-list-item-content>
                <v-list-item-action>
                  <v-btn :to="`/list/${log.id}`" color="green darken-1" text>編集</v-btn>
                  <v-btn color="red darken-1" text @click="deleteLog(log.id)">削除</v-btn>
                </v-list-item-action>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-container>
        <v-btn to="/editUser" dark color="green" min-width="100" style="margin: 10px;">ユーザーの編集</v-btn>
        <v-btn dark color="red darken-1" @click="logout()" style="margin: 10px" >ログアウト</v-btn>
      </v-card>
    </v-layout>
  </div>
</template>

<script>

export default {
  head() {
    return {
      title: 'Saunalog-List'
    }
  },
  data() {
    return {
      saunaLogs: [],
      user: null
    };
  },
  created() {
    this.getLogData();
  },
  async mounted() {
    await this.fetchUserData();
  },
  methods: {
    async getLogData() {
      try {
        const response = await this.$axios.get(`${process.env.API_ENDPOINT}/saunalog/`, {withCredentials: true});
        this.saunaLogs = response.data;
      } catch(error) {
        console.error(error);
        alert('正常にデータを取得できませんでした。ログイン状態を確認してください。');
      }
    },
    async fetchUserData() {
      const response = await this.$axios.get(`${process.env.API_ENDPOINT}/getUser`);
      this.user = response.data;
    },
    async deleteLog(id) {
      try {
        await this.$axios.delete(`${process.env.API_ENDPOINT}/saunalog/${id}`);
        this.getLogData();
        alert('データを削除しました。');
      } catch(error) {
        if(error.response && error.response.data && error.response.data.message) {
          alert(`エラー： ${error.response.data.message}`)
        }
      }
    },
    async logout() {
      try {
        await this.$auth.logout();
        alert('ログアウトしました。');
        this.$router.push('/');
      } catch(error) {
        console.error(error);
        if(error.response && error.response.data && error.response.data.message) {
          alert(`エラー： ${error.response.data.message}`)
        }
      }
    }
  }
}  
</script>