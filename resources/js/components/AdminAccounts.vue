<template>
    <div>
        <Row style="margin-top: 10px;">
            <i-col span="2" style="text-align: left; line-height: 30px;">id搜索：</i-col>
            <i-col span="6">
                <i-input v-model="data.id" placeholder="请输入id"></i-input>
            </i-col>
            <i-col span="2" style="text-align: left; line-height: 30px;">用户名搜索：</i-col>
            <i-col span="6">
                <i-input v-model="data.userName" placeholder="请输入用户名"></i-input>
            </i-col>
            <i-col span="2">
                <Button type="primary" @click="this.release">搜索</Button>
            </i-col>
        </Row>
        <i-table width='90%' border :columns="columns2" :data="data3"></i-table>
        <Page :total="pages.total" :page-size="pages.pageSize" show-total @on-change="changepage"/>
    </div>  
</template>
<script>
    import expandRow from '../component/table-expand.vue';
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
                        width: 300,
                        render: (h, params) => {
                            return h(expandRow, {
                                props: {
                                    row: params.row
                                }
                            })
                        }
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
                                        this.$router.push('/Deteails/' + params.row.id)
                                    }
                                }
                                },'查看')
                        }
                    }
                ],
                data3: [],
                data: {
                    userName:'',
                    id:''
                },
                pages:{
                    current:'',             // 当前页码
                    total:1,               // 数据总数
                    pageSize:0,            //每页条数
                }
            }
        },
        created(){
           this.release(1)
        },
        methods:{
            release (index) {
                this.$axios({
                    method: 'post',
                    url:'admin/accountlist',
                    params: {
                        name:this.data.userName,
                        userId:this.data.id,
                        page:index,
                    }
                })
                .then((response) => {
                    this.data3 = response.data.data
                    this.pages.total = response.data.total
                    this.pages.pageSize = response.data.per_page
                    this.pages.current = response.data.from
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            changepage(index){
                this.release(index)
            }
        }
        
    }
</script>
