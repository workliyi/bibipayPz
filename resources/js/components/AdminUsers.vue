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
                ]
            }
        }
    }
</script>
