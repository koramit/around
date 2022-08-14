import{_ as f}from"./logo.06377e43.js";import{r as b,f as g,n as y,g as V,m as k,o as p,c as h,a as s,t as d,b as t,u as l,q as w,i as v,w as T,d as U}from"./app.8a1dfeb0.js";import{_ as x}from"./FormCheckbox.b98b738c.js";import{_ as i}from"./FormInput.42f0f372.js";import{_ as B}from"./SpinnerButton.6b0f7629.js";const C={class:"flex flex-col justify-center items-center w-full min-h-screen my-6"},N=s("div",{class:"w-40 h-40 z-10 border-primary border-4 rounded-full"},[s("img",{src:f,alt:"around logo"})],-1),R={class:"mt-4 px-4 py-8 w-80 lg:w-96 bg-white rounded shadow -translate-y-20"},j={class:"block font-semibold text-xl text-accent-darker mt-12 text-center"},q=s("div",{class:"mt-4"},null,-1),E=["href"],z={__name:"RegisterForm",props:{layout:null,profile:{type:Object,required:!0},routes:{type:Object,required:!0}},setup(m){const a=m,u=b();g(()=>{y(()=>u.value.focus())});const e=V({login:a.profile.username,full_name:a.profile.name,org_id:a.profile.org_id,division:a.profile.org_division_name,position:a.profile.org_position_title,password_expires_in_days:a.profile.password_expires_in_days,remark:a.profile.remark,name:null,tel_no:null,pln:null,is_md:a.profile.is_md,agreement_accepted:!1,remember:!0}),_=k(()=>e.agreement_accepted&&e.name&&e.full_name&&e.tel_no),c=()=>{e.transform(r=>({...r,remember:r.remember?"on":""})).post(a.routes.registerStore,{onFinish:()=>e.processing=!1})};return(r,o)=>(p(),h("div",C,[N,s("div",R,[s("span",j,d(r.__("Register")),1),q,t(i,{class:"mt-2",name:"name",label:r.__("display name"),modelValue:l(e).name,"onUpdate:modelValue":o[0]||(o[0]=n=>l(e).name=n),error:l(e).errors.name,placeholder:r.__("nickname, alias anything you want"),ref_key:"name_input",ref:u},null,8,["label","modelValue","error","placeholder"]),t(i,{class:"mt-2",name:"full_name",label:r.__("full name"),placeholder:r.__("title first name last name"),modelValue:l(e).full_name,"onUpdate:modelValue":o[1]||(o[1]=n=>l(e).full_name=n),error:l(e).errors.full_name,readonly:m.profile.org_id!==void 0},null,8,["label","placeholder","modelValue","error","readonly"]),t(i,{class:"mt-2",type:"tel",name:"tel_no",label:r.__("tel no"),modelValue:l(e).tel_no,"onUpdate:modelValue":o[2]||(o[2]=n=>l(e).tel_no=n),error:l(e).errors.tel_no,placeholder:r.__("for emergency case only")},null,8,["label","modelValue","error","placeholder"]),m.profile.is_md?(p(),w(i,{key:0,class:"mt-2",type:"tel",name:"pln",label:r.__("license number"),modelValue:l(e).pln,"onUpdate:modelValue":o[3]||(o[3]=n=>l(e).pln=n),error:l(e).errors.pln,placeholder:"\u0E40\u0E25\u0E02 \u0E27."},null,8,["label","modelValue","error"])):v("",!0),t(x,{class:"mt-2",modelValue:l(e).agreement_accepted,"onUpdate:modelValue":o[4]||(o[4]=n=>l(e).agreement_accepted=n),label:r.__("Accept Terms and Policies"),toggler:!0},null,8,["modelValue","label"]),s("a",{href:m.routes.terms,class:"mt-2 block text-accent-darker underline",target:"_blank"},d(r.__("Terms and Policies")),9,E),t(B,{spin:l(e).processing,class:"btn-accent w-full mt-4",onClick:c,disabled:!l(_)},{default:T(()=>[U(d(r.__("REGISTER")),1)]),_:1},8,["spin","disabled"])])]))}};export{z as default};
