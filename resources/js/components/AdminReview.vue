<template>
  <div>
    <Row>
      <i-col span="2" style="text-align: left; line-height: 30px;">币种：</i-col>
      <i-col span="4" style="padding-right:10px">
        <Select v-model="token_symbol" filterable @on-change="release">
          <Option v-for="item in cityList" :value="item.id" :key="item.id">{{ item.token_name }}</Option>
        </Select>
      </i-col>
      <i-col span="2" style="text-align: left; line-height: 30px;">状态:</i-col>
      <i-col span="4" style="padding-right:10px">
        <Select v-model="status" filterable @on-change="release">
          <Option v-for="item in statuss" :value="item.status" :key="item.value">{{ item.label }}</Option>
        </Select>
      </i-col>
      <i-col span="2">
        <i-button type="ghost" style="margin-left: 8px" @click="this.TermReview">配置审核</i-button>
      </i-col>
    </Row>
    <i-table width="90%" border :columns="columns2" :data="data3"></i-table>
    <Page :total="pages.total" :page-size="pages.pageSize" show-total @on-change="changepage"/>
  </div>
</template>
<script>
let moment = require("moment");
export default {
  data() {
    return {
      columns2: [
        {
          title: "序号",
          key: "id",
          width: 100,
          fixed: "left"
        },
        {
          title: "币种",
          key: "token_name",
          width: 100
        },
        {
          title: "提现地址",
          key: "address",
          width: 300
        },
        {
          title: "提现金额",
          key: "balance",
          width: 200
        },
        {
          title: "手续费",
          key: "poundage",
          width: 200
        },
        {
          title: "状态",
          width: 200,
          render: (h, params) => {
            let status = params.row.status;
            if (status == 0) {
              return h("span", "待审核");
            }
            if (status == 1) {
              return h("span", "已通过");
            }
            if (status == 3) {
              return h("span", "未通过");
            }
          }
        },
        {
          title: "日期",
          width: 200,
          render: (h, params) => {
            let price = this.time(params.row.created_time * 1000);
            return h("Input", {
              props: {
                type: "text",
                value: price,
                disabled: "disabled"
              }
            });
          }
        },
        {
          title: "操作",
          key: "action",
          fixed: "right",
          width: 200,
          render: (h, params) => {
            if (params.row.status != 0) {
              return;
            }
            return h("div", [
              h(
                "Button",
                {
                  props: {
                    type: "success",
                    size: "small"
                  },
                  style: {
                    marginLeft: "30px"
                  },
                  on: {
                    click: () => {
                      this.examine(params.row.id, 1);
                    }
                  }
                },
                "通过"
              ),
              h(
                "Button",
                {
                  props: {
                    type: "success",
                    size: "small"
                  },
                  style: {
                    marginLeft: "30px"
                  },
                  on: {
                    click: () => {
                      this.examine(params.row.id, 3);
                    }
                  }
                },
                "不通过"
              )
            ]);
          }
        }
      ],
      data3: [],
      cityList: [],
      statuss: [
        { label: "待审核", status: 0 },
        { label: "已通过", status: 1 },
        { label: "未通过", status: 3 }
      ],
      token_symbol: "",
      status: "",
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
    //审核(通过、拒绝)
    examine(id, status) {
      this.$axios({
        method: "post",
        url: "admin/examine",
        params: {
          id: id,
          status: status
        }
      })
        .then(response => {
          // this.data3 = response.data.data
          if (response.data.errmsg == "OK") {
            this.release(this.pages.current);
          }
          console.log(response.data);
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    release(index) {
      this.$axios({
        method: "post",
        url: "admin/withdrawlist",
        params: {
          token_symbol: this.token_symbol,
          status: this.status,
          page: index
        }
      })
        .then(response => {
          this.cityList = response.data.poundage;
          this.data3 = response.data.data.data;
          this.pages.total = response.data.total;
          this.pages.pageSize = response.data.per_page;
          this.pages.current = response.data.from;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    TermReview() {
      this.$router.push("/TermReview");
    },
    time(value) {
      return moment(parseInt(value)).format("YYYY-MM-DD HH:mm");
    },
    changepage(index) {
      this.pages.current = index
      this.release(index);
    }
  }
};
</script>
