<template>
  <v-app>
    <v-navigation-drawer color="#06283D" dark v-model="drawer" :mini-variant="miniVariant" :clipped="clipped" fixed app>
      <v-list>
        <v-list-item v-for="(item, i) in items" :key="i" :to="item.to" router exact>
          <v-list-item-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title v-text="item.title" />
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-app-bar color="#06283D" dark :clipped-left="clipped" fixed app>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-toolbar-title v-text="title" />
      <v-spacer />
      <v-btn icon @click.stop="rightDrawer = !rightDrawer">
        <v-icon>mdi-menu</v-icon>
      </v-btn>
    </v-app-bar>
    <v-main>
      <v-container>
        <Nuxt />
      </v-container>
    </v-main>
    <v-footer :absolute="!fixed" app>
      <span>&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
  </v-app>
</template>

<script>
  export default {
    name: 'DefaultLayout',
    data() {
      return {
        clipped: false,
        drawer: false,
        fixed: false,
        items: [{
            icon: 'mdi-account',
            title: 'Clientes',
            to: '/'
          },
          {
            icon: 'mdi-clipboard-list',
            title: 'Produtos',
            to: '/produtos'
          },
          {
            icon: 'mdi-cart-check',
            title: 'Pedidos',
            to: '/pedidos'
          }
        ],
        miniVariant: false,
        right: true,
        rightDrawer: false,
        title: 'Olá!'
      }
    }
  }

</script>
