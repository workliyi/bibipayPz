<template>
  <div>
    <i-table width="90%" border :columns="columns2" :data="data3"></i-table>
    <Page :total="100" show-total/>
  </div>
</template>
<script>
let moment = require("moment");
export default {
  data() {
    return {
      columns2: [
        {
          title: "#ID",
          key: "id",
          width: 100
        },
        {
          title: "状态",
          width: 250,
          render: (h, params) => {
            let status = params.row.end_status;
            if (status === 1) {
              return h("span", "进行中");
            }
            if (status === 2) {
              return h("span", "已完成");
            }
            if (status === 3) {
              return h("span", "未开始");
            }
          }
        },
        {
          title: "发布日期",
          width: 250,
          render: (h, params) => {
            let price = this.time(params.row.create_time * 1000);
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
          title: "到期日期",
          width: 250,
          render: (h, params) => {
            let price = this.time(params.row.pay_end_time * 1000);
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
          width: 250,
          render: (h, params) => {
            return h(
              "div",
              {
                style: {
                  width: "100%",
                  display: "flex",
                  justifyContent: "space-around"
                }
              },
              [
                h(
                  "i-button",
                  { 
                      attrs: { type: "success", size: "small" },
                      on:{
                          click:()=>{
                                this.$axios({
                                method: "post",
                                url: "admin/withdraw",
                                params: {
                                            id:params.row.id,
                                        }
                                })
                                .then(response => {
                                    // this.data3 = response.data.data;
                                    console.log(response.data);
                                })
                                .catch(function(error) {
                                    console.log(error);
                                });
                          }
                      }
                  },
                  "下架"
                ),
                h(
                  "i-button",
                  { attrs: { type: "success", size: "small" } },
                  "查看"
                )
              ]
            );
          }
        }
      ],
      data3: [
        {
          id: 74,
          name: "我们都是好孩子",
          phone: "17744407804",
          time: "2018-10-11 22:15:17"
        }
      ]
    };
  },
  created() {
    this.$axios({
      method: "post",
      url: "admin/prolist"
    })
      .then(response => {
        this.data3 = response.data.data;
        console.log(response.data);
      })
      .catch(function(error) {
        console.log(error);
      });
  },
  methods: {
    time(value) {
      return moment(parseInt(value)).format("YYYY-MM-DD HH:mm");
    }
  }
};
</script>
