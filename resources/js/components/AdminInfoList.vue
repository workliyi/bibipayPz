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
                        width: 100
                    },
                    {
                        title: '状态',
                        key: 'status',
                        width: 250
                    },
                    {
                        title: '发布日期',
                        key: 'startTime',
                        width: 250
                    },
                    {
                        title: '到期日期',
                        key: 'endTime',
                        width: 250
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 250,
                        render:(h, params) => {
                            return h('div',{style:{width: '100%',display: 'flex',justifyContent: 'space-around'}},[
                                 h('i-button',{attrs:{type:"success",size:"small"}},'查看'),
                                 h('i-button',{attrs:{type:"success",size:"small"}},'查看')
                            ])
                        }
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
