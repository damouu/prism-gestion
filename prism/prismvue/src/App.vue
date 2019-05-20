<template>
  <div id="app">
    <div id="nav">

        <b-navbar toggleable="lg" type="dark" variant="danger">

          <b-container>

            <b-navbar-brand to="/">Prism</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

              <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav class="ml-auto">
                  <b-nav-item class="mr-5" to="/reservation">Reservation</b-nav-item>
                  <b-nav-item class="mr-5" href="#">Emprunts</b-nav-item>
                  <b-nav-item class="mr-5" to="/inventaire">Inventaire</b-nav-item>
                  <b-nav-item class="mr-5" href="#">Agenda</b-nav-item>
                </b-navbar-nav>
              </b-collapse>

          </b-container>

        </b-navbar>
    </div>


    <router-view/>
  </div>
</template>

<style lang="scss">

  @import 'node_modules/bootstrap/scss/bootstrap';
  @import 'node_modules/bootstrap-vue/src/index.scss';

#app {

}
</style>
