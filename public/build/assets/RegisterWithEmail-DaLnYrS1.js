import{_ as p}from"./logo-DsX578f4.js";import{_}from"./SpinnerButton-DJMJUfXj.js";import{_ as n}from"./FormInput-CXQbVQFs.js";import{_ as f}from"./FormCheckbox-C2kLr4xg.js";import{T as c,i as b,r as w,o as V,n as g,c as y,b as t,t as m,d as s,u as l,e as k,h as T,f as U}from"./app-CsDEoyKE.js";const v={class:"flex flex-col justify-center items-center w-full min-h-screen my-6"},h={class:"mt-4 px-4 py-8 w-80 lg:w-96 bg-white rounded shadow -translate-y-20"},E={class:"block font-semibold text-xl text-accent-darker mt-12 text-center"},R={href:"/terms-and-policies",class:"mt-2 block text-accent-darker underline",target:"_blank"},j={__name:"RegisterWithEmail",setup($){const e=c({email:null,password:null,password_confirmation:null,full_name:null,name:null,tel_no:null,agreement_accepted:!1,remember:!0}),i=b(()=>e.agreement_accepted&&e.email&&e.password&&e.password_confirmation&&e.name&&e.full_name&&e.tel_no),u=()=>{e.transform(r=>({...r,remember:r.remember?"on":""})).post("/register-with-email",{onFinish:()=>e.processing=!1})},d=w();return V(()=>{g(()=>d.value.focus())}),(r,a)=>(T(),y("div",v,[a[8]||(a[8]=t("div",{class:"w-40 h-40 z-10 border-primary border-4 rounded-full"},[t("img",{src:p,alt:"around logo"})],-1)),t("div",h,[t("span",E,m(r.__("Register")),1),a[7]||(a[7]=t("div",{class:"mt-4"},null,-1)),s(n,{class:"mt-2",name:"email",label:"email",modelValue:l(e).email,"onUpdate:modelValue":a[0]||(a[0]=o=>l(e).email=o),error:l(e).errors.email,ref_key:"email_input",ref:d},null,8,["modelValue","error"]),s(n,{class:"mt-2",type:"password",name:"password",label:r.__("password"),modelValue:l(e).password,"onUpdate:modelValue":a[1]||(a[1]=o=>l(e).password=o),error:l(e).errors.password},null,8,["label","modelValue","error"]),s(n,{class:"mt-2",type:"password",name:"password_confirmation",label:r.__("confirm password"),modelValue:l(e).password_confirmation,"onUpdate:modelValue":a[2]||(a[2]=o=>l(e).password_confirmation=o),error:l(e).errors.password_confirmation},null,8,["label","modelValue","error"]),s(n,{class:"mt-2",name:"name",label:r.__("display name"),modelValue:l(e).name,"onUpdate:modelValue":a[3]||(a[3]=o=>l(e).name=o),error:l(e).errors.name,placeholder:r.__("nickname, alias anything you want")},null,8,["label","modelValue","error","placeholder"]),s(n,{class:"mt-2",name:"full_name",label:`${r.__("full name")} (in Thai)`,placeholder:r.__("title first name last name"),modelValue:l(e).full_name,"onUpdate:modelValue":a[4]||(a[4]=o=>l(e).full_name=o),error:l(e).errors.full_name},null,8,["label","placeholder","modelValue","error"]),s(n,{class:"mt-2",type:"tel",name:"tel_no",label:r.__("tel no"),modelValue:l(e).tel_no,"onUpdate:modelValue":a[5]||(a[5]=o=>l(e).tel_no=o),error:l(e).errors.tel_no,placeholder:r.__("for emergency case only")},null,8,["label","modelValue","error","placeholder"]),s(f,{class:"mt-2",modelValue:l(e).agreement_accepted,"onUpdate:modelValue":a[6]||(a[6]=o=>l(e).agreement_accepted=o),label:r.__("Accept Terms and Policies"),toggler:!0},null,8,["modelValue","label"]),t("a",R,m(r.__("Terms and Policies")),1),s(_,{spin:l(e).processing,class:"btn-accent w-full mt-4",onClick:u,disabled:!i.value},{default:k(()=>[U(m(r.__("REGISTER")),1)]),_:1},8,["spin","disabled"])])]))}};export{j as default};
