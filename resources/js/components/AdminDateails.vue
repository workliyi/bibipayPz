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
                        title: '收支',
                        key: 'income',
                        width: 200
                    },
                    {
                        title: '币种',
                        key: 'Currency',
                        width: 300
                    },
                    {
                        title: '金额',
                        key: 'Amount',
                        width: 300
                    },
                    {
                        title: '详情',
                        key: 'Details',
                        width: 300
                    },
                    {
                        title: '时间',
                        key: 'time',
                        width: 300
                    },
                ],
                data3: [
                    {
                    }
                ]
            }
        }
        
    }
</script>
