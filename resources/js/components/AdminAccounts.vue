<template>
  <div>
        <Row>
            <i-col span="9">共注册：</i-col>
            <i-col span="9">当日注册：</i-col>
        </Row>
        <Row>
            <i-col span="9">共充值（usdt）：</i-col>
            <i-col span="9">当日充值（usdt）：</i-col>
            <i-col span="6">充值查询</i-col>
        </Row>
        <Row>
            <i-col span="1" style="text-align: center; line-height: 30px;">时间：</i-col>
            <i-col span="6">
                <Date-picker type="datetime" placeholder="选择日期" :value.sync="data.start_time"></Date-picker>
            </i-col>
            <i-col span="1" style="text-align: center">-</i-col>
            <i-col span="6">
                <Date-picker type="datetime" placeholder="选择日期" :value.sync="data.end_time"></Date-picker>
            </i-col>
        </Row>
        <Row>
            <i-col span="1" style="text-align: center; line-height: 30px;">id搜索</i-col>
            <i-col span="6">
                <i-input :value.sync="data.delivery_type" placeholder="请输入id"></i-input>
            </i-col>
            <i-col span="1" style="text-align: center">手机号搜索</i-col>
            <i-col span="6">
                <i-input :value.sync="data.delivery_type" placeholder="请输入手机号码"></i-input>
            </i-col>
            <i-col span="1" style="text-align: center">用户名搜索</i-col>
            <i-col span="6">
                <i-input :value.sync="data.delivery_type" placeholder="请输入用户名"></i-input>
            </i-col>
        </Row>
    <i-table width="90%" border :columns="columns2" :data="data3"></i-table>
    <Page :total="100" show-total/>
  </div>
</template>
<script>
    export default {
        data () {
            return {
                columns2: [
                    {
                        title: '#ID',
                        key: 'id',
                        width: 200
                    },
                    {
                        title: '用户名称',
                        key: 'name',
                        width: 300
                    },
                    {
                        title: '账户金额',
                        key: 'phone',
                        width: 300
                    },
                    {
                        title: '明细记录',
                        key: 'action',
                        width: 300,
                        render:(h, params) => {
                            return h('i-button',{attrs:{type:"success",size:"small"}},'查看')
                        }
                    }
                ],
                data3: [
                    {
                        id: 74,
                        name: '我们都是好孩子',
                        phone: "'0IPC', '0USDT'",
                        time: '2018-10-11 22:15:17'
                    }
                ],
                data: {
                    start_time:'',
                    end_time:''
                }
            }
        },
        // created(){
        //     this.$axios({
        //         method: 'post',
        //         url:'admin/userlist',
        //         params: {
        //             userId:'',
        //             name:'',
        //             tel:'',
        //         }
        //     })
        //     .then((response) => {
        //         console.log(response)
        //     })
        //     .catch(function (error) {
        //         console.log(error);
        //     })
        // }
    }
</script>
