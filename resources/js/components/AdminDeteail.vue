<template>
  <div>
        <Row>
            <i-col span="9">共注册：</i-col>
            <i-col span="9">当日注册：</i-col>
        </Row>
        <Row>
            <i-col span="9">共充值（usdt）：</i-col>
            <i-col span="9">当日充值（usdt）：</i-col>
            <i-col span="6"><router-link to="/Users">返回</router-link></i-col>
        </Row>
        <hr>
        <Row style="margin-top: 10px;">
            <i-col span="2" style="text-align: left; line-height: 30px;">用户名搜索：</i-col>
            <i-col span="6">
                <i-input v-model="data.name" placeholder="请输入用户名"></i-input>
            </i-col>
            <i-col span="2">
                <Button type="primary">搜索</Button>
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
                        title: '用户名称·',
                        key: 'name',
                        width: 400
                    },
                    {
                        title: '数量',
                        key: 'number',
                        width: 400
                    },
                    {
                        title: '充值时间',
                        key: 'time',
                        width: 400
                    }
                ],
                data3: [],
                data: {
                    name:'',
                }
            }
        },
        created(){
            this.$axios({
                            method: "post",
                            url: "admin/usdtlist",
                            params: {
                                name:this.data.name,
                            }
                        })
                            .then(response => {
                            console.log(response.data.data);
                            this.data3 = response.data.data
                            })
                            .catch(function(error) {
                            console.log(error);
                            });
        }
        
    }
</script>
