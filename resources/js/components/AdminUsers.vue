<template>
  <div>
    <Row>
      <i-col span="9">共注册：{{date.login_count}} 人</i-col>
      <i-col span="9">当日注册：{{date.today_login_count}} 人</i-col>
    </Row>
    <Row>
      <i-col span="9">共充值（usdt）：{{date.usdt_count}}</i-col>
      <i-col span="9">当日充值（usdt）：{{date.today_usdt_count}}</i-col>
      <i-col span="6">
        <router-link to="/Deteail">充值查询</router-link>
      </i-col>
    </Row>
    <hr>
    <Row style="margin-top: 10px;">
      <i-col span="1" style="text-align: center; line-height: 30px;">时间：</i-col>
      <i-col span="6">
        <DatePicker placeholder="选择日期" format="yyyy-MM-dd" @on-change="start_time"></DatePicker>
      </i-col>
      <i-col span="1" style="text-align: center">-</i-col>
      <i-col span="6">
        <DatePicker placeholder="选择日期" format="yyyy-MM-dd" @on-change="end_time"></DatePicker>
      </i-col>
    </Row>
    <Row style="margin-top: 10px;">
      <i-col span="2" style="text-align: left; line-height: 30px;">id搜索：</i-col>
      <i-col span="5">
        <i-input v-model="data.id" placeholder="请输入id"></i-input>
      </i-col>
      <i-col span="2" style="text-align: left; line-height: 30px;">手机号搜索：</i-col>
      <i-col span="5">
        <i-input v-model="data.ipone" placeholder="请输入手机号码"></i-input>
      </i-col>
      <i-col span="2" style="text-align: left; line-height: 30px;">用户名搜索：</i-col>
      <i-col span="5">
        <i-input v-model="data.userName" placeholder="请输入用户名"></i-input>
      </i-col>
      <i-button type="primary" @click="this.release">搜索</i-button>
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
          title: "#ID",
          key: "id",
          width: 200
        },
        {
          title: "用户名称",
          key: "name",
          width: 300
        },
        {
          title: "手机号",
          key: "tel",
          width: 300
        },
        {
          title: "注册时间",
          key: "created_at",
          width: 300
        }
      ],
      data3: [],
      data: {
        start_time: "",
        end_time: "",
        userName: "",
        ipone: "",
        id: ""
      },
      date: {},
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
    start_time(date) {
      console.log(date);
      this.data.start_time = date;
    },
    end_time(date) {
      // console.log(date)
      this.data.end_time = date;
    },
    release(index) {
      this.$axios({
        method: "post",
        url: "admin/userlist",
        params: {
          id: this.data.id,
          name: this.data.userName,
          tel: this.data.ipone,
          beginTime: this.data.start_time,
          endTime: this.data.end_time,
          page: index
        }
      })
        .then(response => {
          console.log("response");
          console.log(response.data.data.data);
          this.data3 = response.data.data.data;
          let {
            login_count,
            today_login_count,
            today_usdt_count,
            usdt_count
          } = response.data;
          this.date = {
            login_count,
            today_login_count,
            today_usdt_count,
            usdt_count
          };
          this.pages.total = response.data.data.total;
          this.pages.pageSize = response.data.data.per_page;
          this.pages.current = response.data.data.from;
          // console.log(response.data);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    changepage(index) {
      this.release(index);
    }
  }
};
</script>
