<template>
   
    <Transfer
        :data="data1"
        :target-keys="targetKeys1"
        :render-format="render1"
        filterable
        :titles="['不需要审核', '需要审核']"
        @on-change="handleChange1">
    </Transfer>
    
</template>

<script>
    export default {
        data () {
            return {
                data1: [],
                targetKeys1:[],
                data:[]
            }
        },
        created(){
            this.$axios.get('admin/retutoken').then( (response) => {
                let data = response.data;
                data.forEach((item, index) => {
                        let {id, status, poundage, token_name} = item
                        this.data1.push({
                            key: id,
                            id: id,
                            label: token_name,
                            status:status,
                            token_name:token_name,
                            poundage:poundage
                        })
                        if(item.status == 0){
                            this.targetKeys1.push(item.id)
                        }
                });
            })
        },
        methods: {
            render1 (item) {
                return item.label;
            },
            handleChange1 (newTargetKeys, direction, moveKeys) {
                if(direction == 'right'){
                    moveKeys.forEach((item,index) => {
                        this.data1[item - 1].status = 0;
                    })
                    this.setting(this.data1)
                } else {
                    moveKeys.forEach((item,index) => {
                        this.data1[item-1].status = 1;
                    })
                    this.setting(this.data1)
                }
                this.targetKeys1 = newTargetKeys;
            },
            setting(data){
                console.log(typeof data[0])
                this.$axios({
                    method: 'post',
                    url:'admin/setting',
                    params:{
                        TokenModel:data
                    }
                })
                .then((response) => {
                    let data = response.data;
                        data.forEach((item, index) => {
                            console.log(item)
                                let {id, status, poundage, token_name} = item
                                this.data1.push({
                                    key: id,
                                    id: id,
                                    label: token_name,
                                    status:status,
                                    token_name:token_name,
                                    poundage:poundage
                                })
                                if(item.status == 0){
                                    this.targetKeys1.push(item.id)
                                }
                        });
                    console.log(response.data)
                })
                .catch(function (error) {
                    console.log(error);
                })
            }
        }
    }
</script>
