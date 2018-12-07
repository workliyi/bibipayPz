<template>
    <i-form :model="data" label-position="right" :label-width="150" :style="{width: '70%', margin:'auto'}">
        <Form-item label="IPC:">
            <i-input :value.sync="data.IPC" placeholder="请输入手续费"></i-input>
        </Form-item>
        <Form-item label="USDT:">
            <i-input :value.sync="data.USDT" placeholder="请输入手续费"></i-input>
        </Form-item>
        <Form-item label="CTC:">
            <i-input :value.sync="data.CTC" placeholder="请输入手续费"></i-input>
        </Form-item>
        <Form-item label="UST:">
            <i-input :value.sync="data.UST" placeholder="请输入手续费"></i-input>
        </Form-item>
        <Form-item label="AXS:">
            <i-input :value.sync="data.AXS" placeholder="请输入手续费"></i-input>
        </Form-item>
        <Form-item>
            <i-button type="primary">提交</i-button>
            <i-button type="ghost" style="margin-left: 8px">取消</i-button>
        </Form-item>
    </i-form>
</template>
<script>
    export default {
        data () {
            return {
                data: {
                    IPC:'',
                    USDT:'',
                    CTC:'',
                    UST:'',
                    AXS:''
                }
            }
        }
    }
</script>
