<template>
  <div>
        <Row>
            <i-col span="9">共注册：</i-col>
            <i-col span="9">当日注册：</i-col>
        </Row>
        <Row>
            <i-col span="9">共充值（usdt）：</i-col>
            <i-col span="9">当日充值（usdt）：</i-col>
            <i-col span="6"><router-link to="/Deteail">充值查询</router-link></i-col>
        </Row>
        <hr>
        <Row style="margin-top: 10px;">
            <i-col span="1" style="text-align: center; line-height: 30px;">时间：</i-col>
            <i-col span="6">
                <Date-picker type="datetime" placeholder="选择日期" :value.sync="data.start_time"></Date-picker>
            </i-col>
            <i-col span="1" style="text-align: center">-</i-col>
            <i-col span="6">
                <Date-picker type="datetime" placeholder="选择日期" :value.sync="data.end_time"></Date-picker>
            </i-col>
        </Row>
        <Row style="margin-top: 10px;">
            <i-col span="2" style="text-align: left; line-height: 30px;">id搜索：</i-col>
            <i-col span="6">
                <i-input :value.sync="data.id" placeholder="请输入id"></i-input>
            </i-col>
            <i-col span="2" style="text-align: left; line-height: 30px;">手机号搜索：</i-col>
            <i-col span="6">
                <i-input :value.sync="data.ipone" placeholder="请输入手机号码"></i-input>
            </i-col>
            <i-col span="2" style="text-align: left; line-height: 30px;">用户名搜索：</i-col>
            <i-col span="6">
                <i-input :value.sync="data.userName" placeholder="请输入用户名"></i-input>
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
                        title: '手机号',
                        key: 'phone',
                        width: 300
                    },
                    {
                        title: '注册时间',
                        key: 'time',
                        width: 300
                    }
                ],
                data3: [
                    {
                        id: 74,
                        name: '我们都是好孩子',
                        phone: '17744407804',
                        time: '2018-10-11 22:15:17'
                    }
                ],
                data: {
                    start_time:'',
                    end_time:'',
                    userName:'',
                    ipone:'',
                    id:''
                }
            }
        }
        
    }
</script>
