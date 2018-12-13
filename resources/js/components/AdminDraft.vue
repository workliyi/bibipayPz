<template>
  <div>
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
          title: "权证名称",
          key: "title",
          width: 200
        },
        {
          title: "发布日期",
          width: 300,
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
                    attrs: { type: "success", size: "small" },
                    on:{
                      click: () => {
                        this.$router.push('/Draft/' + params.row.id)
                      }
                    }
                  },
                  "查看/修改"
                ),
                h(
                  "i-button",
                  {
                    attrs: { type: "success", size: "small" },
                    on: {
                      click: () => {
                        console.log('newproduct')
                        this.$axios({
                          method: "post",
                          url: "admin/newproduct",
                          params: {
                            id: params.row.id
                          }
                        })
                          .then(response => {
                            console.log(response.data.code);
                            this.release();
                          })
                          .catch(function(error) {
                            console.log(error);
                          });
                      }
                    }
                  },
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
                            id: params.row.id
                          }
                        })
                          .then(response => {
                            console.log(response.data);
                            this.release();
                          })
                          .catch(function(error) {
                            console.log(error);
                          });
                      }
                    }
                  },
                  "删除"
                )
              ]
            );
          }
        }
      ],
      data3: [],
      pages:{
          current:'',             // 当前页码
          total:1,               // 数据总数
          pageSize:0,            //每页条数
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
        url: "admin/draft",
        params:{
          page:index
        }
      })
        .then(response => {
          this.data3 = response.data.data;
          this.pages.total = response.data.total
          this.pages.pageSize = response.data.per_page
          this.pages.current = response.data.from
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    time(value) {
      return moment(parseInt(value)).format("YYYY-MM-DD HH:mm");
    },
    changepage(index){
      console.log(index)
      this.release(index)
    }
  }
};
</script>
