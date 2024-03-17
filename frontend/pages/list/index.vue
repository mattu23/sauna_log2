<template>
  <div id="app">
    <v-app-bar app>
      <v-dialog v-model="dialog" max-width="600px" @close="resetDialog">
      <sauna-map :key="selectedLog ? selectedLog.id : 0" :log-id="selectedLog.id" :saunaName="selectedLog.name" v-if="selectedLog"></sauna-map>
      </v-dialog>
      <v-toolbar-title>サウナリスト一覧</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-menu offset-y>
      <template v-slot:activator="{ on, attrs }">
        <v-btn text v-bind="attrs" v-on="on">{{ user.username }} （{{ userRoles }}） ：マイページ</v-btn>
      </template>
      <v-list>
        <v-list-item to="/editUser">
          <v-list-item-title>ユーザー情報変更</v-list-item-title>
        </v-list-item>
        <v-list-item to="/editPassword">
          <v-list-item-title>パスワード変更</v-list-item-title>
        </v-list-item>
        <v-list-item @click="deleteUser()">
          <v-list-item-title>アカウント削除</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
    <v-btn text @click="logout()">ログアウト</v-btn>
    </v-app-bar>
    <v-layout justify-center>
      <v-card class="mx-auto"> 
        <v-btn to="/register" class="primary my-5" min-width="200" style="margin: 10px;">サウナログの新規登録</v-btn>
        <v-btn color="success" @click="exportCsv" class="my-3">CSV出力</v-btn>
        <v-container>
          <v-list dense>
            <v-list-item-group>
              <div id="map-section">
                <v-list-item v-for="log in saunaLogs" :key="log.id" :to="`/list/${log.id}`">
                <v-list-item-content>
                  <v-list-item-title  style="font-size: 16px;">{{ log.name }}</v-list-item-title>
                  <v-list-item-subtitle>エリア：{{ log.area }}</v-list-item-subtitle>
                  <v-list-item-subtitle>評価：{{ log.rank }}</v-list-item-subtitle>
                  <v-list-item-subtitle>コメント：{{ log.comment }}</v-list-item-subtitle>
                  <v-list-item-subtitle>投稿者：{{ log.user.username }}</v-list-item-subtitle>
                </v-list-item-content>
                <v-list-item-action v-if="user && user.roles && (user.roles.some(role => role.name === 'admin') || log.user.id === user.id)">
                <v-btn icon @click="() => deleteLog(log.id)">
                    <v-icon color="red">mdi-delete</v-icon>
                </v-btn>
                </v-list-item-action>
              </v-list-item>
             </div>
            </v-list-item-group>
          </v-list>
        </v-container>
        <v-pagination
          v-model="page"
          :length="totalPages"
          @input="getLogData"
          circle
        ></v-pagination>
      </v-card>
    </v-layout>
  </div>
</template>



<script>
import SaunaMap from '@/components/SaunaMap';


export default {
  head() {
    return {
      title: 'Saunalog-List'
    }
  },
  components: {
    SaunaMap,
  },
  data() {
    return {
      saunaLogs: [],
      user: {},
      page: 1,
      totalPages: 0,
      itemsPerPage: 5,
      userRoles: '', 
      selectedLog: null,
      dialog: false,
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
        const response = await this.$axios.get(`${process.env.API_ENDPOINT}/saunalog/all`, {
          params: {
            page: this.page,
            limit: this.itemsPerPage
          },
          withCredentials: true
        });
        this.saunaLogs = response.data.logs;
        this.totalPages = response.data.totalPages;
      } catch(error) {
        console.error(error);
        alert('正常にデータを取得できませんでした。ログイン状態を確認してください。');
      }
    },
    async fetchUserData() {
      const response = await this.$axios.get(`${process.env.API_ENDPOINT}/getUser`);
      this.user = response.data;
      this.userRoles = this.user.roles.map(role => role.name).join(', ');
    },
    async deleteLog(id) {
      if(confirm('本当にデータを削除しますか？')) {
        try {
          await this.$axios.delete(`${process.env.API_ENDPOINT}/saunalog/${id}`);
          this.getLogData();
          alert('データを削除しました。');
        } catch(error) {
          if(error.response && error.response.data && error.response.data.message) {
            alert(`エラー： ${error.response.data.message}`)
          }
        }
      }
    },
    exportCsv() {
      const today = new Date().toISOString().slice(0, 10);
      const fileName = `saunalogs-${today}.csv`;

      this.$axios({
        url: `${process.env.API_ENDPOINT}/saunalogs-csv`,
        method: 'GET',
        responseType: 'blob',
      }).then((response) => {
        if(!response || !response.data) {
          alert('CSVデータの取得に失敗しました。もう一度お試しください。');
          return;
        }
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', fileName);
        document.body.appendChild(link);
        link.click();
        link.remove();
      }).catch((error) => {
        console.error('CSV出力エラー: ', error);
        alert('CSV出力に失敗しました。');
      });
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
    selectLog(log) {
      this.resetDialog();
      this.$nextTick(() => {
        this.selectedLog = log;
        this.dialog = true;
      })
    },
    resetDialog() {
      this.selectedLog = null;
      this.dialog = false;
    },
  }
}  
</script>