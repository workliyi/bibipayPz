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
          title: "权证名称",
          key: "title",
          width: 200
        },
        {
          title: "发布日期",
          width: 300,
          render: (h, params) => {
            console.log(this.time);
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
          width: 300,
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
          width: 300,
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
                      attrs: { type: "success", size: "small" } ,
                      
                    },
                  "修改"
                ),
                h(
                  "i-button",
                  { attrs: { type: "success", size: "small" } },
                  "查看"
                ),
                h(
                  "i-button",
                  { attrs: { type: "success", size: "small" } },
                  "发布"
                ),
                h(
                  "i-button",
                    {
                       attrs: { type: "success", size: "small" },
                       on: {
                            click: () => {
                                this.$axios({
                                    method: "post",
                                    url: "admin/delproduct",
                                    params: {
                                        id:params.row.id,
                                    }
                                })
                                    .then(response => {
                                    console.log(response.data);
                                    })
                                    .catch(function(error) {
                                    console.log(error);
                                    });
                                },
                        }
                    },
                  "删除"
                )
              ]
            );
          }
        }
      ],
      data3: []
    };
  },
  created() {
    
  },
  methods: {
    time(value) {
      return moment(parseInt(value)).format("YYYY-MM-DD HH:mm");
    }
  }
};
</script>
