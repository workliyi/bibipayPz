<template>
  <i-form
    :model="data"
    label-position="right"
    :label-width="150"
    :style="{width: '70%', margin:'auto'}"
  >
    <Form-item label="名称：" v-if="Model || data.title">
      <i-input v-model="data.title" placeholder="请输入名称"></i-input>
    </Form-item>
    <Form-item label="期权标的：" v-if="Model || data.token_type">
      <i-input v-model="data.token_type" placeholder="请输入期权标的"></i-input>
    </Form-item>
    <Form-item label="合约类型：" v-if="Model || data.contract_type">
      <i-input v-model="data.contract_type" placeholder="请输入合约类型"></i-input>
    </Form-item>

    <Form-item label="计价单位：" v-if="Model || data.unit_valuation">
      <i-input v-model="data.unit_valuation" placeholder="请输入计价单位"></i-input>
    </Form-item>
    <Form-item label="*发行价：" v-if="Model || data.issue_price">
      <i-input v-model="data.issue_price" placeholder="请输入发行价"></i-input>
    </Form-item>
    <Form-item label="最小价格单位：" v-if="Model || data.min_price">
      <i-input v-model="data.min_price" placeholder="请输入最小价格单位"></i-input>
    </Form-item>
    <Form-item label="*合约比例：" v-if="Model || data.contract_first || data.constarct_second">
      <Row>
        <i-col span="8">
          <Form-item prop="contract_first">
            <i-input v-model="data.contract_first" placeholder="请输入币种"></i-input>
          </Form-item>
        </i-col>
        <i-col span="1" style="text-align: center">:</i-col>
        <i-col span="8">
          <Form-item prop="constarct_second">
            <i-input v-model="data.constarct_second" placeholder="请输入合约"></i-input>
          </Form-item>
        </i-col>
      </Row>
    </Form-item>
    <Form-item label="文本描述：" v-if="Model || data.description">
      <i-input
        v-model="data.description"
        type="textarea"
        placeholder="请输入文本描述"
        :rows="3"
        :autosize="{maxRows: 3,minRows: 3}"
      ></i-input>
    </Form-item>
    <Form-item label="交割方式：" v-if="Model || data.delivery_type">
      <i-input v-model="data.delivery_type" placeholder="请输入交割方式"></i-input>
    </Form-item>
    <Form-item label="*行权价格：" v-if="Model || data.exercise_price">
      <Row>
        <i-col span="8">
          <Form-item prop="contract_first">
            <i-input v-model="data.exercise_price" placeholder="请输入行权价格"></i-input>
          </Form-item>
        </i-col>
        <i-col span="1" style="text-align: center">RMB</i-col>
      </Row>
    </Form-item>
    <Form-item label="*上市时间：" v-if="Model || data.ipo_time">
      <Row>
        <i-col span="6">
          <Form-item prop="datetime">
            <Date-picker type="datetime" placeholder="选择日期" format="yyyy-MM-dd HH:mm:ss" @on-change="ipo_time"></Date-picker>
          </Form-item>
        </i-col>
      </Row>
    </Form-item>
    <Form-item label="*期权到期时间：" v-if="Model || data.end_time">
      <Row>
        <i-col span="6">
          <Form-item prop="datetime">
            <Date-picker type="datetime" placeholder="选择日期" format="yyyy-MM-dd HH:mm:ss" @on-change="end_time"></Date-picker>
          </Form-item>
        </i-col>
      </Row>
    </Form-item>
    <Form-item label="*交易期限：" v-if="Model || data.pay_start_time ||data.pay_end_time">
      <Row>
        <i-col span="6">
          <Form-item prop="datetime">
            <Date-picker type="datetime" placeholder="选择日期" format="yyyy-MM-dd HH:mm:ss" @on-change="pay_start_time"></Date-picker>
          </Form-item>
        </i-col>
        <i-col span="2" style="text-align: center">-</i-col>
        <i-col span="6">
          <Form-item prop="datetime">
            <Date-picker type="datetime" placeholder="选择日期" format="yyyy-MM-dd HH:mm:ss" @on-change="pay_end_time"></Date-picker>
          </Form-item>
        </i-col>
      </Row>
    </Form-item>
    <Form-item label="*行权时间：" v-if="Model || data.exercise_start_time || data.exercise_end_time">
      <Row>
        <i-col span="6">
          <Form-item prop="datetime">
            <Date-picker type="datetime" placeholder="选择日期" format="yyyy-MM-dd HH:mm:ss" @on-change="exercise_start_time"></Date-picker>
          </Form-item>
        </i-col>
        <i-col span="2" style="text-align: center">-</i-col>
        <i-col span="6">
          <Form-item prop="datetime">
            <Date-picker type="datetime" placeholder="选择日期" format="yyyy-MM-dd HH:mm:ss" @on-change="exercise_end_time" v-model="data.exercise_end_time"></Date-picker>
          </Form-item>
        </i-col>
      </Row>
    </Form-item>
    <Form-item label="*每人最多购买期权数量：" v-if="Model || data.max_num_option">
      <i-input v-model="data.max_num_option" placeholder="请输入每人最多购买期权数量"></i-input>
    </Form-item>
    <Form-item label="履行价格确认方式：" v-if="Model || data.execute_type">
      <i-input v-model="data.execute_type" placeholder="请输入确认方式"></i-input>
    </Form-item>
    <Form-item label="履行价格确认平台：" v-if="Model || data.execute_platform">
      <i-input v-model="data.execute_platform" placeholder="请输入履行价格确认平台"></i-input>
    </Form-item>
    <Form-item label="履行价格权重比例：" v-if="Model || data.execute_weight_ratio">
      <i-input v-model="data.execute_weight_ratio" placeholder="请输入履行价格权重比例"></i-input>
    </Form-item>
    <Form-item label="保证金标的：" v-if="Model || data.bail_type">
      <i-input v-model="data.bail_type" placeholder="请输入保证金标的"></i-input>
    </Form-item>
    <Form-item label="保证金率：" v-if="Model || data.bail_ratio">
      <i-input v-model="data.bail_ratio" placeholder="请输入保证金率"></i-input>
    </Form-item>
    <Form-item label="最小数量单位：" v-if="Model || data.min_number">
      <i-input v-model="data.min_number" disabled></i-input>
    </Form-item>
    <Form-item label="保证金：" v-if="Model || data.bail">
      <i-input v-model="data.bail" placeholder="请输入保证金"></i-input>
    </Form-item>
    <Form-item label="其他要求：" v-if="Model || data.charge_unit">
      <i-input v-model="data.charge_unit" placeholder="请输入其他要求"></i-input>
    </Form-item>
    <Form-item label="保证金返还：" v-if="Model || data.bail_return">
      <i-input v-model="data.bail_return" placeholder="请输入保证金返还"></i-input>
    </Form-item>
    <Row>
      <i-col>
        注：带‘*’为必填项
      </i-col>
    </Row>
    <Form-item>
      <i-button type="primary" @click="release(2)">发布</i-button>
      <i-button type="ghost" style="margin-left: 8px" @click="release(3)">存为草稿</i-button>
      <i-button type="ghost" style="margin-left: 8px" @click='showModel'>预览</i-button>
    </Form-item>
  </i-form>
</template>
<script>
export default {
  data() {
    return {
      data: {
        title: "",
        token_type: "",
        contract_type: "",
        unit_valuation: "",
        min_price: "",
        contract_first: "",
        constarct_second: "",
        description: "",
        delivery_type: "",
        exercise_price: "",
        ipo_time: "",
        end_time: "",
        pay_start_time: "",
        pay_end_time: "",
        exercise_start_time: "",
        exercise_end_time: "",
        max_num_option: "",
        execute_type: "",
        execute_platform: "",
        execute_weight_ratio: "",
        bail_type: "",
        bail_ratio: "",
        min_number: "千张",
        bail: "",
        charge_unit: "",
        bail_return: ""
      },
      Model:true
    };
  },
  created() {
    
    // this.$axios({
    // method: 'post',
    // url:'admin/newproduct',
    // params: {
    //     data:thi
    // }
    // })
    // .then((response) => {
    //    this.data3 = response.data.data
    //     console.log(response.data)
    // })
    // .catch(function (error) {
    //     console.log(error);
    // })
  },
  methods: {
    ipo_time(date){
      this.data.ipo_time = date
    },
    end_time(date){
      this.data.end_time = date
    },
    pay_start_time(date){
      this.data.pay_start_time = date
    },
    pay_end_time(date){
      this.data.pay_end_time = date
    },
    exercise_start_time(date){
      this.data.exercise_start_time = date
    },
    exercise_end_time(date){
      this.data.exercise_end_time = date
    },
    release(withdraw) {
      console.log(withdraw);
      let time = new Date(this.data.exercise_start_time).getTime(); //行权开始时间
      let time1 = new Date(this.data.exercise_end_time).getTime(); //行权结束时间
      let time3 = new Date(this.data.ipo_time).getTime(); //上市时间
      let time4 = new Date(this.data.end_time).getTime(); //期权时间
      let time5 = new Date(this.data.pay_start_time).getTime(); //交易开始时间
      let time6 = new Date(this.data.pay_end_time).getTime(); //交易结束时间
      if (!this.data.title) {
        alert("名称不能为空");
        return;
      } else if (!this.data.issue_price) {
        alert("发行价不能为空");
        return;
      } else if (!this.data.exercise_start_time) {
        alert("缺少行权时间");
        return;
      } else if (!this.data.exercise_end_time) {
        alert("缺少行权时间");
        return;
      } else if (!this.data.ipo_time) {
        alert("上市时间不能为空");
        return;
      } else if (!this.data.end_time) {
        alert("期权到期时间不能为空");
        return;
      } else if (!this.data.max_num_option) {
        alert("每人最多购买期权数量不能为空");
        return;
      } else if (!this.data.exercise_price) {
        alert("行权价格不能为空");
        return;
      } else if (!this.data.contract_first || !this.data.constarct_second) {
        alert("合约比较不能为空");
        return;
      } else if (time4 <= time3) {
        alert("期权到期时间必须大于上市时间");
        return;
      } else if (time5 <= time3 || time6 >= time4) {
        alert("交易期限必须大于上市时间小于期权到期时间");
        return;
      } else if (time <= time4) {
        alert("行权开始时间必须大于期权到期时间");
        return;
      }
      this.$axios({
        method: "post",
        url: "admin/pushproduct",
        params:{
          data: this.data,
          withdraw:withdraw
        }
      })
      .then(response => {
        console.log(response)
        if (withdraw == 2) {
            this.$router.push("/List");
          } else {
            this.$router.push("/Draft");
          }
      })
      .catch(function(error) {
        console.log(error);
      });
    },
    time(value) {
      if (!value) {
        return;
      }
      return moment(parseInt(value) * 1000).format("YYYY-MM-DD HH:mm");
    },
    showModel(){
      if (this.Model) {
        this.Model = false
      } else {
      this.Model = true
      }
    }
  }
};
</script>
