<script>
import axios from "axios";
import { RouterLink, RouterView } from "vue-router";
import ProductList from "./components/ProductList.vue";

export default {
  components:{
    ProductList
  },
};
</script>

<template>
  <ProductList/>
</template>

