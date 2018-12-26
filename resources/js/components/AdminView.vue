<style scoped>
.expand-row {
  margin-bottom: 16px;
}
</style>
<template>
  <i-form
    :model="data"
    label-position="right"
    :label-width="150"
    :style="{width: '70%', margin:'auto'}"
  >
    <Form-item label="名称：" v-if="data.title">
      <i-input v-model="data.title" placeholder="请输入名称" disabled></i-input>
    </Form-item>
    <Form-item label="期权标的：" v-if="data.token_type">
      <i-input v-model="data.token_type" placeholder="请输入期权标的" disabled></i-input>
    </Form-item>
    <Form-item label="合约类型：" v-if="data.contract_type">
      <i-input v-model="data.contract_type" placeholder="请输入合约类型" disabled></i-input>
    </Form-item>

    <Form-item label="计价单位：" v-if="data.unit_valuation">
      <i-input v-model="data.unit_valuation" placeholder="请输入计价单位" disabled></i-input>
    </Form-item>
    <Form-item label="*发行价：" v-if="data.issue_price">
      <i-input v-model="data.issue_price" placeholder="请输入发行价" disabled></i-input>
    </Form-item>
    <Form-item label="最小价格单位：" v-if="data.min_price">
      <i-input v-model="data.min_price" placeholder="请输入最小价格单位" disabled></i-input>
    </Form-item>
    <Form-item label="*合约比例：" v-if="data.contract_first || data.constarct_second">
      <Row>
        <i-col span="8">
          <Form-item prop="contract_first">
            <i-input v-model="data.contract_first" placeholder="请输入币种" disabled></i-input>
          </Form-item>
        </i-col>
        <i-col span="1" style="text-align: center">:</i-col>
        <i-col span="8">
          <Form-item prop="constarct_second">
            <i-input v-model="data.constarct_second" placeholder="请输入合约" disabled></i-input>
          </Form-item>
        </i-col>
      </Row>
    </Form-item>
    <Form-item label="文本描述：" v-if="data.description">
      <i-input
        v-model="data.description"
        type="textarea"
        placeholder="请输入文本描述"
        :rows="3"
        :autosize="{maxRows: 3,minRows: 3}"
        disabled
      ></i-input>
    </Form-item>
    <Form-item label="交割方式：" v-if="data.delivery_type">
      <i-input v-model="data.delivery_type" placeholder="请输入交割方式" disabled></i-input>
    </Form-item>
    <Form-item label="*行权价格：" v-if="data.exercise_price">
      <Row>
        <i-col span="8">
          <Form-item prop="contract_first">
            <i-input v-model="data.exercise_price" placeholder="请输入行权价格" disabled></i-input>
          </Form-item>
        </i-col>
        <i-col span="1" style="text-align: center">RMB</i-col>
      </Row>
    </Form-item>
    <Form-item label="*上市时间：" v-if="data.ipo_time">
      <Row>
        <i-col span="6">
          <Form-item prop="datetime">
            <DatePicker type="datetime" format="yyyy-MM-dd HH:mm" placeholder="选择日期" v-model="data.ipo_time" disabled></DatePicker>
          </Form-item>
        </i-col>
      </Row>
    </Form-item>
    <Form-item label="*期权到期时间：" v-if="data.end_time">
      <Row>
        <i-col span="6">
          <Form-item prop="datetime">
            <DatePicker type="datetime" placeholder="选择日期" format="yyyy-MM-dd HH:mm" v-model="data.end_time" disabled></DatePicker>
          </Form-item>
        </i-col>
      </Row>
    </Form-item>
    <Form-item label="*交易期限：" v-if="data.pay_start_time ||data.pay_end_time">
      <Row>
        <i-col span="6">
          <Form-item prop="datetime">
            <DatePicker type="datetime" placeholder="选择日期" format="yyyy-MM-dd HH:mm" v-model="data.pay_start_time" disabled></DatePicker>
          </Form-item>
        </i-col>
        <i-col span="2" style="text-align: center">-</i-col>
        <i-col span="6">
          <Form-item prop="datetime">
            <DatePicker type="datetime" placeholder="选择日期" format="yyyy-MM-dd HH:mm" v-model="data.pay_end_time" disabled></DatePicker>
          </Form-item>
        </i-col>
      </Row>
    </Form-item>
    <Form-item label="*行权时间：" v-if="data.exercise_start_time || data.exercise_end_time">
      <Row>
        <i-col span="6">
          <Form-item prop="datetime">
            <DatePicker type="datetime" placeholder="选择日期" format="yyyy-MM-dd HH:mm" v-model="data.exercise_start_time" disabled></DatePicker>
          </Form-item>
        </i-col>
        <i-col span="2" style="text-align: center">-</i-col>
        <i-col span="6">
          <Form-item prop="datetime">
            <DatePicker type="datetime" placeholder="选择日期" format="yyyy-MM-dd HH:mm" v-model="data.exercise_end_time" disabled></DatePicker>
          </Form-item>
        </i-col>
      </Row>
    </Form-item>
    <Form-item label="*每人最多购买期权数量：" v-if="data.max_num_option">
      <i-input v-model="data.max_num_option" placeholder="请输入每人最多购买期权数量" disabled></i-input>
    </Form-item>
    <Form-item label="履行价格确认方式：" v-if="data.execute_type">
      <i-input v-model="data.execute_type" placeholder="请输入确认方式" disabled></i-input>
    </Form-item>
    <Form-item label="履行价格确认平台：" v-if="data.execute_platform">
      <i-input v-model="data.execute_platform" placeholder="请输入履行价格确认平台" disabled></i-input>
    </Form-item>
    <Form-item label="履行价格权重比例：" v-if="data.execute_weight_ratio">
      <i-input v-model="data.execute_weight_ratio" placeholder="请输入履行价格权重比例" disabled></i-input>
    </Form-item>
    <Form-item label="保证金标的：" v-if="data.bail_type">
      <i-input v-model="data.bail_type" placeholder="请输入保证金标的" disabled></i-input>
    </Form-item>
    <Form-item label="保证金率：" v-if="data.bail_ratio">
      <i-input v-model="data.bail_ratio" placeholder="请输入保证金率" disabled></i-input>
    </Form-item>
    <Form-item label="最小数量单位：" v-if="data.min_number">
      <i-input v-model="data.min_number" disabled></i-input>
    </Form-item>
    <Form-item label="保证金：" v-if="data.bail">
      <i-input v-model="data.bail" placeholder="请输入保证金" disabled></i-input>
    </Form-item>
    <Form-item label="其他要求：" v-if="data.charge_unit">
      <i-input v-model="data.charge_unit" placeholder="请输入其他要求" disabled></i-input>
    </Form-item>
    <Form-item label="保证金返还：" v-if="data.bail_return">
      <i-input v-model="data.bail_return" placeholder="请输入保证金返还" disabled></i-input>
    </Form-item>
  </i-form>
</template>
<script>
let moment = require("moment");
export default {
  data() {
    return {
        data:{}
    };
  },
  created(){
    this.$axios.get('admin/data',{
      params:{
        id:this.$route.params.id
      }
    })
    .then((response) => {
        this.data = response.data
        this.data.ipo_time = this.time(this.data.ipo_time)
        this.data.end_time = this.time(this.data.end_time)
        this.data.pay_start_time = this.time(this.data.pay_start_time)
        this.data.pay_end_time = this.time(this.data.pay_end_time)
        this.data.exercise_start_time = this.time(this.data.exercise_start_time)
        this.data.exercise_end_time = this.time(this.data.exercise_end_time)
        console.log(response)
    })
  },
  methods:{
    time(value) {
      if (!value) {
        return;
      }
      return moment(parseInt(value) * 1000).format("YYYY-MM-DD HH:mm");
    },
  }
};
</script>