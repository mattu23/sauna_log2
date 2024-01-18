<template>
  <div class="d-flex flex-column justify-center align-center min-vh-100" style="background-image: url(''); background-size: cover;">
    <div style="flex-grow: 1;">
      <v-card elevation="16" width="900px" class="mx-auto mt-5" shaped>
        <v-card-title>
          <h2 class="mx-auto">サウナログへようこそ！</h2>
        </v-card-title>
        <v-card-text>
          <h3 style="text-align: center">あなたのサウナ体験を記録し、共有しましょう。</h3>
          <v-card-actions>
            <v-row style="text-align: center">
              <v-col>
                <v-btn to="/auth/login"  class="primary my-5" min-width="300">ログインまたは新規登録</v-btn>
              </v-col>
            </v-row>
          </v-card-actions>
        </v-card-text>
      </v-card>
    </div>
  </div>
</template>

<script>
export default {
  head() {
    return {
      title: 'Saunalog'
    }
  }
}
</script>
