<template>
    <div>
        <Row>
            <i-col span="2" style="text-align: left; line-height: 30px;">
                币种：
            </i-col>
            <i-col span="4" style="padding-right:10px">
                <Select v-model="model1" filterable>
                    <Option v-for="item in cityList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                </Select>
            </i-col>
            <i-col span="2" style="text-align: left; line-height: 30px;">
                状态:
            </i-col>
            <i-col span="4" style="padding-right:10px">
                <Select v-model="model2" filterable>
                    <Option v-for="item in cityList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                </Select>
            </i-col>
            <i-col span="2">
                <i-button type="ghost" style="margin-left: 8px" @click='this.TermReview'>配置审核</i-button>
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
                model1: '',
                model2: ''
            }
        },
        created(){
            this.$axios({
            method: 'post',
            url:'admin/withdrawlist'
            })
            .then((response) => {
                this.data3 = response.data.data
                console.log(response.data)
            })
            .catch(function (error) {
                console.log(error);
            })
        },
        methods: {
            TermReview () {
                this.$router.push('/TermReview')
            }
        }
    }
</script>
