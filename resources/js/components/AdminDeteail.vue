<template>
  <div>
    <Row>
      <i-col span="9">共注册：{{ count.login_count }}</i-col>
      <i-col span="9">当日注册：{{ count.today_login_count }}</i-col>
    </Row>
    <Row>
      <i-col span="9">共充值（usdt）：{{ count.usdt_count }}</i-col>
      <i-col span="9">当日充值（usdt）：{{ count.today_usdt_count }}</i-col>
      <i-col span="6">
        <router-link to="/Users">返回</router-link>
      </i-col>
    </Row>
    <hr>
    <Row style="margin-top: 10px;">
      <i-col span="2" style="text-align: left; line-height: 30px;">用户名搜索：</i-col>
      <i-col span="6">
        <i-input v-model="data.name" placeholder="请输入用户名"></i-input>
      </i-col>
      <i-col span="2">
        <Button type="primary" @click="release">搜索</Button>
      </i-col>
    </Row>
    <i-table width="90%" border :columns="columns2" :data="data3"></i-table>
    <Page :total="pages.total" :page-size="pages.pageSize" show-total @on-change="changepage"/>
  </div>
</template>
<script>
export default {
  data() {
    return {
      columns2: [
        {
          title: "用户名称·",
          key: "name",
          width: 400
        },
        {
          title: "数量",
          key: "add_number",
          width: 400
        },
        {
          title: "充值时间",
          key: "updated_at",
          width: 400
        }
      ],
      data3: [],
      data: {
        name: ""
      },
      count: {},
      pages: {
        current: "", // 当前页码
        total: 1, // 数据总数
        pageSize: 0 //每页条数
      }
    };
  },
  created() {
    this.release(1);
  },
  methods: {
    release(index) {
      this.$axios({
        method: "post",
        url: "admin/usdtlist",
        params: {
          name: this.data.name,
          page: index
        }
      })
        .then(response => {
          let {
            data,
            login_count,
            today_login_count,
            today_usdt_count,
            usdt_count
          } = response.data;
          this.data3 = data;
          this.count = {
            login_count,
            today_login_count,
            today_usdt_count,
            usdt_count
          };
          this.pages.total = response.data.total;
          this.pages.pageSize = response.data.per_page;
          this.pages.current = response.data.from;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    changepage(index){
    this.release(index);
    }
  }
};
</script>
