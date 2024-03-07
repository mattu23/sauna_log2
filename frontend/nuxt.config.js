import colors from 'vuetify/es5/util/colors'

export default {
  // Disable server-side rendering: https://go.nuxtjs.dev/ssr-mode
  ssr: false,

   // サーバーの設定
   server: {
    host: '0.0.0.0', // サーバーをすべてのネットワークインターフェースで利用可能にする
    port: 3000 // ポート番号（必要に応じて変更可能）
  },

  // Target: https://go.nuxtjs.dev/config-target
  target: 'server',

  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    titleTemplate: '%s',
    title: 'Saunalog',
    htmlAttrs: {
      lang: 'en',
    },
    script: [
      {
        vmid: 'gmap-api',
        src: `https://maps.googleapis.com/maps/api/js?key=AIzaSyDlL_1zEn0A5OsDWNyzRgHIMf1NxlnKtxU&libraries=places&callback=initMap`,
        async: true,
        defer: true
      },
      {
        innerHTML: `
          window.initMap = function() {
          };
        `,
        type: 'text/javascript',
        charset: 'utf-8'
      }
    ],
    __dangerouslyDisableSanitizers: ['script'],
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' },
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  // plugins: [],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/eslint
    '@nuxtjs/eslint-module',
    // https://go.nuxtjs.dev/vuetify
    '@nuxtjs/vuetify',
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/auth',
  ],
  axios: {
    baseURL: 'http://52.198.90.29:8000/api',
    credentials: true, // クロスオリジンのリクエストで認証情報（クッキー等）を含む
  },
  plugins: [
    './plugins/vee-validate.js'
  ],

  env: {
    API_ENDPOINT: process.env.API_ENDPOINT,
    API_BASE_URL: process.env.API_BASE_URL,
    googleMapsApiKey: process.env.GOOGLE_MAPS_API_KEY
  },
  // Vuetify module configuration: https://go.nuxtjs.dev/config-vuetify
  vuetify: {
    customVariables: ['~/assets/variables.scss'],
    theme: {
      dark: false,
      themes: {
        dark: {
          primary: colors.blue.darken2,
          accent: colors.grey.darken3,
          secondary: colors.amber.darken3,
          info: colors.teal.lighten1,
          warning: colors.amber.base,
          error: colors.deepOrange.accent4,
          success: colors.green.accent3,
        },
      },
    },
  },

  auth: {
    redirect: {
      home: '/list'
    },
    strategies: {
      local: {
        endpoints: {
          login: {
            url: "/signin",
            method: "post",
          },
          logout: {
            url: "/logout",
            method: "post",
          },
          user: false,
        },
      },
    },
  },

  // router: {
  //   middleware: ['auth'],
  // },
  
  build: {},
}
