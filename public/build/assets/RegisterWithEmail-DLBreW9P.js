import{_ as p}from"./logo-DsX578f4.js";import{_}from"./SpinnerButton-DXbXdbzm.js";import{_ as n}from"./FormInput-FD2rBO-Y.js";import{_ as c}from"./FormCheckbox-BnRXqYhA.js";import{T as f,j as b,r as w,o as V,n as g,b as y,c as k,d as t,t as m,e as s,u as l,f as h,g as T}from"./app-CC1FPugz.js";const U={class:"flex flex-col justify-center items-center w-full min-h-screen my-6"},v=t("div",{class:"w-40 h-40 z-10 border-primary border-4 rounded-full"},[t("img",{src:p,alt:"around logo"})],-1),E={class:"mt-4 px-4 py-8 w-80 lg:w-96 bg-white rounded shadow -translate-y-20"},R={class:"block font-semibold text-xl text-accent-darker mt-12 text-center"},$=t("div",{class:"mt-4"},null,-1),B={href:"/terms-and-policies",class:"mt-2 block text-accent-darker underline",target:"_blank"},z={__name:"RegisterWithEmail",setup(C){const e=f({email:null,password:null,password_confirmation:null,full_name:null,name:null,tel_no:null,agreement_accepted:!1,remember:!0}),i=b(()=>e.agreement_accepted&&e.email&&e.password&&e.password_confirmation&&e.name&&e.full_name&&e.tel_no),u=()=>{e.transform(a=>({...a,remember:a.remember?"on":""})).post("/register-with-email",{onFinish:()=>e.processing=!1})},d=w();return V(()=>{g(()=>d.value.focus())}),(a,r)=>(y(),k("div",U,[v,t("div",E,[t("span",R,m(a.__("Register")),1),$,s(n,{class:"mt-2",name:"email",label:"email",modelValue:l(e).email,"onUpdate:modelValue":r[0]||(r[0]=o=>l(e).email=o),error:l(e).errors.email,ref_key:"email_input",ref:d},null,8,["modelValue","error"]),s(n,{class:"mt-2",type:"password",name:"password",label:a.__("password"),modelValue:l(e).password,"onUpdate:modelValue":r[1]||(r[1]=o=>l(e).password=o),error:l(e).errors.password},null,8,["label","modelValue","error"]),s(n,{class:"mt-2",type:"password",name:"password_confirmation",label:a.__("confirm password"),modelValue:l(e).password_confirmation,"onUpdate:modelValue":r[2]||(r[2]=o=>l(e).password_confirmation=o),error:l(e).errors.password_confirmation},null,8,["label","modelValue","error"]),s(n,{class:"mt-2",name:"name",label:a.__("display name"),modelValue:l(e).name,"onUpdate:modelValue":r[3]||(r[3]=o=>l(e).name=o),error:l(e).errors.name,placeholder:a.__("nickname, alias anything you want")},null,8,["label","modelValue","error","placeholder"]),s(n,{class:"mt-2",name:"full_name",label:`${a.__("full name")} (in Thai)`,placeholder:a.__("title first name last name"),modelValue:l(e).full_name,"onUpdate:modelValue":r[4]||(r[4]=o=>l(e).full_name=o),error:l(e).errors.full_name},null,8,["label","placeholder","modelValue","error"]),s(n,{class:"mt-2",type:"tel",name:"tel_no",label:a.__("tel no"),modelValue:l(e).tel_no,"onUpdate:modelValue":r[5]||(r[5]=o=>l(e).tel_no=o),error:l(e).errors.tel_no,placeholder:a.__("for emergency case only")},null,8,["label","modelValue","error","placeholder"]),s(c,{class:"mt-2",modelValue:l(e).agreement_accepted,"onUpdate:modelValue":r[6]||(r[6]=o=>l(e).agreement_accepted=o),label:a.__("Accept Terms and Policies"),toggler:!0},null,8,["modelValue","label"]),t("a",B,m(a.__("Terms and Policies")),1),s(_,{spin:l(e).processing,class:"btn-accent w-full mt-4",onClick:u,disabled:!i.value},{default:h(()=>[T(m(a.__("REGISTER")),1)]),_:1},8,["spin","disabled"])])]))}};export{z as default};