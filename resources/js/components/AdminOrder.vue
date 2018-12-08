<template>
    <div>
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
                        title: '订单ID',
                        key: 'id',
                        width: 100
                    },
                    {
                        title: '订单名称',
                        key: 'name',
                        width: 200
                    },
                    {
                        title: '购买金额（usdt)',
                        key: 'money',
                        width: 200
                    },
                    {
                        title: '状态',
                        key: 'status',
                        width: 200
                    },
                    {
                        title: '购买日期',
                        key: 'time',
                        width: 200
                    },
                    {
                        title: '购买人',
                        key: 'Purchaser',
                        width: 200
                    }
                ],
                data3: [
                    {
                        id: 74,
                        name: '权利宝1011号',
                        money: "36.28131077119554",
                        status: '未付款',
                        time: '1539308856',
                        Purchaser: ''
                    }
                ]
            }
        }
    }
</script>
