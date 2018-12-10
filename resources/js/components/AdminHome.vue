<style scoped>
  .layout-con{
    height: 100%;
    width: 100%;
  }
  .menu-item span{
    display: inline-block;
    overflow: hidden;
    width: 100%;
    height: 100%;
    text-overflow: ellipsis;
    white-space: nowrap;
    vertical-align: bottom;
    transition: width .2s ease .2s;
    padding: 14px 24px;
  }
  .menu-item i{
    transform: translateX(0px);
    transition: font-size .2s ease, transform .2s ease;
    vertical-align: middle;
    font-size: 16px;
  }
  .collapsed-menu span{
    width: 0px;
    transition: width .2s ease;
  }
  .collapsed-menu i{
    transform: translateX(5px);
    transition: font-size .2s ease .2s, transform .2s ease .2s;
    vertical-align: middle;
    font-size: 22px;
  }
  .ivu-card-bordered{
      margin-top: 30px;
  }
  .ivu-menu-vertical .ivu-menu-item, .ivu-menu-vertical .ivu-menu-submenu-title{
    padding: 0px;
  }
</style>
<template>
  <div class="layout">
    <Layout :style="{minHeight: '100vh'}">
      <Sider collapsible :collapsed-width="78" v-model="isCollapsed">
        <Menu active-name="1-1" theme="dark" width="auto" :class="menuitemClasses">
          <MenuItem name="1-1">
            <!-- <Icon type="home"></Icon> -->
            <router-link tag="span" to="/">账户管理</router-link>
          </MenuItem>
          <MenuItem name="1-2">
            <!-- <Icon type="person"></Icon> -->
            
            <router-link tag="span" to="/Users">用户管理</router-link>
          </MenuItem>
          <MenuItem name="1-3">
            <!-- <Icon type="document-text"></Icon> -->
            <router-link tag="span" to="/Order">订单管理</router-link>
          </MenuItem>
          <MenuItem name="1-4">
            <!-- <Icon type="paper-airplane"></Icon> -->
            <router-link tag="span" to="/Release">发布管理</router-link>
          </MenuItem>
          <MenuItem name="1-5">
            <!-- <Icon type="document"></Icon> -->
            <router-link tag="span" to="/List">发布信息管理</router-link>
          </MenuItem>
          <MenuItem name="1-6">
            <!-- <Icon type="printer"></Icon> -->
            <router-link tag="span" to="/Review">审核</router-link>
          </MenuItem>
          <MenuItem name="1-7">
            <!-- <Icon type="gear-a"></Icon> -->
            <router-link tag="span" to="/Cost">设置手续费</router-link>
          </MenuItem>
          <MenuItem name="1-8">
            <!-- <Icon type="settings"></Icon> -->
            <router-link tag="span" to="/Draft">草稿箱</router-link>
          </MenuItem>
        </Menu>
      </Sider>
      <Layout>
        <Header :style="{background: '#fff', boxShadow: '0 2px 3px 2px rgba(0,0,0,.1)'}">
          <Row>
            <i-col span="10">USDT地址：{{data.usdt_address}}</i-col>
            <i-col span="7">USDT余额：{{data.usdt_balance}}</i-col>
            <i-col span="7">BTC余额：{{data.btc_balance}}</i-col>
        </Row>
        </Header>
        <Content :style="{padding: '0 16px 16px'}">
          <Card>
            <div :style="{minHeight: '600px'}"><router-view></router-view></div>
          </Card>
        </Content>
      </Layout>
    </Layout>
  </div>
</template>
<script>
  export default {
    data () {
      return {
        isCollapsed: false,
        data:{
          btc_balance:'',
          usdt_address:'',
          usdt_balance:''
        },
      };
    },
    computed: {
      menuitemClasses: function () {
        return [
          'menu-item',
          this.isCollapsed ? 'collapsed-menu' : ''
        ]
      }
      
    },
    created(){
      this.$axios('admin/getusdt').then( (response) => {
        let { btc_balance, usdt_address, usdt_balance } = response.data
        this.data ={
          btc_balance,
          usdt_address,
          usdt_balance
        }
      })
    }
  }
</script>