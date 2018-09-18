<template>
  <v-toolbar fixed app dark color="primary">
    <v-toolbar-side-icon @click.stop="toggleDrawer" v-if="authenticated"></v-toolbar-side-icon>
    <v-toolbar-title>
      <router-link :to="{ name: 'welcome' }" class="white--text">
        {{ appName }}
      </router-link>
    </v-toolbar-title>
    <v-spacer></v-spacer>

    <!-- Authenticated -->
    <template v-if="authenticated">
      <progress-bar :show="busy"></progress-bar>
      <div class="hidden-xs-only">
        <v-btn flat :to="{ name: 'account.profile' }">
          <v-icon>account_circle</v-icon> &nbsp;
          {{ user.name }}
        </v-btn>
        <v-btn icon @click.prevent="logout" :title="$t('logout')">
          <v-icon>power_settings_new</v-icon>
        </v-btn>
      </div>
      <div class="hidden-sm-and-up">
        <v-menu offset-y>
          <v-btn slot="activator" icon>
            <v-icon>account_circle</v-icon>
          </v-btn>
          <v-list light>
            <v-list-tile :to="{ name: 'account.profile' }">
              <v-list-tile-title>{{ user.name }}</v-list-tile-title>
            </v-list-tile>
            <v-list-tile @click.prevent="logout">
              <v-list-tile-title>{{ $t('logout') }}</v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-menu>
      </div>
    </template>

    <!-- Guest -->
    <template v-else>
      <v-btn flat :to="{ name: 'login' }">{{ $t('login') }}</v-btn>
      <v-btn flat :to="{ name: 'register' }">{{ $t('register') }}</v-btn>
    </template>
  </v-toolbar>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  props: {
    drawer: {
      type: Boolean,
      required: true
    }
  },

  data: () => ({
    appName: window.config.appName,
    busy: false
  }),

  computed: mapGetters({
    user: 'authUser',
    authenticated: 'authCheck'
  }),

  methods: {
    toggleDrawer () {
      this.$emit('toggleDrawer');
    },
    async logout () {
      this.busy = true;

      if (this.drawer) {
        this.toggleDrawer();
      }

      // Log out the user.
      await this.$store.dispatch('logout');
      this.busy = false;

      // Redirect to login.
      this.$router.push({ name: 'login' });
    }
  }
};
</script>

<style lang="stylus" scoped>

.v-toolbar__title .router-link-active
  text-decoration: none !important;

</style>
