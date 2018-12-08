<template>
    <div>
        <!-- <Col span="12" style="padding-right:10px">
            <Select v-model="model11" filterable>
                <Option v-for="item in cityList" :value="item.value" :key="item.value">{{ item.label }}</Option>
            </Select>
        </Col> -->
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
                        title: '序号',
                        key: 'id',
                        width: 100,
                        fixed: 'left'
                    },
                    {
                        title: '币种',
                        key: 'currency',
                        width: 200
                    },
                    {
                        title: '提现地址',
                        key: 'address',
                        width: 200
                    },
                    {
                        title: '提现金额',
                        key: 'amount',
                        width: 200
                    },
                    {
                        title: '手续费',
                        key: 'cost',
                        width: 200
                    },
                    {
                        title: '状态',
                        key: 'stastus',
                        width: 200
                    },
                    {
                        title: '日期',
                        key: 'Purchaser',
                        width: 200
                    },
                    {
                        title: '操作',
                        key: 'action',
                        fixed: 'right',
                        width: 200,
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'success',
                                        size: 'small'
                                    }
                                }, '通过'),
                                h('Button', {
                                    props: {
                                        type: 'success',
                                        size: 'small'
                                    }
                                }, '不通过')
                            ]);
                        }
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
                ],
                cityList: [
                    {
                        value: 'New York',
                        label: 'New York'
                    },
                    {
                        value: 'London',
                        label: 'London'
                    },
                    {
                        value: 'Sydney',
                        label: 'Sydney'
                    },
                    {
                        value: 'Ottawa',
                        label: 'Ottawa'
                    },
                    {
                        value: 'Paris',
                        label: 'Paris'
                    },
                    {
                        value: 'Canberra',
                        label: 'Canberra'
                    }
                ],
                model11: '',
            }
        }
    }
</script>
