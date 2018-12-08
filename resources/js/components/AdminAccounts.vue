<template>
    <div>
        <Row style="margin-top: 10px;">
            <i-col span="2" style="text-align: left; line-height: 30px;">id搜索：</i-col>
            <i-col span="6">
                <i-input :value.sync="data.id" placeholder="请输入id"></i-input>
            </i-col>
            <i-col span="2" style="text-align: left; line-height: 30px;">用户名搜索：</i-col>
            <i-col span="6">
                <i-input :value.sync="data.userName" placeholder="请输入用户名"></i-input>
            </i-col>
            <i-col span="2">
                <Button type="primary">搜索</Button>
            </i-col>
        </Row>
        <i-table width='90%' border :columns="columns2" :data="data3"></i-table>
         <Page :total="100" show-total />
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
                            return h('i-button',{
                                attrs:{
                                    type:"success",
                                    size:"small"
                                    },
                                on: {
                                    click: () => {
                                        console.log(params)
                                        this.$router.push('/Deteails')
                                    }
                                }
                                },'查看')
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
                    userName:'',
                    id:''
                }
            }
        }
        
    }
</script>
