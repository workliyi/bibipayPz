<template>
    <i-form :model="data" label-position="right" :label-width="150" :style="{width: '70%', margin:'auto'}">
        <Form-item label="名称：">
            <i-input :value.sync="data.title" placeholder="请输入名称"></i-input>
        </Form-item>
        <Form-item label="期权标的：">
            <i-input :value.sync="data.token_type" placeholder="请输入期权标的"></i-input>
        </Form-item>
        <Form-item label="合约类型：">
            <i-input :value.sync="data.contract_type" placeholder="请输入合约类型"></i-input>
        </Form-item>
        
        <Form-item label="计价单位：">
            <i-input :value.sync="data.unit_valuation" placeholder="请输入计价单位"></i-input>
        </Form-item>
        <Form-item label="*发行价：">
            <i-input :value.sync="data.issue_price" placeholder="请输入发行价"></i-input>
        </Form-item>
        <Form-item label="最小价格单位：">
            <i-input :value.sync="data.min_price" placeholder="请输入最小价格单位"></i-input>
        </Form-item>
        <Form-item label="*合约比例：">
        <Row>
            <i-col span="8">
                <Form-item prop="contract_first">
                    <i-input :value.sync="data.contract_first" placeholder="请输入币种"></i-input>
                </Form-item>
            </i-col>
            <i-col span="1" style="text-align: center">
            :
            </i-col>
            <i-col span="8">
                <Form-item prop="contract_second">
                    <i-input :value.sync="data.contract_second" placeholder="请输入合约"></i-input>
                </Form-item>
            </i-col>
        </Row>
        </Form-item>
        <Form-item label="文本描述：">
            <i-input :value.sync="data.description" type="textarea" placeholder="请输入文本描述" :rows="3" :autosize="{maxRows: 3,minRows: 3}" ></i-input>
        </Form-item>
        <Form-item label="交割方式：">
            <i-input :value.sync="data.delivery_type" placeholder="请输入交割方式"></i-input>
        </Form-item>
        <Form-item label="*行权价格：">
        <Row>
            <i-col span="8">
                <Form-item prop="contract_first">
            <i-input :value.sync="data.exercise_price" placeholder="请输入行权价格"></i-input>
                </Form-item>
            </i-col>
            <i-col span="1" style="text-align: center">
            RMB
            </i-col>
        </Row>
        </Form-item>
        <Form-item label="*上市时间：">
            <Row>
                <i-col span="6">
                    <Form-item prop="datetime">
                        <Date-picker type="datetime" placeholder="选择日期" :value.sync="data.ipo_time"></Date-picker>
                    </Form-item>
                </i-col>
            </Row>
        </Form-item>
        <Form-item label="*期权到期时间：">
            <Row>
                <i-col span="6">
                        <Form-item prop="datetime">
                            <Date-picker type="datetime" placeholder="选择日期" :value.sync="data.end_time"></Date-picker>
                        </Form-item>
                </i-col>
            </Row>
        </Form-item>
        <Form-item label="*交易期限：">
            <Row>
                <i-col span="6">
                    <Form-item prop="datetime">
                        <Date-picker type="datetime" placeholder="选择日期" :value.sync="data.par_start_time"></Date-picker>
                    </Form-item>
                </i-col>
                <i-col span="2" style="text-align: center">-</i-col>
                <i-col span="6">
                    <Form-item prop="datetime">
                        <Date-picker type="datetime" placeholder="选择日期" :value.sync="data.par_end_time"></Date-picker>
                    </Form-item>
                </i-col>
            </Row>
        </Form-item>
        <Form-item label="*行权时间：">
            <Row>
                <i-col span="6">
                    <Form-item prop="datetime">
                        <Date-picker type="datetime" placeholder="选择日期" :value.sync="data.exercise_start_time"></Date-picker>
                    </Form-item>
                </i-col>
                <i-col span="2" style="text-align: center">-</i-col>
                <i-col span="6">
                    <Form-item prop="datetime">
                        <Date-picker type="datetime" placeholder="选择日期" :value.sync="data.exercise_end_time"></Date-picker>
                    </Form-item>
                </i-col>
            </Row>
        </Form-item>
        <Form-item label="*每人最多购买期权数量：">
            <i-input :value.sync="data.max_num_option" placeholder="请输入每人最多购买期权数量"></i-input>
        </Form-item>
        <Form-item label="履行价格确认方式：">
            <i-input :value.sync="data.execute_type" placeholder="请输入确认方式"></i-input>
        </Form-item>
        <Form-item label="履行价格确认平台：">
            <i-input :value.sync="data.execute_platform" placeholder="请输入履行价格确认平台"></i-input>
        </Form-item>
        <Form-item label="履行价格权重比例：">
            <i-input :value.sync="data.execute_weight_ratio" placeholder="请输入履行价格权重比例"></i-input>
        </Form-item>
        <Form-item label="保证金标的：">
            <i-input :value.sync="data.bail_type" placeholder="请输入保证金标的"></i-input>
        </Form-item>
        <Form-item label="保证金率：">
            <i-input :value.sync="data.bail_ratio" placeholder="请输入保证金率"></i-input>
        </Form-item>
        <Form-item label="最小数量单位：">
            <i-input :value.sync="data.min_number" disabled ></i-input>
        </Form-item>
        <Form-item label="保证金：">
            <i-input :value.sync="data.bail" placeholder="请输入保证金"></i-input>
        </Form-item>
        <Form-item label="其他要求：">
            <i-input :value.sync="data.charge_unit" placeholder="请输入其他要求"></i-input>
        </Form-item>
        <Form-item label="保证金返还：">
            <i-input :value.sync="data.bail_return" placeholder="请输入保证金返还"></i-input>
        </Form-item>
        <Form-item>
            <i-button type="primary">发布</i-button>
            <i-button type="ghost" style="margin-left: 8px">存为草稿</i-button>
            <i-button type="ghost" style="margin-left: 8px">预览</i-button>
        </Form-item>
    </i-form>
</template>
<script>
    export default {
        data () {
            return {
                data: {
                    title:"",
                    token_type:'',
                    contract_type:'',
                    unit_valuation:'',
                    min_price:'',
                    contract_first:'',
                    contract_second:'',
                    description:'',
                    delivery_type:'',
                    exercise_price:'',
                    ipo_time:'',
                    end_time:'',
                    par_start_time:'',
                    par_end_time:'',
                    exercise_start_time:'',
                    exercise_end_time:'',
                    max_num_option:'',
                    execute_type:'',
                    execute_platform:'',
                    execute_weight_ratio:'',
                    bail_type:'',
                    bail_ratio:'',
                    min_number:'千张',
                    bail:'',
                    charge_unit:'',
                    bail_return:''
                }
            }
        }
    }
</script>
