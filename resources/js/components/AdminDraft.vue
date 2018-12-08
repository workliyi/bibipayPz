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
                        title: '权证名称',
                        key: 'name',
                        width: 200
                    },
                    {
                        title: '发布日期',
                        key: 'startTime',
                        width: 300
                    },
                    {
                        title: '到期日期',
                        key: 'endTime',
                        width: 300
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 300,
                        render:(h, params) => {
                            return h('div',{style:{width: '100%',display: 'flex',justifyContent: 'space-around'}},[
                                 h('i-button',{attrs:{type:"success",size:"small"}},'修改'),
                                 h('i-button',{attrs:{type:"success",size:"small"}},'查看'),
                                 h('i-button',{attrs:{type:"success",size:"small"}},'发布'),
                                 h('i-button',{attrs:{type:"success",size:"small"}},'删除')
                            ])
                        }
                    }
                ],
                data3: [
                    {
                        name:'权证宝',
                        startTime:'123123',
                        endTime:'qeqqew'
                    }
                ]
            }
        }
    }
</script>
