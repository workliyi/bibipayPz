<template>
  <div class="login">
    <div class="from">
      <h2 class="title">warrant</h2>
      <Form ref="formInline" :model="formInline" :rules="ruleInline" inline class="box-one">
        <FormItem prop="user">
          <Input type="text" v-model="formInline.user" placeholder="Username">
            <Icon type slot="prepend">账号</Icon>
          </Input>
        </FormItem>
        <FormItem prop="password">
          <Input type="password" v-model="formInline.password" placeholder="Password">
            <Icon type slot="prepend">密码</Icon>
          </Input>
        </FormItem>
        <FormItem>
          <Button type="primary" @click="handleSubmit('formInline')">Signin</Button>
        </FormItem>
      </Form>
    </div>
  </div>
</template>
<script>
import md5 from "js-md5";
import { setCookie } from "../api/api.js";
export default {
  data() {
    return {
      formInline: {
        user: "",
        password: ""
      },
      ruleInline: {
        user: [
          {
            required: true,
            message: "Please fill in the user name",
            trigger: "blur"
          }
        ],
        password: [
          {
            required: true,
            message: "Please fill in the password.",
            trigger: "blur"
          },
          {
            type: "string",
            min: 6,
            message: "The password length cannot be less than 6 bits",
            trigger: "blur"
          }
        ]
      }
    };
  },
  created() {
    let redirect = this.$route.query.redirect;
    if (redirect) {
      this.renderFunc()
    }
  },
  methods: {
    handleSubmit(name) {
      this.$refs[name].validate(valid => {
        this.$axios({
          method: "post",
          url: "admin/dologin",
          params: {
            username: this.formInline.user,
            password: this.formInline.password
          }
        })
          .then(response => {
            if (response.data.id) {
              let token = md5(
                md5(this.formInline.user) + md5(this.formInline.password)
              );
              setCookie("token", token, 1);
              this.$router.push("/");
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      });
    },
    renderFunc() {
      this.$Message.info({
        render: h => {
          return h("span", [
            "请登录 ",
          ]);
        }
      });
    }
  }
};
</script>
<style scoped>
.login {
  background: linear-gradient(135deg, #7262d1, #48d7e4);
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0px;
  left: 0px;
}
.from {
  width: 175px;
  height: 150px;
  position: absolute;
  top: 0px;
  left: 0px;
  bottom: 0px;
  right: 0px;
  margin: auto;
}
.title {
  text-align: center;
  font-size: 4em;
  font-family: cursive;
  margin-right: 20px;
}
.box-one {
  margin-top: 15px;
}
</style>
