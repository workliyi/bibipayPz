<template>
  <div>
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
          title: "收支",
          width: 200,
          render: (h, params) => {
            if (params.row.less_number) {
              return h("span", "支出");
            }
            if (params.row.add_number) {
              return h("span", "收入");
            }
          }
        },
        {
          title: "币种",
          key: "type",
          width: 300
        },
        {
          title: "金额",
          width: 300,
          render: (h, params) => {
            if (params.row.less_number) {
              return h("span", params.row.less_number);
            } else {
              return h("span", params.row.add_number);
            }
          }
        },
        {
          title: "详情",
          key: "action_type",
          width: 300
        },
        {
          title: "时间",
          key: "created_time",
          width: 300
        }
      ],
      data3: [],
      pages: {
        current: "", // 当前页码
        total: 1, // 数据总数
        pageSize: 0 //每页条数
      }
    };
  },
  created() {
        this.release(1)
        },
  methods: {
    changepage(index) {
        this.release(index)
      console.log(index);
    },
    release(index) {
      let id = this.$route.params.id;
      this.$axios({
        method: "post",
        url: "admin/countlog",
        params: {
          user_id: id,
          page:index
        }
      })
        .then(response => {
          console.log(response.data);
          this.data3 = response.data.data;
          this.pages.total = response.data.total;
          this.pages.pageSize = response.data.per_page;
          this.pages.current = response.data.from;
          console.log(this.pages);
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  }
};
</script>
