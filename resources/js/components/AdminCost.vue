<template>
  <i-form label-position="right" :label-width="150" :style="{width: '70%', margin:'auto'}">
    <Form-item :label="item.token_name" v-for="(item, index) in poundage" :key="index">
      <Row :gutter="16">
        <i-col span="10">
          <i-input v-model="item.poundage" placeholder="请输入手续费"></i-input>
        </i-col>
        <i-col span="2">
          <i-button type="primary" @click="postAdSpaces(item)">提交</i-button>
        </i-col>
      </Row>
    </Form-item>
  </i-form>
</template>
<script>
export default {
  data() {
    return {
      poundage: []
    };
  },
  created() {
    this.$axios
      .get("admin/retutoken")
      .then(response => {
        this.poundage = response.data;
        console.log(this.poundage);
      })
      .catch(function(error) {
        console.log(error);
      });
  },
  methods: {
    release(name, poundage) {
      this.$axios({
        method: "post",
        url: "admin/poundage",
        params: {
          token_name: name,
          poundage: poundage
        }
      })
        .then(response => {
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    postAdSpaces(item) {
      this.$axios({
        method: "post",
        url: "admin/poundage",
        params: {
          id: item.id,
          poundage: item.poundage,
          token_name: item.token_name
        }
      }).then(response => {});
    }
  }
};
</script>
