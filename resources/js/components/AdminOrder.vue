<template>
  <div>
    <Row style="margin-top: 10px;">
      <i-col span="2" style="text-align: left; line-height: 30px;">订单名称：</i-col>
      <i-col span="6">
        <i-input v-model="Order.name" placeholder="请输入订单名称"></i-input>
      </i-col>
      <i-col span="2" style="text-align: left; line-height: 30px;">订单ID:</i-col>
      <i-col span="6">
        <i-input v-model="Order.id" placeholder="请输入订单ID"></i-input>
      </i-col>
      <i-col span="2">
        <Button type="primary" @click="release">搜索</Button>
      </i-col>
    </Row>
    <i-table style="margin-top: 10px;" width="90%" border :columns="columns2" :data="data3"></i-table>
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
          title: "订单ID",
          key: "id",
          width: 100
        },
        {
          title: "订单名称",
          key: "product_name",
          width: 200
        },
        {
          title: "购买金额（usdt)",
          width: 200,
          render: (h, params) => {
            let price = params.row.token_price * params.row.token_amount;
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
          title: "状态",
          render: (h, params) => {
            let status = params.row.status;
            if (status === 0) {
              return h("span", "未付款");
            }
            if (status === 1) {
              return h("span", "已支付");
            }
            if (status === 2) {
              return h("span", "已行权");
            }
            if (status === 3) {
              return h("span", "已失效");
            }
            if (status === 4) {
              return h("span", "自动行权");
            }
          }
        },
        {
          title: "购买日期",
          width: 200,
          render: (h, params) => {
            let price = this.time(params.row.buy_time * 1000);
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
          title: "购买人",
          key: "user_name",
          width: 200
        }
      ],
      data3: [],
      Order: {
        name: "",
        id: ""
      },
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
        url: "admin/orderlist",
        params: {
          product_num: this.Order.id,
          product_name: this.Order.name,
          page: index
        }
      })
        .then(response => {
            this.data3 = response.data.data;
            this.pages.total = response.data.total;
            this.pages.pageSize = response.data.per_page;
            this.pages.current = response.data.from;
          console.log(response.data);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    time(value) {
      return moment(parseInt(value)).format("YYYY-MM-DD HH:mm");
    },
    changepage(index) {
      this.release(index);
    }
  }
};
</script>
